<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Announcement Management</h1>
            <a href="/admin/announcements/create" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
                Create Announcement
            </a>
        </div>
        <p class="text-gray-600 mt-2">Manage system-wide announcements and notifications</p>
    </div>

    <!-- Announcements List -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Announcements (<?= count($announcements) ?>)</h3>
        </div>

        <?php if (empty($announcements)): ?>
            <div class="text-center py-12">
                <i data-lucide="megaphone" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No announcements</h3>
                <p class="text-gray-600">Create your first announcement to communicate with users.</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-200">
                <?php foreach ($announcements as $announcement): ?>
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h4 class="text-lg font-semibold text-gray-900 mr-3">
                                        <a href="/announcements/<?= $announcement['id'] ?>" class="text-primary hover:text-primary-hover">
                                            <?= esc($announcement['title']) ?>
                                        </a>
                                    </h4>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        <?php
                                        switch($announcement['priority']) {
                                            case 'high': echo 'bg-red-100 text-red-800'; break;
                                            case 'medium': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'low': echo 'bg-green-100 text-green-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst($announcement['priority']) ?> Priority
                                    </span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-2
                                        <?php
                                        switch($announcement['category']) {
                                            case 'weather': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'government': echo 'bg-purple-100 text-purple-800'; break;
                                            case 'market': echo 'bg-green-100 text-green-800'; break;
                                            case 'general': echo 'bg-gray-100 text-gray-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst($announcement['category']) ?>
                                    </span>
                                </div>

                                <p class="text-gray-600 mb-3">
                                    <?= esc(substr($announcement['content'], 0, 200)) ?><?= strlen($announcement['content']) > 200 ? '...' : '' ?>
                                </p>

                                <div class="flex items-center text-sm text-gray-500">
                                    <span>Created by: <?= esc($announcement['creator_name']) ?></span>
                                    <span class="mx-2">•</span>
                                    <span><?= date('M d, Y H:i', strtotime($announcement['created_at'])) ?></span>
                                    <?php if ($announcement['updated_at'] !== $announcement['created_at']): ?>
                                        <span class="mx-2">•</span>
                                        <span>Updated: <?= date('M d, Y H:i', strtotime($announcement['updated_at'])) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 ml-4">
                                <a href="/admin/announcements/edit/<?= $announcement['id'] ?>" class="text-primary hover:text-primary-hover">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form method="post" action="/admin/announcements/delete/<?= $announcement['id'] ?>" class="inline swal-confirm-form" data-confirm="Are you sure you want to delete this announcement?">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>