<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50" style="background-image: url('<?= base_url('img/11.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255,255,255,0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="mail-check" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Verify Your Email</h1>
                <p class="text-gray-600 mt-2">Enter the 6-digit code sent to:</p>
                <p class="text-sm font-semibold text-gray-800 mt-1"><?= esc($email ?? '') ?></p>
            </div>

            <!-- Server-side error fallback (non-JS) -->
            <?php if (session('error')): ?>
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                    <span class="text-red-700"><?= esc(session('error')) ?></span>
                </div>
            <?php endif; ?>

            <!-- OTP Verification Form -->
            <form id="verifyForm" action="<?= base_url('auth/register-verify') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-6">
                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2">Verification Code</label>
                    <input 
                        type="text" 
                        id="otp" 
                        name="otp" 
                        required
                        maxlength="6"
                        pattern="[0-9]{6}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-center text-2xl tracking-[0.25em]"
                        placeholder="000000"
                        autocomplete="one-time-code"
                    >
                </div>
                <button 
                    type="submit" 
                    id="verifyBtn"
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-all duration-200 flex items-center justify-center shadow-md"
                >
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    <span id="btnText">Verify and Create Account</span>
                    <svg id="btnSpinner" class="animate-spin hidden ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <!-- Resend OTP -->
            <form id="resendForm" class="mt-4">
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

            <div class="mt-6 text-center">
                <a href="<?= base_url('auth/register-buyer') ?>" class="text-sm text-primary hover:underline font-medium">Back to registration</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const verifyForm = document.getElementById('verifyForm');
    const verifyBtn = document.getElementById('verifyBtn');
    const verifyBtnText = document.getElementById('btnText');
    const verifyBtnSpinner = document.getElementById('btnSpinner');
    const resendBtn = document.getElementById('resendBtn');
    const otpInput = document.getElementById('otp');

    // Verify OTP
    verifyForm?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const otp = otpInput.value.trim();

        if (!otp || otp.length !== 6) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Code',
                text: 'Please enter the 6-digit verification code.',
                confirmButtonColor: '#16a34a'
            });
            return;
        }

        verifyBtn.disabled = true;
        verifyBtnText.textContent = 'Verifying...';
        verifyBtnSpinner.classList.remove('hidden');

        try {
            const response = await fetch(verifyForm.action, {
                method: 'POST',
                body: new FormData(verifyForm),
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
            verifyBtn.disabled = false;
            verifyBtnText.textContent = 'Verify and Create Account';
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
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: data.message,
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

    // Auto-focus OTP
    otpInput?.focus();
});
</script>

<?= $this->endSection() ?>
