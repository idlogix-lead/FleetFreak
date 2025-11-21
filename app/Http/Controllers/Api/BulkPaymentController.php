<?php

namespace App\Http\Controllers\Api;

use App\Models\Driver;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Route;
use App\Models\OrderDetail;
use App\Models\PaymentLine;
use App\Models\Partner;
use App\Models\Vehicle;
use App\Models\Event;
use App\Models\PaymentHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class BulkPaymentController extends Controller
{
    static $role_module_id = 16;
    static $ignores = ['payment_window_edit'=>true,'payment_window_update'=>true];
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }
    public function index(Request $request)
    {
        
        $breadcrumbs = [
            [
                'name' => "Bulk Payment",
                'link' => route("bulkpayments.index"),
                'active' => true,
            ]
        ];

        $agentquery = $request->input('agent');
        // $customerQuery = $request->input('customer');
        // $dateQuery = $request->input('date');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $orders = OrderDetail::whereHas('order', function($queryBuilder){
            $queryBuilder->where('overall_status', 'approved');
        })
        ->where('status', 'completed')
        // ->when($customerQuery, function ($queryBuilder) use ($customerQuery) {
        //     $queryBuilder->whereHas('order.partner_customer', function ($subQuery) use ($customerQuery) {
        //         $subQuery->where('name', 'like', '%' . $customerQuery . '%');
        //     });
        // })
        ->when($agentquery, function ($queryBuilder) use ($agentquery) {
            $queryBuilder->whereHas('order.partner_business', function ($subQuery) use ($agentquery) {
                $subQuery->where('id', '=', $agentquery );
            });
        
        })
       
        ->when($fromDate && !$toDate, function ($queryBuilder) use ($fromDate) {
            $queryBuilder->where('date', '>=', $fromDate);
        })
        ->when(!$fromDate && $toDate, function ($queryBuilder) use ($toDate) {
            $queryBuilder->where('date', '<=', $toDate);
        })
        ->when($fromDate && $toDate, function ($queryBuilder) use ($fromDate, $toDate) {
            $queryBuilder->whereBetween('date', [$fromDate, $toDate]);
        })
        // ->when($dateQuery, function ($queryBuilder) use ($dateQuery) {
        //     $queryBuilder->whereDate('date', Carbon::parse($dateQuery));
        // })
        ->paginate();
        //added 
        
        

        return view('bulk-payment.index', compact('orders', 'breadcrumbs'))
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
    // public function create(Request $request)
    // {
    //     $breadcrumbs = [
    //         [
    //             'name'=>"Bulk Payment",
    //             'link'=>route("bulkpayments.index"),
    //             'active'=>false,
    //         ],
    //         [
    //             'name'=>"Create",
    //             'link'=>route("bulkpayments.create"),
    //             'active'=>true,
    //         ]
    //     ];
       
    //     // $business_partner = Partner::where('partner_type','business')->get();
    //     // $customer_partner = Partner::where('partner_type','customer')->get();
    //     return view('bulk-payment.create', compact('order','breadcrumbs','nextOrderNo'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request $request
    //  * *
    //  */
    public function store(Request $request)
    {
        $payload = [];
        // dd($request);
        // Validate the request data
        if($request['status'] == 'draft'){
            $request['status'] = 'draft';
        }
        else{
            $request['status'] = 'paid';
            
        }
        $payment_validator = Validator::make($request->all(), [
            // 'order_id' => 'required|integer',
            'order_no' => 'required|string',
            'order_detail_no' => 'required|integer',
            'customer_name' => 'required|string',
            'driver_name' => 'nullable|string',
            'amount' => 'required|numeric',
            'agent_id'=> ['required'],
            'customer_id'=> ['required'],
            'status'=> ['nullable'],


        ]);
        

    //    dd($payment_validator);

        if ($payment_validator->fails()) {
        //    dd($payment_validator->errors());
            return response()->json(['errors'=>$payment_validator->errors()]);
        }
        // Update lead attributes with validated data
        $payment_data = $payment_validator->validated();
        $payload['created_by'] = auth()->user()->id;
        $payload['payment_no'] = $this->generatePaymentNo();
        // $payload['agent_id'] = auth()->user()->partner_id;
        $payload['date'] = Carbon::today()->toDateString();;
        $payload['description'] = 'this is created by agent_id '.$payment_data['agent_id'].' '.'on'.' '.$payload['date'].'';
      
        $payload['payment_data'] = $payment_data;
        // dd($payload);
        Order::store_payment($payload);
        //$order_data = Order::create($order_data);

        return redirect()->route('bulkpayments.index')->with('success', 'Payment Created Successfully.');
    }
    function generatePaymentNo()
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


    public function payment_window_index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Payment Window",
                'link'=>route("payment_window.index"),
                'active'=>true,
            ]
        ];
        // $payment_headers = PaymentHeader::whereHas('paymentLines.order_details', function ($query) {
        //     $query->where('status', 'paid')
        //           ->orWhere('status', 'draft');
        // })
        // ->whereHas('paymentLines.order', function ($query) {
        //     $query->where('overall_status', 'approved');
        // })
        $payment_headers = PaymentHeader::where('status','draft')->orWhere('status','paid')
        ->get();
        // $payment_headers = PaymentHeader::get();

        return view('payment-window.index', compact('payment_headers','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) );
    }

    public function payment_window_create(Request $request)
    {
        
           
       
        $breadcrumbs = [
            [
                'name' => "Payment Window",
                'link' => route("payment_window.create"),
                'active' => true,
            ]
        ];
    
        $customerQuery = $request->input('customer');
        // $dateQuery = $request->input('date');
        $agentQuery = $request->input('agent');
    
        // Check if at least one filter is provided
        if ($customerQuery || $agentQuery) {
            $orders = OrderDetail::whereHas('order', function($queryBuilder) use ($agentQuery,$customerQuery) {
                $queryBuilder->where('overall_status', 'approved')->where('business_partner_id',$agentQuery)->orWhere('customer_partner_id',$customerQuery);
            })
            ->where('status', 'completed')->orWhere('status', 'draft')
            ->when($customerQuery, function ($queryBuilder) use ($customerQuery) {
                $queryBuilder->whereHas('order.partner_customer', function ($subQuery) use ($customerQuery) {
                    $subQuery->where('id', $customerQuery);
                });
            })
            ->when($agentQuery, function ($queryBuilder) use ($agentQuery) {
                $queryBuilder->whereHas('order.partner_business', function ($subQuery) use ($agentQuery) {
                    $subQuery->where('id', $agentQuery);
                });
            })
            // ->when($dateQuery, function ($queryBuilder) use ($dateQuery) {
            //     $queryBuilder->whereDate('date', Carbon::parse($dateQuery));
            // })
            ->paginate();
        } else {
            // Create an empty paginator
            $orders = new LengthAwarePaginator([], 0, 15, 1, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);
        }
        
        return view('payment-window.create', compact('orders', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }
    public function payment_window_store(Request $request)
    {


        $payload = [];
        // dd($request);
        // Validate the request data
        $payment_window_validator = Validator::make($request->all(), [
            // 'order_id.*' => 'required|integer',
            'customer' => 'nullable|string',
            'date'=>['required'],
            'description'=>['nullable'],
            'total_amount'=>['required'],
            'order_no.*' => ['required','string'],
            'order_detail_no.*' => ['required','integer'],
            'order_id.*' => ['nullable','string'],
            'amount.*' => ['required','numeric'],
            'agent_id.*' => ['required','numeric'],
            'customer_id.*' => ['required','numeric'],
            'action' => ['required'],
        ]);

        // dd($payment_window_validator);

        if ($payment_window_validator->fails()) {
        //    dd($payment_window_validator->errors());
            return back()->with('errors', $payment_window_validator->errors());
        }
        // Update lead attributes with validated data
        $payment_window_data = $payment_window_validator->validated();
        $payload['created_by'] = auth()->user()->id;
        $payload['payment_no'] = $this->generatePaymentNo();
        // $payload['agent_id'] = auth()->user()->partner_id;      
        $payload['payment_window_data'] = $payment_window_data;

        
        // dd($payload);
        Order::store_window_payment($payload);
        //$order_data = Order::create($order_data);

        return redirect()->route('payment_window.index')->with('success', 'Payment_Window Created Successfully.');
    }
    public function payment_window_edit($id)
    {
        $breadcrumbs = [
            [
                'name' => "Payment Window",
                'link' => route("payment_window.index"),
                'active' => false,
            ],
            [
                'name' => "Edit Payment",
                'link' => route("payment_window.edit", $id),
                'active' => true,
            ]
        ];
        $payment_header = PaymentHeader::findOrFail($id);
        $orders = PaymentLine::where('payment_header_id',$id)->get();
        // dd($order);
        // $business_partner = Partner::where('partner_type','business')->get();
        //$customer_partner = Partner::where('partner_type','customer')->get();

        return view('payment-window.create', compact('payment_header','breadcrumbs','orders'));
    }

    public function payment_window_update(Request $request,$header)
    {
        $payload = [];
        // dd($request);
        $header = PaymentHeader::find($header);
        // dd($header);

        // Validate the request data
        $payment_window_validator = Validator::make($request->all(), [
            // 'order_id.*' => 'required|integer',
            // 'customer' => 'required|string',
            'date'=>['nullable'],
            'description'=>['nullable'],
            'total_amount'=>['required'],
            'order_no.*' => ['required','string'],
            'order_detail_no.*' => ['required','integer'],
            'order_id.*' => ['nullable','string'],
            'amount.*' => ['required','numeric'],
            'agent_id.*' => ['required','numeric'],
            'customer_id.*' => ['required','numeric'],
            'action' => ['required'],
        ]);

        // dd($payment_window_validator);

        if ($payment_window_validator->fails()) {
        //    dd($payment_window_validator->errors());
            return back()->with('errors', $payment_window_validator->errors());
        }
        // Update lead attributes with validated data
        $payment_window_data = $payment_window_validator->validated();
        $payload['updated_by'] = auth()->user()->id;
        $payload['payment_no'] = $this->generatePaymentNo();
        $payload['header'] = $header;

        // $payload['agent_id'] = auth()->user()->partner_id;      
        $payload['payment_window_data'] = $payment_window_data;

        
        // dd($payload);
        Order::update_window_payment($payload);
        //$order_data = Order::create($order_data);

        return redirect()->route('payment_window.index')->with('success', 'Payment_Window Created Successfully.');
    }

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
    // public function destroy($id)
    // {
    //     $order = Order::find($id)->delete();

    //     return redirect()->route('driver_assignments.index')
    //         ->with('success', 'Order deleted successfully');
    // }
    // ------------------------------------end---------------------------------------------
   
   


       
}
