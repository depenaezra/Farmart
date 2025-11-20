<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Announcement</h1>
                <p class="text-gray-600">Update announcement details</p>
            </div>
            <a href="/admin/announcements" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                Back to Announcements
            </a>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <form method="post" action="/admin/announcements/edit/<?= $announcement['id'] ?>">
                <?= csrf_field() ?>

                <?php if (session()->has('errors')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">There were some errors with your submission:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mr-3"></i>
                            <div class="text-sm text-red-700">
                                <?= session('error') ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="<?= old('title', $announcement['title']) ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Enter announcement title" required>
                        <p class="mt-1 text-sm text-gray-500">Choose a clear, descriptive title for your announcement.</p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            <option value="">Select a category</option>
                            <option value="weather" <?= old('category', $announcement['category']) === 'weather' ? 'selected' : '' ?>>Weather</option>
                            <option value="government" <?= old('category', $announcement['category']) === 'government' ? 'selected' : '' ?>>Government</option>
                            <option value="market" <?= old('category', $announcement['category']) === 'market' ? 'selected' : '' ?>>Market</option>
                            <option value="general" <?= old('category', $announcement['category']) === 'general' ? 'selected' : '' ?>>General</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Categorize your announcement for better organization.</p>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" name="priority"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            <option value="">Select priority level</option>
                            <option value="low" <?= old('priority', $announcement['priority']) === 'low' ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= old('priority', $announcement['priority']) === 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= old('priority', $announcement['priority']) === 'high' ? 'selected' : '' ?>>High</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">High priority announcements will be more prominently displayed.</p>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Content <span class="text-red-500">*</span>
                        </label>
                        <textarea id="content" name="content" rows="8"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Write your announcement content here..." required><?= old('content', $announcement['content']) ?></textarea>
                        <p class="mt-1 text-sm text-gray-500">Provide detailed information about your announcement. Minimum 20 characters.</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="/admin/announcements" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                            <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                            Update Announcement
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>