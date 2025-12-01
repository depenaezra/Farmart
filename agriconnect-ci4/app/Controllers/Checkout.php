<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Models\CartModel;

class Checkout extends BaseController
{
    protected $orderModel;
    protected $productModel;
    protected $userModel;
    protected $cartModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->cartModel = new CartModel();
    }
    
    /**
     * Show checkout page
     */
    public function index()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Please login to checkout');
        }

        // Check if this is a success redirect (no cart needed)
        $isSuccessRedirect = session()->has('checkout_success') && session()->get('checkout_success');

        $cart = $this->cartModel->getUserCart($userId);

        if (empty($cart) && !$isSuccessRedirect) {
            return redirect()->to('/cart')
                ->with('error', 'No items selected for checkout');
        }

        // Calculate subtotal for selected items
        $subtotal = 0;
        if (!$isSuccessRedirect) {
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
        }

        // Get current user data for pre-filling delivery info
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Checkout',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'user' => $user,
            'is_direct_checkout' => false,
            'is_success_redirect' => $isSuccessRedirect
        ];

        return view('checkout/index', $data);
    }

    /**
     * Direct checkout for Buy Now functionality
     */
    public function directCheckout()
    {
        // Check if user is authenticated and not admin
        $userId = session()->get('user_id');
        if (!$userId || session()->get('user_role') === 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Please login to checkout');
        }

        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity') ?? 1;

        // Validate product
        $product = $this->productModel->getProductWithFarmer($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        // Validate quantity
        if ($quantity < 1 || $quantity > $product['stock_quantity']) {
            return redirect()->back()->with('error', 'Invalid quantity');
        }

        // Create direct checkout cart with just this product
        $directCart = [
            'direct_' . $productId => [
                'id' => 'direct_' . $productId,
                'product_id' => $productId,
                'product_name' => $product['name'],
                'price' => $product['price'],
                'unit' => $product['unit'],
                'quantity' => $quantity,
                'farmer_id' => $product['farmer_id'],
                'farmer_name' => $product['farmer_name'],
                'image_url' => $product['image_url'],
                'location' => $product['location']
            ]
        ];

        $subtotal = $product['price'] * $quantity;

        // Get current user data for pre-filling delivery info
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Checkout',
            'cart' => $directCart,
            'subtotal' => $subtotal,
            'user' => $user,
            'is_direct_checkout' => true
        ];

        return view('checkout/index', $data);
    }
    
    /**
     * Process checkout and create orders
     */
    public function placeOrder()
    {
        $userId = session()->get('user_id');
        $isDirectCheckout = $this->request->getPost('is_direct_checkout') === '1';

        // Debug logging
        log_message('debug', 'PlaceOrder called - UserID: ' . $userId . ', User Role: ' . session()->get('user_role') . ', DirectCheckout: ' . ($isDirectCheckout ? 'Yes' : 'No'));

        // Get cart items from database
        if ($isDirectCheckout) {
            // For direct checkout, get cart from POST data
            $productId = $this->request->getPost('direct_product_id');
            $quantity = $this->request->getPost('direct_quantity');

            if (!$productId || !$quantity) {
                return redirect()->back()->with('error', 'Invalid checkout data')->withInput();
            }

            $product = $this->productModel->getProductWithFarmer($productId);
            if (!$product) {
                return redirect()->back()->with('error', 'Product not found')->withInput();
            }

            // Check stock again
            if ($quantity > $product['stock_quantity']) {
                return redirect()->back()->with('error', 'Not enough stock available')->withInput();
            }

            $cart = [
                'direct_' . $productId => [
                    'product_id' => $productId,
                    'product_name' => $product['name'],
                    'price' => $product['price'],
                    'unit' => $product['unit'],
                    'quantity' => $quantity,
                    'farmer_id' => $product['farmer_id'],
                    'farmer_name' => $product['farmer_name'],
                    'image_url' => $product['image_url'],
                    'location' => $product['location']
                ]
            ];
        } else {
            // For regular checkout, use all cart items (simplified for now)
            $cart = $this->cartModel->getUserCart($userId);
            log_message('debug', 'Using all cart items: ' . count($cart));

            if (empty($cart)) {
                return redirect()->to('/cart')
                    ->with('error', 'Your cart is empty');
            }
        }
        
        $validation = \Config\Services::validation();

        $rules = [
            'delivery_address' => 'required|min_length[10]',
            'contact_number' => 'required',
            'payment_method' => 'required|in_list[in_person,gcash]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $deliveryAddress = $this->request->getPost('delivery_address');
        $contactNumber = $this->request->getPost('contact_number');
        $paymentMethod = $this->request->getPost('payment_method');
        $notes = $this->request->getPost('notes');
        $buyerId = session()->get('user_id');
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        $orderIds = [];
        
        try {
            // Create separate order for each farmer
            $ordersByFarmer = [];
            foreach ($cart as $item) {
                $farmerId = $item['farmer_id'];
                if (!isset($ordersByFarmer[$farmerId])) {
                    $ordersByFarmer[$farmerId] = [];
                }
                $ordersByFarmer[$farmerId][] = $item;
            }
            
            // Create orders
            foreach ($ordersByFarmer as $farmerId => $items) {
                foreach ($items as $item) {
                    $orderData = [
                        'buyer_id' => $buyerId,
                        'farmer_id' => $farmerId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'],
                        'total_price' => $item['price'] * $item['quantity'],
                        'delivery_address' => $deliveryAddress . "\nContact: " . $contactNumber,
                        'payment_method' => $paymentMethod,
                        'notes' => $notes
                    ];

                    log_message('debug', 'Creating order with data: ' . json_encode($orderData));

                    $orderId = $this->orderModel->createOrder($orderData);

                    log_message('debug', 'Order creation result for product ' . $item['product_id'] . ': ' . ($orderId ? 'Success (ID: ' . $orderId . ')' : 'Failed'));

                    if ($orderId) {
                        $orderIds[] = $orderId;

                        // Reduce stock
                        $stockReduced = $this->productModel->reduceStock($item['product_id'], $item['quantity']);
                        log_message('debug', 'Stock reduction for product ' . $item['product_id'] . ': ' . ($stockReduced ? 'Success' : 'Failed'));
                    }
                }
            }
            
            $db->transComplete();

            log_message('debug', 'Transaction completed. Status: ' . ($db->transStatus() ? 'Success' : 'Failed') . ', Order IDs: ' . json_encode($orderIds));

            if ($db->transStatus() === false) {
                log_message('error', 'Transaction failed during order placement');
                return redirect()->back()
                    ->with('error', 'Failed to place order. Please try again.');
            }
            
            // Clear cart from database (only for regular checkout, not direct checkout)
            if (!$isDirectCheckout) {
                $this->cartModel->clearUserCart($userId);
            }

            // Set success flag for popup and stay on checkout page
            session()->set('checkout_success', true);
            session()->set('checkout_order_count', count($orderIds));

            // Return to checkout page to show success popup
            return redirect()->to('/checkout')->with('success', 'Order placed successfully!');
                
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->with('error', 'An error occurred while processing your order.');
        }
    }
    
    /**
     * Order success page
     */
    public function success()
    {
        $orderIds = session()->getFlashdata('order_ids');
        
        if (!$orderIds) {
            return redirect()->to('/marketplace');
        }
        
        $data = [
            'title' => 'Order Successful',
            'order_count' => count($orderIds)
        ];
        
        return view('checkout/success', $data);
    }
}
