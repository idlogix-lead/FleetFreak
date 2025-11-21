<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocationExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class LocationController
 * @package App\Http\Controllers
 */
class LocationController extends Controller
{
    static $ignores = [];

    static $role_module_id = 19;
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
                'name'=>"Location",
                'link'=>route("locations.index"),
                'active'=>true,
            ]
        ];

        $user = auth()->user();

        $company = $user->companies->first();
        $perPage = $request->input('perPage', 10);

        // if (!$company) {
        //     return redirect()->route('home')->with('error', 'No associated company found.');
        // }
        $companyId = auth()->user()->active_company() ?? null;

        $locations = Location::when($companyId, function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })->paginate($perPage);

        return view('location.index', compact('locations','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $locations->perPage());
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
                'name'=>"Location",
                'link'=>route("locations.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("locations.create"),
                'active'=>true,
            ]
        ];
        $location = new Location();
        return view('location.create', compact('location','breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request)
    {
        $payload = [];
        // Validate the request data
        $location_validator = Validator::make($request->all(),  [
			'name' => ['string','required'],
			'description' => ['nullable'],
            'company_id' => ['nullable'],
    ]);
        if ($location_validator->fails()) {
            return back()->with('errors', $location_validator->errors());
        }
        // Update lead attributes with validated data
        $location_data = $location_validator->validated();
        $location_data['created_by'] = auth()->user()->id;
        $payload['location_data'] = $location_data;
        Location::store_location($payload);

        // $location = Location::create($data);

        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
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
                'name'=>"Location",
                'link'=>route("locations.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("locations.show",$id),
                'active'=>true,
            ]
        ];
        $location = Location::where('company_id',auth()->user()->active_company())->find($id);

        return view('location.show', compact('location','breadcrumbs'));
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
                'name'=>"Location",
                'link'=>route("locations.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("locations.edit",$id),
                'active'=>true,
            ]
        ];
        $location = Location::where('company_id',auth()->user()->active_company())->find($id);

        return view('location.edit', compact('location','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Location $location
     * *
     */
    public function update(Request $request,$location)
    {
        $location = Location::find($location);
        $payload = [];
        // Validate the request data
        $location_validator = Validator::make($request->all(),  [
			'name' => ['string','required'],
			'description' => ['nullable'],
            'company_id' => ['nullable'],
    ]);
        if ($location_validator->fails()) {
            return back()->with('errors', $location_validator->errors());
        }
        // Update lead attributes with validated data
        $location_data = $location_validator->validated();
        $location_data['updated_by'] = auth()->user()->id;
        $payload['location_data'] = $location_data;
        $payload['location'] = $location;
        Location::update_location($payload);

        return redirect()->route('locations.index')
            ->with('success', 'Location updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $location = Location::where('company_id',auth()->user()->active_company())->find($id);
        $fromRateLists = $location->routesFrom()->whereHas('ratelist')->exists();
        $toRateLists = $location->routesTo()->whereHas('ratelist')->exists();

        if ($fromRateLists || $toRateLists) {
            return redirect()->back()->with('error', 'Location cannot be deleted because it has associated RateList records.');
        }
        else{
            $location->delete();
            return redirect()->route('locations.index')
            ->with('success', 'Location deleted successfully');
        }


    }
    public function export(){
        return Excel::download(new LocationExport, 'locations.xlsx');
    }
}
