<?php

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'description' => $this->description,
            'province' => $this->province->name,
            'city' => $this->city->name,
            'sub_district' => $this->subDistrict->name,
            'type' => $this->category->name,
            'sub_type' => $this->subCategory->name,
            'image' => $this->image,
            'price_short' => $this->shortPriceForm(),
            'price_long' => "Rp. " . number_format($this->price),
            'status' => $this->offer_type,
            'is_sold' => $this->is_sold,
            'land_size' => $this->land_size,
            'building_size' => $this->building_size,
            'bedroom' => $this->bedroom,
            'bathroom' => $this->bathroom,
            'is_like' => $this->likes->count() > 0 ?? false,
            'total_view' => $this->views_count,
            'reviews' => ReviewResource::collection($this->reviews)
        ];
    }

    private function shortPriceForm(): string
    {
        if ($this->price < 1000000000) {
            return "Rp. " . number_format($this->price/1000000, 2) . " Juta";
        }

        return "Rp. " . number_format($this->price/1000000000, 2) . " M";
    }
}
