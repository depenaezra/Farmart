<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-[80vh] py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="shopping-bag" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Register as Buyer</h1>
            <p class="text-gray-600 mt-2">Start buying fresh local produce directly from farmers</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <form action="/auth/register-buyer" method="POST">
                <?= csrf_field() ?>
                
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
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password *
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            minlength="8"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="At least 8 characters"
                        >
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirm Password *
                        </label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="Re-enter password"
                        >
                    </div>
                </div>
                
                <button 
                    type="submit" 
                    class="w-full bg-accent text-white py-3 rounded-lg font-semibold hover:bg-accent/90 transition-colors flex items-center justify-center"
                >
                    <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                    Register as Buyer
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
