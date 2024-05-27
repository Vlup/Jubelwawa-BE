<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $input = validator($request->all(), [
            'property' => 'required|exists,properties,id'
        ])->validate();

        $property = Property::findOrFail($input['property']);
        $agent = $property->user;
        $user = auth()->user();
    }
}
