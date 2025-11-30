<div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 sticky top-6">
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900">My Profile</h3>
        <p class="text-sm text-gray-600">Quick links to manage your account</p>
    </div>

    <nav class="space-y-2">
        <a href="/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'profile') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="user" class="w-4 h-4"></i>
            Profile
        </a>

        <a href="/buyer/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'buyer/dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="grid" class="w-4 h-4"></i>
            Dashboard
        </a>

        <a href="/buyer/inventory" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'buyer/inventory') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="package" class="w-4 h-4"></i>
            My Listings
        </a>

        <a href="/buyer/products/add" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'buyer/products/add') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Add Product
        </a>

        <a href="/cart" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'cart') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
            Cart
        </a>

        <a href="/buyer/sales/orders" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (strpos(uri_string(), 'buyer/sales/orders') === 0) ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            Sales Orders
        </a>

        <a href="/buyer/orders" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (strpos(uri_string(), 'buyer/orders') === 0 && uri_string() !== 'buyer/sales/orders') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
            My Orders
        </a>

        <a href="/profile/edit" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?= (uri_string() === 'profile/edit') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' ?>">
            <i data-lucide="settings" class="w-4 h-4"></i>
            Account Settings
        </a>

        <form action="/auth/logout" method="POST" class="mt-3">
            <?= csrf_field() ?>
            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-red-600 font-medium swal-confirm-form" data-confirm="Log out from your account?">
                <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                Logout
            </button>
        </form>
    </nav>
</div>
