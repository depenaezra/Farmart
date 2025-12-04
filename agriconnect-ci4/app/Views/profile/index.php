<?= $this->extend(session()->get('user_role') === 'admin' ? 'layouts/admin' : 'layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">


    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3 text-green-600"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-3 text-red-600"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->get('user_role') === 'admin'): ?>
        <!-- Admin Profile Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Admin Profile Card -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Admin Profile Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-6">
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i data-lucide="shield" class="w-10 h-10 text-white"></i>
                            </div>
                            <h2 class="text-white mb-1"><?= esc($user['name']) ?></h2>
                            <p class="text-white/90 text-sm mb-3"><?= esc($user['email']) ?></p>
                            <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs rounded-full border border-white/30">
                                <i data-lucide="crown" class="w-3 h-3 mr-1"></i>
                                Administrator
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-center text-gray-700">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                                <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Admin since</p>
                                <p class="font-semibold text-gray-900"><?= date('M Y', strtotime($user['created_at'])) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mr-3">
                                <i data-lucide="shield-check" class="w-5 h-5 text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Account Status</p>
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="zap" class="w-5 h-5 text-blue-600 mr-2"></i>
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <button onclick="toggleEditForm()" class="w-full flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 hover:shadow-md transition-all duration-200">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i data-lucide="edit" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-semibold text-blue-900 text-sm">Edit Profile</p>
                                <p class="text-xs text-blue-700">Update your information</p>
                            </div>
                        </button>

                        <button onclick="toggleAddAdminForm()" class="w-full flex items-center p-3 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 hover:shadow-md transition-all duration-200">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i data-lucide="user-plus" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-semibold text-green-900 text-sm">Add Admin</p>
                                <p class="text-xs text-green-700">Create new administrator</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Account Management -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <h2 class="font-semibold text-gray-900 flex items-center">
                            <i data-lucide="user-cog" class="w-5 h-5 text-blue-600 mr-2"></i>
                            Account Management
                        </h2>
                    </div>

                    <div class="p-6">
                        <!-- Edit Profile Form -->
                        <div id="editProfileForm" class="max-w-2xl mx-auto">
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-900 mb-2">Edit Profile Information</h3>
                                <p class="text-sm text-gray-600">Update your account details below</p>
                            </div>

                            <form action="/profile/update" method="POST" class="space-y-4">
                                <?= csrf_field() ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="user" class="w-4 h-4 inline mr-1"></i>
                                            Full Name
                                        </label>
                                        <input type="text" id="edit_name" name="name" value="<?= esc($user['name']) ?>" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i>
                                            Email Address
                                        </label>
                                        <input type="email" id="edit_email" name="email" value="<?= esc($user['email']) ?>" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                            Phone Number
                                        </label>
                                        <input type="tel" id="edit_phone" name="phone" value="<?= esc($user['phone'] ?? '') ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="edit_location" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i>
                                            Location
                                        </label>
                                        <input type="text" id="edit_location" name="location" value="<?= esc($user['location'] ?? '') ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                        Save Changes
                                    </button>
                                    <button type="button" onclick="toggleEditForm()" class="inline-flex items-center px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Add Admin Form -->
                        <div id="addAdminForm" class="hidden max-w-2xl mx-auto">
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-900 mb-2">Create New Admin Account</h3>
                                <p class="text-sm text-gray-600">Add a new administrator to the system</p>
                            </div>

                            <form action="/admin/users/create-admin" method="POST" class="space-y-4">
                                <?= csrf_field() ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="user" class="w-4 h-4 inline mr-1"></i>
                                            Full Name *
                                        </label>
                                        <input type="text" id="admin_name" name="name" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i>
                                            Email Address *
                                        </label>
                                        <input type="email" id="admin_email" name="email" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="admin_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                            Phone Number
                                        </label>
                                        <input type="tel" id="admin_phone" name="phone"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="admin_location" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i>
                                            Location
                                        </label>
                                        <input type="text" id="admin_location" name="location"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="lock" class="w-4 h-4 inline mr-1"></i>
                                            Password *
                                        </label>
                                        <input type="password" id="admin_password" name="password" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="admin_password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i data-lucide="lock" class="w-4 h-4 inline mr-1"></i>
                                            Confirm Password *
                                        </label>
                                        <input type="password" id="admin_password_confirm" name="password_confirm" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                                        Create Admin
                                    </button>
                                    <button type="button" onclick="toggleAddAdminForm()" class="inline-flex items-center px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Regular User Profile Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: User Profile Card -->
            <div class="lg:col-span-1 space-y-6">
                <!-- User Profile Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-primary to-primary-hover p-6">
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <span class="text-white text-2xl">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </span>
                            </div>
                            <h2 class="text-white mb-1"><?= esc($user['name']) ?></h2>
                            <p class="text-white/90 text-sm mb-3"><?= esc($user['email']) ?></p>
                            <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs rounded-full border border-white/30">
                                <i data-lucide="user" class="w-3 h-3 mr-1"></i>
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-center text-gray-700">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                                <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Member since</p>
                                <p class="font-semibold text-gray-900"><?= date('M Y', strtotime($user['created_at'])) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mr-3">
                                <i data-lucide="shield-check" class="w-5 h-5 text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-primary mr-2"></i>
                        Quick Stats
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                            <div class="text-2xl text-blue-600 mb-1"><?= count($recent_likes) ?></div>
                            <div class="text-xs text-blue-800">Likes</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                            <div class="text-2xl text-green-600 mb-1"><?= count($recent_comments) ?></div>
                            <div class="text-xs text-green-800">Comments</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                            <div class="text-2xl text-purple-600 mb-1"><?= count($recent_cart_items) ?></div>
                            <div class="text-xs text-purple-800">Cart Items</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                            <div class="text-2xl text-orange-600 mb-1">0</div>
                            <div class="text-xs text-orange-800">Orders</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Activities -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <h2 class="font-semibold text-gray-900 flex items-center">
                            <i data-lucide="activity" class="w-5 h-5 text-primary mr-2"></i>
                            Recent Activities
                        </h2>
                    </div>

                    <div class="p-6">
                        <!-- Activity Type Selector -->
                        <div class="mb-6">
                            <select id="activityType" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                <option value="likes">❤️ Recent Likes (<?= count($recent_likes) ?>)</option>
                                <option value="comments">💬 Recent Comments (<?= count($recent_comments) ?>)</option>
                                <option value="cart">🛒 Cart Additions (<?= count($recent_cart_items) ?>)</option>
                            </select>
                        </div>

                        <!-- Activity Content -->
                        <div class="min-h-[400px] max-h-[600px] overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: #3b82f6 #f1f5f9;">
                            <!-- Likes Content -->
                            <div id="likes-content" class="activity-content space-y-3">
                                <?php if (!empty($recent_likes)): ?>
                                    <?php foreach ($recent_likes as $like): ?>
                                        <a href="/forum/post/<?= $like['post_id'] ?>" class="block">
                                            <div class="flex items-start p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-100 hover:shadow-md hover:border-red-200 transition-all duration-200">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                        <i data-lucide="heart" class="w-5 h-5 text-red-500"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1 ml-4">
                                                    <p class="text-sm font-semibold text-gray-900 mb-1">
                                                        <?= esc($like['post_title']) ?>
                                                    </p>
                                                    <p class="text-xs text-gray-600 flex items-center">
                                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                                        <?= date('M j, Y \a\t g:i A', strtotime($like['created_at'])) ?>
                                                    </p>
                                                </div>
                                                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-12">
                                        <i data-lucide="heart" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                        <p class="text-gray-500">No recent likes</p>
                                        <p class="text-sm text-gray-400 mt-1">Start liking posts in the forum</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Comments Content -->
                            <div id="comments-content" class="activity-content space-y-3 hidden">
                                <?php if (!empty($recent_comments)): ?>
                                    <?php foreach ($recent_comments as $comment): ?>
                                        <a href="/forum/post/<?= $comment['post_id'] ?>" class="block">
                                            <div class="flex items-start p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100 hover:shadow-md hover:border-blue-200 transition-all duration-200">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i data-lucide="message-circle" class="w-5 h-5 text-blue-500"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1 ml-4">
                                                    <p class="text-sm font-semibold text-gray-900 mb-1">
                                                        On: <?= esc($comment['post_title']) ?>
                                                    </p>
                                                    <p class="text-sm text-gray-600 italic mb-2 line-clamp-2">
                                                        "<?= esc(substr($comment['comment'], 0, 100)) ?><?= strlen($comment['comment']) > 100 ? '...' : '' ?>"
                                                    </p>
                                                    <p class="text-xs text-gray-600 flex items-center">
                                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                                        <?= date('M j, Y \a\t g:i A', strtotime($comment['created_at'])) ?>
                                                    </p>
                                                </div>
                                                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-12">
                                        <i data-lucide="message-circle" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                        <p class="text-gray-500">No recent comments</p>
                                        <p class="text-sm text-gray-400 mt-1">Join the conversation in the forum</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Cart Content -->
                            <div id="cart-content" class="activity-content space-y-3 hidden">
                                <?php if (!empty($recent_cart_items)): ?>
                                    <?php foreach ($recent_cart_items as $item): ?>
                                        <a href="/marketplace/product/<?= $item['product_id'] ?>" class="block">
                                            <div class="flex items-start p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100 hover:shadow-md hover:border-green-200 transition-all duration-200">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                        <i data-lucide="shopping-cart" class="w-5 h-5 text-green-500"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1 ml-4">
                                                    <p class="text-sm font-semibold text-gray-900 mb-1">
                                                        <?= esc($item['product_name']) ?>
                                                    </p>
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <span class="text-xs text-gray-600">Quantity: <span class="font-semibold"><?= $item['quantity'] ?></span></span>
                                                        <span class="text-sm text-green-600">₱<?= number_format($item['price'], 2) ?></span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 flex items-center">
                                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                                        <?= date('M j, Y \a\t g:i A', strtotime($item['created_at'])) ?>
                                                    </p>
                                                </div>
                                                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-12">
                                        <i data-lucide="shopping-cart" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                        <p class="text-gray-500">No items in cart</p>
                                        <p class="text-sm text-gray-400 mt-1">Browse the marketplace to add items</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Activity dropdown functionality
function showActivityContent(type) {
    document.querySelectorAll('.activity-content').forEach(content => {
        content.classList.add('hidden');
    });

    const selectedContent = document.getElementById(type + '-content');
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
}

// Admin form toggle functions
function toggleEditForm() {
    const form = document.getElementById('editProfileForm');
    const addForm = document.getElementById('addAdminForm');

    if (addForm && !addForm.classList.contains('hidden')) {
        addForm.classList.add('hidden');
    }

    form.classList.toggle('hidden');
}

function toggleAddAdminForm() {
    const form = document.getElementById('addAdminForm');
    const editForm = document.getElementById('editProfileForm');

    if (editForm && !editForm.classList.contains('hidden')) {
        editForm.classList.add('hidden');
    }

    form.classList.toggle('hidden');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const activityTypeSelect = document.getElementById('activityType');
    if (activityTypeSelect) {
        showActivityContent('likes');

        activityTypeSelect.addEventListener('change', function() {
            showActivityContent(this.value);
        });
    }
});
</script>

<style>
/* Custom Scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #3b82f6, #2563eb);
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #2563eb, #1d4ed8);
}

/* Line Clamp Utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?= $this->endSection() ?>
