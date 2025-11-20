<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="/forum" class="inline-flex items-center text-primary hover:text-primary-hover mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Forum
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
            <p class="text-gray-600 mt-2">Share your thoughts and experiences with the community</p>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <form action="/forum/create" method="POST">
                <?= csrf_field() ?>

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Post Title *</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="<?= old('title') ?>"
                            required
                            minlength="5"
                            maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Enter an engaging title for your post"
                        >
                        <?php if (isset($errors['title'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['title'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select
                            id="category"
                            name="category"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="general" <?= old('category', 'general') === 'general' ? 'selected' : '' ?>>General Discussion</option>
                            <option value="farming tips" <?= old('category') === 'farming tips' ? 'selected' : '' ?>>Farming Tips</option>
                            <option value="market prices" <?= old('category') === 'market prices' ? 'selected' : '' ?>>Market Prices</option>
                            <option value="weather" <?= old('category') === 'weather' ? 'selected' : '' ?>>Weather Updates</option>
                            <option value="equipment" <?= old('category') === 'equipment' ? 'selected' : '' ?>>Equipment & Tools</option>
                        </select>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Content *</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="10"
                            required
                            minlength="20"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Share your thoughts, experiences, or ask questions..."
                        ><?= old('content') ?></textarea>
                        <?php if (isset($errors['content'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['content'] ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-gray-500 mt-1">Minimum 20 characters. Be respectful and helpful to fellow farmers.</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex gap-4">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        <i data-lucide="send" class="w-5 h-5 inline mr-2"></i>
                        Create Post
                    </button>
                    <a href="/forum" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Guidelines -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Posting Guidelines</h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li>• Be respectful and constructive in your posts</li>
                <li>• Share accurate information and personal experiences</li>
                <li>• Ask questions that help the farming community</li>
                <li>• Avoid spam, advertisements, or inappropriate content</li>
                <li>• Use clear, descriptive titles for your posts</li>
            </ul>
        </div>
    </div>
</div>

<?= $this->endSection() ?>