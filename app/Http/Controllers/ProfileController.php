<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(Request $request): UserResource
    {
        $user = auth()->user();
        return new UserResource($user);
    }

    public function update(Request $request): JsonResponse
    {
        $user = auth()->user();

        $input = validator($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,phone_number,'.$user->id,
        ])->validate();

        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'] ?? null;
        $user->email = $input['email'];
        $user->phone_number = $input['phone_number'];
        $user->save();

        return response()->json([
            "status" => true,
            'message' => 'Edit profile successfully!',
        ]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $user = auth()->user();
        $input = validator($request->all(), [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8'
        ])->validate();

        if (!Hash::check($input['old_password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Your old password is wrong.'
            ], 400);
        }

        $user->password = Hash::make($input['new_password']);
        $user->save();
        return response()->json([
            "status" => true,
            'message' => 'Change password successfully!',
        ]);
    }
}
