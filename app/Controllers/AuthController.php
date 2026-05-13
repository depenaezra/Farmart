<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BlockedEmailModel;
use App\Models\OtpTokenModel;
use App\Models\SystemSettingModel;
use App\Models\WhitelistedEmailModel;

class AuthController extends BaseController
{
    private const REGISTRATION_SESSION_KEY = 'pending_registration';
    private const REGISTRATION_OTP_TTL = 600;

    protected $userModel;
    protected $blockedEmailModel;
    protected $otpTokenModel;
    protected $systemSettingModel;
    protected $whitelistedEmailModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userModel = new UserModel();
        $this->blockedEmailModel = new BlockedEmailModel();
        $this->otpTokenModel = new OtpTokenModel();
        $this->systemSettingModel = new SystemSettingModel();
        $this->whitelistedEmailModel = new WhitelistedEmailModel();
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
        $userId = session()->get('otp_verified_user');
        if (!$userId) {
            return redirect()->to(base_url('auth/otp'))->with('error', 'Unauthorized or session expired. Please verify OTP again.');
        }

        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        $newPassword = (string) $newPassword;
        $confirmPassword = (string) $confirmPassword;

        $length = strlen($newPassword);
        if ($newPassword === '' || $length < 8 || $length > 15) {
            return redirect()->back()->with('error', 'Password must be 8 to 15 characters long.');
        }

        $hasUpper = preg_match('/[A-Z]/', $newPassword) === 1;
        $hasNumber = preg_match('/\d/', $newPassword) === 1;
        $hasSpecial = preg_match('/[^A-Za-z0-9]/', $newPassword) === 1;
        if (!$hasUpper || !$hasNumber || !$hasSpecial) {
            return redirect()->back()->with('error', 'Password must include at least 1 uppercase letter, 1 number, and 1 special character.');
        }
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        // Update password (UserModel will hash automatically)
        $this->userModel->update($userId, ['password' => $newPassword]);

        // Clear OTP session
        session()->remove('otp_verified_user');

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
            $email = $this->request->getPost('email');
            $user = $this->userModel->getUserByEmail($email);
            if (!$user) {
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
                session()->setFlashdata('otp_email_sent', true);
                return redirect()->back()->with('success', 'OTP sent to your email.');
            } else {
                return redirect()->back()->with('error', 'Failed to send OTP. ' . $result);
            }
        }

        /**
         * Verify OTP
         */
        public function verifyOtp()
        {
            $otp = $this->request->getPost('otp');
            $email = session()->get('otp_email');
            if (!$email) {
                log_message('error', 'OTP verification failed: session missing.');
                return redirect()->back()->with('error', 'Session expired or missing. Please request OTP again.');
            }
            $user = $this->userModel->getUserByEmail($email);
            if (!$user) {
                log_message('error', 'OTP verification failed: email not found.');
                return redirect()->back()->with('error', 'Email not found. Please check your email address.');
            }
            $otpRecord = $this->otpTokenModel
                ->where('userID', $user['id'])
                ->where('token', $otp)
                ->first();
            if (!$otpRecord) {
                log_message('error', 'OTP verification failed: OTP incorrect for user ' . $user['id']);
                return redirect()->back()->with('error', 'Incorrect OTP. Please check the code sent to your email.');
            }
            if (strtotime($otpRecord['expires_at']) < time()) {
                log_message('error', 'OTP verification failed: OTP expired for user ' . $user['id']);
                return redirect()->back()->with('error', 'OTP expired. Please request a new code.');
            }
            // OTP valid, allow password reset (redirect to change password)
            session()->set('otp_verified_user', $user['id']);
            session()->remove('otp_email');
            session()->remove('otp_email_sent');
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

         // If OTP verification pending, redirect to OTP page
         if (session()->has('otp_login_user_id') && session()->get('otp_login_user_id')) {
             return redirect()->to('/auth/login-verify');
         }

         // Clean up inconsistent session state so login works normally.
         if (session()->has('logged_in') && session()->get('logged_in') && !session()->get('user_id')) {
             session()->destroy();
         }
         
         return view('auth/login');
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
      * Process login
      */
     public function loginProcess()
     {
         $validation = \Config\Services::validation();
         
         $rules = [
             'email' => 'required|valid_email',
             'password' => 'required'
         ];
         
if (!$this->validate($rules)) {
              return redirect()->back()
                  ->withInput()
                  ->with('errors', $validation->getErrors());
          }
          
          $email = $this->request->getPost('email');
          $password = $this->request->getPost('password');

          // Email whitelist gate (if enabled).
          if ($this->systemSettingModel->isEnabled('email_whitelist_enabled', false)) {
              if (!$this->whitelistedEmailModel->isWhitelisted($email)) {
                  return redirect()->back()
                      ->withInput()
                      ->with('error', 'Access is restricted. Your email is not allowed to sign in.');
              }
          }
          
          // Find user by email
          $user = $this->userModel->getUserByEmail($email);
          
          if (!$user) {
              return redirect()->back()
                  ->withInput()
                  ->with('error', 'Invalid email or password');
          }

          // Check if email is blocked and disable account if so
          if ($this->blockedEmailModel->isBlocked($email)) {
              if ($user['status'] === 'active') {
                  $this->userModel->update($user['id'], ['status' => 'disabled']);
              }
              return redirect()->to('/auth/disabled');
          }
            
          // Check if user is active
          if ($user['status'] !== 'active') {
              return redirect()->to('/auth/disabled');
          }

          // Check login suspension and auto-clear if expired
          $suspendedUntil = $user['login_suspended_until'] ?? null;
          if ($suspendedUntil && strtotime($suspendedUntil) > time()) {
              return redirect()->back()
                  ->withInput()
                  ->with('error', 'Your account is temporarily suspended. Please try again later.');
          }
          // Auto-clear expired suspension
          if ($suspendedUntil && strtotime($suspendedUntil) <= time()) {
              $this->userModel->update($user['id'], [
                  'failed_login_attempts' => 0,
                  'login_suspended_until' => null
              ]);
              $user['failed_login_attempts'] = 0;
          }

          // Verify password
          if (!password_verify($password, $user['password'])) {
              // Track failed login attempt
              $failedAttempts = (int) ($user['failed_login_attempts'] ?? 0) + 1;
              $cooldownMinutes = 5;
              
              if ($failedAttempts >= 5) {
                  $suspendedUntil = date('Y-m-d H:i:s', strtotime("+$cooldownMinutes minutes"));
                  $this->userModel->update($user['id'], [
                      'failed_login_attempts' => $failedAttempts,
                      'login_suspended_until' => $suspendedUntil
                  ]);
                  return redirect()->back()
                      ->withInput()
                      ->with('error', 'Too many failed attempts. Please try again after ' . $cooldownMinutes . ' minutes.');
              } else {
                  $this->userModel->update($user['id'], ['failed_login_attempts' => $failedAttempts]);
                  $remaining = 5 - $failedAttempts;
                  return redirect()->back()
                      ->withInput()
                      ->with('error', 'Invalid email or password. ' . $remaining . ' attempts remaining.');
              }
          }

          // Reset failed login attempts on successful password verification
          if (!empty($user['failed_login_attempts']) || !empty($user['login_suspended_until'])) {
              $this->userModel->update($user['id'], [
                  'failed_login_attempts' => 0,
                  'login_suspended_until' => null
              ]);
          }

          // Generate OTP for 2FA verification
           $otp = random_int(100000, 999999);
           $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

           // Save OTP to DB (clear any existing OTPs for this user first)
           $this->otpTokenModel->where('userID', $user['id'])->delete();
           $this->otpTokenModel->insert([
               'userID' => $user['id'],
               'token' => $otp,
               'expires_at' => $expiresAt,
               'created_at' => date('Y-m-d H:i:s')
           ]);

           // Send OTP to email
           $sendResult = \App\Libraries\PHPMailerService::sendOTP($email, $otp);
         if ($sendResult !== true) {
             return redirect()->back()
                 ->withInput()
                 ->with('error', 'Failed to send verification code. ' . $sendResult);
         }

         // Store user in session for OTP verification (don't set full logged_in yet)
         session()->set([
             'otp_login_user_id' => $user['id'],
             'otp_login_email' => $user['email'],
         ]);

          // Redirect to OTP verification page
          return redirect()->to('/auth/login-verify')
              ->with('success', 'A verification code has been sent to your email.');
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
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'required',
            'password' => 'required|min_length[8]|max_length[15]|regex_match[/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/]',
            'confirm_password' => 'required|matches[password]',
            'location' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Check if email is blocked
        $email = $this->request->getPost('email');
        if ($this->blockedEmailModel->isBlocked($email)) {
            return redirect()->to('/auth/blocked-registration');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $email,
            'phone' => $this->request->getPost('phone'),
            'password' => $this->request->getPost('password'),
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

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to send verification code. ' . $sendResult);
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
        $pending = session()->get(self::REGISTRATION_SESSION_KEY);
        if (!$pending || !isset($pending['data'], $pending['otp'])) {
            return redirect()->to('/auth/register-buyer')
                ->with('error', 'No pending registration found. Please register first.');
        }

        if (time() > (int) ($pending['expires_at'] ?? 0)) {
            session()->remove(self::REGISTRATION_SESSION_KEY);

            return redirect()->to('/auth/register-buyer')
                ->with('error', 'Verification code expired. Please register again.');
        }

        $inputOtp = trim((string) $this->request->getPost('otp'));
        if ($inputOtp === '' || $inputOtp !== (string) $pending['otp']) {
            return redirect()->back()->with('error', 'Invalid verification code. Please try again.');
        }

        $data = $pending['data'];
        if (!$this->userModel->save($data)) {
            session()->remove(self::REGISTRATION_SESSION_KEY);

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

        return redirect()->to('/marketplace')
            ->with('success', 'Registration successful! Welcome to Farmart.');
    }

    /**
     * Resend registration OTP email.
     */
    public function resendRegistrationOtp()
    {
        $pending = session()->get(self::REGISTRATION_SESSION_KEY);
        if (!$pending || !isset($pending['data']['email'])) {
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
            return redirect()->back()->with('error', 'Unable to resend verification code. ' . $sendResult);
        }

        return redirect()->back()->with('success', 'A new verification code has been sent to your email.');
    }
    
     /**
      * Logout
      */
     public function logout()
     {
         // Clear OTP login session if present
         session()->remove('otp_login_user_id');
         session()->remove('otp_login_email');
         
         session()->destroy();
         return redirect()->to('/')
             ->with('success', 'You have been logged out successfully.');
     }
    
     /**
      * Show login OTP verification page
      */
     public function loginVerify()
     {
         $userId = session()->get('otp_login_user_id');
         if (!$userId) {
             return redirect()->to('/auth/login')
                 ->with('error', 'Please login first.');
         }

         $user = $this->userModel->find($userId);
         if (!$user) {
             session()->remove('otp_login_user_id');
             session()->remove('otp_login_email');
             return redirect()->to('/auth/login')
                 ->with('error', 'User not found.');
         }

         // Show OTP verification form
         return view('auth/login_verify', [
             'email' => $user['email'],
         ]);
     }

     /**
      * Verify login OTP and complete authentication
      */
     public function loginVerifyProcess()
     {
         $userId = session()->get('otp_login_user_id');
         if (!$userId) {
             return redirect()->to('/auth/login')
                 ->with('error', 'Session expired. Please login again.');
         }

         $otpInput = trim((string) $this->request->getPost('otp'));
         if (!$otpInput) {
             return redirect()->back()->with('error', 'Please enter the verification code.');
         }

         $user = $this->userModel->find($userId);
         if (!$user) {
             session()->remove('otp_login_user_id');
             session()->remove('otp_login_email');
             return redirect()->to('/auth/login')
                 ->with('error', 'User not found.');
         }

        // Email whitelist gate (if enabled).
        if ($this->systemSettingModel->isEnabled('email_whitelist_enabled', false)) {
            if (!$this->whitelistedEmailModel->isWhitelisted((string) ($user['email'] ?? ''))) {
                session()->remove('otp_login_user_id');
                session()->remove('otp_login_email');
                return redirect()->to('/auth/login')
                    ->with('error', 'Access is restricted. Your email is not allowed to sign in.');
            }
        }

        // Check login suspension again (in case it changed after step 1).
        $suspendedUntil = $user['login_suspended_until'] ?? null;
        if ($suspendedUntil && strtotime($suspendedUntil) > time()) {
            session()->remove('otp_login_user_id');
            session()->remove('otp_login_email');
            return redirect()->to('/auth/login')
                ->with('error', 'Your account is temporarily suspended. Please try again later.');
        }

         // Check OTP from database
         $otpRecord = $this->otpTokenModel
             ->where('userID', $userId)
             ->where('token', $otpInput)
             ->first();

         if (!$otpRecord) {
             log_message('error', 'OTP verification failed: Invalid OTP for user ' . $userId);
             return redirect()->back()->with('error', 'Invalid verification code. Please try again.');
         }

         if (strtotime($otpRecord['expires_at']) < time()) {
             log_message('error', 'OTP verification failed: OTP expired for user ' . $userId);
             return redirect()->back()->with('error', 'Verification code has expired. Please login again.');
         }

         // OTP valid - complete login
         // Clear any existing OTPs for this user
         $this->otpTokenModel->where('userID', $userId)->delete();

         // Set full session
         session()->set([
             'user_id' => $user['id'],
             'user_name' => $user['name'],
             'user_email' => $user['email'],
             'user_role' => $user['role'],
             'logged_in' => true,
         ]);

         // Clean up OTP login session
         session()->remove('otp_login_user_id');
         session()->remove('otp_login_email');

         // Redirect to appropriate homepage
         if ($user['role'] === 'admin') {
             $redirectUrl = '/admin/dashboard';
         } else {
             $redirectUrl = session()->get('redirect_url') ?? $this->getUserHomePage($user['role']);
         }
         session()->remove('redirect_url');

         session()->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');

         return redirect()->to($redirectUrl);
     }

     /**
      * Resend login OTP
      */
     public function resendLoginOtp()
     {
         $userId = session()->get('otp_login_user_id');
         if (!$userId) {
             return redirect()->to('/auth/login')
                 ->with('error', 'Session expired. Please login again.');
         }

         $user = $this->userModel->find($userId);
         if (!$user) {
             session()->remove('otp_login_user_id');
             session()->remove('otp_login_email');
             return redirect()->to('/auth/login')
                 ->with('error', 'User not found.');
         }

         // Generate new OTP
         $otp = random_int(100000, 999999);
         $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

         // Clear old OTP and save new one
         $this->otpTokenModel->where('userID', $userId)->delete();
         $this->otpTokenModel->insert([
             'userID' => $userId,
             'token' => $otp,
             'expires_at' => $expiresAt,
             'created_at' => date('Y-m-d H:i:s')
         ]);

         // Send new OTP
         $sendResult = \App\Libraries\PHPMailerService::sendOTP($user['email'], $otp);
         if ($sendResult !== true) {
             return redirect()->back()->with('error', 'Failed to resend code. ' . $sendResult);
         }

         return redirect()->back()->with('success', 'A new verification code has been sent to your email.');
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
        // ...existing code...
}
