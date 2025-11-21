<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\WhatsAppController;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PendingRentalInvoices;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class PendingInvoicesController extends Controller
{
    static $ignores = ['create_customer_from_order' => true, 'deleteRow' => true];
    public $my_companies;
    static $role_module_id = 47;


    public function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
        // $this->middleware('checkCompanyAccess');
    }

    public function index(Request $request)
    {
        // abort(404);
        $breadcrumbs = [
            [
                'name'=>"Pending Invoices",
                'link'=>route("pending_rental_invoices.index"),
                'active'=>true,
            ]
        ];
        $company_id = auth()->user()->active_company();
         $perPage = $request->input('perPage', 10);
        $order_details = OrderDetail::where('company_id',$company_id)->where('status','completed')->whereHas('order', function($queryBuilder) {
            $queryBuilder->where('overall_status', 'approved')->where('trip_type','monthly_booking');
        })->get();
        // dd($payment_headers);
        // $payment_headers = PaymentHeader::get();

        return view('pending-invoices.index', compact('order_details','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    public function create(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "Pending Invoices",
                'link' => route("pending_rental_invoices.create"),
                'active' => true,
            ]
        ];

        $customerQuery = $request->input('customer');
        $dateQuery = $request->input('date');
        $todateQuery = $request->input('todate');
        $agentQuery = $request->input('agent');
        // dd($dateQuery);
        // Check if at least one filter is provided
        if ($customerQuery || $agentQuery || $dateQuery) {
            $orders = OrderDetail::where('company_id',auth()->user()->active_company())->whereHas('order', function($queryBuilder) use($agentQuery,$customerQuery) {
                $queryBuilder->where('overall_status', 'approved')->where('trip_type','monthly_booking')->when($agentQuery,function($qr) use($agentQuery){
                    $qr->where('business_partner_id',$agentQuery);
                })->when($customerQuery,function($qry) use($customerQuery){
                    $qry->where('customer_partner_id',$customerQuery);

                });
            })
            ->where('status', 'incomplete')
            ->when($dateQuery,function($q) use ($dateQuery){
                $q->whereDate('date','>=',$dateQuery);
            })
            ->when($todateQuery,function($q) use ($todateQuery){
                $q->whereDate('date','<=',$todateQuery);
            })
            ->orderBy('date')
            ->paginate();
            // dd($orders);
        } else {
            $orders = OrderDetail::where('company_id',auth()->user()->active_company())->whereHas('order', function($queryBuilder) {
                $queryBuilder->where('overall_status', 'approved')->where('trip_type','monthly_booking');
            })
            ->where('status', 'incomplete')->orderBy('date')
            ->paginate();
        //     // Create an empty paginator
            // $orders = new LengthAwarePaginator([], 0, 15, 1, [
            //     'path' => LengthAwarePaginator::resolveCurrentPath(),
            // ]);
        }

        return view('pending-invoices.create', compact('orders', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request)
    {
        $payload = [];
        // dd($request);
        // Validate the request data
        $payment_window_validator = Validator::make($request->all(), [
            // 'order_id.*' => 'required|integer',

            'customer' => 'nullable|string',
            'order_no.*' => ['required','string'],
            'order_detail_no.*' => ['required','integer'],
            'order_id.*' => ['nullable','string'],
            'amount.*' => ['required','numeric'],
            'agent_id.*' => ['nullable'],
            'customer_id.*' => ['required','numeric'],
            'action' => ['required'],
        ]);

        // dd($payment_window_validator);

        if ($payment_window_validator->fails()) {
        //    dd($payment_window_validator->errors());
            return back()->with('errors', $payment_window_validator->errors());
        }
        // Update lead attributes with validated data
        $pending_invoices_data = $payment_window_validator->validated();
        // $payload['created_by'] = auth()->user()->id;
        // $payload['agent_id'] = auth()->user()->partner_id;
        $payload['pending_invoices_data'] = $pending_invoices_data;


        // dd($payload);
        PendingRentalInvoices::store_pending_invoices($payload);
        //$order_data = Order::create($order_data);

        return redirect()->route('pending_rental_invoices.index')->with('success', 'Payment_Window Created Successfully.');
    }

    private function sendWhatsAppNotification($businessPartnerId, $orderData)
    {
        if ($orderData['overall_status'] !== 'draft') {
            $status = $orderData['status'] ?? 'pending';
            $whatsAppController = new WhatsAppController();
            $admin_partner_id = 95;
            // dd($admin_partner_id);

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

                // $whatsAppController->sendNotification(array_merge($orderData, $orderDetail), $status, $businessPartnerId, null, null, $admin_partner_id, null);
            }

            // foreach ($order_data['pickup_time'] as $key => $pickup_time) {
            //     $pickupDateTimeString = $order_data['date'][$key] . ' ' . trim($pickup_time);
            //     $pickupDateTime = Carbon::createFromFormat('Y-m-d H:i', $pickupDateTimeString);
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
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    // public function show($id)
    // {
    //     $breadcrumbs = [
    //         [
    //             'name' => "Daily Rental",
    //             'link' => route("daily_rentals.index"),
    //             'active' => false,
    //         ],
    //         [
    //             'name' => "Show",
    //             'link' => route("daily_rentals.show", $id),
    //             'active' => true,
    //         ],
    //     ];
    //     $order = Order::checkGlobal(9)->where('company_id',auth()->user()->active_company())->find($id);

    //     return view('daily-rental.show', compact('order', 'breadcrumbs'));
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */
    public function edit($id)
    {
        $breadcrumbs = [
            [
                'name' => "Pending Invoices",
                'link' => route("pending_rental_invoices.index"),
                'active' => false,
            ],
            [
                'name' => "Edit Payment",
                'link' => route("pending_rental_invoices.edit", $id),
                'active' => true,
            ]
        ];
        $payment_header = PaymentHeader::where('company_id',auth()->user()->active_company())->findOrFail($id);
        $orders = PaymentLine::where('company_id',auth()->user()->active_company())->where('payment_header_id',$id)->get();
        // dd($order);
        // $business_partner = Partner::where('partner_type','business')->get();
        //$customer_partner = Partner::where('partner_type','customer')->get();

        return view('pending-invoices.create', compact('payment_header','breadcrumbs','orders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $order
     * *
     */
    public function update(Request $request, $order)
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

        return redirect()->route('pending_rental_invoices.index')->with('success', 'Payment_Window Created Successfully.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    // public function destroy($id)
    // {
    //     $order = Order::where('company_id',auth()->user()->active_company())->find($id)->delete();

    //     return redirect()->route('daily_rentals.index')
    //         ->with('success', 'Order deleted successfully');
    // }
}