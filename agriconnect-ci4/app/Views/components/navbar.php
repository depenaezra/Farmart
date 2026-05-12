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

<nav class="farmart-nav-shell sticky top-0 z-50">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-[4.25rem]">
            <!-- Logo -->
            <a href="/" class="flex items-center shrink-0 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                <img src="/img/farmart_logo.png" alt="Farmart" class="h-11 w-auto">
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center flex-1 justify-center px-4">
                <div class="farmart-nav-pill-wrap flex flex-wrap items-center justify-center gap-1 rounded-full px-1.5 py-1">
                    <a href="/marketplace" class="<?= $isActive(['marketplace', 'marketplace/*']) ? 'farmart-nav-pill-active' : 'farmart-nav-pill-idle' ?> font-semibold text-sm flex items-center gap-1.5 px-3.5 py-2 rounded-full transition-all duration-200"><i data-lucide="shopping-bag" class="w-4 h-4"></i>Marketplace</a>
                    <a href="/weather" class="<?= $isActive(['weather', 'weather/*']) ? 'farmart-nav-pill-active' : 'farmart-nav-pill-idle' ?> font-semibold text-sm flex items-center gap-1.5 px-3.5 py-2 rounded-full transition-all duration-200"><i data-lucide="cloud-sun" class="w-4 h-4"></i>Weather</a>
                    <a href="/announcements" class="<?= $isActive(['announcements', 'announcements/*']) ? 'farmart-nav-pill-active' : 'farmart-nav-pill-idle' ?> font-semibold text-sm flex items-center gap-1.5 px-3.5 py-2 rounded-full transition-all duration-200"><i data-lucide="bell" class="w-4 h-4"></i>Announcements</a>
                    <a href="/forum" class="<?= $isActive(['forum', 'forum/*']) ? 'farmart-nav-pill-active' : 'farmart-nav-pill-idle' ?> font-semibold text-sm flex items-center gap-1.5 px-3.5 py-2 rounded-full transition-all duration-200"><i data-lucide="message-square" class="w-4 h-4"></i>Forum</a>
                    <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                        <a href="/messages/inbox" class="<?= $isActive(['messages', 'messages/*']) ? 'farmart-nav-pill-active' : 'farmart-nav-pill-idle' ?> font-semibold text-sm flex items-center gap-1.5 px-3.5 py-2 rounded-full transition-all duration-200"><i data-lucide="mail" class="w-4 h-4"></i>Messages</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-4 shrink-0">
                <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                    <!-- Profile dropdown menu -->
                    <div class="relative" id="profile-dropdown-wrapper">
                        <button id="profile-dropdown-btn" class="farmart-profile-trigger flex items-center gap-2 text-slate-700 font-semibold text-sm pl-1 pr-3 py-1.5 rounded-full transition-all duration-200" aria-expanded="false" aria-haspopup="true">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-br from-primary to-emerald-800 text-white shadow-md">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </span>
                            <span class="max-w-[9rem] truncate"><?= esc(session()->get('user_name') ?? 'Profile') ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500 transition-transform shrink-0" id="profile-dropdown-icon"></i>
                        </button>
                        <!-- Dropdown -->
                        <div id="profile-dropdown-menu" class="farmart-dropdown-panel absolute right-0 mt-2 w-80 bg-white hidden z-50 max-h-[calc(100vh-80px)] md:max-h-none overflow-y-auto md:overflow-y-visible">
                            <div class="farmart-dropdown-head px-4 py-3 rounded-t-[inherit]">
                                <p class="text-[11px] uppercase tracking-widest text-primary font-bold">Menu</p>
                                <p class="text-sm text-slate-600 mt-0.5">Buyer, seller, and account</p>
                            </div>
                            <p class="farmart-dropdown-section-label px-4 pt-2.5 pb-1">Account</p>
                            <a href="/profile" class="farmart-dropdown-link block px-4 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-primary inline-flex items-center justify-center ring-1 ring-emerald-100 shrink-0"><i data-lucide="user" class="w-4 h-4"></i></span><span class="text-sm font-medium">Profile</span>
                            </a>
                            <a href="/profile/edit" class="farmart-dropdown-link block px-4 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 inline-flex items-center justify-center ring-1 ring-slate-200/80 shrink-0"><i data-lucide="settings" class="w-4 h-4"></i></span><span class="text-sm font-medium">Account settings</span>
                            </a>
                            <p class="farmart-dropdown-section-label px-4 pt-3 pb-1 border-t border-slate-100 mt-1">Shopping <span class="text-slate-400 font-semibold normal-case">(buyer)</span></p>
                            <a href="/cart" class="farmart-dropdown-link block px-4 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-800 inline-flex items-center justify-center ring-1 ring-sky-100 shrink-0"><i data-lucide="shopping-cart" class="w-4 h-4"></i></span><span class="text-sm font-medium">Cart</span>
                            </a>
                            <a href="/buyer/orders" class="farmart-dropdown-link block px-4 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-violet-50 text-violet-800 inline-flex items-center justify-center ring-1 ring-violet-100 shrink-0"><i data-lucide="shopping-bag" class="w-4 h-4"></i></span><span class="text-sm font-medium">My orders</span>
                            </a>
                            <p class="farmart-dropdown-section-label px-4 pt-3 pb-1 border-t border-slate-100 mt-1">Selling <span class="text-slate-400 font-semibold normal-case">(farmer)</span></p>
                            <a href="/buyer/dashboard" class="farmart-dropdown-link block px-4 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 inline-flex items-center justify-center ring-1 ring-slate-200/80 shrink-0"><i data-lucide="grid" class="w-4 h-4"></i></span><span class="text-sm font-medium">Seller dashboard</span>
                            </a>
                            <a href="/buyer/inventory" class="farmart-dropdown-link block px-4 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-800 inline-flex items-center justify-center ring-1 ring-amber-100 shrink-0"><i data-lucide="package" class="w-4 h-4"></i></span><span class="text-sm font-medium">My listings</span>
                            </a>
                            <a href="/buyer/products/add" class="farmart-dropdown-link block px-4 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-primary inline-flex items-center justify-center ring-1 ring-emerald-100 shrink-0"><i data-lucide="plus-circle" class="w-4 h-4"></i></span><span class="text-sm font-medium">Add product</span>
                            </a>
                            <a href="/buyer/sales/orders" class="farmart-dropdown-link block px-4 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-orange-50 text-orange-900 inline-flex items-center justify-center ring-1 ring-orange-100 shrink-0"><i data-lucide="clipboard-list" class="w-4 h-4"></i></span><span class="text-sm font-medium">Sales orders</span>
                            </a>
                            <hr class="my-1 border-slate-100">
                            <form action="/auth/logout" method="POST" class="m-0 swal-confirm-form" data-confirm-title="Sign out?" data-confirm="You will leave your session and need to sign in again to use your account." data-confirm-ok="Sign out" data-confirm-icon="question">
                                <?= csrf_field() ?>
                                <button type="submit" class="farmart-dropdown-link w-full text-left px-4 py-2.5 text-rose-700 hover:bg-rose-50 flex items-center gap-3 font-semibold text-sm">
                                    <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 inline-flex items-center justify-center ring-1 ring-rose-100 shrink-0"><i data-lucide="log-out" class="w-4 h-4"></i></span>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Not logged in -->
                    <a href="/auth/login" class="text-slate-600 hover:text-primary font-semibold text-sm px-4 py-2 rounded-full hover:bg-slate-100 transition-colors">Login</a>
                    <a href="/auth/register-buyer" class="bg-gradient-to-r from-primary to-emerald-800 text-white px-5 py-2.5 rounded-full hover:from-primary-hover hover:to-emerald-900 font-semibold text-sm shadow-md shadow-primary/25 transition-all">
                        Register
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" type="button" class="md:hidden text-slate-700 p-2.5 rounded-xl hover:bg-slate-100 transition-colors ring-1 ring-slate-200/80">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 pt-2 space-y-0.5 bg-white/95 border-t border-slate-200/80">
            <a href="/marketplace" class="block py-2.5 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium"><i data-lucide="shopping-bag" class="w-5 h-5 mr-2 inline"></i>Marketplace</a>
            <a href="/weather" class="block py-2.5 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium"><i data-lucide="cloud-sun" class="w-5 h-5 mr-2 inline"></i>Weather</a>
            <a href="/announcements" class="block py-2.5 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium"><i data-lucide="bell" class="w-5 h-5 mr-2 inline"></i>Announcements</a>
            <a href="/forum" class="block py-2.5 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium"><i data-lucide="message-square" class="w-5 h-5 mr-2 inline"></i>Forum</a>

            <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                <hr class="my-2 border-slate-100">
                <a href="/messages/inbox" class="block py-2.5 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium"><i data-lucide="mail" class="w-5 h-5 mr-2 inline"></i>Messages</a>
                <p class="px-3 pt-2 pb-0.5 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Account</p>
                <a href="/profile" class="block py-2 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium text-sm"><i data-lucide="user" class="w-4 h-4 mr-2 inline"></i>Profile</a>
                <a href="/profile/edit" class="block py-2 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium text-sm"><i data-lucide="settings" class="w-4 h-4 mr-2 inline"></i>Account settings</a>
                <p class="px-3 pt-2 pb-0.5 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Shopping (buyer)</p>
                <a href="/cart" class="block py-2 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium text-sm"><i data-lucide="shopping-cart" class="w-4 h-4 mr-2 inline"></i>Cart</a>
                <a href="/buyer/orders" class="block py-2 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium text-sm"><i data-lucide="shopping-bag" class="w-4 h-4 mr-2 inline"></i>My orders</a>
                <p class="px-3 pt-2 pb-0.5 text-[10px] uppercase tracking-wider text-slate-400 font-bold">Selling (farmer)</p>
                <a href="/buyer/dashboard" class="block py-2 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium text-sm"><i data-lucide="grid" class="w-4 h-4 mr-2 inline"></i>Seller dashboard</a>
                <a href="/buyer/inventory" class="block py-2 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium text-sm"><i data-lucide="package" class="w-4 h-4 mr-2 inline"></i>My listings</a>
                <a href="/buyer/products/add" class="block py-2 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium text-sm"><i data-lucide="plus-circle" class="w-4 h-4 mr-2 inline"></i>Add product</a>
                <a href="/buyer/sales/orders" class="block py-2 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium text-sm"><i data-lucide="clipboard-list" class="w-4 h-4 mr-2 inline"></i>Sales orders</a>
                <hr class="my-2 border-slate-100">
                <form action="/auth/logout" method="POST" class="m-0 swal-confirm-form" data-confirm-title="Sign out?" data-confirm="You will leave your session and need to sign in again to use your account." data-confirm-ok="Sign out" data-confirm-icon="question">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="block w-full text-left py-2.5 px-3 text-rose-700 hover:bg-rose-50 rounded-xl transition-colors font-semibold"><i data-lucide="log-out" class="w-5 h-5 mr-2 inline"></i>Logout</button>
                </form>
            <?php else: ?>
                <a href="/auth/login" class="block py-2.5 px-3 text-slate-700 hover:text-primary hover:bg-slate-50 rounded-xl transition-colors font-medium">Login</a>
                <a href="/auth/register-buyer" class="block py-2.5 px-3 text-center text-white bg-gradient-to-r from-primary to-emerald-800 hover:from-primary-hover rounded-xl transition-colors font-semibold shadow-md">Register</a>
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
