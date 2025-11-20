<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;

class Farmer extends BaseController
{
    protected $productModel;
    protected $orderModel;
    
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
    }
    
    /**
     * Farmer Dashboard
     */
    public function dashboard()
    {
        $farmerId = session()->get('user_id');
        
        $data = [
            'title' => 'Farmer Dashboard',
            'userName' => session()->get('user_name'),
            'statistics' => [
                'products' => $this->productModel->getFarmerStatistics($farmerId),
                'orders' => $this->orderModel->getFarmerStatistics($farmerId)
            ],
            'recent_orders' => $this->orderModel->getOrdersByFarmer($farmerId, null)->slice(0, 5)
        ];
        
        return view('farmer/dashboard', $data);
    }
    
    /**
     * Show add product form
     */
    public function addProduct()
    {
        $data = [
            'title' => 'Add New Product'
        ];
        
        return view('farmer/add_product', $data);
    }
    
    /**
     * Process add product
     */
    public function addProductProcess()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'description' => 'permit_empty',
            'price' => 'required|decimal|greater_than[0]',
            'unit' => 'required',
            'category' => 'required|in_list[vegetables,fruits,grains,other]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'location' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        // Handle image upload
        $imageUrl = null;
        $image = $this->request->getFile('image');
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $newName);
            $imageUrl = '/uploads/products/' . $newName;
        }
        
        $data = [
            'farmer_id' => session()->get('user_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'unit' => $this->request->getPost('unit'),
            'category' => $this->request->getPost('category'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'location' => $this->request->getPost('location'),
            'image_url' => $imageUrl,
            'status' => 'available'
        ];
        
        if ($this->productModel->save($data)) {
            return redirect()->to('/farmer/inventory')
                ->with('success', 'Product added successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add product. Please try again.');
        }
    }
    
    /**
     * Show inventory/products list
     */
    public function inventory()
    {
        $farmerId = session()->get('user_id');
        
        $data = [
            'title' => 'My Inventory',
            'products' => $this->productModel->getProductsByFarmer($farmerId)
        ];
        
        return view('farmer/inventory', $data);
    }
    
    /**
     * Show edit product form
     */
    public function editProduct($id)
    {
        $product = $this->productModel->find($id);
        
        // Check if product belongs to current farmer
        if (!$product || $product['farmer_id'] != session()->get('user_id')) {
            return redirect()->to('/farmer/inventory')
                ->with('error', 'Product not found.');
        }
        
        $data = [
            'title' => 'Edit Product',
            'product' => $product
        ];
        
        return view('farmer/edit_product', $data);
    }
    
    /**
     * Process edit product
     */
    public function editProductProcess($id)
    {
        $product = $this->productModel->find($id);
        
        // Check if product belongs to current farmer
        if (!$product || $product['farmer_id'] != session()->get('user_id')) {
            return redirect()->to('/farmer/inventory')
                ->with('error', 'Product not found.');
        }
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'price' => 'required|decimal|greater_than[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'unit' => $this->request->getPost('unit'),
            'category' => $this->request->getPost('category'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'location' => $this->request->getPost('location')
        ];
        
        // Handle new image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $newName);
            $data['image_url'] = '/uploads/products/' . $newName;
        }
        
        // Update status based on stock
        $data['status'] = $data['stock_quantity'] > 0 ? 'available' : 'out-of-stock';
        
        if ($this->productModel->update($id, $data)) {
            return redirect()->to('/farmer/inventory')
                ->with('success', 'Product updated successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product.');
        }
    }
    
    /**
     * Delete product
     */
    public function deleteProduct($id)
    {
        $product = $this->productModel->find($id);
        
        // Check if product belongs to current farmer
        if (!$product || $product['farmer_id'] != session()->get('user_id')) {
            return redirect()->to('/farmer/inventory')
                ->with('error', 'Product not found.');
        }
        
        if ($this->productModel->delete($id)) {
            return redirect()->to('/farmer/inventory')
                ->with('success', 'Product deleted successfully!');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete product.');
        }
    }
    
    /**
     * Update stock quantity
     */
    public function updateStock($id)
    {
        $product = $this->productModel->find($id);
        
        // Check if product belongs to current farmer
        if (!$product || $product['farmer_id'] != session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
        }
        
        $quantity = $this->request->getPost('quantity');
        
        if ($this->productModel->updateStock($id, $quantity)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Stock updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update stock']);
        }
    }
    
    /**
     * Show farmer orders
     */
    public function orders()
    {
        $farmerId = session()->get('user_id');
        $status = $this->request->getGet('status');
        
        $data = [
            'title' => 'My Orders',
            'orders' => $this->orderModel->getOrdersByFarmer($farmerId, $status),
            'current_status' => $status
        ];
        
        return view('farmer/orders', $data);
    }
    
    /**
     * Show order details
     */
    public function orderDetail($id)
    {
        $order = $this->orderModel->getOrderWithDetails($id);
        
        // Check if order belongs to current farmer
        if (!$order || $order['farmer_id'] != session()->get('user_id')) {
            return redirect()->to('/farmer/orders')
                ->with('error', 'Order not found.');
        }
        
        $data = [
            'title' => 'Order Details',
            'order' => $order
        ];
        
        return view('farmer/order_detail', $data);
    }
    
    /**
     * Update order status
     */
    public function updateOrderStatus($id)
    {
        $order = $this->orderModel->find($id);
        
        // Check if order belongs to current farmer
        if (!$order || $order['farmer_id'] != session()->get('user_id')) {
            return redirect()->to('/farmer/orders')
                ->with('error', 'Order not found.');
        }
        
        $newStatus = $this->request->getPost('status');
        
        if ($this->orderModel->updateStatus($id, $newStatus)) {
            return redirect()->back()
                ->with('success', 'Order status updated successfully!');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update order status.');
        }
    }
}
