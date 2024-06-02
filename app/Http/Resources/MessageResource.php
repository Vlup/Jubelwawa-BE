<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'text' => $this->text,
            'user' => new UserResource($this->user),
            'position' => $this->user_id === auth()->user()->id ? 'right' : 'left',
            'property' => new PropertyDetailResource($this->chat->property),
        ];
    }
}
