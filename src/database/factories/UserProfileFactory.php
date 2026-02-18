<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserProfileFactory extends Factory
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
            'post_code' => '100-0000',
            'address' => $this->faker->prefecture(),
            'building' => '建物名ダミー',
            'icon_path' => 'icons/test_icon.jpg'
        ];
    }
}
