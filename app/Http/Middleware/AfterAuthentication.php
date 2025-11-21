<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AfterAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && auth()->user()->actor_id == 4 && auth()->user()->flag == 1) {
            // Redirect to the password change page
            if (!$request->is('password/change')) {
                // Redirect to the password change page if they are not on it
                return redirect()->route('password.change');
            }
        }

        if(Auth::check() && !auth()->user()->is_super_admin && !auth()->user()->companies()->count()){
            if (!$request->is('register_process')) {
                return redirect()->route('register.company');
            }
        }

        return $next($request);
    }
}
