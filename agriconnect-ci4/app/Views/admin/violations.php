<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Violation Reports</h1>
                <p class="text-gray-600">Review and manage user reports efficiently</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-600"></i>
                        <span class="text-sm font-semibold text-gray-700">
                            <?= $statistics['violations']['pending'] ?? 0 ?> Pending
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Status Filter -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex items-center gap-3 mb-4">
            <i data-lucide="filter" class="w-5 h-5 text-gray-600"></i>
            <h3 class="text-lg font-semibold text-gray-900">Filter by Status</h3>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="/admin/violations" class="group relative px-5 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm
                <?= !$current_status ? 'bg-primary text-white shadow-md scale-105' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow' ?>">
                <div class="flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4"></i>
                    <span>All Reports</span>
                    <span class="ml-1 px-2 py-0.5 text-xs font-bold rounded-full <?= !$current_status ? 'bg-white/20' : 'bg-gray-200' ?>">
                        <?= $statistics['violations']['total'] ?? 0 ?>
                    </span>
                </div>
            </a>
            <a href="/admin/violations?status=pending" class="group relative px-5 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm
                <?= $current_status === 'pending' ? 'bg-yellow-500 text-white shadow-md scale-105' : 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100 hover:shadow' ?>">
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <span>Pending</span>
                    <span class="ml-1 px-2 py-0.5 text-xs font-bold rounded-full <?= $current_status === 'pending' ? 'bg-white/20' : 'bg-yellow-200' ?>">
                        <?= $statistics['violations']['pending'] ?? 0 ?>
                    </span>
                </div>
            </a>
            <a href="/admin/violations?status=reviewed" class="group relative px-5 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm
                <?= $current_status === 'reviewed' ? 'bg-blue-500 text-white shadow-md scale-105' : 'bg-blue-50 text-blue-700 hover:bg-blue-100 hover:shadow' ?>">
                <div class="flex items-center gap-2">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    <span>Reviewed</span>
                    <span class="ml-1 px-2 py-0.5 text-xs font-bold rounded-full <?= $current_status === 'reviewed' ? 'bg-white/20' : 'bg-blue-200' ?>">
                        <?= $statistics['violations']['reviewed'] ?? 0 ?>
                    </span>
                </div>
            </a>
            <a href="/admin/violations?status=resolved" class="group relative px-5 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm
                <?= $current_status === 'resolved' ? 'bg-green-500 text-white shadow-md scale-105' : 'bg-green-50 text-green-700 hover:bg-green-100 hover:shadow' ?>">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>Resolved</span>
                    <span class="ml-1 px-2 py-0.5 text-xs font-bold rounded-full <?= $current_status === 'resolved' ? 'bg-white/20' : 'bg-green-200' ?>">
                        <?= $statistics['violations']['resolved'] ?? 0 ?>
                    </span>
                </div>
            </a>
        </div>
    </div>

    <!-- Violations List -->
    <?php if (empty($violations)): ?>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="flag" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No violation reports found</h3>
                <p class="text-gray-500">Reports will appear here when users submit them</p>
            </div>
        </div>
    <?php else: ?>
        <div class="space-y-5">
            <?php foreach ($violations as $violation): ?>
                <div class="bg-white rounded-xl shadow-lg border-2 border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 hover:border-primary/20">
                    <!-- Status Bar -->
                    <div class="h-2 <?php
                        switch($violation['status']) {
                            case 'pending': echo 'bg-gradient-to-r from-yellow-400 to-yellow-500'; break;
                            case 'reviewed': echo 'bg-gradient-to-r from-blue-400 to-blue-500'; break;
                            case 'resolved': echo 'bg-gradient-to-r from-green-400 to-green-500'; break;
                            default: echo 'bg-gray-400';
                        }
                    ?>"></div>
                    
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                            <!-- Main Content -->
                            <div class="flex-1">
                                <!-- Status Badge & Type -->
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-lg shadow-sm
                                        <?php
                                        switch($violation['status']) {
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800 border border-yellow-300'; break;
                                            case 'reviewed': echo 'bg-blue-100 text-blue-800 border border-blue-300'; break;
                                            case 'resolved': echo 'bg-green-100 text-green-800 border border-green-300'; break;
                                            default: echo 'bg-gray-100 text-gray-800 border border-gray-300';
                                        }
                                        ?>">
                                        <i data-lucide="<?php
                                            switch($violation['status']) {
                                                case 'pending': echo 'clock'; break;
                                                case 'reviewed': echo 'eye'; break;
                                                case 'resolved': echo 'check-circle'; break;
                                                default: echo 'circle';
                                            }
                                        ?>" class="w-4 h-4"></i>
                                        <?= ucfirst($violation['status']) ?>
                                    </span>
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 border border-gray-300">
                                        <i data-lucide="tag" class="w-3 h-3"></i>
                                        <?= ucfirst(str_replace('_', ' ', $violation['reported_type'])) ?>
                                    </span>
                                </div>

                                <!-- Reason Title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-start gap-2">
                                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-500 flex-shrink-0 mt-1"></i>
                                    <span><?= esc($violation['reason']) ?></span>
                                </h3>

                                <!-- Report Details -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-4 space-y-2">
                                    <div class="flex items-center gap-2 text-sm">
                                        <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                                        <span class="font-medium text-gray-700">Reported by:</span>
                                        <span class="text-gray-900"><?= esc($violation['reporter_name']) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm">
                                        <i data-lucide="calendar" class="w-4 h-4 text-gray-500"></i>
                                        <span class="font-medium text-gray-700">Reported on:</span>
                                        <span class="text-gray-900"><?= date('M d, Y H:i', strtotime($violation['created_at'])) ?></span>
                                    </div>
                                    <?php if ($violation['reviewed_at']): ?>
                                        <div class="flex items-center gap-2 text-sm">
                                            <i data-lucide="check" class="w-4 h-4 text-gray-500"></i>
                                            <span class="font-medium text-gray-700">Reviewed by:</span>
                                            <span class="text-gray-900"><?= esc($violation['reviewer_name']) ?> on <?= date('M d, Y H:i', strtotime($violation['reviewed_at'])) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Item Status Notice -->
                                <?php if (!$violation['item_exists']): ?>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                                        <div class="flex items-center gap-2 text-red-700">
                                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                            <span class="font-semibold text-sm">Reported item has been deleted</span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- View Item Button -->
                                <button type="button" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-hover transition-all duration-200 shadow-md hover:shadow-lg" data-bs-toggle="modal" data-bs-target="#violationModal<?= $violation['id'] ?>">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    View Reported Item Details
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="violationModal<?= $violation['id'] ?>" tabindex="-1" aria-labelledby="violationModalLabel<?= $violation['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-gray-50">
                                                <h5 class="modal-title font-bold text-lg" id="violationModalLabel<?= $violation['id'] ?>">
                                                    <i data-lucide="file-text" class="w-5 h-5 inline mr-2"></i>
                                                    Reported Item Details
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Reported Item Information</h6>
                                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                                                        <p class="text-sm"><strong>Type:</strong> <?= ucfirst(str_replace('_', ' ', $violation['reported_type'])) ?></p>
                                                        <p class="text-sm"><strong>ID:</strong> <?= $violation['reported_id'] ?></p>
                                                        <p class="text-sm"><strong>Status:</strong>
                                                            <?php if ($violation['item_exists']): ?>
                                                                <span class="text-green-600 font-semibold">Item exists</span>
                                                            <?php else: ?>
                                                                <span class="text-red-600 font-semibold">Item deleted</span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <?php if ($violation['item_exists']): ?>
                                                <div class="border-t pt-4">
                                                    <h6 class="text-lg font-semibold text-gray-900 mb-3">Item Content</h6>
                                                    <?php if ($violation['reported_type'] === 'forum_post'): ?>
                                                        <?php $post = $violation['reported_item'] ?? null; ?>
                                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                                                            <h6 class="font-medium text-gray-900 mb-2">Forum Post</h6>
                                                            <p class="text-gray-700 mb-3"><?= esc($post['content'] ?? 'No content available') ?></p>
                                                        </div>
                                                    <?php elseif ($violation['reported_type'] === 'product'): ?>
                                                        <?php $product = $violation['reported_item'] ?? null; ?>
                                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                                                            <h6 class="font-medium text-gray-900 mb-2">Product</h6>
                                                            <p class="text-lg font-semibold text-gray-900 mb-2"><?= esc($product['name'] ?? 'No product info available') ?></p>
                                                            <?php if (!empty($product['images'])): ?>
                                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-3">
                                                                    <?php foreach($product['images'] as $image): ?>
                                                                        <img src="<?= esc($image) ?>" class="w-full h-32 object-cover rounded-lg border" alt="Product Image"/>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php elseif ($violation['reported_type'] === 'user'): ?>
                                                        <?php $user = $violation['reported_item'] ?? null; ?>
                                                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                                                            <h6 class="font-medium text-gray-900 mb-2">User Profile</h6>
                                                            <div class="space-y-2">
                                                                <p><strong class="text-gray-700">Name:</strong> <span class="text-gray-900"><?= esc($user['name'] ?? 'No user info') ?></span></p>
                                                                <p><strong class="text-gray-700">Email:</strong> <span class="text-gray-900"><?= esc($user['email'] ?? '') ?></span></p>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php else: ?>
                                                <div class="border-t pt-4">
                                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                                        <i data-lucide="trash-2" class="w-12 h-12 text-red-400 mx-auto mb-2"></i>
                                                        <p class="text-red-700 font-semibold">The reported item has already been deleted</p>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer bg-gray-50">
                                                <?php if ($violation['item_exists']): ?>
                                                    <form action="/admin/violations/<?= $violation['id'] ?>/delete" method="POST" class="me-auto swal-confirm-form" data-confirm="Are you sure you want to delete this reported item?">
                                                        <button type="submit" class="btn btn-danger">
                                                            <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                                                            Delete Item
                                                        </button>
                                                    </form>
                                                    <form action="/admin/violations/<?= $violation['id'] ?>/status" method="POST">
                                                        <input type="hidden" name="status" value="reviewed">
                                                        <button type="submit" class="btn btn-success">
                                                            <i data-lucide="check" class="w-4 h-4 inline mr-1"></i>
                                                            Keep Item
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <div class="me-auto text-sm text-gray-600">
                                                        <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                                                        Item already deleted
                                                    </div>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="lg:w-64 flex flex-col gap-3">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                        <i data-lucide="settings" class="w-4 h-4"></i>
                                        Quick Actions
                                    </h4>
                                    
                                    <?php if ($violation['status'] === 'pending'): ?>
                                        <form action="/admin/violations/<?= $violation['id'] ?>/status" method="POST" class="mb-2">
                                            <input type="hidden" name="status" value="reviewed">
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold shadow-sm">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                                Mark as Reviewed
                                            </button>
                                        </form>
                                        <form action="/admin/violations/<?= $violation['id'] ?>/status" method="POST" class="mb-2">
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold shadow-sm">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                Mark as Resolved
                                            </button>
                                        </form>
                                    <?php elseif ($violation['status'] === 'reviewed'): ?>
                                        <form action="/admin/violations/<?= $violation['id'] ?>/status" method="POST" class="mb-2">
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold shadow-sm">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                Mark as Resolved
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <div class="text-center py-2">
                                            <i data-lucide="check-circle-2" class="w-8 h-8 text-green-500 mx-auto mb-2"></i>
                                            <p class="text-sm text-gray-600 font-medium">Report Resolved</p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Delete Buttons -->
                                    <?php if ($violation['item_exists']): ?>
                                        <form action="/admin/violations/<?= $violation['id'] ?>/delete" method="POST" class="swal-confirm-form" data-confirm="Are you sure you want to delete this reported item? This action cannot be undone.">
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition-colors text-sm font-semibold">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                Delete Reported Item
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <div class="border-t border-gray-200 my-3"></div>
                                    
                                    <form action="/admin/violations/<?= $violation['id'] ?>/delete-report" method="POST" class="swal-confirm-form" data-confirm="Are you sure you want to delete this violation report? This will remove the report from the system.">
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors text-sm font-semibold">
                                            <i data-lucide="file-x" class="w-4 h-4"></i>
                                            Delete This Report
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>