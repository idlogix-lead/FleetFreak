<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

/**
 * Class ServiceProviderController
 * @package App\Http\Controllers
 */
class ServiceProviderController extends Controller
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
                'name'=>"ServiceProvider",
                'link'=>route("service_providers.index"),
                'active'=>true,
            ]
        ];
        // $serviceProviders = ServiceProvider::paginate();


        // $serviceProviders = ServiceProvider::whereHas('user', function ($query) {
        //     $query->where('type', 'service_provider');
        // })->with(['user' => function ($query) {
        //     $query->select('name', 'email');
        // }])->paginate();
        // return view('service-provider.index', compact('serviceProviders','breadcrumbs'))
        // ->with('i', (request()->input('page', 1) - 1) * $serviceProviders->perPage());

        $serviceProviders = ServiceProvider::whereHas('user', function ($query) {
            $query->where('type', 'service_provider');
        })->with('user')->paginate();
        
        return view('service-provider.index', compact('serviceProviders','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $serviceProviders->perPage());
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
                'name'=>"ServiceProvider",
                'link'=>route("service_providers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("service_providers.create"),
                'active'=>true,
            ]
        ];
        $serviceProvider = new ServiceProvider();
        return view('service-provider.create', compact('serviceProvider','breadcrumbs'));
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
            'name' => 'required',
            // 'email' => 'required|email|unique:users,email',
            'email' => ['required','email',Rule::unique('users')],
            // 'password' => 'required|confirmed',
            'password' => ['required','confirmed'],

            'address' => 'required',
            'city' => 'required',
            'contact' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
       

        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;
    
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type'=> 'service_provider',
            // service provider role id = 3
            'role_id'=> 3,
            'created_by'=> $data['created_by'],
        ]);
        ServiceProvider::create([
            'user_id'=> $user->id,
            'address'=> $data['address'],
            'city'=> $data['city'],
            'contact'=> $data['contact'],
        ]);
        
        return redirect()->route('service_providers.index')->with('success', 'ServiceProvider created successfully.');
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
                'name'=>"ServiceProvider",
                'link'=>route("service_providers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("service_providers.show",$id),
                'active'=>true,
            ]
        ];
        $serviceProvider = ServiceProvider::find($id);

        return view('service-provider.show', compact('serviceProvider','breadcrumbs'));
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
                'name'=>"ServiceProvider",
                'link'=>route("service_providers.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("service_providers.edit",$id),
                'active'=>true,
            ]
        ];
        $serviceProvider = ServiceProvider::whereHas('user', function ($query) {
            $query->where('type', 'service_provider');
        })->with('user')->find($id);
        return view('service-provider.edit', compact('serviceProvider','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ServiceProvider $serviceProvider
     * *
     */
    public function update(Request $request, ServiceProvider $serviceProvider)
    {
        // Validate the request data
        // $validator = Validator::make($request->all(), ServiceProvider::$rules);
        // if ($validator->fails()) {
        //     return back()->with('errors', $validator->errors());
        // }
        // // Update lead attributes with validated data
        // $data = $validator->validated();
        // $data['updated_by'] = auth()->user()->id;




        // $serviceProvider->update($data);

    //     return redirect()->route('service_providers.index')
    //         ->with('success', 'ServiceProvider updated successfully');
    // }
    // {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'required|email|unique:users,email',
            'email' => ['required','email',Rule::unique('users')->ignore($serviceProvider->user_id)],
            // 'password' => 'required|confirmed',
            'password' => ['nullable','confirmed'],

            'address' => 'required',
            'city' => 'required',
            'contact' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
       

        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        
        // $user = User::create([
        // $user->name = $data['name'],
        // $user->email = $data['email'],
        // $user->password = $data['password'],
        // $user->password = bcrypt($request->password);
        // $user->save();

        // ]);
        // $user->name = $data[name];
        // $user->email = $request->email;
        // $user->password = $request->password;
        // $user->confirm_password = $request->confirm_password;
        // $user->save();
       
        
        // $user = User::find($serviceProvider->user_id);
        User::where('id',$serviceProvider->user_id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            // 'password' => $data['password']?Hash::make($data['password']),
            'type'=> 'service_provider',
            'role_id'=> 3,
            'updated_by'=> $data['updated_by'],
            // 'service_provider_id' => $serviceProvider->id
        ]);
        if($data['password']){
            User::where('id',$serviceProvider->user_id)->update([
                'password' => Hash::make($data['password']),
            ]);
        }
        ServiceProvider::where('id',$serviceProvider->id)->update([
            // 'user_id'=> $user ->id,
            'address'=> $data['address'],
            'city'=> $data['city'],
            'contact'=> $data['contact'],
        ]);

        // return redirect()->route('service_providers.index')->with('success', 'ServiceProvider created successfully.');
        return back()->with('success', 'Service Provider updated successfully');
    }
    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $serviceProvider = ServiceProvider::find($id)->delete();

        return redirect()->route('service_providers.index')
            ->with('success', 'ServiceProvider deleted successfully');
    }
}
