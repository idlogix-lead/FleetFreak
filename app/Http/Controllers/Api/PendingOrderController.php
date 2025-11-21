<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class PendingOrderController extends Controller
{
    static $role_module_id = 10;
    public $my_companies;

    static $ignores=[
        // 'api_index'=>true,
    ];
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
 
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    public function api_index()
    {
        
        // if(auth()->user()->id==2){
        //     $orders = Order::checkGlobal(10)->where('company_id',auth()->user()->active_company())->where('overall_status', 'pending')->get();
        //     }
        //     else{
        //     $orders = Order::checkGlobal(10)->where('company_id',auth()->user()->active_company())->where('overall_status', 'pending')->where('business_partner_id',auth()->user()->partner_id)->get();

        //     }

        // return view('pending_order.index', compact('orders','breadcrumbs'))
        //     ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
        // $orders = Order::with('orderDetails','partner_customer','business_partner')->where('company_id',auth()->user()->active_company())->where('overall_status', 'pending')->get();
        $orders = Order::with('orderDetails','partner_customer','business_partner','vehicleModel','orderDetails.typeOfLoad','orderDetails.unit')->where('company_id',auth()->user()->active_company())->where('overall_status', 'pending')->get();
        return response()->json(['order'=>$orders]);
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
                'name'=>"Pending Order",
                'link'=>route("pending_orders.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("pending_orders.edit",$id),
                'active'=>true,
            ]
        ];
        $order = Order::checkGlobal(10)->where('company_id',auth()->user()->active_company())->find($id);
        // dd($order);
        // $business_partner = Partner::where('partner_type','business')->get();
        //$customer_partner = Partner::where('partner_type','customer')->get();

        // return view('pending_order.edit', compact('order','breadcrumbs'));
        return response()->json(['order'=>$order]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * *
     */
    public function api_update(Request $request,$order)
    {
        $payload = [];
        $order = Order::find($order);
        // Validate the request data
        $order_validator = Validator::make($request->all(), [
        'overall_status'=>['required'],
        'reason'=>['nullable'],
        // 'status.*'=>['required'],
        ]);

        if ($order_validator->fails()) {

            // return back()->with('errors', $order_validator->errors());
            return response()->json(['errors'=>$order_validator->errors()],400);
        }
        // Update lead attributes with validated data
        $order_data = $order_validator->validated();
        $order_data['updated_by'] = auth()->user()->id;
        if($order->partner_customer->actor_id == 8){

                $order_data['status'] = 'incomplete';

        }
        else{

            $order_data['status'] = 'approved';
        }
        if($request['overall_status']=='unapproved'){
            $order_data['status']= 'unapproved';
        }
        if($request['overall_status']=='cancelled'){
            $order_data['status']= 'cancelled';
        }

         //changed pending to approved
        $payload['order_data']= $order_data;
        $payload['order'] = $order;

        // dd($payload);


        Order::update_pending_order($payload);
        $authenticatedUser = auth()->user();
        if ($authenticatedUser->actor_id == 2) { // Admin's actor_id is 2
            $agent = User::find($order->created_by);
            if ($agent && $agent->actor_id == 4) { // Agent's actor_id is 4
                $this->sendFirebaseNotification($agent->firebase_token, 'Order Status Updated', 'The status of your order (Order No: ' . $order->order_no . ') has been updated to ' . $order_data['status'] . ' by ' . $authenticatedUser->name . '.');
            }
        }


        // $order->update($order_data);

        // return redirect()->route('pending_orders.index')
        //     ->with('success', 'Order updated successfully');
        return response()->json(['success'=>'order update successfully', 'order'=>$order]);
    }

//     protected function sendFirebaseNotification($deviceKey, $title, $body)
// {
//     $firebase = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));    $messaging = $firebase->createMessaging();

//     $message = CloudMessage::withTarget('token', $deviceKey)
//         ->withNotification(Notification::create($title, $body));

//     try {
//         $response = $messaging->send($message);
//         // \Log::info('Firebase response: ', (array)$response);
//     } catch (\Exception $e) {
//         // \Log::error('Error sending Firebase notification: ' . $e->getMessage());
//     }
// }

//     /**
//      * @param int $id
//      * @return \Illuminate\Http\RedirectResponse
//      * @throws \Exception
//      */
//     // public function destroy($id)
//     // {
//     //     $order = Order::find($id)->delete();

//     //     return redirect()->route('pending_orders.index')
//     //         ->with('success', 'Order deleted successfully');
//     // }
}
