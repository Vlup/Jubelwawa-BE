<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyDetailResource;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Models\Province;
use App\Models\City;
use App\Models\Category;
use App\Models\Like;
use App\Models\Review;
use App\Models\SubCategory;
use App\Models\SubDistrict;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PropertyController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $input = validator($request->all(), [
            'province' => 'nullable|exists:provinces,id',
            'city' => 'nullable|exists:cities,id',
            'sub_district' => 'nullable|exists:sub_districts,id',
            'allotment' => 'nullable|in:Sell,Lease,Buy,Rent',
            'min_price' => 'nullable|numeric|min:1',
            'max_price' => 'nullable|numeric|min:1',
            'type' => 'nullable|exists:categories,id',
            'subtype' => 'nullable|exists:sub_categories,id'
        ])->validate();

        $properties = Property::with([
            'category', 
            'subCategory',
            'province', 
            'city', 
            'subDistrict', 
            'likes' => function ($q) {
                $q->where('user_id', auth()->user()?->id ?? null);
            },
            'views' => function ($q) {
                $q->where('user_id', auth()->user()?->id ?? null);
            }])
            ->when(isset($input['province']), function ($q) use ($input) {
                $q->where('province_id', $input['province']);
            })
            ->when(isset($input['city']), function ($q) use ($input) {
                $q->where('city_id', $input['city']);
            })
            ->when(isset($input['sub_district']), function ($q) use ($input) {
                $q->where('sub_district_id', $input['sub_district']);
            })
            ->when(isset($input['allotment']), function ($q) use ($input) {
                $q->where('offer_type', $input['allotment']);
            })
            ->when(isset($input['min_price']), function ($q) use ($input) {
                $q->where('price', '>=', $input['min_price']);
            })
            ->when(isset($input['max_price']), function ($q) use ($input) {
                $q->where('price', '<=', $input['max_price']);
            })
            ->when(isset($input['type']), function ($q) use ($input) {
                $q->where('category_id', $input['type']);
            })
            ->when(isset($input['subtype']), function ($q) use ($input) {
                $q->where('sub_category_id', $input['subtype']);
            })
            ->withCount('views')
            ->latest()
            ->get();

        return PropertyResource::collection($properties);
    }

    public function show($id)
    {
        $property = Property::with([
            'reviews.user',
            'likes' => function ($q) {
                $q->where('user_id', auth()->user()?->id ?? null);
            },
            'views' => function ($q) {
                $q->where('user_id', auth()->user()?->id ?? null);
            }])
            ->withCount('views')
            ->findOrFail($id);
        
        return new PropertyDetailResource($property);
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
            'description' => 'required|string',
            'bedroom' => 'required|numeric|min:1',
            'bathroom' => 'required|numeric|min:1',
            'land_size' => 'required|numeric|min:1',
            'building_size' => 'required|numeric|min:1',
            'type' => 'required|exists:categories,id',
            'subtype' => 'required|exists:sub_categories,id',
            'status' => 'required|in:Sell,Lease,Buy,Rent' 
        ])->validate();

        $property = new Property();
        $property->user_id = auth()->user()->id;
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

    public function update(Request $request, $id)
    {
        $input = validator($request->all(), [
            'province' => 'required|exists:provinces,id',
            'city' => 'required|exists:cities,id',
            'sub_district' => 'required|exists:sub_districts,id',
            'name' => 'required|string',
            'price' => 'required|numeric|min:1',
            'image' => 'required|string',
            'description' => 'required|string',
            'bedroom' => 'required|numeric|min:1',
            'bathroom' => 'required|numeric|min:1',
            'land_size' => 'required|numeric|min:1',
            'building_size' => 'required|numeric|min:1',
            'type' => 'required|exists:categories,id',
            'subtype' => 'required|exists:sub_categories,id',
            'status' => 'required|in:Sell,Lease,Buy,Rent' 
        ])->validate();

        $property = Property::findOrFail($id);
        $property->user_id = auth()->user()->id;
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
            'message' => 'Property updated successfully!',
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

    public function likeProperty($propertyId)
    {
        $userId = auth()->user()->id;
        $like = Like::where('user_id', $userId)
                    ->where('property_id', $propertyId)
                    ->first();

        if ($like) {
            $like->delete();
            return response()->json([
                'status' => true,
                'message' => 'Property unliked successfully.',
            ]);
        } else {
            Like::create([
                'user_id' => $userId,
                'property_id' => $propertyId,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Property liked successfully.',
            ]);
        }
    }

    public function addView($propertyId)
    {
        $userId = auth()->user()->id;

        $view = View::where('user_id', $userId)
                    ->where('property_id', $propertyId)
                    ->first();

        if (!$view) {
            View::create([
                'user_id' => $userId,
                'property_id' => $propertyId,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'View added successfully.',
        ]);
    }

    public function review(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $input = validator($request->all(), [
            'rate' => 'required|numeric|min:1|max:5',
            'content' => 'required|string|max:200'
        ])->validate();
        
        $review = new Review();
        $review->user_id = auth()->user()->id;
        $review->property_id = $property->id;
        $review->rate = $input['rate'];
        $review->content = $input['content'];
        $review->save();

        return response()->json([
            'status' => true,
            'message' => 'Review property successfully.',
        ]);
    }
}
