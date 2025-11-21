<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!$notifiable->routeNotificationFor('firebase')) {
            return;
        }

        $message = $notification->toFirebase($notifiable);

        Firebase::messaging()->send($message);
    }
}
