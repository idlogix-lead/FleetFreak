<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleModule;
use App\Models\RoleHasModule;
use App\Models\RoleModuleActors;
use App\Models\RolePermission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{
    static $ignores = [
        'role_module_create' => true,
        'role_module_edit' => true,
        'role_module_update' => true,
    ];
    public $my_companies;
    static $role_module_id = 2;
    public function __construct()
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
    public function index()
    {
        // dd(auth()->user()->role_module_permission_via_action(2,'global')->permission);
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>true,
            ]
        ];

        // $roles = Role::all()
        $roles = Role::where('client_id', auth()->user()->client_id)
        // whereNotIn('actor_id',[1])
        // ->where('is_system',0)
        ->checkGlobal(self::$role_module_id)
        ->paginate();
        //   dd($roles);
        // dd( auth()->user(),auth()->user()->get_user_role_session_permissions()->whereNotNull('role_permission_type.sidebar')->sortBy('role_permission_type.sidebar.link_position'));

        return view('role.index', compact('roles','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $roles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        // not in use
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("roles.create"),
                'active'=>true,
            ]
        ];
        $role = new Role();
        $role_modules = RoleModule::
        whereHas('role_module_actors',function($query){
            $query->where('actor_id',auth()->user()->actor_id);

        })
        ->
        get();
        //dd($role_modules);
        return view('role.create', compact('role','breadcrumbs','role_modules'));
    }
    static function create_permissions($data){
        $permissions = [];
        foreach($data['read'] as $role_module_id => $read_permission){
            $permissions [] = [
                'role_module_id' => $role_module_id,
                'read' => $read_permission,
                'create' => $data['create'][$role_module_id],
                'update' => $data['update'][$role_module_id],
                'delete' => $data['delete'][$role_module_id],
                'recover' => $data['recover'][$role_module_id],
                'global' => $data['global'][$role_module_id],
                'permission_id' => $data['permission_id'][$role_module_id],
            ];
        }
        return $permissions;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required',Rule::unique('roles')->whereNull('deleted_at')],
            'role_module_ids' => ['required', 'array'],
            'role_module_ids.*' => ['required', Rule::exists('role_modules', 'id')],
            // unique profile wise
            // 'home' => 'required',

            // 'permission'=>['required', 'array'],
            // 'permission.*'=>['required', 'array'],
            // 'permission.*.*'=>['required','array'],
            // 'permission.*.*.permission'=>['required'],
            // 'permission.*.*.permission_id'=>['nullable'],

        ]);
        if($validator->fails()) {
            return back()->with('errors', $validator->errors())->withInput();
            // return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // $permissions = self::create_permissions($data);
        // dd($data,$permissions);
        // $data['created_by'] = auth()->user()->id;

        $role = Role::create([
            'name' => $data['name'],
            // 'home' => $data['home'],
            // 'actor_id'=> 10 ,
            'client_id'=> auth()->user()->client_id,
            'created_by' => auth()->user()->id,
        ]);
        foreach($data['role_module_ids'] as $role_module_id){
            RoleHasModule::firstOrCreate([
                'role_id' => $role->id,
                'role_module_id' => $role_module_id,
            ]);
        }

        // foreach($data['permission'] as $module_id => $permissions){
        //     foreach($permissions as $structure_id => $permission){
        //         RolePermission::create([
        //             'role_module_id' => $module_id,
        //             'role_permission_type_id' => $structure_id,
        //             'role_id' => $role->id,
        //             'permission' => $permission['permission'],
        //         ]);
        //     }
        // }
        // foreach($permissions as $permission){
        //     unset($permission['permission_id']);
        //     $permission['role_id'] = $role->id;
        //     RolePermission::create($permission);
        // }

        return redirect()->route('roles.edit', $role->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     */
    public function show($id)
    {
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("roles.show",$id),
                'active'=>true,
            ]
        ];

        $role = Role::checkGlobal(self::$role_module_id)->find($id);

        return view('role.show', compact('role','breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     */
    public function edit($id)
    {
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("roles.edit",$id),
                'active'=>true,
            ]
        ];
        $role = Role::where('client_id', auth()->user()->client_id)
        // ->where('is_system',0)
        ->checkGlobal(self::$role_module_id)
        ->find($id);

        if(!$role){
            return back()->with('error', 'Role not found!');
        }

        $role_modules = RoleModule::
        // whereHas('role_module_actors',function($query){
        //     $query->where('actor_id',auth()->user()->actor_id);
        // })
        whereHas('role_has_modules',function($query) use($id){
            $query->where('role_id', $id);
        })
        ->orderBy('is_report')
        ->get();


        $role_permissions = $role->rolePermissions??null;
        // dd($role_permissions);
        return view('role.edit', compact('role','breadcrumbs','role_modules','role_permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Role $role
     */
    public function update(Request $request, Role $role)
    {
        if($role->is_system){
            return redirect()->back()->with('error', 'System Roles are not editable');
        }
        // dd($request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['required',Rule::unique('roles')->whereNull('deleted_at')->ignore($role->id)],
            // unique profile wise
            'home' => 'required',

            'permission'=>['required', 'array'],
            'permission.*'=>['required', 'array'],
            'permission.*.*'=>['required','array'],
            'permission.*.*.permission'=>['required'],
            'permission.*.*.permission_id'=>['nullable'],

        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        $data = $validator->validated();
        $role->update([
            'name' => $data['name'],
            'home' => $data['home'],
            'updated_by' => auth()->user()->id,
        ]);
        foreach($data['permission'] as $module_id => $permissions){
            if(!RoleHasModule::where('role_id', $role->id)->where('role_module_id', $module_id)->first()){
                // RolePermission::
                // where('role_module_id' , $module_id)
                // ->where('role_id' ,$role->id)
                // ->delete();
                continue;
            }
            foreach($permissions as $structure_id => $permission){
                if($permission['permission_id']){
                    RolePermission::where('id',$permission['permission_id'])->update([
                        'permission' => $permission['permission'],
                    ]);
                }else{
                    RolePermission::create([
                        'role_module_id' => $module_id,
                        'role_permission_type_id' => $structure_id,
                        'role_id' => $role->id,
                        'permission' => $permission['permission'],
                    ]);
                }
            }
        }

        return back()->with('success', 'Role Updated Successfully!');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if($role->is_system=1){
            return redirect()->route('roles.index')
            ->with('error', 'You Cant Delete System Role');
        } else {
            $role->delete();
            return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
        }

    }


    public function role_module_create(){
        $role_modules = null;
        return view('role.modules.create', compact('role_modules'));
    }
    public function role_module_edit($role_id){
        $breadcrumbs = [
            [
                'name'=>"Role",
                'link'=>route("roles.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("roles.edit",$role_id),
                'active'=>false,
            ],
            [
                'name'=>"Module",
                'link'=>route("roles.modules.edit",$role_id),
                'active'=>true,
            ]
        ];
        $role_modules = RoleHasModule::where('role_id', $role_id)->get();
        return view('role.modules.edit', compact('role_id', 'role_modules', 'breadcrumbs'));
    }
    public function role_module_update(Request $request, $role_id){
        $validator = Validator::make($request->all(), [
            'role_module_ids' => ['required', 'array'],
            'role_module_ids.*' => ['required', Rule::exists('role_modules', 'id')],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        $role=Role::where('id', $role_id)->first();

        if($role->is_system){
            return redirect()->back()->with('error', 'System Roles are not editable');
        }
        $data = $validator->validated();
        // dd($data);
        RoleHasModule::whereNotIn('role_module_id', $data['role_module_ids'])
        ->where('role_id', $role_id)
        ->delete();
        foreach($data['role_module_ids'] as $role_module_id){
            RoleHasModule::firstOrCreate([
                'role_id' => $role_id,
                'role_module_id' => $role_module_id,
            ]);
        }
        return redirect()->route('roles.edit', $role_id)->with('success', 'Role modules updated Successfully!');
        // return view('role.modules.update');
    }
}
