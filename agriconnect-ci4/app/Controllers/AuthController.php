<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    /**
     * Show login form
     */
    public function login()
    {
        // Redirect if already logged in
        if (session()->has('logged_in') && session()->get('logged_in')) {
            return redirect()->to($this->getUserHomePage(session()->get('user_role')));
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

        return redirect()->to($redirectUrl)
            ->with('success', 'Welcome back, ' . $user['name'] . '!');
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
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'required',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'location' => 'required',
            'cooperative' => 'permit_empty'
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
            'role' => 'farmer',
            'location' => $this->request->getPost('location'),
            'cooperative' => $this->request->getPost('cooperative'),
            'status' => 'active'
        ];
        
        if ($this->userModel->save($data)) {
            $userId = $this->userModel->insertID();
            $user = $this->userModel->find($userId);

            // Set session
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'logged_in' => true
            ]);

            return redirect()->to('/marketplace')
                ->with('success', 'Registration successful! Welcome to the marketplace.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again.');
        }
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
            'status' => 'active'
        ];
        
        if ($this->userModel->save($data)) {
            $userId = $this->userModel->insertID();
            $user = $this->userModel->find($userId);

            // Set session
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'logged_in' => true
            ]);

            return redirect()->to('/marketplace')
                ->with('success', 'Registration successful! Welcome to the marketplace.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again.');
        }
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
            case 'farmer':
                return '/marketplace';
            case 'buyer':
                return '/marketplace';
            case 'admin':
                return '/admin/dashboard';
            default:
                return '/';
        }
    }
}
