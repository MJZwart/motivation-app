<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function store(Request $request){
        // #68
    }

    public function show(){
        $notifications = Auth::user()->notifications;
        $response = new JsonResponse(['data' => NotificationResource::collection($notifications)], Response::HTTP_OK); 
            //Creates the response before marking as read, so the notifications sent are still marked as unread.
        $this->markAsRead($notifications);
        return $response;
    }

    public function destroy(Notification $notification){
        //
    }

    public function hasUnreadNotifications(){
        return Notification::where('user_id', Auth::user()->id)->where('read', false)->count() > 0;
    }

    private function markAsRead($notificationArray){
        foreach($notificationArray as $notification){
            if(!$notification->read){
                $notification->read = true;
                $notification->update();
            }
        }
    }
}
