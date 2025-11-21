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
use App\Models\AccountTransaction;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\User;
use App\Models\UserCompany;

class VehicleDashboardController extends Controller
{
    function __construct(){
        $this->middleware('RolePermissions');
    }
    public function index(Request $request){
        // super admin dashboard
        if(auth()->user()->actor_id==1){
            return view('home_dashboard.superadmin_dashboard');
        }
        $from_date = $request->input('date');
        $to_date = $request->input('to_date');
        $vehicle_id = $request->input('vehicle_id');
        // dd($request);
        // admin filters
        if(auth()->user()->actor_id==2){


            $route_expiry_details =  Vehicle::where('company_id',auth()->user()->active_company())->whereDate('route_permits_expiry_date','<=',Carbon::today())->when($vehicle_id, function($q) use ($vehicle_id) {
                $q->where('id', $vehicle_id);
            })->get();
            // $maintainence_vehicles =  Event::with('vehicle')->where('company_id',auth()->user()->active_company())->whereIn('action',['planed_maintenance'])->when($from_date && $to_date, function($q) use ($from_date, $to_date) {
            //     $q->whereBetween('date', [$from_date, $to_date]);
            // })
            // ->when($from_date && !$to_date, function($q) use ($from_date) {
            //     $q->where('date', '>=', $from_date);
            // })
            // ->when(!$from_date && $to_date, function($q) use ($to_date) {
            //     $q->where('date', '<=', $to_date);
            // })
            // ->when($vehicle_id, function($q) use ($vehicle_id) {
            //     $q->where('source_id', $vehicle_id);
            // })
            // ->orderBy('date')->get();
            $maintainence_vehicles =  Invoice::with('vehicle')->where('company_id',auth()->user()->active_company())->where('document_status','completed')->where('document_type_id',5)->when($from_date && $to_date, function($q) use ($from_date, $to_date) {
                $q->whereBetween('date', [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function($q) use ($from_date) {
                $q->where('date', '>=', $from_date);
            })
            ->when(!$from_date && $to_date, function($q) use ($to_date) {
                $q->where('date', '<=', $to_date);
            })
            ->when($vehicle_id, function($q) use ($vehicle_id) {
                $q->where('vehicle_id', $vehicle_id);
            })
            ->orderBy('date')->get();
            $maintainence_vehicles_count = $maintainence_vehicles->count();
            // dd($maintainence_vehicles_count);

            $vehicle_busy = OrderDetail::with('vehicle')->where('company_id',auth()->user()->active_company())->where('status', 'incomplete')
            // ->whereHas('order', function($q){
            //     $q->whereIn('trip_type',['passenger_trip','cargo_trip',]);
            // })
            ->whereNotNull('vehicle_id')->when($from_date && $to_date, function($q) use ($from_date, $to_date) {
                $q->whereBetween('date', [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function($q) use ($from_date) {
                $q->where('date', '>=', $from_date);
            })
            ->when(!$from_date && $to_date, function($q) use ($to_date) {
                $q->where('date', '<=', $to_date);
            })
            ->when($vehicle_id, function($q) use ($vehicle_id) {
                $q->where('vehicle_id', $vehicle_id);
            })
            ->orderBy('date')->get()->groupBy('order.trip_type');
            // dd($vehicle_busy);
            
            $events = [];
            foreach ($vehicle_busy as $tripType => $details) {
                foreach ($details as $detail) {
                    $events[] = [
                        'id' => $detail->id,
                        'vehicle_no' => $detail->vehicle->vehicle_no,
                        'vehicle_model' => $detail->vehicle->vehicleModel->name,
                        'from_loc' => $detail->from_loc,
                        'to_loc' => $detail->to_loc,
                        'title' =>  $tripType,
                        'status' =>  $detail->status,
                        'date' =>  $detail->date,
                        'end_date' =>  $detail->end_date,
                        'pickup_time' =>  $detail->pickup_time,
                        'estimated_time' =>  $detail->estimated_time,
                        'driver'=> isset($detail->driver) && $detail->driver ? $detail->driver->name : '---',
                        'start' => Carbon::parse($detail->date)->toIso8601String(),
                        'end' => Carbon::parse($detail->date)->endOfDay()->toIso8601String(), // End time same day

                    ];
                }
            }
            // dd($events);


            $vehicle_rides_busy_count = $vehicle_busy->map(function($key){
                return $key->count();
            });
            // ------------------------------------------

            $inspection_overdue =  Event::with('vehicle')->where('company_id',auth()->user()->active_company())->whereIn('action',['planed_maintenance'])->whereDate('date','<=',Carbon::today())->get()->count();
            $inspection_duesoon =  Event::with('vehicle')->where('company_id',auth()->user()->active_company())->whereIn('action',['planed_maintenance'])->whereDate('date', [Carbon::today(), Carbon::today()->addDays(7)])->get()->count();
            // dd($inspection_overdue);
            $all_orders = OrderDetail::where('company_id',auth()->user()->active_company())->whereHas('order', function($q){
                $q->whereIn('trip_type',['monthly_booking','tour_booking','daily_booking']);
            })->where('status', 'incomplete')->get()->groupBy('order.trip_type');
            $countsByTripType = $all_orders->map(function ($group) {
                return $group->count();
            });   



            // top 10 highest revenue generated vehicles:

            $topVehicles = AccountTransaction::where('account_id', 5) // Filter for ride revenue
                ->join('order_lines', 'account_transactions.line_id', '=', 'order_lines.id') // Join OrderDetail
                ->join('vehicles', 'order_lines.vehicle_id', '=', 'vehicles.id') // Join Vehicles
                ->select('order_lines.vehicle_id', 'vehicles.vehicle_no', DB::raw('SUM(account_transactions.credit) as total_revenue')) // Sum revenue
                ->groupBy('order_lines.vehicle_id', 'vehicles.vehicle_no') // Group by vehicle_id
                ->orderByDesc('total_revenue') // Order by highest revenue
                ->limit(10)
                ->get(); // Get top 10 vehicles
                
            $maxRevenue = $topVehicles->max('total_revenue');
            // dd($topVehicles);
            // ------------------
            // dd($countsByTripType);        
            // dd($vehicle_busy_count['passenger_trip']);

            // if($route_expiry_details){
            //     foreach($route_expiry_details as $route_expiry_detail){
            //         $title = "vehicle".$route_expiry_detail->vehicle_no. "route expired";
            //         $description = "your vehicle route has been expired";
            //         // create notifications
            //         $notifications = [];
            //         if ($route_expiry_detail->driver_id) {
            //             $notifications[] = [
            //                 'receiver_partner_id' => $route_expiry_detail->driver_id,
            //                 'sender_id' => auth()->user()->id,
            //                 'calendar' => 1,
            //                 // 'sms' => 1,
            //                 // 'email' => 1,
            //                 // 'whatsapp' => 1,
            //                 // 'fcm_mobile_push' => 1,
            //                 // 'fcm_web_push' => 1,
            //             ];
            //         }

            //         // if($data['business_partner_id']){
            //         //     $notifications[] = [
            //         //         'receiver_id' => intval($data['business_partner_id']),
            //         //         'sender_id' => $auth_user_id,
            //         //         'calendar' => 1,
            //         //         // 'sms' => 1,
            //         //         // 'email' => 1,
            //         //         // 'whatsapp' => 1,
            //         //         // 'fcm_mobile_push' => 1,
            //         //         // 'fcm_web_push' => 1,
            //         //     ];
            //         // }
            //         $admin_user = UserCompany::where('company_id', $route_expiry_detail->company_id)->whereHas('user', function ($user) {
            //             return $user->where('is_company_admin', 1);
            //         })->first();
            //         if ($admin_user) {
            //             $notifications[] = [
            //                 'receiver_id' => $admin_user->user_id,
            //                 'sender_id' => auth()->user()->id,
            //                 'calendar' => 1,
            //                 // 'sms' => 1,
            //                 // 'email' => 1,
            //                 // 'whatsapp' => 1,
            //                 // 'fcm_mobile_push' => 1,
            //                 // 'fcm_web_push' => 1,
            //             ];
            //         }

            //         // dd($notifications);

            //         // dd($order->overall_status);
                   
            //             Event::createEvent(43, $route_expiry_detail->id, $route_expiry_detail->vehicle_no, 'route_expire', $title, $description, $action_details3 = null, auth()->user()->active_company(), $route_expiry_detail->route_permits_expiry_date, $notifications);


            //     }
                
            // }
            // if ($request->ajax()) {
            //     return view('home_dashboard.vehicle_dashboard_partial', compact('route_expiry_details','maintainence_vehicles','vehicle_busy','vehicle_rides_busy_count','maintainence_vehicles_count','inspection_overdue','inspection_duesoon','countsByTripType','events','topVehicles','maxRevenue'))->render();
            // }

            return view('home_dashboard.vehicle_dashboard',
                compact('route_expiry_details','maintainence_vehicles','vehicle_busy','vehicle_rides_busy_count','maintainence_vehicles_count','inspection_overdue','inspection_duesoon','countsByTripType','events','topVehicles','maxRevenue')
           
    
            );
        }
        else{
            return view('home_dashboard.dashboard');
        }

        
    }

   




}
