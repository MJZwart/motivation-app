<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperiencePoint extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function getCurrentOrMaxExp(int $currentLevel, int $maxLevel) {
        return ExperiencePoint::where('level', $currentLevel)
                    ->orWhere('level', $maxLevel)
                    ->first()->experience_points;
    }
}
