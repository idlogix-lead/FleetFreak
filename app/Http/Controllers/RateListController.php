<?php

namespace App\Http\Controllers;


//use App\Models\PackageDetail;
use App\Models\RateList;
use App\Models\Route;
use App\Models\VehicleClass;
use App\Models\Event;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RateListExport;

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
    static $ignores = [];

    static $role_module_id = 8;
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
                'name'=>"Ratelist",
                'link'=>route("ratelists.index"),
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
        $ratelists = RateList::when($companyId, function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })->checkGlobal(8)->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name','ILIKE', '%' . $query . '%');
        })->paginate($perPage);


        return view('ratelist.index', compact('ratelists','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $ratelists->perPage());
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
                'name'=>"Ratelist",
                'link'=>route("ratelists.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("ratelists.create"),
                'active'=>true,
            ]
        ];
        $user = auth()->user();

        // Retrieve the company associated with the user
        $company = auth()->user()->active_company();

        // Check if the company exists
        // if (!$company) {
        //     return redirect()->route('home')->with('error', 'No associated company found.');
        // }

        // Fetch vehicle classes for the associated company
        $vehicleClasses = VehicleClass::when($company, function ($query) use ($company) {
            return $query->where('company_id', $company);
        })
        ->get();
        $ratelist = new RateList();
        $routes = Route::get();
        $vehicle_class = VehicleClass::get();

        return view('ratelist.create', compact('ratelist','breadcrumbs','routes','vehicle_class'));
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
            'description' => ['nullable'],
            'route_id'=> ['required'],
            'price'=>['required'],
            'vehicle_class_id'=>['required'],
            'estimated_time_hour'=>['nullable','integer'],
            'estimated_time_min'=>['nullable','integer'],
            'company_id' => ['nullable'],


        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors())->withInput();

            // return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $estimated_time_hour = isset($data['estimated_time_hour']) ? (int)$data['estimated_time_hour'] : 0;
        $estimated_time_min = isset($data['estimated_time_min']) ? (int)$data['estimated_time_min'] : 0;
        $data['estimated_time'] = ($estimated_time_hour *60) + $estimated_time_min;
        //$data['created_by'] = auth()->user()->id;
        $payload = [
            'ratelist'=>$data,
        ];
        // dd($payload);
        RateList::store_ratelist($payload);

        //$package = Package::create($data);

        return redirect()->route('ratelists.index')->with('success', 'Ratelist created successfully.');
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
                'name'=>"Ratelist",
                'link'=>route("ratelists.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("ratelists.show",$id),
                'active'=>true,
            ]
        ];
        $ratelist = RateList::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);

        return view('ratelist.show', compact('ratelist','breadcrumbs'));
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
                'name'=>"Ratelist",
                'link'=>route("ratelists.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("ratelists.edit",$id),
                'active'=>true,
            ]
        ];
        $ratelist = RateList::checkGlobal(self::$role_module_id)->where('company_id',auth()->user()->active_company())->find($id);
        $routes = Route::get();
        $vehicle_class = VehicleClass::get();


        return view('ratelist.edit', compact('ratelist','breadcrumbs','routes','vehicle_class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  RateList $ratelist
     * *
     */
    public function update(Request $request, RateList $ratelist)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
        'name' => ['required','string'],
        'description' => ['nullable'],
        'route_id'=> ['required'],
        'vehicle_class_id'=>['required'],
        // 'estimated_time'=>['required'],
        'estimated_time_hour'=>['nullable','integer'],
        'estimated_time_min'=>['nullable','integer'],


        'price'=>['required'],
        'company_id'=> ['nullable']

        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $estimated_time_hour = isset($data['estimated_time_hour']) ? (int)$data['estimated_time_hour'] : 0;
        $estimated_time_min = isset($data['estimated_time_min']) ? (int)$data['estimated_time_min'] : 0;
        $data['estimated_time'] = ($estimated_time_hour *60) + $estimated_time_min;
        //$data['updated_by'] = auth()->user()->id;
        $payload = [
            'ratelist'=>$data,
            'ratelist_id'=>$ratelist->id,
        ];

        //$package->update($data);

        //dd($payload);
        RateList::update_ratelist($payload);

        return redirect()->route('ratelists.index')
            ->with('success', 'Ratelist updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $ratelist = RateList::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('ratelists.index')
            ->with('success', 'Package deleted successfully');
    }

    public function export(){
        return Excel::download(new RateListExport, 'ratelists.xlsx');
    }
}
