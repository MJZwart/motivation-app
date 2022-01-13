<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VariableHandler {

    public static function getExperienceTable(){
        if(!Cache::has('experienceTable')){
            Cache::remember('experienceTable', 60, function () {
                return DB::table('experience_points')->get();
            });
        }
        return Cache::get('experienceTable');
    }


}