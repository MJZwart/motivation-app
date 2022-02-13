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
        User::factory(10)
            ->hasCharacters(1)
            ->hasTaskLists(3)
            ->create(['first_login' => false]);
            
        User::factory(10)
            ->hasVillages(1)
            ->hasTaskLists(3)
            ->create(['first_login' => false]);

        User::factory()
            ->hasCharacters(1)
            ->hasTaskLists(2)
            ->create(['username' => 'admin', 'admin' => true]);

        User::factory()
            ->hasCharacters(1)
            ->hasTaskLists(2)
            ->create(['username' => 'chartest', 'first_login' => false]);

        User::factory()
            ->hasVillages(1)
            ->hasTaskLists(2)
            ->create(['username' => 'villtest', 'first_login' => false, 'rewards' => 'VILLAGE']);

        User::factory()
            ->hasTaskLists(2)
            ->create(['username' => 'test', 'first_login' => false, 'rewards' => 'NONE']);
    }
}
