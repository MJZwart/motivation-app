<?php

namespace Database\Factories;

use App\Models\BugReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class BugReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BugReport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $pages = ['Home', 'Overview', 'Friend', 'Messages', 'Achievements', 'Notifications', 'Login'];
        $types = ['LANGUAGE', 'DESIGN', 'FUNCTIONALITY', 'OTHER'];
        return [
            'title' => $this->faker->jobTitle,
            'page' => $pages[array_rand($pages)],
            'type' => $types[array_rand($types)],
            'severity' => rand(1, 5),
            'user_id' => rand(1, 15),
            'comment' => $this->faker->sentence,
            'status' => rand(0,2),
        ];
    }
}
