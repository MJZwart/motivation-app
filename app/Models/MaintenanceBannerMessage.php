<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceBannerMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'starts_at',
        'ends_at',
    ];
}
