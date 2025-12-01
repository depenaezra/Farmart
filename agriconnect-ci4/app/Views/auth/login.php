<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center" style="background-image: url('<?= base_url('img/outdoor.png') ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="max-w-md w-full rounded-xl p-8" style="backdrop-filter: blur(2px); background-color: rgba(255, 255, 255, 0.1); border-width: 3px; border-color: white">
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
            <div class="w-16 h-16 bg-success rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="log-in" class="w-8 h-8 text-white"></i>
            
            </div>
                <h1 class="text-3xl font-bold text-gray-900">Welcome Back</h1>
                <p class="text-gray-600 mt-2">Login to access your account</p>
            </div>
            <?php if (session('error')): ?>
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                    <span class="text-red-700 font-semibold"><?= esc(session('error')) ?></span>
                </div>
            <?php endif; ?>
            <?php if (session('success')): ?>
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    <span class="text-green-700 font-semibold"><?= esc(session('success')) ?></span>
                </div>
            <?php endif; ?>
            <form action="/auth/login" method="POST">
                <?= csrf_field() ?>
                
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
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="your.email@example.com"
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
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Enter your password"
                        >
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400" tabindex="-1" style="background:none; border:none;">
                            <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                            <i data-lucide="eye-off" id="eyeOffIcon" class="w-5 h-5" style="display:none;"></i>
                        </button>
                    </div>
                    <div class="mt-2 text-right">
                        <a href="#" class="text-sm text-primary hover:underline font-medium">Forgot password?</a>
                    </div>
                </div>
                
                <button 
                    type="submit" 
                    class="w-full bg-success text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center"
                >
                    <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                    Login
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Don't have an account?
                </p>
                <div class="mt-2">
                    <a href="/auth/register-buyer" class="text-primary hover:text-primary-hover font-semibold">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
            // Initial state: show eye, hide eye-off
            eyeIcon.style.display = '';
            eyeOffIcon.style.display = 'none';
        }
    });
</script>
<?= $this->endSection() ?>
