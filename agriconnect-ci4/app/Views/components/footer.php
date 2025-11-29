<footer class="bg-gray-800 text-white mt-auto">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <i data-lucide="sprout" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold">Farmart</h3>
                </div>
                <p class="text-gray-400 text-sm">
                    Connecting Nasugbu farmers directly with local buyers. Fresh produce, fair prices, strong community.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/marketplace" class="text-gray-400 hover:text-white">Marketplace</a></li>
                    <li><a href="/weather" class="text-gray-400 hover:text-white">Weather</a></li>
                    <li><a href="/announcements" class="text-gray-400 hover:text-white">Announcements</a></li>
                    <li><a href="/forum" class="text-gray-400 hover:text-white">Community Forum</a></li>
                </ul>
            </div>
            
            <!-- For Farmers -->
            <div>
                <h4 class="font-semibold mb-4">For Farmers</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/auth/register-farmer" class="text-gray-400 hover:text-white">Register as Farmer</a></li>
                    <li><a href="/auth/login" class="text-gray-400 hover:text-white">Farmer Login</a></li>
                    <li><a href="/#how-it-works" class="text-gray-400 hover:text-white">How It Works</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h4 class="font-semibold mb-4">Contact</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-start">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 mt-0.5"></i>
                        <span>Nasugbu, Batangas, Philippines</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="mail" class="w-4 h-4 mr-2 mt-0.5"></i>
                        <span>support@agriconnect.ph</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="phone" class="w-4 h-4 mr-2 mt-0.5"></i>
                        <span>0943-123-4567</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <hr class="border-gray-700 my-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
            <p>&copy; <?= date('Y') ?> Farmart. All rights reserved.</p>
            <p class="mt-2 md:mt-0">
                Built for Nasugbu farming community
            </p>
        </div>
    </div>
</footer>
