<?php
 
namespace RichardPK\WeatherApi;
 
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
 
class ServiceProvider extends BaseServiceProvider
{

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }
}