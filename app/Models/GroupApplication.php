<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupApplication extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    public $timestamps = false;

    public $table = 'group_applications';

    public static function newApplication(int $groupId, int $userId)
    {
        GroupApplication::create(['group_id' => $groupId, 'user_id' => $userId]);
    }
}
