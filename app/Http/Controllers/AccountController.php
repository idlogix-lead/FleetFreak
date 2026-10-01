<?php

namespace App\Http\Controllers;

use App\Models\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class AccountController
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    static $ignores = ['get_code_no' => true];

    static $role_module_id = 24;
    function __construct(){
        $this->middleware('RolePermissions');
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
                'name'=>"Account",
                'link'=>route("accounts.index"),
                'active'=>true,
            ]
        ];
        $companyId = auth()->user()->active_company();
        $perPage = $request->input('perPage', 10);
        $accounts = Account::where('company_id', $companyId)->paginate($perPage);

        return view('account.index', compact('accounts','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $accounts->perPage());
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
                'name'=>"Account",
                'link'=>route("accounts.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("accounts.create"),
                'active'=>true,
            ]
        ];
        $account = new Account();
        return view('account.create', compact('account','breadcrumbs'));
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
        // $req = $request->all();

        $validator = Validator::make($request->all(), [
			'name' => 'required',
			// 'code' => 'required|string',
            'code' => ['required', Rule::unique('accounts')],
			'description' => 'nullable',
			'is_active' => 'required',
			'is_summary' => 'required',
			// 'company_id' => 'required',
			'account_type_id' => 'required',
			'account_subtype_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $company =  auth()->user()->companies->first();
        $data['company_id'] = $company->id;
        // get_code_no

        $account = Account::create($data);
        // Account::where('id', $account->id)->first();
        $account->accountType;

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
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
                'name'=>"Account",
                'link'=>route("accounts.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("accounts.show",$id),
                'active'=>true,
            ]
        ];
        $account = Account::find($id);

        // return view('account.show', compact('account','breadcrumbs'));
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
                'name'=>"Account",
                'link'=>route("accounts.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("accounts.edit",$id),
                'active'=>true,
            ]
        ];

        $company =  auth()->user()->companies->first();
        // $data['company_id'] = $company->id;
        $account = Account::where('company_id', $company->id)->find($id);

        return view('account.edit', compact('account','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Account $account
     * *
     */
    public function update(Request $request, Account $account)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
			'name' => 'required',
			// 'code' => 'required|string',
            'code' => ['required', Rule::unique('accounts')->ignore($account->id)],
			'description' => 'nullable',
			'is_active' => 'required',
			'is_summary' => 'required',
			// 'company_id' => 'required',
			'account_type_id' => 'required',
			'account_subtype_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        // dd($account->is_system);
        if($account->is_system==true){
            return back()->withErrors(['error' => 'You can’t edit system accounts'])->withInput();
        }
        else{
            $account->update($data);

            return redirect()->route('accounts.index')
                ->with('success', 'Account updated successfully');
        }

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $company =  auth()->user()->companies->first();
        $account = Account::where('company_id', $company->id)->where('id',$id)->first();
        if($account->is_system==true){
            return redirect()->route('accounts.index')->with('error', 'System account cannot be deleted');

        }
        else{
            $account->delete();
            return redirect()->route('accounts.index')->with('success', 'Account deleted successfully');
        }

    }

    public static function get_code_no($account_type_id, $account_subtype_id,$company_id=null){

        if(!$company_id){
            $company =  auth()->user()->companies->first();
            $company_id=$company->id;
        }
        $account = Account::where('account_type_id', $account_type_id)
        ->where('company_id', $company_id)
        ->where('account_subtype_id', $account_subtype_id)
        ->orderByDesc('id')->first();

        if($account){
            $code = $account->code;
            $code = explode('-', $code);
            $no = intval(end($code));
            $no++;
        }else{
            $no = 1;
        }

        return $no;
    }
}
