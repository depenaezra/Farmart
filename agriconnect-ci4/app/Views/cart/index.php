<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
            <p class="text-gray-600">Review your items and proceed to checkout</p>
        </div>

        <?php if (empty($cart)): ?>
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <div class="mb-6">
                    <i data-lucide="shopping-cart" class="w-24 h-24 text-gray-300 mx-auto"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Add some fresh produce from our marketplace</p>
                <a href="/marketplace" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors font-semibold">
                    <i data-lucide="shopping-bag" class="w-5 h-5 mr-2"></i>
                    Browse Marketplace
                </a>
            </div>
        <?php else: ?>
            <!-- Cart Items -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
                <div class="p-6">
                    <div class="hidden md:grid md:grid-cols-12 gap-4 text-sm font-semibold text-gray-600 border-b border-gray-200 pb-4 mb-4">
                        <div class="col-span-6">Product</div>
                        <div class="col-span-2 text-center">Price</div>
                        <div class="col-span-2 text-center">Quantity</div>
                        <div class="col-span-2 text-center">Total</div>
                    </div>

                    <?php foreach ($cart as $item): ?>
                        <div class="flex flex-col md:grid md:grid-cols-12 gap-4 items-center py-4 border-b border-gray-100 last:border-b-0">
                            <!-- Product Info -->
                            <div class="col-span-6 flex items-center space-x-4">
                                <?php if ($item['image_url']): ?>
                                    <img src="<?= esc($item['image_url']) ?>" alt="<?= esc($item['product_name']) ?>" class="w-16 h-16 object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
                                        <i data-lucide="package" class="w-8 h-8 text-green-600"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900"><?= esc($item['product_name']) ?></h3>
                                    <p class="text-sm text-gray-600">by <?= esc($item['farmer_name']) ?></p>
                                    <p class="text-sm text-gray-500"><?= esc($item['location']) ?></p>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-span-2 text-center">
                                <span class="text-gray-900 font-medium">₱<?= number_format($item['price'], 2) ?></span>
                                <span class="text-sm text-gray-600 block">/ <?= esc($item['unit']) ?></span>
                            </div>

                            <!-- Quantity -->
                            <div class="col-span-2 flex items-center justify-center">
                                <form action="/cart/update/<?= $item['id'] ?>" method="POST" class="flex items-center space-x-2">
                                    <?= csrf_field() ?>
                                    <button type="button" onclick="updateQuantity('<?= $item['id'] ?>', <?= $item['quantity'] - 1 ?>)" class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-sm">
                                        <i data-lucide="minus" class="w-3 h-3"></i>
                                    </button>
                                    <input
                                        type="number"
                                        name="quantity"
                                        value="<?= $item['quantity'] ?>"
                                        min="1"
                                        class="w-16 text-center border border-gray-300 rounded px-2 py-1 text-sm"
                                        onchange="updateQuantity('<?= $item['id'] ?>', this.value)"
                                    >
                                    <button type="button" onclick="updateQuantity('<?= $item['id'] ?>', <?= $item['quantity'] + 1 ?>)" class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-sm">
                                        <i data-lucide="plus" class="w-3 h-3"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Total -->
                            <div class="col-span-2 text-center">
                                <span class="text-gray-900 font-semibold">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                            </div>

                            <!-- Remove Button -->
                            <div class="flex justify-end md:col-span-12 md:justify-end">
                                <form action="/cart/remove/<?= $item['id'] ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Remove item">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal (<?= $item_count ?> item<?= $item_count > 1 ? 's' : '' ?>)</span>
                        <span>₱<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Delivery Fee</span>
                        <span>₱50.00</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-lg font-semibold text-gray-900">
                        <span>Total</span>
                        <span>₱<?= number_format($subtotal + 50, 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <div class="flex gap-4">
                    <a href="/marketplace" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Continue Shopping
                    </a>
                    <a href="/cart/clear" class="inline-flex items-center px-6 py-3 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors font-semibold">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Clear Cart
                    </a>
                </div>

                <a href="/checkout" class="inline-flex items-center px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors font-semibold">
                    <i data-lucide="credit-card" class="w-5 h-5 mr-2"></i>
                    Proceed to Checkout
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function updateQuantity(cartItemId, newQuantity) {
    if (newQuantity < 1) return;

    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/cart/update/${cartItemId}`;

    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '<?= csrf_token() ?>';
    csrfInput.value = '<?= csrf_hash() ?>';
    form.appendChild(csrfInput);

    // Add quantity
    const quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = newQuantity;
    form.appendChild(quantityInput);

    document.body.appendChild(form);
    form.submit();
}
</script>

<?= $this->endSection() ?>