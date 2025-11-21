<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\WhatsAppController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class PendingOrderController extends Controller
{
    static $ignores = [];
    protected $auth, $messaging;
    static $role_module_id = 10;
    public function __construct(Messaging $messaging)
    {
        $this->middleware('RolePermissions');
        $this->auth = Firebase::auth();
        $this->messaging = $messaging;
    }
    public function index(Request $request)
    {

        $customer = $request->input('customer_partner');
        $order_no = $request->input('order_no');
        $agent = $request->input('agent');
        $query = $request->input('query');
        $perPage = $request->input('perPage', 10);

        $breadcrumbs = [
            [
                'name' => "Pending Rides",
                'link' => route("pending_orders.index"),
                'active' => true,
            ],
        ];

        $user = auth()->user();

        $company = $user->companies->first();

        // if (!$company) {
        //     return redirect()->route('dashboard')->with('error', 'No associated company found.');
        // }
        $companyId = auth()->user()->active_company() ?? null;
        // for admin user_id = 2:
        if (auth()->user()->actor_id == 2) {
            $orders = Order::checkGlobal(10)->where('overall_status', 'pending')->where('company_id', $companyId)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($q) use ($query) {
                    $q->where('order_no', 'ILIKE', '%' . $query . '%')
                        ->orWhere('trip_type','ILIKE','%'. $query .'%')
                        ->orWhereHas('partner_business', function ($q2) use ($query) {
                            $q2->where('company_name', 'ILIKE', '%' . $query . '%');
                        })->orWhereHas('partner_customer',function($q3) use ($query){
                            $q3->where('name', 'ILIKE', '%' . $query . '%');
                        });

                });
            })->orderBy('created_at', 'desc')->paginate($perPage);
        } else {
            $orders = Order::when($companyId, function ($query) use ($companyId) {
                return $query->where('company_id', $companyId);
            })->checkGlobal(10)->where('overall_status', 'pending')
            // ->where('business_partner_id', auth()->user()->partner_id)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($q) use ($query) {
                    $q->where('order_no', 'ILIKE', '%' . $query . '%')
                      ->orWhereHas('partner_customer',function($q3) use ($query){
                            $q3->where('name', 'ILIKE', '%' . $query . '%');
                        });
                });
            })->orderBy('created_at', 'desc')->paginate($perPage);

        }

        return view('pending_order.index', compact('orders', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    // public function create()
    // {
    //     $breadcrumbs = [
    //         [
    //             'name'=>"Pending Order",
    //             'link'=>route("pending_orders.index"),
    //             'active'=>false,
    //         ],
    //         [
    //             'name'=>"Create",
    //             'link'=>route("pending_orders.create"),
    //             'active'=>true,
    //         ]
    //     ];
    //     //$order = new Order();
    //     $latestOrder = Order::latest()->first();
    //     $nextNumber = $latestOrder ? intval(substr($latestOrder->order_no, 6)) + 1 : 1;
    //     $nextOrderNo =str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

    // // Create a new order instance
    //     $order = new Order();
    //     $order->order_no = $nextOrderNo;
    // // Other attributes...
    //     // $business_partner = Partner::where('partner_type','business')->get();
    //     // $customer_partner = Partner::where('partner_type','customer')->get();
    //     return view('pending_order.create', compact('order','breadcrumbs','nextOrderNo'));
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
    //     'reason'=>['nullable'],
    //     // order_details validation:

    //     'rate_list_id.*' => ['required'],
    //     'rate.*'=>['required'],
    //     'status.*'=>['required'],
    //     'adult.*'=>['required'],
    //     'child.*'=>['required'],
    //     'bags.*'=>['required'],
    //     'date.*'=>['required'],
    //     'pickup_time.*'=>['required'],
    //     // 'checkout_time.*'=>['required'],
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
    //     $payload['order_data'] = $order_data;
    //     // dd($payload);
    //     Order::store_order($payload);
    //     //$order_data = Order::create($order_data);

    //     return redirect()->route('pending_orders.index')->with('success', 'Order created successfully.');
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
    //             'name'=>"Pending Order",
    //             'link'=>route("pending_orders.index"),
    //             'active'=>false,
    //         ],
    //         [
    //             'name'=>"Show",
    //             'link'=>route("pending_orders.show",$id),
    //             'active'=>true,
    //         ]
    //     ];
    //     $order = Order::find($id);

    //     return view('pending_order.show', compact('order','breadcrumbs'));
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int $id
    //  * *
    //  */
    public function edit($id)
    {
        $breadcrumbs = [
            [
                'name' => "Pending Rides",
                'link' => route("pending_orders.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("pending_orders.edit", $id),
                'active' => true,
            ],
        ];
        $order = Order::checkGlobal(10)->where('company_id',auth()->user()->active_company())->find($id);
        // dd($order);
        // $business_partner = Partner::where('partner_type','business')->get();
        //$customer_partner = Partner::where('partner_type','customer')->get();

        return view('pending_order.edit', compact('order', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * *
     */
    // public function update(Request $request,$order)
    // {
    //     $payload = [];
    //     $order = Order::find($order);
    //     // Validate the request data
    //     $order_validator = Validator::make($request->all(), [
    //     'overall_status'=>['nullable'],
    //     'reason'=>['nullable'],
    //     'status.*'=>['required'],
    //     ]);

    //     if ($order_validator->fails()) {

    //         return back()->with('errors', $order_validator->errors());
    //     }
    //     // Update lead attributes with validated data
    //     $order_data = $order_validator->validated();
    //     $order_data['updated_by'] = auth()->user()->id;
    //     if($order->partner_customer->actor_id == 8){

    //             $order_data['status'] = 'incomplete';

    //     }
    //     else{

    //         $order_data['status'] = 'approved';
    //     }
    //     if($request['overall_status']=='unapproved'){
    //         $order_data['status']= 'unapproved';
    //     }
    //     if($request['overall_status']=='cancelled'){
    //         $order_data['status']= 'cancelled';
    //     }

    //      //changed pending to approved
    //     $payload['order_data']= $order_data;
    //     $payload['order'] = $order;

    //     // dd($payload);

    //     Order::update_pending_order($payload);

    //     // $order->update($order_data);

    //     return redirect()->route('pending_orders.index')
    //         ->with('success', 'Order updated successfully');
    // }

    public function update(Request $request, $order)
    {
        $payload = [];
        $order = Order::find($order);
        // dd($request);

        // Validate the request data
        $order_validator = Validator::make($request->all(), [
            'overall_status' => ['nullable'],
            'reason' => ['nullable'],
            'status.*' => ['required'],
        ]);
        // dd($order_validator);

        if ($order_validator->fails()) {
            return back()->with('errors', $order_validator->errors());
        }


        // Update lead attributes with validated data
        $order_data = $order_validator->validated();

        $order_data['updated_by'] = auth()->user()->id;
        // $orderData=$order->partner_customer->name;
        // $orderData=$order->order_no;
   
        // dd($order_data);
    //    dd($order->partner_customer->name);

        // $order_data=$order->order_no;
        // $order_data=$order->customer_partner_id;



        // $orderDetail = $order->orderDetails()->first();


        if ($order->partner_customer->actor_id == 8) {
            $order_data['status'] = 'incomplete';
            // $status = 'approved';

        } else {
            $order_data['status'] = 'approved';
            // $status = 'approved';
        }

        if ($request['overall_status'] == 'unapproved') {
            $order_data['status'] = 'unapproved';

        }

        if ($request['overall_status'] == 'cancelled') {
            $order_data['status'] = 'cancelled';

        }

        // $order_data['rate_list_id'] = $order->rate_list_id;

        // Prepare the payload
        $payload['order_data'] = $order_data;
        $payload['order'] = $order;

        // Update the pending order
        Order::update_pending_order($payload);


        // if ($businessPartnerId && ($order_data['status'] == 'approved' || $order_data['status'] == 'incomplete')) {
        //     // Loop through each order detail and send a notification
        //     foreach ($order->orderDetails as $orderDetail) {
        //         $orderDetailData = $order_data;

        //         // Check if the rate_list_id exists; if not, use the driver pickup and dropoff locations
        //         if (!isset($orderDetail->rate_list_id) || empty($orderDetail->rate_list_id)) {
        //             // This means the order is for a driver without a rate list
        //             $orderDetailData['rate_list_id'] = null;
        //             $orderDetailData['driver_rate'] = $orderDetail->driver_rate;
        //             $orderDetailData['driver_pickup_loc'] = $orderDetail->from_loc ?? 'N/A';
        //             $orderDetailData['driver_dropoff_loc'] = $orderDetail->to_loc ?? 'N/A';
        //             $orderDetailData['driverbool'] = true; // Custom flag to indicate driver data is being used
        //         } else {
        //             // If rate_list_id exists, use it as usual
        //             $orderDetailData['rate_list_id'] = $orderDetail->rate_list_id;
        //             $orderDetailData['rate'] = $orderDetail->rate;
        //             $orderDetailData['flight_num'] = $orderDetail->flight_num ?? 'none';
        //             $orderDetailData['airline_name'] = $orderDetail->airline_name ?? 'none';
        //         }

        //         $this->sendWhatsAppNotification($businessPartnerId, $orderDetailData);
        //     }
        // } else {
        //     Log::error('Business partner ID is missing in the order', ['order_id' => $order->id]);
        // }

        // Send notification to the agent
        $agent = $order->createdBy; // Use the relationship to get the User object
        if ($agent && $agent->firebase_token && $order_data['status'] == 'approved' ) {
            Log::info('Sending notification to agent', ['agent_id' => $agent->id, 'firebase_token' => $agent->firebase_token]);
            $this->sendNotificationToAgent($agent->firebase_token, $order_data['status']);
        } else {
            Log::error('Firebase token is empty for the agent', ['agent_id' => $order->created_by]);
        }

        return redirect()->route('pending_orders.index')
            ->with('success', 'Order updated successfully');
    }


    // private function sendWhatsAppNotification($businessPartnerId, $orderData)
    // {
    //     $status = $orderData['status'] ?? 'approved'?? 'incomplete';
    //     $whatsAppController = new WhatsAppController();
    //     $whatsAppController->sendNotification($businessPartnerId,null,null, $orderData,$status);
    // }


    protected function sendNotificationToAgent($firebaseToken, $orderStatus)
    {

            $notificationTitle = 'Order Updated';
            $notificationBody = "Your order has been approved.";




        $notification = [
            "title" => $notificationTitle,
            "body" => $notificationBody,
        ];

        $data = [
            "order_status" => $orderStatus,
        ];

        try {
            $this->send([$firebaseToken], $notification, $data);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to send notification to agent: ' . $e->getMessage());
        }
    }

    public function send($firebase_token, $notification, $data)
    {
        $notification = Notification::create($notification['title'], $notification['body']);
        $message = CloudMessage::withTarget('token', $firebase_token[0])
            ->withNotification($notification)
            ->withData($data);

        return $this->messaging->send($message);
    }










    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    // public function destroy($id)
    // {
    //     $order = Order::find($id)->delete();

    //     return redirect()->route('pending_orders.index')
    //         ->with('success', 'Order deleted successfully');
    // }
}
