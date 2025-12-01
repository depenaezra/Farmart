<?= $this->extend(session()->get('user_role') === 'admin' ? 'layouts/admin' : 'layouts/main') ?>

<?= $this->section('content') ?>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #2563eb, #1e40af);
}
.hover\:scale-102:hover {
    transform: scale(1.02);
}
</style>

<div class="container mx-auto px-4 py-2 max-w-6xl">
    <div class="w-full">


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

        <div class="flex flex-col lg:flex-row gap-6 max-w-4xl mx-auto">
            <!-- Profile Card -->
            <div class="flex-1 flex flex-col space-y-4">
                <div class="bg-gradient-to-br from-primary to-primary-hover rounded-xl shadow-lg border border-gray-100 p-4 transform hover:scale-105 transition-all duration-300">
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30 flex items-center justify-center mx-auto mb-3 shadow-md">
                            <span class="text-2xl font-bold text-white">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1"><?= esc($user['name']) ?></h3>
                        <p class="text-white/90 text-sm mb-3"><?= esc($user['email']) ?></p>
                        <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-full border border-white/30">
                            <i data-lucide="user" class="w-3 h-3 inline mr-1"></i>
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-white/20">
                        <div class="flex items-center text-white/90">
                            <i data-lucide="calendar" class="w-5 h-5 mr-3"></i>
                            <span class="text-sm">Member since</span>
                            <span class="ml-auto text-white font-semibold">
                                <?= date('M Y', strtotime($user['created_at'])) ?>
                            </span>
                        </div>
                        <div class="flex items-center text-white/90">
                            <i data-lucide="shield-check" class="w-5 h-5 mr-3"></i>
                            <span class="text-sm">Status</span>
                            <span class="ml-auto">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white/20 text-white border border-white/30">
                                    <i data-lucide="check-circle" class="w-3 h-3 inline mr-1"></i>
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-3">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-primary mr-1"></i>
                        Quick Stats
                    </h3>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="text-center p-2 bg-gradient-to-br from-blue-50 to-blue-100 rounded">
                            <div class="text-lg font-bold text-blue-600"><?= count($recent_likes) ?></div>
                            <div class="text-xs text-blue-800 font-medium">Likes</div>
                        </div>
                        <div class="text-center p-2 bg-gradient-to-br from-green-50 to-green-100 rounded">
                            <div class="text-lg font-bold text-green-600"><?= count($recent_comments) ?></div>
                            <div class="text-xs text-green-800 font-medium">Comments</div>
                        </div>
                        <div class="text-center p-2 bg-gradient-to-br from-purple-50 to-purple-100 rounded">
                            <div class="text-lg font-bold text-purple-600"><?= count($recent_cart_items) ?></div>
                            <div class="text-xs text-purple-800 font-medium">Cart</div>
                        </div>
                        <div class="text-center p-2 bg-gradient-to-br from-orange-50 to-orange-100 rounded">
                            <div class="text-lg font-bold text-orange-600">0</div>
                            <div class="text-xs text-orange-800 font-medium">Orders</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="flex-1 flex flex-col">
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 hover:shadow-lg transition-shadow duration-300 flex-1 flex flex-col min-h-[400px]">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center justify-center">
                        <i data-lucide="activity" class="w-5 h-5 text-primary mr-2"></i>
                        Recent Activities
                    </h2>

                    <!-- Activity Type Selector -->
                    <div class="mb-4">
                        <select id="activityType" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="likes">Recent Likes (<?= count($recent_likes) ?>)</option>
                            <option value="comments">Recent Comments (<?= count($recent_comments) ?>)</option>
                            <option value="cart">Cart Additions (<?= count($recent_cart_items) ?>)</option>
                        </select>
                    </div>

                    <!-- Activity Content -->
                    <div class="space-y-6 flex-1 overflow-y-auto custom-scrollbar">
                        <!-- Likes Content -->
                        <div id="likes-content" class="activity-content">
                            <?php if (!empty($recent_likes)): ?>
                                <?php foreach ($recent_likes as $like): ?>
                                    <a href="/forum/post/<?= $like['post_id'] ?>" class="block">
                                        <div class="flex items-start space-x-2 p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-100 hover:shadow-sm transition-all duration-200 cursor-pointer">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                    <i data-lucide="heart" class="w-4 h-4 text-red-500"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-gray-900 truncate">
                                                    <?= esc($like['post_title']) ?>
                                                </p>
                                                <p class="text-xs text-gray-500 flex items-center mt-0.5">
                                                    <i data-lucide="clock" class="w-2.5 h-2.5 mr-1"></i>
                                                    <?= date('M j, Y', strtotime($like['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-6">
                                    <i data-lucide="heart" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                    <p class="text-xs text-gray-500">No recent likes</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Comments Content -->
                        <div id="comments-content" class="activity-content hidden">
                            <?php if (!empty($recent_comments)): ?>
                                <?php foreach ($recent_comments as $comment): ?>
                                    <a href="/forum/post/<?= $comment['post_id'] ?>" class="block">
                                        <div class="flex items-start space-x-2 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100 hover:shadow-sm transition-all duration-200 cursor-pointer">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i data-lucide="message-circle" class="w-4 h-4 text-blue-500"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-gray-900 truncate">
                                                    On: <?= esc($comment['post_title']) ?>
                                                </p>
                                                <p class="text-xs text-gray-600 line-clamp-2 italic">
                                                    "<?= esc(substr($comment['comment'], 0, 30)) ?><?= strlen($comment['comment']) > 30 ? '...' : '' ?>"
                                                </p>
                                                <p class="text-xs text-gray-500 flex items-center mt-0.5">
                                                    <i data-lucide="clock" class="w-2.5 h-2.5 mr-1"></i>
                                                    <?= date('M j, Y', strtotime($comment['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-6">
                                    <i data-lucide="message-circle" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                    <p class="text-xs text-gray-500">No recent comments</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Cart Content -->
                        <div id="cart-content" class="activity-content hidden">
                            <?php if (!empty($recent_cart_items)): ?>
                                <?php foreach ($recent_cart_items as $item): ?>
                                    <a href="/marketplace/product/<?= $item['product_id'] ?>" class="block">
                                        <div class="flex items-start space-x-2 p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100 hover:shadow-sm transition-all duration-200 cursor-pointer">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i data-lucide="shopping-cart" class="w-4 h-4 text-green-500"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-gray-900 truncate">
                                                    <?= esc($item['product_name']) ?>
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    <span class="font-medium">Qty: <?= $item['quantity'] ?></span> •
                                                    <span class="text-green-600 font-semibold">₱<?= number_format($item['price'], 2) ?></span>
                                                </p>
                                                <p class="text-xs text-gray-500 flex items-center mt-0.5">
                                                    <i data-lucide="clock" class="w-2.5 h-2.5 mr-1"></i>
                                                    <?= date('M j, Y', strtotime($item['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-6">
                                    <i data-lucide="shopping-cart" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                    <p class="text-xs text-gray-500">No recent cart additions</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Activity dropdown functionality
function showActivityContent(type) {
    // Hide all activity contents
    document.querySelectorAll('.activity-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Show selected activity content
    const selectedContent = document.getElementById(type + '-content');
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const activityTypeSelect = document.getElementById('activityType');
    if (activityTypeSelect) {
        // Show default content (likes) on page load
        showActivityContent('likes');

        activityTypeSelect.addEventListener('change', function() {
            showActivityContent(this.value);
        });
    }
});
</script>

<?= $this->endSection() ?>

