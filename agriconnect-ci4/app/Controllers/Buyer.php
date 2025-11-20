<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Buyer extends BaseController
{
    protected $orderModel;
    
    public function __construct()
    {
        $this->orderModel = new OrderModel();
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
