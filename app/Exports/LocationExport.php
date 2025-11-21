<?php

namespace App\Exports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LocationExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Location::all();
    }
    public function map($location): array
    {
        return[
              $location->id,
              $location->name,
              $location->description,
              $location->created_by,
              $location->updated_by,
              $location->created_at,
              $location->updated_at
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
