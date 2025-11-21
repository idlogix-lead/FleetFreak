<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);

    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class,'source_id','id');

    }
    /**
     * Get the user associated with the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sourceTable()
    {
        return $this->hasOne(Table::class, 'id', 'source_table_id');
    }
    /**
     * Get all of the comments for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'event_id', 'id');
    }

    public static function createEvent($source_table_id, $source_id,$source_name, $action, $action_details, $action_details2 = null,$action_details3 = null,$company_id=null, $date = null, $notifications = [], $auth_user_id = null)
    {
        // if(!$company_id){
        //     dd("company_id required");
        // }
        // ['businessagent','employee','customer','vehicle','driver','user','route','ratelist','order','orderdetail','pendingorder','driverassignment']

        $event = self::create([
            // 'source' => $source,
            'source_table_id' => $source_table_id,
            'company_id' => $company_id,
            'source_id' => $source_id,
            'source_name'=> $source_name,
            'action' => $action,
            'action_details' => $action_details,
            'action_details2' => $action_details2,
            'action_details3' => $action_details3,
            'date' => $date??date('Y-m-d'),
            'created_by' => $auth_user_id??auth()->user()?->id,
            'created_by_name' => $auth_user_id??auth()->user()?->name,
        ]);
        foreach($notifications as $notification){
            // dd($notification);
            Notification::notify($action_details, $action_details2, $notification['receiver_id']?? null,$notification['receiver_partner_id']??null, $notification['whatsapp']??0, $notification['email']??0, $notification['sms']??0, $notification['fcm_web_push']??0, $notification['fcm_mobile_push']??0, $notification['sender_id'], $source_id, $notification['calendar']??0, $event->id);
        }
        return $event;
    }
}
