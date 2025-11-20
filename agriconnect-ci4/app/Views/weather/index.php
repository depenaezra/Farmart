<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Weather & Agricultural Advisory</h1>
        <p class="text-gray-600">Stay informed about weather conditions affecting Nasugbu agriculture</p>
    </div>

    <!-- Current Weather -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Current Conditions</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-4xl mb-2">
                    <i data-lucide="cloud" class="w-12 h-12 text-gray-600"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">
                    <?= $weather['current']['temperature'] ?>°C
                </div>
                <div class="text-gray-600">
                    <?= esc($weather['current']['condition']) ?>
                </div>
            </div>

            <div class="text-center">
                <div class="text-4xl mb-2">
                    <i data-lucide="droplets" class="w-12 h-12 text-blue-600"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">
                    <?= $weather['current']['humidity'] ?>%
                </div>
                <div class="text-gray-600">
                    Humidity
                </div>
            </div>

            <div class="text-center">
                <div class="text-4xl mb-2">
                    <i data-lucide="wind" class="w-12 h-12 text-green-600"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">
                    <?= $weather['current']['wind_speed'] ?> km/h
                </div>
                <div class="text-gray-600">
                    Wind Speed
                </div>
            </div>

            <div class="text-center">
                <div class="text-4xl mb-2">
                    <i data-lucide="cloud-rain" class="w-12 h-12 text-blue-500"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">
                    <?= $weather['current']['rainfall'] ?> mm
                </div>
                <div class="text-gray-600">
                    Rainfall
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-gray-700">
                <strong>Location:</strong> <?= esc($weather['location']) ?>
            </p>
        </div>
    </div>

    <!-- 5-Day Forecast -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">5-Day Forecast</h2>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <?php foreach ($weather['forecast'] as $day): ?>
                <div class="text-center p-4 border border-gray-200 rounded-lg">
                    <div class="font-semibold text-gray-900 mb-2">
                        <?= esc($day['day']) ?>
                    </div>
                    <div class="text-sm text-gray-600 mb-2">
                        <?= esc($day['date']) ?>
                    </div>
                    <div class="text-2xl mb-2">
                        <?php
                        switch (strtolower($day['condition'])) {
                            case 'sunny':
                                echo '<i data-lucide="sun" class="w-8 h-8 text-yellow-500"></i>';
                                break;
                            case 'partly cloudy':
                                echo '<i data-lucide="cloud-sun" class="w-8 h-8 text-gray-500"></i>';
                                break;
                            case 'cloudy':
                                echo '<i data-lucide="cloud" class="w-8 h-8 text-gray-600"></i>';
                                break;
                            case 'rainy':
                                echo '<i data-lucide="cloud-rain" class="w-8 h-8 text-blue-500"></i>';
                                break;
                            default:
                                echo '<i data-lucide="cloud" class="w-8 h-8 text-gray-600"></i>';
                        }
                        ?>
                    </div>
                    <div class="text-lg font-semibold text-gray-900">
                        <?= $day['high'] ?>° / <?= $day['low'] ?>°
                    </div>
                    <div class="text-sm text-gray-600">
                        <?= esc($day['condition']) ?>
                    </div>
                    <div class="text-sm text-blue-600 mt-1">
                        <?= $day['rain_chance'] ?>% rain
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Weather Advisories -->
    <?php if (!empty($weather['advisories'])): ?>
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Weather Advisories</h2>

            <div class="space-y-4">
                <?php foreach ($weather['advisories'] as $advisory): ?>
                    <div class="border-l-4 <?= $advisory['type'] === 'warning' ? 'border-l-red-500' : 'border-l-blue-500' ?> pl-4 py-2">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <?php if ($advisory['type'] === 'warning'): ?>
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
                                <?php else: ?>
                                    <i data-lucide="info" class="w-5 h-5 text-blue-500"></i>
                                <?php endif; ?>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <?= esc($advisory['title']) ?>
                                </h3>
                                <p class="text-gray-700 mt-1">
                                    <?= esc($advisory['message']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Agricultural Tips -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-green-900 mb-4">Agricultural Recommendations</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-green-800 mb-2">Current Conditions</h3>
                <ul class="text-green-700 space-y-1">
                    <li class="flex items-center">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                        Favorable for planting tomatoes and leafy vegetables
                    </li>
                    <li class="flex items-center">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                        Good conditions for pest management
                    </li>
                    <li class="flex items-center">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                        Monitor soil moisture levels
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-green-800 mb-2">Upcoming Forecast</h3>
                <ul class="text-green-700 space-y-1">
                    <li class="flex items-center">
                        <i data-lucide="alert-circle" class="w-4 h-4 mr-2"></i>
                        Prepare for possible heavy rain this weekend
                    </li>
                    <li class="flex items-center">
                        <i data-lucide="alert-circle" class="w-4 h-4 mr-2"></i>
                        Secure crops and ensure proper drainage
                    </li>
                    <li class="flex items-center">
                        <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                        Check announcements for government advisories
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>