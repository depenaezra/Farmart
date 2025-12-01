<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50" style="background-image: url('<?= base_url('img/11.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255,255,255,0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="lock" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Set New Password</h1>
                <p class="text-gray-600 mt-2">Enter your new password below</p>
            </div>
            <form method="post" action="<?= base_url('auth/changePasswordProcess') ?>">
                <div class="mb-6">
                    <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                    <input type="password" id="new_password" name="new_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter new password">
                </div>
                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Confirm new password">
                </div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center">
                    <i data-lucide="lock" class="w-5 h-5 mr-2"></i>
                    Change Password
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
