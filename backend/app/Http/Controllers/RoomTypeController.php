<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomTypeResource;
use App\Services\RoomTypeService;
use Illuminate\Support\Facades\Response;

class RoomTypeController extends Controller
{
    public function __construct(private readonly RoomTypeService $roomTypeService) {}

    public function index()
    {
        $roomTypes = $this->roomTypeService->paginate(relations: 'inventories');
        return Response::gen(
            RoomTypeResource::collection($roomTypes)
        );
    }

    public function show(string $roomTypeId)
    {
        $roomType = $this->roomTypeService->getRoomTypesWithRooms($roomTypeId);
        return Response::gen(
            RoomTypeResource::make($roomType)
        );
    }

    public function store() {}

    public function update() {}

    public function destroy() {}
}
