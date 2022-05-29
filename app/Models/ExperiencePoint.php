<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperiencePoint extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Gets the experience points needed for the current (given) level, or the max
     * level that exists in the database. If the max level is not given, get the max
     * level from the databse first.
     */
    public static function getCurrentOrMaxExp(int $currentLevel, int $maxLevel) {
        if (!$maxLevel) $maxLevel = ExperiencePoint::max('level');
        return ExperiencePoint::where('level', $currentLevel)
                    ->orWhere('level', $maxLevel)
                    ->first()->experience_points;
    }
}
