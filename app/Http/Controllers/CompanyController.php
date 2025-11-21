<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Account;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Support\Facades\Hash;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class CompanyController
 * @package App\Http\Controllers
 */
class CompanyController extends Controller
{
    // static $ignores = ['register_company' => true, ''];
    public $my_companies;
    static $role_module_id = 40;

    function __construct(){
        // $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            if(!auth()->user()->is_company_admin){
                return redirect()->route('dashboard')->with('You are not allowd to access companies Module!');
            }
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
                'name'=>"Company",
                'link'=>route("companies.index"),
                'active'=>true,
            ]
        ];
        $perPage = $request->input('perPage', 10);
        $companies = Company::myCompanies()->paginate($perPage);

        return view('company.index', compact('companies','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $companies->perPage());
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
                'name'=>"Company",
                'link'=>route("companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("companies.create"),
                'active'=>true,
            ]
        ];
        $company = new Company();
        return view('company.create', compact('company','breadcrumbs'));
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
            'name' => 'required|string',
			'description' => 'nullable|string',
			'address1' => 'string|nullable',
			'address2' => 'string|nullable',
			'address3' => 'string|nullable',
			'city' => 'string|nullable',
			'country' => 'string|nullable',
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $company = Company::create($data);
        $user_company = UserCompany::create([
            'user_id'=>auth()->user()->id,
            'company_id'=>$company->id,
        ]);

        // creating default accounts for registered company
        Account::defaultAccounts($company->id,auth()->user()->id);


        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
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
                'name'=>"Company",
                'link'=>route("companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("companies.show",$id),
                'active'=>true,
            ]
        ];
        $company = Company::find($id);

        return view('company.show', compact('company','breadcrumbs'));
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
                'name'=>"Company",
                'link'=>route("companies.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("companies.edit",$id),
                'active'=>true,
            ]
        ];
        $company = Company::myCompanies()->find($id);

        return view('company.edit', compact('company','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Company $company
     * *
     */
    public function update(Request $request, Company $company)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
			'description' => 'nullable|string',
			'address1' => 'string|nullable',
			'address2' => 'string|nullable',
			'address3' => 'string|nullable',
			'city' => 'string|nullable',
			'country' => 'string|nullable',
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $company->update($data);

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $company = Company::find($id)->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully');
    }

    public function register_company_store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(),[

            'company_name' => ['required',Rule::unique('companies','name')],
            // 'company_name' => ['required'],
			// 'address1' => ['required'],
            // 'city'=> ['required'],
            // 'country'=> ['required']
            ]);
       // Check if validation fails
        if ($validator->fails()) {
            // Return JSON response with validation errors
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message'=> 'Company name not Available or Someone has already registered with this name.'
            ]);
        }

    // Update lead attributes with validated data
        $data = $validator->validated();

        // Here you can store the validated data into the database
        // Example:
        // Company::create($data); // Assuming you have a Company model

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Company information saved successfully.'
        ]);

    }
    public function register_company(){
        return view('auth.register_process', ['other'=>true]);

    }
    public function register_process(){
        // dd('register_process');
        if(auth()->user()->active_company_id){
            return redirect()->route('dashboard');
        }
        return view('auth.register_process');
    }

    public function change_active_company(Request $request){
        $validator = Validator::make($request->all(),[
            'company_id' => [Rule::exists('user_companies')->where('user_id', auth()->user()->id)],
        ]);
        if ($validator->fails()) {
            // return back()->with('errors', $validator->errors());
            Session::flash('errors', $validator->errors());

            // return response()->json(['errors'=> $validator->errors()->all()], 401);
        }
        $data = $validator->validated();

        User::where('id', auth()->user()->id)
        ->update([
            'active_company_id' => $data['company_id']
        ]);
        Session::flash('success', 'Active Company Changed Successfully!');
        return response()->json(['success'=> 'Active Company Changed Successfully!'], 200);
    }

    public function register_submit(Request $request)
    {
        // dd($request);
        // Validate the request data
        $validator = Validator::make($request->all(),[
            // 'email'=>'required',
            'company_name' => 'required',
			'address1' => 'required',
            'city'=> 'required',
            'country'=> 'required',
            'create_type'=>'required',
            'name'=> [Rule::requiredIf($request->create_type == 1)] ,
            'password' => [Rule::requiredIf($request->create_type == 1),'min:8','confirmed'],
            'password_confirmation' => [Rule::requiredIf($request->create_type == 1), 'min:8'],
            'phone_no'=>[Rule::requiredIf($request->create_type == 1)],

        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();


        // $data['created_by'] = auth()->user()->id;
        // dd($data);
        $user = auth()->user();

        $company = Company::create([
            'name'=>$data['company_name'],
            'address1'=>$data['address1'] ?? null,
            'email'=>$user->email,
            'city'=>$data['city'] ?? null,
            'description'=>'',
            'client_id'=>$user->client_id,
            'country'=>$data['country'] ?? null,
        ]);

        if($data['create_type'] == 1){
            User::where('id', $user->id)->update([
                'name' => $data['name'],
                'phone_no1' => $data['phone_no'],
                'active_company_id' => $company->id
            ]);
        }

        $user_company = UserCompany::create([
            'user_id'=>$user->id,
            'company_id'=>$company->id,
        ]);

        // creating default accounts for registered company
        Account::defaultAccounts($company->id,$user->id);

        if($data['create_type'] == 1){
            return redirect()->route('dashboard')->with('success', 'Company Registered Successfully!');
        }else{
            return redirect()->route('companies.index')->with('success', 'Company Registered Successfully!');
        }
    }

}
