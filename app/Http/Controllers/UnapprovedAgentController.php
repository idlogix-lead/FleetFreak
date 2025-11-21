<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


use App\Http\Controllers\Api\WhatsAppController;






class UnapprovedAgentController extends Controller
{
    static $role_module_id = 21;


    public $my_companies;

    function __construct(){
        $this->middleware('RolePermissions');

        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });

    }




    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"Unapproved Agent",
                'link'=>route("unapproved_agents.index"),
                'active'=>true,
            ]
        ];
        $perPage = $request->input('perPage', 10);
        $partners = Partner::checkGlobal(21)->where('company_id',auth()->user()->active_company())->where('actor_id',4)->whereNot('pass_key',null)->where('permission_status',null)->doesntHave('users')->paginate($perPage);


        // $permissions = auth()->user()->get_user_role_session_permissions();
        // $target_method = ''
        // $permission = $permissions->filter(function($value, $key) use($target_method){
        //     $module =  $value->role_module_functions->filter(function($value, $key)  use($target_method){
        //         return $value->method == $target_method;
        //     });
        //     return $module->first();
        // })->first();
        // dd($permissions->where('role_permission_type.is_read',1));

        return view('business-agent.agent_register.index', compact('partners','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $partners->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $breadcrumbs = [
            [
                'name'=>"Unapproved Agent",
                'link'=>route("unapproved_agents.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("unapproved_agents.edit",$id),
                'active'=>true,
            ]
        ];
        $partner = Partner::checkGlobal(21)->where('company_id',auth()->user()->active_company())->find($id);
        // $business_partner = Partner::where('partner_type','business')->get();
        $form_type = 'notall';


        return view('business-agent.agent_register.edit', compact('partner','breadcrumbs', 'form_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $partner)
    {
        // dd($request);
        $partner = Partner::find($partner);



        // Validate the request data
        $partner_validator = Validator::make($request->all(), [
            'permission' => ['required'],

            ]);
        if ($partner_validator->fails()) {
            // dd($partner_validator->errors());
            return back()->with('errors', $partner_validator->errors());
        }
        // Update lead attributes with validated data
        $partner_data = $partner_validator->validated();
        $partner_data['updated_by'] = auth()->user()->id;
        if($partner_data['permission']==1){
            $user = User::create([
                    'name' => $partner->name,
                    'email' => $partner->email,
                    'image'=> 'profile_images/default/default.jpeg',
                    'partner_id'=> $partner->id,
                    'actor_id'=> 4,
                    'role_id'=> 4,
                    'flag'=>1,
                    'permission'=> $partner_data['permission'],
                    'password' => Hash::make($partner->pass_key),





                ]);
            // dd($partner);
            $business_partner_id = $partner->id;
            $whatsAppController = new WhatsAppController();
            // $whatsAppController->sendNotification(null, 'agentApproved', $business_partner_id, null, null, null, null,$partner);
        }




        else{
            $partner->update(['permission_status'=>'cancelled']);
        }
        // dd($partner_data);
        // User::where('partner_id',$partner->id)->update(['permission'=> $partner_data['permission'],'updated_by'=>$partner_data['updated_by']]);






        return redirect()->route('unapproved_agents.index')
            ->with('success', 'Agent updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
