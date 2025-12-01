<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div role="alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i data-lucide="check-circle" class="w-5 h-5 inline mr-2"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div role="alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i data-lucide="x-circle" class="w-5 h-5 inline mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Sales Orders</h1>
        <p class="text-gray-600">Track and manage the orders placed on your listings</p>
    </div>

    <!-- Status Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap gap-2">
            <a href="/buyer/sales/orders" class="px-4 py-2 rounded-lg font-semibold <?= empty($current_status) ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                All Orders
            </a>
            <a href="/buyer/sales/orders?status=pending" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Pending
            </a>
            <a href="/buyer/sales/orders?status=confirmed" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'confirmed' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Confirmed
            </a>
            <a href="/buyer/sales/orders?status=processing" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'processing' ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Processing
            </a>
            <a href="/buyer/sales/orders?status=completed" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Completed
            </a>
            <a href="/buyer/sales/orders?status=cancelled" class="px-4 py-2 rounded-lg font-semibold <?= $current_status === 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
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
                <?= empty($current_status) ? 'You have not received any orders yet' : 'No orders with this status' ?>
            </p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Order #<?= $order['order_sequence'] ?></h3>
                                <p class="text-sm text-gray-600">
                                    <?= date('M d, Y H:i', strtotime($order['created_at'])) ?> •
                                    Buyer: <?= esc($order['buyer_name']) ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-primary mb-1">
                                    ₱<?= number_format($order['total_price'], 2) ?>
                                </div>
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
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Order Item</h4>
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center">
                                    <?php if (!empty($order['image_url'])): ?>
                                        <img src="<?= esc($order['image_url']) ?>" alt="<?= esc($order['product_name']) ?>" class="w-10 h-10 object-cover rounded mr-3">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                            <i data-lucide="package" class="w-5 h-5 text-gray-600"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-medium text-gray-900"><?= esc($order['product_name']) ?></p>
                                        <p class="text-sm text-gray-600">
                                            <?= esc($order['quantity']) ?> × <?= esc($order['unit']) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        ₱<?= number_format($order['total_price'], 2) ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    Delivery: <?= esc($order['delivery_address']) ?>
                                </div>
                                <div class="flex gap-2">
                                    <a href="/buyer/sales/orders/<?= $order['id'] ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                        View Details
                                    </a>

                                    <?php if (in_array($order['status'], ['pending', 'confirmed', 'processing'])): ?>
                                        <form action="/buyer/sales/orders/<?= $order['id'] ?>/update-status" method="POST" class="inline-flex items-center gap-2">
                                            <?= csrf_field() ?>
                                            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirm</option>
                                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Mark Completed</option>
                                            </select>
                                            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition-colors text-sm font-semibold">
                                                Update
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>

<?= $this->endSection() ?>


