<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <i data-lucide="sprout" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-xl font-bold text-primary">AgriConnect</span>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="/marketplace" class="text-gray-700 hover:text-primary font-medium">Marketplace</a>
                <a href="/weather" class="text-gray-700 hover:text-primary font-medium">Weather</a>
                <a href="/announcements" class="text-gray-700 hover:text-primary font-medium">Announcements</a>
                <a href="/forum" class="text-gray-700 hover:text-primary font-medium">Forum</a>
                
                <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                    <!-- Logged in user menu -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-primary font-medium">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            <span><?= esc(session()->get('user_name')) ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>
                        
                        <!-- Dropdown -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <?php $role = session()->get('user_role'); ?>
                            
                            <?php if ($role === 'farmer'): ?>
                                <a href="/farmer/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4 inline mr-2"></i>Dashboard
                                </a>
                                <a href="/farmer/inventory" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i data-lucide="package" class="w-4 h-4 inline mr-2"></i>My Products
                                </a>
                                <a href="/farmer/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i data-lucide="shopping-bag" class="w-4 h-4 inline mr-2"></i>Orders
                                </a>
                            <?php elseif ($role === 'buyer'): ?>
                                <a href="/buyer/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i data-lucide="shopping-bag" class="w-4 h-4 inline mr-2"></i>My Orders
                                </a>
                                <a href="/cart" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i data-lucide="shopping-cart" class="w-4 h-4 inline mr-2"></i>Cart
                                </a>
                            <?php elseif ($role === 'admin'): ?>
                                <a href="/admin/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i data-lucide="shield" class="w-4 h-4 inline mr-2"></i>Admin Dashboard
                                </a>
                                <a href="/admin/users" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i data-lucide="users" class="w-4 h-4 inline mr-2"></i>Users
                                </a>
                                <a href="/admin/products" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i data-lucide="package" class="w-4 h-4 inline mr-2"></i>Products
                                </a>
                            <?php endif; ?>
                            
                            <a href="/messages/inbox" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i data-lucide="mail" class="w-4 h-4 inline mr-2"></i>Messages
                            </a>
                            <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>My Profile
                            </a>
                            <hr class="my-1">
                            <a href="/auth/logout" class="block px-4 py-2 text-red-600 hover:bg-red-50 rounded-b-lg">
                                <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Not logged in -->
                    <a href="/auth/login" class="text-gray-700 hover:text-primary font-medium">Login</a>
                    <a href="/auth/register-farmer" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover font-medium">
                        Register
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-gray-700">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <a href="/marketplace" class="block py-2 text-gray-700 hover:text-primary">Marketplace</a>
            <a href="/weather" class="block py-2 text-gray-700 hover:text-primary">Weather</a>
            <a href="/announcements" class="block py-2 text-gray-700 hover:text-primary">Announcements</a>
            <a href="/forum" class="block py-2 text-gray-700 hover:text-primary">Forum</a>
            
            <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                <hr class="my-2">
                <div class="text-sm text-gray-500 mb-2">Logged in as: <?= esc(session()->get('user_name')) ?></div>
                
                <?php $role = session()->get('user_role'); ?>
                <?php if ($role === 'farmer'): ?>
                    <a href="/farmer/dashboard" class="block py-2 text-gray-700 hover:text-primary">Dashboard</a>
                    <a href="/farmer/inventory" class="block py-2 text-gray-700 hover:text-primary">My Products</a>
                    <a href="/farmer/orders" class="block py-2 text-gray-700 hover:text-primary">Orders</a>
                <?php elseif ($role === 'buyer'): ?>
                    <a href="/buyer/orders" class="block py-2 text-gray-700 hover:text-primary">My Orders</a>
                    <a href="/cart" class="block py-2 text-gray-700 hover:text-primary">Cart</a>
                <?php elseif ($role === 'admin'): ?>
                    <a href="/admin/dashboard" class="block py-2 text-gray-700 hover:text-primary">Admin Dashboard</a>
                <?php endif; ?>
                
                <a href="/messages/inbox" class="block py-2 text-gray-700 hover:text-primary">Messages</a>
                <a href="/profile" class="block py-2 text-gray-700 hover:text-primary">My Profile</a>
                <a href="/auth/logout" class="block py-2 text-red-600">Logout</a>
            <?php else: ?>
                <hr class="my-2">
                <a href="/auth/login" class="block py-2 text-gray-700 hover:text-primary">Login</a>
                <a href="/auth/register-farmer" class="block py-2 text-gray-700 hover:text-primary">Register as Farmer</a>
                <a href="/auth/register-buyer" class="block py-2 text-gray-700 hover:text-primary">Register as Buyer</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
