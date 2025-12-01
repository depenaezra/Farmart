<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_number',
        'buyer_id',
        'farmer_id',
        'product_id',
        'quantity',
        'unit',
        'total_price',
        'status',
        'delivery_address',
        'payment_method',
        'notes'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'buyer_id' => 'required|integer',
        'farmer_id' => 'required|integer',
        'product_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'unit' => 'required|max_length[50]',
        'total_price' => 'required|decimal|greater_than[0]',
        'delivery_address' => 'required'
    ];
    
    /**
     * Generate unique order number
     */
    public function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    }
    
    /**
     * Create order with order number
     */
    public function createOrder($data)
    {
        $data['order_number'] = $this->generateOrderNumber();
        $data['status'] = 'pending';
        
        if ($this->insert($data)) {
            return $this->getInsertID();
        }
        return false;
    }
    
    /**
     * Get orders by buyer
     */
    public function getOrdersByBuyer($buyerId, $status = null)
    {
        // First check if there are any orders for this buyer
        $rawOrders = $this->where('buyer_id', $buyerId)->findAll();
        log_message('debug', 'Raw orders count for buyer ' . $buyerId . ': ' . count($rawOrders));

        $builder = $this->select('orders.*,
                                  products.name as product_name,
                                  products.image_url,
                                  farmer.name as farmer_name,
                                  farmer.phone as farmer_phone,
                                  farmer.location as farmer_location')
                        ->join('products', 'products.id = orders.product_id')
                        ->join('users as farmer', 'farmer.id = orders.farmer_id')
                        ->where('orders.buyer_id', $buyerId)
                        ->orderBy('orders.created_at', 'ASC'); // Changed to ASC for chronological order

        if ($status) {
            $builder->where('orders.status', $status);
        }

        $result = $builder->get()->getResultArray();
        log_message('debug', 'Joined orders count for buyer ' . $buyerId . ': ' . count($result));

        // Add sequential order number based on chronological order (oldest first)
        foreach ($result as $index => &$order) {
            $order['order_sequence'] = $index + 1;
        }

        // Reverse back to DESC order for display (most recent first)
        $result = array_reverse($result);

        return $result;
    }
    
    /**
     * Get orders by farmer
     */
    public function getOrdersByFarmer($farmerId, $status = null)
    {
        $builder = $this->select('orders.*,
                                  products.name as product_name,
                                  products.image_url,
                                  buyer.name as buyer_name,
                                  buyer.phone as buyer_phone,
                                  buyer.email as buyer_email,
                                  buyer.location as buyer_location')
                        ->join('products', 'products.id = orders.product_id')
                        ->join('users as buyer', 'buyer.id = orders.buyer_id')
                        ->where('orders.farmer_id', $farmerId)
                        ->orderBy('orders.created_at', 'ASC'); // Changed to ASC for chronological order

        if ($status) {
            $builder->where('orders.status', $status);
        }

        $result = $builder->get()->getResultArray();

        // Add sequential order number based on chronological order (oldest first)
        foreach ($result as $index => &$order) {
            $order['order_sequence'] = $index + 1;
        }

        // Reverse back to DESC order for display (most recent first)
        $result = array_reverse($result);

        return $result;
    }
    
    /**
     * Get order with details
     */
    public function getOrderWithDetails($orderId, $userId = null, $userRole = null)
    {
        $order = $this->select('orders.*,
                              products.name as product_name,
                              products.description as product_description,
                              products.image_url,
                              buyer.name as buyer_name,
                              buyer.phone as buyer_phone,
                              buyer.email as buyer_email,
                              buyer.location as buyer_location,
                              farmer.name as farmer_name,
                              farmer.phone as farmer_phone,
                              farmer.email as farmer_email,
                              farmer.location as farmer_location,
                              farmer.cooperative')
                    ->join('products', 'products.id = orders.product_id')
                    ->join('users as buyer', 'buyer.id = orders.buyer_id')
                    ->join('users as farmer', 'farmer.id = orders.farmer_id')
                    ->where('orders.id', $orderId)
                    ->first();

        if ($order && $userId) {
            // Determine sequence based on user role
            if ($userRole === 'buyer' || $order['buyer_id'] == $userId) {
                // For buyer viewing their order, sequence based on their orders
                $userOrders = $this->where('buyer_id', $userId)
                                   ->orderBy('created_at', 'ASC')
                                   ->findAll();
            } else {
                // For seller viewing their sales order, sequence based on their sales
                $userOrders = $this->where('farmer_id', $userId)
                                   ->orderBy('created_at', 'ASC')
                                   ->findAll();
            }

            foreach ($userOrders as $index => $userOrder) {
                if ($userOrder['id'] == $orderId) {
                    $order['order_sequence'] = $index + 1;
                    break;
                }
            }
        }

        return $order;
    }
    
    /**
     * Update order status
     */
    public function updateStatus($orderId, $status)
    {
        $validStatuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];
        
        if (in_array($status, $validStatuses)) {
            return $this->update($orderId, ['status' => $status]);
        }
        
        return false;
    }
    
    /**
     * Get farmer statistics
     */
    public function getFarmerStatistics($farmerId)
    {
        $db = \Config\Database::connect();

        return [
            'total_orders' => $db->table('orders')->where('farmer_id', $farmerId)->countAllResults(),
            'pending' => $db->table('orders')->where('farmer_id', $farmerId)->where('status', 'pending')->countAllResults(),
            'processing' => $db->table('orders')->where('farmer_id', $farmerId)->where('status', 'processing')->countAllResults(),
            'completed' => $db->table('orders')->where('farmer_id', $farmerId)->where('status', 'completed')->countAllResults(),
            'cancelled' => $db->table('orders')->where('farmer_id', $farmerId)->where('status', 'cancelled')->countAllResults(),
            'total_sales' => $db->table('orders')
                                ->selectSum('total_price')
                                ->where('farmer_id', $farmerId)
                                ->where('status', 'completed')
                                ->get()
                                ->getRow()
                                ->total_price ?? 0
        ];
    }
    
    /**
     * Get buyer statistics
     */
    public function getBuyerStatistics($buyerId)
    {
        $db = \Config\Database::connect();

        $count = function (?string $status = null) use ($db, $buyerId) {
            $builder = $db->table('orders')->where('buyer_id', $buyerId);
            if ($status !== null) {
                $builder->where('status', $status);
            }
            return $builder->countAllResults();
        };

        $totalSpentRow = $db->table('orders')
            ->selectSum('total_price')
            ->where('buyer_id', $buyerId)
            ->where('status', 'completed')
            ->get()
            ->getRow();

        return [
            'total_orders' => $count(),
            'pending'      => $count('pending'),
            'confirmed'    => $count('confirmed'),
            'processing'   => $count('processing'),
            'completed'    => $count('completed'),
            'cancelled'    => $count('cancelled'),
            'total_spent'  => $totalSpentRow->total_price ?? 0,
        ];
    }
    
    /**
     * Get all orders for admin
     */
    public function getAllOrdersForAdmin($status = null)
    {
        $builder = $this->select('orders.*, 
                                  products.name as product_name,
                                  buyer.name as buyer_name,
                                  farmer.name as farmer_name')
                        ->join('products', 'products.id = orders.product_id')
                        ->join('users as buyer', 'buyer.id = orders.buyer_id')
                        ->join('users as farmer', 'farmer.id = orders.farmer_id')
                        ->orderBy('orders.created_at', 'DESC');
        
        if ($status) {
            $builder->where('orders.status', $status);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get recent orders
     */
    public function getRecentOrders($limit = 10)
    {
        return $this->select('orders.*,
                               products.name as product_name,
                               buyer.name as buyer_name,
                               farmer.name as farmer_name')
                    ->join('products', 'products.id = orders.product_id')
                    ->join('users as buyer', 'buyer.id = orders.buyer_id')
                    ->join('users as farmer', 'farmer.id = orders.farmer_id')
                    ->orderBy('orders.created_at', 'DESC')
                    ->limit($limit)
                    ->get()
                    ->getResultArray();
    }

    /**
     * Get sales data for charts (monthly sales for last 12 months)
     */
    public function getSalesChartData($farmerId)
    {
        $db = \Config\Database::connect();

        // Get sales data for last 12 months
        $query = $db->query("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(total_price) as revenue,
                COUNT(*) as orders_count
            FROM orders
            WHERE farmer_id = ?
                AND status = 'completed'
                AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC
        ", [$farmerId]);

        return $query->getResultArray();
    }

    /**
     * Get order status distribution for pie chart
     */
    public function getOrderStatusChartData($farmerId)
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT
                status,
                COUNT(*) as count
            FROM orders
            WHERE farmer_id = ?
            GROUP BY status
        ", [$farmerId]);

        return $query->getResultArray();
    }
}
