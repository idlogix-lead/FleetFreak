<?php

namespace App\Http\Controllers;

use App\Models\RolePermissionType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class RolePermissionTypeController
 * @package App\Http\Controllers
 */
class RolePermissionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    public function index()
    {
        $breadcrumbs = [
            [
                'name'=>"RolePermissionType",
                'link'=>route("role_permission_types.index"),
                'active'=>true,
            ]
        ];
        $roleModuleStructures = RolePermissionType::paginate();

        return view('role-module-structure.index', compact('roleModuleStructures','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $roleModuleStructures->perPage());
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
                'name'=>"RolePermissionType",
                'link'=>route("role_permission_types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("role_permission_types.create"),
                'active'=>true,
            ]
        ];
        $roleModuleStructure = new RolePermissionType();
        return view('role-module-structure.create', compact('roleModuleStructure','breadcrumbs'));
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
			'role_module_id' => 'required',
			'action' => 'required|string',
			'function' => 'required',
			'return' => 'required',
			'denial_msg' => 'required|string',
    ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // $data['created_by'] = auth()->user()->id;

        $roleModuleStructure = RolePermissionType::create($data);

        return redirect()->route('role_permission_types.index')->with('success', 'RolePermissionType created successfully.');
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
                'name'=>"RolePermissionType",
                'link'=>route("role_permission_types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("role_permission_types.show",$id),
                'active'=>true,
            ]
        ];
        $roleModuleStructure = RolePermissionType::find($id);

        return view('role-module-structure.show', compact('roleModuleStructure','breadcrumbs'));
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
                'name'=>"RolePermissionType",
                'link'=>route("role_permission_types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("role_permission_types.edit",$id),
                'active'=>true,
            ]
        ];
        $roleModuleStructure = RolePermissionType::find($id);

        return view('role-module-structure.edit', compact('roleModuleStructure','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  RolePermissionType $roleModuleStructure
     * *
     */
    public function update(Request $request, RolePermissionType $roleModuleStructure)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
			'role_module_id' => 'required',
			'action' => 'required|string',
			'function' => 'required',
			'return' => 'required',
			'denial_msg' => 'required|string',
    ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // $data['updated_by'] = auth()->user()->id;

        $roleModuleStructure->update($data);

        return redirect()->route('role_permission_types.index')
            ->with('success', 'RolePermissionType updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        dd($id);
        RolePermissionType::findOrFail( $id )->delete();

        return redirect()->back()
            ->with('success', 'Row deleted successfully');
    }
}
