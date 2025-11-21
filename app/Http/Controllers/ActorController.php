<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\RoleModuleActors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class ActorController
 * @package App\Http\Controllers
 */
class ActorController extends Controller
{
    static $role_module_id = 4;
    public function __construct()
    {
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
                'name' => "Actor",
                'link' => route("actors.index"),
                'active' => true,
            ],
        ];
        $actors = Actor::checkGlobal(4)->paginate();

        return view('actor.index', compact('actors', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $actors->perPage());
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
                'name' => "Actor",
                'link' => route("actors.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("actors.create"),
                'active' => true,
            ],
        ];
        $actor = new Actor();
        return view('actor.create', compact('actor', 'breadcrumbs'));
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
            'name' => [
                'required',
                'string',
                'regex:/^[a-zA-Z]+$/',
                'not_regex:/\s/'],
            'description' => 'string',

            'role_module_id' => ['required', 'array'],
            'role_module_id.*' => ['required', Rule::exists('role_modules', 'id')],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        // $data['created_by'] = auth()->user()->id;

        $actor = Actor::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        foreach ($data['role_module_id'] as $module_id) {
            RoleModuleActors::firstOrCreate([
                'role_module_id' => $module_id,
                'actor_id' => $actor->id,
            ]);
        }


        return redirect()->route('actors.index')->with('success', 'Actor created successfully.');
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
                'name' => "Actor",
                'link' => route("actors.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("actors.show", $id),
                'active' => true,
            ],
        ];
        $actor = Actor::checkGlobal(4)->find($id);

        return view('actor.show', compact('actor', 'breadcrumbs'));
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
                'name' => "Actor",
                'link' => route("actors.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("actors.edit", $id),
                'active' => true,
            ],
        ];
        $actor = Actor::checkGlobal(4)->find($id);

        return view('actor.edit', compact('actor', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Actor $actor
     * *
     */
    public function update(Request $request, Actor $actor)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'regex:/^[a-zA-Z]+$/', 'not_regex:/\s/'],
            'description' => ['string'],

            'role_module_id' => ['required', 'array'],
            'role_module_id.*' => ['required', Rule::exists('role_modules', 'id')],
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $actor->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        RoleModuleActors::whereNotIn('role_module_id', $data['role_module_id'])->delete();

        foreach ($data['role_module_id'] as $module_id) {
            RoleModuleActors::firstOrCreate([
                'role_module_id' => $module_id,
                'actor_id' => $actor->id,
            ]);
        }

        return redirect()->back()->with('success', 'Actor updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $actor = Actor::find($id)->delete();

        return redirect()->route('actors.index')
            ->with('success', 'Actor deleted successfully');
    }
}
