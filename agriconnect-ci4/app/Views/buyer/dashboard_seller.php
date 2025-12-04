<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Seller Dashboard</h1>
        <p class="text-gray-600">Welcome back, <?= esc($userName) ?>! Manage the products you sell and track your performance.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Listings Card -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-sm border border-green-100 p-6 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="p-3 bg-green-500 rounded-xl shadow-lg shadow-green-500/30">
                            <i data-lucide="package" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-green-700 mb-1">Active Listings</p>
                    <p class="text-gray-900"><?= $statistics['products']['total_products'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-sm border border-blue-100 p-6 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="p-3 bg-blue-500 rounded-xl shadow-lg shadow-blue-500/30">
                            <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-blue-700 mb-1">Total Orders</p>
                    <p class="text-gray-900"><?= $statistics['orders']['total_orders'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- Sales Revenue Card -->
        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-2xl shadow-sm border border-amber-100 p-6 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="p-3 bg-amber-500 rounded-xl shadow-lg shadow-amber-500/30">
                            <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-amber-700 mb-1">Sales Revenue</p>
                    <p class="text-gray-900">₱<?= number_format($statistics['orders']['total_sales'] ?? 0, 2) ?></p>
                </div>
            </div>
        </div>

        <!-- Pending Orders Card -->
        <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-2xl shadow-sm border border-purple-100 p-6 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="p-3 bg-purple-500 rounded-xl shadow-lg shadow-purple-500/30">
                            <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-purple-700 mb-1">Pending Orders</p>
                    <p class="text-gray-900"><?= $statistics['orders']['pending'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Spoilage Alerts -->
    <?php
    $hasAlerts = !empty($spoilage_alerts['early_warning']) || !empty($spoilage_alerts['nearing_spoilage']) || !empty($spoilage_alerts['spoiled']);
    if ($hasAlerts):
    ?>

    <!-- Early Warning Alerts (5-7 days) -->
    <?php if (!empty($spoilage_alerts['early_warning'])): ?>
    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl shadow-sm border-2 border-yellow-200 p-6 mb-6 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="p-3 bg-yellow-500 rounded-xl shadow-lg mr-4">
                <i data-lucide="clock" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h3 class="text-gray-900 mb-1">Early Spoilage Warning</h3>
                <p class="text-yellow-700">Products expiring in 5-7 days - plan ahead</p>
            </div>
        </div>

        <div class="space-y-3">
            <?php foreach ($spoilage_alerts['early_warning'] as $product): ?>
                <div class="flex items-center justify-between p-5 bg-white rounded-xl border-2 border-yellow-100 hover:border-yellow-200 transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center flex-1 min-w-0">
                        <?php
                        $imageUrl = '';
                        if (!empty($product['image_url'])) {
                            $decoded = json_decode($product['image_url'], true);
                            if (is_array($decoded) && !empty($decoded)) {
                                $imageUrl = $decoded[0];
                            } else {
                                $imageUrl = $product['image_url'];
                            }
                        }
                        ?>
                        <?php if ($imageUrl): ?>
                            <div class="w-16 h-16 rounded-xl overflow-hidden mr-4 flex-shrink-0 ring-2 ring-yellow-200">
                                <img src="<?= base_url($imageUrl) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-full object-cover">
                            </div>
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl mr-4 flex items-center justify-center flex-shrink-0 ring-2 ring-yellow-200">
                                <i data-lucide="package" class="w-7 h-7 text-gray-400"></i>
                            </div>
                        <?php endif; ?>

                        <div class="min-w-0 flex-1">
                            <p class="text-gray-900 mb-1 truncate"><?= esc($product['name']) ?></p>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-gray-600">
                                <span class="flex items-center">
                                    <i data-lucide="tag" class="w-3 h-3 mr-1"></i>
                                    ₱<?= number_format($product['price'], 2) ?>
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="package" class="w-3 h-3 mr-1"></i>
                                    <?= $product['stock_quantity'] ?> in stock
                                </span>
                                <span class="flex items-center text-yellow-700">
                                    <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                    <?= date('M d, Y', strtotime($product['spoilage_date'])) ?> (<?= $product['days_until_spoilage'] ?> days)
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 ml-4 flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                            <i data-lucide="alert-circle" class="w-3 h-3 mr-1.5"></i>
                            Early Warning
                        </span>
                        <a href="/buyer/products/edit/<?= $product['id'] ?>" class="bg-yellow-600 text-white px-5 py-2.5 rounded-xl hover:bg-yellow-700 transition-colors duration-200 shadow-sm hover:shadow">
                            Update Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Critical Spoilage Alerts (1-3 days) -->
    <?php if (!empty($spoilage_alerts['nearing_spoilage'])): ?>
    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl shadow-sm border-2 border-orange-300 p-6 mb-6 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="p-3 bg-orange-500 rounded-xl shadow-lg mr-4 animate-pulse">
                <i data-lucide="alert-triangle" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h3 class="text-gray-900 mb-1">Urgent Spoilage Alert</h3>
                <p class="text-orange-700">Products expiring within 3 days - immediate action needed</p>
            </div>
        </div>

        <div class="space-y-3">
            <?php foreach ($spoilage_alerts['nearing_spoilage'] as $product): ?>
                <div class="flex items-center justify-between p-5 bg-white rounded-xl border-2 border-orange-200 hover:border-orange-300 transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center flex-1 min-w-0">
                        <?php
                        $imageUrl = '';
                        if (!empty($product['image_url'])) {
                            $decoded = json_decode($product['image_url'], true);
                            if (is_array($decoded) && !empty($decoded)) {
                                $imageUrl = $decoded[0];
                            } else {
                                $imageUrl = $product['image_url'];
                            }
                        }
                        ?>
                        <?php if ($imageUrl): ?>
                            <div class="w-16 h-16 rounded-xl overflow-hidden mr-4 flex-shrink-0 ring-2 ring-orange-300">
                                <img src="<?= base_url($imageUrl) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-full object-cover">
                            </div>
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl mr-4 flex items-center justify-center flex-shrink-0 ring-2 ring-orange-300">
                                <i data-lucide="package" class="w-7 h-7 text-gray-400"></i>
                            </div>
                        <?php endif; ?>

                        <div class="min-w-0 flex-1">
                            <p class="text-gray-900 mb-1 truncate"><?= esc($product['name']) ?></p>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-gray-600 mb-2">
                                <span class="flex items-center">
                                    <i data-lucide="tag" class="w-3 h-3 mr-1"></i>
                                    ₱<?= number_format($product['price'], 2) ?>
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="package" class="w-3 h-3 mr-1"></i>
                                    <?= $product['stock_quantity'] ?> in stock
                                </span>
                                <span class="flex items-center text-orange-700">
                                    <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                    <?= date('M d, Y', strtotime($product['spoilage_date'])) ?> (<?= $product['days_until_spoilage'] ?> days)
                                </span>
                            </div>
                            <?php if (isset($product['price_suggestion'])): ?>
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-2 mt-2">
                                    <p class="text-orange-700">
                                        <i data-lucide="trending-down" class="w-3 h-3 inline mr-1"></i>
                                        Suggested Price: ₱<?= number_format($product['price_suggestion']['suggested_price'], 2) ?>
                                        (<?= $product['price_suggestion']['reduction_percent'] ?>% discount)
                                    </p>
                                    <p class="text-orange-600 italic mt-1">
                                        <?= $product['price_suggestion']['reason'] ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center text-green-600 mt-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                                    <span>Price appropriately adjusted</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 ml-4 flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                            <i data-lucide="alert-triangle" class="w-3 h-3 mr-1.5"></i>
                            Expires Soon
                        </span>
                        <a href="/buyer/products/edit/<?= $product['id'] ?>" class="bg-orange-600 text-white px-5 py-2.5 rounded-xl hover:bg-orange-700 transition-colors duration-200 shadow-sm hover:shadow">
                            Update Price
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Spoiled Products Alerts -->
    <?php if (!empty($spoilage_alerts['spoiled'])): ?>
    <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl shadow-sm border-2 border-red-300 p-6 mb-8 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="p-3 bg-red-500 rounded-xl shadow-lg mr-4">
                <i data-lucide="x-circle" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h3 class="text-gray-900 mb-1">Spoiled Products</h3>
                <p class="text-red-700">These products have expired - update pricing or remove from sale</p>
            </div>
        </div>

        <div class="space-y-3">
            <?php foreach ($spoilage_alerts['spoiled'] as $product): ?>
                <div class="flex items-center justify-between p-5 bg-white rounded-xl border-2 border-red-200 hover:border-red-300 transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center flex-1 min-w-0">
                        <?php
                        $imageUrl = '';
                        if (!empty($product['image_url'])) {
                            $decoded = json_decode($product['image_url'], true);
                            if (is_array($decoded) && !empty($decoded)) {
                                $imageUrl = $decoded[0];
                            } else {
                                $imageUrl = $product['image_url'];
                            }
                        }
                        ?>
                        <?php if ($imageUrl): ?>
                            <div class="w-16 h-16 rounded-xl overflow-hidden mr-4 flex-shrink-0 ring-2 ring-red-300 opacity-75">
                                <img src="<?= base_url($imageUrl) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-full object-cover grayscale">
                            </div>
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl mr-4 flex items-center justify-center flex-shrink-0 ring-2 ring-red-300 opacity-75">
                                <i data-lucide="package" class="w-7 h-7 text-gray-400"></i>
                            </div>
                        <?php endif; ?>

                        <div class="min-w-0 flex-1">
                            <p class="text-gray-900 mb-1 truncate"><?= esc($product['name']) ?></p>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-gray-600 mb-2">
                                <span class="flex items-center">
                                    <i data-lucide="tag" class="w-3 h-3 mr-1"></i>
                                    ₱<?= number_format($product['price'], 2) ?>
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="package" class="w-3 h-3 mr-1"></i>
                                    <?= $product['stock_quantity'] ?> in stock
                                </span>
                                <span class="flex items-center text-red-700">
                                    <i data-lucide="calendar-x" class="w-3 h-3 mr-1"></i>
                                    Expired: <?= date('M d, Y', strtotime($product['spoilage_date'])) ?> (<?= abs($product['days_until_spoilage']) ?> days ago)
                                </span>
                            </div>
                            <?php if (isset($product['price_suggestion'])): ?>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-2 mt-2">
                                    <p class="text-red-700">
                                        <i data-lucide="trending-down" class="w-3 h-3 inline mr-1"></i>
                                        Suggested Price: ₱<?= number_format($product['price_suggestion']['suggested_price'], 2) ?>
                                        (<?= $product['price_suggestion']['reduction_percent'] ?>% discount)
                                    </p>
                                    <p class="text-red-600 italic mt-1">
                                        <?= $product['price_suggestion']['reason'] ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center text-green-600 mt-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                                    <span>Price appropriately adjusted</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 ml-4 flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-red-100 text-red-800 border border-red-200">
                            <i data-lucide="x-circle" class="w-3 h-3 mr-1.5"></i>
                            Spoiled
                        </span>
                        <a href="/buyer/products/edit/<?= $product['id'] ?>?spoiled=1" class="bg-red-600 text-white px-5 py-2.5 rounded-xl hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                            Update Price
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sales Over Time Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                </div>
                <h3 class="text-gray-900">Sales Revenue</h3>
            </div>
            <p class="text-gray-500 mb-4">Last 12 Months</p>
            <div class="relative h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <i data-lucide="pie-chart" class="w-5 h-5 text-blue-600"></i>
                </div>
                <h3 class="text-gray-900">Order Status</h3>
            </div>
            <p class="text-gray-500 mb-4">Distribution</p>
            <div class="relative h-64 flex items-center justify-center">
                <canvas id="statusChart" class="max-w-full max-h-full"></canvas>
            </div>
        </div>

        <!-- Top Products Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-purple-600"></i>
                </div>
                <h3 class="text-gray-900">Top Products</h3>
            </div>
            <p class="text-gray-500 mb-4">Best Performers</p>
            <div class="relative h-64">
                <canvas id="productsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center mb-6">
            <div class="p-2 bg-primary/10 rounded-lg mr-3">
                <i data-lucide="zap" class="w-5 h-5 text-primary"></i>
            </div>
            <h3 class="text-gray-900">Quick Actions</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/buyer/products/add" class="group flex items-start p-5 bg-white rounded-xl border-2 border-primary/20 hover:border-primary hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="p-3 bg-primary/10 rounded-xl group-hover:bg-primary group-hover:scale-110 transition-all duration-300 mr-4">
                    <i data-lucide="plus-circle" class="w-6 h-6 text-primary group-hover:text-white transition-colors duration-300"></i>
                </div>
                <div>
                    <p class="text-gray-900 mb-1">Add Product</p>
                    <p class="text-gray-600">List a new product for sale</p>
                </div>
            </a>

            <a href="/buyer/inventory" class="group flex items-start p-5 bg-white rounded-xl border-2 border-green-100 hover:border-green-400 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="p-3 bg-green-100 rounded-xl group-hover:bg-green-500 group-hover:scale-110 transition-all duration-300 mr-4">
                    <i data-lucide="package" class="w-6 h-6 text-green-600 group-hover:text-white transition-colors duration-300"></i>
                </div>
                <div>
                    <p class="text-gray-900 mb-1">Manage Listings</p>
                    <p class="text-gray-600">Update stock and product details</p>
                </div>
            </a>

            <a href="/buyer/sales/orders" class="group flex items-start p-5 bg-white rounded-xl border-2 border-blue-100 hover:border-blue-400 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="p-3 bg-blue-100 rounded-xl group-hover:bg-blue-500 group-hover:scale-110 transition-all duration-300 mr-4">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-300"></i>
                </div>
                <div>
                    <p class="text-gray-900 mb-1">View Orders</p>
                    <p class="text-gray-600">Check order status</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <h3 class="text-gray-900">Recent Orders</h3>
            </div>
            <a href="/buyer/sales/orders" class="flex items-center text-primary hover:text-primary-hover transition-colors duration-200">
                <span class="mr-1">View All</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <?php if (empty($recent_orders)): ?>
            <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300">
                <div class="p-4 bg-white rounded-full inline-flex mb-4 shadow-sm">
                    <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-400"></i>
                </div>
                <p class="text-gray-700 mb-2">No orders yet</p>
                <p class="text-gray-500">Orders placed on your products will appear here</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($recent_orders as $order): ?>
                    <div class="flex items-center justify-between p-5 border-2 border-gray-100 rounded-xl hover:border-gray-300 hover:shadow-md transition-all duration-300 bg-gradient-to-r from-white to-gray-50">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="p-3 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl mr-4 flex-shrink-0">
                                <i data-lucide="shopping-bag" class="w-5 h-5 text-indigo-700"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-gray-900 mb-1">Order #<?= $order['id'] ?></p>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-gray-600">
                                    <span class="flex items-center">
                                        <i data-lucide="user" class="w-3 h-3 mr-1"></i>
                                        <?= esc($order['buyer_name']) ?>
                                    </span>
                                    <span class="flex items-center">
                                        <i data-lucide="dollar-sign" class="w-3 h-3 mr-1"></i>
                                        ₱<?= number_format($order['total_price'], 2) ?>
                                    </span>
                                    <span class="flex items-center">
                                        <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                        <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 ml-4 flex-shrink-0">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full border-2
                                <?php
                                switch($order['status']) {
                                    case 'pending': echo 'bg-yellow-50 text-yellow-800 border-yellow-200'; break;
                                    case 'confirmed': echo 'bg-blue-50 text-blue-800 border-blue-200'; break;
                                    case 'processing': echo 'bg-purple-50 text-purple-800 border-purple-200'; break;
                                    case 'completed': echo 'bg-green-50 text-green-800 border-green-200'; break;
                                    case 'cancelled': echo 'bg-red-50 text-red-800 border-red-200'; break;
                                    default: echo 'bg-gray-50 text-gray-800 border-gray-200';
                                }
                                ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <a href="/buyer/sales/orders/<?= $order['id'] ?>" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors duration-200">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Over Time Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        const salesData = <?= json_encode($chart_data['sales_over_time'] ?? []) ?>;

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.map(item => {
                    const date = new Date(item.month + '-01');
                    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short' });
                }),
                datasets: [{
                    label: 'Revenue (₱)',
                    data: salesData.map(item => parseFloat(item.revenue)),
                    borderColor: '#2d7a3e',
                    backgroundColor: 'rgba(45, 122, 62, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2d7a3e',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Order Status Distribution Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const statusData = <?= json_encode($chart_data['order_status'] ?? []) ?>;

        const statusColors = {
            'pending': '#f59e0b',
            'confirmed': '#3b82f6',
            'processing': '#8b5cf6',
            'completed': '#10b981',
            'cancelled': '#ef4444'
        };

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
                datasets: [{
                    data: statusData.map(item => parseInt(item.count)),
                    backgroundColor: statusData.map(item => statusColors[item.status] || '#6b7280'),
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8
                    }
                }
            }
        });
    }

    // Top Products Chart
    const productsCtx = document.getElementById('productsChart');
    if (productsCtx) {
        const productsData = <?= json_encode($chart_data['top_products'] ?? []) ?>;

        new Chart(productsCtx, {
            type: 'bar',
            data: {
                labels: productsData.map(item => item.product_name.length > 20 ? item.product_name.substring(0, 20) + '...' : item.product_name),
                datasets: [{
                    label: 'Revenue (₱)',
                    data: productsData.map(item => parseFloat(item.total_revenue)),
                    backgroundColor: '#2d7a3e',
                    borderColor: '#236330',
                    borderWidth: 0,
                    borderRadius: 8,
                    hoverBackgroundColor: '#236330'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
