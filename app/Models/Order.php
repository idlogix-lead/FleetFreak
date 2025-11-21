<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Order
 *
 * @property $id
 * @property $order_no
 * @property $customer_partner_id
 * @property $business_partner_id
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property Partner $partner
 * @property User $user
 * @property Partner $partner
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Order extends BaseModel
{

    // static $rules = [
    //         'order_no' => 'required|string',
    //         'customer_partner_id' => 'required',
    //         'business_partner_id' => 'required',
    // ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['order_no', 'customer_partner_id', 'business_partner_id'];
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_Class()
    {
        return $this->belongsTo(\App\Models\VehicleClass::class, 'vehicle_class_id', 'id');
    }
    public function vehicleModel()
    {
        return $this->belongsTo(\App\Models\VehicleModel::class, 'vehicle_model_id', 'id');
    }

    public function partner_business()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'business_partner_id', 'id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // public function vehicle()
    // {
    //     return $this->hasOneThrough(Vehicle::class, OrderDetail::class, 'order_id', 'id', 'id', 'vehicle_id');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner_customer()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'customer_partner_id', 'id');
    }
    public function wareHouse()
    {
        return $this->belongsTo(\App\Models\WareHouse::class, 'warehouse_id', 'id');
    }
    public function priceList()
    {
        return $this->belongsTo(\App\Models\PriceList::class, 'price_list_id', 'id');
    }
    public function business_partner()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'business_partner_id', 'id');
    }
    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function paymentLines()
    {
        return $this->hasMany(PaymentLine::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comapny()
    {
        return $this->belongsTo(Company::class);

    }
    public function scopecheckGlobal($query, $role_module_id)
    {
        return $query->when(!auth()->user()->role_module_permission_via_action($role_module_id, 'global')->permission, function ($query) {
            $query->where('created_by', auth()->user()->id);
        });
        // return $this;
    }
    public static function totalOrders()
    {
        return self::where('overall_status', 'approved')->orWhere('overall_status', 'pending')->count();
    }

    public static function store_order($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);
        $user = auth()->user();
        $company = auth()->user()->active_company();

        $order = Order::create([
            'order_no' => $order_data['order_no'],
            'trip_type' => $order_data['trip_type'],
            'direction' => $order_data['direction'] ?? null,
            'description' => $order_data['description'] ?? null,
            'customer_partner_id' => $order_data['customer_partner_id'],
            'business_partner_id' => $order_data['business_partner_id'],
            'vehicle_class_id' => $order_data['vehicle_class_id'] ?? null,
            'vehicle_model_id' => $order_data['vehicle_model_id'] ?? null,
            'trip_type' => $order_data['trip_type'] ?? null,
            'overall_adult' => $order_data['overall_adult'] ?? null,
            'overall_child' => $order_data['overall_child'] ?? null,
            'overall_bags' => $order_data['overall_bags'] ?? null,
            'overall_status' => $order_data['overall_status'],
            'booking_amount' => $order_data['booking_amount'] ?? null,
            'final_amount' => $order_data['final_amount'],
            'reason' => $order_data['reason'],
            'created_by' => $order_data['created_by'],
            'company_id' => $company,

        ]);

        // Event::createEvent(21, $order->id, $order->order_no, 'created', 'Order created Successfully',null,null,$company);
        // Event::createEvent(21, $order->id, $order->order_no, 'status_changed', 'your order is in ' . $order_data['overall_status'],null,null,$company);

        // $isReturn = $order_data['is_return'] ?? 0;

        foreach ($order_data['rate'] as $key => $rate) {
            // if ($order_data['trip_type'] === 'passenger_trip') {

            //     $rateList = RateList::find($order_data['rate_list_id'][$key]);

            //     $route = Route::find($rateList->route_id);
            // }

            $data = [
                'order_id' => $order->id,
                'rate_list_id' => $order_data['rate_list_id'][$key] ?? null,
                // 'rate' => $order_data['rate'][$key],
                'vehicle_id'=> $order_data['vehicle_id'][$key] ?? null,
                'driver_id'=> $order_data['driver_id'][$key] ?? null,
                'rate' => $rate,
                'status' => $order_data['status'],
                'duration' => $order_data['duration'][$key] ?? null,
                'date' => $order_data['date'][$key],
                'pickup_time' => $order_data['pickup_time'][$key] ?? null,
                'is_ac' => $order_data['is_ac'][$key] ?? 1,
                'with_driver' => $order_data['with_driver'][$key] ?? 1,
                'flight_num' => $order_data['flight_num'][$key] ?? null,
                'airline_name' => $order_data['airline_name'][$key] ?? null,
                'company_id' => $company,
                'weight' => $order_data['weight'][$key] ?? null,
                'unit' => $order_data['unit'][$key] ?? null,
                'type_of_load' => $order_data['type_of_load'][$key] ?? null,
                'end_date' => $order_data['end_date'][$key] ?? null,

                'from_loc' => $order_data['from'][$key] ?? null,
                'to_loc' => $order_data['to'][$key] ?? null,
                'estimated_time' => $order_data['estimated_time'][$key] ?? null,

            ];

            if (isset($order_data['from_api']) && $order_data['from_api'] == true) {

                if ($order_data['trip_type'] === 'passenger_trip' || $order_data['trip_type'] === 'tour_booking') {

                    $rateList = RateList::find($order_data['rate_list_id'][$key]);

                    $route = Route::find($rateList->route_id);
                    
                    $data['from_loc'] = $route->fromLoc->name;

                    $data['to_loc'] = $route->toLoc->name;
                 }
                     // $data['adult'] = $adult;
                    // dd($order_data);
                    // $data['adult'] = $order_data['adult'][$key] ?? null;

                    // // 'child' => $order_data['child'][$key],
                    // $data['bags'] = $order_data['bags'][$key] ?? null;
                    $data['adult'] = $order_data['overall_adult'] ?? $order_data['adult'][$key] ?? null;
                    $data['child'] = $order_data['overall_child'] ?? $order_data['child'][$key] ?? null;
                    $data['bags'] = $order_data['overall_bags'] ?? $order_data['bags'][$key] ?? null;
            } else {
                $data['adult'] = $order_data['overall_adult'] ?? $order_data['adult'][$key] ?? null;
                $data['bags'] = $order_data['overall_bags'] ?? $order_data['bags'][$key] ?? null;

                $data['from_loc'] = $order_data['from'][$key] ?? null;

                $data['to_loc'] = $order_data['to'][$key] ?? null;

            }

            if (isset($extra_fields)) {
                $data['vehicle_id'] = $order_data['vehicle_id'][$key];
                $data['driver_id'] = $order_data['driver_id'][$key];
            }
            // from rental vehicle controller extra field of end_date
            // if (isset($from_rental_vehicle)){
            //     $data['end_date'] = $order_data['end_date'][$key];
            // }

            // Create the order detail

            $order_detail = OrderDetail::create($data);
            $title = "Ride" . $order->order_no . " Ride Schedule";
            $description = "Ride is created  for this date " . $order_detail->date;
            // create notifications
            $notifications = [];
            if ($order->customer_partner_id) {
                $notifications[] = [
                    'receiver_partner_id' => $order->customer_partner_id,
                    'sender_id' => $user->id,
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
            $admin_user = UserCompany::where('company_id', $data['company_id'])->whereHas('user', function ($user) {
                return $user->where('is_company_admin', 1);
            })->first();
            if ($admin_user) {
                $notifications[] = [
                    'receiver_id' => $admin_user->user_id,
                    'sender_id' => $user->id,
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
            if ($order->overall_status != 'draft') {
                // dd('hi');
                Event::createEvent(20, $order_detail->id, $order_detail->order_id, 'order_created', $title, $description, $action_details3 = null, $company, $order_detail->date, $notifications);
                // dd('kjhhjk');
            }

            // Event::createEvent($table->id, $data['vehicle_id'], $vehicle->registration_no, 'planed_maintenance', $title, $description, $action_details3 = null,$data['company_id'], $date, $notifications);/

            // $sender_id = auth()->user()->id;
            // for notifications
            // if ($order_data['overall_status'] == 'pending') {
            //     $user_agent = User::where('partner_id', $order_data['business_partner_id'])->pluck('id')->first();
            //     if (auth()->user()->id = 2) {
            //         Notification::notify('Pending Orders', 'This order has been created by admin for you', $user_agent, 0, 0, 0, 0, 0, $sender_id, $order->id);
            //     } elseif (auth()->user()->id = 35) {
            //         Notification::notify('Pending Orders', 'You have a order in pending from Hassan Farooq', 2, 0, 0, 0, 0, 0,$sender_id,$order->id);
            //         Notification::notify('Pending Orders', 'Your Order has been sent for admin approval', 35, 0, 0, 0, 0, 0,$sender_id,$order->id);
            //     } elseif (auth()->user()->id = 36) {
            //         Notification::notify('Pending Orders', 'You have a order in pending from Umer Farooq', 2, 0, 0, 0, 0, 0,$sender_id,$order->id);
            //         Notification::notify('Pending Orders', 'Your Order has been sent for admin approval', 36, 0, 0, 0, 0, 0,$sender_id,$order->id);

            //     }

            // Notification::create([
            //     'title'=>'pending orders',
            //     'detail'=>'You Have a Pending Order From'.''.auth()->user()->name,
            //     'receiver_id'=>auth()->user()->id,

            // ]);
            // }

        }
    }

    public static function store_draft($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);
        $company = auth()->user()->active_company();
        $order = Order::create([
            'order_no' => $order_data['order_no'],
            'trip_type' => $order_data['trip_type'],
            'direction' => $order_data['direction'] ?? null,
            'description' => $order_data['description'] ?? null,
            'customer_partner_id' => $order_data['customer_partner_id'],
            'business_partner_id' => $order_data['business_partner_id'],
            'vehicle_class_id' => $order_data['vehicle_class_id'] ?? null,
            'vehicle_model_id' => $order_data['vehicle_model_id'] ?? null,
            'trip_type' => $order_data['trip_type'] ?? null,
            'overall_adult' => $order_data['overall_adult'] ?? null,
            'overall_child' => $order_data['overall_child'] ?? null,
            'overall_bags' => $order_data['overall_bags'] ?? null,
            'overall_status' => $order_data['overall_status'],
            'booking_amount' => $order_data['booking_amount'] ?? null,
            'final_amount' => $order_data['final_amount'],
            'reason' => $order_data['reason'],
            'created_by' => $order_data['created_by'],
            'company_id' => $company,
        ]);

        // Event::createEvent(21, $order->id, $order->order_no, 'created', 'Order saved as draft successfully.');
        // Event::createEvent(21, $order->id, $order->order_no, 'status_changed', 'Your order is saved as ' . $order_data['overall_status']);

        if (isset($order_data['rate']) && is_array($order_data['rate'])) {
            foreach ($order_data['rate'] as $key => $rate) {

                // $rateList = RateList::find($order_data['rate_list_id'][$key]);

                // $route = Route::find($rateList->route_id);

                $data = [
                    'order_id' => $order->id,
                    'rate_list_id' => $order_data['rate_list_id'][$key] ?? null,
                    // 'rate' => $order_data['rate'][$key],
                    'vehicle_id'=> $order_data['vehicle_id'][$key] ?? null,
                    'driver_id'=> $order_data['driver_id'][$key] ?? null,
                    'rate' => $rate,
                    'status' => $order_data['status'],
                    'duration' => $order_data['duration'][$key] ?? null,
                    'date' => $order_data['date'][$key],
                    'pickup_time' => $order_data['pickup_time'][$key] ?? null,
                    'is_ac' => $order_data['is_ac'][$key] ?? 1,
                    'with_driver' => $order_data['with_driver'][$key] ?? 1,
                    'flight_num' => $order_data['flight_num'][$key] ?? null,
                    'airline_name' => $order_data['airline_name'][$key] ?? null,
                    'company_id' => $company,
                    'weight' => $order_data['weight'][$key] ?? null,
                    'unit' => $order_data['unit'][$key] ?? null,
                    'type_of_load' => $order_data['type_of_load'][$key] ?? null,
                    'end_date' => $order_data['end_date'][$key] ?? null,
    
                    'from_loc' => $order_data['from'][$key] ?? null,
                    'to_loc' => $order_data['to'][$key] ?? null,
    


                ];
                if (isset($order_data['from_api']) && $order_data['from_api'] == true) {

                    // if ($order_data['trip_type'] === 'passenger_trip') {
                    // $rateList = RateList::find($order_data['rate_list_id'][$key]);

                    // $route = Route::find($rateList->route_id);
                    // $data['from_loc'] = $route->fromLoc->name;

                    // $data['to_loc'] = $route->toLoc->name;
                    // }

                    if ($order_data['trip_type'] === 'passenger_trip' || $order_data['trip_type'] === 'tour_booking') {

                        $rateList = RateList::find($order_data['rate_list_id'][$key]);
    
                        $route = Route::find($rateList->route_id);
                        
                        $data['from_loc'] = $route->fromLoc->name;
    
                        $data['to_loc'] = $route->toLoc->name;
                    }
                         // $data['adult'] = $adult;
                        // dd($order_data);
                        // $data['adult'] = $order_data['adult'][$key] ?? null;
    
                        // // 'child' => $order_data['child'][$key],
                        // $data['bags'] = $order_data['bags'][$key] ?? null;
                        $data['adult'] = $order_data['overall_adult'] ?? $order_data['adult'][$key] ?? null;
                        $data['child'] = $order_data['overall_child'] ?? $order_data['child'][$key] ?? null;
                        $data['bags'] = $order_data['overall_bags'] ?? $order_data['bags'][$key] ?? null;



                } else {

                    $data['from_loc'] = $order_data['from'][$key] ?? null;

                    $data['to_loc'] = $order_data['to'][$key] ?? null;

                }

                if (isset($extra_fields)) {
                    $data['vehicle_id'] = $order_data['vehicle_id'][$key] ?? null;
                    $data['driver_id'] = $order_data['driver_id'][$key] ?? null;
                }
                // dd($data);
                $company = auth()->user()->active_company();

                $order_detail = OrderDetail::create($data);
                // Event::createEvent(20, $order_detail->id, $order_detail->order_id, 'created', 'Order detail saved as draft successfully.', null, null, $company);
            }
        }
    }

    public static function store_payment($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        $payment_header = PaymentHeader::create([
            'payment_no' => $payment_no,
            'agent_id' => $payment_data['agent_id'],
            'customer_id' => $payment_data['customer_id'],
            'date' => $date,
            'total_amount' => $payment_data['amount'],
            'description' => $description,
            'status' => $payment_data['status'],
            'created_by' => $created_by,
            'company_id' => $company_id,
            'type' => 'receipt',

        ]);
        PaymentLine::create([
            'order_id' => $payment_data['order_no'],
            'order_detail_id' => $payment_data['order_detail_no'],
            'payment_header_id' => $payment_header->id,
            'total_amount' => $payment_data['amount'],
            'transaction_date' => $date,
            'description' => $description,
            'payment_type' => 'cash',
            'reference_no' => null,
            'company_id' => $company_id,

        ]);

        OrderDetail::where('id', $payment_data['order_detail_no'])->update(['status' => $payment_data['status']]);
        // $debit_account = Account::where('company_id',$company_id)->where('name', 'Cash Account')->first();
        // $credit_account = Account::where('company_id',$company_id)->where('name', 'Accounts Receivable')->first();
        // $currency = User::current_currency();
        // AccountTransaction::createTransaction($date,$company_id,$debit_account->id,$credit_account->id,$payment_data['amount'],$currency->id,$payment_data['order_no'],$payment_data['order_detail_no'],null,21);
        $debit_account = Account::where('company_id', $company_id)->where('name', 'Cash Account')->first();
        $credit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Receivable')->first();
        $currency = User::current_currency();
        AccountTransaction::createTransaction($date, $company_id, $debit_account->id, $credit_account->id,1, $payment_data['amount'], $currency->id, $payment_data['order_no'], $payment_data['order_detail_no'], null, 21,business_partner_id:$payment_data['agent_id']);

        // Event::createEvent(21,$order->id,$order->order_no,'created','Order created Successfully');
        // Event::createEvent(21,$order->id,$order->order_no,'status_changed','your order is in '.$order_data['overall_status']);

        // Event::createEvent(20,$order_detail->id,$order_detail->order_id,'created','Order Created Successfully');

    }
    public static function store_agent_payment($payload)
    {
        // $company_id = auth()->user()->companies;
        // dd($company_id);
        $company_id = auth()->user()->active_company();

        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        $header = PaymentHeader::create([
            'payment_no' => $payment_no,
            'agent_id' => $payment_window_data['agent_id'],
            'date' => $payment_window_data['date'],
            'total_amount' => $payment_window_data['amount'],
            'description' => $payment_window_data['description'],
            'created_by' => $created_by,
            'status' => $payment_window_data['status'],
            'company_id' => $company_id,
            'type' => 'payment',

        ]);
        if ($payment_window_data['status'] == 'paid') {
            $debit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Payable')->first();
            $credit_account = Account::where('company_id', $company_id)->where('name', 'Cash Account')->first();
            $currency = User::current_currency();
            AccountTransaction::createTransaction($header['date'], $company_id, $debit_account->id, $credit_account->id,1, $header['total_amount'], $currency->id, $header->id, null, null, 24,business_partner_id:$payment_window_data['agent_id']);
        }

        // foreach ($payment_window_data['order_detail_no'] as $key => $order_detail_no) {
        //     $data = [
        //         'order_id' => $payment_window_data['order_id'][$key],
        //         'order_detail_id' => $order_detail_no,
        //         'payment_header_id' => $payment_header->id,

        //         'total_amount' => $payment_window_data['amount'][$key],
        //         'transaction_date' => $payment_window_data['date'],
        //         'description' => $payment_window_data['description'],
        //         'payment_type' => 'cash',
        //         'reference_no' => null,
        //         'company_id' => $company_id,

        //     ];

        //     OrderDetail::where('id', $order_detail_no)->update(['status' => $payment_window_data['action']]);
        //     // Create the order detail

        //     PaymentLine::create($data);
        // $debit_account = Account::where('company_id',$company_id)->where('name', 'Cash Account')->first();
        // $credit_account = Account::where('company_id',$company_id)->where('name', 'Accounts Receivable')->first();
        // $currency = User::current_currency();
        // AccountTransaction::createTransaction($payment_window_data['date'],$company_id,$debit_account->id,$credit_account->id,$payment_window_data['amount'][$key],$currency->id,$payment_window_data['order_id'][$key],$order_detail_no,null,21);

        // Event::createEvent(20,$order_detail->id,$order_detail->order_id,'created','Order Created Successfully');

        // }

    }
    public static function update_agent_payment($payload)
    {
        $company_id = auth()->user()->active_company();

        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        $header->update([
            // 'payment_no' => $payment_no,
            'agent_id' => $payment_window_data['agent_id'],
            'date' => $payment_window_data['date'],
            'total_amount' => $payment_window_data['amount'],
            'description' => $payment_window_data['description'],
            'updated_by' => $updated_by,
            'status' => $payment_window_data['status'],
            'company_id' => $company_id,

        ]);
        if ($payment_window_data['status'] == 'paid') {
            $debit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Payable')->first();
            $credit_account = Account::where('company_id', $company_id)->where('name', 'Cash Account')->first();
            $currency = User::current_currency();
            AccountTransaction::createTransaction($header['date'], $company_id, $debit_account->id, $credit_account->id,1, $header['total_amount'], $currency->id, $header->id, null, null, 24,business_partner_id:$payment_window_data['agent_id']);
        }

        // foreach ($payment_window_data['order_detail_no'] as $key => $order_detail_no) {
        //     // Check if order_detail_no exists in order_details table
        //     $orderDetail = OrderDetail::find($order_detail_no);

        //     if ($orderDetail) {
        //         $data = [
        //             'order_id' => $payment_window_data['order_id'][$key],
        //             'order_detail_id' => $order_detail_no,
        //             'total_amount' => $payment_window_data['amount'][$key],
        //             'transaction_date' => $payment_window_data['date'],
        //             'description' => $payment_window_data['description'],
        //             'payment_type' => 'cash',
        //             'reference_no' => null,
        //         ];

        //         // Update the order detail status
        //         $orderDetail->update(['status' => $payment_window_data['action']]);

        //         // Update the payment line
        //         PaymentLine::where('payment_header_id', $header->id)
        //             ->where('order_detail_id', $order_detail_no) // Ensure the correct payment line is being updated
        //             ->update($data);
        //     } else {
        //         // Handle the case where order_detail_no does not exist in order_details table
        //         // Log an error, throw an exception, or take other appropriate action
        //         throw new Exception("OrderDetail with id $order_detail_no does not exist.");
        //     }
        // }
    }
    public static function store_window_payment($payload)
    {
        // $company_id = auth()->user()->companies;
        // dd($company_id);
        $company_id = auth()->user()->active_company();

        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        $payment_header = PaymentHeader::create([
            'payment_no' => $payment_no,
            // 'agent_id' => $payment_window_data['agent_id'][0],
            'customer_id' => $payment_window_data['customer_id'][0],
            'date' => $payment_window_data['date'],
            'total_amount' => $payment_window_data['total_amount'],
            'description' => $payment_window_data['description'],
            'created_by' => $created_by,
            'status' => $payment_window_data['action'],
            'company_id' => $company_id,
            'type' => 'receipt',

        ]);

        foreach ($payment_window_data['order_detail_no'] as $key => $order_detail_no) {
            $data = [
                'order_id' => $payment_window_data['order_id'][$key],
                'order_detail_id' => $order_detail_no,
                'payment_header_id' => $payment_header->id,

                'total_amount' => $payment_window_data['amount'][$key],
                'transaction_date' => $payment_window_data['date'],
                'description' => $payment_window_data['description'],
                'payment_type' => 'cash',
                'reference_no' => null,
                'company_id' => $company_id,

            ];

            OrderDetail::where('id', $order_detail_no)->update(['status' => $payment_window_data['action']]);
            $order_detail = OrderDetail::where('id', $order_detail_no)->first();
            // Create the order detail
            PaymentLine::create($data);
            // $debit_account = Account::where('company_id',$company_id)->where('name', 'Cash Account')->first();
            // $credit_account = Account::where('company_id',$company_id)->where('name', 'Accounts Receivable')->first();
            // $currency = User::current_currency();
            // AccountTransaction::createTransaction($payment_window_data['date'],$company_id,$debit_account->id,$credit_account->id,$payment_window_data['amount'][$key],$currency->id,$payment_window_data['order_id'][$key],$order_detail_no,null,21);

            // Event::createEvent(20,$order_detail->id,$order_detail->order_id,'created','Order Created Successfully');

        }
        $debit_account = Account::where('company_id', $company_id)->where('name', 'Cash Account')->first();
        $credit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Receivable')->first();
        $currency = User::current_currency();
        AccountTransaction::createTransaction($payment_window_data['date'], $company_id, $debit_account->id, $credit_account->id,1, $payment_window_data['total_amount'], $currency->id, $payment_window_data['order_id'][$key], null, null, 21,business_partner_id:$order_detail->order->business_partner_id);

    }
    public static function update_window_payment($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        $header->update([
            'payment_no' => $payment_no,
            'agent_id' => $payment_window_data['agent_id'][0],
            'customer_id' => $payment_window_data['customer_id'][0],
            'date' => $payment_window_data['date'],
            'total_amount' => $payment_window_data['total_amount'],
            'description' => $payment_window_data['description'],
            'updated_by' => $updated_by,
            'status' => $payment_window_data['action'],
        ]);

        foreach ($payment_window_data['order_detail_no'] as $key => $order_detail_no) {
            // Check if order_detail_no exists in order_details table
            $orderDetail = OrderDetail::find($order_detail_no);

            if ($orderDetail) {
                $data = [
                    'order_id' => $payment_window_data['order_id'][$key],
                    'order_detail_id' => $order_detail_no,
                    'total_amount' => $payment_window_data['amount'][$key],
                    'transaction_date' => $payment_window_data['date'],
                    'description' => $payment_window_data['description'],
                    'payment_type' => 'cash',
                    'reference_no' => null,
                ];

                // Update the order detail status
                $orderDetail->update(['status' => $payment_window_data['action']]);

                // Update the payment line
                PaymentLine::where('payment_header_id', $header->id)
                    ->where('order_detail_id', $order_detail_no) // Ensure the correct payment line is being updated
                    ->update($data);
            } else {
                // Handle the case where order_detail_no does not exist in order_details table
                // Log an error, throw an exception, or take other appropriate action
                throw new Exception("OrderDetail with id $order_detail_no does not exist.");
            }
        }
    }

    public static function driver_store_order($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // Get the authenticated user
        $user = Auth::user();
        // dd($user);
        $company_id = auth()->user()->active_company() ?? null;
        // dd($company_id);

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Invalid Driver'], 401);
        }

        // Get the driver's partner ID
        $driverPartnerId = $user->partner_id;

        // dd($driverPartnerId);

        $vehicle = Vehicle::where('driver_id', $driverPartnerId)->first();
        // dd($vehicle);
        if (!$vehicle) {
            return response()->json(['error' => 'No vehicle assigned to this driver'], 400);
        }
        $vehicleclassId = $vehicle->vehicleModel->vehicle_class_id;
        $vehiclemodelId = $vehicle->vehicle_model_id;
        $vehicleId = $vehicle->id;
        // dd($vehicleId);

        //$order = new Order();

        // Create a new order instance
        // $order = new Order();
        // $order->order_no = ;
        $bookingAmount = $order_data['rate'] ?? $order_data['driver_rate'] ?? null;

        $order = Order::create([
            'order_no' => $nextOrderNo,
            'customer_partner_id' => $order_data['customer_partner_id'],
            'business_partner_id' => $driverPartnerId,
            'overall_adult' => $order_data['overall_adult'] ?? null,
            'overall_child' => $order_data['overall_child'] ?? null,
            'overall_bags' => $order_data['overall_bags'] ?? null,
            'overall_status' => $order_data['overall_status'],
            'booking_amount' => $bookingAmount,
            'final_amount' => $order_data['final_amount'] ?? null,
            'reason' => $order_data['reason'] ?? null,
            'created_by' => $order_data['created_by'],
            'vehicle_class_id' => $vehicleclassId,
            'vehicle_model_id' => $vehiclemodelId,
            'company_id' => $company_id,
        ]);
        // $customer_partner = Partner::with('customer_partner_id')->find($customer_partner_id);

        // if (!$customer_partner) {
        //     throw new \Exception("Customer partner not found with ID: $customer_partner_id");
        // }

         $order_detail =OrderDetail::create([
            'order_id' => $order->id,
            'rate_list_id' => $order_data['rate_list_id'] ?? null,
            'rate' => $order_data['rate'] ?? null,
            'status' => $order_data['status'] ?? null,
            'adult' => $order_data['adult'] ?? null,
            'child' => $order_data['child'] ?? null,
            'bags' => $order_data['bags'] ?? null,
            'date' => now(),
            'pickup_time' => Carbon::now()->format('H:i:s'),
            'vehicle_id' => $vehicleId,
            // 'created_by' => $order_data['created_by']?? null,
            'driver_id' => $driverPartnerId,
            'driver_pickup_loc' => $order_data['driver_pickup_loc'],
            'driver_dropoff_loc' => $order_data['driver_dropoff_loc'],
            'driver_rate' => $order_data['driver_rate'],
            'from_loc' => $order_data['driver_pickup_loc'],
            'to_loc' => $order_data['driver_dropoff_loc'],
            'company_id' => $company_id,

        ]);

        // Create the order detail

        // OrderDetail::create($data);
        $title = "Ride" . $order->order_no . " Ride Schedule";
        $description = "Ride is created  for this date " . $order_detail->date;
        // create notifications
        $notifications = [];
        if ($order->customer_partner_id) {
            $notifications[] = [
                'receiver_partner_id' => $order->customer_partner_id,
                'sender_id' => $user->id,
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
            $admin_user = UserCompany::where('company_id', $order_detail['company_id'])->whereHas('user', function ($user) {
            return $user->where('is_company_admin', 1);
        })->first();
        if ($admin_user) {
            $notifications[] = [
                'receiver_id' => $admin_user->user_id,
                'sender_id' => $user->id,
                'calendar' => 1,
                // 'sms' => 1,
                // 'email' => 1,
                // 'whatsapp' => 1,
                // 'fcm_mobile_push' => 1,
                // 'fcm_web_push' => 1,
            ];
        }

        // dd($notifications);
        //  dd($description);
        // dd($order->overall_status);
        if ($order->overall_status != 'draft') {
            // dd('hi');
            Event::createEvent(20, $order_detail->id, $order_detail->order_id, 'driver_order_created', $title, $description, $action_details3 = null, $company_id, $order_detail->date, $notifications);
            // dd('kjhhjk');
        }

        // if (isset($extra_fields)) {
        //     $data['vehicle_id'] = $order_data['vehicle_id'][$key];
        //     $data['driver_id'] = $order_data['driver_id'][$key];
        // }

        // Create the order detail
        // OrderDetail::create($data);
        return $order;

    }
    public static function update_driver_store_order($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user is authenticated and has a partner_id
        if (!$user || !$user->partner_id) {
            return response()->json(['error' => 'Invalid Driver'], 401);
        }

        // Get the driver's partner ID
        $driverPartnerId = $user->partner_id;
        $company_id = auth()->user()->active_company() ?? null;

        $order->update([
            // 'order_no' => $nextOrderNo,
            'customer_partner_id' => $order_data['customer_partner_id'],
            'business_partner_id' => $driverPartnerId,
            'overall_adult' => $order_data['overall_adult'] ?? null,
            'overall_child' => $order_data['overall_child'] ?? null,
            'overall_bags' => $order_data['overall_bags'] ?? null,
            'overall_status' => $order_data['overall_status'],
            'booking_amount' => $order_data['booking_amount'] ?? null,
            'final_amount' => $order_data['final_amount'] ?? null,
            'reason' => $order_data['reason'] ?? null,
            'created_by' => $order_data['created_by'],
            'company_id' => $company_id,
        ]);
        // $customer_partner = Partner::with('customer_partner_id')->find($customer_partner_id);

        // if (!$customer_partner) {
        //     throw new \Exception("Customer partner not found with ID: $customer_partner_id");
        // }

        $data =
            ['order_id' => $order->id,
            'rate_list_id' => $order_data['rate_list_id'] ?? null,
            'rate' => $order_data['rate'] ?? null,
            'status' => $order_data['status'] ?? null,
            'adult' => $order_data['adult'] ?? null,
            'child' => $order_data['child'] ?? null,
            'bags' => $order_data['bags'] ?? null,
            'date' => $order_data['date'] ?? null,
            'pickup_time' => $order_data['pickup_time'] ?? null,
            // 'created_by' => $order_data['created_by']?? null,
            'driver_id' => $driverPartnerId,
            'driver_pickup_loc' => $order_data['driver_pickup_loc'],
            'driver_dropoff_loc' => $order_data['driver_dropoff_loc'],
            'driver_rate' => $order_data['driver_rate'],
            'company_id' => $company_id,
        ];

        // if (isset($extra_fields)) {
        //     $data['vehicle_id'] = $order_data['vehicle_id'][$key];
        //     $data['driver_id'] = $order_data['driver_id'][$key];
        // }

        // Create the order detail
        // OrderDetail::create($data);
        OrderDetail::where('order_id', $order->id)->update($data);
        // return $order;

    }

    public static function update_order($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        $user = auth()->user();

        $company = auth()->user()->active_company();
        // dd($payload);
        $order->update([
            // 'order_no' => $order_data['order_no'],
            // 'order_no' => $order->order_no,
            'trip_type' => $order_data['trip_type'],
            'direction' => $order_data['direction'] ?? null,
            'description' => $order_data['description'] ?? null,
            'customer_partner_id' => $order_data['customer_partner_id'],
            'business_partner_id' => $order_data['business_partner_id'],
            'vehicle_class_id' => $order_data['vehicle_class_id'] ?? null,
            'vehicle_model_id' => $order_data['vehicle_model_id'] ?? null,
            'overall_adult' => $order_data['overall_adult'] ?? null,
            'overall_child' => $order_data['overall_child'] ?? null,
            'overall_bags' => $order_data['overall_bags'] ?? null,
            'overall_status' => $order_data['overall_status'],
            'booking_amount' => $order_data['booking_amount'] ?? null,
            'final_amount' => $order_data['final_amount'],
            'reason' => $order_data['reason'],
            'updated_by' => $order_data['updated_by'],
            'company_id' => $company,
        ]);
        // dd($order->id);
        OrderDetail::where('order_id', $order->id)->delete();

        foreach ($order_data['rate'] as $key => $rate) {
            // if ($order_data['trip_type'] === 'passenger_trip') {

            //     $rateList = RateList::find($order_data['rate_list_id'][$key]);

            //     $route = Route::find($rateList->route_id);
            // }

            $data = [
                'order_id' => $order->id,
                'rate_list_id' => $order_data['rate_list_id'][$key] ?? null,
                // 'rate' => $order_data['rate'][$key],
                'vehicle_id'=> $order_data['vehicle_id'][$key] ?? null,
                'driver_id'=> $order_data['driver_id'][$key] ?? null,
                'rate' => $rate,
                'status' => $order_data['status'],
                'duration' => $order_data['duration'][$key] ?? null,
                'date' => $order_data['date'][$key],
                'pickup_time' => $order_data['pickup_time'][$key] ?? null,
                'is_ac' => $order_data['is_ac'][$key] ?? 1,
                'with_driver' => $order_data['with_driver'][$key] ?? 1,
                'flight_num' => $order_data['flight_num'][$key] ?? null,
                'airline_name' => $order_data['airline_name'][$key] ?? null,
                'company_id' => $company,
                'weight' => $order_data['weight'][$key] ?? null,
                'unit' => $order_data['unit'][$key] ?? null,
                'type_of_load' => $order_data['type_of_load'][$key] ?? null,
                'end_date' => $order_data['end_date'][$key] ?? null,

                'from_loc' => $order_data['from'][$key] ?? null,
                'to_loc' => $order_data['to'][$key] ?? null,
                'estimated_time' => $order_data['estimated_time'][$key] ?? null,


            ];

            if (isset($order_data['from_api']) && $order_data['from_api'] == true) {

                if ($order_data['trip_type'] === 'passenger_trip' || $order_data['trip_type'] === 'tour_booking') {

                    $rateList = RateList::find($order_data['rate_list_id'][$key]);

                    $route = Route::find($rateList->route_id);
                    
                    $data['from_loc'] = $route->fromLoc->name;

                    $data['to_loc'] = $route->toLoc->name;
                 }
                     // $data['adult'] = $adult;
                    // dd($order_data);
                    // $data['adult'] = $order_data['adult'][$key] ?? null;

                    // // 'child' => $order_data['child'][$key],
                    // $data['bags'] = $order_data['bags'][$key] ?? null;
                    $data['adult'] = $order_data['overall_adult'] ?? $order_data['adult'][$key] ?? null;
                    $data['child'] = $order_data['overall_child'] ?? $order_data['child'][$key] ?? null;
                    $data['bags'] = $order_data['overall_bags'] ?? $order_data['bags'][$key] ?? null;
            } else {
                $data['adult'] = $order_data['overall_adult'] ?? $order_data['adult'][$key] ?? null;
                $data['bags'] = $order_data['overall_bags'] ?? $order_data['bags'][$key] ?? null;

                $data['from_loc'] = $order_data['from'][$key] ?? null;

                $data['to_loc'] = $order_data['to'][$key] ?? null;

            }

            if (isset($extra_fields)) {
                $data['vehicle_id'] = $order_data['vehicle_id'][$key];
                $data['driver_id'] = $order_data['driver_id'][$key];
            }
            // from rental vehicle controller extra field of end_date
            // if (isset($from_rental_vehicle)){
            //     $data['end_date'] = $order_data['end_date'][$key];
            // }

            // Create the order detail

            $order_detail = OrderDetail::create($data);
            // $order_detail = OrderDetail::updateOrCreate(
            //             ['order_id' => $order->id], // Search criteria
            //             $data // Data to update or insert
            //         );
            $title = "Ride" . $order->order_no . " Ride Schedule";
            $description = "Ride is created  for this date " . $order_detail->date;
            // create notifications
            $notifications = [];
            if ($order->customer_partner_id) {
                $notifications[] = [
                    'receiver_partner_id' => $order->customer_partner_id,
                    'sender_id' => $user->id,
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
            $admin_user = UserCompany::where('company_id', $data['company_id'])->whereHas('user', function ($user) {
                return $user->where('is_company_admin', 1);
            })->first();
            if ($admin_user) {
                $notifications[] = [
                    'receiver_id' => $admin_user->user_id,
                    'sender_id' => $user->id,
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
            if ($order->overall_status != 'draft') {
                // dd('hi');
                Event::createEvent(20, $order_detail->id, $order_detail->order_id, 'order_created', $title, $description, $action_details3 = null, $company, $order_detail->date, $notifications);
                // dd('kjhhjk');
            }

            // Event::createEvent($table->id, $data['vehicle_id'], $vehicle->registration_no, 'planed_maintenance', $title, $description, $action_details3 = null,$data['company_id'], $date, $notifications);/

            // $sender_id = auth()->user()->id;
            // for notifications
            // if ($order_data['overall_status'] == 'pending') {
            //     $user_agent = User::where('partner_id', $order_data['business_partner_id'])->pluck('id')->first();
            //     if (auth()->user()->id = 2) {
            //         Notification::notify('Pending Orders', 'This order has been created by admin for you', $user_agent, 0, 0, 0, 0, 0, $sender_id, $order->id);
            //     } elseif (auth()->user()->id = 35) {
            //         Notification::notify('Pending Orders', 'You have a order in pending from Hassan Farooq', 2, 0, 0, 0, 0, 0,$sender_id,$order->id);
            //         Notification::notify('Pending Orders', 'Your Order has been sent for admin approval', 35, 0, 0, 0, 0, 0,$sender_id,$order->id);
            //     } elseif (auth()->user()->id = 36) {
            //         Notification::notify('Pending Orders', 'You have a order in pending from Umer Farooq', 2, 0, 0, 0, 0, 0,$sender_id,$order->id);
            //         Notification::notify('Pending Orders', 'Your Order has been sent for admin approval', 36, 0, 0, 0, 0, 0,$sender_id,$order->id);

            //     }

            // Notification::create([
            //     'title'=>'pending orders',
            //     'detail'=>'You Have a Pending Order From'.''.auth()->user()->name,
            //     'receiver_id'=>auth()->user()->id,

            // ]);
            // }

        }

        // foreach ($order_data['rate'] as $key => $rate) {
        //     // if ($order_data['trip_type'] === 'passenger_trip') {

        //     //     $rateList = RateList::find($order_data['rate_list_id'][$key]);

        //     //     $route = Route::find($rateList->route_id);
        //     // }
        //     $data =
        //         [
        //         'order_id' => $order->id,
        //         'rate_list_id' => $order_data['rate_list_id'][$key] ?? null,
        //         'flight_num' => $order_data['flight_num'][$key] ?? null,
        //         'airline_name' => $order_data['airline_name'][$key] ?? null,
        //         'rate' => $rate,
        //         'status' => $order_data['status'], // add key when passing status from frontend

        //         'date' => $order_data['date'][$key],
        //         'end_date' => $order_data['end_date'][$key] ?? null,
        //         'duration' => $order_data['duration'][$key] ?? null,
        //         'pickup_time' => $order_data['pickup_time'][$key] ?? null,
        //         // 'checkout_time'=>$order_data['checkout_time'][$key],
        //         'is_ac' => $order_data['is_ac'][$key] ?? 1,
        //         'with_driver' => $order_data['with_driver'][$key] ?? 1,
        //         'company_id' => $company,
        //         'weight' => $order_data['weight'][$key] ?? null,
        //         'unit' => $order_data['unit'][$key] ?? null,
        //         'type_of_load' => $order_data['type_of_load'][$key] ?? null,
        //         'vehicle_id' => $order_data['vehicle_id'][$key] ?? null,
        //         'driver_id'=> $order_data['driver_id'][$key] ?? null,
        //         // 'from_loc' => $order_data['from'][$key] ?? null,
        //         // 'to_loc' => $order_data['to'][$key] ?? null,

        //     ];
        //     if (isset($order_data['from_api']) && $order_data['from_api'] == true) {
        //         if ($order_data['trip_type'] === 'passenger_trip') {

        //             $rateList = RateList::find($order_data['rate_list_id'][$key]);

        //             $route = Route::find($rateList->route_id);
        //         }

        //         $data['adult'] = $order_data['adult'][$key];
        //         // 'child' => $order_data['child'][$key],
        //         $data['bags'] = $order_data['bags'][$key];

        //         $data['from_loc'] = $route->fromLoc->name;

        //         $data['to_loc'] = $route->toLoc->name;

        //     } else {
        //         $data['adult'] = $order_data['overall_adult'] ?? $order_data['adult'][$key] ?? null;
        //         $data['bags'] = $order_data['overall_bags'] ?? $order_data['bags'][$key] ?? null;

        //         $data['from_loc'] = $order_data['from'][$key] ?? null;

        //         $data['to_loc'] = $order_data['to'][$key] ?? null;

        //     }

        //     if (isset($extra_fields)) {
        //         $data['vehicle_id'] = $order_data['vehicle_id'][$key];
        //         $data['driver_id'] = $order_data['driver_id'][$key];
        //     }

        //     // Create the order detail
        //     // OrderDetail::where('order_id',$order->id)->update($data);
        //     // OrderDetail::create($data);
        //     // Create the order detail

        //     // $order_detail = OrderDetail::where('order_id', $order->id)->updateOrCreate($data);
        //     $order_detail = OrderDetail::updateOrCreate(
        //         ['order_id' => $order->id], // Search criteria
        //         $data // Data to update or insert
        //     );
            
        //     $title = "Ride" . $order->order_no . " Ride Schedule";
        //     $description = "Ride is created  for this date " . $order_detail->date;
        //     // create notifications
        //     $notifications = [];
        //     if ($order->customer_partner_id) {
        //         $notifications[] = [
        //             'receiver_partner_id' => $order->customer_partner_id,
        //             'sender_id' => $user->id,
        //             'calendar' => 1,
        //             // 'sms' => 1,
        //             // 'email' => 1,
        //             // 'whatsapp' => 1,
        //             // 'fcm_mobile_push' => 1,
        //             // 'fcm_web_push' => 1,
        //         ];
        //     }

        //     // if($data['business_partner_id']){
        //     //     $notifications[] = [
        //     //         'receiver_id' => intval($data['business_partner_id']),
        //     //         'sender_id' => $auth_user_id,
        //     //         'calendar' => 1,
        //     //         // 'sms' => 1,
        //     //         // 'email' => 1,
        //     //         // 'whatsapp' => 1,
        //     //         // 'fcm_mobile_push' => 1,
        //     //         // 'fcm_web_push' => 1,
        //     //     ];
        //     // }
        //     $admin_user = UserCompany::where('company_id', $data['company_id'])->whereHas('user', function ($user) {
        //         return $user->where('is_company_admin', 1);
        //     })->first();
        //     if ($admin_user) {
        //         $notifications[] = [
        //             'receiver_id' => $admin_user->user_id,
        //             'sender_id' => $user->id,
        //             'calendar' => 1,
        //             // 'sms' => 1,
        //             // 'email' => 1,
        //             // 'whatsapp' => 1,
        //             // 'fcm_mobile_push' => 1,
        //             // 'fcm_web_push' => 1,
        //         ];
        //     }

        //     // dd($notifications);

        //     // dd($order->overall_status);
        //     if ($order->overall_status != 'draft') {
        //         // dd('hi');
        //         Event::createEvent(20, $order_detail->id, $order_detail->order_id, 'order_created', $title, $description, $action_details3 = null, $company, $order_detail->date, $notifications);

        //     }

        // }

    }

    public static function update_draft($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        $company = auth()->user()->active_company();

        $order->update([
            // 'order_no' => $order_data['order_no'],
            // 'order_no' => $order->order_no,
            'trip_type' => $order_data['trip_type'],
            'direction' => $order_data['direction'] ?? null,
            'description' => $order_data['description'] ?? null,
            'customer_partner_id' => $order_data['customer_partner_id'],
            'business_partner_id' => $order_data['business_partner_id'],
            'vehicle_class_id' => $order_data['vehicle_class_id'] ?? null,
            'vehicle_model_id' => $order_data['vehicle_model_id'] ?? null,
            'overall_adult' => $order_data['overall_adult'] ?? null,
            'overall_child' => $order_data['overall_child'] ?? null,
            'overall_bags' => $order_data['overall_bags'] ?? null,
            'overall_status' => $order_data['overall_status'],
            'booking_amount' => $order_data['booking_amount'] ?? null,
            'final_amount' => $order_data['final_amount'],
            'reason' => $order_data['reason'],
            'updated_by' => $order_data['updated_by'],
            'company_id' => $company,
        ]);
        // dd($order->id);
        OrderDetail::where('order_id', $order->id)->delete();
        if (isset($order_data['rate']) && is_array($order_data['rate'])) {
            foreach ($order_data['rate'] as $key => $rate) {

                // $rateList = RateList::find($order_data['rate_list_id'][$key]);

                // $route = Route::find($rateList->route_id);

                $data = [
                    'order_id' => $order->id,
                    'rate_list_id' => $order_data['rate_list_id'][$key] ?? null,
                    // 'rate' => $order_data['rate'][$key],
                    'vehicle_id'=> $order_data['vehicle_id'][$key] ?? null,
                    'driver_id'=> $order_data['driver_id'][$key] ?? null,
                    'rate' => $rate,
                    'status' => $order_data['status'],
                    'duration' => $order_data['duration'][$key] ?? null,
                    'date' => $order_data['date'][$key],
                    'pickup_time' => $order_data['pickup_time'][$key] ?? null,
                    'is_ac' => $order_data['is_ac'][$key] ?? 1,
                    'with_driver' => $order_data['with_driver'][$key] ?? 1,
                    'flight_num' => $order_data['flight_num'][$key] ?? null,
                    'airline_name' => $order_data['airline_name'][$key] ?? null,
                    'company_id' => $company,
                    'weight' => $order_data['weight'][$key] ?? null,
                    'unit' => $order_data['unit'][$key] ?? null,
                    'type_of_load' => $order_data['type_of_load'][$key] ?? null,
                    'end_date' => $order_data['end_date'][$key] ?? null,
    
                    'from_loc' => $order_data['from'][$key] ?? null,
                    'to_loc' => $order_data['to'][$key] ?? null,
    


                ];
                if (isset($order_data['from_api']) && $order_data['from_api'] == true) {

                    // if ($order_data['trip_type'] === 'passenger_trip') {
                    // $rateList = RateList::find($order_data['rate_list_id'][$key]);

                    // $route = Route::find($rateList->route_id);
                    // $data['from_loc'] = $route->fromLoc->name;

                    // $data['to_loc'] = $route->toLoc->name;
                    // }

                    if ($order_data['trip_type'] === 'passenger_trip' || $order_data['trip_type'] === 'tour_booking') {

                        $rateList = RateList::find($order_data['rate_list_id'][$key]);
    
                        $route = Route::find($rateList->route_id);
                        
                        $data['from_loc'] = $route->fromLoc->name;
    
                        $data['to_loc'] = $route->toLoc->name;
                    }
                         // $data['adult'] = $adult;
                        // dd($order_data);
                        // $data['adult'] = $order_data['adult'][$key] ?? null;
    
                        // // 'child' => $order_data['child'][$key],
                        // $data['bags'] = $order_data['bags'][$key] ?? null;
                        $data['adult'] = $order_data['overall_adult'] ?? $order_data['adult'][$key] ?? null;
                        $data['child'] = $order_data['overall_child'] ?? $order_data['child'][$key] ?? null;
                        $data['bags'] = $order_data['overall_bags'] ?? $order_data['bags'][$key] ?? null;



                } else {

                    $data['from_loc'] = $order_data['from'][$key] ?? null;

                    $data['to_loc'] = $order_data['to'][$key] ?? null;

                }

                if (isset($extra_fields)) {
                    $data['vehicle_id'] = $order_data['vehicle_id'][$key] ?? null;
                    $data['driver_id'] = $order_data['driver_id'][$key] ?? null;
                }
                // dd($data);
                $company = auth()->user()->active_company();
            //   dd($data);
                $order_detail = OrderDetail::create($data);
                // $order_detail = OrderDetail::updateOrCreate(
                //     ['order_id' => $order->id], // Search criteria
                //     $data // Data to update or insert
                // );
                // Event::createEvent(20, $order_detail->id, $order_detail->order_id, 'created', 'Order detail saved as draft successfully.', null, null, $company);
            }
        }

        // foreach ($order_data['adult'] as $key => $adult) {
        //     // $rateList = RateList::find($order_data['rate_list_id'][$key]);
        //     // // dd( $rateList);

        //     // $route = Route::find($rateList->route_id);
        //     // dd($payload);
        //     $data =
        //         [
        //         'order_id' => $order->id,
        //         'rate_list_id' => $order_data['rate_list_id'][$key] ?? null,
        //         'rate' => $order_data['rate'][$key] ?? null,
        //         'status' => $order_data['status'] ?? null,
        //         'adult' => $adult,
        //         'child' => $order_data['child'][$key] ?? null,
        //         'bags' => $order_data['bags'][$key] ?? null,
        //         'date' => $order_data['date'][$key] ?? null,
        //         'pickup_time' => $order_data['pickup_time'][$key] ?? null,
        //         'is_ac' => $order_data['is_ac'][$key] ?? null,
        //         // 'from_loc' => $order_data['from'][$key] ?? null,
        //         // 'to_loc' => $order_data['to'][$key] ?? null,
        //         'flight_num' => $order_data['flight_num'][$key] ?? null,
        //         'airline_name' => $order_data['airline_name'][$key] ?? null,

        //     ];
        //     // dd(isset($order_data['from_api']));

        //     if (isset($order_data['from_api']) && $order_data['from_api'] == true) {

        //         $rateList = RateList::find($order_data['rate_list_id'][$key]);
        //         // dd( $rateList);

        //         $route = Route::find($rateList->route_id);
        //         // dd($order_data);

        //         $data['from_loc'] = $route->fromLoc->name;
        //         $data['to_loc'] = $route->toLoc->name;

        //     } else {

        //         $data['from_loc'] = $order_data['from'][$key] ?? null;

        //         $data['to_loc'] = $order_data['to'][$key] ?? null;

        //     }

        //     if (isset($extra_fields)) {
        //         $data['vehicle_id'] = $order_data['vehicle_id'][$key];
        //         $data['driver_id'] = $order_data['driver_id'][$key];
        //     }

        //     // Create the order detail
        //     // OrderDetail::where('order_id',$order->id)->update($data);
        //     // OrderDetail::create($data);
        //     $order_detail = OrderDetail::updateOrCreate(
        //         ['order_id' => $order->id], // Search criteria
        //         $data // Data to update or insert
        //     );
        // }

    }

    public static function api_update_draft(Order $order, $order_data)
    {
        $order->update([
            'customer_partner_id' => $order_data['customer_partner_id'],
            'business_partner_id' => $order_data['business_partner_id'] ?? $order->business_partner_id,
            'vehicle_class_id' => $order_data['vehicle_class_id'] ?? $order->vehicle_class_id,
            'trip_type' => $order_data['trip_type'] ?? $order->trip_type,
            'overall_adult' => $order_data['overall_adult'] ?? $order->overall_adult,
            'overall_child' => $order_data['overall_child'] ?? $order->overall_child,
            'overall_bags' => $order_data['overall_bags'] ?? $order->overall_bags,
            'overall_status' => 'draft',
            'booking_amount' => $order_data['booking_amount'] ?? $order->booking_amount,
            'final_amount' => $order_data['final_amount'] ?? $order->final_amount,
            'updated_by' => $order_data['updated_by'],
        ]);

        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->update([
                'rate_list_id' => $order_data['rate_list_id'][$key] ?? $orderDetail->rate_list_id,
                'rate' => $order_data['rate'][$key] ?? $orderDetail->rate,
                'status' => 'draft',
                'adult' => $order_data['adult'][$key] ?? $orderDetail->adult,
                'child' => $order_data['child'][$key] ?? $orderDetail->child,
                'bags' => $order_data['bags'][$key] ?? $orderDetail->bags,
                'date' => $order_data['date'][$key] ?? $orderDetail->date,
                'pickup_time' => $order_data['pickup_time'][$key] ?? $orderDetail->pickup_time,
                'is_ac' => $order_data['is_ac'][$key] ?? $orderDetail->is_ac,
                'flight_num' => $order_data['flight_num'][$key] ?? $orderDetail->flight_num,
            ]);
        }
    }
    public static function api_update_order(Order $order, $order_data)
    {
        $order->update([
            'customer_partner_id' => $order_data['customer_partner_id'],
            'business_partner_id' => $order_data['business_partner_id'] ?? $order->business_partner_id,
            'vehicle_class_id' => $order_data['vehicle_class_id'] ?? $order->vehicle_class_id,
            'trip_type' => $order_data['trip_type'] ?? $order->trip_type,
            'overall_adult' => $order_data['overall_adult'] ?? $order->overall_adult,
            'overall_child' => $order_data['overall_child'] ?? $order->overall_child,
            'overall_bags' => $order_data['overall_bags'] ?? $order->overall_bags,
            'overall_status' => $order_data['overall_status'],
            'booking_amount' => $order_data['booking_amount'] ?? $order->booking_amount,
            'final_amount' => $order_data['final_amount'] ?? $order->final_amount,
            'updated_by' => $order_data['updated_by'],
        ]);

        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->update([
                'rate_list_id' => $order_data['rate_list_id'][$key] ?? $orderDetail->rate_list_id,
                'rate' => $order_data['rate'][$key] ?? $orderDetail->rate,
                'status' => $order_data['status'][$key] ?? $orderDetail->status,
                'adult' => $order_data['adult'][$key] ?? $orderDetail->adult,
                'child' => $order_data['child'][$key] ?? $orderDetail->child,
                'bags' => $order_data['bags'][$key] ?? $orderDetail->bags,
                'date' => $order_data['date'][$key] ?? $orderDetail->date,
                'pickup_time' => $order_data['pickup_time'][$key] ?? $orderDetail->pickup_time,
                'is_ac' => $order_data['is_ac'][$key] ?? $orderDetail->is_ac,
                'flight_num' => $order_data['flight_num'][$key] ?? $orderDetail->flight_num,
                'from_loc' => $order_data['from_loc'][$key] ?? $orderDetail->from_loc,
                'to_loc' => $order_data['to_loc'][$key] ?? $orderDetail->to_loc,
            ]);
        }
    }

    public static function update_pending_order($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        $order->update([
            'overall_status' => $order_data['overall_status'],
            'reason' => $order_data['reason'] ?? null,
            'updated_by' => $order_data['updated_by'],
        ]);

        $data =
            [
            'status' => $order_data['status'], // add key when passing status from frontend
        ];

        // Create the order detail
        OrderDetail::where('order_id', $order->id)->update($data);
        $user_id = User::where('partner_id', $order->business_partner_id)->pluck('id')->first();
        $sender_id = auth()->user()->id;
        if ($order_data['overall_status'] == 'approved') {
            Notification::notify('Approved Order', 'You order has been approved by admin', $user_id, null, 0, 0, 0, 0, 0, $sender_id, $order->id);
        } elseif ($order_data['overall_status'] == 'cancelled') {
            Notification::notify('Cancelled Order', 'You order has been cancelled by admin', $user_id, null, 0, 0, 0, 0, 0, $sender_id, $order->id);

        } else {
            Notification::notify('Unapproved Order', 'You order has not been approved by admin', $user_id, null, 0, 0, 0, 0, 0, $sender_id, $order->id);
        }

        // $sender_id = auth()->user()->id;

        // // Set the notification title and message based on the overall status
        // if ($order_data['overall_status'] == 'approved') {
        //     $title = 'Approved Order';
        //     $message = 'Your order has been approved by admin';
        // } elseif ($order_data['overall_status'] == 'cancelled') {
        //     $title = 'Cancelled Order';
        //     $message = 'Your order has been cancelled by admin';
        // } else {
        //     $title = 'Unapproved Order';
        //     $message = 'Your order has not been approved by admin';
        // }

        // // Create the notification with sender_id and source_id (order_id)
        // Notification::notify(
        //     $title, // Notification title
        //     $message, // Notification detail
        //     $user_id, // Receiver ID
        //     0, 0, 0, 0, 0, 0, 0,
        //     $sender_id, // Sender ID (admin who triggered the notification)
        //     $order->id// Source ID (the order that was updated)
        // );

    }


    //----------------------------------- PurchaseOrder section --------------------------------------
    public static function store_purchase_order($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $purchaseOrder = Order::create([
            'order_no'=>$document_no,
            'po_reference'=>$data['po_reference']??null,
            'description'=>$data['description'],
            'document_type_id'=>$data['document_type_id'],
            'date_ordered'=>$data['date_ordered'],
            'date_promised'=>$data['date_promised'],
            'business_partner_id'=>$data['business_partner_id'],
            'partner_location_id'=>$data['partner_location_id'],
            // 'invoice_location_id'=>$data['invoice_location_id'],
            // 'invoice_partner_id'=>$data['invoice_partner_id'],
            'warehouse_id'=>$data['warehouse_id'],
            'price_list_id'=>$data['price_list'],
            'currency'=>$data['currency'],
            'payment_term'=>$data['payment_term'],
            'booking_amount'=>$data['booking_amount'],
            'final_amount'=>$data['final_amount'],
            'document_action'=>$data['document_action'],
            'document_status'=>$data['document_status'],
            'created_by'=> $data['created_by'],
            'company_id'=> auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id,
        ]);

        foreach($data['rows']??[] as $row){
            OrderDetail::create([
                'order_id'=> $purchaseOrder->id,
                'status'=> 'pending',
                'seq_no'=>$row['seq_no'],
                'date_ordered'=>$row['date_ordered'] ?? null,
                'date_promised'=>$row['date_promised']??null,
                'quantity'=>$row['quantity'],
                'product_id'=>$row['product_id'],
                'unit'=>$row['unit'],
                'rate'=>$row['rate'],
                'order_qty'=>$row['quantity'] ?? null,
                'delivered_qty'=>$row['delivered_qty'] ?? 0,
                'reserved_qty'=>$row['reserved_qty'] ?? 0,
                'invoiced_qty'=>$row['invoiced_qty'] ?? 0,
                'unit_price'=>$row['unit_price'] ?? null,
                'list_price'=>$row['list_price'] ?? null,
                'tax_id'=>$row['tax'],
                'discount'=>$row['discount'] ?? 0,
                'tax_value'=>$row['tax_value'] ?? null,
                'line_amount'=>$row['line_amount'],
                'total_line_amount'=>$row['total_line_amount'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
            ]);
        }
    }
    public static function update_purchase_order($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $purchaseOrder->update([
            // 'document_no'=>$data['document_no'],
            'po_reference'=>$data['po_reference']??null,
            'description'=>$data['description'],
            // 'document_type_id'=>$data['document_type_id'],
            'date_ordered'=>$data['date_ordered'],
            'date_promised'=>$data['date_promised'],
            'business_partner_id'=>$data['business_partner_id'],
            'partner_location_id'=>$data['partner_location_id'],
            // 'invoice_location_id'=>$data['invoice_location_id'],
            // 'invoice_partner_id'=>$data['invoice_partner_id'],
            'warehouse_id'=>$data['warehouse_id'],
            'price_list_id'=>$data['price_list'],
            'currency'=>$data['currency'],
            'payment_term'=>$data['payment_term'],
            'booking_amount'=>$data['booking_amount'],
            'final_amount'=>$data['final_amount'],
            'document_status'=>$data['document_status'],
            'document_action'=>$data['document_action'],
            'updated_by'=> $data['updated_by']
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                OrderDetail::where('id', $row['row_id'])->where('order_id',$purchaseOrder->id)->update([
                'seq_no'=>$row['seq_no'],
                'date_ordered'=>$row['date_ordered'] ?? null,
                'status'=> 'pending',
                'date_promised'=>$row['date_promised']??null,
                'quantity'=>$row['quantity'],
                'product_id'=>$row['product_id'],
                'unit'=>$row['unit'],
                'order_qty'=>$row['quantity'] ?? null,
                'delivered_qty'=>$row['delivered_qty'] ?? 0,
                'reserved_qty'=>$row['reserved_qty'] ?? 0,
                'invoiced_qty'=>$row['invoiced_qty'] ?? 0,
                'rate'=>$row['rate'],
                'unit_price'=>$row['unit_price'] ?? null,
                'list_price'=>$row['list_price'] ?? null,
                'tax_id'=>$row['tax'],
                'discount'=>$row['discount'] ?? 0,
                'tax_value'=>$row['tax_value'] ?? null,
                'line_amount'=>$row['line_amount'],
                'total_line_amount'=>$row['total_line_amount'],
                ]);
            }
            else{

                OrderDetail::create([
                'order_id'=> $purchaseOrder->id,
                'seq_no'=>$row['seq_no'],
                'date_ordered'=>$row['date_ordered'] ?? null,
                'status'=> 'pending',
                'rate'=>$row['rate'],
                'date_promised'=>$row['date_promised']??null,
                'quantity'=>$row['quantity'],
                'product_id'=>$row['product_id'],
                'unit'=>$row['unit'],
                'order_qty'=>$row['quantity'] ?? null,
                'delivered_qty'=>$row['delivered_qty'] ?? 0,
                'reserved_qty'=>$row['reserved_qty'] ?? 0,
                'invoiced_qty'=>$row['invoiced_qty'] ?? 0,
                'unit_price'=>$row['unit_price'] ?? null,
                'list_price'=>$row['list_price'] ?? null,
                'tax_id'=>$row['tax'],
                'discount'=>$row['discount'] ?? 0,
                'tax_value'=>$row['tax_value'] ?? null,
                'line_amount'=>$row['line_amount'],
                'total_line_amount'=>$row['total_line_amount'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,

                ]);
            }

        }
    }


    // public static function pendingPurchaseOrders(){
    //     return self::where('document_status','completed')->whereHas('orderDetails',function($q){
    //         $q->where('status','pending');
    //     })->get();
    // }
    public static function generate_PO_no($company_id, $document_type)
    {
        $last = self::where('company_id', $company_id)
        //
            ->where('document_type_id', $document_type->id)
        //
            ->orderByDesc('id')->first();
        if ($last) {
            $document_no = intval(last(explode('-', $last->order_no))) + 1;

        } else {
            $document_no = 1;
        }
        // $prefix =  self::document_no_prefix($document_type);

        return $document_type->code . str_pad($document_no, 4, "0", STR_PAD_LEFT);
    }
    
}