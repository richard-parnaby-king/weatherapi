<?php

namespace RichardPK\WeatherApi\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class WeatherController extends Controller
{
    protected $weatherService;
    
    public function __construct(
        \RichardPK\WeatherApi\Services\OpenWeather $weatherService
    ) {
        $this->weatherService = $weatherService;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return String
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get(Request $request)
    {
        //Require location parameter
        $request->expectsJson();
        $request->validate([
            'location' => ['required', 'string', 'max:255'],
        ]);
        
        //Get the weather for this location.
        $weather = $this->weatherService->get($request->location);
        
        return response()->json($weather);
    }
    
}
