<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Models\Concerns\HasClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends BaseModel
{
    use HasFactory;
    use BelongsToOrganization;
    use HasClient;

    protected $table = 'order_lines';

    protected $fillable = [
        'order_id', 'company_id', 'rate_list_id', 'rate', 'status',
        'adult', 'child', 'bags', 'flight_num', 'airline_name',
        'date', 'pickup_time', 'estimated_time', 'checkout_time', 'is_ac',
        'driver_rate', 'driver_pickup_loc', 'driver_dropoff_loc',
        'ride_start_mileage', 'ride_end_mileage', 'from_loc', 'to_loc',
        'driver_id', 'vehicle_id', 'weight', 'type_of_load', 'unit',
        'with_driver', 'end_date', 'duration',
        'client_id', 'product_id', 'date_promised', 'date_ordered',
        'quantity', 'order_qty', 'delivered_qty', 'reserved_qty', 'invoiced_qty',
        'unit_price', 'list_price', 'discount', 'tax_value',
        'line_amount', 'total_line_amount', 'seq_no', 'tax_id',
        'created_at', 'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }
    public function rate_list()
    {
        return $this->belongsTo(\App\Models\RateList::class, 'rate_list_id', 'id');
    }
    public function driver()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'driver_id');
    }
    // public function vehicle()
    // {
    //     return $this->belongsTo(\App\Models\Vehicle::class, 'vehicle_id');
    // }
    public static function company_id()
    {
        $company_id = auth()->user()->active_company();
        // dd($company_id );
        return $company_id;
    }
    public function paymentLines()
    {
        return $this->hasMany(PaymentLine::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    // for admin dashboard display:
    public static function totalOrders($query = null)
    {
        // $query = $query ?: self::query();
        return $query ? $query->where('company_id', self::company_id())->count() : self::all()->where('company_id', self::company_id())->count();
    }
    public static function completedOrders($query = null)
    {

        return $query ? $query->where('status', 'completed')->where('company_id', self::company_id())->count() : self::where('status', 'completed')->where('company_id', self::company_id())->count();
    }
    public static function pendingOrders($query = null)
    {
        // $query = $query ? $query: self::query();
        return $query ? $query->where('status', 'pending')->where('company_id', self::company_id())->count() : self::where('status', 'pending')->where('company_id', self::company_id())->count();
    }
    public static function approvedOrders($query = null)
    {
        // $query = $query ?: self::query();
        return $query ? $query->where('status', 'approved')->where('company_id', self::company_id())->count() : self::where('status', 'approved')->where('company_id', self::company_id())->count();
    }
    public static function unapprovedOrders($query = null)
    {
        // $query = $query ?: self::query();
        return $query ? $query->where('status', 'unapproved')->where('company_id', self::company_id())->count() : self::where('status', 'unapproved')->where('company_id', self::company_id())->count();
    }
    public static function cancelOrders($query = null)
    {
        // $query = $query ?: self::query();
        return $query ? $query->where('status', 'cancelled')->where('company_id', self::company_id())->count() : self::where('status', 'cancelled')->where('company_id', self::company_id())->count();
    }
    public static function incompleteOrders($query = null)
    {
        // $query = $query ?: self::query();
        return $query ? $query->where('status', 'incomplete')->where('company_id', self::company_id())->count() : self::where('status', 'incomplete')->where('company_id', self::company_id())->count();
    }
     public static function incompleteOrdersWithDriver($query = null)
    {
        return $query
            ? $query->where('status', 'incomplete')
                    ->whereNotNull('driver_id')
                    ->where('company_id', self::company_id())
                    ->count()
            : self::where('status', 'incomplete')
                ->whereNotNull('driver_id')
                ->where('company_id', self::company_id())
                ->count();
    }
    public static function incompleteOrdersWithoutDriver($query = null)
    {
        return $query
            ? $query->where('status', 'incomplete')
                    ->whereNull('driver_id')
                    ->where('company_id', self::company_id())
                    ->count()
            : self::where('status', 'incomplete')
                ->whereNull('driver_id')
                ->where('company_id', self::company_id())
                ->count();
    }


    // -----------------------------------------------------------
    // for agent:
    public static function agentTotalOrders($query = null)
    {
        return $query ? $query->whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->count() : self::whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->count();
    }
    public static function agentPendingOrders($query = null)
    {
        return $query ? $query->whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'pending')->count() : self::whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'pending')->count();
    }
    public static function agentApprovedOrders($query = null)
    {
        return $query ? $query->whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'approved')->count() : self::whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'approved')->count();
    }
    public static function agentUnapprovedOrders($query = null)
    {
        return $query ? $query->whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'unapproved')->count() : self::whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'unapproved')->count();
    }
    public static function agentCompletedOrders($query = null)
    {
        return $query ? $query->whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'completed')->count() : self::whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'completed')->count();
    }
    public static function agentCancelOrders($query = null)
    {
        return $query ? $query->whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'cancelled')->count() : self::whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'cancelled')->count();
    }
    public static function agentIncompleteOrders($query = null)
    {
        return $query ? $query->whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'incomplete')->count() : self::whereHas('order', function ($query) {
            $query->where('business_partner_id', auth()->user()->partner_id);
        })->where('status', 'incomplete')->count();
    }
    // -------------------------------------------------------------


    public static function vehivcle_details()
    {
        $company_id = auth()->user()->active_company();

        return [


            //   "unassigned_vehicles"=>self::where('status','approved')->count(),
            //   "assigned_vehicles"=>self::where('status','incomplete')->count(),
            "paid" => self::where('company_id', $company_id)->where('status', 'paid')->count(),
            "unpaid" => self::where('company_id', $company_id)->where('status', 'completed')->count()
        ];
    }


    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // static function adults($id){
    //     return OrderDetail::where('order_id',$id)->first();

    // }
    // static function child($id){
    //     return OrderDetail::where('order_id',$id)->first();

    // }
    // static function bags($id){
    //     return OrderDetail::where('order_id',$id)->first();

    // }

    // public function loadTypes()
    // {
    //     return $this->belongsToMany(LoadType::class);
    // }
    public function typeOfLoad()
    {
        return $this->belongsTo(LoadType::class, 'type_of_load');
    }

    public function unit()
    {
        return $this->belongsTo(UnitMeasure::class, 'unit', 'id');
    }
    public function unitMeasure()
    {
        return $this->belongsTo(UnitMeasure::class, 'unit', 'id');
    }
     public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
