<?php

namespace App\Http\Controllers;

use App\Models\SubDistrict;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubDistrictController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $subDistricts = SubDistrict::when($request->input('city'), function ($q) use ($request) {
                $q->where('city_id', $request->input('city'));
            })
            ->get()
            ->map(function ($subDistrict) {
                return [
                    'label' => $subDistrict->name,
                    'value' => $subDistrict->id
                ];
            });

        return response()->json([
            'data' => $subDistricts
        ]);
    }
}
