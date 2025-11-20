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
                <div class="text-sm text-gray-600">Rainfall</div>
            </div>
        </div>
    </div>

    <!-- 5-Day Forecast -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">5-Day Forecast</h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <?php foreach ($weather['forecast'] as $day): ?>
                <div class="text-center p-4 border border-gray-200 rounded-lg">
                    <div class="font-semibold text-gray-900 mb-1"><?= esc($day['day']) ?></div>
                    <div class="text-sm text-gray-600 mb-2"><?= esc($day['date']) ?></div>
                    <div class="text-lg font-bold text-primary mb-1">
                        <?= esc($day['high']) ?>° / <?= esc($day['low']) ?>°
                    </div>
                    <div class="text-sm text-gray-600 mb-2"><?= esc($day['condition']) ?></div>
                    <div class="text-xs text-blue-600">
                        <i data-lucide="cloud-rain" class="w-3 h-3 inline mr-1"></i>
                        <?= esc($day['rain_chance']) ?>%
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Agricultural Advisories -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Agricultural Advisories</h3>
        <div class="space-y-4">
            <?php foreach ($weather['advisories'] as $advisory): ?>
                <div class="border-l-4 <?= $advisory['type'] === 'warning' ? 'border-l-red-500 bg-red-50' : 'border-l-blue-500 bg-blue-50' ?> p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i data-lucide="<?= $advisory['type'] === 'warning' ? 'alert-triangle' : 'info' ?>" class="w-5 h-5 <?= $advisory['type'] === 'warning' ? 'text-red-600' : 'text-blue-600' ?> mr-3 mt-0.5"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1"><?= esc($advisory['title']) ?></h4>
                            <p class="text-gray-700"><?= esc($advisory['message']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>