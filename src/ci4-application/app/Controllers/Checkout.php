<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;

class Checkout extends BaseController
{
    protected $orderModel;
    protected $productModel;
    
    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
    }
    
    /**
     * Show checkout page
     */
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        
        if (empty($cart)) {
            return redirect()->to('/marketplace')
                ->with('error', 'Your cart is empty');
        }
        
        // Get selected items from POST request (from cart page)
        $selectedItemIds = $this->request->getPost('selected_items');
        $itemsToCheckout = [];
        
        if ($selectedItemIds && is_array($selectedItemIds) && count($selectedItemIds) > 0) {
            // Filter cart to only include selected items
            foreach ($selectedItemIds as $itemId) {
                if (isset($cart[$itemId])) {
                    $itemsToCheckout[$itemId] = $cart[$itemId];
                }
            }
        } else {
            // If no selection, use all cart items
            $itemsToCheckout = $cart;
        }
        
        if (empty($itemsToCheckout)) {
            return redirect()->to('/cart')
                ->with('error', 'No items selected');
        }
        
        // Calculate totals for selected items
        $subtotal = 0;
        foreach ($itemsToCheckout as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $data = [
            'title' => 'Checkout',
            'cart' => $itemsToCheckout,
            'subtotal' => $subtotal,
            'selected_items' => array_keys($itemsToCheckout)
        ];
        
        return view('checkout/index', $data);
    }
    
    /**
     * Process checkout and create orders
     */
    public function placeOrder()
    {
        $cart = session()->get('cart') ?? [];
        
        if (empty($cart)) {
            return redirect()->to('/marketplace')
                ->with('error', 'Your cart is empty');
        }
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'delivery_address' => 'required|min_length[10]',
            'contact_number' => 'required'
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
        
        // Determine which items to process
        $itemsToProcess = [];
        
        // Check if direct checkout (direct from product page)
        $isDirectCheckout = $this->request->getPost('is_direct_checkout');
        if ($isDirectCheckout) {
            // For direct checkout, process all cart items (which should be just one product)
            $itemsToProcess = $cart;
        } else {
            // For regular checkout, check if specific items are selected
            $selectedItemIds = $this->request->getPost('selected_items');
            
            if ($selectedItemIds && is_array($selectedItemIds) && count($selectedItemIds) > 0) {
                // Process only selected items
                foreach ($selectedItemIds as $itemId) {
                    if (isset($cart[$itemId])) {
                        $itemsToProcess[$itemId] = $cart[$itemId];
                    }
                }
            } else {
                // If no selection, process all items
                $itemsToProcess = $cart;
            }
        }
        
        if (empty($itemsToProcess)) {
            return redirect()->to('/cart')
                ->with('error', 'No items selected for checkout');
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        $orderIds = [];
        $processedItemIds = [];
        
        try {
            // Create separate order for each item (or group by farmer if needed)
            foreach ($itemsToProcess as $itemId => $item) {
                $orderData = [
                    'buyer_id' => $buyerId,
                    'farmer_id' => $item['farmer_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'delivery_address' => $deliveryAddress . "\nContact: " . $contactNumber,
                    'payment_method' => $paymentMethod,
                    'notes' => $notes
                ];
                
                $orderId = $this->orderModel->createOrder($orderData);
                
                if ($orderId) {
                    $orderIds[] = $orderId;
                    $processedItemIds[] = $itemId;
                    
                    // Reduce stock
                    $this->productModel->reduceStock($item['product_id'], $item['quantity']);
                } else {
                    throw new \Exception('Failed to create order for product: ' . $item['product_name']);
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->back()
                    ->with('error', 'Failed to place order. Please try again.');
            }
            
            // Remove processed items from cart
            foreach ($processedItemIds as $itemId) {
                unset($cart[$itemId]);
            }
            
            // Update session cart (or remove if empty)
            if (empty($cart)) {
                session()->remove('cart');
            } else {
                session()->set('cart', $cart);
            }
            
            // Store order IDs in session for success page
            session()->setFlashdata('order_ids', $orderIds);
            
            return redirect()->to('/checkout/success')
                ->with('success', 'Orders placed successfully!');
                
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Checkout error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while processing your order: ' . $e->getMessage());
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
