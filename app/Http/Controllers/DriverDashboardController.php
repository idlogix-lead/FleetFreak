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
use App\Models\User;

class DriverDashboardController extends Controller
{
    function __construct(){
        $this->middleware('RolePermissions');
    }
    public function index(Request $request){
        // super admin dashboard
        if(auth()->user()->actor_id==1){
            return view('home_dashboard.superadmin_dashboard');
        }
        // admin filters
        if(auth()->user()->actor_id==2){

            $license_expiry_date_details =  Partner::where('company_id',auth()->user()->active_company())->whereDate('licensee_expiry_date','<=',Carbon::today())->get();
            $cnic_expiry_date_details =  Partner::where('company_id',auth()->user()->active_company())->whereDate('nic_expiry_date','<=',Carbon::today())->get();
            $license_expiry_count = $license_expiry_date_details->count();
            $cnic_expiry_count  = $cnic_expiry_date_details ->count();
            return view('home_dashboard.driver_dashboard',
                compact('license_expiry_date_details','cnic_expiry_date_details','license_expiry_count','cnic_expiry_count')
           
                // for admin
    
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
