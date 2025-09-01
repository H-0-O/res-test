<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'  => $this->id,
            'name' => $this->name,
            'bed_count' => $this->bed_count,
            'rooms' => $this->whenLoaded('rooms', fn() => RoomResource::collection($this->rooms)),
            'inventories' => $this->whenLoaded('inventories' , fn() => InventoryResource::collection($this->inventories)),
        ];
    }
}
