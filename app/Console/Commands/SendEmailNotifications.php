<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Event;
use App\Mail\RegisterNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $event_details = Event::where('company_id', null)
        ->whereIn('action', ['registered'])
        ->with([
            'notifications' => function($notification){
                return $notification->where('email', 1)->whereNull('email_sent_at');
            },
            'sourceTable'
        ])
        ->get();

        foreach($event_details as $event){
            foreach($event->notifications as $notification){
                try{
                    // $model = 'App\\Models\\'.$event->sourceTable->model_name;
                    // $user = $model::where('id',$event->source_id)->first();
                    $user = User::where('id', $notification->receiver_id)->first();
                    $resp = Mail::to($user)->send(new RegisterNotification($user, $event));
                    $notification->update([
                        'email_sent_at' => date('Y-m-d H:i:s')
                    ]);
                }catch(\Exception $e){
                    echo $e->getMessage();
                    Log::error($e->getMessage(), ['notification_id' => $notification->id, 'user_id' => $notification->receiver_id]);
                }
            }
        }
    }
}
