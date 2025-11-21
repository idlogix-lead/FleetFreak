<?php

namespace App\Http\Controllers;

use App\Models\AccountType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class AccountTypeController
 * @package App\Http\Controllers
 */
class AccountTypeController extends Controller
{
    static $ignores = ['get_accounType_ajax' => true];
    static $role_module_id = 25;
    function __construct(){
        $this->middleware('RolePermissions');
    }
    /**
     * Display a listing of the resource.
     *
     * *
     */
    public function index()
    {
        $breadcrumbs = [
            [
                'name'=>"AccountType",
                'link'=>route("account-types.index"),
                'active'=>true,
            ]
        ];
        $accountTypes = AccountType::with([
            'parent'
        ])->paginate();

        return view('account-type.index', compact('accountTypes','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $accountTypes->perPage());
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
                'name'=>"AccountType",
                'link'=>route("account-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("account-types.create"),
                'active'=>true,
            ]
        ];
        $accountType = new AccountType();
        return view('account-type.create', compact('accountType','breadcrumbs'));
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
            'name' => 'required',
            'code' => ['required', Rule::unique('account_types')],
            'is_active' => ['required'],
            'description' => ['nullable'],
            'parent_id' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        // $company =  auth()->user()->companies->first();
        // $data['company_id'] = $company->id;

        $accountType = AccountType::create($data);

        return redirect()->route('account-types.index')->with('success', 'Account Type created successfully.');
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
                'name'=>"AccountType",
                'link'=>route("account-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("account-types.show",$id),
                'active'=>true,
            ]
        ];
        $accountType = AccountType::find($id);

        return view('account-type.show', compact('accountType','breadcrumbs'));
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
                'name'=>"AccountType",
                'link'=>route("account-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("account-types.edit",$id),
                'active'=>true,
            ]
        ];
        $accountType = AccountType::find($id);

        return view('account-type.edit', compact('accountType','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  AccountType $accountType
     * *
     */
    public function update(Request $request, AccountType $accountType)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => ['required', Rule::unique('account_types')->ignore($accountType->id)],
            // 'code' => 'required',
            'is_active' => ['required'],
            'description' => ['nullable'],
            'parent_id' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $accountType->update($data);

        // return redirect()->route('account-types.index')
        return back()->with('success', 'Account Type updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $accountType = AccountType::find($id)->delete();

        return redirect()->route('account-types.index')
            ->with('success', 'AccountType deleted successfully');
    }

    public function get_accounType_ajax($parent_id = null){
        $search = $_GET['search']??'';

        $resp =  AccountType::when($parent_id, function($query) use($parent_id){
            return $query->where('parent_id', $parent_id);
        })
        ->where('is_active', 1)
        ->when($search, function($query) use($search){
            return $query->where(function($where) use($search) {
                return $where->where('name', 'ILIKE', "%".$search."%")
                ->orWhere('code', 'ILIKE', "%".$search."%");
            });
        })
        ->get();
        return response()->json($resp, 200);
    }
}
