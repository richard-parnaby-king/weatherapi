<?php

namespace RichardPK\WeatherApi\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AvailableTest extends TestCase
{
    const EMAIL = 'pqdvord0iki0oaiezn2glce5av0zzhns@jo3pgli9et7dgnunobckyj22i5kwctph.com';

    /**
     * Does the api endpoint create a user?
     * @return void
     */
    public function test_user_can_create_account()
    {
        
        $response = $this->json('POST', '/api/user/create', [
            'email' => self::EMAIL,
            'password' => 'Password1',
            'name' => 'Test User',
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
            'email' => self::EMAIL,
            'password' => 'Password1',
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
        $response = $this->json('GET', '/api/weather');
        
        $response->assertStatus(401);
        $this->assertTrue(json_decode($response->getContent(), true) == ['message' => 'Unauthenticated.']);
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
        $user = User::where('email', self::EMAIL);
        $user->delete();
        
    }
}
