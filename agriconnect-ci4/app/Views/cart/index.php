<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
        <p class="text-gray-600">Review your items before checkout</p>
    </div>

    <!-- Display success message -->
    <?php if (session()->has('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
            <i data-lucide="check-circle" class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-green-800 font-semibold">Success!</p>
                <p class="text-green-700 text-sm"><?= esc(session()->get('success')) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Display error message -->
    <?php if (session()->has('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-red-800 font-semibold">Error!</p>
                <p class="text-red-700 text-sm"><?= esc(session()->get('error')) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-12 text-center">
            <i data-lucide="shopping-cart" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Your cart is empty</h2>
            <p class="text-gray-600 mb-6">Add some products to get started!</p>
            <a href="/marketplace" class="inline-block bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 inline mr-2"></i>
                Continue Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">
                            Cart Items (0)
                        </h2>
                    </div>
                    
                    <form id="cartForm">
                        <?= csrf_field() ?>
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($cart as $item): ?>
                                <div class="p-6 flex gap-4">
                                    <!-- Checkbox -->
                                    <div class="flex-shrink-0 flex items-center">
                                        <input type="checkbox"
                                               name="selected_items[]"
                                               value="<?= esc($item['id']) ?>"
                                               id="item_<?= $item['id'] ?>"
                                               class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary">
                                    </div>

                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <?php
                                        $cartImage = null;
                                        if (!empty($item['image_url'])) {
                                            $decoded = json_decode($item['image_url'], true);
                                            if (is_array($decoded)) {
                                                $cartImage = $decoded[0];
                                            } else {
                                                $cartImage = $item['image_url'];
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($cartImage)): ?>
                                            <img src="<?= esc($cartImage) ?>"
                                                 alt="<?= esc($item['product_name']) ?>"
                                                 class="w-24 h-24 object-cover rounded-lg">
                                        <?php else: ?>
                                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                            <?= esc($item['product_name']) ?>
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-2">
                                            <i data-lucide="user" class="w-4 h-4 inline mr-1"></i>
                                            <?= esc($item['farmer_name']) ?>
                                        </p>
                                        <?php if (!empty($item['location'])): ?>
                                            <p class="text-sm text-gray-500 mb-3">
                                                <i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i>
                                                <?= esc($item['location']) ?>
                                            </p>
                                        <?php endif; ?>

                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex items-center gap-4">
                                                <!-- Quantity Update -->
                                                <div class="flex items-center gap-2">
                                                    <label for="quantity_<?= $item['id'] ?>" class="text-sm font-medium text-gray-700">Qty:</label>
                                                    <input
                                                        type="number"
                                                        id="quantity_<?= $item['id'] ?>"
                                                        value="<?= $item['quantity'] ?>"
                                                        data-original-quantity="<?= $item['quantity'] ?>"
                                                        min="1"
                                                        max="99"
                                                        class="w-20 px-3 py-1 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-transparent"
                                                    >
                                                    <button type="button" onclick="updateQuantity('<?= esc($item['id']) ?>')" class="px-2 py-1 bg-primary text-white text-xs rounded hover:bg-primary-hover transition-colors">
                                                        Update
                                                    </button>
                                                    <span class="text-sm text-gray-600"><?= esc($item['unit']) ?></span>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-lg font-bold text-primary">
                                                    ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    ₱<?= number_format($item['price'], 2) ?> per <?= esc($item['unit']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="flex-shrink-0">
                                        <button type="button" onclick="removeFromCart('<?= esc($item['id']) ?>')" class="text-red-500 hover:text-red-700 transition-colors">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </form>

                    <!-- Clear Cart -->
                    <div class="p-6 border-t border-gray-200">
                        <button type="button" onclick="clearCart()" class="text-red-600 hover:text-red-700 text-sm font-medium transition-colors">
                            <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                            Clear Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Checkout Button -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 sticky top-4">
                    <button type="button" onclick="proceedToCheckout()" class="w-full bg-primary text-white text-center px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        <i data-lucide="shopping-bag" class="w-5 h-5 inline mr-2"></i>
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Initialize cart functionality
    initializeCart();
});

function initializeCart() {
    // Add event listeners to checkboxes
    const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCartTotal);
    });

    // Initial total calculation
    updateCartTotal();
}

function updateCartTotal() {
    const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
    let total = 0;
    let selectedCount = 0;

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const itemContainer = checkbox.closest('.p-6');
            const priceText = itemContainer.querySelector('.text-primary').textContent.trim();
            // Extract number from format like "₱1,234.56"
            const priceMatch = priceText.match(/₱([\d,]+\.?\d*)/);
            const itemTotal = priceMatch ? parseFloat(priceMatch[1].replace(',', '')) : 0;

            total += itemTotal;
            selectedCount++;
        }
    });

    console.log('Selected items:', selectedCount, 'Total:', total); // Debug log

    // Update main cart item count
    const cartHeader = document.querySelector('.bg-white.rounded-xl.shadow-md.border .p-6.border-b h2');
    if (cartHeader) {
        cartHeader.textContent = `Cart Items (${selectedCount})`;
    }

    // Update Order Summary section
    const orderSummaryContainer = document.querySelector('.lg\\:col-span-1 .bg-white.rounded-xl.shadow-md .space-y-3');

    if (orderSummaryContainer) {
        console.log('Found order summary container');

        // Find all rows in order summary
        const allRows = orderSummaryContainer.querySelectorAll('.flex.justify-between');
        console.log('Found', allRows.length, 'rows in order summary');

        allRows.forEach((row, index) => {
            const label = row.querySelector('span:first-child');
            const amount = row.querySelector('span:last-child');

            if (label && amount) {
                console.log(`Row ${index}: label="${label.textContent}", amount="${amount.textContent}"`);

                if (label.textContent.includes('Subtotal')) {
                    console.log('Updating subtotal row');
                    // Update subtotal label and amount
                    label.textContent = `Subtotal (${selectedCount} items)`;
                    amount.textContent = `₱${total.toFixed(2)}`;
                } else if (label.textContent.includes('Total')) {
                    console.log('Updating total row');
                    // Update total amount
                    amount.textContent = `₱${total.toFixed(2)}`;
                }
            }
        });
    } else {
        console.log('Order summary container not found');
    }
}

function removeFromCart(cartItemId) {
    // Find the cart item element to get details
    const cartItemElement = document.querySelector(`input[id="quantity_${cartItemId}"]`);
    if (!cartItemElement) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Could not find item details'
        });
        return;
    }

    const itemContainer = cartItemElement.closest('.p-6');
    const productName = itemContainer.querySelector('h3').textContent.trim();
    const farmerName = itemContainer.querySelector('.text-gray-600').textContent.replace('by ', '').trim();
    const quantity = parseInt(cartItemElement.value) || 0;
    const priceText = itemContainer.querySelector('.text-primary').textContent.trim();
    // Extract number from format like "₱1,234.56"
    const priceMatch = priceText.match(/₱([\d,]+\.?\d*)/);
    const itemTotal = priceMatch ? parseFloat(priceMatch[1].replace(',', '')) : 0;
    const unitPrice = quantity > 0 ? itemTotal / quantity : 0;

    Swal.fire({
        title: 'Remove Item',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <h3 class="font-semibold text-lg mb-2">${productName}</h3>
                    <p class="text-gray-600 mb-2">Seller: ${farmerName}</p>
                </div>
                <div class="border-t pt-3">
                    <div class="flex justify-between mb-2">
                        <span>Quantity:</span>
                        <span>${quantity}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Unit Price:</span>
                        <span>₱${unitPrice.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Total (${quantity} items):</span>
                        <span>₱${itemTotal.toFixed(2)}</span>
                    </div>
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between font-bold text-red-600">
                            <span>This will be removed from your cart</span>
                        </div>
                    </div>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Keep in cart'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get CSRF token
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

            fetch(`/cart/remove/${cartItemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    '<?= csrf_token() ?>': csrfToken
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message and reload page
                    Swal.fire({
                        icon: 'success',
                        title: 'Removed!',
                        html: `
                            <div class="text-center">
                                <p class="mb-2">${data.message}</p>
                                <div class="bg-gray-50 p-3 rounded-lg mt-3">
                                    <p class="text-sm text-gray-600">Remaining items in cart: <strong>${data.cart_count}</strong></p>
                                </div>
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: 'Continue Shopping',
                        showCancelButton: true,
                        cancelButtonText: 'View Updated Cart',
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#3b82f6'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            location.reload();
                        } else {
                            window.location.href = '/marketplace';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.'
                });
            });
        }
    });
}

function clearCart() {
    Swal.fire({
        title: 'Clear Cart',
        text: 'Clear entire cart? This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, clear it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading indicator
            Swal.fire({
                title: 'Clearing Cart...',
                text: 'Please wait while we clear your cart.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get CSRF token
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

            fetch('/cart/clear', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Cart Cleared!',
                        text: 'All items have been removed from your cart.',
                        confirmButtonText: 'Continue Shopping',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error('Failed to clear cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to clear cart. Please try again.',
                    confirmButtonColor: '#d33'
                });
            });
        }
    });
}

function proceedToCheckout() {
    const form = document.getElementById('cartForm');
    const formData = new FormData(form);
    const selectedItems = formData.getAll('selected_items[]');

    if (selectedItems.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Items Selected',
            text: 'Please select at least one item to checkout.',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    // Submit form directly to checkout
    form.action = '/checkout';
    form.method = 'POST';
    form.submit();
}

function selectAllItems(checked) {
    const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = checked;
    });
}

function updateQuantity(cartItemId) {
    const quantityInput = document.getElementById(`quantity_${cartItemId}`);

    const newQuantity = parseInt(quantityInput.value);

    if (newQuantity < 1 || newQuantity > 99 || isNaN(newQuantity)) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Quantity',
            text: 'Please enter a quantity between 1 and 99.'
        });
        return;
    }

    const itemContainer = quantityInput.closest('.p-6');
    const unitPriceText = itemContainer.querySelector('.text-gray-500').textContent.trim();
    const unitPriceMatch = unitPriceText.match(/₱([\d,]+\.?\d*)/);
    const unitPrice = unitPriceMatch ? parseFloat(unitPriceMatch[1].replace(',', '')) : 0;
    const newTotal = unitPrice * newQuantity;

    // Update the price display immediately
    const priceSpan = itemContainer.querySelector('.text-primary');
    priceSpan.textContent = `₱${newTotal.toFixed(2)}`;

    // Get CSRF token
    const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

    // Show loading
    const updateButton = itemContainer.querySelector('button[onclick*="updateQuantity"]');
    const originalText = updateButton.textContent;
    updateButton.textContent = 'Updating...';
    updateButton.disabled = true;

    fetch(`/cart/update/${cartItemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            '<?= csrf_token() ?>': csrfToken,
            'quantity': newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update original quantity
            quantityInput.setAttribute('data-original-quantity', newQuantity);
            // Update cart totals
            updateCartTotal();
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Quantity updated successfully.',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            // Revert changes
            const oldQuantity = data.old_quantity || quantityInput.getAttribute('data-original-quantity');
            quantityInput.value = oldQuantity;
            const oldTotal = unitPrice * parseInt(oldQuantity);
            priceSpan.textContent = `₱${oldTotal.toFixed(2)}`;
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: data.message || 'Failed to update quantity.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert changes
        const oldQuantity = quantityInput.getAttribute('data-original-quantity');
        quantityInput.value = oldQuantity;
        const oldTotal = unitPrice * parseInt(oldQuantity);
        priceSpan.textContent = `₱${oldTotal.toFixed(2)}`;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating quantity.'
        });
    })
    .finally(() => {
        // Reset button
        updateButton.textContent = originalText;
        updateButton.disabled = false;
    });
}

// Show checkout success notification if redirected from checkout
document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->has('success') && strpos(session()->get('success'), 'order') !== false): ?>
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Checkout Successful!',
                html: `<?= str_replace("'", "\\'", session()->get('success')) ?>`,
                confirmButtonText: 'Great!',
                confirmButtonColor: '#10b981'
            });
        }
        // Reload cart to show updated items
        setTimeout(function() {
            window.location.reload();
        }, 1500);
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>

