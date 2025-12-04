<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Seller Dashboard</h1>
        <p class="text-gray-600">Welcome back, <?= esc($userName) ?>! Manage the products you sell and track your performance.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="package" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Listings</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['products']['total_products'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['orders']['total_orders'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sales Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₱<?= number_format($statistics['orders']['total_sales'] ?? 0, 2) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i data-lucide="trending-up" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['orders']['pending'] ?? 0 ?></p>
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
    <div class="bg-white rounded-xl shadow-md border border-yellow-200 p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Early Spoilage Warning</h3>
                <p class="text-sm text-gray-600">Products expiring in 5-7 days - plan ahead</p>
            </div>
        </div>

        <div class="space-y-4">
            <?php foreach ($spoilage_alerts['early_warning'] as $product): ?>
                <div class="flex items-center justify-between p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                    <div class="flex items-center">
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
                            <img src="<?= base_url($imageUrl) ?>" alt="<?= esc($product['name']) ?>" class="w-12 h-12 object-cover rounded-lg mr-4">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                            </div>
                        <?php endif; ?>

                        <div>
                            <p class="font-semibold text-gray-900"><?= esc($product['name']) ?></p>
                            <p class="text-sm text-gray-600">
                                Current Price: ₱<?= number_format($product['price'], 2) ?> |
                                Stock: <?= $product['stock_quantity'] ?> |
                                Expires: <?= date('M d, Y', strtotime($product['spoilage_date'])) ?> (<?= $product['days_until_spoilage'] ?> days)
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Early Warning
                        </span>
                        <a href="/buyer/products/edit/<?= $product['id'] ?>" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover text-sm font-medium">
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
    <div class="bg-white rounded-xl shadow-md border border-orange-200 p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="p-2 bg-orange-100 rounded-lg mr-3">
                <i data-lucide="alert-triangle" class="w-6 h-6 text-orange-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Urgent Spoilage Alert</h3>
                <p class="text-sm text-gray-600">Products expiring within 3 days - immediate action needed</p>
            </div>
        </div>

        <div class="space-y-4">
            <?php foreach ($spoilage_alerts['nearing_spoilage'] as $product): ?>
                <div class="flex items-center justify-between p-4 border border-orange-200 rounded-lg bg-orange-50">
                    <div class="flex items-center">
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
                            <img src="<?= base_url($imageUrl) ?>" alt="<?= esc($product['name']) ?>" class="w-12 h-12 object-cover rounded-lg mr-4">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                            </div>
                        <?php endif; ?>

                        <div>
                            <p class="font-semibold text-gray-900"><?= esc($product['name']) ?></p>
                            <p class="text-sm text-gray-600">
                                Current Price: ₱<?= number_format($product['price'], 2) ?> |
                                Stock: <?= $product['stock_quantity'] ?> |
                                Expires: <?= date('M d, Y', strtotime($product['spoilage_date'])) ?> (<?= $product['days_until_spoilage'] ?> days)
                            </p>
                            <?php if (isset($product['price_suggestion'])): ?>
                                <p class="text-sm text-orange-600 font-medium">
                                    Suggested Price: ₱<?= number_format($product['price_suggestion']['suggested_price'], 2) ?>
                                    (<?= $product['price_suggestion']['reduction_percent'] ?>% discount)
                                </p>
                                <p class="text-xs text-orange-500 italic">
                                    Reason: <?= $product['price_suggestion']['reason'] ?>
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-green-600 font-medium">
                                    ✓ Price appropriately adjusted
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                            Expires Soon
                        </span>
                        <a href="/buyer/products/edit/<?= $product['id'] ?>" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover text-sm font-medium">
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
    <div class="bg-white rounded-xl shadow-md border border-red-200 p-6 mb-8">
        <div class="flex items-center mb-4">
            <div class="p-2 bg-red-100 rounded-lg mr-3">
                <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Spoiled Products</h3>
                <p class="text-sm text-gray-600">These products have expired - update pricing or remove from sale</p>
            </div>
        </div>

        <div class="space-y-4">
            <?php foreach ($spoilage_alerts['spoiled'] as $product): ?>
                <div class="flex items-center justify-between p-4 border border-red-200 rounded-lg bg-red-50">
                    <div class="flex items-center">
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
                            <img src="<?= base_url($imageUrl) ?>" alt="<?= esc($product['name']) ?>" class="w-12 h-12 object-cover rounded-lg mr-4">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                            </div>
                        <?php endif; ?>

                        <div>
                            <p class="font-semibold text-gray-900"><?= esc($product['name']) ?></p>
                            <p class="text-sm text-gray-600">
                                Current Price: ₱<?= number_format($product['price'], 2) ?> |
                                Stock: <?= $product['stock_quantity'] ?> |
                                Expired: <?= date('M d, Y', strtotime($product['spoilage_date'])) ?> (<?= abs($product['days_until_spoilage']) ?> days ago)
                            </p>
                            <?php if (isset($product['price_suggestion'])): ?>
                                <p class="text-sm text-red-600 font-medium">
                                    Suggested Price: ₱<?= number_format($product['price_suggestion']['suggested_price'], 2) ?>
                                    (<?= $product['price_suggestion']['reduction_percent'] ?>% discount)
                                </p>
                                <p class="text-xs text-red-500 italic">
                                    Reason: <?= $product['price_suggestion']['reason'] ?>
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-green-600 font-medium">
                                    ✓ Price appropriately adjusted
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Spoiled
                        </span>
                        <a href="/buyer/products/edit/<?= $product['id'] ?>?spoiled=1" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover text-sm font-medium">
                            Update Price Only
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
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales Revenue (Last 12 Months)</h3>
            <div class="relative h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status Distribution</h3>
            <div class="relative h-64 flex items-center justify-center">
                <canvas id="statusChart" class="max-w-full max-h-full"></canvas>
            </div>
        </div>

        <!-- Top Products Chart -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Products</h3>
            <div class="relative h-64">
                <canvas id="productsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/buyer/products/add" class="flex items-center p-4 bg-primary/10 rounded-lg hover:bg-primary/20 transition-colors">
                <i data-lucide="plus-circle" class="w-8 h-8 text-primary mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-900">Add Product</p>
                    <p class="text-sm text-gray-600">List a new product for sale</p>
                </div>
            </a>

            <a href="/buyer/inventory" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i data-lucide="package" class="w-8 h-8 text-green-600 mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-900">Manage Listings</p>
                    <p class="text-sm text-gray-600">Update stock and product details</p>
                </div>
            </a>

            <a href="/buyer/sales/orders" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i data-lucide="clipboard-list" class="w-8 h-8 text-blue-600 mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-900">View Orders</p>
                    <p class="text-sm text-gray-600">Check order status</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders from Your Listings</h3>
            <a href="/buyer/sales/orders" class="text-primary hover:text-primary-hover font-medium">View All</a>
        </div>

        <?php if (empty($recent_orders)): ?>
            <div class="text-center py-8">
                <i data-lucide="shopping-bag" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-600">No orders yet</p>
                <p class="text-sm text-gray-500 mt-1">Orders placed on your products will appear here</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($recent_orders as $order): ?>
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-gray-100 rounded-lg mr-4">
                                <i data-lucide="shopping-bag" class="w-5 h-5 text-gray-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Order #<?= $order['id'] ?></p>
                                <p class="text-sm text-gray-600">
                                    <?= esc($order['buyer_name']) ?> •
                                    ₱<?= number_format($order['total_price'], 2) ?> •
                                    <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                <?php
                                switch($order['status']) {
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'confirmed': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'processing': echo 'bg-purple-100 text-purple-800'; break;
                                    case 'completed': echo 'bg-green-100 text-green-800'; break;
                                    case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <a href="/buyer/sales/orders/<?= $order['id'] ?>" class="ml-4 text-primary hover:text-primary-hover">
                                <i data-lucide="eye" class="w-4 h-4"></i>
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
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
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
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
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
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>



