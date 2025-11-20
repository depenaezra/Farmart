import { Cloud, CloudRain, Sun, Wind, Droplets, ThermometerSun } from "lucide-react";
import { Page } from "../../App";

interface WeatherPageProps {
  navigate: (page: Page) => void;
}

const forecast = [
  { day: "Today", icon: CloudRain, temp: "28°C", condition: "Rain Showers", humidity: "85%", wind: "15 km/h" },
  { day: "Tomorrow", icon: CloudRain, temp: "27°C", condition: "Heavy Rain", humidity: "90%", wind: "20 km/h" },
  { day: "Friday", icon: Cloud, temp: "29°C", condition: "Cloudy", humidity: "75%", wind: "10 km/h" },
  { day: "Saturday", icon: Sun, temp: "32°C", condition: "Sunny", humidity: "60%", wind: "8 km/h" },
  { day: "Sunday", icon: Sun, temp: "31°C", condition: "Partly Cloudy", humidity: "65%", wind: "12 km/h" }
];

export default function WeatherPage({ navigate }: WeatherPageProps) {
  return (
    <div className="py-8 bg-neutral-50 min-h-screen">
      <div className="container max-w-5xl">
        <h1 className="mb-2">Weather Forecast</h1>
        <p className="text-neutral-600 mb-8">Nasugbu, Batangas - 5-Day Forecast</p>

        {/* Current Weather */}
        <div className="bg-gradient-to-br from-primary to-primary-light text-white rounded-xl shadow-lg p-8 mb-8">
          <div className="flex flex-col md:flex-row items-center justify-between">
            <div className="mb-6 md:mb-0">
              <p className="text-white/90 mb-2" style={{ fontSize: "1.125rem" }}>Today, Nov 20</p>
              <h2 className="text-white mb-4" style={{ fontSize: "3rem", lineHeight: "1" }}>28°C</h2>
              <p className="text-white/90" style={{ fontSize: "1.25rem" }}>Rain Showers Expected</p>
            </div>
            <CloudRain className="w-32 h-32 text-white/20" />
          </div>
          
          <div className="grid grid-cols-3 gap-6 mt-6 pt-6 border-t border-white/20">
            <div className="text-center">
              <Droplets className="w-8 h-8 mx-auto mb-2 text-white/80" />
              <p className="text-white/70" style={{ fontSize: "0.875rem" }}>Humidity</p>
              <p className="text-white" style={{ fontSize: "1.25rem", fontWeight: "600" }}>85%</p>
            </div>
            <div className="text-center">
              <Wind className="w-8 h-8 mx-auto mb-2 text-white/80" />
              <p className="text-white/70" style={{ fontSize: "0.875rem" }}>Wind Speed</p>
              <p className="text-white" style={{ fontSize: "1.25rem", fontWeight: "600" }}>15 km/h</p>
            </div>
            <div className="text-center">
              <ThermometerSun className="w-8 h-8 mx-auto mb-2 text-white/80" />
              <p className="text-white/70" style={{ fontSize: "0.875rem" }}>Feels Like</p>
              <p className="text-white" style={{ fontSize: "1.25rem", fontWeight: "600" }}>30°C</p>
            </div>
          </div>
        </div>

        {/* Weather Alerts */}
        <div className="bg-warning/10 border-l-4 border-warning rounded-lg p-6 mb-8">
          <div className="flex items-start gap-3">
            <div className="bg-warning rounded-full p-2">
              <CloudRain className="w-6 h-6 text-white" />
            </div>
            <div className="flex-1">
              <h3 className="mb-2">⚠️ Weather Advisory</h3>
              <p className="text-neutral-700 mb-2">
                Heavy rainfall expected this weekend. Farmers are advised to:
              </p>
              <ul className="space-y-1 text-neutral-700" style={{ fontSize: "0.875rem" }}>
                <li>• Secure crops and equipment</li>
                <li>• Prepare drainage systems</li>
                <li>• Delay planting until conditions improve</li>
                <li>• Monitor updates from PAGASA</li>
              </ul>
            </div>
          </div>
        </div>

        {/* 5-Day Forecast */}
        <h2 className="mb-6">5-Day Forecast</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          {forecast.map((day, index) => (
            <div
              key={index}
              className="bg-white rounded-xl shadow-md border-2 border-neutral-200 p-6 text-center hover:border-primary transition-colors"
            >
              <h4 className="mb-4">{day.day}</h4>
              <day.icon className="w-16 h-16 mx-auto mb-4 text-primary" />
              <p style={{ fontSize: "2rem", fontWeight: "700" }} className="text-neutral-900 mb-2">
                {day.temp}
              </p>
              <p className="text-neutral-600 mb-4">{day.condition}</p>
              <div className="space-y-2 text-neutral-600" style={{ fontSize: "0.875rem" }}>
                <div className="flex items-center justify-between">
                  <span>💧 Humidity</span>
                  <span className="font-medium">{day.humidity}</span>
                </div>
                <div className="flex items-center justify-between">
                  <span>🌬️ Wind</span>
                  <span className="font-medium">{day.wind}</span>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Farming Tips */}
        <div className="mt-8 bg-primary/5 rounded-xl p-6 border-2 border-primary/20">
          <h3 className="mb-4">🌾 Farming Tips Based on Weather</h3>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="bg-white rounded-lg p-4">
              <h4 className="mb-2">For Rainy Days</h4>
              <ul className="space-y-1 text-neutral-700" style={{ fontSize: "0.875rem" }}>
                <li>• Check drainage systems</li>
                <li>• Protect crops from flooding</li>
                <li>• Store harvested crops in dry areas</li>
              </ul>
            </div>
            <div className="bg-white rounded-lg p-4">
              <h4 className="mb-2">For Sunny Days</h4>
              <ul className="space-y-1 text-neutral-700" style={{ fontSize: "0.875rem" }}>
                <li>• Increase watering frequency</li>
                <li>• Provide shade for sensitive crops</li>
                <li>• Best time for harvesting</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
