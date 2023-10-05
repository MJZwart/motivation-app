<?php

namespace App\Helpers;

use App\Models\ActionTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionTrackingHandler
{

    public static function registerAction(Request $request, String $type, String $action, String $error = null)
    {
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        ActionTracking::create([
            'user_agent' => $request->header('User-Agent'),
            'user_id' => $userId,
            'action_type' => $type,
            'action' => $action,
            'error' => $error
        ]);
    }
}
