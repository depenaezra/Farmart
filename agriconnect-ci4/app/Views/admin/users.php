<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">User Management</h1>
        <p class="text-gray-600">Manage all users in the system</p>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
        <form method="get" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Users</label>
                <input type="text" id="search" name="search" value="<?= esc($search_term ?? '') ?>"
                       placeholder="Search by name, email, or location..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            <div class="md:w-48">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Filter by Role</label>
                <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">All Roles</option>
                    <option value="admin" <?= ($current_role ?? '') === 'admin' ? 'selected' : '' ?>>Admins</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                    <i data-lucide="search" class="w-4 h-4 inline mr-2"></i>
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">


        <?php if (empty($users)): ?>
            <div class="text-center py-12">
                <i data-lucide="users" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                <p class="text-gray-600">Try adjusting your search criteria.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="/admin/users/<?= $user['id'] ?>" class="text-primary hover:text-primary-hover">
                                                    <?= esc($user['name']) ?>
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500"><?= esc($user['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        <?php
                                        switch($user['role']) {
                                            case 'admin': echo 'bg-purple-100 text-purple-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php $suspendedUntilTs = !empty($user['login_suspended_until']) ? strtotime((string) $user['login_suspended_until']) : false; ?>
                                    <?php $hasActiveSuspension = $suspendedUntilTs !== false && $suspendedUntilTs > time(); ?>
                                    <?php $isManualSuspension = $hasActiveSuspension && $suspendedUntilTs >= strtotime('2099-01-01 00:00:00'); ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                    <?php if ($hasActiveSuspension): ?>
                                        <div class="text-xs text-amber-700 mt-1">
                                            <?= $isManualSuspension ? 'Suspended until manually unsuspended' : 'Suspended until ' . date('M d, Y h:i A', $suspendedUntilTs) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= esc($user['location'] ?: 'Not specified') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($user['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="/admin/users/<?= $user['id'] ?>" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-hover transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                            View
                                        </a>

                                        <?php if ((int) $user['id'] !== (int) session()->get('user_id')): ?>
                                            <?php
                                            $isActiveRow = $user['status'] === 'active';
                                            $toggleTitle = $isActiveRow ? 'Disable login for this user?' : 'Enable login for this user?';
                                            $toggleBody = $isActiveRow
                                                ? 'They will not be able to sign in until you turn access back on.'
                                                : 'They can sign in again if the account is otherwise in good standing.';
                                            $toggleOk = $isActiveRow ? 'Disable login' : 'Enable login';
                                            ?>
                                            <form method="post" action="/admin/users/<?= $user['id'] ?>/toggle-status" class="swal-confirm-form" data-confirm-title="<?= esc($toggleTitle, 'attr') ?>" data-confirm="<?= esc($toggleBody, 'attr') ?>" data-confirm-ok="<?= esc($toggleOk, 'attr') ?>" data-confirm-icon="question"<?= $isActiveRow ? ' data-confirm-danger' : '' ?>>
                                                <?= csrf_field() ?>
                                                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors <?= $user['status'] === 'active' ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?>">
                                                    <i data-lucide="<?= $user['status'] === 'active' ? 'user-x' : 'user-check' ?>" class="w-4 h-4 mr-2"></i>
                                                    <?= $user['status'] === 'active' ? 'Disable Login' : 'Enable Login' ?>
                                                </button>
                                            </form>

                                            <?php if ($hasActiveSuspension): ?>
                                                <form method="post" action="/admin/users/<?= $user['id'] ?>/clear-suspension" class="swal-confirm-form" data-confirm-title="Clear login suspension?" data-confirm="They can try to sign in again immediately if login is enabled for the account." data-confirm-ok="Clear suspension" data-confirm-icon="question">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors">
                                                        <i data-lucide="shield-check" class="w-4 h-4 mr-2"></i>
                                                        Unsuspend
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form method="post" action="/admin/users/<?= $user['id'] ?>/suspend-login" class="swal-confirm-form" data-confirm-title="Suspend sign-in?" data-confirm="Uses the duration selected in this row. The user cannot log in until it ends or you clear it." data-confirm-ok="Apply suspension" data-confirm-danger data-confirm-icon="warning">
                                                    <?= csrf_field() ?>
                                                    <select name="suspend_duration" class="px-3 py-2 text-sm border border-amber-200 bg-amber-50 text-amber-900 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                                        <option value="1h">Suspend 1hr</option>
                                                        <option value="6h">Suspend 6hr</option>
                                                        <option value="12h">Suspend 12hr</option>
                                                        <option value="until_enabled">Until I turn it back on</option>
                                                    </select>
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors">
                                                        <i data-lucide="shield-alert" class="w-4 h-4 mr-2"></i>
                                                        Suspend
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
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