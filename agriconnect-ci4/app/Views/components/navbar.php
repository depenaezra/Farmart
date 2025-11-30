<nav class="bg-white bg-opacity-95 shadow-md sticky top-0 z-50 backdrop-blur-sm">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <i data-lucide="sprout" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-xl font-bold text-primary">Farmart</span>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center flex-1 justify-center">
                <div class="flex items-center space-x-6">
                    <a href="/marketplace" class="text-gray-700 hover:text-primary font-medium flex items-center"><i data-lucide="shopping-bag" class="w-6 h-6 mr-2"></i>Marketplace</a>
                    <a href="/weather" class="text-gray-700 hover:text-primary font-medium flex items-center"><i data-lucide="cloud-sun" class="w-6 h-6 mr-2"></i>Weather</a>
                    <a href="/announcements" class="text-gray-700 hover:text-primary font-medium flex items-center"><i data-lucide="bell" class="w-6 h-6 mr-2"></i>Announcements</a>
                    <a href="/forum" class="text-gray-700 hover:text-primary font-medium flex items-center"><i data-lucide="message-square" class="w-6 h-6 mr-2"></i>Forum</a>
                    <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                        <a href="/messages/inbox" class="text-gray-700 hover:text-primary font-medium flex items-center"><i data-lucide="mail" class="w-6 h-6 mr-2"></i>Messages</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                    <!-- Profile dropdown menu -->
                    <div class="relative" id="profile-dropdown-wrapper">
                        <button id="profile-dropdown-btn" class="flex items-center gap-2 text-gray-700 hover:text-primary font-medium" aria-expanded="false" aria-haspopup="true">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            <span><?= esc(session()->get('user_name') ?? 'Profile') ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" id="profile-dropdown-icon"></i>
                        </button>
                        <!-- Dropdown -->
                        <div id="profile-dropdown-menu" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                            <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4"></i>Profile
                            </a>
                            <a href="/buyer/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="grid" class="w-4 h-4"></i>Dashboard
                            </a>
                            <a href="/buyer/inventory" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="package" class="w-4 h-4"></i>My Listings
                            </a>
                            <a href="/buyer/products/add" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>Add Product
                            </a>
                            <a href="/cart" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="shopping-cart" class="w-4 h-4"></i>Cart
                            </a>
                            <a href="/buyer/sales/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="clipboard-list" class="w-4 h-4"></i>Sales Orders
                            </a>
                            <a href="/buyer/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>My Orders
                            </a>
                            <a href="/profile/edit" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="settings" class="w-4 h-4"></i>Account Settings
                            </a>
                            <hr class="my-1">
                            <form action="/auth/logout" method="POST" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-b-lg flex items-center gap-2 swal-confirm-form" data-confirm="Log out from your account?">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Not logged in -->
                    <a href="/auth/login" class="text-gray-700 hover:text-primary font-medium">Login</a>
                    <a href="/auth/register-buyer" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover font-medium">
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
            <a href="/marketplace" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="shopping-bag" class="w-6 h-6 mr-2"></i>Marketplace</a>
            <a href="/weather" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="cloud-sun" class="w-6 h-6 mr-2"></i>Weather</a>
            <a href="/announcements" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="bell" class="w-6 h-6 mr-2"></i>Announcements</a>
            <a href="/forum" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="message-square" class="w-6 h-6 mr-2"></i>Forum</a>

            <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                <hr class="my-2">
                <a href="/messages/inbox" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="mail" class="w-6 h-6 mr-2"></i>Messages</a>
                <a href="/profile" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="user" class="w-6 h-6 mr-2"></i>Profile</a>
                <a href="/buyer/dashboard" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="grid" class="w-6 h-6 mr-2"></i>Dashboard</a>
                <a href="/buyer/inventory" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="package" class="w-6 h-6 mr-2"></i>My Listings</a>
                <a href="/buyer/products/add" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="plus-circle" class="w-6 h-6 mr-2"></i>Add Product</a>
                <a href="/cart" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="shopping-cart" class="w-6 h-6 mr-2"></i>Cart</a>
                <a href="/buyer/sales/orders" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="clipboard-list" class="w-6 h-6 mr-2"></i>Sales Orders</a>
                <a href="/buyer/orders" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="shopping-bag" class="w-6 h-6 mr-2"></i>My Orders</a>
                <a href="/profile/edit" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="settings" class="w-6 h-6 mr-2"></i>Account Settings</a>
                <hr class="my-2">
                <form action="/auth/logout" method="POST" class="m-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="block w-full text-left py-2 text-red-600 hover:text-red-700"><i data-lucide="log-out" class="w-6 h-6 mr-2 inline"></i>Logout</button>
                </form>
            <?php else: ?>
                <a href="/auth/login" class="block py-2 text-gray-700 hover:text-primary">Login</a>
                <a href="/auth/register-buyer" class="block py-2 text-gray-700 hover:text-primary">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Profile dropdown toggle
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('profile-dropdown-btn');
        const menu = document.getElementById('profile-dropdown-menu');
        const icon = document.getElementById('profile-dropdown-icon');
        const wrapper = document.getElementById('profile-dropdown-wrapper');

        if (!btn || !menu) return;

        // Toggle dropdown
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', !isHidden);
            if (icon) icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        });

        // Close when clicking a menu item
        menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
                if (icon) icon.style.transform = 'rotate(0deg)';
            });
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target)) {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        });
    });
</script>
