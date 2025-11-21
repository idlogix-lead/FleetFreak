<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\User;
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
class AgentPaymentController extends Controller
{
    static $role_module_id = 31;
    public $my_companies;

    static $ignores = ['update_status'=> true ];
    function __construct(){
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });



    }
    public function index(Request $request)
    {


        $breadcrumbs = [
            [
                'name'=>"Payments",
                'link'=>route("payments.index"),
                'active'=>true,
            ]
        ];
        $company_id = auth()->user()->active_company();
        $perPage = $request->input('perPage', 10);

        $payment_headers = PaymentHeader::where('status','draft')
        ->orWhere('status','paid')->whereNot('agent_id',null)
        ->where('company_id', $company_id)
        ->get();
        // dd($payment_headers);
        // $payment_headers = PaymentHeader::get();

        return view('payment.index', compact('payment_headers','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) );
    }
    public function create(Request $request){

        $breadcrumbs = [
            [
                'name'=>"Payment",
                'link'=>route("payments.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("payments.create"),
                'active'=>true,
            ]
        ];
        $payment_headers = new PaymentHeader();
        return view('payment.create', compact('payment_headers','breadcrumbs'));
    }

    public function store(Request $request)
    {
        $payload = [];
        // dd($request);
        // Validate the request data
        $payment_window_validator = Validator::make($request->all(), [
            'date'=>['required'],
            'description'=>['nullable'],
            'amount'=>['required'],
            'agent_id' => ['required'],
            'status'=> ['required']
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
        Order::store_agent_payment($payload);

        //$order_data = Order::create($order_data);

        return redirect()->route('payments.index')->with('success', 'Payment Created Successfully.');
    }
    function generatePaymentNo()
    {
        // Get the current month and year
        $month = Carbon::now()->format('m'); // '06'
        $year = Carbon::now()->format('y'); // '24'

        // Fetch the latest payment number
        $latestPayment = PaymentHeader::where('company_id',auth()->user()->active_company())->latest()->first();

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
    public function edit($id){
        $breadcrumbs = [
            [
                'name' => "Payment",
                'link' => route("payments.index"),
                'active' => false,
            ],
            [
                'name' => "Edit Payment",
                'link' => route("payments.edit", $id),
                'active' => true,
            ]
        ];
        $payment_headers = PaymentHeader::where('company_id',auth()->user()->active_company())->findOrFail($id);
        // $orders = PaymentLine::where('payment_header_id',$id)->get();
        // dd($order);
        // $business_partner = Partner::where('partner_type','business')->get();
        //$customer_partner = Partner::where('partner_type','customer')->get();

        return view('payment.edit', compact('payment_headers','breadcrumbs'));
    }

    public function update(Request $request,$header)
    {

        $payload = [];
        // dd($request);
        $header = PaymentHeader::find($header);
        // dd($header);

        // Validate the request data
        $payment_window_validator = Validator::make($request->all(), [
            'date'=>['required'],
            'description'=>['nullable'],
            'amount'=>['required'],
            'agent_id' => ['required'],
            'status' => ['required'],
        ]);

        // dd($payment_window_validator);

        if ($payment_window_validator->fails()) {
        //    dd($payment_window_validator->errors());
            return back()->with('errors', $payment_window_validator->errors());
        }
        // Update lead attributes with validated data
        $payment_window_data = $payment_window_validator->validated();
        $payload['updated_by'] = auth()->user()->id;
        // $payload['payment_no'] = $this->generatePaymentNo();
        $payload['header'] = $header;

        // $payload['agent_id'] = auth()->user()->partner_id;
        $payload['payment_window_data'] = $payment_window_data;


        // dd($payload);
        Order::update_agent_payment($payload);
        //$order_data = Order::create($order_data);

        return redirect()->route('payments.index')->with('success', 'Payment Created Successfully.');
    }
    public function update_status($header){
        $header = PaymentHeader::find($header);
        $company_id = auth()->user()->active_company();
        // dd($header);

        $header->update(['status'=>'paid']);
        $debit_account = Account::where('company_id',$company_id)->where('name', 'Accounts Payable')->first();
        $credit_account = Account::where('company_id',$company_id)->where('name', 'Cash Account')->first();
        $currency = User::current_currency();
        AccountTransaction::createTransaction($header['date'],$company_id,$debit_account->id,$credit_account->id,1,$header['total_amount'],$currency->id,null,null,null,21,business_partner_id:$header['agent_id']);

        return redirect()->route('payments.index')->with('success', 'Payment Paid Successfully.');



    }

}
