<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Orders</h1>
        <p class="text-gray-600">Track your purchases and order history</p>
    </div>

    <!-- Order Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['total'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['pending'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i data-lucide="truck" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">In Transit</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['shipped'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['delivered'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap gap-2">
            <a href="/buyer/orders" class="px-4 py-2 rounded-lg font-semibold <?= empty($current_status) ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                All Orders
            </a>
            <a href="/buyer/orders?status=pending" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Pending
            </a>
            <a href="/buyer/orders?status=confirmed" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'confirmed' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Confirmed
            </a>
            <a href="/buyer/orders?status=shipped" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'shipped' ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Shipped
            </a>
            <a href="/buyer/orders?status=delivered" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'delivered' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Delivered
            </a>
            <a href="/buyer/orders?status=cancelled" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Cancelled
            </a>
        </div>
    </div>

    <!-- Orders List -->
    <?php if (empty($orders)): ?>
        <div class="text-center py-12">
            <i data-lucide="shopping-bag" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No orders found</p>
            <p class="text-gray-500 mt-2">
                <?= empty($current_status) ? 'You haven\'t placed any orders yet' : 'No orders with this status' ?>
            </p>
            <a href="/marketplace" class="inline-block mt-4 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                Browse Products
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Order #<?= $order['id'] ?></h3>
                                <p class="text-sm text-gray-600">
                                    <?= date('M d, Y H:i', strtotime($order['created_at'])) ?> •
                                    Farmer: <?= esc($order['farmer_name']) ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-primary mb-1">
                                    ₱<?= number_format($order['total_amount'], 2) ?>
                                </div>
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
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Order Items</h4>
                            <div class="space-y-2">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center">
                                            <?php if ($item['image_url']): ?>
                                                <img src="<?= esc($item['image_url']) ?>" alt="<?= esc($item['name']) ?>" class="w-10 h-10 object-cover rounded mr-3">
                                            <?php else: ?>
                                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                                    <i data-lucide="package" class="w-5 h-5 text-gray-600"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <p class="font-medium text-gray-900"><?= esc($item['name']) ?></p>
                                                <p class="text-sm text-gray-600">
                                                    <?= esc($item['quantity']) ?> × ₱<?= number_format($item['price'], 2) ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">
                                                ₱<?= number_format($item['quantity'] * $item['price'], 2) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    Delivery: <?= esc($order['delivery_address']) ?>
                                </div>
                                <div class="flex gap-2">
                                    <a href="/buyer/orders/<?= $order['id'] ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                        View Details
                                    </a>

                                    <?php if ($order['status'] === 'pending'): ?>
                                        <form action="/buyer/orders/<?= $order['id'] ?>/cancel" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                                                Cancel Order
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>