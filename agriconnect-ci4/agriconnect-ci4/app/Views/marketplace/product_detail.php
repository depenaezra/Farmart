<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="/marketplace" class="inline-flex items-center text-primary hover:text-primary-hover mb-6">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Marketplace
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 overflow-hidden">
                <?php if ($product['image_url']): ?>
                    <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-96 object-cover">
                <?php else: ?>
                    <div class="w-full h-96 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                        <i data-lucide="package" class="w-24 h-24 text-green-600"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Details -->
            <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 p-6">
                <div class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded mb-4">
                    <?= ucfirst(esc($product['category'])) ?>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= esc($product['name']) ?></h1>

                <div class="text-primary text-4xl font-bold mb-6">
                    ₱<?= number_format($product['price'], 2) ?>
                    <span class="text-lg text-gray-600">/ <?= esc($product['unit']) ?></span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="map-pin" class="w-5 h-5 mr-3"></i>
                        <span>Location: <?= esc($product['farmer_location'] ?? 'Nasugbu') ?></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="user" class="w-5 h-5 mr-3"></i>
                        <span>Farmer: <?= esc($product['farmer_name']) ?></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="phone" class="w-5 h-5 mr-3"></i>
                        <span>Contact: <?= esc($product['farmer_phone']) ?></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="package-check" class="w-5 h-5 mr-3"></i>
                        <span>Stock: <?= esc($product['stock_quantity']) ?> <?= esc($product['unit']) ?> available</span>
                    </div>
                    <?php if ($product['cooperative']): ?>
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                        <span>Cooperative: <?= esc($product['cooperative']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($product['description']): ?>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-700 leading-relaxed"><?= nl2br(esc($product['description'])) ?></p>
                </div>
                <?php endif; ?>

                <!-- Add to Cart Button (if authenticated as buyer) -->
                <?php if (session()->get('user_role') === 'buyer'): ?>
                <button class="w-full bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary-hover transition-colors font-semibold">
                    <i data-lucide="shopping-cart" class="w-5 h-5 inline mr-2"></i>
                    Add to Cart
                </button>
                <?php elseif (!session()->has('user_id')): ?>
                <a href="/auth/login" class="block w-full bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary-hover transition-colors font-semibold text-center">
                    <i data-lucide="log-in" class="w-5 h-5 inline mr-2"></i>
                    Login to Purchase
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Other Products from Same Farmer -->
        <?php if (!empty($other_products)): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">More from <?= esc($product['farmer_name']) ?></h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($other_products as $other): ?>
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all overflow-hidden">
                    <?php if ($other['image_url']): ?>
                        <img src="<?= esc($other['image_url']) ?>" alt="<?= esc($other['name']) ?>" class="w-full h-32 object-cover">
                    <?php else: ?>
                        <div class="w-full h-32 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <i data-lucide="package" class="w-8 h-8 text-green-600"></i>
                        </div>
                    <?php endif; ?>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-1"><?= esc($other['name']) ?></h3>
                        <p class="text-primary font-bold">
                            ₱<?= number_format($other['price'], 2) ?>
                            <span class="text-sm text-gray-600">/ <?= esc($other['unit']) ?></span>
                        </p>
                        <a href="/marketplace/product/<?= $other['id'] ?>" class="block w-full bg-gray-100 text-gray-700 py-2 rounded-lg text-center hover:bg-gray-200 transition-colors mt-2 text-sm">
                            View Details
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>