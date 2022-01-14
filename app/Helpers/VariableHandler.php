<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Helpers\RewardEnums;

class VariableHandler {

    public static function getExperienceTable(){
        if(!Cache::has('experienceTable')){
            Cache::remember('experienceTable', 60, function () {
                return DB::table('experience_points')->get();
            });
        }
        return Cache::get('experienceTable');
    }

    public const TYPEGENERIC = 1;
    public const TYPEPHYSICAL = 2;
    public const TYPEMENTAL = 3;
    public const TYPESOCIAL = 4;

    public static function getCharacterExpGain(String $type){
        return DB::table('character_exp_gain')
            ->where('task_type', $type)
            ->select('strength', 'agility', 'endurance', 'intelligence', 'charisma', 'level')->first();
    }
    public static function getVillageExpGain(String $type){
        return DB::table('village_exp_gain')
            ->where('task_type', $type)
            ->select('economy', 'labour', 'craft', 'art', 'community', 'level')->first();
    }

    // public static function getCharacterExpGain(){
    //     if(!Cache::has('characterExpGain')) {
    //         Cache::remember('characterExpGain', 60, function () {
    //             return DB::table('character_exp_gain')->get();
    //         });
    //     }
    //     return Cache::get('characterExpGain');
    // }

    // public static function getVillageExpGain(){
    //     if(!Cache::has('villageExpGain')) {
    //         Cache::remember('villageExpGain', 60, function () {
    //             return DB::table('village_exp_gain')->get();
    //         });
    //     }
    //     return Cache::get('villageExpGain');
    // }


}