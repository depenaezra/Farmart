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

// Marketplace (admins are redirected to the admin panel)
$routes->get('/marketplace', 'Marketplace::index', ['filter' => 'redirectadmin']);
$routes->get('/marketplace/product/(:num)', 'Marketplace::product/$1', ['filter' => 'redirectadmin']);
$routes->get('/marketplace/search', 'Marketplace::search', ['filter' => 'redirectadmin']);

// Weather
$routes->get('/weather', 'Weather::index', ['filter' => 'redirectadmin']);
$routes->get('/weather/api', 'Weather::getWeather');
$routes->get('/weather/update-cache', 'Weather::updateCache'); // For cron job

// Announcements
$routes->get('/announcements', 'Announcements::index', ['filter' => 'redirectadmin']);
$routes->get('/announcements/(:num)', 'Announcements::view/$1', ['filter' => 'redirectadmin']);

// Forum (public read)
$routes->get('/forum', 'Forum::index', ['filter' => 'redirectadmin']);
$routes->get('/forum/post/(:num)', 'Forum::viewPost/$1', ['filter' => 'redirectadmin']);
$routes->get('/forum/post/(:num)/comments', 'Forum::loadMoreComments/$1', ['filter' => 'redirectadmin']);

// Public user profiles
$routes->get('/users/(:num)', 'Users::show/$1', ['filter' => 'redirectadmin']);

// General orders route for all authenticated users
$routes->get('/orders', 'Buyer::orders', ['filter' => ['auth', 'nonadmin']]);

// ============================================================
// AUTHENTICATION ROUTES
// ============================================================

$routes->group('auth', function($routes) {
    // Login
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::loginProcess');
    
    // Disabled account page
    $routes->get('disabled', 'AuthController::disabled');
    
    // Blocked registration page
    $routes->get('blocked-registration', 'AuthController::blockedRegistration');
    
    // Register (farmer registration routes redirect to unified buyer registration)
    $routes->get('register-farmer', 'AuthController::registerBuyer');
    $routes->post('register-farmer', 'AuthController::registerBuyerProcess');
    $routes->get('register-buyer', 'AuthController::registerBuyer');
    $routes->post('register-buyer', 'AuthController::registerBuyerProcess');
    $routes->get('register-verify', 'AuthController::registerVerify');
    $routes->post('register-verify', 'AuthController::registerVerifyProcess');
    $routes->post('register-resend-otp', 'AuthController::resendRegistrationOtp');
    
    // Logout (both GET and POST)
    $routes->get('logout', 'AuthController::logout');
    $routes->post('logout', 'AuthController::logout');

    // Password reset OTP routes
    $routes->get('otp', 'AuthController::otp');
    $routes->post('sendOtp', 'AuthController::sendOtp');
    $routes->post('verifyOtp', 'AuthController::verifyOtp');
    $routes->get('change_password', 'AuthController::changePassword');
    $routes->post('changePasswordProcess', 'AuthController::changePasswordProcess');

});

// ============================================================
// BUYER ROUTES (Protected - Unified buyer/seller functionality)
// ============================================================

$routes->group('buyer', ['filter' => ['auth:buyer,farmer,user', 'nonadmin']], function($routes) {
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
$routes->group('cart', ['filter' => ['auth:buyer,farmer,user', 'nonadmin']], function($routes) {
    $routes->get('/', 'Cart::index');
    $routes->post('add', 'Cart::add');
    $routes->post('buy_now', 'Cart::buyNow');
    $routes->post('update/(:segment)', 'Cart::update/$1');
    $routes->post('remove/(:segment)', 'Cart::remove/$1');
    $routes->get('clear', 'Cart::clear');
});

// Checkout
$routes->group('checkout', ['filter' => ['auth:buyer,farmer,user', 'nonadmin']], function($routes) {
    $routes->get('/', 'Checkout::index');
    $routes->post('/', 'Checkout::index'); // For form submission with selected items
    $routes->get('direct', 'Checkout::directCheckout');
    $routes->post('direct', 'Checkout::directCheckout');
    $routes->post('place-order', 'Checkout::placeOrder');
    $routes->get('success', 'Checkout::success');
});

// ============================================================
// PROFILE ROUTES (Protected - All authenticated users)
// ============================================================

$routes->get('/profile.php', 'Profile::index', ['filter' => ['auth', 'nonadmin']]);

$routes->group('profile', ['filter' => ['auth', 'nonadmin']], function($routes) {
    $routes->get('/', 'Profile::index');
    $routes->get('edit', 'Profile::edit');
    $routes->post('update', 'Profile::update');
    $routes->get('verify-otp', 'Profile::verifyOtp');
    $routes->post('verify-otp', 'Profile::verifyOtpProcess');
    $routes->post('resend-otp', 'Profile::resendOtp');
});

// ============================================================
// MESSAGING ROUTES (Protected - All authenticated users)
// ============================================================

$routes->group('messages', ['filter' => ['auth', 'nonadmin']], function($routes) {
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

$routes->group('forum', ['filter' => ['auth', 'nonadmin']], function($routes) {
    $routes->get('create', 'Forum::create');
    $routes->post('create', 'Forum::createProcess');
    $routes->post('post/(:num)/comment', 'Forum::addComment/$1');
    $routes->post('post/(:num)/like', 'Forum::likePost/$1');
    $routes->post('post/(:num)/delete', 'Forum::deletePost/$1');
    $routes->get('mentions', 'Forum::getMentions');
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
    $routes->post('users/(:num)/suspend-login', 'Admin::suspendUserLogin/$1');
    $routes->post('users/(:num)/clear-suspension', 'Admin::clearUserSuspension/$1');
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

    // Login whitelist (maintenance: restrict login to admins + listed emails)
    $routes->get('login-whitelist', 'Admin::loginWhitelist');
    $routes->post('login-whitelist/add-email', 'Admin::loginWhitelistAddEmail');
    $routes->post('login-whitelist/remove/(:num)', 'Admin::loginWhitelistRemove/$1');
    $routes->post('login-whitelist/toggle', 'Admin::loginWhitelistToggle');
    
    // Email Blocker
    $routes->get('email-blocker', 'Admin::emailBlocker');
    $routes->post('email-blocker/block', 'Admin::blockEmail');
    $routes->post('email-blocker/unblock/(:num)', 'Admin::unblockEmail/$1');
});

// ============================================================
// REPORTING ROUTES (Protected - Authenticated users)
// ============================================================

$routes->post('report', 'Report::submit', ['filter' => ['auth', 'nonadmin']]);

// ============================================================
// API ROUTES (Optional - for AJAX)
// ============================================================

$routes->group('api', function($routes) {
    // Products
    $routes->get('products', 'Api\Products::index');
    $routes->get('products/(:num)', 'Api\Products::show/$1');
    $routes->get('products/search', 'Api\Products::search');

    // Cart (requires auth)
    $routes->post('cart/add', 'Api\Cart::add', ['filter' => ['auth', 'nonadmin']]);
    $routes->get('cart/count', 'Api\Cart::count', ['filter' => ['auth', 'nonadmin']]);
});

// ============================================================
// ERROR ROUTES
// ============================================================

$routes->set404Override(function() {
    return view('errors/html/error_404');
});
