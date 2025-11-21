<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Partner;

class Notification extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public function receiver()
    {
        return $this->belongsTo(\App\Models\User::class, 'receiver_id', 'id');
    }

    public function receiver_partner()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'receiver_partner_id');
    }


    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'sender_id', 'id');
    }

    public function source()
    {
        return $this->belongsTo(\App\Models\Order::class, 'source_id', 'id');
    }
    // for admin side notification
    public static function unread_count(){
        return self::where('is_read',0)->where('receiver_id',auth()->user()->id)->count();
    }
    public static function admin_unread_notifications(){
        return self::where('is_read',0)->where('receiver_id',auth()->user()->id)->get();
    }
    public static function admin_all_notifications(){
        return self::get();
    }

    // for agent side notification
    public static function agent_unread_notifications(){
        return self::where('is_read',0)->where('receiver_id',auth()->user()->id)->where('receiver_partner_id',null)->get();
    }
    public static function agent_unread_count(){
        return self::where('is_read',0)->where('receiver_id',auth()->user()->id)->where('receiver_partner_id',null)->count();
    }
    public static function agent_all_notifications(){
        return self::where('receiver_id',auth()->user()->id)->where('receiver_partner_id',null)->get();
    }
    public static function notify($title, $detail,$receiver_id,$receiver_partner_id, $whatsapp, $email,$sms,$fcm_web_push,$fcm_mobile_push,$sender_id,$source_id,$calendar=0,$event_id = null)
    {
        self::create([
            'title' => $title,
            'detail' => $detail,
            'receiver_id'=> $receiver_id,
            'receiver_partner_id'=>$receiver_partner_id,
            // by default there value will be 0:
            'whatsapp' => $whatsapp,
            'email' => $email,
            'sms' => $sms,
            'fcm_web_push' => $fcm_web_push,
            'fcm_mobile_push' => $fcm_mobile_push,
            'calendar' => $calendar,

            'sender_id'=>$sender_id,
            'source_id'=>$source_id,
            'event_id'=>$event_id,
        ]);
    }
}
