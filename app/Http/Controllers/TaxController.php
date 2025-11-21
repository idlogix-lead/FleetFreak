<?php

namespace App\Http\Controllers;

use App\Models\Tax;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class TaxController
 * @package App\Http\Controllers
 */
class TaxController extends Controller
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
                'name'=>"Tax",
                'link'=>route("taxes.index"),
                'active'=>true,
            ]
        ];
        $taxes = Tax::where('company_id',Auth::user()->active_company())->paginate();


        return view('tax.index', compact('taxes','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $taxes->perPage());
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
                'name'=>"Tax",
                'link'=>route("taxes.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("taxes.create"),
                'active'=>true,
            ]
        ];
        $tax = new Tax();
        return view('tax.create', compact('tax','breadcrumbs'));
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
        $validator = Validator::make($request->all(),[
			'name' => 'required',
			'rate' => 'required',
			'description' => 'nullable',
			'is_default' => 'nullable',
			'is_active' => 'nullable',
			'valid_from' => 'nullable',
			'type' => 'nullable',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();

        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $payload = [];
        $data['created_by'] = auth()->user()->id;
        $payload['data'] = $data;

        $tax = Tax::store_tax($payload);

        return redirect()->route('taxes.index')->with('success', 'Tax created successfully.');
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
                'name'=>"Tax",
                'link'=>route("taxes.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("taxes.show",$id),
                'active'=>true,
            ]
        ];
        $tax = Tax::where('company_id',Auth::user()->active_company())->find($id);

        return view('tax.show', compact('tax','breadcrumbs'));
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
                'name'=>"Tax",
                'link'=>route("taxes.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("taxes.edit",$id),
                'active'=>true,
            ]
        ];
        $tax = Tax::where('company_id',Auth::user()->active_company())->find($id);

        return view('tax.edit', compact('tax','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tax $tax
     * *
     */
    public function update(Request $request, Tax $tax)
    {
        // Validate the request data
        $validator = Validator::make($request->all(),[
            'name' => 'required',
			'rate' => 'required',
			'description' => 'nullable',
			'is_default' => 'nullable',
			'is_active' => 'nullable',
			'valid_from' => 'nullable',
			'type' => 'nullable',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;
        $payload = [];
        $payload['tax'] = $tax;
        $payload['data'] = $data;   
        Tax::update_tax($payload);

        return redirect()->route('taxes.index')
            ->with('success', 'Tax updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tax = Tax::where('company_id',Auth::user()->active_company())->find($id)->delete();

        return redirect()->route('taxes.index')
            ->with('success', 'Tax deleted successfully');
    }
}
