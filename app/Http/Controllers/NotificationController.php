<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class NotificationController extends Controller
{
    public function savetoken(request $request)
    {
        $user_id=auth()->user()->id;
        // dd($request->currentToken);
        User::where('id',$user_id)->
            update(['firebase_web_token' => $request->currentToken]);
        return response()->json(['token saved'], 200);
    }
}
