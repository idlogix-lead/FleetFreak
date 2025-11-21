<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ActivityController
 * @package App\Http\Controllers
 */
class ActivityController extends Controller
{
    static $ignores = ['deleteActivityRow' => true];
    static $role_module_id = 33;
    public $my_companies;
    // ignored permission functions
    // static $ignores = ['partner_dropdown'=>true, 'create_customer_from_order'=>true];

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
                'name'=>"Maintenance Activity",
                'link'=>route("activities.index"),
                'active'=>true,
            ]
        ];

        $perPage = $request->input('perPage', 10);
        $activities = Activity::where('company_id',auth()->user()->active_company())->paginate($perPage);

        return view('activity.index', compact('activities','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $activities->perPage());
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
                'name'=>"Maintenance Activity",
                'link'=>route("activities.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("activities.create"),
                'active'=>true,
            ]
        ];
        $activity = new Activity();
        return view('activity.create', compact('activity','breadcrumbs'));
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
        // dd($request);
        $payload = [];
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
			'description' => ['nullable'],
			'is_active' => ['nullable'],
            // activityLine validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],

            'rows.*.seq_no' => ['required'],
            'rows.*.name' => ['required',],
            'rows.*.description' => ['nullable'],
            'rows.*.is_active' => ['nullable']
        ]);
        if (!isset($request['rows']) || count($request['rows']) < 1) {
            return back()->withErrors(['errors' => 'At least one activity line is required.'])->withInput();
        }
        // dd($validator);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // dd($data['rows']);
        $data['created_by'] = auth()->user()->id;
        $payload['company_id'] = auth()->user()->active_company();

       $payload['data'] =$data;
       Activity::store_activity($payload);

        return redirect()->route('activities.index')->with('success', 'Activity created successfully.');
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
                'name'=>"Maintenance Activity",
                'link'=>route("activities.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("activities.show",$id),
                'active'=>true,
            ]
        ];
        $activity = Activity::where('company_id',auth()->user()->active_company())->find($id);

        return view('activity.show', compact('activity','breadcrumbs'));
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
                'name'=>"Maintenance Activity",
                'link'=>route("activities.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("activities.edit",$id),
                'active'=>true,
            ]
        ];
        $activity = Activity::where('company_id',auth()->user()->active_company())->find($id);

        return view('activity.edit', compact('activity','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Activity $activity
     * *
     */
    public function update(Request $request,$activity)
    {
        // Validate the request data
        $activity = Activity::find($activity);
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
			'description' => ['nullable'],
			'is_active' => ['required'],
            // activitylines validation:
            'rows' => ['nullable', 'array'],
            'rows.*.row_id'=>['nullable'],
            'rows.*.seq_no' => ['required'],
            'rows.*.name' => ['required',],
            'rows.*.description' => ['nullable'],
            'rows.*.is_active' => ['nullable']
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // dd($data);
        $data['updated_by'] = auth()->user()->id;
        $payload['data']= $data;


        $payload['activity']= $activity;
        $payload['company_id']= auth()->user()->active_company();

        Activity::update_activity($payload);

        return redirect()->route('activities.edit',$activity->id)
            ->with('success', 'Activity updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $activity = Activity::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully');
    }
    public function deleteActivityRow($id)
    {
        try {
            // Find the row by ID and delete it
            $activity_line = ActivityLine::findOrFail($id);
            if($activity_line->invoiceLines()->exists()){
                // dd('in the main row',$activity_line);
                return response()->json(['error' => true, 'message' => 'you cant remove this row it is associated with invoice line ']);
            }
            else{
                // dd('in the else row',$activity_line);
                $activity_line->delete();
                return response()->json(['success' => true, 'message' => 'Row removed successfully']);
            }


        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove row'], 500);
        }
    }
}
