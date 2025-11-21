<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use App\Http\Controllers\Api\WhatsAppController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications scheduled for 24 hours before pickup time';
    protected $whatsAppController;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WhatsAppController $whatsAppController)
    {
        parent::__construct();
        $this->whatsAppController = $whatsAppController;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch unsent notifications where the sent_at time has passed
        $notifications = Notification::where('sent_type', 'scheduled')
            // ->where('sent_at', '<=', now()->setTimezone('Asia/Karachi'))
            ->where('sent_at', '<=', carbon::now())

            ->where('status', 'unsent')
            ->with('receiver_partner')
            ->get();
        //  dd($notifications->toArray(),carbon::now());
        // dd($notifications);

        foreach ($notifications as $notification) {
            // try {

                // $notificationTime = $notification->sent_at->setTimezone('Asia/Karachi');
                //   og::info('Processing notification ID: ' . $notification->id . ' scheduled at ' . $not  LificationTime);

                $receiver = $notification->receiver_partner;
                // dd($receiver);

                // foreach ($receivers as $receiver) {
                // Check if the receiver has a WhatsApp number
                // dd($receiver);
                if (!$receiver->whatsapp_no) {
                    Log::error('WhatsApp number missing for receiver ID: ' . $receiver->id . ' for notification ID: ' . $notification->id);
                    continue;
                }

                $contacts = [
                    [
                        'prefix' => $receiver->prefix_whatsapp,
                        'postfix' => $receiver->whatsapp_no,
                        'priority' => 10,
                    ]
                ];

                $message =  $notification->detail;

                // Debugging: Check contacts and message
                // dd($contacts, $message);

                // Send WhatsApp message
                // $this->sendWhatsAppMessage($contacts, $message);
                $this->whatsAppController->sendWhatsAppMessage($contacts, $message);

                // Update notification status

                $notification->update([
                    'status' => 'sent',
                ]);

                // dd( $notification);

                Log::info('Notification sent successfully for notification ID: ' . $notification->id);
                // }

            // } catch (\Exception $e) {
            //     Log::error('Error sending notification ID: ' . $notification->id . ' - ' . $e->getMessage());
            // }
        }

        return 0;
    }
    // Helper function to send WhatsApp message
    // protected function sendWhatsAppMessage($contacts, $message)
    // {
    //     // if(!$contacts || !$message){
    //     //     dd($contacts, $message);
    //     // }
    //     $requestData = [
    //         'contacts' => $contacts,
    //         'message' => $message,
    //     ];

    //     // dd($requestData);

    //     $response = Http::post('http://72.255.1.252:9000/api/zaroon', $requestData);

    //     if ($response->successful()) {
    //         Log::info('WhatsApp notification sent successfully');
    //     } else {
    //         Log::error('Error sending WhatsApp notification: ' . $response->getReasonPhrase());
    //     }
    // }
}
