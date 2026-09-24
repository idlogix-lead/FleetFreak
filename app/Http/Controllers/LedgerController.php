<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Support\OrganizationAccess;
use App\Models\Order;
use App\Models\OrderDetail;
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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AgentLedgerExport;
use App\Exports\DriverLedgerExport;


/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class LedgerController extends Controller
{
    static $role_module_id = 17;
    static $ignores = [];
    function __construct(){
        $this->middleware('RolePermissions');
    }
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "Ledgers",
                'link' => route("ledgers.index"),
                'active' => true,
            ]
        ];
        if(auth()->user()->actor_id==2){
            $agentQuery = $request->input('agent');
        }
        else{
            $agentQuery = auth()->user()->partner_id;


        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // $orders = OrderDetail::whereHas('order', function($queryBuilder) {
        //         $queryBuilder->where('overall_status', 'approved');
        //     })
        //     ->where('status', 'completed')
        //     ->when($agentQuery, function ($queryBuilder) use ($agentQuery) {
        //         $queryBuilder->whereHas('order.partner_business', function ($subQuery) use ($agentQuery) {
        //             $subQuery->where('id', $agentQuery);
        //         });
        //     })
        //     ->when($fromDate && !$toDate, function ($queryBuilder) use ($fromDate) {
        //         $queryBuilder->whereDate('date', '>=', $fromDate);
        //     })
        //     ->when(!$fromDate && $toDate, function ($queryBuilder) use ($toDate) {
        //         $queryBuilder->whereDate('date', '<=', $toDate);
        //     })
        //     ->when($fromDate && $toDate, function ($queryBuilder) use ($fromDate, $toDate) {
        //         $queryBuilder->whereBetween('date', [$fromDate, $toDate]);
        //     })
        //     ->paginate();


        // sher ali raw query:
        // ---------------------------------------------------
        // if ($agentQuery || $fromDate || $toDate){







        //     $rawquery = " select tmptable.agent,tmptable.customer,tmptable.trtype,tmptable.tr_date,tmptable.tr_no,tmptable.description,sum(tmptable.debit) debit,sum(credit) credit from (

        //     select '' agent,'' customer,'OPN' trtype,'' tr_date,'' tr_no,'' description,sum(tmptable.debit) debit,sum(credit) credit from (
        //         SELECT bp.name agent,'inv' trtype,od.date tr_date,concat( o.order_no,'-',od.id) tr_no,o.description,o.booking_amount debit,0 credit
        //         FROM `orders` o join order_details od
        //         on o.id=od.order_id
        //         join partners bp on o.business_partner_id=bp.id
        //         where od.status in ('completed','paid')";

        //         if($agentQuery!=""){
        //             $rawquery = $rawquery ." and bp.id=".$agentQuery." ";
        //         }

        //         if($fromDate!="" && $toDate!="")
        //          $rawquery = $rawquery ." and od.date < '".$fromDate."' ";

        //          $rawquery = $rawquery ." union ALL
        //         SELECT agent.name agent,'pay' as trtype,ph.date,ph.payment_no,ph.description,0 as debit,pl.total_amount from payment_headers ph join payment_lines pl on ph.id=pl.payment_header_id
        //         join partners agent on ph.agent_id=agent.id
        //         join partners as customer on ph.customer_id=customer.id
        //         where 1=1 ";
        //         if($agentQuery!=""){
        //             "and agent.id=".$agentQuery." ";
        //          }

        //         if($fromDate!="" && $toDate!="")
        //             $rawquery = $rawquery ."  and ph.date < '".$fromDate."' ";

        //         $rawquery = $rawquery ." ) as tmptable
        //         union all


        //     SELECT bp.name agent,cs.name customer,'inv' trtype,od.date tr_date,concat( o.order_no,'-',od.id) tr_no,o.description,o.booking_amount debit,0 credit
        //         FROM `orders` o join order_details od
        //         on o.id=od.order_id
        //         join partners bp on o.business_partner_id=bp.id

        //         join partners cs on o.customer_partner_id=cs.id
        //         where od.status in ('completed','paid')";

        //         if($agentQuery!=""){
        //             $rawquery = $rawquery ." and bp.id=".$agentQuery." ";
        //         }

        //         if($fromDate!="" && $toDate!="")
        //          $rawquery = $rawquery ." and od.date between '".$fromDate."' and '".$toDate."'";

        //          $rawquery = $rawquery ." union ALL
        //         SELECT agent.name agent,cs.name customer,'pay' as trtype,ph.date,ph.payment_no,ph.description,0 as debit,pl.total_amount from payment_headers ph join payment_lines pl on ph.id=pl.payment_header_id
        //        join partners agent on ph.agent_id=agent.id
        //         join partners as cs on ph.customer_id=cs.id
        //          where 1=1 ";
        //         if($agentQuery!=""){
        //             "and agent.id=".$agentQuery." ";
        //          }

        //         if($fromDate!="" && $toDate!="")
        //             $rawquery = $rawquery ."  and ph.date between '".$fromDate."' and '".$toDate."' ";

        //         $rawquery = $rawquery ." ) as tmptable
        //         group by  tmptable.agent,tmptable.customer,tmptable.trtype,tmptable.tr_date,tmptable.tr_no,tmptable.description
        //         order by  tmptable.agent,tmptable.customer,tmptable.tr_date,tmptable.trtype;";

        //         // return $rawquery;
        //         $ledgerEntries = DB::select($rawquery);
        // }
        // ----------------------------------------------------
        if(auth()->user()->actor_id==2){


            if ($agentQuery || $fromDate || $toDate) {
                // Opening line summary query before fromDate
                $openingSummaryQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
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
                        PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
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
                $orderQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
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
                $paymentQuery = PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
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

                // Build the complete query and execute
                $subquerySql = DB::table(DB::raw("({$combinedQuery->toSql()}) as tmptable"))
                    ->mergeBindings($combinedQuery->getQuery()) // Bind the parameters from the combined query
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
            }
            else{
                $ledgerEntries = new LengthAwarePaginator([], 0, 15, 1, [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                ]);
            }
        }
        else{
            if ($fromDate || $toDate) {
                // Opening line summary query before fromDate
                $openingSummaryQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
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
                        PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
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
                $orderQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
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
                $paymentQuery = PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
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

                // Build the complete query and execute
                $subquerySql = DB::table(DB::raw("({$combinedQuery->toSql()}) as tmptable"))
                    ->mergeBindings($combinedQuery->getQuery()) // Bind the parameters from the combined query
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
            }
            else{
                $ledgerEntries = new LengthAwarePaginator([], 0, 15, 1, [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                ]);

            }

        }
        return view('ledgers.index', compact( 'breadcrumbs','ledgerEntries'))
            ->with('i', (request()->input('page', 1) - 1) * 1);
    }
    public function export(Request $request)
    {
        // dd($request->input('agent'));
        $breadcrumbs = [
            [
                'name' => "Ledgers",
                'link' => route("ledgers.index"),
                'active' => true,
            ]
        ];
        if(auth()->user()->actor_id==2){
            $agentQuery = $request->input('agent');
        }
        else{
            $agentQuery = auth()->user()->partner_id;


        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

       
        if(auth()->user()->actor_id==2){


            if ($agentQuery || $fromDate || $toDate) {
                // Opening line summary query before fromDate
                $openingSummaryQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
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
                        PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
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
                $orderQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
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
                $paymentQuery = PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
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

                // Build the complete query and execute
                $subquerySql = DB::table(DB::raw("({$combinedQuery->toSql()}) as tmptable"))
                    ->mergeBindings($combinedQuery->getQuery()) // Bind the parameters from the combined query
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
            }
            else{
                $ledgerEntries = new LengthAwarePaginator([], 0, 15, 1, [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                ]);
            }
        }
        else{
            if ($fromDate || $toDate) {
                // Opening line summary query before fromDate
                $openingSummaryQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
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
                        PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
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
                $orderQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
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
                $paymentQuery = PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
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

                // Build the complete query and execute
                $subquerySql = DB::table(DB::raw("({$combinedQuery->toSql()}) as tmptable"))
                    ->mergeBindings($combinedQuery->getQuery()) // Bind the parameters from the combined query
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
            }
            else{
                $ledgerEntries = new LengthAwarePaginator([], 0, 15, 1, [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                ]);

            }

        }
        // dd($ledgerEntries);
        if($fromDate && $toDate){
            return Excel::download(new AgentLedgerExport(collect($ledgerEntries)), 'agent_ledger.xlsx');
        }
        else{
            return redirect()->back();
        }
        // return view('ledgers.index', compact( 'breadcrumbs','ledgerEntries'))
        //     ->with('i', (request()->input('page', 1) - 1) * 1);
    }

    public function driver_ledger(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "Driver Ledgers",
                'link' => route("ledger.driver_ledger"),
                'active' => true,
            ]
        ];
        if(auth()->user()->actor_id==2){
            $driverQuery = $request->input('agent');
        }
        // for agent side:
        // else{
        //     $agentQuery = auth()->user()->partner_id;


        // }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // $orders = OrderDetail::whereHas('order', function($queryBuilder) {
        //         $queryBuilder->where('overall_status', 'approved');
        //     })
        //     ->where('status', 'completed')
        //     ->when($agentQuery, function ($queryBuilder) use ($agentQuery) {
        //         $queryBuilder->whereHas('order.partner_business', function ($subQuery) use ($agentQuery) {
        //             $subQuery->where('id', $agentQuery);
        //         });
        //     })
        //     ->when($fromDate && !$toDate, function ($queryBuilder) use ($fromDate) {
        //         $queryBuilder->whereDate('date', '>=', $fromDate);
        //     })
        //     ->when(!$fromDate && $toDate, function ($queryBuilder) use ($toDate) {
        //         $queryBuilder->whereDate('date', '<=', $toDate);
        //     })
        //     ->when($fromDate && $toDate, function ($queryBuilder) use ($fromDate, $toDate) {
        //         $queryBuilder->whereBetween('date', [$fromDate, $toDate]);
        //     })
        //     ->paginate();


        // sher ali raw query:
        // ---------------------------------------------------
        // if ($agentQuery || $fromDate || $toDate){







        //     $rawquery = " select tmptable.agent,tmptable.customer,tmptable.trtype,tmptable.tr_date,tmptable.tr_no,tmptable.description,sum(tmptable.debit) debit,sum(credit) credit from (

        //     select '' agent,'' customer,'OPN' trtype,'' tr_date,'' tr_no,'' description,sum(tmptable.debit) debit,sum(credit) credit from (
        //         SELECT bp.name agent,'inv' trtype,od.date tr_date,concat( o.order_no,'-',od.id) tr_no,o.description,o.booking_amount debit,0 credit
        //         FROM `orders` o join order_details od
        //         on o.id=od.order_id
        //         join partners bp on o.business_partner_id=bp.id
        //         where od.status in ('completed','paid')";

        //         if($agentQuery!=""){
        //             $rawquery = $rawquery ." and bp.id=".$agentQuery." ";
        //         }

        //         if($fromDate!="" && $toDate!="")
        //          $rawquery = $rawquery ." and od.date < '".$fromDate."' ";

        //          $rawquery = $rawquery ." union ALL
        //         SELECT agent.name agent,'pay' as trtype,ph.date,ph.payment_no,ph.description,0 as debit,pl.total_amount from payment_headers ph join payment_lines pl on ph.id=pl.payment_header_id
        //         join partners agent on ph.agent_id=agent.id
        //         join partners as customer on ph.customer_id=customer.id
        //         where 1=1 ";
        //         if($agentQuery!=""){
        //             "and agent.id=".$agentQuery." ";
        //          }

        //         if($fromDate!="" && $toDate!="")
        //             $rawquery = $rawquery ."  and ph.date < '".$fromDate."' ";

        //         $rawquery = $rawquery ." ) as tmptable
        //         union all


        //     SELECT bp.name agent,cs.name customer,'inv' trtype,od.date tr_date,concat( o.order_no,'-',od.id) tr_no,o.description,o.booking_amount debit,0 credit
        //         FROM `orders` o join order_details od
        //         on o.id=od.order_id
        //         join partners bp on o.business_partner_id=bp.id

        //         join partners cs on o.customer_partner_id=cs.id
        //         where od.status in ('completed','paid')";

        //         if($agentQuery!=""){
        //             $rawquery = $rawquery ." and bp.id=".$agentQuery." ";
        //         }

        //         if($fromDate!="" && $toDate!="")
        //          $rawquery = $rawquery ." and od.date between '".$fromDate."' and '".$toDate."'";

        //          $rawquery = $rawquery ." union ALL
        //         SELECT agent.name agent,cs.name customer,'pay' as trtype,ph.date,ph.payment_no,ph.description,0 as debit,pl.total_amount from payment_headers ph join payment_lines pl on ph.id=pl.payment_header_id
        //        join partners agent on ph.agent_id=agent.id
        //         join partners as cs on ph.customer_id=cs.id
        //          where 1=1 ";
        //         if($agentQuery!=""){
        //             "and agent.id=".$agentQuery." ";
        //          }

        //         if($fromDate!="" && $toDate!="")
        //             $rawquery = $rawquery ."  and ph.date between '".$fromDate."' and '".$toDate."' ";

        //         $rawquery = $rawquery ." ) as tmptable
        //         group by  tmptable.agent,tmptable.customer,tmptable.trtype,tmptable.tr_date,tmptable.tr_no,tmptable.description
        //         order by  tmptable.agent,tmptable.customer,tmptable.tr_date,tmptable.trtype;";

        //         // return $rawquery;
        //         $ledgerEntries = DB::select($rawquery);
        // }
        // ----------------------------------------------------
        if(auth()->user()->actor_id == 2) {

            if ($driverQuery || $fromDate || $toDate) {
                // Opening line summary query before fromDate
                $openingSummaryQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
                    ->select(
                        DB::raw("'' as driver"),
                        DB::raw("'' as customer"),
                        DB::raw("'OPN' as trtype"),
                        DB::raw("'' as tr_date"),
                        DB::raw("'' as tr_no"),
                        DB::raw("'' as description"),
                        DB::raw("sum(NULLIF(COALESCE(od.rate, od.driver_rate), '')::numeric) as debit"),
                        // DB::raw("sum(NULLIF(od.rate, '')::numeric) as debit"),
                        DB::raw("0 as credit")
                    )
                    ->join('order_lines as od', 'orders.id', '=', 'od.order_id')

                    ->whereIn('od.status', ['completed'])
                    ->where('orders.business_partner_id', $driverQuery)
                    ->when($driverQuery, function ($query) use ($driverQuery) {
                        $query->where('od.driver_id', $driverQuery);
                    })
                    ->when($fromDate, function ($query) use ($fromDate) {
                        $query->where('od.date', '<', $fromDate);
                    })
                    ->unionAll(
                        PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
                            ->select(
                                DB::raw("'' as driver"),
                                DB::raw("'' as customer"),
                                DB::raw("'OPN' as trtype"),
                                DB::raw("'' as tr_date"),
                                DB::raw("'' as tr_no"),
                                DB::raw("'' as description"),
                                DB::raw("0 as debit"),
                                DB::raw("sum(NULLIF(payment_lines.total_amount, '')::numeric) as credit")
                            )
                            ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
                            ->when($driverQuery, function ($query) use ($driverQuery) {
                                $query->where('payment_headers.agent_id', $driverQuery);
                            })
                            ->when($fromDate, function ($query) use ($fromDate) {
                                $query->where('payment_headers.date', '<', $fromDate);
                            })
                    );

                // Order query between fromDate and toDate
                $orderQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
                    ->select(
                        'drivers.name as driver',
                        'customers.name as customer',
                        DB::raw("'inv' as trtype"),
                        DB::raw("order_lines.date::text as tr_date"),
                        DB::raw("concat(orders.order_no, '-', order_lines.id) as tr_no"),
                        'orders.description as description',
                        DB::raw("NULLIF(COALESCE(order_lines.rate, order_lines.driver_rate), '')::numeric as debit"),
                        // 'order_lines.rate as debit',
                        DB::raw("0 as credit")
                    )
                    ->join('order_lines', 'orders.id', '=', 'order_lines.order_id')
                    ->join('partners as drivers', 'order_lines.driver_id', '=', 'drivers.id')
                    ->join('partners as customers', 'orders.customer_partner_id', '=', 'customers.id')
                    ->whereIn('order_lines.status', ['completed'])
                    ->where('orders.business_partner_id',$driverQuery)
                    ->when($driverQuery, function ($query) use ($driverQuery) {
                        $query->where('drivers.id', $driverQuery);
                    })
                    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                        $query->whereBetween('order_lines.date', [$fromDate, $toDate]);
                    });

                // Payment query between fromDate and toDate
                $paymentQuery = PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
                    ->select(
                        'drivers.name as driver',
                        'customers.name as customer',
                        DB::raw("'pay' as trtype"),
                        DB::raw("payment_headers.date::text as tr_date"),
                        'payment_headers.payment_no as tr_no',
                        'payment_headers.description as description',
                        DB::raw("0 as debit"),
                        DB::raw("NULLIF(payment_lines.total_amount, '')::numeric as credit")
                    )
                    ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
                    ->join('partners as drivers', 'payment_headers.driver_id', '=', 'drivers.id')
                    ->join('partners as customers', 'payment_headers.customer_id', '=', 'customers.id')
                    ->when($driverQuery, function ($query) use ($driverQuery) {
                        $query->where('drivers.id', $driverQuery);
                    })
                    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                        $query->whereBetween('payment_headers.date', [$fromDate, $toDate]);
                    });

                // Combining all queries
                $combinedQuery = $openingSummaryQuery
                    ->unionAll($orderQuery)
                    ->unionAll($paymentQuery);

                // Build the complete query and execute
                $subquerySql = DB::table(DB::raw("({$combinedQuery->toSql()}) as tmptable"))
                    ->mergeBindings($combinedQuery->getQuery()) // Bind the parameters from the combined query
                    ->select(
                        'tmptable.driver',
                        'tmptable.customer',
                        'tmptable.trtype',
                        'tmptable.tr_date',
                        'tmptable.tr_no',
                        'tmptable.description',
                        DB::raw('SUM(tmptable.debit) as debit'),
                        DB::raw('SUM(tmptable.credit) as credit')
                    )
                    ->groupBy('tmptable.driver', 'tmptable.customer', 'tmptable.trtype', 'tmptable.tr_date', 'tmptable.tr_no', 'tmptable.description')
                    ->orderBy('tmptable.driver')
                    ->orderBy('tmptable.customer')
                    ->orderBy('tmptable.tr_date')
                    ->orderBy('tmptable.trtype')
                    ->get();

                // Execute the complete query
                $ledgerEntries = $subquerySql->toArray();
            }
            else{
                $ledgerEntries = new LengthAwarePaginator([], 0, 15, 1, [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                ]);
            }
        }

        // ------start agent side------
        // else{
        //     if ($fromDate || $toDate) {
        //         // Opening line summary query before fromDate
        //         $openingSummaryQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
        //             ->select(
        //                 DB::raw("'' as agent"),
        //                 DB::raw("'' as customer"),
        //                 DB::raw("'OPN' as trtype"),
        //                 DB::raw("'' as tr_date"),
        //                 DB::raw("'' as tr_no"),
        //                 DB::raw("'' as description"),
        //                 DB::raw("sum(NULLIF(od.rate, '')::numeric) as debit"),
        //                 DB::raw("0 as credit")
        //             )
        //             ->join('order_details as od', 'orders.id', '=', 'od.order_id')
        //             ->whereIn('od.status', ['completed', 'paid'])
        //             ->when($agentQuery, function ($query) use ($agentQuery) {
        //                 $query->where('orders.business_partner_id', $agentQuery);
        //             })
        //             ->when($fromDate, function ($query) use ($fromDate) {
        //                 $query->where('od.date', '<', $fromDate);
        //             })
        //             ->unionAll(
        //                 PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
        //                     ->select(
        //                         DB::raw("'' as agent"),
        //                         DB::raw("'' as customer"),
        //                         DB::raw("'OPN' as trtype"),
        //                         DB::raw("'' as tr_date"),
        //                         DB::raw("'' as tr_no"),
        //                         DB::raw("'' as description"),
        //                         DB::raw("0 as debit"),
        //                         DB::raw("sum(NULLIF(payment_lines.total_amount, '')::numeric) as credit")
        //                     )
        //                     ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
        //                     ->when($agentQuery, function ($query) use ($agentQuery) {
        //                         $query->where('payment_headers.agent_id', $agentQuery);
        //                     })
        //                     ->when($fromDate, function ($query) use ($fromDate) {
        //                         $query->where('payment_headers.date', '<', $fromDate);
        //                     })
        //             );

        //         // Order query between fromDate and toDate
        //         $orderQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
        //             ->select(
        //                 'partners.name as agent',
        //                 'customers.name as customer',
        //                 DB::raw("'inv' as trtype"),
        //                 'order_details.date as tr_date',
        //                 DB::raw("concat(orders.order_no, '-', order_details.id) as tr_no"),
        //                 'orders.description as description',
        //                 'order_details.rate as debit',
        //                 DB::raw("0 as credit")
        //             )
        //             ->join('order_details', 'orders.id', '=', 'order_details.order_id')
        //             ->join('partners', 'orders.business_partner_id', '=', 'partners.id')
        //             ->join('partners as customers', 'orders.customer_partner_id', '=', 'customers.id')
        //             ->whereIn('order_details.status', ['completed', 'paid'])
        //             ->when($agentQuery, function ($query) use ($agentQuery) {
        //                 $query->where('partners.id', $agentQuery);
        //             })
        //             ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
        //                 $query->whereBetween('order_details.date', [$fromDate, $toDate]);
        //             });

        //         // Payment query between fromDate and toDate
        //         $paymentQuery = PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
        //             ->select(
        //                 'agents.name as agent',
        //                 'customers.name as customer',
        //                 DB::raw("'pay' as trtype"),
        //                 DB::raw("payment_headers.date::text as tr_date"),
        //                 'payment_headers.payment_no as tr_no',
        //                 'payment_headers.description as description',
        //                 DB::raw("0 as debit"),
        //                 DB::raw("NULLIF(payment_lines.total_amount, '')::numeric as credit")
        //             )
        //             ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
        //             ->join('partners as agents', 'payment_headers.agent_id', '=', 'agents.id')
        //             ->join('partners as customers', 'payment_headers.customer_id', '=', 'customers.id')
        //             ->when($agentQuery, function ($query) use ($agentQuery) {
        //                 $query->where('agents.id', $agentQuery);
        //             })
        //             ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
        //                 $query->whereBetween('payment_headers.date', [$fromDate, $toDate]);
        //             });

        //         // Combining all queries
        //         $combinedQuery = $openingSummaryQuery
        //             ->unionAll($orderQuery)
        //             ->unionAll($paymentQuery);

        //         // Build the complete query and execute
        //         $subquerySql = DB::table(DB::raw("({$combinedQuery->toSql()}) as tmptable"))
        //             ->mergeBindings($combinedQuery->getQuery()) // Bind the parameters from the combined query
        //             ->select(
        //                 'tmptable.agent',
        //                 'tmptable.customer',
        //                 'tmptable.trtype',
        //                 'tmptable.tr_date',
        //                 'tmptable.tr_no',
        //                 'tmptable.description',
        //                 DB::raw('SUM(tmptable.debit) as debit'),
        //                 DB::raw('SUM(tmptable.credit) as credit')
        //             )
        //             ->groupBy('tmptable.agent', 'tmptable.customer', 'tmptable.trtype', 'tmptable.tr_date', 'tmptable.tr_no', 'tmptable.description')
        //             ->orderBy('tmptable.agent')
        //             ->orderBy('tmptable.customer')
        //             ->orderBy('tmptable.tr_date')
        //             ->orderBy('tmptable.trtype')
        //             ->get();

        //         // Execute the complete query
        //         $ledgerEntries = $subquerySql->toArray();
        //     }
        //     else{
        //         $ledgerEntries = new LengthAwarePaginator([], 0, 15, 1, [
        //             'path' => LengthAwarePaginator::resolveCurrentPath(),
        //         ]);

        //     }

        // }
        // ------end agent side------

        // $rawquery = "select tmptable.customer,tmptable.trtype,tmptable.tr_date,tmptable.tr_no,tmptable.description,sum(tmptable.debit) debit,sum(credit) credit from (
        // SELECT bp.name customer,'inv' trtype,ifnull(od.checkout_time,od.pickup_time) tr_date,concat( o.order_no,'-',od.id) tr_no,o.description,o.booking_amount debit,0 credit
        // FROM `orders` o join order_details od
        // on o.id=od.order_id
        // join partners bp on o.business_partner_id=bp.id
        // where od.status in ('completed','paid')";

        // if($agentQuery!=""){
        //     $rawquery = $rawquery ." and bp.id=".$agentQuery." ";
        // }

        // if($fromDate!="" && $toDate!="")
        //  $rawquery = $rawquery ." and od.pickup_time between '".$fromDate."' and '".$toDate."'";

        //  $rawquery = $rawquery ." union ALL
        // SELECT agents.name customer,'pay' as trtype,ph.date,ph.payment_no,ph.description,0 as debit,pl.total_amount from payment_headers ph join payment_lines pl on ph.id=pl.payment_header_id
        // join partners customer on ph.agent_id=customer.id
        // join partners as agents on customer.business_partner_id=agents.id
        // where 1=1 ";
        // if($agentQuery!=""){
        //     "and agents.id=".$agentQuery." ";
        //  }

        // if($fromDate!="" && $toDate!="")
        //     $rawquery = $rawquery ."  and ph.date between '".$fromDate."' and '".$toDate."' ";

        // $rawquery = $rawquery ." ) as tmptable
        // group by tmptable.customer,tmptable.trtype,tmptable.tr_date,tmptable.tr_no,tmptable.description
        // order by tmptable.customer,tmptable.tr_date,tmptable.trtype;";

        // $ledgerEntries = DB::select($rawquery);
        // dd($ledgerEntries);
        return view('driver-ledgers.index', compact( 'breadcrumbs','ledgerEntries'))
            ->with('i', (request()->input('page', 1) - 1) * 1);
    }
    public function driver_export(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "Driver Ledgers",
                'link' => route("ledger.driver_ledger"),
                'active' => true,
            ]
        ];
        if(auth()->user()->actor_id==2){
            $driverQuery = $request->input('agent');
        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if(auth()->user()->actor_id == 2) {

            if ($driverQuery || $fromDate || $toDate) {
                // Opening line summary query before fromDate
                $openingSummaryQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
                    ->select(
                        DB::raw("'' as driver"),
                        DB::raw("'' as customer"),
                        DB::raw("'OPN' as trtype"),
                        DB::raw("'' as tr_date"),
                        DB::raw("'' as tr_no"),
                        DB::raw("'' as description"),
                        DB::raw("sum(NULLIF(COALESCE(od.rate, od.driver_rate), '')::numeric) as debit"),
                        // DB::raw("sum(NULLIF(od.rate, '')::numeric) as debit"),
                        DB::raw("0 as credit")
                    )
                    ->join('order_lines as od', 'orders.id', '=', 'od.order_id')

                    ->whereIn('od.status', ['completed'])
                    ->where('orders.business_partner_id', $driverQuery)
                    ->when($driverQuery, function ($query) use ($driverQuery) {
                        $query->where('od.driver_id', $driverQuery);
                    })
                    ->when($fromDate, function ($query) use ($fromDate) {
                        $query->where('od.date', '<', $fromDate);
                    })
                    ->unionAll(
                        PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
                            ->select(
                                DB::raw("'' as driver"),
                                DB::raw("'' as customer"),
                                DB::raw("'OPN' as trtype"),
                                DB::raw("'' as tr_date"),
                                DB::raw("'' as tr_no"),
                                DB::raw("'' as description"),
                                DB::raw("0 as debit"),
                                DB::raw("sum(NULLIF(payment_lines.total_amount, '')::numeric) as credit")
                            )
                            ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
                            ->when($driverQuery, function ($query) use ($driverQuery) {
                                $query->where('payment_headers.agent_id', $driverQuery);
                            })
                            ->when($fromDate, function ($query) use ($fromDate) {
                                $query->where('payment_headers.date', '<', $fromDate);
                            })
                    );

                // Order query between fromDate and toDate
                $orderQuery = Order::query()->withoutGlobalOrganizationalScope()->where('orders.company_id', OrganizationAccess::companyId())
                    ->select(
                        'drivers.name as driver',
                        'customers.name as customer',
                        DB::raw("'inv' as trtype"),
                        DB::raw("order_lines.date::text as tr_date"),
                        DB::raw("concat(orders.order_no, '-', order_lines.id) as tr_no"),
                        'orders.description as description',
                        DB::raw("NULLIF(COALESCE(order_lines.rate, order_lines.driver_rate), '')::numeric as debit"),
                        // 'order_lines.rate as debit',
                        DB::raw("0 as credit")
                    )
                    ->join('order_lines', 'orders.id', '=', 'order_lines.order_id')
                    ->join('partners as drivers', 'order_lines.driver_id', '=', 'drivers.id')
                    ->join('partners as customers', 'orders.customer_partner_id', '=', 'customers.id')
                    ->whereIn('order_lines.status', ['completed'])
                    ->where('orders.business_partner_id',$driverQuery)
                    ->when($driverQuery, function ($query) use ($driverQuery) {
                        $query->where('drivers.id', $driverQuery);
                    })
                    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                        $query->whereBetween('order_lines.date', [$fromDate, $toDate]);
                    });

                // Payment query between fromDate and toDate
                $paymentQuery = PaymentHeader::query()->withoutGlobalOrganizationalScope()->where('payment_headers.company_id', OrganizationAccess::companyId())
                    ->select(
                        'drivers.name as driver',
                        'customers.name as customer',
                        DB::raw("'pay' as trtype"),
                        DB::raw("payment_headers.date::text as tr_date"),
                        'payment_headers.payment_no as tr_no',
                        'payment_headers.description as description',
                        DB::raw("0 as debit"),
                        DB::raw("NULLIF(payment_lines.total_amount, '')::numeric as credit")
                    )
                    ->join('payment_lines', 'payment_headers.id', '=', 'payment_lines.payment_header_id')
                    ->join('partners as drivers', 'payment_headers.driver_id', '=', 'drivers.id')
                    ->join('partners as customers', 'payment_headers.customer_id', '=', 'customers.id')
                    ->when($driverQuery, function ($query) use ($driverQuery) {
                        $query->where('drivers.id', $driverQuery);
                    })
                    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                        $query->whereBetween('payment_headers.date', [$fromDate, $toDate]);
                    });

                // Combining all queries
                $combinedQuery = $openingSummaryQuery
                    ->unionAll($orderQuery)
                    ->unionAll($paymentQuery);

                // Build the complete query and execute
                $subquerySql = DB::table(DB::raw("({$combinedQuery->toSql()}) as tmptable"))
                    ->mergeBindings($combinedQuery->getQuery()) // Bind the parameters from the combined query
                    ->select(
                        'tmptable.driver',
                        'tmptable.customer',
                        'tmptable.trtype',
                        'tmptable.tr_date',
                        'tmptable.tr_no',
                        'tmptable.description',
                        DB::raw('SUM(tmptable.debit) as debit'),
                        DB::raw('SUM(tmptable.credit) as credit')
                    )
                    ->groupBy('tmptable.driver', 'tmptable.customer', 'tmptable.trtype', 'tmptable.tr_date', 'tmptable.tr_no', 'tmptable.description')
                    ->orderBy('tmptable.driver')
                    ->orderBy('tmptable.customer')
                    ->orderBy('tmptable.tr_date')
                    ->orderBy('tmptable.trtype')
                    ->get();

                // Execute the complete query
                $ledgerEntries = $subquerySql->toArray();
            }
            else{
                $ledgerEntries = new LengthAwarePaginator([], 0, 15, 1, [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                ]);
            }
        }
        if($fromDate && $toDate){
            return Excel::download(new DriverLedgerExport(collect($ledgerEntries)), 'driver_ledgers.xlsx');
        }
        else{
            return redirect()->back();
        }
    }


}
