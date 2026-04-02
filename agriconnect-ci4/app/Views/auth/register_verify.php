<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50" style="background-image: url('<?= base_url('img/11.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255, 255, 255, 0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="mail-check" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Verify Your Email</h1>
                <p class="text-gray-600 mt-2">Enter the 6-digit code sent to:</p>
                <p class="text-sm font-semibold text-gray-800 mt-1"><?= esc($email ?? '') ?></p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('auth/register-verify') ?>" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2">Verification Code</label>
                    <input type="text" id="otp" name="otp" required maxlength="6" pattern="[0-9]{6}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-center tracking-[0.25em]" placeholder="000000">
                </div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    Verify and Create Account
                </button>
            </form>

            <form method="post" action="<?= base_url('auth/register-resend-otp') ?>" class="mt-3">
                <?= csrf_field() ?>
                <button type="submit" class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center">
                    <i data-lucide="refresh-cw" class="w-5 h-5 mr-2"></i>
                    Resend Code
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/auth/register-buyer" class="text-sm text-primary hover:underline font-medium">Back to registration</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
