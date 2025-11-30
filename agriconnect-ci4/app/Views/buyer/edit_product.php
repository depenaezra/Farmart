<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="/buyer/inventory" class="inline-flex items-center text-primary hover:text-primary-hover mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to My Listings
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
            <p class="text-gray-600 mt-2">Update your product information</p>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="e.g., Fresh Tomatoes"
                        >
                        <?php if (isset($errors['name'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['name'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Describe your product..."
                        ><?= old('description', $product['description']) ?></textarea>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                        <select
                            id="category"
                            name="category"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="e.g., kilo, piece"
                        >
                        <?php if (isset($errors['unit'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['unit'] ?></p>
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="0"
                        >
                        <?php if (isset($errors['stock_quantity'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['stock_quantity'] ?></p>
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="e.g., Brgy. Aga, Nasugbu"
                        >
                        <?php if (isset($errors['location'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['location'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Image Upload -->
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Product Image</label>
                        <?php if (!empty($product['image_url'])): ?>
                            <div class="mb-4">
                                <img src="<?= base_url($product['image_url']) ?>" alt="Current product image" class="w-32 h-32 object-cover rounded-lg border">
                                <p class="text-sm text-gray-500 mt-2">Current image. Upload a new one to replace it.</p>
                            </div>
                        <?php endif; ?>
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-hover"
                        >
                        <p class="text-sm text-gray-500 mt-1">Upload a new image to replace the current one (optional)</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex gap-4">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        <i data-lucide="save" class="w-5 h-5 inline mr-2"></i>
                        Update Product
                    </button>
                    <a href="/buyer/inventory" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>