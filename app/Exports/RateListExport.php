<?php

namespace App\Exports;

use App\Models\RateList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RateListExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Same rows as RateListController::index.
        return RateList::checkGlobal(8)->get();
    }

    public function map($ratelist): array
    {
        return[
              $ratelist->id,
              $ratelist->name,
              $ratelist->estimated_time,
              $ratelist->price,
              $ratelist->description,
              $ratelist->vehicle_class->name,
              $ratelist->route->name,
              $ratelist->created_at,
              $ratelist->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            ('#'),
            ('name'),
            ('estimated_time'),
            ('price'),
            ('description'),
            ('vehicle_class'),
            ('route'),
            ('created_at'),
            ('updated_at')


        ];
    }
}
