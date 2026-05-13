<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BlockedEmailModel;
use App\Models\OtpTokenModel;
use App\Models\ApplicationSettingModel;
use App\Models\LoginWhitelistEmailModel;

class AuthController extends BaseController
{
    private const REGISTRATION_SESSION_KEY = 'pending_registration';
    private const REGISTRATION_OTP_TTL = 600;
    private const SETTING_LOGIN_WHITELIST_ENABLED = 'login_whitelist_enabled';

    protected $userModel;
    protected $blockedEmailModel;
    protected $otpTokenModel;
    protected $applicationSettingModel;
    protected $loginWhitelistEmailModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userModel                  = new UserModel();
        $this->blockedEmailModel          = new BlockedEmailModel();
        $this->otpTokenModel              = new OtpTokenModel();
        $this->applicationSettingModel  = new ApplicationSettingModel();
        $this->loginWhitelistEmailModel   = new LoginWhitelistEmailModel();
    }


    /**
     * Show change password form after OTP verification
     */
    public function changePassword()
    {
        // Only allow if OTP was verified
        if (!session()->get('otp_verified_user')) {
            return redirect()->to(base_url('auth/otp'))->with('error', 'Unauthorized or session expired. Please verify OTP again.');
        }
        return view('auth/change_password');
    }

    /**
     * Process password change after OTP verification
     */
    public function changePasswordProcess()
    {
        $isAjax = $this->request->isAJAX();
        $userId = session()->get('otp_verified_user');
        if (!$userId) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Session expired. Please request OTP again.'
                ]);
            }
            return redirect()->to(base_url('auth/otp'))->with('error', 'Unauthorized or session expired. Please verify OTP again.');
        }

        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!$newPassword || strlen($newPassword) < 8) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Password must be at least 8 characters.'
                ]);
            }
            return redirect()->back()->with('error', 'Password must be at least 8 characters.');
        }

        // Validate password strength
        $passwordError = $this->validatePasswordStrength($newPassword);
        if ($passwordError) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $passwordError
                ]);
            }
            return redirect()->back()->with('error', $passwordError);
        }

        if ($newPassword !== $confirmPassword) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Passwords do not match.'
                ]);
            }
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        $this->userModel->update($userId, ['password' => $newPassword]);
        session()->remove('otp_verified_user');

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Password changed successfully!',
                'redirect' => base_url('auth/login')
            ]);
        }

        return redirect()->to(base_url('auth/login'))->with('success', 'Password changed successfully. You may now log in.');
    }

    /**
     * Show OTP form
     */
        public function otp()
        {
            return view('auth/otp');
        }

        /**
         * Send OTP to email
         */
        public function sendOtp()
        {
            $isAjax = $this->request->isAJAX();
            $email = $this->request->getPost('email');
            $user = $this->userModel->getUserByEmail($email);
            if (!$user) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Email not found.'
                    ]);
                }
                return redirect()->back()->with('error', 'Email not found.');
            }

            // Generate OTP
            $otp = random_int(100000, 999999);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            // Save OTP to DB
            $this->otpTokenModel->where('userID', $user['id'])->delete(); // Remove old OTPs
            $this->otpTokenModel->insert([
                'userID' => $user['id'],
                'token' => $otp,
                'expires_at' => $expiresAt,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Send email using PHPMailerService
            $result = \App\Libraries\PHPMailerService::sendOTP($email, $otp);
            if ($result === true) {
                session()->set('otp_email', $email);
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'OTP sent to your email.',
                        'redirect' => base_url('auth/otp')
                    ]);
                }
                session()->setFlashdata('otp_email_sent', true);
                return redirect()->back()->with('success', 'OTP sent to your email.');
            } else {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Failed to send OTP. ' . $result
                    ]);
                }
                return redirect()->back()->with('error', 'Failed to send OTP. ' . $result);
            }
        }

    /**
     * Verify OTP
     */
    public function verifyOtp()
    {
        $isAjax = $this->request->isAJAX();
        $otp = trim((string) $this->request->getPost('otp'));
        $email = session()->get('otp_email');

        if (!$email) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Session expired. Please request OTP again.'
                ]);
            }
            log_message('error', 'OTP verification failed: session missing.');
            return redirect()->back()->with('error', 'Session expired or missing. Please request OTP again.');
        }

        $user = $this->userModel->getUserByEmail($email);
        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Email not found.'
                ]);
            }
            log_message('error', 'OTP verification failed: email not found.');
            return redirect()->back()->with('error', 'Email not found. Please check your email address.');
        }

        $otpRecord = $this->otpTokenModel
            ->where('userID', $user['id'])
            ->where('token', $otp)
            ->first();

        if (!$otpRecord) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid verification code. Please try again.'
                ]);
            }
            log_message('error', 'OTP verification failed: OTP incorrect for user ' . $user['id']);
            return redirect()->back()->with('error', 'Incorrect OTP. Please check the code sent to your email.');
        }

        if (strtotime($otpRecord['expires_at']) < time()) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'OTP expired. Please request a new code.'
                ]);
            }
            log_message('error', 'OTP verification failed: OTP expired for user ' . $user['id']);
            return redirect()->back()->with('error', 'OTP expired. Please request a new code.');
        }

        // OTP valid
        session()->set('otp_verified_user', $user['id']);
        session()->remove('otp_email');

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'OTP verified successfully!',
                'redirect' => base_url('auth/change_password')
            ]);
        }

        return redirect()->to(base_url('auth/change_password'))->with('success', 'OTP verified. You may now reset your password.');
    }
    /**
     * Show login form
     */
    public function login()
    {
        // Redirect if already logged in
        if (session()->has('logged_in') && session()->get('logged_in') && session()->get('user_id')) {
            return redirect()->to($this->getUserHomePage(session()->get('user_role')));
        }

        // Clean up inconsistent session state so login works normally.
        if (session()->has('logged_in') && session()->get('logged_in') && !session()->get('user_id')) {
            session()->destroy();
        }
        
        return view('auth/login', [
            'initial_lockout_seconds' => (int) (session()->getFlashdata('lockout_seconds') ?: 0),
        ]);
    }
    
    /**
     * Show disabled account page
     */
    public function disabled()
    {
        return view('auth/disabled');
    }
    
    /**
     * Show blocked registration page
     */
    public function blockedRegistration()
    {
        return view('auth/blocked-registration');
    }
    
    /**
     * Process login with security tracking
     * - 5 failed attempts = 5 minute cooldown
     * - 10 failed attempts = security alert email sent
     * Supports both regular POST and AJAX (returns JSON)
     */
    public function loginProcess()
    {
        $isAjax = $this->request->isAJAX();
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please fill in all required fields.',
                    'errors' => $errors,
                    'csrf_token' => csrf_hash()
                ]);
            }
            return redirect()->back()
                ->withInput()
                ->with('errors', $errors);
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Find user by email
        $user = $this->userModel->getUserByEmail($email);
        
        if (!$user) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid email or password.',
                    'csrf_token' => csrf_hash()
                ]);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }

        // Check if email is blocked and disable account if so
        if ($this->blockedEmailModel->isBlocked($email)) {
            if ($user['status'] === 'active') {
                $this->userModel->update($user['id'], ['status' => 'disabled']);
            }
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'This account has been blocked.',
                    'csrf_token' => csrf_hash()
                ]);
            }
            return redirect()->to('/auth/disabled');
        }
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Your account is disabled.',
                    'csrf_token' => csrf_hash()
                ]);
            }
            return redirect()->to('/auth/disabled');
        }

        // Login whitelist: only admins and explicitly whitelisted emails may sign in
        if ($this->isLoginWhitelistEnabled()) {
            if (($user['role'] ?? '') !== 'admin' && ! $this->loginWhitelistEmailModel->isWhitelisted($email)) {
                $wlMsg = 'Login is temporarily restricted to approved accounts. Please contact an administrator.';
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status'   => 'error',
                        'message'  => $wlMsg,
                        'csrf_token' => csrf_hash(),
                    ]);
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', $wlMsg);
            }
        }

        // Clear any expired lockout (resets recent counter, keeps total)
        $this->userModel->clearExpiredLockout($user['id']);

        // Check if account is currently locked out
        if ($this->userModel->isLockedOut($user['id'])) {
            $lockoutUntil = $this->userModel->getLockoutUntil($user['id']);
            $remainingSeconds = max(0, strtotime($lockoutUntil) - time());
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'locked',
                    'message' => 'Account temporarily locked.',
                    'lockout_remaining' => $remainingSeconds,
                    'csrf_token' => csrf_hash()
                ]);
            }
            session()->setFlashdata('lockout_seconds', $remainingSeconds);
            $remainingMinutes = ceil($remainingSeconds / 60);
            return redirect()->back()
                ->withInput()
                ->with('error', "Too many failed attempts. Please try again in {$remainingMinutes} minute(s).");
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            $this->userModel->incrementFailedAttempt($user['id']);
            
            $failedAttempts = $this->userModel->getFailedAttempts($user['id']);
            $totalAttempts = $this->userModel->getTotalFailedAttempts($user['id']);
            
            $sentEmail = false;
            
            if ($totalAttempts >= 10 && $user['security_email_sent'] == 0) {
                $result = \App\Libraries\PHPMailerService::sendSecurityAlert(
                    $user['email'],
                    $user['name'],
                    $totalAttempts
                );
                if ($result === true) {
                    $this->userModel->markSecurityEmailSent($user['id']);
                    $sentEmail = true;
                }
            }
            
            $lockoutRemaining = 0;
            if ($failedAttempts >= 5 && $failedAttempts < 10) {
                $this->userModel->setLockout($user['id'], 5);
                // Calculate accurate remaining time after lockout is set
                $lockoutUntil = $this->userModel->getLockoutUntil($user['id']);
                $lockoutRemaining = $lockoutUntil ? max(0, strtotime($lockoutUntil) - time()) : 0;
            }
            
            $errorMsg = 'Invalid email or password.';
            if ($failedAttempts >= 5) {
                $errorMsg .= ' Account locked for 5 minutes.';
            } else {
                $remaining = 5 - $failedAttempts;
                $errorMsg .= " You have {$remaining} attempt(s) remaining before account lock.";
            }
            if ($sentEmail) {
                $errorMsg .= ' Security alert sent to your email.';
            }
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => $failedAttempts >= 5 ? 'locked' : 'error',
                    'message' => $errorMsg,
                    'lockout_remaining' => ($failedAttempts >= 5) ? $lockoutRemaining : 0,
                    'attempts' => $failedAttempts,
                    'attempts_remaining' => ($failedAttempts >= 5) ? 0 : max(0, 5 - $failedAttempts),
                    'csrf_token' => csrf_hash()
                ]);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMsg);
        }
        
        // Password correct
        $this->userModel->resetFailedAttempts($user['id']);
        
        session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
            'logged_in' => true
        ]);

        $redirectUrl = ($user['role'] === 'admin') ? '/admin/dashboard' : (session()->get('redirect_url') ?? $this->getUserHomePage($user['role']));
        session()->remove('redirect_url');

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Login successful!',
                'redirect' => $redirectUrl
            ]);
        }

        session()->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');
        return redirect()->to($redirectUrl);
    }
     
    /**
     * Show farmer registration form
     */
    public function registerFarmer()
    {
        return view('auth/register_farmer');
    }
    
    /**
     * Process farmer registration
     */
    public function registerFarmerProcess()
    {
        return $this->registerBuyerProcess();
    }
    
    /**
     * Show buyer registration form
     */
    public function registerBuyer()
    {
        return view('auth/register_buyer');
    }
    
    /**
     * Process buyer registration
     */
    public function registerBuyerProcess()
    {
        $isAjax = $this->request->isAJAX();
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'required',
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'min_length' => 'Password must be at least 8 characters long.'
                ]
            ],
            'confirm_password' => 'required|matches[password]',
            'location' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please fix the errors below.',
                    'errors' => $errors
                ]);
            }
            return redirect()->back()
                ->withInput()
                ->with('errors', $errors);
        }

        // Validate password strength
        $password = $this->request->getPost('password');
        $passwordError = $this->validatePasswordStrength($password);
        if ($passwordError) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => $passwordError,
                    'errors'  => ['password' => $passwordError],
                ]);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', $passwordError);
        }

        // Check if email is blocked
        $email = $this->request->getPost('email');
        if ($this->blockedEmailModel->isBlocked($email)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Registration from this email is not allowed.'
                ]);
            }
            return redirect()->to('/auth/blocked-registration');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $email,
            'phone' => $this->request->getPost('phone'),
            'password' => $password,
            'role' => 'buyer',
            'location' => $this->request->getPost('location'),
            'cooperative' => $this->request->getPost('cooperative'),
            'status' => 'active'
        ];

        $otp = random_int(100000, 999999);
        $registrationPayload = [
            'data' => $data,
            'otp' => (string) $otp,
            'expires_at' => time() + self::REGISTRATION_OTP_TTL,
            'sent_at' => time(),
        ];

        session()->set(self::REGISTRATION_SESSION_KEY, $registrationPayload);

        $sendResult = \App\Libraries\PHPMailerService::sendOTP($data['email'], $otp);
        if ($sendResult !== true) {
            session()->remove(self::REGISTRATION_SESSION_KEY);
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to send verification email. ' . $sendResult
                ]);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to send verification code. ' . $sendResult);
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Verification code sent. Please check your email.',
                'redirect' => base_url('auth/register-verify')
            ]);
        }

        return redirect()->to('/auth/register-verify')
            ->with('success', 'Verification code sent. Please check your email before creating your account.');
    }

    /**
     * Show registration OTP verification page.
     */
    public function registerVerify()
    {
        $pending = session()->get(self::REGISTRATION_SESSION_KEY);
        if (!$pending || !isset($pending['data']['email'])) {
            return redirect()->to('/auth/register-buyer')
                ->with('error', 'No pending registration found. Please register first.');
        }

        if (time() > (int) ($pending['expires_at'] ?? 0)) {
            session()->remove(self::REGISTRATION_SESSION_KEY);

            return redirect()->to('/auth/register-buyer')
                ->with('error', 'Verification code expired. Please register again.');
        }

        return view('auth/register_verify', [
            'email' => $pending['data']['email'],
        ]);
    }

    /**
     * Verify registration OTP and create account.
     */
    public function registerVerifyProcess()
    {
        $isAjax = $this->request->isAJAX();
        $pending = session()->get(self::REGISTRATION_SESSION_KEY);
        if (!$pending || !isset($pending['data'], $pending['otp'])) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No pending registration found. Please register first.'
                ]);
            }
            return redirect()->to('/auth/register-buyer')
                ->with('error', 'No pending registration found. Please register first.');
        }

        if (time() > (int) ($pending['expires_at'] ?? 0)) {
            session()->remove(self::REGISTRATION_SESSION_KEY);
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Verification code expired. Please register again.'
                ]);
            }
            return redirect()->to('/auth/register-buyer')
                ->with('error', 'Verification code expired. Please register again.');
        }

        $inputOtp = trim((string) $this->request->getPost('otp'));
        if ($inputOtp === '' || $inputOtp !== (string) $pending['otp']) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid verification code. Please try again.'
                ]);
            }
            return redirect()->back()->with('error', 'Invalid verification code. Please try again.');
        }

        $data = $pending['data'];
        if (!$this->userModel->save($data)) {
            session()->remove(self::REGISTRATION_SESSION_KEY);
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Registration failed. Please try again.'
                ]);
            }
            return redirect()->to('/auth/register-buyer')
                ->with('error', 'Registration failed. Please try again.');
        }

        $userId = $this->userModel->insertID();
        $user = $this->userModel->find($userId);
        session()->remove(self::REGISTRATION_SESSION_KEY);

        if ($user) {
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'logged_in' => true,
            ]);
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Registration successful! Welcome to Farmart.',
                'redirect' => base_url('/marketplace')
            ]);
        }

        return redirect()->to('/marketplace')
            ->with('success', 'Registration successful! Welcome to Farmart.');
    }

    /**
     * Resend registration OTP email.
     */
    public function resendRegistrationOtp()
    {
        $isAjax = $this->request->isAJAX();
        $pending = session()->get(self::REGISTRATION_SESSION_KEY);
        if (!$pending || !isset($pending['data']['email'])) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No pending registration found.'
                ]);
            }
            return redirect()->to('/auth/register-buyer')
                ->with('error', 'No pending registration found. Please register first.');
        }

        $otp = random_int(100000, 999999);
        $pending['otp'] = (string) $otp;
        $pending['expires_at'] = time() + self::REGISTRATION_OTP_TTL;
        $pending['sent_at'] = time();
        session()->set(self::REGISTRATION_SESSION_KEY, $pending);

        $sendResult = \App\Libraries\PHPMailerService::sendOTP($pending['data']['email'], $otp);
        if ($sendResult !== true) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Unable to resend verification code. ' . $sendResult
                ]);
            }
            return redirect()->back()->with('error', 'Unable to resend verification code. ' . $sendResult);
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'A new verification code has been sent to your email.'
            ]);
        }

        return redirect()->back()->with('success', 'A new verification code has been sent to your email.');
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')
            ->with('success', 'You have been logged out successfully.');
    }
    
    private function isLoginWhitelistEnabled(): bool
    {
        $db = \Config\Database::connect();
        if (! $db->tableExists('application_settings')) {
            return false;
        }

        return $this->applicationSettingModel->getValue(self::SETTING_LOGIN_WHITELIST_ENABLED, '0') === '1';
    }

    /**
     * Get user homepage based on role
     */
    private function getUserHomePage($role)
    {
        switch ($role) {
            case 'buyer':
            case 'farmer':
            case 'user':
                return '/marketplace';
            case 'admin':
                return '/admin/dashboard';
            default:
                return '/';
        }
    }

    /**
     * Validate password strength (min 8 chars, 1 uppercase, 1 number, 1 special character)
     * Returns error message if invalid, null if valid
     */
    private function validatePasswordStrength(string $password): ?string
    {
        if (strlen($password) < 8) {
            return 'Password must be at least 8 characters long.';
        }
        if (! preg_match('/[A-Z]/', $password)) {
            return 'Password must contain at least one uppercase letter (A-Z).';
        }
        if (! preg_match('/\d/', $password)) {
            return 'Password must contain at least one number (0-9).';
        }
        if (! preg_match('/[^A-Za-z0-9]/', $password)) {
            return 'Password must contain at least one special character (for example !@#$%^&*).';
        }

        return null;
    }
}
