<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\WhatsAppController;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RateList;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class DriverAssignmentController extends Controller
{

    protected $auth, $messaging;
    static $role_module_id = 14;
    static $ignores = ['vehicle_dropdown' => true, 'assignVehicle' => true, 'incompleteRides' => true, 'incompleteRidesUpdate' => true, 'completedRides' => true, 'driverUpdate' => true, 'cancelledRides' => true,'getAvailableVehiclesAjax'=>true];

    public function __construct(Messaging $messaging)
    {

        $this->middleware('RolePermissions');
        // $this->auth = Firebase::auth();
        $this->messaging = $messaging;
    }
    public function index(Request $request)
    {

        // $unassignedvehivcles=OrderDetail::agentApprovedOrders();
        // dd($unassignedvehivcles);
        // $unassignedvehivcles=OrderDetail::vehivcle_details();
        // dd($unassignedvehivcles['unassigned_vehicles'], $unassignedvehivcles['assigned_vehicles']);

        $breadcrumbs = [
            [
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.index"),
                'active' => true,
            ],
        ];

        $user = auth()->user();
        $company = auth()->user()->active_company();

        $query = $request->input('query');
        $customerQuery = $request->input('customer');
        $dateQuery = $request->input('date');

        $orders = OrderDetail::whereHas('order', function ($queryBuilder) {
            $queryBuilder->where('overall_status', 'approved');

        })
            ->where('status', 'approved')
            ->when($company, function ($queryBuilder) use ($company) {
                $queryBuilder->whereHas('order', function ($subQuery) use ($company) {
                    $subQuery->where('company_id', $company);
                });
            })
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('order.partner_business', function ($subQuery) use ($query) {
                    $subQuery->where('name', 'ILIKE', '%' . $query . '%');
                });
            })
            ->when($customerQuery, function ($queryBuilder) use ($customerQuery) {
                $queryBuilder->whereHas('order.partner_customer', function ($subQuery) use ($customerQuery) {
                    $subQuery->where('name', 'ILIKE', '%' . $customerQuery . '%');
                });
            })
            ->when($dateQuery, function ($queryBuilder) use ($dateQuery) {
                $queryBuilder->whereDate('date', Carbon::parse($dateQuery));
            })
            ->paginate();
        //added
        foreach ($orders as $order) {
            // $order->availableVehicles = Vehicle::getAvailableVehicles($order);
            $vehicles = Vehicle::getAvailableVehicles($order);
            $order->modelVehicles = $vehicles['model_vehicles'];
            $order->classVehicles = $vehicles['class_vehicles'];
            $order->isModelAvailable = $vehicles['is_model_available'];
        }

        return view('driver-assignment.index', compact('orders', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }
//     public function index(Request $request)
// {
//     $breadcrumbs = [
//         [
//             'name' => "Driver Assignment",
//             'link' => route("driver_assignments.index"),
//             'active' => true,
//         ]
//     ];

//     $query = $request->input('query');

//     $orders = OrderDetail::whereHas('order', function($queryBuilder) {
//         $queryBuilder->where('overall_status', 'approved');
//     })
//     ->where('status', 'pending')
//     ->when($query, function ($queryBuilder) use ($query) {
//         $queryBuilder->where(function ($subQuery) use ($query) {
//             // Try to parse the query as a date
//             $date = null;
//             try {
//                 $date = Carbon::parse($query);
//             } catch (\Exception $e) {
//                 // Not a date, do nothing
//             }

//             if ($date) {
//                 // If it's a date, search by date
//                 $subQuery->whereDate('date', $date);
//             } else {
//                 // Otherwise, search by agent name or customer name
//                 $subQuery->whereHas('order.partner_business', function ($businessQuery) use ($query) {
//                     $businessQuery->where('name', 'like', '%' . $query . '%');
//                 })
//                 ->orWhereHas('order.partner_customer', function ($customerQuery) use ($query) {
//                     $customerQuery->where('name', 'like', '%' . $query . '%');
//                 });
//             }
//         });
//     })
//     ->paginate();

//     return view('driver-assignment.index', compact('orders', 'breadcrumbs'))
//         ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
// }
    // ---------------------------------------------------------------------------------
    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    // public function create()
    // {
    //     $breadcrumbs = [
    //         [
    //             'name'=>"Driver Assignment",
    //             'link'=>route("driver_assignments.index"),
    //             'active'=>false,
    //         ],
    //         [
    //             'name'=>"Create",
    //             'link'=>route("driver_assignments.create"),
    //             'active'=>true,
    //         ]
    //     ];
    //     //$order = new Order();
    //     $latestOrder = Order::latest()->first();
    //     $nextNumber = $latestOrder ? intval(substr($latestOrder->order_no, 6)) + 1 : 1;
    //     $nextOrderNo = 'ORD-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

    // // Create a new order instance
    //     $order = new Order();
    //     $order->order_no = $nextOrderNo;
    // // Other attributes...
    //     // $business_partner = Partner::where('partner_type','business')->get();
    //     // $customer_partner = Partner::where('partner_type','customer')->get();
    //     return view('driver-assignment.create', compact('order','breadcrumbs','nextOrderNo'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request $request
    //  * *
    //  */
    // public function store(Request $request)
    // {
    //     $payload = [];
    //     // Validate the request data
    //     $order_validator = Validator::make($request->all(), [
    //     'order_no' => ['required','unique:orders,order_no'],
    //     'customer_partner_id' => ['required'],
    //     'business_partner_id' => ['required'],
    //     'overall_adult'=>['required'],
    //     'overall_child'=>['required'],
    //     'overall_bags'=>['required'],
    //     'overall_status'=>['required'],
    //     'booking_amount'=>['nullable'],
    //     'final_amount'=>['nullable'],
    //     // order_details validation:

    //     //'order_id' => ['required'],
    //     'rate_list_id.*' => ['required'],
    //     'rate.*'=>['required'],
    //     'status.*'=>['required'],
    //     'adult.*'=>['required'],
    //     'child.*'=>['required'],
    //     'bags.*'=>['required'],
    //     'date.*'=>['required'],
    //     'pickup_time.*'=>['required'],
    //     // 'checkout_time.*'=>['nullable'],
    //     'is_ac.*'=>['required'],
    //     ]);

    //     if ($order_validator->fails()) {
    //        dd($order_validator->errors());
    //         return back()->with('errors', $order_validator->errors());
    //     }
    //     // Update lead attributes with validated data
    //     $order_data = $order_validator->validated();
    //     $order_data['created_by'] = auth()->user()->id;
    //     // $order_data['status'] = 'pending';
    //     $order_data['reason'] = null;
    //     $payload['order_data'] = $order_data;
    //     // dd($payload);
    //     Order::store_order($payload);
    //     //$order_data = Order::create($order_data);

    //     return redirect()->route('driver_assignments.index')->with('success', 'Order created successfully.');
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int $id
    //  * *
    //  */
    // public function show($id)
    // {
    //     $breadcrumbs = [
    //         [
    //             'name'=>"Driver Assignment",
    //             'link'=>route("driver_assignments.index"),
    //             'active'=>false,
    //         ],
    //         [
    //             'name'=>"Show",
    //             'link'=>route("driver_assignments.show",$id),
    //             'active'=>true,
    //         ]
    //     ];
    //     $order = Order::find($id);

    //     return view('driver-assignment.show', compact('order','breadcrumbs'));
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int $id
    //  * *
    //  */
    // public function edit($id)
    // {
    //     $breadcrumbs = [
    //         [
    //             'name'=>"Driver Assignment",
    //             'link'=>route("driver_assignments.index"),
    //             'active'=>false,
    //         ],
    //         [
    //             'name'=>"Edit",
    //             'link'=>route("driver_assignments.edit",$id),
    //             'active'=>true,
    //         ]
    //     ];
    //     $order = Order::find($id);
    //     // dd($order);
    //     // $business_partner = Partner::where('partner_type','business')->get();
    //     //$customer_partner = Partner::where('partner_type','customer')->get();

    //     return view('driver-assignment.edit', compact('order','breadcrumbs'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request $request
    //  * @param  Order $order
    //  * *
    //  */
    // public function update(Request $request,$order)
    // {
    //     $payload = [];
    //     $extra_fields = true;
    //     $order = Order::find($order);
    //     // Validate the request data
    //     $order_validator = Validator::make($request->all(), [
    //     'order_no' => ['required'],
    //     'customer_partner_id' => ['required'],
    //     'business_partner_id' => ['required'],
    //     'overall_adult'=>['required'],
    //     'overall_child'=>['required'],
    //     'overall_bags'=>['required'],
    //     'overall_status'=>['nullable'],
    //     'booking_amount'=>['nullable'],
    //     'final_amount'=>['nullable'],
    //     // order_details validation:
    //     'rate_list_id.*' => ['required'],
    //     'rate.*'=>['required'],
    //     'status.*'=>['required'],
    //     'adult.*'=>['required'],
    //     'child.*'=>['required'],
    //     'bags.*'=>['required'],
    //     'date.*'=>['required'],
    //     'pickup_time.*'=>['required'],
    //     // 'checkout_time.*'=>['nullable'],
    //     'is_ac.*'=>['required'],
    //     'vehicle_id.*'=>['required'],
    //     'driver_id.*'=>['required'],
    //     ]);
    //     if ($order_validator->fails()) {

    //         return back()->with('errors', $order_validator->errors());
    //     }
    //     // Update lead attributes with validated data
    //     $order_data = $order_validator->validated();
    //     $order_data['updated_by'] = auth()->user()->id;
    //     $order_data['status'] = 'pending';
    //     $order_data['reason'] = null;
    //     $payload['order_data']= $order_data;
    //     $payload['extra_fields']= $extra_fields;
    //     $payload['order'] = $order;

    //     // dd($payload);
    //     Order::update_order($payload);

    //     // $order->update($order_data);

    //     return redirect()->route('driver_assignments.index')
    //         ->with('success', 'Order updated successfully');
    // }

    // /**
    //  * @param int $id
    //  * @return \Illuminate\Http\RedirectResponse
    //  * @throws \Exception
    //  */
    public function destroy($id)
    {
        // dd($id);

        $order = OrderDetail::find($id);
        $mainorder = $order->order;
        $updatedamount = $mainorder->booking_amount - $order->rate;
        $mainorder->booking_amount = $updatedamount;
        $mainorder->save();
        // dd($mainorder);
        $order->delete();

        return redirect()->route('driver_assignments.index')
            ->with('success', 'OrderLine deleted successfully');
    }
    // ------------------------------------end---------------------------------------------
    // public function deleteOrder($id){

    //     $order = OrderDetail::find($id)->delete();

    //     return redirect()->route('driver-assignment.index')
    //         ->with('success', 'OrderLine deleted successfully');

    // }
    public function assignVehicle(Request $request)
    {

        // dd($request);
        $order = OrderDetail::find($request->row_id);
        if ($order) {
            $order->vehicle_id = $request->vehicle_id;
            $order->driver_id = $request->driver_id ?? null;
            $order->status = 'incomplete';
            $order->save();

            $driver = User::where('partner_id', $request->driver_id)->first();

            if ($driver && $driver->firebase_token) {
                // dd($driver);
                // Send notification to the driver
                // $this->sendNotificationToDriver($driver->firebase_token, $order);
            } else {
                Log::error('Firebase token is empty for the driver', ['driver_id' => $order->driver_id]);
            }
            // $order->order->order_no;

            $orderData = OrderDetail::with([
                'order.partner_customer',
            ])->find($request->row_id)->toArray();
            // $orderData = $order;
            // $orderData=$order->order->order_no;
            // dd($orderData);
            // $this->sendWhatsAppNotification($orderData, $order, $request);

            return redirect()->back()->with('success', 'vehicle assigned successfully.');

        }
    }

    private function sendWhatsAppNotification($orderData, $order, Request $request)
    {
        // if ($orderData['overall_status'] !== 'draft') {
        // Get relevant details for notification
        $status = $orderData['status'] ?? 'incomplete';
        $businessPartnerId = $order->order->business_partner_id;
        $customer_partner_id = $order->order->customer_partner_id;
        $driver_id = $request->driver_id;
        $admin_partner_id = 95;

        // Loop through the pickup times to schedule notifications
        // foreach ($request->pickup_time as $key => $pickup_time) {
        $pickupDateTimeString = $order->date . ' ' . trim($order->pickup_time);
        $pickupDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $pickupDateTimeString);
        $scheduleTime = $pickupDateTime->subHours(24);

        // Schedule WhatsApp notification for customer, business partner, driver, and admin
        $whatsAppController = new WhatsAppController();
        $whatsAppController->sendNotification(
            $orderData,
            $status,
            $businessPartnerId,
            $customer_partner_id,
            $driver_id,
            $admin_partner_id,
            $scheduleTime
        );
        // }
        // }
    }

    public function sendNotificationToDriver($firebaseToken, $order)
    {
        $notificationTitle = 'Vehicle Assigned';
        $notificationBody = "A vehicle has been assigned to you for order #{$order->id}.";

        $notification = [
            "title" => $notificationTitle,
            "body" => $notificationBody,
        ];

        $data = [
            "order_id" => $order->id,
            "vehicle_id" => $order->vehicle_id,
        ];

        try {
            $response = $this->send([$firebaseToken], $notification, $data);
            // dd($response);
            Log::info('Notification sent to driver', ['response' => $response]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification to driver: ' . $e->getMessage());
        }
    }

    public function send($firebase_token, $notification, $data)
    {
        $notification = Notification::create($notification['title'], $notification['body']);
        $message = CloudMessage::withTarget('token', $firebase_token[0])
            ->withNotification($notification)
            ->withData($data);

        $sendReport = $this->messaging->send($message);
        return $sendReport;
    }

    public function incompleteRides(Request $request)
    {
        $company_id = auth()->user()->active_company();
    //  dd($company_id);

        //dd('incomplete rides');
        $breadcrumbs = [
            [
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.incomplete_rides"),
                'active' => true,
            ],
        ];
        $query = $request->input('query');
        $customerQuery = $request->input('customer');
        $dateQuery = $request->input('date');
        // $orders = Order::where('overall_status','approved')->paginate();
        $orders = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', $company_id)->where('status', 'incomplete')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('order.partner_business', function ($subQuery) use ($query) {
                    $subQuery->where('name', 'ILIKE', '%' . $query . '%');
                });
            })
            ->when($customerQuery, function ($queryBuilder) use ($customerQuery) {
                $queryBuilder->whereHas('order.partner_customer', function ($subQuery) use ($customerQuery) {
                    $subQuery->where('name', 'ILIKE', '%' . $customerQuery . '%');
                });
            })
            ->when($dateQuery, function ($queryBuilder) use ($dateQuery) {
                $queryBuilder->whereDate('date', Carbon::parse($dateQuery));
            })
            ->paginate();
        // foreach ($orders as $order) {
        //     $order->availableDrivers = Driver::getAvailableDrivers($order);
        // }

        return view('driver-assignment.incomplete_rides', compact('orders', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }
    // public function driverUpdate(Request $request){
    //     // dd($request);
    //     $order = OrderDetail::find($request->row_id);
    //     if ($order) {
    //         $order->driver_id = $request->driver_id;
    //         $order->save();

    //         return response()->json(['success' => true, 'message' => 'Driver Assigned Successfully!']);
    //     }

    //     return response()->json(['success' => false, 'message' => 'Driver Not Found.']);

    // }
    public function incompleteRidesUpdate(Request $request)
    {

        // dd($request);
        $order = OrderDetail::find($request->row_id);
        if ($order) {
            // $order->status = 'completed';
            $order->driver_id = $request->driver_id;
            $order->save();

            return redirect()->back()->with('success', 'driver updated successfully.');

        }
    }

    public function completedRides(Request $request)
    {
        $company_id = auth()->user()->active_company();


        //dd('incomplete rides');
        $breadcrumbs = [
            [
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.completed_rides"),
                'active' => true,
            ],
        ];
        $query = $request->input('query');
        $customerQuery = $request->input('customer');
        $dateQuery = $request->input('date');

        $orders = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', $company_id)->where('status', 'completed')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('order.partner_business', function ($subQuery) use ($query) {
                    $subQuery->where('name', 'ILIKE', '%' . $query . '%');
                });
            })
            ->when($customerQuery, function ($queryBuilder) use ($customerQuery) {
                $queryBuilder->whereHas('order.partner_customer', function ($subQuery) use ($customerQuery) {
                    $subQuery->where('name', 'ILIKE', '%' . $customerQuery . '%');
                });
            })
            ->when($dateQuery, function ($queryBuilder) use ($dateQuery) {
                $queryBuilder->whereDate('date', Carbon::parse($dateQuery));
            })
            ->paginate();

        return view('driver-assignment.completed_rides', compact('orders', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }
    public function cancelledRides(Request $request)
    {
        $company_id = auth()->user()->active_company();

        //dd('incomplete rides');
        $breadcrumbs = [
            [
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.cancelled_rides"),
                'active' => true,
            ],
        ];
        $query = $request->input('query');
        $customerQuery = $request->input('customer');
        $dateQuery = $request->input('date');

        $orders = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', $company_id)->where('status', 'cancelled')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('order.partner_business', function ($subQuery) use ($query) {
                    $subQuery->where('name', 'ILIKE', '%' . $query . '%');
                });
            })
            ->when($customerQuery, function ($queryBuilder) use ($customerQuery) {
                $queryBuilder->whereHas('order.partner_customer', function ($subQuery) use ($customerQuery) {
                    $subQuery->where('name', 'ILIKE', '%' . $customerQuery . '%');
                });
            })
            ->when($dateQuery, function ($queryBuilder) use ($dateQuery) {
                $queryBuilder->whereDate('date', Carbon::parse($dateQuery));
            })
            ->paginate();

        return view('driver-assignment.cancel_rides', compact('orders', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }

    public function getAvailableVehiclesAjax(Request $request)
    {

        // Get the start and end times for the order
        $date = $request->input('date');
        $time = $request->input('time');
        $rate_list = $request->input('rate_list');
        $vehicle_model_id = $request->input('vehicle_model_id');
        $est_time = $request->input('est_time');
        // dd($request);
       if($rate_list){
            $estimatedMins = RateList::where('id',$rate_list)->first();
            // dd($estimatedMins);
            $estimatedMins = (int) $estimatedMins->estimated_time;
       }
       else{
            $estimatedMins = (int) $est_time;
       }

        $start = Carbon::parse($time);

        $end = $start->copy()->addMinutes($estimatedMins);
        // Find busy vehicles
        // testing---------------
        $busy_vehicles = OrderDetail::join('rate_lists', 'order_lines.rate_list_id', '=', 'rate_lists.id')
            ->where('date', $date)
            ->where('status', 'incomplete')
            ->whereNotNull('vehicle_id')
            ->where(function ($query) use ($start, $end) {
                $query->whereTime('pickup_time', '<=', $end->toTimeString())
                ->whereRaw("
                pickup_time + INTERVAL '1 second' * (CAST(rate_lists.estimated_time AS integer) * 60) >= ?
            ", [$start->toTimeString()]);
            })
            ->pluck('vehicle_id')
            ->toArray();
        // Fetch vehicles by model
        $model_vehicles = Vehicle::with('vehicleModel')->whereNotIn('id', $busy_vehicles)
            ->where('is_status', 'active')->where('vehicle_model_id', $vehicle_model_id)
            ->get();
        $formattedVehicles = $model_vehicles->map(function ($vehicle) use($estimatedMins) {
            return [
                'id' => $vehicle->id, // Vehicle ID
                'model_name' => $vehicle->vehicleModel->name ?? 'Unknown', // Vehicle Model Name (check for null)
                'vehicle_no'=> $vehicle->vehicle_no,
                'driver_id'=> $vehicle->driver_id,
                'estimated_min'=> $estimatedMins,
            ];
        });

        return response()->json(['model_vehicles' => $formattedVehicles,
    ]);


    }

}
