<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CastlequestController extends Controller
{
    public function registerPageVisit() 
    {
        $today = Carbon::now()->toDateString();        
        $updated = DB::table('castlequest')->where('date', $today)->incrementEach(['page_visits' => 1, 'quest_reloads' => 1]);
        if ($updated === 0) { 
            $this->createNewEntry($today);
        }
    }

    public function registerNewQuest() 
    {
        $today = Carbon::now()->toDateString();        
        $updated = DB::table('castlequest')->where('date', $today)->increment('quest_reloads');
        if ($updated === 0) { 
            $this->createNewEntry($today);
        }
    }

    private function createNewEntry(string $date) 
    {
        DB::table('castlequest')->insert([
            'date' => $date,
            'page_visits' => 1,
            'quest_reloads' => 1,
        ]);
    }
}
