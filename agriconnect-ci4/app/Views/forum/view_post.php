<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="/forum" class="text-primary hover:text-primary-hover font-semibold inline-flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Forum
        </a>
    </div>

    <!-- Post Content -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <div class="mb-4">
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= esc($post['title']) ?></h1>

            <div class="flex items-center text-sm text-gray-600 mb-4 space-x-4">
                <span class="flex items-center">
                    <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                    <?= esc($post['author_name']) ?>
                    <?php if ($post['author_role'] === 'admin'): ?>
                        <span class="ml-1 px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded">Admin</span>
                    <?php elseif ($post['author_role'] === 'farmer'): ?>
                        <span class="ml-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Farmer</span>
                    <?php endif; ?>
                </span>
                <span class="flex items-center">
                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                    <?= date('M d, Y H:i', strtotime($post['created_at'])) ?>
                </span>
                <span class="flex items-center">
                    <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                    <?= $post['likes'] ?> likes
                </span>
            </div>
        </div>

        <div class="prose max-w-none">
            <?= nl2br(esc($post['content'])) ?>
        </div>

        <!-- Post Actions (if logged in) -->
        <?php if (session()->get('user_id')): ?>
            <div class="mt-6 pt-4 border-t border-gray-200">
                <form action="/forum/post/<?= $post['id'] ?>/like" method="POST" class="inline">
                    <button type="submit" class="text-primary hover:text-primary-hover font-semibold inline-flex items-center">
                        <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                        Like Post
                    </button>
                </form>

                <?php if (session()->get('user_id') == $post['user_id'] || session()->get('user_role') == 'admin'): ?>
                    <form action="/forum/post/<?= $post['id'] ?>/delete" method="POST" class="inline ml-4"
                          onsubmit="return confirm('Are you sure you want to delete this post?')">
                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold inline-flex items-center">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                            Delete Post
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Comments Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Comments (<?= count($comments) ?>)</h2>

        <!-- Comments List -->
        <?php if (empty($comments)): ?>
            <div class="text-center py-8">
                <i data-lucide="message-square" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-600">No comments yet. Be the first to comment!</p>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($comments as $comment): ?>
                    <div class="border-b border-gray-100 pb-6 last:border-b-0 last:pb-0">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="font-semibold text-gray-900"><?= esc($comment['author_name']) ?></span>
                                    <?php if ($comment['author_role'] === 'admin'): ?>
                                        <span class="px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded">Admin</span>
                                    <?php elseif ($comment['author_role'] === 'farmer'): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Farmer</span>
                                    <?php endif; ?>
                                    <span class="text-sm text-gray-500">
                                        <?= date('M d, Y H:i', strtotime($comment['created_at'])) ?>
                                    </span>
                                </div>
                                <p class="text-gray-700"><?= nl2br(esc($comment['comment'])) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Add Comment Form (if logged in) -->
        <?php if (session()->get('user_id')): ?>
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add a Comment</h3>

                <form action="/forum/post/<?= $post['id'] ?>/comment" method="POST">
                    <div class="mb-4">
                        <textarea
                            name="comment"
                            rows="4"
                            placeholder="Share your thoughts..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            required
                        ></textarea>
                    </div>

                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover font-semibold">
                        <i data-lucide="send" class="w-4 h-4 mr-2 inline"></i>
                        Post Comment
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-gray-600 mb-4">Please <a href="/auth/login" class="text-primary hover:text-primary-hover font-semibold">log in</a> to join the discussion.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>