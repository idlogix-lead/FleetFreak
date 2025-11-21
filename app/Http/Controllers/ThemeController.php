<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\User;
use App\models\RolePermission;
use App\models\RolePermissionType;
use App\models\RolePermissionTypeFunction;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function update_theme(Request $request)
    {
        $request->validate([
            'theme' => 'required'
        ]);
        $user = User::find(Auth::user()->id);
        if($request->input('theme') == 'light-theme' || $request->input('theme') == 'dark-theme'|| $request->input('theme') == 'semi-dark'){
            $user['header_color'] = null;
            $user['sidebar_color'] = null;

        }
        $user->update(['theme' => $request->input('theme'),]);
        return redirect()->back();
    }
    public function update_header(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->header_color = $request->color;
        $user->save();
        return redirect('/');
    }
    public function update_sidebar(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->sidebar_color = $request->color;
        $user->save();
        return redirect('/');
    }
}

