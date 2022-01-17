<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskList;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = ['GENERIC', 'PHYSICAL', 'SOCIAL', 'MENTAL'];
        return [
            'user_id' => rand(1, User::count()),
            'task_list_id' => rand(1, TaskList::count()),
            'difficulty' => rand(1, 5),
            'type' => $types[rand(0, 3)],
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}

