<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends BaseModel
{
    use HasFactory;
    
    protected $table = 'order_lines';

    protected $guarded = [];

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
