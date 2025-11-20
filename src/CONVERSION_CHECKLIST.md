# AgriConnect - React to CodeIgniter 4 Conversion Checklist

## Complete Page-by-Page Conversion Guide

This checklist maps each React component to its corresponding CodeIgniter 4 implementation.

---

## ✅ Page Inventory (21 Pages Total)

### Public Pages (No Authentication Required)

#### 1. Landing Page
- **React Component**: `/components/landing/LandingPage.tsx`
- **CI4 View**: `app/Views/landing.php`
- **CI4 Controller**: `Home::index()`
- **Route**: `/` (GET)
- **Features**:
  - Hero section with CTAs
  - Featured products grid
  - Quick access icons
  - Why AgriConnect section
  - Call to action section
- **Data Needed**: Featured products from database
- **Conversion Notes**: 
  - Replace Unsplash images with local/optimized images
  - Convert ImageWithFallback to regular img tags
  - Make buttons into anchor tags or forms

#### 2. Marketplace (Public Browse)
- **React Component**: `/components/buyer/Marketplace.tsx`
- **CI4 View**: `app/Views/marketplace/index.php`
- **CI4 Controller**: `Marketplace::index()`
- **Route**: `/marketplace` (GET)
- **Features**:
  - Product search
  - Category filters
  - Price range filters
  - Product grid with cards
- **Data Needed**: All available products
- **Conversion Notes**:
  - Search/filter can use GET parameters
  - Implement pagination for large datasets
  - Add AJAX for filters (optional)

### Authentication Pages

#### 3. Login Page
- **React Component**: `/components/auth/Login.tsx`
- **CI4 View**: `app/Views/auth/login.php`
- **CI4 Controller**: `AuthController::login()`
- **Routes**: 
  - `/auth/login` (GET - show form)
  - `/auth/login` (POST - process login)
- **Features**:
  - Email/phone and password fields
  - Role selection (Farmer/Buyer/Admin)
  - Remember me checkbox
  - Forgot password link
- **Validation Rules**:
  - Email: required, valid_email
  - Password: required, min_length[6]
- **Conversion Notes**:
  - Add CSRF token
  - Implement session management
  - Add rate limiting for security

#### 4. Register Farmer
- **React Component**: `/components/auth/RegisterFarmer.tsx`
- **CI4 View**: `app/Views/auth/register_farmer.php`
- **CI4 Controller**: `AuthController::registerFarmer()`
- **Routes**:
  - `/auth/register-farmer` (GET)
  - `/auth/register-farmer` (POST)
- **Features**:
  - Name, email, phone, password fields
  - Location/barangay selector
  - Cooperative selection
  - Terms acceptance
- **Validation Rules**:
  - All fields required
  - Email must be unique
  - Phone format validation
  - Password min 8 characters
- **Conversion Notes**:
  - Hash passwords with password_hash()
  - Send welcome email (optional)

#### 5. Register Buyer
- **React Component**: `/components/auth/RegisterBuyer.tsx`
- **CI4 View**: `app/Views/auth/register_buyer.php`
- **CI4 Controller**: `AuthController::registerBuyer()`
- **Routes**:
  - `/auth/register-buyer` (GET)
  - `/auth/register-buyer` (POST)
- **Features**: Similar to Register Farmer but without cooperative field
- **Conversion Notes**: Same as Register Farmer

### Farmer Dashboard & Features (Requires Farmer Role)

#### 6. Farmer Dashboard
- **React Component**: `/components/farmer/Dashboard.tsx`
- **CI4 View**: `app/Views/farmer/dashboard.php`
- **CI4 Controller**: `Farmer::dashboard()`
- **Route**: `/farmer/dashboard` (GET)
- **Auth Filter**: farmer
- **Features**:
  - Statistics cards (total products, pending orders, sales)
  - Quick action buttons
  - Recent orders list
  - Announcements widget
- **Data Needed**:
  - User's products count
  - User's orders with status breakdown
  - Sales statistics
  - Recent announcements
- **Queries**:
  ```sql
  SELECT COUNT(*) FROM products WHERE farmer_id = ?
  SELECT COUNT(*) FROM orders WHERE farmer_id = ? AND status = 'pending'
  SELECT SUM(total_price) FROM orders WHERE farmer_id = ? AND status = 'completed'
  ```

#### 7. Add Product
- **React Component**: `/components/farmer/AddProduct.tsx`
- **CI4 View**: `app/Views/farmer/add_product.php`
- **CI4 Controller**: `Farmer::addProduct()`
- **Routes**:
  - `/farmer/products/add` (GET)
  - `/farmer/products/add` (POST)
- **Auth Filter**: farmer
- **Features**:
  - Product form (name, description, price, unit)
  - Category selector
  - Stock quantity
  - Image upload (multiple)
  - Location
- **Validation Rules**:
  - Name: required, min_length[3]
  - Price: required, numeric, greater_than[0]
  - Stock: required, integer, greater_than_equal_to[0]
  - Images: uploaded, mime_in[image/jpg,image/jpeg,image/png], max_size[2048]
- **Conversion Notes**:
  - Handle file upload with validation
  - Resize/compress images
  - Store in public/uploads/products/

#### 8. Inventory Management
- **React Component**: `/components/farmer/Inventory.tsx`
- **CI4 View**: `app/Views/farmer/inventory.php`
- **CI4 Controller**: `Farmer::inventory()`
- **Route**: `/farmer/inventory` (GET)
- **Auth Filter**: farmer
- **Features**:
  - List all farmer's products
  - Edit product button
  - Delete product button
  - Stock status indicators
  - Quick stock update
- **Data Needed**: All products where farmer_id = current_user_id
- **Additional Routes**:
  - `/farmer/products/edit/{id}` (GET/POST)
  - `/farmer/products/delete/{id}` (POST)
  - `/farmer/products/update-stock/{id}` (POST)

#### 9. Farmer Orders
- **React Component**: `/components/farmer/Orders.tsx`
- **CI4 View**: `app/Views/farmer/orders.php`
- **CI4 Controller**: `Farmer::orders()`
- **Route**: `/farmer/orders` (GET)
- **Auth Filter**: farmer
- **Features**:
  - List all orders for farmer's products
  - Filter by status
  - Order details view
  - Status update buttons
  - Buyer contact info
- **Data Needed**:
  ```sql
  SELECT o.*, u.name as buyer_name, u.phone, p.name as product_name
  FROM orders o
  JOIN users u ON o.buyer_id = u.id
  JOIN products p ON o.product_id = p.id
  WHERE o.farmer_id = ?
  ORDER BY o.created_at DESC
  ```
- **Additional Routes**:
  - `/farmer/orders/{id}` (GET - view details)
  - `/farmer/orders/{id}/update-status` (POST)

### Buyer Features (Requires Buyer Role)

#### 10. Product Detail Page
- **React Component**: `/components/buyer/ProductDetail.tsx`
- **CI4 View**: `app/Views/marketplace/product_detail.php`
- **CI4 Controller**: `Marketplace::product($id)`
- **Route**: `/marketplace/product/{id}` (GET)
- **Auth**: Optional (guests can view, buyers can add to cart)
- **Features**:
  - Product images gallery
  - Full product details
  - Farmer information
  - Add to cart button (with quantity selector)
  - Message farmer button
  - Reviews/ratings (future)
- **Data Needed**:
  ```sql
  SELECT p.*, u.name as farmer_name, u.phone, u.location as farmer_location
  FROM products p
  JOIN users u ON p.farmer_id = u.id
  WHERE p.id = ?
  ```

#### 11. Shopping Cart
- **React Component**: `/components/buyer/Cart.tsx`
- **CI4 View**: `app/Views/buyer/cart.php`
- **CI4 Controller**: `Cart::index()`
- **Route**: `/cart` (GET)
- **Auth Filter**: buyer
- **Features**:
  - Cart items list
  - Quantity adjustment
  - Remove item button
  - Subtotal calculation
  - Proceed to checkout button
- **Session Data**: Cart stored in session
- **Additional Routes**:
  - `/cart/add/{product_id}` (POST)
  - `/cart/update/{item_id}` (POST)
  - `/cart/remove/{item_id}` (POST)

#### 12. Checkout
- **React Component**: `/components/buyer/Checkout.tsx`
- **CI4 View**: `app/Views/buyer/checkout.php`
- **CI4 Controller**: `Checkout::index()`
- **Routes**:
  - `/checkout` (GET)
  - `/checkout/place-order` (POST)
- **Auth Filter**: buyer
- **Features**:
  - Order summary
  - Delivery address form
  - Payment method selection
  - Place order button
- **Order Processing**:
  1. Validate cart not empty
  2. Check stock availability
  3. Create order records
  4. Update product stock
  5. Clear cart
  6. Send notifications
  7. Redirect to order confirmation

#### 13. Buyer Order History
- **React Component**: `/components/buyer/OrderHistory.tsx`
- **CI4 View**: `app/Views/buyer/orders.php`
- **CI4 Controller**: `Buyer::orders()`
- **Route**: `/buyer/orders` (GET)
- **Auth Filter**: buyer
- **Features**:
  - List all buyer's orders
  - Filter by status
  - Order tracking
  - Reorder button
  - Cancel order (if pending)
- **Data Needed**:
  ```sql
  SELECT o.*, u.name as farmer_name, p.name as product_name
  FROM orders o
  JOIN users u ON o.farmer_id = u.id
  JOIN products p ON o.product_id = p.id
  WHERE o.buyer_id = ?
  ORDER BY o.created_at DESC
  ```

### Messaging System (Farmers & Buyers)

#### 14. Inbox/Messages
- **React Component**: `/components/messaging/Inbox.tsx`
- **CI4 View**: `app/Views/messaging/inbox.php`
- **CI4 Controller**: `Messages::inbox()`
- **Route**: `/messages` (GET)
- **Auth Filter**: authenticated
- **Features**:
  - Message list (inbox/sent tabs)
  - Unread indicators
  - Search messages
  - Compose new message button
  - View message thread
- **Data Needed**:
  ```sql
  SELECT m.*, 
         sender.name as sender_name,
         receiver.name as receiver_name
  FROM messages m
  JOIN users sender ON m.sender_id = sender.id
  JOIN users receiver ON m.receiver_id = receiver.id
  WHERE m.receiver_id = ? OR m.sender_id = ?
  ORDER BY m.created_at DESC
  ```
- **Additional Routes**:
  - `/messages/compose` (GET/POST)
  - `/messages/{id}` (GET - view)
  - `/messages/{id}/reply` (POST)

### Community Features (All Authenticated Users)

#### 15. Weather Page
- **React Component**: `/components/weather/WeatherPage.tsx`
- **CI4 View**: `app/Views/weather/index.php`
- **CI4 Controller**: `Weather::index()`
- **Route**: `/weather` (GET)
- **Auth**: Optional
- **Features**:
  - Current weather for Nasugbu
  - 7-day forecast
  - Agricultural advisories
  - Planting calendar
- **Data Source**: 
  - Option 1: PAGASA API
  - Option 2: OpenWeatherMap API
  - Option 3: Manual updates by admin
- **Conversion Notes**:
  - Cache weather data (update hourly)
  - Store in database or cache file

#### 16. Announcements
- **React Component**: `/components/announcements/Announcements.tsx`
- **CI4 View**: `app/Views/announcements/index.php`
- **CI4 Controller**: `Announcements::index()`
- **Route**: `/announcements` (GET)
- **Auth**: Optional
- **Features**:
  - List all announcements
  - Filter by category (weather, government, market)
  - Priority badges
  - Pagination
- **Data Needed**: All announcements ordered by priority and date
- **Conversion Notes**:
  - Implement pagination (20 per page)
  - Add RSS feed (optional)

#### 17. Community Forum
- **React Component**: `/components/forum/Forum.tsx`
- **CI4 View**: `app/Views/forum/index.php`
- **CI4 Controller**: `Forum::index()`
- **Route**: `/forum` (GET)
- **Auth**: Optional to view, required to post
- **Features**:
  - List forum topics/posts
  - Category filter
  - Search posts
  - Create new post button
  - Like/comment on posts
- **Additional Routes**:
  - `/forum/post/{id}` (GET - view post)
  - `/forum/create` (GET/POST - new post)
  - `/forum/post/{id}/comment` (POST)
  - `/forum/post/{id}/like` (POST)
- **Data Needed**:
  ```sql
  SELECT fp.*, u.name as author_name,
         COUNT(fc.id) as comment_count
  FROM forum_posts fp
  JOIN users u ON fp.user_id = u.id
  LEFT JOIN forum_comments fc ON fp.id = fc.post_id
  GROUP BY fp.id
  ORDER BY fp.created_at DESC
  ```

### Admin Panel (Requires Admin Role)

#### 18. Admin Dashboard
- **React Component**: `/components/admin/Dashboard.tsx`
- **CI4 View**: `app/Views/admin/dashboard.php`
- **CI4 Controller**: `Admin::dashboard()`
- **Route**: `/admin` (GET)
- **Auth Filter**: admin
- **Features**:
  - System statistics overview
  - User count (farmers, buyers)
  - Product count
  - Order count and revenue
  - Recent activity
  - Quick action buttons
- **Data Needed**:
  - Total users by role
  - Total products
  - Total orders and revenue
  - Recent registrations
  - Pending approvals

#### 19. User Management
- **React Component**: `/components/admin/UserManagement.tsx`
- **CI4 View**: `app/Views/admin/users.php`
- **CI4 Controller**: `Admin::users()`
- **Route**: `/admin/users` (GET)
- **Auth Filter**: admin
- **Features**:
  - List all users (searchable, filterable)
  - User details view
  - Activate/deactivate user
  - Delete user
  - Role management
- **Data Needed**: All users with pagination
- **Additional Routes**:
  - `/admin/users/{id}` (GET - view details)
  - `/admin/users/{id}/toggle-status` (POST)
  - `/admin/users/{id}/delete` (POST)

#### 20. Product Moderation
- **React Component**: `/components/admin/ProductModeration.tsx`
- **CI4 View**: `app/Views/admin/products.php`
- **CI4 Controller**: `Admin::products()`
- **Route**: `/admin/products` (GET)
- **Auth Filter**: admin
- **Features**:
  - List all products
  - Approve/reject new products
  - Edit product details
  - Remove inappropriate products
  - Search and filter
- **Data Needed**:
  ```sql
  SELECT p.*, u.name as farmer_name
  FROM products p
  JOIN users u ON p.farmer_id = u.id
  ORDER BY p.created_at DESC
  ```
- **Additional Routes**:
  - `/admin/products/{id}/approve` (POST)
  - `/admin/products/{id}/reject` (POST)
  - `/admin/products/{id}/delete` (POST)

#### 21. Post Announcement
- **React Component**: `/components/admin/PostAnnouncement.tsx`
- **CI4 View**: `app/Views/admin/announcements/create.php`
- **CI4 Controller**: `Admin::createAnnouncement()`
- **Routes**:
  - `/admin/announcements/create` (GET)
  - `/admin/announcements/create` (POST)
- **Auth Filter**: admin
- **Features**:
  - Announcement form
  - Category selector
  - Priority selector
  - Rich text editor
  - Preview
  - Publish button
- **Validation Rules**:
  - Title: required, min_length[5], max_length[255]
  - Content: required, min_length[20]
  - Category: required, in_list[weather,government,market,general]
- **Additional Routes**:
  - `/admin/announcements` (GET - list all)
  - `/admin/announcements/{id}/edit` (GET/POST)
  - `/admin/announcements/{id}/delete` (POST)

---

## 🔧 Component Mapping Reference

### Shared Components

#### Navigation Bar
- **React**: `/components/Layout.tsx` (nav section)
- **CI4**: `app/Views/components/navbar.php`
- **Features**:
  - Logo and branding
  - Main navigation links
  - User menu (when logged in)
  - Cart icon with badge (buyers)
  - Mobile hamburger menu

#### Footer
- **React**: `/components/Layout.tsx` (footer section)
- **CI4**: `app/Views/components/footer.php`
- **Features**:
  - Quick links
  - Contact information
  - Copyright notice
  - Terms and privacy links

---

## 📊 Database Relationships

```
users (id)
  ├── products (farmer_id) - One farmer has many products
  ├── orders as buyer (buyer_id) - One buyer has many orders
  ├── orders as farmer (farmer_id) - One farmer receives many orders
  ├── messages sent (sender_id)
  ├── messages received (receiver_id)
  ├── forum_posts (user_id)
  └── announcements (created_by)

products (id)
  ├── orders (product_id) - One product can be in many orders
  └── users (farmer_id) - Many products belong to one farmer

orders (id)
  ├── users as buyer (buyer_id)
  ├── users as farmer (farmer_id)
  └── products (product_id)

forum_posts (id)
  ├── users (user_id)
  └── forum_comments (post_id) - One post has many comments

forum_comments (id)
  ├── forum_posts (post_id)
  └── users (user_id)
```

---

## 🎨 Static Assets to Copy

### CSS Files
- ✅ `/styles/globals.css` → `public/assets/css/globals.css`

### JavaScript (if needed)
- Create `public/assets/js/main.js` for:
  - Mobile menu toggle
  - Form validation
  - Image previews
  - AJAX requests

### Images
- Prepare optimized images for:
  - Hero section
  - Product placeholders
  - User avatars
  - Category icons

---

## 🔐 Security Checklist

- [ ] All forms have CSRF protection
- [ ] Passwords are hashed with password_hash()
- [ ] SQL queries use parameter binding
- [ ] File uploads are validated and sanitized
- [ ] User input is escaped in views (esc() function)
- [ ] Authentication filters on protected routes
- [ ] Role-based authorization checks
- [ ] Session security configured (httponly, secure flags)
- [ ] Rate limiting on login attempts
- [ ] XSS protection enabled

---

## 🚀 Route Configuration

**app/Config/Routes.php**:

```php
<?php

// Public routes
$routes->get('/', 'Home::index');
$routes->get('/marketplace', 'Marketplace::index');
$routes->get('/marketplace/product/(:num)', 'Marketplace::product/$1');
$routes->get('/weather', 'Weather::index');
$routes->get('/announcements', 'Announcements::index');
$routes->get('/forum', 'Forum::index');

// Auth routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::login');
    $routes->get('register-farmer', 'AuthController::registerFarmer');
    $routes->post('register-farmer', 'AuthController::registerFarmer');
    $routes->get('register-buyer', 'AuthController::registerBuyer');
    $routes->post('register-buyer', 'AuthController::registerBuyer');
    $routes->get('logout', 'AuthController::logout');
});

// Farmer routes (protected)
$routes->group('farmer', ['filter' => 'auth:farmer'], function($routes) {
    $routes->get('dashboard', 'Farmer::dashboard');
    $routes->get('inventory', 'Farmer::inventory');
    $routes->get('products/add', 'Farmer::addProduct');
    $routes->post('products/add', 'Farmer::addProduct');
    $routes->get('products/edit/(:num)', 'Farmer::editProduct/$1');
    $routes->post('products/edit/(:num)', 'Farmer::editProduct/$1');
    $routes->post('products/delete/(:num)', 'Farmer::deleteProduct/$1');
    $routes->get('orders', 'Farmer::orders');
    $routes->get('orders/(:num)', 'Farmer::orderDetail/$1');
});

// Buyer routes (protected)
$routes->group('buyer', ['filter' => 'auth:buyer'], function($routes) {
    $routes->get('orders', 'Buyer::orders');
});

$routes->group('cart', ['filter' => 'auth:buyer'], function($routes) {
    $routes->get('/', 'Cart::index');
    $routes->post('add/(:num)', 'Cart::add/$1');
    $routes->post('update/(:num)', 'Cart::update/$1');
    $routes->post('remove/(:num)', 'Cart::remove/$1');
});

$routes->group('checkout', ['filter' => 'auth:buyer'], function($routes) {
    $routes->get('/', 'Checkout::index');
    $routes->post('place-order', 'Checkout::placeOrder');
});

// Messages (authenticated)
$routes->group('messages', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Messages::inbox');
    $routes->get('compose', 'Messages::compose');
    $routes->post('compose', 'Messages::compose');
    $routes->get('(:num)', 'Messages::view/$1');
    $routes->post('(:num)/reply', 'Messages::reply/$1');
});

// Admin routes (protected)
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Admin::dashboard');
    $routes->get('users', 'Admin::users');
    $routes->get('users/(:num)', 'Admin::userDetail/$1');
    $routes->post('users/(:num)/toggle-status', 'Admin::toggleUserStatus/$1');
    $routes->get('products', 'Admin::products');
    $routes->post('products/(:num)/approve', 'Admin::approveProduct/$1');
    $routes->get('announcements/create', 'Admin::createAnnouncement');
    $routes->post('announcements/create', 'Admin::createAnnouncement');
});
```

---

## ✨ Progressive Enhancement

For better user experience, add JavaScript for:

1. **Form Validation** (client-side before server-side)
2. **Live Search** (marketplace, user management)
3. **Image Preview** (before upload)
4. **Cart Management** (AJAX add to cart)
5. **Notifications** (toast messages)
6. **Infinite Scroll** (product listings)

---

## 📱 Mobile Optimization

Already implemented in design:
- ✅ Mobile-first Tailwind classes
- ✅ Large touch targets (min 3rem)
- ✅ Readable fonts (min 1rem)
- ✅ Hamburger menu
- ✅ Responsive grids

Additional improvements:
- [ ] PWA configuration (optional)
- [ ] Offline mode (service worker)
- [ ] Add to home screen prompt

---

## 🧪 Testing Strategy

### Unit Tests
- Model methods (CRUD operations)
- Helper functions
- Validation rules

### Integration Tests
- Authentication flow
- Order processing
- Cart functionality

### User Acceptance Testing
- Test with actual farmers (Filipino, varying digital literacy)
- Test on low-bandwidth connections
- Test on different devices

---

## 📈 Analytics & Monitoring

Consider adding:
- [ ] Google Analytics
- [ ] Error logging (CodeIgniter built-in)
- [ ] Performance monitoring
- [ ] User behavior tracking

---

**Conversion Status**: Ready for implementation  
**Estimated Development Time**: 4-6 weeks  
**Priority**: High  
**Target Launch**: Q1 2025
