<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <a href="/marketplace" class="inline-flex items-center text-primary hover:text-primary-hover mb-6">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Marketplace
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php
                $images = [];
                if (!empty($product['image_url'])) {
                    $decoded = json_decode($product['image_url'], true);
                    if (is_array($decoded)) {
                        $images = $decoded;
                    } else {
                        $images = [$product['image_url']];
                    }
                }
                ?>
                <?php if (!empty($images)): ?>
                    <?php if (count($images) === 1): ?>
                        <img src="<?= esc($images[0]) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-64 object-cover">
                    <?php else: ?>
                        <div class="relative">
                            <img id="mainImage" src="<?= esc($images[0]) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-64 object-cover">
                            <button onclick="prevImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all" id="prevBtn">
                                <i data-lucide="chevron-left" class="w-6 h-6"></i>
                            </button>
                            <button onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all" id="nextBtn">
                                <i data-lucide="chevron-right" class="w-6 h-6"></i>
                            </button>
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                <?php foreach ($images as $index => $image): ?>
                                    <button onclick="showImage(<?= $index ?>)" class="w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-75 transition-all" id="dot-<?= $index ?>"></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="w-full h-64 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                        <i data-lucide="package" class="w-16 h-16 text-green-600"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Details -->
            <div class="bg-white rounded-lg shadow-md border-2 border-gray-200 p-4">
                <div class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded mb-2">
                    <?= ucfirst(esc($product['category'])) ?>
                </div>

                <h1 class="text-xl font-bold text-gray-900 mb-2"><?= esc($product['name']) ?></h1>

                <div class="text-primary text-2xl font-bold mb-3">
                    ₱<?= number_format($product['price'], 2) ?>
                    <span class="text-sm text-gray-600">/ <?= esc($product['unit']) ?></span>
                </div>


                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-gray-600 text-sm">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>
                        <span>Location: <?= esc($product['farmer_location'] ?? 'Nasugbu') ?></span>
                    </div>
                    <div class="flex items-center text-gray-600 text-sm">
                        <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                        <span>Seller: <?= esc($product['farmer_name']) ?></span>
                    </div>
                    <div class="flex items-center text-gray-600 text-sm">
                        <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                        <span>Contact: <?= esc($product['farmer_phone']) ?></span>
                    </div>
                    <div class="flex items-center text-gray-600 text-sm">
                        <i data-lucide="package-check" class="w-4 h-4 mr-2"></i>
                        <span>Stock: <?= esc($product['stock_quantity']) ?> <?= esc($product['unit']) ?> available</span>
                    </div>
                    <?php if ($product['cooperative']): ?>
                    <div class="flex items-center text-gray-600 text-sm">
                        <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                        <span>Cooperative: <?= esc($product['cooperative']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($product['description']): ?>
                <div class="mb-4">
                    <h3 class="text-base font-semibold mb-1">Description</h3>
                    <p class="text-gray-700 text-sm leading-relaxed"><?= nl2br(esc($product['description'])) ?></p>
                </div>
                <?php endif; ?>
<!-- Quantity Selector & Add to Cart -->
<?php if ($product['farmer_id'] != session()->get('user_id')): ?>
<div class="border-t pt-6 mt-6">
    <label class="block text-sm font-semibold text-gray-700 mb-3">Quantity</label>
    <div class="flex items-center gap-6">
        <!-- Quantity Controls -->
        <div class="flex items-center gap-2">
            <button onclick="decrementQuantity()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1.5 px-2 rounded-lg transition-colors">
                <i data-lucide="minus" class="w-4 h-4"></i>
            </button>
            <input type="number" id="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>" class="w-16 text-center border-2 border-gray-300 rounded-lg py-1.5 px-2 font-semibold focus:border-primary focus:outline-none">
            <button onclick="incrementQuantity()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1.5 px-2 rounded-lg transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
            </button>
            <span class="text-sm text-gray-600 ml-1"><?= esc($product['unit']) ?></span>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button onclick="addToCart()" class="aspect-square bg-primary hover:bg-primary-hover text-white font-bold rounded-lg transition-colors flex flex-col items-center justify-center gap-1 p-2 min-w-[80px]">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                <span class="text-xs">Add to Cart</span>
            </button>

            <button onclick="buyNow()" class="aspect-square bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors flex flex-col items-center justify-center gap-1 p-2 min-w-[80px]">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
                <span class="text-xs">Buy Now</span>
            </button>

            <button onclick="reportPost(<?= $product['id'] ?>, 'product')" class="aspect-square bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors flex flex-col items-center justify-center gap-1 p-2 min-w-[80px]">
                <i data-lucide="flag" class="w-5 h-5"></i>
                <span class="text-xs">Report</span>
            </button>
        </div>
    </div>
</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Buttons (for product owners) -->
        <?php if ($product['farmer_id'] == session()->get('user_id')): ?>
        <div class="mt-6 flex gap-4 justify-center">
            <a href="/buyer/products/edit/<?= $product['id'] ?>" class="flex-1 max-w-xs bg-blue-500 text-white py-3 px-6 rounded-lg hover:bg-blue-600 transition-colors font-semibold text-center">
                Edit Product
            </a>
            <form action="/buyer/products/delete/<?= $product['id'] ?>" method="POST" class="flex-1 max-w-xs">
                <?= csrf_field() ?>
                <button type="submit" class="w-full bg-red-500 text-white py-3 px-6 rounded-lg hover:bg-red-600 transition-colors font-semibold" onclick="return confirm('Are you sure you want to delete this product?')">
                    Delete Product
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Other Products from Same Farmer -->
        <?php if (!empty($other_products)): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">More from <?= esc($product['farmer_name']) ?></h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($other_products as $other): ?>
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all overflow-hidden">
                    <?php
                    $otherPreviewImage = null;
                    if (!empty($other['image_url'])) {
                        $decoded = json_decode($other['image_url'], true);
                        if (is_array($decoded)) {
                            $otherPreviewImage = $decoded[0]; // Show first image
                        } else {
                            $otherPreviewImage = $other['image_url'];
                        }
                    }
                    ?>
                    <?php if ($otherPreviewImage): ?>
                        <img src="<?= esc($otherPreviewImage) ?>" alt="<?= esc($other['name']) ?>" class="w-full h-32 object-cover">
                    <?php else: ?>
                        <div class="w-full h-32 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <i data-lucide="package" class="w-8 h-8 text-green-600"></i>
                        </div>
                    <?php endif; ?>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-1"><?= esc($other['name']) ?></h3>
                        <p class="text-primary font-bold">
                            ₱<?= number_format($other['price'], 2) ?>
                            <span class="text-sm text-gray-600">/ <?= esc($other['unit']) ?></span>
                        </p>
                        <a href="/marketplace/product/<?= $other['id'] ?>" class="block w-full bg-gray-100 text-gray-700 py-2 rounded-lg text-center hover:bg-gray-200 transition-colors mt-2 text-sm">
                            View Details
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold mb-4">Report Content</h3>
            <form id="reportForm">
                <input type="hidden" id="reportedType" name="reported_type">
                <input type="hidden" id="reportedId" name="reported_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <select id="reportReason" name="reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                        <option value="">Select a reason</option>
                        <option value="spam">Spam</option>
                        <option value="inappropriate">Inappropriate content</option>
                        <option value="harassment">Harassment</option>
                        <option value="false_information">False information</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (optional)</label>
                    <textarea id="reportDescription" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Provide more details..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeReportModal()" class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function reportPost(id, type) {
    document.getElementById('reportedId').value = id;
    document.getElementById('reportedType').value = type;
    document.getElementById('reportModal').classList.remove('hidden');
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
    document.getElementById('reportForm').reset();
}

function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const currentValue = parseInt(input.value);
    if (currentValue < max) {
        input.value = currentValue + 1;
        updateBuyNowQuantity();
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    const min = parseInt(input.getAttribute('min'));
    const currentValue = parseInt(input.value);
    if (currentValue > min) {
        input.value = currentValue - 1;
        updateBuyNowQuantity();
    }
}

function updateBuyNowQuantity() {
    const quantityInput = document.getElementById('quantity');
    const buyNowQuantity = document.getElementById('buy_now_quantity');
    buyNowQuantity.value = quantityInput.value;
}

function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const productId = <?= $product['id'] ?>;
    const productName = '<?= esc($product['name']) ?>';
    const productPrice = parseFloat('<?= $product['price'] ?>');
    const productUnit = '<?= esc($product['unit']) ?>';
    const farmerName = '<?= esc($product['farmer_name']) ?>';

    const subtotal = (productPrice * quantity).toFixed(2);

    // Show confirmation with order summary
    Swal.fire({
        title: 'Add to Cart',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <h3 class="font-semibold text-lg mb-2">${productName}</h3>
                    <p class="text-gray-600 mb-2">Seller: ${farmerName}</p>
                </div>
                <div class="border-t pt-3">
                    <div class="flex justify-between mb-2">
                        <span>Price per ${productUnit}:</span>
                        <span>₱${productPrice.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Quantity:</span>
                        <span>${quantity} ${productUnit}</span>
                    </div>
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span>₱${subtotal}</span>
                        </div>
                    </div>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Add to Cart',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get CSRF token
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    '<?= csrf_token() ?>': csrfToken,
                    'product_id': productId,
                    'quantity': quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success SweetAlert with order summary
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        html: `
                            <div class="text-center">
                                <p class="mb-2">${data.message}</p>
                                <div class="bg-gray-50 p-3 rounded-lg mt-3">
                                    <p class="text-sm text-gray-600">Total items in cart: <strong>${data.cart_count}</strong></p>
                                </div>
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: 'Continue Shopping',
                        showCancelButton: true,
                        cancelButtonText: 'View Cart',
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#3b82f6'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            // View Cart clicked
                            window.location.href = '/cart';
                        }
                    });

                    // Update cart count if element exists
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement && data.cart_count !== undefined) {
                        cartCountElement.textContent = data.cart_count;
                    }
                } else {
                    // Show error SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    showConfirmButton: true,
                    confirmButtonColor: '#d33'
                });
            });
        }
    });
}

function buyNow() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const productId = <?= $product['id'] ?>;
    const productName = '<?= esc($product['name']) ?>';
    const productPrice = parseFloat('<?= $product['price'] ?>');
    const productUnit = '<?= esc($product['unit']) ?>';
    const farmerName = '<?= esc($product['farmer_name']) ?>';

    const subtotal = (productPrice * quantity).toFixed(2);

    // Show confirmation with order summary
    Swal.fire({
        title: 'Buy Now',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <h3 class="font-semibold text-lg mb-2">${productName}</h3>
                    <p class="text-gray-600 mb-2">Seller: ${farmerName}</p>
                </div>
                <div class="border-t pt-3">
                    <div class="flex justify-between mb-2">
                        <span>Price per ${productUnit}:</span>
                        <span>₱${productPrice.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Quantity:</span>
                        <span>${quantity} ${productUnit}</span>
                    </div>
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span>₱${subtotal}</span>
                        </div>
                    </div>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Proceed to Checkout',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a form and submit to direct checkout
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/checkout/direct';

            // Add CSRF token
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Add product_id
            const productIdInput = document.createElement('input');
            productIdInput.type = 'hidden';
            productIdInput.name = 'product_id';
            productIdInput.value = productId;
            form.appendChild(productIdInput);

            // Add quantity
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = quantity;
            form.appendChild(quantityInput);

            // Append form to body and submit
            document.body.appendChild(form);
            form.submit();
        }
    });
}


let currentImageIndex = 0;
const productImages = <?= json_encode($images) ?>;

function showImage(index) {
    currentImageIndex = index;
    const mainImage = document.getElementById('mainImage');
    mainImage.src = productImages[index];

    // Update dots
    document.querySelectorAll('[id^="dot-"]').forEach((dot, i) => {
        if (i === index) {
            dot.classList.remove('bg-opacity-50');
            dot.classList.add('bg-opacity-100');
        } else {
            dot.classList.remove('bg-opacity-100');
            dot.classList.add('bg-opacity-50');
        }
    });
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
    showImage(currentImageIndex);
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % productImages.length;
    showImage(currentImageIndex);
}

// Initialize first dot as active
document.addEventListener('DOMContentLoaded', function() {
    if (productImages && productImages.length > 1) {
        showImage(0);
    }
});

document.getElementById('reportForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/report', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            try {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'Success',
                    showCloseButton: true,
                    showClass: { popup: 'animate__animated animate__bounceIn' },
                    hideClass: { popup: 'animate__animated animate__fadeOutUp' },
                    timer: 2000
                });
            } catch (e) {
                alert(data.message);
            }
            closeReportModal();
        } else {
            try {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred',
                    showCloseButton: true,
                    showClass: { popup: 'animate__animated animate__shakeX' },
                    hideClass: { popup: 'animate__animated animate__fadeOutUp' }
                });
            } catch (e) {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        try {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred. Please try again.',
                showCloseButton: true
            });
        } catch (e) {
            alert('An error occurred. Please try again.');
        }
    });
});
</script>

<?= $this->endSection() ?>