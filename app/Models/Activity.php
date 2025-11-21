<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Activity
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_by
 * @property $updated_by
 * @property $company_id
 * @property $is_active
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Company $company
 * @property User $user
 * @property User $user
 * @property ActivityLine[] $activityLines
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Activity extends BaseModel
{
    use SoftDeletes;

    // static $rules = [
	// 		'name' => 'required|string',
	// 		'description' => 'required|string',
	// 		'is_active' => 'required',
    // ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'description', 'company_id', 'is_active'];
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityLines()
    {
        return $this->hasMany(\App\Models\ActivityLine::class, 'activity_id', 'id');
    }

    // public function scopewhereCompanyId($company_id){
    //     return $this->where('company_id', $company_id)->get();
    // }
    public static function getActiveActivity($company_id){
        return self::with([
            'activityLines' => function($line){
                return $line->where('is_active', 1);
            }
        ])
        ->where('is_active', 1)
        ->where('company_id', $company_id)
        // ->whereCompanyId($company_id)
        ->first();
    }
    public static function store_activity($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $activity = Activity::create([
            'name'=>$data['name'],
            'description'=>$data['description']??null,
            'is_active'=>$data['is_active'],
            'company_id'=>$company_id,
            'created_by'=> $data['created_by']
        ]);

        foreach($data['rows']??[] as $row){
            ActivityLine::create([
                'activity_id'=> $activity->id,
                'seq_no'=>$row['seq_no'],
                'name'=>$row['name'],
                'description'=>$row['description']??null,
                'is_active'=>$row['is_active'],
                'company_id'=>$company_id,
                'created_by'=> $data['created_by']

            ]);
        }
    }
    public static function update_activity($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $activity->update([
            'name'=>$data['name'],
            'description'=>$data['description']??null,
            'is_active'=>$data['is_active'],
            'company_id'=>$company_id,
            'updated_by'=> $data['updated_by']
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->update([
                'seq_no'=>$row['seq_no'],
                'name'=>$row['name'],
                'description'=>$row['description']??null,
                'is_active'=>$row['is_active'],
                'company_id'=>$company_id,
                'updated_by'=> $data['updated_by'],
                'created_by'=> auth()->user()->id,
                ]);
            }
            else{

                ActivityLine::create([
                    'activity_id'=> $activity->id,
                    'seq_no'=>$row['seq_no'],
                    'name'=>$row['name'],
                    'description'=>$row['description']??null,
                    'is_active'=>$row['is_active'],
                    'company_id'=>$company_id,
                    'updated_by'=> $data['updated_by'],
                    'created_by'=> auth()->user()->id,

                ]);
            }

        }
    }

}
