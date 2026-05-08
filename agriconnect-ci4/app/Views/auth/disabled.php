<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center" style="background-image: url('<?= base_url('img/4.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255, 255, 255, 0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="user-x" class="w-10 h-10 text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Account Disabled</h1>
            <p class="text-gray-600 mb-8">Your account has been disabled or blocked. Please contact support for assistance.</p>
            <a href="<?= base_url('contact') ?>" class="inline-block px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-primary-hover transition-colors shadow-md">
                <i data-lucide="help-circle" class="w-5 h-5 inline mr-2"></i>
                Contact Support
            </a>
            <div class="mt-6 pt-6 border-t border-gray-200">
                <a href="<?= base_url('auth/login') ?>" class="text-primary hover:underline font-medium">
                    <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Focus the support button
    document.querySelector('a[href*="contact"]')?.focus();
});
</script>

<?= $this->endSection() ?>
