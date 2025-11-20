# AgriConnect - Complete Site Map & Navigation Flow

## 🗺️ Visual Site Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        AGRICONNECT                              │
│                  Nasugbu Agricultural Marketplace               │
└─────────────────────────────────────────────────────────────────┘
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     LANDING PAGE (/)                            │
│  • Hero Section with CTAs                                       │
│  • Quick Access Icons (Weather, Announcements, Forum)           │
│  • Featured Products Grid                                       │
│  • Why AgriConnect Section                                      │
│  • Call to Action                                               │
└─────────────────────────────────────────────────────────────────┘
              │                │                │
              ▼                ▼                ▼
      ┌──────────┐    ┌──────────┐    ┌──────────┐
      │ LOGIN    │    │MARKETPLACE│    │ WEATHER  │
      └──────────┘    └──────────┘    └──────────┘
```

---

## 📍 Main Navigation Structure

### Public Access (No Login Required)

```
PUBLIC PAGES
├── 🏠 Landing Page (/)
│   └── Featured products, Quick access, CTA buttons
│
├── 🛒 Marketplace (/marketplace)
│   ├── Product Grid
│   ├── Search & Filters
│   ├── Category Tabs
│   └── → Product Detail (/marketplace/product/{id})
│       ├── Product Images
│       ├── Full Description
│       ├── Farmer Info
│       ├── Add to Cart (requires login)
│       └── Message Farmer (requires login)
│
├── ☁️ Weather (/weather)
│   ├── Current Weather
│   ├── 7-Day Forecast
│   ├── Agricultural Advisories
│   └── Planting Calendar
│
└── 📢 Announcements (/announcements)
    ├── Government Programs
    ├── Weather Alerts
    ├── Market Updates
    └── General News
```

### Authentication Flow

```
AUTHENTICATION
├── 🔐 Login (/auth/login)
│   ├── Email/Phone Input
│   ├── Password Input
│   ├── Role Selection (Farmer/Buyer/Admin)
│   └── → Redirect based on role
│       ├── Farmer → Farmer Dashboard
│       ├── Buyer → Marketplace
│       └── Admin → Admin Dashboard
│
├── 👨‍🌾 Register Farmer (/auth/register-farmer)
│   ├── Personal Info
│   ├── Contact Details
│   ├── Location/Barangay
│   ├── Cooperative Selection
│   └── → Login Page
│
└── 🛍️ Register Buyer (/auth/register-buyer)
    ├── Personal Info
    ├── Contact Details
    ├── Delivery Address
    └── → Login Page
```

---

## 👨‍🌾 Farmer Portal Navigation

```
FARMER DASHBOARD (/farmer/dashboard)
├── 📊 Statistics Cards
│   ├── Total Products
│   ├── Pending Orders
│   ├── Completed Orders
│   └── Total Sales
│
├── ⚡ Quick Actions
│   ├── → Add Product
│   ├── → Manage Inventory
│   ├── → View Orders
│   └── → Messages
│
├── 📦 Recent Orders Widget
│   └── → Farmer Orders Page
│
└── 📢 Announcements Widget
    └── → Announcements Page

FARMER PRODUCTS
├── ➕ Add Product (/farmer/products/add)
│   ├── Product Form
│   │   ├── Name, Description
│   │   ├── Price, Unit
│   │   ├── Category Selection
│   │   ├── Stock Quantity
│   │   ├── Location
│   │   └── Image Upload
│   └── → Inventory after save
│
└── 📋 Inventory (/farmer/inventory)
    ├── Product List Table
    ├── Edit Product Button → Edit Form
    ├── Delete Product Button → Confirmation
    ├── Stock Status Indicators
    └── Quick Stock Update

FARMER ORDERS (/farmer/orders)
├── Orders List
│   ├── Filter by Status
│   ├── Order Cards
│   │   ├── Order ID
│   │   ├── Buyer Info
│   │   ├── Product Details
│   │   ├── Status Badge
│   │   └── Date
│   └── → Order Detail View
│
└── Order Management
    ├── Update Status
    │   ├── Confirm Order
    │   ├── Mark as Processing
    │   ├── Mark as Completed
    │   └── Cancel Order
    └── Contact Buyer Button → Messages
```

---

## 🛍️ Buyer Portal Navigation

```
BUYER SHOPPING FLOW
├── 🛒 Marketplace (/marketplace)
│   ├── Product Grid
│   ├── Search Bar
│   ├── Category Filters
│   ├── Price Range Filter
│   └── Product Cards
│       └── → Product Detail
│
├── 📦 Product Detail (/marketplace/product/{id})
│   ├── Product Gallery
│   ├── Price & Availability
│   ├── Farmer Information
│   ├── Quantity Selector
│   ├── Add to Cart Button → Cart Updated
│   └── Message Farmer → Inbox
│
├── 🛒 Shopping Cart (/cart)
│   ├── Cart Items List
│   ├── Quantity Adjustment
│   ├── Remove Item
│   ├── Subtotal Calculation
│   └── Proceed to Checkout → Checkout Page
│
├── 💳 Checkout (/checkout)
│   ├── Order Summary
│   ├── Delivery Address Form
│   ├── Payment Method Selection
│   ├── Order Notes
│   ├── Total Calculation
│   └── Place Order → Order Confirmation
│
└── 📋 Order History (/buyer/orders)
    ├── Orders List
    ├── Filter by Status
    ├── Order Cards
    │   ├── Order ID
    │   ├── Farmer Info
    │   ├── Product Details
    │   ├── Status Tracking
    │   └── Order Date
    ├── Reorder Button
    └── Cancel Order (if pending)
```

---

## 💬 Communication Features

```
MESSAGING SYSTEM (/messages)
├── 📥 Inbox Tab
│   ├── Received Messages List
│   ├── Unread Indicators
│   ├── Search Messages
│   └── → Message Thread
│
├── 📤 Sent Tab
│   ├── Sent Messages List
│   └── → Message Thread
│
├── ✉️ Compose Message (/messages/compose)
│   ├── Recipient Selection
│   ├── Subject Input
│   ├── Message Body
│   └── Send Button
│
└── 💬 Message Thread (/messages/{id})
    ├── Conversation History
    ├── Message Details
    └── Reply Form

COMMUNITY FORUM (/forum)
├── 📝 Forum Topics List
│   ├── Category Filter
│   ├── Search Posts
│   ├── Sort Options
│   └── Topic Cards
│       ├── Title
│       ├── Author
│       ├── Likes Count
│       ├── Comments Count
│       └── Date
│
├── ➕ Create New Post (/forum/create)
│   ├── Title Input
│   ├── Category Selection
│   ├── Content Editor
│   └── Post Button
│
└── 📖 View Topic (/forum/post/{id})
    ├── Post Content
    ├── Author Info
    ├── Like Button
    ├── Comments Section
    └── Add Comment Form
```

---

## 👤 Admin Panel Navigation

```
ADMIN DASHBOARD (/admin)
├── 📊 System Statistics
│   ├── Total Users (by role)
│   ├── Total Products
│   ├── Total Orders
│   ├── Revenue Overview
│   └── Recent Activity
│
├── ⚡ Quick Actions
│   ├── → User Management
│   ├── → Product Moderation
│   ├── → Post Announcement
│   └── → View Reports
│
└── 📈 Analytics Charts
    ├── User Growth
    ├── Order Trends
    └── Product Categories

ADMIN USER MANAGEMENT (/admin/users)
├── Users List
│   ├── Search Users
│   ├── Filter by Role
│   ├── User Cards/Table
│   │   ├── Name, Email
│   │   ├── Role Badge
│   │   ├── Status (Active/Inactive)
│   │   ├── Join Date
│   │   └── Actions
│   └── Pagination
│
└── User Actions
    ├── → View Profile
    ├── Toggle Active Status
    ├── Delete User (with confirmation)
    └── Change Role

ADMIN PRODUCT MODERATION (/admin/products)
├── Products List
│   ├── Search Products
│   ├── Filter by Status/Category
│   ├── Product Cards/Table
│   │   ├── Product Info
│   │   ├── Farmer Name
│   │   ├── Status
│   │   ├── Price
│   │   └── Actions
│   └── Pagination
│
└── Product Actions
    ├── Approve Product
    ├── Reject Product
    ├── Edit Product Details
    └── Delete Product

ADMIN ANNOUNCEMENTS
├── 📋 View All Announcements (/admin/announcements)
│   ├── Announcements List
│   ├── Filter by Category
│   └── → Edit/Delete
│
└── ➕ Create Announcement (/admin/announcements/create)
    ├── Title Input
    ├── Content Editor
    ├── Category Selection
    │   ├── Weather
    │   ├── Government
    │   ├── Market
    │   └── General
    ├── Priority Selection
    │   ├── Low
    │   ├── Medium
    │   └── High
    ├── Preview
    └── Publish Button
```

---

## 🔐 Access Control Matrix

```
PERMISSION MATRIX
┌──────────────────┬───────┬────────┬───────┬───────┐
│ Feature          │ Guest │ Farmer │ Buyer │ Admin │
├──────────────────┼───────┼────────┼───────┼───────┤
│ Landing Page     │   ✅  │   ✅   │  ✅   │  ✅   │
│ Browse Market    │   ✅  │   ✅   │  ✅   │  ✅   │
│ View Product     │   ✅  │   ✅   │  ✅   │  ✅   │
│ Weather          │   ✅  │   ✅   │  ✅   │  ✅   │
│ Announcements    │   ✅  │   ✅   │  ✅   │  ✅   │
│ Forum (Read)     │   ✅  │   ✅   │  ✅   │  ✅   │
├──────────────────┼───────┼────────┼───────┼───────┤
│ Forum (Post)     │   ❌  │   ✅   │  ✅   │  ✅   │
│ Messages         │   ❌  │   ✅   │  ✅   │  ✅   │
├──────────────────┼───────┼────────┼───────┼───────┤
│ Add Product      │   ❌  │   ✅   │  ❌   │  ✅   │
│ Edit Own Product │   ❌  │   ✅   │  ❌   │  ✅   │
│ Manage Inventory │   ❌  │   ✅   │  ❌   │  ✅   │
│ View Own Orders  │   ❌  │   ✅   │  ❌   │  ✅   │
│ (as farmer)      │       │        │       │       │
├──────────────────┼───────┼────────┼───────┼───────┤
│ Add to Cart      │   ❌  │   ❌   │  ✅   │  ✅   │
│ Checkout         │   ❌  │   ❌   │  ✅   │  ✅   │
│ Order History    │   ❌  │   ❌   │  ✅   │  ✅   │
│ (as buyer)       │       │        │       │       │
├──────────────────┼───────┼────────┼───────┼───────┤
│ User Management  │   ❌  │   ❌   │  ❌   │  ✅   │
│ Moderate Products│   ❌  │   ❌   │  ❌   │  ✅   │
│ Post Announce    │   ❌  │   ❌   │  ❌   │  ✅   │
│ System Analytics │   ❌  │   ❌   │  ❌   │  ✅   │
└──────────────────┴───────┴────────┴───────┴───────┘
```

---

## 🔄 User Journey Maps

### Journey 1: New Farmer Registration to First Sale

```
1. Landing Page
   ↓ Click "Register as Farmer"
2. Register Farmer Page
   ↓ Fill form, submit
3. Login Page
   ↓ Enter credentials
4. Farmer Dashboard
   ↓ Click "Add Product"
5. Add Product Page
   ↓ Fill product details, upload image
6. Inventory Page
   ↓ Product now listed
7. Wait for buyer...
8. Notification of new order
   ↓ Navigate to Orders
9. Farmer Orders Page
   ↓ View order details
10. Update order status
    ↓ Mark as completed
11. ✅ First sale complete!
```

### Journey 2: Buyer Purchase Flow

```
1. Landing Page
   ↓ Browse featured products OR click "Browse Products"
2. Marketplace
   ↓ Search/filter products
3. Product Detail
   ↓ Click "Add to Cart"
4. Cart icon shows (1)
   ↓ Continue shopping OR go to cart
5. Shopping Cart
   ↓ Review items, adjust quantity
6. Click "Proceed to Checkout"
7. Checkout Page
   ↓ Enter delivery address
8. Place Order
9. Order Confirmation
   ↓ Navigate to Order History
10. Track order status
11. ✅ Order received!
```

### Journey 3: Admin Moderating Content

```
1. Admin Login
   ↓ Credentials verified
2. Admin Dashboard
   ↓ View statistics
3. Click "Product Moderation"
4. Product Moderation Page
   ↓ See pending products
5. Review product details
   ↓ Approve OR Reject
6. Product status updated
7. Farmer notified
8. ✅ Moderation complete!
```

---

## 📱 Mobile Navigation Pattern

```
MOBILE MENU (Hamburger)
├── 🏠 Home
├── 🛒 Marketplace
├── ☁️ Weather
├── 📢 Announcements
├── 💬 Community Forum
│
├── [If Logged In]
│   ├── 📊 Dashboard (Farmer/Admin only)
│   ├── 📦 My Orders
│   ├── 💬 Messages
│   ├── 🛒 Cart (Buyer only)
│   └── 🚪 Logout
│
└── [If Not Logged In]
    └── 🔐 Login
```

---

## 🔗 Internal Linking Strategy

### Footer Links (All Pages)
```
FOOTER
├── Quick Links
│   ├── Marketplace
│   ├── Weather Updates
│   ├── Announcements
│   └── Community Forum
│
├── Contact Info
│   ├── Phone
│   ├── Email
│   └── Location
│
└── Legal
    ├── Privacy Policy (future)
    ├── Terms of Service (future)
    └── About Us (future)
```

### Breadcrumb Examples
```
Farmer Dashboard:
Home > Farmer Dashboard

Add Product:
Home > Farmer Dashboard > Add Product

Product Detail:
Home > Marketplace > [Product Name]

Order History:
Home > My Orders

Admin Users:
Home > Admin > User Management
```

---

## 📊 Page Priority & Frequency

### High Priority (Most Visited)
1. 🏠 Landing Page - Entry point
2. 🛒 Marketplace - Main feature
3. 👨‍🌾 Farmer Dashboard - Farmer hub
4. 📦 Product Detail - Product info
5. 🔐 Login - Access control

### Medium Priority (Regular Use)
6. 🛒 Cart - Buyer checkout
7. 📋 Inventory - Farmer management
8. 📦 Orders - Both farmers & buyers
9. 💬 Messages - Communication
10. ☁️ Weather - Daily checks

### Lower Priority (Occasional Use)
11. 📢 Announcements - Weekly checks
12. 💬 Forum - Community engagement
13. 👤 Admin Panel - Admin only
14. 📝 Register - One-time use
15. ✉️ Checkout - Purchase completion

---

## 🎯 Call-to-Action Flow

### Primary CTAs by Page

```
Landing Page:
  → "Browse Products" → Marketplace
  → "Register as Farmer" → Register Farmer

Marketplace:
  → "View Details" → Product Detail
  → "Apply Filters" → Filtered Results

Product Detail:
  → "Add to Cart" → Cart Updated
  → "Message Farmer" → Inbox

Cart:
  → "Proceed to Checkout" → Checkout

Checkout:
  → "Place Order" → Order Confirmation

Farmer Dashboard:
  → "Add Product" → Add Product Form
  → "View Orders" → Orders Page

Admin Dashboard:
  → "Manage Users" → User Management
  → "Moderate Products" → Product Moderation
```

---

## 📈 Navigation Analytics (Future)

### Metrics to Track Post-Launch
- Most visited pages
- Average session duration per page
- Drop-off points in checkout flow
- Most used navigation paths
- Mobile vs desktop usage
- Search queries in marketplace
- Time to complete registration
- Farmer product posting frequency

---

## 🗺️ Site Map Summary

```
TOTAL PAGES: 21
├── Public: 5 pages
├── Auth: 3 pages
├── Farmer: 4 pages
├── Buyer: 3 pages
├── Community: 2 pages
└── Admin: 4 pages

TOTAL COMPONENTS: 60+
├── Main Pages: 21
├── UI Components: 40+
└── Layout: 1 (Nav + Footer)

TOTAL ROUTES: 30+
```

---

**Navigation Complexity**: Medium  
**User Flow**: Intuitive  
**Mobile Optimized**: ✅ Yes  
**Accessibility**: Basic (can be enhanced)  

---

**Last Updated**: November 20, 2024  
**Version**: 1.0.0  
**Status**: Complete & Ready
