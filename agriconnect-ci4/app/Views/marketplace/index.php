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
        <h2 class="text-2xl font-bold text-gray-900 mb-6 inline-flex items-center gap-2">
            <span class="w-9 h-9 rounded-lg bg-primary text-white inline-flex items-center justify-center">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
            </span>
            Shop by Category
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <a href="/marketplace?category=vegetables" class="farmart-interactive bg-white rounded-xl shadow border-2 <?= ($filters['category'] ?? '') === 'vegetables' ? 'border-green-500 bg-green-50 ring-1 ring-green-200' : 'border-gray-200 hover:border-green-300' ?> transition-all duration-200 p-3 sm:p-4 text-center group">
                <div class="flex flex-col items-center">
                    <div class="bg-green-100 rounded-full p-2 mb-2 group-hover:bg-green-200 transition-colors">
                        <i data-lucide="carrot" class="w-8 h-8 sm:w-9 sm:h-9 text-green-600"></i>
                    </div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900">Vegetables</h3>
                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-1">Fresh produce</p>
                </div>
            </a>

            <a href="/marketplace?category=fruits" class="farmart-interactive bg-white rounded-xl shadow border-2 <?= ($filters['category'] ?? '') === 'fruits' ? 'border-red-500 bg-red-50 ring-1 ring-red-200' : 'border-gray-200 hover:border-red-300' ?> transition-all duration-200 p-3 sm:p-4 text-center group">
                <div class="flex flex-col items-center">
                    <div class="bg-red-100 rounded-full p-2 mb-2 group-hover:bg-red-200 transition-colors">
                        <i data-lucide="apple" class="w-8 h-8 sm:w-9 sm:h-9 text-red-600"></i>
                    </div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900">Fruits</h3>
                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-1">Sweet &amp; juicy</p>
                </div>
            </a>

            <a href="/marketplace?category=grains" class="farmart-interactive bg-white rounded-xl shadow border-2 <?= ($filters['category'] ?? '') === 'grains' ? 'border-yellow-500 bg-yellow-50 ring-1 ring-yellow-200' : 'border-gray-200 hover:border-yellow-300' ?> transition-all duration-200 p-3 sm:p-4 text-center group">
                <div class="flex flex-col items-center">
                    <div class="bg-yellow-100 rounded-full p-2 mb-2 group-hover:bg-yellow-200 transition-colors">
                        <i data-lucide="wheat" class="w-8 h-8 sm:w-9 sm:h-9 text-yellow-600"></i>
                    </div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900">Grains</h3>
                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-1">Rice &amp; staples</p>
                </div>
            </a>

            <a href="/marketplace?category=other" class="farmart-interactive bg-white rounded-xl shadow border-2 <?= ($filters['category'] ?? '') === 'other' ? 'border-purple-500 bg-purple-50 ring-1 ring-purple-200' : 'border-gray-200 hover:border-purple-300' ?> transition-all duration-200 p-3 sm:p-4 text-center group">
                <div class="flex flex-col items-center">
                    <div class="bg-purple-100 rounded-full p-2 mb-2 group-hover:bg-purple-200 transition-colors">
                        <i data-lucide="package" class="w-8 h-8 sm:w-9 sm:h-9 text-purple-600"></i>
                    </div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900">Other</h3>
                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-1">Miscellaneous</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow border border-gray-200 p-4 sm:p-5 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 inline-flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="w-5 h-5 text-primary"></i>
            Filter Products
        </h3>
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
                <a href="/marketplace" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-semibold transition-colors farmart-interactive" data-confirm-title="Clear filters?" data-confirm="Search, category, and price filters will be reset so you browse the full marketplace." data-confirm-ok="Clear" data-confirm-icon="question">
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
            <h2 class="text-2xl font-bold text-gray-900 mb-6 inline-flex items-center gap-2">
                <span class="w-9 h-9 rounded-lg bg-mint-light text-primary inline-flex items-center justify-center border border-mint">
                    <i data-lucide="leaf" class="w-5 h-5"></i>
                </span>
                Fresh Products
            </h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3 sm:gap-4">
            <?php foreach ($products as $product): ?>
                <a href="/marketplace/product/<?= $product['id'] ?>" class="farmart-interactive farmart-compact-card bg-white rounded-xl shadow border border-gray-200 overflow-hidden group flex flex-col h-full">
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
                    <div class="relative h-28 sm:h-32 shrink-0 overflow-hidden bg-gradient-to-br from-green-50 to-green-100">
                        <?php if ($previewImage): ?>
                            <img src="<?= esc($previewImage) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="package" class="w-10 h-10 text-green-600"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-2 left-2">
                            <span class="bg-white/90 text-primary text-[10px] sm:text-xs font-semibold px-1.5 py-0.5 rounded-full">
                                <?= ucfirst(esc($product['category'])) ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-2.5 sm:p-3 flex flex-col flex-1 min-h-0">
                        <h3 class="text-xs sm:text-sm font-semibold line-clamp-2 text-gray-900 leading-snug"><?= esc($product['name']) ?></h3>
                        <p class="text-primary text-sm sm:text-base font-bold mt-auto pt-2">
                            ₱<?= number_format($product['price'], 2) ?>
                            <span class="text-[10px] sm:text-xs text-gray-600 font-normal">/ <?= esc($product['unit']) ?></span>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


