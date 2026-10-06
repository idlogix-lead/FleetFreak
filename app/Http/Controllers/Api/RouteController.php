<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RateList;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * Class RouteController
 * @package App\Http\Controllers
 */

class RouteController extends Controller
{
    static $role_module_id = 7;

    static $ignores = [
        'api_index' => true,
        'getFrom' => true,
        'getTo' => true,
        'getRateList' => true,
        'api_store' => true,
        'api_update' => true,
        'api_destroy' => true,

    ];

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }
    public function getFrom()
    {
        // $user = Auth::user();
        $company_id = auth()->user()->active_company() ??  null;
        // dd($company_id);
        // $routes = Route::distinct()->pluck('from')->toArray();//->unique();
        // return response()->json($routes);
        $routes = Route::where('company_id', $company_id)->with('fromLoc')
            ->get()
            ->pluck('fromLoc.name')
            ->unique()
            ->values()
            ->toArray();

        return response()->json($routes);
    }

    // Method to get 'to' values
    public function getTo(Request $request)
    {
        // Validate the request
        $request->validate([
            'from' => 'required|string',
        ]);

        // Get the 'from' value from the request
        $from = $request->input('from');
        // $user = Auth::user();
        $company_id = auth()->user()->active_company() ??  null;
        // Fetch 'to' locations that correspond to the selected 'from' location
        // $toLocations = Route::where('from', $from)->pluck('to')->unique();
        $toLocations = Route::whereHas('fromLoc', function ($query) use ($from) {
            $query->where('name', $from);
        })
        ->with('toLoc') // Assuming 'toLocation' is the relationship for the 'to' field
        ->where('company_id', $company_id)
        ->get()
        ->pluck('toLoc.name')
        ->unique()
        ->filter() // Removes null values
        ->values() // Reindexes the array
        ->toArray();

        return response()->json($toLocations);
    }

    public function getRateList()
    {
        $user = Auth::user();
        $company_id = auth()->user()->active_company();

        $rateList = RateList::whereHas('route', function ($rQry) {
            $rQry->whereHas('fromLoc', function ($fromQuery) {
                $fromQuery->where('name', request()->from); // Match the 'from' name in 'fromloc'
            })
            ->whereHas('toLoc', function ($toQuery) {
                $toQuery->where('name', request()->to); // Match the 'to' name in 'toloc'
            });
        })->where('company_id',$company_id)
        // ->where("vehicle_class_id", request()->vehicle_class_id)
        ->with('route.fromLoc', 'route.toLoc') // Include relationships for eager loading
        ->get();
        return response()->json(['ratelist'=>$rateList]);

    }

    public function api_index(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "Route",
                'link' => route("routes.index"),
                'active' => true,
            ],
        ];

        // dd('hhhhhhhhhh');
        // $query = $request->input('query');
        // $user = Auth::user();
        $company_id = auth()->user()->active_company();
        $routes = Route::with('fromLoc','toLoc')->where('company_id',$company_id)->get();
        // ->when($query, function ($queryBuilder) use ($query) {
        //     $queryBuilder->where('name','like', '%' . $query . '%');
        // })->paginate();

        // return view('route.index', compact('routes','breadcrumbs'))
        //     ->with('i', (request()->input('page', 1) - 1) * $routes->perPage());
        return response()->json(['success' => true, 'data' => $routes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    public function create()
    {
        $breadcrumbs = [
            [
                'name' => "Route",
                'link' => route("routes.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("routes.create"),
                'active' => true,
            ],
        ];
        $route = new Route();
        // $route_rate = RouteRate::get()->first();

        // return view('route.create', compact('route','breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function api_store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            'from' => ['required','string'],
            'to' => ['required','string'],
            'distance' => ['required'],
            'distance_unit' => ['required'],
            'is_flight'=>['nullable'],
            // 'company_id' => ['nullable'],
            //'rate_with_fuel'=>['required'],
            //'rate_without_fuel'=>['required'],
        ]);
        if ($validator->fails()) {
            // dd($validator->errors());
            // return back()->with('errors', $validator->errors())->withInput();
            return response()->json(['error' => $validator->errors()], 400);
            // return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        //$data['created_by'] = auth()->user()->id;
        $payload = [
            'data' => $data,
        ];
        Route::store_route($payload);

        //$route = Route::create($data);

        // return redirect()->route('routes.index')->with('success', 'Route created successfully.');
        return response()->json(['success' => 'route create successfully', 'data' => $payload]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    // public function show($id)
    // {
    //     $breadcrumbs = [
    //         [
    //             'name' => "Route",
    //             'link' => route("routes.index"),
    //             'active' => false,
    //         ],
    //         [
    //             'name' => "Show",
    //             'link' => route("routes.show", $id),
    //             'active' => true,
    //         ],
    //     ];
    //     $route = Route::checkGlobal(7)->find($id);

    //     return view('route.show', compact('route', 'breadcrumbs'));
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */
    // public function edit($id)
    // {
    //     $breadcrumbs = [
    //         [
    //             'name' => "Route",
    //             'link' => route("routes.index"),
    //             'active' => false,
    //         ],
    //         [
    //             'name' => "Edit",
    //             'link' => route("routes.edit", $id),
    //             'active' => true,
    //         ],
    //     ];
    //     $route = Route::checkGlobal(7)->find($id);
    //     // $route_rate = RouteRate::where('route_id',$route->id)->first();

    //     return view('route.edit', compact('route', 'breadcrumbs'));
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Route $route
     * *
     */
    public function api_update(Request $request, Route $route, )
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
             'name' => ['required','string'],
            'from' => ['required','string'],
            'to' => ['required','string'],
            'distance' => ['required'],
            'distance_unit' => ['required'],
            'is_flight'=>['nullable'],
            'company_id' => ['nullable'],
            //'rate_with_fuel'=>['required'],
            //'rate_without_fuel'=>['required'],
        ]);
        if ($validator->fails()) {
            // dd($validator->errors());
            // return back()->with('errors', $validator->errors());
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        //$data['updated_by'] = auth()->user()->id;
        //$routeRates = RouteRate::findOrFail($package_route_id);

        $payload = [
            'data' => $data,
            'route' => $route,
            //'routeRates'=>$routeRates,
        ];
        //dd($payload);
        Route::update_route($payload);

        //$route->update($data);

        // return redirect()->route('routes.index')
        //     ->with('success', 'Route updated successfully');
        return response()->json(['success' => 'route update successfully', 'data' => $payload], 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        // $user = Auth::user();
        $company_id =auth()->user()->active_company();
        $route = Route::where('company_id', $company_id)->findOrFail($id)->delete();

        // return redirect()->route('routes.index')
        //     ->with('success', 'Route deleted successfully');
        return response()->json(['message' => 'route delete successfully']);
    }

}
