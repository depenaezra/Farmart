<div class="w-64 bg-white shadow-lg fixed left-0 top-0 z-40 pb-4">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                <i data-lucide="shield" class="w-5 h-5 text-white"></i>
            </div>
            <span class="text-lg font-bold text-primary">Admin Panel</span>
        </div>
    </div>

    <nav class="mt-6">
        <div class="px-4 space-y-0.5">
            <a href="/admin/dashboard" class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                Dashboard
            </a>

            <a href="/admin/users" class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                Users
            </a>

            <a href="/admin/products" class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                Products
            </a>

            <a href="/admin/violations" class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="flag" class="w-5 h-5 mr-3"></i>
                Violations
            </a>

            <a href="/admin/announcements" class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="megaphone" class="w-5 h-5 mr-3"></i>
                Announcements
            </a>

            <a href="/admin/analytics" class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                Analytics
            </a>

            <a href="/messages/inbox" class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="mail" class="w-5 h-5 mr-3"></i>
                Messages
            </a>

            <a href="/profile" class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="user" class="w-5 h-5 mr-3"></i>
                My Profile
            </a>

            <a href="/auth/logout" class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                Logout
            </a>
        </div>
    </nav>
</div>