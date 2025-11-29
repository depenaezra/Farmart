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
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $data = [
            'title' => 'Checkout',
            'cart' => $cart,
            'subtotal' => $subtotal
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
                        'notes' => $notes
                    ];
                    
                    $orderId = $this->orderModel->createOrder($orderData);
                    
                    if ($orderId) {
                        $orderIds[] = $orderId;
                        
                        // Reduce stock
                        $this->productModel->reduceStock($item['product_id'], $item['quantity']);
                    }
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->back()
                    ->with('error', 'Failed to place order. Please try again.');
            }
            
            // Clear cart
            session()->remove('cart');
            
            // Store order IDs in session for success page
            session()->setFlashdata('order_ids', $orderIds);
            
            return redirect()->to('/checkout/success')
                ->with('success', 'Orders placed successfully!');
                
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
