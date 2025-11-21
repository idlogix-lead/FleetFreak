<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermissionTypeFunction extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function role_permission_type(){
        // return $this->hasOne(RolePermissionType::class,'id','role_permission_type_id');
        return $this->belongsTo(RolePermissionType::class);
    }

}
