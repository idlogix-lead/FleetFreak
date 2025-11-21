<?php

namespace App\Http\Controllers\Api;

//use App\Models\PackageDetail;
use App\Http\Controllers\Controller;
use App\Models\RateList;
use App\Models\Route;
use App\Models\VehicleClass;
use App\Models\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class PackageController
 * @package App\Http\Controllers
 */
class RateListController extends Controller
{

    static $role_module_id = 8;
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');

    }
    static $ignores = [
        'api_store' => true,
        'api_index' => true,
        'api_update' => true,
        'api_show' => true,
        'api_edit' => true,
        'api_destroy'=>true
    ];
    public function api_index()
    {
        $user=auth()->user();
        $company_id=auth()->user()->active_company();
        // Retrieve all rate lists with their related routes
        $packages = RateList::with('route', 'vehicle_class')->where('company_id',$company_id)->get();

        // Return the results as a JSON response
        return response()->json(['status'=>'success','RateLists'=>$packages]);
    }

    public function api_create()
    {

        $breadcrumbs = [
            [
                'name' => "Ratelist",
                'link' => route("ratelists.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("ratelists.create"),
                'active' => true,
            ],
        ];
        $user=auth()->user();
        $company_id=auth()->user()->active_company();
        $ratelist = new RateList();
        $routes = Route::where('company_id',$company_id)->get();
        $vehicle_class = VehicleClass::get();

        return view('ratelist.create', compact('ratelist', 'breadcrumbs', 'routes', 'vehicle_class'));
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
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'route_id' => ['required'],
            'price' => ['required'],
            'vehicle_class_id' => ['required'],
            'estimated_time_hour' => ['nullable', 'integer'],
            'estimated_time_min' => ['nullable', 'integer'],
            'company_id' => ['nullable'],


        ]);
        if ($validator->fails()) {
            // return back()->with('errors', $validator->errors())->withInput();
            return response()->json(['errors'=>$validator->errors()],400);

            // return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $estimated_time_hour = isset($data['estimated_time_hour']) ? (int) $data['estimated_time_hour'] : 0;
        $estimated_time_min = isset($data['estimated_time_min']) ? (int) $data['estimated_time_min'] : 0;
        $data['estimated_time'] = ($estimated_time_hour * 60) + $estimated_time_min;
        //$data['created_by'] = auth()->user()->id;
        $payload = [
            'ratelist' => $data,
        ];
        // dd($payload);
        RateList::store_ratelist($payload);

        //$package = Package::create($data);

        // return redirect()->route('ratelists.index')->with('success', 'Ratelist created successfully.');
        return response()->json(['suceess'=>'rate_list created successfully','data'=>$data],200);
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
                'name' => "Ratelist",
                'link' => route("ratelists.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("ratelists.show", $id),
                'active' => true,
            ],
        ];
        $user=auth()->user();
        $company_id=auth()->user()->active_company();
        $ratelist = RateList::checkGlobal(8)->where('company_id',$company_id)->find($id);

        // return view('ratelist.show', compact('ratelist', 'breadcrumbs'));
        return response()->json(['ratelist'=>$ratelist,'breadcrumbs'=>$breadcrumbs]);
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
                'name' => "Ratelist",
                'link' => route("ratelists.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("ratelists.edit", $id),
                'active' => true,
            ],
        ];
        $user=auth()->user();
        $company_id=auth()->user()->active_company();
        $ratelist = RateList::checkGlobal(8)->where('company_id',$company_id)->find($id);
        $routes = Route::get();
        $vehicle_class = VehicleClass::where('company_id',$company_id)->get();

        return view('ratelist.edit', compact('ratelist', 'breadcrumbs', 'routes', 'vehicle_class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  RateList $ratelist
     * *
     */
    public function api_update(Request $request, RateList $ratelist)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'route_id' => ['required'],
            'vehicle_class_id' => ['required'],
            // 'estimated_time'=>['required'],
            'estimated_time_hour' => ['nullable', 'integer'],
            'estimated_time_min' => ['nullable', 'integer'],

            'price' => ['required'],

        ]);
        if ($validator->fails()) {
            // return back()->with('errors', $validator->errors());
            return response()->json(['errors'=>$validator->errors()],400);
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $estimated_time_hour = isset($data['estimated_time_hour']) ? (int) $data['estimated_time_hour'] : 0;
        $estimated_time_min = isset($data['estimated_time_min']) ? (int) $data['estimated_time_min'] : 0;
        $data['estimated_time'] = ($estimated_time_hour * 60) + $estimated_time_min;
        //$data['updated_by'] = auth()->user()->id;
        $payload = [
            'ratelist' => $data,
            'ratelist_id' => $ratelist->id,
        ];

        //$package->update($data);

        //dd($payload);
        RateList::update_ratelist($payload);

        // return redirect()->route('ratelists.index')
        //     ->with('success', 'Ratelist updated successfully');
        return response()->json(['success'=>'ratelist update successfully','payload'=>$payload]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $user=auth()->user();
        $company_id=auth()->user()->active_company();
        $ratelist = RateList::find($id)->where('company_id',$company_id)->delete();

        // return redirect()->route('ratelists.index')
        //     ->with('success', 'Package deleted successfully');
        return response()->json(['success'=>'ratelist delete successfully'],200);

    }
}
