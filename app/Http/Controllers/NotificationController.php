<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\NotificationHandler;
use App\Helpers\ResponseWrapper;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\NotificationResource;
use App\Http\Requests\SendNotificationRequest;

class NotificationController extends Controller
{
    /**
     * Returns a user's notifications. Marks all unread as read
     * 
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $resource = $this->getSortedNotificationsResource();
        return new JsonResponse(['data' => $resource]);
    }

    private function getSortedNotificationsResource()
    {
        /** @var User */
        $user = Auth::user();
        $notificationQuery = $user->notifications()->latest();
        $notificationCollection = $notificationQuery->get();
        $resource = NotificationResource::collection($notificationCollection);
        //Creates the response before marking as read, so the notifications sent are still marked as unread.
        if ($this->needsUpdate($notificationCollection))
            $this->markAsRead($notificationQuery);
        return $resource;
    }

    /**
     * Deletes notification from database.
     *
     * @param Request $request
     * @param Notification $notification
     * @return JsonResponse
     */
    public function destroy(Request $request, Notification $notification): JsonResponse
    {
        if ($notification->user_id == Auth::user()->id) {
            $notification->delete();
            ActionTrackingHandler::handleAction($request, 'DELETE_NOTIFICATION', 'Deleting notification');
            $resource = $this->getSortedNotificationsResource();
            return ResponseWrapper::successResponse(_('messages.notification.deleted'), ['notifications' => $resource]);
        } else {
            ActionTrackingHandler::handleAction($request, 'DELETE_NOTIFICATION', 'Deleting notification', 'Not authorized');
            return ResponseWrapper::forbiddenResponse(_('messages.not_authorized'));
        }
    }

    /**
     * Sets all notifications as read if any of them are unread
     * 
     * @param Query $notificationQuery
     * @return void
     */
    private function markAsRead($notificationQuery)
    {
        $notificationQuery->where('read', false)->update(['read' => true]);
    }

    /**
     * Checks if any notification in the collection needs to be marked as read
     * 
     * @param Collection $notificationCollection
     * @return Boolean
     */
    private function needsUpdate($notificationCollection): bool
    {
        return $notificationCollection->contains('read', false);
    }

    /**
     * Admin function
     * Sends a notification to all users
     * 
     * @param SendNotificationRequest $request
     * @return JsonResponse
     */
    public function sendNotificationToAll(SendNotificationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        foreach (User::lazy() as $user) {
            NotificationHandler::createFromAdminDashboard($user->id, $validated['title'], $validated['text'], $validated['link'] ?? null, $validated['link_text'] ?? null);
        }
        return ResponseWrapper::successResponse(__('messages.notification.sent'));
    }

    /**
     * Sets the variable link_active to false, so the front-end knows not to allow further
     * interaction with the links. Useful for one-time clicks such as accepting friend requests
     *
     * @param Notification $notification
     * @return JsonResponse
     */
    public function disableAction(Notification $notification): JsonResponse
    {
        $notification->link_active = false;
        $notification->save();
        $resource = $this->getSortedNotificationsResource();
        return new JsonResponse(['data' => $resource]);
    }
}
