<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class RolePermissionController
 * @package App\Http\Controllers
 */
class RolePermissionController extends Controller
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
                'name'=>"RolePermission",
                'link'=>route("role_permissions.index"),
                'active'=>true,
            ]
        ];
        $rolePermissions = RolePermission::paginate();

        return view('role-permission.index', compact('rolePermissions','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $rolePermissions->perPage());
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
                'name'=>"RolePermission",
                'link'=>route("role_permissions.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("role_permissions.create"),
                'active'=>true,
            ]
        ];
        $rolePermission = new RolePermission();
        return view('role-permission.create', compact('rolePermission','breadcrumbs'));
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
        $validator = Validator::make($request->all(), RolePermission::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $rolePermission = RolePermission::create($data);

        return redirect()->route('role_permissions.index')->with('success', 'RolePermission created successfully.');
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
                'name'=>"RolePermission",
                'link'=>route("role_permissions.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("role_permissions.show",$id),
                'active'=>true,
            ]
        ];
        $rolePermission = RolePermission::find($id);

        return view('role-permission.show', compact('rolePermission','breadcrumbs'));
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
                'name'=>"RolePermission",
                'link'=>route("role_permissions.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("role_permissions.edit",$id),
                'active'=>true,
            ]
        ];
        $rolePermission = RolePermission::find($id);

        return view('role-permission.edit', compact('rolePermission','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  RolePermission $rolePermission
     */
    public function update(Request $request, RolePermission $rolePermission)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), RolePermission::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $rolePermission->update($data);

        return redirect()->route('role_permissions.index')
            ->with('success', 'RolePermission updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rolePermission = RolePermission::find($id)->delete();

        return redirect()->route('role_permissions.index')
            ->with('success', 'RolePermission deleted successfully');
    }
}
