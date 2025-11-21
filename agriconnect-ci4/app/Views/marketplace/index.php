<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Marketplace</h1>
        <p class="text-gray-600">Browse fresh produce directly from Nasugbu farmers</p>
    </div>
    
    <!-- Category Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="/marketplace?category=vegetables" class="bg-white rounded-xl shadow-md border-2 <?= ($filters['category'] ?? '') === 'vegetables' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300' ?> transition-all p-6 text-center">
            <div class="flex flex-col items-center">
                <i data-lucide="carrot" class="w-12 h-12 text-green-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Vegetables</h3>
                <p class="text-sm text-gray-600 mt-1">Fresh produce</p>
            </div>
        </a>

        <a href="/marketplace?category=fruits" class="bg-white rounded-xl shadow-md border-2 <?= ($filters['category'] ?? '') === 'fruits' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300' ?> transition-all p-6 text-center">
            <div class="flex flex-col items-center">
                <i data-lucide="apple" class="w-12 h-12 text-red-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Fruits</h3>
                <p class="text-sm text-gray-600 mt-1">Sweet & juicy</p>
            </div>
        </a>

        <a href="/marketplace?category=grains" class="bg-white rounded-xl shadow-md border-2 <?= ($filters['category'] ?? '') === 'grains' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-yellow-300' ?> transition-all p-6 text-center">
            <div class="flex flex-col items-center">
                <i data-lucide="wheat" class="w-12 h-12 text-yellow-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Grains</h3>
                <p class="text-sm text-gray-600 mt-1">Rice & staples</p>
            </div>
        </a>

        <a href="/marketplace?category=other" class="bg-white rounded-xl shadow-md border-2 <?= ($filters['category'] ?? '') === 'other' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300' ?> transition-all p-6 text-center">
            <div class="flex flex-col items-center">
                <i data-lucide="package" class="w-12 h-12 text-purple-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Other</h3>
                <p class="text-sm text-gray-600 mt-1">Miscellaneous</p>
            </div>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <form action="/marketplace" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="keyword" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                <input
                    type="text"
                    id="keyword"
                    name="keyword"
                    value="<?= esc($filters['keyword'] ?? '') ?>"
                    placeholder="Product name, farmer..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                >
            </div>

            <div>
                <label for="min_price" class="block text-sm font-semibold text-gray-700 mb-2">Min Price</label>
                <input
                    type="number"
                    id="min_price"
                    name="min_price"
                    value="<?= esc($filters['min_price'] ?? '') ?>"
                    placeholder="₱0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                >
            </div>

            <div>
                <label for="max_price" class="block text-sm font-semibold text-gray-700 mb-2">Max Price</label>
                <input
                    type="number"
                    id="max_price"
                    name="max_price"
                    value="<?= esc($filters['max_price'] ?? '') ?>"
                    placeholder="₱1000"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                >
            </div>
            
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover font-semibold">
                    <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                    Search
                </button>
                <a href="/marketplace" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 font-semibold">
                    Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="text-center py-12">
            <i data-lucide="package-open" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No products found</p>
            <p class="text-gray-500 mt-2">Try adjusting your search filters</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all overflow-hidden">
                    <?php if ($product['image_url']): ?>
                        <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <i data-lucide="package" class="w-16 h-16 text-green-600"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <div class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded mb-2">
                            <?= ucfirst(esc($product['category'])) ?>
                        </div>
                        
                        <h3 class="text-xl font-semibold mb-2"><?= esc($product['name']) ?></h3>
                        <p class="text-primary text-2xl font-bold mb-2">
                            ₱<?= number_format($product['price'], 2) ?>
                            <span class="text-sm text-gray-600">/ <?= esc($product['unit']) ?></span>
                        </p>
                        
                        <div class="text-sm text-gray-600 mb-1 flex items-center">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                            <?= esc($product['location'] ?? 'Nasugbu') ?>
                        </div>
                        <div class="text-sm text-gray-600 mb-1 flex items-center">
                            <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                            <?= esc($product['farmer_name']) ?>
                        </div>
                        <div class="text-sm text-gray-600 mb-3 flex items-center">
                            <i data-lucide="package-check" class="w-4 h-4 mr-1"></i>
                            <?= esc($product['stock_quantity']) ?> <?= esc($product['unit']) ?> available
                        </div>
                        
                        <a href="/marketplace/product/<?= $product['id'] ?>" class="block w-full bg-primary text-white py-2 rounded-lg text-center hover:bg-primary-hover transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
