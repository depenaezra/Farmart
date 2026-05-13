<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50" style="background-image: url('<?= base_url('img/11.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255,255,255,0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Two-Factor Authentication</h1>
                <p class="text-gray-600 mt-2">Enter the 6-digit code from your authenticator app</p>
            </div>

            <?php if (session('error')): ?>
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                    <span class="text-red-700"><?= esc(session('error')) ?></span>
                </div>
            <?php endif; ?>

            <form id="verify2faForm" action="<?= base_url('auth/verify-2fa') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-6">
                    <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Verification Code</label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        required
                        maxlength="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-center text-2xl tracking-[0.25em]"
                        placeholder="000000"
                        autocomplete="one-time-code"
                    >
                    <p class="mt-2 text-sm text-gray-500">
                        Lost your device? Use a <a href="#" id="showBackupLink" class="text-primary hover:underline font-medium">backup code</a>.
                    </p>
                </div>

                <button 
                    type="submit" 
                    id="verifyBtn"
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-all duration-200 flex items-center justify-center shadow-md"
                >
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    <span id="btnText">Verify</span>
                    <svg id="btnSpinner" class="animate-spin hidden ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">
                    <a href="<?= base_url('auth/logout') ?>" class="text-primary hover:underline">
                        Cancel and log out
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verify2faForm');
    const verifyBtn = document.getElementById('verifyBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const codeInput = document.getElementById('code');
    const showBackupLink = document.getElementById('showBackupLink');

    // Show backup code hint (not full UI yet)
    showBackupLink?.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'info',
            title: 'Backup Code',
            text: 'Enter one of your 8-character backup codes instead of the 6-digit TOTP.',
            confirmButtonColor: '#166534'
        });
    });

    form?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const code = codeInput.value.trim();

        if (!code) {
            Swal.fire({
                icon: 'error',
                title: 'Required',
                text: 'Please enter a verification code.',
                confirmButtonColor: '#166534'
            });
            return;
        }

        verifyBtn.disabled = true;
        btnText.textContent = 'Verifying...';
        btnSpinner.classList.remove('hidden');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
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
                    window.location.href = data.redirect || '/';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: data.message,
                    confirmButtonColor: '#166534'
                });
                codeInput.value = '';
                codeInput.focus();
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Network error. Please try again.',
                confirmButtonColor: '#166534'
            });
        } finally {
            verifyBtn.disabled = false;
            btnText.textContent = 'Verify';
            btnSpinner.classList.add('hidden');
        }
    });

    codeInput?.focus();
});
</script>

<?= $this->endSection() ?>
