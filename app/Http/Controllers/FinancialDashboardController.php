<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Vehicle;
use App\Models\OrderDetail;
use App\Exports\ExportOrder;
use App\Exports\OverallExportPendingOrder;
use App\Exports\OverallExportAgentPendingOrder;
use Illuminate\Http\Request;
use App\Models\PaymentHeader;
use App\Exports\ExportAgentOrder;
use App\Exports\ExportCancelOrder;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportPendingOrder;
use App\Exports\ExportApprovedOrder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportCompletedOrder;
use App\Exports\ExportIncompleteOrder;
use App\Exports\ExportUnapprovedOrder;
use App\Exports\ExportAgentCancelOrder;
use App\Exports\ExportAgentPendingOrder;
use App\Exports\ExportAgentApprovedOrder;
use App\Exports\ExportAgentCompletedOrder;
use App\Exports\ExportAgentIncompleteOrder;
use App\Exports\ExportAgentUnapprovedOrder;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Event;
use App\Models\User;
use App\Models\UserCompany;

class FinancialDashboardController extends Controller
{
    function __construct(){
        $this->middleware('RolePermissions');
    }
    public function index(Request $request){
        // abort(404);
        // super admin dashboard
        if(auth()->user()->actor_id==1){
            abort(404);
        }
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        // admin filters
        if(auth()->user()->actor_id==2){
            // Receivable portion----------------

           
            $Receivables =  AccountTransaction::where('company_id',auth()->user()->active_company())->whereHas('Account',function($q){
                $q->where('name', 'Accounts Receivable');
            })->when($from_date && $to_date, function($q) use ($from_date, $to_date) {
                $q->whereBetween('transaction_date', [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function($q) use ($from_date) {
                $q->where('transaction_date', '>=', $from_date);
            })
            ->when(!$from_date && $to_date, function($q) use ($to_date) {
                $q->where('transaction_date', '<=', $to_date);
            })->get();
            $totalReceivables = $Receivables->sum(function($transaction) {
                return $transaction->debit - $transaction->credit;
            });
            // Receivable portion ends here ---------------------------


            // Payable portion ----------------


            $Payables =  AccountTransaction::where('company_id',auth()->user()->active_company())->whereHas('Account',function($q){
                $q->where('name', 'Accounts Payable');
            })->when($from_date && $to_date, function($q) use ($from_date, $to_date) {
                $q->whereBetween('transaction_date', [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function($q) use ($from_date) {
                $q->where('transaction_date', '>=', $from_date);
            })
            ->when(!$from_date && $to_date, function($q) use ($to_date) {
                $q->where('transaction_date', '<=', $to_date);
            })->get();
            $total_payable = $Payables->sum(function($transaction) {
                return $transaction->credit - $transaction->debit;
            });
            // Payable portion ends here--------------------------------------


            // Revenue portion----------------

            $Revenue =  AccountTransaction::where('company_id',auth()->user()->active_company())->whereHas('Account',function($q){
                $q->where('name', 'Ride Revenue');
            })->when($from_date && $to_date, function($q) use ($from_date, $to_date) {
                $q->whereBetween('transaction_date', [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function($q) use ($from_date) {
                $q->where('transaction_date', '>=', $from_date);
            })
            ->when(!$from_date && $to_date, function($q) use ($to_date) {
                $q->where('transaction_date', '<=', $to_date);
            })->get();
            $total_revenue = $Revenue->sum(function($transaction) {
                return $transaction->credit - $transaction->debit;
            });
            // Calculate monthly revenue
            $monthlyRevenue = $Revenue->groupBy(function($transaction) {
                return Carbon::parse($transaction->transaction_date)->format('Y-m'); // Group by Year-Month
            })->map(function($transactions) {
                return $transactions->sum(function($transaction) {
                    return $transaction->credit - $transaction->debit;
                });
            });

            // Convert monthly data to format suitable for chart
            $revenuechartData = $monthlyRevenue->map(function($revenue, $month) {
                return [
                    'month' => Carbon::parse($month)->format('M'),
                    'revenue' => $revenue,
                ];
            })->values()->toArray();
            // Revenue portion ends here----------------------------------------------



            // Expense portion----------------

            $Expense =  AccountTransaction::where('company_id',auth()->user()->active_company())->whereHas('Account',function($q){
                // $q->whereIn('name', ['Fuel Expenses','Maintenance Expense','Service Expense','Marketing Expense','Sales Expense','Admin Expense','Entertainment Expense','Rent Expense','Electricity Expense','Internet Charges','Toll Expenses']);
                $q->whereHas('accountSubType',function($qr){
                    $qr->whereIn('name',['Admin Expense','Sales Expense','Cost']);
                });
            })->when($from_date && $to_date, function($q) use ($from_date, $to_date) {
                $q->whereBetween('transaction_date', [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function($q) use ($from_date) {
                $q->where('transaction_date', '>=', $from_date);
            })
            ->when(!$from_date && $to_date, function($q) use ($to_date) {
                $q->where('transaction_date', '<=', $to_date);
            })->get();
            $total_expenses = $Expense->sum(function($transaction) {
                return $transaction->credit - $transaction->debit;
            });


            $CostExpense = AccountTransaction::where('company_id', auth()->user()->active_company())
            ->whereHas('Account', function ($q) {
                $q->whereIn('name', ['Fuel Expenses', 'Maintenance Expense']);
            })
            ->get()
            ->groupBy(['Account.name', function ($transaction) {
                return Carbon::parse($transaction->transaction_date)->format('Y-m'); // Group by year-month
            }]);
           

            // Initialize data structure
            $monthlyExpenses = [];

            foreach ($CostExpense as $accountName => $transactionsByMonth) {
                foreach ($transactionsByMonth as $month => $transactions) {
                    $total = $transactions->sum(function ($transaction) {
                        return $transaction->credit - $transaction->debit;
                    });

                    $monthlyExpenses[$month][$accountName] = $total;
                }
            }

            // Fill missing months with zero
            $allMonths = collect($CostExpense->flatten()->pluck('transaction_date'))
                ->map(fn($date) => Carbon::parse($date)->format('Y-m'))
                ->unique()
                ->values();

            foreach ($allMonths as $month) {
                $monthlyExpenses[$month]['Fuel Expenses'] = $monthlyExpenses[$month]['Fuel Expenses'] ?? 0;
                $monthlyExpenses[$month]['Maintenance Expense'] = $monthlyExpenses[$month]['Maintenance Expense'] ?? 0;
            }

            // Prepare data for chart
            $cost_expenses = collect($monthlyExpenses)->map(function ($expenses, $month) {
                return [
                    'month' => Carbon::parse($month)->format('M'),
                    'fuel_expense' => $expenses['Fuel Expenses'],
                    'maintenance_expense' => $expenses['Maintenance Expense'],
                ];
            })->values();

             $othersCostExpense = AccountTransaction::where('company_id', auth()->user()->active_company())->whereHas('Account',function($q){
                $q->whereIn('name', ['Service Expense','Marketing Expense','Sales Expense','Admin Expense','Entertainment Expense','Rent Expense','Electricity Expense','Internet Charges','Toll Expenses']);
            })->get()->groupBy('Account.name') // Group by the related Account's name
            ->map(function ($transactions) {
                return $transactions->sum(fn($transaction) => $transaction->credit - $transaction->debit);
            })->toArray();


            $othersCostExpenseTotal = AccountTransaction::where('company_id', auth()->user()->active_company())
            ->whereHas('Account', function ($q) {
                $q->whereIn('name', [
                    'Service Expense', 'Marketing Expense', 'Sales Expense', 'Admin Expense', 
                    'Entertainment Expense', 'Rent Expense', 'Electricity Expense', 
                    'Internet Charges', 'Toll Expenses'
                ]);
            })
            ->get()
            ->groupBy(function ($transaction) {
                return \Carbon\Carbon::parse($transaction->transaction_date)->format('M Y'); // Format as 'Jan 2024'
            })
            ->map(function ($transactions) {
                // Sum only the debit values for total expenses
                return $transactions->sum('debit');
            });
        
            // Prepare data for Highcharts
            $othersCostExpenseTotalCount = [
                'months' => $othersCostExpenseTotal->keys()->toArray(), // Extract month names
                'costs' => $othersCostExpenseTotal->values()->toArray() // Extract total expenses
            ];
            // dd($othersCostExpenseTotalCount);
            
                
            // Expense portion ends here---------------------------------------
            

            // dd($totalReceivables);

           
            return view('home_dashboard.financial_dashboard',
                compact('Receivables','totalReceivables','total_payable','total_revenue','revenuechartData','total_expenses','cost_expenses','othersCostExpense','othersCostExpenseTotalCount')
           
            );
        }
        else{
            return view('home_dashboard.dashboard');
        }

        
    }

    public function admin_rides_window(Request $request, $ordertype){
        $queryAgent = $request->input('agent');

        $fromDate = $request->input('date');
        $toDate = $request->input('to_date');

        $orders = OrderDetail::query();

        // Apply filters based on the request inputs
        if ($queryAgent) {
            $orders->whereHas('order', function($query) use ($queryAgent) {
                $query->where('business_partner_id', $queryAgent);
            });
        }
         if ($fromDate && $toDate) {
            $orders->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $orders->whereDate('date', '>=', $fromDate);
        } elseif ($toDate) {
            $orders->whereDate('date', '<=', $toDate);
        }

        // if ($fromDate) {
        //     $orders->whereDate('date', $fromDate);
        // }

        // Apply order type filter
        if($ordertype == 'total-orders'){
            $orders = $orders->paginate(10);
        }
        if($ordertype == 'pending-orders'){
            $orders = $orders->where('status','pending')->paginate(10);
        }
        if($ordertype == 'incomplete-orders'){
            $orders = $orders->where('status','incomplete')->paginate(10);
        }
        if($ordertype == 'completed-orders'){
            $orders = $orders->where('status','completed')->paginate(10);
        }
        if($ordertype == 'approved-orders'){
            $orders = $orders->where('status','approved')->paginate(10);
        }
        if($ordertype == 'unapproved-orders'){
            $orders = $orders->where('status','unapproved')->paginate(10);
        }
        if($ordertype == 'cancelled-orders'){
            $orders = $orders->where('status','cancelled')->paginate(10);
        }

        return view('home_dashboard.total-order', compact('orders', 'ordertype'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }

    //  Apply Excel for admin

    public function export_orders(){

        return Excel::download(new ExportOrder, 'orders.xlsx');

    }
    public function overall_export_pending_orders(){

        return Excel::download(new OverallExportPendingOrder, 'orders.xlsx');

    }

    public function export_pending_orders(){

        return Excel::download(new ExportPendingOrder, 'pending_orders.xlsx');
    }
    public function export_incomplete_orders(){

        return Excel::download(new ExportIncompleteOrder, 'incomplete_orders.xlsx');
    }
    public function export_completed_orders(){

        return Excel::download(new ExportCompletedOrder, 'completed_orders.xlsx');
    }
    public function export_approved_orders()
    {

        return Excel::download(new ExportApprovedOrder, 'approved_orders.xlsx');
    }
    public function export_cancel_orders()
    {

        return Excel::download(new ExportCancelOrder, 'cancel_orders.xlsx');
    }
    public function export_unapproved_orders()
    {

        return Excel::download(new ExportUnapprovedOrder, 'unapproved_orders.xlsx');
    }

    // apply excel for agent:
        public function agent_export_orders(){

            return Excel::download(new ExportAgentOrder, 'orders.xlsx');

        }
        public function overall_agent_export_pending_orders(){

            return Excel::download(new OverallExportAgentPendingOrder, 'pending_orders.xlsx');
        }

        public function agent_export_pending_orders(){

            return Excel::download(new ExportAgentPendingOrder, 'pending_orders.xlsx');
        }
        public function agent_export_incomplete_orders(){

            return Excel::download(new ExportAgentIncompleteOrder, 'incomplete_orders.xlsx');
        }
        public function agent_export_completed_orders(){

            return Excel::download(new ExportAgentCompletedOrder, 'completed_orders.xlsx');
        }
        public function agent_export_approved_orders()
        {

            return Excel::download(new ExportAgentApprovedOrder, 'approved_orders.xlsx');
        }
        public function agent_export_cancel_orders()
        {

            return Excel::download(new ExportAgentCancelOrder, 'cancel_orders.xlsx');
        }
        public function agent_export_unapproved_orders()
        {

            return Excel::download(new ExportAgentUnapprovedOrder, 'unapproved_orders.xlsx');
        }



    //  filter ride status from haris

    // public function agent_rides_window(Request $request, $ordertype){
    //     // $company = auth()->user()->active_company();

    //     $queryAgent = auth()->user()->partner_id;
    //     $fromDate = $request->input('date');
    //     $toDate = $request->input('to_date');
    //     $status = $request->input('status');

    //     // dd($queryAgent,$fromDate,$toDate,$status);

    //     $orders = OrderDetail::query();

    //     // dd($orders);

    //     // Apply filters based on the request inputs
    //     if($status){

    //         // $cleanStatus = strtok($status, '-');
    //         // dd($cleanStatus);

    //         $ordertype = $status;
    //         // dd($ordertype);


    //         // $orders->whereHas('order',function($query) use ($queryAgent){
    //         //     $query->where('business_partner_id',$queryAgent);
    //         // })->where('status',$cleanStatus)->paginate(10);
    //     }

    //     if ($queryAgent) {
    //         $orders->whereHas('order', function($query) use ($queryAgent) {
    //             $query->where('business_partner_id', $queryAgent);
    //         });
    //     }
    //      if ($fromDate && $toDate) {
    //         $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->whereBetween('date', [$fromDate, $toDate]);

    //     } elseif ($fromDate) {
    //         $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->whereDate('date', '>=', $fromDate);
    //     } elseif ($toDate) {
    //         $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->whereDate('date', '<=', $toDate);
    //     }
    //     // if ($fromDate) {
    //     //     $orders->whereDate('date', $fromDate);
    //     // }
    //     // dd($ordertype);

    //     // Apply order type filter
    //     if($ordertype == 'total-orders'){
    //         // dd($ordertype);
    //         $orders = $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->paginate(10);
    //     }
    //     if($ordertype == 'pending-orders'){
    //         $orders = $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->where('status','pending')->paginate(10);
    //     }
    //     if($ordertype == 'incomplete-orders'){
    //         $orders = $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->where('status','incomplete')->paginate(10);
    //     }
    //     if($ordertype == 'completed-orders'){
    //         // dd($ordertype);
    //         $orders = $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->where('status','completed')->paginate(10);
    //     }
    //     if($ordertype == 'approved-orders'){
    //         $orders = $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->where('status','approved')->paginate(10);
    //     }
    //     if($ordertype == 'unapproved-orders'){
    //         $orders = $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->where('status','unapproved')->paginate(10);
    //     }
    //     if($ordertype == 'cancelled-orders'){
    //         $orders = $orders->whereHas('order',function($query){
    //             $query->where('business_partner_id',auth()->user()->partner_id);
    //         })->where('status','cancelled')->paginate(10);
    //     }
    //     // to define all statuses in dropdown:

    //     $statuses = ['total-orders','pending-orders', 'approved-orders', 'unapproved-orders', 'completed-orders', 'cancelled-orders', 'incomplete-orders'];

    //     return view('agent-rides-status.index', compact('orders', 'ordertype','fromDate','toDate','statuses'))
    //         ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    // }

    // filter ride status from saba
     public function agent_rides_window(Request $request, $ordertype){
        $company_id = auth()->user()->active_company();

        // dd($company);

        // $queryAgent = auth()->user()->partner_id;
        $fromDate = $request->input('date');
        $toDate = $request->input('to_date');
        $status = $request->input('status');

        // dd($queryAgent,$fromDate,$toDate,$status);

        $orders = OrderDetail::query();

        // dd($orders);

        // Apply filters based on the request inputs
        if($status){

            // $cleanStatus = strtok($status, '-');
            // dd($cleanStatus);

            $ordertype = $status;
            // dd($ordertype);


            // $orders->whereHas('order',function($query) use ($queryAgent){
            //     $query->where('business_partner_id',$queryAgent);
            // })->where('status',$cleanStatus)->paginate(10);
        }

        if ($company_id ) {
            $orders->whereHas('order', function($query) use ($company_id ) {
                $query->where('company_id', $company_id );
            });
        }
         if ($fromDate && $toDate) {
            $orders->whereHas('order',function($query) use ($company_id){
                $query->where('company_id', $company_id);
            })->whereBetween('date', [$fromDate, $toDate]);

        } elseif ($fromDate) {
            $orders->whereHas('order',function($query)  use ($company_id){
                $query->where('company_id', $company_id);
            })->whereDate('date', '>=', $fromDate);
        } elseif ($toDate) {
            $orders->whereHas('order',function($query) use ($company_id){
                $query->where('company_id', $company_id);

            })->whereDate('date', '<=', $toDate);
        }

        //    dd($orders->get());
        // if ($fromDate) {
        //     $orders->whereDate('date', $fromDate);
        // }
        // dd($ordertype);

        // Apply order type filter
        if($ordertype == 'total-orders'){
            // dd($ordertype);
            $orders = $orders->whereHas('order',function($query) use ($company_id){
                $query->where('company_id', $company_id);
            })->paginate(10);
        }
        if($ordertype == 'pending-orders'){
            $orders = $orders->whereHas('order',function($query) use($company_id){
                $query->where('company_id', $company_id);
            })->where('status','pending')->paginate(10);
        }
        if($ordertype == 'incomplete-orders'){
            $orders = $orders->whereHas('order',function($query) use($company_id){
                $query->where('company_id', $company_id);
            })->where('status','incomplete')->paginate(10);
        }
        if($ordertype == 'completed-orders'){
            // dd($ordertype);
            $orders = $orders->whereHas('order',function($query) use($company_id){
                $query->where('company_id', $company_id);
            })->where('status','completed')->paginate(10);
        }
        if($ordertype == 'approved-orders'){
            $orders = $orders->whereHas('order',function($query) use($company_id){
                $query->where('company_id', $company_id);
            })->where('status','approved')->paginate(10);
        }
        if($ordertype == 'unapproved-orders'){
            $orders = $orders->whereHas('order',function($query) use($company_id){
                $query->where('company_id', $company_id);
            })->where('status','unapproved')->paginate(10);
        }
        if($ordertype == 'cancelled-orders'){
            $orders = $orders->whereHas('order',function($query) use($company_id){
                $query->where('company_id', $company_id);
            })->where('status','cancelled')->paginate(10);
        }
        // to define all statuses in dropdown:

        $statuses = ['total-orders','pending-orders', 'approved-orders', 'unapproved-orders', 'completed-orders', 'cancelled-orders', 'incomplete-orders'];

        return view('agent-rides-status.index', compact('orders', 'ordertype','fromDate','toDate','statuses'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }


    public function total_rides(){
        $company_id = auth()->user()->active_company();
        $ordertype='total-orders';
        $statuses = ['total-orders','pending-orders', 'approved-orders', 'unapproved-orders', 'completed-orders', 'cancelled-orders', 'incomplete-orders'];


        // dd($ordertype);
        $orders = OrderDetail::whereHas('order', function ($query) use ($company_id) {
            $query->where('company_id', $company_id);
        })->paginate(10);
        return view('agent-rides-status.index', compact('orders','ordertype','statuses'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }




}
