<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center" style="background-image: url('<?= base_url('img/4.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255, 255, 255, 0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-success rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="log-in" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Welcome Back</h1>
                <p class="text-gray-600 mt-2">Login to access your account</p>
            </div>

            <!-- Lockout countdown timer (hidden by default) -->
            <div id="lockoutCountdown" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 animate__animated animate__pulse" style="display: none;">
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="clock" class="w-5 h-5 text-red-600"></i>
                    <span class="text-red-700 font-semibold">Account Temporarily Locked</span>
                </div>
                <p class="text-red-600">
                    Too many failed attempts. Try again in 
                    <span id="countdownTimer" class="font-bold text-lg text-red-800">5:00</span>.
                </p>
                <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                    <div id="countdownProgress" class="bg-red-500 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>

            <!-- Server-side error fallback (for non-JS or AJAX errors) -->
            <?php if (session('error')): ?>
                <div id="serverError" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                    <span class="text-red-700"><?= esc(session('error')) ?></span>
                </div>
            <?php endif; ?>

            <!-- AJAX inline message container -->
            <div id="ajaxMessage" class="mb-6 hidden">
                <div class="flex items-start gap-3 p-4 rounded-lg border">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <span class="text-sm"></span>
                </div>
            </div>

            <form id="loginForm" action="<?= base_url('auth/login') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= old('email') ?>"
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-shadow"
                            placeholder="your.email@example.com"
                            autocomplete="email"
                        >
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div> 
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-shadow"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                        >
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors" tabindex="-1" style="background:none; border:none;" aria-label="Toggle password visibility">
                            <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                            <i data-lucide="eye-off" id="eyeOffIcon" class="w-5 h-5" style="display:none;"></i>
                        </button>
                    </div>
                    <div class="mt-2 text-right">
                        <a href="<?= base_url('auth/otp') ?>" class="text-sm text-primary hover:underline font-medium">Forgot password?</a>
                    </div>
                </div>

                <button 
                    type="submit" 
                    id="loginSubmitBtn"
                    class="w-full bg-success text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg"
                >
                    <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                    <span id="btnText">Login</span>
                    <svg id="btnSpinner" class="animate-spin hidden ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Don't have an account?
                </p>
                <div class="mt-2">
                    <a href="<?= base_url('auth/register-buyer') ?>" class="text-primary hover:text-primary-hover font-semibold transition-colors">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const INITIAL_LOCKOUT_SECONDS = <?= (int) ($initial_lockout_seconds ?? 0) ?>;
    const CSRF_TOKEN_NAME = '<?= csrf_token() ?>';
    // Password visibility toggle
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');
    let isPasswordVisible = false;

    if (togglePassword && passwordInput && eyeIcon && eyeOffIcon) {
        togglePassword.addEventListener('click', function() {
            isPasswordVisible = !isPasswordVisible;
            passwordInput.setAttribute('type', isPasswordVisible ? 'text' : 'password');
            eyeIcon.style.display = isPasswordVisible ? 'none' : '';
            eyeOffIcon.style.display = isPasswordVisible ? '' : 'none';
        });
        eyeIcon.style.display = '';
        eyeOffIcon.style.display = 'none';
    }

    // AJAX form submission
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('loginSubmitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const lockoutCountdown = document.getElementById('lockoutCountdown');

    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(loginForm);
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Client-side basic validation
            if (!email || !password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Required Fields',
                    text: 'Please enter both email and password.',
                    confirmButtonColor: '#16a34a',
                    showClass: { popup: 'animate__animated animate__shakeX' },
                    hideClass: { popup: 'animate__animated animate__fadeOut' }
                });
                return;
            }

            // Disable button and show spinner
            submitBtn.disabled = true;
            btnText.textContent = 'Logging in...';
            if (btnSpinner) btnSpinner.classList.remove('hidden');

            let responseData = null;
            try {
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                responseData = await response.json();
                const data = responseData;

                // Refresh CSRF token from response
                if (data.csrf_token) {
                    const tokenInputs = document.querySelectorAll('input[name="' + CSRF_TOKEN_NAME + '"]');
                    tokenInputs.forEach(input => input.value = data.csrf_token);
                }

                // Handle inline AJAX message
                const ajaxMessage = document.getElementById('ajaxMessage');
                if (ajaxMessage) {
                    const innerDiv = ajaxMessage.querySelector('div');
                    const icon = ajaxMessage.querySelector('i');
                    const textSpan = ajaxMessage.querySelector('span');
                    if (data.status === 'error' || data.status === 'locked') {
                        const message = data.message || 'An error occurred.';
                        if (data.status === 'error') {
                            innerDiv.className = 'flex items-start gap-3 p-4 rounded-lg border bg-red-50 border-red-200';
                            icon.className = 'w-5 h-5 text-red-600 flex-shrink-0 mt-0.5';
                            icon.setAttribute('data-lucide', 'alert-circle');
                            textSpan.className = 'text-red-700';
                        } else {
                            innerDiv.className = 'flex items-start gap-3 p-4 rounded-lg border bg-yellow-50 border-yellow-200';
                            icon.className = 'w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5';
                            icon.setAttribute('data-lucide', 'alert-triangle');
                            textSpan.className = 'text-yellow-700';
                        }
                        textSpan.textContent = message;
                        ajaxMessage.classList.remove('hidden');
                        if (window.lucide) lucide.createIcons();
                    } else {
                        ajaxMessage.classList.add('hidden');
                    }
                }

                if (data.status === 'success') {
                    // Show success and redirect
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome!',
                        text: data.message || 'Login successful. Redirecting...',
                        timer: 1500,
                        showConfirmButton: false,
                        showClass: { popup: 'animate__animated animate__fadeIn' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    }).then(() => {
                        window.location.href = data.redirect || '/';
                    });
                } else if (data.status === 'locked') {
                    // Show lockout with countdown
                    let remaining = parseInt(data.lockout_remaining, 10);
                    if (!Number.isFinite(remaining) || remaining < 1) {
                        remaining = 300;
                    }
                    Swal.fire({
                        icon: 'warning',
                        title: 'Account Locked',
                        html: `Too many failed attempts. Please wait <strong>${Math.floor(remaining/60)}:${(remaining%60).toString().padStart(2,'0')}</strong> before trying again.`,
                        confirmButtonColor: '#16a34a',
                        showClass: { popup: 'animate__animated animate__zoomIn' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    });

                    // Also update the lockout banner if present
                    if (lockoutCountdown) {
                        lockoutCountdown.style.display = 'block';
                        startCountdown(remaining);
                    }
                } else if (data.status === 'error') {
                    let errorMsg = data.message || 'Invalid email or password.';
                    
                    // If we have field-specific errors, build a list
                    if (data.errors) {
                        const errorsList = Object.values(data.errors).flat();
                        errorMsg = errorsList.join('\n');
                    } else {
                        // Decode HTML entities if present
                        const temp = document.createElement('textarea');
                        temp.innerHTML = errorMsg;
                        errorMsg = temp.value;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: errorMsg,
                        confirmButtonColor: '#16a34a',
                        showClass: { popup: 'animate__animated animate__shakeX' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    });
                } else {
                    // Fallback: session-based full page with flash alerts
                    window.location.reload();
                }

            } catch (error) {
                console.error('Login error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Unable to connect to server. Please check your internet connection.',
                    confirmButtonColor: '#16a34a'
                });
            } finally {
                const locked = responseData && responseData.status === 'locked';
                if (!locked) {
                    submitBtn.disabled = false;
                    btnText.textContent = 'Login';
                    if (btnSpinner) btnSpinner.classList.add('hidden');
                }
            }
        });
    }

    // Live countdown function
    function startCountdown(seconds) {
        const total = Math.max(1, Math.floor(Number(seconds) || 0));
        let remaining = total;
        const timerEl = document.getElementById('countdownTimer');
        const progressEl = document.getElementById('countdownProgress');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const submitBtn = document.getElementById('loginSubmitBtn');

        if (lockoutCountdown) lockoutCountdown.style.display = 'block';

        // Disable form for lockout duration
        if (emailInput) emailInput.disabled = true;
        if (passwordInput) passwordInput.disabled = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
        if (btnSpinner) btnSpinner.classList.add('hidden');
        if (document.getElementById('btnText')) document.getElementById('btnText').textContent = 'Login';

        function update() {
            const mins = Math.floor(remaining / 60);
            const secs = remaining % 60;
            if (timerEl) timerEl.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
            if (progressEl) progressEl.style.width = `${Math.max(0, (remaining / total) * 100)}%`;

            if (remaining <= 0) {
                if (lockoutCountdown) lockoutCountdown.style.display = 'none';
                if (emailInput) emailInput.disabled = false;
                if (passwordInput) passwordInput.disabled = false;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            } else {
                remaining--;
                setTimeout(update, 1000);
            }
        }
        update();
    }

    if (INITIAL_LOCKOUT_SECONDS > 0 && lockoutCountdown) {
        lockoutCountdown.style.display = 'block';
        startCountdown(INITIAL_LOCKOUT_SECONDS);
    }

    // Auto-focus email field
    const emailField = document.getElementById('email');
    if (emailField && !(INITIAL_LOCKOUT_SECONDS > 0)) emailField.focus();
});
</script>

<?= $this->endSection() ?>
