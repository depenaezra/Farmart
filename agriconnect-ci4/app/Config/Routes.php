<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// PUBLIC ROUTES
// ============================================================

// Landing page
$routes->get('/', 'Home::index');

// Dashboard (redirects based on user role)
$routes->get('/dashboard', 'Home::dashboard', ['filter' => 'auth']);

// Marketplace
$routes->get('/marketplace', 'Marketplace::index');
$routes->get('/marketplace/product/(:num)', 'Marketplace::product/$1');
$routes->get('/marketplace/search', 'Marketplace::search');

// Weather
$routes->get('/weather', 'Weather::index');
$routes->get('/weather/api', 'Weather::getWeather');
$routes->get('/weather/update-cache', 'Weather::updateCache'); // For cron job

// Announcements
$routes->get('/announcements', 'Announcements::index');
$routes->get('/announcements/(:num)', 'Announcements::view/$1');

// Forum (public read)
$routes->get('/forum', 'Forum::index');
$routes->get('/forum/post/(:num)', 'Forum::viewPost/$1');
$routes->get('/forum/post/(:num)/comments', 'Forum::loadMoreComments/$1');

// Public user profiles
$routes->get('/users/(:num)', 'Users::show/$1');

// ============================================================
// AUTHENTICATION ROUTES
// ============================================================

$routes->group('auth', function($routes) {
    // Login
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::loginProcess');
    
    // Register (farmer registration routes redirect to unified buyer registration)
    $routes->get('register-farmer', 'AuthController::registerBuyer');
    $routes->post('register-farmer', 'AuthController::registerBuyerProcess');
    $routes->get('register-buyer', 'AuthController::registerBuyer');
    $routes->post('register-buyer', 'AuthController::registerBuyerProcess');
    
    // Logout
    $routes->get('logout', 'AuthController::logout');
});

// ============================================================
// BUYER ROUTES (Protected - Unified buyer/seller functionality)
// ============================================================

$routes->group('buyer', ['filter' => 'auth:user,admin'], function($routes) {
    // Seller dashboard & products (buyer as seller)
    $routes->get('dashboard', 'Buyer::dashboard');
    $routes->get('products', 'Buyer::products');
    $routes->get('products/add', 'Buyer::addProduct');
    $routes->post('products/add', 'Buyer::addProductProcess');
    $routes->get('products/edit/(:num)', 'Buyer::editProduct/$1');
    $routes->post('products/edit/(:num)', 'Buyer::editProductProcess/$1');
    $routes->post('products/delete/(:num)', 'Buyer::deleteProduct/$1');
    $routes->post('products/update-stock/(:num)', 'Buyer::updateStock/$1');
    $routes->get('inventory', 'Buyer::inventory');

    // Seller orders management
    $routes->get('sales/orders', 'Buyer::sellerOrders');
    $routes->get('sales/orders/(:num)', 'Buyer::sellerOrderDetail/$1');
    $routes->post('sales/orders/(:num)/update-status', 'Buyer::updateSellerOrderStatus/$1');

    // Orders (as buyer)
    $routes->get('orders', 'Buyer::orders');
    $routes->get('orders/(:num)', 'Buyer::orderDetail/$1');
    $routes->post('orders/(:num)/cancel', 'Buyer::cancelOrder/$1');
});

// Cart
$routes->group('cart', ['filter' => 'auth:user,admin'], function($routes) {
    $routes->get('/', 'Cart::index');
    $routes->post('add', 'Cart::add');
    $routes->post('update/(:segment)', 'Cart::update/$1');
    $routes->post('remove/(:segment)', 'Cart::remove/$1');
    $routes->get('clear', 'Cart::clear');
});

// Checkout
$routes->group('checkout', ['filter' => 'auth:user,admin'], function($routes) {
    $routes->get('/', 'Checkout::index');
    $routes->post('place-order', 'Checkout::placeOrder');
    $routes->get('success', 'Checkout::success');
});

// ============================================================
// PROFILE ROUTES (Protected - All authenticated users)
// ============================================================

$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Profile::index');
    $routes->get('edit', 'Profile::edit');
    $routes->post('update', 'Profile::update');
});

// ============================================================
// MESSAGING ROUTES (Protected - All authenticated users)
// ============================================================

$routes->group('messages', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Messages::index');
    $routes->get('inbox', 'Messages::inbox');
    $routes->get('conversation/(:num)', 'Messages::getConversation/$1');
    $routes->post('conversation', 'Messages::getConversation');
    $routes->get('sent', 'Messages::sent');
    $routes->get('compose', 'Messages::compose');
    $routes->post('compose', 'Messages::send');
    $routes->post('send', 'Messages::send');
    $routes->get('view/(:num)', 'Messages::view/$1');
    $routes->post('reply/(:num)', 'Messages::reply/$1');
    $routes->post('delete/(:num)', 'Messages::delete/$1');
});

// ============================================================
// FORUM ROUTES (Protected for posting)
// ============================================================

$routes->group('forum', ['filter' => 'auth'], function($routes) {
    $routes->get('create', 'Forum::create');
    $routes->post('create', 'Forum::createProcess');
    $routes->post('post/(:num)/comment', 'Forum::addComment/$1');
    $routes->post('post/(:num)/like', 'Forum::likePost/$1');
    $routes->post('post/(:num)/delete', 'Forum::deletePost/$1');
});

// ============================================================
// ADMIN ROUTES (Protected - Admin only)
// ============================================================

$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    // Dashboard
    $routes->get('/', 'Admin::dashboard');
    $routes->get('dashboard', 'Admin::dashboard');
    
    // User Management
    $routes->get('users', 'Admin::users');
    $routes->get('users/(:num)', 'Admin::userDetail/$1');
    $routes->post('users/(:num)/toggle-status', 'Admin::toggleUserStatus/$1');
    $routes->post('users/(:num)/change-role', 'Admin::changeUserRole/$1');
    $routes->post('users/(:num)/delete', 'Admin::deleteUser/$1');
    
    // Product Moderation
    $routes->get('products', 'Admin::products');
    $routes->get('products/(:num)', 'Admin::productDetail/$1');
    $routes->post('products/(:num)/approve', 'Admin::approveProduct/$1');
    $routes->post('products/(:num)/reject', 'Admin::rejectProduct/$1');
    $routes->post('products/(:num)/delete', 'Admin::deleteProduct/$1');
    
    // Orders Management
    $routes->get('orders', 'Admin::orders');
    $routes->get('orders/(:num)', 'Admin::orderDetail/$1');
    $routes->post('orders/(:num)/update-status', 'Admin::updateOrderStatus/$1');
    
    // Announcements
    $routes->get('announcements', 'Admin::announcements');
    $routes->get('announcements/create', 'Admin::createAnnouncement');
    $routes->post('announcements/create', 'Admin::createAnnouncementProcess');
    $routes->get('announcements/edit/(:num)', 'Admin::editAnnouncement/$1');
    $routes->post('announcements/edit/(:num)', 'Admin::editAnnouncementProcess/$1');
    $routes->post('announcements/delete/(:num)', 'Admin::deleteAnnouncement/$1');

    // Violations
    $routes->get('violations', 'Admin::violations');
    $routes->post('violations/(:num)/status', 'Admin::updateViolationStatus/$1');
    $routes->post('violations/(:num)/delete', 'Admin::deleteReportedItem/$1');
    $routes->post('violations/(:num)/delete-report', 'Admin::deleteViolationReport/$1');

    // Analytics
    $routes->get('analytics', 'Admin::analytics');

    // Settings
    $routes->get('settings', 'Admin::settings');
    $routes->post('settings', 'Admin::updateSettings');
});

// ============================================================
// REPORTING ROUTES (Protected - Authenticated users)
// ============================================================

$routes->post('report', 'Report::submit', ['filter' => 'auth']);

// ============================================================
// API ROUTES (Optional - for AJAX)
// ============================================================

$routes->group('api', function($routes) {
    // Products
    $routes->get('products', 'Api\Products::index');
    $routes->get('products/(:num)', 'Api\Products::show/$1');
    $routes->get('products/search', 'Api\Products::search');

    // Cart (requires auth)
    $routes->post('cart/add', 'Api\Cart::add', ['filter' => 'auth']);
    $routes->get('cart/count', 'Api\Cart::count', ['filter' => 'auth']);
});

// ============================================================
// ERROR ROUTES
// ============================================================

$routes->set404Override(function() {
    return view('errors/html/error_404');
});
