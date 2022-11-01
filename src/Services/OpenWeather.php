<?php
namespace RichardPK\WeatherApi\Services;

use Illuminate\Support\Facades\Http;

class OpenWeather
{
    /**
     * Get weather data at specified location.
     * @param String $location
     * @return Array
     *
     * @throws \Exception
     */
    public function get($location)
    {
        //Get api key from .env file. Default to test api key.
        $apiKey = env('WEATHER_API_KEY', 'f2f3b1952f28e24cddff32322f1c21fc');
        
        //Convert location name into lat/long coordinates
        $response = Http::get('http://api.openweathermap.org/geo/1.0/direct', [
            'q' => sprintf('%s,GB', $location),
            'limit' => '1',
            'appid' => $apiKey,
        ]);
        
        $response = json_decode($response->body(), true);
        
        //If location is not found, throw an exception.
        if(!array_key_exists(0, $response)) {
            throw new \Exception(sprintf('Location "%s" not found.', $location));
        }
        
        //Extract Lat/Long
        $lat = $response[0]['lat'];
        $lon = $response[0]['lon'];
        
        //What's the weather?
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $lat,
            'lon' => $lon,
            'units' => 'metric',
            'appid' => $apiKey,
        ]);
        
        //Return array of data.
        return json_decode($response->body(), true);
    }
}