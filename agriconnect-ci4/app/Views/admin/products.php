<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Product Management</h1>
        <p class="text-gray-600">Moderate and manage all products in the marketplace</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 mb-4">
        <form method="get" class="flex flex-col sm:flex-row gap-3">
            <div class="sm:w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                    <option value="">All Products</option>
                    <option value="available" <?= $current_status === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="pending" <?= $current_status === 'pending' ? 'selected' : '' ?>>Pending Approval</option>
                    <option value="out-of-stock" <?= $current_status === 'out-of-stock' ? 'selected' : '' ?>>Out of Stock</option>
                    <option value="rejected" <?= $current_status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors text-sm">
                    <i data-lucide="filter" class="w-4 h-4 inline mr-1"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Products (<?= count($products) ?>)</h3>
        </div>

        <?php if (empty($products)): ?>
            <div class="text-center py-8">
                <i data-lucide="package" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-600 text-sm">Try adjusting your filter criteria.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Farmer</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Stock</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Category</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($products as $product): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3">
                                    <div class="flex items-center">
                                        <?php if ($product['image_url']): ?>
                                            <img src="/uploads/<?= $product['image_url'] ?>" alt="<?= esc($product['name']) ?>"
                                                 class="w-10 h-10 rounded-lg object-cover mr-3 flex-shrink-0">
                                        <?php else: ?>
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                                <i data-lucide="package" class="w-4 h-4 text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                <a href="/admin/products/<?= $product['id'] ?>" class="text-primary hover:text-primary-hover">
                                                    <?= esc($product['name']) ?>
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">
                                                <?= esc(substr($product['description'], 0, 30)) ?><?= strlen($product['description']) > 30 ? '...' : '' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 hidden sm:table-cell">
                                    <div class="text-sm text-gray-900 truncate">
                                        <a href="/admin/users/<?= $product['farmer_id'] ?>" class="text-primary hover:text-primary-hover">
                                            <?= esc($product['farmer_name']) ?>
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500 truncate"><?= esc($product['farmer_email']) ?></div>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 whitespace-nowrap">
                                    ₱<?= number_format($product['price'], 2) ?><br><span class="text-xs text-gray-500">/ <?= esc($product['unit']) ?></span>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 hidden md:table-cell">
                                    <?= $product['stock_quantity'] ?>
                                </td>
                                <td class="px-3 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
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
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 hidden lg:table-cell">
                                    <?= ucfirst($product['category']) ?>
                                </td>
                                <td class="px-3 py-3">
                                    <a href="/admin/products/<?= $product['id'] ?>" class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-xs font-medium rounded hover:bg-primary-hover transition-colors">
                                        <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>