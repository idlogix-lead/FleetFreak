<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RideStarted extends Notification
{
    use Queueable;

    public $rider;

    public function __construct($rider)
    {
        $this->rider = $rider;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->rider->name} has started the ride.",
            'rider_id' => $this->rider->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "{$this->rider->name} has started the ride.",
            'rider_id' => $this->rider->id,
        ]);
    }
}