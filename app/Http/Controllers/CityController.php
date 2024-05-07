<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cities = City::when($request->input('province'), function ($q) use ($request) {
                $q->where('province_id', $request->input('province'));
            })
            ->get()
            ->map(function ($city) {
                return [
                    'label' => $city->name,
                    'value' => $city->id
                ];
            });

        return response()->json([
            'data' => $cities
        ]);
    }
}
