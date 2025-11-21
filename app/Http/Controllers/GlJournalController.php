<?php

namespace App\Http\Controllers;

use App\Models\GlJournal;
use App\Models\GlJournalLine;
use App\Models\Table;
use App\Models\AccountTransaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

/**
 * Class GlJournalController
 * @package App\Http\Controllers
 */
class GlJournalController extends Controller
{

    static $role_module_id = 30;

    // ignored permission functions
    // static $ignores = ['partner_dropdown'=>true];
    public $my_companies;

    function __construct(){
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
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
                'name'=>"GlJournal",
                'link'=>route("gl-journals.index"),
                'active'=>true,
            ]
        ];
        $perPage = $request->input('perPage', 10);
        $glJournals = GlJournal::where('company_id',auth()->user()->active_company())->paginate($perPage);

        return view('gl-journal.index', compact('glJournals','breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $glJournals->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * *
     */
    public function create()
    {
        // $breadcrumbs = [
        //     [
        //         'name'=>"GlJournal",
        //         'link'=>route("gl-journals.index"),
        //         'active'=>false,
        //     ],
        //     [
        //         'name'=>"Create",
        //         'link'=>route("gl-journals.create"),
        //         'active'=>true,
        //     ]
        // ];
        // dd($this->my_companies[0]);
        if(auth()->user()->active_company()){
            // $glJournal = GlJournal::create([
            //     'company_id' => auth()->user()->active_company(),
            //     'created_by' => auth()->user()->id,
            //     'transaction_date' => date('Y-m-d'),
            //     'status' => 'draft',
            // ]);
            $glJournal = GlJournal::auto_create([
                'company_id' => auth()->user()->active_company(),
                'created_by' => auth()->user()->id,
                'transaction_date' => date('Y-m-d'),
                'status' => 'draft',
            ]);
            // dd($glJournal);
            return redirect()->route('gl-journals.edit', $glJournal->id);
        }
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
        $validator = Validator::make($request->all(), GlJournal::$rules);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $data['created_by'] = auth()->user()->id;

        $glJournal = GlJournal::create($data);

        return redirect()->route('gl-journals.index')->with('success', 'GlJournal created successfully.');
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
                'name'=>"GlJournal",
                'link'=>route("gl-journals.index"),
                'active'=>false,
            ],
            [
                'name'=>"Show",
                'link'=>route("gl-journals.show",$id),
                'active'=>true,
            ]
        ];
        $glJournal = GlJournal::find($id);

        return view('gl-journal.show', compact('glJournal','breadcrumbs'));
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
                'name'=>"GlJournal",
                'link'=>route("gl-journals.index"),
                'active'=>false,
            ],
            [
                'name'=>"Edit",
                'link'=>route("gl-journals.edit",$id),
                'active'=>true,
            ]
        ];
        $glJournal = GlJournal::where('company_id',auth()->user()->active_company())->where('id',$id)->with([
            'gljournallines',
        ])
        ->first();

        return view('gl-journal.edit', compact('glJournal','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  GlJournal $glJournal
     * *
     */
    public function update(Request $request, GlJournal $glJournal)
    {
        // dd($request->all(), $glJournal);
        // Validate the request data
        $validator = Validator::make($request->all(), [
            // 'company_id' => 'numeric',

            'transaction_date' => ['nullable', 'date'],
            'debit' => ['nullable', 'numeric'],
            'credit' =>['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],

            'rows' => ['nullable', 'array'],

            'rows.*.row_id' => ['required'],
            'rows.*.account_id' => ['nullable', 'numeric'],
            'rows.*.product_id' => ['nullable', 'numeric'],
            'rows.*.partner_id' => ['nullable', 'numeric'],
            'rows.*.debit' => ['nullable', 'numeric'],
            'rows.*.credit' => ['nullable', 'numeric'],
            'rows.*.quantity' => ['nullable', 'numeric'],
            'rows.*.description' => ['nullable', 'string'],
        ]);
        if ($validator->fails()) {
            // dd($validator->errors());
            // return back()->with('errors', $validator->errors());
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Update lead attributes with validated data
        $data = $validator->validated();


        $data['updated_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        // $payload = $data;
        GlJournal::update_glJournal($data, $glJournal);


        return response()->json(['message' => 'saved'], 200);

        // return redirect()->route('gl-journals.index')
        //     ->with('success', 'GlJournal updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $glJournal = GlJournal::where('company_id',auth()->user()->active_company())->where('status', 'draft')->where('id',$id)->delete();

        return redirect()->route('gl-journals.index')
            ->with('success', 'GlJournal deleted successfully');
    }

    public function add_new_row($gl_journal_id){
        $row = GlJournalLine::create([
            'gl_journal_id' => $gl_journal_id,
            'created_by' => auth()->user()->id,
        ]);
        return response()->json(GlJournalLine::find($row->id), 200);
    }

    public function destroy_row($gl_journal_id){
        // error
        $glJournal = GlJournalLine::find($gl_journal_id)->delete();

        return response()->json(['message' => 'Row removed Succesfully!'] , 200);


        // ege
        // return response()->json(['error' => 'Row removed Succesfully!'] ,401);

    }

    public function complete($gl_journal_id){
        // complete
        // dd($gl_journal_id);
        $journal = GlJournal::where('company_id',auth()->user()->active_company())
        ->where('id', $gl_journal_id)
        ->where('status', 'draft')
        ->with('gljournallines')
        ->withSum('gljournallines', 'debit')->withSum('gljournallines', 'credit')
        ->first();
        // dd($journal);
        if($journal){
            if ($journal->gljournallines()
            ->whereNull('account_id')
            // ->orWhereNull('debit')
            ->exists() || !($journal->transaction_date)) {
                // Handle the case where there are null account_id entries
                // For example, return an error or skip further processing
                return response()->json(['error' => 'Please fill the required Red Fields.']);
            }
            if($journal->debit == $journal->credit && $journal->gljournallines_sum_debit == $journal->gljournallines_sum_credit){
                // $table = Table::where('name', 'gl_journals')->first();
                $table_id = 15;
                // dd(auth()->user()->current_currency());
                foreach($journal->gljournallines as $line){
                    AccountTransaction::create([
                        'table_id' => $table_id, // Parent record id
                        'record_id' => $journal->id, // Parent record id
                        'line_id' => $line->id, // Child line id
                        'account_id' => $line->account_id,
                        'quantity' => $line->quantity,
                        'debit' => $line->debit,
                        'credit' => $line->credit,

                        'transaction_date' => $journal->transaction_date,

                        'company_id' => auth()->user()->active_company(),
                        'currency_id' => auth()->user()?->current_currency()?->id,
                        'description' => ($line->description??$journal->description)??'',
                        'created_by' => auth()->user()->id, // Assuming you're using Laravel's auth
                        // 'updated_by' => '', // Set to the current user
                        // 'created_at' => now(), // Current timestamp
                        // 'updated_at' => now(), // Current timestamp
                    ]);
                }
                GlJournal::where('company_id',auth()->user()->active_company())
                ->where('id', $gl_journal_id)
                ->where('status', 'draft')
                ->update([
                    'status' => 'completed'
                ]);
                return response()->json(['message'=>'Status Completed Successfully'],200);
            }else{
                return response()->json(['error'=>'Total Debit not equals to Total Credit'],402);
            }
        }else{
            return response()->json(['error'=>'Sorry no draft GlJournal Account Found'],404);
        }
    }
}
