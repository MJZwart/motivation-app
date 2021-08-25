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
            'name' => 'Finish the day\'s homework.',
            'description' => 'Finish the homework you were assigned for the day.',
            'repeatable' => 'DAILY',
        ],[
            'difficulty' => 1,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ],[
            'difficulty' => ,
            'type' => ,
            'name' => '',
            'description' => '',
            'repeatable' => '',
        ]);
    }
}
