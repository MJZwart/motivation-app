<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(20)
            ->hasCharacters(1)
            ->hasTaskLists(3)
            ->create(['first_login' => false]);

        User::factory()
            ->hasCharacters(1)
            ->hasTaskLists(2)
            ->create(['username' => 'admin', 'admin' => true]);
    }
}
