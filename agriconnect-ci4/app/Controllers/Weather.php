<?php

namespace App\Controllers;

class Weather extends BaseController
{
    /**
     * Weather page
     */
    public function index()
    {
        $data = [
            'title' => 'Weather & Agricultural Advisory',
            'location' => 'Nasugbu, Batangas',
            'lat' => 14.0667,
            'lon' => 120.6333
        ];
        
        return view('weather/index', $data);
    }
    
    /**
     * Get real-time weather data (AJAX endpoint)
     */
    public function getWeather()
    {
        $lat = $this->request->getGet('lat') ?? 14.0667; // Nasugbu default
        $lon = $this->request->getGet('lon') ?? 120.6333;
        
        $cacheKey = 'weather_' . round($lat, 4) . '_' . round($lon, 4);
        $cache = \Config\Services::cache();
        
        // Try to get from cache first
        $cachedData = $cache->get($cacheKey);
        
        if ($cachedData !== null) {
            // Check if cache is still fresh (less than 5 minutes old)
            $cacheAge = time() - ($cachedData['cached_at'] ?? 0);
            
            if ($cacheAge < 300) { // 5 minutes = 300 seconds
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $cachedData['data'],
                    'last_updated' => date('Y-m-d H:i:s', $cachedData['cached_at']),
                    'from_cache' => true
                ]);
            }
        }
        
        // Cache expired or doesn't exist, fetch fresh data
        $weatherData = $this->fetchWeatherFromAPI($lat, $lon);
        
        if ($weatherData) {
            // Store in cache for 5 minutes
            $cache->save($cacheKey, [
                'data' => $weatherData,
                'cached_at' => time()
            ], 300); // 5 minutes cache
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $weatherData,
                'last_updated' => date('Y-m-d H:i:s'),
                'from_cache' => false
            ]);
        }
        
        // If API fails but we have cached data, use it even if expired
        if ($cachedData !== null) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $cachedData['data'],
                'last_updated' => date('Y-m-d H:i:s', $cachedData['cached_at']),
                'from_cache' => true,
                'note' => 'Using cached data. API temporarily unavailable.'
            ]);
        }
        
        // Fallback to mock data if API fails and no cache
        return $this->response->setJSON([
            'success' => true,
            'data' => $this->getMockWeatherData(),
            'last_updated' => date('Y-m-d H:i:s'),
            'note' => 'Using sample data. Please configure Google Weather API key or OpenWeatherMap API key for real-time updates.'
        ]);
    }
    
    /**
     * Update weather cache (can be called by cron job every 5 minutes)
     */
    public function updateCache()
    {
        $lat = 14.0667; // Nasugbu default
        $lon = 120.6333;
        
        $cacheKey = 'weather_' . round($lat, 4) . '_' . round($lon, 4);
        $cache = \Config\Services::cache();
        
        // Fetch fresh weather data
        $weatherData = $this->fetchWeatherFromAPI($lat, $lon);
        
        if ($weatherData) {
            // Store in cache for 5 minutes
            $cache->save($cacheKey, [
                'data' => $weatherData,
                'cached_at' => time()
            ], 300); // 5 minutes cache
            
            log_message('info', 'Weather cache updated successfully');
            
            if ($this->request->isAJAX() || $this->request->getGet('format') === 'json') {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Weather cache updated successfully',
                    'last_updated' => date('Y-m-d H:i:s')
                ]);
            }
            
            return 'Weather cache updated successfully at ' . date('Y-m-d H:i:s');
        }
        
        log_message('error', 'Failed to update weather cache');
        
        if ($this->request->isAJAX() || $this->request->getGet('format') === 'json') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update weather cache'
            ]);
        }
        
        return 'Failed to update weather cache';
    }
    
    /**
     * Fetch weather from Google Weather API
     */
    private function fetchWeatherFromAPI($lat, $lon)
    {
        // Google Weather API (using OpenWeatherMap as data source, styled like Google)
        $apiKey = getenv('GOOGLE_WEATHER_API_KEY') ?? getenv('OPENWEATHER_API_KEY') ?? '';
        
        if (empty($apiKey)) {
            return null;
        }
        
        try {
            $client = \Config\Services::curlrequest();
            
            // Current weather
            $currentUrl = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric";
            $currentResponse = $client->get($currentUrl);
            $currentData = json_decode($currentResponse->getBody(), true);
            
            if ($currentResponse->getStatusCode() !== 200 || !isset($currentData['main'])) {
                return null;
            }
            
            // Forecast
            $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric";
            $forecastResponse = $client->get($forecastUrl);
            $forecastData = json_decode($forecastResponse->getBody(), true);
            
            // Process with Google Weather style formatting
            $weather = [
                'location' => $currentData['name'] . ', ' . ($currentData['sys']['country'] ?? 'PH'),
                'current' => [
                    'temperature' => round($currentData['main']['temp']),
                    'feels_like' => round($currentData['main']['feels_like']),
                    'condition' => ucfirst($currentData['weather'][0]['description'] ?? 'Clear'),
                    'humidity' => $currentData['main']['humidity'],
                    'wind_speed' => round($currentData['wind']['speed'] * 3.6), // Convert m/s to km/h
                    'wind_direction' => isset($currentData['wind']['deg']) ? $this->getWindDirection($currentData['wind']['deg']) : null,
                    'pressure' => $currentData['main']['pressure'],
                    'visibility' => isset($currentData['visibility']) ? round($currentData['visibility'] / 1000, 1) : null,
                    'rainfall' => isset($currentData['rain']['1h']) ? round($currentData['rain']['1h'], 1) : 0,
                    'icon' => $currentData['weather'][0]['icon'] ?? '01d',
                    'uv_index' => null, // Would need UV index API
                    'sunrise' => isset($currentData['sys']['sunrise']) ? date('H:i', $currentData['sys']['sunrise']) : null,
                    'sunset' => isset($currentData['sys']['sunset']) ? date('H:i', $currentData['sys']['sunset']) : null
                ],
                'forecast' => [],
                'hourly' => [],
                'advisories' => $this->generateGoogleStyleAdvisories($currentData, $forecastData)
            ];
            
            // Process forecast (Google style - simple and clean)
            if (isset($forecastData['list'])) {
                $dailyForecast = [];
                $processedDates = [];
                
                foreach ($forecastData['list'] as $item) {
                    $date = date('Y-m-d', $item['dt']);
                    
                    if (!isset($processedDates[$date]) && date('H', $item['dt']) >= 12) {
                        $dayName = date('l', $item['dt']);
                        $dailyForecast[] = [
                            'day' => $dayName === date('l') ? 'Today' : ($dayName === date('l', strtotime('+1 day')) ? 'Tomorrow' : date('D', $item['dt'])),
                            'date' => date('M d', $item['dt']),
                            'high' => round($item['main']['temp_max']),
                            'low' => round($item['main']['temp_min']),
                            'condition' => ucfirst($item['weather'][0]['description']),
                            'rain_chance' => isset($item['pop']) ? round($item['pop'] * 100) : 0,
                            'icon' => $item['weather'][0]['icon'] ?? '01d'
                        ];
                        $processedDates[$date] = true;
                        
                        if (count($dailyForecast) >= 10) break; // Google shows 10-day forecast
                    }
                }
                
                $weather['forecast'] = $dailyForecast;
                
                // Process hourly forecast (next 24 hours)
                $hourlyForecast = [];
                $now = time();
                foreach ($forecastData['list'] as $item) {
                    if ($item['dt'] > $now && count($hourlyForecast) < 24) {
                        $hourlyForecast[] = [
                            'time' => date('H:i', $item['dt']),
                            'hour' => date('g A', $item['dt']),
                            'temperature' => round($item['main']['temp']),
                            'condition' => ucfirst($item['weather'][0]['description']),
                            'rain_chance' => isset($item['pop']) ? round($item['pop'] * 100) : 0,
                            'icon' => $item['weather'][0]['icon'] ?? '01d'
                        ];
                    }
                }
                $weather['hourly'] = $hourlyForecast;
            }
            
            return $weather;
            
        } catch (\Exception $e) {
            log_message('error', 'Weather API Error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get wind direction
     */
    private function getWindDirection($degrees)
    {
        $directions = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
        $index = round($degrees / 22.5) % 16;
        return $directions[$index] ?? 'N';
    }
    
    /**
     * Generate Google-style agricultural advisories
     */
    private function generateGoogleStyleAdvisories($currentData, $forecastData)
    {
        $advisories = [];
        
        $temp = $currentData['main']['temp'];
        $humidity = $currentData['main']['humidity'];
        $rainfall = isset($currentData['rain']['1h']) ? $currentData['rain']['1h'] : 0;
        $windSpeed = isset($currentData['wind']['speed']) ? $currentData['wind']['speed'] * 3.6 : 0; // km/h
        
        // Temperature advisories
        if ($temp > 35) {
            $advisories[] = [
                'type' => 'warning',
                'title' => 'High Temperature Warning',
                'message' => 'Extreme heat detected. Ensure adequate irrigation and provide shade for sensitive crops. Avoid field work during midday hours.'
            ];
        } elseif ($temp < 15) {
            $advisories[] = [
                'type' => 'warning',
                'title' => 'Low Temperature Alert',
                'message' => 'Cold temperatures detected. Protect sensitive crops and consider covering plants to prevent frost damage.'
            ];
        }
        
        // Rainfall advisories
        if ($rainfall > 15) {
            $advisories[] = [
                'type' => 'warning',
                'title' => 'Heavy Rainfall Warning',
                'message' => 'Heavy rainfall detected. Secure crops, check drainage systems, and avoid field work. Prepare for possible flooding.'
            ];
        } elseif ($rainfall > 7.5 && $rainfall <= 15) {
            $advisories[] = [
                'type' => 'info',
                'title' => 'Moderate Rainfall',
                'message' => 'Moderate rainfall expected. Check drainage systems and ensure crops are not waterlogged.'
            ];
        } elseif ($rainfall > 0 && $rainfall <= 7.5) {
            $advisories[] = [
                'type' => 'info',
                'title' => 'Light Rainfall',
                'message' => 'Light rain is beneficial for crops. Good time for natural irrigation.'
            ];
        }
        
        // Wind advisories
        if ($windSpeed > 60) {
            $advisories[] = [
                'type' => 'warning',
                'title' => 'Strong Wind Warning',
                'message' => 'Strong winds detected. Secure crops and structures. Avoid field work during high winds.'
            ];
        }
        
        // Favorable conditions
        if ($temp >= 20 && $temp <= 30 && $humidity >= 50 && $humidity <= 75 && $rainfall < 5 && $windSpeed < 30) {
            $advisories[] = [
                'type' => 'info',
                'title' => 'Favorable Planting Conditions',
                'message' => 'Current weather conditions are ideal for planting most crops. Good time for field preparation and planting activities.'
            ];
        }
        
        // Check forecast for upcoming heavy rain
        if (isset($forecastData['list'])) {
            $heavyRainDays = 0;
            foreach ($forecastData['list'] as $item) {
                if (isset($item['rain']['3h']) && $item['rain']['3h'] > 15) {
                    $heavyRainDays++;
                }
            }
            
            if ($heavyRainDays > 0) {
                $advisories[] = [
                    'type' => 'warning',
                    'title' => 'Heavy Rain Expected',
                    'message' => 'Heavy rainfall is expected in the coming days. Prepare in advance: secure crops, check drainage systems, and avoid planting in the next few days.'
                ];
            }
        }
        
        return $advisories;
    }
    
    /**
     * Get mock weather data as fallback (Google-style)
     */
    private function getMockWeatherData()
    {
        return [
            'location' => 'Nasugbu, Batangas',
            'current' => [
                'temperature' => 28,
                'feels_like' => 30,
                'condition' => 'Partly Cloudy',
                'humidity' => 75,
                'wind_speed' => 12,
                'wind_direction' => 'SE',
                'pressure' => 1013,
                'rainfall' => 0,
                'icon' => '02d',
                'sunrise' => '06:00',
                'sunset' => '18:00'
            ],
            'forecast' => [
                [
                    'day' => 'Today',
                    'date' => date('M d'),
                    'high' => 30,
                    'low' => 24,
                    'condition' => 'Partly Cloudy',
                    'rain_chance' => 20,
                    'icon' => '02d'
                ],
                [
                    'day' => 'Tomorrow',
                    'date' => date('M d', strtotime('+1 day')),
                    'high' => 29,
                    'low' => 23,
                    'condition' => 'Cloudy',
                    'rain_chance' => 40,
                    'icon' => '03d'
                ],
                [
                    'day' => date('D', strtotime('+2 days')),
                    'date' => date('M d', strtotime('+2 days')),
                    'high' => 28,
                    'low' => 22,
                    'condition' => 'Heavy Rain',
                    'rain_chance' => 80,
                    'icon' => '09d'
                ],
                [
                    'day' => date('D', strtotime('+3 days')),
                    'date' => date('M d', strtotime('+3 days')),
                    'high' => 27,
                    'low' => 22,
                    'condition' => 'Moderate Rain',
                    'rain_chance' => 70,
                    'icon' => '09d'
                ],
                [
                    'day' => date('D', strtotime('+4 days')),
                    'date' => date('M d', strtotime('+4 days')),
                    'high' => 29,
                    'low' => 23,
                    'condition' => 'Partly Cloudy',
                    'rain_chance' => 30,
                    'icon' => '02d'
                ]
            ],
            'hourly' => [],
            'advisories' => [
                [
                    'type' => 'info',
                    'title' => 'Sample Data',
                    'message' => 'This is sample weather data. Configure Google Weather API key or OpenWeatherMap API key for real-time updates.'
                ]
            ]
        ];
    }
}
