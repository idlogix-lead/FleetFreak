<?php

namespace App\Jobs;

use App\Http\Controllers\Api\WhatsAppController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class SendScheduledNotification implements ShouldQueue
{
    // 
    

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer_partner_id;
    protected $business_partner_id;
    protected $driver_id;
    protected $orderData;
    protected $status;

    public function __construct($customer_partner_id, $business_partner_id = null, $driver_id = null, $orderData, $status)
    {
        $this->customer_partner_id = $customer_partner_id;
        $this->business_partner_id = $business_partner_id;
        $this->driver_id = $driver_id;
        $this->orderData = $orderData;
        $this->status = $status;
    }

    public function handle()
    {
        $whatsAppController = new WhatsAppController();
        $whatsAppController->sendNotification(
            $this->business_partner_id,
            $this->customer_partner_id,
            $this->driver_id,
            $this->orderData,
            $this->status
        );
        // dd($this->customer_partner_id);
    }
}
