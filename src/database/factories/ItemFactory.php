<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_name' => $this->faker->word(),
            'brand_name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 5000),
            'condition_id' => $this->faker->numberBetween(1, 4),
            'detail' => $this->faker->word(),
            'item_path' => 'items/item1.jpg'
        ];
    }
}
