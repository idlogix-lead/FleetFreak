<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BroadcastMessage
 *
 * @property $id
 * @property $company_id
 * @property $created_by
 * @property $updated_by
 * @property $title
 * @property $message
 * @property $description
 * @property $broadcast_type
 * @property $broadcast_frequency
 * @property $expired_date
 * @property $expired
 * @property $published
 * @property $created_at
 * @property $updated_at
 *
 * @property Company $company
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class BroadcastMessage extends BaseModel
{

    static $rules = [
			'title' => 'string',
			'message' => 'string',
			'description' => 'string',
			'broadcast_type' => 'required',
			'expired' => 'required',
			'published' => 'required',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['company_id', 'title', 'message', 'description', 'broadcast_type', 'broadcast_frequency', 'expired_date', 'expired', 'published'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_update()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }


}
