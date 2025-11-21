<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingRentalInvoices extends BaseModel
{
    use HasFactory;

    public static function store_pending_invoices($payload)
    {
        // $company_id = auth()->user()->companies;
        // dd($company_id);
        $company_id = auth()->user()->active_company();

        foreach ($payload as $key => $val) {
            $$key = $val;
        }

        foreach ($pending_invoices_data['order_detail_no'] as $key => $order_detail_no) {

            OrderDetail::where('id', $order_detail_no)->update(['status' => $pending_invoices_data['action']]);
            $order_detail = OrderDetail::where('id', $order_detail_no)->first();

            // Create the order detail
            // dd($order_detail);
            $debit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Receivable')->first();
            $credit_account = Account::where('company_id', $company_id)->where('name', 'Ride Revenue')->first();
            $currency = User::current_currency();
            AccountTransaction::createTransaction($order_detail->date, $company_id, $debit_account->id, $credit_account->id,1, $order_detail->rate, $currency->id, $pending_invoices_data['order_id'][$key], $order_detail_no, null, 21,business_partner_id:$order_detail->order->business_partner_id);

        }
        // $debit_account = Account::where('company_id', $company_id)->where('name', 'Accounts Receivable')->first();
        // $credit_account = Account::where('company_id', $company_id)->where('name', 'Ride Revenue')->first();
        // $currency = User::current_currency();
        // AccountTransaction::createTransaction($order_detail->date, $company_id, $debit_account->id, $credit_account->id, $payment_window_data['total_amount'], $currency->id, $payment_window_data['order_id'][$key], null, null, 21);


    }
}
