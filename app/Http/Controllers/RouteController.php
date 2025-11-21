<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Event;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RouteExport;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Models\RouteRate;

/**
 * Class RouteController
 * @package App\Http\Controllers
 */
class RouteController extends Controller
{
    static $ignores = [];

    static $role_module_id = 7;
    public $my_companies;
    function __construct(){
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Route",
                'link'=>route("routes.index"),
                'active'=>true,
            ]
        ];
        $user = auth()->user();

        $company = $user->companies->first();

        // if (!$company) {
        //     return redirect()->route('home')->with('error', 'No associated company found.');
        // }
        $companyId = auth()->user()->active_company() ?? null;

        $query = $request->input('query');
        $perPage = $request->input('perPage', 10);

        $routes = Route::when($companyId, function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })->checkGlobal(7)->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name','ILIKE', '%' . $query . '%');
        })->paginate($perPage);

        return view('route.index', compact('routes','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $routes->perPage());
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
                'name'=>"Route",
                'link'=>route("routes.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("routes.create"),
                'active'=>true,
            ]
        ];
        $route = new Route();
        // $route_rate = RouteRate::get()->first();

        return view('route.create', compact('route','breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request)
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
            return back()->with('errors', $validator->errors())->withInput();
            // return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        //$data['created_by'] = auth()->user()->id;
        $payload = [
            'data'=>$data
        ];
        Route::store_route($payload);

        //$route = Route::create($data);

        return redirect()->route('routes.index')->with('success', 'Route created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    public function show($id)
    {
        $breadcrumbs = [
            [
                'name'=>"Route",
                'link'=>route("routes.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("routes.show",$id),
                'active'=>true,
            ]
        ];
        $route = Route::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);

        return view('route.show', compact('route','breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */
    public function edit($id)
    {
        $breadcrumbs = [
            [
                'name'=>"Route",
                'link'=>route("routes.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("routes.edit",$id),
                'active'=>true,
            ]
        ];
        $route = Route::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);
        // $route_rate = RouteRate::where('route_id',$route->id)->first();

        return view('route.edit', compact('route','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Route $route
     * *
     */
    public function update(Request $request, Route $route)
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
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        //$data['updated_by'] = auth()->user()->id;
        //$routeRates = RouteRate::findOrFail($package_route_id);

        $payload = [
            'data'=>$data,
            'route'=>$route,
            //'routeRates'=>$routeRates,
        ];
        //dd($payload);
        Route::update_route($payload);

        //$route->update($data);

        return redirect()->route('routes.index')
            ->with('success', 'Route updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $route = Route::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('routes.index')
            ->with('success', 'Route deleted successfully');
    }

    public function export(){
        return Excel::download(new RouteExport, 'routes.xlsx');
    }
}
