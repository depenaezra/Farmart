<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\AnnouncementModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $productModel;
    protected $orderModel;
    protected $announcementModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->announcementModel = new AnnouncementModel();
    }
    
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Calculate revenue from completed orders this month
        $db = \Config\Database::connect();
        $currentMonth = date('Y-m');
        $revenue = $db->table('orders')
                      ->selectSum('total_price')
                      ->where('status', 'completed')
                      ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                      ->get()
                      ->getRow()
                      ->total_price ?? 0;

        $data = [
            'title' => 'Admin Dashboard',
            'statistics' => [
                'users' => $this->userModel->getStatistics(),
                'products' => [
                    'total' => $this->productModel->countAll(),
                    'available' => $this->productModel->where('status', 'available')->countAllResults(false),
                    'pending' => $this->productModel->where('status', 'pending')->countAllResults(false),
                    'out_of_stock' => $this->productModel->where('status', 'out-of-stock')->countAllResults()
                ],
                'orders' => [
                    'total' => $this->orderModel->countAll(),
                    'pending' => $this->orderModel->where('status', 'pending')->countAllResults(false),
                    'completed' => $this->orderModel->where('status', 'completed')->countAllResults()
                ],
                'revenue' => $revenue
            ],
            'recent_orders' => $this->orderModel->getRecentOrders(5),
            'recent_users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(5)
        ];

        return view('admin/dashboard', $data);
    }
    
    /**
     * User Management
     */
    public function users()
    {
        $role = $this->request->getGet('role');
        $search = $this->request->getGet('search');
        
        if ($search) {
            $users = $this->userModel->searchUsers($search, $role);
        } elseif ($role) {
            $users = $this->userModel->where('role', $role)->findAll();
        } else {
            $users = $this->userModel->findAll();
        }
        
        $data = [
            'title' => 'User Management',
            'users' => $users,
            'current_role' => $role,
            'search_term' => $search
        ];
        
        return view('admin/users', $data);
    }
    
    /**
     * User Detail
     */
    public function userDetail($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found.');
        }
        
        // Get user's products if farmer
        $products = [];
        if ($user['role'] === 'farmer') {
            $products = $this->productModel->getProductsByFarmer($id);
        }
        
        // Get user's orders
        $orders = [];
        if ($user['role'] === 'farmer') {
            $orders = $this->orderModel->getOrdersByFarmer($id);
        } elseif ($user['role'] === 'buyer') {
            $orders = $this->orderModel->getOrdersByBuyer($id);
        }
        
        $data = [
            'title' => 'User: ' . $user['name'],
            'user' => $user,
            'products' => $products,
            'orders' => $orders
        ];
        
        return view('admin/user_detail', $data);
    }
    
    /**
     * Toggle User Status
     */
    public function toggleUserStatus($id)
    {
        if ($this->userModel->toggleStatus($id)) {
            return redirect()->back()
                ->with('success', 'User status updated.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update user status.');
        }
    }
    
    /**
     * Change User Role
     */
    public function changeUserRole($id)
    {
        $newRole = $this->request->getPost('role');

        if (!in_array($newRole, ['farmer', 'buyer', 'admin'])) {
            return redirect()->back()
                ->with('error', 'Invalid role specified.');
        }

        if ($this->userModel->update($id, ['role' => $newRole])) {
            return redirect()->back()
                ->with('success', 'User role updated successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update user role.');
        }
    }

    /**
     * Delete User
     */
    public function deleteUser($id)
    {
        // Prevent deleting own account
        if ($id == session()->get('user_id')) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')
                ->with('success', 'User deleted.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete user.');
        }
    }
    
    /**
     * Product Management
     */
    public function products()
    {
        $status = $this->request->getGet('status');
        
        $products = $this->productModel->getAllProductsForAdmin($status);
        
        $data = [
            'title' => 'Product Management',
            'products' => $products,
            'current_status' => $status
        ];
        
        return view('admin/products', $data);
    }
    
    /**
     * Product Detail
     */
    public function productDetail($id)
    {
        $product = $this->productModel->getProductWithFarmer($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')
                ->with('error', 'Product not found.');
        }
        
        $data = [
            'title' => 'Product: ' . $product['name'],
            'product' => $product
        ];
        
        return view('admin/product_detail', $data);
    }
    
    /**
     * Approve Product
     */
    public function approveProduct($id)
    {
        if ($this->productModel->approveProduct($id)) {
            return redirect()->back()
                ->with('success', 'Product approved.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to approve product.');
        }
    }
    
    /**
     * Reject Product
     */
    public function rejectProduct($id)
    {
        if ($this->productModel->rejectProduct($id)) {
            return redirect()->back()
                ->with('success', 'Product rejected.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to reject product.');
        }
    }
    
    /**
     * Delete Product
     */
    public function deleteProduct($id)
    {
        if ($this->productModel->delete($id)) {
            return redirect()->to('/admin/products')
                ->with('success', 'Product deleted.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete product.');
        }
    }
    
    /**
     * Announcements Management
     */
    public function announcements()
    {
        $announcements = $this->announcementModel->getAllWithCreator();
        
        $data = [
            'title' => 'Manage Announcements',
            'announcements' => $announcements
        ];
        
        return view('admin/announcements', $data);
    }
    
    /**
     * Create Announcement Form
     */
    public function createAnnouncement()
    {
        $data = [
            'title' => 'Create Announcement'
        ];
        
        return view('admin/create_announcement', $data);
    }
    
    /**
     * Process Create Announcement
     */
    public function createAnnouncementProcess()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[5]',
            'content' => 'required|min_length[20]',
            'category' => 'required|in_list[weather,government,market,general]',
            'priority' => 'required|in_list[low,medium,high]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'category' => $this->request->getPost('category'),
            'priority' => $this->request->getPost('priority'),
            'created_by' => session()->get('user_id')
        ];
        
        if ($this->announcementModel->save($data)) {
            return redirect()->to('/admin/announcements')
                ->with('success', 'Announcement created successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create announcement.');
        }
    }
    
    /**
     * Edit Announcement
     */
    public function editAnnouncement($id)
    {
        $announcement = $this->announcementModel->find($id);
        
        if (!$announcement) {
            return redirect()->to('/admin/announcements')
                ->with('error', 'Announcement not found.');
        }
        
        $data = [
            'title' => 'Edit Announcement',
            'announcement' => $announcement
        ];
        
        return view('admin/edit_announcement', $data);
    }
    
    /**
     * Process Edit Announcement
     */
    public function editAnnouncementProcess($id)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[5]',
            'content' => 'required|min_length[20]',
            'category' => 'required|in_list[weather,government,market,general]',
            'priority' => 'required|in_list[low,medium,high]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'category' => $this->request->getPost('category'),
            'priority' => $this->request->getPost('priority')
        ];
        
        if ($this->announcementModel->update($id, $data)) {
            return redirect()->to('/admin/announcements')
                ->with('success', 'Announcement updated successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update announcement.');
        }
    }
    
    /**
     * Delete Announcement
     */
    public function deleteAnnouncement($id)
    {
        if ($this->announcementModel->delete($id)) {
            return redirect()->to('/admin/announcements')
                ->with('success', 'Announcement deleted.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete announcement.');
        }
    }
    
    /**
     * Analytics
     */
    public function analytics()
    {
        $data = [
            'title' => 'Analytics & Reports'
        ];
        
        return view('admin/analytics', $data);
    }
    
    /**
     * Settings
     */
    public function settings()
    {
        $data = [
            'title' => 'System Settings'
        ];
        
        return view('admin/settings', $data);
    }
}
