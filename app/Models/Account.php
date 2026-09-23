<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AccountController;
/**
 * Class Account
 *
 * @property $id
 * @property $name
 * @property $code
 * @property $description
 * @property $is_active
 * @property $is_summary
 * @property $company_id
 * @property $account_type_id
 * @property $account_subtype_id
 * @property $created_by
 * @property $updated_by
 * @property $created_at
 * @property $updated_at
 *
 * @property AccountType $accountType
 * @property AccountType $accountType
 * @property Company $company
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Account extends BaseModel
{
    use BelongsToOrganization;

    static $rules = [
			'name' => 'required|string',
			'code' => 'required|string',
			'description' => 'string',
			'is_active' => 'required',
			'is_summary' => 'required',
			'company_id' => 'required',
			'account_type_id' => 'required',
			'account_subtype_id' => 'required',
    ];

    protected $perPage = 10;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'description', 'is_active', 'is_summary',
        'company_id', 'account_type_id', 'account_subtype_id',
        'created_by', 'updated_by', 'is_system',
        'created_at', 'updated_at',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountSubType()
    {
        return $this->belongsTo(\App\Models\AccountType::class, 'account_subtype_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountType()
    {
        return $this->belongsTo(\App\Models\AccountType::class, 'account_type_id', 'id');
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

    public static function dropdown($ignore = [], $is_summary = true, $parent_id = null){
        return self::when($is_summary, function($query){
            // return $query->whereNull('parent_id');
            return $query->where('is_summary',1);
        })
        ->when(!$is_summary, function($query){
            // return $query->whereNull('parent_id');
            return $query->where('is_summary',0);
        })
        ->when($parent_id, function($query) use($parent_id){
            return $query->where('parent_id', $parent_id);
        })
        ->where('is_active', 1)
        ->whereNotIn('id', $ignore)
        ->get();
    }


    public static function defaultAccounts($company_id,$user_id){
        // $comp_id = Company::where('id', $company_id)->exists();
        // if ($comp_id) {
        //     return;
        // }

        $accountType1 = AccountType::whereNull('parent_id')->where('name', 'Asset')->first();
        $accountType2 = AccountType::whereNull('parent_id')->where('name', 'Liability')->first();
        $accountType3 = AccountType::whereNull('parent_id')->where('name', 'Revenue')->first();
        $accountType4 = AccountType::whereNull('parent_id')->where('name', 'Expense')->first();
        // Fetch subtypes dynamically
        $subtypes1 = $accountType1->subTypes;
        $subtypes2 = $accountType2->subTypes;
        $subtypes3 = $accountType3->subTypes;
        $subtypes4 = $accountType4->subTypes;
        // dd($accountType1->id,$subtypes1->where('name', 'Current Assets')->first()->id);



        $defaultAccounts = [
            [
                'name' => 'Accounts Receivable',
                'code' => $accountType1->code.'-'.$subtypes1->where('name', 'Current Assets')->first()->code,
                'description' => 'Money owed by customers',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType1->id,
                'account_subtype_id' => $subtypes1->where('name', 'Current Assets')->first()->id ?? null
            ],
            [
                'name' => 'Cash Account',
                'code' => $accountType1->code.'-'.$subtypes1->where('name', 'Current Assets')->first()->code,
                'description' => 'Primary cash account',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType1->id,
                'account_subtype_id' => $subtypes1->where('name', 'Current Assets')->first()->id ?? null
            ],
            [
                'name' => 'Bank Account',
                'code' => $accountType1->code.'-'.$subtypes1->where('name', 'Current Assets')->first()->code,
                'description' => 'Main bank account',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType1->id,
                'account_subtype_id' => $subtypes1->where('name', 'Current Assets')->first()->id ?? null
            ],
            [
                'name' => 'Product Stock',
                'code' => $accountType1->code.'-'.$subtypes1->where('name', 'Current Assets')->first()->code,
                'description' => 'product stock account',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType1->id,
                'account_subtype_id' => $subtypes1->where('name', 'Current Assets')->first()->id ?? null
            ],
            [
                'name' => 'Product Inventory Clearing',
                'code' => $accountType1->code.'-'.$subtypes1->where('name', 'Current Assets')->first()->code,
                'description' => 'product inventory clearing account',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType1->id,
                'account_subtype_id' => $subtypes1->where('name', 'Current Assets')->first()->id ?? null
            ],
            [
                'name' => 'Accounts Payable',
                'code' => $accountType2->code.'-'.$subtypes2->where('name', 'Current Liability')->first()->code,
                'description' => 'Money owed to suppliers',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType2->id,
                'account_subtype_id' => $subtypes2->where('name', 'Current Liability')->first()->id ?? null
            ],
            [
                'name' => 'Not Invoiced Receipt',
                'code' => $accountType2->code.'-'.$subtypes2->where('name', 'Current Liability')->first()->code,
                'description' => 'not invoiced receipt description',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType2->id,
                'account_subtype_id' => $subtypes2->where('name', 'Current Liability')->first()->id ?? null
            ],
            [
                'name' => 'Ride Revenue',
                'code' => $accountType3->code.'-'.$subtypes3->where('name', 'Revenue')->first()->code,
                'description' => 'Income from rides',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType3->id,
                'account_subtype_id' => $subtypes3->where('name', 'Revenue')->first()->id ?? null
            ],
            [
                'name' => 'Other Income',
                'code' => $accountType3->code.'-'.$subtypes3->where('name', 'Other Revenue')->first()->code,
                'description' => 'Miscellaneous income',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType3->id,
                'account_subtype_id' => $subtypes3->where('name', 'Other Revenue')->first()->id ?? null
            ],
            [
                'name' => 'Fuel Expenses',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Cost')->first()->code,
                'description' => 'Expenses for fuel',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Cost')->first()->id ?? null
            ],
            [
                'name' => 'Maintenance Expense',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Cost')->first()->code,
                'description' => 'Vehicle maintenance costs',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Cost')->first()->id ?? null
            ],
            [
                'name' => 'Service Expense',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Cost')->first()->code,
                'description' => 'Service costs',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Cost')->first()->id ?? null
            ],
            [
                'name' => 'Marketing Expense',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Sales Expense')->first()->code,
                'description' => 'Marketing and promotions',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Sales Expense')->first()->id ?? null
            ],
            [
                'name' => 'Sales Expense',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Sales Expense')->first()->code,
                'description' => 'Sales-related expenses',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Sales Expense')->first()->id ?? null
            ],
            [
                'name' => 'Admin Expense',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Admin Expense')->first()->code,
                'description' => 'Administrative expenses',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Admin Expense')->first()->id ?? null
            ],
            [
                'name' => 'Entertainment Expense',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Admin Expense')->first()->code,
                'description' => 'Entertainment costs',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Admin Expense')->first()->id ?? null
            ],
            [
                'name' => 'Rent Expense',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Admin Expense')->first()->code,
                'description' => 'Costs for rent',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Admin Expense')->first()->id ?? null
            ],
            [
                'name' => 'Electricity Expense',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Admin Expense')->first()->code,
                'description' => 'Electricity costs',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Admin Expense')->first()->id ?? null
            ],
            [
                'name' => 'Internet Charges',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Admin Expense')->first()->code,
                'description' => 'Internet service expenses',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Admin Expense')->first()->id ?? null
            ],
            [
                'name' => 'Toll Expenses',
                'code' => $accountType4->code.'-'.$subtypes4->where('name', 'Cost')->first()->code,
                'description' => 'Tolls expenses',
                'is_active' => true,
                'is_summary' => false,
                'account_type_id' => $accountType4->id,
                'account_subtype_id' => $subtypes4->where('name', 'Cost')->first()->id ?? null
            ]
        ];

         // Loop through each account details and create an entry in the database
        foreach ($defaultAccounts as $accountData) {
            $code = AccountController::get_code_no($accountData['account_type_id'],$accountData['account_subtype_id'],$company_id);

            $accountData['code']=$accountData['code'].'-'.$code;
            
            Account::firstOrCreate(
                [
                    'company_id' => $company_id,
                    'name' => $accountData['name'], // Ensure it's unique for this company
                ],
                array_merge($accountData, [
                    'company_id' => $company_id,
                    'created_by' => $user_id,
                    'is_system' => true
                ])
            );
        }


    }
    public static function dropdownAccount(){
        return self::where('company_id',auth()->user()->active_company())->get();
    }

}
