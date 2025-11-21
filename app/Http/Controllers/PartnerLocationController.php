<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerLocation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class PartnerLocationController
 * @package App\Http\Controllers
 */
class PartnerLocationController extends Controller
{
    static $ignores = [];
    public $my_companies;
    static $role_module_id = 49;


    public function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
        // $this->middleware('checkCompanyAccess');
    }
   
    public function index()
    {
        $breadcrumbs = [
            [
                'name'=>"PartnerLocation",
                'link'=>route("partner-locations.index"),
                'active'=>true,
            ]
        ];
        $partnerLocations = PartnerLocation::orderBy('id','desc')->paginate();


        return view('partner-location.index', compact('partnerLocations','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $partnerLocations->perPage());
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
                'name'=>"PartnerLocation",
                'link'=>route("partner-locations.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("partner-locations.create"),
                'active'=>true,
            ]
        ];
        $partnerLocation = new PartnerLocation();
        return view('partner-location.create', compact('partnerLocation','breadcrumbs'));
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
			'partner_id' => 'required',
			'address1' => 'required',
			'address2' => 'nullable',
			'address3' => 'nullable',
			'primary_contact_person' => 'nullable',
			'secondary_contact_person' => 'nullable',
			'prefix_phone' => 'nullable',
			'phone_no' => 'nullable',
			'prefix_whatsapp' => 'required',
			'whatsapp_no' => 'required',
			'city' => 'nullable',
			'country' => 'nullable',
			'is_default' => 'nullable',
			'ship_address' => 'nullable',
			'invoice_address' => 'nullable',
    ]
);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        $defaultCount = PartnerLocation::where('partner_id', $request->partner_id)
        ->where('is_default', 1)
        ->count();

        // Prevent unchecking all defaults
        if ($request->input('is_default') == 0 && $defaultCount == 0) {
            return back()->withErrors(['is_default' => 'You must have at least one default address.'])->withInput();
        }
        
        if ($request->is_default) {
            // Reset all other records to not default for the same partner_id
            PartnerLocation::where('partner_id', $request->partner_id)
                ->update(['is_default' => 0]);
        }
        $payload = [];
        // Update lead attributes with validated data
        $partner_location_data = $validator->validated();
        $partner_location_data['created_by'] = auth()->user()->id;
        $payload['partner_location_data']=$partner_location_data;
        PartnerLocation::store_partner_location($payload);

        return redirect()->route('partner-locations.index')->with('success', 'PartnerLocation created successfully.');
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
                'name'=>"PartnerLocation",
                'link'=>route("partner-locations.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("partner-locations.show",$id),
                'active'=>true,
            ]
        ];
        $partnerLocation = PartnerLocation::find($id);

        return view('partner-location.show', compact('partnerLocation','breadcrumbs'));
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
                'name'=>"PartnerLocation",
                'link'=>route("partner-locations.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("partner-locations.edit",$id),
                'active'=>true,
            ]
        ];
        $partnerLocation = PartnerLocation::find($id);

        return view('partner-location.edit', compact('partnerLocation','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  PartnerLocation $partnerLocation
     * *
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $partner_loc = PartnerLocation::findOrFail($id);
        // Validate the request data
        $validator = Validator::make($request->all(),[
            'partner_id' => 'required',
			'address1' => 'required',
			'address2' => 'nullable',
			'address3' => 'nullable',
			'primary_contact_person' => 'nullable',
			'secondary_contact_person' => 'nullable',
			'prefix_phone' => 'nullable',
			'phone_no' => 'nullable',
			'prefix_whatsapp' => 'required',
			'whatsapp_no' => 'required',
			'city' => 'nullable',
			'country' => 'nullable',
			'is_default' => 'nullable',
			'ship_address' => 'nullable',
			'invoice_address' => 'nullable',
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
      // Count existing default addresses for this partner (excluding current)
        $defaultCount = PartnerLocation::where('partner_id', $request->partner_id)
        ->where('id', '!=', $partner_loc->id)
        ->where('is_default', 1)
        ->count();

        // Prevent unchecking all defaults
        if ($request->input('is_default') == 0 && $defaultCount == 0 && $partner_loc->is_default) {
            return back()->withErrors(['is_default' => 'You must have at least one default address.'])->withInput();
        }

        // If is_default is checked, remove default from others
        if ($request->input('is_default') == 1) {
            PartnerLocation::where('partner_id', $request->partner_id)
                ->where('id', '!=', $partner_loc->id)
                ->update(['is_default' => 0]);
            // dd($request->partner_id);
            Partner::where('id',$request->partner_id)->update(['partner_loc_id'=>$partner_loc->id]);
        }

        
        // Update lead attributes with validated data
        $partner_location_data = $validator->validated();
        $partner_location_data['updated_by'] = auth()->user()->id;
        $payload = [];
        $payload['partner_location_data'] = $partner_location_data;
        $payload['partner_loc'] = $partner_loc;
        PartnerLocation::update_partner_location($payload);
        return redirect()->route('partner-locations.index')
            ->with('success', 'PartnerLocation updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $partnerLocation = PartnerLocation::find($id)->delete();

        return redirect()->route('partner-locations.index')
            ->with('success', 'PartnerLocation deleted successfully');
    }
}
