<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseController extends Controller
{
    protected $auth, $messaging;

   

    public function __construct(Messaging $messaging)
    {
        $this->middleware('auth:sanctum');
        $this->auth = Firebase::auth();
        $this->messaging = $messaging;
    }

    public function test_firebase()
    {
        $notification = [
            "title" => "zaroon order created",
            "body" => "vital hajj create an new order",
        ];
        $data = [
            "id" => 105
        ];
        $firebase_token = [
            auth()->user()->firebase_token,
        ];

        if (empty($firebase_token[0])) {
            // dd('Firebase token is empty for the authenticated user');
        }

        $resp = $this->send($firebase_token, $notification, $data);
        // dd('sent', $resp, $firebase_token);
    return response()->json(['success'=>'notification send successfully']);

    }

    public function send($firebase_token, $notification, $data)
    {
        $notification = Notification::create($notification['title'], $notification['body']);

        $message = CloudMessage::withTarget('token', $firebase_token[0])
            ->withNotification($notification)
            ->withData($data);

        $sendReport = $this->messaging->send($message);
        return $sendReport;
    }
}