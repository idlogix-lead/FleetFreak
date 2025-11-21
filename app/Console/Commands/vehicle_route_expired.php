<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;

class vehicle_route_expired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:vehicle_route_expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification that your vehicle route has been expired ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
      
        $route_expiry_details =  Vehicle::whereDate('route_permits_expiry_date','<=',Carbon::today())->get();
            if($route_expiry_details){
                foreach($route_expiry_details as $route_expiry_detail){
                    $title = "vehicle".$route_expiry_detail->vehicle_no. "route expired";
                    $description = "your vehicle route has been expired";
                    // create notifications
                    $notifications = [];
                    if ($route_expiry_detail->driver_id) {
                        $notifications[] = [
                            'receiver_partner_id' => $route_expiry_detail->driver_id,
                            'sender_id' => auth()->user()->id,
                            'calendar' => 1,
                            // 'sms' => 1,
                            // 'email' => 1,
                            // 'whatsapp' => 1,
                            // 'fcm_mobile_push' => 1,
                            // 'fcm_web_push' => 1,
                        ];
                    }

                    // if($data['business_partner_id']){
                    //     $notifications[] = [
                    //         'receiver_id' => intval($data['business_partner_id']),
                    //         'sender_id' => $auth_user_id,
                    //         'calendar' => 1,
                    //         // 'sms' => 1,
                    //         // 'email' => 1,
                    //         // 'whatsapp' => 1,
                    //         // 'fcm_mobile_push' => 1,
                    //         // 'fcm_web_push' => 1,
                    //     ];
                    // }
                    $admin_user = UserCompany::where('company_id', $route_expiry_detail->company_id)->whereHas('user', function ($user) {
                        return $user->where('is_company_admin', 1);
                    })->first();
                    if ($admin_user) {
                        $notifications[] = [
                            'receiver_id' => $admin_user->user_id,
                            'sender_id' => auth()->user()->id,
                            'calendar' => 1,
                            // 'sms' => 1,
                            // 'email' => 1,
                            // 'whatsapp' => 1,
                            // 'fcm_mobile_push' => 1,
                            // 'fcm_web_push' => 1,
                        ];
                    }

                    // dd($notifications);

                    // dd($order->overall_status);
                   
                        Event::createEvent(43, $route_expiry_detail->id, $route_expiry_detail->vehicle_no, 'route_expire', $title, $description, $action_details3 = null, $route_expiry_detail->company_id , $route_expiry_detail->route_permits_expiry_date, $notifications);


                }
                
            }
    }
}
