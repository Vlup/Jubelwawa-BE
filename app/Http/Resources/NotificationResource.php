<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => $this->data['title'],
            'description' => $this->data['description'],
            'province' => $this->data['province'],
            'city' => $this->data['city'],
            'sub_district' => $this->data['sub_district'],
            'image' => $this->data['image'],
            'is_read' => (bool) $this->read_at
        ];
    }
}
