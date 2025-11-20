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
}
