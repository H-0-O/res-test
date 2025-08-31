<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{

    protected $model = Inventory::class;

    public function definition()
    {
        return [
            'date' => fake()->unique()->dateTimeBetween('+1 days', '+4 weeks')->format('Y-m-d'),
        ];
    }
}
