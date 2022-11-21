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
            ->create(['rewards' => 'CHARACTER', 'first_login' => false]);

        User::factory(20)
            ->hasVillages(1)
            ->hasTaskLists(3)
            ->create(['rewards' => 'VILLAGE', 'first_login' => false]);

        User::factory()
            ->hasCharacters(1)
            ->hasTaskLists(2)
            ->create(['rewards' => 'CHARACTER', 'username' => 'admin', 'admin' => true, 'first_login' => false]);

        User::factory()
            ->hasCharacters(1)
            ->hasTaskLists(2)
            ->create(['rewards' => 'CHARACTER', 'username' => 'chartest', 'first_login' => false]);

        User::factory()
            ->hasVillages(1)
            ->hasTaskLists(2)
            ->create(['username' => 'villtest', 'first_login' => false, 'rewards' => 'VILLAGE']);

        User::factory()
            ->hasTaskLists(2)
            ->hasCharacters(1)
            ->create(['username' => 'test', 'first_login' => false, 'rewards' => 'CHARACTER']);

        User::factory(20)
            ->hasTaskLists(2)
            ->create(['first_login' => false, 'rewards' => 'NONE']);

        User::factory()
            ->hasTaskLists(1)
            ->create(['username' => 'cyptest1', 'first_login' => false, 'rewards' => 'NONE']);

        User::factory()
            ->hasTaskLists(1)
            ->create(['username' => 'cyptest2', 'first_login' => false, 'rewards' => 'NONE']);

        User::factory()
            ->hasTaskLists(1)
            ->create(['username' => 'cyptest3', 'first_login' => false, 'rewards' => 'NONE']);

        User::factory()
            ->hasTaskLists(1)
            ->create(['username' => 'cypadmin', 'first_login' => false, 'rewards' => 'NONE', 'admin' => true]);
    }
}
