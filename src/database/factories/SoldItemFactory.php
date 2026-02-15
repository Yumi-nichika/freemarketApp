<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;

class SoldItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'payment_method' => $this->faker->numberBetween(1, 2),
            'post_code' => '100-0000',
            'address' => $this->faker->prefecture(),
            'building' => '建物名ダミー',
        ];
    }
}
