<div id="admin-sidebar" class="w-64 bg-white shadow-lg fixed left-0 top-0 z-40 pb-4 min-h-screen transition-all duration-300">
    <div id="sidebar-header" class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                </div>
                <span id="sidebar-title" class="text-lg font-bold text-blue-600">Admin Panel</span>
            </div>
            <button id="sidebar-toggle" class="text-gray-500 hover:text-primary transition-colors flex-shrink-0">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
        </div>
    </div>

    <nav id="nav-container" class="mt-6 px-4 space-y-1">
            <a href="/admin/dashboard" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="/admin/users" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                <span class="sidebar-text">Users</span>
            </a>

            <a href="/admin/products" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                <span class="sidebar-text">Products</span>
            </a>

            <a href="/admin/violations" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="flag" class="w-5 h-5 mr-3"></i>
                <span class="sidebar-text">Violations</span>
            </a>

            <a href="/admin/announcements" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="megaphone" class="w-5 h-5 mr-3"></i>
                <span class="sidebar-text">Announcements</span>
            </a>

            <a href="/admin/analytics" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                <span class="sidebar-text">Analytics</span>
            </a>

            <a href="/messages/inbox" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="mail" class="w-5 h-5 mr-3"></i>
                <span class="sidebar-text">Messages</span>
            </a>

            <a href="/profile" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white rounded-lg transition-colors">
                <i data-lucide="user" class="w-5 h-5 mr-3"></i>
                <span class="sidebar-text">My Profile</span>
            </a>


            <form action="/auth/logout" method="POST" class="m-0">
                <?= csrf_field() ?>
                <button type="submit" class="w-full sidebar-link flex items-center px-4 py-3 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition-colors swal-confirm-form" data-confirm="Log out from your account?">
                    <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                    <span class="sidebar-text">Logout</span>
                </button>
            </form>
    </nav>
</div>