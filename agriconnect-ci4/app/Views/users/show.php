<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-2xl font-bold text-gray-600">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900"><?= esc($user['name']) ?></h2>
                    <div class="text-sm text-gray-600 mt-1">
                        <?= esc($user['role']) ?>
                        <?php if (!empty($user['location'])): ?>
                            • <?= esc($user['location']) ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($user['cooperative'])): ?>
                        <div class="text-sm text-gray-500 mt-2">Cooperative: <?= esc($user['cooperative']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="ml-auto text-sm text-gray-500">
                    Joined: <?= date('M d, Y', strtotime($user['created_at'])) ?>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-700">
                <?php if (!empty($user['phone'])): ?>
                    <div>Phone: <?= esc($user['phone']) ?></div>
                <?php endif; ?>
                <div>Email: <?= esc($user['email']) ?></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="bg-white rounded-lg border border-gray-200 p-4 mb-4">
                    <h3 class="font-semibold text-lg mb-3">Recent Products</h3>
                    <?php if (empty($products)): ?>
                        <div class="text-gray-600">No products yet.</div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($products as $p): ?>
                                <div class="flex items-center gap-3 border rounded p-3">
                                    <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden">
                                        <?php if (!empty($p['image_url'])): ?>
                                            <img src="<?= esc($p['image_url']) ?>" alt="<?= esc($p['name']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <a href="/marketplace/product/<?= $p['id'] ?>" class="font-medium text-gray-900 hover:text-primary"><?= esc($p['name']) ?></a>
                                        <div class="text-sm text-gray-600"><?= esc($p['category']) ?> • ₱<?= number_format($p['price'], 2) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-lg border border-gray-200 p-4 mb-4">
                    <h3 class="font-semibold text-lg mb-3">Recent Posts</h3>
                    <?php if (empty($posts)): ?>
                        <div class="text-gray-600">No posts yet.</div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($posts as $post): ?>
                                <div class="border rounded p-3">
                                    <a href="/forum/post/<?= $post['id'] ?>" class="font-medium text-gray-900 hover:text-primary"><?= esc($post['title']) ?></a>
                                    <div class="text-xs text-gray-500"><?= date('M d, Y H:i', strtotime($post['created_at'])) ?><?php if (!empty($post['category'])): ?> • <?= esc($post['category']) ?><?php endif; ?></div>
                                    <div class="text-sm text-gray-700 mt-2"><?= esc(substr(strip_tags($post['content']), 0, 120)) ?>...</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>