<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="/announcements" class="inline-flex items-center text-primary hover:text-primary-hover transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Announcements
            </a>
        </div>

        <!-- Announcement Content -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="p-8">
                <div class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded mb-4">
                    <?= ucfirst(esc($announcement['category'] ?? 'general')) ?>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    <?= esc($announcement['title']) ?>
                </h1>

                <div class="flex items-center text-sm text-gray-600 mb-6">
                    <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                    <span class="mr-4"><?= esc($announcement['creator_name']) ?></span>
                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                    <span class="mr-4"><?= date('M d, Y', strtotime($announcement['created_at'])) ?></span>
                    <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                    <span><?= date('H:i', strtotime($announcement['created_at'])) ?></span>
                </div>

                <div class="prose prose-lg max-w-none">
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                        <?= nl2br(esc($announcement['content'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Announcements -->
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Announcements</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- This would be populated with related announcements in a real implementation -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
                    <div class="text-sm text-primary font-semibold mb-2">General</div>
                    <h4 class="font-semibold text-gray-900 mb-2">Sample Announcement Title</h4>
                    <p class="text-gray-600 text-sm">This is a sample announcement preview...</p>
                    <a href="#" class="text-primary text-sm hover:underline mt-2 inline-block">Read more</a>
                </div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
                    <div class="text-sm text-primary font-semibold mb-2">Market</div>
                    <h4 class="font-semibold text-gray-900 mb-2">Another Sample Announcement</h4>
                    <p class="text-gray-600 text-sm">This is another sample announcement...</p>
                    <a href="#" class="text-primary text-sm hover:underline mt-2 inline-block">Read more</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>