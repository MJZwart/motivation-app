<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Tasks
        foreach(User::get() as $user){
            Task::factory(5)
                ->create([
                    'user_id' => $user->id,
                    'task_list_id' => TaskList::where('user_id', $user->id)->first()->id
                ]);
            Task::factory(3)
                ->create([
                    'user_id' => $user->id,
                    'super_task_id' => Task::where('user_id', $user->id)->first()->id,
                    'task_list_id' => TaskList::where('user_id', $user->id)->first()->id
                ]);
            Task::factory(3)
                ->create([
                    'user_id' => $user->id,
                    'repeatable' => 'DAILY',
                    'task_list_id' => TaskList::where('user_id', $user->id)->first()->id
                ]);
            
            DB::table('repeatable_tasks_completed')->insertOrIgnore([
                'user_id' => $user->id,
                'task_id' => Task::where('user_id', $user->id)->where('repeatable', 'DAILY')->first()->id,
            ]);
        }

        DB::table('example_tasks')->insert([
            'difficulty' => 4,
            'type' => 3,
            'name' => 'Finish the day\'s homework',
            'description' => 'Finish the homework you were assigned for the day.',
            'repeatable' => 'DAILY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 1,
            'type' => 2,
            'name' => 'Drink a glass of water',
            'description' => '',
            'repeatable' => 'INFINITE',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 4,
            'type' => 2,
            'name' => 'Take the stairs',
            'description' => 'Take the stairs instead of the elevator.',
            'repeatable' => 'INFINITE',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 3,
            'type' => 4,
            'name' => 'Compliment a friend',
            'description' => 'Give a friend (or even a stranger) a genuine compliment from the goodness of your heart.',
            'repeatable' => 'DAILY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 5,
            'type' => 1,
            'name' => 'Weekly clean up',
            'description' => 'Clean your home/room, water your plants, take out the trash, etc.',
            'repeatable' => 'WEEKLY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 5,
            'type' => 4,
            'name' => 'Hang out with a friend',
            'description' => 'Go for a walk, see a movie or find another way to hang out with a friend and enjoy your time with them.',
            'repeatable' => 'WEEKLY',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 4,
            'type' => 4,
            'name' => 'Finally send that letter',
            'description' => 'Send that letter or e-mail you\'ve been meaning to send for ages.',
            'repeatable' => 'NONE',
        ]);
        DB::table('example_tasks')->insert([
            'difficulty' => 5,
            'type' => 2,
            'name' => 'Work out',
            'description' => 'Go for a run, do some yoga, go to the gym, whichever way you like to get action in your body.',
            'repeatable' => 'DAILY',
        ]);
        // ,[
        //     'difficulty' => ,
        //     'type' => ,
        //     'name' => '',
        //     'description' => '',
        //     'repeatable' => '',
        // ]
    }
}
