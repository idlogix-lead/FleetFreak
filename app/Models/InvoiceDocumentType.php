<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoiceDocumentType
 *
 * @property $id
 * @property $name
 * @property $code
 * @property $description
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class InvoiceDocumentType extends BaseModel
{

    static $rules = [
			'name' => 'required|string',
			'code' => 'required|string',
			'description' => 'string',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'code', 'description'];
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_create()
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
    public static function documentTypes(){
        return self::get();

    }


}
