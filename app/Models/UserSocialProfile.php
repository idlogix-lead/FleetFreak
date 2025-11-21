<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocialProfile extends BaseModel
{
    use HasFactory;
    protected $fillable = ['website','github','twitter','instagram','facebook'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
