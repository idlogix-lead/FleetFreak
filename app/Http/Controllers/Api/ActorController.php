<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Class ActorController
 * @package App\Http\Controllers\Api
 */
class ActorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    public function api_index()
    {
        $breadcrumbs = [
            [
                'name' => "Actor",
                'link' => route("actors.index"),
                'active' => true,
            ],
        ];
        $actors = Actor::paginate();

        return response()->json([
            'actors' => $actors,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * *  
     */
    public function api_create()
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
        return response()->json([
            'actor' => $actor,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function api_store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'regex:/^[a-zA-Z]+$/',
                'not_regex:/\s/'],
            'description' => 'string']);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
        $actor = Actor::create($data);

        return response()->json([
            'success' => 'Actor created successfully',
            'actor' => $actor,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * *
     */
    public function api_show($id)
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
        $actor = Actor::find($id);

        return response()->json([
            'breadcrumbs' => $breadcrumbs,
            'actor' => $actor,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * *
     */
    public function api_edit($id)
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
        $actor = Actor::find($id);

        return response()->json([
            'breadcrumbs' => $breadcrumbs,
            'actor' => $actor,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Actor $actor
     * *
     */
    public function api_update(Request $request, Actor $actor)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'regex:/^[a-zA-Z]+$/', 'not_regex:/\s/'],
            'description' => ['string']]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $actor->update($data);

        return response()->json([
            'success' => 'Actor update successfully',
            'actor' => $actor,
        ], 201);

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function api_destroy($id)
    {
        $actor = Actor::find($id)->delete();

        return response()->json([
            'success', 'Actor deleted successfully',
            'actor' => $actor,
        ]);
    }
}
