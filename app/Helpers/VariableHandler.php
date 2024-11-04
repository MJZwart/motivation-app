<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\ExperiencePoint;

class VariableHandler
{
    public static function getExperienceTable()
    {
        if (!Cache::has('experienceTable')) {
            Cache::remember('experienceTable', 60, function () {
                return ExperiencePoint::get();
            });
        }
        return Cache::get('experienceTable');
    }

    public static function getVillageExpGain(String $type)
    {
        return DB::table('village_exp_gain')
            ->where('task_type', $type)
            ->select('economy', 'labour', 'craft', 'art', 'community', 'level', 'coins')->first();
    }
}
