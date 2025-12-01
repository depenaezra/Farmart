<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= esc($product['name']) ?></h1>
                <p class="text-gray-600">Product Details & Management</p>
            </div>
            <a href="/admin/products" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                Back to Products
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Product Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Image -->
                    <div>
                        <?php if ($product['image_url']): ?>
                            <img src="/uploads/<?= $product['image_url'] ?>" alt="<?= esc($product['name']) ?>"
                                 class="w-full h-64 object-cover rounded-lg">
                        <?php else: ?>
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i data-lucide="package" class="w-16 h-16 text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product Name</label>
                            <p class="text-gray-900 mt-1 font-semibold"><?= esc($product['name']) ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="text-gray-900 mt-1"><?= esc($product['description'] ?: 'No description provided') ?></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price</label>
                                <p class="text-gray-900 mt-1 font-semibold">₱<?= number_format($product['price'], 2) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit</label>
                                <p class="text-gray-900 mt-1"><?= esc($product['unit']) ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                <p class="text-gray-900 mt-1"><?= $product['stock_quantity'] ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="text-gray-900 mt-1"><?= ucfirst($product['category']) ?></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <p class="text-gray-900 mt-1"><?= esc($product['location'] ?: 'Not specified') ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full mt-1
                                <?php
                                switch($product['status']) {
                                    case 'available': echo 'bg-green-100 text-green-800'; break;
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'out-of-stock': echo 'bg-red-100 text-red-800'; break;
                                    case 'rejected': echo 'bg-gray-100 text-gray-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst(str_replace('-', ' ', $product['status'])) ?>
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created</label>
                            <p class="text-gray-900 mt-1"><?= date('F d, Y H:i', strtotime($product['created_at'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seller Information & Actions -->
        <div class="lg:col-span-1">
            <div class="space-y-6">
                <!-- Seller Info -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Seller Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="text-gray-900 mt-1">
                                <a href="/admin/users/<?= $product['farmer_id'] ?>" class="text-primary hover:text-primary-hover">
                                    <?= esc($product['farmer_name']) ?>
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="text-gray-900 mt-1"><?= esc($product['farmer_email']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="text-gray-900 mt-1"><?= esc($product['farmer_phone'] ?: 'Not provided') ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <p class="text-gray-900 mt-1"><?= esc($product['farmer_location'] ?: 'Not specified') ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cooperative</label>
                            <p class="text-gray-900 mt-1"><?= esc($product['cooperative'] ?: 'Not specified') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <?php if ($product['status'] === 'pending'): ?>
                            <form method="post" action="/admin/products/<?= $product['id'] ?>/approve" class="swal-confirm-form" data-confirm="Are you sure you want to approve this product?">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i data-lucide="check" class="w-4 h-4 inline mr-2"></i>
                                    Approve Product
                                </button>
                            </form>

                            <form method="post" action="/admin/products/<?= $product['id'] ?>/reject" class="swal-confirm-form" data-confirm="Are you sure you want to reject this product?">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                                    Reject Product
                                </button>
                            </form>
                        <?php endif; ?>

                        <form method="post" action="/admin/products/<?= $product['id'] ?>/delete" class="swal-confirm-form" data-confirm="Are you sure you want to delete this product? This action cannot be undone.">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4 inline mr-2"></i>
                                Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>