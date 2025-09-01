<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'user' => $this->whenLoaded('user', fn() => []),
            'room_type' => $this->whenLoaded('roomType', fn() => RoomTypeResource::make($this->roomType)),
            'status' => $this->status->value,
            'date_to_reserve' => $this->date_to_reserve->format('Y-m-d'),
        ];
    }
}
