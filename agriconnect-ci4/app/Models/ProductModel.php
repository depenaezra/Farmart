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

    /**
     * Get seasonal products based on current month
     * Using typical Philippine agricultural calendar
     */
    public function getSeasonalProducts($limit = 8)
    {
        $currentMonth = date('n'); // 1-12

        // Define seasonal products by month (Philippine agricultural calendar)
        $seasonalProducts = [
            1 => ['tomato', 'lettuce', 'cabbage', 'carrot', 'onion', 'garlic'], // January
            2 => ['tomato', 'lettuce', 'cabbage', 'carrot', 'onion', 'garlic'], // February
            3 => ['tomato', 'eggplant', 'calabaza', 'malunggay', 'sitaw'], // March
            4 => ['tomato', 'eggplant', 'calabaza', 'malunggay', 'sitaw'], // April
            5 => ['corn', 'rice', 'munggo', 'sitaw', 'kalabasa'], // May
            6 => ['corn', 'rice', 'munggo', 'sitaw', 'kalabasa'], // June
            7 => ['rice', 'corn', 'eggplant', 'tomato', 'okra'], // July
            8 => ['rice', 'corn', 'eggplant', 'tomato', 'okra'], // August
            9 => ['rice', 'corn', 'sweet potato', 'cassava', 'okra'], // September
            10 => ['rice', 'corn', 'sweet potato', 'cassava', 'okra'], // October
            11 => ['rice', 'corn', 'sweet potato', 'cassava', 'okra'], // November
            12 => ['rice', 'corn', 'sweet potato', 'cassava', 'okra'] // December
        ];

        // Fruits by season
        $seasonalFruits = [
            1 => ['calamansi', 'banana', 'pineapple'], // January
            2 => ['calamansi', 'banana', 'pineapple'], // February
            3 => ['mango', 'calamansi', 'banana'], // March
            4 => ['mango', 'calamansi', 'banana'], // April
            5 => ['mango', 'calamansi', 'banana'], // May
            6 => ['mango', 'calamansi', 'banana'], // June
            7 => ['mango', 'calamansi', 'banana'], // July
            8 => ['mango', 'calamansi', 'banana'], // August
            9 => ['calamansi', 'banana', 'pineapple'], // September
            10 => ['calamansi', 'banana', 'pineapple'], // October
            11 => ['calamansi', 'banana', 'pineapple'], // November
            12 => ['calamansi', 'banana', 'pineapple'] // December
        ];

        $currentSeasonalProducts = $seasonalProducts[$currentMonth] ?? [];
        $currentSeasonalFruits = $seasonalFruits[$currentMonth] ?? [];

        // Build query to get products that match seasonal names
        $builder = $this->select('products.*, users.name as farmer_name, users.cooperative')
                        ->join('users', 'users.id = products.farmer_id')
                        ->where('products.status', 'available')
                        ->where('products.stock_quantity >', 0)
                        ->groupStart();

        $seasonalNames = array_merge($currentSeasonalProducts, $currentSeasonalFruits);
        $first = true;
        foreach ($seasonalNames as $productName) {
            if (!$first) {
                $builder->orLike('products.name', $productName);
            } else {
                $builder->like('products.name', $productName);
                $first = false;
            }
        }

        $builder->groupEnd()
                ->orderBy('products.created_at', 'DESC')
                ->limit($limit);

        return $builder->get()->getResultArray();
    }

    /**
     * Get top performing products by sales for charts
     */
    public function getTopProductsChartData($farmerId, $limit = 10)
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT
                p.name as product_name,
                SUM(o.total_price) as total_revenue,
                SUM(o.quantity) as total_quantity_sold,
                COUNT(o.id) as orders_count
            FROM products p
            LEFT JOIN orders o ON p.id = o.product_id AND o.status = 'completed'
            WHERE p.farmer_id = ?
            GROUP BY p.id, p.name
            HAVING total_revenue > 0
            ORDER BY total_revenue DESC
            LIMIT ?
        ", [$farmerId, $limit]);

        return $query->getResultArray();
    }
}
