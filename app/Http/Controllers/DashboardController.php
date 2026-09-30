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

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware('RolePermissions');
    }
    public function index(Request $request)
    {
        // super admin dashboard
        if (auth()->user()->actor_id == 1) {
            return view('home_dashboard.superadmin_dashboard');
        }
        // admin filters

        $agentId = $request->input('agent');
        // $agentName=$request->input('agent_name');
        // $agentName = Partner::where('id', $agentId)->whereHas('users')->get();

        // dd($agentName);
        $fromDate = $request->input('date');
        $toDate = $request->input('to_date');
        $countryquery = $request->input('country');
        $query = OrderDetail::query();
        if (auth()->user()->actor_id == 2 & $agentId != null) {
            $agentName = Partner::where('id', $agentId)->first()->company_name;
        }


        // Apply filters if present
        if ($agentId) {

            $query = $query->whereHas('order', function ($q) use ($agentId) {
                $q->where('business_partner_id', $agentId);
            });
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('date', '<=', $toDate);
        }

        // if ($date) {

        //     $query = $query->whereDate('date', $date);
        // }


        // Fetch the filtered orders
        $totalOrders = OrderDetail::totalOrders(clone $query);
        $completedOrders = OrderDetail::completedOrders(clone $query);
        $pendingOrders = OrderDetail::pendingOrders(clone $query);
        $approvedOrders = OrderDetail::approvedOrders(clone $query);
        $unapprovedOrders = OrderDetail::unapprovedOrders(clone $query);
        $cancelOrders = OrderDetail::cancelOrders(clone $query);
        $incompleteOrders = OrderDetail::incompleteOrders(clone $query);
        $incompleteOrdersWithDriver = OrderDetail::incompleteOrdersWithDriver(clone $query);
        $incompleteOrderWithoutDriver = OrderDetail::incompleteOrdersWithoutDriver(clone $query);


        // fetch the filtered orders for agent
        $agenttotalOrders = OrderDetail::agentTotalOrders(clone $query);
        $agentcompletedOrders = OrderDetail::agentCompletedOrders(clone $query);
        $agentpendingOrders = OrderDetail::agentPendingOrders(clone $query);
        $agentapprovedOrders = OrderDetail::agentApprovedOrders(clone $query);
        $agentunapprovedOrders = OrderDetail::agentUnapprovedOrders(clone $query);
        $agentcancelOrders = OrderDetail::agentCancelOrders(clone $query);
        $agentincompleteOrders = OrderDetail::agentIncompleteOrders(clone $query);

        // ------------
        $company_id = auth()->user()->active_company();

        $unbookedCount = Vehicle::whereDoesntHave('orderDetails')->orWhereHas('orderDetails', function ($query) {
            $query->whereIn('status', ['paid', 'completed']);
        })->count();
        // for count
        $bookedCount = Vehicle::whereHas('orderDetails', function ($query) {
            $query->where('status', 'incomplete')->where('date', Carbon::today());
        })->count();
        // for getting records:
        $vehicle_assignments = OrderDetail::where('company_id', $company_id)->where('status', 'incomplete')->where('date', Carbon::today())->get();
        // dd($vehicle_assignments);
        // daily rides
        // Apply country filter to dailyrides
        $dailyridesQuery = OrderDetail::with('vehicle', 'rate_list')
            ->where('company_id', $company_id)
            ->where('status', 'incomplete')
            ->whereNotNull('rate_list_id')
            ->whereDate('date', Carbon::today())
            ->when($countryquery, function ($q) use ($countryquery) {
                $q->whereHas('order.partner_customer', function ($qr) use ($countryquery) {
                    $qr->where('country', $countryquery);
                });
                // dd($countryquery);

            });

        // if ($countryquery) {
        //     dd($countryquery);
        //     $dailyridesQuery = $dailyridesQuery
        //     ->whereHas('order.partner_customer', function($q) use ($countryquery) {
        //     return $q->where('country', $countryquery);
        // });
        // }

        $dailyrides = $dailyridesQuery->get();
        // dd($dailyrides);


        // Group daily rides by vehicle id
        $groupedRides = $dailyrides->groupBy('vehicle_id');

        // Initialize an array to store aggregated data
        $vehicleBookings = [];

        // Iterate through grouped rides
        foreach ($groupedRides as $vehicleId => $rides) {
            // Calculate total estimated time for this vehicle
            $totalEstimatedTime = $rides->sum(function ($ride) {
                return $ride->rate_list->estimated_time ?? 0;
            });

            // Get vehicle details (assuming vehicle model and number are concatenated)
            // dd($rides->first()->vehicleModel->name);
            $vehicleDetails = $rides->first()->vehicle->vehicleModel->name . ' ' . $rides->first()->vehicle_no;


            $vehicleCount = $rides->count();

            // Push data to $vehicleBookings array
            $vehicleBookings[] = [
                'x' => $vehicleDetails ?? 'Unknown Vehicle',
                'y' => round($totalEstimatedTime / 60, 2),
                'vehiclesCount' => $vehicleCount,
            ];
        }
        // $company_id = auth()->user()->active_company();


        $orderDetailsWithVehicle = OrderDetail::whereNotNull('vehicle_id')
            ->where('company_id', $company_id)
            ->whereIn('status', ['incomplete', 'in_progress'])->whereBetween('date', [Carbon::today(), Carbon::today()->addDays(7)])->get();
        // $allcustomer = Partner::where('actor_id',6)->get();
        // -------------getting top agents with highest sales----------
        $topAgents = Partner::whereHas('orders_agent.order_details', function ($query) use ($company_id) {
            $query->where('status', 'completed')->where('company_id', $company_id);
        })
            ->where('actor_id', 4)->get()
            ->map(function ($partner) {
                $totalSales = $partner->orders_agent->sum(function ($order) {
                    return $order->order_details->sum('rate');
                });
                return [
                    'agent_name' => $partner->name, // Adjust according to your column name
                    'total_sales' => $totalSales,
                ];
            })

            ->sortByDesc('total_sales')
            ->take(5);
        $company_id = auth()->user()->active_company();

        // -----------------------End-------------------------------
        // dd($topAgents);
        $unpaidrides = OrderDetail::whereHas('order', function ($queryBuilder) {
            $queryBuilder->where('overall_status', 'approved');
        })->where('status', 'completed')->count();
        $paidrides = OrderDetail::whereHas('order', function ($queryBuilder) {
            $queryBuilder->where('overall_status', 'approved');
        })->where('status', 'paid')->count();
        $drivers = Partner::where('actor_id', 5)
            ->where('company_id', $company_id)
            ->get();
        $employees = Partner::where('actor_id', 7)->get();
        // for agent:
        $agentallcustomer = Partner::where('actor_id', 6)->where('business_partner_id', auth()->user()->partner_id)->get();

        // only run when login user is agent
        if (auth()->user()->actor_id == 4) {

            $agentQuery = auth()->user()->partner_id;
            $fromDate = Carbon::now()->subDays(7)->startOfDay();
            $toDate = Carbon::now()->endOfDay();
            // Opening line summary query before fromDate
            $openingSummaryQuery = Order::query()
                ->select(
                    DB::raw("'' as agent"),
                    DB::raw("'' as customer"),
                    DB::raw("'OPN' as trtype"),
                    DB::raw("'' as tr_date"),
                    DB::raw("'' as tr_no"),
                    DB::raw("'' as description"),
                    DB::raw("sum(NULLIF(od.rate, '')::numeric) as debit"),
                    DB::raw("0 as credit")
                )
                ->join('order_lines as od', 'orders.id', '=', 'od.order_id')
                ->whereIn('od.status', ['completed', 'paid'])
                ->when($agentQuery, function ($query) use ($agentQuery) {
                    $query->where('orders.business_partner_id', $agentQuery);
                })
                ->when($fromDate, function ($query) use ($fromDate) {
                    $query->where('od.date', '<', $fromDate);
                })
                ->unionAll(
                    PaymentHeader::query()
                        ->select(
                            DB::raw("'' as agent"),
                            DB::raw("'' as customer"),
                            DB::raw("'OPN' as trtype"),
                            DB::raw("'' as tr_date"),
                            DB::raw("'' as tr_no"),
                            DB::raw("'' as description"),
                            DB::raw("0 as debit"),
                            DB::raw("sum(NULLIF(payment_lines.total_amount, '')::numeric) as credit")
                        )
                        ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
                        ->when($agentQuery, function ($query) use ($agentQuery) {
                            $query->where('payment_headers.agent_id', $agentQuery);
                        })
                        ->when($fromDate, function ($query) use ($fromDate) {
                            $query->where('payment_headers.date', '<', $fromDate);
                        })
                );

            // Order query between fromDate and toDate
            $orderQuery = Order::query()
                ->select(
                    'partners.name as agent',
                    'customers.name as customer',
                    DB::raw("'inv' as trtype"),
                    DB::raw("order_lines.date::text as tr_date"),
                    DB::raw("concat(orders.order_no, '-', order_lines.id) as tr_no"),
                    'orders.description as description',
                    DB::raw("NULLIF(order_lines.rate, '')::numeric as debit"),
                    DB::raw("0 as credit")
                )
                ->join('order_lines', 'orders.id', '=', 'order_lines.order_id')
                ->join('partners', 'orders.business_partner_id', '=', 'partners.id')
                ->join('partners as customers', 'orders.customer_partner_id', '=', 'customers.id')
                ->whereIn('order_lines.status', ['completed', 'paid'])
                ->when($agentQuery, function ($query) use ($agentQuery) {
                    $query->where('partners.id', $agentQuery);
                })
                ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('order_lines.date', [$fromDate, $toDate]);
                });

            // Payment query between fromDate and toDate
            $paymentQuery = PaymentHeader::query()
                ->select(
                    'agents.name as agent',
                    'customers.name as customer',
                    DB::raw("'pay' as trtype"),
                    DB::raw("payment_headers.date::text as tr_date"),
                    'payment_headers.payment_no as tr_no',
                    'payment_headers.description as description',
                    DB::raw("0 as debit"),
                    DB::raw("NULLIF(payment_lines.total_amount, '')::numeric as credit")
                )
                ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
                ->join('partners as agents', 'payment_headers.agent_id', '=', 'agents.id')
                ->join('partners as customers', 'payment_headers.customer_id', '=', 'customers.id')
                ->when($agentQuery, function ($query) use ($agentQuery) {
                    $query->where('agents.id', $agentQuery);
                })
                ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('payment_headers.date', [$fromDate, $toDate]);
                });

            // Combining all queries
            $combinedQuery = $openingSummaryQuery
                ->unionAll($orderQuery)
                ->unionAll($paymentQuery);

            // Build the complete query and execute. fromSub() takes the SQL and
            // the bindings from the same (organization-scoped) builder; pairing
            // toSql() with mergeBindings(getQuery()) dropped the first leg's
            // company_id binding and shifted every later one.
            $subquerySql = DB::query()->fromSub($combinedQuery, 'tmptable')
                ->select(
                    'tmptable.agent',
                    'tmptable.customer',
                    'tmptable.trtype',
                    'tmptable.tr_date',
                    'tmptable.tr_no',
                    'tmptable.description',
                    DB::raw('SUM(tmptable.debit) as debit'),
                    DB::raw('SUM(tmptable.credit) as credit')
                )
                ->groupBy('tmptable.agent', 'tmptable.customer', 'tmptable.trtype', 'tmptable.tr_date', 'tmptable.tr_no', 'tmptable.description')
                ->orderBy('tmptable.agent')
                ->orderBy('tmptable.customer')
                ->orderBy('tmptable.tr_date')
                ->orderBy('tmptable.trtype')
                ->get();

            // Execute the complete query
            $ledgerEntries = $subquerySql->toArray();
        } else {
            $ledgerEntries = null;
        }
        // -----------------------------------


        return view('home_dashboard.dashboard', [
            'unbookedCount' => $unbookedCount,
            'bookedCount' => $bookedCount,
            'vehicle_assignments' => $vehicle_assignments,
            'orderDetailsWithVehicle' => $orderDetailsWithVehicle,
            'topAgents' => $topAgents,
            'unpaidrides' => $unpaidrides,
            'paidrides' => $paidrides,
            'drivers' => $drivers,
            'employees' => $employees,
            // for agent
            'agentallcustomer' => $agentallcustomer,
            'agenttotalOrders' => $agenttotalOrders,
            'agentcompletedOrders' => $agentcompletedOrders,
            'agentpendingOrders' => $agentpendingOrders,
            'agentapprovedOrders' => $agentapprovedOrders,
            'agentcancelOrders' => $agentcancelOrders,
            'agentincompleteOrders' => $agentincompleteOrders,
            'agentunapprovedOrders' => $agentunapprovedOrders,
            // for admin
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'approvedOrders' => $approvedOrders,
            'unapprovedOrders' => $unapprovedOrders,
            'cancelOrders' => $cancelOrders,
            'incompleteOrders' => $incompleteOrders,
            'vehicleBookings' => $vehicleBookings,
            'ledgerEntries' => $ledgerEntries,
            'agentName' => $agentName ?? '',
            'incompleteOrdersWithDriver' => $incompleteOrdersWithDriver,
            'incompleteOrderWithoutDriver' => $incompleteOrderWithoutDriver


        ]);
    }

    public function indexNew(Request $request)
    {
        // super admin dashboard
        if (auth()->user()->actor_id == 1) {
            return view('home_dashboard.superadmin_dashboard');
        }
        // admin filters

        $agentId = $request->input('agent');
        // $agentName=$request->input('agent_name');
        // $agentName = Partner::where('id', $agentId)->whereHas('users')->get();

        // dd($agentName);
        $fromDate = $request->input('date');
        $toDate = $request->input('to_date');
        $countryquery = $request->input('country');
        $query = OrderDetail::query();
        if (auth()->user()->actor_id == 2 & $agentId != null) {
            $agentName = Partner::where('id', $agentId)->first()->company_name;
        }


        // Apply filters if present
        if ($agentId) {

            $query = $query->whereHas('order', function ($q) use ($agentId) {
                $q->where('business_partner_id', $agentId);
            });
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('date', '<=', $toDate);
        }

        // if ($date) {

        //     $query = $query->whereDate('date', $date);
        // }


        // Fetch the filtered orders
        $totalOrders = OrderDetail::totalOrders(clone $query);
        $completedOrders = OrderDetail::completedOrders(clone $query);
        $pendingOrders = OrderDetail::pendingOrders(clone $query);
        $approvedOrders = OrderDetail::approvedOrders(clone $query);
        $unapprovedOrders = OrderDetail::unapprovedOrders(clone $query);
        $cancelOrders = OrderDetail::cancelOrders(clone $query);
        $incompleteOrders = OrderDetail::incompleteOrders(clone $query);
        $incompleteOrdersWithDriver = OrderDetail::incompleteOrdersWithDriver(clone $query);
        $incompleteOrderWithoutDriver = OrderDetail::incompleteOrdersWithoutDriver(clone $query);


        // fetch the filtered orders for agent
        $agenttotalOrders = OrderDetail::agentTotalOrders(clone $query);
        $agentcompletedOrders = OrderDetail::agentCompletedOrders(clone $query);
        $agentpendingOrders = OrderDetail::agentPendingOrders(clone $query);
        $agentapprovedOrders = OrderDetail::agentApprovedOrders(clone $query);
        $agentunapprovedOrders = OrderDetail::agentUnapprovedOrders(clone $query);
        $agentcancelOrders = OrderDetail::agentCancelOrders(clone $query);
        $agentincompleteOrders = OrderDetail::agentIncompleteOrders(clone $query);

        // ------------
        $company_id = auth()->user()->active_company();

        $unbookedCount = Vehicle::whereDoesntHave('orderDetails')->orWhereHas('orderDetails', function ($query) {
            $query->whereIn('status', ['paid', 'completed']);
        })->count();
        // for count
        $bookedCount = Vehicle::whereHas('orderDetails', function ($query) {
            $query->where('status', 'incomplete')->where('date', Carbon::today());
        })->count();
        // for getting records:
        $vehicle_assignments = OrderDetail::where('company_id', $company_id)->where('status', 'incomplete')->where('date', Carbon::today())->get();
        // dd($vehicle_assignments);
        // daily rides
        // Apply country filter to dailyrides
        $dailyridesQuery = OrderDetail::with('vehicle', 'rate_list')
            ->where('company_id', $company_id)
            ->where('status', 'incomplete')
            ->whereNotNull('rate_list_id')
            ->whereDate('date', Carbon::today())
            ->when($countryquery, function ($q) use ($countryquery) {
                $q->whereHas('order.partner_customer', function ($qr) use ($countryquery) {
                    $qr->where('country', $countryquery);
                });
                // dd($countryquery);

            });

        // if ($countryquery) {
        //     dd($countryquery);
        //     $dailyridesQuery = $dailyridesQuery
        //     ->whereHas('order.partner_customer', function($q) use ($countryquery) {
        //     return $q->where('country', $countryquery);
        // });
        // }

        $dailyrides = $dailyridesQuery->get();
        // dd($dailyrides);


        // Group daily rides by vehicle id
        $groupedRides = $dailyrides->groupBy('vehicle_id');

        // Initialize an array to store aggregated data
        $vehicleBookings = [];

        // Iterate through grouped rides
        foreach ($groupedRides as $vehicleId => $rides) {
            // Calculate total estimated time for this vehicle
            $totalEstimatedTime = $rides->sum(function ($ride) {
                return $ride->rate_list->estimated_time ?? 0;
            });

            // Get vehicle details (assuming vehicle model and number are concatenated)
            // dd($rides->first()->vehicleModel->name);
            $vehicleDetails = $rides->first()->vehicle->vehicleModel->name . ' ' . $rides->first()->vehicle_no;


            $vehicleCount = $rides->count();

            // Push data to $vehicleBookings array
            $vehicleBookings[] = [
                'x' => $vehicleDetails ?? 'Unknown Vehicle',
                'y' => round($totalEstimatedTime / 60, 2),
                'vehiclesCount' => $vehicleCount,
            ];
        }
        // $company_id = auth()->user()->active_company();


        $orderDetailsWithVehicle = OrderDetail::whereNotNull('vehicle_id')
            ->where('company_id', $company_id)
            ->whereIn('status', ['incomplete', 'in_progress'])->whereBetween('date', [Carbon::today(), Carbon::today()->addDays(7)])->get();
        // $allcustomer = Partner::where('actor_id',6)->get();
        // -------------getting top agents with highest sales----------
        $topAgents = Partner::whereHas('orders_agent.order_details', function ($query) use ($company_id) {
            $query->where('status', 'completed')->where('company_id', $company_id);
        })
            ->where('actor_id', 4)->get()
            ->map(function ($partner) {
                $totalSales = $partner->orders_agent->sum(function ($order) {
                    return $order->order_details->sum('rate');
                });
                return [
                    'agent_name' => $partner->name, // Adjust according to your column name
                    'total_sales' => $totalSales,
                ];
            })

            ->sortByDesc('total_sales')
            ->take(5);
        $company_id = auth()->user()->active_company();

        // -----------------------End-------------------------------
        // dd($topAgents);
        $unpaidrides = OrderDetail::whereHas('order', function ($queryBuilder) {
            $queryBuilder->where('overall_status', 'approved');
        })->where('status', 'completed')->count();
        $paidrides = OrderDetail::whereHas('order', function ($queryBuilder) {
            $queryBuilder->where('overall_status', 'approved');
        })->where('status', 'paid')->count();
        $drivers = Partner::where('actor_id', 5)
            ->where('company_id', $company_id)
            ->get();
        $employees = Partner::where('actor_id', 7)->get();
        // for agent:
        $agentallcustomer = Partner::where('actor_id', 6)->where('business_partner_id', auth()->user()->partner_id)->get();

        // only run when login user is agent
        if (auth()->user()->actor_id == 4) {

            $agentQuery = auth()->user()->partner_id;
            $fromDate = Carbon::now()->subDays(7)->startOfDay();
            $toDate = Carbon::now()->endOfDay();
            // Opening line summary query before fromDate
            $openingSummaryQuery = Order::query()
                ->select(
                    DB::raw("'' as agent"),
                    DB::raw("'' as customer"),
                    DB::raw("'OPN' as trtype"),
                    DB::raw("'' as tr_date"),
                    DB::raw("'' as tr_no"),
                    DB::raw("'' as description"),
                    DB::raw("sum(NULLIF(od.rate, '')::numeric) as debit"),
                    DB::raw("0 as credit")
                )
                ->join('order_lines as od', 'orders.id', '=', 'od.order_id')
                ->whereIn('od.status', ['completed', 'paid'])
                ->when($agentQuery, function ($query) use ($agentQuery) {
                    $query->where('orders.business_partner_id', $agentQuery);
                })
                ->when($fromDate, function ($query) use ($fromDate) {
                    $query->where('od.date', '<', $fromDate);
                })
                ->unionAll(
                    PaymentHeader::query()
                        ->select(
                            DB::raw("'' as agent"),
                            DB::raw("'' as customer"),
                            DB::raw("'OPN' as trtype"),
                            DB::raw("'' as tr_date"),
                            DB::raw("'' as tr_no"),
                            DB::raw("'' as description"),
                            DB::raw("0 as debit"),
                            DB::raw("sum(NULLIF(payment_lines.total_amount, '')::numeric) as credit")
                        )
                        ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
                        ->when($agentQuery, function ($query) use ($agentQuery) {
                            $query->where('payment_headers.agent_id', $agentQuery);
                        })
                        ->when($fromDate, function ($query) use ($fromDate) {
                            $query->where('payment_headers.date', '<', $fromDate);
                        })
                );

            // Order query between fromDate and toDate
            $orderQuery = Order::query()
                ->select(
                    'partners.name as agent',
                    'customers.name as customer',
                    DB::raw("'inv' as trtype"),
                    DB::raw("order_lines.date::text as tr_date"),
                    DB::raw("concat(orders.order_no, '-', order_lines.id) as tr_no"),
                    'orders.description as description',
                    DB::raw("NULLIF(order_lines.rate, '')::numeric as debit"),
                    DB::raw("0 as credit")
                )
                ->join('order_lines', 'orders.id', '=', 'order_lines.order_id')
                ->join('partners', 'orders.business_partner_id', '=', 'partners.id')
                ->join('partners as customers', 'orders.customer_partner_id', '=', 'customers.id')
                ->whereIn('order_lines.status', ['completed', 'paid'])
                ->when($agentQuery, function ($query) use ($agentQuery) {
                    $query->where('partners.id', $agentQuery);
                })
                ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('order_lines.date', [$fromDate, $toDate]);
                });

            // Payment query between fromDate and toDate
            $paymentQuery = PaymentHeader::query()
                ->select(
                    'agents.name as agent',
                    'customers.name as customer',
                    DB::raw("'pay' as trtype"),
                    DB::raw("payment_headers.date::text as tr_date"),
                    'payment_headers.payment_no as tr_no',
                    'payment_headers.description as description',
                    DB::raw("0 as debit"),
                    DB::raw("NULLIF(payment_lines.total_amount, '')::numeric as credit")
                )
                ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
                ->join('partners as agents', 'payment_headers.agent_id', '=', 'agents.id')
                ->join('partners as customers', 'payment_headers.customer_id', '=', 'customers.id')
                ->when($agentQuery, function ($query) use ($agentQuery) {
                    $query->where('agents.id', $agentQuery);
                })
                ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('payment_headers.date', [$fromDate, $toDate]);
                });

            // Combining all queries
            $combinedQuery = $openingSummaryQuery
                ->unionAll($orderQuery)
                ->unionAll($paymentQuery);

            // Build the complete query and execute. fromSub() takes the SQL and
            // the bindings from the same (organization-scoped) builder; pairing
            // toSql() with mergeBindings(getQuery()) dropped the first leg's
            // company_id binding and shifted every later one.
            $subquerySql = DB::query()->fromSub($combinedQuery, 'tmptable')
                ->select(
                    'tmptable.agent',
                    'tmptable.customer',
                    'tmptable.trtype',
                    'tmptable.tr_date',
                    'tmptable.tr_no',
                    'tmptable.description',
                    DB::raw('SUM(tmptable.debit) as debit'),
                    DB::raw('SUM(tmptable.credit) as credit')
                )
                ->groupBy('tmptable.agent', 'tmptable.customer', 'tmptable.trtype', 'tmptable.tr_date', 'tmptable.tr_no', 'tmptable.description')
                ->orderBy('tmptable.agent')
                ->orderBy('tmptable.customer')
                ->orderBy('tmptable.tr_date')
                ->orderBy('tmptable.trtype')
                ->get();

            // Execute the complete query
            $ledgerEntries = $subquerySql->toArray();
        } else {
            $ledgerEntries = null;
        }
        // -----------------------------------


        return view('home_dashboard.main', [
            'unbookedCount' => $unbookedCount,
            'bookedCount' => $bookedCount,
            'vehicle_assignments' => $vehicle_assignments,
            'orderDetailsWithVehicle' => $orderDetailsWithVehicle,
            'topAgents' => $topAgents,
            'unpaidrides' => $unpaidrides,
            'paidrides' => $paidrides,
            'drivers' => $drivers,
            'employees' => $employees,
            // for agent
            'agentallcustomer' => $agentallcustomer,
            'agenttotalOrders' => $agenttotalOrders,
            'agentcompletedOrders' => $agentcompletedOrders,
            'agentpendingOrders' => $agentpendingOrders,
            'agentapprovedOrders' => $agentapprovedOrders,
            'agentcancelOrders' => $agentcancelOrders,
            'agentincompleteOrders' => $agentincompleteOrders,
            'agentunapprovedOrders' => $agentunapprovedOrders,
            // for admin
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'approvedOrders' => $approvedOrders,
            'unapprovedOrders' => $unapprovedOrders,
            'cancelOrders' => $cancelOrders,
            'incompleteOrders' => $incompleteOrders,
            'vehicleBookings' => $vehicleBookings,
            'ledgerEntries' => $ledgerEntries,
            'agentName' => $agentName ?? '',
            'incompleteOrdersWithDriver' => $incompleteOrdersWithDriver,
            'incompleteOrderWithoutDriver' => $incompleteOrderWithoutDriver


        ]);
    }

    public function admin_rides_window(Request $request, $ordertype)
    {
        $queryAgent = $request->input('agent');

        $fromDate = $request->input('date');
        $toDate = $request->input('to_date');

        $orders = OrderDetail::query();

        // Apply filters based on the request inputs
        if ($queryAgent) {
            $orders->whereHas('order', function ($query) use ($queryAgent) {
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
        if ($ordertype == 'total-orders') {
            $orders = $orders->paginate(10);
        }
        if ($ordertype == 'pending-orders') {
            $orders = $orders->where('status', 'pending')->paginate(10);
        }
        if ($ordertype == 'incomplete-orders') {
            $orders = $orders->where('status', 'incomplete')->paginate(10);
        }
        if ($ordertype == 'completed-orders') {
            $orders = $orders->where('status', 'completed')->paginate(10);
        }
        if ($ordertype == 'approved-orders') {
            $orders = $orders->where('status', 'approved')->paginate(10);
        }
        if ($ordertype == 'unapproved-orders') {
            $orders = $orders->where('status', 'unapproved')->paginate(10);
        }
        if ($ordertype == 'cancelled-orders') {
            $orders = $orders->where('status', 'cancelled')->paginate(10);
        }

        return view('home_dashboard.total-order', compact('orders', 'ordertype'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }

    //  Apply Excel for admin

    public function export_orders()
    {

        return Excel::download(new ExportOrder, 'orders.xlsx');
    }
    public function overall_export_pending_orders()
    {

        return Excel::download(new OverallExportPendingOrder, 'orders.xlsx');
    }

    public function export_pending_orders()
    {

        return Excel::download(new ExportPendingOrder, 'pending_orders.xlsx');
    }
    public function export_incomplete_orders()
    {

        return Excel::download(new ExportIncompleteOrder, 'incomplete_orders.xlsx');
    }
    public function export_completed_orders()
    {

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
    public function agent_export_orders()
    {

        return Excel::download(new ExportAgentOrder, 'orders.xlsx');
    }
    public function overall_agent_export_pending_orders()
    {

        return Excel::download(new OverallExportAgentPendingOrder, 'pending_orders.xlsx');
    }

    public function agent_export_pending_orders()
    {

        return Excel::download(new ExportAgentPendingOrder, 'pending_orders.xlsx');
    }
    public function agent_export_incomplete_orders()
    {

        return Excel::download(new ExportAgentIncompleteOrder, 'incomplete_orders.xlsx');
    }
    public function agent_export_completed_orders()
    {

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
    public function agent_rides_window(Request $request, $ordertype)
    {
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
        if ($status) {

            // $cleanStatus = strtok($status, '-');
            // dd($cleanStatus);

            $ordertype = $status;
            // dd($ordertype);


            // $orders->whereHas('order',function($query) use ($queryAgent){
            //     $query->where('business_partner_id',$queryAgent);
            // })->where('status',$cleanStatus)->paginate(10);
        }

        if ($company_id) {
            $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            });
        }
        if ($fromDate && $toDate) {
            $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $orders->whereHas('order', function ($query)  use ($company_id) {
                $query->where('company_id', $company_id);
            })->whereDate('date', '>=', $fromDate);
        } elseif ($toDate) {
            $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->whereDate('date', '<=', $toDate);
        }

        //    dd($orders->get());
        // if ($fromDate) {
        //     $orders->whereDate('date', $fromDate);
        // }
        // dd($ordertype);

        // Apply order type filter
        if ($ordertype == 'total-orders') {
            // dd($ordertype);
            $orders = $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->paginate(10);
        }
        if ($ordertype == 'pending-orders') {
            $orders = $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->where('status', 'pending')->paginate(10);
        }
        if ($ordertype == 'incomplete-orders') {
            $orders = $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->where('status', 'incomplete')->paginate(10);
        }
        if ($ordertype == 'completed-orders') {
            // dd($ordertype);
            $orders = $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->where('status', 'completed')->paginate(10);
        }
        if ($ordertype == 'approved-orders') {
            $orders = $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->where('status', 'approved')->paginate(10);
        }
        if ($ordertype == 'unapproved-orders') {
            $orders = $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->where('status', 'unapproved')->paginate(10);
        }
        if ($ordertype == 'cancelled-orders') {
            $orders = $orders->whereHas('order', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->where('status', 'cancelled')->paginate(10);
        }
        // to define all statuses in dropdown:

        $statuses = ['total-orders', 'pending-orders', 'approved-orders', 'unapproved-orders', 'completed-orders', 'cancelled-orders', 'incomplete-orders'];

        return view('agent-rides-status.index', compact('orders', 'ordertype', 'fromDate', 'toDate', 'statuses'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }


    public function total_rides()
    {
        $company_id = auth()->user()->active_company();
        $ordertype = 'total-orders';
        $statuses = ['total-orders', 'pending-orders', 'approved-orders', 'unapproved-orders', 'completed-orders', 'cancelled-orders', 'incomplete-orders'];


        // dd($ordertype);
        $orders = OrderDetail::whereHas('order', function ($query) use ($company_id) {
            $query->where('company_id', $company_id);
        })->paginate(10);
        return view('agent-rides-status.index', compact('orders', 'ordertype', 'statuses'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
    }
}
