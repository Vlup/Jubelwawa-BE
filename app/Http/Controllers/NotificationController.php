<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Notification;
use App\Models\SubCategory;
use App\Models\SubDistrict;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class NotificationController extends Controller
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

        $notifications = Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', auth()->user()->id)
            ->latest()
            ->get()
            ->filter(function (Notification $notif) use ($input) {
                $isProvince = $isCity = $isSubDistrict = $isAllotment = $isMinPrice = $isMaxPrice = $isType = $isSubType = true;

                if (isset($input['province'])) {
                    $isProvince = Province::findOrFail($input['province'])->name === $notif->data['province'];
                }

                if (isset($input['city'])) {
                    $isCity = City::findOrFail($input['city'])->name === $notif->data['city'];
                }

                if (isset($input['sub_district'])) {
                    $isSubDistrict = SubDistrict::findOrFail($input['sub_district'])->name === $notif->data['sub_district'];
                }

                if (isset($input['allotment'])) {
                    $isAllotment = $input['allotment'] === $notif->data['offet_type'];
                }

                if (isset($input['min_price'])) {
                    $isMinPrice = $notif->data['price'] >= $input['min_price'];
                }

                if (isset($input['max_price'])) {
                    $isMaxPrice = $notif->data['price'] <= $input['max_price'];
                }
                
                if (isset($input['type'])) {
                    $isType = Category::findOrFail($input['type'])->name === $notif->data['type'];
                }

                if (isset($input['subtype'])) {
                    $isSubType = SubCategory::findOrFail($input['subtype'])->name === $notif->data['subtype'];
                }


                return $isProvince && $isCity && $isSubDistrict && $isAllotment && $isMinPrice && $isMaxPrice && $isType && $isSubType;
            });

        return NotificationResource::collection($notifications);
    }
}
