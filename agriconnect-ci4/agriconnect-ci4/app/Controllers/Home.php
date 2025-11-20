<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        $data = [
            'title' => 'AgriConnect - Nasugbu Agricultural Marketplace',
            'featured_products' => $productModel->getAvailableProducts(8)
        ];

        return view('landing', $data);
    }

    /**
     * Dashboard - redirects to appropriate dashboard based on user role
     */
    public function dashboard()
    {
        $userRole = session()->get('user_role');

        switch ($userRole) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'farmer':
                return redirect()->to('/farmer/dashboard');
            case 'buyer':
                // Buyers don't have a specific dashboard, redirect to orders
                return redirect()->to('/buyer/orders');
            default:
                return redirect()->to('/')->with('error', 'Invalid user role.');
        }
    }
}
