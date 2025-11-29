<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;

class Buyer extends BaseController
{
    protected $productModel;
    protected $orderModel;
    
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
    }

    /**
     * Buyer seller dashboard (buyer as seller)
     */
    public function dashboard()
    {
        $userId = session()->get('user_id');

        $data = [
            'title'    => 'Seller Dashboard',
            'userName' => session()->get('user_name'),
            'statistics' => [
                // Reuse farmer statistics methods for any seller user
                'products' => $this->productModel->getFarmerStatistics($userId),
                'orders'   => $this->orderModel->getFarmerStatistics($userId),
            ],
            'recent_orders' => array_slice($this->orderModel->getOrdersByFarmer($userId, null), 0, 5),
        ];

        return view('buyer/dashboard_seller', $data);
    }

    /**
     * Seller products list (buyer as seller) - alias for inventory
     */
    public function products()
    {
        return $this->inventory();
    }

    /**
     * Seller inventory (buyer as seller)
     */
    public function inventory()
    {
        $userId = session()->get('user_id');

        $data = [
            'title'    => 'My Listings',
            'products' => $this->productModel->getProductsByFarmer($userId),
        ];

        return view('buyer/inventory', $data);
    }

    /**
     * Show add product form (buyer as seller)
     */
    public function addProduct()
    {
        $data = [
            'title'  => 'Add New Product',
            'errors' => session()->get('errors') ?? [],
        ];

        return view('buyer/add_product', $data);
    }

    /**
     * Process add product (buyer as seller)
     */
    public function addProductProcess()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'name'           => 'required|min_length[3]',
            'description'    => 'permit_empty',
            'price'          => 'required|decimal|greater_than[0]',
            'unit'           => 'required',
            'category'       => 'required|in_list[vegetables,fruits,grains,other]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'location'       => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Handle image upload
        $imageUrl = null;
        $image    = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $newName);
            $imageUrl = '/uploads/products/' . $newName;
        }

        $data = [
            // Use existing farmer_id column as generic seller id
            'farmer_id'      => session()->get('user_id'),
            'name'           => $this->request->getPost('name'),
            'description'    => $this->request->getPost('description'),
            'price'          => $this->request->getPost('price'),
            'unit'           => $this->request->getPost('unit'),
            'category'       => $this->request->getPost('category'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'location'       => $this->request->getPost('location'),
            'image_url'      => $imageUrl,
            'status'         => 'available',
        ];

        if ($this->productModel->save($data)) {
            return redirect()->to('/buyer/inventory')
                ->with('success', 'Product added successfully!');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to add product. Please try again.');
    }

    /**
     * Seller orders list (buyers acting as sellers)
     */
    public function sellerOrders()
    {
        $sellerId = session()->get('user_id');
        $status = $this->request->getGet('status');

        $data = [
            'title' => 'Sales Orders',
            'orders' => $this->orderModel->getOrdersByFarmer($sellerId, $status),
            'current_status' => $status
        ];

        return view('buyer/orders_seller', $data);
    }

    /**
     * Seller order detail
     */
    public function sellerOrderDetail($id)
    {
        $order = $this->orderModel->getOrderWithDetails($id);

        if (!$order || $order['farmer_id'] != session()->get('user_id')) {
            return redirect()->to('/buyer/sales/orders')
                ->with('error', 'Order not found.');
        }

        $data = [
            'title' => 'Sales Order #' . ($order['order_number'] ?? $id),
            'order' => $order
        ];

        return view('buyer/order_detail_seller', $data);
    }

    /**
     * Update seller order status
     */
    public function updateSellerOrderStatus($id)
    {
        $order = $this->orderModel->find($id);

        if (!$order || $order['farmer_id'] != session()->get('user_id')) {
            return redirect()->to('/buyer/sales/orders')
                ->with('error', 'Order not found.');
        }

        $newStatus = $this->request->getPost('status');

        if ($this->orderModel->updateStatus($id, $newStatus)) {
            return redirect()->back()
                ->with('success', 'Order status updated successfully!');
        }

        return redirect()->back()
            ->with('error', 'Failed to update order status.');
    }
    
    /**
     * Buyer orders list
     */
    public function orders()
    {
        $buyerId = session()->get('user_id');
        $status = $this->request->getGet('status');
        
        $orders = $this->orderModel->getOrdersByBuyer($buyerId, $status);
        
        $data = [
            'title' => 'My Orders',
            'orders' => $orders,
            'current_status' => $status,
            'statistics' => $this->orderModel->getBuyerStatistics($buyerId)
        ];
        
        return view('buyer/orders', $data);
    }
    
    /**
     * Order detail
     */
    public function orderDetail($id)
    {
        $order = $this->orderModel->getOrderWithDetails($id);
        
        // Verify order belongs to current buyer
        if (!$order || $order['buyer_id'] != session()->get('user_id')) {
            return redirect()->to('/buyer/orders')
                ->with('error', 'Order not found.');
        }
        
        $data = [
            'title' => 'Order #' . $order['order_number'],
            'order' => $order
        ];
        
        return view('buyer/order_detail', $data);
    }
    
    /**
     * Cancel order
     */
    public function cancelOrder($id)
    {
        $order = $this->orderModel->find($id);
        
        // Verify order belongs to current buyer
        if (!$order || $order['buyer_id'] != session()->get('user_id')) {
            return redirect()->to('/buyer/orders')
                ->with('error', 'Order not found.');
        }
        
        // Only allow cancelling pending orders
        if ($order['status'] !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending orders can be cancelled.');
        }
        
        if ($this->orderModel->updateStatus($id, 'cancelled')) {
            // Restore stock
            $this->productModel = new \App\Models\ProductModel();
            $this->productModel->updateStock($order['product_id'], $order['quantity']);
            
            return redirect()->to('/buyer/orders')
                ->with('success', 'Order cancelled successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to cancel order.');
        }
    }
}
