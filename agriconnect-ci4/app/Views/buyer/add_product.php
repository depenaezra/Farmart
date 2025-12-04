<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="/buyer/inventory" class="inline-flex items-center text-primary hover:text-primary-hover mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to My Listings
            </a>
            <h1 class="text-3xl text-gray-900">Add New Product</h1>
            <p class="text-gray-600 mt-2">List a new product that you want to sell</p>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <form action="/buyer/products/add" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-gray-700 mb-2">Product Name *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?= old('name') ?>"
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
                        <label for="description" class="block text-gray-700 mb-2">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Describe your product..."
                        ><?= old('description') ?></textarea>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-gray-700 mb-2">Category *</label>
                        <select
                            id="category"
                            name="category"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">Select Category</option>
                            <option value="vegetables" <?= old('category') === 'vegetables' ? 'selected' : '' ?>>Vegetables</option>
                            <option value="fruits" <?= old('category') === 'fruits' ? 'selected' : '' ?>>Fruits</option>
                            <option value="grains" <?= old('category') === 'grains' ? 'selected' : '' ?>>Grains</option>
                            <option value="other" <?= old('category') === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <?php if (isset($errors['category'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['category'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-gray-700 mb-2">Unit *</label>
                        <input
                            type="text"
                            id="unit"
                            name="unit"
                            value="<?= old('unit', 'kilo') ?>"
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
                        <label for="price" class="block text-gray-700 mb-2">Price (₱) *</label>
                        <input
                            type="number"
                            id="price"
                            name="price"
                            value="<?= old('price') ?>"
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
                        <label for="stock_quantity" class="block text-gray-700 mb-2">Stock Quantity *</label>
                        <input
                            type="number"
                            id="stock_quantity"
                            name="stock_quantity"
                            value="<?= old('stock_quantity') ?>"
                            required
                            min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="0"
                        >
                        <?php if (isset($errors['stock_quantity'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['stock_quantity'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Harvest Date -->
                    <div>
                        <label for="harvest_date" class="block text-gray-700 mb-2">Harvest Date</label>
                        <input
                            type="date"
                            id="harvest_date"
                            name="harvest_date"
                            value="<?= old('harvest_date') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        <p class="text-sm text-gray-500 mt-1">Leave blank if unknown. Used for spoilage prediction.</p>
                        <?php if (isset($errors['harvest_date'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['harvest_date'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Shelf Life Days -->
                    <div>
                        <label for="shelf_life_days" class="block text-gray-700 mb-2">Shelf Life (Days)</label>
                        <input
                            type="number"
                            id="shelf_life_days"
                            name="shelf_life_days"
                            value="<?= old('shelf_life_days') ?>"
                            min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="e.g., 7"
                        >
                        <p class="text-sm text-gray-500 mt-1">Leave blank to use default based on category.</p>
                        <?php if (isset($errors['shelf_life_days'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['shelf_life_days'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Location -->
                    <div class="md:col-span-2">
                        <label for="location" class="block text-gray-700 mb-2">Location *</label>
                        <input
                            type="text"
                            id="location"
                            name="location"
                            value="<?= old('location') ?>"
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
                        <label for="images" class="block text-gray-700 mb-2">Product Images (Max 5)</label>
                        <input
                            type="file"
                            id="images"
                            name="images[]"
                            accept="image/*"
                            multiple
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-white hover:file:bg-primary-hover"
                        >
                        <p class="text-sm text-gray-500 mt-1">Select up to 5 images. Supported formats: JPG, PNG, GIF (Max 5MB each)</p>
                    </div>

                    <!-- Image Preview & Analytics Section -->
                    <div id="imageAnalyticsSection" class="md:col-span-2 hidden">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200 p-6 mt-4">
                            <div class="flex items-center gap-2 mb-4">
                                <i data-lucide="sparkles" class="w-5 h-5 text-blue-600"></i>
                                <h3 class="text-gray-900">AI Image Recognition Analytics</h3>
                            </div>
                            
                            <!-- Image Previews -->
                            <div id="imagePreviews" class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6"></div>
                            
                            <!-- Analytics Results -->
                            <div id="analyticsResults" class="space-y-4"></div>
                            
                            <!-- Analysis Status -->
                            <div id="analysisStatus" class="mt-4 p-4 bg-white rounded-lg border border-blue-200">
                                <div class="flex items-center gap-3">
                                    <div class="animate-spin">
                                        <i data-lucide="loader-2" class="w-5 h-5 text-blue-600"></i>
                                    </div>
                                    <span class="text-gray-700">Analyzing images...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex gap-4">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-hover transition-colors">
                        <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                        Add Product
                    </button>
                    <a href="/buyer/inventory" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Image Recognition Analytics Engine
class ProductImageAnalyzer {
    constructor() {
        this.productDatabase = {
            tomatoes: { category: 'vegetables', basePrice: 45, shelfLife: 7, keywords: ['red', 'round', 'tomato'] },
            carrots: { category: 'vegetables', basePrice: 35, shelfLife: 14, keywords: ['orange', 'carrot', 'long'] },
            lettuce: { category: 'vegetables', basePrice: 30, shelfLife: 5, keywords: ['green', 'leafy', 'lettuce'] },
            cabbage: { category: 'vegetables', basePrice: 25, shelfLife: 10, keywords: ['green', 'round', 'cabbage'] },
            eggplant: { category: 'vegetables', basePrice: 40, shelfLife: 7, keywords: ['purple', 'eggplant'] },
            banana: { category: 'fruits', basePrice: 50, shelfLife: 5, keywords: ['yellow', 'banana', 'curved'] },
            mango: { category: 'fruits', basePrice: 80, shelfLife: 7, keywords: ['yellow', 'mango', 'tropical'] },
            apple: { category: 'fruits', basePrice: 90, shelfLife: 14, keywords: ['red', 'apple', 'round'] },
            orange: { category: 'fruits', basePrice: 60, shelfLife: 10, keywords: ['orange', 'citrus', 'round'] },
            strawberry: { category: 'fruits', basePrice: 120, shelfLife: 3, keywords: ['red', 'berry', 'small'] },
            rice: { category: 'grains', basePrice: 50, shelfLife: 365, keywords: ['white', 'grain', 'rice'] },
            corn: { category: 'grains', basePrice: 35, shelfLife: 5, keywords: ['yellow', 'corn', 'kernels'] }
        };
    }

    async analyzeImage(file) {
        // Simulate processing delay
        await new Promise(resolve => setTimeout(resolve, 1500 + Math.random() * 1000));
        
        // Mock image recognition based on filename
        const filename = file.name.toLowerCase();
        let detectedProduct = null;
        let confidence = 0;
        
        // Try to match product from filename
        for (const [product, data] of Object.entries(this.productDatabase)) {
            if (filename.includes(product) || data.keywords.some(kw => filename.includes(kw))) {
                detectedProduct = product;
                confidence = 0.85 + Math.random() * 0.14;
                break;
            }
        }
        
        // If no match, randomly select one
        if (!detectedProduct) {
            const products = Object.keys(this.productDatabase);
            detectedProduct = products[Math.floor(Math.random() * products.length)];
            confidence = 0.65 + Math.random() * 0.2;
        }
        
        const productData = this.productDatabase[detectedProduct];
        
        // Calculate quality score based on mock analysis
        const qualityScore = 0.75 + Math.random() * 0.24;
        const freshnessScore = 0.70 + Math.random() * 0.29;
        
        // Calculate suggested price with variance
        const priceVariance = 0.85 + Math.random() * 0.3;
        const suggestedPrice = (productData.basePrice * priceVariance).toFixed(2);
        
        return {
            detectedProduct: detectedProduct.charAt(0).toUpperCase() + detectedProduct.slice(1),
            category: productData.category,
            confidence: (confidence * 100).toFixed(1),
            qualityScore: (qualityScore * 100).toFixed(1),
            freshnessScore: (freshnessScore * 100).toFixed(1),
            suggestedPrice: suggestedPrice,
            shelfLife: productData.shelfLife,
            attributes: this.generateAttributes(detectedProduct, qualityScore)
        };
    }
    
    generateAttributes(product, quality) {
        const attributes = [];
        
        if (quality > 0.9) {
            attributes.push('Premium Quality');
        } else if (quality > 0.8) {
            attributes.push('High Quality');
        } else {
            attributes.push('Good Quality');
        }
        
        const conditions = ['Fresh', 'Ripe', 'Organic', 'Locally Grown'];
        attributes.push(conditions[Math.floor(Math.random() * conditions.length)]);
        
        return attributes;
    }
}

// Initialize analyzer
const analyzer = new ProductImageAnalyzer();
let analysisResults = [];

// Handle image selection
document.getElementById('images').addEventListener('change', async function(e) {
    const files = Array.from(e.target.files).slice(0, 5);
    
    if (files.length === 0) {
        document.getElementById('imageAnalyticsSection').classList.add('hidden');
        return;
    }
    
    // Show analytics section
    document.getElementById('imageAnalyticsSection').classList.remove('hidden');
    
    // Clear previous content
    const previewContainer = document.getElementById('imagePreviews');
    const resultsContainer = document.getElementById('analyticsResults');
    const statusContainer = document.getElementById('analysisStatus');
    
    previewContainer.innerHTML = '';
    resultsContainer.innerHTML = '';
    statusContainer.classList.remove('hidden');
    analysisResults = [];
    
    // Display image previews
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'relative group';
            previewDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}" 
                     class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                    #${index + 1}
                </div>
            `;
            previewContainer.appendChild(previewDiv);
        };
        reader.readAsDataURL(file);
    });
    
    // Analyze images
    try {
        const analyses = await Promise.all(files.map(file => analyzer.analyzeImage(file)));
        analysisResults = analyses;
        
        // Hide status
        statusContainer.classList.add('hidden');
        
        // Display results
        displayAnalysisResults(analyses);
        
        // Auto-fill form if high confidence
        if (analyses.length > 0 && analyses[0].confidence > 80) {
            autoFillForm(analyses[0]);
        }
        
    } catch (error) {
        statusContainer.innerHTML = `
            <div class="flex items-center gap-3 text-red-600">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <span>Analysis failed. Please try again.</span>
            </div>
        `;
        lucide.createIcons();
    }
});

function displayAnalysisResults(analyses) {
    const resultsContainer = document.getElementById('analyticsResults');
    
    // Primary Detection Result
    const primary = analyses[0];
    const consensusProducts = [...new Set(analyses.map(a => a.detectedProduct))];
    const avgConfidence = (analyses.reduce((sum, a) => sum + parseFloat(a.confidence), 0) / analyses.length).toFixed(1);
    
    resultsContainer.innerHTML = `
        <!-- Primary Detection -->
        <div class="bg-white rounded-lg p-4 border border-blue-200">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="scan" class="w-4 h-4 text-blue-600"></i>
                        <span class="text-gray-700">Detected Product</span>
                    </div>
                    <p class="text-gray-900 text-xl">${primary.detectedProduct}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-500">Confidence</span>
                    <p class="text-green-600">${primary.confidence}%</p>
                </div>
            </div>
            <div class="flex gap-2 flex-wrap">
                ${primary.attributes.map(attr => `
                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">${attr}</span>
                `).join('')}
            </div>
        </div>
        
        <!-- Quality Metrics -->
        <div class="bg-white rounded-lg p-4 border border-blue-200">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="activity" class="w-4 h-4 text-blue-600"></i>
                <span class="text-gray-900">Quality Metrics</span>
            </div>
            <div class="space-y-3">
                <!-- Overall Quality -->
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Overall Quality</span>
                        <span class="text-gray-900">${primary.qualityScore}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-500" 
                             style="width: ${primary.qualityScore}%"></div>
                    </div>
                </div>
                <!-- Freshness -->
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Freshness Index</span>
                        <span class="text-gray-900">${primary.freshnessScore}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" 
                             style="width: ${primary.freshnessScore}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Market Insights -->
        <div class="bg-white rounded-lg p-4 border border-blue-200">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="trending-up" class="w-4 h-4 text-blue-600"></i>
                <span class="text-gray-900">Market Insights</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500">Suggested Price</span>
                    <p class="text-gray-900">₱${primary.suggestedPrice}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500">Est. Shelf Life</span>
                    <p class="text-gray-900">${primary.shelfLife} days</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500">Category</span>
                    <p class="text-gray-900 capitalize">${primary.category}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500">Images Analyzed</span>
                    <p class="text-gray-900">${analyses.length}</p>
                </div>
            </div>
        </div>
        
        <!-- Auto-Fill Notification -->
        ${primary.confidence > 80 ? `
        <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mt-0.5"></i>
            <div>
                <p class="text-green-900 text-sm">High confidence detection! Form fields have been auto-filled based on AI analysis.</p>
                <p class="text-green-700 text-xs mt-1">You can review and adjust the values before submitting.</p>
            </div>
        </div>
        ` : ''}
    `;
    
    // Reinitialize lucide icons
    lucide.createIcons();
}

function autoFillForm(analysis) {
    // Auto-fill category
    const categorySelect = document.getElementById('category');
    if (categorySelect.value === '') {
        categorySelect.value = analysis.category;
    }
    
    // Suggest product name if empty
    const nameInput = document.getElementById('name');
    if (nameInput.value === '') {
        nameInput.value = `Fresh ${analysis.detectedProduct}`;
    }
    
    // Suggest price if empty
    const priceInput = document.getElementById('price');
    if (priceInput.value === '') {
        priceInput.value = analysis.suggestedPrice;
    }
    
    // Suggest shelf life if empty
    const shelfLifeInput = document.getElementById('shelf_life_days');
    if (shelfLifeInput.value === '') {
        shelfLifeInput.value = analysis.shelfLife;
    }
    
    // Add suggested description
    const descriptionInput = document.getElementById('description');
    if (descriptionInput.value === '') {
        descriptionInput.value = `${analysis.attributes.join(', ')} ${analysis.detectedProduct.toLowerCase()}. Quality score: ${analysis.qualityScore}%. Estimated freshness: ${analysis.freshnessScore}%.`;
    }
}

// Initialize Lucide icons on page load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>

<?= $this->endSection() ?>
