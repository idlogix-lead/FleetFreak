<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendScheduledNotification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Partner;
use App\Models\RateList;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */

class OrderController extends Controller
{
    static $role_module_id = 45;
    public $my_companies;
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies = auth()->user()->companies->toArray();
            return $next($request);
        });

    }

    static $ignores = [
        // 'api_store' => true,
        // // 'api_index' => true,
        // 'api_update' => true,
        // 'api_show' => true,
        // 'api_edit' => true,
        'login_partner_orders' => true,
        'latestPendingOrders' => true,
        'getStatusCount' => true,
        'saveDraft' => true,
        'updateOrderStatus' => true,
        'api_today_total_rides'=>true,
        'api_today_active_rides'=>true,
        'api_today_pending_rides'=>true,
        'api_monthly_pending_rides'=>true
        // 'generate_next_order_no' => true,
        // 'api_store_draft' => true,
    ];
    public function api_index()
    {
        $breadcrumbs = [
            [
                'name' => "Order",
                'link' => route("orders.index"),
                'active' => true,
            ],
        ];
        $companyId = auth()->user()->active_company() ?? null;
        // $orders = Order::all();
        //     $orders = Order::where('company_id', $companyId)
        //     ->checkGlobal(9)->where('overall_status', '!=', 'pending')->where('overall_status', '!=', 'approved')->where('created_by', auth()->user()->id)->when($query, function ($q) use ($query) {
        //     $q->where(function ($q) use ($query) {
        //         $q->where('order_no', 'ILIKE', '%' . $query . '%')
        //             ->orWhere('overall_status', 'ILIKE', '%' . $query . '%')
        //             ->orWhereHas('partner_customer', function ($q3) use ($query) {
        //                 $q3->where('name', 'ILIKE', '%' . $query . '%');
        //             });

        //     });
        // })->orderBy('created_at', 'desc')->paginate($perPage);

        $orders = Order::with('orderDetails','partner_customer','business_partner')->where('company_id', $companyId)->checkGlobal(45)->where('overall_status', '!=', 'pending')->where('overall_status', '!=', 'approved')->where('created_by', auth()->user()->id)->orderBy('created_at', 'desc')->get();


        return response()->json([
            // 'breadcrumbs' => $breadcrumbs,
            'orders' => $orders,
        ], 200);
    }
    public function api_today_total_rides()
    {
        $today_date = Carbon::now()->toDateString(); // Get today's date in 'Y-m-d' format
        $today_total_rides = OrderDetail::whereDate('date', $today_date)
            ->count();

        return response()->json(['today_total_rides' => $today_total_rides]);
    }

    public function api_today_active_rides(){
        $today_active_rides = OrderDetail::where('status','incomplete')
        ->count();

        return response()->json(['today_active_rides' => $today_active_rides]);
    }
    public function api_today_pending_rides()
    {
        $today_date = Carbon::now()->toDateString(); // Get today's date in 'Y-m-d' format
        $today_pending_rides = OrderDetail::where('status','pending')->whereDate('date', $today_date)
            ->count();

        return response()->json(['today_pending_rides' => $today_pending_rides]);
    }
     public function api_monthly_pending_rides()
    {
       $today_date = Carbon::now()->toDateString(); // Get today's date in 'Y-m-d' format
        $date_30_days_ago = Carbon::now()->addDays(30)->toDateString(); // Subtract 30 days

        $monthly_pending_rides = OrderDetail::where('status', 'pending')
            ->whereBetween('date', [$date_30_days_ago, $today_date]) // Filter between the range
            ->count();

        return response()->json(['monthly_pending_rides' => $monthly_pending_rides]);

        // $today_date = Carbon::now()->toDateString(); // Get today's date in 'Y-m-d' format
        // $future_date = Carbon::now()->addDays(30)->toDateString(); // Add 30 days

        // $monthly_pending_rides = OrderDetail::where('status', 'pending')
        //     ->whereBetween('date', [$today_date, $future_date]) // Filter between the range
        //     ->count();

        // return response()->json(['monthly_pending_rides' => $monthly_pending_rides]);

    }


    public function login_partner_orders()
    {
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Invalid user'], 401);
        }

        // Get the driver's partner ID
        $agentPartnerId = $user->partner_id;

        // Fetch all pending rides assigned to this driver
        $Rides = Order::where('business_partner_id', $agentPartnerId)
        // ->where('status', 'incomplete')
        // ->with('order.partner_customer', 'rate_list.route')
            ->with('order_details.rate_list.route', 'partner_customer')
            ->with('order_details.order.partner_customer', 'order_details.driver', 'order_details.vehicle')
            ->get();


        return response()->json([
            'rides' => $Rides,
        ]);
    }


    public function getStatusCount()
    {
        $user = Auth::user();

        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Invalid user'], 401);
        }

        // Get the driver's partner ID
        $agentPartnerId = $user->partner_id;

        // Fetch the count of orders grouped by overall status for the authenticated partner
        $orderStatusCounts = Order::select('overall_status', DB::raw('count(*) as total'))
            ->where('business_partner_id', $agentPartnerId)
            ->groupBy('overall_status')
            ->get();

        // Fetch the count of order details grouped by status for the authenticated partner
        $orderDetailStatusCounts = OrderDetail::select('status', DB::raw('count(*) as total'))
            ->whereHas('order', function ($query) use ($agentPartnerId) {
                $query->where('business_partner_id', $agentPartnerId);
            })
            ->groupBy('status')
            ->get();
        $response = [
            'order_status_counts' => $orderStatusCounts,
            'order_detail_status_counts' => $orderDetailStatusCounts,
        ];

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    // public function api_create()
    // {
    //     $breadcrumbs = [
    //         [
    //             'name' => "Order",
    //             'link' => route("orders.index"),
    //             'active' => false,
    //         ],
    //         [
    //             'name' => "Create",
    //             'link' => route("orders.create"),
    //             'active' => true,
    //         ],
    //     ];
    //     //$order = new Order();
    //     $latestOrder = Order::latest()->first();

    //     // Extract and increment the numeric part
    //     if ($latestOrder) {
    //         // Extract the numeric part and increment it
    //         $nextNumber = intval($latestOrder->order_no) + 1;
    //     } else {
    //         $nextNumber = 1;
    //     }

    //     // Format the next order number to ensure it is 6 digits long
    //     $nextOrderNo = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

    //     // Create a new order instance
    //     $order = new Order();
    //     $order->order_no = $nextOrderNo;
    //     // Other attributes...
    //     // $business_partner = Partner::where('partner_type','business')->get();
    //     // $customer_partner = Partner::where('partner_type','customer')->get();
    //     return view('order.create', compact('order', 'breadcrumbs', 'nextOrderNo'));
    // }
    // public function order_no(){

    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */


    public function api_store(Request $request)
    {
        $payload = [];


        $order_validator_rules = [
            'overall_status' => ['required'],
        ];

        if ($request->overall_status != 'draft') {
            if ($request['trip_type'] == 'passenger_trip') {
                $order_validator_rules = array_merge($order_validator_rules, [
                'order_no' => ['required', 'unique:orders,order_no'],
                'customer_partner_id' => ['nullable'],
                'business_partner_id' => ['required'],
                'vehicle_model_id' => ['required'],
                'vehicle_class_id' => ['required'],
                'overall_adult' => ['required'],
                'overall_child' => ['required'],
                'overall_bags' => ['required'],
                'overall_status' => ['required'],
                'booking_amount' => ['nullable'],
                'final_amount' => ['nullable'],
                'company_id' => ['nullable'],
                'trip_type' => ['required'],
                // order_details validation:

                //'order_id' => ['required'],
                'rate_list_id.*' => ['required'],

                // 'from.*' => ['required'],
                // 'to.*' => ['required'],
                'rate.*' => ['required'],
                // 'status.*' => ['required'],
                // 'flight_num.*' => ['nullable'],
                // 'airline_name.*' => ['nullable'],
                'adult.*' => ['required'],
                'child.*'=>['required'],
                'bags.*' => ['required'],
                'date.*' => ['required'],
                'pickup_time.*' => ['required'],
                'company_id.*' => ['nullable'],
                // 'checkout_time.*'=>['nullable'],
                'is_ac.*' => ['required'],
                //customer validation
                'customer_business_partner_id'=>['nullable'],
                'customer_name' => ['nullable'],
                'whatsapp_no' => ['nullable'],
                'prefix_whatsapp' => ['nullable'],
                'email' => ['nullable'],
                'passport' => ['nullable'],
                'prefix_phone' => ['nullable'],
                'phone_no' => ['nullable'],
                'cnic' => ['nullable'],
                'address1' => ['nullable'],
                'country' => ['nullable'],
                'city' => ['nullable'],

                ]);
            }
            else{

                // $order_validator = Validator::make($request->all(), [
                    $order_validator_rules = array_merge($order_validator_rules, [
                    'order_no' => ['required', 'unique:orders,order_no'],
                    'customer_partner_id' => ['nullable'],
                    'business_partner_id' => ['required'],
                    'vehicle_model_id' => ['required'],
                    'vehicle_class_id' => ['required'],

                    'overall_status' => ['required'],
                    'booking_amount' => ['nullable'],
                    'final_amount' => ['nullable'],
                    'trip_type' => ['required'],
                    'direction' => ['required'],
                    'company_id' => ['nullable'],
                    // order_details validation:

                    //'order_id' => ['required'],
                    // 'rate_list_id.*' => ['nullable'],

                    'from.*' => ['required'],
                    'to.*' => ['required'],
                    'rate.*' => ['required'],
                    // 'status.*' => ['required'],
                    'weight.*' => ['required'],
                    'unit.*' => ['required'],
                    'type_of_load.*' => ['required'],
                    'date.*' => ['required'],
                    'pickup_time.*' => ['required'],
                    'company_id.*' => ['nullable'],
                    // 'checkout_time.*'=>['nullable'],
                    'is_ac.*' => ['required'],
                    // customer model required fields:
                    'customer_business_partner_id'=>['nullable'],
                    'customer_name' => ['nullable'],
                    'whatsapp_no' => ['nullable'],
                    'prefix_whatsapp' => ['nullable'],
                    'email' => ['nullable'],
                    'passport' => ['nullable'],
                    'phone_no' => ['nullable'],
                    'prefix_phone' => ['nullable'],

                    'cnic' => ['nullable'],
                    'address1' => ['nullable'],
                    'country' => ['nullable'],
                    'city' => ['nullable'],

                ]);
            }
            // dd($order_validator);
            if($request['trip_type'] == 'passenger_trip'){
                foreach ($request->input('rate_list_id') as $key => $rate_list_id) {
                    $rateList = RateList::findOrFail($rate_list_id);
                    $route = Route::findOrFail($rateList->route_id);

                    if ($route && $route->is_flight == 1) {
                        // Add flight_num validation if is_flight is 1
                        $order_validator_rules['flight_num.' . $key] = ['required'];
                        $order_validator_rules['airline_name.' . $key] = ['required'];
                    }
                }
            }
        }

        $order_validator = Validator::make($request->all(), $order_validator_rules);

        if ($request->overall_status != 'draft' && (!isset($request['pickup_time']) || count($request['pickup_time']) < 1)) {
            return response()->json(['errors' => 'At least one order line is required.']);
        }

        if ($order_validator->fails()) {

            return response()->json(['errors' => $order_validator->errors()], 400);
        }
        if($request->overall_status == 'draft'){
            $order_data = $request->all();
          }else{
              $order_data = $order_validator->validated();
          }
        // $order_data = $request->all();
        $order_data['created_by'] = auth()->user()->id;
        // dd($order_data);
        // $authenticatedUser = auth()->user();
        // $order_data['business_partner_id'] = $authenticatedUser->partner_id;

        // $order_data['customer_partner_id'] = $customer->id;

        if ($order_data['overall_status'] == 'draft') {
            $order_data['status'] = 'draft';

        } else {
            $order_data['status'] = 'pending';
        }

        $order_data['reason'] = null;
        // dd($order_data);
        // creating customer in order
        if($order_data['customer_partner_id'] == null){
            // $authenticatedUser = auth()->user();
            //  $customer_business_partner = $authenticatedUser->partner_id;
            // creating customer in order
            $customer_partner_id = Partner::create([
                'name' => $order_data['customer_name'] ?? null,
                'whatsapp_no' => $order_data['whatsapp_no'] ?? null,
                'prefix_whatsapp' => $order_data['prefix_whatsapp'] ?? null,
                'email' => $order_data['email'] ?? null,
                'cnic' => $order_data['cnic'] ?? null,
                'phone_no' => $order_data['phone_no'] ?? null,
                'prefix_phone' => $order_data['prefix_phone'] ?? null,
                'passport' => $order_data['passport'] ?? null,
                'address1' => $order_data['address1'] ?? null,
                'country' => $order_data['country'] ?? null,
                'city' => $order_data['city'] ?? null,
                'actor_id' => 6,
                'created_by' => auth()->user()->id,
                'business_partner_id' => $order_data['customer_business_partner_id'] ?? null,
                'company_id' => auth()->user()->active_company(),
            ]);
            $order_data['customer_partner_id'] = $customer_partner_id->id??null;
        }

        // $latestOrder = Order::latest()->first();
        // $nextNumber = $latestOrder ? intval($latestOrder->order_no) + 1 : 1;
        // $nextOrderNo = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // // dd($order_data);

        // $order_data['order_no'] = $nextOrderNo;
        $order_data['from_api'] = true;
        // dd($order_data );

        $payload['order_data'] = $order_data;
        // $payload['nextOrderNo'] = $nextOrderNo;

        if ($order_data['overall_status'] == 'draft') {
            // dd($payload);

            Order::store_draft($payload);
        } else {
            // dd($payload);
            Order::store_order($payload);
        }

        // $this->sendWhatsAppNotification($order_data['business_partner_id'], $order_data);



        return response()->json([
            'message' => 'Order created successfully.',
            'data' => $order_data,
        ], 200);

    }
    private function sendWhatsAppNotification($businessPartnerId, $orderData)
    {
        if ($orderData['overall_status'] !== 'draft') {
            $status = $orderData['status'] ?? 'pending';
            $whatsAppController = new WhatsAppController();
            $admin_partner_id =95;
            // dd($admin_partner_id);
            if ($orderData['trip_type'] === 'passenger_trip' || $orderData['trip_type'] === 'tour_booking') {

            foreach ($orderData['rate_list_id'] as $key => $rateListId) {
                $orderDetail = [
                    'rate_list_id' => $rateListId,
                    'pickup_time' => $orderData['pickup_time'][$key],
                    'date' => $orderData['date'][$key],
                    // 'from_loc' => $orderData['from_loc'][$key],
                    // 'to_loc' => $orderData['to_loc'][$key],
                    'rate' => $orderData['rate'][$key],
                    'airline_name' => $orderData['airline_name'][$key] ?? null,
                    'flight_num' => $orderData['flight_num'][$key] ?? null,
                ];
                $whatsAppController->sendNotification(array_merge($orderData, $orderDetail), $status,$businessPartnerId, null, null,$admin_partner_id,null);
            }
        }

            // foreach ($order_data['pickup_time'] as $key => $pickup_time) {
            //     $pickupDateTimeString = $order_data['date'][$key] . ' ' . trim($pickup_time);
            //     $pickupDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $pickupDateTimeString);
            //     $scheduleTime = $pickupDateTime->subHours(24);

            //     // Schedule notification for customer 24 hours before pickup time
            //     $whatsAppController = new WhatsAppController();
            //     $whatsAppController->sendNotification(
            //         $order_data,
            //         'approved',
            //         $businessPartnerId,
            //         $order_data['customer_partner_id'],
            //         null,
            //         $admin_partner_id,
            //         $scheduleTime,
            //     );

            // }
        }
    }

    // protected function sendFirebaseNotification($deviceKey, $title, $body)
    // {
    //     // $firebase = (new Factory)->withServiceAccount('firebase_credentials.json');
    //     $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
    //     $messaging = $firebase->createMessaging();

    //     $message = CloudMessage::withTarget('token', $deviceKey)
    //         ->withNotification(Notification::create($title, $body));

    //     try {
    //         $response = $messaging->send($message);
    //         // \Log::info('Firebase response: ', (array) $response);
    //     } catch (\Exception $e) {
    //         // \Log::error('Error sending Firebase notification: ' . $e->getMessage());
    //     }
    // }

//     public function api_store_draft(Request $request)
// {
//     $payload = [];

//     // Validate the request data for drafts
//     $draft_validator = Validator::make($request->all(), [
//         'overall_status' => ['required'],
//     ]);

//     if ($draft_validator->fails()) {
//         return response()->json(['errors' => $draft_validator->errors()], 400);
//     }

//     // Update lead attributes with validated data
//     $draft_data = $draft_validator->validated();
//     $draft_data['created_by'] = auth()->user()->id;
//     $draft_data['status'] = 'draft';
//     $draft_data['reason'] = null;

//     $latestOrder = Order::latest()->first();

//     // Extract and increment the numeric part
//     if ($latestOrder) {
//         $nextNumber = intval($latestOrder->order_no) + 1;
//     } else {
//         $nextNumber = 1;
//     }

//     // Format the next order number to ensure it is 6 digits long
//     $nextOrderNo = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

//     $authenticatedUser = auth()->user();
//     $draft_data['business_partner_id'] = $authenticatedUser->partner_id;
//     $draft_data['order_no'] = $nextOrderNo;

//     $payload['order_data'] = $draft_data;
//     $payload['nextOrderNo'] = $nextOrderNo;

//     Order::store_draft_order($payload);

//     return response()->json(['message' => 'Draft order created successfully.', 'data' => $draft_data], 200);
// }

// public function saveDraft(Request $request)
// {
//     // Validate the draft data (minimal validation)
//     $validator = Validator::make($request->all(), [
//         'customer_partner_id' => ['nullable'],
//         'overall_adult' => ['nullable'],
//         'overall_child' => ['nullable'],
//         'overall_bags' => ['nullable'],
//         'overall_status' => ['nullable'],
//         'booking_amount' => ['nullable'],
//         'final_amount' => ['nullable'],
//         'rate_list_id.*' => ['nullable'],
//         'rate.*' => ['nullable'],
//         'status.*' => ['nullable'],
//         'adult.*' => ['nullable'],
//         'child.*' => ['nullable'],
//         'bags.*' => ['nullable'],
//         'date.*' => ['nullable'],
//         'pickup_time.*' => ['nullable'],
//         'is_ac.*' => ['nullable'],
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 400);
//     }

//     $order_data = $validator->validated();
//     $order_data['created_by'] = auth()->user()->id;
//     $order_data['overall_status'] = 'draft';
//     $order_data['status'] = 'draft';
//     $order_data['reason'] = null;

//     // Creating a new customer partner if 'name' is provided
//     if ($request->has('name')) {
//         $customer = new Partner();
//         $customer->name = request('name');
//         $customer->created_by = auth()->user()->id;
//         $customer->actor_id = 6;
//         $customer->save();
//         $order_data['customer_partner_id'] = $customer->id;
//     }

//     $latestOrder = Order::latest()->first();
//     $nextNumber = $latestOrder ? intval($latestOrder->order_no) + 1 : 1;
//     $nextOrderNo = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
//     $authenticatedUser = auth()->user();
//     $order_data['business_partner_id'] = $authenticatedUser->partner_id;
//     $order_data['order_no'] = $nextOrderNo;

//     $payload['order_data'] = $order_data;
//     $payload['nextOrderNo'] = $nextOrderNo;

//     Order::store_order($payload);

//     return response()->json([
//         'message' => 'Draft saved successfully.',
//         'data' => $order_data,
//     ], 200);
// }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    public function api_show($id)
    {
        $breadcrumbs = [
            [
                'name' => "Order",
                'link' => route("orders.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("orders.show", $id),
                'active' => true,
            ],
        ];
        $order = Order::find($id);

        return response()->json([
            // 'breadcrumbs' => $breadcrumbs,
            'order' => $order,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */
    public function api_edit($id)
    {
        $breadcrumbs = [
            [
                'name' => "Order",
                'link' => route("orders.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("orders.edit", $id),
                'active' => true,
            ],
        ];
        // $order = Order::find($id);
        $order = Order::with('orderDetails','partner_customer','business_partner')->checkGlobal(45)->where('company_id', auth()->user()->active_company())->find($id);
        
        // $business_partner = Partner::where('partner_type', 'business')->get();
        // $customer_partner = Partner::where('partner_type', 'customer')->get();

        $data = [
            'order' => $order,
            'breadcrumbs' => $breadcrumbs,
            // 'business_partner' => $business_partner,
            // 'customer_partner' => $customer_partner,
        ];

        return response()->json($data, 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $order
     * *
     */
    public function api_update(Request $request, $order)
    {
        $payload = [];
        $order = Order::find($order);

        $order_validator_rules = [
            'overall_status' => ['required'],
        ];

        if ($request->overall_status != 'draft') {
            if ($request['trip_type'] == 'passenger_trip') {
                $order_validator_rules = array_merge($order_validator_rules, [
                // 'order_no' => ['required', 'unique:orders,order_no'],
                'customer_partner_id' => ['nullable'],
                'business_partner_id' => ['required'],
                'vehicle_model_id' => ['required'],
                'vehicle_class_id' => ['required'],
                'overall_adult' => ['required'],
                'overall_child' => ['required'],
                'overall_bags' => ['required'],
                'overall_status' => ['required'],
                'booking_amount' => ['nullable'],
                'final_amount' => ['nullable'],
                'company_id' => ['nullable'],
                'trip_type' => ['required'],
                // order_details validation:

                //'order_id' => ['required'],
                'rate_list_id.*' => ['required'],

                // 'from.*' => ['required'],
                // 'to.*' => ['required'],
                'rate.*' => ['required'],
                // 'status.*' => ['required'],
                'flight_num.*' => ['nullable'],
                'airline_name.*' => ['nullable'],
                'adult.*' => ['required'],
                'child.*'=>['required'],
                'bags.*' => ['required'],
                'date.*' => ['required'],
                'pickup_time.*' => ['required'],
                'company_id.*' => ['nullable'],
                // 'checkout_time.*'=>['nullable'],
                'is_ac.*' => ['required'],
                //customer validation
                'customer_business_partner_id'=>['nullable'],
                'customer_name' => ['nullable'],
                'whatsapp_no' => ['nullable'],
                'prefix_whatsapp' => ['nullable'],
                'email' => ['nullable'],
                'passport' => ['nullable'],
                'prefix_phone' => ['nullable'],
                'phone_no' => ['nullable'],
                'cnic' => ['nullable'],
                'address1' => ['nullable'],
                'country' => ['nullable'],
                'city' => ['nullable'],

                ]);
            }
            else{

                // $order_validator = Validator::make($request->all(), [
                    $order_validator_rules = array_merge($order_validator_rules, [
                    // 'order_no' => ['required', 'unique:orders,order_no'],
                    'customer_partner_id' => ['nullable'],
                    'business_partner_id' => ['required'],
                    'vehicle_model_id' => ['required'],
                    'vehicle_class_id' => ['required'],

                    'overall_status' => ['required'],
                    'booking_amount' => ['nullable'],
                    'final_amount' => ['nullable'],
                    'trip_type' => ['required'],
                    'direction' => ['required'],
                    'company_id' => ['nullable'],
                    // order_details validation:

                    //'order_id' => ['required'],
                    // 'rate_list_id.*' => ['nullable'],

                    'from.*' => ['required'],
                    'to.*' => ['required'],
                    'rate.*' => ['required'],
                    // 'status.*' => ['required'],
                    'weight.*' => ['required'],
                    'unit.*' => ['required'],
                    'type_of_load.*' => ['required'],
                    'date.*' => ['required'],
                    'pickup_time.*' => ['required'],
                    'company_id.*' => ['nullable'],
                    // 'checkout_time.*'=>['nullable'],
                    'is_ac.*' => ['required'],
                    // customer model required fields:
                    'customer_business_partner_id'=>['nullable'],
                    'customer_name' => ['nullable'],
                    'whatsapp_no' => ['nullable'],
                    'prefix_whatsapp' => ['nullable'],
                    'email' => ['nullable'],
                    'passport' => ['nullable'],
                    'phone_no' => ['nullable'],
                    'prefix_phone' => ['nullable'],

                    'cnic' => ['nullable'],
                    'address1' => ['nullable'],
                    'country' => ['nullable'],
                    'city' => ['nullable'],

                ]);
            }
            // dd($order_validator);
            if($request['trip_type'] == 'passenger_trip'){
                foreach ($request->input('rate_list_id') as $key => $rate_list_id) {
                    $rateList = RateList::findOrFail($rate_list_id);
                    $route = Route::findOrFail($rateList->route_id);

                    if ($route && $route->is_flight == 1) {
                        // Add flight_num validation if is_flight is 1
                        $order_validator_rules['flight_num.' . $key] = ['required'];
                        $order_validator_rules['airline_name.' . $key] = ['required'];
                    }
                }
            }
        }

        $order_validator = Validator::make($request->all(), $order_validator_rules);

        if ($request->overall_status != 'draft' && (!isset($request['pickup_time']) || count($request['pickup_time']) < 1)) {
            return response()->json(['errors' => 'At least one order line is required.']);
        }

        if ($order_validator->fails()) {

            return response()->json(['errors' => $order_validator->errors()], 400);
        }

        if($request->overall_status == 'draft'){
            $order_data = $request->all();
          }else{
              $order_data = $order_validator->validated();
          }
        $order_data['updated_by'] = auth()->user()->id;
        // dd($order_data);
        // $authenticatedUser = auth()->user();
        // $order_data['business_partner_id'] = $authenticatedUser->partner_id;

        if ($order_data['overall_status'] == 'draft') {
            $order_data['status'] = 'draft';

        } else {
            $order_data['status'] = 'pending';
        }

        $order_data['reason'] = null;
        // $order_data['customer_partner_id'] = $order->customer_partner_id;
        if($order_data['customer_partner_id'] == null){
            // $authenticatedUser = auth()->user();
            //  $customer_business_partner = $authenticatedUser->partner_id;
            // creating customer in order
            $customer_partner_id = Partner::create([
                'name' => $order_data['customer_name'] ?? null,
                'whatsapp_no' => $order_data['whatsapp_no'] ?? null,
                'prefix_whatsapp' => $order_data['prefix_whatsapp'] ?? null,
                'email' => $order_data['email'] ?? null,
                'cnic' => $order_data['cnic'] ?? null,
                'phone_no' => $order_data['phone_no'] ?? null,
                'prefix_phone' => $order_data['prefix_phone'] ?? null,
                'passport' => $order_data['passport'] ?? null,
                'address1' => $order_data['address1'] ?? null,
                'country' => $order_data['country'] ?? null,
                'city' => $order_data['city'] ?? null,
                'actor_id' => 6,
                'created_by' => auth()->user()->id,
                'business_partner_id' => $order_data['customer_business_partner_id'] ?? null,
                'company_id' => auth()->user()->active_company(),
            ]);
            $order_data['customer_partner_id'] = $customer_partner_id->id??null;
        }
        $payload['order_data'] = $order_data;

        // update customer in order
        // $partner_id = $order->customer_partner_id;
        // $partner = Partner::find($partner_id);
        // /dd($partner);
        // $partner->update([
        //     'name' => $order_data['name'] ?? null,
        //     'whatsapp_no' => $order_data['whatsapp_no'] ?? null,
        //     'prefix_whatsapp' => $order_data['prefix_whatsapp'] ?? null,
        //     'email' => $order_data['email'] ?? null,
        //     'cnic' => $order_data['cnic'] ?? null,
        //     'phone_no' => $order_data['phone_no'] ?? null,
        //     'prefix_phone' => $order_data['prefix_phone'] ?? null,
        //     'passport' => $order_data['passport'] ?? null,
        //     'address1' => $order_data['address1'] ?? null,
        //     'country' => $order_data['country'] ?? null,
        //     'city' => $order_data['city'] ?? null,
        //     // 'passport' => $order_data['passport'] ?? null,
        //     'actor_id' => 6,
        //     'updated_by' => auth()->user()->id,
        // ]);

        // $latestOrder = Order::latest()->first();
        // $nextNumber = $latestOrder ? intval($latestOrder->order_no) + 1 : 1;
        // $nextOrderNo = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // dd($order_data);

        // $order_data['order_no'] = $nextOrderNo;
        $order_data['from_api'] = true;
        // dd($order_data );

        $payload['order_data'] = $order_data;
        // $payload['nextOrderNo'] = $nextOrderNo;
        $payload['order'] = $order;

        if ($order_data['overall_status'] == 'draft') {
            // dd($payload);

            Order::update_draft($payload);
        } else {
            Order::update_order($payload);
        }

        $this->sendWhatsAppNotification($order_data['business_partner_id'], $order_data);

        return response()->json([
            'message' => 'Order update successfully.',
            'data' => $order_data,
        ], 200);
    }

    public function latestPendingOrders()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Invalid user'], 401);
        }

        // Get the partner ID of the authenticated user
        $partnerId = $user->partner_id;

        // Fetch the latest 3 pending orders for this partner
        $orders = Order::where('business_partner_id', $partnerId)
            ->where('overall_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->with('order_details.rate_list.route')
            ->take(3)
            ->get();

        // Return the orders as a JSON response
        return response()->json([
            'message' => 'Latest pending orders fetched successfully.',
            'data' => $orders,
        ], 200);
    }

    public function updateOrderStatus(Request $request, $id)
    {

        $request->validate([
            'overall_status' => ['required'],
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->overall_status = $request->input('overall_status');

        $order->save();

        return response()->json(['message' => 'Order status updated successfully', 'order' => $order], 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $order = Order::find($id)->delete();

        return response()->json([
            'success', 'order deleted successfully',
            'order' => $order,
        ]);
    }
    public function currentorders()
    {
        // $order = order::all()->reverse();
        // return response()->json($order);

        $breadcrumbs = [
            [
                'name' => "orders",
                'active' => false,
            ],
            [
                'name' => "orders",
                'active' => true,
            ],
        ];
        $order = order::all()->reverse();

        return response()->json([
            'breadcrumbs' => $breadcrumbs,
            'order' => $order,
        ], 200);

    }
    // order filtration
    public function completeOrders()
    {

        $completeOrders = Order::where('overall_status', 'completed')->get();

        return response()->json(['complete_orders' => $completeOrders], 200);
    }
    public function draftOrders()
    {

        $draftOrders = Order::where('overall_status', 'draft')->get();

        return response()->json(['draft_orders' => $draftOrders], 200);
    }
    public function approveOrders()
    {

        $approveOrders = Order::where('overall_status', 'approved')->get();

        return response()->json(['approve_orders' => $approveOrders], 200);
    }
    public function unapproveOrders()
    {

        $unapproveOrders = Order::where('overall_status', 'unapproved')->get();

        return response()->json(['unapprove_orders' => $unapproveOrders], 200);
    }
    public function cancelOrders()
    {

        $cancelOrders = Order::where('overall_status', 'cancelled')->get();

        return response()->json(['cancel_orders' => $cancelOrders], 200);
    }
    public function pendingOrders()
    {

        $pendingOrders = Order::where('overall_status', 'pending')->get();

        return response()->json(['pending_orders' => $pendingOrders], 200);
    }
}
