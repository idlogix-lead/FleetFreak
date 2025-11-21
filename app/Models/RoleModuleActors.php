<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModuleActors extends BaseModel
{
    use HasFactory;
    protected $guarded = [];


    public function actor()
    {
        return $this->belongsTo(Actor::class);
    }

    public function roleModule()
    {
        return $this->belongsTo(RoleModule::class);
    }
}
