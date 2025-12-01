<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Cart extends BaseController
{
    protected $productModel;
    
    public function __construct()
    {
        $this->productModel = new ProductModel();
    }
    
    /**
     * View cart
     */
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $data = [
            'title' => 'Shopping Cart',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'item_count' => count($cart)
        ];
        
        return view('cart/index', $data);
    }
    
    /**
     * Add item to cart
     */
    public function add()
    {
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

        // Get current cart
        $cart = session()->get('cart') ?? [];

        // Check if product already in cart
        $existingKey = null;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey) {
            // Update quantity
            $cart[$existingKey]['quantity'] += $quantity;
        } else {
            // Add new item
            $cartItemId = 'cart_' . time() . '_' . $productId;
            $cart[$cartItemId] = [
                'id' => $cartItemId,
                'product_id' => $productId,
                'product_name' => $product['name'],
                'price' => $product['price'],
                'unit' => $product['unit'],
                'quantity' => $quantity,
                'farmer_id' => $product['farmer_id'],
                'farmer_name' => $product['farmer_name'],
                'image_url' => $product['image_url'],
                'location' => $product['location']
            ];
        }

        session()->set('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    /**
     * Buy now - add to cart and redirect to checkout
     */
    public function buyNow()
    {
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

        // Get current cart
        $cart = session()->get('cart') ?? [];

        // Check if product already in cart
        $existingKey = null;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey) {
            // Update quantity
            $cart[$existingKey]['quantity'] += $quantity;
        } else {
            // Add new item
            $cartItemId = 'cart_' . time() . '_' . $productId;
            $cart[$cartItemId] = [
                'id' => $cartItemId,
                'product_id' => $productId,
                'product_name' => $product['name'],
                'price' => $product['price'],
                'unit' => $product['unit'],
                'quantity' => $quantity,
                'farmer_id' => $product['farmer_id'],
                'farmer_name' => $product['farmer_name'],
                'image_url' => $product['image_url'],
                'location' => $product['location']
            ];
        }

        session()->set('cart', $cart);

        return redirect()->to('/checkout')->with('success', 'Product added to cart! Proceeding to checkout...');
    }
    
    /**
     * Update cart item quantity
     */
    public function update($cartItemId)
    {
        $cart = session()->get('cart') ?? [];
        $quantity = $this->request->getPost('quantity');
        
        if (isset($cart[$cartItemId])) {
            if ($quantity > 0) {
                $cart[$cartItemId]['quantity'] = (int)$quantity;
                session()->set('cart', $cart);
                return redirect()->to('/cart')->with('success', 'Cart updated');
            } else {
                unset($cart[$cartItemId]);
                session()->set('cart', $cart);
                return redirect()->to('/cart')->with('success', 'Item removed');
            }
        }
        
        return redirect()->to('/cart');
    }
    
    /**
     * Remove item from cart
     */
    public function remove($cartItemId)
    {
        $cart = session()->get('cart') ?? [];
        
        if (isset($cart[$cartItemId])) {
            unset($cart[$cartItemId]);
            session()->set('cart', $cart);
            return redirect()->to('/cart')->with('success', 'Item removed from cart');
        }
        
        return redirect()->to('/cart');
    }
    
    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->remove('cart');
        return redirect()->to('/cart')->with('success', 'Cart cleared');
    }
}
