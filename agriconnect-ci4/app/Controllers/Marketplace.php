<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Marketplace extends BaseController
{
    protected $productModel;
    
    public function __construct()
    {
        $this->productModel = new ProductModel();
    }
    
    /**
     * Marketplace index - Browse all products
     */
    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $category = $this->request->getGet('category');
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        $location = $this->request->getGet('location');

        $products = $this->productModel->searchProducts([
            'keyword' => $keyword,
            'category' => $category,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'location' => $location
        ]);

        // Get seasonal products for the info board
        $seasonalProducts = $this->productModel->getSeasonalProducts(8);

        $data = [
            'title' => 'Marketplace - Fresh Local Produce',
            'products' => $products,
            'seasonal_products' => $seasonalProducts,
            'filters' => [
                'keyword' => $keyword,
                'category' => $category,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'location' => $location
            ]
        ];

        return view('marketplace/index', $data);
    }
    
    /**
     * Product detail page
     */
    public function product($id)
    {
        $product = $this->productModel->getProductWithFarmer($id);
        
        if (!$product) {
            return redirect()->to('/marketplace')
                ->with('error', 'Product not found.');
        }
        
        // Get other products from same farmer
        $otherProducts = $this->productModel->getProductsByFarmer($product['farmer_id']);
        $otherProducts = array_filter($otherProducts, function($p) use ($id) {
            return $p['id'] != $id && $p['status'] === 'available';
        });
        $otherProducts = array_slice($otherProducts, 0, 4);
        
        $data = [
            'title' => $product['name'] . ' - Marketplace',
            'product' => $product,
            'other_products' => $otherProducts
        ];
        
        return view('marketplace/product_detail', $data);
    }
    
    /**
     * Search products (AJAX endpoint)
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        $products = $this->productModel->searchProducts(['keyword' => $keyword]);
        
        return $this->response->setJSON([
            'success' => true,
            'products' => $products
        ]);
    }
}
