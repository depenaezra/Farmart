# AgriConnect - Web Deployment Guide

## Overview
This guide explains how to deploy the AgriConnect web application from this React/TypeScript prototype to a production-ready CodeIgniter 4 application hosted on Hostinger.

## Current Tech Stack (Prototype)
- **Framework**: React 18 with TypeScript
- **Styling**: Tailwind CSS v4
- **State Management**: React Hooks (useState)
- **Data Storage**: Local Storage (mock backend)
- **Icons**: Lucide React
- **UI Components**: Shadcn/ui

## Target Production Stack
- **Backend**: CodeIgniter 4 (PHP 8.0+)
- **Frontend**: HTML5, CSS3 (Tailwind CSS), Vanilla JavaScript
- **Database**: MySQL/MariaDB
- **Hosting**: Hostinger Shared/VPS Hosting
- **Server**: Apache/Nginx

---

## Phase 1: Convert React Components to CodeIgniter 4 Views

### Step 1: Set Up CodeIgniter 4 Project

1. **Install CodeIgniter 4** on your Hostinger account:
   ```bash
   composer create-project codeigniter4/appstarter agriconnect
   ```

2. **Configure Database** (app/Config/Database.php):
   ```php
   public array $default = [
       'hostname' => 'localhost',
       'username' => 'your_db_user',
       'password' => 'your_db_password',
       'database' => 'agriconnect_db',
       'DBDriver' => 'MySQLi',
       'charset'  => 'utf8mb4',
   ];
   ```

3. **Set Base URL** (app/Config/App.php):
   ```php
   public string $baseURL = 'https://yourdomain.com/';
   ```

### Step 2: Database Schema

Create the following tables in MySQL:

```sql
-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('farmer', 'buyer', 'admin') NOT NULL,
    phone VARCHAR(20),
    location VARCHAR(255),
    cooperative VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    category ENUM('vegetables', 'fruits', 'grains', 'other') NOT NULL,
    stock_quantity INT NOT NULL,
    location VARCHAR(255),
    image_url VARCHAR(500),
    status ENUM('available', 'out-of-stock', 'pending') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    farmer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit VARCHAR(50),
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    delivery_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Messages table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Announcements table
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category ENUM('weather', 'government', 'market', 'general') NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Forum posts table
CREATE TABLE forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(100),
    likes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Forum comments table
CREATE TABLE forum_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Step 3: Create CodeIgniter Models

Create models in `app/Models/`:

**UserModel.php**:
```php
<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'role', 'phone', 'location', 'cooperative'];
    protected $useTimestamps = true;
    
    public function getFarmers() {
        return $this->where('role', 'farmer')->findAll();
    }
    
    public function getBuyers() {
        return $this->where('role', 'buyer')->findAll();
    }
}
```

**ProductModel.php**:
```php
<?php
namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model {
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['farmer_id', 'name', 'description', 'price', 'unit', 'category', 'stock_quantity', 'location', 'image_url', 'status'];
    protected $useTimestamps = true;
    
    public function getAvailableProducts() {
        return $this->where('status', 'available')->where('stock_quantity >', 0)->findAll();
    }
    
    public function getProductsByFarmer($farmerId) {
        return $this->where('farmer_id', $farmerId)->findAll();
    }
}
```

Create similar models for Orders, Messages, Announcements, etc.

### Step 4: Create Controllers

**Home.php** (app/Controllers/Home.php):
```php
<?php
namespace App\Controllers;

class Home extends BaseController {
    public function index() {
        $productModel = new \App\Models\ProductModel();
        $data['featured_products'] = $productModel->getAvailableProducts();
        
        return view('landing', $data);
    }
}
```

**AuthController.php**:
```php
<?php
namespace App\Controllers;

class AuthController extends BaseController {
    public function login() {
        if ($this->request->getMethod() === 'post') {
            $userModel = new \App\Models\UserModel();
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            $user = $userModel->where('email', $email)->first();
            
            if ($user && password_verify($password, $user['password'])) {
                session()->set([
                    'user_id' => $user['id'],
                    'user_name' => $user['name'],
                    'user_role' => $user['role'],
                    'logged_in' => true
                ]);
                
                return redirect()->to($this->getUserHomePage($user['role']));
            }
            
            return redirect()->back()->with('error', 'Invalid credentials');
        }
        
        return view('auth/login');
    }
    
    private function getUserHomePage($role) {
        switch ($role) {
            case 'farmer': return '/farmer/dashboard';
            case 'buyer': return '/marketplace';
            case 'admin': return '/admin/dashboard';
            default: return '/';
        }
    }
}
```

### Step 5: Convert React Components to PHP Views

Copy the HTML structure from React components to CodeIgniter views (`app/Views/`).

**Example: app/Views/landing.php**:
```php
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary to-primary-light text-white py-12 md:py-20">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-white mb-4" style="font-size: 2.5rem; line-height: 1.1;">
                    Direct Marketplace for Local Farmers
                </h1>
                <p class="text-neutral-100 mb-8" style="font-size: 1.25rem;">
                    Connecting Nasugbu farmers directly with buyers. Fresh produce, fair prices, strong community.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/marketplace" class="flex items-center justify-center gap-2 px-8 py-4 bg-white text-primary hover:bg-neutral-100 rounded-lg transition-colors shadow-lg">
                        <span>Browse Products</span>
                    </a>
                    <a href="/auth/register-farmer" class="flex items-center justify-center gap-2 px-8 py-4 bg-accent text-white hover:bg-accent-hover rounded-lg transition-colors shadow-lg">
                        <span>Register as Farmer</span>
                    </a>
                </div>
            </div>
            <!-- Add hero image here -->
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-12 md:py-16">
    <div class="container">
        <h2 class="mb-8">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featured_products as $product): ?>
                <div class="bg-white rounded-xl shadow-md border-2 border-neutral-200 hover:border-primary transition-all overflow-hidden">
                    <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3><?= esc($product['name']) ?></h3>
                        <p class="text-primary text-2xl font-semibold">₱<?= number_format($product['price'], 2) ?></p>
                        <p class="text-sm text-neutral-600"><?= esc($product['location']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
```

**Create Layout: app/Views/layouts/main.php**:
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', true) ?? 'AgriConnect - Nasugbu Agricultural Marketplace' ?></title>
    <meta name="description" content="AgriConnect connects Nasugbu farmers directly with local buyers. Fresh produce, fair prices.">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/globals.css">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen flex flex-col bg-neutral-50">
    <!-- Navigation -->
    <?= view('components/navbar') ?>
    
    <!-- Main Content -->
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>
    
    <!-- Footer -->
    <?= view('components/footer') ?>
    
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
```

---

## Phase 2: Copy Static Assets

### Step 1: Copy CSS File

Copy `/styles/globals.css` to `public/assets/css/globals.css` in your CodeIgniter project.

### Step 2: Set Up Tailwind CSS

You have two options:

**Option A: Use CDN (Easier for deployment)**
Already included in the layout above.

**Option B: Compile Tailwind (Better performance)**
1. Install Tailwind in your CI4 project
2. Configure `tailwind.config.js` to scan your view files
3. Build and include in your layout

---

## Phase 3: Implement Authentication

### Security Best Practices

1. **Password Hashing**:
```php
// When registering
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// When logging in
password_verify($inputPassword, $storedHashedPassword);
```

2. **CSRF Protection** (Built into CodeIgniter):
```php
<?= csrf_field() ?>
```

3. **Session Management**:
```php
// In filters or controllers
if (!session()->get('logged_in')) {
    return redirect()->to('/auth/login');
}
```

4. **Create Authentication Filter** (app/Filters/Auth.php):
```php
<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // Do nothing
    }
}
```

---

## Phase 4: File Upload Handling

For product images:

```php
public function uploadProductImage() {
    $file = $this->request->getFile('product_image');
    
    if ($file->isValid() && !$file->hasMoved()) {
        // Validate file
        $validationRule = [
            'product_image' => [
                'uploaded[product_image]',
                'mime_in[product_image,image/jpg,image/jpeg,image/png]',
                'max_size[product_image,2048]', // 2MB max
            ],
        ];
        
        if (!$this->validate($validationRule)) {
            return redirect()->back()->with('error', 'Invalid image file');
        }
        
        // Generate new name
        $newName = $file->getRandomName();
        
        // Move to public/uploads/products
        $file->move(ROOTPATH . 'public/uploads/products', $newName);
        
        return '/uploads/products/' . $newName;
    }
    
    return null;
}
```

---

## Phase 5: API Endpoints (Optional - for AJAX)

If you want to keep some interactivity with JavaScript:

**app/Controllers/API/ProductsController.php**:
```php
<?php
namespace App\Controllers\API;
use CodeIgniter\RESTful\ResourceController;

class ProductsController extends ResourceController {
    protected $modelName = 'App\Models\ProductModel';
    protected $format = 'json';
    
    public function index() {
        return $this->respond($this->model->findAll());
    }
    
    public function show($id = null) {
        $product = $this->model->find($id);
        if ($product) {
            return $this->respond($product);
        }
        return $this->failNotFound('Product not found');
    }
}
```

---

## Phase 6: Deployment to Hostinger

### Step 1: Prepare Files

1. Upload CodeIgniter 4 project to Hostinger via FTP/SFTP or File Manager
2. Place in `public_html` or subdirectory

### Step 2: Configure Hostinger

1. **Set PHP Version**: Go to Hostinger control panel → PHP Configuration → Set to PHP 8.0+

2. **.htaccess Configuration** (in public folder):
```apache
# Disable directory browsing
Options -Indexes

# Follow symbolic links
Options +FollowSymLinks

# Prevent direct access to .env
<Files .env>
    order allow,deny
    Deny from all
</Files>

# Rewrite engine
RewriteEngine On
RewriteBase /

# Redirect to index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

3. **Environment Variables**: 
   - Copy `.env.example` to `.env`
   - Set production environment:
     ```
     CI_ENVIRONMENT = production
     ```

### Step 3: Database Setup on Hostinger

1. Create MySQL database via Hostinger control panel
2. Import your SQL schema
3. Update database credentials in `.env`

### Step 4: File Permissions

```bash
chmod 755 writable/
chmod 755 writable/cache/
chmod 755 writable/logs/
chmod 755 writable/session/
chmod 755 public/uploads/
```

### Step 5: SSL Certificate

1. Enable free SSL in Hostinger control panel
2. Force HTTPS in `.htaccess`:
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## Phase 7: Performance Optimization for Low Bandwidth

### 1. Image Optimization

```php
// Use image compression libraries
use \App\Libraries\ImageOptimizer;

$optimizer = new ImageOptimizer();
$optimizer->compress($imagePath, 80); // 80% quality
```

### 2. Enable Caching

In `app/Config/Cache.php`:
```php
public string $handler = 'file';
```

Cache views and queries:
```php
// Cache database queries
$products = cache()->remember('featured_products', 3600, function() {
    return $this->productModel->getAvailableProducts();
});
```

### 3. Minify CSS/JS

Use CodeIgniter's asset pipeline or tools like:
- CSSNano
- Terser
- Or include pre-minified versions

### 4. Lazy Loading Images

```html
<img src="placeholder.jpg" data-src="actual-image.jpg" loading="lazy" class="lazy">
```

### 5. Enable GZIP Compression

In `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```

---

## Phase 8: Mobile-First Responsive Design Checklist

✅ All layouts use Tailwind's mobile-first breakpoints  
✅ Touch targets minimum 48x48px (3rem)  
✅ Large, readable fonts (minimum 1rem for body text)  
✅ Simple navigation with hamburger menu on mobile  
✅ Forms use HTML5 input types for better mobile keyboards  
✅ Images are responsive with proper aspect ratios  
✅ Tested on actual mobile devices

---

## Phase 9: Testing Checklist

- [ ] Test all user roles (farmer, buyer, admin)
- [ ] Test authentication and authorization
- [ ] Test CRUD operations for products
- [ ] Test order flow
- [ ] Test messaging system
- [ ] Test file uploads
- [ ] Test on different devices (mobile, tablet, desktop)
- [ ] Test on different browsers (Chrome, Firefox, Safari)
- [ ] Test with slow internet connection (throttle in DevTools)
- [ ] Security testing (SQL injection, XSS, CSRF)

---

## Phase 10: Go Live

1. ✅ All tests passed
2. ✅ Database backed up
3. ✅ SSL enabled
4. ✅ .env configured for production
5. ✅ Error logging enabled
6. ✅ Monitoring set up

### Post-Launch

- Monitor error logs regularly
- Set up automated backups (daily)
- Monitor database performance
- Gather user feedback
- Plan iterative improvements

---

## Maintenance & Updates

### Regular Tasks
- Weekly database backups
- Monthly security updates
- Quarterly feature reviews
- Monitor server logs for errors

### Future Enhancements
- Mobile app (React Native/Flutter)
- SMS notifications
- Payment gateway integration
- Advanced analytics dashboard
- Multi-language support (English/Tagalog)

---

## Resources

- [CodeIgniter 4 Documentation](https://codeigniter.com/user_guide/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Hostinger Knowledge Base](https://support.hostinger.com/)
- [PHP Best Practices](https://phptherightway.com/)

---

## Support

For technical support:
- Email: dev@agriconnect.ph
- Documentation: https://docs.agriconnect.ph
- Community Forum: Use the built-in forum feature!

---

**Last Updated**: November 20, 2024  
**Version**: 1.0.0
