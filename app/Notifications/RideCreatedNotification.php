<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class RideCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $ride;

    public function __construct($ride)
    {
        $this->ride = $ride;
    }

    public function via($notifiable)
    {
        return ['firebase'];
    }

    public function toFirebase($notifiable)
    {
        $message = CloudMessage::withTarget('token', $notifiable->firebase_token)
            ->withNotification(FirebaseNotification::create('New Order Created', 'An agent has created a new order.'));

        return Firebase::messaging()->send($message);
    }
}