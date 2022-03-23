<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        $types = ['FEEDBACK', 'SUGGESTION', 'QUESTION', 'OTHER'];
        return [
            'type' => $types[array_rand($types)],
            'text' => $this->faker->sentence,
            'email' => $this->faker->email,
            'archived' => $this->faker->boolean,
            'user_id' => array_rand($users) + 1
        ];
    }
}
