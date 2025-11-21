<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CalendarController extends Controller
{
    static $role_module_id = 39;

    // ignored permission functions
    static $ignores = ['getEvents' =>  true] ;
    public $my_companies;

    public function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies = auth()->user()->companies->toArray();
            return $next($request);
        });

    }
    public function index()
    {
        return view('calendar.tui-calendar'); // The calendar view
    }

    public function getEvents(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
        ]);
        //validation errors
        // dd($request->input('vehicle_id'));
        if ($validator->fails()) {
            return json_encode(['errors' => $validator->errors()]);
        }

        $vehicle_id=$request->input('vehicle_id');

        if ($vehicle_id) {
    //         $event_details = Event::where('company_id', auth()->user()->active_company())
    //             ->whereIn('action', ['order_created'])
    //             ->whereBetween('date', [$request->start, $request->end])
    //             ->with('sourceTable')
    //             ->get();
    //         dd($event_details);

    //    $event_details = Event::where('company_id', auth()->user()->active_company())
    //         ->whereIn('action', ['order_created'])
    //         ->whereBetween('date', [$request->start, $request->end])
    //         ->when($vehicle_id, function ($query) use ($vehicle_id) {
    //             $query->whereHas('sourceTable', function ($q) use ($vehicle_id) {
    //                 $q->where('id', $vehicle_id);

    //             });
    //         })
    //         ->with([
    //             'notifications' => function ($notification) {
    //                 $notification->where('calendar', 1)
    //                     ->where('receiver_id', auth()->user()->id);
    //             },
    //             'sourceTable',
    //         ])
    //         ->get();


            $event_details = Event::where('company_id', auth()->user()->active_company())
                ->whereIn('action', ['order_created','driver_order_created'])
                ->whereBetween('date', [$request->start, $request->end])
                ->with([
                    'notifications' => function ($notification) {
                        return $notification
                            ->where('calendar', 1)
                            ->where('receiver_id', auth()->user()->id);
                    },
                    'sourceTable',
                ])
                ->get();


            //   dd($event_details);

        } else {
            $event_details = Event::where('company_id', auth()->user()->active_company())
                ->whereIn('action', ['planed_maintenance', 'order_created','driver_order_created'])
                ->whereBetween('date', [$request->start, $request->end])
                ->with([
                    'notifications' => function ($notification) {
                        return $notification
                            ->where('calendar', 1)
                            ->where('receiver_id', auth()->user()->id);
                    },
                    'sourceTable',
                ])
                ->get();
        }
        // dd($event_details);

        // dd($events->where('action', 'planed_maintenance'));
        // foreach($events->where('action', 'planed_maintenance') as $maintenance)
        $events = [];
        if ($request->input('vehicle_id')) {
            $vehicle_id = $request->input('vehicle_id');
            // $events = array_merge($events, self::vehicle_filtered_events($event_details->where('action', 'order_created'), $vehicle_id));
            $events = array_merge($events, self::vehicle_filtered_events($event_details, $vehicle_id));


        } else {
            $events = array_merge($events, self::maintenance_events($event_details->where('action', 'planed_maintenance')));
            $events = array_merge($events, self::ride_events($event_details->where('action', 'order_created')));
            $events = array_merge($events, self::driver_ride_events($event_details->where('action', 'driver_order_created')));

        }

        $event_base = [
            'id' => 1,
            'title' => "Maintenance Pending",
            'raw' => "Corla 7865",
            'attendees' => false,
            'body' => [
                'event_type' => 'regular_maintenance',
                'driver' => 'Haris',
                'created_by' => 'Maqsood',
                'phone' => '92310139929',
                'description' => 'maintenance kra ly ab',
            ],
            'user_id' => auth()->user()->id,
            'start_date' => date("2024-11-26"),
            'start' => date("2024-11-26"),
            'end' => date("2024-11-26"),
            'borderColor' => 'yellow',
            'backgroundColor' => 'transparent',
            'customStyle' => [
                'fontStyle' => 'italic',
                'fontSize' => '16px',
                'backgroundColor' => 'red',
            ],

            // 'calander_id'=> 'lead_created',
            // 'parent'=> 1999999,
            // 'location'=>"Pakistan",
            // 'owner'=> 'Holidays',
            // 'category'=> 'time',
            'state' => false,
            'isReadOnly' => true,
            'isFocused' => false,
            'isAllday' => true,
            'isPrivate' => false,
            'isPending' => false,
            // // 'name'=> 'Holidays',
            // 'color'=> 'green',
            // 'dragBackgroundColor'=> '#ffFF00',
            'open' => true,
        ];

        return response()->json($events);
    }

    public static function maintenance_events($event_details)
    {
        // dd($event_details);
        $events = [];
        foreach ($event_details as $event_detail) {
            // dd($event_detail->sourceTable?->model_name);
            if ($event_detail->sourceTable?->model_name) {
                // $vehicle = App\Http\Models\$event_detail->sourceTable->model_name::where('id',$event_detail->source_id)->first();
                $vehicle = app("App\\Models\\" . $event_detail->sourceTable->model_name)
                    ->where('id', $event_detail->source_id)
                    ->first();

                foreach ($event_detail->notifications as $notification) {
                    // dd($notification);
                    $events[] = [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'raw' => $vehicle->registration_no,
                        'body' => [
                            'event_type' => 'regular_maintenance',
                            'vehicle_id' => $event_detail->source_id,
                            // 'driver' => 'Haris',
                            // 'created_by' => 'Maqsood',
                            // 'phone' => '92310139929',
                            'description' => $notification->detail,
                        ],
                        'user_id' => auth()->user()->id,

                        'start_date' => date($event_detail->date),
                        'start' => date($event_detail->date),
                        'end' => date($event_detail->date),

                        'borderColor' => 'yellow',
                        'backgroundColor' => 'red',
                        'customStyle' => [
                            'fontStyle' => 'italic',
                            'fontSize' => '16px',
                            'backgroundColor' => 'red',
                        ],

                        // 'calander_id'=> 'lead_created',
                        // 'parent'=> 1999999,
                        // 'location'=>"Pakistan",
                        // 'owner'=> 'Holidays',
                        // 'category'=> 'time',
                        'attendees' => false,
                        'state' => false,
                        'isReadOnly' => true,
                        'isFocused' => false,
                        'isAllday' => true,
                        'isPrivate' => false,
                        'isPending' => false,
                        // // 'name'=> 'Holidays',
                        // 'color'=> 'green',
                        // 'dragBackgroundColor'=> '#ffFF00',
                        'open' => true,
                    ];
                }
            }

        }
        // dd($events);
        return $events;

    }
    public static function ride_events($event_details)
    {
        // dd($event_details);
        $events = [];
        foreach ($event_details as $event_detail) {
            // dd($event_detail->sourceTable?->model_name);
            // dd($event_detail->source_id);
            if ($event_detail->sourceTable?->model_name) {
                // $vehicle = App\Http\Models\$event_detail->sourceTable->model_name::where('id',$event_detail->source_id)->first();
                $order_detail = app("App\\Models\\" . $event_detail->sourceTable->model_name)
                    ->where('id', $event_detail->source_id)
                    ->first();
                // dd($order_detail->driver['name']);
                // dd($order_detail->vehicle['vehicle_identification_number']);

                // dd($order_detail->order['id']);
                  if ($order_detail->status == 'unapproved' || $order_detail->status == 'cancelled'  || $order_detail->status == 'completed') {
                        continue; // Skip to the next iteration
                        }

                foreach ($event_detail->notifications as $notification) {
                    // dd($notification);
                    // dd($order_detail->order['order_no']);

                    // dd($order_detail->status);
                    $events[] = [
                        'id' => $notification->id,
                        // 'title' =>($order_detail->order['trip_type']),
                        'title' => ucwords(str_replace('_', ' ', $order_detail->order['trip_type'])),
                        'raw' => $order_detail->status,
                        'body' => [
                            'event_type' => 'ride_events',
                            'order_no' =>$order_detail->order['order_no'],
                            // 'end_date' =>$order_detail->end_date,
                            'end_date' => $order_detail->end_date ? Carbon::parse($order_detail->end_date)->format('d/m/Y') : null ,
                            'rent' => $order_detail->rate,
                            'order_detail_id' => $event_detail->source_id,
                            'order_id' => $order_detail->order['id'],
                            'pickup_time' => $order_detail->pickup_time,
                            'status' => $order_detail->status,
                            'from' => $order_detail->from_loc,
                            'to' => $order_detail->to_loc,
                            'driver' => $order_detail->driver['name'] ?? null,
                            'vehicle_no' => $order_detail->vehicle['vehicle_no'] ?? null,

                            // 'driver' => 'Haris',
                            // 'created_by' => 'Maqsood',
                            // 'phone' => '92310139929',
                            'description' => $notification->detail,
                        ],
                        'user_id' => auth()->user()->id,

                        'start_date' => date($event_detail->date),
                        'start' => date($event_detail->date),
                        'end' => date($event_detail->date),

                        'borderColor' => 'yellow',
                        'backgroundColor' => '#640d5f',
                        'customStyle' => [
                            'fontStyle' => 'italic',
                            'fontSize' => '16px',
                            // 'backgroundColor' => 'yellow',
                            // 'color'=>'white'
                        ],

                        // 'calander_id'=> 'lead_created',
                        // 'parent'=> 1999999,
                        // 'location'=>"Pakistan",
                        // 'owner'=> 'Holidays',
                        // 'category'=> 'time',
                        'attendees' => false,
                        'state' => false,
                        'isReadOnly' => true,
                        'isFocused' => false,
                        'isAllday' => true,
                        'isPrivate' => false,
                        'isPending' => false,
                        // // 'name'=> 'Holidays',
                        // 'color'=> 'green',
                        // 'dragBackgroundColor'=> '#ffFF00',
                        'open' => true,
                    ];
                }
            }

        }
        // dd($events);
        return $events;

    }
     public static function driver_ride_events($event_details)
    {
        // dd($event_details);
        $events = [];
        foreach ($event_details as $event_detail) {
            // dd($event_detail->sourceTable?->model_name);
            // dd($event_detail->source_id);
            if ($event_detail->sourceTable?->model_name) {
                // $vehicle = App\Http\Models\$event_detail->sourceTable->model_name::where('id',$event_detail->source_id)->first();
                $order_detail = app("App\\Models\\" . $event_detail->sourceTable->model_name)
                    ->where('id', $event_detail->source_id)
                    ->first();
                // dd($order_detail->driver['name']);
                // dd($order_detail->vehicle['vehicle_identification_number']);
                    //  dd($order_detail->status);

                // dd($order_detail->order['id']);
                   if ($order_detail->status == 'unapproved' || $order_detail->status == 'cancelled'  || $order_detail->status == 'completed') {
                    // dd($order_detail->status);
                continue; // Skip to the next iteration
            }
                foreach ($event_detail->notifications as $notification) {
                    // dd($notification);
                    // dd($order_detail->status);

                    // dd($order_detail->status);
                    $events[] = [
                        'id' => $notification->id,
                        // 'title' => $notification->title,
                        'title' => ucwords(str_replace('_', ' ', $order_detail->order['trip_type'])),
                        'raw' => $order_detail->status,
                        'body' => [
                            'event_type' => 'ride_events',
                            'rent' => $order_detail->rate,
                            'order_no' =>$order_detail->order['order_no'],
                            'end_date' => $order_detail->end_date ? Carbon::parse($order_detail->end_date)->format('d/m/Y') : null ,
                            'order_detail_id' => $event_detail->source_id,
                            'order_id' => $order_detail->order['id'],
                            'pickup_time' => $order_detail->pickup_time,
                            'status' => $order_detail->status,
                            'from' => $order_detail->from_loc,
                            'to' => $order_detail->to_loc,
                            'driver' => $order_detail->driver['name'] ?? null,
                            'vehicle_no' => $order_detail->vehicle['vehicle_no'] ?? null,

                            // 'driver' => 'Haris',
                            // 'created_by' => 'Maqsood',
                            // 'phone' => '92310139929',
                            'description' => $notification->detail,
                        ],
                        'user_id' => auth()->user()->id,

                        'start_date' => date($event_detail->date),
                        'start' => date($event_detail->date),
                        'end' => date($event_detail->date),

                        'borderColor' => 'red',
                        'backgroundColor' => 'black',
                        'customStyle' => [
                            'fontStyle' => 'italic',
                            'fontSize' => '16px',
                            'backgroundColor' => 'yellow',
                        ],

                        // 'calander_id'=> 'lead_created',
                        // 'parent'=> 1999999,
                        // 'location'=>"Pakistan",
                        // 'owner'=> 'Holidays',
                        // 'category'=> 'time',
                        'attendees' => false,
                        'state' => false,
                        'isReadOnly' => true,
                        'isFocused' => false,
                        'isAllday' => true,
                        'isPrivate' => false,
                        'isPending' => false,
                        // // 'name'=> 'Holidays',
                        // 'color'=> 'green',
                        // 'dragBackgroundColor'=> '#ffFF00',
                        'open' => true,
                    ];
                }
            }

        }
        // dd($events);
        return $events;

    }
    public static function vehicle_filtered_events($event_details, $vehicle_id)
    {
        //  dd($event_details);
        // dd($vehicle_id);
        //  if($event_detail->sourceTable?->model_name){
        // $vehicle = App\Http\Models\$event_detail->sourceTable->model_name::where('id',$event_detail->source_id)->first();
        //         $order_detail = app("App\\Models\\OrderDetail")
        //         ->where('id', 3)
        //         ->where('vehicle_id',$vehicle_id)
        //         ->first();
        // //  }
        //         dd($order_detail);
        // dd($event_details);
        $events = [];

        foreach ($event_details as $event_detail) {
            // dd($event_detail->action);
            // dd($event_detail->sourceTable?->model_name);
            // dd($event_detail->source_id);
            if ($event_detail->sourceTable?->model_name) {
                // $vehicle = App\Http\Models\$event_detail->sourceTable->model_name::where('id',$event_detail->source_id)->first();
               $order_detail = app("App\\Models\\" . $event_detail->sourceTable->model_name)
                    ->where('id', $event_detail->source_id)
                    ->when($vehicle_id, function ($q) use ($vehicle_id) {
                        $q->where('vehicle_id', $vehicle_id);
                    })
                    ->first();


                // Retrieve the first matching record

                // dd($order_detail);
                // dd($order_detail->driver['name']);
                // dd($order_detail->vehicle['vehicle_identification_number']);

                // dd($order_detail->order['id']);
                if ($order_detail) {

                 if ($order_detail->status == 'unapproved' || $order_detail->status == 'cancelled'  || $order_detail->status == 'completed') {
                    // dd($order_detail->status);
                        continue; // Skip to the next iteration
                    }
                foreach ($event_detail->notifications as $notification) {
                    // dd($notification);
                    // dd($order_detail->status);

                    // dd($order_detail->status);
                    $events[] = [
                        'id' => $notification->id,
                        // 'title' => $notification->title,
                        'title' => ucwords(str_replace('_', ' ', $order_detail->order['trip_type'])),
                        'raw' => $order_detail->status,
                        'body' => [
                            'event_type' => 'vehicle_filter_events',
                            'rent' => $order_detail->rate,
                            'order_no' =>$order_detail->order['order_no'],
                            // 'end_date' =>$order_detail->end_date,
                            // 'end_date' => Carbon::parse($order_detail->end_date)->format('d/m/Y'),
                             'end_date' => $order_detail->end_date ? Carbon::parse($order_detail->end_date)->format('d/m/Y') : null ,
                            'order_detail_id' => $event_detail->source_id,
                            'order_id' => $order_detail->order['id'],
                            'pickup_time' => $order_detail->pickup_time,
                            'status' => $order_detail->status,
                            'from' => $order_detail->from_loc,
                            'to' => $order_detail->to_loc,
                            'driver' => $order_detail->driver['name'] ?? null,
                            // 'vehicle_identification_no' => $order_detail->vehicle['vehicle_identification_number'] ?? null,
                            'vehicle_no' => $order_detail->vehicle['vehicle_no'] ?? null,


                            // 'driver' => 'Haris',
                            // 'created_by' => 'Maqsood',
                            // 'phone' => '92310139929',
                            'description' => $notification->detail,
                        ],
                        'user_id' => auth()->user()->id,

                        'start_date' => date($event_detail->date),
                        'start' => date($event_detail->date),
                        'end' => date($event_detail->date),

                        'borderColor' => 'yellow',
                        'backgroundColor' => '#640d5f',
                        'customStyle' => [
                            'fontStyle' => 'italic',
                            'fontSize' => '16px',
                            'backgroundColor' => $event_detail->action === 'driver_order_created' ? 'yellow' : '',
                        ],

                        // 'calander_id'=> 'lead_created',
                        // 'parent'=> 1999999,
                        // 'location'=>"Pakistan",
                        // 'owner'=> 'Holidays',
                        // 'category'=> 'time',
                        'attendees' => false,
                        'state' => false,
                        'isReadOnly' => true,
                        'isFocused' => false,
                        'isAllday' => true,
                        'isPrivate' => false,
                        'isPending' => false,
                        // // 'name'=> 'Holidays',
                        // 'color'=> 'green',
                        // 'dragBackgroundColor'=> '#ffFF00',
                        'open' => true,
                    ];
                }
            }
            }

        }
        // dd($events);
        return $events;

    }

    // public function events(Request $request) {
    //     $validator = Validator::make( request()->all(), [
    //         'events' => [ 'required', 'array' ],
    //         'users' => [ 'required', 'array' ],
    //         'start' => [ 'required', 'date' ],
    //         'end' => [ 'required', 'date' ],
    //     ] );
    //     //validation errors
    //     if ( $validator->fails() ) {
    //         return json_encode([ 'errors' => $validator->errors() ]);
    //     }
    //     $data = $validator->validated();
    //     $events = [];
    //     $leads = [];
    //     $lead_reminders = [];
    //     foreach ( $data[ 'events' ] as $event ) {
    //         if ( $event == 'lead_created' ) {
    //             $leads = Lead::check_own()->where( 'lead_active_state', 'active' )
    //             ->whereIn('lead_creatorid',$data['users'])
    //             ->select( [ 'lead_id', 'lead_creatorid', 'lead_created', 'lead_description', 'lead_country', 'lead_firstname', 'lead_lastname', 'lead_email', 'lead_phone', 'lead_title' ] )
    //             // ->where( 'lead_created', '>=', $data[ 'start' ] )
    //             // ->where( 'lead_created', '<=', $data[ 'end' ] )
    //             ->when(
    //                 $data[ 'start' ] == $data[ 'end' ],
    //                 function($query) use($data){
    //                     $query->whereDate( 'lead_created', $data[ 'start' ]);
    //                 },
    //                 function($query) use($data){
    //                     $query->where( 'lead_created', '>=', $data[ 'start' ]);
    //                     $query->where( 'lead_created', '<=', $data[ 'end' ]);
    //                 }
    //             )
    //             ->with('creator')
    //             ->get();
    //         }
    //         if ( $event == 'lead_reminder' ) {
    //             $lead_reminders = Lead::check_own()->where( 'leads.lead_active_state', 'active' )
    //             ->whereIn('leads.lead_creatorid',$data['users'])
    //             ->select( [
    //                 'leads.lead_id',
    //                 'leads.lead_creatorid',
    //                 'leads.lead_created',
    //                 'leads.lead_description',
    //                 'leads.lead_country',
    //                 'leads.lead_firstname',
    //                 'leads.lead_lastname',
    //                 'leads.lead_email',
    //                 'leads.lead_phone',
    //                 'leads.lead_title',
    //                 'reminders.reminder_datetime',
    //                 'reminders.reminder_title'
    //             ])
    //             ->when(
    //                 $data[ 'start' ] == $data[ 'end' ],
    //                 function($query) use($data){
    //                     $query->whereDate( 'reminders.reminder_datetime', $data[ 'start' ]);
    //                 },
    //                 function($query) use($data){
    //                     $query->where( 'reminders.reminder_datetime', '>=', $data[ 'start' ]);
    //                     $query->where( 'reminders.reminder_datetime', '<=', $data[ 'end' ]);
    //                 }
    //             )
    //             ->rightJoin( 'reminders', 'reminders.reminderresource_id', "leads.lead_id")
    //             ->with('creator:id,first_name,last_name')
    //             ->get();
    //             // dd($lead_reminders);
    //         }
    //     }
    //     foreach ( $leads as $lead ) {
    //         array_push( $events, [
    //             'id'=> $lead->lead_id,
    //             'title'=> "$lead->lead_title",
    //             'raw'=> $lead->lead_email,
    //             'attendees'=> [ "$lead->lead_firstname $lead->lead_lastname" ],
    //             'body'=> [$lead->lead_description,($lead->creator->first_name." ".$lead->creator->last_name),$lead->lead_phone],
    //             'user_id'=> $lead->lead_creatorid,
    //             'calander_id'=> 'lead_created',
    //             // 'parent'=> 1999999,
    //             'start_date'=> date( $lead->lead_created ),
    //             'start'=> date( $lead->lead_created ),
    //             'end'=> date( $lead->lead_created ),
    //             'location'=>$lead->lead_country,
    //             // 'owner'=> 'Holidays',

    //             'category'=> 'time',
    //             'state'=> 'Lead Created',
    //             'isReadOnly'=> true,
    //             'customStyle'=> [
    //                 'fontStyle'=> 'italic',
    //                 'fontSize'=> '16px',
    //             ],
    //             'isFocused'=>false,
    //             'isAllday'=>true,
    //             'isPrivate'=>false,
    //             'isPending'=>false,

    //             // // 'name'=> 'Holidays',
    //             // 'color'=> 'green',
    //             // 'borderColor'=> '#ffFF00',
    //             'backgroundColor'=> $this->lead_created_color,
    //             // 'dragBackgroundColor'=> '#ffFF00',
    //             'open'=> true
    //         ]);
    //     }
    //     foreach ($lead_reminders as $lead ) {
    //         array_push( $events, [
    //             'id'=> $lead->lead_id,
    //             'title'=> "$lead->lead_title",
    //             'raw'=> $lead->lead_email,
    //             // 'text'=> 'Good Friday',
    //             'attendees'=> [ "$lead->lead_firstname $lead->lead_lastname" ],
    //             'body'=> [$lead->reminder_title,($lead->creator->first_name." ".$lead->creator->last_name),$lead->lead_phone],
    //             'user_id'=> $lead->lead_creatorid,
    //             'calander_id'=> 'lead_reminder',
    //             // 'parent'=> 1999999,
    //             'start_date'=> date( $lead->reminder_datetime ),
    //             'start'=> date( $lead->reminder_datetime ),
    //             'end'=> date( $lead->reminder_datetime ),
    //             'location'=>$lead->lead_country,
    //             // 'owner'=> 'Holidays',

    //             'category'=> 'time',
    //             'state'=> 'Lead Reminder',
    //             'isReadOnly'=> true,
    //             'customStyle'=> [
    //                 'fontStyle'=> 'italic',
    //                 'fontSize'=> '16px',
    //             ],
    //             'isFocused'=>false,
    //             'isAllday'=>false,
    //             'isPrivate'=>false,
    //             'isPending'=>false,

    //             // // 'name'=> 'Holidays',
    //             // 'color'=> '#ffFF00',
    //             // 'borderColor'=> '#ffFF00',
    //             'backgroundColor'=> $this->lead_reminders_color,
    //             // 'dragBackgroundColor'=> $this->lead_reminders_color,
    //             'open'=> true
    //         ]);
    //     }

    //     return json_encode($events, 200 );
    // }
}
