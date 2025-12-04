<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
        <p class="text-gray-600">Review your items before checkout</p>
    </div>

    <!-- Display success message -->
    <?php if (session()->has('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
            <i data-lucide="check-circle" class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-green-800">Success!</p>
                <p class="text-green-700 text-sm"><?= esc(session()->get('success')) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Display error message -->
    <?php if (session()->has('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-red-800">Error!</p>
                <p class="text-red-700 text-sm"><?= esc(session()->get('error')) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <!-- Empty Cart State -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="shopping-cart" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h2 class="text-gray-900 mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Add some fresh products from our marketplace!</p>
                <a href="/marketplace" class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover transition-colors">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Cart Header -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="font-semibold text-gray-900 flex items-center">
                                <i data-lucide="shopping-bag" class="w-5 h-5 text-primary mr-2"></i>
                                Cart Items (<span id="selectedCount">0</span>)
                            </h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       id="selectAll"
                                       onchange="selectAllItems(this.checked)"
                                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary mr-2">
                                <span class="text-sm text-gray-700">Select All</span>
                            </label>
                        </div>
                    </div>
                    
                    <form id="cartForm">
                        <?= csrf_field() ?>
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($cart as $item): ?>
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex gap-4">
                                        <!-- Checkbox -->
                                        <div class="flex-shrink-0 flex items-start pt-1">
                                            <input type="checkbox"
                                                   name="selected_items[]"
                                                   value="<?= esc($item['id']) ?>"
                                                   id="item_<?= $item['id'] ?>"
                                                   class="item-checkbox w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary">
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
                                                     class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                                            <?php else: ?>
                                                <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                    <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex-1">
                                                    <h3 class="text-gray-900 mb-1">
                                                        <?= esc($item['product_name']) ?>
                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-2">
                                                        <span class="flex items-center">
                                                            <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                                            <?= esc($item['farmer_name']) ?>
                                                        </span>
                                                        <?php if (!empty($item['location'])): ?>
                                                            <span class="flex items-center">
                                                                <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                                                <?= esc($item['location']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <!-- Remove Button -->
                                                <button type="button" 
                                                        onclick="removeFromCart('<?= esc($item['id']) ?>')" 
                                                        class="ml-4 text-gray-400 hover:text-red-600 transition-colors"
                                                        title="Remove item">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </div>

                                            <!-- Price and Quantity Row -->
                                            <div class="flex flex-wrap items-center justify-between gap-4 mt-4">
                                                <!-- Quantity Controls -->
                                                <div class="flex items-center gap-3">
                                                    <span class="text-sm text-gray-700">Quantity:</span>
                                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                                        <button type="button" 
                                                                onclick="decrementQuantity('<?= esc($item['id']) ?>')"
                                                                class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 transition-colors">
                                                            <i data-lucide="minus" class="w-4 h-4"></i>
                                                        </button>
                                                        <input type="number"
                                                               id="quantity_<?= $item['id'] ?>"
                                                               value="<?= $item['quantity'] ?>"
                                                               data-original-quantity="<?= $item['quantity'] ?>"
                                                               data-unit-price="<?= $item['price'] ?>"
                                                               min="1"
                                                               max="99"
                                                               onchange="handleQuantityChange('<?= esc($item['id']) ?>')"
                                                               class="w-16 px-3 py-2 text-center border-x border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary">
                                                        <button type="button" 
                                                                onclick="incrementQuantity('<?= esc($item['id']) ?>')"
                                                                class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 transition-colors">
                                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                                        </button>
                                                    </div>
                                                    <span class="text-sm text-gray-600"><?= esc($item['unit']) ?></span>
                                                </div>

                                                <!-- Price -->
                                                <div class="text-right">
                                                    <p class="text-primary item-total" id="total_<?= $item['id'] ?>">
                                                        ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        ₱<?= number_format($item['price'], 2) ?> / <?= esc($item['unit']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </form>

                    <!-- Cart Footer -->
                    <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
                        <button type="button" 
                                onclick="clearCart()" 
                                class="text-red-600 hover:text-red-700 text-sm font-medium transition-colors flex items-center">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                            Clear Cart
                        </button>
                        <a href="/marketplace" class="text-primary hover:text-primary-hover text-sm font-medium transition-colors flex items-center">
                            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 sticky top-4">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <h2 class="font-semibold text-gray-900 flex items-center">
                            <i data-lucide="receipt" class="w-5 h-5 text-primary mr-2"></i>
                            Order Summary
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-700">
                                <span id="subtotalLabel">Subtotal (0 items)</span>
                                <span id="subtotalAmount">₱0.00</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Shipping</span>
                                <span class="text-sm text-gray-500">Calculated at checkout</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-gray-900">
                                    <span>Total</span>
                                    <span id="totalAmount" class="text-primary">₱0.00</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" 
                                onclick="proceedToCheckout()" 
                                class="w-full bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover transition-colors flex items-center justify-center mb-3">
                            <i data-lucide="shopping-bag" class="w-5 h-5 mr-2"></i>
                            Proceed to Checkout
                        </button>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-2">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-blue-900 mb-1">Secure Checkout</p>
                                    <p class="text-xs text-blue-700">Your payment information is protected</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCartTotal);
    });

    // Initial total calculation
    updateCartTotal();
}

function updateCartTotal() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    let total = 0;
    let selectedCount = 0;

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const itemContainer = checkbox.closest('.p-6');
            const priceText = itemContainer.querySelector('.item-total').textContent.trim();
            const priceMatch = priceText.match(/₱([\d,]+\.?\d*)/);
            const itemTotal = priceMatch ? parseFloat(priceMatch[1].replace(/,/g, '')) : 0;

            total += itemTotal;
            selectedCount++;
        }
    });

    // Update selected count in header
    const selectedCountElement = document.getElementById('selectedCount');
    if (selectedCountElement) {
        selectedCountElement.textContent = selectedCount;
    }

    // Update order summary
    const subtotalLabel = document.getElementById('subtotalLabel');
    const subtotalAmount = document.getElementById('subtotalAmount');
    const totalAmount = document.getElementById('totalAmount');

    if (subtotalLabel) {
        subtotalLabel.textContent = `Subtotal (${selectedCount} ${selectedCount === 1 ? 'item' : 'items'})`;
    }
    if (subtotalAmount) {
        subtotalAmount.textContent = `₱${total.toFixed(2)}`;
    }
    if (totalAmount) {
        totalAmount.textContent = `₱${total.toFixed(2)}`;
    }

    // Update select all checkbox state
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
    }
}

function selectAllItems(checked) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = checked;
    });
    updateCartTotal();
}

function incrementQuantity(cartItemId) {
    const input = document.getElementById(`quantity_${cartItemId}`);
    const currentValue = parseInt(input.value) || 1;
    if (currentValue < 99) {
        input.value = currentValue + 1;
        handleQuantityChange(cartItemId);
    }
}

function decrementQuantity(cartItemId) {
    const input = document.getElementById(`quantity_${cartItemId}`);
    const currentValue = parseInt(input.value) || 1;
    if (currentValue > 1) {
        input.value = currentValue - 1;
        handleQuantityChange(cartItemId);
    }
}

function handleQuantityChange(cartItemId) {
    const quantityInput = document.getElementById(`quantity_${cartItemId}`);
    const newQuantity = parseInt(quantityInput.value);

    if (newQuantity < 1 || newQuantity > 99 || isNaN(newQuantity)) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Quantity',
            text: 'Please enter a quantity between 1 and 99.',
            confirmButtonColor: '#f59e0b'
        });
        quantityInput.value = quantityInput.getAttribute('data-original-quantity');
        return;
    }

    updateQuantity(cartItemId);
}

function updateQuantity(cartItemId) {
    const quantityInput = document.getElementById(`quantity_${cartItemId}`);
    const newQuantity = parseInt(quantityInput.value);
    const unitPrice = parseFloat(quantityInput.getAttribute('data-unit-price'));
    const newTotal = unitPrice * newQuantity;

    // Update the price display immediately
    const totalElement = document.getElementById(`total_${cartItemId}`);
    totalElement.textContent = `₱${newTotal.toFixed(2)}`;

    // Update cart totals
    updateCartTotal();

    // Get CSRF token
    const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

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
            quantityInput.setAttribute('data-original-quantity', newQuantity);
            // Refresh icons after DOM update
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        } else {
            // Revert changes
            const oldQuantity = data.old_quantity || quantityInput.getAttribute('data-original-quantity');
            quantityInput.value = oldQuantity;
            const oldTotal = unitPrice * parseInt(oldQuantity);
            totalElement.textContent = `₱${oldTotal.toFixed(2)}`;
            updateCartTotal();
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: data.message || 'Failed to update quantity.',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const oldQuantity = quantityInput.getAttribute('data-original-quantity');
        quantityInput.value = oldQuantity;
        const oldTotal = unitPrice * parseInt(oldQuantity);
        totalElement.textContent = `₱${oldTotal.toFixed(2)}`;
        updateCartTotal();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating quantity.',
            confirmButtonColor: '#ef4444'
        });
    });
}

function removeFromCart(cartItemId) {
    const quantityInput = document.getElementById(`quantity_${cartItemId}`);
    if (!quantityInput) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Could not find item details',
            confirmButtonColor: '#ef4444'
        });
        return;
    }

    const itemContainer = quantityInput.closest('.p-6');
    const productName = itemContainer.querySelector('h3').textContent.trim();
    const farmerElement = itemContainer.querySelector('.text-gray-600');
    const farmerName = farmerElement ? farmerElement.textContent.trim() : 'Unknown';
    const quantity = parseInt(quantityInput.value) || 0;
    const unitPrice = parseFloat(quantityInput.getAttribute('data-unit-price'));
    const itemTotal = unitPrice * quantity;

    Swal.fire({
        title: 'Remove Item?',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <h3 class="font-semibold text-lg mb-2">${productName}</h3>
                    <p class="text-gray-600 mb-2">Seller: ${farmerName}</p>
                </div>
                <div class="border-t pt-3">
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="text-gray-600">Quantity:</span>
                        <span class="font-medium">${quantity}</span>
                    </div>
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="text-gray-600">Unit Price:</span>
                        <span class="font-medium">₱${unitPrice.toFixed(2)}</span>
                    </div>
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-bold">Total:</span>
                            <span class="font-bold text-red-600">₱${itemTotal.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Keep in cart'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Removed!',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            });
        }
    });
}

function clearCart() {
    Swal.fire({
        title: 'Clear Entire Cart?',
        text: 'This will remove all items from your cart. This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, clear cart!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Clearing Cart...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/cart/clear', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
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
                    confirmButtonColor: '#ef4444'
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
            text: 'Please select at least one item to proceed to checkout.',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    // Submit form to checkout
    form.action = '/checkout';
    form.method = 'POST';
    form.submit();
}
</script>

<?= $this->endSection() ?>
