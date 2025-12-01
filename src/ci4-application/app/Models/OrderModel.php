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
        'payment_method',
        'status',
        'delivery_address',
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
        
        return $this->insert($data);
    }
    
    /**
     * Get orders by buyer
     */
    public function getOrdersByBuyer($buyerId, $status = null)
    {
        $builder = $this->select('orders.*, 
                                  products.name as product_name, 
                                  products.image_url,
                                  farmer.name as farmer_name, 
                                  farmer.phone as farmer_phone,
                                  farmer.location as farmer_location')
                        ->join('products', 'products.id = orders.product_id')
                        ->join('users as farmer', 'farmer.id = orders.farmer_id')
                        ->where('orders.buyer_id', $buyerId)
                        ->orderBy('orders.created_at', 'DESC');
        
        if ($status) {
            $builder->where('orders.status', $status);
        }
        
        return $builder->get()->getResultArray();
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
                        ->orderBy('orders.created_at', 'DESC');

        if ($status) {
            $builder->where('orders.status', $status);
        }

        return $builder->get()->getResultArray();
    }
    
    /**
     * Get order with details
     */
    public function getOrderWithDetails($orderId)
    {
        return $this->select('orders.*, 
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
        
        return [
            'total_orders' => $this->where('buyer_id', $buyerId)->countAllResults(false),
            'pending' => $this->where('buyer_id', $buyerId)->where('status', 'pending')->countAllResults(false),
            'completed' => $this->where('buyer_id', $buyerId)->where('status', 'completed')->countAllResults(false),
            'total_spent' => $db->table('orders')
                               ->selectSum('total_price')
                               ->where('buyer_id', $buyerId)
                               ->where('status', 'completed')
                               ->get()
                               ->getRow()
                               ->total_spent ?? 0
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
}
