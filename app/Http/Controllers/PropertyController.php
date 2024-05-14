<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Models\Province;
use App\Models\City;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubDistrict;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request): PropertyResource
    {
        $status = $request->input('status');
        $properties = Property::with('category', 'subCategory', 'province', 'city', 'subDistrict')
            ->when($status, function ($q) use ($status) {
                $q->where('offer_type', $status);
            })
            ->latest()
            ->get();

        return new PropertyResource($properties);
    }

    public function store(Request $request)
    {
        $input = validator($request->all(), [
            'province' => 'required|exists:provinces,id',
            'city' => 'required|exists:cities,id',
            'sub_district' => 'required|exists:sub_districts,id',
            'name' => 'required|string',
            'price' => 'required|numeric|min:1',
            'image' => 'required|string',
            'description' => 'required|text',
            'bedroom' => 'required|numeric|min:1',
            'bathroom' => 'required|numeric|min:1',
            'land_size' => 'required|numeric|min:1',
            'building_size' => 'required|numeric|min:1',
            'type' => 'required|exists:categories,id',
            'sub_type' => 'required|exists:sub_categories,id',
            'status' => 'required|in:SELL,LEASE,BUY,RENT' 
        ])->validate();

        $property = new Property();
        $property->province_id = $input['province'];
        $property->city_id = $input['city'];
        $property->sub_district_id = $input['sub_district'];
        $property->title = $input['name'];
        $property->description = $input['description'];
        $property->image = $input['image'];
        $property->price = $input['price'];
        $property->bedroom = $input['bedroom'];
        $property->bathroom = $input['bathroom'];
        $property->land_size = $input['land_size'];
        $property->building_size = $input['building_size'];
        $property->category_id = $input['type'];
        $property->sub_category_id = $input['subtype'];
        $property->offer_type = $input['status'];
        $property->save();

        return response()->json([
            "status" => true,
            'message' => 'Property created successfully!',
        ]);
    }

    public function selectOption()
    {
        $provinces = Province::get()
            ->map(function ($province) {
                return [
                    'label' => $province->name,
                    'value' => $province->id
                ];
            })
            ->toArray();

        $status = [
            ['label' => Property::SELL , 'value' => Property::SELL],
            ['label' => Property::LEASE , 'value' => Property::LEASE],
            ['label' => Property::BUY , 'value' => Property::BUY],
            ['label' => Property::RENT , 'value' => Property::RENT],
        ];

        $type = Category::get()
            ->map(function ($category) {
                return [
                    'label' => $category->name,
                    'value' => $category->id,
                ];
            })
            ->toArray();

        return response()->json([
            'data' => [
                'status' => $status,
                'province' => $provinces,
                'type' => $type
            ]
        ]);
    }

    public function getCityByProvince(Request $request)
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
        })
        ->toArray();

        return response()->json([
            'data' => $cities
        ]);
    }

    public function getSubDistrictByCity(Request $request)
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
        })
        ->toArray();

        return response()->json([
            'data' => $subDistricts
        ]);
    }

    public function getSubCategoryByCategory(Request $request)
    {
        $subType = SubCategory::when($request->input('type'), function ($q) use ($request) {
            $q->where('category_id', $request->input('type'));
        })
        ->get()
        ->map(function ($subCategory) {
            return [
                'label' => $subCategory->name,
                'value' => $subCategory->id
            ];
        })
        ->toArray();

        return response()->json([
            'data' => $subType
        ]);
    }
}
