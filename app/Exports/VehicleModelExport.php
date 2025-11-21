<?php

namespace App\Exports;

use App\Models\VehicleModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehicleModelExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return VehicleModel::all();
    }
    public function map($model): array
    {
        return[
              $model->id,
              $model->name,
              $model->description,
              $model->vehicleClass->name,
              $model->carCompany->name,
              $model->created_by,
              $model->updated_by,
              $model->created_at,
              $model->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            ('#'),
            ('name'),
            ('description'),
            ('vehicleClass'),
            ('carCompany'),
            ('created_by'),
            ('updated_by'),
            ('created_at'),
            ('updated_at')


        ];
    }
}
