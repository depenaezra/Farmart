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

            <!-- Server-side error fallback (non-JS) -->
            <?php if (session('error')): ?>
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                    <span class="text-red-700"><?= esc(session('error')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Step 1: Email Form -->
            <form id="sendOtpForm" action="<?= base_url('auth/sendOtp') ?>" method="POST" class="<?= session()->getFlashdata('otp_email_sent') ? 'hidden' : '' ?>">
                <?= csrf_field() ?>
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="your.email@example.com"
                            autocomplete="email"
                        >
                    </div>
                </div>
                <button 
                    type="submit" 
                    id="sendOtpBtn"
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-all duration-200 flex items-center justify-center shadow-md"
                >
                    <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                    <span id="sendBtnText">Send OTP</span>
                    <svg id="sendBtnSpinner" class="animate-spin hidden ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <!-- Step 2: OTP Form (hidden initially) -->
            <form id="verifyOtpForm" action="<?= base_url('auth/verifyOtp') ?>" method="POST" class="hidden">
                <?= csrf_field() ?>
                <div class="mb-6">
                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2 mt-4">Enter 6-digit Code</label>
                    <input 
                        type="text" 
                        id="otp" 
                        name="otp" 
                        required
                        maxlength="6"
                        pattern="\d{6}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-center text-2xl tracking-[0.25em]"
                        placeholder="000000"
                        autocomplete="one-time-code"
                    >
                </div>
                <button 
                    type="submit" 
                    id="verifyOtpBtn"
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-all duration-200 flex items-center justify-center shadow-md"
                >
                    <i data-lucide="key-round" class="w-5 h-5 mr-2"></i>
                    <span id="verifyBtnText">Verify OTP</span>
                    <svg id="verifyBtnSpinner" class="animate-spin hidden ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <!-- Resend OTP button (only shown when OTP form is visible) -->
            <form id="resendForm" class="mt-4 hidden">
                <?= csrf_field() ?>
                <button 
                    type="button" 
                    id="resendBtn"
                    class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center"
                >
                    <i data-lucide="refresh-cw" class="w-5 h-5 mr-2"></i>
                    Resend Code
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const sendOtpForm = document.getElementById('sendOtpForm');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const sendBtnText = document.getElementById('sendBtnText');
    const sendBtnSpinner = document.getElementById('sendBtnSpinner');
    const verifyOtpForm = document.getElementById('verifyOtpForm');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const verifyBtnText = document.getElementById('verifyBtnText');
    const verifyBtnSpinner = document.getElementById('verifyBtnSpinner');
    const resendBtn = document.getElementById('resendBtn');

    // Send OTP via AJAX
    sendOtpForm?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = emailInput.value.trim();
        if (!email || !email.includes('@')) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: 'Please enter a valid email address.',
                confirmButtonColor: '#16a34a'
            });
            return;
        }

        sendOtpBtn.disabled = true;
        sendBtnText.textContent = 'Sending...';
        sendBtnSpinner.classList.remove('hidden');

        try {
            const response = await fetch(sendOtpForm.action, {
                method: 'POST',
                body: new FormData(sendOtpForm),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Sent!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                    showClass: { popup: 'animate__animated animate__fadeIn' },
                    hideClass: { popup: 'animate__animated animate__fadeOut' }
                }).then(() => {
                    sendOtpForm.classList.add('hidden');
                    verifyOtpForm.classList.remove('hidden');
                    document.getElementById('resendForm').classList.remove('hidden');
                    document.getElementById('otp').focus();
                });
            } else {
                let errorMsg = data.message || 'Failed to send OTP.';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMsg = errors.join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                    confirmButtonColor: '#16a34a'
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Please check your connection and try again.',
                confirmButtonColor: '#16a34a'
            });
        } finally {
            sendOtpBtn.disabled = false;
            sendBtnText.textContent = 'Send OTP';
            sendBtnSpinner.classList.add('hidden');
        }
    });

    // Verify OTP
    verifyOtpForm?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const otp = document.getElementById('otp').value.trim();
        if (!otp || otp.length !== 6 || !/^\d+$/.test(otp)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid OTP',
                text: 'Please enter a valid 6-digit code.',
                confirmButtonColor: '#16a34a'
            });
            return;
        }

        verifyOtpBtn.disabled = true;
        verifyBtnText.textContent = 'Verifying...';
        verifyBtnSpinner.classList.remove('hidden');

        try {
            const response = await fetch(verifyOtpForm.action, {
                method: 'POST',
                body: new FormData(verifyOtpForm),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Verified!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                let errorMsg = data.message || 'Verification failed.';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMsg = errors.join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: errorMsg,
                    confirmButtonColor: '#16a34a'
                });
                document.getElementById('otp').value = '';
                document.getElementById('otp').focus();
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Network error. Please try again.',
                confirmButtonColor: '#16a34a'
            });
        } finally {
            verifyOtpBtn.disabled = false;
            verifyBtnText.textContent = 'Verify OTP';
            verifyBtnSpinner.classList.add('hidden');
        }
    });

    // Resend OTP
    resendBtn?.addEventListener('click', async function() {
        if (!confirm('Send a new verification code to your email?')) return;

        resendBtn.disabled = true;
        resendBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        try {
            const response = await fetch('<?= base_url('auth/resendRegistrationOtp') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Sent!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                let errorMsg = data.message || 'Failed to resend.';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMsg = errors.join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                    confirmButtonColor: '#16a34a'
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not resend OTP.',
                confirmButtonColor: '#16a34a'
            });
        } finally {
            resendBtn.disabled = false;
            resendBtn.innerHTML = '<i data-lucide="refresh-cw" class="w-5 h-5 mr-2"></i> Resend Code';
            lucide.createIcons();
        }
    });

    // Focus email initially
    emailInput?.focus();
});
</script>

<?= $this->endSection() ?>
