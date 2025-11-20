
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= esc($user['name']) ?></h1>
                <p class="text-gray-600">User Details & Activity</p>
            </div>
            <a href="/admin/users" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                Back to Users
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-medium text-gray-700">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900"><?= esc($user['name']) ?></h3>
                    <p class="text-gray-600"><?= esc($user['email']) ?></p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                            <?php
                            switch($user['role']) {
                                case 'admin': echo 'bg-purple-100 text-purple-800'; break;
                                case 'farmer': echo 'bg-green-100 text-green-800'; break;
                                case 'buyer': echo 'bg-blue-100 text-blue-800'; break;
                                default: echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                            <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= ucfirst($user['status']) ?>
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="text-gray-900 mt-1"><?= esc($user['phone'] ?: 'Not provided') ?></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <p class="text-gray-900 mt-1"><?= esc($user['location'] ?: 'Not provided') ?></p>
                    </div>

                    <?php if ($user['role'] === 'farmer'): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cooperative</label>
                            <p class="text-gray-900 mt-1"><?= esc($user['cooperative'] ?: 'Not specified') ?></p>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Joined</label>
                        <p class="text-gray-900 mt-1"><?= date('F d, Y', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex space-x-2">
                        <form method="post" action="/admin/users/toggle-status/<?= $user['id'] ?>" class="flex-1">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full px-4 py-2 text-sm font-medium rounded-lg
                                <?= $user['status'] === 'active' ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?>
                                transition-colors"
                                onclick="return confirm('Are you sure you want to <?= $user['status'] === 'active' ? 'deactivate' : 'activate' ?> this user?')">
                                <i data-lucide="<?= $user['status'] === 'active' ? 'user-x' : 'user-check' ?>" class="w-4 h-4 inline mr-2"></i>
                                <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                            </button>
                        </form>

                        <?php if ($user['id'] != session()->get('user_id')): ?>
                            <form method="post" action="/admin/users/delete/<?= $user['id'] ?>" class="flex-1">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors"
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <i data-lucide="trash-2" class="w-4 h-4 inline mr-2"></i>
                                    Delete
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Tabs -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md border border-gray-200">
                <div class="border-b border-gray-200">
                    <nav class="flex">
                        <button class="tab-button active px-6 py-4 text-sm font-medium text-gray-900 border-b-2 border-primary"
                                data-tab="products">
                            Products
                        </button>
                        <button class="tab-button px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent"
                                data-tab="orders">
                            Orders
                        </button>
                    </nav>
                </div>

                <!-- Products Tab -->
                <div id="products-tab" class="tab-content p-6">
                    <?php if ($user['role'] === 'farmer'): ?>
                        <?php if (empty($products)): ?>
                            <div class="text-center py-12">
                                <i data-lucide="package" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No products</h3>
                                <p class="text-gray-600">This farmer hasn't added any products yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($products as $product): ?>
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            <?php if ($product['image_url']): ?>
                                                <img src="/uploads/<?= $product['image_url'] ?>" alt="<?= esc($product['name']) ?>"
                                                     class="w-12 h-12 rounded-lg object-cover mr-4">
                                            <?php else: ?>
                                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                                    <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">
                                                    <a href="/admin/products/<?= $product['id'] ?>" class="text-primary hover:text-primary-hover">
                                                        <?= esc($product['name']) ?>
                                                    </a>
                                                </h4>
                                                <p class="text-sm text-gray-600">₱<?= number_format($product['price'], 2) ?> per <?= esc($product['unit']) ?></p>
                                                <p class="text-xs text-gray-500">Stock: <?= $product['stock_quantity'] ?> | Status: <?= ucfirst($product['status']) ?></p>
                                            </div>
                                        </div>
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                            <?php
                                            switch($product['status']) {
                                                case 'available': echo 'bg-green-100 text-green-800'; break;
                                                case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'out-of-stock': echo 'bg-red-100 text-red-800'; break;
                                                default: echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst($product['status']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i data-lucide="user" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Not applicable</h3>
                            <p class="text-gray-600">Only farmers can have products.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Orders Tab -->
                <div id="orders-tab" class="tab-content p-6 hidden">
                    <?php if (empty($orders)): ?>
                        <div class="text-center py-12">
                            <i data-lucide="shopping-bag" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders</h3>
                            <p class="text-gray-600">This user hasn't placed or received any orders yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($orders as $order): ?>
                                <div class="p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900">Order #<?= $order['id'] ?></h4>
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
                                    <div class="text-sm text-gray-600 mb-2">
                                        <?php if ($user['role'] === 'farmer'): ?>
                                            Buyer: <?= esc($order['buyer_name']) ?> •
                                        <?php else: ?>
                                            Farmer: <?= esc($order['farmer_name']) ?> •
                                        <?php endif; ?>
                                        Product: <?= esc($order['product_name']) ?> •
                                        Quantity: <?= $order['quantity'] ?> <?= esc($order['unit']) ?>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">
                                            <?= date('M d, Y H:i', strtotime($order['created_at'])) ?>
                                        </span>
                                        <span class="font-semibold text-gray-900">
                                            ₱<?= number_format($order['total_price'], 2) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-gray-900', 'border-primary');
                btn.classList.add('text-gray-500');
            });

            // Hide all tab contents
