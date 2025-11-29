<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="/buyer/orders" class="inline-flex items-center text-primary hover:text-primary-hover mb-6">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Orders
        </a>

        <!-- Order Header -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        Order #<?= esc($order['order_number']) ?>
                    </h1>
                    <p class="text-sm text-gray-600">
                        Placed on <?= date('F d, Y \a\t H:i', strtotime($order['created_at'])) ?>
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full
                        <?php
                        switch($order['status']) {
                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                            case 'confirmed': echo 'bg-blue-100 text-blue-800'; break;
                            case 'processing': echo 'bg-purple-100 text-purple-800'; break;
                            case 'processing': echo 'bg-purple-100 text-purple-800'; break;
                            case 'completed': echo 'bg-green-100 text-green-800'; break;
                            case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                            default: echo 'bg-gray-100 text-gray-800';
                        }
                        ?>">
                        <?= ucfirst($order['status']) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Information -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Product Information</h2>
                    
                    <div class="flex gap-4">
                        <?php if (!empty($order['image_url'])): ?>
                            <img src="<?= esc($order['image_url']) ?>" 
                                 alt="<?= esc($order['product_name']) ?>" 
                                 class="w-24 h-24 object-cover rounded-lg">
                        <?php else: ?>
                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i data-lucide="package" class="w-12 h-12 text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <?= esc($order['product_name']) ?>
                            </h3>
                            <?php if (!empty($order['product_description'])): ?>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    <?= esc($order['product_description']) ?>
                                </p>
                            <?php endif; ?>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span>
                                    <i data-lucide="package" class="w-4 h-4 inline mr-1"></i>
                                    Quantity: <?= esc($order['quantity']) ?> <?= esc($order['unit']) ?>
                                </span>
                                <span>
                                    <i data-lucide="dollar-sign" class="w-4 h-4 inline mr-1"></i>
                                    Unit Price: ₱<?= number_format($order['total_price'] / $order['quantity'], 2) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Delivery Information</h2>
                    
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 mr-3 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Delivery Address</p>
                                <p class="text-gray-900 whitespace-pre-line"><?= nl2br(esc($order['delivery_address'])) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($order['notes'])): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-1">Additional Notes</p>
                            <p class="text-gray-600 whitespace-pre-line"><?= nl2br(esc($order['notes'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Farmer Information -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Farmer Information</h2>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i data-lucide="user" class="w-5 h-5 text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Name</p>
                                <p class="text-gray-900"><?= esc($order['farmer_name']) ?></p>
                            </div>
                        </div>
                        
                        <?php if (!empty($order['farmer_phone'])): ?>
                            <div class="flex items-center">
                                <i data-lucide="phone" class="w-5 h-5 text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Phone</p>
                                    <p class="text-gray-900"><?= esc($order['farmer_phone']) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($order['farmer_email'])): ?>
                            <div class="flex items-center">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Email</p>
                                    <p class="text-gray-900"><?= esc($order['farmer_email']) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($order['farmer_location'])): ?>
                            <div class="flex items-center">
                                <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Location</p>
                                    <p class="text-gray-900"><?= esc($order['farmer_location']) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($order['cooperative'])): ?>
                            <div class="flex items-center">
                                <i data-lucide="users" class="w-5 h-5 text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Cooperative</p>
                                    <p class="text-gray-900"><?= esc($order['cooperative']) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 sticky top-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold">₱<?= number_format($order['total_price'], 2) ?></span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span class="font-semibold text-gray-500">TBD</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span class="text-primary">₱<?= number_format($order['total_price'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <?php if ($order['status'] === 'pending'): ?>
                            <form action="/buyer/orders/<?= $order['id'] ?>/cancel" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full bg-red-100 text-red-700 py-3 px-4 rounded-lg hover:bg-red-200 font-semibold transition-colors">
                                    <i data-lucide="x-circle" class="w-5 h-5 inline mr-2"></i>
                                    Cancel Order
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <a href="/messages/compose?to=<?= $order['farmer_id'] ?>" class="block w-full bg-primary text-white py-3 px-4 rounded-lg hover:bg-primary-hover font-semibold transition-colors text-center">
                            <i data-lucide="message-circle" class="w-5 h-5 inline mr-2"></i>
                            Contact Farmer
                        </a>
                    </div>

                    <!-- Order Timeline -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Order Timeline</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5 mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Order Placed</p>
                                    <p class="text-gray-500 text-xs"><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></p>
                                </div>
                            </div>
                            
                            <?php if ($order['status'] !== 'pending'): ?>
                                <div class="flex items-start">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-gray-900">Order Confirmed</p>
                                        <p class="text-gray-500 text-xs"><?= date('M d, Y H:i', strtotime($order['updated_at'])) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (in_array($order['status'], ['processing', 'completed'])): ?>
                                <div class="flex items-start">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-1.5 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-gray-900">Processing</p>
                                        <p class="text-gray-500 text-xs">Order is being prepared</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($order['status'] === 'completed'): ?>
                                <div class="flex items-start">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-gray-900">Completed</p>
                                        <p class="text-gray-500 text-xs">Order fulfilled</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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

