<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center" style="background-image: url('<?= base_url('img/4.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255, 255, 255, 0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Account Disabled</h1>
                <p class="text-gray-600 mt-2">Sorry, you can't access Farmart at the moment</p>
            </div>
            <div class="mt-6">
                <a href="<?= base_url('/auth/login') ?>" class="block text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>