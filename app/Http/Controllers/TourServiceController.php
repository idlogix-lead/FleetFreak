<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\WhatsAppController;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class TourServiceController extends Controller
{
    static $ignores = ['create_customer_from_order' => true, 'deleteRow' => true];
    public $my_companies;
    static $role_module_id = 43;


    public function __construct()
    {
        $this->middleware('RolePermissions');
        $this->middleware(function ($request, $next) {
            $this->my_companies =  auth()->user()->companies->toArray();
            return $next($request);
        });
        // $this->middleware('checkCompanyAccess');
    }

    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'name' => "TourService",
                'link' => route("tour_services.index"),
                'active' => true,
            ],
        ];

        // $user = auth()->user();

        // $company = $user->companies->first();

        // if (!$company) {
        //     return redirect()->route('dashboard')->with('error', 'No associated company found.');
        // }
        // $companyId = $company->id ?? null;
        $companyId = auth()->user()->active_company() ?? null;
        $query = $request->input('query');
        $perPage = $request->input('perPage', 10);

        if (auth()->user()->actor_id == 2) {
            $orders = Order::when($companyId, function ($query) use ($companyId) {
                return $query->where('company_id', $companyId);
            })
                ->checkGlobal(9)->whereNotIn('overall_status', ['pending', 'approved'])->where('trip_type', 'tour_booking')
                ->when($query, function ($q) use ($query) {
                    $q->where(function ($q) use ($query) {
                        $q->where('order_no', 'ILIKE', '%' . $query . '%')
                            ->orWhere('overall_status', 'ILIKE', '%' . $query . '%')
                            ->orWhereHas('partner_business', function ($q2) use ($query) {
                                $q2->where('company_name', 'ILIKE', '%' . $query . '%');
                            })->orWhereHas('partner_customer', function ($q3) use ($query) {
                            $q3->where('name', 'ILIKE', '%' . $query . '%');
                        });

                    });
                })->orderBy('created_at', 'desc')->paginate($perPage);

        } else {
            $orders = Order::where('company_id', $companyId)
                ->checkGlobal(9)->where('overall_status', '!=', 'pending')->where('overall_status', '!=', 'approved')->where('created_by', auth()->user()->id)->when($query, function ($q) use ($query) {
                $q->where(function ($q) use ($query) {
                    $q->where('order_no', 'ILIKE', '%' . $query . '%')
                        ->orWhere('overall_status', 'ILIKE', '%' . $query . '%')
                        ->orWhereHas('partner_customer', function ($q3) use ($query) {
                            $q3->where('name', 'ILIKE', '%' . $query . '%');
                        });

                });
            })->orderBy('created_at', 'desc')->paginate($perPage);
            // })->get();

        }
        // dd($orders);
        return view('tour-service.index', compact('orders', 'breadcrumbs'))
            ->with('i', (request()->input('page', 1) - 1) * $orders->perPage());
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
                'name' => "TourService",
                'link' => route("tour_services.index"),
                'active' => false,
            ],
            [
                'name' => "Create",
                'link' => route("tour_services.create"),
                'active' => true,
            ],
        ];
        $user = auth()->user();
        $company = $user->companies->first();
        $company_id = auth()->user()->active_company();

        // Correct query to get vehicle models associated with the authenticated user's company
        $vehicleModels = \App\Models\VehicleModel::when($company_id, function ($query) use ($company_id) {
            return $query->whereHas('vehicleClass', function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            });
        })
            ->with('vehicleClass') // Eager load vehicleClass relationship
            ->get()
            ->sortBy('name') // Sort by vehicle model's name
            ->unique('name'); // Ensure unique names
        //$order = new Order();

        $businessPartners = \App\Models\Partner::when($company_id, function ($query) use ($company_id) {
            return $query->where('company_id', $company_id);
        })
        // ->where('partner_type', 'business')
        // Assuming 'business' is the type for agents
            ->get();
        $latestOrder = Order::where('company_id',$company_id)->latest()->first();

        // Extract and increment the numeric part
        if ($latestOrder) {
            // Extract the numeric part and increment it
            $nextNumber = intval($latestOrder->order_no) + 1;
        } else {
            $nextNumber = 1;
        }

        // Format the next order number to ensure it is 6 digits long
        $nextOrderNo = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // Create a new order instance
        $order = new Order();
        $order->order_no = $nextOrderNo;
        // $vehicle_class = VehicleClass::get();
        // Other attributes...
        // $business_partner = Partner::where('partner_type','business')->get();
        // $customer_partner = Partner::where('partner_type','customer')->get();
        return view('tour-service.create', compact('order', 'vehicleModels', 'breadcrumbs', 'nextOrderNo', 'businessPartners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * *
     */
    public function store(Request $request)
    {
        $payload = [];
        // dd($request);
        // Validate the request data
        if ($request['trip_type'] == 'tour_booking') {
            $order_validator = Validator::make($request->all(), [
                'order_no' => ['required', 'unique:orders,order_no'],
                'customer_partner_id' => ['nullable'],
                'business_partner_id' => ['required'],
                'vehicle_model_id' => ['required'],
                'vehicle_class_id' => ['required'],
                'overall_adult' => ['required'],
                'overall_child' => ['required'],
                'overall_bags' => ['required'],
                'overall_status' => ['required'],
                'booking_amount' => ['nullable'],
                'final_amount' => ['nullable'],
                'company_id' => ['nullable'],
                'trip_type' => ['required'],
                // order_details validation:

                //'order_id' => ['required'],
                'rate_list_id.*' => ['nullable'],

                'from.*' => ['required'],
                'to.*' => ['required'],
                'rate.*' => ['required'],
                'status.*' => ['required'],
                'duration.*' => ['required'],
                'end_date.*' => ['required'],
                // 'flight_num.*' => ['nullable'],
                // 'airline_name.*' => ['nullable'],
                // 'adult.*' => ['required'],
                // 'child.*'=>['required'],
                // 'bags.*' => ['required'],
                'date.*' => ['required'],
                // 'pickup_time.*' => ['required'],
                'company_id.*' => ['nullable'],
                // 'checkout_time.*'=>['nullable'],
                // 'is_ac.*' => ['required'],
                // customer model required fields:
                // 'customer_name' => ['required'],
                // 'whatsapp_no' => ['required'],
                // 'prefix_whatsapp' => ['required'],
                // 'email' => ['nullable'],
                // 'passport' => ['nullable'],
                // 'phone_no' => ['nullable'],
                // 'prefix_phone' => ['nullable'],

                // 'cnic' => ['nullable'],
                // 'address1' => ['nullable'],
                // 'country' => ['nullable'],
                // 'city' => ['nullable'],

            ]);

        }
        // dd($order_validator);

        // dd($order_validator);

        if (!isset($request['rate']) || count($request['rate']) < 1) {
            return back()->withErrors(['errors' => 'At least one order line is required.'])->withInput();
        }
        // if (count($request['pickup_time']) < 1 || $request['pickup_time']==null) {
        //     return back()->with('errors', 'AtLeast One Orderline is required.');
        // }

        if ($order_validator->fails()) {
            //    dd($order_validator->errors());
            return back()->with('errors', $order_validator->errors())->withInput();

            // return back()->with('errors', $order_validator->errors());
        }
        // Update lead attributes with validated data
        $order_data = $order_validator->validated();
        $order_data['created_by'] = auth()->user()->id;
        if ($order_data['overall_status'] == 'draft') {
            $order_data['status'] = 'draft';

        } else {

            $order_data['status'] = 'pending';

        }

        $order_data['reason'] = null;

        // creating customer in order
        // dd($order_data);
        $user = auth()->user();
        // $company = auth()->user()->active_company() ?? null;

        // $customer_partner_id = Partner::create([
        //     'name' => $order_data['customer_name'],
        //     'whatsapp_no' => $order_data['whatsapp_no'],
        //     'prefix_whatsapp' => $order_data['prefix_whatsapp'] ?? null,
        //     'email' => $order_data['email'] ?? null,
        //     'cnic' => $order_data['cnic'] ?? null,
        //     'phone_no' => $order_data['phone_no'] ?? null,
        //     'prefix_phone' => $order_data['prefix_phone'] ?? null,
        //     'passport' => $order_data['passport'] ?? null,
        //     'address1' => $order_data['address1'] ?? null,
        //     'country' => $order_data['country'] ?? null,
        //     'city' => $order_data['city'] ?? null,
        //     // 'passport'=>$order_data['passport']?? null,
        //     'actor_id' => 6,
        //     'created_by' => auth()->user()->id,
        //     'business_partner_id' => $order_data['business_partner_id'],
        //     'company_id' => $company,
        // ]);
        // $order_data['customer_partner_id'] = $customer_partner_id->id;
        // --------end--------
        // dd($order_data);
        $payload['order_data'] = $order_data;
        // dd($payload);
        Order::store_order($payload);
        //$order_data = Order::create($order_data);
        // $this->sendWhatsAppNotification($order_data['business_partner_id'], $order_data);

        return redirect()->route('tour_services.index')->with('success', 'Order created successfully.');
    }

    private function sendWhatsAppNotification($businessPartnerId, $orderData)
    {
        if ($orderData['overall_status'] !== 'draft') {
            $status = $orderData['status'] ?? 'pending';
            $whatsAppController = new WhatsAppController();
            $admin_partner_id = 95;
            // dd($admin_partner_id);

            foreach ($orderData['rate_list_id'] as $key => $rateListId) {
                $orderDetail = [
                    'rate_list_id' => $rateListId,
                    'pickup_time' => $orderData['pickup_time'][$key],
                    'date' => $orderData['date'][$key],
                    // 'from_loc' => $orderData['from_loc'][$key],
                    // 'to_loc' => $orderData['to_loc'][$key],
                    'rate' => $orderData['rate'][$key],
                    'airline_name' => $orderData['airline_name'][$key] ?? null,
                    'flight_num' => $orderData['flight_num'][$key] ?? null,
                ];

                // $whatsAppController->sendNotification(array_merge($orderData, $orderDetail), $status, $businessPartnerId, null, null, $admin_partner_id, null);
            }

            // foreach ($order_data['pickup_time'] as $key => $pickup_time) {
            //     $pickupDateTimeString = $order_data['date'][$key] . ' ' . trim($pickup_time);
            //     $pickupDateTime = Carbon::createFromFormat('Y-m-d H:i', $pickupDateTimeString);
            //     $scheduleTime = $pickupDateTime->subHours(24);

            //     // Schedule notification for customer 24 hours before pickup time
            //     $whatsAppController = new WhatsAppController();
            //     $whatsAppController->sendNotification(
            //         $order_data,
            //         'approved',
            //         $businessPartnerId,
            //         $order_data['customer_partner_id'],
            //         null,
            //         $admin_partner_id,
            //         $scheduleTime,
            //     );

            // }
        }
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
                'name' => "TourService",
                'link' => route("tour_services.index"),
                'active' => false,
            ],
            [
                'name' => "Show",
                'link' => route("tour_services.show", $id),
                'active' => true,
            ],
        ];
        $order = Order::checkGlobal(9)->where('company_id',auth()->user()->active_company())->find($id);

        return view('tour-service.show', compact('order', 'breadcrumbs'));
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
                'name' => "TourService",
                'link' => route("tour_services.index"),
                'active' => false,
            ],
            [
                'name' => "Edit",
                'link' => route("tour_services.edit", $id),
                'active' => true,
            ],
        ];
        $order = Order::checkGlobal(9)->where('company_id',auth()->user()->active_company())->find($id);

        // dd($order);
        // $business_partner = Partner::where('partner_type','business')->get();
        //$customer_partner = Partner::where('partner_type','customer')->get();

        return view('tour-service.edit', compact('order', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Order $order
     * *
     */
    public function update(Request $request, $order)
    {
        // dd('update');
        $payload = [];
        $order = Order::find($order);
        // dd($order);
        // Validate the request data
        // dd($request);
        if ($request['trip_type'] == 'tour_booking') {
            $order_validator = Validator::make($request->all(), [
                'order_no' => ['required'],
                'customer_partner_id' => ['nullable'],
                'business_partner_id' => ['required'],
                'vehicle_model_id' => ['required'],
                'vehicle_class_id' => ['required'],
                'overall_adult' => ['required'],
                'overall_child' => ['required'],
                'overall_bags' => ['required'],
                'overall_status' => ['required'],
                'booking_amount' => ['nullable'],
                'final_amount' => ['nullable'],
                'company_id' => ['nullable'],
                'trip_type' => ['required'],
                // order_details validation:

                //'order_id' => ['required'],
                'rate_list_id.*' => ['nullable'],
                'from.*' => ['required'],
                'to.*' => ['required'],
                'rate.*' => ['required'],
                'status.*' => ['required'],
                'duration.*' => ['required'],
                'end_date.*' => ['required'],
                // 'flight_num.*' => ['nullable'],
                // 'airline_name.*' => ['nullable'],
                // 'adult.*' => ['required'],
                // 'child.*'=>['required'],
                // 'bags.*' => ['required'],
                'date.*' => ['required'],
                // 'pickup_time.*' => ['required'],
                'company_id.*' => ['nullable'],
                // 'checkout_time.*'=>['nullable'],
                // 'is_ac.*' => ['required'],
                // customer model required fields:
                // 'customer_name' => ['required'],
                // 'whatsapp_no' => ['required'],
                // 'prefix_whatsapp' => ['required'],
                // 'email' => ['nullable'],
                // 'passport' => ['nullable'],
                // 'phone_no' => ['nullable'],
                // 'prefix_phone' => ['nullable'],

                // 'cnic' => ['nullable'],
                // 'address1' => ['nullable'],
                // 'country' => ['nullable'],
                // 'city' => ['nullable'],

            ]);
            // dd('passenger validator', $order_validator);

            // dd('this is cargo validator'.$order_validator);
        }

        if (!isset($request['rate']) || count($request['rate']) < 1) {
            return back()->withErrors(['errors' => 'At least one order line is required.'])->withInput();
        }
        if ($order_validator->fails()) {

            return back()->with('errors', $order_validator->errors());
        }
        // Update lead attributes with validated data
        $order_data = $order_validator->validated();
        $order_data['updated_by'] = auth()->user()->id;
        if ($order_data['overall_status'] == 'draft') {
            $order_data['status'] = 'draft';
        } else {

            $order_data['status'] = 'pending';

        }
        $order_data['reason'] = null;
        $order_data['customer_partner_id'] = $order->customer_partner_id;
        $payload['order_data'] = $order_data;
        $user = auth()->user();
        // $company = auth()->user()->active_company() ?? null;

        // $partner_id = $order->customer_partner_id;
        // $partner = Partner::find($partner_id);
        // // /dd($partner);
        // $partner->update([
        //     'name' => $order_data['customer_name'],
        //     'whatsapp_no' => $order_data['whatsapp_no'],
        //     'prefix_whatsapp' => $order_data['prefix_whatsapp'] ?? null,
        //     'email' => $order_data['email'] ?? null,
        //     'cnic' => $order_data['cnic'] ?? null,
        //     'phone_no' => $order_data['phone_no'] ?? null,
        //     'prefix_phone' => $order_data['prefix_phone'] ?? null,
        //     'passport' => $order_data['passport'] ?? null,
        //     'address1' => $order_data['address1'] ?? null,
        //     'country' => $order_data['country'] ?? null,
        //     'city' => $order_data['city'] ?? null,
        //     'company_id' => $company,
        //     // 'passport' => $order_data['passport'] ?? null,
        //     'actor_id' => 6,
        //     'updated_by' => auth()->user()->id,
        // ]);
        // dd($partner);

        $payload['order'] = $order;

        // dd($payload);
        Order::update_order($payload);

        // $order->update($order_data);
        // $this->sendWhatsAppNotification($order_data['business_partner_id'], $order_data);

        return redirect()->route('tour_services.index')
            ->with('success', 'Order updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $order = Order::where('company_id',auth()->user()->active_company())->find($id)->delete();

        return redirect()->route('tour_services.index')
            ->with('success', 'Order deleted successfully');
    }
    public function deleteRow($id)
    {
        try {
            // Find the row by ID and delete it
            $order_detail = OrderDetail::findOrFail($id);
            $order_detail->delete();

            return response()->json(['success' => true, 'message' => 'Row removed successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove row'], 500);
        }
    }
    public function changePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required_with:password_confirmation|same:password_confirmation|min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8']);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }
        // Update lead attributes with validated data
        $data = $validator->validated();
        $user = User::findOrFail($id);
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

}
