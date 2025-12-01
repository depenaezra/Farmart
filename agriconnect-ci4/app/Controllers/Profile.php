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
        $recentCartItems = $db->table('cart')
            ->select('cart.created_at, cart.quantity, products.name as product_name, products.price, products.id as product_id')
            ->join('products', 'products.id = cart.product_id')
            ->where('cart.user_id', $userId)
            ->orderBy('cart.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

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

