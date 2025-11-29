<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Home extends BaseController
{
    public function index()
    {
        // Redirect logged-in users to marketplace
        if (session()->has('logged_in') && session()->get('logged_in')) {
            return redirect()->to('/marketplace');
        }

        $data = [
            'title' => 'Farmart - Nasugbu Agricultural Marketplace',
            'featured_products' => []
        ];

        try {
            $productModel = new ProductModel();
            $data['featured_products'] = $productModel->getAvailableProducts(8);
        } catch (DatabaseException $e) {
            // Database connection failed - continue with empty products array
            log_message('error', 'Database connection failed in Home controller: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Any other error - continue with empty products array
            log_message('error', 'Error loading products in Home controller: ' . $e->getMessage());
        }

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
                // Buyers now have a seller dashboard where they can also list products
                return redirect()->to('/buyer/dashboard');
            default:
                return redirect()->to('/')->with('error', 'Invalid user role.');
        }
    }
}
