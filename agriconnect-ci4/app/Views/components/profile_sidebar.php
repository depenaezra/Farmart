<div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
    <div class="mb-4 flex items-center justify-end">
        <button id="sidebar-collapse-btn" type="button" class="text-gray-500 hover:text-primary ml-2" aria-label="Toggle sidebar" aria-controls="profile-sidebar-container" aria-expanded="true">
            <i data-lucide="menu" class="inline-block md:hidden w-6 h-6" aria-hidden="true"></i>
            <i data-lucide="chevron-left" class="hidden md:inline-block w-4 h-4 transition-transform" aria-hidden="true"></i>
        </button>
    </div>

    <nav class="space-y-2">
        <a href="/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'profile') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="user" class="w-4 h-4"></i>
            <span class="sidebar-text">Profile</span>
        </a>

        <a href="/buyer/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'buyer/dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="grid" class="w-4 h-4"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <a href="/buyer/inventory" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'buyer/inventory') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="package" class="w-4 h-4"></i>
            <span class="sidebar-text">My Listings</span>
        </a>

        <a href="/buyer/products/add" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'buyer/products/add') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span class="sidebar-text">Add Product</span>
        </a>

        <a href="/cart" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'cart') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
            <span class="sidebar-text">Cart</span>
        </a>

        <a href="/buyer/sales/orders" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (strpos(uri_string(), 'buyer/sales/orders') === 0) ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            <span class="sidebar-text">Sales Orders</span>
        </a>

        <a href="/buyer/orders" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (strpos(uri_string(), 'buyer/orders') === 0 && uri_string() !== 'buyer/sales/orders') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
            <span class="sidebar-text">My Orders</span>
        </a>

        <a href="/profile/edit" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'profile/edit') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="settings" class="w-4 h-4"></i>
            <span class="sidebar-text">Account Settings</span>
        </a>

        <form action="/auth/logout" method="POST" class="mt-3">
            <?= csrf_field() ?>
            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-red-600 font-medium swal-confirm-form" data-confirm="Log out from your account?">
                <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </nav>
</div>
