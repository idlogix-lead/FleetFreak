<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Models\Concerns\HasClient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


/**
 * Class Invoice
 *
 * @property $id
 * @property $document_no
 * @property $company_id
 * @property $vehicle_id
 * @property $business_partner_id
 * @property $date
 * @property $description
 * @property $total_amount
 * @property $grand_total_amount
 * @property $document_status
 * @property $document_type
 * @property $created_by
 * @property $updated_by
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Partner $partner
 * @property Company $company
 * @property User $user
 * @property User $user
 * @property Vehicle $vehicle
 * @property InvoiceLine[] $invoiceLines
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Invoice extends BaseModel
{
    use SoftDeletes;
    use BelongsToOrganization;
    use HasClient;

    static $rules = [
        'document_no' => 'required|string',
        'company_id' => 'required',
        'vehicle_id' => 'required',
        'business_partner_id' => 'required',
        'date' => 'required',
        'description' => 'string',
        'total_amount' => 'required',
        'grand_total_amount' => 'required',
        'document_status' => 'required',
        'document_type' => 'required',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_no', 'company_id', 'vehicle_id', 'business_partner_id',
        'date', 'description', 'total_amount', 'grand_total_amount', 'document_status',
        'created_by', 'updated_by',
        'document_type_id', 'start_time', 'end_time',
        'client_id', 'order_id', 'date_ordered', 'is_active', 'date_invoiced', 'account_date',
        'user_id', 'partner_location_id', 'currency', 'company_agent',
        'discount_printed', 'payment_rule', 'payment_term', 'is_pay_schedule_valid',
        'document_action', 'material_inout_id', 'price_list_id',
        'created_at', 'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'business_partner_id', 'id');
    }

    public static function getColumnNames()
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by_details()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_by_details()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class, 'vehicle_id', 'id');
    }

    public function InvDocumentType()
    {
        return $this->belongsTo(\App\Models\InvoiceDocumentType::class, 'document_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoiceLines()
    {
        return $this->hasMany(\App\Models\InvoiceLine::class, 'invoice_id', 'id');
    }

    public static function document_no_prefix($document_type)
    {
        switch ($document_type) {
            case 'maintenance':
                return 'INV-MNT-';
                break;
            case 'fuel':
                return 'INV-FUEL-';
                break;
            case 'toll_tax':
                return 'INV-TOLL-';
                break;
            case 'entertainment':
                return 'INV-ENT-';
                break;
            default:
                return 'INV-EXP-';
        }
    }

    public static function generate_document_no($company_id, $document_type)
    {
        $last = self::where('company_id', $company_id)
        //
            ->where('document_type', $document_type)
        //
            ->orderByDesc('id')->first();
        if ($last) {
            $document_no = intval(last(explode('-', $last->document_no))) + 1;
        } else {
            $document_no = 1;
        }
        $prefix = self::document_no_prefix($document_type);

        return $prefix . str_pad($document_no, 4, "0", STR_PAD_LEFT);
    }
    public static function generate_document_no1($company_id, $document_type)
    {
        $last = self::where('company_id', $company_id)
        //
            ->where('document_type_id', $document_type->id)
        //
            ->orderByDesc('id')->first();
        if ($last) {
            $document_no = intval(last(explode('-', $last->document_no))) + 1;
        } else {
            $document_no = 1;
        }
        // $prefix =  self::document_no_prefix($document_type);

        return $document_type->code . str_pad($document_no, 4, "0", STR_PAD_LEFT);
    }

    public static function store_maintainence($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // Get the authenticated user
        // $user = auth()->user();
        // $company_id = auth()->user()->active_company() ??  null;

        $maintenance = Invoice::create([
            'vehicle_id' => $data['vehicle_id'],
            'business_partner_id' => $data['business_partner_id'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'description' => $data['description'],
            'inspection_id' => $data['inspection_id'] ?? null,
            'total_amount' => $data['total_amount'],
            'grand_total_amount' => $data['grand_total_amount'],
            'created_by' => $data['created_by'],

            'document_status' => $data['document_status'],
            'document_type_id' => $data['document_type_id'],
            'company_id' => $data['company_id'],
            'document_no' => $document_no,
        ]);
        foreach ($data['row'] as $row) {
            if ($data['document_status'] == 'completed' && isset($row['is_checked']) && $row['is_checked'] == 0) {
                continue;
            }
            $invoice_line = InvoiceLine::create([
                'invoice_id' => $maintenance->id,
                'is_checked' => $row['is_checked'] ?? null,
                'activity_line_id' => $row['activity_id'] ?? null,
                'is_service_charge' => $row['is_service_charge'],
                'description' => $row['description'],
                'quantity' => $row['quantity'] ?? 0,
                'rate' => $row['rate'] ?? 0,
                'line_amount' => $row['line_total'] ?? 0,
            ]);

            if (isset($row['product_id'])) {
                InvoiceLineProduct::firstOrCreate([
                    'invoice_line_id' => $invoice_line->id,
                    'product_id' => $row['product_id'],
                ]);
            }
            // foreach($row['product_id']??[] as $product_id){
            //     InvoiceLineProduct::create([
            //         'invoice_line_id' => $invoice_line->id,
            //         'product_id' => $product_id,
            //     ]);
            // }
        }

        // if ($data['document_status'] == 'completed' && $data['document_type_id'] == '1') {
        //     $debit_account = Account::where('company_id', $data['company_id'])->where('name', 'Maintenance Expense')->first();
        //     $credit_account = Account::where('company_id', $data['company_id'])->where('name', 'Accounts Payable')->first();
        //     $currency = User::current_currency();
        //     AccountTransaction::createTransaction($data['date'], $data['company_id'], $debit_account->id, $credit_account->id, $data['grand_total_amount'], $currency->id, $maintenance->id, null, null, 51,business_partner_id:$data['business_partner_id']);
        //     self::createNextMaintenanceEvent($data, $data['created_by']);
        // }

    }
    public static function store_toll_tax($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // Get the authenticated user
        $user = auth()->user();
        // dd($payload);
        $company_id = auth()->user()->active_company() ?? null;

        $toll_tax = Invoice::create([
            'vehicle_id' => $data['vehicle_id'],
            'business_partner_id' => $data['business_partner_id'],
            'date' => $data['date'],
            'description' => $data['description'],
            'total_amount' => $data['total_amount'],
            'grand_total_amount' => $data['grand_total_amount'],
            'created_by' => $data['created_by'],

            'document_status' => $data['document_status'],
            'document_type_id' => $data['document_type_id'],
            'company_id' => $company_id,
            'document_no' => $document_no,
        ]);
        foreach ($data['row'] as $row) {
            $image_url = null;

            //     if ($data['document_status'] == 'completed' && isset($row['is_checked']) && $row['is_checked'] == 0) {
            //         continue;
            // if (isset($row['picture_of_toll_tax']) && is_object($row['picture_of_toll_tax'])) {
            //     $image = $row['picture_of_toll_tax'];
            //     $imageName = time() . '_' . $image->getClientOriginalName();
            //     $image->move(public_path('storage/resources_images/uploads'), $imageName);
            //     $image_url = 'resources_images/uploads/' . $imageName;
            // }
            if (isset($row['picture_of_toll_tax']) && is_object($row['picture_of_toll_tax'])) {
                $image = $row['picture_of_toll_tax'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/toll_tax_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $image_url = $companyFolderPath . '/' . $imageName;
            }
            //     }
            // dd($image_url);
            $invoice_line = InvoiceLine::create([
                'invoice_id' => $toll_tax->id,
                // 'is_checked' => $row['is_checked'] ?? null,
                // 'activity_line_id' => $row['activity_id'] ?? null,
                // 'is_service_charge' => $row['is_service_charge'],
                // 'description' => $row['description'],
                'quantity' => 0,
                'rate' => 0,
                'line_amount' => $row['line_amount'],
                'check_point' => $row['check_point'],
                'picture_of_toll_tax' => $image_url ?? null,
            ]);
        }

        if ($data['document_status'] == 'completed') {

            $debit_account = Account::where('company_id', $company_id)->where('name', 'Toll Expenses')->first();
            $credit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Payable')->first();
            $currency = User::current_currency();
            AccountTransaction::createTransaction($data['date'], $company_id, $debit_account->id, $credit_account->id,1, $data['grand_total_amount'], $currency->id, $toll_tax->id, null, null, 51);
        }
    }

    public static function store_fuel_expense($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // Get the authenticated user
        $user = auth()->user();
        // dd($payload);
        $company_id = auth()->user()->active_company() ?? null;

        $fuel_expense = Invoice::create([
            'vehicle_id' => $data['vehicle_id'],
            'business_partner_id' => $data['business_partner_id'],
            'date' => $data['date'],
            'description' => $data['description'],
            'total_amount' => $data['total_amount'],
            'grand_total_amount' => $data['grand_total_amount'],
            'created_by' => $data['created_by'],

            'document_status' => $data['document_status'],
            'document_type_id' => $data['document_type_id'],
            'company_id' => $company_id,
            'document_no' => $document_no,
        ]);
        foreach ($data['row'] as $row) {
            $meter_reading_image_url = null;
            $bill_image_url = null;
            $petrol_machine_image_url = null;
            $driver_image_url = null;
            //     if ($data['document_status'] == 'completed' && isset($row['is_checked']) && $row['is_checked'] == 0) {
            //         continue;
            // if (isset($row['picture_of_toll_tax']) && is_object($row['picture_of_toll_tax'])) {
            //     $image = $row['picture_of_toll_tax'];
            //     $imageName = time() . '_' . $image->getClientOriginalName();
            //     $image->move(public_path('storage/resources_images/uploads'), $imageName);
            //     $image_url = 'resources_images/uploads/' . $imageName;
            // }
            if (isset($row['meter_reading_image']) && is_object($row['meter_reading_image'])) {
                $image = $row['meter_reading_image'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/meter_reading_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $meter_reading_image_url = $companyFolderPath . '/' . $imageName;
            }
            if (isset($row['bill_image']) && is_object($row['bill_image'])) {
                $image = $row['bill_image'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/bill_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $bill_image_url = $companyFolderPath . '/' . $imageName;
            }
            if (isset($row['petrol_machine_image']) && is_object($row['petrol_machine_image'])) {
                $image = $row['petrol_machine_image'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/petrol_machine_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $petrol_machine_image_url = $companyFolderPath . '/' . $imageName;
            }
            if (isset($row['driver_selfie']) && is_object($row['driver_selfie'])) {
                $image = $row['driver_selfie'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/driver_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $driver_image_url = $companyFolderPath . '/' . $imageName;
            }
            //     }
            $invoice_line = InvoiceLine::create([
                'invoice_id' => $fuel_expense->id,
                // 'is_checked' => $row['is_checked'] ?? null,
                // 'activity_line_id' => $row['activity_id'] ?? null,
                // 'is_service_charge' => $row['is_service_charge'],
                // 'description' => $row['description'],
                'quantity' => 0,
                'rate' => 0,
                'line_amount' => $row['line_amount'],
                // 'check_point' => $row['check_point'],
                // 'picture_of_toll_Tax' => $image_url
                'meter_reading_km' => $row['meter_reading_km'],
                'fuel_quantity_liters' => $row['fuel_quantity_liters'],
                'total_fuel_cost' => $row['total_fuel_cost'],
                'meter_reading_image' => $meter_reading_image_url ?? null,
                'bill_image' => $bill_image_url ?? null,
                'petrol_machine_image' => $petrol_machine_image_url ?? null,
                'driver_selfie' => $driver_image_url ?? null,

            ]);
        }

        if ($data['document_status'] == 'completed') {

            $debit_account = Account::where('company_id', $company_id)->where('name', 'Fuel Expenses')->first();
            $credit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Payable')->first();
            $currency = User::current_currency();
            AccountTransaction::createTransaction($data['date'], $company_id, $debit_account->id, $credit_account->id, (float)$data['grand_total_amount'], $currency->id, $fuel_expense->id, null, null, 51,business_partner_id:$data['business_partner_id']);
        }
    }

    public static function createNextMaintenanceEvent($data, $auth_user_id)
    {
        if($data['document_type_id'] == 5){

            $table = Table::where('id', 43)->first();

            $modelClass = 'App\\Models\\' . $table->model_name;
            $vehicle = $modelClass::where('id', $data['vehicle_id'])->with('vehicleManager')->first();
            $days = $vehicle->maintenance_interval_days;
            $km = $vehicle->maintenance_oilchange_interval_km;

            if ($vehicle->maintenance_interval_days) {
                $date = Carbon::parse($data['date'])->addDays($days)->toDateString();
                // $date = Carbon::now()->addDays($days)->toDateString();
                $title = "Vehicle " . $vehicle->registration_no . " Inspection Schedule";
                $description = "Vehicle Inspection is planed for this date " . $date;

                $notifications = [];
               // dd($vehicle?->driver?->user?->id, $vehicle?->vehicleManager?->user_id, User::where('partner_id', $data['business_partner_id'])->first());

               if ($vehicle?->driver?->user?->id) {
                    $notifications[] = [
                        'receiver_id' => $vehicle?->driver?->user?->id,
                        'sender_id' => $auth_user_id,
                        'calendar' => 1,
                        // 'sms' => 1,
                        // 'email' => 1,
                        // 'whatsapp' => 1,
                        // 'fcm_mobile_push' => 1,
                        // 'fcm_web_push' => 1,
                    ];
                }

                if ($vehicle?->vehicleManager?->user_id) {
                    $notifications[] = [
                        'receiver_id' => $vehicle->vehicleManager->user_id,
                        'sender_id' => $auth_user_id,
                        'calendar' => 1,
                        // 'sms' => 1,
                        // 'email' => 1,
                        // 'whatsapp' => 1,
                        // 'fcm_mobile_push' => 1,
                        // 'fcm_web_push' => 1,
                    ];
                }

                if ($data['business_partner_id'] ) {
                    $target_reciver_partner = User::where('partner_id', $data['business_partner_id'])->first();
                    if($target_reciver_partner){
                        // dd($target_reciver_partner);
                        $notifications[] = [
                            'receiver_id' => $target_reciver_partner->id,
                            'sender_id' => $auth_user_id,
                            'calendar' => 1,
                            // 'sms' => 1,
                            // 'email' => 1,
                            // 'whatsapp' => 1,
                            // 'fcm_mobile_push' => 1,
                            // 'fcm_web_push' => 1,
                        ];
                    }
                }

                $admin_user = UserCompany::where('company_id', $data['company_id'])->whereHas('user', function ($user) {
                    return $user->where('is_company_admin', 1);
                })->first();

                if ($admin_user) {
                    $notifications[] = [
                        'receiver_id' => $admin_user->user_id,
                        'sender_id' => $auth_user_id,
                        'calendar' => 1,
                        // 'sms' => 1,
                        // 'email' => 1,
                        // 'whatsapp' => 1,
                        // 'fcm_mobile_push' => 1,
                        // 'fcm_web_push' => 1,
                    ];
                }
                // dd($date,$notifications,$data['vehicle_id']);
                Event::createEvent($table->id, $data['vehicle_id'], $vehicle->registration_no, 'planed_maintenance', $title, $description, $action_details3 = null, $data['company_id'], $date, $notifications);

            }
        }
    }
    public static function update_maintainence($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // Get the authenticated user
        $user = auth()->user();
        $company_id = auth()->user()->active_company() ?? null;
        $invoice_id = Invoice::where('id', $invoice->id)->where('company_id', $company_id)->update([
            'vehicle_id' => $data['vehicle_id'],
            'business_partner_id' => $data['business_partner_id'],
            'date' => $data['date'],
            'description' => $data['description'],
            'total_amount' => $data['total_amount'],
            'grand_total_amount' => $data['grand_total_amount'],
            'updated_by' => $data['updated_by'],

            'document_status' => $data['document_status'],

            // 'company_id' => $company_id,
            // 'document_no' => $document_no,
        ]);
        foreach ($data['row'] as $row) {
            if ($row['row_id']) {
                InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)->update([
                    // 'invoice_id' => $maintenance->id,
                    'is_checked' => $row['is_checked'] ?? null,
                    'activity_line_id' => $row['activity_id'] ?? null,
                    'is_service_charge' => $row['is_service_charge'],
                    'description' => $row['description'],
                    'quantity' => $row['quantity'],
                    'rate' => $row['rate'],
                    'line_amount' => $row['line_total'],
                ]);
                // if(isset($row['product_id'])){
                InvoiceLineProduct::where('invoice_line_id', $row['row_id'])
                // ->where('product_id','!=', $row['product_id'])
                    ->delete();
                // }
                $invoice_line_id = $row['row_id'];

                if ($data['document_status'] == 'completed') {
                    InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)
                        ->where('is_checked', 0)
                        ->delete();
                    continue;
                }
            } else {
                $invoice_line = InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'is_checked' => $row['is_checked'] ?? null,
                    'activity_line_id' => $row['activity_id'] ?? null,
                    'is_service_charge' => $row['is_service_charge'],
                    'description' => $row['description'],
                    'quantity' => $row['quantity'],
                    'rate' => $row['rate'],
                    'line_amount' => $row['line_total'],
                ]);
                $invoice_line_id = $invoice_line->id;
            }
            if (isset($row['product_id'])) {
                InvoiceLineProduct::firstOrCreate([
                    'invoice_line_id' => $invoice_line_id,
                    'product_id' => $row['product_id'],
                ]);
            }
            // foreach($row['product_id']??[] as $product_id){
            //     InvoiceLineProduct::firstOrCreate([
            //         'invoice_line_id' => $invoice_line_id,
            //         'product_id' => $product_id,
            //     ]);
            // }
        }
        $invoice_final_id = Invoice::where('id', $invoice->id)->where('company_id', $data['company_id'])
            ->first();
        // if ($data['document_status'] == 'completed') {
        //     // system accounts
        //     $debit_account = Account::where('company_id', $data['company_id'])->where('name', 'Maintenance Expense')->first();
        //     $credit_account = Account::where('company_id', $data['company_id'])->where('name', 'Accounts Payable')->first();
        //     $currency = User::current_currency();
        //     AccountTransaction::createTransaction($data['date'], $data['company_id'], $debit_account->id, $credit_account->id, $data['grand_total_amount'], $currency->id, $invoice_final_id->id, null, null, 51,business_partner_id:$data['business_partner_id']);
        //     self::createNextMaintenanceEvent($data, $data['updated_by']);
        // }

    }
    public static function update_maintainence_status($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);
        // Get the authenticated user
        $user = auth()->user();
        $company_id = auth()->user()->active_company() ?? null;
        $invoice_id = Invoice::where('id', $invoice->id)->where('company_id', $company_id)->update([
           
            'updated_by' => $data['updated_by'],
            'document_status' => $data['document_status'],
        ]);
        // foreach ($data['row'] as $row) {
        //     if ($row['row_id']) {
        //         InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)->update([
        //             // 'invoice_id' => $maintenance->id,
        //             'is_checked' => $row['is_checked'] ?? null,
        //             'activity_line_id' => $row['activity_id'] ?? null,
        //             'is_service_charge' => $row['is_service_charge'],
        //             'description' => $row['description'],
        //             'quantity' => $row['quantity'],
        //             'rate' => $row['rate'],
        //             'line_amount' => $row['line_total'],
        //         ]);
        //         // if(isset($row['product_id'])){
        //         InvoiceLineProduct::where('invoice_line_id', $row['row_id'])
        //         // ->where('product_id','!=', $row['product_id'])
        //             ->delete();
        //         // }
        //         $invoice_line_id = $row['row_id'];

        //         if ($data['document_status'] == 'completed') {
        //             InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)
        //                 ->where('is_checked', 0)
        //                 ->delete();
        //             continue;
        //         }
        //     } else {
        //         $invoice_line = InvoiceLine::create([
        //             'invoice_id' => $invoice->id,
        //             'is_checked' => $row['is_checked'] ?? null,
        //             'activity_line_id' => $row['activity_id'] ?? null,
        //             'is_service_charge' => $row['is_service_charge'],
        //             'description' => $row['description'],
        //             'quantity' => $row['quantity'],
        //             'rate' => $row['rate'],
        //             'line_amount' => $row['line_total'],
        //         ]);
        //         $invoice_line_id = $invoice_line->id;
        //     }
        //     if (isset($row['product_id'])) {
        //         InvoiceLineProduct::firstOrCreate([
        //             'invoice_line_id' => $invoice_line_id,
        //             'product_id' => $row['product_id'],
        //         ]);
        //     }
        //     // foreach($row['product_id']??[] as $product_id){
        //     //     InvoiceLineProduct::firstOrCreate([
        //     //         'invoice_line_id' => $invoice_line_id,
        //     //         'product_id' => $product_id,
        //     //     ]);
        //     // }
        // }
        $invoice_final_id = Invoice::where('id', $invoice->id)->where('company_id', $data['company_id'])
            ->first();
        if ($data['document_status'] == 'completed') {
            // system accounts
            $debit_account = Account::where('company_id', $data['company_id'])->where('name', 'Maintenance Expense')->first();
            $credit_account = Account::where('company_id', $data['company_id'])->where('name', 'Accounts Payable')->first();
            $currency = User::current_currency();
            AccountTransaction::createTransaction(now()->format('Y-m-d'), $data['company_id'], $debit_account->id, $credit_account->id,1, $invoice_final_id->grand_total_amount, $currency->id, $invoice_final_id->id, null, null, 51,business_partner_id:$invoice_final_id->business_partner_id);
            self::createNextMaintenanceEvent($data, $data['updated_by']);
        }

    }
    public static function update_toll_tax($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);
        // Get the authenticated user
        $user = auth()->user();
        $company_id = auth()->user()->active_company() ?? null;
        $inv = Invoice::where('id', $invoice->id)->where('company_id', $company_id)->update([
            'vehicle_id' => $data['vehicle_id'],
            'business_partner_id' => $data['business_partner_id'],
            'date' => $data['date'],
            'description' => $data['description'],
            'total_amount' => $data['total_amount'],
            'grand_total_amount' => $data['grand_total_amount'],
            'updated_by' => $data['updated_by'],

            'document_status' => $data['document_status'],

            // 'company_id' => $company_id,
            // 'document_no' => $document_no,
        ]);

        //    dd($inv);
        // Retrieve the updated record
        $toll_tax = Invoice::where('id', $invoice->id)
            ->where('company_id', $company_id)
            ->first();
        // dd($toll_tax);

        // Debug the updated record
        // dd($toll_tax);
        // dd($data['row']);
        foreach ($data['row'] as $row) {
            //   dd($row['picture_of_toll_tax']);
            if ($row['row_id']) {
                // Fetch the existing record
              $existing_invoice_line = InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)->first();

                // Initialize image URLs with existing values
                // $image_url = $existing_invoice_line->picture_of_toll_Tax ?? null;
                $image_url=$row['picture_of_toll_tax']?? null;

                // if ($row) {

                // if (isset($row['picture_of_toll_tax']) && is_object($row['picture_of_toll_tax'])) {
                //     $image = $row['picture_of_toll_tax'];
                //     $imageName = time() . '_' . $image->getClientOriginalName();
                //     $image->move(public_path('storage/resources_images/uploads'), $imageName);
                //     $image_url = 'resources_images/uploads/' . $imageName;
                // }
                if (isset($row['picture_of_toll_tax']) && is_object($row['picture_of_toll_tax'])) {
                    $image = $row['picture_of_toll_tax'];
                    $imageName = time() . '_' . $image->getClientOriginalName();

                    $company = Company::find($company_id);
                    // Define the company folder path
                    // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                    $companyFolderPath = $company->name . '/toll_tax_images/uploads';

                    // Check if the folder exists, if not create it
                    if (!Storage::exists($companyFolderPath)) {
                        Storage::makeDirectory($companyFolderPath); // Create the folder
                    }

                    // Store the image
                    $image->storeAs($companyFolderPath, $imageName, 'public');

                    // Construct the image URL
                    $image_url = $companyFolderPath . '/' . $imageName;
                }
                // dd($toll_tax);
                InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)->update([
                    // $invoice_line=InvoiceLine::where('invoice_id', $invoice->id)->update([

                    'invoice_id' => $toll_tax->id,
                    // 'is_checked' => $row['is_checked'] ?? null,
                    // 'activity_line_id' => $row['activity_id'] ?? null,
                    // 'is_service_charge' => $row['is_service_charge'],
                    // 'description' => $row['description'],
                    // 'quantity' => $row['quantity'] ?? null,
                    // 'rate' => $row['line_amount'],
                    'quantity' => 0,
                    'rate' => 0,
                    'line_amount' => $row['line_amount'],
                    'check_point' => $row['check_point'],
                    'picture_of_toll_tax' => $image_url,
                ]);
                //   $data=InvoiceLine::where('id',$invoice_line->id)->get();
                //  dd($data);
                // if(isset($row['product_id'])){
                // InvoiceLineProduct::where('invoice_line_id', $row['row_id'])
                // // ->where('product_id','!=', $row['product_id'])
                // ->delete();
                // }
                // $invoice_line_id = $row['row_id'];

                //     if ($data['document_status'] == 'completed') {
                //         InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)
                //             ->where('is_checked', 0)
                //             ->delete();
                //         continue;
                //     }
                // } else {
                //     $invoice_line = InvoiceLine::create([
                //         'invoice_id' => $invoice->id,
                //         'is_checked' => $row['is_checked'] ?? null,
                //         'activity_line_id' => $row['activity_id'] ?? null,
                //         'is_service_charge' => $row['is_service_charge'],
                //         'description' => $row['description'],
                //         'quantity' => $row['quantity'],
                //         'rate' => $row['rate'],
                //         'line_amount' => $row['line_total'],
                //     ]);
                //     $invoice_line_id = $invoice_line->id;
                // }
                // if (isset($row['product_id'])) {
                //     InvoiceLineProduct::firstOrCreate([
                //         'invoice_line_id' => $invoice_line_id,
                //         'product_id' => $row['product_id'],
                //     ]);
                // }
                // foreach($row['product_id']??[] as $product_id){
                //     InvoiceLineProduct::firstOrCreate([
                //         'invoice_line_id' => $invoice_line_id,
                //         'product_id' => $product_id,
                //     ]);
                // }
              }

              else{

                 $image_url=null;
                if (isset($row['picture_of_toll_tax']) && is_object($row['picture_of_toll_tax'])) {
                    $image = $row['picture_of_toll_tax'];
                    $imageName = time() . '_' . $image->getClientOriginalName();

                    $company = Company::find($company_id);
                    // Define the company folder path
                    // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                    $companyFolderPath = $company->name . '/toll_tax_images/uploads';

                    // Check if the folder exists, if not create it
                    if (!Storage::exists($companyFolderPath)) {
                        Storage::makeDirectory($companyFolderPath); // Create the folder
                    }

                    // Store the image
                    $image->storeAs($companyFolderPath, $imageName, 'public');

                    // Construct the image URL
                    $image_url = $companyFolderPath . '/' . $imageName;
                }
                InvoiceLine::create([
                    'invoice_id' => $toll_tax->id,
                    // 'is_checked' => $row['is_checked'] ?? null,
                    // 'activity_line_id' => $row['activity_id'] ?? null,
                    // 'is_service_charge' => $row['is_service_charge'],
                    // 'description' => $row['description'],
                    'quantity' => 0,
                    'rate' => 0,
                    'line_amount' => $row['line_amount'],
                    'check_point' => $row['check_point'],
                    'picture_of_toll_Tax' => $image_url ?? null,
                ]);


              }
            // $invoice_final_id = Invoice::where('id', $invoice->id)->where('company_id', $company_id)
            //     ->first();
            // if ($data['document_status'] == 'completed') {

            //     $debit_account = Account::where('company_id', $company_id)->where('name', 'Maintenance Expense')->first();
            //     $credit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Payable')->first();
            //     $currency = User::current_currency();
            //     AccountTransaction::createTransaction($data['date'], $company_id, $debit_account->id, $credit_account->id, $data['grand_total_amount'], $currency->id, $invoice_final_id->id, null, null, 51);
            // }
        }
        // dd($row['row_id']);

    }
    public static function update_fuel_expense($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);
        // Get the authenticated user
        $user = auth()->user();
        $company_id = auth()->user()->active_company() ?? null;
        Invoice::where('id', $invoice->id)->where('company_id', $company_id)->update([
            'vehicle_id' => $data['vehicle_id'],
            'business_partner_id' => $data['business_partner_id'],
            'date' => $data['date'],
            'description' => $data['description'],
            'total_amount' => $data['total_amount'],
            'grand_total_amount' => $data['grand_total_amount'],
            'updated_by' => $data['updated_by'],

            'document_status' => $data['document_status'],

            // 'company_id' => $company_id,
            // 'document_no' => $document_no,
        ]);

        // Retrieve the updated record
        $fuel_expense = Invoice::where('id', $invoice->id)
            ->where('company_id', $company_id)
            ->first();

        // Debug the updated record
        // dd($toll_tax);
        // dd($toll_tax);
        foreach ($data['row'] as $row) {

            if ($row['row_id']) {

               // Fetch the existing record
                $existing_invoice_line = InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)->first();

                // Initialize image URLs with existing values
                $meter_reading_image_url = $existing_invoice_line->meter_reading_image ?? null;
                $bill_image_url = $existing_invoice_line->bill_image ?? null;
                $petrol_machine_image_url = $existing_invoice_line->petrol_machine_image ?? null;
                $driver_image_url = $existing_invoice_line->driver_selfie ?? null;

                // if ($row) {

                // if (isset($row['picture_of_toll_tax']) && is_object($row['picture_of_toll_tax'])) {
                //     $image = $row['picture_of_toll_tax'];
                //     $imageName = time() . '_' . $image->getClientOriginalName();
                //     $image->move(public_path('storage/resources_images/uploads'), $imageName);
                //     $image_url = 'resources_images/uploads/' . $imageName;
                // }
                if (isset($row['meter_reading_image']) && is_object($row['meter_reading_image'])) {
                    $image = $row['meter_reading_image'];
                    $imageName = time() . '_' . $image->getClientOriginalName();

                    $company = Company::find($company_id);
                    // Define the company folder path
                    // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                    $companyFolderPath = $company->name . '/meter_reading_images/uploads';

                    // Check if the folder exists, if not create it
                    if (!Storage::exists($companyFolderPath)) {
                        Storage::makeDirectory($companyFolderPath); // Create the folder
                    }

                    // Store the image
                    $image->storeAs($companyFolderPath, $imageName, 'public');

                    // Construct the image URL
                    $meter_reading_image_url = $companyFolderPath . '/' . $imageName;
                    // dd($meter_reading_image_url);
                }
                if (isset($row['bill_image']) && is_object($row['bill_image'])) {
                    $image = $row['bill_image'];
                    $imageName = time() . '_' . $image->getClientOriginalName();

                    $company = Company::find($company_id);
                    // Define the company folder path
                    // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                    $companyFolderPath = $company->name . '/bill_images/uploads';

                    // Check if the folder exists, if not create it
                    if (!Storage::exists($companyFolderPath)) {
                        Storage::makeDirectory($companyFolderPath); // Create the folder
                    }

                    // Store the image
                    $image->storeAs($companyFolderPath, $imageName, 'public');

                    // Construct the image URL
                    $bill_image_url = $companyFolderPath . '/' . $imageName;
                }
                if (isset($row['petrol_machine_image']) && is_object($row['petrol_machine_image'])) {
                    $image = $row['petrol_machine_image'];
                    $imageName = time() . '_' . $image->getClientOriginalName();

                    $company = Company::find($company_id);
                    // Define the company folder path
                    // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                    $companyFolderPath = $company->name . '/petrol_machine_images/uploads';

                    // Check if the folder exists, if not create it
                    if (!Storage::exists($companyFolderPath)) {
                        Storage::makeDirectory($companyFolderPath); // Create the folder
                    }

                    // Store the image
                    $image->storeAs($companyFolderPath, $imageName, 'public');

                    // Construct the image URL
                    $petrol_machine_image_url = $companyFolderPath . '/' . $imageName;
                }
                if (isset($row['driver_selfie']) && is_object($row['driver_selfie'])) {
                    $image = $row['driver_selfie'];
                    $imageName = time() . '_' . $image->getClientOriginalName();

                    $company = Company::find($company_id);
                    // Define the company folder path
                    // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                    $companyFolderPath = $company->name . '/driver_images/uploads';

                    // Check if the folder exists, if not create it
                    if (!Storage::exists($companyFolderPath)) {
                        Storage::makeDirectory($companyFolderPath); // Create the folder
                    }

                    // Store the image
                    $image->storeAs($companyFolderPath, $imageName, 'public');

                    // Construct the image URL
                    $driver_image_url = $companyFolderPath . '/' . $imageName;
                }
                // dd($toll_tax);
                InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)->update([
                    // InvoiceLine::where('invoice_id', $invoice->id)->update([

                    'invoice_id' => $fuel_expense->id,
                    // 'is_checked' => $row['is_checked'] ?? null,
                    // 'activity_line_id' => $row['activity_id'] ?? null,
                    // 'is_service_charge' => $row['is_service_charge'],
                    // 'description' => $row['description'],
                    // 'quantity' => $row['quantity'] ?? null,
                    // 'rate' => $row['line_amount'],
                    // 'line_amount' => $row['line_amount'],
                    // 'check_point' => $row['check_point'],
                    // 'picture_of_toll_Tax' => $image_url
                    'quantity' => 0,
                    'rate' => 0,
                    'line_amount' => $row['line_amount'],
                    // 'check_point' => $row['check_point'],
                    // 'picture_of_toll_Tax' => $image_url
                    'meter_reading_km' => $row['meter_reading_km'],
                    'fuel_quantity_liters' => $row['fuel_quantity_liters'],
                    'total_fuel_cost' => $row['total_fuel_cost'],
                    'meter_reading_image' => $meter_reading_image_url,
                    'bill_image' => $bill_image_url,
                    'petrol_machine_image' => $petrol_machine_image_url,
                    'driver_selfie' => $driver_image_url,
                ]);
                // if(isset($row['product_id'])){
                // InvoiceLineProduct::where('invoice_line_id', $row['row_id'])
                // // ->where('product_id','!=', $row['product_id'])
                // ->delete();
                // }
                // $invoice_line_id = $row['row_id'];

                //     if ($data['document_status'] == 'completed') {
                //         InvoiceLine::where('id', $row['row_id'])->where('invoice_id', $invoice->id)
                //             ->where('is_checked', 0)
                //             ->delete();
                //         continue;
                //     }
                // } else {
                //     $invoice_line = InvoiceLine::create([
                //         'invoice_id' => $invoice->id,
                //         'is_checked' => $row['is_checked'] ?? null,
                //         'activity_line_id' => $row['activity_id'] ?? null,
                //         'is_service_charge' => $row['is_service_charge'],
                //         'description' => $row['description'],
                //         'quantity' => $row['quantity'],
                //         'rate' => $row['rate'],
                //         'line_amount' => $row['line_total'],
                //     ]);
                //     $invoice_line_id = $invoice_line->id;
                // }
                // if (isset($row['product_id'])) {
                //     InvoiceLineProduct::firstOrCreate([
                //         'invoice_line_id' => $invoice_line_id,
                //         'product_id' => $row['product_id'],
                //     ]);
                // }
                // foreach($row['product_id']??[] as $product_id){
                //     InvoiceLineProduct::firstOrCreate([
                //         'invoice_line_id' => $invoice_line_id,
                //         'product_id' => $product_id,
                //     ]);
                // }
            }
            else{
                $meter_reading_image_url = null;
                $bill_image_url = null;
                $petrol_machine_image_url = null;
                $driver_image_url = null;
                if (isset($row['meter_reading_image']) && is_object($row['meter_reading_image'])) {
                $image = $row['meter_reading_image'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/meter_reading_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $meter_reading_image_url = $companyFolderPath . '/' . $imageName;
                }
                if (isset($row['bill_image']) && is_object($row['bill_image'])) {
                $image = $row['bill_image'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/bill_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $bill_image_url = $companyFolderPath . '/' . $imageName;
                 }
                if (isset($row['petrol_machine_image']) && is_object($row['petrol_machine_image'])) {
                $image = $row['petrol_machine_image'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/petrol_machine_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $petrol_machine_image_url = $companyFolderPath . '/' . $imageName;
                 }
                if (isset($row['driver_selfie']) && is_object($row['driver_selfie'])) {
                $image = $row['driver_selfie'];
                $imageName = time() . '_' . $image->getClientOriginalName();

                $company = Company::find($company_id);
                // Define the company folder path
                // $companyFolderPath = 'resources_images/uploads/' . $company->name;
                $companyFolderPath = $company->name . '/driver_images/uploads';

                // Check if the folder exists, if not create it
                if (!Storage::exists($companyFolderPath)) {
                    Storage::makeDirectory($companyFolderPath); // Create the folder
                }

                // Store the image
                $image->storeAs($companyFolderPath, $imageName, 'public');

                // Construct the image URL
                $driver_image_url = $companyFolderPath . '/' . $imageName;
                 }
                 InvoiceLine::create([
                'invoice_id' => $fuel_expense->id,
                // 'is_checked' => $row['is_checked'] ?? null,
                // 'activity_line_id' => $row['activity_id'] ?? null,
                // 'is_service_charge' => $row['is_service_charge'],
                // 'description' => $row['description'],
                'quantity' => 0,
                'rate' => 0,
                'line_amount' => $row['line_amount'],
                // 'check_point' => $row['check_point'],
                // 'picture_of_toll_Tax' => $image_url
                'meter_reading_km' => $row['meter_reading_km'],
                'fuel_quantity_liters' => $row['fuel_quantity_liters'],
                'total_fuel_cost' => $row['total_fuel_cost'],
                'meter_reading_image' => $meter_reading_image_url ?? null,
                'bill_image' => $bill_image_url ?? null,
                'petrol_machine_image' => $petrol_machine_image_url ?? null,
                'driver_selfie' => $driver_image_url ?? null,

                 ]);
             }
            // $invoice_final_id = Invoice::where('id', $invoice->id)->where('company_id', $company_id)
            //     ->first();
            // if ($data['document_status'] == 'completed') {

            //     $debit_account = Account::where('company_id', $company_id)->where('name', 'Fuel Expenses')->first();
            //     $credit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Payable')->first();
            //     $currency = User::current_currency();
            //     AccountTransaction::createTransaction($data['date'], $company_id, $debit_account->id, $credit_account->id, $data['grand_total_amount'], $currency->id, $invoice_final_id->id, null, null, 51);
            // }
        }
    }


    public static function store_purchase_invoice($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }
        // dd($payload);

        $invoice = Invoice::create([
            'document_no'=>$document_no,
            'description'=>$data['description'],
            'document_type_id'=>$data['document_type_id'],
            // 'date'=>$data['date_ordered'],
            'date_invoiced'=>$data['date_invoiced'],
            // 'account_date'=>$data['account_date'],
            'business_partner_id'=>$data['business_partner_id'],
            'partner_location_id'=>$data['partner_location_id'],
            'material_inout_id'=>$data['material_inout_id'],
            'order_id'=>$data['order_id'],
            'price_list_id'=>$data['price_list_id'],
            // 'payment_term'=>$data['payment_term'],
            // 'payment_rule'=>$data['payment_rule'],
            'discount_printed'=>$data['discount_printed'] ?? 0,
            'currency'=>$data['currency'] ?? null,
            'document_action'=>$data['document_action'],
            'document_status'=>$data['document_status'],
            'user_id'=>auth()->user()->id,
            'total_amount'=>$data['total_amount'],
            'grand_total_amount'=>$data['total_amount'],
            'created_by'=> $data['created_by'],
            'company_id'=> auth()->user()->active_company(),
            'client_id'=>auth()->user()->active_company_details()->client_id,
        ]);

        foreach($data['rows']??[] as $row){
            $invoice_line = InvoiceLine::create([
                'invoice_id'=> $invoice->id,
                'order_detail_id'=> $row['order_detail_line_id'],
                'material_inout_line_id'=> $row['material_inout_line_id'],
                'seq_no'=> $row['seq_no'],
                'product_id'=>$row['product_id'],
                'uom_id'=>$row['unit'],
                'quantity'=>$row['quantity'],
                // 'quantity_invoiced'=>$row['quantity_invoiced'],
                'rate'=>$row['rate'],
                // 'unit_rate'=>$row['unit_rate'],
                // 'list_rate'=>$row['list_rate'],
                'tax_id'=>$row['tax'],
                'tax_amount'=>$row['tax_amount'],
                'line_amount'=>$row['line_amount'],
                'total_line_amount'=>$row['total_line_amount'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,
            ]);
            // if($data['document_status']!='draft' && $row['order_detail_line_id']){
            //     OrderDetail::where('id',$row['order_detail_line_id'])->update(['invoiced_qty'=>$row['quantity']]);
            //     MaterialInoutLine ::where('id',$row['material_inout_line_id'])->update(['status'=>'completed']);
            // }
            if($data['document_status']!='draft' && $row['order_detail_line_id']){
                $order_detail = OrderDetail::where('id',$row['order_detail_line_id'])->first();
                $order_detail->update(['invoiced_qty' => DB::raw("invoiced_qty + {$row['quantity']}")]);
                $order_detail->refresh();
                MaterialInoutLine ::where('id',$row['material_inout_line_id'])->update(['status'=>'completed']);

                 // hitting account transactions
                 $debit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Product Inventory Clearing')->first();
                 $credit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Accounts Payable')->first();
                 $currency = User::current_currency();
                 AccountTransaction::createTransaction($data['date_invoiced'], auth()->user()->active_company(), $debit_account->id, $credit_account->id,$row['quantity'], (float)$row['total_line_amount'], $currency->id, $invoice->id, $invoice_line->id, null, 51,business_partner_id:$data['business_partner_id']);
                 M_MatchPo::create([
                    'product_id'=>$row['product_id'],
                    'quantity'=>$row['quantity'],
                    'po_line_id'=>$row['order_detail_line_id'],
                    'material_inout_line_id'=>$row['material_inout_line_id'],
                    'document_type_id'=>12,
                    'pi_line_id'=>$invoice_line->id,
                    'quantity'=>$row['quantity'],
                    'transaction_date'=>Carbon::now(),
                    'company_id'=>auth()->user()->active_company(),
                    'client_id'=>auth()->user()->active_company_details()->client_id,
                    'created_by'=>auth()->user()->id,
                 ]);
                 // end here
                 
            }
            
        }
    }
    public static function update_purchase_invoice($payload)
    {
        foreach ($payload as $key => $val) {
            $$key = $val;
        }


        $purchaseInvoice->update([
            'description'=>$data['description'],
            'document_type_id'=>$data['document_type_id'],
            // 'date'=>$data['date_ordered'],
            'date_invoiced'=>$data['date_invoiced'],
            // 'account_date'=>$data['account_date'],
            'business_partner_id'=>$data['business_partner_id'],
            'partner_location_id'=>$data['partner_location_id'],
            'material_inout_id'=>$data['material_inout_id'],
            'order_id'=>$data['order_id'],
            'price_list_id'=>$data['price_list_id'],
            // 'payment_term'=>$data['payment_term'],
            // 'payment_rule'=>$data['payment_rule'],
            'discount_printed'=>$data['discount_printed'],
            'currency'=>$data['currency'],
            'total_amount'=>$data['total_amount'],
            'grand_total_amount'=>$data['total_amount'],
            'document_action'=>$data['document_action'],
            'document_status'=>$data['document_status'],
            'updated_by'=> $data['updated_by']
        ]);
        // ActivityLine::where('id', $row['row_id'])->where('activity_id',$activity->id)->delete();

        foreach($data['rows']??[] as $row){
            if($row['row_id']){
                $invoice_line = InvoiceLine::where('id', $row['row_id'])->where('invoice_id',$purchaseInvoice->id)->update([
                    'order_detail_line_id'=> $row['order_detail_line_id'],
                    'material_inout_line_id'=> $row['material_inout_line_id'],
                    'seq_no'=> $row['seq_no'],
                    'product_id'=>$row['product_id'],
                    'uom_id'=>$row['unit'],
                    'quantity'=>$row['quantity'],
                    // 'quantity_invoiced'=>$row['quantity_invoiced'],
                    'rate'=>$row['rate'],
                    // 'unit_rate'=>$row['unit_rate'],
                    // 'list_rate'=>$row['list_rate'],
                    'tax_id'=>$row['tax'],
                    'tax_amount'=>$row['tax_amount'],
                    'line_amount'=>$row['line_amount'],
                    'total_line_amount'=>$row['total_line_amount'],
                ]);
                if($data['document_status']!='draft' && $row['order_detail_line_id']){
                    $order_detail = OrderDetail::where('id',$row['order_detail_line_id'])->first();
                    $order_detail->update(['invoiced_qty' => DB::raw("invoiced_qty + {$row['quantity']}")]);
                    $order_detail->refresh();
                    MaterialInoutLine ::where('id',$row['material_inout_line_id'])->update(['status'=>'completed']);
                    // hitting account transactions
                    $debit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Product Inventory Clearing')->first();
                    $credit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Accounts Payable')->first();
                    $currency = User::current_currency();
                    AccountTransaction::createTransaction($data['date_invoiced'], auth()->user()->active_company(), $debit_account->id, $credit_account->id,$row['quantity'], (float)$row['total_line_amount'], $currency->id, $purchaseInvoice->id, $invoice_line->id, null, 51,business_partner_id:$data['business_partner_id']);
                    // end here
                    
                }
            }
            else{

                $invoice_line= InvoiceLine::create([
                'invoice_id'=> $invoice->id,
                'order_detail_line_id'=> $row['order_detail_line_id'],
                'material_inout_line_id'=> $row['material_inout_line_id'],
                'seq_no'=> $row['seq_no'],
                'product_id'=>$row['product_id'],
                'uom_id'=>$row['unit'],
                'quantity'=>$row['quantity'],
                // 'quantity_invoiced'=>$row['quantity_invoiced'],
                'rate'=>$row['rate'],
                // 'unit_rate'=>$row['unit_rate'],
                // 'list_rate'=>$row['list_rate'],
                'tax_id'=>$row['tax'],
                'tax_amount'=>$row['tax_amount'],
                'line_amount'=>$row['line_amount'],
                'total_line_amount'=>$row['total_line_amount'],
                'company_id'=> auth()->user()->active_company(),
                'client_id'=>auth()->user()->active_company_details()->client_id,

                ]);
                if($data['document_status']!='draft' && $row['order_detail_line_id']){
                    $order_detail = OrderDetail::where('id',$row['order_detail_line_id'])->first();
                    $order_detail->update(['invoiced_qty' => DB::raw("invoiced_qty + {$row['quantity']}")]);
                    $order_detail->refresh();
                    MaterialInoutLine ::where('id',$row['material_inout_line_id'])->update(['status'=>'completed']);
                    // hitting account transactions
                    $debit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Product Inventory Clearing')->first();
                    $credit_account = Account::where('company_id', auth()->user()->active_company())->where('name', 'Accounts Payable')->first();
                    $currency = User::current_currency();
                    AccountTransaction::createTransaction($data['date_invoiced'], auth()->user()->active_company(), $debit_account->id, $credit_account->id,$row['quantity'], (float)$row['total_line_amount'], $currency->id, $purchaseInvoice->id, $invoice_line->id, null, 51,business_partner_id:$data['business_partner_id']);
                    // end here
    
                }
            }

        }
    }

    public static function generate_no($company_id, $document_type)
    {
        $last = self::where('company_id', $company_id)
        //
            ->where('document_type_id', $document_type->id)
        //
            ->orderByDesc('id')->first();
        if ($last) {
            $document_no = intval(last(explode('-', $last->document_no))) + 1;

        } else {
            $document_no = 1;
        }
        // $prefix =  self::document_no_prefix($document_type);

        return $document_type->code . str_pad($document_no, 4, "0", STR_PAD_LEFT);
    }
}
