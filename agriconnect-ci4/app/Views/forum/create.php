<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="/forum" class="text-primary hover:text-primary-hover font-semibold inline-flex items-center">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Forum
            </a>
        </div>

        <!-- Create Post Form -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Post</h1>

            <form action="/forum/create" method="POST">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?= esc(old('title')) ?>"
                        placeholder="Enter your post title..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        required
                        minlength="5"
                        maxlength="255"
                    >
                </div>

                <div class="mb-6">
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category
                    </label>
                    <select
                        id="category"
                        name="category"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                        <option value="general" <?= old('category') === 'general' ? 'selected' : '' ?>>General Discussion</option>
                        <option value="farming tips" <?= old('category') === 'farming tips' ? 'selected' : '' ?>>Farming Tips</option>
                        <option value="market prices" <?= old('category') === 'market prices' ? 'selected' : '' ?>>Market Prices</option>
                        <option value="equipment" <?= old('category') === 'equipment' ? 'selected' : '' ?>>Equipment & Tools</option>
                        <option value="weather" <?= old('category') === 'weather' ? 'selected' : '' ?>>Weather & Climate</option>
                        <option value="other" <?= old('category') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="10"
                        placeholder="Share your thoughts, ask questions, or provide helpful information..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        required
                        minlength="20"
                    ><?= esc(old('content')) ?></textarea>
                    <p class="text-sm text-gray-500 mt-1">Minimum 20 characters. Be respectful and helpful to the community.</p>
                </div>

                <div class="flex items-center justify-between">
                    <a href="/forum" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold">
                        Cancel
                    </a>

                    <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold inline-flex items-center">
                        <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                        Create Post
                    </button>
                </div>
            </form>
        </div>

        <!-- Guidelines -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-blue-900 mb-4">Posting Guidelines</h2>
            <ul class="text-blue-800 space-y-2">
                <li class="flex items-start">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0"></i>
                    Be respectful and constructive in your discussions
                </li>
                <li class="flex items-start">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0"></i>
                    Share accurate information and cite sources when possible
                </li>
                <li class="flex items-start">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0"></i>
                    Ask specific questions to get better answers
                </li>
                <li class="flex items-start">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0"></i>
                    Keep discussions relevant to agriculture and farming
                </li>
            </ul>
        </div>
    </div>
</div>

<?= $this->endSection() ?>