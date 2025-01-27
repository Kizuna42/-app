<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(100, 100000),
            'image' => 'default.jpg',
            'condition' => $this->faker->randomElement(['新品', '未使用に近い', '目立った傷や汚れなし']),
            'is_sold' => false,
        ];
    }
}
