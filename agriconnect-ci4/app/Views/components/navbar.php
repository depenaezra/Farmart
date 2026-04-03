<?php
$currentUri = trim(uri_string(), '/');
$isActive = static function (array $patterns) use ($currentUri): bool {
    foreach ($patterns as $pattern) {
        $pattern = trim($pattern, '/');
        if (str_ends_with($pattern, '*')) {
            $prefix = rtrim(substr($pattern, 0, -1), '/');
            if ($prefix === '' || str_starts_with($currentUri, $prefix)) {
                return true;
            }
            continue;
        }
        if ($currentUri === $pattern) {
            return true;
        }
    }
    return false;
};
?>

<nav class="bg-white/95 border-b border-mint shadow-md sticky top-0 z-50 backdrop-blur-sm">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center">
                <img src="/img/farmart_logo.png" alt="Farmart" class="h-12 w-auto">
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center flex-1 justify-center">
                <div class="flex items-center space-x-2 rounded-full bg-mint-light/80 px-3 py-1.5 border border-mint">
                    <a href="/marketplace" class="<?= $isActive(['marketplace', 'marketplace/*']) ? 'text-white bg-primary shadow-sm' : 'text-gray-700 hover:text-primary hover:bg-white' ?> font-medium flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all duration-200"><i data-lucide="shopping-bag" class="w-4 h-4"></i>Marketplace</a>
                    <a href="/weather" class="<?= $isActive(['weather', 'weather/*']) ? 'text-white bg-primary shadow-sm' : 'text-gray-700 hover:text-primary hover:bg-white' ?> font-medium flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all duration-200"><i data-lucide="cloud-sun" class="w-4 h-4"></i>Weather</a>
                    <a href="/announcements" class="<?= $isActive(['announcements', 'announcements/*']) ? 'text-white bg-primary shadow-sm' : 'text-gray-700 hover:text-primary hover:bg-white' ?> font-medium flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all duration-200"><i data-lucide="bell" class="w-4 h-4"></i>Announcements</a>
                    <a href="/forum" class="<?= $isActive(['forum', 'forum/*']) ? 'text-white bg-primary shadow-sm' : 'text-gray-700 hover:text-primary hover:bg-white' ?> font-medium flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all duration-200"><i data-lucide="message-square" class="w-4 h-4"></i>Forum</a>
                    <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                        <a href="/messages/inbox" class="<?= $isActive(['messages', 'messages/*']) ? 'text-white bg-primary shadow-sm' : 'text-gray-700 hover:text-primary hover:bg-white' ?> font-medium flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all duration-200"><i data-lucide="mail" class="w-4 h-4"></i>Messages</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                    <!-- Profile dropdown menu -->
                    <div class="relative" id="profile-dropdown-wrapper">
                        <button id="profile-dropdown-btn" class="flex items-center gap-2 text-gray-700 hover:text-primary font-medium px-3 py-2 rounded-full border border-mint bg-mint-light/60 hover:bg-mint-light transition-colors" aria-expanded="false" aria-haspopup="true">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-primary text-white">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </span>
                            <span><?= esc(session()->get('user_name') ?? 'Profile') ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" id="profile-dropdown-icon"></i>
                        </button>
                        <!-- Dropdown -->
                        <div id="profile-dropdown-menu" class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-mint hidden z-50 max-h-[calc(100vh-80px)] md:max-h-none overflow-y-auto md:overflow-y-visible">
                            <div class="px-4 py-3 border-b border-mint-light bg-gradient-to-r from-mint-light to-white rounded-t-xl">
                                <p class="text-xs uppercase tracking-wide text-secondary font-semibold">Quick Navigation</p>
                                <p class="text-sm text-gray-600">Account and seller tools</p>
                            </div>
                            <a href="/profile" class="block px-4 py-2.5 text-gray-700 hover:bg-mint-light/60 flex items-center gap-2 transition-colors">
                                <span class="w-7 h-7 rounded-lg bg-green-100 text-primary inline-flex items-center justify-center"><i data-lucide="user" class="w-4 h-4"></i></span>Profile
                            </a>
                            <a href="/buyer/dashboard" class="block px-4 py-2.5 text-gray-700 hover:bg-mint-light/60 flex items-center gap-2 transition-colors">
                                <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 inline-flex items-center justify-center"><i data-lucide="grid" class="w-4 h-4"></i></span>Dashboard
                            </a>
                            <a href="/buyer/inventory" class="block px-4 py-2.5 text-gray-700 hover:bg-mint-light/60 flex items-center gap-2 transition-colors">
                                <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 inline-flex items-center justify-center"><i data-lucide="package" class="w-4 h-4"></i></span>My Listings
                            </a>
                            <a href="/buyer/products/add" class="block px-4 py-2.5 text-gray-700 hover:bg-mint-light/60 flex items-center gap-2 transition-colors">
                                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 inline-flex items-center justify-center"><i data-lucide="plus-circle" class="w-4 h-4"></i></span>Add Product
                            </a>
                            <a href="/cart" class="block px-4 py-2.5 text-gray-700 hover:bg-mint-light/60 flex items-center gap-2 transition-colors">
                                <span class="w-7 h-7 rounded-lg bg-cyan-100 text-cyan-700 inline-flex items-center justify-center"><i data-lucide="shopping-cart" class="w-4 h-4"></i></span>Cart
                            </a>
                            <a href="/buyer/sales/orders" class="block px-4 py-2.5 text-gray-700 hover:bg-mint-light/60 flex items-center gap-2 transition-colors">
                                <span class="w-7 h-7 rounded-lg bg-orange-100 text-orange-700 inline-flex items-center justify-center"><i data-lucide="clipboard-list" class="w-4 h-4"></i></span>Sales Orders
                            </a>
                            <a href="/buyer/orders" class="block px-4 py-2.5 text-gray-700 hover:bg-mint-light/60 flex items-center gap-2 transition-colors">
                                <span class="w-7 h-7 rounded-lg bg-violet-100 text-violet-700 inline-flex items-center justify-center"><i data-lucide="shopping-bag" class="w-4 h-4"></i></span>My Orders
                            </a>
                            <a href="/profile/edit" class="block px-4 py-2.5 text-gray-700 hover:bg-mint-light/60 flex items-center gap-2 transition-colors">
                                <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-700 inline-flex items-center justify-center"><i data-lucide="settings" class="w-4 h-4"></i></span>Account Settings
                            </a>
                            <hr class="my-1 border-mint-light">
                            <form action="/auth/logout" method="POST" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-b-xl flex items-center gap-2 swal-confirm-form" data-confirm="Log out from your account?">
                                    <span class="w-7 h-7 rounded-lg bg-red-100 text-red-600 inline-flex items-center justify-center"><i data-lucide="log-out" class="w-4 h-4"></i></span>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Not logged in -->
                    <a href="/auth/login" class="text-gray-700 hover:text-primary font-medium px-3 py-2 rounded-lg hover:bg-mint-light/70 transition-colors">Login</a>
                    <a href="/auth/register-buyer" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover font-medium shadow-sm">
                        Register
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-gray-700 p-2 rounded-lg hover:bg-mint-light/80 transition-colors">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 pt-2 space-y-1 bg-white/95 border-t border-mint-light rounded-b-xl">
            <a href="/marketplace" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="shopping-bag" class="w-5 h-5 mr-2 inline"></i>Marketplace</a>
            <a href="/weather" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="cloud-sun" class="w-5 h-5 mr-2 inline"></i>Weather</a>
            <a href="/announcements" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="bell" class="w-5 h-5 mr-2 inline"></i>Announcements</a>
            <a href="/forum" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="message-square" class="w-5 h-5 mr-2 inline"></i>Forum</a>

            <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                <hr class="my-2">
                <a href="/messages/inbox" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="mail" class="w-5 h-5 mr-2 inline"></i>Messages</a>
                <a href="/profile" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="user" class="w-5 h-5 mr-2 inline"></i>Profile</a>
                <a href="/buyer/dashboard" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="grid" class="w-5 h-5 mr-2 inline"></i>Dashboard</a>
                <a href="/buyer/inventory" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="package" class="w-5 h-5 mr-2 inline"></i>My Listings</a>
                <a href="/buyer/products/add" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="plus-circle" class="w-5 h-5 mr-2 inline"></i>Add Product</a>
                <a href="/cart" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="shopping-cart" class="w-5 h-5 mr-2 inline"></i>Cart</a>
                <a href="/buyer/sales/orders" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="clipboard-list" class="w-5 h-5 mr-2 inline"></i>Sales Orders</a>
                <a href="/buyer/orders" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="shopping-bag" class="w-5 h-5 mr-2 inline"></i>My Orders</a>
                <a href="/profile/edit" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors"><i data-lucide="settings" class="w-5 h-5 mr-2 inline"></i>Account Settings</a>
                <hr class="my-2">
                <form action="/auth/logout" method="POST" class="m-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="block w-full text-left py-2.5 px-3 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"><i data-lucide="log-out" class="w-5 h-5 mr-2 inline"></i>Logout</button>
                </form>
            <?php else: ?>
                <a href="/auth/login" class="block py-2.5 px-3 text-gray-700 hover:text-primary hover:bg-mint-light/60 rounded-lg transition-colors">Login</a>
                <a href="/auth/register-buyer" class="block py-2.5 px-3 text-white bg-primary hover:bg-primary-hover rounded-lg transition-colors">Register</a>
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
