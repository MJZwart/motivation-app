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
        User::factory(30)
            ->hasVillages(1)
            ->hasTaskLists(3)
            ->create(['rewards' => 1, 'first_login' => false]);

        User::factory()
            ->hasVillages(1)
            ->hasTaskLists(2)
            ->create(['rewards' => 1, 'username' => 'admin', 'admin' => true, 'first_login' => false]);

        User::factory()
            ->hasVillages(1)
            ->hasTaskLists(2)
            ->create(['username' => 'villtest', 'first_login' => false, 'rewards' => 1]);

        User::factory()
            ->hasTaskLists(2)
            ->hasVillages(1)
            ->create(['username' => 'test', 'first_login' => false, 'rewards' => 1]);

        User::factory(20)
            ->hasTaskLists(2)
            ->create(['first_login' => false, 'rewards' => 0]);

        User::factory()
            ->hasTaskLists(1)
            ->create(['username' => 'cyptest1', 'first_login' => false, 'rewards' => 0]);

        User::factory()
            ->hasTaskLists(1)
            ->create(['username' => 'cyptest2', 'first_login' => false, 'rewards' => 0]);

        User::factory()
            ->hasTaskLists(1)
            ->create(['username' => 'cyptest3', 'first_login' => false, 'rewards' => 0]);

        User::factory()
            ->hasTaskLists(1)
            ->create(['username' => 'cypadmin', 'first_login' => false, 'rewards' => 0, 'admin' => true]);
    }
}
