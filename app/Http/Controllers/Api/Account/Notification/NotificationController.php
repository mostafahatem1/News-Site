<?php

namespace App\Http\Controllers\Api\Account\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationrCollection;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    function getNotifications(){

        $notifications = auth()->user()->notifications;
        $unreadNotifications = auth()->user()->unreadNotifications;

        return api_response('Notifications retrieved successfully', 200, [
            'notifications' => new NotificationrCollection($notifications),
            'unreadNotifications' => new NotificationrCollection($unreadNotifications),
        ]);
    }

    function readNotification($id)
    {
      
        $notification = auth('sanctum')->user()->notifications()->find($id);

        if (!$notification) {
            return api_response('Notification not found', 404);
        }

        $notification->markAsRead();

        return api_response('Notification marked as read', 200);
    }
}
