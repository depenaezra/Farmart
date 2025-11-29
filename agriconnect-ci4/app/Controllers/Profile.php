<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
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
        
        $data = [
            'title' => 'My Profile',
            'user' => $user
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
        
        // Skip validation if password is not being updated
        $skipValidation = empty($password);
        
        if ($this->userModel->skipValidation($skipValidation)->update($userId, $updateData)) {
            // Update session data
            session()->set('user_name', $updateData['name']);
            session()->set('user_email', $updateData['email']);
            
            return redirect()->to('/profile')
                ->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }
}

