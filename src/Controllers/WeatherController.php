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
        //Has JWT been supplied? Is it valid?
        if(!$this->requireJwt($request)) {
            return $this->unauthenticated();
        }
        
        //Require location parameter
        $request->expectsJson();
        $request->validate([
            'location' => ['required', 'string', 'max:255'],
        ]);
        
        //Get the weather for this location.
        $weather = $this->weatherService->get($request->location);
        
        return response()->json($weather);
    }
    
    /**
     * Request is not authorised. Return a 401 header.
     * @return Illuminate\Routing\ResponseFactory
     */
    private function unauthenticated() {
        return response(['message' => 'Unauthenticated.'], 401)
            ->header('Content-Type', 'application/json');
    }
    
    /**
     * Check if a JWT Bearer header has been supplied and is valid.
     * @param \Illuminate\Http\Request $request
     * @return Boolean TRUE only if JWT has been supplied and is valid.
     */
    protected function requireJwt($request) {
        
        //Has authorisation header been set?
        $header = $request->header('Authorization');
        if(is_null($header)) {
            return FALSE;
        }
        //Extract JWT from header.
        $jwt = str_replace('Bearer ', '', $header);
        
        //Require JWT token authorisation.
        $tks = explode('.', $jwt);
        if (count($tks) !== 3) {
            return FALSE;
        }
        
        $header64 = $tks[0];
        $payload64 = $tks[1];
        $signature64 = $tks[2];
        
        //Decode header
        $header = json_decode($this->base64UrlDecode($header64), true);
        if(is_null($header)) {
            return FALSE;
        }
        
        //Decode payload
        $payload = json_decode($this->base64UrlDecode($payload64), true);
        if(is_null($payload)) {
            return FALSE;
        }
        
        //Regenerate the signature using our key.
        $signature = $this->base64UrlEncode(hash_hmac(
            'sha256',
            sprintf('%s.%s', $header64, $payload64),
            env('JWT_KEY'),
            true
        ));
        //Compare the signatures.
        if(!hash_equals($signature, $signature64)) {
            return FALSE;
        }
        
        //Confirm issued at timestamp is not set for some future time.
        if(!isset($payload['nbf']) || $payload['nbf'] > time()) {
            return FALSE;
        }
        
        //Confirm JWT has not expired.
        if(!isset($payload['exp']) || time() > $payload['exp']) {
            return FALSE;
        }
        
        //JWT is authenticated. User is authorissed.
        return TRUE;
    }
    
    /**
     * Helper function to remove padding from the string
     * @param mixed
     * @return string
     */
    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Helper function to add padding from the string
     * @param mixed
     * @return string
     */
    private function base64UrlDecode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
