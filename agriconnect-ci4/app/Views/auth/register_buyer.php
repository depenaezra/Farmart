<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center" style="background-image: url('<?= base_url('img/11.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-2xl mx-auto p-8" style="backdrop-filter: blur(2px); background-color: rgba(255, 255, 255, 0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <form id="registerForm" action="<?= base_url('auth/register-buyer') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="flex flex-col items-center mb-8">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="shopping-bag" class="w-8 h-8 text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Account</h1>
                    <p class="text-gray-600 mt-2">Fill in your details to get started</p>
                </div>

                <!-- Server-side error fallback (non-JS) -->
                <?php if (session('error')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                        <span class="text-red-700"><?= esc(session('error')) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (session('errors')): 
                    $errors = session('errors');
                    if (is_array($errors)): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <ul class="space-y-2">
                                <?php foreach ($errors as $err): ?>
                                    <li class="flex items-start gap-3">
                                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                                        <span class="text-red-700 text-sm"><?= esc($err) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;
                endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?= old('name') ?>"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="Maria Santos"
                        >
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?= old('phone') ?>"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="0917-123-4567"
                        >
                    </div>
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?= old('email') ?>"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="your.email@example.com"
                    >
                </div>

                <div class="mb-6">
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        value="<?= old('location') ?>"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="Brgy. Poblacion, Nasugbu"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                minlength="8"
                                class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                placeholder="Min. 8 characters"
                            >
                            <button type="button" id="togglePass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" tabindex="-1" style="background:none; border:none;">
                                <i data-lucide="eye" class="eye-icon-pass w-5 h-5"></i>
                                <i data-lucide="eye-off" class="eye-off-icon-pass w-5 h-5" style="display:none;"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                required
                                class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                placeholder="Confirm password"
                            >
                            <button type="button" id="toggleConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" tabindex="-1" style="background:none; border:none;">
                                <i data-lucide="eye" class="eye-icon-confirm w-5 h-5"></i>
                                <i data-lucide="eye-off" class="eye-off-icon-confirm w-5 h-5" style="display:none;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Requirements:</label>
                    <ul class="text-sm text-gray-600 space-y-1" id="passwordRequirements">
                        <li id="req-length"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>At least 8 characters</li>
                        <li id="req-upper"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>One uppercase letter (A-Z)</li>
                        <li id="req-lower"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>One lowercase letter (a-z)</li>
                        <li id="req-number"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>One number (0-9)</li>
                        <li id="req-special"><i data-lucide="circle" class="w-4 h-4 inline text-gray-300 mr-2"></i>One special character (@$!%*?&)</li>
                    </ul>
                </div>

                <button 
                    type="submit" 
                    id="registerBtn"
                    class="w-full bg-accent text-white py-3 rounded-lg font-semibold hover:bg-accent-hover transition-all duration-200 flex items-center justify-center shadow-md"
                >
                    <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                    <span id="btnText">Create Account</span>
                    <svg id="btnSpinner" class="animate-spin hidden ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Already have an account?
                </p>
                <div class="mt-2">
                    <a href="<?= base_url('auth/login') ?>" class="text-primary hover:text-primary-hover font-semibold transition-colors">
                        Login here
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggles
    const togglePass = document.getElementById('togglePass');
    const passInput = document.getElementById('password');
    const toggleConfirm = document.getElementById('toggleConfirm');
    const confirmInput = document.getElementById('confirm_password');

    if (togglePass && passInput) {
        togglePass.addEventListener('click', () => {
            const visible = passInput.type === 'text';
            passInput.type = visible ? 'password' : 'text';
            togglePass.querySelector('.eye-icon-pass').style.display = visible ? '' : 'none';
            togglePass.querySelector('.eye-off-icon-pass').style.display = visible ? 'none' : '';
        });
    }
    if (toggleConfirm && confirmInput) {
        toggleConfirm.addEventListener('click', () => {
            const visible = confirmInput.type === 'text';
            confirmInput.type = visible ? 'password' : 'text';
            toggleConfirm.querySelector('.eye-icon-confirm').style.display = visible ? '' : 'none';
            toggleConfirm.querySelector('.eye-off-icon-confirm').style.display = visible ? 'none' : '';
        });
    }

    // Password strength live feedback
    const passField = document.getElementById('password');
    const reqs = {
        length: document.getElementById('req-length'),
        upper: document.getElementById('req-upper'),
        lower: document.getElementById('req-lower'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special')
    };

    if (passField) {
        passField.addEventListener('input', function() {
            const val = this.value;
            const hasLength = val.length >= 8;
            const hasUpper = /[A-Z]/.test(val);
            const hasLower = /[a-z]/.test(val);
            const hasNumber = /\d/.test(val);
            const hasSpecial = /[@$!%*?&]/.test(val);

            updateRequirement(reqs.length, hasLength);
            updateRequirement(reqs.upper, hasUpper);
            updateRequirement(reqs.lower, hasLower);
            updateRequirement(reqs.number, hasNumber);
            updateRequirement(reqs.special, hasSpecial);
        });

        function updateRequirement(el, met) {
            if (!el) return;
            const icon = el.querySelector('i');
            if (met) {
                icon.classList.remove('text-gray-300');
                icon.classList.add('text-green-500');
                icon.setAttribute('data-lucide', 'check-circle-2');
            } else {
                icon.classList.remove('text-green-500');
                icon.classList.add('text-gray-300');
                icon.setAttribute('data-lucide', 'circle');
            }
            lucide.createIcons();
        }
    }

    // AJAX form submission
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('registerBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            submitBtn.disabled = true;
            btnText.textContent = 'Creating account...';
            btnSpinner.classList.remove('hidden');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
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
                        window.location.href = data.redirect;
                    });
                } else {
                    let errorMsg = data.message || 'Registration failed.';
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
                    title: 'Connection Error',
                    text: 'Please check your connection and try again.',
                    confirmButtonColor: '#16a34a'
                });
            } finally {
                submitBtn.disabled = false;
                btnText.textContent = 'Create Account';
                btnSpinner.classList.add('hidden');
            }
        });
    }

    // Focus first field
    document.getElementById('name')?.focus();
});
</script>

<?= $this->endSection() ?>
