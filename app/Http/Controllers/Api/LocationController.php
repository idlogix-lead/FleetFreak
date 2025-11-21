<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class LocationController
 * @package App\Http\Controllers
 */
class LocationController extends Controller
{
    static $ignores = [
        'api_store' => true,
        'api_index' => true,
        'api_update' => true,
        'api_show' => true,
        'api_destroy'=>true
    ];

    static $role_module_id = 19;
    public $my_companies;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    public function api_index()
    {
        // $user = auth()->user();

        // $company = $user->companies->first();
        $company_id = auth()->user()->active_company();
        $locations = Location::where('company_id',$company_id)->get();
        // if (!$company) {
        //     return redirect()->route('home')->with('error', 'No associated company found.');
        // }
        // $companyId = $company->id ?? null;

        // $locations = Location::when($companyId, function ($query) use ($companyId) {
        //     return $query->where('company_id', $companyId);
        // })->get();

        return response()->json(['status'=>'success','locations'=> $locations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function api_store(Request $request)
    {
        $payload = [];
        // Validate the request data
        $location_validator = Validator::make($request->all(),  [
			'name' => ['required'],
			'description' => ['nullable'],
            'company_id' => ['nullable'],
    ]);
        if ($location_validator->fails()) {
            return response()->json(['errors'=>$location_validator->errors()],400);
        }
        // Update lead attributes with validated data
        $location_data = $location_validator->validated();
        $location_data['created_by'] = auth()->user()->id;
        $payload['location_data'] = $location_data;
        Location::store_location($payload);

        // $location = Location::create($data);

        return response()->json(['suceess'=>'Location created successfully','data'=>$location_data],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    public function api_show($id)
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

        return response()->json(['location'=>$location,'breadcrumbs'=>$breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Location $location
     * *
     */
    public function api_update(Request $request,$location)
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

        return response()->json(['suceess'=>'Location updated successfully','data'=>$location_data],200);

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $location = Location::where('company_id',auth()->user()->active_company())->find($id);
        $fromRateLists = $location->routesFrom()->whereHas('ratelist')->exists();
        $toRateLists = $location->routesTo()->whereHas('ratelist')->exists();

        if ($fromRateLists || $toRateLists) {
            return response()->json(['error', 'Location cannot be deleted because it has associated RateList records.']);
        }
        else{
            $location->delete();
            return response()->json(['suceess'=>'Location deleted successfully'],200);

        }


    }
}
