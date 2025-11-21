<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
// use GuzzleHttp\Client;
use App\Models\OrderDetail;
use App\Models\Vehicle;
use App\Models\Order;
use App\Models\Partner;
use App\Models\RateList;

use App\Models\User;
use App\Models\VehicleClass;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    // public function sendNotification($businessPartnerId = null, $customer_partner_id = null, $driver_id = null, $orderData, $status, $scheduleTime = null)
    // {

    //     $contacts = [];

    //     if ($businessPartnerId) {
    //         $businesspartner = Partner::find($businessPartnerId);
    //         if (!$businesspartner) {
    //             Log::error('Partner not found for ID: ' . $businessPartnerId);
    //             return false;
    //         }
    //         $contacts[] = [
    //             "prefix" => $businesspartner->prefix_whatsapp,
    //             "postfix" => $businesspartner->whatsapp_no,
    //         ];
    //     }
    //     // dd($businessPartnerId);

    //     if ($customer_partner_id) {
    //         $customerpartner = Partner::find($customer_partner_id);
    //         if (!$customerpartner) {
    //             Log::error('Partner not found for ID: ' . $customer_partner_id);
    //             return false;
    //         }
    //         $contacts[] = [
    //             "prefix" => $customerpartner->prefix_whatsapp,
    //             "postfix" => $customerpartner->whatsapp_no,
    //         ];
    //     }
    //     // dd($customer_partner_id);

    //     if ($driver_id) {
    //         $driverpartner = Partner::find($driver_id);
    //         if (!$driverpartner) {
    //             Log::error('Partner not found for ID: ' . $driver_id);
    //             return false;
    //         }
    //         $contacts[] = [
    //             "prefix" => $driverpartner->prefix_whatsapp,
    //             "postfix" => $driverpartner->whatsapp_no,
    //         ];
    //     }
    //     // dd($driver_id);

    //     if (empty($contacts)) {
    //         Log::error('No valid partners provided.');
    //         return false;
    //     }
    //     if (!isset($orderData['driverbool'])) {
    //         $rateList = RateList::with('route')->where('id', $orderData['rate_list_id'])->first();

    //         if (!$rateList || !$rateList->route) {
    //             Log::error('RateList or Route not found for rate_list_id: ' . $orderData['rate_list_id']);
    //             return false;
    //         }
    //         $fromLocation = $rateList->route->from;
    //         $toLocation = $rateList->route->to;
    //         $rateString = $rateList->price;
    //         if (isset($orderData['airline_name']) && isset($orderData['flight_num'])) {
    //             $airlineName = is_array($orderData['airline_name']) ? implode(', ', $orderData['airline_name']) : ($orderData['airline_name'] ?? 'N/A');
    //             $flightNum = is_array($orderData['flight_num']) ? implode(', ', $orderData['flight_num']) : ($orderData['flight_num'] ?? 'N/A');
    //         } else {
    //             $airlineName = 'None';
    //             $flightNum = 'None';
    //         }

    //     } else {

    //         $fromLocation = $orderData['driver_pickup_loc'];
    //         $toLocation = $orderData['driver_dropoff_loc'];
    //         $rateString = $orderData['driver_rate'] ?? 'N/A';

    //     }
    //     // dd($fromLocation);

    //     $newline = '%0A';
    //     $baseMessage = " Your order has been status $newline $newline Ride Details $newline";
    //     $statusMessage = '';
    //     switch ($status) {
    //         case 'approved':
    //             $statusMessage = 'approved by the admin';
    //             break;
    //         case 'incomplete':
    //             $statusMessage = 'approved by the admin';
    //             break;
    //         case 'completed':
    //             $statusMessage = 'completed';
    //             break;
    //         case 'pending':
    //         default:
    //             $statusMessage = "sent to admin for approval";
    //             break;
    //     }

    //     $message = str_replace('status', $statusMessage, $baseMessage);
    //     $message .= ' *Order/Ride* *No* : ' . ($orderData['order_no'] ?? 'N/A') . $newline;
    //     $message .= ' *Customer* *Name* : ' . ($orderData['name'] ?? $orderData['customer_name'] ?? 'N/A') . $newline;
    //     $message .= ' *Pick* *Up* : ' . ($fromLocation ?? 'N/A') . $newline;
    //     $message .= ' *To* : ' . ($toLocation ?? 'N/A') . $newline;
    //     if (!empty($airlineName)) {
    //         $message .= ' *Airline* *Name* : ' . $airlineName . $newline;
    //     }
    //     if (!empty($flightNum)) {
    //         $message .= ' *Flight* *No* : ' . $flightNum . $newline;
    //     }
    //     $message .= ' *Cash From Customer* : ' . $rateString . 'sar' . $newline;
    //     $message .= ' Zaroon Transport ' . $newline . $newline;
    //     $message .= " *If* *you* *have* *any* *query* *please* *contact* *this* *number* : $newline 966 59 261 2127";
    //     $requestData = [
    //         'contacts' => $contacts,
    //         'message' => $message,
    //     ];

    //     // Send the API request to your public server IP

    //     $response = Http::post('http://72.255.1.252:9000/api/zaroon', $requestData);

    //     if ($response->getStatusCode() == 200) {

    //         Log::info('WhatsApp notification sent successfully');
    //     } else {

    //         Log::error('Error sending WhatsApp notification: ' . $response->getReasonPhrase());
    //     }
    // }

    public function sendNotification( $orderData, $status, $businessPartnerId = null, $customer_partner_id = null, $driver_id = null,$admin_partner_id = null, $scheduleTime = null)
    {
        // Prepare contacts as usual
        $contacts = [];

        $receivers=[];

        if ($businessPartnerId) {
            $businesspartner = Partner::find($businessPartnerId);
            if (!$businesspartner) {
                Log::error('Partner not found for ID: ' . $businessPartnerId);
                return false;
            }
            $contacts[] = [
                "prefix" => $businesspartner->prefix_whatsapp,
                "postfix" => $businesspartner->whatsapp_no,
            ];
            $receivers[]=$businessPartnerId;
        }
        // dd($businessPartnerId);

        if ($customer_partner_id) {
            $customerpartner = Partner::find($customer_partner_id);
            if (!$customerpartner) {
                Log::error('Partner not found for ID: ' . $customer_partner_id);
                return false;
            }
            $contacts[] = [
                "prefix" => $customerpartner->prefix_whatsapp,
                "postfix" => $customerpartner->whatsapp_no,
            ];
            $receivers[]=$customer_partner_id;
        }

        if ($driver_id) {
            $driverpartner = Partner::find($driver_id);
            if (!$driverpartner) {
                Log::error('Partner not found for ID: ' . $driver_id);
                return false;
            }
            $contacts[] = [
                "prefix" => $driverpartner->prefix_whatsapp,
                "postfix" => $driverpartner->whatsapp_no,
            ];
            // dd($driverpartner);
            $receivers[]=$driver_id;
        }

        $adminPartner = Partner::where('id',$admin_partner_id)->first();
        // dd($adminPartner->prefix_whatsapp);
        if ($adminPartner) {
            $contacts[] = [
                "prefix" => $adminPartner->prefix_whatsapp,
                "postfix" => $adminPartner->whatsapp_no,
            ];
            $receivers[] = $admin_partner_id;
        }
        // dd($receivers, $contacts);

        if (empty($contacts)) {
            Log::error('No valid partners provided.');
            return false;
        }


        // dd($orderData, $status);

        // Prepare the message as usual
        $message = $this->generateMessage($orderData,$status,$driver_id ?? $driver_id=null);
        // dd($message);
        // Check if scheduling a notification or sending it immediately
        if ($scheduleTime) {
            // Save scheduled notification to the Notification table
            foreach($receivers as $receiver){
                Notification::create([
                    'receiver_partner_id' => $receiver,
                    'sender_id' => 1, // Admin user id
                    'sent_at' => $scheduleTime,
                    'sent_type' => 'scheduled',
                    'status' => 'unsent',
                    'detail' => $message,

                ]);
            }
        } else {
            // Send immediate notification to business partner and driver
            $this->sendWhatsAppMessage($contacts, $message);

            // Save notification to the Notification table
            foreach($receivers as $receiver){
                Notification::create([
                    'receiver_partner_id' => $receiver,
                    'sender_id' => 1, // Admin user id
                    'sent_at' => now(),
                    'sent_type' => 'direct',
                    'status' => 'sent',
                    'detail' => $message,
                ]);
            }
        }
    }

// Helper function to send WhatsApp message
    protected function sendWhatsAppMessage($contacts, $message)
    {
        // dd($message);
        $requestData = [
            'contacts' => $contacts,
            'message' => $message,
        ];

        $response = Http::post('http://72.255.1.252:9000/api/zaroon', $requestData);

        if ($response->getStatusCode() == 200) {
            Log::info('WhatsApp notification sent successfully');
        } else {
            Log::error('Error sending WhatsApp notification: ' . $response->getReasonPhrase());
        }
    }

// Helper function to generate the message
    public function generateMessage($orderData, $status, $driver_id = null){
        // dd($orderData['rate_list_id']);

        if (!isset($orderData['driverbool']) and isset($orderData['rate_list_id'])) {
            $rateList = Ratelist::with(['route'])

            ->where('id', $orderData['rate_list_id'])
            ->first();
            // dd($rateList);
            // dd($orderData['rate_list_id']);
            if (!$rateList || !$rateList->route) {
                Log::error('RateList or Route not found for rate_list_id: ' . $orderData['rate_list_id']);
                return false;
            }

            $fromLocation = $rateList->route->fromLoc->name;
            $toLocation = $rateList->route->toLoc->name;
            $rateString = $rateList->price;
            // dd( $fromLocation );

            if (isset($orderData['airline_name']) && isset($orderData['flight_num'])) {
                $airlineName = is_array($orderData['airline_name']) ? implode(', ', $orderData['airline_name']) : ($orderData['airline_name'] ?? 'N/A');


                $flightNum = is_array($orderData['flight_num']) ? implode(', ', $orderData['flight_num']) : ($orderData['flight_num'] ?? 'N/A');

            } else {
                $airlineName = 'None';
                $flightNum = 'None';
            }

        } else {
            // dd($orderData);
            $fromLocation = $orderData['driver_pickup_loc'];
            $toLocation = $orderData['driver_dropoff_loc'];
            $rateString = $orderData['driver_rate'] ?? 'N/A';


        }

        // dd($orderData);

        $totalpersons = VehicleClass::where('id', 1)->value('seats_allow');

        $vehicletype = VehicleClass::where('id', 1)->value('name');

        $newline = '%0A';


        $bullet = ' ● ' ;

        $linebreak = ' ======================= ';

        $tab='    ';

        $driverpartner = Partner::find($driver_id) ?? null;
        // dd($driverpartner);

        $drivername = $driverpartner->name ?? null;

        $driverphone = $driverpartner->phone_no ?? null;
        // dd($driverphone);
        $driverphoneprefix = $driverpartner->prefix_phone ?? null;
        $driverphonenumber = $driverphone . $driverphoneprefix ?? null;

        $driverwhatsapp = $driverpartner->whatsapp_no ?? null;
        $driverwhatsappprefix = $driverpartner->prefix_whatsapp ?? null;
        $driverwhatsappnumber = $driverwhatsapp . $driverwhatsappprefix ?? null;

        $vehicle = Vehicle::where('driver_id', $driver_id)->first() ?? null;
        $vehicleNumber = $vehicle ? $vehicle->vehicle_no  : 'N/A' ?? null;

        $date = is_array($orderData['date']) ? implode(' ', $orderData['date']) : $orderData['date'];
        $pickup_time = is_array($orderData['pickup_time']) ? implode(' ', $orderData['pickup_time']) : $orderData['pickup_time'];

        $pickuptime = $date . ' ' . $pickup_time;

        // dd($orderData);
        // $customer_name=Order::find($orderData['order']['id']);
        // dd($customer_name);

        // dd($pickuptime);

        // $orderDetail = OrderDetail::where('order_id', $orderData['order_id'])->get();

        // // Handle case where order detail might not be found
        // if (!$orderDetail) {
        //     Log::error('OrderDetail not found for order_id: ' . $orderData['order_id']);
        //     $pickupDateTime = 'N/A';
        // } else {
        //     $pickupDate = $orderDetail->pickup_date ?? 'N/A';
        //     $pickupTime = $orderDetail->pickup_time ?? 'N/A';

        //     // Combine date and time
        //     $pickupDateTime = ($pickupDate !== 'N/A' && $pickupTime !== 'N/A')
        //         ? $pickupDate . ' ' . $pickupTime
        //         : 'N/A';
        // }
            // $baseMessage = " Your order has been status $newline $newline Ride Details $newline";
            // $statusMessage = '';
            // switch ($status) {
            //     case 'approved':
            //         $statusMessage = 'approved by the admin';
            //         break;
            //     case 'incomplete':
            //         $statusMessage = 'approved by the admin';
            //         break;
            //     case 'completed':
            //         $statusMessage = 'completed';
            //         break;
            //     case 'pending':
            //     default:
            //         $statusMessage = "sent to admin for approval";
            //         break;
            // }


            // $message = str_replace('status', $statusMessage, $baseMessage);
            // $message = ' *Order #* ' . ($orderData['order_no'] ?? 'N/A') . $newline . $newline;
            // // $message .= ' Pick up time : ' . $pickupDateTime . $newline;
            // $message .= ' Passenger Name : ' . ($orderData['name'] ?? $orderData['customer_name'] ?? 'N/A') . $newline;
            // $message .= ' Total Persons : ' . $totalpersons . $newline . $newline;
            // $message .= ' Passenger WhatsApp Number : ' . ($orderData['prefix_whatsapp'] . $orderData['whatsapp_no'] ?? 'N/A') . $newline . $newline;
            // $message .= ' Pickup from : ' . ($fromLocation ?? 'N/A') . $newline;
            // $message .= ' Drop off to : ' . ($toLocation ?? 'N/A') . $newline . $newline;
            // if (!empty($airlineName)) {
            //     $message .= ' Air Line Name: ' . $airlineName . $newline;
            // }
            // if (!empty($flightNum)) {
            //     $message .= ' Flight No : ' . $flightNum . $newline . $newline ;
            // }
            // $message .= ' *Vehicle* *Type* : ' . $vehicletype . $newline;
            // $message .= ' Fare : '. 'SR.' . $rateString  . $newline . $newline;
            // $message .= ' Zaroon Transport' . $newline ;
            // $message .= " *If* *you* *have* *any* *query* *please* *contact* *this* *number* : $newline 966 59 261 2127";
            // dd($orderData);

            if ($status == 'incomplete') {
                // Template after driver assign
                $message = ' *Zaroon* *Transport* *Company*' . $newline ;
                $message .= ' *Order #* ' . ($orderData['order']['order_no'] ?? 'N/A') . $newline . $newline;
                $message .= ' *Passenger* *Detail* :' . $newline ;
                $message .= $bullet . ' Pick up time : '. $pickuptime . $newline;
                $message .= $bullet . ' Name : ' . ($orderData['order']['partner_customer']['name']  ?? 'N/A') . $newline;
                // dd($message);
                $message .= $bullet . ' Total Persons : ' . $totalpersons . $newline ;
                // $message .= ' Passenger WhatsApp Number : ' . ($orderData['order']['partner_customer']['prefix_whatsapp'] . $orderData['order']['partner_customer']['whatsapp_no'] ?? 'N/A') . $newline . $newline;
                $message .= $bullet . ' Pickup from : ' . ($fromLocation ?? 'N/A') . $newline;
                $message .= $bullet . ' Drop off to : ' . ($toLocation ?? 'N/A') . $newline ;
                if (!empty($airlineName)) {
                    $message .= $bullet . ' Air Line Name: ' . $airlineName . $newline;
                }
                if (!empty($flightNum)) {
                    $message .= $bullet . ' Flight No : ' . $flightNum . $newline . $newline;
                }
                $message .= ' *Car* *and* *Driver* *details* :' . $newline;
                $message .= $bullet . ' *Driver* *name* :' . $drivername . $newline;
                $message .= $bullet . ' *Driver* *Local* *No* :' . $driverphonenumber . $newline;
                $message .= $bullet . ' *Driver* *WhatsApp* *No* :' . $driverwhatsappnumber . $newline . $newline;
                $message .= ' *Vehicle* *Type* : ' . $vehicletype . $newline ;
                $message .= ' *Vehicle* *Number* : ' . $vehicleNumber . $newline . $newline;
                // $message .= ' Zaroon Transport' . $newline;
                $message .= " *Note:*  Please contact the driver for further assistance or reach out to our customer support. $newline $bullet  Makkah Office 1: 966 59 594 9715 $newline $bullet Makkah Office 2: 966 59 972 7715 $newline $bullet  Madinah Office: 966 53 437 7445";

            }
            elseif($status == 'register'){
            $message = ' *Zaroon* *Transport* *Company*' . $newline;
            $message .= ' *Registration* *Detail* :' . $newline;
            }
             else {
                $message = ' *Zaroon* *Transport* *Company*' . $newline ;
                $message .= ' Order # ' . ($orderData['order_no'] ?? 'N/A') . $newline . $newline;
                $message .= ' *Passenger* *Detail* ' . $newline;
                $message .= $bullet . ' Name : ' . ($orderData['name'] ?? $orderData['customer_name'] ?? 'N/A') . $newline;
                $message .= $bullet . ' Pick up time : '. $pickuptime . $newline;
                $message .= $bullet . ' Total Persons : ' . $totalpersons . $newline ;
                $message .= $bullet . ' WhatsApp No : ' . ($orderData['prefix_whatsapp'] . $orderData['whatsapp_no'] ?? 'N/A') . $newline;
                $message .= $bullet . ' Pickup from : ' . ($fromLocation ?? 'N/A') . $newline;
                $message .= $bullet . ' Drop off to : ' . ($toLocation ?? 'N/A') . $newline;
                if (!empty($airlineName)) {
                    $message .= $bullet . ' Air Line Name: ' . $airlineName . $newline;
                }
                if (!empty($flightNum)) {
                    $message .= $bullet . ' Flight No : ' . $flightNum . $newline . $newline . $newline;
                }
                $message .= ' *Vehicle* *Type* : ' . $vehicletype . $newline ;
                $message .= ' Fare : ' . 'SR.' . $rateString . $newline . $newline;
                $message .= " *Note:*  Please contact the driver for further assistance or reach out to our customer support. $newline $bullet  Makkah Office 1: 966 59 594 9715 $newline $bullet Makkah Office 2: 966 59 972 7715 $newline $bullet  Madinah Office: 966 53 437 7445";
            }

        // dd( $airlineName, $flightNum );

        return $message;
    }
}



// $notificationData = [
//     'receiver_id' => $businessPartnerId ?? $customer_partner_id ?? $driver_id,
//     // 'sender_id' => $businessPartnerId,
//     // 'source_id' => $orderData->order->id,
//     'detail' => $message,
//     'status' => 'sent',
//     'sent_at' => now(),
//     'sent_type' => 'direct',
//     // 'contacts' => $contacts,
// ];


//         if ($scheduleTime) {
//             // If it's a scheduled notification, set it as pending
//             $notificationData['status'] = 'pending';
//             $notificationData['sent_type'] = 'scheduled';
//             $notificationData['sent_at'] = null;

//             // Schedule the job to send this notification 24 hours before the ride
//             SendScheduledNotification::dispatch($notificationData, ['contacts' => $contacts])->delay($scheduleTime);
//         } else {
//             // If it's an immediate notification, send it now
//             $response = Http::post('http://72.255.1.252:9000/api/zaroon',$requestData);

//             if ($response->successful()) {
//                 Log::info('WhatsApp notification sent successfully');
//             } else {
//                 Log::error('Error sending WhatsApp notification: ' . $response->body());
//             }
//         }
//         // unset($notificationData['contacts']);

//         // // Save the notification to the database
//         // Notification::create($notificationData);
//     }
// }

// $requestData = [
//     'contacts' => $contacts,
//     'message' => $message,
// ];
