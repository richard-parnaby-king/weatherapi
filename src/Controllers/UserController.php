<?php

namespace RichardPK\WeatherApi\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return String
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //Usually ajax requests will submit the accepts:application/json header. If they don't
        // and the validation throws its exception, Laravel will store the errors in message
        // session variable to be shown on the next page load and will redirec the user back
        // to the homepage. We don't want that. Tell Laravel the response is expected to be JSON.
        $request->expectsJson();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);
        
        //Validation passed. Create a new user.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        //Success message.
        return response()->json(['msg' => 'User Created']);
    }
    
    /**
     * Handle an incoming token request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return String
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function token(Request $request)
    {
        $request->expectsJson();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', Rules\Password::defaults()],
        ]);
        
        //Find the user with this email address.
        $user = User::where('email', $request->email)->first();
     
        //If there is no user or the user's password does not match what has been supplied,
        // then throw a validation message.
        if (!$user || ! Hash::check($request->password, $user->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
     
        //Creat a new token. Identify the token with the supplied name.
        return $user->createToken($request->name)->plainTextToken;
    }
}
