<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
        <p class="text-gray-600">System overview and management</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Users -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['users']['total'] ?? 0 ?></p>
                    <p class="text-xs text-gray-500">Registered users</p>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="package" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['products']['total'] ?? 0 ?></p>
                    <p class="text-xs text-gray-500">
                        <?= $statistics['products']['available'] ?? 0 ?> available,
                        <?= $statistics['products']['pending'] ?? 0 ?> pending
                    </p>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['orders']['total'] ?? 0 ?></p>
                    <p class="text-xs text-gray-500">
                        <?= $statistics['orders']['pending'] ?? 0 ?> pending,
                        <?= $statistics['orders']['completed'] ?? 0 ?> completed
                    </p>
                </div>
            </div>
        </div>

        <!-- Violations -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i data-lucide="flag" class="w-6 h-6 text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Reports</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $statistics['violations']['pending'] ?? 0 ?></p>
                    <p class="text-xs text-gray-500">
                        <?= $statistics['violations']['total'] ?? 0 ?> total reports
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <a href="/admin/orders" class="text-primary hover:text-primary-hover font-medium">View All</a>
            </div>

            <?php if (empty($recent_orders)): ?>
                <div class="text-center py-8">
                    <i data-lucide="shopping-bag" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-600">No orders yet</p>
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
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                <a href="/admin/users" class="text-primary hover:text-primary-hover font-medium">View All</a>
            </div>

            <?php if (empty($recent_users)): ?>
                <div class="text-center py-8">
                    <i data-lucide="user-plus" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-600">No users yet</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recent_users as $user): ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-gray-100 rounded-lg mr-4">
                                    <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900"><?= esc($user['name']) ?></p>
                                    <p class="text-sm text-gray-600">
                                        <?= ucfirst($user['role']) ?> •
                                        <?= date('M d, Y', strtotime($user['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Violations -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Reports</h3>
                <a href="/admin/violations" class="text-primary hover:text-primary-hover font-medium">View All</a>
            </div>

            <?php if (empty($recent_violations)): ?>
                <div class="text-center py-8">
                    <i data-lucide="flag" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-600">No reports yet</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recent_violations as $violation): ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-lg mr-4">
                                    <i data-lucide="flag" class="w-5 h-5 text-red-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900"><?= esc($violation['reason']) ?></p>
                                    <p class="text-sm text-gray-600">
                                        <?= esc($violation['reporter_name']) ?> •
                                        <?= date('M d, Y', strtotime($violation['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                <?php
                                switch($violation['status']) {
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'reviewed': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'resolved': echo 'bg-green-100 text-green-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst($violation['status']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>