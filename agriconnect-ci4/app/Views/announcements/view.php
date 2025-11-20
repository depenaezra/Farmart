<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="/announcements" class="text-primary hover:text-primary-hover font-semibold inline-flex items-center">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Announcements
            </a>
        </div>

        <!-- Announcement Content -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
            <!-- Header -->
            <div class="mb-6">
                <!-- Category and Priority Badges -->
                <div class="flex items-center space-x-2 mb-4">
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
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    <?= esc($announcement['title']) ?>
                </h1>

                <!-- Meta Information -->
                <div class="flex items-center text-sm text-gray-600 space-x-6">
                    <span class="flex items-center">
                        <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                        <?= esc($announcement['creator_name'] ?? 'Admin') ?>
                    </span>
                    <span class="flex items-center">
                        <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                        <?= date('M d, Y H:i', strtotime($announcement['created_at'])) ?>
                    </span>
                    <?php if ($announcement['updated_at'] && $announcement['updated_at'] !== $announcement['created_at']): ?>
                        <span class="flex items-center">
                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                            Updated <?= date('M d, Y H:i', strtotime($announcement['updated_at'])) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                <div class="text-gray-800 leading-relaxed">
                    <?= nl2br(esc($announcement['content'])) ?>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button onclick="window.print()" class="text-gray-600 hover:text-gray-800 font-semibold inline-flex items-center">
                            <i data-lucide="printer" class="w-4 h-4 mr-1"></i>
                            Print
                        </button>
                        <button onclick="shareAnnouncement()" class="text-gray-600 hover:text-gray-800 font-semibold inline-flex items-center">
                            <i data-lucide="share" class="w-4 h-4 mr-1"></i>
                            Share
                        </button>
                    </div>

                    <a href="/announcements" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover font-semibold">
                        View All Announcements
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Announcements -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">More Announcements</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- This would be populated with related announcements in a real implementation -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded">Market</span>
                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded">Low Priority</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <a href="#" class="hover:text-primary transition-colors">Market Price Update - November 2024</a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-3">
                        Current market prices remain stable: Tomatoes: ₱70-85/kg, Lettuce: ₱55-65/kg...
                    </p>
                    <div class="text-sm text-gray-500">
                        Nov 20, 2024
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded">Government</span>
                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Medium Priority</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <a href="#" class="hover:text-primary transition-colors">New Government Subsidy Program</a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-3">
                        DA announces new subsidy program for small-scale farmers. Registration starts next week...
                    </p>
                    <div class="text-sm text-gray-500">
                        Nov 20, 2024
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function shareAnnouncement() {
    if (navigator.share) {
        navigator.share({
            title: '<?= esc($announcement['title']) ?>',
            text: 'Check out this announcement from AgriConnect',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Link copied to clipboard!');
        });
    }
}
</script>

<?= $this->endSection() ?>