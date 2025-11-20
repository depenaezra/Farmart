<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Announcements</h1>
        <p class="text-gray-600">Stay updated with the latest news and information</p>
    </div>

    <!-- Category Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <form action="/announcements" method="GET" class="flex flex-wrap gap-4">
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Filter by Category</label>
                <select
                    id="category"
                    name="category"
                    onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                >
                    <option value="all" <?= $current_category === 'all' || !$current_category ? 'selected' : '' ?>>All Categories</option>
                    <option value="weather" <?= $current_category === 'weather' ? 'selected' : '' ?>>Weather</option>
                    <option value="government" <?= $current_category === 'government' ? 'selected' : '' ?>>Government</option>
                    <option value="market" <?= $current_category === 'market' ? 'selected' : '' ?>>Market</option>
                    <option value="general" <?= $current_category === 'general' ? 'selected' : '' ?>>General</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Announcements List -->
    <?php if (empty($announcements)): ?>
        <div class="text-center py-12">
            <i data-lucide="bell" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No announcements found</p>
            <p class="text-gray-500 mt-2">
                <?php if ($current_category && $current_category !== 'all'): ?>
                    No announcements in the "<?= ucfirst($current_category) ?>" category.
                    <a href="/announcements" class="text-primary hover:text-primary-hover">View all announcements</a>
                <?php else: ?>
                    Check back later for updates.
                <?php endif; ?>
            </p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($announcements as $announcement): ?>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Category and Priority Badges -->
                            <div class="flex items-center space-x-2 mb-3">
                                <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full">
                                    <?= ucfirst(esc($announcement['category'])) ?>
                                </span>
                                <?php
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'high' => 'bg-red-100 text-red-800'
                                ];
                                ?>
                                <span class="inline-block px-3 py-1 <?= $priorityColors[$announcement['priority']] ?> text-sm font-semibold rounded-full">
                                    <?= ucfirst(esc($announcement['priority'])) ?> Priority
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                                <a href="/announcements/<?= $announcement['id'] ?>" class="hover:text-primary transition-colors">
                                    <?= esc($announcement['title']) ?>
                                </a>
                            </h2>

                            <!-- Meta Information -->
                            <div class="flex items-center text-sm text-gray-600 mb-4 space-x-4">
                                <span class="flex items-center">
                                    <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                    <?= esc($announcement['creator_name'] ?? 'Admin') ?>
                                </span>
                                <span class="flex items-center">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                    <?= date('M d, Y H:i', strtotime($announcement['created_at'])) ?>
                                </span>
                            </div>

                            <!-- Content Preview -->
                            <div class="text-gray-700 line-clamp-3">
                                <?= nl2br(esc(substr($announcement['content'], 0, 300))) ?>...
                            </div>

                            <!-- Read More Link -->
                            <div class="mt-4">
                                <a href="/announcements/<?= $announcement['id'] ?>" class="text-primary hover:text-primary-hover font-semibold">
                                    Read Full Announcement →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Information Box -->
    <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i data-lucide="info" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Stay Informed</h3>
                <p class="text-blue-800">
                    Announcements include important updates about weather conditions, government programs,
                    market information, and other news relevant to Nasugbu farmers and buyers.
                    Check back regularly for the latest information.
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>