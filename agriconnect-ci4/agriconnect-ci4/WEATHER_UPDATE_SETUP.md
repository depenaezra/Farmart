# Weather Cache Update Setup

The weather system now automatically caches weather data and updates it every 5 minutes. Here's how to set it up:

## Automatic Cache Updates

### Option 1: Cron Job (Recommended for Production)

Set up a cron job to call the cache update endpoint every 5 minutes:

**Linux/Mac:**
```bash
*/5 * * * * curl -s http://your-domain.com/weather/update-cache > /dev/null 2>&1
```

**Windows (Task Scheduler):**
1. Open Task Scheduler
2. Create Basic Task
3. Set trigger to "Daily" and repeat every 5 minutes
4. Action: Start a program
5. Program: `curl`
6. Arguments: `http://your-domain.com/weather/update-cache`

### Option 2: JavaScript Auto-Update (Already Implemented)

The frontend JavaScript automatically triggers cache updates every 5 minutes when users are on the weather page. This works but is less reliable than a cron job.

## How It Works

1. **Cache Storage**: Weather data is cached for 5 minutes using CodeIgniter's cache system
2. **Auto-Refresh**: The cache is automatically checked and updated when:
   - Cache is older than 5 minutes
   - User manually refreshes
   - Background update is triggered
3. **Fallback**: If API fails, cached data (even if expired) is used as fallback

## API Configuration

Add to your `.env` file:
```
OPENWEATHER_API_KEY=your_api_key_here
```

Or:
```
GOOGLE_WEATHER_API_KEY=your_api_key_here
```

## Testing

Test the cache update endpoint:
```bash
curl http://localhost/weather/update-cache
```

Or visit in browser:
```
http://your-domain.com/weather/update-cache
```

## Cache Location

Cache files are stored in: `writable/cache/`

