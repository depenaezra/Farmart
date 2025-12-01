<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50" style="background-image: url('<?= base_url('img/11.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255, 255, 255, 0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="key-round" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Reset Password</h1>
                <p class="text-gray-600 mt-2">Enter your email to receive an OTP code</p>
            </div>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">OTP sent to your email.</div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">OTP could not be sent: <?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (!session()->getFlashdata('otp_email_sent')): ?>
            <form method="post" action="<?= base_url('auth/sendOtp') ?>">
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="your.email@example.com">
                    <button type="submit" class="mt-3 w-full bg-primary text-white py-2 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center">
                        <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                        Send OTP
                    </button>
                </div>
            </form>
            <?php else: ?>
            <form method="post" action="<?= base_url('auth/verifyOtp') ?>">
                <div class="mb-6">
                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2 mt-4">OTP Code</label>
                    <input type="text" id="otp" name="otp" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter OTP">
                </div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center">
                    <i data-lucide="key-round" class="w-5 h-5 mr-2"></i>
                    Verify OTP
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
