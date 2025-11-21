<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends BaseModel
{
    use HasFactory;
    protected $guarded = [];
    public function Account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'account_id', 'id');
    }

    public static function createTransaction($transaction_date, $company_id, $debit_account_id, $credit_account_id,$quantity=1, $amount, $currency_id, $record_id=null, $line_id=null, $description=null,$table_id,$business_partner_id=null)
    {
        // Create the debit transaction
        self::create([
            'transaction_date' => $transaction_date,
            'company_id' => $company_id,
            'account_id' => $debit_account_id,
            'quantity' => $quantity ?? 1,
            'debit' => $amount,
            'credit' => 0,
            'currency_id' => $currency_id,
            'record_id' => $record_id?? null,
            'line_id' => $line_id??null,
            'description' => $description,
            'table_id' => $table_id??null,
            'b_partner_id' => $business_partner_id??null,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        // Create the credit transaction
        self::create([
            'transaction_date' => $transaction_date,
            'company_id' => $company_id,
            'account_id' => $credit_account_id,
            'quantity' => $quantity ?? 1,
            'debit' => 0,
            'credit' => $amount,
            'currency_id' => $currency_id,
            'record_id' => $record_id ?? null,
            'line_id' => $line_id??null,
            'description' => $description,
            'table_id' => $table_id??null,
            'b_partner_id' => $business_partner_id??null,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id

        ]);
    }
}
