<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="/buyer/inventory" class="inline-flex items-center text-primary hover:text-primary-hover mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to My Listings
            </a>
            <h1 class="text-3xl font-bold text-gray-900">
                <?= isset($is_spoiled) && $is_spoiled ? 'Update Spoiled Product Price' : 'Edit Product' ?>
            </h1>
            <p class="text-gray-600 mt-2">
                <?= isset($is_spoiled) && $is_spoiled ? 'Update the price for your spoiled product' : 'Update your product information' ?>
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <?php if (isset($is_spoiled) && $is_spoiled): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 mr-2"></i>
                        <span class="text-red-800 font-medium">This product is spoiled</span>
                    </div>
                    <p class="text-red-700 text-sm mt-1">You can only update the price for spoiled products. Other details are locked to prevent changes.</p>
                </div>
            <?php endif; ?>

            <form action="/buyer/products/edit/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Product Name *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?= old('name', $product['name']) ?>"
                            required
                            <?= isset($is_spoiled) && $is_spoiled ? 'readonly' : '' ?>
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($is_spoiled) && $is_spoiled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                            placeholder="e.g., Fresh Tomatoes"
                        >
                        <?php if (isset($errors['name'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['name'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            <?= isset($is_spoiled) && $is_spoiled ? 'readonly' : '' ?>
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($is_spoiled) && $is_spoiled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                            placeholder="Describe your product..."
                        ><?= old('description', $product['description']) ?></textarea>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                        <select
                            id="category"
                            name="category"
                            required
                            <?= isset($is_spoiled) && $is_spoiled ? 'disabled' : '' ?>
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($is_spoiled) && $is_spoiled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                        >
                            <option value="">Select Category</option>
                            <option value="vegetables" <?= old('category', $product['category']) === 'vegetables' ? 'selected' : '' ?>>Vegetables</option>
                            <option value="fruits" <?= old('category', $product['category']) === 'fruits' ? 'selected' : '' ?>>Fruits</option>
                            <option value="grains" <?= old('category', $product['category']) === 'grains' ? 'selected' : '' ?>>Grains</option>
                            <option value="other" <?= old('category', $product['category']) === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <?php if (isset($errors['category'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['category'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                    </div>

                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-semibold text-gray-700 mb-2">Unit *</label>
                        <input
                            type="text"
                            id="unit"
                            name="unit"
                            value="<?= old('unit', $product['unit']) ?>"
                            required
                            <?= isset($is_spoiled) && $is_spoiled ? 'readonly' : '' ?>
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($is_spoiled) && $is_spoiled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                            placeholder="e.g., kilo, piece"
                        >
                        <?php if (isset($errors['unit'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['unit'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price (₱) *</label>
                        <input
                            type="number"
                            id="price"
                            name="price"
                            value="<?= old('price', $product['price']) ?>"
                            required
                            min="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="0.00"
                        >
                        <?php if (isset($errors['price'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['price'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-blue-600 text-xs mt-1 font-medium">Update price for spoiled product</p>
                        <?php endif; ?>
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-sm font-semibold text-gray-700 mb-2">Stock Quantity *</label>
                        <input
                            type="number"
                            id="stock_quantity"
                            name="stock_quantity"
                            value="<?= old('stock_quantity', $product['stock_quantity']) ?>"
                            required
                            min="0"
                            <?= isset($is_spoiled) && $is_spoiled ? 'readonly' : '' ?>
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($is_spoiled) && $is_spoiled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                            placeholder="0"
                        >
                        <?php if (isset($errors['stock_quantity'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['stock_quantity'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                    </div>

                    <!-- Harvest Date -->
                    <div>
                        <label for="harvest_date" class="block text-sm font-semibold text-gray-700 mb-2">Harvest Date</label>
                        <input
                            type="date"
                            id="harvest_date"
                            name="harvest_date"
                            value="<?= old('harvest_date', $product['harvest_date'] ?? '') ?>"
                            <?= isset($is_spoiled) && $is_spoiled ? 'readonly' : '' ?>
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($is_spoiled) && $is_spoiled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                        >
                        <p class="text-sm text-gray-500 mt-1">Leave blank if unknown. Used for spoilage prediction.</p>
                        <?php if (isset($errors['harvest_date'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['harvest_date'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                    </div>

                    <!-- Shelf Life Days -->
                    <div>
                        <label for="shelf_life_days" class="block text-sm font-semibold text-gray-700 mb-2">Shelf Life (Days)</label>
                        <input
                            type="number"
                            id="shelf_life_days"
                            name="shelf_life_days"
                            value="<?= old('shelf_life_days', $product['shelf_life_days'] ?? '') ?>"
                            min="1"
                            <?= isset($is_spoiled) && $is_spoiled ? 'readonly' : '' ?>
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($is_spoiled) && $is_spoiled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                            placeholder="e.g., 7"
                        >
                        <p class="text-sm text-gray-500 mt-1">Leave blank to use default based on category.</p>
                        <?php if (isset($errors['shelf_life_days'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['shelf_life_days'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                    </div>

                    <!-- Location -->
                    <div class="md:col-span-2">
                        <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                        <input
                            type="text"
                            id="location"
                            name="location"
                            value="<?= old('location', $product['location']) ?>"
                            required
                            <?= isset($is_spoiled) && $is_spoiled ? 'readonly' : '' ?>
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($is_spoiled) && $is_spoiled ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                            placeholder="e.g., Brgy. Aga, Nasugbu"
                        >
                        <?php if (isset($errors['location'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['location'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($is_spoiled) && $is_spoiled): ?>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                    </div>

                    <!-- Image Upload -->
                    <div class="md:col-span-2">
                        <label for="images" class="block text-sm font-semibold text-gray-700 mb-2">Product Images (Max 5)</label>
                        <?php
                        $currentImages = [];
                        if (!empty($product['image_url'])) {
                            $decoded = json_decode($product['image_url'], true);
                            if (is_array($decoded)) {
                                $currentImages = $decoded;
                            } else {
                                $currentImages = [$product['image_url']];
                            }
                        }
                        ?>
                        <?php if (!empty($currentImages)): ?>
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Images:</p>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                                    <?php foreach ($currentImages as $index => $image): ?>
                                        <div class="relative">
                                            <img src="<?= base_url($image) ?>" alt="Product image <?= $index + 1 ?>" class="w-full h-20 object-cover rounded-lg border">
                                            <?php if (!isset($is_spoiled) || !$is_spoiled): ?>
                                                <button type="button" onclick="removeImage(<?= $index ?>)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                                    ×
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (!isset($is_spoiled) || !$is_spoiled): ?>
                                    <p class="text-sm text-gray-500 mt-2">Upload new images to add or replace existing ones.</p>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500 mt-2">Image management disabled for spoiled products.</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!isset($is_spoiled) || !$is_spoiled): ?>
                            <input
                                type="file"
                                id="images"
                                name="images[]"
                                accept="image/*"
                                multiple
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-hover"
                            >
                            <p class="text-sm text-gray-500 mt-1">Select up to 5 images. Supported formats: JPG, PNG, GIF (Max 5MB each)</p>
                        <?php else: ?>
                            <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 text-center py-4">
                                Image upload disabled for spoiled products
                            </div>
                            <p class="text-gray-500 text-xs mt-1">Locked for spoiled products</p>
                        <?php endif; ?>
                        <input type="hidden" name="existing_images" value='<?= json_encode($currentImages) ?>'>
                        <input type="hidden" name="removed_images" id="removed_images" value="">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex gap-4">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        <i data-lucide="save" class="w-5 h-5 inline mr-2"></i>
                        <?= isset($is_spoiled) && $is_spoiled ? 'Update Price' : 'Update Product' ?>
                    </button>
                    <a href="/buyer/inventory" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function removeImage(index) {
    const existingImagesInput = document.querySelector('input[name="existing_images"]');
    const removedImagesInput = document.getElementById('removed_images');

    let existingImages = JSON.parse(existingImagesInput.value);
    let removedImages = removedImagesInput.value ? JSON.parse(removedImagesInput.value) : [];

    // Add to removed list
    removedImages.push(existingImages[index]);
    removedImagesInput.value = JSON.stringify(removedImages);

    // Remove from existing images
    existingImages.splice(index, 1);
    existingImagesInput.value = JSON.stringify(existingImages);

    // Remove the image element from DOM
    event.target.closest('.relative').remove();
}
</script>

<?= $this->endSection() ?>