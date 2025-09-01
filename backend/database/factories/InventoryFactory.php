<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{

    protected $model = Inventory::class;

    public function definition()
    {
        $totalRooms = fake()->numberBetween(3, 12);
        return [
            'total_rooms' => $totalRooms,
            'available_rooms' => $totalRooms - fake()->numberBetween(0, 2),
            'date' => fake()->unique()->dateTimeBetween('+1 days', '+4 weeks')->format('Y-m-d'),
        ];
    }
}
