<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Hossein Salehi',
            'email' => 'test@example.com',
        ]);

        RoomType::factory()->count(3)->afterCreating(function(RoomType $roomType) {
            $count = fake()->unique()->numberBetween(3 , 12);
            $roomType->rooms()->createMany(
                Room::factory()->count($count)->make()->toArray()
            );
            $roomType->inventory()->createMany(
                Inventory::factory()->count(3)->make([
                    'total_rooms' => $count,
                    'available_rooms' => $count
                ])->toArray()
            );
        })->create();
        
    }
}
