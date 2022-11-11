<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'difficulty',
        'type',
        'task_id',
    ];

    public static function createFromTask(Task $task) {
        Favourite::create([
            'user_id' => Auth::user()->id,
            'name' => $task->name,
            'description' => $task->description,
            'difficulty' => $task->difficulty,
            'type' => $task->type,
            'task_id' => $task->id,
        ]);
    }
}
