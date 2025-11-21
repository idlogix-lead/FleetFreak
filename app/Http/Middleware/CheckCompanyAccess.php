<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // not used
        // $user = auth()->user();

        // if ($user) {
        //     // Retrieve all companies linked to the user
        //     $companies = $user->companies;

        //     if ($companies->count() == 1) {
        //         // If the user belongs to only one company, use that company's ID
        //         $company_id = $companies->first()->id;
        //     } elseif ($companies->count() > 1) {
        //         // If the user belongs to multiple companies, choose one (or ask the user)
        //         // Here we can either:
        //         // - Pick a default (e.g., first company)
        //         // - OR prompt the user to select one (this requires additional logic on the frontend)
        //         $company_id = $companies->first()->id; // You can adjust this logic based on your needs
        //     } else {
        //         // If the user doesn't belong to any company, handle appropriately
        //         return redirect()->route('noCompanyAccess'); // Example: redirect if no company is found
        //     }

        //     // Store company_id in session or use as needed
        //     // session(['company_id' => $company_id]);
        // }
        return $next($request);
    }
}
