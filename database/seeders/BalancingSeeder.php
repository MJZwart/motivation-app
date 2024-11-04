<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalancingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('village_exp_gain')->insert([
            'task_type' => 'GENERIC',
            'economy' => 3,
            'labour' => 3,
            'craft' => 3,
            'art' => 3,
            'community' => 3,
            'level' => 10,
            'coins' => 7,
        ]);
        DB::table('village_exp_gain')->insert([
            'task_type' => 'PHYSICAL',
            'economy' => 1,
            'labour' => 9,
            'craft' => 4,
            'art' => 1,
            'community' => 3,
            'level' => 10,
            'coins' => 7,
        ]);
        DB::table('village_exp_gain')->insert([
            'task_type' => 'MENTAL',
            'economy' => 6,
            'labour' => 2,
            'craft' => 6,
            'art' => 3,
            'community' => 1,
            'level' => 10,
            'coins' => 7,
        ]);
        DB::table('village_exp_gain')->insert([
            'task_type' => 'SOCIAL',
            'economy' => 3,
            'labour' => 1,
            'craft' => 1,
            'art' => 6,
            'community' => 7,
            'level' => 10,
            'coins' => 7,
        ]);
    }
}
