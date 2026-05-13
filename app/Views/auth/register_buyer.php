<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center" style="background-image: url('<?= base_url('img/11.jpg') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-2xl mx-auto p-8" style="backdrop-filter: blur(2px); background-color: rgba(255, 255, 255, 0.1); border-width: 3px; border-color: white; margin-bottom: 100px; margin-top: 100px;">
        
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <form action="/auth/register-buyer" method="POST">
                <?= csrf_field() ?>
                <div class="flex flex-col items-center mb-8">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="shopping-bag" class="w-8 h-8 text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">Register</h1>
                    <p class="text-gray-600 mt-2">Create your account to get started</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Full Name *
                        </label>
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
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Phone Number *
                        </label>
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
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address *
                    </label>
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
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                        Location *
                    </label>
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
    <div class="relative">
        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
            Password *
        </label>
        <div class="relative">
            <input 
                type="password" 
                id="password" 
                name="password" 
                required
                minlength="8"
                maxlength="15"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent pr-10"
                placeholder="8-15 chars, 1 uppercase, 1 number, 1 special"
                aria-describedby="password-help"
            >
            <button type="button" 
                    id="togglePassword" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                    aria-label="Show password">
                <i data-lucide="eye-off" class="w-4 h-4" id="passwordIcon"></i>
            </button>
        </div>
        <p id="password-help" class="mt-2 text-sm text-gray-500">
            Password must be 8-15 characters with at least 1 uppercase letter, 1 number, and 1 special character
        </p>
        <div id="passwordRequirements" class="mt-2 space-y-1 text-sm">
            <p id="lengthReq" class="flex items-start text-red-500">
                <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> 8-15 characters
            </p>
            <p id="uppercaseReq" class="flex items-start text-red-500">
                <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> 1 uppercase letter
            </p>
            <p id="numberReq" class="flex items-start text-red-500">
                <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> 1 number
            </p>
            <p id="specialReq" class="flex items-start text-red-500">
                <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> 1 special character
            </p>
        </div>
    </div>
    
    <div class="relative">
        <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">
            Confirm Password *
        </label>
        <div class="relative">
            <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent pr-10"
                placeholder="Re-enter password"
                aria-describedby="confirmPassword-help"
            >
            <button type="button" 
                    id="toggleConfirmPassword" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                    aria-label="Show password">
                <i data-lucide="eye-off" class="w-4 h-4" id="confirmPasswordIcon"></i>
            </button>
        </div>
        <p id="confirmPassword-help" class="mt-2 text-sm text-gray-500 hidden">
            Passwords must match
        </p>
        <div id="confirmPasswordMessage" class="mt-2 text-sm">
            <p id="matchStatus" class="flex items-start"></p>
        </div>
    </div>
</div>
                
                <button 
                    type="submit" 
                    class="w-full bg-accent text-white py-3 rounded-lg font-semibold hover:bg-accent/90 transition-colors flex items-center justify-center"
                >
                    <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                    Register
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Already have an account?
                    <a href="/auth/login" class="text-accent hover:text-accent/90 font-semibold">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Password visibility toggle for password
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');
    
    if (togglePassword && passwordInput && passwordIcon) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            // Toggle icon
            if (type === 'password') {
                passwordIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons(); // Refresh icons
        });
    }
    
    // Password visibility toggle for confirm password
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const confirmPasswordIcon = document.getElementById('confirmPasswordIcon');
    
    if (toggleConfirmPassword && confirmPasswordInput && confirmPasswordIcon) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            // Toggle icon
            if (type === 'password') {
                confirmPasswordIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                confirmPasswordIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons(); // Refresh icons
        });
    }
    
    // Real-time password validation
    passwordInput = document.getElementById('password');
    confirmPasswordInput = document.getElementById('confirm_password');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validatePasswordField(passwordInput, this.form);
        });
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            validateConfirmPasswordField(passwordInput, confirmPasswordInput, this.form);
        });
    }
});

// Password validation functions (copied from farmart-ui.js for local use)
function validatePasswordRules(pw) {
    var s = String(pw || '');
    var errors = [];
    if (s.length < 8 || s.length > 15) errors.push('8 to 15 characters');
    if (!/[A-Z]/.test(s)) errors.push('at least 1 uppercase letter');
    if (!/\d/.test(s)) errors.push('at least 1 number');
    if (!/[^A-Za-z0-9]/.test(s)) errors.push('at least 1 special character');
    return { ok: errors.length === 0, errors: errors };
}

function validatePasswordField(input, form) {
    var value = input.value;
    var v = validatePasswordRules(value);
    
    // Update requirement indicators
    updatePasswordRequirements(value, form);
    
    // Show/hide help text based on validity
    var helpText = form.querySelector('#password-help');
    if (helpText) {
        if (v.ok) {
            helpText.classList.remove('text-red-500');
            helpText.classList.add('text-green-500');
            helpText.textContent = 'Password meets all requirements';
        } else {
            helpText.classList.remove('text-green-500');
            helpText.classList.add('text-red-500');
            helpText.textContent = 'Password must be 8-15 characters with at least 1 uppercase letter, 1 number, and 1 special character';
        }
    }
}

function validateConfirmPasswordField(passwordInput, confirmInput, form) {
    var passwordValue = passwordInput.value;
    var confirmValue = confirmInput.value;
    
    var matchStatus = form.querySelector('#matchStatus');
    var confirmHelpText = form.querySelector('#confirmPassword-help');
    
    if (passwordValue === confirmValue && passwordValue !== '') {
        if (matchStatus) {
            matchStatus.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> Passwords match';
            lucide.createIcons(); // Refresh icons
        }
        if (confirmHelpText) {
            confirmHelpText.classList.remove('hidden');
            confirmHelpText.classList.add('text-green-500');
            confirmHelpText.textContent = 'Passwords match';
        }
    } else {
        if (matchStatus) {
            matchStatus.innerHTML = passwordValue !== '' && confirmValue !== '' 
                ? '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> Passwords do not match'
                : '';
            lucide.createIcons(); // Refresh icons
        }
        if (confirmHelpText) {
            confirmHelpText.classList.remove('hidden');
            confirmHelpText.classList.add('text-red-500');
            confirmHelpText.textContent = 'Passwords must match';
        }
    }
}

function updatePasswordRequirements(value, form) {
    var lengthReq = form.querySelector('#lengthReq');
    var uppercaseReq = form.querySelector('#uppercaseReq');
    var numberReq = form.querySelector('#numberReq');
    var specialReq = form.querySelector('#specialReq');
    
    if (lengthReq) {
        if (value.length >= 8 && value.length <= 15) {
            lengthReq.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> 8-15 characters';
            lengthReq.classList.remove('text-red-500');
            lengthReq.classList.add('text-green-500');
        } else {
            lengthReq.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> 8-15 characters';
            lengthReq.classList.remove('text-green-500');
            lengthReq.classList.add('text-red-500');
        }
    }
    
    if (uppercaseReq) {
        if (/[A-Z]/.test(value)) {
            uppercaseReq.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> 1 uppercase letter';
            uppercaseReq.classList.remove('text-red-500');
            uppercaseReq.classList.add('text-green-500');
        } else {
            uppercaseReq.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> 1 uppercase letter';
            uppercaseReq.classList.remove('text-green-500');
            uppercaseReq.classList.add('text-red-500');
        }
    }
    
    if (numberReq) {
        if (/\d/.test(value)) {
            numberReq.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> 1 number';
            numberReq.classList.remove('text-red-500');
            numberReq.classList.add('text-green-500');
        } else {
            numberReq.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> 1 number';
            numberReq.classList.remove('text-green-500');
            numberReq.classList.add('text-red-500');
        }
    }
    
    if (specialReq) {
        if (/[^A-Za-z0-9]/.test(value)) {
            specialReq.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> 1 special character';
            specialReq.classList.remove('text-red-500');
            specialReq.classList.add('text-green-500');
        } else {
            specialReq.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> 1 special character';
            specialReq.classList.remove('text-green-500');
            specialReq.classList.add('text-red-500');
        }
    }
    
    // Refresh icons after updating
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}
</script>
<?= $this->endSection() ?>
