<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-12">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="w-12 h-12 text-green-600"></i>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Placed Successfully!</h1>
            
            <p class="text-lg text-gray-600 mb-2">
                Thank you for your order. Your <?= $order_count > 1 ? $order_count . ' orders have' : 'order has' ?> been received.
            </p>
            
            <p class="text-gray-500 mb-8">
                <?php if ($order_count > 1): ?>
                    You will receive separate notifications from each farmer regarding your orders.
                <?php else: ?>
                    The farmer will contact you soon to confirm your order and delivery details.
                <?php endif; ?>
            </p>

            <div class="space-y-4">
                <a href="/buyer/orders" class="inline-block bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                    <i data-lucide="shopping-bag" class="w-5 h-5 inline mr-2"></i>
                    View My Orders
                </a>
                
                <div class="pt-4">
                    <a href="/marketplace" class="inline-block text-primary hover:text-primary-hover font-medium">
                        <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                        Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Information -->
            <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="font-semibold text-gray-900 mb-3">What's Next?</h3>
                <ul class="text-left text-sm text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                        <span>Your order<?= $order_count > 1 ? 's' : '' ?> <?= $order_count > 1 ? 'have' : 'has' ?> been sent to the farmer<?= $order_count > 1 ? 's' : '' ?></span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                        <span>The farmer will review and confirm your order</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                        <span>You'll receive updates on your order status</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                        <span>Delivery details will be coordinated with you</span>
                    </li>
                </ul>
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

