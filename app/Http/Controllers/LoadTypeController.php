<?php

namespace App\Http\Controllers;

use App\Models\LoadType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class LoadTypeController
 * @package App\Http\Controllers
 */
class LoadTypeController extends Controller
{

    static $ignores = [];

    static $role_module_id = 27;
    public $my_companies;
    // 27
    function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * *
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"LoadType",
                'link'=>route("load-types.index"),
                'active'=>true,
            ]
        ];
        $company_id = auth()->user()->active_company();
        $perPage = $request->input('perPage', 10);

        $loadTypes = LoadType::where('company_id', $company_id)->paginate($perPage);

        return view('load-type.index', compact('loadTypes','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $loadTypes->perPage());
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
                'name'=>"LoadType",
                'link'=>route("load-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("load-types.create"),
                'active'=>true,
            ]
        ];
        $loadType = new LoadType();
        return view('load-type.create', compact('loadType','breadcrumbs'));
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
        $validator = Validator::make($request->all(), LoadType::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $company_id = auth()->user()->active_company();

        $loadType = LoadType::create([
            'name'=>$data['name'],
            'description'=>$data['description'],
            'company_id'=>$company_id
        ]);

        return redirect()->route('load-types.index')->with('success', 'LoadType created successfully.');
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
                'name'=>"LoadType",
                'link'=>route("load-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("load-types.show",$id),
                'active'=>true,
            ]
        ];
        $loadType = LoadType::where('company_id',auth()->user()->active_company())->find($id);

        return view('load-type.show', compact('loadType','breadcrumbs'));
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
                'name'=>"LoadType",
                'link'=>route("load-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("load-types.edit",$id),
                'active'=>true,
            ]
        ];
        $loadType = LoadType::where('company_id',auth()->user()->active_company())->find($id);

        return view('load-type.edit', compact('loadType','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  LoadType $loadType
     * *
     */
    public function update(Request $request, LoadType $loadType)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), LoadType::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $company_id = auth()->user()->active_company();


        $loadType->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'company_id' => $company_id
        ]);

        return redirect()->route('load-types.index')
            ->with('success', 'LoadType updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $loadType = LoadType::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('load-types.index')
            ->with('success', 'LoadType deleted successfully');
    }
}
