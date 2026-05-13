<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8 bg-gradient-to-r from-primary to-primary-hover text-white rounded-2xl p-6 shadow-lg border border-mint-dark/30">
        <div class="flex items-center gap-3 mb-2">
            <span class="w-10 h-10 rounded-xl bg-white/20 inline-flex items-center justify-center">
                <i data-lucide="megaphone" class="w-5 h-5"></i>
            </span>
            <h1 class="text-3xl font-bold mb-0">Announcements</h1>
        </div>
        <p class="text-mint-light">Stay updated with the latest news and announcements from Farmart</p>
    </div>

    <!-- Category Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap gap-2">
            <a href="/announcements" class="px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2 <?= empty($current_category) ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                <i data-lucide="layers" class="w-4 h-4"></i>
                All
            </a>
            <a href="/announcements?category=general" class="px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2 <?= $current_category === 'general' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                <i data-lucide="newspaper" class="w-4 h-4"></i>
                General
            </a>
            <a href="/announcements?category=weather" class="px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2 <?= $current_category === 'weather' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                <i data-lucide="cloud-sun" class="w-4 h-4"></i>
                Weather
            </a>
            <a href="/announcements?category=market" class="px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2 <?= $current_category === 'market' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                <i data-lucide="store" class="w-4 h-4"></i>
                Market
            </a>
            <a href="/announcements?category=policy" class="px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2 <?= $current_category === 'policy' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                Policy
            </a>
        </div>
    </div>

    <!-- Announcements List -->
    <?php if (empty($announcements)): ?>
        <div class="text-center py-12">
            <i data-lucide="megaphone-off" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No announcements found</p>
            <p class="text-gray-500 mt-2">Check back later for updates</p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($announcements as $announcement): ?>
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded mb-2">
                                    <?= ucfirst(esc($announcement['category'] ?? 'general')) ?>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                    <a href="/announcements/<?= $announcement['id'] ?>" class="hover:text-primary transition-colors">
                                        <?= esc($announcement['title']) ?>
                                    </a>
                                </h2>
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                    <span class="mr-4"><?= esc($announcement['creator_name']) ?></span>
                                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                    <span class="mr-4"><?= date('M d, Y', strtotime($announcement['created_at'])) ?></span>
                                    <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                    <span><?= date('H:i', strtotime($announcement['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <a href="/announcements/<?= $announcement['id'] ?>" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                    Read More
                                </a>
                            </div>
                        </div>

                        <div class="text-gray-700 line-clamp-3">
                            <?= esc(substr($announcement['content'], 0, 300)) ?>...
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>