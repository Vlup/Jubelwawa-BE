<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\JsonResponse;

class ProvinceController extends Controller
{
    public function index(): JsonResponse
    {
        $provinces = Province::get()
            ->map(function ($province) {
                return [
                    'label' => $province->name,
                    'value' => $province->id
                ];
            });

        return response()->json([
            'data' => $provinces
        ]);
    }
}
