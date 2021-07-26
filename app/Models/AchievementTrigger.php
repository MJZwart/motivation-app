<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementTrigger extends Model
{
    use HasFactory;

    protected $fillable = ['trigger_type', 'trigger_description'];
}
