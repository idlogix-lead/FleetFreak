<?php

namespace App\Exports;

use App\Models\VehicleCompany;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehicleCompanyExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return VehicleCompany::all();
    }
    public function map($company): array
    {
        return[
              $company->id,
              $company->name,
              $company->description,
              $company->created_by,
              $company->updated_by,
              $company->created_at,
              $company->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            ('#'),
            ('name'),
            ('description'),
            ('created_by'),
            ('updated_by'),
            ('created_at'),
            ('updated_at')


        ];
    }
}
