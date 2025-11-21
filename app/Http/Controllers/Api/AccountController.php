<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //

    static $ignores = [
        'api_get_code_no' => true,
    ];

    static $role_module_id = 24;
    public $my_companies;

    function __construct(){
        $this->middleware('auth:sanctum');
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
    }

    public function  api_store(Request $request){
        
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
                // return back()->with('errors', $validator->errors());
                return response()->json($validator->errors());
            }
            // Update lead attributes with validated data
            $data = $validator->validated();
            $data['created_by'] = auth()->user()->id;
            // $company =  auth()->user()->companies->first();
            $data['company_id'] = auth()->user()->active_company();
            // get_code_no
    
            $account = Account::create($data);
            return response()->json(['message'=>'account created successfully.','account'=>$account]);
            // Account::where('id', $account->id)->first();
            // $account->accountType;

    
            // return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
        
    }

    public static function api_get_code_no($account_type_id, $account_subtype_id,$company_id=null){

        if(!$company_id){
            // $company =  auth()->user()->companies->first();
            // $company_id=$company->id;
            // $company=auth()->user()->active_company();
            $company = auth()->user()->active_company();
        }
        $account = Account::where('account_type_id', $account_type_id)
        ->where('company_id', $company)
        ->where('account_subtype_id', $account_subtype_id)
        ->orderByDesc('id')->first();

        if($account){
            $code = $account->code;
            // // dd($code);
            // $code = explode('-', $code);
            // // dd($code);
            // $no = intval(end($code));
            // dd($no);
            // $no++;
            $codeArray = explode('-', $code);

            // Get the last number, increment it, and remove it from the array
            $no = intval(array_pop($codeArray));
            $no++;

            // Recreate the code with the incremented number
            $newCode = implode('-', $codeArray) . '-' . $no;

        }else{

            // dd('here');
            $accountSubTypeCode = AccountType::where('id', $account_subtype_id)->first();
            $accountTypeCode = AccountType::where('id', $account_type_id)->first();
            // dd($accountTypeCode->code,'-',$accountSubTypeCode->code);
            $newCode = $accountTypeCode->code.'-'.$accountSubTypeCode->code.'-1';
            // $no = 1;
        }

        // return $no;

        return response()->json($newCode);
    }

}
