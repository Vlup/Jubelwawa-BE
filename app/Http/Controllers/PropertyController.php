<?php

namespace App\Http\Controllers;

use App\Models\SubDistrict;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $properties = Property::latest()->get();

        return response()->json([
            'data' => $properties
        ]);
    }
}
