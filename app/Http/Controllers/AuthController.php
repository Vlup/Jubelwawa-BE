<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $rules = [
            'fullname' => 'required|max:255',
            'email' => 'required|email:dns|unique:users,email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,phone_number',
            'password' => 'required|min:8|max:255',
            'confirmation_password' => 'required|min:8|max:255'
        ];

        $input = $request->validate($rules);

        if ($input['password'] !== $input['confirmation_password']) {
            return response()->json([
                "status" => false,
                "message" => 'The password confirmation does not match.'
            ], 400);
        }

        $input['password'] = Hash::make($input['password']);

        [$firstName, $lastName] = $this->splitFullName($input['fullname']);

        $user = new User();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $input['email'];
        $user->phone_number = $input['phone_number'];
        $user->password = $input['password'];
        $user->save();

        return response()->json([
            "status" => true,
            'message' => 'Registration successful! Please login.',
        ]);
    }

    public function login(Request $request)
    {
        $input = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);
    
        $type = filter_var($input['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        $credentials = [
            $type => $input['login'],
            'password' => $input['password']
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                "status" => false,
                "message" => 'Email & Password is invalid!'
            ], 401);
        }

        $user = User::where($type, $input['login'])->first();

        return response()->json([
            "status" => true,
            'message' => 'Login successful!',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();

        return response()->json([
            "status" => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function splitFullName($fullName) {
        $fullName = trim($fullName);
    
        $nameParts = explode(' ', $fullName);
    
        $firstName = null;
        $lastName = null;
    
        if (count($nameParts) == 1) {
            $firstName = $nameParts[0];
        } else {
            $lastName = array_pop($nameParts);
            $firstName = implode(' ', $nameParts);
        }
    
        return [$firstName, $lastName];
    }
}
