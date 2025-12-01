<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'product_id',
        'quantity'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'product_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]'
    ];

    /**
     * Add item to cart or update quantity if exists
     */
    public function addToCart($userId, $productId, $quantity)
    {
        // Check if item already exists in cart
        $existingItem = $this->where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            return $this->update($existingItem['id'], ['quantity' => $newQuantity]);
        } else {
            // Add new item
            return $this->insert([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * Get user's cart with product details
     */
    public function getUserCart($userId)
    {
        return $this->select('cart.*, products.name as product_name, products.price, products.unit, products.image_url, users.name as farmer_name, users.location')
                    ->join('products', 'products.id = cart.product_id')
                    ->join('users', 'users.id = products.farmer_id')
                    ->where('cart.user_id', $userId)
                    ->findAll();
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity($cartId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->delete($cartId);
        }
        return $this->update($cartId, ['quantity' => $quantity]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($cartId, $userId)
    {
        return $this->where('id', $cartId)
                    ->where('user_id', $userId)
                    ->delete();
    }

    /**
     * Clear user's entire cart
     */
    public function clearUserCart($userId)
    {
        return $this->where('user_id', $userId)->delete();
    }

    /**
     * Get cart item count for user
     */
    public function getCartCount($userId)
    {
        return $this->where('user_id', $userId)->countAllResults();
    }

    /**
     * Get cart total for user
     */
    public function getCartTotal($userId)
    {
        $cartItems = $this->getUserCart($userId);
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Check if product is in user's cart
     */
    public function isInCart($userId, $productId)
    {
        return $this->where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->countAllResults() > 0;
    }
}