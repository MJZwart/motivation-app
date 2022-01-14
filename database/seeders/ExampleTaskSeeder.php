<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExampleTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Example tasks
        DB::table('example_tasks')->insert([
            'difficulty' => 4,
            'type' => 'MENTAL',
            'name' => 'Finish the day\'s homework',
            'description' => 'Finish the homework you were assigned for the day.',
            'repeatable' => 'DAILY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 1,
            'type' => 'PHYSICAL',
            'name' => 'Drink a glass of water',
            'description' => '',
            'repeatable' => 'INFINITE',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 4,
            'type' => 'PHYSICAL',
            'name' => 'Take the stairs',
            'description' => 'Take the stairs instead of the elevator.',
            'repeatable' => 'INFINITE',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 3,
            'type' => 'SOCIAL',
            'name' => 'Compliment a friend',
            'description' => 'Give a friend (or even a stranger) a genuine compliment from the goodness of your heart.',
            'repeatable' => 'DAILY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 5,
            'type' => 'GENERIC',
            'name' => 'Weekly clean up',
            'description' => 'Clean your home/room, water your plants, take out the trash, etc.',
            'repeatable' => 'WEEKLY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 2,
            'type' => 'GENERIC',
            'name' => 'Clear that clutter',
            'description' => 'Clear the desk/table/floor a little bit. Get some breathing room in your home.',
            'repeatable' => 'DAILY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 5,
            'type' => 'SOCIAL',
            'name' => 'Hang out with a friend',
            'description' => 'Go for a walk, see a movie or find another way to hang out with a friend and enjoy your time with them.',
            'repeatable' => 'WEEKLY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 4,
            'type' => 'SOCIAL',
            'name' => 'Finally send that letter',
            'description' => 'Send that letter or e-mail you\'ve been meaning to send for ages.',
            'repeatable' => 'NONE',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 5,
            'type' => 'PHYSICAL',
            'name' => 'Work out',
            'description' => 'Go for a run, do some yoga, go to the gym, whichever way you like to get action in your body.',
            'repeatable' => 'DAILY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 4,
            'type' => 'MENTAL',
            'name' => 'Finish a puzzle',
            'description' => 'Pick a puzzle that fits your level and put your mind to it.',
            'repeatable' => 'DAILY',
        ]);
    }
}
