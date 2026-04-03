<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Profile extends BaseController
{
    private const PROFILE_UPDATE_SESSION_KEY = 'pending_profile_update';
    private const PROFILE_OTP_TTL = 600;

    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    /**
     * View profile
     */
    public function index()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login')
                ->with('error', 'Please login to view your profile.');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/auth/login')
                ->with('error', 'User not found.');
        }

        $db = \Config\Database::connect();

        // Get recent likes (last 10)
        $recentLikes = $db->table('forum_likes')
            ->select('forum_likes.created_at, forum_posts.title as post_title, forum_posts.id as post_id')
            ->join('forum_posts', 'forum_posts.id = forum_likes.post_id')
            ->where('forum_likes.user_id', $userId)
            ->orderBy('forum_likes.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Get recent comments (last 10)
        $recentComments = $db->table('forum_comments')
            ->select('forum_comments.comment, forum_comments.created_at, forum_posts.title as post_title, forum_posts.id as post_id')
            ->join('forum_posts', 'forum_posts.id = forum_comments.post_id')
            ->where('forum_comments.user_id', $userId)
            ->orderBy('forum_comments.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Get recent cart additions (last 10)
        $recentCartItems = [];
        try {
            if ($db->tableExists('cart')) {
                $recentCartItems = $db->table('cart')
                    ->select('cart.created_at, cart.quantity, products.name as product_name, products.price, products.id as product_id')
                    ->join('products', 'products.id = cart.product_id')
                    ->where('cart.user_id', $userId)
                    ->orderBy('cart.created_at', 'DESC')
                    ->limit(10)
                    ->get()
                    ->getResultArray();
            }
        } catch (DatabaseException $e) {
            log_message('error', 'Profile cart query failed: ' . $e->getMessage());
        }

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'recent_likes' => $recentLikes,
            'recent_comments' => $recentComments,
            'recent_cart_items' => $recentCartItems
        ];

        return view('profile/index', $data);
    }
    
    /**
     * Edit profile form
     */
    public function edit()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/auth/login')
                ->with('error', 'Please login to edit your profile.');
        }
        
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/auth/login')
                ->with('error', 'User not found.');
        }
        
        $data = [
            'title' => 'Edit Profile',
            'user' => $user
        ];
        
        return view('profile/edit', $data);
    }
    
    /**
     * Update profile
     */
    public function update()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/auth/login')
                ->with('error', 'Please login to update your profile.');
        }
        
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/auth/login')
                ->with('error', 'User not found.');
        }
        
        $validation = \Config\Services::validation();
        
        // Validation rules
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'phone' => 'permit_empty|max_length[20]',
            'location' => 'permit_empty|max_length[255]',
            'cooperative' => 'permit_empty|max_length[255]'
        ];
        
        // Only validate password if it's provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[8]';
            $rules['password_confirm'] = 'matches[password]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        // Prepare update data
        $updateData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone') ?? '',
            'location' => $this->request->getPost('location') ?? '',
        ];
        
        // Add cooperative only for farmers
        if (session()->get('user_role') === 'farmer') {
            $updateData['cooperative'] = $this->request->getPost('cooperative') ?? '';
        }
        
        // Update password only if provided
        if (!empty($password)) {
            $updateData['password'] = $password;
        }
        
        $phoneChanged = ($updateData['phone'] ?? '') !== ($user['phone'] ?? '');
        $passwordChanged = !empty($password);

        // Require OTP verification before updating contact number or password.
        if ($phoneChanged || $passwordChanged) {
            $otp = (string) random_int(100000, 999999);

            session()->set(self::PROFILE_UPDATE_SESSION_KEY, [
                'user_id' => $userId,
                'data' => $updateData,
                'otp' => $otp,
                'expires_at' => time() + self::PROFILE_OTP_TTL,
            ]);

            $sendResult = \App\Libraries\PHPMailerService::sendOTP($user['email'], $otp);
            if ($sendResult !== true) {
                session()->remove(self::PROFILE_UPDATE_SESSION_KEY);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Unable to send OTP. ' . $sendResult);
            }

            return redirect()->to('/profile/verify-otp')
                ->with('success', 'OTP sent to your email. Enter the code to confirm contact/password change.');
        }

        if ($this->userModel->skipValidation(true)->update($userId, $updateData)) {
            session()->set('user_name', $updateData['name']);
            session()->set('user_email', $updateData['email']);

            return redirect()->to('/profile')
                ->with('success', 'Profile updated successfully!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update profile. Please try again.');
    }

    /**
     * Show OTP verification page for pending profile updates.
     */
    public function verifyOtp()
    {
        $pending = session()->get(self::PROFILE_UPDATE_SESSION_KEY);
        $userId = session()->get('user_id');

        if (!$pending || !isset($pending['user_id']) || (int) $pending['user_id'] !== (int) $userId) {
            return redirect()->to('/profile')
                ->with('error', 'No pending OTP verification found.');
        }

        if (time() > (int) ($pending['expires_at'] ?? 0)) {
            session()->remove(self::PROFILE_UPDATE_SESSION_KEY);

            return redirect()->to('/profile')
                ->with('error', 'OTP has expired. Please submit your changes again.');
        }

        return view('profile/verify_otp');
    }

    /**
     * Verify OTP and apply pending sensitive profile updates.
     */
    public function verifyOtpProcess()
    {
        $pending = session()->get(self::PROFILE_UPDATE_SESSION_KEY);
        $userId = session()->get('user_id');

        if (!$pending || !isset($pending['user_id'], $pending['data'], $pending['otp']) || (int) $pending['user_id'] !== (int) $userId) {
            return redirect()->to('/profile')
                ->with('error', 'No pending OTP verification found.');
        }

        if (time() > (int) ($pending['expires_at'] ?? 0)) {
            session()->remove(self::PROFILE_UPDATE_SESSION_KEY);

            return redirect()->to('/profile')
                ->with('error', 'OTP has expired. Please submit your changes again.');
        }

        $otpInput = trim((string) $this->request->getPost('otp'));
        if ($otpInput === '' || !hash_equals((string) $pending['otp'], $otpInput)) {
            return redirect()->back()->with('error', 'Invalid OTP code. Please try again.');
        }

        $updateData = $pending['data'];
        if ($this->userModel->skipValidation(true)->update($userId, $updateData)) {
            session()->set('user_name', $updateData['name'] ?? session()->get('user_name'));
            session()->set('user_email', $updateData['email'] ?? session()->get('user_email'));

            session()->remove(self::PROFILE_UPDATE_SESSION_KEY);

            return redirect()->to('/profile')
                ->with('success', 'Profile changes verified and updated successfully!');
        }

        return redirect()->to('/profile')
            ->with('error', 'Failed to apply profile changes. Please try again.');
    }

    /**
     * Resend OTP for pending profile update verification.
     */
    public function resendOtp()
    {
        $pending = session()->get(self::PROFILE_UPDATE_SESSION_KEY);
        $userId = session()->get('user_id');

        if (!$pending || !isset($pending['user_id']) || (int) $pending['user_id'] !== (int) $userId) {
            return redirect()->to('/profile')
                ->with('error', 'No pending OTP verification found.');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            session()->remove(self::PROFILE_UPDATE_SESSION_KEY);

            return redirect()->back()
                ->with('error', 'User not found. Please login again.');
        }

        $otp = (string) random_int(100000, 999999);
        $pending['otp'] = $otp;
        $pending['expires_at'] = time() + self::PROFILE_OTP_TTL;
        session()->set(self::PROFILE_UPDATE_SESSION_KEY, $pending);

        $sendResult = \App\Libraries\PHPMailerService::sendOTP($user['email'], $otp);
        if ($sendResult !== true) {
            return redirect()->back()->with('error', 'Unable to resend OTP. ' . $sendResult);
        }

        return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
    }
}

