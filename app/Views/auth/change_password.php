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
                <p class="text-gray-600 mt-2">Enter your new password below</p>
            </div>
            <form method="post" action="<?= base_url('auth/changePasswordProcess') ?>">
<div class="mb-6">
    <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
    <div class="relative">
        <input type="password" id="new_password" name="new_password" required minlength="8" maxlength="15" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent pr-10" placeholder="8-15 chars, 1 uppercase, 1 number, 1 special" aria-describedby="newPassword-help">
        <button type="button" id="toggleNewPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" aria-label="Show password">
            <i data-lucide="eye-off" class="w-4 h-4" id="newPasswordIcon"></i>
        </button>
    </div>
    <p id="newPassword-help" class="mt-2 text-sm text-gray-500">
        Password must be 8-15 characters with at least 1 uppercase letter, 1 number, and 1 special character
    </p>
    <div id="newPasswordRequirements" class="mt-2 space-y-1 text-sm">
        <p id="newLengthReq" class="flex items-start text-red-500">
            <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> 8-15 characters
        </p>
        <p id="newUppercaseReq" class="flex items-start text-red-500">
            <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> 1 uppercase letter
        </p>
        <p id="newNumberReq" class="flex items-start text-red-500">
            <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> 1 number
        </p>
        <p id="newSpecialReq" class="flex items-start text-red-500">
            <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> 1 special character
        </p>
    </div>
</div>
<div class="mb-6">
    <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
    <div class="relative">
        <input type="password" id="confirm_password" name="confirm_password" required minlength="8" maxlength="15" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent pr-10" placeholder="Re-enter new password" aria-describedby="confirmNewPassword-help">
        <button type="button" id="toggleConfirmNewPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" aria-label="Show password">
            <i data-lucide="eye-off" class="w-4 h-4" id="confirmNewPasswordIcon"></i>
        </button>
    </div>
    <p id="confirmNewPassword-help" class="mt-2 text-sm text-gray-500 hidden">
        Passwords must match
    </p>
    <div id="confirmNewPasswordMessage" class="mt-2 text-sm">
        <p id="newMatchStatus" class="flex items-start"></p>
    </div>
</div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center">
                    <i data-lucide="lock" class="w-5 h-5 mr-2"></i>
                    Change Password
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Password visibility toggle for new password
document.addEventListener('DOMContentLoaded', function() {
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const newPasswordInput = document.getElementById('new_password');
    const newPasswordIcon = document.getElementById('newPasswordIcon');
    
    if (toggleNewPassword && newPasswordInput && newPasswordIcon) {
        toggleNewPassword.addEventListener('click', function() {
            const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            newPasswordInput.setAttribute('type', type);
            // Toggle icon
            if (type === 'password') {
                newPasswordIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                newPasswordIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons(); // Refresh icons
        });
    }
    
    // Password visibility toggle for confirm password
    const toggleConfirmNewPassword = document.getElementById('toggleConfirmNewPassword');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const confirmNewPasswordIcon = document.getElementById('confirmNewPasswordIcon');
    
    if (toggleConfirmNewPassword && confirmPasswordInput && confirmNewPasswordIcon) {
        toggleConfirmNewPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            // Toggle icon
            if (type === 'password') {
                confirmNewPasswordIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                confirmNewPasswordIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons(); // Refresh icons
        });
    }
    
    // Real-time password validation
    newPasswordInput = document.getElementById('new_password');
    confirmPasswordInput = document.getElementById('confirm_password');
    
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            validatePasswordField(newPasswordInput, this.form);
        });
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            validateConfirmPasswordField(newPasswordInput, confirmPasswordInput, this.form);
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
    var helpText = form.querySelector('#newPassword-help');
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
    
    var matchStatus = form.querySelector('#newMatchStatus');
    var confirmHelpText = form.querySelector('#confirmNewPassword-help');
    
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
    var lengthReq = form.querySelector('#newLengthReq');
    var uppercaseReq = form.querySelector('#newUppercaseReq');
    var numberReq = form.querySelector('#newNumberReq');
    var specialReq = form.querySelector('#newSpecialReq');
    
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
