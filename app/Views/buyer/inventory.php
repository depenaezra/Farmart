<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">My Listings</h1>
                <p class="text-gray-600">Manage the products you are selling</p>
            </div>
            <a href="/buyer/products/add" class="farmart-interactive bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-primary-hover font-semibold transition-all shadow-sm hover:shadow-md">
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
            <a href="/buyer/products/add" class="farmart-interactive inline-block mt-4 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-all shadow-sm">
                Add Your First Product
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php foreach ($products as $product): ?>
                <div class="farmart-interactive farmart-compact-card bg-white rounded-xl shadow border border-gray-200 overflow-hidden hover:border-primary/40 transition-all flex flex-col">
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
                        <img src="<?= esc($inventoryImage) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-32 sm:h-36 object-cover shrink-0">
                    <?php else: ?>
                        <div class="w-full h-32 sm:h-36 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shrink-0">
                            <i data-lucide="package" class="w-12 h-12 text-green-600"></i>
                        </div>
                    <?php endif; ?>

                    <div class="p-3 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold line-clamp-2"><?= esc($product['name']) ?></h3>
                                <div class="inline-block px-1.5 py-0.5 bg-primary/10 text-primary text-[10px] font-semibold rounded mt-1">
                                    <?= ucfirst(esc($product['category'])) ?>
                                </div>
                            </div>
                        </div>

                        <p class="text-primary text-base sm:text-lg font-bold mb-1.5">
                            ₱<?= number_format($product['price'], 2) ?>
                            <span class="text-xs text-gray-600 font-normal">/ <?= esc($product['unit']) ?></span>
                        </p>

                        <div class="text-xs text-gray-600 mb-1.5 flex items-start gap-1">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 mt-0.5 shrink-0"></i>
                            <span class="line-clamp-2"><?= esc($product['location']) ?></span>
                        </div>

                        <div class="flex items-center justify-between gap-2 mb-2">
                            <div class="text-xs text-gray-600">
                                <span class="font-medium">Stock:</span> <?= esc($product['stock_quantity']) ?> <?= esc($product['unit']) ?>
                            </div>
                            <span class="inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded shrink-0
                                <?= $product['status'] === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= ucfirst(str_replace('-', ' ', $product['status'])) ?>
                            </span>
                        </div>

                        <div class="text-[10px] text-gray-500 mb-2">
                            Added <?= date('M d, Y', strtotime($product['created_at'])) ?>
                        </div>

                        <a href="/marketplace/product/<?= $product['id'] ?>" class="block w-full bg-primary text-white py-2 rounded-lg text-center text-sm font-semibold hover:bg-primary-hover transition-all duration-200 mt-auto shadow-sm hover:shadow-md hover:brightness-[1.02]">
                            View in marketplace
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


