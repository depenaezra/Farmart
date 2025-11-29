<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout</h1>
        <p class="text-gray-600">Complete your order</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Delivery Information</h2>
                
                <form action="/checkout/place-order" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-6">
                        <label for="delivery_address" class="block text-sm font-semibold text-gray-700 mb-2">
                            Delivery Address <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="delivery_address"
                            name="delivery_address"
                            rows="4"
                            required
                            minlength="10"
                            placeholder="Enter your complete delivery address..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        ><?= old('delivery_address') ?></textarea>
                        <p class="text-sm text-gray-500 mt-1">Include street, barangay, and landmarks</p>
                    </div>

                    <div class="mb-6">
                        <label for="contact_number" class="block text-sm font-semibold text-gray-700 mb-2">
                            Contact Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="tel"
                            id="contact_number"
                            name="contact_number"
                            required
                            placeholder="09XX XXX XXXX"
                            value="<?= old('contact_number') ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        <p class="text-sm text-gray-500 mt-1">For delivery coordination</p>
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Additional Notes (Optional)
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="Special delivery instructions, preferred delivery time, etc."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        ><?= old('notes') ?></textarea>
                    </div>

                    <!-- Display validation errors -->
                    <?php if (session()->has('errors')): ?>
                        <div role="alert" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <ul class="text-sm text-red-700 space-y-1">
                                <?php foreach (session()->get('errors') as $error): ?>
                                    <li>• <?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="flex gap-4">
                        <a href="/cart" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                            <i data-lucide="arrow-left" class="w-5 h-5 inline mr-2"></i>
                            Back to Cart
                        </a>
                        <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                            <i data-lucide="shopping-bag" class="w-5 h-5 inline mr-2"></i>
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 sticky top-4">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                
                <!-- Order Items -->
                <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                    <?php foreach ($cart as $item): ?>
                        <div class="flex gap-3 pb-4 border-b border-gray-200 last:border-0">
                            <?php if (!empty($item['image_url'])): ?>
                                <img src="<?= esc($item['image_url']) ?>" 
                                     alt="<?= esc($item['product_name']) ?>" 
                                     class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">
                                    <?= esc($item['product_name']) ?>
                                </h3>
                                <p class="text-xs text-gray-600 mb-1">
                                    <?= esc($item['quantity']) ?> × <?= esc($item['unit']) ?>
                                </p>
                                <p class="text-sm font-semibold text-primary">
                                    ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Totals -->
                <div class="space-y-3 border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-semibold">₱<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span class="font-semibold text-gray-500">TBD</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="flex justify-between text-lg font-bold text-gray-900">
                            <span>Total</span>
                            <span class="text-primary">₱<?= number_format($subtotal, 2) ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                        Shipping costs will be confirmed by the farmer
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>

<?= $this->endSection() ?>

