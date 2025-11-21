<?php

namespace App\Http\Controllers;

use App\Models\M_MatchPo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class MMatchPoController
 * @package App\Http\Controllers
 */
class M_MatchPoController extends Controller
{
    static $role_module_id = 68;
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
    public function index()
    {
        $breadcrumbs = [
            [
                'name'=>"M_MatchPo",
                'link'=>route("m-match-pos.index"),
                'active'=>true,
            ]
        ];
        $mMatchPos = M_MatchPo::paginate();


        return view('m-match-po.index', compact('mMatchPos','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $mMatchPos->perPage());
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
                'name'=>"M_MatchPo",
                'link'=>route("m-match-pos.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("m-match-pos.create"),
                'active'=>true,
            ]
        ];
        $mMatchPo = new M_MatchPo();
        return view('m-match-po.create', compact('mMatchPo','breadcrumbs'));
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
        $validator = Validator::make($request->all(), M_MatchPo::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $mMatchPo = M_MatchPo::create($data);

        return redirect()->route('m-match-pos.index')->with('success', 'M_MatchPo created successfully.');
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
                'name'=>"M_MatchPo",
                'link'=>route("m-match-pos.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("m-match-pos.show",$id),
                'active'=>true,
            ]
        ];
        $mMatchPo = M_MatchPo::find($id);

        return view('m-match-po.show', compact('mMatchPo','breadcrumbs'));
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
                'name'=>"M_MatchPo",
                'link'=>route("m-match-pos.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("m-match-pos.edit",$id),
                'active'=>true,
            ]
        ];
        $mMatchPo = M_MatchPo::find($id);

        return view('m-match-po.edit', compact('mMatchPo','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  M_MatchPo $mMatchPo
     * *
     */
    public function update(Request $request, M_MatchPo $mMatchPo)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), M_MatchPo::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $mMatchPo->update($data);

        return redirect()->route('m-match-pos.index')
            ->with('success', 'M_MatchPo updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $mMatchPo = M_MatchPo::find($id)->delete();

        return redirect()->route('m-match-pos.index')
            ->with('success', 'M_MatchPo deleted successfully');
    }
}
