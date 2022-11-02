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
        //Default JWT signature key.
        if(is_null(env('JWT_KEY'))) {
            putenv ("JWT_KEY=GnRQFxveT6hmrcgTF3LhRFBTsiqpY3ehViJOAy8YSvnUkIshorgw83M9R3x4AMDUB4GuDLM9XQN4OakHwbJwGAgU9fkOIMfaVqqvxkkKrRPoGhUBhdJOm8QSDiAuTbtbbErHMSQGVX8QVXWfwTmcn8brzv7XEM1M5u95ogaCk4d39VHsfoy8YxkXovfircf7CA3lVxRq7R44b033sAYdn127KsW9aA1kZLwZkFqqFceqkjt9XPwSzp8LkOH4oVBw");
        }
    }
}