<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/marketplace" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                        <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                        Marketplace
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2"><?= esc($product['name']) ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="space-y-4">
                <?php if ($product['image_url']): ?>
                    <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-96 object-cover rounded-xl shadow-lg">
                <?php else: ?>
                    <div class="w-full h-96 bg-gradient-to-br from-green-100 to-green-200 rounded-xl shadow-lg flex items-center justify-center">
                        <i data-lucide="package" class="w-24 h-24 text-green-600"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <div>
                    <div class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full mb-3">
                        <?= ucfirst(esc($product['category'])) ?>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= esc($product['name']) ?></h1>
                    <p class="text-primary text-4xl font-bold mb-4">
                        ₱<?= number_format($product['price'], 2) ?>
                        <span class="text-lg text-gray-600">/ <?= esc($product['unit']) ?></span>
                    </p>

                    <div class="space-y-2 text-gray-600">
                        <div class="flex items-center">
                            <i data-lucide="map-pin" class="w-5 h-5 mr-2 text-gray-400"></i>
                            <span>Location: <?= esc($product['location'] ?? 'Nasugbu') ?></span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="user" class="w-5 h-5 mr-2 text-gray-400"></i>
                            <span>Farmer: <?= esc($product['farmer_name']) ?></span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="package-check" class="w-5 h-5 mr-2 text-gray-400"></i>
                            <span>Stock: <?= esc($product['stock_quantity']) ?> <?= esc($product['unit']) ?> available</span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="calendar" class="w-5 h-5 mr-2 text-gray-400"></i>
                            <span>Listed: <?= date('M j, Y', strtotime($product['created_at'])) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <?php if (!empty($product['description'])): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed"><?= nl2br(esc($product['description'])) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Add to Cart Form -->
                <?php if (session()->get('logged_in') && in_array(session()->get('user_role'), ['buyer', 'admin'])): ?>
                    <form action="/cart/add" method="POST" class="space-y-4">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="decrementQuantity()" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                    <i data-lucide="minus" class="w-4 h-4"></i>
                                </button>
                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="<?= $product['stock_quantity'] ?>"
                                    class="w-20 text-center px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                >
                                <button type="button" onclick="incrementQuantity()" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                                <span class="text-gray-600"><?= esc($product['unit']) ?></span>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary-hover transition-colors font-semibold flex items-center justify-center"
                            <?= $product['stock_quantity'] < 1 ? 'disabled class="opacity-50 cursor-not-allowed"' : '' ?>
                        >
                            <i data-lucide="shopping-cart" class="w-5 h-5 mr-2"></i>
                            Add to Cart
                        </button>
                    </form>
                <?php else: ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-600 mr-2"></i>
                            <p class="text-yellow-800">
                                Please <a href="/auth/login" class="underline hover:text-yellow-900">login as a buyer</a> to add items to your cart.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Farmer Contact -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Contact Farmer</h3>
                    <p class="text-gray-600 mb-3">Interested in this product? Get in touch with the farmer directly.</p>
                    <a href="/messages/compose?to=<?= $product['farmer_id'] ?>" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i data-lucide="message-circle" class="w-4 h-4 mr-2"></i>
                        Send Message
                    </a>
                </div>
            </div>
        </div>

        <!-- Other Products from Same Farmer -->
        <?php if (!empty($other_products)): ?>
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">More from <?= esc($product['farmer_name']) ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($other_products as $other_product): ?>
                        <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 hover:border-primary transition-all overflow-hidden">
                            <?php if ($other_product['image_url']): ?>
                                <img src="<?= esc($other_product['image_url']) ?>" alt="<?= esc($other_product['name']) ?>" class="w-full h-32 object-cover">
                            <?php else: ?>
                                <div class="w-full h-32 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                    <i data-lucide="package" class="w-8 h-8 text-green-600"></i>
                                </div>
                            <?php endif; ?>

                            <div class="p-4">
                                <h3 class="text-lg font-semibold mb-1"><?= esc($other_product['name']) ?></h3>
                                <p class="text-primary font-bold mb-2">
                                    ₱<?= number_format($other_product['price'], 2) ?>
                                    <span class="text-sm text-gray-600">/ <?= esc($other_product['unit']) ?></span>
                                </p>
                                <a href="/marketplace/product/<?= $other_product['id'] ?>" class="block w-full bg-gray-100 text-gray-700 py-2 rounded-lg text-center hover:bg-gray-200 transition-colors text-sm">
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

<script>
function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}
</script>

<?= $this->endSection() ?>