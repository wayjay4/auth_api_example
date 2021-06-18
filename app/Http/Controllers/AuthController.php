<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // set validation rules for request fields
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ];

        // validate and get the request fields
        $fields = $request->validate($rules);

        // create the new user
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // create user api token
        $token = $user->createToken('myapptoken')->plainTextToken;

        // create the response w/user and token data
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        // send the response
        return response($response, 201);
    }

    public function login(Request $request)
    {
        // set the validation rules for request fields
        $rules = [
            'email' => 'required|string',
            'password' => 'required|string',
        ];

        // validate the request fields
        $fields = $request->validate($rules);

        // check email exists
        $user = User::where('email', $fields['email'])->first();

        // verify password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            // set error response
            $e_response = [
                'message' => 'Bad Credentials.'
            ];

            // return error response
            return response($e_response, 401);
        }

        // create user api token
        $token = $user->createToken('myapptoken')->plainTextToken;

        // create the response w/user and token data
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        // return success response
        return response($response, 201);
    }

    public function logout(Request $request)
    {   
        // delete all user's tokens
        auth()->user()->tokens()->delete();

        // create response
        $response = [
            'message' => 'Logged out'
        ];

        return response($response, 201);
    }
}
