# ⚡ AgriConnect - 5-Minute Setup Guide

Get AgriConnect running in 5 minutes!

## Prerequisites Checklist

- [ ] PHP 7.4+ installed
- [ ] MySQL/MariaDB installed
- [ ] Composer installed
- [ ] Terminal/Command Prompt access

## Setup Steps

### Step 1: Install CodeIgniter 4 (2 minutes)

```bash
# Install CodeIgniter 4
composer create-project codeigniter4/appstarter agriconnect-ci4

# Navigate to project
cd agriconnect-ci4
```

### Step 2: Copy Files (1 minute)

From the `ci4-application` folder, copy these files into your CI4 project:

**Required Files:**
```
ci4-application/app/Controllers/     → agriconnect-ci4/app/Controllers/
ci4-application/app/Models/          → agriconnect-ci4/app/Models/
ci4-application/app/Views/           → agriconnect-ci4/app/Views/
ci4-application/app/Filters/         → agriconnect-ci4/app/Filters/
ci4-application/app/Config/Routes.php → agriconnect-ci4/app/Config/Routes.php
```

### Step 3: Database Setup (1 minute)

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE agriconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import schema (adjust path as needed)
mysql -u root -p agriconnect < ../ci4-application/database/schema.sql
```

### Step 4: Configure (30 seconds)

```bash
# Copy environment file
cp env .env

# Edit .env (use nano, vim, or any text editor)
nano .env
```

Edit these lines in `.env`:
```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.database = agriconnect
database.default.username = root
database.default.password = YOUR_MYSQL_PASSWORD
```

### Step 5: Add Auth Filter (30 seconds)

Edit `app/Config/Filters.php`, find the `$aliases` array and add:

```php
public array $aliases = [
    // ... existing filters ...
    'auth' => \App\Filters\AuthFilter::class,  // ADD THIS LINE
];
```

### Step 6: Create Upload Folder (30 seconds)

```bash
mkdir -p public/uploads/products
chmod -R 755 public/uploads
chmod -R 755 writable
```

### Step 7: Run! (10 seconds)

```bash
php spark serve
```

## ✅ Verify Installation

Open your browser and visit: **http://localhost:8080**

You should see the AgriConnect landing page!

## 🔐 Test Login

Try these demo accounts:

**Farmer Account:**
- Email: `juan.santos@example.com`
- Password: `password123`
- Features: Dashboard, Add products, Manage orders

**Buyer Account:**
- Email: `miguel.buyer@example.com`
- Password: `password123`
- Features: Browse marketplace, Cart, Checkout

**Admin Account:**
- Email: `admin@agriconnect.ph`
- Password: `password123`
- Features: Full admin panel

## 🎯 What to Test

1. ✅ **Landing Page** - Browse featured products
2. ✅ **Marketplace** - Search and filter products
3. ✅ **Login** - Try all three account types
4. ✅ **Farmer Dashboard** - Add a product
5. ✅ **Buyer Flow** - Add to cart, checkout
6. ✅ **Admin Panel** - View statistics

## 🚨 Quick Troubleshooting

### "404 Page Not Found" on all pages?
```bash
# Enable mod_rewrite (Apache)
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### "Database connection failed"?
```bash
# Check MySQL is running
sudo systemctl status mysql

# Test connection
mysql -u root -p

# Verify credentials in .env match your MySQL setup
```

### "Permission denied" errors?
```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

### Can't upload images?
```bash
# Make sure folder exists and is writable
mkdir -p public/uploads/products
chmod -R 755 public/uploads/
```

## 📸 Screenshot Checklist

You should be able to see:
- ✅ Landing page with green hero section
- ✅ Navigation bar with logo
- ✅ Featured products grid
- ✅ Login page with forms
- ✅ Farmer dashboard with statistics
- ✅ Marketplace with product listings
- ✅ Admin panel with user management

## 🎉 Success Indicators

If you can do these, you're successfully running:

1. ✅ See the landing page
2. ✅ Login with demo account
3. ✅ Navigate to different pages
4. ✅ See products in marketplace
5. ✅ Access farmer/buyer/admin dashboards

## 📞 Need Help?

Check these files for more info:
- Full guide: `/ci4-application/README.md`
- Installation details: `/ci4-application/INSTALLATION_GUIDE.md`
- Status report: `/CI4_DEPLOYMENT_STATUS.md`

---

**Total Setup Time**: ~5 minutes  
**Files Created**: All controllers, models, views  
**Database**: Pre-populated with sample data  
**Status**: Production-ready prototype
