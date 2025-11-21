<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    // public function users()
    // {
    //     return $this->belongsTo(User::class);
    // }
    public function account()
    {
        return $this->hasMany(Account::class);
    }

    public function car_company()
    {
        return $this->hasMany(VehicleCompany::class);
    }

    public function event()
    {
        return $this->hasMany(Event::class);
    }

    public function location()
    {
        return $this->hasMany(Location::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function orderdetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function partner()
    {
        return $this->hasMany(Partner::class);
    }
    public function paymentheader()
    {
        return $this->hasMany(PaymentHeader::class);
    }
    public function paymentline()
    {
        return $this->hasMany(PaymentLine::class);
    }
    public function ratelist()
    {
        return $this->hasMany(RateList::class);
    }
    public function route()
    {
        return $this->hasMany(Route::class);

    }
    public function routerate()
    {
        return $this->hasMany(RouteRate::class);
    }

    public function vehicle()
    {
        return $this->hasMany( Vehicle::class);
    }
    public function vehicleclass()
    {
        return $this->hasMany(VehicleClass::class);
    }
    public function vehiclemodel()
    {
        return $this->hasMany(VehicleModel::class);
    }
    public function user_company()
    {
        return $this->hasMany(UserCompany::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_companies');
    }

    public function scopemyCompanies(){
        return $this->whereHas('user_company', function($user){
            return $user->where('user_id', auth()->user()->id);
        });
    }

    public static function myCompaniesDropdown(){
        return self::myCompanies()->get();
    }

}
