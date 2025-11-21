<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Violation Reports</h1>
                <p class="text-gray-600">Review and manage user reports</p>
            </div>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap gap-2">
            <a href="/admin/violations?status=pending" class="px-4 py-2 rounded-lg font-medium transition-colors
                <?= $current_status === 'pending' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                Pending (<?= $statistics['violations']['pending'] ?? 0 ?>)
            </a>
            <a href="/admin/violations?status=reviewed" class="px-4 py-2 rounded-lg font-medium transition-colors
                <?= $current_status === 'reviewed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                Reviewed (<?= $statistics['violations']['reviewed'] ?? 0 ?>)
            </a>
            <a href="/admin/violations?status=resolved" class="px-4 py-2 rounded-lg font-medium transition-colors
                <?= $current_status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                Resolved (<?= $statistics['violations']['resolved'] ?? 0 ?>)
            </a>
            <a href="/admin/violations" class="px-4 py-2 rounded-lg font-medium transition-colors
                <?= !$current_status ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                All (<?= $statistics['violations']['total'] ?? 0 ?>)
            </a>
        </div>
    </div>

    <!-- Violations List -->
    <?php if (empty($violations)): ?>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-12 text-center">
            <i data-lucide="flag" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No violation reports found</p>
            <p class="text-gray-500 mt-2">Reports will appear here when users submit them</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($violations as $violation): ?>
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded">
                                        <?= ucfirst(str_replace('_', ' ', $violation['reported_type'])) ?>
                                    </span>
                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded
                                        <?php
                                        switch($violation['status']) {
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'reviewed': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'resolved': echo 'bg-green-100 text-green-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst($violation['status']) ?>
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <?= esc($violation['reason']) ?>
                                </h3>
                                <div class="text-sm text-gray-600 mb-3">
                                    <p><strong>Reported by:</strong> <?= esc($violation['reporter_name']) ?></p>
                                    <p><strong>Reported Item ID:</strong> <?= $violation['reported_id'] ?> (<?= ucfirst(str_replace('_', ' ', $violation['reported_type'])) ?>)</p>
                                    <p><strong>Reported on:</strong> <?= date('M d, Y H:i', strtotime($violation['created_at'])) ?></p>
                                    <?php if ($violation['reviewed_at']): ?>
                                        <p><strong>Reviewed by:</strong> <?= esc($violation['reviewer_name']) ?> on <?= date('M d, Y H:i', strtotime($violation['reviewed_at'])) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <?php
                                    $viewUrl = '';
                                    switch ($violation['reported_type']) {
                                        case 'forum_post':
                                            $viewUrl = '/forum/post/' . $violation['reported_id'];
                                            break;
                                        case 'forum_comment':
                                            $viewUrl = '/forum/post/' . $violation['reported_id']; // Assuming comment links to post
                                            break;
                                        case 'product':
                                            $viewUrl = '/marketplace/product/' . $violation['reported_id'];
                                            break;
                                        case 'user':
                                            $viewUrl = '/admin/users/' . $violation['reported_id'];
                                            break;
                                    }
                                    if ($viewUrl): ?>
                                        <a href="<?= $viewUrl ?>" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                            View Reported Item
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php if ($violation['description']): ?>
                                    <div class="bg-gray-50 p-3 rounded-lg mb-3">
                                        <p class="text-sm text-gray-700">
                                            <strong>Description:</strong> <?= nl2br(esc($violation['description'])) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="ml-6 flex flex-col gap-2">
                                <?php if ($violation['status'] === 'pending'): ?>
                                    <form action="/admin/violations/<?= $violation['id'] ?>/status" method="POST" class="inline">
                                        <input type="hidden" name="status" value="reviewed">
                                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                            Mark as Reviewed
                                        </button>
                                    </form>
                                    <form action="/admin/violations/<?= $violation['id'] ?>/status" method="POST" class="inline">
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                            Mark as Resolved
                                        </button>
                                    </form>
                                <?php elseif ($violation['status'] === 'reviewed'): ?>
                                    <form action="/admin/violations/<?= $violation['id'] ?>/status" method="POST" class="inline">
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                            Mark as Resolved
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Delete Reported Item Button -->
                                <form action="/admin/violations/<?= $violation['id'] ?>/delete" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this reported item? This action cannot be undone.')">
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold inline-flex items-center">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                        Delete Item
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>