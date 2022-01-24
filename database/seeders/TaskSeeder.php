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
        $examples = DB::table('example_tasks')->get();
        //Tasks
        foreach(User::get() as $user){
            for($i = 0 ; $i < 5 ; $i++){
                $randNr = rand(0, count($examples) -1);
                Task::create([
                    'user_id' => $user->id,
                    'task_list_id' => TaskList::where('user_id', $user->id)->first()->id,
                    'name' => $examples[$randNr]->name,
                    'description' => $examples[$randNr]->description,
                    'repeatable' => $examples[$randNr]->repeatable,
                    'difficulty' => $examples[$randNr]->difficulty,
                    'type' => $examples[$randNr]->type,
                ]);
            }
            
            //Sorry for the mess
            // Task::factory(5)
            //     ->create([
            //         'user_id' => $user->id,
            //         'task_list_id' => TaskList::where('user_id', $user->id)->first()->id
            //     ]);
            // Task::factory(3)
            //     ->create([
            //         'user_id' => $user->id,
            //         'super_task_id' => Task::where('user_id', $user->id)->first()->id,
            //         'task_list_id' => TaskList::where('user_id', $user->id)->first()->id
            //     ]);
            // Task::factory(3)
            //     ->create([
            //         'user_id' => $user->id,
            //         'repeatable' => 'DAILY',
            //         'task_list_id' => TaskList::where('user_id', $user->id)->first()->id
            //     ]);
            
            // DB::table('repeatable_tasks_completed')->insertOrIgnore([
            //     'user_id' => $user->id,
            //     'task_id' => Task::where('user_id', $user->id)->where('repeatable', 'DAILY')->first()->id,
            // ]);
        }

        // ,[
        //     'difficulty' => ,
        //     'type' => ,
        //     'name' => '',
        //     'description' => '',
        //     'repeatable' => '',
        // ]
    }
}
