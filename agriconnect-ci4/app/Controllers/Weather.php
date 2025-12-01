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
     * Generate dynamic agricultural advisories based on current weather
     */
    private function generateDynamicAdvisories($current)
    {
        $advisories = [];
        $temp = isset($current['temp_C']) ? intval($current['temp_C']) : null;
        $humidity = isset($current['humidity']) ? intval($current['humidity']) : null;
        $rainfall = isset($current['precipMM']) ? floatval($current['precipMM']) : 0;
        $windSpeed = isset($current['windspeedKmph']) ? intval($current['windspeedKmph']) : 0;

        // Temperature advisories
        if ($temp !== null && $temp > 35) {
            $advisories[] = [
                'type' => 'warning',
                'title' => 'High Temperature Warning',
                'message' => 'Extreme heat detected. Ensure adequate irrigation and provide shade for sensitive crops. Avoid field work during midday hours.'
            ];
        } elseif ($temp !== null && $temp < 15) {
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
        if ($temp !== null && $temp >= 20 && $temp <= 30 && $humidity !== null && $humidity >= 50 && $humidity <= 75 && $rainfall < 5 && $windSpeed < 30) {
            $advisories[] = [
                'type' => 'info',
                'title' => 'Favorable Planting Conditions',
                'message' => 'Current weather conditions are ideal for planting most crops. Good time for field preparation and planting activities.'
            ];
        }

        // General tips (always shown)
        $advisories[] = [
            'type' => 'info',
            'title' => 'General Farm Management',
            'message' => 'Check and clean irrigation channels. Monitor soil moisture and adjust fertilization.'
        ];

        return $advisories;
    }
    
    /**
     * Get real-time weather data (AJAX endpoint)
     */
    public function getWeather()
    {
        $location = $this->request->getGet('location');
        $lat = $this->request->getGet('lat');
        $lon = $this->request->getGet('lon');

        // If location, lat, or lon are missing or invalid, use Nasugbu Batangas
        if (empty($location) || !is_numeric($lat) || !is_numeric($lon)) {
            $location = 'Nasugbu Batangas';
            $lat = 14.0667;
            $lon = 120.6333;
        }

        $cacheKey = 'weather_' . md5($location . $lat . $lon);
        $cache = \Config\Services::cache();

        // Try to get from cache first
        $cachedData = $cache->get($cacheKey);

        if ($cachedData !== null) {
            $cacheAge = time() - ($cachedData['cached_at'] ?? 0);
            if ($cacheAge < 300) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $cachedData['data'],
                    'last_updated' => date('Y-m-d H:i:s', $cachedData['cached_at']),
                    'from_cache' => true
                ]);
            }
        }

        // Always use Nasugbu Batangas for wttr.in
        $weatherData = $this->fetchWeatherFromAPI(14.0667, 120.6333);

        if ($weatherData) {
            $cache->save($cacheKey, [
                'data' => $weatherData,
                'cached_at' => time()
            ], 300);
            return $this->response->setJSON([
                'success' => true,
                'data' => $weatherData,
                'last_updated' => date('Y-m-d H:i:s'),
                'from_cache' => false
            ]);
        }

        if ($cachedData !== null) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $cachedData['data'],
                'last_updated' => date('Y-m-d H:i:s', $cachedData['cached_at']),
                'from_cache' => true,
                'note' => 'Using cached data. API temporarily unavailable.'
            ]);
        }

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
        // Use wttr.in for simple weather data (no API key required)
        try {
            $client = \Config\Services::curlrequest();
            $locationQuery = urlencode('Nasugbu Batangas');
            $url = "https://wttr.in/{$locationQuery}?format=j1";
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            if (!$data || !isset($data['current_condition'][0])) {
                return null;
            }

            $current = $data['current_condition'][0];
            $weather = [
                'location' => 'Nasugbu, Batangas',
                'current' => [
                    'temperature' => isset($current['temp_C']) ? intval($current['temp_C']) : null,
                    'feels_like' => isset($current['FeelsLikeC']) ? intval($current['FeelsLikeC']) : null,
                    'condition' => isset($current['weatherDesc'][0]['value']) ? $current['weatherDesc'][0]['value'] : '',
                    'humidity' => isset($current['humidity']) ? intval($current['humidity']) : null,
                    'wind_speed' => isset($current['windspeedKmph']) ? intval($current['windspeedKmph']) : null,
                    'wind_direction' => isset($current['winddir16Point']) ? $current['winddir16Point'] : null,
                    'pressure' => isset($current['pressure']) ? intval($current['pressure']) : null,
                    'rainfall' => isset($current['precipMM']) ? floatval($current['precipMM']) : 0,
                    'icon' => '01d', // wttr.in does not provide icons, use default
                    'sunrise' => isset($data['weather'][0]['astronomy'][0]['sunrise']) ? $data['weather'][0]['astronomy'][0]['sunrise'] : null,
                    'sunset' => isset($data['weather'][0]['astronomy'][0]['sunset']) ? $data['weather'][0]['astronomy'][0]['sunset'] : null
                ],
                'forecast' => [],
                'hourly' => [],
                'advisories' => $this->generateDynamicAdvisories($current)
            ];


            // Simple daily forecast (next 3 days)
            if (isset($data['weather'])) {
                foreach ($data['weather'] as $i => $day) {
                    $weather['forecast'][] = [
                        'day' => $i === 0 ? 'Today' : ($i === 1 ? 'Tomorrow' : date('D', strtotime($day['date']))),
                        'date' => $day['date'],
                        'high' => isset($day['maxtempC']) ? intval($day['maxtempC']) : null,
                        'low' => isset($day['mintempC']) ? intval($day['mintempC']) : null,
                        'condition' => isset($day['hourly'][4]['weatherDesc'][0]['value']) ? $day['hourly'][4]['weatherDesc'][0]['value'] : '',
                        'rain_chance' => isset($day['hourly'][4]['chanceofrain']) ? intval($day['hourly'][4]['chanceofrain']) : 0,
                        'icon' => '01d'
                    ];
                }
            }

            // Simple hourly forecast (next 8 hours)
            if (isset($data['weather'][0]['hourly'])) {
                foreach ($data['weather'][0]['hourly'] as $hour) {
                    $weather['hourly'][] = [
                        'time' => isset($hour['time']) ? $hour['time'] : '',
                        'hour' => isset($hour['time']) ? sprintf('%02d:00', intval($hour['time'])/100) : '',
                        'temperature' => isset($hour['tempC']) ? intval($hour['tempC']) : null,
                        'condition' => isset($hour['weatherDesc'][0]['value']) ? $hour['weatherDesc'][0]['value'] : '',
                        'rain_chance' => isset($hour['chanceofrain']) ? intval($hour['chanceofrain']) : 0,
                        'icon' => '01d'
                    ];
                }
            }

            return $weather;
        } catch (\Exception $e) {
            log_message('error', 'wttr.in API Error: ' . $e->getMessage());
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
