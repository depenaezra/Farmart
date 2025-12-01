<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Warning Banner -->
    <div id="weatherApiWarning" class="hidden mb-6">
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded">
            <i data-lucide="alert-triangle" class="w-5 h-5 inline-block mr-2 align-middle"></i>
            <span id="weatherApiWarningText"></span>
        </div>
    </div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">Weather</h1>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Google Weather</span>
            </div>
            <p class="text-gray-600">Real-time weather conditions and farming recommendations for <?= esc($location) ?></p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <i data-lucide="refresh-cw" id="refreshIcon" class="w-4 h-4"></i>
            <span id="lastUpdated">Loading...</span>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-12">
        <i data-lucide="loader-2" class="w-12 h-12 text-primary mx-auto mb-4 animate-spin"></i>
        <p class="text-gray-600">Loading weather data...</p>
    </div>

    <!-- Weather Content -->
    <div id="weatherContent" class="hidden">
        <!-- Current Weather -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1" id="weatherLocation">Nasugbu, Batangas</h2>
                    <p class="text-gray-600" id="weatherLastUpdated">Last updated: Loading...</p>
                </div>
                <div class="text-right">
                    <div class="flex items-center gap-3">
                        <span id="weatherIcon" class="w-16 h-16 flex items-center justify-center text-5xl">🌤️</span>
                        <div>
                            <div class="text-4xl font-bold text-primary mb-1" id="weatherTemp">--°C</div>
                            <div class="text-lg text-gray-600" id="weatherCondition">--</div>
                            <div class="text-sm text-gray-500" id="weatherFeelsLike"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-4">
                <div class="text-center">
                    <i data-lucide="droplets" class="w-8 h-8 text-blue-500 mx-auto mb-2"></i>
                    <div class="text-2xl font-semibold text-gray-900" id="weatherHumidity">--%</div>
                    <div class="text-sm text-gray-600">Humidity</div>
                </div>
                <div class="text-center">
                    <i data-lucide="wind" class="w-8 h-8 text-gray-500 mx-auto mb-2"></i>
                    <div class="text-2xl font-semibold text-gray-900" id="weatherWind">-- km/h</div>
                    <div class="text-sm text-gray-600">Wind</div>
                    <div class="text-xs text-gray-500 mt-1" id="weatherWindDirection"></div>
                </div>
                <div class="text-center">
                    <i data-lucide="cloud-rain" class="w-8 h-8 text-blue-600 mx-auto mb-2"></i>
                    <div class="text-2xl font-semibold text-gray-900" id="weatherRainfall">-- mm</div>
                    <div class="text-sm text-gray-600">Rainfall</div>
                </div>
                <div class="text-center">
                    <i data-lucide="gauge" class="w-8 h-8 text-purple-500 mx-auto mb-2"></i>
                    <div class="text-2xl font-semibold text-gray-900" id="weatherPressure">-- hPa</div>
                    <div class="text-sm text-gray-600">Pressure</div>
                </div>
            </div>
            
            <!-- Sunrise/Sunset -->
            <div class="flex items-center justify-center gap-6 pt-4 border-t border-gray-200" id="sunTimes">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i data-lucide="sunrise" class="w-4 h-4"></i>
                    <span id="sunriseTime">--:--</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i data-lucide="sunset" class="w-4 h-4"></i>
                    <span id="sunsetTime">--:--</span>
                </div>
            </div>
        </div>

        <!-- Hourly Forecast -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Hourly Forecast</h3>
            <div>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4 justify-center items-center w-full" id="hourlyContainer">
                    <!-- Hourly items will be inserted here -->
                </div>
            </div>
        </div>

        <!-- 10-Day Forecast -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">10-Day Forecast</h3>
            <div class="space-y-2" id="forecastContainer">
                <!-- Forecast items will be inserted here -->
            </div>
        </div>

        <!-- Agricultural Advisories -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Agricultural Advisories</h3>
            <div class="space-y-4" id="advisoriesContainer"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = <?= $lat ?? 14.0667 ?>;
    const lon = <?= $lon ?? 120.6333 ?>;
    let refreshInterval;

    updateWeather();

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    function updateWeather() {
        const refreshIcon = document.getElementById('refreshIcon');
        if (refreshIcon) {
            refreshIcon.classList.add('animate-spin');
        }
        
        fetch(`/weather/api?lat=${lat}&lon=${lon}`)
            .then(response => response.json())
            .then(data => {
                    if (data.success && data.data) {
                        displayWeather(data.data);
                        updateLastUpdated(data.last_updated, data.from_cache);
                        // Show warning if note is present (API not configured or using sample data)
                        if (data.note) {
                            const warningDiv = document.getElementById('weatherApiWarning');
                            const warningText = document.getElementById('weatherApiWarningText');
                            if (warningDiv && warningText) {
                                warningText.textContent = data.note;
                                warningDiv.classList.remove('hidden');
                                if (typeof lucide !== 'undefined') lucide.createIcons();
                            }
                        } else {
                            const warningDiv = document.getElementById('weatherApiWarning');
                            if (warningDiv) warningDiv.classList.add('hidden');
                        }
                    } else {
                        showError('Failed to load weather data');
                    }
            })
            .catch(error => {
                console.error('Weather fetch error:', error);
                showError('Error loading weather data');
            })
            .finally(() => {
                if (refreshIcon) {
                    refreshIcon.classList.remove('animate-spin');
                }
            });
    }
    
    function displayWeather(weather) {
        // Hide loading, show content
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('weatherContent').classList.remove('hidden');
        
        // Update current weather
        document.getElementById('weatherLocation').textContent = weather.location;
        document.getElementById('weatherTemp').textContent = weather.current.temperature + '°C';
        document.getElementById('weatherCondition').textContent = weather.current.condition;
        
        if (weather.current.feels_like) {
            document.getElementById('weatherFeelsLike').textContent = 'Feels like ' + weather.current.feels_like + '°C';
        }
        
        // Weather icon
        if (weather.current.icon) {
            const iconImg = document.getElementById('weatherIcon');
            iconImg.src = `https://openweathermap.org/img/wn/${weather.current.icon}@2x.png`;
            iconImg.classList.remove('hidden');
        }
        
        document.getElementById('weatherHumidity').textContent = weather.current.humidity + '%';
        document.getElementById('weatherWind').textContent = weather.current.wind_speed + ' km/h';
        if (weather.current.wind_direction) {
            document.getElementById('weatherWindDirection').textContent = weather.current.wind_direction;
        }
        document.getElementById('weatherRainfall').textContent = (weather.current.rainfall || 0) + ' mm';
        if (weather.current.pressure) {
            document.getElementById('weatherPressure').textContent = weather.current.pressure + ' hPa';
        }
        if (weather.current.sunrise) {
            document.getElementById('sunriseTime').textContent = weather.current.sunrise;
        }
        if (weather.current.sunset) {
            document.getElementById('sunsetTime').textContent = weather.current.sunset;
        }
        
        // Update hourly forecast
        if (weather.hourly && weather.hourly.length > 0) {
            const hourlyContainer = document.getElementById('hourlyContainer');
            hourlyContainer.innerHTML = '';
            
            weather.hourly.forEach(hour => {
                const hourlyItem = document.createElement('div');
                hourlyItem.className = 'flex flex-col items-center min-w-[80px] p-3 border border-gray-200 rounded-lg';
                hourlyItem.innerHTML = `
                    <div class="text-sm font-medium text-gray-600 mb-2">${hour.hour}</div>
                    <span class="w-10 h-10 mb-2 flex items-center justify-center text-2xl">${getWeatherEmoji(hour.condition)}</span>
                    <div class="text-lg font-bold text-gray-900 mb-1">${hour.temperature}°</div>
                    <div class="text-xs text-blue-600">${hour.rain_chance}%</div>
                `;
                hourlyContainer.appendChild(hourlyItem);
            });
        }
        
        // Update forecast (Google-style list)
        const forecastContainer = document.getElementById('forecastContainer');
        forecastContainer.innerHTML = '';
        
        weather.forecast.forEach(day => {
            const forecastItem = document.createElement('div');
            forecastItem.className = 'flex items-center justify-between p-4 border-b border-gray-200 last:border-0 hover:bg-gray-50 transition-colors';
            forecastItem.innerHTML = `
                <div class="flex items-center gap-4 flex-1">
                    <div class="w-24">
                        <div class="font-semibold text-gray-900">${day.day}</div>
                        <div class="text-sm text-gray-600">${day.date}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-12 h-12 flex items-center justify-center text-3xl">${getWeatherEmoji(day.condition)}</span>
                        <div class="text-sm text-gray-600 w-32">${day.condition}</div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i data-lucide="cloud-rain" class="w-4 h-4"></i>
                        <span>${day.rain_chance}%</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-right">
                    <div class="text-lg font-semibold text-gray-900">${day.high}°</div>
                    <div class="text-lg text-gray-400">${day.low}°</div>
                </div>
            `;
            forecastContainer.appendChild(forecastItem);
        });
            // Helper: map condition to emoji
            function getWeatherEmoji(condition) {
                const cond = (condition || '').toLowerCase();
                if (cond.includes('rain')) return '🌧️';
                if (cond.includes('cloud')) return '⛅';
                if (cond.includes('sun') || cond.includes('clear')) return '☀️';
                if (cond.includes('storm') || cond.includes('thunder')) return '⛈️';
                if (cond.includes('snow')) return '❄️';
                return '🌤️';
            }

        
        // Update advisories
        const advisoriesContainer = document.getElementById('advisoriesContainer');
        advisoriesContainer.innerHTML = '';
        
        if (weather.advisories && weather.advisories.length > 0) {
            weather.advisories.forEach(advisory => {
                const advisoryItem = document.createElement('div');
                advisoryItem.className = `border-l-4 ${advisory.type === 'warning' ? 'border-l-red-500 bg-red-50' : 'border-l-blue-500 bg-blue-50'} p-4 rounded-r-lg`;
                advisoryItem.innerHTML = `
                    <div class="flex items-start">
                        <i data-lucide="${advisory.type === 'warning' ? 'alert-triangle' : 'info'}" class="w-5 h-5 ${advisory.type === 'warning' ? 'text-red-600' : 'text-blue-600'} mr-3 mt-0.5"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">${advisory.title}</h4>
                            <p class="text-gray-700">${advisory.message}</p>
                        </div>
                    </div>
                `;
                advisoriesContainer.appendChild(advisoryItem);
            });
        } else {
            advisoriesContainer.innerHTML = '<p class="text-gray-500 text-center">No advisories at this time.</p>';
        }
        
        // Re-initialize icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
    
    function updateLastUpdated(timestamp, fromCache = false) {
        const lastUpdatedEl = document.getElementById('lastUpdated');
        const weatherLastUpdatedEl = document.getElementById('weatherLastUpdated');
        
        if (timestamp) {
            const date = new Date(timestamp);
            const formatted = date.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            const cacheNote = fromCache ? ' (cached)' : ' (live)';
            
            if (lastUpdatedEl) lastUpdatedEl.textContent = 'Updated: ' + formatted + cacheNote;
            if (weatherLastUpdatedEl) weatherLastUpdatedEl.textContent = 'Last updated: ' + formatted;
        }
    }
    
    function showError(message) {
        document.getElementById('loadingState').innerHTML = `
            <i data-lucide="alert-circle" class="w-12 h-12 text-red-500 mx-auto mb-4"></i>
            <p class="text-red-600">${message}</p>
            <button onclick="updateWeather()" class="mt-4 bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover">
                Retry
            </button>
        `;
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
    
    // Auto-refresh every 5 minutes (300000 ms)
    refreshInterval = setInterval(updateWeather, 5 * 60 * 1000);
    
    // Also trigger cache update on server every 5 minutes (in background)
    setInterval(function() {
        // Silently update cache in background
        fetch(`/weather/update-cache?format=json`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).catch(error => console.log('Background cache update:', error));
    }, 5 * 60 * 1000);
    
    // Manual refresh button
    const refreshIcon = document.getElementById('refreshIcon');
    if (refreshIcon) {
        refreshIcon.parentElement.addEventListener('click', function() {
            updateWeather();
            // Also trigger cache update
            fetch(`/weather/update-cache?format=json`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(error => console.log('Cache update:', error));
        });
        refreshIcon.parentElement.style.cursor = 'pointer';
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
});
</script>

<?= $this->endSection() ?>