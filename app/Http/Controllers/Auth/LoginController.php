<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role_crud;
// use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    // protected $redirectTo = RouteServiceProvider::HOME;

    // protected function authenticated(Request $request, $user)
    // {
    //     // dd(Session::getId());
    //     User::where('id',$user->id)->update(['session_id' => Session::getId()]);
    //     if($user->role->role_name != "superadmin"){
    //         // $role_cruds = Role_crud::
    //         // where('role_id',auth()->user()->role_id)
    //         // ->with('role_name')
    //         // ->whereHas('role_name',function($query){
    //         //     $query->whereNull('deleted_at');
    //         // })
    //         // ->join('roles','roles.id','role_cruds.role_id')
    //         // ->get();
    //         // $role_cruds = auth()->user()->get_user_roles_permissions(auth()->user());
    //         // $role_cruds = null;

    //         // Session::put('role_cruds',$role_cruds);
    //     }

    //     return redirect($user->role->home)->with('success',['Logged-In Successfully!']);
    // }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // protected function authenticated(Request $request)
    // {
    //     // Call saveToken method from NotificationController to save the Firebase token
    //     app(\App\Http\Controllers\NotificationController::class)->savetoken($request);

    //     return redirect()->intended('/');
    // }
}
