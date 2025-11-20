<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Community Forum</h1>
        <p class="text-gray-600">Connect with fellow farmers and share knowledge</p>
    </div>

    <!-- Create Post Button (if logged in) -->
    <?php if (session()->get('user_id')): ?>
        <div class="mb-6">
            <a href="/forum/create" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold inline-flex items-center">
                <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                Create New Post
            </a>
        </div>
    <?php endif; ?>

    <!-- Posts List -->
    <?php if (empty($posts)): ?>
        <div class="text-center py-12">
            <i data-lucide="message-square" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No posts yet</p>
            <p class="text-gray-500 mt-2">Be the first to start a discussion!</p>
            <?php if (session()->get('user_id')): ?>
                <a href="/forum/create" class="mt-4 inline-block bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold">
                    Create First Post
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($posts as $post): ?>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                <a href="/forum/post/<?= $post['id'] ?>" class="hover:text-primary transition-colors">
                                    <?= esc($post['title']) ?>
                                </a>
                            </h2>

                            <div class="flex items-center text-sm text-gray-600 mb-3 space-x-4">
                                <span class="flex items-center">
                                    <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                    <?= esc($post['author_name']) ?>
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                    <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="message-circle" class="w-4 h-4 mr-1"></i>
                                    <?= $post['comment_count'] ?> comments
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                                    <?= $post['likes'] ?> likes
                                </span>
                            </div>

                            <p class="text-gray-700 line-clamp-3">
                                <?= esc(substr($post['content'], 0, 200)) ?>...
                            </p>
                        </div>

                        <div class="ml-4">
                            <a href="/forum/post/<?= $post['id'] ?>" class="text-primary hover:text-primary-hover font-semibold">
                                Read More →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>