<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Resources\NotificationResource;
use App\Http\Requests\SendNotificationRequest;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function store(Request $request){
        // #68
    }

    /**
     * Returns a user's notifications. Marks all unread as read
     */
    public function show(){
        /** @var User */
        $user = Auth::user();
        $notificationQuery = $user->notifications()->latest();
        $notificationCollection = $notificationQuery->get();
        $response = new JsonResponse(['data' => NotificationResource::collection($notificationCollection)], Response::HTTP_OK); 
            //Creates the response before marking as read, so the notifications sent are still marked as unread.
        if ($this->needsUpdate($notificationCollection))
            $this->markAsRead($notificationQuery);
        return $response;
    }

    public function destroy(Request $request, Notification $notification){
        if($notification->user_id == Auth::user()->id){
            $notification->delete();
            ActionTrackingHandler::handleAction($request, 'DELETE_NOTIFICATION', 'Deleting notification');
            return new JsonResponse(['message' => ['success' => 'Notification deleted.'], 'data' => NotificationResource::collection(Auth::user()->notifications)], Response::HTTP_OK); 
        } else {
            ActionTrackingHandler::handleAction($request, 'DELETE_NOTIFICATION', 'Deleting notification', 'Not authorized');
            return new JsonResponse(['message' => 'You are not authorized to do this.'], Response::HTTP_FORBIDDEN); 
        }
    }

    /**
     * Sets all notifications as read if any of them are unread
     */
    private function markAsRead($notificationQuery){
        $notificationQuery->where('read', false)->update(['read' => true]);
    }
    
    /**
     * Checks if any notification in the collection needs to be marked as read
     */
    private function needsUpdate($notificationCollection): bool {
        return $notificationCollection->contains('read', false);
    }

    /**
     * Admin function
     * Sends a notification to all users
     */
    public function sendNotificationToAll(SendNotificationRequest $request){
        $validated = $request->validated();
        foreach(User::lazy() as $user){
            Notification::create(['user_id' => $user->id,
            'title' => $validated['title'],
            'text' => $validated['text']]);
        }
        return new JsonResponse(['message' => ['success' => 'Notification sent.']]);
    }
}
