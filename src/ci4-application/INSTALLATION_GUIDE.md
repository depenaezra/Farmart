# AgriConnect CodeIgniter 4 - Installation Guide

## 📦 What's Included

This package contains the core CodeIgniter 4 application files for AgriConnect:

### ✅ Complete Files
1. **Database Schema** (`database/schema.sql`) - Ready to import
2. **Routes Configuration** (`app/Config/Routes.php`) - All routes defined
3. **Authentication Filter** (`app/Filters/AuthFilter.php`) - Security filter
4. **Models** - All 5 models complete:
   - UserModel.php
   - ProductModel.php
   - OrderModel.php
   - MessageModel.php
   - AnnouncementModel.php
5. **Controllers** - 3 main controllers:
   - AuthController.php (Login, Register)
   - Home.php (Landing page)
   - Farmer.php (Complete farmer features)

### ⏳ Files to Create (Templates Provided Below)
- Marketplace Controller
- Cart & Checkout Controllers
- Buyer Controller
- Messages Controller
- Forum Controller
- Admin Controller
- All PHP Views (21 pages)
- Configuration files
- .htaccess files

---

## 🚀 Quick Installation Steps

### Step 1: Set Up CodeIgniter 4

```bash
# 1. Install CodeIgniter 4
composer create-project codeigniter4/appstarter agriconnect-ci4

# 2. Navigate to project
cd agriconnect-ci4

# 3. Copy files from this package to your CI4 installation
```

### Step 2: File Structure

Copy files to these locations in your CI4 project:

```
agriconnect-ci4/
├── app/
│   ├── Config/
│   │   └── Routes.php           [COPY FROM ci4-application/app/Config/]
│   ├── Controllers/
│   │   ├── AuthController.php   [COPY FROM ci4-application/app/Controllers/]
│   │   ├── Home.php             [COPY FROM ci4-application/app/Controllers/]
│   │   ├── Farmer.php           [COPY FROM ci4-application/app/Controllers/]
│   │   └── [Create remaining controllers using templates below]
│   ├── Filters/
│   │   └── AuthFilter.php       [COPY FROM ci4-application/app/Filters/]
│   ├── Models/
│   │   ├── UserModel.php        [COPY FROM ci4-application/app/Models/]
│   │   ├── ProductModel.php     [COPY FROM ci4-application/app/Models/]
│   │   ├── OrderModel.php       [COPY FROM ci4-application/app/Models/]
│   │   ├── MessageModel.php     [COPY FROM ci4-application/app/Models/]
│   │   └── AnnouncementModel.php [COPY FROM ci4-application/app/Models/]
│   └── Views/
│       └── [Create all views using templates]
├── public/
│   ├── uploads/
│   │   └── products/            [CREATE THIS FOLDER]
│   ├── assets/
│   │   ├── css/
│   │   │   └── globals.css      [COPY FROM /styles/globals.css]
│   │   └── js/
│   │       └── main.js          [CREATE - optional JavaScript]
│   └── .htaccess                [MODIFY - see below]
└── writable/
    ├── cache/
    ├── logs/
    └── session/
```

### Step 3: Database Setup

```sql
-- 1. Create database
CREATE DATABASE agriconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. Import schema
mysql -u your_user -p agriconnect < database/schema.sql

-- Or use phpMyAdmin to import schema.sql
```

### Step 4: Configure Environment

```bash
# 1. Copy env file
cp env .env

# 2. Edit .env file
```

Edit `.env`:
```ini
# ENVIRONMENT
CI_ENVIRONMENT = development

# APP
app.baseURL = 'http://localhost:8080/'
# Change to your domain in production: 'https://agriconnect.ph/'

# DATABASE
database.default.hostname = localhost
database.default.database = agriconnect
database.default.username = your_db_user
database.default.password = your_db_password
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### Step 5: Configure Filters

Edit `app/Config/Filters.php`:

```php
public array $aliases = [
    'csrf'          => \CodeIgniter\Filters\CSRF::class,
    'toolbar'       => \CodeIgniter\Filters\DebugToolbar::class,
    'honeypot'      => \CodeIgniter\Filters\Honeypot::class,
    'invalidchars'  => \CodeIgniter\Filters\InvalidChars::class,
    'secureheaders' => \CodeIgniter\Filters\SecureHeaders::class,
    'auth'          => \App\Filters\AuthFilter::class, // ADD THIS LINE
];
```

### Step 6: Set File Permissions

```bash
chmod 755 public/uploads
chmod 755 public/uploads/products
chmod 755 writable
chmod 755 writable/cache
chmod 755 writable/logs
chmod 755 writable/session
```

### Step 7: Run Development Server

```bash
php spark serve
```

Visit: `http://localhost:8080`

---

## 📝 Additional Controllers Needed

### Marketplace Controller Template

Create `app/Controllers/Marketplace.php`:

```php
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
    
    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $category = $this->request->getGet('category');
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        
        $products = $this->productModel->searchProducts([
            'keyword' => $keyword,
            'category' => $category,
            'min_price' => $minPrice,
            'max_price' => $maxPrice
        ]);
        
        $data = [
            'title' => 'Marketplace',
            'products' => $products,
            'filters' => [
                'keyword' => $keyword,
                'category' => $category,
                'min_price' => $minPrice,
                'max_price' => $maxPrice
            ]
        ];
        
        return view('marketplace/index', $data);
    }
    
    public function product($id)
    {
        $product = $this->productModel->getProductWithFarmer($id);
        
        if (!$product) {
            return redirect()->to('/marketplace')
                ->with('error', 'Product not found.');
        }
        
        $data = [
            'title' => $product['name'],
            'product' => $product
        ];
        
        return view('marketplace/product_detail', $data);
    }
}
```

### Cart Controller Template

Create `app/Controllers/Cart.php`:

```php
<?php
namespace App\Controllers;

class Cart extends BaseController
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        
        $data = [
            'title' => 'Shopping Cart',
            'cart' => $cart
        ];
        
        return view('cart/index', $data);
    }
    
    public function add()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity') ?? 1;
        
        // Get product details
        $productModel = new \App\Models\ProductModel();
        $product = $productModel->find($productId);
        
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }
        
        // Get current cart
        $cart = session()->get('cart') ?? [];
        
        // Add to cart
        $cartItemId = 'cart_' . time() . '_' . $productId;
        $cart[$cartItemId] = [
            'id' => $cartItemId,
            'product_id' => $productId,
            'product_name' => $product['name'],
            'price' => $product['price'],
            'unit' => $product['unit'],
            'quantity' => $quantity,
            'farmer_id' => $product['farmer_id'],
            'image_url' => $product['image_url']
        ];
        
        session()->set('cart', $cart);
        
        return redirect()->back()->with('success', 'Product added to cart!');
    }
    
    public function update($cartItemId)
    {
        $cart = session()->get('cart') ?? [];
        $quantity = $this->request->getPost('quantity');
        
        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] = max(1, (int)$quantity);
            session()->set('cart', $cart);
        }
        
        return redirect()->to('/cart');
    }
    
    public function remove($cartItemId)
    {
        $cart = session()->get('cart') ?? [];
        unset($cart[$cartItemId]);
        session()->set('cart', $cart);
        
        return redirect()->to('/cart');
    }
    
    public function clear()
    {
        session()->remove('cart');
        return redirect()->to('/cart');
    }
}
```

---

## 🎨 View Templates

All views should extend the base layout. Here's the structure:

### Base Layout Template

Create `app/Views/layouts/main.php`:

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'AgriConnect') ?></title>
    <meta name="description" content="AgriConnect - Direct marketplace for Nasugbu farmers">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#2d7a3e',
                        'primary-hover': '#236330',
                        'secondary': '#8b6f47',
                        'accent': '#d97706',
                        'success': '#16a34a',
                        'warning': '#f59e0b',
                        'error': '#dc2626',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/globals.css">
</head>
<body>
    <?= view('components/navbar') ?>
    
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-success/10 border border-success text-success px-4 py-3 rounded relative mb-4 container mx-auto mt-4" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-error/10 border border-error text-error px-4 py-3 rounded relative mb-4 container mx-auto mt-4" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>
    
    <?= view('components/footer') ?>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
```

### Example View - Landing Page

Create `app/Views/landing.php`:

```php
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-[#2d7a3e] to-[#4a9b5a] text-white py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Direct Marketplace for Local Farmers
                </h1>
                <p class="text-xl mb-8">
                    Connecting Nasugbu farmers directly with buyers. Fresh produce, fair prices, strong community.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/marketplace" class="bg-white text-[#2d7a3e] px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 text-center">
                        Browse Products
                    </a>
                    <a href="/auth/register-farmer" class="bg-[#d97706] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#b45309] text-center">
                        Register as Farmer
                    </a>
                </div>
            </div>
            
            <div class="relative">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border-2 border-white/20">
                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=600" alt="Farmers" class="w-full h-64 object-cover rounded-xl">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8">Featured Products</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featured_products as $product): ?>
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-[#2d7a3e] transition-all overflow-hidden">
                    <?php if ($product['image_url']): ?>
                        <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No image</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2"><?= esc($product['name']) ?></h3>
                        <p class="text-[#2d7a3e] text-2xl font-bold mb-2">
                            ₱<?= number_format($product['price'], 2) ?>
                            <span class="text-sm text-gray-600">per <?= esc($product['unit']) ?></span>
                        </p>
                        <p class="text-sm text-gray-600">📍 <?= esc($product['location']) ?></p>
                        <p class="text-sm text-gray-600">👨‍🌾 <?= esc($product['farmer_name']) ?></p>
                        
                        <a href="/marketplace/product/<?= $product['id'] ?>" class="mt-4 block w-full bg-[#2d7a3e] text-white py-2 rounded-lg text-center hover:bg-[#236330]">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
```

---

## 🔧 Next Steps

1. ✅ Import database schema
2. ✅ Configure .env file
3. ⏳ Create remaining controllers (use templates above)
4. ⏳ Create all views (21 pages - use landing.php as template)
5. ⏳ Copy globals.css to public/assets/css/
6. ⏳ Test all functionality
7. ⏳ Deploy to Hostinger

---

## 📚 Reference

- See `/DEPLOYMENT_GUIDE.md` for complete deployment instructions
- See `/CONVERSION_CHECKLIST.md` for page-by-page breakdown
- See `/HTML_EXPORT_GUIDE.md` for HTML templates

---

## ✅ Login Credentials

Default password for all test users: `password123`

- **Farmer**: juan.santos@example.com
- **Buyer**: miguel.buyer@example.com
- **Admin**: admin@agriconnect.ph

---

## 🐛 Troubleshooting

### Database connection failed
- Check credentials in .env
- Ensure MySQL is running
- Verify database exists

### Permission denied errors
- Check folder permissions: `chmod 755 writable/`
- Check uploads folder: `chmod 755 public/uploads/`

### Routes not working
- Check .htaccess in public folder
- Enable mod_rewrite in Apache
- Check base URL in .env

---

**Status**: Core files complete, views need to be created  
**Time to complete**: 4-6 hours for remaining views  
**Difficulty**: Medium
