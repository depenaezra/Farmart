# AgriConnect - CodeIgniter 4 Application

Direct marketplace connecting Nasugbu farmers with local buyers.

## 🚀 Quick Start Guide

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB
- Composer
- Apache/Nginx with mod_rewrite enabled

### Installation Steps

#### 1. Install CodeIgniter 4

```bash
# Create new CI4 project
composer create-project codeigniter4/appstarter agriconnect-ci4

# Navigate to project
cd agriconnect-ci4
```

#### 2. Copy Application Files

Copy all files from this `/ci4-application` folder into your CodeIgniter 4 installation:

```bash
# Copy Controllers
cp -r ci4-application/app/Controllers/* app/Controllers/

# Copy Models
cp -r ci4-application/app/Models/* app/Models/

# Copy Views
cp -r ci4-application/app/Views/* app/Views/

# Copy Filters
cp -r ci4-application/app/Filters/* app/Filters/

# Copy Config
cp ci4-application/app/Config/Routes.php app/Config/Routes.php

# Copy environment file
cp ci4-application/env.example .env

# Copy .htaccess
cp ci4-application/public/.htaccess public/.htaccess
```

#### 3. Create Database

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE agriconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Import schema
mysql -u root -p agriconnect < ci4-application/database/schema.sql
```

Or use phpMyAdmin:
1. Create database named `agriconnect`
2. Import `/ci4-application/database/schema.sql`

#### 4. Configure Environment

Edit `.env` file:

```ini
# Set environment
CI_ENVIRONMENT = development

# Set base URL
app.baseURL = 'http://localhost:8080/'

# Database credentials
database.default.hostname = localhost
database.default.database = agriconnect
database.default.username = root
database.default.password = YOUR_PASSWORD
database.default.DBDriver = MySQLi
database.default.port = 3306
```

#### 5. Configure Auth Filter

Edit `app/Config/Filters.php` and add the auth filter to aliases:

```php
public array $aliases = [
    'csrf'          => \CodeIgniter\Filters\CSRF::class,
    'toolbar'       => \CodeIgniter\Filters\DebugToolbar::class,
    'honeypot'      => \CodeIgniter\Filters\Honeypot::class,
    'invalidchars'  => \CodeIgniter\Filters\InvalidChars::class,
    'secureheaders' => \CodeIgniter\Filters\SecureHeaders::class,
    'auth'          => \App\Filters\AuthFilter::class,  // ADD THIS LINE
];
```

#### 6. Create Upload Directory

```bash
# Create uploads folder
mkdir -p public/uploads/products
chmod 755 public/uploads
chmod 755 public/uploads/products

# Ensure writable permissions
chmod 755 writable
chmod 755 writable/cache
chmod 755 writable/logs
chmod 755 writable/session
```

#### 7. Run Development Server

```bash
php spark serve
```

Visit: http://localhost:8080

## ✅ Demo Accounts

The database comes with pre-populated demo accounts:

| Role | Email | Password |
|------|-------|----------|
| **Farmer** | juan.santos@example.com | password123 |
| **Buyer** | miguel.buyer@example.com | password123 |
| **Admin** | admin@agriconnect.ph | password123 |

## 📁 Project Structure

```
agriconnect-ci4/
├── app/
│   ├── Config/
│   │   └── Routes.php              ✅ All routes configured
│   ├── Controllers/
│   │   ├── AuthController.php      ✅ Login/Register
│   │   ├── Home.php                ✅ Landing page
│   │   ├── Farmer.php              ✅ Farmer features
│   │   ├── Buyer.php               ✅ Buyer features
│   │   ├── Marketplace.php         ✅ Product browsing
│   │   ├── Cart.php                ✅ Shopping cart
│   │   ├── Checkout.php            ✅ Order processing
│   │   ├── Messages.php            ✅ Messaging system
│   │   ├── Forum.php               ✅ Community forum
│   │   ├── Announcements.php       ✅ Announcements
│   │   ├── Weather.php             ✅ Weather info
│   │   └── Admin.php               ✅ Admin panel
│   ├── Models/
│   │   ├── UserModel.php           ✅ User management
│   │   ├── ProductModel.php        ✅ Product management
│   │   ├── OrderModel.php          ✅ Order management
│   │   ├── MessageModel.php        ✅ Messages
│   │   └── AnnouncementModel.php   ✅ Announcements
│   ├── Filters/
│   │   └── AuthFilter.php          ✅ Authentication
│   └── Views/
│       ├── layouts/
│       │   └── main.php            ✅ Base layout
│       ├── components/
│       │   ├── navbar.php          ✅ Navigation
│       │   └── footer.php          ✅ Footer
│       ├── auth/                   ✅ Login/Register pages
│       ├── marketplace/            ✅ Marketplace views
│       └── [other views]
├── public/
│   ├── uploads/
│   │   └── products/               📁 Product images
│   └── .htaccess                   ✅ URL rewriting
├── writable/
│   ├── cache/
│   ├── logs/
│   └── session/
└── .env                            ⚙️ Configuration

```

## 🎯 What's Included

### ✅ Complete Features

1. **Authentication System**
   - Login/Logout
   - Farmer registration
   - Buyer registration
   - Role-based access control
   - Session management

2. **Farmer Features**
   - Dashboard with statistics
   - Add/edit/delete products
   - Product image upload
   - Inventory management
   - View and manage orders
   - Update order status

3. **Buyer Features**
   - Browse marketplace
   - Search and filter products
   - Add to cart
   - Checkout and place orders
   - View order history
   - Cancel orders

4. **Marketplace**
   - Product listing with filters
   - Category filtering
   - Price range filtering
   - Location-based search
   - Product details page

5. **Communication**
   - Direct messaging system
   - Inbox/sent messages
   - Message composition
   - Reply functionality

6. **Community**
   - Forum posts
   - Comments
   - Likes
   - Category organization

7. **Admin Panel**
   - User management
   - Product moderation
   - Announcement management
   - System statistics
   - Analytics dashboard

8. **Additional Features**
   - Weather information
   - Government announcements
   - Market updates
   - Responsive mobile-first design

## 🧪 Testing

### Test the Application

1. **Landing Page**: http://localhost:8080
2. **Marketplace**: http://localhost:8080/marketplace
3. **Login**: http://localhost:8080/auth/login
4. **Register**: http://localhost:8080/auth/register-farmer

### Test User Flows

**As a Farmer:**
1. Login with `juan.santos@example.com` / `password123`
2. Go to Farmer Dashboard
3. Add a new product
4. View inventory
5. Check orders

**As a Buyer:**
1. Login with `miguel.buyer@example.com` / `password123`
2. Browse marketplace
3. Add items to cart
4. Proceed to checkout
5. Place order

**As an Admin:**
1. Login with `admin@agriconnect.ph` / `password123`
2. View admin dashboard
3. Manage users
4. Moderate products
5. Create announcements

## 🔧 Configuration

### Database

- Default database: `agriconnect`
- Character set: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`
- Sample data included

### File Uploads

- Location: `public/uploads/products/`
- Supported formats: JPG, PNG, GIF
- Max size: 2MB (configurable)

### Sessions

- Driver: File-based
- Expiration: 2 hours
- Location: `writable/session/`

## 📝 Next Steps

### To Complete Views (if needed)

Some advanced views may need creation. Check `/ci4-application/INSTALLATION_GUIDE.md` for:
- Additional page templates
- View structure examples
- Component patterns

### For Production Deployment

1. Change `CI_ENVIRONMENT` to `production` in `.env`
2. Update `app.baseURL` to your domain
3. Set strong database password
4. Configure HTTPS
5. Set up backup system
6. Enable error logging
7. Optimize database queries

See `/ci4-application/INSTALLATION_GUIDE.md` for detailed deployment steps.

## 🐛 Troubleshooting

### Common Issues

**Database connection failed**
```
Solution: Check credentials in .env file
- Verify MySQL is running
- Test connection with: mysql -u root -p
```

**404 on all routes**
```
Solution: Enable mod_rewrite
- Apache: sudo a2enmod rewrite
- Check .htaccess in public folder
- Verify AllowOverride All in Apache config
```

**Permission denied errors**
```
Solution: Set correct permissions
- chmod 755 writable/
- chmod 755 public/uploads/
- chown -R www-data:www-data writable/
```

**Cannot upload images**
```
Solution: Check upload directory
- mkdir -p public/uploads/products
- chmod 755 public/uploads/products
- Check php.ini upload_max_filesize
```

## 📚 Additional Resources

- CodeIgniter 4 Documentation: https://codeigniter.com/user_guide/
- Database Schema: `/ci4-application/database/schema.sql`
- Installation Guide: `/ci4-application/INSTALLATION_GUIDE.md`
- Deployment Status: `/CI4_DEPLOYMENT_STATUS.md`

## 🎉 Success!

If you can see the landing page and login successfully, you're all set!

**Default credentials:**
- Farmer: `juan.santos@example.com` / `password123`
- Buyer: `miguel.buyer@example.com` / `password123`
- Admin: `admin@agriconnect.ph` / `password123`

---

**Built for**: Nasugbu Agricultural Community  
**Version**: 1.0.0  
**Date**: November 2024
