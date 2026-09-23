<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\RolePermissionTypeFunction;

class RolePermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd('work');
        try{
            $target = explode('@',Route::current()->action['uses']);
            $target_class = $target[0];
            $target_method = $target[1];
            $role_module_id = $target_class::$role_module_id??null;
            if($role_module_id){
                $user = Auth::user();
                // dd($role_module_id, $target_method);
                $permission = $user->role_module_permission_via_method($role_module_id, $target_method);
                // dd($permission, $target_method);
                // $permission = $permissions->filter(function($value, $key) use($target_method){
                //     $module =  $value->role_module_functions->filter(function($value, $key)  use($target_method){
                //         return $value->method == $target_method;
                //     });
                //     return $module->first();
                // })->first();

                // dd($permission, $target_method);
                // dd($permissions,$permissions->where('role_module_function',function($query){
                //     return $query->where('method','index');
                // }));

                //old----------------------------------
                // $structure = RolePermissionTypeFunction::where('method',$target_method)
                // ->whereHas('role_permission_type',function($query) use($role_module_id){
                //     return $query->where('role_module_id',$role_module_id);
                // })->first();
                // $permission = $permissions->where('role_permission_type_id', $structure->role_permission_type_id)->first();
                // dd($permission ,$target_method);
                //old----------------------------------
                // dd($permission);
                if($permission){

                    if($permission->permission == 1){

                        return $next($request);
                    }else{
                        // dd($target_method);
                        if($permission->role_module_functions->where('method',$target_method)->first()->return_type == 'view'){
                            return back()->with(['error'=>[$permission->role_permission_type->denial_msg]]);
                        }else{

                            return response()->json(['errors'=>[$permission->role_permission_type->denial_msg]],401);
                        }
                    }
                }else{

                    $ignores = $target_class::$ignores??[];
                    if(isset($ignores[$target_method])){
                        return $next($request);
                    }


                    // dd('no permission found');
                    return redirect()->route('unauthorized');

                    // return $next($request);
                    // return response();
                    // if($permission->role_permission_type->return_type == 'view'){
                    //     return back()->with(['errors'=>[$permission->role_permission_type->denial_msg]]);
                    // }else{
                    //     return response()->json(['errors'=>[$permission->role_permission_type->denial_msg]],401);
                    // }

                    // if($structure->return_type == 'view'){
                    //     return back()->with(['errors'=>[$permission->role_permission_type->denial_msg]]);
                    // }else{
                    //     return response()->json(['errors'=>[$permission->role_permission_type->denial_msg]],401);
                    // }

                }

            }else{
                return $next($request);

            }
        }catch (\Throwable $e) {
            report($e);

            if (request()->expectsJson()) {
                return response()->json(['errors' => ['Permission check failed.']], 403);
            }

            return redirect()->route('unauthorized');
        }
    }
    // public function handleold(Request $request, Closure $next): Response
    // {
    //     // return $next($request);
    //     try{
    //         $target = explode('@',Route::current()->action['uses']);
    //         $target_class = $target[0];
    //         $target_method = $target[1];

    //         if(isset($target_class::$permissions)){
    //             $permissions = $target_class::$permissions;
    //             if(isset($permissions[$target_method])){
    //                 $user = Auth::user();
    //                 $permission = $permissions[$target_method];
    //                 $access = $user->role_module_permissions($permission['role_name_id']);
    //                 if($access){
    //                     $action = $access->{$permission['action'].'_permission'};
    //                     if($action){
    //                         return $next($request);
    //                     }else{
    //                         if($permission['return'] == 'json'){
    //                             return response()->json(['errors'=>$permission['denial_msg']], 401);
    //                         }
    //                         return redirect()->back()->with('error',$permission['denial_msg']);
    //                     }
    //                 }else{
    //                     if($permission['return'] == 'json'){
    //                         return response()->json(['errors'=>$permission['denial_msg']], 401);
    //                     }
    //                     return redirect()->back()->with('error',['something went wrong!']);
    //                 }
    //             }else{
    //                 return $next($request);
    //             }
    //         }else{
    //             return $next($request);
    //         }
    //     }catch(\Exception $e){
    //         dd($e->getMessage());
    //     }
    // }
}
