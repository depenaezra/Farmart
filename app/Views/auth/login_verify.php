<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-10 max-w-md">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-primary rounded-full flex items-center justify-center mx-auto mb-3">
                <i data-lucide="shield-check" class="w-7 h-7 text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Verify Your Identity</h1>
            <p class="text-gray-600 mt-2">We've sent a 6-digit verification code to:<br><strong><?= esc($email) ?></strong></p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <form method="post" action="/auth/login-verify" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2">Enter Verification Code</label>
                <input
                    type="text"
                    id="otp"
                    name="otp"
                    required
                    maxlength="6"
                    pattern="[0-9]{6}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-center tracking-[0.25em] text-lg"
                    placeholder="000000"
                    autocomplete="off"
                    autofocus
                >
                <p class="text-xs text-gray-500 mt-1 text-center">Check your email for the 6-digit code. It expires in 10 minutes.</p>
            </div>

            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors">
                Verify & Login
            </button>
        </form>

        <form method="post" action="/auth/resend-login-otp" class="mt-3">
            <?= csrf_field() ?>
            <button type="submit" class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                Resend Code
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="/auth/logout" class="text-sm text-gray-600 hover:text-primary">
                Cancel Login
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>