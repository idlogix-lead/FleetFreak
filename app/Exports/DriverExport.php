<?php

namespace App\Exports;

use App\Models\Partner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DriverExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Partner::where('actor_id',5)->get();
    }
    public function map($partner_driver): array
    {
        return[
              $partner_driver->id,
              $partner_driver->name,
              $partner_driver->actor_id,
              $partner_driver->business_partner_id,
              $partner_driver->email,
              $partner_driver->passport,
              $partner_driver->phone_No,
              $partner_driver->whatsapp_no,
              $partner_driver->cnic,
              $partner_driver->age,
              $partner_driver->experience,
              $partner_driver->akama,
              $partner_driver->address1,
              $partner_driver->country,
              $partner_driver->city,
              $partner_driver->created_by,
              $partner_driver->updated_by,
              $partner_driver->created_at,
              $partner_driver->updated_at
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
            ('age'),
            ('experience'),
            ('akama'),
            ('company_name'),
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
