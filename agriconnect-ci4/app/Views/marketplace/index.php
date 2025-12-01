<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="px-4 py-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-2xl p-8 mb-8 text-white">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Fresh from Farm to Table</h1>
            <p class="text-xl mb-8 opacity-90">Discover the finest produce from Nasugbu farmers</p>

            <!-- Prominent Search Bar -->
            <form action="/marketplace" method="GET" class="max-w-2xl mx-auto">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            name="keyword"
                            value="<?= esc($filters['keyword'] ?? '') ?>"
                            placeholder="Search for fresh produce, farmers..."
                            class="w-full px-6 py-4 rounded-xl text-gray-900 text-lg focus:outline-none focus:ring-2 focus:ring-white/50"
                        >
                        <i data-lucide="search" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-500"></i>
                    </div>
                    <button type="submit" class="bg-white text-green-700 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Cards -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="/marketplace?category=vegetables" class="bg-white rounded-xl shadow-md border-2 <?= ($filters['category'] ?? '') === 'vegetables' ? 'border-green-500 bg-green-50 scale-105' : 'border-gray-200 hover:border-green-300 hover:scale-105' ?> transition-all duration-300 p-6 text-center group">
                <div class="flex flex-col items-center">
                    <div class="bg-green-100 rounded-full p-3 mb-3 group-hover:bg-green-200 transition-colors">
                        <i data-lucide="carrot" class="w-12 h-12 text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Vegetables</h3>
                    <p class="text-sm text-gray-600 mt-1">Fresh produce</p>
                </div>
            </a>

            <a href="/marketplace?category=fruits" class="bg-white rounded-xl shadow-md border-2 <?= ($filters['category'] ?? '') === 'fruits' ? 'border-red-500 bg-red-50 scale-105' : 'border-gray-200 hover:border-red-300 hover:scale-105' ?> transition-all duration-300 p-6 text-center group">
                <div class="flex flex-col items-center">
                    <div class="bg-red-100 rounded-full p-3 mb-3 group-hover:bg-red-200 transition-colors">
                        <i data-lucide="apple" class="w-12 h-12 text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Fruits</h3>
                    <p class="text-sm text-gray-600 mt-1">Sweet & juicy</p>
                </div>
            </a>

            <a href="/marketplace?category=grains" class="bg-white rounded-xl shadow-md border-2 <?= ($filters['category'] ?? '') === 'grains' ? 'border-yellow-500 bg-yellow-50 scale-105' : 'border-gray-200 hover:border-yellow-300 hover:scale-105' ?> transition-all duration-300 p-6 text-center group">
                <div class="flex flex-col items-center">
                    <div class="bg-yellow-100 rounded-full p-3 mb-3 group-hover:bg-yellow-200 transition-colors">
                        <i data-lucide="wheat" class="w-12 h-12 text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Grains</h3>
                    <p class="text-sm text-gray-600 mt-1">Rice & staples</p>
                </div>
            </a>

            <a href="/marketplace?category=other" class="bg-white rounded-xl shadow-md border-2 <?= ($filters['category'] ?? '') === 'other' ? 'border-purple-500 bg-purple-50 scale-105' : 'border-gray-200 hover:border-purple-300 hover:scale-105' ?> transition-all duration-300 p-6 text-center group">
                <div class="flex flex-col items-center">
                    <div class="bg-purple-100 rounded-full p-3 mb-3 group-hover:bg-purple-200 transition-colors">
                        <i data-lucide="package" class="w-12 h-12 text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Other</h3>
                    <p class="text-sm text-gray-600 mt-1">Miscellaneous</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Products</h3>
        <form action="/marketplace" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                <input
                    type="number"
                    id="min_price"
                    name="min_price"
                    value="<?= esc($filters['min_price'] ?? '') ?>"
                    placeholder="₱0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                >
            </div>

            <div class="flex-1">
                <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                <input
                    type="number"
                    id="max_price"
                    name="max_price"
                    value="<?= esc($filters['max_price'] ?? '') ?>"
                    placeholder="₱1000"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                >
            </div>

            <div class="flex gap-2 items-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                    <i data-lucide="filter" class="w-4 h-4 inline mr-1"></i>
                    Apply
                </button>
                <a href="/marketplace" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="text-center py-16">
            <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="package-open" class="w-12 h-12 text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 mb-2">No products found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your search filters or browse our categories</p>
            <a href="/marketplace" class="bg-primary text-white px-8 py-3 rounded-xl hover:bg-primary-hover transition-colors font-semibold">
                Browse All Products
            </a>
        </div>
    <?php else: ?>
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Fresh Products</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
            <?php foreach ($products as $product): ?>
                <a href="/marketplace/product/<?= $product['id'] ?>" class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl hover:scale-105 transition-all duration-300 overflow-hidden group aspect-square block">
                    <div class="relative h-full">
                        <?php
                        $previewImage = null;
                        if (!empty($product['image_url'])) {
                            $decoded = json_decode($product['image_url'], true);
                            if (is_array($decoded)) {
                                $previewImage = $decoded[0]; // Show first image
                            } else {
                                $previewImage = $product['image_url'];
                            }
                        }
                        ?>
                        <?php if ($previewImage): ?>
                            <img src="<?= esc($previewImage) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-2/3 object-cover group-hover:scale-110 transition-transform duration-300">
                        <?php else: ?>
                            <div class="w-full h-2/3 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                <i data-lucide="package" class="w-16 h-16 text-green-600"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-3 left-3">
                            <span class="bg-white/90 text-primary text-xs font-semibold px-2 py-1 rounded-full">
                                <?= ucfirst(esc($product['category'])) ?>
                            </span>
                        </div>

                        <div class="p-4 h-1/3 flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-semibold mb-1 line-clamp-2"><?= esc($product['name']) ?></h3>
                                <p class="text-primary text-lg font-bold">
                                    ₱<?= number_format($product['price'], 2) ?>
                                    <span class="text-xs text-gray-600 font-normal">/ <?= esc($product['unit']) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


