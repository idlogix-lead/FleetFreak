<?php

namespace App\Exports;

use App\Models\Partner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Same rows as CustomerController::index: agents only export their own
        // customers. Organization scoping comes from the Partner global scope.
        if (auth()->user()->actor_id == 4) {
            return Partner::checkGlobal(12)->whereIn('actor_id', [6])
                ->where('business_partner_id', auth()->user()->partner_id)->get();
        }

        return Partner::checkGlobal(12)->whereIn('actor_id', [6, 8])->get();
    }
    public function map($partner_customer): array
    {
        return[
              $partner_customer->id,
              $partner_customer->name,
              $partner_customer->actor_id,
              $partner_customer->business_partner_id,
              $partner_customer->email,
              $partner_customer->passport,
              $partner_customer->phone_No,
              $partner_customer->whatsapp_no,
              $partner_customer->cnic,
              $partner_customer->address1,
              $partner_customer->country,
              $partner_customer->city,
              $partner_customer->created_by,
              $partner_customer->updated_by,
              $partner_customer->created_at,
              $partner_customer->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            ('#'),
            ('name'),
            ('actor_id'),
            ('business_partner_id'),
            ('email'),
            ('passport'),
            ('phone_No'),
            ('whatsapp_no'),
            ('cnic'),
            ('address1'),
            ('country'),
            ('city'),
            ('created_by'),
            ('updated_by'),
            ('created_at'),
            ('updated_at')
            


        ];
    }
}
