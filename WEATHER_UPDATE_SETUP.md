# Weather Setup Guide

The weather page now supports a real weather API when configured, and falls back to wttr.in or sample data when the key is missing.

## 1. Choose the API source

Recommended: OpenWeatherMap.

If `OPENWEATHER_API_KEY` is not set, the app will automatically fall back to wttr.in.

## 2. Add the API key to `.env`

Open your project `.env` file and add:

```env
OPENWEATHER_API_KEY=your_openweather_api_key_here
```

If your key contains spaces or special characters, wrap it in quotes:

```env
OPENWEATHER_API_KEY="your key here"
```

## 3. Get an OpenWeatherMap key

1. Create an account at OpenWeatherMap.
2. Generate an API key from your dashboard.
3. Paste the key into `OPENWEATHER_API_KEY`.

## 4. Test the live endpoint

Open this in your browser:

```text
http://localhost:8080/weather/api?lat=14.0667&lon=120.6333
```

You should see JSON with `success: true` and weather data.

## 5. Test the cache updater

Open this in your browser:

```text
http://localhost:8080/weather/update-cache?format=json
```

Or run:

```bash
curl http://localhost:8080/weather/update-cache?format=json
```

## 6. Set up automatic refresh

### Production cron job

Run every 5 minutes:

```bash
*/5 * * * * curl -s http://your-domain.com/weather/update-cache > /dev/null 2>&1
```

### Windows Task Scheduler

1. Open Task Scheduler.
2. Create a Basic Task.
3. Set the trigger to repeat every 5 minutes.
4. Set the action to start a program.
5. Program: `curl`
6. Arguments: `http://localhost:8080/weather/update-cache?format=json`

## 7. What happens if no API key is set?

1. The app uses wttr.in automatically.
2. If wttr.in is unavailable, the page falls back to sample weather data.
3. Cached data is reused when available.

## 8. Cache behavior

1. Weather data is cached for 5 minutes.
2. Manual refresh updates the cache.
3. Background refresh also updates the cache when the page is open.

## 9. Where cache lives

Cache files are stored in:

```text
writable/cache/
```

