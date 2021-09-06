<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'difficulty',
        'repeatable',
    ];
}
