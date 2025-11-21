<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class NotificationController extends Controller
{
    // by default there is no ignore function
    static $ignores = [
        'markAsRead' => true,
        'markAllAsRead' => true,
        'unreadCount' => true,
        'api_index' => true,
    ];

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }
    // public function sendPushNotification()
    // {
    //     $firebase = (new Factory)
    //         ->withServiceAccount('F:\\zaroon transport\\ride_booking_idlogix_synadmin_laravel\\config\\firebase_credentials.json');

    //     $messaging = $firebase->createMessaging();

    //     $message = CloudMessage::fromArray([
    //         'notification' => [
    //             'title' => 'Hello from Firebase!',
    //             'body' => 'This is a test notification.'
    //         ],
    //         'topic' => 'global'
    //     ]);

    //     $messaging->send($message);

    //     return response()->json(['message' => 'Push notification sent successfully']);
    // }

    public function sendPushNotification()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config_path('firebase_credentials.json'));

        $messaging = $firebase->createMessaging();

        $message = CloudMessage::withTarget('topic', 'admin_notifications')
            ->withNotification(Notification::create('Hello from Firebase!', 'This is a test notification.'));

        try {
            $response = $messaging->send($message);
            \Log::info('Firebase response: ', (array) $response);
            return response()->json(['message' => 'Push notification sent successfully', 'response' => $response], 200);
        } catch (\Exception $e) {
            \Log::error('Error sending Firebase notification: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send notification'], 500);
        }
    }

    public function api_index()
    {

        $userId = auth()->user()->id;
        // dd($userId);

       
        $notifications = Notification::where('receiver_id', $userId)
            ->with('receiver','sender')
            ->get();

        return response()->json(['notifications' => $notifications]);

    }

    public function markAsRead($id)
    {
        $user = auth()->user()->id;
        $notification = Notification::where('id', $id)
            ->where('receiver_id', $user)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found or access denied.'], 404);
        }

        $notification->is_read = 1;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read successfully.']);
    }

    public function markAllAsRead()
    {
        $userId = auth()->user()->id;

        Notification::where('receiver_id', $userId)
            ->update(['is_read' => 1]);

        return response()->json(['message' => 'All notifications marked as read successfully.']);
    }

    public function unreadCount()
    {
        $userId = auth()->user()->id;

        $unreadCount = Notification::where('receiver_id', $userId)
            ->where('is_read', 0)
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

}
