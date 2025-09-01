<?php

namespace Tests\Unit;

use App\Dto\ReserveDto;
use App\Enums\ReservationStatusEnum;
use App\Jobs\CleanupExpiredReservationJob;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservationService = $this->app->make(ReservationService::class);

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

    public function test_expiration_of_reservation()
    {

        $reservation = Reservation::create([
            // because we don't have authentication and login for now , I just fetch test user from db 
            'user_id' => User::first()->id,
            'room_type_id' => $this->roomType->id,
            'status' => ReservationStatusEnum::ACTIVE,
            'requested_count' => 2,
            'date_to_reserve' => $this->inventory->date->format('Y-m-d'),
            'expiration_time' => Carbon::now()->subMinutes(2)->setSeconds(0)
        ]);

        $this->inventory->available_rooms -= 2;
        $this->inventory->save();

        $this->assertDatabaseHas('inventories', [
            'id' => $this->inventory->id,
            'available_rooms' => 1,
        ]);


        $job = new CleanupExpiredReservationJob();
        $job->handle($this->reservationService);

        $this->assertDatabaseHas('inventories', [
            'id' => $this->inventory->id,
            'available_rooms' => 3,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => ReservationStatusEnum::EXPIRED->value,
        ]);
    }
}
