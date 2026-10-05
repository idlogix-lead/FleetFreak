<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\AccountTransaction;
use App\Models\Account;
use App\Models\Partner;
use App\Models\PaymentHeader;
use App\Models\PaymentLine;
use App\Models\RateList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Models\TimeLog;
use App\Models\Vehicle;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class DriverAssignmentController extends Controller
{

    protected $auth, $messaging;
    static $role_module_id = 14;
    static $ignores = [
        'api_assignVehicle' => true,
        'api_incompleteRides' => true,
        'api_incompleteRidesUpdate' => true,
        'api_ratelist' => true,
        'api_store' => true,
        'getAvailableVehiclesAjax' => true,
        'getallride_assign_to_driver' => true,
        'updateRideStatus' => true,
        'api_vehicle_assign'=> true
    ];
    public $my_companies;
    public function __construct(Messaging $messaging)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
        $this->auth = Firebase::auth();
        $this->messaging = $messaging;
    }
    public function admin_index()
    {
        $company_id = auth()->user()->active_company();
        $allRides = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', $company_id)->where('status', 'incomplete')->get();

        return response()->json([
            'all_rides' => $allRides,
        ]);
    }
    public function getallride_assign_to_driver()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Unauthorized or missing partner ID'], 401);
        }

        // Get the driver's partner ID
        $driverPartnerId = $user->partner_id;

        // Fetch all rides assigned to this driver, regardless of status
        $allRides = OrderDetail::where('driver_id', $driverPartnerId)->where('company_id', auth()->user()->active_company())
            ->with('order.partner_customer', 'order.business_partner', 'rate_list.route')
            ->get();

        // Return the rides as a JSON response
        return response()->json([
            'rides' => $allRides,
        ]);
    }
    public function getAuthenticatedUser()
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Invalid user'], 401);
        }
        $user->load('partner');

        return response()->json([
            'user' => $user,
        ]);
    }
    public function api_index()
    {
        $breadcrumbs = [
            [
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.index"),
                'active' => true,
            ],
        ];
        // $orders = Order::where('overall_status','approved')->paginate();
        $orders = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', auth()->user()->active_company())->where('status', 'pending')->paginate();

        return response()->json(['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    public function api_create()
    {
        $breadcrumbs = [
            [
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("driver_assignments.create"),
                'active' => true,
            ],
        ];
        //$order = new Order();
        $latestOrder = Order::where('company_id', auth()->user()->active_company())->latest()->first();
        $nextNumber = $latestOrder ? intval(substr($latestOrder->order_no, 6)) + 1 : 1;
        $nextOrderNo = 'ORD-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // Create a new order instance
        $order = new Order();
        $order->order_no = $nextOrderNo;
        // Other attributes...
        // $business_partner = Partner::where('partner_type','business')->get();
        // $customer_partner = Partner::where('partner_type','customer')->get();
        $data = [
            'order' => $order,
            'breadcrumbs' => $breadcrumbs,
            'nextOrderNo' => $nextOrderNo,
        ];

        // Return the data as a JSON response
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    // public function api_store(Request $request)
    // {
    //     $payload = [];
    //     // Validate the request data
    //     $order_validator = Validator::make($request->all(), [
    //         'order_no' => ['required', 'unique:orders,order_no'],
    //         'customer_partner_id' => ['required'],
    //         'business_partner_id' => ['required'],
    //         'overall_adult' => ['required'],
    //         'overall_child' => ['required'],
    //         'overall_bags' => ['required'],
    //         'overall_status' => ['required'],
    //         'booking_amount' => ['nullable'],
    //         'final_amount' => ['nullable'],
    //         // order_details validation:

    //         //'order_id' => ['required'],
    //         'rate_list_id.*' => ['required'],
    //         'rate.*' => ['required'],
    //         'status.*' => ['required'],
    //         'adult.*' => ['required'],
    //         'child.*' => ['required'],
    //         'bags.*' => ['required'],
    //         'date.*' => ['required'],
    //         'pickup_time.*' => ['required'],
    //         // 'checkout_time.*'=>['nullable'],
    //         'is_ac.*' => ['required'],
    //     ]);

    //     if ($order_validator->fails()) {
    //         //    dd($order_validator->errors());
    //         return response()->json(['errors' => $order_validator->errors()], 400);
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    public function show($id)
    {
        $breadcrumbs = [
            [
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("driver_assignments.show", $id),
                'active' => true,
            ],
        ];
        $order = Order::where('company_id', auth()->user()->active_company())->find($id);

        return view('driver-assignment.show', compact('order', 'breadcrumbs'));
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
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("driver_assignments.edit", $id),
                'active' => true,
            ],
        ];
        $order = Order::where('company_id', auth()->user()->active_company())->find($id);
        // dd($order);
        // $business_partner = Partner::where('partner_type','business')->get();
        //$customer_partner = Partner::where('partner_type','customer')->get();

        return view('driver-assignment.edit', compact('order', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $order
     * *
     */
    // public function api_update(Request $request, $order)
    // {
    //     $payload = [];
    //     $extra_fields = true;
    //     $order = Order::find($order);
    //     // Validate the request data
    //     $order_validator = Validator::make($request->all(), [
    //         'order_no' => ['required'],
    //         'customer_partner_id' => ['required'],
    //         'business_partner_id' => ['required'],
    //         'overall_adult' => ['required'],
    //         'overall_child' => ['required'],
    //         'overall_bags' => ['required'],
    //         'overall_status' => ['nullable'],
    //         'booking_amount' => ['nullable'],
    //         'final_amount' => ['nullable'],
    //         // order_details validation:
    //         'rate_list_id.*' => ['required'],
    //         'rate.*' => ['required'],
    //         'status.*' => ['required'],
    //         'adult.*' => ['required'],
    //         'child.*' => ['required'],
    //         'bags.*' => ['required'],
    //         'date.*' => ['required'],
    //         'pickup_time.*' => ['required'],
    //         // 'checkout_time.*'=>['nullable'],
    //         'is_ac.*' => ['required'],
    //         'vehicle_id.*' => ['required'],
    //         'driver_id.*' => ['required'],
    //     ]);
    //     if ($order_validator->fails()) {

    //         return back()->with('errors', $order_validator->errors());
    //     }
    //     // Update lead attributes with validated data
    //     $order_data = $order_validator->validated();
    //     $order_data['updated_by'] = auth()->user()->id;
    //     $order_data['status'] = 'pending';
    //     $order_data['reason'] = null;
    //     $payload['order_data'] = $order_data;
    //     $payload['extra_fields'] = $extra_fields;
    //     $payload['order'] = $order;

    //     // dd($payload);
    //     Order::update_order($payload);

    //     // $order->update($order_data);

    //     return redirect()->route('driver_assignments.index')
    //         ->with('success', 'Order updated successfully');
    // }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $order = Order::where('company_id', auth()->user()->active_company())->findOrFail($id)->delete();

        return redirect()->route('driver_assignments.index')
            ->with('success', 'Order deleted successfully');
    }
    public function api_assignVehicle(Request $request)
    {

        // dd($request);
        $order = OrderDetail::where('company_id', auth()->user()->active_company())->find($request->row_id);
        if ($order) {
            $order->vehicle_id = $request->vehicle_id;
            $order->status = 'incomplete';
            $order->save();

            return response()->json(['success' => true, 'message' => 'Vehicle assigned successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Order not found.']);
    }
    public function api_vehicle_assign(Request $request)
    {

        // dd($request);
        $order = OrderDetail::where('company_id', auth()->user()->active_company())->find($request->order_detail_id);
        if ($order) {
            $order->vehicle_id = $request->vehicle_id;
            $order->status = 'incomplete';
            if($order->order->trip_type=='daily_booking'){
               if($order->with_driver==1){
                $driver_id=Vehicle::find($request->vehicle_id)->value('driver_id');
                $order->driver_id=$driver_id;
               }
            }
            if($order->order->trip_type!='monthly_booking' && $order->order->trip_type!='daily_booking'){
                $driver_id=Vehicle::find($request->vehicle_id)->value('driver_id');
                $order->driver_id=$driver_id;
            }
            // dd($order->order->trip_type);
            $order->save();
            $incomplete_order=OrderDetail::with('order','vehicle','driver')->where('id',$order->id)->where('company_id', auth()->user()->active_company())->first();

            return response()->json(['success' => true, 'message' => 'Vehicle assigned successfully!','Incomplete_order'=>$incomplete_order]);
        }

        return response()->json(['success' => false, 'message' => 'Order not found.']);
    }
    public function api_incompleteRides(Request $request)
    {

        //dd('incomplete rides');
        $breadcrumbs = [
            [
                'name' => "Driver Assignment",
                'link' => route("driver_assignments.incomplete_rides"),
                'active' => true,
            ],
        ];
        // $orders = Order::where('overall_status','approved')->paginate();
        $orders = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('status', 'incomplete')->where('company_id', auth()->user()->active_company())->paginate();

        return response()->json([
            'orders' => $orders,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
    public function api_incompleteRidesUpdate(Request $request)
    {

        // dd($request);
        $order = OrderDetail::where('company_id', auth()->user()->active_company())->find($request->row_id);
        if ($order) {
            $order->status = 'completed';
            $order->save();

            return response()->json(['success' => true, 'message' => 'Ride Completed Successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Ride Not Found.']);
    }
    public function api_ridelist()
    {
        $ridelist = OrderDetail::where('company_id', auth()->user()->active_company())->all();
        return response()->json($ridelist);
    }

    // public function ride_assign_to_driver()
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Ensure the user is authenticated and has a partner_id
    //     if (!$user || !$user->partner_id) {
    //         return response()->json(['error' => 'Unauthorized or missing partner ID'], 401);
    //     }

    //     // Get the driver's partner ID
    //     $driverPartnerId = $user->partner_id;

    //     // Fetch all rides assigned to this driver from order_details
    //     $rides = OrderDetail::where('driver_id', $driverPartnerId)->get();

    //     // Return the rides as a JSON response
    //     return response()->json([
    //         'rides' => $rides
    //     ]);
    // }

    public function ride_assign_to_driver_pending()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Unauthorized or missing partner ID'], 401);
        }

        // Get the driver's partner ID
        $driverPartnerId = $user->partner_id;
        $company_id = auth()->user()->active_company();

        // Fetch all pending rides assigned to this driver
        $pendingRides = OrderDetail::where('driver_id', $driverPartnerId)
            ->where('status', 'incomplete')
            ->where('company_id', $company_id)
            ->with('typeOfLoad', 'unit', 'order.partner_customer', 'order.business_partner', 'rate_list.route')
            ->get();
        // $pendingRides = OrderDetail::where('driver_id', $driverPartnerId)
        // ->where('status', 'incomplete')
        //     ->with('typeOfLoad','unit')
        //     ->get();
        // dd($pendingRides);

        // Return the pending rides as a JSON response
        foreach ($pendingRides as $ride) {

            // Calculate the ride start time
            $rideStartTime = Carbon::parse($ride->order->start_time);
            // Check if the ride start time is within 1 hour
            if ($rideStartTime->diffInHours(Carbon::now()) == 1) {
                // Send ride reminder notification to the driver
                $driver = $ride->driver; // Assuming you have a Driver model related to OrderDetail
                if ($driver && $driver->firebase_token) {
                    $this->sendRideReminderNotification($driver->firebase_token, $ride);
                } else {
                    Log::error('Firebase token is empty for the driver', ['driver_id' => $driver->id]);
                }
            }
        }

        // Return the pending rides as a JSON response
        // dd($pendingRides);
        // foreach ($pendingRides as $ride) {
        //     $type_of_load_id=$ride->type_of_load;
        //     dd($type_of_load_id);
        // }

        return response()->json([

            'pending_rides' => $pendingRides,

        ]);
    }
    public function get_time_logs()
    {
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Unauthorized or missing partner ID'], 401);
        }

        // Get the driver's partner ID
        // $driverPartnerId = $user->partner_id;
        $company_id = auth()->user()->active_company();

        $data = TimeLog::where('company_id', $company_id)->get();
        //  dd('$data');

        return response()->json(['data' => $data]);
    }
    public function store_time_logs(Request $request)
    {

        // dd('jvnjonor jwvjo');
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Unauthorized or missing partner ID'], 401);
        }


        // Get the driver's partner ID
        // $driverPartnerId = $user->partner_id;
        $company_id = auth()->user()->active_company();
        $timelog_validator = Validator::make($request->all(), [
            'description' => ['required'],
            'is_active' => ['required'],
            'meter_reading' => ['required'],
        ]);

        if ($timelog_validator->fails()) {
            //    dd($order_validator->errors());
            return response()->json(['errors' => $timelog_validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $time_log = $timelog_validator->validated();

        // dd($time_log);
        // $order_data['created_by'] = auth()->user()->id;
        $time_log_data = TimeLog::create([
            'description' => $time_log['description'],
            'is_active' => $time_log['is_active'],
            'meter_reading' => $time_log['meter_reading'],
            'company_id' => $company_id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'time' => carbon::now()
        ]);
        return response()->json([
            'success' => true,
            'message' => 'TimeLog created successfully.',
            'data' => $time_log_data,
        ]);
    }



    protected function sendRideReminderNotification($firebaseToken, $ride)
    {
        $notificationTitle = 'Ride Reminder';
        $notificationBody = "Your ride is starting in 1 hour. Please arrive at the pickup location on time.";

        $notification = [
            "title" => $notificationTitle,
            "body" => $notificationBody,
        ];

        $data = [

            "ride_id" => $ride->id,
        ];

        try {

            $this->send([$firebaseToken], $notification, $data);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to send ride reminder notification to driver: ' . $e->getMessage());
        }
    }
    public function ride_assign_to_driver_completed()
    {
        // Get the authenticated user
        $user = Auth::user();
        // dd($user);
        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Unauthorized or missing partner ID'], 401);
        }

        // Get the driver's partner ID
        $driverPartnerId = $user->partner_id;
        // dd($driverPartnerId);
        $company_id = auth()->user()->active_company();
        //    dd($company_id);
        // Fetch all completed rides assigned to this driver
        $completedRides = OrderDetail::where('driver_id', $driverPartnerId)
            ->where('company_id', $company_id)
            ->where('status', 'completed')
            ->with('typeOfLoad', 'unit', 'order.partner_customer', 'order.business_partner', 'rate_list.route')
            ->get();

        // Return the completed rides as a JSON response
        return response()->json([
            'completed_rides' => $completedRides,
        ]);
    }

    public function ride_assign_to_driver_unapproved()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'user not found'], 401);
        }

        // Get the driver's partner ID
        $driverPartnerId = $user->partner_id;
        $company_id = auth()->user()->active_company();
        // Fetch all completed rides assigned to this driver
        $unapproveRides = OrderDetail::where('driver_id', $driverPartnerId)
            ->where('status', 'pending')
            ->where('company_id', $company_id)
            ->with('typeOfLoad', 'unit', 'order.partner_customer', 'order.business_partner', 'rate_list.route')
            ->get();

        // Return the completed rides as a JSON response
        return response()->json([
            'unapprove_rides' => $unapproveRides,
        ]);
    }

    public function ride_assign_to_driver_inprogress()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'user not found'], 401);
        }

        // Get the driver's partner ID
        $driverPartnerId = $user->partner_id;
        $company_id = auth()->user()->active_company();

        // Fetch all completed rides assigned to this driver
        $inprogress = OrderDetail::where('driver_id', $driverPartnerId)
            ->where('company_id', $company_id)
            ->where('status', 'in_progress')
            ->with('typeOfLoad', 'unit', 'order.partner_customer', 'order.business_partner', 'rate_list.route')
            ->get();

        // Return the completed rides as a JSON response
        return response()->json([
            'inprogress_rides' => $inprogress,
        ]);
    }
    public function updateRideStatus(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        $company_id = auth()->user()->active_company() ?? null;
        // dd($company_id);

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Invalid user id'], 401);
        }

        // Validate the reques
        $validatedData = $request->validate([
            'ride_id' => 'required|exists:order_lines,id',
            'status' => 'required', // Adjust status values as needed
        ]);

        // Get the ride
        $ride = OrderDetail::where('id', $validatedData['ride_id'])
            ->where('driver_id', $user->partner_id)
            ->first();

        // Ensure the ride belongs to the driver
        if (!$ride) {
            return response()->json(['error' => 'Ride not found or does not belong to the authenticated driver'], 404);
        }

        // Update the ride status
        $ride->status = $validatedData['status'];
        $ride->ride_start_mileage = $request['ride_start_mileage'];
        $ride->ride_end_mileage = $request['ride_end_mileage'];
        $ride->save();


        $order = $ride->order;
        $allCompleted = $order->orderDetails->every(function ($detail) {
            return $detail->status === 'completed';
        });


        $customerActorId = $order->partner_customer->actor_id;

        if ($validatedData['status'] === 'completed' && $customerActorId == 8) {
            // Create PaymentHeader and PaymentLine if the ride is completed
            $fare_amount = $ride->rate ?? $ride->driver_rate;

            $paymentHeader = PaymentHeader::create([

                'payment_no' => $this->generatePaymentNo(),
                'type' => 'payment',
                'agent_id' => $order->agent_id,
                'customer_id' => $order->customer_partner_id,
                'driver_id' => $user->partner_id,
                'date' => Carbon::today()->toDateString(),
                'total_amount' => $fare_amount,
                'description' => 'Payment for completed order ' . $order->id,
                'status' => 'paid',
                'created_by' => $user->id,
                'company_id' => $company_id
            ]);

            PaymentLine::create([
                'order_id' => $order->id,
                'order_detail_id' => $ride->id,
                'payment_header_id' => $paymentHeader->id,
                'total_amount' => $fare_amount,
                'transaction_date' => Carbon::today()->toDateString(),
                'description' => 'Payment line for completed order detail ' . $ride->id,
                'payment_type' => 'cash',
                'reference_no' => null,
                'company_id' => $company_id
            ]);
            // OrderDetail::find($ride->id)->update(['status' => 'paid']);


        }
        if ($validatedData['status'] === 'completed') {
            // dd($request);
            $debit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Receivable')->first();
            $credit_account = Account::where('company_id', $company_id)->where('name', 'Ride Revenue')->first();
            $currency = User::current_currency();

            AccountTransaction::create([

                'transaction_date' => $ride->date, // Fill with actual date
                'company_id' => $company_id,       // Fill with actual company ID
                'account_id' => $debit_account->id,
                'quantity' => 1,         // Fill with quantity
                'debit' => $ride->rate ?? $ride->driver_rate,            // Fill with debit amount
                'credit' => 0,           // Fill with credit amount
                'currency_id' => $currency->id,
                'record_id' => $order->id,
                'line_id' => $ride->id,
                'description' => 'testing description',      // Fill with description
                'table_id' => 21,
                'created_by' => $user->id,
                'b_partner_id'=> $order->business_partner_id,

            ]);
            AccountTransaction::create([
                'transaction_date' => $ride->date, // Same fields for the second transaction
                'company_id' => $company_id,
                'account_id' => $credit_account->id,
                'quantity' => 1,
                'debit' => 0,
                'credit' => $ride->rate ?? $ride->driver_rate,
                'currency_id' => $currency->id,
                'record_id' => $order->id,
                'line_id' => $ride->id,
                'description' => 'testing description',
                'table_id' => 21,
                'created_by' => $user->id,
                'b_partner_id'=> $order->business_partner_id,

            ]);
            // $this->sendWhatsAppNotificationComplete($order, $ride, $user->partner_id);

        }

        // If all order details are completed, update the overall_status of the order
        if ($allCompleted) {
            // $order->overall_status = 'completed';
            $order->save();
        }

        // Return the updated ride as a JSON response
        return response()->json([
            'message' => 'Ride status updated successfully',
            'ride' => $ride,
        ]);
    }

    // private function sendWhatsAppNotificationComplete($order, $ride, $driver_id)
    // {
    //     $businessPartnerId = $order->business_partner_id;
    //     $customer_partner_id = $order->customer_partner_id;

    //     $orderData = [
    //         'order_no' => $order->order_no,
    //         'name' => $order->partner_customer->name,
    //         'rate' => $ride->rate,
    //         'driver_rate' => $ride->driver_rate,
    //         'driver_pickup_loc' => $ride->driver_pickup_loc,
    //         'driver_dropoff_loc' => $ride->driver_dropoff_loc,
    //         'rate_list_id' => $ride->rate_list_id ?? null,
    //         'flight_num'=>$ride->flight_num,
    //         'airline_name'=>$ride->airline_name,
    //         // 'driverbool'=>'',
    //     ];
    //     if (!isset($orderData['rate_list_id'])) {
    //         $orderData['driverbool'] = true;
    //     }
    //     // dd($orderData);
    //     $whatsAppController = new WhatsAppController();
    //     // $whatsAppController->sendNotification($businessPartnerId, $customer_partner_id, $driver_id, $orderData, 'completed');

    //     // dd($whatsAppController);
    // }

    //     private function sendWhatsAppNotification($businessPartnerId,$customer_partner_id,$driver_id , $orderData)
    //     {
    //         if ($orderData['overall_status'] !== 'draft') {
    //             $status = $orderData['status'] ?? 'completed';
    //         $whatsAppController = new WhatsAppController();
    //         $whatsAppController->sendNotification($businessPartnerId,$customer_partner_id,$driver_id , $orderData,$status);
    //     }
    // }

    // private function sendNotificationToBusinessPartner($businessPartnerId, $order, $status)
    // {
    //     $businessPartnerId = $order->business_partner_id;
    //     $orderData = [
    //         'order_no' => $order->order_no,
    //         'rate_list_id' => $order->rate_list_id,
    //         'name' => $order->customer_name,
    //         'rate' => $order->orderDetails->pluck('rate')->toArray(),
    //     ];

    //     $whatsAppController = new WhatsAppController();
    //     $whatsAppController->sendNotification($businessPartnerId, $orderData, $status);
    // }

    // private function sendNotificationToDriver($driverId, $order, $status)
    // {
    //     // $businessPartnerId = $order->business_partner_id;
    //     $orderData = [
    //         'order_no' => $order->order_no,
    //         'rate_list_id' => $order->rate_list_id,
    //         'name' => $order->customer_name,
    //         'rate' => $order->orderDetails->pluck('rate')->toArray(),
    //     ];

    //     $whatsAppController = new WhatsAppController();
    //     $whatsAppController->sendNotification($driverId, $orderData, $status);
    // }

    public function generatePaymentNo()
    {
        // Get the current month and year
        $month = Carbon::now()->format('m'); // '06'
        $year = Carbon::now()->format('y'); // '24'

        // Fetch the latest payment number
        $latestPayment = PaymentHeader::latest()->first();

        if ($latestPayment) {
            // Increment the numeric part of the payment number
            $numericPart = intval(substr($latestPayment->payment_no, 0, 5)) + 1;
        } else {
            // If no previous payment number exists, start from 1
            $numericPart = 1;
        }

        // Format the numeric part to have leading zeros (pad to 5 digits)
        $formattedNumericPart = str_pad($numericPart, 5, '0', STR_PAD_LEFT); // '00001'

        // Combine everything to form the payment number
        $paymentNo = $formattedNumericPart . '-' . $month . $year; // '00001-0624'

        return $paymentNo;
    }

    // public function update_ride_status(Request $request, $rideId)
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Ensure the user is authenticated and has a partner_id
    //     if (!$user || !$user->partner_id) {
    //         return response()->json(['error' => 'Unauthorized or missing partner ID'], 401);
    //     }

    //     // Find the ride
    //     $ride = OrderDetail::where('id', $rideId)
    //         ->where('driver_id', $user->partner_id)
    //         ->first();

    //     if (!$ride) {
    //         return response()->json(['error' => 'Ride not found or not assigned to this driver'], 404);
    //     }

    //     // Update the ride status to completed
    //     $ride->status = $request->input('status');
    //     $ride->save();

    //     // Return the updated ride as a JSON response
    //     return response()->json([
    //         'message' => 'Ride status updated to completed',
    //         'ride' => $ride,
    //     ]);
    // }

    public function driver_create_customer(request $request)
    { {
            $payload = [];

            $partner_validator = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'email' => ['nullable'],
                'phone_no' => ['string', 'max:11', 'required'],
                'prefix_phone' => ['required'],
                'whatsapp_no' => ['nullable', 'max:11'],
                'prefix_whatsapp' => ['nullable'],
                'cnic' => ['string', 'nullable'],
                'address1' => ['string', 'nullable'],
                'address2' => ['string', 'nullable'],
                'address3' => ['string', 'nullable'],
                'city' => ['nullable'],
                'country' => ['nullable'],
                'create_user' => ['nullable'],
                'passport' => ['string', 'nullable'],
                'business_partner_id' => ['nullable'],
                'actor_id' => ['nullable'],

            ]);

            // Validate the request data
            if ($partner_validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $partner_validator->errors(),
                ], 401);
            }

            // Update lead attributes with validated data
            $partner_data = $partner_validator->validated();
            $partner_data['created_by'] = auth()->user()->id;

            $payload['partner_data'] = $partner_data;

            // Handle partner type and store data
            $stored_partner = null;
            // dd($partner_data);
            if ($partner_data['actor_id'] == 8) {
                // dd($partner_data ['actor_id']);
                $stored_partner = Partner::store_walkin_customer($payload);
                // $stored_partner['partner_type'] = 'walkin_customer';
            }
            // Uncomment and implement other partner types as needed
            // if ($partner_data['partner_type'] == 'business') {
            //     $stored_partner = Partner::store_business($payload);
            // }
            // if ($partner_data['partner_type'] == 'employee') {
            //     $stored_partner = Partner::store_employee($payload);
            // }

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully.',
                'data' => $stored_partner,
            ]);
        }
    }

    public function driver_order_create(Request $request)
    {
        $payload = [];
        // Validate the request data
        $order_validator = Validator::make($request->all(), [
            // 'order_no' => ['required','unique:orders,order_no'],
            'customer_partner_id' => ['required'],
            // 'business_partner_id' => ['nullable'],
            // 'overall_adult'=>['required'],
            // 'overall_child'=>['required'],
            // 'overall_bags'=>['required'],
            // 'overall_status'=>['required'],
            // 'booking_amount'=>['nullable'],
            // 'final_amount'=>['nullable'],
            'vehicle_class_id' => ['nullable'],
            // order_details validation:

            //'order_id' => ['required'],
            'rate_list_id.*' => ['nullable'],
            // 'rate.*'=>['required'],
            'status.*' => ['required'],
            // 'adult.*'=>['required'],
            // 'child.*'=>['required'],
            // 'bags.*'=>['required'],
            // 'date.*'=>['required'],
            'pickup_time.*' => ['required'],
            // 'checkout_time.*'=>['nullable'],
            // 'is_ac.*'=>['required'],
            'driver_pickup_loc' => ['required'],
            'driver_dropoff_loc' => ['required'],
            'driver_rate' => ['required'],
            'from_loc' => ['nullable'],
            'to_loc' => ['nullable'],
            // 'ride_start_mileage' => ['required'],
            // 'ride_end_mileage' => ['required'],

        ]);

        if ($order_validator->fails()) {
            //    dd($order_validator->errors());
            return response()->json(['errors' => $order_validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $order_data = $order_validator->validated();
        $order_data['created_by'] = auth()->user()->id;

        // $user = Auth::user();
        // $businessPartnerId = $user->partner_id;

        $order_data['overall_status'] = 'pending';
        $order_data['status'] = 'pending';
        $order_data['reason'] = null;
        $user = auth()->user();
        $company = $user->companies->first();
        // $vehicle = Vehicle::where('id', $order_validator['vehicle_id'])
        //     ->where('driver_id', $order_validator['driver_id'])
        //     ->first();

        // if (!$vehicle) {
        //     return response()->json(['error' => 'The selected vehicle is not assigned to the specified driver.'], 400);
        // }
        // $vehicleArray = $vehicle->toArray();

        $latestOrder = Order::where('company_id', $company->id)->latest()->first();

        // Extract and increment the numeric part
        if ($latestOrder) {
            // Extract the numeric part and increment it
            $nextNumber = intval($latestOrder->order_no) + 1;
        } else {
            $nextNumber = 1;
        }

        // Format the next order number to ensure it is 6 digits long
        $nextOrderNo = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // $order_data['overall_adult'] = null;
        // $order_data['overall_child'] = null;
        // $order_data['overall_bags'] = null;
        // $order_data['booking_amount'] = null;
        // $order_data['final_amount'] = null;
        if ($request->has('rate_list_id')) {
            $order_data['rate_list_id'] = $request->input('rate_list_id');
            // $order_data['rate']=$request->rate;
        }

        $payload['order_data'] = $order_data;
        $payload['nextOrderNo'] = $nextOrderNo;

        // dd($nextOrderNo);
        // dd($payload);

        // dd($order_data);

        $order = Order::driver_store_order($payload);
        // dd($order);
        //$order_data = Order::create($order_data);
        // $order_with_partner = Order::with('partner_customer')->find($order->id);



        // if (!empty($order_data['whatsapp_no'])) {
        //     $contacts = [
        //         [
        //             'prefix' => '+92', // Adjust prefix as needed
        //             'postfix' => $order_data['whatsapp_no']
        //         ]
        //     ];
        //     $message = "Your order has been created successfully! Order ID: {$order_data['order_no']}";

        //     $response = Http::post('http://72.255.1.252:9000/api/zaroon', [
        //         'contacts' => $contacts,
        //         'message' => $message
        //     ]);

        //     if ($response->failed()) {
        //         // Log or handle failure
        //         Log::error('Notification failed: ' . $response->body());
        //     }
        // }
        // $orderDetail = $order->orderDetails()->first();

        // if ($orderDetail) {
        //     $order_data['rate_list_id'] = $orderDetail->rate_list_id;

        //     // dd($orderDetail->rate_list_id);
        //     $order_data['rate'] = $orderDetail->driver_rate;
        //     $order_data['driver_id'] = $orderDetail->driver_id;
        // }

        // $order_data['business_partner_id'] = auth()->user()->partner_id;
        // $order_data['order_no'] = $order->order_no;
        // $order_data['name'] = $order->partner_customer->name;
        // if(!isset($order_data['rate_list_id'])){
        //     $order_data['driverbool'] = true;
        // }


        // $this->sendWhatsAppNotification($order_data['business_partner_id'], $order_data);
        // $this->sendWhatsAppNotification($order_data['business_partner_id'], $order_data);
        // $this->sendWhatsAppNotification($order_data['business_partner_id'], $order_data);

        return response()->json(['message' => 'Order create successfully.', 'data' => $order_data], 201);
    }

    // private function sendWhatsAppNotification($businessPartnerId, $orderData)
    // {
    //     if ($orderData['overall_status'] !== 'draft') {
    //         $status = $orderData['status'] ?? 'pending';
    //         $whatsAppController = new WhatsAppController();

    //         // Send the immediate notification to the business partner
    //         $whatsAppController->sendNotification($orderData, $status, $businessPartnerId, null, null );

    //         // Handle the pickup_time to ensure it's a Carbon instance
    //         // if (isset($orderData['pickup_time'])) {
    //         //     // Check if pickup_time is a string or a Carbon instance
    //         //     if (is_string($orderData['pickup_time'])) {
    //         //         $pickupTime = \Carbon\Carbon::parse($orderData['pickup_time']);
    //         //     } elseif ($orderData['pickup_time'] instanceof \Carbon\Carbon) {
    //         //         $pickupTime = $orderData['pickup_time'];
    //         //     } else {
    //         //         // Handle the case where pickup_time might be an array or other format
    //         //         // Assuming the first value is the pickup time in case of an array
    //         //         $pickupTime = \Carbon\Carbon::parse($orderData['pickup_time'][0]);
    //         //     }

    //         //     // Subtract 24 hours for scheduling
    //         //     $scheduleTime = $pickupTime->subHours(24);

    //         //     // Schedule the notification for the customer partner
    //         //     $whatsAppController->sendNotification(null, $orderData['customer_partner_id'], null, $orderData, $status, $scheduleTime);
    //         }
    //     }

    public function driver_order_update(Request $request, $order)
    {
        $payload = [];
        $order = Order::find($order);
        // dd($order);
        // Validate the request data
        $order_validator = Validator::make($request->all(), [
            // 'order_no' => ['required','unique:orders,order_no'],
            'customer_partner_id' => ['required'],
            // 'business_partner_id' => ['nullable'],
            // 'overall_adult'=>['required'],
            // 'overall_child'=>['required'],
            // 'overall_bags'=>['required'],
            // 'overall_status'=>['required'],
            // 'booking_amount'=>['nullable'],
            // 'final_amount'=>['nullable'],
            // order_details validation:

            //'order_id' => ['required'],
            // 'rate_list_id.*' => ['required'],
            // 'rate.*'=>['required'],
            'status.*' => ['required'],
            // 'adult.*'=>['required'],
            // 'child.*'=>['required'],
            // 'bags.*'=>['required'],
            // 'date.*'=>['required'],
            // 'pickup_time.*'=>['required'],
            // 'checkout_time.*'=>['nullable'],
            // 'is_ac.*'=>['required'],
            'driver_pickup_loc' => ['required'],
            'driver_dropoff_loc' => ['required'],
            'driver_rate' => ['required'],

        ]);

        if ($order_validator->fails()) {
            //    dd($order_validator->errors());
            return response()->json(['errors' => $order_validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $order_data = $order_validator->validated();
        $order_data['created_by'] = auth()->user()->id;
        $order_data['overall_status'] = 'pending';
        $order_data['status'] = 'pending';
        $order_data['reason'] = null;

        // $latestOrder = Order::latest()->first();

        // // Extract and increment the numeric part
        // if ($latestOrder) {
        //     // Extract the numeric part and increment it
        //     $nextNumber = intval($latestOrder->order_no) + 1;
        // } else {
        //     $nextNumber = 1;
        // }

        // // Format the next order number to ensure it is 6 digits long
        // $nextOrderNo = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // $order_data['overall_adult'] = null;
        // $order_data['overall_child'] = null;
        // $order_data['overall_bags'] = null;
        // $order_data['booking_amount'] = null;
        // $order_data['final_amount'] = null;

        $payload['order_data'] = $order_data;
        // $payload['nextOrderNo'] = $nextOrderNo;
        $payload['order'] = $order;

        // dd($payload);
        $order = Order::update_driver_store_order($payload);

        //$order_data = Order::create($order_data);
        // $order_with_partner = Order::with('partner_customer')->find($order->id);

        return response()->json(['message' => 'Order update successfully.', 'data' => $order_data], 201);
    }
    // public function update_ride_request(Request $request, $id)
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Ensure the user is authenticated and has a partner_id
    //     if (!$user || !$user->partner_id) {
    //         return response()->json(['error' => 'Unauthorized or missing partner ID'], 401);
    //     }

    //     // Validate the request data
    //     $validator = Validator::make($request->all(), [
    //         'name' => ['required', 'string'],
    //         'phone_no' => ['string'],
    //         'whatsapp_no' => ['nullable'],
    //         'cnic' => ['required', 'string'],
    //         'passport' => ['required'],
    //         'pickup_location' => ['required'],
    //         'drop_location' => ['required'],
    //         'driver_rate' => ['required'],
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     // Find the ride request by ID
    //     $rideRequest = OrderDetail::find($id);

    //     // Ensure the ride request exists
    //     if (!$rideRequest) {
    //         return response()->json(['error' => 'Ride request not found'], 404);
    //     }

    //     // Update the ride request
    //     $rideRequest->adult = $request->input('adult');
    //     $rideRequest->child = $request->input('child');
    //     $rideRequest->bags = $request->input('bags');
    //     $rideRequest->pickup_time = $request->input('pickup_time');
    //     $rideRequest->pickup_location = $request->input('pickup_location');
    //     $rideRequest->drop_location = $request->input('drop_location');
    //     $rideRequest->driver_rate = $request->input('driver_rate');
    //     $rideRequest->date = $request->input('date');
    //     $rideRequest->is_ac = $request->input('is_ac');
    //     $rideRequest->order_id = $request->input('order_id');
    //     $rideRequest->rate_list_id = $request->input('rate_list_id');
    //     $rideRequest->driver_id = $request->input('driver_id');
    //     $rideRequest->status = $request->input('status', $rideRequest->status); // Default to current status if not provided
    //     $rideRequest->save();

    //     // Return the updated ride request as a JSON response
    //     return response()->json([
    //         'message' => 'Ride request updated',
    //         'ride_request' => $rideRequest,
    //     ]);
    // }

    public function getWalkinCustomers(Request $request)
    {
        // Fetch partners whose partner_type is "walkin customer"
        $walkinCustomers = Partner::where('actor_id', 8)->get();

        return response()->json(['walkin_customers' => $walkinCustomers]);
    }

    public function getAvailableVehiclesAjax(Request $request)
    {

        // Get the start and end times for the order
        $date = $request->input('date');
        $time = $request->input('time');
        $rate_list = $request->input('rate_list');
        // dd($rate_list);
        if ($rate_list) {
            $estimatedMins = RateList::where('id', $rate_list)->first();
            // dd($estimatedMins);
            $estimatedMins = (int) $estimatedMins->estimated_time;
        } else {
            $estimatedMins = 0;
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
            ->where('is_status', 'active')
            ->get();
        $formattedVehicles = $model_vehicles->map(function ($vehicle) use ($estimatedMins) {
            return [
                'id' => $vehicle->id, // Vehicle ID
                'model_name' => $vehicle->vehicleModel->name ?? 'Unknown', // Vehicle Model Name (check for null)
                'vehicle_no' => $vehicle->vehicle_no,
                'driver_id' => $vehicle->driver_id,
                'estimated_min' => $estimatedMins,
            ];
        });
        // dd($formattedVehicles);
        return response()->json([
            'model_vehicles' => $formattedVehicles,
        ]);
    }
     public function api_approvedRides(){
        $company_id = auth()->user()->active_company();
        $approvedRides = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', $company_id)
            ->where('status', 'approved')
            ->with('order','typeOfLoad', 'unit', 'order.partner_customer', 'order.business_partner', 'rate_list.route','driver','vehicle.vehicleModel.vehicleClass','vehicle.vehicleModel.carCompany')
            ->get();

        return response()->json([
            'approved_rides' => $approvedRides,
        ]);
    }

    public function api_incompletedRides(){
        $company_id = auth()->user()->active_company();
        $incompletedRides = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', $company_id)
            ->where('status', 'incomplete')
            ->with('order','typeOfLoad', 'unit', 'order.partner_customer', 'order.business_partner', 'rate_list.route','driver','vehicle.vehicleModel.vehicleClass','vehicle.vehicleModel.carCompany')
            ->get();
            return response()->json([
                'incompleted_rides' => $incompletedRides,
            ]);

    }
    public function api_completedRides(){
        $company_id = auth()->user()->active_company();
        $completedRides = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', $company_id)
            ->where('status', 'completed')
            ->with('order','typeOfLoad', 'unit', 'order.partner_customer', 'order.business_partner', 'rate_list.route','driver','vehicle.vehicleModel.vehicleClass','vehicle.vehicleModel.carCompany')
            ->get();
            return response()->json([
                'completed_rides' => $completedRides,
            ]);
    }
    public function api_cancelledRides(){
        $company_id = auth()->user()->active_company();
        $cancelledRides = OrderDetail::whereHas('order', function ($query) {
            $query->where('overall_status', 'approved');
        })->where('company_id', $company_id)
            ->where('status', 'cancelled')
            ->with('order','typeOfLoad', 'unit', 'order.partner_customer', 'order.business_partner', 'rate_list.route','driver','vehicle.vehicleModel.vehicleClass','vehicle.vehicleModel.carCompany')
            ->get();
            return response()->json([
                'cancelled_rides' => $cancelledRides,
            ]);
    }

}
