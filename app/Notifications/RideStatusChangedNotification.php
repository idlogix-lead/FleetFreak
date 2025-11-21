<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class RideStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $ride;
    private $status;

    public function __construct($ride, $status)
    {
        $this->ride = $ride;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['firebase'];
    }

    public function toFirebase($notifiable)
    {
        $message = CloudMessage::withTarget('token', $notifiable->firebase_token)
            ->withNotification(FirebaseNotification::create('Ride Status Changed', "Your ride status is now: {$this->status}"));

        return Firebase::messaging()->send($message);
    }
}