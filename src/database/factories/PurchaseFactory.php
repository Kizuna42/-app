<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_method' => $this->faker->randomElement(['credit_card', 'bank_transfer']),
            'postal_code' => $this->faker->postcode,
            'address' => $this->faker->address,
            'building_name' => $this->faker->optional()->secondaryAddress,
            'status' => 'completed',
        ];
    }
}
