<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_listing' => $this->property_id,
            'full_name' => $this->user->first_name . ' ' . $this->user->last_name,
            'profile_image' => $this->user->image,
            'rate' => $this->rate,
            'content' => $this->content,
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
