<div id="admin-sidebar" class="farmart-admin-sidebar w-64 shadow-lg fixed left-0 top-0 z-40 pb-6 min-h-screen transition-all duration-300">
    <div id="sidebar-header" class="p-6 border-b border-white/10">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center space-x-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-emerald-800 flex items-center justify-center shadow-lg shadow-black/20 shrink-0">
                    <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                </div>
                <div class="min-w-0">
                    <span id="sidebar-title" class="text-base font-bold text-white tracking-tight block truncate">Farmart</span>
                    <span class="text-xs font-medium text-emerald-300/90 uppercase tracking-wider">Admin</span>
                </div>
            </div>
            <button type="button" id="sidebar-toggle" class="text-slate-400 hover:text-white transition-colors flex-shrink-0 rounded-lg p-1 hover:bg-white/10" aria-label="Toggle sidebar">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
        </div>
    </div>

    <nav id="nav-container" class="mt-5 px-3 space-y-1">
            <a href="/admin/dashboard" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="/admin/users" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="users" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">Users</span>
            </a>

            <a href="/admin/products" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="package" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">Products</span>
            </a>

            <a href="/admin/violations" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="flag" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">Violations</span>
            </a>

            <a href="/admin/announcements" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="megaphone" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">Announcements</span>
            </a>

            <a href="/admin/analytics" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">Analytics</span>
            </a>

            <a href="/admin/email-blocker" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="shield-x" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">Email Blocker</span>
            </a>

            <a href="/messages/inbox" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="mail" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">Messages</span>
            </a>

            <a href="/profile" class="sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="user" class="w-5 h-5 mr-3 shrink-0"></i>
                <span class="sidebar-text">My Profile</span>
            </a>

            <div class="pt-4 mt-4 border-t border-white/10">
            <form action="/auth/logout" method="POST" class="m-0 swal-confirm-form" data-confirm-title="Sign out?" data-confirm="You will leave your session and need to sign in again to use the admin panel." data-confirm-ok="Sign out" data-confirm-icon="question">
                <?= csrf_field() ?>
                <button type="submit" class="w-full sidebar-link flex items-center px-4 py-3 rounded-xl transition-all duration-200">
                    <i data-lucide="log-out" class="w-5 h-5 mr-3 shrink-0"></i>
                    <span class="sidebar-text">Logout</span>
                </button>
            </form>
            </div>
    </nav>
</div>
