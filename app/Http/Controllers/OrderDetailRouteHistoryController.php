<?php

namespace App\Http\Controllers;
use App\Models\OrderDetailRouteHistory;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class OrderDetailRouteHistoryController extends Controller
{
    public function getRouteHistory(Request $request)
    {
        // Fetch active vehicles with incomplete orders
        $activeVehicles = Vehicle::where('is_status', 'active')
            ->whereHas('orderDetails', function ($query) {
                $query->where('status', 'incomplete');
            })
            ->pluck('vehicle_no', 'id');
    
        $vehicleId = $request->get('vehicle_id');
    
        // Fetch route history for the selected vehicle
        $routeHistories = [];
        if ($vehicleId) {
            $routeHistories = OrderDetailRouteHistory::whereHas('orderDetails', function ($query) use ($vehicleId) {
                $query->where('vehicle_id', $vehicleId);
            })
            ->orderBy('created_at')
            ->get(['latitude', 'longitude']);
        }
    
        // Handle AJAX request
        if ($request->ajax()) {
            return response()->json($routeHistories);
        }
    
        // For non-AJAX requests, return the view
        return view('vehicle.vehicle_map_final', [
            'routeHistories' => $routeHistories,
            'vehicleId' => $vehicleId,
            'activeVehicles' => $activeVehicles,
        ]);
    }
}
