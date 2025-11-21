<?php

namespace App\Http\Controllers;

use App\Models\BroadcastMessage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class BroadcastMessageController
 * @package App\Http\Controllers
 */
class BroadcastMessageController extends Controller
{


     static $role_module_id = 29;
     public $my_companies;

     function __construct()
     {
         $this->middleware('RolePermissions');
         $this->middleware(function ($request, $next) {
             $this->my_companies =  auth()->user()->companies->toArray();
             return $next($request);
         });
     }
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name'=>"BroadcastMessage",
                'link'=>route("broadcast-messages.index"),
                'active'=>true,
            ]
        ];
        $perPage = $request->input('perPage', 10);
        $broadcastMessages = BroadcastMessage::where('company_id',auth()->user()->active_company())->paginate($perPage);

        return view('broadcast-message.index', compact('broadcastMessages','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $broadcastMessages->perPage());
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
                'name'=>"BroadcastMessage",
                'link'=>route("broadcast-messages.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("broadcast-messages.create"),
                'active'=>true,
            ]
        ];
        $broadcastMessage = new BroadcastMessage();
        return view('broadcast-message.create', compact('broadcastMessage','breadcrumbs'));
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
        $validator = Validator::make($request->all(), BroadcastMessage::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $broadcastMessage = BroadcastMessage::create($data);

        return redirect()->route('broadcast-messages.index')->with('success', 'BroadcastMessage created successfully.');
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
                'name'=>"BroadcastMessage",
                'link'=>route("broadcast-messages.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("broadcast-messages.show",$id),
                'active'=>true,
            ]
        ];
        $broadcastMessage = BroadcastMessage::where('company_id',auth()->user()->active_company())->find($id);

        return view('broadcast-message.show', compact('broadcastMessage','breadcrumbs'));
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
                'name'=>"BroadcastMessage",
                'link'=>route("broadcast-messages.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("broadcast-messages.edit",$id),
                'active'=>true,
            ]
        ];
        $broadcastMessage = BroadcastMessage::where('company_id',auth()->user()->active_company())->find($id);

        return view('broadcast-message.edit', compact('broadcastMessage','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  BroadcastMessage $broadcastMessage
     * *
     */
    public function update(Request $request, BroadcastMessage $broadcastMessage)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), BroadcastMessage::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $broadcastMessage->update($data);

        return redirect()->route('broadcast-messages.index')
            ->with('success', 'BroadcastMessage updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $broadcastMessage = BroadcastMessage::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('broadcast-messages.index')
            ->with('success', 'BroadcastMessage deleted successfully');
    }
}
