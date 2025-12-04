<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\AnnouncementModel;
use App\Models\ViolationModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $productModel;
    protected $orderModel;
    protected $announcementModel;
    protected $violationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->announcementModel = new AnnouncementModel();
        $this->violationModel = new ViolationModel();
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

        // User registration data for chart (last 12 months)
        $userRegistrationData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $db->table('users')
                       ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                       ->countAllResults();
            $userRegistrationData[] = [
                'month' => date('M Y', strtotime($month)),
                'count' => $count
            ];
        }

        // Order data for chart (last 12 months)
        $orderData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $db->table('orders')
                       ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                       ->countAllResults();
            $orderData[] = [
                'month' => date('M Y', strtotime($month)),
                'count' => $count
            ];
        }

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
                'violations' => [
                    'total' => $this->violationModel->countAll(),
                    'pending' => $this->violationModel->where('status', 'pending')->countAllResults(false),
                    'reviewed' => $this->violationModel->where('status', 'reviewed')->countAllResults(false),
                    'resolved' => $this->violationModel->where('status', 'resolved')->countAllResults()
                ],
                'revenue' => $revenue
            ],
            'charts' => [
                'user_registrations' => $userRegistrationData,
                'orders' => $orderData
            ],
            'recent_orders' => $this->orderModel->getRecentOrders(5),
            'recent_users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(5),
            'recent_violations' => $this->violationModel->getViolationsForAdmin('pending')
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

        // Get user's products (all users can have products now)
        $products = $this->productModel->getProductsByFarmer($id);

        // Get user's orders (both as buyer and seller)
        $buyerOrders = $this->orderModel->getOrdersByBuyer($id);
        $sellerOrders = $this->orderModel->getOrdersByFarmer($id);
        $orders = array_merge($buyerOrders, $sellerOrders);

        // Sort orders by creation date (most recent first)
        usort($orders, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

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
        $type = $this->request->getGet('type'); // 'normal' or 'spoiled'

        if ($type === 'spoiled') {
            $products = $this->productModel->getSpoiledProductsForAdmin();
            $title = 'Spoiled Products Management';
        } else {
            $products = $this->productModel->getAllProductsForAdmin($status);
            $title = 'Product Management';
        }

        $data = [
            'title' => $title,
            'products' => $products,
            'current_status' => $status,
            'current_type' => $type
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
     * Orders Management
     */
    public function orders()
    {
        $status = $this->request->getGet('status');
        
        $orders = $this->orderModel->getAllOrdersForAdmin($status);
        
        $data = [
            'title' => 'Order Management',
            'orders' => $orders,
            'current_status' => $status,
            'statistics' => [
                'total' => $this->orderModel->countAll(),
                'pending' => $this->orderModel->where('status', 'pending')->countAllResults(false),
                'confirmed' => $this->orderModel->where('status', 'confirmed')->countAllResults(false),
                'processing' => $this->orderModel->where('status', 'processing')->countAllResults(false),
                'completed' => $this->orderModel->where('status', 'completed')->countAllResults(false),
                'cancelled' => $this->orderModel->where('status', 'cancelled')->countAllResults(false)
            ]
        ];
        
        return view('admin/orders', $data);
    }
    
    /**
     * Order Detail
     */
    public function orderDetail($id)
    {
        $order = $this->orderModel->getOrderWithDetails($id);
        
        if (!$order) {
            return redirect()->to('/admin/orders')
                ->with('error', 'Order not found.');
        }
        
        $data = [
            'title' => 'Order: ' . $order['order_number'],
            'order' => $order
        ];
        
        return view('admin/order_detail', $data);
    }
    
    /**
     * Update Order Status
     */
    public function updateOrderStatus($id)
    {
        $status = $this->request->getPost('status');
        
        $validStatuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            return redirect()->back()
                ->with('error', 'Invalid status specified.');
        }
        
        if ($this->orderModel->updateStatus($id, $status)) {
            return redirect()->back()
                ->with('success', 'Order status updated successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update order status.');
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
        $db = \Config\Database::connect();

        // Current month and last month
        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        // Monthly growth - user registrations
        $currentMonthUsers = $db->table('users')
                              ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                              ->countAllResults();

        $lastMonthUsers = $db->table('users')
                           ->where('DATE_FORMAT(created_at, "%Y-%m")', $lastMonth)
                           ->countAllResults();

        $userGrowth = $lastMonthUsers > 0 ? (($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;

        // Active users - users who have placed orders or posted in forum in last 30 days
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $activeUsers = $db->table('users')
                         ->where('id IN (
                             SELECT DISTINCT buyer_id FROM orders WHERE created_at >= "' . $thirtyDaysAgo . '"
                             UNION
                             SELECT DISTINCT user_id FROM forum_posts WHERE created_at >= "' . $thirtyDaysAgo . '"
                             UNION
                             SELECT DISTINCT user_id FROM forum_comments WHERE created_at >= "' . $thirtyDaysAgo . '"
                         )')
                         ->countAllResults();

        // Conversion rate - orders per user
        $totalUsers = $this->userModel->countAll();
        $totalOrders = $this->orderModel->countAll();
        $conversionRate = $totalUsers > 0 ? ($totalOrders / $totalUsers) * 100 : 0;

        // System activity metrics (orders completed this month)
        $monthlyOrders = $db->table('orders')
                           ->where('status', 'completed')
                           ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                           ->countAllResults();

        // User registration data for chart (last 12 months)
        $userRegistrationData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $db->table('users')
                       ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                       ->countAllResults();
            $userRegistrationData[] = [
                'month' => date('M Y', strtotime($month)),
                'count' => $count
            ];
        }

        // Order data for chart (last 12 months)
        $orderData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $db->table('orders')
                       ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                       ->countAllResults();
            $orderData[] = [
                'month' => date('M Y', strtotime($month)),
                'count' => $count
            ];
        }


        // Top products by orders
        $topProducts = $db->table('products p')
                         ->select('p.id, p.name, p.category, COUNT(o.id) as order_count, SUM(o.total_price) as total_revenue')
                         ->join('orders o', 'p.id = o.product_id AND o.status = "completed"', 'left')
                         ->groupBy('p.id')
                         ->orderBy('order_count', 'DESC')
                         ->limit(5)
                         ->get()
                         ->getResultArray();

        // Top sellers by orders (users who have sold products - appear as farmer_id in completed orders)
        $topFarmers = $db->table('users u')
                        ->select('u.id, u.name, u.location, COUNT(DISTINCT o.id) as order_count')
                        ->join('orders o', 'u.id = o.farmer_id AND o.status = "completed"', 'left')
                        ->where('u.role !=', 'admin')  // Exclude admins from seller rankings
                        ->groupBy('u.id')
                        ->having('COUNT(DISTINCT o.id) >', 0)  // Only include users who have actually sold something
                        ->orderBy('order_count', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();


        $data = [
            'title' => 'Analytics & Reports',
            'metrics' => [
                'user_growth' => round($userGrowth, 1),
                'active_users' => $activeUsers,
                'conversion_rate' => round($conversionRate, 1),
                'monthly_orders' => $monthlyOrders,
                'total_users' => $totalUsers,
                'total_products' => $this->productModel->countAll(),
                'total_orders' => $totalOrders
            ],
            'charts' => [
                'user_registrations' => $userRegistrationData,
                'orders' => $orderData
            ],
            'top_data' => [
                'products' => $topProducts,
                'farmers' => $topFarmers
            ],
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

    /**
     * Violations Management
     */
    public function violations()
    {
        $status = $this->request->getGet('status');

        $violations = $this->violationModel->getViolationsForAdmin($status);

        // Get statistics using fresh queries to avoid query builder state issues
        $db = \Config\Database::connect();
        
        $data = [
            'title' => 'Violation Reports',
            'violations' => $violations,
            'current_status' => $status,
            'statistics' => [
                'violations' => [
                    'total' => $this->violationModel->countAll(),
                    'pending' => $db->table('violations')->where('status', 'pending')->countAllResults(),
                    'reviewed' => $db->table('violations')->where('status', 'reviewed')->countAllResults(),
                    'resolved' => $db->table('violations')->where('status', 'resolved')->countAllResults()
                ]
            ]
        ];

        return view('admin/violations', $data);
    }

    /**
     * Update Violation Status
     */
    public function updateViolationStatus($id)
    {
        $status = $this->request->getPost('status');

        if (!in_array($status, ['pending', 'reviewed', 'resolved'])) {
            return redirect()->back()
                ->with('error', 'Invalid status specified.');
        }

        if ($this->violationModel->updateStatus($id, $status, session()->get('user_id'))) {
            return redirect()->back()
                ->with('success', 'Violation status updated successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update violation status.');
        }
    }

    /**
     * Delete Reported Item
     */
    public function deleteReportedItem($violationId)
    {
        $violation = $this->violationModel->find($violationId);

        if (!$violation) {
            return redirect()->back()
                ->with('error', 'Violation report not found.');
        }

        $deleted = false;
        $itemType = '';

        switch ($violation['reported_type']) {
            case 'forum_post':
                $deleted = $this->deleteForumPost($violation['reported_id']);
                $itemType = 'forum post';
                break;
            case 'forum_comment':
                $deleted = $this->deleteForumComment($violation['reported_id']);
                $itemType = 'forum comment';
                break;
            case 'product':
                $deleted = $this->deleteProduct($violation['reported_id']);
                $itemType = 'product';
                break;
            case 'user':
                // For user reports, perhaps suspend instead of delete
                $deleted = $this->userModel->update($violation['reported_id'], ['status' => 'inactive']);
                $itemType = 'user account';
                break;
        }

        if ($deleted) {
            // Mark violation as resolved
            $this->violationModel->updateStatus($violationId, 'resolved', session()->get('user_id'));
            return redirect()->back()
                ->with('success', ucfirst($itemType) . ' deleted successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete ' . $itemType . '.');
        }
    }

    /**
     * Delete Violation Report
     */
    public function deleteViolationReport($id)
    {
        $violation = $this->violationModel->find($id);

        if (!$violation) {
            return redirect()->back()
                ->with('error', 'Violation report not found.');
        }

        if ($this->violationModel->delete($id)) {
            return redirect()->to('/admin/violations')
                ->with('success', 'Violation report deleted successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete violation report.');
        }
    }

    /**
     * Delete Forum Post (Admin)
     */
    private function deleteForumPost($postId)
    {
        $db = \Config\Database::connect();
        return $db->table('forum_posts')->delete(['id' => $postId]);
    }

    /**
     * Delete Forum Comment (Admin)
     */
    private function deleteForumComment($commentId)
    {
        $db = \Config\Database::connect();
        return $db->table('forum_comments')->delete(['id' => $commentId]);
    }

    /**
     * Update Settings
     */
    public function updateSettings()
    {
        // This is a placeholder for settings functionality
        // In a real application, you would save these to a settings table or config file

        return redirect()->back()
            ->with('success', 'Settings updated successfully!');
    }
}
