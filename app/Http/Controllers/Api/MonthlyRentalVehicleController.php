<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Partner;
use Illuminate\Support\Facades\Validator;
class MonthlyRentalVehicleController extends Controller
{
    static $ignores = [];
    public $my_companies;
    static $role_module_id = 41;


    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
        // $this->middleware('checkCompanyAccess');
    }
    public function api_index(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "Rental Vehicle",
                'link' => route("rental_vehicles.index"),
                'active' => true,
            ],
        ];

        // $user = auth()->user();

        // $company = $user->companies->first();

        // if (!$company) {
        //     return redirect()->route('dashboard')->with('error', 'No associated company found.');
        // }
        $companyId = auth()->user()->active_company() ?? null;
        // $query = $request->input('query');
        // $perPage = $request->input('perPage', 10);

        // if (auth()->user()->actor_id == 2) {
        //     $orders = Order::when($companyId, function ($query) use ($companyId) {
        //         return $query->where('company_id', $companyId);
        //     })
        //         ->checkGlobal(9)->whereNotIn('overall_status', ['pending', 'approved'])->where('trip_type', 'monthly_booking')
        //         ->when($query, function ($q) use ($query) {
        //             $q->where(function ($q) use ($query) {
        //                 $q->where('order_no', 'ILIKE', '%' . $query . '%')
        //                     ->orWhere('overall_status', 'ILIKE', '%' . $query . '%')
        //                     ->orWhereHas('partner_business', function ($q2) use ($query) {
        //                         $q2->where('company_name', 'ILIKE', '%' . $query . '%');
        //                     })->orWhereHas('partner_customer', function ($q3) use ($query) {
        //                     $q3->where('name', 'ILIKE', '%' . $query . '%');
        //                 });

        //             });
        //         })->orderBy('created_at', 'desc')->paginate($perPage);

        // } else {
        //     $orders = Order::where('company_id', $companyId)
        //         ->checkGlobal(9)->where('overall_status', '!=', 'pending')->where('overall_status', '!=', 'approved')->where('created_by', auth()->user()->id)->when($query, function ($q) use ($query) {
        //         $q->where(function ($q) use ($query) {
        //             $q->where('order_no', 'ILIKE', '%' . $query . '%')
        //                 ->orWhere('overall_status', 'ILIKE', '%' . $query . '%')
        //                 ->orWhereHas('partner_customer', function ($q3) use ($query) {
        //                     $q3->where('name', 'ILIKE', '%' . $query . '%');
        //                 });

        //         });
        //     })->orderBy('created_at', 'desc')->paginate($perPage);
        //     // })->get();

        // }

        $orders = Order::with('orderDetails','partner_customer','business_partner')->where('company_id', $companyId)
            ->checkGlobal(41)->where('overall_status', '!=', 'pending')->where('overall_status', '!=', 'approved')->where('trip_type', 'monthly_booking')->orderBy('created_at', 'desc')->get();
        // dd($orders);
        // return view('rental-vehicle.index', compact('orders', 'breadcrumbs'))
        //     ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());

        return response()->json($orders);
    }


    public function api_store(Request $request){
        // if ($request['trip_type'] == 'monthly_booking') {
            $order_validator = Validator::make($request->all(), [
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
                'customer_business_partner_id'=>['nullable'],
                // order_details validation:

                //'order_id' => ['required'],
                // 'rate_list_id.*' => ['required'],

                // 'from.*' => ['required'],
                // 'to.*' => ['required'],
                'duration.*' => ['required'],
                // 'with_driver.*' => ['nullable'],
                'rate.*' => ['required'],
                // 'status.*' => ['required'],
                // 'flight_num.*' => ['nullable'],
                // 'airline_name.*' => ['nullable'],
                'adult.*' => ['required'],
                'child.*'=>['required'],
                'bags.*' => ['required'],
                'date.*' => ['required'],
                'end_date.*' => ['required'],
                // 'pickup_time.*' => ['required'],
                'company_id.*' => ['nullable'],
                // 'checkout_time.*'=>['nullable'],
                // 'is_ac.*' => ['required'],
                // customer model required fields:
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
        // }

        if (!isset($request['rate']) || count($request['rate']) < 1) {
            return back()->withErrors(['errors' => 'At least one order line is required.'])->withInput();
        }

        if ($order_validator->fails()) {
            return response()->json(['error' => $order_validator->errors()], 401);
        }
        $order_data = $order_validator->validated();
        $order_data['created_by'] = auth()->user()->id;
        if ($order_data['overall_status'] == 'draft') {
            $order_data['status'] = 'draft';

        } else {

            $order_data['status'] = 'pending';

        }

        $order_data['reason'] = null;
        // $order_data['with_driver'] = 0;
        $from_rental_vehicle = true;

        // creating customer in order
        // dd($order_data);
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
        $order_data['with_driver'] = [0];

        // dd($order_data );

        $payload['order_data'] = $order_data;
        // $payload['nextOrderNo'] = $nextOrderNo;
        // $payload['order_data'] = $order_data;
        $payload['from_rental_vehicle'] = $from_rental_vehicle;
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
    
}
