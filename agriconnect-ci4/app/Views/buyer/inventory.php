<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">My Listings</h1>
                <p class="text-gray-600">Manage the products you are selling</p>
            </div>
            <a href="/buyer/products/add" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                Add Product
            </a>
        </div>
    </div>

    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="text-center py-12">
            <i data-lucide="package-open" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No listings yet</p>
            <p class="text-gray-500 mt-2">Start by adding your first product to sell</p>
            <a href="/buyer/products/add" class="inline-block mt-4 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                Add Your First Product
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 overflow-hidden hover:border-primary transition-all">
                    <?php
                    $inventoryImage = null;
                    if (!empty($product['image_url'])) {
                        $decoded = json_decode($product['image_url'], true);
                        if (is_array($decoded)) {
                            $inventoryImage = $decoded[0];
                        } else {
                            $inventoryImage = $product['image_url'];
                        }
                    }
                    ?>
                    <?php if ($inventoryImage): ?>
                        <img src="<?= esc($inventoryImage) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <i data-lucide="package" class="w-16 h-16 text-green-600"></i>
                        </div>
                    <?php endif; ?>

                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold mb-1"><?= esc($product['name']) ?></h3>
                                <div class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded mb-2">
                                    <?= ucfirst(esc($product['category'])) ?>
                                </div>
                            </div>
                        </div>

                        <p class="text-primary text-xl font-bold mb-2">
                            ₱<?= number_format($product['price'], 2) ?>
                            <span class="text-sm text-gray-600">/ <?= esc($product['unit']) ?></span>
                        </p>

                        <div class="text-sm text-gray-600 mb-2 flex items-center">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                            <?= esc($product['location']) ?>
                        </div>

                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Stock:</span> <?= esc($product['stock_quantity']) ?> <?= esc($product['unit']) ?>
                            </div>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                <?= $product['status'] === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= ucfirst(str_replace('-', ' ', $product['status'])) ?>
                            </span>
                        </div>

                        <div class="text-xs text-gray-500">
                            Added: <?= date('M d, Y', strtotime($product['created_at'])) ?>
                        </div>

                        <a href="/marketplace/product/<?= $product['id'] ?>" class="block w-full bg-primary text-white py-2 rounded-lg text-center hover:bg-primary-hover transition-colors mt-3">
                            View in Marketplace
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


