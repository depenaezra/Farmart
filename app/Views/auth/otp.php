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
            <form method="post" action="<?= base_url('auth/sendOtp') ?>" id="forgotPasswordForm">
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="your.email@example.com">
                    <div id="emailHelp" class="mt-2 text-sm text-gray-500">
                        We'll send a one-time code to this email to verify your identity
                    </div>
                    <div id="emailValidation" class="mt-2 space-y-1 text-sm hidden">
                        <p id="emailFormat" class="flex items-start text-red-500">
                            <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Please enter a valid email address
                        </p>
                    </div>
                </div>
                <button type="submit" class="mt-3 w-full bg-primary text-white py-2 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center">
                    <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                    Send OTP
                </button>
            </form>
            <?php else: ?>
            <form method="post" action="<?= base_url('auth/verifyOtp') ?>" id="verifyOtpForm">
                <div class="mb-6">
                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2 mt-4">OTP Code</label>
                    <input type="text" id="otp" name="otp" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter OTP" maxlength="6">
                    <div id="otpHelp" class="mt-2 text-sm text-gray-500">
                        Check your email for the 6-digit code
                    </div>
                    <div id="otpValidation" class="mt-2 space-y-1 text-sm hidden">
                        <p id="otpLength" class="flex items-start text-red-500">
                            <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Must be 6 digits
                        </p>
                        <p id="otpNumbers" class="flex items-start text-red-500">
                            <i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Numbers only
                        </p>
                    </div>
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

<?= $this->section('scripts') ?>
<script>
// Email validation for forgot password form
document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const verifyOtpForm = document.getElementById('verifyOtpForm');
    
    if (forgotPasswordForm) {
        const emailInput = forgotPasswordForm.querySelector('#email');
        const emailHelp = forgotPasswordForm.querySelector('#emailHelp');
        const emailValidation = forgotPasswordForm.querySelector('#emailValidation');
        const emailFormat = forgotPasswordForm.querySelector('#emailFormat');
        
        if (emailInput && emailHelp && emailValidation && emailFormat) {
            // Real-time email validation
            emailInput.addEventListener('input', function() {
                const email = emailInput.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email === '') {
                    emailHelp.classList.remove('hidden');
                    emailValidation.classList.add('hidden');
                    emailFormat.classList.remove('text-green-500');
                    emailFormat.classList.add('text-red-500');
                    emailFormat.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Please enter a valid email address';
                } else if (!emailPattern.test(email)) {
                    emailHelp.classList.add('hidden');
                    emailValidation.classList.remove('hidden');
                    emailFormat.classList.remove('text-green-500');
                    emailFormat.classList.add('text-red-500');
                    emailFormat.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Please enter a valid email address';
                } else {
                    emailHelp.classList.add('hidden');
                    emailValidation.classList.add('hidden');
                    emailFormat.classList.remove('text-red-500');
                    emailFormat.classList.add('text-green-500');
                    emailFormat.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2"></i> Valid email address';
                }
            });
            
            // Form submission validation
            forgotPasswordForm.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email === '' || !emailPattern.test(email)) {
                    e.preventDefault();
                    emailHelp.classList.add('hidden');
                    emailValidation.classList.remove('hidden');
                    emailFormat.classList.remove('text-green-500');
                    emailFormat.classList.add('text-red-500');
                    emailFormat.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Please enter a valid email address';
                    emailInput.focus();
                }
            });
        }
    }
    
    if (verifyOtpForm) {
        const otpInput = verifyOtpForm.querySelector('#otp');
        const otpHelp = verifyOtpForm.querySelector('#otpHelp');
        const otpValidation = verifyOtpForm.querySelector('#otpValidation');
        const otpLength = verifyOtpForm.querySelector('#otpLength');
        const otpNumbers = verifyOtpForm.querySelector('#otpNumbers');
        
        if (otpInput && otpHelp && otpValidation && otpLength && otpNumbers) {
            // Real-time OTP validation
            otpInput.addEventListener('input', function() {
                const otp = otpInput.value;
                
                // Check length
                if (otp.length !== 6) {
                    otpHelp.classList.add('hidden');
                    otpValidation.classList.remove('hidden');
                    otpLength.classList.remove('text-green-500');
                    otpLength.classList.add('text-red-500');
                    otpLength.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Must be 6 digits';
                } else {
                    otpLength.classList.remove('text-red-500');
                    otpLength.classList.add('text-green-500');
                    otpLength.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2"></i> Must be 6 digits';
                }
                
                // Check if numbers only
                if (!/^\d+$/.test(otp)) {
                    otpHelp.classList.add('hidden');
                    otpValidation.classList.remove('hidden');
                    otpNumbers.classList.remove('text-green-500');
                    otpNumbers.classList.add('text-red-500');
                    otpNumbers.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Numbers only';
                } else {
                    otpNumbers.classList.remove('text-red-500');
                    otpNumbers.classList.add('text-green-500');
                    otpNumbers.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2"></i> Numbers only';
                }
                
                // Show help if both valid
                if (otp.length === 6 && /^\d+$/.test(otp)) {
                    otpHelp.classList.add('hidden');
                    otpValidation.classList.add('hidden');
                }
            });
            
            // Form submission validation
            verifyOtpForm.addEventListener('submit', function(e) {
                const otp = otpInput.value;
                
                if (otp.length !== 6 || !/^\d+$/.test(otp)) {
                    e.preventDefault();
                    otpHelp.classList.add('hidden');
                    otpValidation.classList.remove('hidden');
                    
                    if (otp.length !== 6) {
                        otpLength.classList.remove('text-green-500');
                        otpLength.classList.add('text-red-500');
                        otpLength.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Must be 6 digits';
                    }
                    
                    if (!/^\d+$/.test(otp)) {
                        otpNumbers.classList.remove('text-green-500');
                        otpNumbers.classList.add('text-red-500');
                        otpNumbers.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2"></i> Numbers only';
                    }
                    
                    otpInput.focus();
                }
            });
        }
    }
    
    // Add click-to-copy functionality for OTP (if displayed anywhere)
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('copy-otp')) {
            const otpText = e.target.getAttribute('data-otp');
            if (otpText) {
                navigator.clipboard.writeText(otpText).then(function() {
                    // Show temporary success message
                    const originalText = e.target.innerHTML;
                    e.target.innerHTML = '<i data-lucide="copy" class="w-4 h-4"></i> Copied!';
                    setTimeout(function() {
                        e.target.innerHTML = originalText;
                    }, 2000);
                }).catch(function() {
                    alert('Failed to copy OTP');
                });
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
