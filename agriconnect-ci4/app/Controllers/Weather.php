<?php

namespace App\Controllers;

class Weather extends BaseController
{
    /**
     * Weather page
     */
    public function index()
    {
        // In production, this would fetch real weather data from API
        // For now, we'll use mock data
        
        $weatherData = [
            'location' => 'Nasugbu, Batangas',
            'current' => [
                'temperature' => 28,
                'condition' => 'Partly Cloudy',
                'humidity' => 75,
                'wind_speed' => 12,
                'rainfall' => 0
            ],
            'forecast' => [
                [
                    'day' => 'Today',
                    'date' => date('M d'),
                    'high' => 30,
                    'low' => 24,
                    'condition' => 'Partly Cloudy',
                    'rain_chance' => 20
                ],
                [
                    'day' => 'Tomorrow',
                    'date' => date('M d', strtotime('+1 day')),
                    'high' => 29,
                    'low' => 23,
                    'condition' => 'Cloudy',
                    'rain_chance' => 40
                ],
                [
                    'day' => date('l', strtotime('+2 days')),
                    'date' => date('M d', strtotime('+2 days')),
                    'high' => 28,
                    'low' => 22,
                    'condition' => 'Rainy',
                    'rain_chance' => 80
                ],
                [
                    'day' => date('l', strtotime('+3 days')),
                    'date' => date('M d', strtotime('+3 days')),
                    'high' => 27,
                    'low' => 22,
                    'condition' => 'Rainy',
                    'rain_chance' => 70
                ],
                [
                    'day' => date('l', strtotime('+4 days')),
                    'date' => date('M d', strtotime('+4 days')),
                    'high' => 29,
                    'low' => 23,
                    'condition' => 'Partly Cloudy',
                    'rain_chance' => 30
                ]
            ],
            'advisories' => [
                [
                    'type' => 'warning',
                    'title' => 'Heavy Rainfall Warning',
                    'message' => 'PAGASA warns of heavy rainfall this weekend. Secure crops and prepare drainage.'
                ],
                [
                    'type' => 'info',
                    'title' => 'Planting Advisory',
                    'message' => 'Current weather conditions favorable for planting tomatoes and leafy vegetables.'
                ]
            ]
        ];
        
        $data = [
            'title' => 'Weather & Agricultural Advisory',
            'weather' => $weatherData
        ];
        
        return view('weather/index', $data);
    }
}
