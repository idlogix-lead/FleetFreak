<?php

namespace App\Exports;

use App\Models\Partner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AgentExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Same rows as BusinessAgentController::index (approved agents only).
        return Partner::checkGlobal(13)->where('actor_id', 4)
            ->whereHas('users', fn ($q) => $q->where('permission', 1))->get();
    }
    public function map($partner_agent): array
    {
        return[
              $partner_agent->id,
              $partner_agent->name,
              $partner_agent->actor_id,
              $partner_agent->business_partner_id,
              $partner_agent->email,
              $partner_agent->passport,
              $partner_agent->phone_No,
              $partner_agent->whatsapp_no,
              $partner_agent->cnic,
              $partner_agent->company_name,
              $partner_agent->address1,
              $partner_agent->country,
              $partner_agent->city,
              $partner_agent->created_by,
              $partner_agent->updated_by,
              $partner_agent->created_at,
              $partner_agent->updated_at
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
