<?php

namespace App\Http\Controllers;

use App\Models\Sale;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class SaleController
 * @package App\Http\Controllers
 */
class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * *
     */
    public function index()
    {
        //haris changes
        // $breadcrumbs = [
        //     [
        //         'name' => "Sale",
        //         'link' => route("sales.index"),
        //         'active' => true,
        //     ]
        // ];
        // $sales = Sale::paginate();

        // return view('sale.index', compact('sales', 'breadcrumbs'))
        //     ->with('i', (request()->input('page', 1) - 1) * $sales->perPage());
        return view("theme.index2");
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
                'name' => "Sale",
                'link' => route("sales.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("sales.create"),
                'active' => true,
            ]
        ];
        $sale = new Sale();
        return view('sale.create', compact('sale', 'breadcrumbs'));
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
        $validator = Validator::make($request->all(), Sale::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $sale = Sale::create($data);

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
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
                'name' => "Sale",
                'link' => route("sales.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("sales.show", $id),
                'active' => true,
            ]
        ];
        $sale = Sale::find($id);

        return view('sale.show', compact('sale', 'breadcrumbs'));
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
                'name' => "Sale",
                'link' => route("sales.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("sales.edit", $id),
                'active' => true,
            ]
        ];
        $sale = Sale::find($id);

        return view('sale.edit', compact('sale', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Sale $sale
     * *
     */
    public function update(Request $request, Sale $sale)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), Sale::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['updated_by'] = auth()->user()->id;

        $sale->update($data);

        return redirect()->route('sales.index')
            ->with('success', 'Sale updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $sale = Sale::find($id)->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully');
    }
}
