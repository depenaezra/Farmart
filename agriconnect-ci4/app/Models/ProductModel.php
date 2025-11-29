<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'farmer_id',
        'name',
        'description',
        'price',
        'unit',
        'category',
        'stock_quantity',
        'location',
        'image_url',
        'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'farmer_id' => 'required|integer',
        'name' => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty',
        'price' => 'required|decimal|greater_than[0]',
        'unit' => 'required|max_length[50]',
        'category' => 'required|in_list[vegetables,fruits,grains,other]',
        'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        'location' => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Product name is required',
            'min_length' => 'Product name must be at least 3 characters'
        ],
        'price' => [
            'required' => 'Price is required',
            'greater_than' => 'Price must be greater than 0'
        ],
        'stock_quantity' => [
            'required' => 'Stock quantity is required',
            'greater_than_equal_to' => 'Stock cannot be negative'
        ]
    ];
    
    /**
     * Get all available products
     */
    public function getAvailableProducts($limit = null)
    {
        $builder = $this->select('products.*, users.name as farmer_name, users.phone as farmer_phone, users.cooperative')
                        ->join('users', 'users.id = products.farmer_id')
                        ->where('products.status', 'available')
                        ->where('products.stock_quantity >', 0)
                        ->orderBy('products.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get products by farmer
     */
    public function getProductsByFarmer($farmerId)
    {
        return $this->where('farmer_id', $farmerId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get product with farmer details
     */
    public function getProductWithFarmer($productId)
    {
        return $this->select('products.*, users.name as farmer_name, users.email as farmer_email, users.phone as farmer_phone, users.location as farmer_location, users.cooperative')
                    ->join('users', 'users.id = products.farmer_id')
                    ->where('products.id', $productId)
                    ->first();
    }
    
    /**
     * Search products
     */
    public function searchProducts($params = [])
    {
        $builder = $this->select('products.*, users.name as farmer_name, users.cooperative')
                        ->join('users', 'users.id = products.farmer_id')
                        ->where('products.status', 'available');
        
        // Search keyword
        if (!empty($params['keyword'])) {
            $builder->groupStart()
                    ->like('products.name', $params['keyword'])
                    ->orLike('products.description', $params['keyword'])
                    ->orLike('users.name', $params['keyword'])
                    ->groupEnd();
        }
        
        // Category filter
        if (!empty($params['category']) && $params['category'] !== 'all') {
            $builder->where('products.category', $params['category']);
        }
        
        // Price range
        if (!empty($params['min_price'])) {
            $builder->where('products.price >=', $params['min_price']);
        }
        if (!empty($params['max_price'])) {
            $builder->where('products.price <=', $params['max_price']);
        }
        
        // Location filter
        if (!empty($params['location'])) {
            $builder->like('products.location', $params['location']);
        }
        
        $builder->orderBy('products.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Update stock quantity
     */
    public function updateStock($productId, $quantity)
    {
        $product = $this->find($productId);
        if ($product) {
            $newQuantity = max(0, $product['stock_quantity'] + $quantity);
            $status = $newQuantity > 0 ? 'available' : 'out-of-stock';
            
            return $this->update($productId, [
                'stock_quantity' => $newQuantity,
                'status' => $status
            ]);
        }
        return false;
    }
    
    /**
     * Reduce stock (for orders)
     */
    public function reduceStock($productId, $quantity)
    {
        return $this->updateStock($productId, -$quantity);
    }
    
    /**
     * Get products by category
     */
    public function getProductsByCategory($category)
    {
        return $this->select('products.*, users.name as farmer_name, users.cooperative')
                    ->join('users', 'users.id = products.farmer_id')
                    ->where('products.category', $category)
                    ->where('products.status', 'available')
                    ->where('products.stock_quantity >', 0)
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get farmer statistics
     */
    public function getFarmerStatistics($farmerId)
    {
        return [
            'total_products' => $this->where('farmer_id', $farmerId)->countAllResults(false),
            'available' => $this->where('farmer_id', $farmerId)->where('status', 'available')->countAllResults(false),
            'out_of_stock' => $this->where('farmer_id', $farmerId)->where('status', 'out-of-stock')->countAllResults(false),
            'pending' => $this->where('farmer_id', $farmerId)->where('status', 'pending')->countAllResults()
        ];
    }
    
    /**
     * Get all products for admin
     */
    public function getAllProductsForAdmin($status = null)
    {
        $builder = $this->select('products.*, users.name as farmer_name, users.email as farmer_email')
                        ->join('users', 'users.id = products.farmer_id')
                        ->orderBy('products.created_at', 'DESC');
        
        if ($status) {
            $builder->where('products.status', $status);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Approve product
     */
    public function approveProduct($productId)
    {
        return $this->update($productId, ['status' => 'available']);
    }
    
    /**
     * Reject product
     */
    public function rejectProduct($productId)
    {
        return $this->update($productId, ['status' => 'rejected']);
    }
}
