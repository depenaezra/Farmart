<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, <?= esc($userName) ?>!</h1>
        <p class="text-gray-600">Here's an overview of your farming business</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="package" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
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
                    <p class="text-sm font-medium text-gray-600">Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₱<?= number_format($statistics['orders']['revenue'] ?? 0, 2) ?></p>
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

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/farmer/products/add" class="flex items-center p-4 bg-primary/10 rounded-lg hover:bg-primary/20 transition-colors">
                <i data-lucide="plus-circle" class="w-8 h-8 text-primary mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-900">Add Product</p>
                    <p class="text-sm text-gray-600">List a new product for sale</p>
                </div>
            </a>

            <a href="/farmer/inventory" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i data-lucide="package" class="w-8 h-8 text-green-600 mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-900">Manage Inventory</p>
                    <p class="text-sm text-gray-600">Update stock and products</p>
                </div>
            </a>

            <a href="/farmer/orders" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
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
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            <a href="/farmer/orders" class="text-primary hover:text-primary-hover font-medium">View All</a>
        </div>

        <?php if (empty($recent_orders)): ?>
            <div class="text-center py-8">
                <i data-lucide="shopping-bag" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-600">No orders yet</p>
                <p class="text-sm text-gray-500 mt-1">Your orders will appear here</p>
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
                                    ₱<?= number_format($order['total_amount'], 2) ?> •
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
                                    case 'shipped': echo 'bg-purple-100 text-purple-800'; break;
                                    case 'delivered': echo 'bg-green-100 text-green-800'; break;
                                    case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <a href="/farmer/orders/<?= $order['id'] ?>" class="ml-4 text-primary hover:text-primary-hover">
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