<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout</h1>
        <p class="text-gray-600">
            <?php if (isset($is_success_redirect) && $is_success_redirect): ?>
                Order Completed
            <?php else: ?>
                Complete your order
            <?php endif; ?>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <?php if (!isset($is_success_redirect) || !$is_success_redirect): ?>
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Delivery Information</h2>
                
                <form action="/checkout/place-order" method="POST">
                    <?= csrf_field() ?>
                    <?php if (isset($is_direct_checkout) && $is_direct_checkout): ?>
                        <input type="hidden" name="is_direct_checkout" value="1">
                        <?php foreach ($cart as $item): ?>
                            <input type="hidden" name="direct_product_id" value="<?= $item['product_id'] ?>">
                            <input type="hidden" name="direct_quantity" value="<?= $item['quantity'] ?>">
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
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
                        ><?= old('delivery_address', isset($user['location']) && !empty($user['location']) ? esc($user['location']) : '') ?></textarea>
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
                            value="<?= old('contact_number', isset($user['phone']) && !empty($user['phone']) ? esc($user['phone']) : '') ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        <p class="text-sm text-gray-500 mt-1">For delivery coordination</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="in_person"
                                    required
                                    <?= old('payment_method') === 'in_person' || !old('payment_method') ? 'checked' : '' ?>
                                    class="text-primary focus:ring-primary"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-700">Cash on Delivery (In-person)</span>
                            </label>
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="gcash"
                                    required
                                    <?= old('payment_method') === 'gcash' ? 'checked' : '' ?>
                                    class="text-primary focus:ring-primary"
                                >
                                <span class="ml-3 text-sm font-medium text-gray-700">GCash (Online Payment)</span>
                            </label>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Select your preferred payment method</p>
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
                        <?php if (isset($is_direct_checkout) && $is_direct_checkout): ?>
                            <?php foreach ($cart as $item): ?>
                                <a href="/marketplace/product/<?= $item['product_id'] ?>" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                                    <i data-lucide="arrow-left" class="w-5 h-5 inline mr-2"></i>
                                    Back to Product
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="/cart" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                                <i data-lucide="arrow-left" class="w-5 h-5 inline mr-2"></i>
                                Back to Cart
                            </a>
                        <?php endif; ?>
                        <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                            <i data-lucide="shopping-bag" class="w-5 h-5 inline mr-2"></i>
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Order Summary -->
        <?php if (!isset($is_success_redirect) || !$is_success_redirect): ?>
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
                    <div class="flex justify-between text-lg font-bold text-gray-900">
                        <span>Total</span>
                        <span class="text-primary">₱<?= number_format($subtotal, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Success Message -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 sticky top-4">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Completed</h2>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <p class="text-gray-600 mb-4">Your order has been successfully placed!</p>
                    <a href="/marketplace" class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Check for checkout success
    <?php if (session()->has('checkout_success') && session()->get('checkout_success')): ?>
        <?php
        $orderCount = session()->get('checkout_order_count', 1);
        session()->remove('checkout_success');
        session()->remove('checkout_order_count');
        ?>
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Thank you for your purchase!',
                text: 'Your order<?= $orderCount > 1 ? 's have' : ' has' ?> been placed successfully.',
                confirmButtonText: 'Continue Shopping',
                allowOutsideClick: false,
                customClass: {
                    popup: 'animate__animated animate__bounceIn'
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = '/marketplace';
                }
            });
        } else {
            alert('Thank you for your purchase! Your order has been placed successfully.');
            setTimeout(function() {
                window.location.href = '/marketplace';
            }, 2000);
        }
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>

