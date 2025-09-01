<?php

namespace App\Services;

use App\Models\RoomType;

class RoomTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new RoomType);
    }

    public function getRoomTypesWithRooms(string $roomTypeId)
    {
        return RoomType::with('rooms')->findOrFail($roomTypeId);
    }
}
