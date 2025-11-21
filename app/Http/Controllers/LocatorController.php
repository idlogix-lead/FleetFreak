<?php

namespace App\Http\Controllers;

use App\Models\Locator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class LocatorController
 * @package App\Http\Controllers
 */
class LocatorController extends Controller
{
    static $ignores = [];
    public $my_companies;
    static $role_module_id = 53;
    // 28
    function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }
    public function index()
    {
        $breadcrumbs = [
            [
                'name'=>"Locator",
                'link'=>route("locators.index"),
                'active'=>true,
            ]
        ];
        $locators = Locator::paginate();


        return view('locator.index', compact('locators','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $locators->perPage());
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
                'name'=>"Locator",
                'link'=>route("locators.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("locators.create"),
                'active'=>true,
            ]
        ];
        $locator = new Locator();
        return view('locator.create', compact('locator','breadcrumbs'));
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
        $validator = Validator::make($request->all(),[
			'warehouse_id' => ['required'],
			'code' => ['nullable'],
			'locator_type' => ['nullable'],
			'is_active' => ['nullable'],
			'is_default' => ['nullable'],
			'relative_priority' => ['nullable'],
			'aisle' => ['nullable'], 
			'bin' => ['nullable'],
			'level' => ['nullable'],

        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $payload = [];
        $payload['data'] = $data;
        Locator::store_locator($payload);

        return redirect()->route('locators.index')->with('success', 'Locator created successfully.');
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
                'name'=>"Locator",
                'link'=>route("locators.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("locators.show",$id),
                'active'=>true,
            ]
        ];
        $locator = Locator::find($id);

        return view('locator.show', compact('locator','breadcrumbs'));
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
                'name'=>"Locator",
                'link'=>route("locators.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("locators.edit",$id),
                'active'=>true,
            ]
        ];
        $locator = Locator::find($id);

        return view('locator.edit', compact('locator','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Locator $locator
     * *
     */
    public function update(Request $request,$locator)
    {
        $locator = Locator::findOrFail($locator);
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'warehouse_id' => ['required'],
			'code' => ['nullable'],
			'locator_type' => ['nullable'],
			'is_active' => ['nullable'],
			'is_default' => ['nullable'],
			'relative_priority' => ['nullable'],
			'aisle' => ['nullable'], 
			'bin' => ['nullable'],
			'level' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $payload = [];
        $payload['data'] = $data;
        $payload['locator'] = $locator;
        Locator::update_locator($payload);
        return redirect()->route('locators.index')
            ->with('success', 'Locator updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $locator = Locator::find($id)->delete();

        return redirect()->route('locators.index')
            ->with('success', 'Locator deleted successfully');
    }
}
