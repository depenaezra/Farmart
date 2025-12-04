<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

@keyframes bounce-subtle {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}

.animate-slide-in-right {
    animation: slideInRight 0.5s ease-out forwards;
}

.animate-pulse-slow {
    animation: pulse 2s ease-in-out infinite;
}

.animate-bounce-subtle {
    animation: bounce-subtle 2s ease-in-out infinite;
}

.stagger-1 { animation-delay: 0.1s; opacity: 0; }
.stagger-2 { animation-delay: 0.2s; opacity: 0; }
.stagger-3 { animation-delay: 0.3s; opacity: 0; }
.stagger-4 { animation-delay: 0.4s; opacity: 0; }
.stagger-5 { animation-delay: 0.5s; opacity: 0; }
.stagger-6 { animation-delay: 0.6s; opacity: 0; }
.stagger-7 { animation-delay: 0.7s; opacity: 0; }
.stagger-8 { animation-delay: 0.8s; opacity: 0; }

.weather-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.weather-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: scale(1.05);
}

.hourly-item {
    transition: all 0.3s ease;
}

.hourly-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.forecast-item {
    transition: all 0.3s ease;
}

.forecast-item:hover {
    transform: translateX(8px);
    background: linear-gradient(to right, #f9fafb, #f3f4f6);
}

.advisory-card {
    transition: all 0.3s ease;
}

.advisory-card:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.refresh-button {
    transition: all 0.3s ease;
    cursor: pointer;
}

.refresh-button:hover {
    transform: rotate(90deg);
}

.refresh-button:active {
    transform: rotate(180deg);
}
</style>

<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Warning Banner -->
    <div id="weatherApiWarning" class="hidden mb-6 animate-fade-in">
        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 text-yellow-800 p-5 rounded-xl shadow-sm">
            <div class="flex items-start">
                <i data-lucide="alert-triangle" class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0 animate-pulse-slow"></i>
                <span id="weatherApiWarningText" class="flex-1"></span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8 flex items-center justify-between flex-wrap gap-4 animate-fade-in-up">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">Weather</h1>
                
<span class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full shadow-md inline-flex items-center gap-1.5">
                    <i data-lucide="cloud" class="w-3 h-3"></i>
                    <span>Live Data</span>
                </span>            </div>
            <p class="text-gray-600">Real-time weather conditions and farming recommendations for <?= esc($location) ?></p>
        </div>
        <div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm">
            <i data-lucide="refresh-cw" id="refreshIcon" class="w-4 h-4 text-primary refresh-button"></i>
            <span id="lastUpdated" class="text-gray-600">Loading...</span>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-20">
        <div class="inline-flex flex-col items-center">
            <div class="relative mb-6">
                <div class="w-20 h-20 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                <i data-lucide="cloud-sun" class="w-8 h-8 text-primary absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 animate-pulse-slow"></i>
            </div>
            <p class="text-gray-700 mb-2">Loading weather data...</p>
            <p class="text-gray-500">Please wait</p>
        </div>
    </div>

    <!-- Weather Content -->
    <div id="weatherContent" class="hidden">
        <!-- Current Weather - Hero Card -->
        <div class="bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-3xl shadow-2xl border border-green-400 p-8 mb-8 text-white weather-card animate-fade-in-up overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl -ml-24 -mb-24"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                            <h2 class="text-white/90" id="weatherLocation">Nasugbu, Batangas</h2>
                        </div>
                        <p class="text-green-100" id="weatherLastUpdated">Last updated: Loading...</p>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm px-6 py-4 rounded-2xl border border-white/20">
                        <span id="weatherIcon" class="w-20 h-20 flex items-center justify-center text-6xl animate-bounce-subtle">🌤️</span>
                        <div>
                            <div class="text-5xl mb-1" id="weatherTemp">--°C</div>
                            <div class="text-xl text-green-100 mb-1" id="weatherCondition">--</div>
                            <div class="text-green-200" id="weatherFeelsLike"></div>
                        </div>
                    </div>
                </div>

                <!-- Weather Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20 stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <i data-lucide="droplets" class="w-8 h-8 text-green-200"></i>
                        </div>
                        <div class="text-3xl mb-1" id="weatherHumidity">--%</div>
                        <div class="text-green-200">Humidity</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20 stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <i data-lucide="wind" class="w-8 h-8 text-green-200"></i>
                        </div>
                        <div class="text-3xl mb-1" id="weatherWind">-- km/h</div>
                        <div class="text-green-200">Wind Speed</div>
                        <div class="text-green-300 mt-1" id="weatherWindDirection"></div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20 stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <i data-lucide="cloud-rain" class="w-8 h-8 text-green-200"></i>
                        </div>
                        <div class="text-3xl mb-1" id="weatherRainfall">-- mm</div>
                        <div class="text-green-200">Rainfall</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20 stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <i data-lucide="gauge" class="w-8 h-8 text-green-200"></i>
                        </div>
                        <div class="text-3xl mb-1" id="weatherPressure">-- hPa</div>
                        <div class="text-green-200">Pressure</div>
                    </div>
                </div>
                
                <!-- Sunrise/Sunset -->
                <div class="flex items-center justify-center gap-8 pt-6 border-t border-white/20" id="sunTimes">
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-5 py-3 rounded-xl border border-white/20">
                        <i data-lucide="sunrise" class="w-5 h-5 text-yellow-200"></i>
                        <div>
                            <div class="text-green-200 mb-1">Sunrise</div>
                            <div class="text-xl" id="sunriseTime">--:--</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-5 py-3 rounded-xl border border-white/20">
                        <i data-lucide="sunset" class="w-5 h-5 text-orange-200"></i>
                        <div>
                            <div class="text-green-200 mb-1">Sunset</div>
                            <div class="text-xl" id="sunsetTime">--:--</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hourly Forecast -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 mb-8 weather-card animate-fade-in-up stagger-1">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-green-100 rounded-xl">
                    <i data-lucide="clock" class="w-6 h-6 text-green-600"></i>
                </div>
                <h3 class="text-gray-900">Hourly Forecast</h3>
            </div>
            <div class="overflow-x-auto -mx-2 px-2">
                <div class="inline-flex gap-3 min-w-full pb-2" id="hourlyContainer">
                    <!-- Hourly items will be inserted here -->
                </div>
            </div>
        </div>

        <!-- 10-Day Forecast -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 mb-8 weather-card animate-fade-in-up stagger-2">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-purple-100 rounded-xl">
                    <i data-lucide="calendar-days" class="w-6 h-6 text-purple-600"></i>
                </div>
                <h3 class="text-gray-900">10-Day Forecast</h3>
            </div>
            <div class="space-y-2" id="forecastContainer">
                <!-- Forecast items will be inserted here -->
            </div>
        </div>

        <!-- Agricultural Advisories -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-md border border-green-200 p-6 weather-card animate-fade-in-up stagger-3">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-green-500 rounded-xl shadow-lg shadow-green-500/30">
                    <i data-lucide="sprout" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-gray-900">Agricultural Advisories</h3>
            </div>
            <div class="space-y-3" id="advisoriesContainer"></div>
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
        const weatherContent = document.getElementById('weatherContent');
        weatherContent.classList.remove('hidden');
        
        // Update current weather
        document.getElementById('weatherLocation').textContent = weather.location;
        document.getElementById('weatherTemp').textContent = weather.current.temperature + '°C';
        document.getElementById('weatherCondition').textContent = weather.current.condition;
        
        if (weather.current.feels_like) {
            document.getElementById('weatherFeelsLike').textContent = 'Feels like ' + weather.current.feels_like + '°C';
        }
        
        // Weather icon
        const iconEl = document.getElementById('weatherIcon');
        iconEl.textContent = getWeatherEmoji(weather.current.condition);
        
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
            
            weather.hourly.forEach((hour, index) => {
                const hourlyItem = document.createElement('div');
                hourlyItem.className = 'hourly-item flex flex-col items-center min-w-[100px] p-4 bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-2xl hover:border-green-300 hover:from-green-50 hover:to-green-100';
                hourlyItem.innerHTML = `
                    <div class="text-gray-600 mb-3">${hour.hour}</div>
                    <span class="w-12 h-12 mb-3 flex items-center justify-center text-3xl">${getWeatherEmoji(hour.condition)}</span>
                    <div class="text-xl text-gray-900 mb-2">${hour.temperature}°</div>
                    <div class="flex items-center gap-1 text-green-600">
                        <i data-lucide="droplet" class="w-3 h-3"></i>
                        <span>${hour.rain_chance}%</span>
                    </div>
                `;
                hourlyContainer.appendChild(hourlyItem);
            });
        }
        
        // Update forecast (Google-style list)
        const forecastContainer = document.getElementById('forecastContainer');
        forecastContainer.innerHTML = '';
        
        weather.forecast.forEach((day, index) => {
            const forecastItem = document.createElement('div');
            forecastItem.className = 'forecast-item flex items-center justify-between p-5 border-2 border-gray-100 rounded-xl hover:border-purple-200 bg-white';
            forecastItem.innerHTML = `
                <div class="flex items-center gap-6 flex-1">
                    <div class="w-28">
                        <div class="text-gray-900 mb-1">${day.day}</div>
                        <div class="text-gray-500">${day.date}</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="w-14 h-14 flex items-center justify-center text-4xl">${getWeatherEmoji(day.condition)}</span>
                        <div class="text-gray-700 w-36">${day.condition}</div>
                    </div>
                    <div class="flex items-center gap-2 bg-green-50 px-3 py-1.5 rounded-lg border border-green-200">
                        <i data-lucide="cloud-rain" class="w-4 h-4 text-green-600"></i>
                        <span class="text-green-700">${day.rain_chance}%</span>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-right">
                    <div class="text-xl text-gray-900">${day.high}°</div>
                    <div class="text-xl text-gray-400">${day.low}°</div>
                </div>
            `;
            forecastContainer.appendChild(forecastItem);
        });
        
        // Update advisories
        const advisoriesContainer = document.getElementById('advisoriesContainer');
        advisoriesContainer.innerHTML = '';
        
        if (weather.advisories && weather.advisories.length > 0) {
            weather.advisories.forEach((advisory, index) => {
                const advisoryItem = document.createElement('div');
                const isWarning = advisory.type === 'warning';
                advisoryItem.className = `advisory-card border-l-4 ${isWarning ? 'border-l-red-500 bg-white' : 'border-l-green-500 bg-white'} p-5 rounded-r-xl shadow-sm hover:shadow-md`;
                advisoryItem.innerHTML = `
                    <div class="flex items-start gap-4">
                        <div class="p-2 ${isWarning ? 'bg-red-100' : 'bg-green-100'} rounded-lg flex-shrink-0">
                            <i data-lucide="${isWarning ? 'alert-triangle' : 'info'}" class="w-5 h-5 ${isWarning ? 'text-red-600' : 'text-green-600'}"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-gray-900 mb-2">${advisory.title}</h4>
                            <p class="text-gray-700">${advisory.message}</p>
                        </div>
                    </div>
                `;
                advisoriesContainer.appendChild(advisoryItem);
            });
        } else {
            advisoriesContainer.innerHTML = `
                <div class="text-center py-8 bg-white rounded-xl border-2 border-dashed border-gray-300">
                    <i data-lucide="check-circle" class="w-12 h-12 text-green-500 mx-auto mb-3"></i>
                    <p class="text-gray-600">No advisories at this time.</p>
                    <p class="text-gray-500 mt-1">Weather conditions are favorable.</p>
                </div>
            `;
        }
        
        // Re-initialize icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Helper: map condition to emoji
    function getWeatherEmoji(condition) {
        const cond = (condition || '').toLowerCase();
        if (cond.includes('rain')) return '🌧️';
        if (cond.includes('cloud')) return '⛅';
        if (cond.includes('sun') || cond.includes('clear')) return '☀️';
        if (cond.includes('storm') || cond.includes('thunder')) return '⛈️';
        if (cond.includes('snow')) return '❄️';
        if (cond.includes('mist') || cond.includes('fog')) return '🌫️';
        if (cond.includes('wind')) return '💨';
        return '🌤️';
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
        const loadingState = document.getElementById('loadingState');
        loadingState.innerHTML = `
            <div class="inline-flex flex-col items-center bg-white rounded-2xl p-8 shadow-lg border border-red-200">
                <div class="p-4 bg-red-100 rounded-full mb-4">
                    <i data-lucide="alert-circle" class="w-12 h-12 text-red-600"></i>
                </div>
                <p class="text-red-700 text-xl mb-2">${message}</p>
                <p class="text-gray-600 mb-6">Please try again</p>
                <button onclick="location.reload()" class="bg-primary text-white px-6 py-3 rounded-xl hover:bg-primary-hover transition-all hover:shadow-lg">
                    <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                    Retry
                </button>
            </div>
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
