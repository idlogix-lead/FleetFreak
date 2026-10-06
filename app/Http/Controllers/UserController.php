<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    static $role_module_id = 1;
    static $ignores = [];
    public $my_companies;
    public function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies = auth()->user()->companies->toArray();
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
                'name' => "User",
                'link' => route("users.index"),
                'active' => true,
            ],
        ];
        $company_id = auth()->user()->active_company();
        $perPage = $request->input('perPage', 10);

        // dd();
        // Retrieve all user IDs for the specified company
        $user_ids = UserCompany::where('company_id', $company_id)->pluck('user_id')->toArray();
        // Retrieve all users with those IDs
        // $users = User::whereIn('id', $user_ids)->get();
        // dd($users);
        $users = User::whereIn('id', $user_ids)
            ->where('id', '!=', auth()->user()?->client?->user?->id)
            ->where('client_id', auth()->user()->client_id)
        // ->where('company_id', auth()->user()->active_company())

        ->checkGlobal(self::$role_module_id)->with('role')->paginate($perPage);

        // dd($data);
        return view('user.index', compact('users', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    public function create()
    {
        // abort(404);
        $breadcrumbs = [
            [
                'name' => "User",
                'link' => route("users.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("users.create"),
                'active' => true,
            ],
        ];
        $user = new User();
        $roles = Role::assignableBy(auth()->user())->get();
        return view('user.create', compact('user', 'breadcrumbs', 'roles'));
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
            'name' => ['required', 'string'],
            'email' => ['required', 'unique:users', 'string'],
            'phone_no1' => ['nullable'],
            'phone_no2' => ['nullable'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'min:8'],
            // Only the caller's client's roles, never the super admin role (docs/HANDOVER.md §9.12).
            'role_id' => ['required', Rule::in($this->assignableRoleIds())],
            'vehicle_ids' => ['nullable', 'array'],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors())->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/profile_images/uploads'), $imageName);
            $data['image'] = 'profile_images/uploads/' . $imageName; // Set the image path in the profile
        } else {
            // If no image is uploaded, set the default image path
            $data['image'] = null;
        }

        $created_by = auth()->user()->id;

        $vehicle_ids = $data['vehicle_ids']??[];
        unset($data['vehicle_ids']);
        $data['client_id'] = auth()->user()->client_id;
        $payload = [
            'vehicle_ids' => $vehicle_ids,
            'data' => $data,
            'created_by' => $created_by,
            'normal' => true,
        ];


        $user = User::store_user($payload);
        if($user->role->actor_id != 9 && count($vehicle_ids) > 0){
            return redirect()->route('users.index')->with('error',"Only Vehicle Manager Can Have Vehicle Access To Manage Vehcles!" )->with('success', 'User created successfully!.');
        }
        // dd($data);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
                'name' => "User",
                'link' => route("users.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("users.show", $id),
                'active' => true,
            ],
        ];
        // Only users the caller may manage (User::scopeManageableBy, docs/HANDOVER.md §9.12); anyone else is a 404.
        $user = User::checkGlobal(self::$role_module_id)->manageableBy(auth()->user())->findOrFail($id);

        return view('user.show', compact('user', 'breadcrumbs'));
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
                'name' => "User",
                'link' => route("users.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("users.edit", $id),
                'active' => true,
            ],
        ];
        // Only users the caller may manage (User::scopeManageableBy, docs/HANDOVER.md §9.12); anyone else is a 404.
        $user = User::checkGlobal(self::$role_module_id)->manageableBy(auth()->user())->findOrFail($id);

        $roles = Role::assignableBy(auth()->user())->get();
        return view('user.edit', compact('user', 'breadcrumbs', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * *
     */
    public function update(Request $request, $user)
    {
        // Only users the caller may manage (User::scopeManageableBy, docs/HANDOVER.md §9.12); anyone else is a 404.
        // Route-model binding used to load any user in any organization, the super admin included.
        $user = User::checkGlobal(self::$role_module_id)->manageableBy(auth()->user())->findOrFail($user);

        // Validate the request data

        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'phone_no1' => ['nullable'],
            'phone_no2' => ['nullable'],
            'description' => ['nullable'],
            // Only the caller's client's roles, never the super admin role, including for the caller's own account.
            'role_id' => ['nullable', Rule::in($this->assignableRoleIds())],
            'vehicle_ids' => ['nullable', 'array'],
            'role_id_hidden' => ['nullable', Rule::in($this->assignableRoleIds())],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // Neither role field sent: keep the current role (update_user reads both keys).
        $data['role_id'] = $data['role_id'] ?? $data['role_id_hidden'] ?? $user->role_id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/profile_images/uploads'), $imageName);
            $data['image'] = 'profile_images/uploads/' . $imageName; // Set the image path in the profile
        }
      else {
    // If no image is uploaded, set the default image path
            $data['image'] = null;
        }

        $updated_by = auth()->user()->id;
        $vehicle_ids = $data['vehicle_ids']??[];
        unset($data['vehicle_ids']);
        $payload = [
            'data' => $data,
            'user' => $user,
            'vehicle_ids' => $vehicle_ids,
            'updated_by' => $updated_by,
        ];
        // dd($payload);
        User::update_user($payload);

        if($user->role->actor_id != 9 && count($vehicle_ids) > 0){
            return redirect()->back()->with('error',"Only Vehicle Manager Can Have Vehicle Access To Manage Vehcles!" )->with('success', 'User updated successfully!.');
        }
        // $user->update([]);

        return redirect()->back()
            ->with('success', 'User updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = User::where('id', $this->my_companies[0]['pivot']['user_id'])->find($id)->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
    /** @return int[] the ids of the roles the caller may give a user (Role::scopeAssignableBy). */
    private function assignableRoleIds(): array
    {
        return Role::assignableBy(auth()->user())->pluck('id')->all();
    }

    // public function changePassword(Request $request ,$id){
    //     $validator = Validator::make($request->all(), [
    //     'password'=> 'required_with:password_confirmation|same:password_confirmation|min:8',
    //     'password_confirmation' =>  'required_with:password|same:password|min:8']);
    //     if ($validator->fails()) {
    //         return back()->with('errors', $validator->errors());
    //     }
    //     // Update lead attributes with validated data
    //     $data = $validator->validated();
    //     $user = User::findOrFail($id);
    //     $user->password = Hash::make($data['password']);
    //     $user->save();

    //     return redirect()->back()->with('success', 'Password changed successfully!');
    // }
}
