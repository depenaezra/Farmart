<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    private const REGISTRATION_SESSION_KEY = 'pending_registration';
    private const REGISTRATION_OTP_TTL = 600;


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

        if (!$newPassword || strlen($newPassword) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters.');
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
    protected $userModel;
    protected $otpTokenModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->otpTokenModel = new \App\Models\OtpTokenModel();
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

        // Clean up inconsistent session state so login works normally.
        if (session()->has('logged_in') && session()->get('logged_in') && !session()->get('user_id')) {
            session()->destroy();
        }
        
        return view('auth/login');
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
        
        // Find user by email
        $user = $this->userModel->getUserByEmail($email);
        
        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Your account has been deactivated. Please contact admin.');
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }
        
        // Set session
        session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
            'logged_in' => true
        ]);

        // Redirect to appropriate homepage
        // Admin users always go to admin dashboard, ignoring any redirect_url
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
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'location' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
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
        session()->destroy();
        return redirect()->to('/')
            ->with('success', 'You have been logged out successfully.');
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
