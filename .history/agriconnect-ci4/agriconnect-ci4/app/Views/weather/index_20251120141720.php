
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Weather & Agricultural Advisory</h1>
        <p class="text-gray-600">Current weather conditions and farming recommendations for Nasugbu</p>
    </div>

    <!-- Current Weather -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1"><?= esc($weather['location']) ?></h2>
                <p class="text-gray-600">Last updated: <?= date('M d, Y H:i') ?></p>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold text-primary mb-1"><?= esc($weather['current']['temperature']) ?>°C</div>
                <div class="text-lg text-gray-600"><?= esc($weather['current']['condition']) ?></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <i data-lucide="droplets" class="w-8 h-8 text-blue-500 mx-auto mb-2"></i>
                <div class="text-2xl font-semibold text-gray-900"><?= esc($weather['current']['humidity']) ?>%</div>
                <div class="text-sm text-gray-600">Humidity</div>
            </div>
            <div class="text-center">
                <i data-lucide="wind" class="w-8 h-8 text-gray-500 mx-auto mb-2"></i>
                <div class="text-2xl font-semibold text-gray-900"><?= esc($weather['current']['wind_speed']) ?> km/h</div>
                <div class="text-sm text-gray-600">Wind Speed</div>
            </div>
            <div class="text-center">
                <i data-lucide="cloud-rain" class="w-8 h-8 text-blue-600 mx-auto mb-2"></i>
                <div class="text-2xl font-semibold text-gray-900"><?= esc($weather['current']['rainfall']) ?> mm</div>
