<?php

namespace Tests\Unit;

use App\Dto\ReserveDto;
use App\Models\Inventory;
use App\Models\Reservation;
use App\Models\RoomType;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReserveServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Inventory $inventory;
    private RoomType $roomType;
    
    private ReservationService $reservationService;

    protected function setUp(): void{
        parent::setUp();
        $this->reservationService = app(ReservationService::class);

        $this->user = User::factory()->create();
        $this->roomType = RoomType::factory()->create();
        $this->inventory = Inventory::factory()->create([
            'room_type_id' => $this->roomType->id, 
            'available_rooms' => 3
        ]);
    }
    /**
     * A basic unit test example.
     */
    public function test_reserve_method_success(): void
    {
        
        $re = $this->reservationService->reserve(new ReserveDto(
            $this->roomType->id,
            2,
            $this->inventory->date
        ));

        $this->assertInstanceOf(Reservation::class, $re);
    }
}
