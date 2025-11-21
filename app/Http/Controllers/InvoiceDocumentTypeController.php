<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDocumentType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class InvoiceDocumentTypeController
 * @package App\Http\Controllers
 */
class InvoiceDocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    static $role_module_id = 36;

    // ignored permission functions
    static $ignores = [];
    public $my_companies;

    function __construct(){
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });

    }
    public function index()
    {
        $breadcrumbs = [
            [
                'name'=>"InvoiceDocumentType",
                'link'=>route("invoice-document-types.index"),
                'active'=>true,
            ]
        ];
        $invoiceDocumentTypes = InvoiceDocumentType::paginate();

        return view('invoice-document-type.index', compact('invoiceDocumentTypes','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $invoiceDocumentTypes->perPage());
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
                'name'=>"InvoiceDocumentType",
                'link'=>route("invoice-document-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Create",
                'link'=>route("invoice-document-types.create"),
                'active'=>true,
            ]
        ];
        $invoiceDocumentType = new InvoiceDocumentType();
        return view('invoice-document-type.create', compact('invoiceDocumentType','breadcrumbs'));
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
        $validator = Validator::make($request->all(), InvoiceDocumentType::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $invoiceDocumentType = InvoiceDocumentType::create($data);

        return redirect()->route('invoice-document-types.index')->with('success', 'InvoiceDocumentType created successfully.');
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
                'name'=>"InvoiceDocumentType",
                'link'=>route("invoice-document-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("invoice-document-types.show",$id),
                'active'=>true,
            ]
        ];
        $invoiceDocumentType = InvoiceDocumentType::find($id);

        return view('invoice-document-type.show', compact('invoiceDocumentType','breadcrumbs'));
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
                'name'=>"InvoiceDocumentType",
                'link'=>route("invoice-document-types.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("invoice-document-types.edit",$id),
                'active'=>true,
            ]
        ];
        $invoiceDocumentType = InvoiceDocumentType::find($id);

        return view('invoice-document-type.edit', compact('invoiceDocumentType','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  InvoiceDocumentType $invoiceDocumentType
     * *
     */
    public function update(Request $request, InvoiceDocumentType $invoiceDocumentType)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), InvoiceDocumentType::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $invoiceDocumentType->update($data);

        return redirect()->route('invoice-document-types.index')
            ->with('success', 'InvoiceDocumentType updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $invoiceDocumentType = InvoiceDocumentType::find($id)->delete();

        return redirect()->route('invoice-document-types.index')
            ->with('success', 'InvoiceDocumentType deleted successfully');
    }
}
