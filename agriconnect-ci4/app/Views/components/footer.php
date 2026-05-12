<footer class="bg-slate-950 text-slate-100 mt-auto border-t border-white/10">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="mb-4">
                    <img src="/img/farmart_logo_footer.png" alt="Farmart" class="h-16 w-auto">
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Connecting Nasugbu farmers directly with local buyers. Fresh produce, fair prices, strong community.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4 text-white tracking-tight">Quick Links</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="/marketplace" class="text-slate-400 hover:text-emerald-300 transition-colors">Marketplace</a></li>
                    <li><a href="/weather" class="text-slate-400 hover:text-emerald-300 transition-colors">Weather</a></li>
                    <li><a href="/announcements" class="text-slate-400 hover:text-emerald-300 transition-colors">Announcements</a></li>
                    <li><a href="/forum" class="text-slate-400 hover:text-emerald-300 transition-colors">Community Forum</a></li>
                </ul>
            </div>
            
            <!-- Get Started -->
            <div>
                <h4 class="font-semibold mb-4 text-white tracking-tight">Get Started</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="/auth/register-buyer" class="text-slate-400 hover:text-emerald-300 transition-colors">Register</a></li>
                    <li><a href="/auth/login" class="text-slate-400 hover:text-emerald-300 transition-colors">Login</a></li>
                    <li><a href="/#how-it-works" class="text-slate-400 hover:text-emerald-300 transition-colors">How It Works</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h4 class="font-semibold mb-4 text-white tracking-tight">Contact</h4>
                <ul class="space-y-2.5 text-sm text-slate-400">
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
        
        <hr class="border-white/10 my-8">
        
        <div class="flex flex-col md:flex-row justify-between items-center text-sm text-slate-500">
            <p>&copy; <?= date('Y') ?> Farmart. All rights reserved.</p>
            <p class="mt-2 md:mt-0">
                Built for Nasugbu farming community
            </p>
        </div>
    </div>
</footer>
