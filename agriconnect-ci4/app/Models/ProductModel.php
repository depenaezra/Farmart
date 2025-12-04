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
        'status',
        'harvest_date',
        'shelf_life_days',
        'spoiled_date',
        'original_price_when_spoiled'
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
        'location' => 'permit_empty|max_length[255]',
        'harvest_date' => 'permit_empty|valid_date[Y-m-d]',
        'shelf_life_days' => 'permit_empty|integer|greater_than[0]'
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

    /**
     * Check if a product category is perishable (subject to spoilage)
     */
    private function isPerishableCategory($category)
    {
        $nonPerishableCategories = ['grains'];
        return !in_array($category, $nonPerishableCategories);
    }

    /**
     * Calculate expected spoilage date for a product
     */
    public function calculateSpoilageDate($product)
    {
        // Skip spoilage calculation for non-perishable categories
        if (!$this->isPerishableCategory($product['category'])) {
            return null;
        }

        if (empty($product['harvest_date'])) {
            return null;
        }

        $shelfLifeDays = $product['shelf_life_days'] ?? $this->getDefaultShelfLife($product['category']);

        if (!$shelfLifeDays) {
            return null;
        }

        $harvestDate = new \DateTime($product['harvest_date']);
        $harvestDate->modify("+{$shelfLifeDays} days");

        return $harvestDate->format('Y-m-d');
    }

    /**
     * Get default shelf life in days based on product category
     */
    private function getDefaultShelfLife($category)
    {
        $defaultShelfLife = [
            'vegetables' => 7,  // Most vegetables last 5-10 days
            'fruits' => 10,     // Most fruits last 7-14 days
            'grains' => 365,    // Grains can last a year if stored properly
            'other' => 14       // Default 2 weeks
        ];

        return $defaultShelfLife[$category] ?? 14;
    }

    /**
     * Check if product is nearing spoilage (within specified days)
     */
    public function isNearingSpoilage($product, $warningDays = 3)
    {
        $spoilageDate = $this->calculateSpoilageDate($product);

        if (!$spoilageDate) {
            return false;
        }

        $today = new \DateTime();
        $spoilageDateTime = new \DateTime($spoilageDate);
        $daysUntilSpoilage = $today->diff($spoilageDateTime)->days;

        // If spoilage date has passed, it's already spoiled
        if ($spoilageDateTime < $today) {
            return true;
        }

        return $daysUntilSpoilage <= $warningDays;
    }

    /**
     * Get spoilage status for a product
     */
    public function getSpoilageStatus($product)
    {
        $spoilageDate = $this->calculateSpoilageDate($product);

        if (!$spoilageDate) {
            return 'unknown';
        }

        $today = new \DateTime();
        $spoilageDateTime = new \DateTime($spoilageDate);

        if ($spoilageDateTime < $today) {
            // Product is spoiled - track when it became spoiled
            $this->markProductAsSpoiled($product);
            return 'spoiled';
        }

        $daysUntilSpoilage = $today->diff($spoilageDateTime)->days;

        if ($daysUntilSpoilage <= 1) {
            return 'critical'; // 1 day or less
        } elseif ($daysUntilSpoilage <= 3) {
            return 'warning'; // 2-3 days
        } else {
            return 'good'; // More than 3 days
        }
    }

    /**
     * Mark a product as spoiled and track the date/price
     */
    private function markProductAsSpoiled($product)
    {
        // Only update if not already marked as spoiled
        if (empty($product['spoiled_date'])) {
            $this->update($product['id'], [
                'spoiled_date' => date('Y-m-d H:i:s'),
                'original_price_when_spoiled' => $product['price']
            ]);
        }
    }

    /**
     * Check if a product needs spoilage attention (hasn't been properly discounted)
     */
    private function productNeedsSpoilageAttention($product)
    {
        $suggestion = $this->getPriceSuggestion($product);

        // If no suggestion is needed, product doesn't need attention
        if (!$suggestion) {
            return false;
        }

        // If current price is at or below suggested price, no attention needed
        return $product['price'] > $suggestion['suggested_price'];
    }

    /**
     * Get realistic price reduction suggestion based on product type and spoilage status
     */
    public function getPriceSuggestion($product)
    {
        $status = $this->getSpoilageStatus($product);

        if ($status === 'unknown' || $status === 'good') {
            return null;
        }

        $originalPrice = $product['price'];
        $category = $product['category'];
        $name = strtolower($product['name']);

        // Base discount percentages by spoilage status
        $baseDiscounts = [
            'warning' => 15,    // Early warning: 15% base
            'critical' => 35,   // Critical: 35% base
            'spoiled' => 55    // Spoiled: 55% base
        ];

        $reductionPercent = $baseDiscounts[$status] ?? 0;

        // Adjust based on product category and type
        $categoryAdjustments = [
            'vegetables' => $this->getVegetableDiscountAdjustment($name, $status),
            'fruits' => $this->getFruitDiscountAdjustment($name, $status),
            'other' => 5 // Small adjustment for other items
        ];

        $reductionPercent += $categoryAdjustments[$category] ?? 0;

        // Price-based adjustments (more expensive items get slightly lower percentage discounts)
        if ($originalPrice > 200) {
            $reductionPercent -= 5; // Luxury items: reduce discount by 5%
        } elseif ($originalPrice > 100) {
            $reductionPercent -= 3; // Premium items: reduce discount by 3%
        } elseif ($originalPrice < 20) {
            $reductionPercent += 3; // Cheap items: increase discount by 3%
        }

        // Ensure reasonable bounds
        $reductionPercent = max(10, min(80, $reductionPercent));

        $suggestedPrice = $originalPrice * (1 - $reductionPercent / 100);

        return [
            'original_price' => $originalPrice,
            'suggested_price' => round($suggestedPrice, 2),
            'reduction_percent' => $reductionPercent,
            'status' => $status,
            'reason' => $this->getDiscountReason($category, $name, $status)
        ];
    }

    /**
     * Get discount adjustment for vegetables based on type
     */
    private function getVegetableDiscountAdjustment($name, $status)
    {
        // Leafy greens spoil very quickly
        $quickSpoil = ['lettuce', 'spinach', 'kale', 'cabbage', 'celery'];
        // Root vegetables last longer
        $longLasting = ['carrot', 'potato', 'onion', 'garlic', 'beet'];

        if ($this->containsKeywords($name, $quickSpoil)) {
            return $status === 'warning' ? 10 : 5; // Higher discount for quick-spoil items
        } elseif ($this->containsKeywords($name, $longLasting)) {
            return $status === 'warning' ? -5 : -3; // Lower discount for longer-lasting items
        }

        return 0; // Standard adjustment
    }

    /**
     * Get discount adjustment for fruits based on type
     */
    private function getFruitDiscountAdjustment($name, $status)
    {
        // High-value tropical fruits
        $premium = ['mango', 'pineapple', 'durian', 'rambutan'];
        // Common fruits
        $common = ['banana', 'apple', 'orange', 'calamansi'];

        if ($this->containsKeywords($name, $premium)) {
            return $status === 'warning' ? -8 : -5; // Lower discount for premium fruits
        } elseif ($this->containsKeywords($name, $common)) {
            return $status === 'warning' ? 3 : 0; // Slightly higher for common fruits
        }

        return 0; // Standard adjustment
    }

    /**
     * Check if product name contains any of the keywords
     */
    private function containsKeywords($name, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (strpos($name, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get human-readable reason for the discount suggestion
     */
    private function getDiscountReason($category, $name, $status)
    {
        $statusText = [
            'warning' => 'nearing expiration',
            'critical' => 'expiring soon',
            'spoiled' => 'already spoiled'
        ];

        $categoryText = ucfirst($category);
        $statusReason = $statusText[$status];

        // Add specific product insights
        $insights = [];
        if (strpos($name, 'lettuce') !== false || strpos($name, 'spinach') !== false) {
            $insights[] = 'leafy greens spoil quickly';
        }
        if (strpos($name, 'mango') !== false || strpos($name, 'pineapple') !== false) {
            $insights[] = 'high-value tropical fruit';
        }
        if (strpos($name, 'carrot') !== false || strpos($name, 'potato') !== false) {
            $insights[] = 'root vegetables last longer';
        }

        $insightText = !empty($insights) ? ' (' . implode(', ', $insights) . ')' : '';

        return "{$categoryText} {$statusReason}{$insightText}";
    }

    /**
     * Get products that need spoilage attention for a farmer
     * Returns products in different alert categories
     */
    public function getSpoilageAlerts($farmerId)
    {
        $products = $this->getProductsByFarmer($farmerId);

        $alerts = [
            'early_warning' => [], // 5-7 days before spoilage
            'nearing_spoilage' => [], // 1-3 days before spoilage
            'spoiled' => [] // Already spoiled
        ];

        foreach ($products as $product) {
            // Skip non-perishable categories
            if (!$this->isPerishableCategory($product['category'])) {
                continue;
            }

            $spoilageDate = $this->calculateSpoilageDate($product);
            if (!$spoilageDate) {
                continue;
            }

            $today = new \DateTime();
            $spoilageDateTime = new \DateTime($spoilageDate);
            $daysUntilSpoilage = $today->diff($spoilageDateTime)->days;

            $product['spoilage_status'] = $this->getSpoilageStatus($product);
            $product['spoilage_date'] = $spoilageDate;
            $product['price_suggestion'] = $this->getPriceSuggestion($product);
            $product['days_until_spoilage'] = $spoilageDateTime < $today ? -$daysUntilSpoilage : $daysUntilSpoilage;

            // Only include products that need attention (haven't been properly discounted)
            $needsAttention = $this->productNeedsSpoilageAttention($product);

            if ($needsAttention) {
                if ($spoilageDateTime < $today) {
                    // Already spoiled
                    $alerts['spoiled'][] = $product;
                } elseif ($daysUntilSpoilage <= 3) {
                    // Critical - 3 days or less
                    $alerts['nearing_spoilage'][] = $product;
                } elseif ($daysUntilSpoilage <= 7) {
                    // Early warning - 4-7 days
                    $alerts['early_warning'][] = $product;
                }
            }
        }

        return $alerts;
    }

    /**
     * Get products nearing spoilage for a farmer (backward compatibility)
     */
    public function getNearingSpoilageProducts($farmerId, $warningDays = 3)
    {
        $alerts = $this->getSpoilageAlerts($farmerId);
        return array_merge($alerts['nearing_spoilage'], $alerts['spoiled']);
    }

    /**
     * Automatically delete spoiled products that haven't been appropriately discounted after grace period
     * This method should be called by a cron job or scheduled task
     */
    public function cleanupSpoiledProducts($graceDays = 5)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$graceDays} days"));

        // Find spoiled products that are past the grace period
        $spoiledProducts = $this->where('spoiled_date IS NOT NULL')
                                ->where('spoiled_date <', $cutoffDate)
                                ->findAll();

        $deletedCount = 0;
        foreach ($spoiledProducts as $product) {
            // Check if the product has been appropriately discounted
            $suggestion = $this->getPriceSuggestion($product);

            // If there's no suggestion needed (price is already appropriate) or price is at/below suggestion, don't delete
            if (!$suggestion || $product['price'] <= $suggestion['suggested_price']) {
                continue;
            }

            // Log the deletion (you might want to create a separate log table)
            log_message('info', "Automatically deleting spoiled product ID {$product['id']} ({$product['name']}) - insufficient price adjustment within {$graceDays} days");

            // Delete the product
            $this->delete($product['id']);
            $deletedCount++;
        }

        return $deletedCount;
    }

    /**
     * Get spoiled products for admin view
     */
    public function getSpoiledProductsForAdmin()
    {
        return $this->select('products.*, users.name as farmer_name, users.email as farmer_email')
                    ->join('users', 'users.id = products.farmer_id')
                    ->where('products.spoiled_date IS NOT NULL')
                    ->orderBy('products.spoiled_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get recently deleted spoiled products (if you implement soft deletes)
     * For now, this returns products that should have been deleted but weren't
     */
    public function getRecentlyDeletedSpoiledProducts($days = 30)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        $products = $this->select('products.*, users.name as farmer_name, users.email as farmer_email')
                         ->join('users', 'users.id = products.farmer_id')
                         ->where('products.spoiled_date IS NOT NULL')
                         ->where('products.spoiled_date <', $cutoffDate)
                         ->orderBy('products.spoiled_date', 'DESC')
                         ->findAll();

        // Filter to only include products that still need attention (haven't been properly discounted)
        return array_filter($products, function($product) {
            return $this->productNeedsSpoilageAttention($product);
        });
    }
}
