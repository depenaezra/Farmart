<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="log-in" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Welcome Back</h1>
            <p class="text-gray-600 mt-2">Login to access your account</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
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
                    </div>
                </div>
                
                <button 
                    type="submit" 
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-hover transition-colors flex items-center justify-center"
                >
                    <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                    Login
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Don't have an account?
                </p>
                <div class="mt-2 space-x-2">
                    <a href="/auth/register-farmer" class="text-primary hover:text-primary-hover font-semibold">
                        Register as Farmer
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="/auth/register-buyer" class="text-primary hover:text-primary-hover font-semibold">
                        Register as Buyer
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Demo Credentials -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm font-semibold text-blue-800 mb-2">Demo Accounts:</p>
            <div class="text-xs text-blue-700 space-y-1">
                <p><strong>Farmer:</strong> juan.santos@example.com</p>
                <p><strong>Buyer:</strong> miguel.buyer@example.com</p>
                <p><strong>Admin:</strong> admin@agriconnect.ph</p>
                <p><strong>Password (all):</strong> password123</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
