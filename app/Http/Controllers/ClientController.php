<?php

namespace App\Http\Controllers;

use App\Models\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class ClientController
 * @package App\Http\Controllers
 */
class ClientController extends Controller
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
                'name'=>"Client",
                'link'=>route("clients.index"),
                'active'=>true,
            ]
        ];
        $clients = Client::paginate();

        return view('client.index', compact('clients','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $clients->perPage());
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
                'name'=>"Client",
                'link'=>route("clients.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("clients.create"),
                'active'=>true,
            ]
        ];
        $client = new Client();
        return view('client.create', compact('client','breadcrumbs'));
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
        $validator = Validator::make($request->all(), Client::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $client = Client::create($data);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
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
                'name'=>"Client",
                'link'=>route("clients.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("clients.show",$id),
                'active'=>true,
            ]
        ];
        $client = Client::find($id);

        return view('client.show', compact('client','breadcrumbs'));
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
                'name'=>"Client",
                'link'=>route("clients.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("clients.edit",$id),
                'active'=>true,
            ]
        ];
        $client = Client::find($id);

        return view('client.edit', compact('client','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Client $client
     * *
     */
    public function update(Request $request, Client $client)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), Client::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $client->update($data);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $client = Client::find($id)->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully');
    }
}
