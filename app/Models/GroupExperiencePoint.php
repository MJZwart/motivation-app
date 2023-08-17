<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupExperiencePoint extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Gets the experience points needed for the current (given) level, or the max
     * level that exists in the database. If the max level is not given, get the max
     * level from the databse first.
     */
    public static function getCurrentOrMaxExp(int $currentLevel, int | null $maxLevel = null) {
        if (!$maxLevel) $maxLevel = GroupExperiencePoint::max('level');
        return GroupExperiencePoint::where('level', $currentLevel)
                    ->orWhere('level', $maxLevel)
                    ->first()->experience_points;
    }
}
