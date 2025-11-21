<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-[#2d7a3e] to-[#4a9b5a] text-white py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Direct Marketplace for Nasugbu Farmers
                </h1>
                <p class="text-xl mb-8 text-white/90">
                    Connecting local farmers directly with buyers. Fresh produce, fair prices, strong community.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/marketplace" class="bg-white text-[#2d7a3e] px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 text-center transition-colors">
                        Browse Products
                    </a>
                    <a href="/auth/register-farmer" class="bg-[#d97706] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#b45309] text-center transition-colors">
                        Register as Farmer
                    </a>
                </div>
            </div>
            
            <div class="relative">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border-2 border-white/20">
                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=600&h=400&fit=crop" alt="Farmers" class="w-full h-64 object-cover rounded-xl">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Navigation Cards -->
<section class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
            <a href="/marketplace" class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all p-6 text-center">
                <div class="flex flex-col items-center">
                    <i data-lucide="store" class="w-12 h-12 text-primary mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Marketplace</h3>
                    <p class="text-sm text-gray-600 mt-1">Browse products</p>
                </div>
            </a>

            <a href="/announcements" class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all p-6 text-center">
                <div class="flex flex-col items-center">
                    <i data-lucide="megaphone" class="w-12 h-12 text-primary mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Announcements</h3>
                    <p class="text-sm text-gray-600 mt-1">Latest updates</p>
                </div>
            </a>

            <a href="/forum" class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all p-6 text-center">
                <div class="flex flex-col items-center">
                    <i data-lucide="message-circle" class="w-12 h-12 text-primary mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Forum</h3>
                    <p class="text-sm text-gray-600 mt-1">Community discussions</p>
                </div>
            </a>

            <a href="/weather" class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all p-6 text-center">
                <div class="flex flex-col items-center">
                    <i data-lucide="cloud-sun" class="w-12 h-12 text-primary mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Weather</h3>
                    <p class="text-sm text-gray-600 mt-1">Local forecasts</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Why Choose AgriConnect?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="handshake" class="w-8 h-8 text-primary"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Direct Connection</h3>
                <p class="text-gray-600">Buy directly from farmers, eliminating middlemen and ensuring fair prices for everyone.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="leaf" class="w-8 h-8 text-primary"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Fresh & Local</h3>
                <p class="text-gray-600">All products are locally grown in Nasugbu, ensuring maximum freshness and quality.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="users" class="w-8 h-8 text-primary"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Community Support</h3>
                <p class="text-gray-600">Join a thriving community of farmers and buyers supporting local agriculture.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featured_products)): ?>
<section class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">Featured Products</h2>
            <a href="/marketplace" class="text-primary hover:text-primary-hover font-semibold flex items-center">
                View All
                <i data-lucide="arrow-right" class="w-5 h-5 ml-1"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featured_products as $product): ?>
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all overflow-hidden">
                    <?php if ($product['image_url']): ?>
                        <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <i data-lucide="package" class="w-16 h-16 text-green-600"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2"><?= esc($product['name']) ?></h3>
                        <p class="text-primary text-2xl font-bold mb-2">
                            ₱<?= number_format($product['price'], 2) ?>
                            <span class="text-sm text-gray-600">per <?= esc($product['unit']) ?></span>
                        </p>
                        <div class="text-sm text-gray-600 mb-1 flex items-center">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                            <?= esc($product['location'] ?? 'Nasugbu') ?>
                        </div>
                        <div class="text-sm text-gray-600 mb-3 flex items-center">
                            <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                            <?= esc($product['farmer_name']) ?>
                        </div>
                        
                        <a href="/marketplace/product/<?= $product['id'] ?>" class="block w-full bg-primary text-white py-2 rounded-lg text-center hover:bg-primary-hover transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="py-12 md:py-16 bg-primary text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Join AgriConnect?</h2>
        <p class="text-xl mb-8 text-white/90">Whether you're a farmer or a buyer, join our community today!</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/auth/register-farmer" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                I'm a Farmer
            </a>
            <a href="/auth/register-buyer" class="bg-accent text-white px-8 py-4 rounded-lg font-semibold hover:bg-accent/90 transition-colors">
                I'm a Buyer
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
