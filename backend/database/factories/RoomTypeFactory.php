<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypeFactory extends Factory
{

    protected $model = RoomType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roomTypeNames = [
            'DBL',
            'TWIN',
            'VIP',
        ];

        return [
            'name' => fake()->unique()->randomElement($roomTypeNames),
            'bed_count' => fake()->numberBetween(2 , 8),
        ];
    }

}
