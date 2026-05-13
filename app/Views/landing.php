<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-[#14532d] via-[#166534] to-[#15803d] text-white py-14 md:py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.04\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-90 pointer-events-none" aria-hidden="true"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14 items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-emerald-200/90 mb-3">Nasugbu agricultural marketplace</p>
                <h1 class="text-4xl md:text-5xl lg:text-[3.25rem] font-bold mb-5 leading-tight tracking-tight">
                    Direct marketplace for local farmers
                </h1>
                <p class="text-lg md:text-xl mb-9 text-white/85 max-w-xl leading-relaxed">
                    Connect growers with buyers. Fresh produce, fair prices, and a stronger community.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="/marketplace" class="bg-white text-primary px-8 py-3.5 rounded-xl font-semibold hover:bg-slate-100 text-center transition-all shadow-lg shadow-black/20">
                        Browse products
                    </a>
                    <a href="/auth/register-buyer" class="bg-amber-500 text-white px-8 py-3.5 rounded-xl font-semibold hover:bg-amber-600 text-center transition-all ring-2 ring-white/20 shadow-lg shadow-black/15">
                        Create account
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
<section class="py-14 md:py-20 bg-mint-light">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-14">
            <a href="/marketplace" class="farmart-card rounded-2xl p-5 md:p-6 text-center group hover:-translate-y-0.5">
                <div class="flex flex-col items-center">
                    <i data-lucide="store" class="w-12 h-12 text-green-600 mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Marketplace</h3>
                    <p class="text-sm text-gray-600 mt-1">Browse products</p>
                </div>
            </a>

            <a href="/announcements" class="farmart-card rounded-2xl p-5 md:p-6 text-center group hover:-translate-y-0.5">
                <div class="flex flex-col items-center">
                    <i data-lucide="megaphone" class="w-12 h-12 text-green-600 mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Announcements</h3>
                    <p class="text-sm text-gray-600 mt-1">Latest updates</p>
                </div>
            </a>

            <a href="/forum" class="farmart-card rounded-2xl p-5 md:p-6 text-center group hover:-translate-y-0.5">
                <div class="flex flex-col items-center">
                    <i data-lucide="message-circle" class="w-12 h-12 text-green-600 mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Forum</h3>
                    <p class="text-sm text-gray-600 mt-1">Community discussions</p>
                </div>
            </a>

            <a href="/weather" class="farmart-card rounded-2xl p-5 md:p-6 text-center group hover:-translate-y-0.5">
                <div class="flex flex-col items-center">
                    <i data-lucide="cloud-sun" class="w-12 h-12 text-green-600 mb-3"></i>
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
        <h2 class="text-3xl font-bold text-center mb-12">Why Choose Farmart?</h2>
        
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
<section class="py-12 md:py-16 bg-mint-light">
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
                <div class="farmart-card rounded-2xl overflow-hidden group hover:-translate-y-0.5">
                    <?php
                    $landingPreviewImage = null;
                    if (!empty($product['image_url'])) {
                        $decoded = json_decode($product['image_url'], true);
                        if (is_array($decoded)) {
                            $landingPreviewImage = $decoded[0];
                        } else {
                            $landingPreviewImage = $product['image_url'];
                        }
                    }
                    ?>
                    <?php if ($landingPreviewImage): ?>
                        <img src="<?= esc($landingPreviewImage) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-48 object-cover">
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
<section class="py-12 md:py-16 bg-success text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Join Farmart?</h2>
        <p class="text-xl mb-8 text-white/90">Whether you're a farmer or a buyer, join our community today!</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/auth/register-buyer" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Register
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
