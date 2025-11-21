<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

use App\Models\RoleModule;
use App\Models\RoleModuleActors;

use App\Models\Actor;
use App\Models\RolePermissionType;
use App\Models\RolePermissionTypeFunction;
use App\models\RolePermission;
/**
 * Class RoleModuleController
 * @package App\Http\Controllers
 */
class RoleModuleController extends Controller
{

    static $role_module_id = 3;

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
                'name'=>"RoleModule",
                'link'=>route("role_modules.index"),
                'active'=>true,
            ]
        ];
        $roleModules = RoleModule::checkGlobal(3)->paginate();

        return view('role-module.index', compact('roleModules','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $roleModules->perPage());
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
                'name'=>"RoleModule",
                'link'=>route("role_modules.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("role_modules.create"),
                'active'=>true,
            ]
        ];
        $roleModule = new RoleModule();
        $actors = Actor::get();

        return view('role-module.create', compact('roleModule','breadcrumbs','actors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required',Rule::unique('role_modules')],
            //these fields name are from RolePermissionType
            'action.*' => ['required'],
            // 'function.*' => 'required',
            // 'return_type.*' => 'required',
            'denial_msg.*' => ['required'],
            'is_read.*' => ['required'],

            'module_function_id' => ['required','array'],
            'function' => ['required','array'],
            'return_type' => ['required','array'],

            'module_function_id.*' => ['required','array'],
            'function.*' => ['required','array'],
            'return_type.*' => ['required','array'],

            'module_function_id.*.*' => ['nullable'],
            'function.*.*' => ['required'],
            'return_type.*.*' => ['required'],

            // 'actor_id'=> ['required','array'],
            // 'actor_id.*'=> ['required']
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            // dd($validator->errors());
            return back()->with('errors', $validator->errors());
        }
        //haris changes
        $data = $validator->validated();
        $role_permission_typed_data  = $this->Role_Module_Structure_Data($data);
        //$data['created_by'] = auth()->user()->id;
        //  dd($data,$role_permission_typed_data);
        // dd($data);
        $roleModule = RoleModule::create(['name' => $data['name'],'created_by'=> auth()->user()->id,'actor_id'=>auth()->user()->actor_id]);
        // foreach ($data['actor_id'] as $key=>$actorId) {
        //     RoleModuleActors::create([
        //         'role_module_id' => $roleModule->id,
        //         'actor_id' => $actorId,
        //     ]);
        // }


        foreach ($role_permission_typed_data as $data) {
            $structured_data = RolePermissionType::create(
                // array_merge($data, ['role_module_id' => $roleModule->id])
                [
                    'role_module_id' => $roleModule->id,
                    'is_read' => $data['is_read'],
                    'action' => $data['action'],
                    'denial_msg' => $data['denial_msg'],
                ]
            );

            foreach($data['role_module_function'] as $module_function){
                RolePermissionTypeFunction::create([
                    'role_permission_type_id' => $structured_data->id,
                    'method' => $module_function['function'],
                    'return_type' => $module_function['return_type'],
                ]);
            }
        }

        // RoleController::create_permission_for_admin($roleModule->id);


        return redirect()->route('role_modules.index')->with('success', 'RoleModule created successfully.');
    }
    public static function RoleModuleFunctions($functions){
        $function = [];
        foreach($functions['module_function_id'] as $key => $id){
            $function[] = [
                'module_function_id' => $id,
                'function' => $functions['function'][$key],
                'return_type' => $functions['return_type'][$key],
            ];
        }
        return $function;
    }
    public function Role_Module_Structure_Data($data){
        $roleModuleStructureData = [];

        foreach($data['action'] as $index => $action){
            $rowData = [
                'action' => $action,
                'role_module_function' => isset($data['function'][$index])?$this->RoleModuleFunctions([
                    'module_function_id' => $data['module_function_id'][$index]??'',
                    'function' => $data['function'][$index],
                    'return_type' => $data['return_type'][$index],
                ]):[],
                'is_read' => $data['is_read'][$index],
                'denial_msg' => $data['denial_msg'][$index],
            ];

            // Check if rowIds are set
            if (isset($data['rowIds'][$index])) {
                $rowData['rowIds'] = $data['rowIds'][$index];
            }

            $roleModuleStructureData[] = $rowData;
        }


        return $roleModuleStructureData;

    }

    // public function Role_Module_Structure_Data($request, $roleModuleId) {
    //     $roleModuleStructureData = [];

    //     foreach ($request->action as $index => $action) {
    //         $roleModuleStructureData[] = [
    //             'role_module_id' => $roleModuleId,
    //             'action' => $action,
    //             'function' => $request->function[$index],
    //             '`return`' => $request->return[$index],
    //             'denial_msg' => $request->denial[$index],
    //         ];
    //     }

    //     return $roleModuleStructureData;
    // }



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
                'name'=>"RoleModule",
                'link'=>route("role_modules.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("role_modules.show",$id),
                'active'=>true,
            ]
        ];
        $roleModule = RoleModule::checkGlobal(3)->find($id);

        return view('role-module.show', compact('roleModule','breadcrumbs'));
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
                'name'=>"RoleModule",
                'link'=>route("role_modules.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("role_modules.edit",$id),
                'active'=>true,
            ]
        ];
        $roleModule = RoleModule::checkGlobal(3)->with('role_permission_type')->find($id);
        $actors = Actor::get();

        //dd($roleModule);

        return view('role-module.edit', compact('roleModule','breadcrumbs','actors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  RoleModule $roleModule
     * *
     */
    public function update(Request $request, RoleModule $roleModule)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required',Rule::unique('role_modules')->ignore($roleModule->id)],
            //these fields name are from RolePermissionType
            'action.*' => ['required'],

            'is_read.*' => ['required'],

            'module_function_id' => ['nullable','array'],
            'function' => ['nullable','array'],
            'return_type' => ['nullable','array'],

            'module_function_id.*' => ['required','array'],
            'function.*' => ['required','array'],
            'return_type.*' => ['required','array'],

            'module_function_id.*.*' => ['nullable'],
            'function.*.*' => ['required'],
            'return_type.*.*' => ['required'],

            'denial_msg.*' => 'required',
            'rowIds.*'=> ['nullable'],

            // 'actor_id'=> ['required','array'],
            // 'actor_id.*'=> ['required']
        ]);
        if ($validator->fails()) {
            // dd($validator->errors());

            return back()->with('errors', $validator->errors());
        }

        // Update lead attributes with validated data
        $data = $validator->validated();
        $role_permission_typed_data  = $this->Role_Module_Structure_Data($data);


        // dd($data,$role_permission_typed_data);

        $data['updated_by'] = auth()->user()->id;
        //haris changes
        $roleModule->update(['name' => $data['name'],'updated_by'=> auth()->user()->id]);

        // RoleModuleActors::where('role_module_id' , $roleModule->id)->delete();

        // foreach ($data['actor_id'] as $key=>$actorId) {
        //     RoleModuleActors::create([
        //         'role_module_id' => $roleModule->id,
        //         'actor_id' => $actorId,
        //     ]);
        // }



        foreach($role_permission_typed_data as $structered_data){
            // $module_functions = $structered_data['role_module_function']
            if (isset($structered_data['rowIds']) && RolePermissionType::where('id', $structered_data['rowIds'])->exists()) {
                $structured_data_id = $structered_data['rowIds'];
                //dd($structured_data_id);
                RolePermissionType::where('id',$structured_data_id)->update([
                    'action' => $structered_data['action'],
                    'is_read' => $structered_data['is_read']??0,
                    'denial_msg' => $structered_data['denial_msg'],
                ]);
                RolePermissionTypeFunction::where('role_permission_type_id',$structured_data_id)->delete();
            }
            else
            {
                $module_structured_data = RolePermissionType::create([
                    'role_module_id' => $roleModule->id,
                    'is_read' => $structered_data['is_read']??0,
                    'action' => $structered_data['action'],
                    'denial_msg' => $structered_data['denial_msg'],
                ]);

                $structured_data_id = $module_structured_data->id;
            }

            foreach($structered_data['role_module_function'] as $module_function){
                RolePermissionTypeFunction::create([
                    'role_permission_type_id' => $structured_data_id,
                    'method' => $module_function['function'],
                    'return_type' => $module_function['return_type'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'RoleModule updated successfully');
}

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(RoleModule $roleModule)
    {
        $roleModule->delete();

        return redirect()->route('role_modules.index')
            ->with('success', 'RoleModule deleted successfully');
    }

    public function delete_row($id){
        // dd($id);
        $module_structure_id = RolePermissionType::findOrFail($id);
        if(RolePermission::where('role_permission_type_id',$id)->first() || RolePermissionTypeFunction::where('role_permission_type_id',$id)->first()){

            return response()->json(['error'=>'Row cannot be removed because it already exists'],401);

        }
        else{
            $module_structure_id->delete();
            return response()->json(['msg'=>'Row removed successfully'],200);
        }
    }

}
