<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;

class Users extends BaseController
{
    protected $userModel;
    protected $productModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Public profile view for any user by id
     */
    public function show($id = null)
    {
        if (!$id || !is_numeric($id)) {
            return redirect()->to('/')
                ->with('error', 'Invalid user.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/')
                ->with('error', 'User not found.');
        }

        // Recent products by this user (farmer)
        $products = $this->productModel->getProductsByFarmer($id);
        // Limit to latest 6 for view
        $products = array_slice($products, 0, 6);

        // Recent forum posts by this user
        $db = \Config\Database::connect();
        $posts = $db->table('forum_posts')
                    ->select('id, title, content, created_at, image_url, category')
                    ->where('user_id', $id)
                    ->orderBy('created_at', 'DESC')
                    ->limit(6)
                    ->get()
                    ->getResultArray();

        $data = [
            'title' => $user['name'] . ' - Profile',
            'user' => $user,
            'products' => $products,
            'posts' => $posts
        ];

        return view('users/show', $data);
    }
}
