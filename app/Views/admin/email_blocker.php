<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Email Blocker</h1>
        <p class="text-gray-600">Block emails from creating accounts and disable existing accounts</p>
    </div>

    <!-- Add Blocked Email Form -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Block New Email</h2>
        <form method="post" action="/admin/email-blocker/block" class="swal-confirm-form" data-confirm-title="Block this email?" data-confirm="That address will not be able to register a new account." data-confirm-ok="Block email" data-confirm-icon="warning" data-submit-loading data-loading-label="Blocking…">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter email to block">
                </div>
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason (Optional)</label>
                    <input type="text" id="reason" name="reason"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Reason for blocking">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i data-lucide="shield-x" class="w-4 h-4 inline mr-2"></i>
                    Block Email
                </button>
            </div>
        </form>
    </div>

    <!-- Blocked Emails List -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Blocked Emails</h2>
        </div>

        <?php if (empty($blockedEmails)): ?>
            <div class="text-center py-12">
                <i data-lucide="mail" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No blocked emails</h3>
                <p class="text-gray-600">Emails blocked from registration will appear here.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blocked By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blocked At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($blockedEmails as $blocked): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= esc($blocked['email']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?= esc($blocked['reason'] ?: 'No reason provided') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?= esc($blocked['blocked_by_name']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M d, Y H:i', strtotime($blocked['blocked_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form method="post" action="/admin/email-blocker/unblock/<?= $blocked['id'] ?>" class="swal-confirm-form" data-confirm-title="Unblock this email?" data-confirm="They will be allowed to register again if they try." data-confirm-ok="Unblock" data-confirm-icon="question">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 text-sm font-medium rounded-lg hover:bg-green-200 transition-colors">
                                            <i data-lucide="shield-check" class="w-4 h-4 mr-2"></i>
                                            Unblock
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>