<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupApplication extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    public $table = 'group_applications';
}
