<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->title,
            'description' => $this->description,
            'province' => $this->province->name,
            'city' => $this->city->name,
            'sub_district' => $this->subDistrict->name,
            'type' => $this->category->name,
            'sub_type' => $this->subCategory->name,
            'image' => $this->image,
            'price' => "Rp. " . number_format($this->price),
            'status' => $this->offer_type,
            'is_sold' => $this->is_sold,
            'land_size' => $this->land_size,
            'building_size' => $this->building_size,
            'bedroom' => $this->bedroom,
            'bathroom' => $this->bathroom,
        ];
    }
}
