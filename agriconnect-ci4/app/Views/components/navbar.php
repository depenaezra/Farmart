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
                        <a href="/profile" class="text-gray-700 hover:text-primary font-medium flex items-center"><i data-lucide="user" class="w-6 h-6 mr-2"></i>My Profile</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <?php if (session()->has('logged_in') && session()->get('logged_in')): ?>
                    <a href="/auth/logout" class="text-red-600 hover:text-red-700 font-medium flex items-center"><i data-lucide="log-out" class="w-6 h-6 mr-2"></i>Logout</a>
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
                <a href="/messages/inbox" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="mail" class="w-6 h-6 mr-2"></i>Messages</a>
                <a href="/profile" class="block py-2 text-gray-700 hover:text-primary"><i data-lucide="user" class="w-6 h-6 mr-2"></i>My Profile</a>
                <a href="/auth/logout" class="block py-2 text-red-600"><i data-lucide="log-out" class="w-6 h-6 mr-2"></i>Logout</a>
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
</script>
