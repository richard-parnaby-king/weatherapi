<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use RichardPK\WeatherApi\Controllers\UserController;
use RichardPK\WeatherApi\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

//Routes to create a user then login and create a new token for that user.
Route::middleware('api')->prefix('api/user')->group(function () {
    //Create a user
    Route::post('/create', [UserController::class, 'store'])
                ->name('user.create');
                
    //Generate a new token for this user.
    //Tokens are returned in plain text only once so while we can create
    // multiple tokens and each be valid, we cannot recall a previous
    // token for re-use.
    Route::post('/token', [UserController::class, 'token'])
                ->name('user.token');
});


//Endpoint to get weather from remote api. Require token auth.
Route::prefix('api')
       ->middleware('api', 'auth:sanctum')
       ->get('/weather', [WeatherController::class, 'get'])
       ->name('weather.get');
