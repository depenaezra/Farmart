# AgriConnect - HTML Export Guide

## Quick Static HTML Export (For Reference/Prototyping)

If you need to quickly export the current React application as static HTML files for client review or as a template reference, follow this guide.

---

## Method 1: Using Browser "Save As"

### Steps:

1. **Run the application** in development mode
2. **Navigate to each page** you want to export
3. **Right-click** → **Save As** → **Webpage, Complete**
4. **Organize** the files into a folder structure

### Limitations:
- Breaks interactivity
- Hardcoded state values
- Not suitable for production

---

## Method 2: Manual HTML Templates

Create standalone HTML files for each page that can be used as CodeIgniter view templates.

### Base HTML Template Structure

**template-base.html**:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AgriConnect - Direct marketplace for Nasugbu farmers">
    <title>AgriConnect - Nasugbu Agricultural Marketplace</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#2d7a3e',
                        'primary-hover': '#236330',
                        'primary-light': '#4a9b5a',
                        'secondary': '#8b6f47',
                        'secondary-hover': '#6f5838',
                        'accent': '#d97706',
                        'accent-hover': '#b45309',
                        'success': '#16a34a',
                        'warning': '#f59e0b',
                        'error': '#dc2626',
                        'info': '#3b82f6',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <style>
        /* Import from globals.css */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            color: #292524;
            background-color: #ffffff;
        }
        
        h1 {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
            color: #1c1917;
        }
        
        h2 {
            font-size: 1.75rem;
            font-weight: 600;
            line-height: 1.3;
            color: #1c1917;
        }
        
        h3 {
            font-size: 1.5rem;
            font-weight: 600;
            line-height: 1.3;
            color: #1c1917;
        }
        
        button {
            font-size: 1.125rem;
            font-weight: 500;
            min-height: 3rem;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
        }
        
        input, textarea, select {
            font-size: 1.125rem;
            min-height: 3rem;
            padding: 0.75rem 1rem;
        }
        
        label {
            font-size: 1.125rem;
            font-weight: 500;
            color: #44403c;
        }
        
        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        @media (min-width: 768px) {
            .container {
                padding: 0 2rem;
            }
        }
    </style>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen flex flex-col bg-neutral-50">
    
    <!-- Navigation Bar -->
    <nav class="bg-white border-b-2 border-[#2d7a3e] sticky top-0 z-50 shadow-sm">
        <div class="container">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-[#2d7a3e] rounded-lg flex items-center justify-center">
                        <span class="text-white text-2xl">🌾</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[#2d7a3e] font-bold text-xl">AgriConnect</span>
                        <span class="text-neutral-600 text-xs">Nasugbu, Batangas</span>
                    </div>
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="/" class="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-[#2d7a3e] hover:bg-neutral-100 rounded-lg transition-colors">
                        <i data-lucide="home" class="w-5 h-5"></i>
                        <span>Home</span>
                    </a>
                    <a href="/marketplace" class="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-[#2d7a3e] hover:bg-neutral-100 rounded-lg transition-colors">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        <span>Marketplace</span>
                    </a>
                    <a href="/weather" class="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-[#2d7a3e] hover:bg-neutral-100 rounded-lg transition-colors">
                        <i data-lucide="cloud" class="w-5 h-5"></i>
                        <span>Weather</span>
                    </a>
                    <a href="/announcements" class="flex items-center gap-2 px-4 py-2 text-neutral-700 hover:text-[#2d7a3e] hover:bg-neutral-100 rounded-lg transition-colors">
                        <i data-lucide="megaphone" class="w-5 h-5"></i>
                        <span>Announcements</span>
                    </a>
                    <a href="/login" class="flex items-center gap-2 px-6 py-2 bg-[#2d7a3e] text-white hover:bg-[#236330] rounded-lg transition-colors">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span>Login</span>
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 text-neutral-700 hover:text-[#2d7a3e]">
                    <i data-lucide="menu" class="w-8 h-8"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-neutral-200 shadow-lg">
            <div class="container py-4 space-y-2">
                <a href="/" class="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
                    <i data-lucide="home" class="w-6 h-6"></i>
                    <span>Home</span>
                </a>
                <a href="/marketplace" class="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                    <span>Marketplace</span>
                </a>
                <a href="/weather" class="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
                    <i data-lucide="cloud" class="w-6 h-6"></i>
                    <span>Weather</span>
                </a>
                <a href="/announcements" class="w-full flex items-center gap-3 px-4 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
                    <i data-lucide="megaphone" class="w-6 h-6"></i>
                    <span>Announcements</span>
                </a>
                <div class="border-t border-neutral-200 pt-2 mt-2">
                    <a href="/login" class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-[#2d7a3e] text-white hover:bg-[#236330] rounded-lg transition-colors">
                        <i data-lucide="user" class="w-6 h-6"></i>
                        <span>Login</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="flex-1">
        <!-- PAGE CONTENT GOES HERE -->
    </main>
    
    <!-- Footer -->
    <footer class="bg-neutral-800 text-white mt-auto">
        <div class="container py-8 md:py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-white mb-4">AgriConnect</h3>
                    <p class="text-neutral-300">
                        Direct marketplace connecting Nasugbu farmers with local buyers and cooperatives.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-white mb-4">Quick Links</h4>
                    <div class="space-y-2">
                        <a href="/marketplace" class="block text-neutral-300 hover:text-white transition-colors">Marketplace</a>
                        <a href="/weather" class="block text-neutral-300 hover:text-white transition-colors">Weather Updates</a>
                        <a href="/announcements" class="block text-neutral-300 hover:text-white transition-colors">Announcements</a>
                        <a href="/forum" class="block text-neutral-300 hover:text-white transition-colors">Community Forum</a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-white mb-4">Contact</h4>
                    <div class="space-y-2 text-neutral-300">
                        <p>📞 (043) 123-4567</p>
                        <p>📧 info@agriconnect.ph</p>
                        <p>📍 Nasugbu, Batangas</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-neutral-700 mt-8 pt-8 text-center text-neutral-400">
                <p>&copy; 2024 AgriConnect. All rights reserved.</p>
                <div class="flex justify-center gap-6 mt-4">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">About Us</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
```

---

## Example Page Templates

### 1. Landing Page HTML

**landing.html**:
```html
<!-- Insert into Main Content section -->
<div class="bg-white">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-[#2d7a3e] to-[#4a9b5a] text-white py-12 md:py-20">
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
                        <a href="/marketplace" class="flex items-center justify-center gap-2 px-8 py-4 bg-white text-[#2d7a3e] hover:bg-neutral-100 rounded-lg transition-colors shadow-lg" style="font-size: 1.25rem;">
                            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                            <span>Browse Products</span>
                            <i data-lucide="arrow-right" class="w-6 h-6"></i>
                        </a>
                        <a href="/register-farmer" class="flex items-center justify-center gap-2 px-8 py-4 bg-[#d97706] text-white hover:bg-[#b45309] rounded-lg transition-colors shadow-lg" style="font-size: 1.25rem;">
                            <i data-lucide="users" class="w-6 h-6"></i>
                            <span>Register as Farmer</span>
                        </a>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border-2 border-white/20">
                        <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=600&h=400&fit=crop" alt="Filipino farmers" class="w-full h-64 md:h-80 object-cover rounded-xl">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Products -->
    <section class="py-12 md:py-16">
        <div class="container">
            <div class="flex items-center justify-between mb-8">
                <h2>Featured Products</h2>
                <a href="/marketplace" class="flex items-center gap-2 text-[#2d7a3e] hover:underline">
                    <span>View All</span>
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product Card 1 -->
                <div class="bg-white rounded-xl shadow-md border-2 border-neutral-200 hover:border-[#2d7a3e] hover:shadow-lg transition-all overflow-hidden">
                    <div class="relative h-48">
                        <img src="https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400&h=300&fit=crop&q=80" alt="Fresh Tomatoes" class="w-full h-full object-cover">
                        <div class="absolute top-2 right-2 bg-[#16a34a] text-white px-3 py-1 rounded-full text-sm">
                            Available
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="mb-2">Fresh Tomatoes</h3>
                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-[#2d7a3e] text-2xl font-semibold">₱80</span>
                            <span class="text-neutral-500">per kilo</span>
                        </div>
                        <div class="space-y-1 text-neutral-600 text-sm">
                            <p>👨‍🌾 Juan Santos</p>
                            <p>🏢 Nasugbu Farmers Coop</p>
                            <p class="text-[#16a34a]">📦 50 kg available</p>
                        </div>
                    </div>
                </div>
                
                <!-- Add more product cards... -->
            </div>
        </div>
    </section>
</div>
```

---

## Method 3: Screenshot-Based Documentation

For visual reference:

1. **Run the app**
2. **Take screenshots** of each page at different breakpoints:
   - Mobile (375px)
   - Tablet (768px)
   - Desktop (1280px)
3. **Annotate** with component names and functionality
4. **Create PDF** documentation with all screenshots
5. **Share with developers** for implementation reference

---

## Color Reference Card

For easy reference when converting:

```css
/* Primary Colors */
--primary: #2d7a3e        /* Main green */
--primary-hover: #236330  /* Darker green */
--primary-light: #4a9b5a  /* Lighter green */

/* Secondary Colors */
--secondary: #8b6f47      /* Earth brown */
--secondary-hover: #6f5838

/* Accent */
--accent: #d97706         /* Orange for CTAs */
--accent-hover: #b45309

/* Semantic */
--success: #16a34a        /* Green for success */
--warning: #f59e0b        /* Yellow for warnings */
--error: #dc2626          /* Red for errors */
--info: #3b82f6           /* Blue for info */

/* Neutrals */
--neutral-50: #fafaf9
--neutral-100: #f5f5f4
--neutral-200: #e7e5e4
--neutral-300: #d6d3d1
--neutral-400: #a8a29e
--neutral-500: #78716c
--neutral-600: #57534e
--neutral-700: #44403c
--neutral-800: #292524
--neutral-900: #1c1917
```

---

## Icon Reference

Using Lucide Icons (https://lucide.dev):

```html
<!-- Common icons used -->
<i data-lucide="home"></i>
<i data-lucide="shopping-bag"></i>
<i data-lucide="shopping-cart"></i>
<i data-lucide="user"></i>
<i data-lucide="cloud"></i>
<i data-lucide="megaphone"></i>
<i data-lucide="message-square"></i>
<i data-lucide="layout-dashboard"></i>
<i data-lucide="package"></i>
<i data-lucide="plus"></i>
<i data-lucide="edit"></i>
<i data-lucide="trash-2"></i>
<i data-lucide="search"></i>
<i data-lucide="filter"></i>
<i data-lucide="arrow-right"></i>
<i data-lucide="chevron-down"></i>
<i data-lucide="log-out"></i>
<i data-lucide="users"></i>
<i data-lucide="wheat"></i>
<i data-lucide="leaf"></i>
<i data-lucide="trending-up"></i>
```

---

## Form Examples

### Login Form
```html
<form method="POST" action="/auth/login" class="space-y-6">
    <div>
        <label for="email" class="block mb-2">Email or Phone</label>
        <input 
            type="text" 
            id="email" 
            name="email" 
            required 
            class="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-[#2d7a3e]"
            placeholder="juan@example.com or 0917-123-4567"
        >
    </div>
    
    <div>
        <label for="password" class="block mb-2">Password</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            required 
            class="w-full px-4 py-3 border-2 border-neutral-300 rounded-lg focus:outline-none focus:border-[#2d7a3e]"
            placeholder="Enter your password"
        >
    </div>
    
    <button type="submit" class="w-full px-6 py-3 bg-[#2d7a3e] text-white hover:bg-[#236330] rounded-lg transition-colors">
        Login
    </button>
</form>
```

---

## Button Styles Reference

```html
<!-- Primary Button -->
<button class="px-6 py-3 bg-[#2d7a3e] text-white hover:bg-[#236330] rounded-lg transition-colors">
    Primary Action
</button>

<!-- Secondary Button -->
<button class="px-6 py-3 bg-white text-[#2d7a3e] border-2 border-[#2d7a3e] hover:bg-neutral-50 rounded-lg transition-colors">
    Secondary Action
</button>

<!-- Accent/CTA Button -->
<button class="px-6 py-3 bg-[#d97706] text-white hover:bg-[#b45309] rounded-lg transition-colors">
    Call to Action
</button>

<!-- Danger Button -->
<button class="px-6 py-3 bg-[#dc2626] text-white hover:bg-[#b91c1c] rounded-lg transition-colors">
    Delete
</button>

<!-- Ghost Button -->
<button class="px-6 py-3 text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
    Cancel
</button>
```

---

## Export Complete Application

To export all pages as static HTML:

1. **Create folder structure**:
```
html-export/
├── index.html (landing)
├── marketplace.html
├── login.html
├── register-farmer.html
├── register-buyer.html
├── farmer/
│   ├── dashboard.html
│   ├── add-product.html
│   ├── inventory.html
│   └── orders.html
├── buyer/
│   ├── cart.html
│   ├── checkout.html
│   └── orders.html
├── weather.html
├── announcements.html
├── forum.html
├── messages.html
├── admin/
│   ├── dashboard.html
│   ├── users.html
│   ├── products.html
│   └── announcements.html
├── assets/
│   ├── css/
│   │   └── globals.css
│   ├── js/
│   │   └── main.js
│   └── images/
└── README.md
```

2. **Copy base template** to each HTML file
3. **Replace main content** section with page-specific content
4. **Update navigation** to reflect current page
5. **Add page-specific** JavaScript if needed

---

## Notes

- All HTML templates use **Tailwind CSS CDN** for easy deployment
- Replace CDN links with **compiled CSS** for production
- Use **relative paths** for images and links
- Test on **multiple browsers** and devices
- Optimize images before deployment
- Consider using a **static site generator** like 11ty or Hugo for easier management

---

**Export Status**: Templates ready  
**Framework**: Pure HTML/CSS/JS  
**Dependencies**: Tailwind CSS CDN, Lucide Icons CDN  
**Browser Support**: Modern browsers (Chrome, Firefox, Safari, Edge)
