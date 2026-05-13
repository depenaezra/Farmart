<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CartModel;

class Cart extends BaseController
{
    protected $productModel;
    protected $cartModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->cartModel = new CartModel();
    }
    
    /**
     * View cart
     */
    public function index()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Please login to view your cart');
        }

        $cart = $this->cartModel->getUserCart($userId);
        $subtotal = $this->cartModel->getCartTotal($userId);
        $itemCount = $this->cartModel->getCartCount($userId);

        $data = [
            'title' => 'Shopping Cart',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'item_count' => $itemCount
        ];

        return view('cart/index', $data);
    }
    
    /**
     * Add item to cart
     */
    public function add()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to add items to cart'
                ]);
            }
            return redirect()->to('/auth/login');
        }

        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity') ?? 1;

        // Get product details
        $product = $this->productModel->getProductWithFarmer($productId);

        if (!$product) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }
            return redirect()->back()->with('error', 'Product not found');
        }

        // Check stock
        if ($product['stock_quantity'] < $quantity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Not enough stock available'
                ]);
            }
            return redirect()->back()->with('error', 'Not enough stock available');
        }

        // Add to cart using model
        $result = $this->cartModel->addToCart($userId, $productId, $quantity);

        if (!$result) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add item to cart'
                ]);
            }
            return redirect()->back()->with('error', 'Failed to add item to cart');
        }

        $isExisting = $this->cartModel->isInCart($userId, $productId);
        $message = $isExisting ? 'Product quantity updated in cart!' : 'Product added to cart!';
        $cartCount = $this->cartModel->getCartCount($userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'cart_count' => $cartCount,
                'product' => [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'farmer_name' => $product['farmer_name']
                ]
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Buy now - add to cart and redirect to checkout
     */
    public function buyNow()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login');
        }

        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity') ?? 1;

        // Get product details
        $product = $this->productModel->getProductWithFarmer($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        // Check stock
        if ($product['stock_quantity'] < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available');
        }

        // Add to cart using model
        $result = $this->cartModel->addToCart($userId, $productId, $quantity);

        if (!$result) {
            return redirect()->back()->with('error', 'Failed to add item to cart');
        }

        return redirect()->to('/checkout')->with('success', 'Product added to cart! Proceeding to checkout...');
    }
    
    /**
     * Update cart item quantity
     */
    public function update($cartItemId)
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login first'
                ]);
            }
            return redirect()->to('/auth/login');
        }

        $quantity = $this->request->getPost('quantity');

        // Get current quantity for reversion
        $currentItem = $this->cartModel->where('id', $cartItemId)->where('user_id', $userId)->first();
        $oldQuantity = $currentItem ? $currentItem['quantity'] : 0;

        $result = $this->cartModel->updateQuantity($cartItemId, $quantity);

        if ($result) {
            $message = $quantity > 0 ? 'Quantity updated successfully' : 'Item removed from cart';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'old_quantity' => $oldQuantity
                ]);
            }
            return redirect()->to('/cart')->with('success', $message);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update quantity',
                'old_quantity' => $oldQuantity
            ]);
        }

        return redirect()->to('/cart')->with('error', 'Failed to update cart');
    }
    
    /**
     * Remove item from cart
     */
    public function remove($cartItemId)
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login first'
                ]);
            }
            return redirect()->to('/auth/login');
        }

        $result = $this->cartModel->removeFromCart($cartItemId, $userId);
        $cartCount = $this->cartModel->getCartCount($userId);

        if ($result) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'cart_count' => $cartCount
                ]);
            }
            return redirect()->to('/cart')->with('success', 'Item removed from cart');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Item not found in cart'
            ]);
        }

        return redirect()->to('/cart')->with('error', 'Item not found in cart');
    }
    
    /**
     * Clear entire cart
     */
    public function clear()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login first'
                ]);
            }
            return redirect()->to('/auth/login');
        }

        $result = $this->cartModel->clearUserCart($userId);

        if ($result) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Cart cleared successfully'
                ]);
            }
            return redirect()->to('/cart')->with('success', 'Cart cleared');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to clear cart'
            ]);
        }

        return redirect()->to('/cart')->with('error', 'Failed to clear cart');
    }
}
