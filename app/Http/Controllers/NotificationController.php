<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function store(Request $request){
        // #68
    }

    public function show(){
        $notifications = Auth::user()->notifications;
        $response = new JsonResponse(['data' => $notifications], Response::HTTP_OK); 
            //Creates the response before marking as read, so the notifications sent are still marked as unread.
        $this->markAsRead($notifications);
        return $response;
    }

    public function destroy(Notification $notification){
        //
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
