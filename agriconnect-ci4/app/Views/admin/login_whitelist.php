<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Login Whitelist</h1>
        <p class="text-gray-600">
            When whitelist mode is <strong>enabled</strong>, only users with the <strong>admin</strong> role and email addresses you add below can sign in.
            Everyone else will see an error at login until you disable whitelist mode or add their email.
        </p>
    </div>

    <?php if (session('error')): ?>
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
            <p><?= esc(session('error')) ?></p>
        </div>
    <?php endif; ?>
    <?php if (session('success')): ?>
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg" role="alert">
            <p><?= esc(session('success')) ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Whitelist mode</h2>
        <p class="text-sm text-gray-600 mb-4">
            Current status:
            <span class="font-semibold <?= $enabled ? 'text-amber-700' : 'text-green-700' ?>">
                <?= $enabled ? 'Enabled (restricted logins)' : 'Disabled (normal logins)' ?>
            </span>
        </p>

        <div class="flex flex-wrap gap-4">
            <?php if (! $enabled): ?>
                <form method="post" action="<?= base_url('admin/login-whitelist/toggle') ?>"
                      class="swal-confirm-form"
                      data-confirm="Are you sure you want to enable login whitelisting? Only administrators and emails on the list below will be able to sign in until you turn this off.">
                    <?= csrf_field() ?>
                    <input type="hidden" name="enable" value="1">
                    <button type="submit" class="px-5 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                        Enable whitelist
                    </button>
                </form>
            <?php else: ?>
                <form method="post" action="<?= base_url('admin/login-whitelist/toggle') ?>"
                      class="swal-confirm-form"
                      data-confirm="Are you sure you want to disable login whitelisting? All active users will be able to sign in again (subject to their account status).">
                    <?= csrf_field() ?>
                    <input type="hidden" name="enable" value="0">
                    <button type="submit" class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        Disable whitelist
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-2">Add email</h2>
        <p class="text-sm text-gray-600 mb-4">Whitelisted addresses can always sign in while whitelist mode is on (admins never need to be listed).</p>
        <form method="post" action="<?= base_url('admin/login-whitelist/add-email') ?>"
              class="flex flex-col sm:flex-row gap-3 sm:items-end swal-confirm-form"
              data-confirm="Add this email to the login whitelist?">
            <?= csrf_field() ?>
            <div class="flex-1">
                <label for="whitelist_email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input type="email" name="email" id="whitelist_email" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="user@example.com">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors font-medium whitespace-nowrap">
                Add to whitelist
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Whitelisted emails</h2>
        <?php if (empty($emails)): ?>
            <p class="text-gray-500 text-sm">No emails added yet. While whitelist mode is enabled with an empty list, only administrators can sign in.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-600">
                            <th class="py-2 pr-4">Email</th>
                            <th class="py-2 pr-4">Added</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emails as $row): ?>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 pr-4 font-medium text-gray-900"><?= esc($row['email']) ?></td>
                                <td class="py-3 pr-4 text-gray-600"><?= esc($row['created_at'] ?? '') ?></td>
                                <td class="py-3 text-right">
                                    <form method="post" action="<?= base_url('admin/login-whitelist/remove/' . (int) $row['id']) ?>"
                                          class="inline swal-confirm-form"
                                          data-confirm="Remove this email from the whitelist? They will not be able to sign in while whitelist mode stays enabled (unless they are an admin).">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            Remove
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
