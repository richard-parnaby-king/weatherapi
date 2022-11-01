<?php

namespace RichardPK\WeatherApi\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AvailableTest extends TestCase
{

    /**
     * Does the api endpoint create a user?
     * @return void
     */
    public function test_user_can_create_account()
    {
        $response = $this->json('POST', '/api/user/create', [
            'email' => 'richard@parnaby-king.co.uk',
            'password' => 'Password1',
            'name' => 'Richard PK',
        ]);
    
        $response->assertStatus(200);
    }
    
    /**
     * Does the api endpoint create a token for a user?
     * @depends test_user_can_create_account
     * @return String
     */
    public function test_user_can_login_and_create_token()
    {   
        //Login and create token
        $response = $this->json('POST', '/api/user/token', [
            'email' => 'richard@parnaby-king.co.uk',
            'password' => 'Password1',
            'name' => 'Weather API Test',
        ]);
        
        $response->assertStatus(200);
        //Store token for next request
        $token = $response->getContent();
        
        return $token;
    }
    
    /**
     * Does the api endpoint respond
     *
     * @return void
     */
    public function test_weather_api_requires_jwt()
    {
        $response = $this->getJson('http://localhost/api/weather');
        
        $response->assertStatus(401);
        $this->assertTrue($response->getData(true) == ['message' => 'Unauthenticated.']);
    }
    
    /**
     * Does the api endpoint respond
     *
     * @depends test_user_can_login_and_create_token
     * @return void
     */
    public function test_weather_api_returns_details($token)
    {
        $response = $this->json('GET', '/api/weather?location=chester', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        
        $body = $response->getData(true);
        
        $this->assertTrue(
            $body['name'] == 'Chester' &&
            $body['coord']['lon'] == '-2.8909' && 
            $body['coord']['lat'] == '53.1909',
            'Weather endpoint not detected'
        );
        
        //Delete our test user. It is no longer required.
        $user = User::where('email', 'richard@parnaby-king.co.uk');
        $user->delete();
        
    }
}
