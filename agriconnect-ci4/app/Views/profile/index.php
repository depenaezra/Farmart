<?= $this->extend(session()->get('user_role') === 'admin' ? 'layouts/admin' : 'layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="w-full">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
                    <p class="text-gray-600">View and manage your account information</p>
                </div>
                <a href="/profile/edit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                    <i data-lucide="edit" class="w-4 h-4 inline mr-2"></i>
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-primary flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl font-bold text-white">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900"><?= esc($user['name']) ?></h3>
                        <p class="text-gray-600"><?= esc($user['email']) ?></p>
                        <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>

                    <div class="space-y-3 pt-6 border-t border-gray-200">
                        <div class="flex items-center text-sm">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400 mr-3"></i>
                            <span class="text-gray-600">Member since</span>
                            <span class="ml-auto text-gray-900 font-medium">
                                <?= date('M Y', strtotime($user['created_at'])) ?>
                            </span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i data-lucide="shield-check" class="w-4 h-4 text-gray-400 mr-3"></i>
                            <span class="text-gray-600">Status</span>
                            <span class="ml-auto">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Profile Information</h2>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-gray-900"><?= esc($user['name']) ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <p class="text-gray-900"><?= esc($user['email']) ?></p>
                        </div>

                        <?php if (!empty($user['phone'])): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <p class="text-gray-900"><?= esc($user['phone']) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($user['location'])): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <p class="text-gray-900"><?= esc($user['location']) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($user['cooperative'])): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cooperative/Organization</label>
                                <p class="text-gray-900"><?= esc($user['cooperative']) ?></p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="lg:col-span-1 flex">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex-1 flex flex-col min-h-[600px]">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 text-center">Recent Activities</h2>

                    <div class="flex-1 space-y-6 overflow-y-auto">
                        <!-- Recent Likes -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center justify-center">
                                <i data-lucide="heart" class="w-5 h-5 text-red-500 mr-2"></i>
                                Recent Likes
                            </h3>
                            <?php if (!empty($recent_likes)): ?>
                                <div class="space-y-2 flex flex-col items-center">
                                    <?php foreach ($recent_likes as $like): ?>
                                        <div class="flex items-start space-x-2 p-3 bg-gray-50 rounded-lg w-full max-w-xs">
                                            <i data-lucide="heart" class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0"></i>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900 truncate">
                                                    <?= esc($like['post_title']) ?>
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    <?= date('M j, Y g:i A', strtotime($like['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 text-center">No recent likes</p>
                            <?php endif; ?>
                        </div>

                        <!-- Recent Comments -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center justify-center">
                                <i data-lucide="message-circle" class="w-5 h-5 text-blue-500 mr-2"></i>
                                Recent Comments
                            </h3>
                            <?php if (!empty($recent_comments)): ?>
                                <div class="space-y-2 flex flex-col items-center">
                                    <?php foreach ($recent_comments as $comment): ?>
                                        <div class="flex items-start space-x-2 p-3 bg-gray-50 rounded-lg w-full max-w-xs">
                                            <i data-lucide="message-circle" class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0"></i>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900 truncate">
                                                    On: <?= esc($comment['post_title']) ?>
                                                </p>
                                                <p class="text-sm text-gray-600 line-clamp-2">
                                                    "<?= esc(substr($comment['comment'], 0, 40)) ?><?= strlen($comment['comment']) > 40 ? '...' : '' ?>"
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    <?= date('M j, Y g:i A', strtotime($comment['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 text-center">No recent comments</p>
                            <?php endif; ?>
                        </div>

                        <!-- Recent Cart Additions -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center justify-center">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-green-500 mr-2"></i>
                                Cart Additions
                            </h3>
                            <?php if (!empty($recent_cart_items)): ?>
                                <div class="space-y-2 flex flex-col items-center">
                                    <?php foreach ($recent_cart_items as $item): ?>
                                        <div class="flex items-start space-x-2 p-3 bg-gray-50 rounded-lg w-full max-w-xs">
                                            <i data-lucide="shopping-cart" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900 truncate">
                                                    <?= esc($item['product_name']) ?>
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    Qty: <?= $item['quantity'] ?> • ₱<?= number_format($item['price'], 2) ?>
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    <?= date('M j, Y g:i A', strtotime($item['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 text-center">No recent cart additions</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

