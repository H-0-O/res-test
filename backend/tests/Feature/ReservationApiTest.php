<?php

namespace Tests\Feature;

use App\Enums\ReservationStatusEnum;
use App\Jobs\CleanupExpiredReservationJob;
use App\Models\Inventory;
use App\Models\RoomType;
use App\Models\User;
use App\Services\ReservationService;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Async\Pool;
use Tests\TestCase;
use Throwable;

class ReservationApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Inventory $inventory;
    private RoomType $roomType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->roomType = RoomType::factory()->create();
        $this->inventory = Inventory::factory()->create([
            'room_type_id' => $this->roomType->id,
            'available_rooms' => 3
        ]);
    }

    public function test_reserve_api_success(): void
    {
        /** @var Carbon $date */
        $date = $this->inventory->date;

        $response = $this->postJson('/api/reservations', [
            'room_type_id' => $this->roomType->id,
            'requested_count' => 2,
            'date_to_reserve' => $date->format('Y-m-d'),
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventories', [
            'id' => $this->inventory->id,
            'available_rooms' => 1,
        ]);
    }


    public function test_reserve_api_not_availability_failure()
    {
        /** @var Carbon $date */
        $date = $this->inventory->date;
        $date->addDay();

        $response = $this->postJson('/api/reservations', [
            'room_type_id' => $this->roomType->id,
            'requested_count' => 2,
            'date_to_reserve' => $date->format('Y-m-d'),
        ]);

        $response->assertStatus(500);
    }

    public function test_over_selling_failure()
    {
        /** @var Carbon $date */
        $date = $this->inventory->date;

        $response = $this->postJson('/api/reservations', [
            'room_type_id' => $this->roomType->id,
            'requested_count' => $this->inventory->available_rooms + $this->inventory->total_rooms,
            'date_to_reserve' => $date->format('Y-m-d'),
        ]);

        $response->assertStatus(500);
    }

    public function test_parallel_api_call()
    {
        $date = $this->inventory->date->format('Y-m-d');
        $pool = Pool::create();
        $statuses = [];
        for ($i = 0; $i < 3; $i++) {
            $pool->add(function () use ($date, $i) {
                $response = $this->postJson('/api/reservations', [
                    'room_type_id' => $this->roomType->id,
                    'requested_count' => $i + 1,
                    'date_to_reserve' => $date,
                ]);
                return $response->getStatusCode();
            })->then(function ($status) use (&$statuses) {
                $statuses[] = $status;
            })->catch(function (Throwable $e) {
                $this->fail("Task failed with error: " . $e->getMessage());
            });
        }
        $pool->wait();

        foreach ($statuses as $status) {
            $this->assertContains($status, [200, 500]);
        }

        $inventory = Inventory::find($this->inventory->id);
        $this->assertGreaterThanOrEqual(0, $inventory->available_rooms);
    }
}
