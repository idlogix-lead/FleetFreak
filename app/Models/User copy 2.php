<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory ,HasApiTokens, Notifiable;
    protected $fillable = ['name','email','role','password','description','phone_no','CNIC','image','theme'];
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'role',
    //     'password',
        
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function get_user_roles_permissions(){
        $user = auth()->user();
        $role_id = $user->role_id;
        $permissions = RolePermission::where('role_id',$role_id)
        ->with(['role_permission_type.rolePermissionTypeFunctions'])
        // ->with(['role_module_functions'])
        ->get();
        return $permissions;
    }
    public function role_module_permissions($module_id){
        $permissions = $this->get_user_roles_permissions()
        // ->where('role_permission_type.action','read');
        // ->with('role_permission_type.rolePermissionTypeFunctions',function($query){
        //     return $query->where('method','index');
        // })
        ;
        

        return $permissions->where('role_module_id',$module_id);
    }
    public function Customer(){
        return $this->hasOne(Customer::class);
    } 
    

    public function serviceProvider(){
        return $this->belongsTo(ServiceProvider::class);
    }
    public function socialprofile(){
        return $this->hasOne(UserSocialProfile::class);
    }
}
