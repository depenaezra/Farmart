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
                <p class="text-gray-600 mt-2">Enter your new secure password</p>
                </div>

                <!-- Server-side error fallback (non-JS) -->
                <?php if (session('error')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                        <span class="text-red-700"><?= esc(session('error')) ?></span>
                    </div>
                <?php endif; ?>

                <form id="changePasswordForm" action="<?= base_url('auth/changePasswordProcess') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-6">
                    <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            required
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Enter new password"
                            autocomplete="new-password"
                        >
                        <button type="button" id="toggleNewPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" tabindex="-1" style="background:none; border:none;" aria-label="Toggle password">
                            <i data-lucide="eye" class="w-5 h-5 eye-icon-new"></i>
                            <i data-lucide="eye-off" class="w-5 h-5 eye-off-icon-new" style="display:none;"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock-check" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            required
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Confirm new password"
                            autocomplete="new-password"
                        >
                        <button type="button" id="toggleConfirmPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" tabindex="-1" style="background:none; border:none;" aria-label="Toggle password">
                            <i data-lucide="eye" class="w-5 h-5 eye-icon-confirm"></i>
                            <i data-lucide="eye-off" class="w-5 h-5 eye-off-icon-confirm" style="display:none;"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="block text-sm font-semibold text-gray-700 mb-2">Password requirements</p>
                    <ul class="text-sm text-gray-600 space-y-1" id="passwordRequirements">
                        <li id="req-length"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>At least 8 characters</li>
                        <li id="req-upper"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>One uppercase letter (A-Z)</li>
                        <li id="req-number"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>One number (0-9)</li>
                        <li id="req-special"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>One special character (not a letter or digit)</li>
                    </ul>
                </div>

                <button 
                    type="submit" 
                    id="submitBtn"
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-all duration-200 flex items-center justify-center shadow-md"
                >
                    <i data-lucide="lock" class="w-5 h-5 mr-2"></i>
                    <span id="btnText">Change Password</span>
                    <svg id="btnSpinner" class="animate-spin hidden ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleNewPass = document.getElementById('toggleNewPass');
    const newPassInput = document.getElementById('new_password');
    const toggleConfirmPass = document.getElementById('toggleConfirmPass');
    const confirmPassInput = document.getElementById('confirm_password');

    if (toggleNewPass && newPassInput) {
        toggleNewPass.addEventListener('click', () => {
            const isVisible = newPassInput.type === 'text';
            newPassInput.type = isVisible ? 'password' : 'text';
            toggleNewPass.querySelector('.eye-icon-new').style.display = isVisible ? '' : 'none';
            toggleNewPass.querySelector('.eye-off-icon-new').style.display = isVisible ? 'none' : '';
        });
    }
    if (toggleConfirmPass && confirmPassInput) {
        toggleConfirmPass.addEventListener('click', () => {
            const isVisible = confirmPassInput.type === 'text';
            confirmPassInput.type = isVisible ? 'password' : 'text';
            toggleConfirmPass.querySelector('.eye-icon-confirm').style.display = isVisible ? '' : 'none';
            toggleConfirmPass.querySelector('.eye-off-icon-confirm').style.display = isVisible ? 'none' : '';
        });
    }

    function getPasswordStrengthMessage(val) {
        if (val.length < 8) return 'Password must be at least 8 characters.';
        if (!/[A-Z]/.test(val)) return 'Password must include at least one uppercase letter (A-Z).';
        if (!/\d/.test(val)) return 'Password must include at least one number (0-9).';
        if (!/[^A-Za-z0-9]/.test(val)) return 'Password must include at least one special character (for example !@#$%).';
        return '';
    }

    const reqs = {
        length: document.getElementById('req-length'),
        upper: document.getElementById('req-upper'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special'),
    };
    if (newPassInput) {
        newPassInput.addEventListener('input', function() {
            const val = this.value;
            function upd(el, ok) {
                if (!el) return;
                const icon = el.querySelector('i');
                if (!icon) return;
                if (ok) {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-green-500');
                    icon.setAttribute('data-lucide', 'check-circle-2');
                } else {
                    icon.classList.remove('text-green-500');
                    icon.classList.add('text-gray-300');
                    icon.setAttribute('data-lucide', 'circle');
                }
                if (window.lucide) lucide.createIcons();
            }
            upd(reqs.length, val.length >= 8);
            upd(reqs.upper, /[A-Z]/.test(val));
            upd(reqs.number, /\d/.test(val));
            upd(reqs.special, /[^A-Za-z0-9]/.test(val));
        });
    }

    // AJAX form submission
    const form = document.getElementById('changePasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const newPass = newPassInput.value;
            const confirmPass = confirmPassInput.value;

            const pwMsg = getPasswordStrengthMessage(newPass);
            if (pwMsg) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Password requirements not met',
                    text: pwMsg,
                    confirmButtonColor: '#16a34a',
                    showClass: { popup: 'animate__animated animate__shakeX' }
                });
                return;
            }

            if (newPass !== confirmPass) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Passwords do not match',
                    text: 'Please enter the same password in both fields.',
                    confirmButtonColor: '#16a34a'
                });
                return;
            }

            submitBtn.disabled = true;
            btnText.textContent = 'Updating...';
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
                        title: 'Success!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        showClass: { popup: 'animate__animated animate__fadeIn' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Could not update password',
                        text: data.message || 'Please check your input and try again.',
                        confirmButtonColor: '#16a34a'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Please try again later.',
                    confirmButtonColor: '#16a34a'
                });
            } finally {
                submitBtn.disabled = false;
                btnText.textContent = 'Change Password';
                btnSpinner.classList.add('hidden');
            }
        });
    }

    newPassInput?.focus();
});
</script>

<?= $this->endSection() ?>
