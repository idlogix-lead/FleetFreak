<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehicleExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Vehicle::all();
    }
    public function map($vehicle): array
    {
        return[
              $vehicle->id,
              $vehicle->vehicle_identification_number,
              $vehicle->driver->name,
              $vehicle->vehicleModel->carCompany->name,
              $vehicle->year,
              $vehicle->color,
              $vehicle->vehicle_no,
              $vehicle->registration_no,
              $vehicle->fuel_type,
              $vehicle->engine_type,
              $vehicle->transmission_type,
              $vehicle->vehicleModel->vehicleClass->name,
              $vehicle->vehicleModel->name,
              $vehicle->reason,
              $vehicle->created_by,
              $vehicle->updated_by,
              $vehicle->created_at,
              $vehicle->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            ('#'),
            ('vehicle_identification_number'),
            ('driver'),
            ('car_company'),
            ('year'),
            ('color'),
            ('vehicle_no'),
            ('registration_no'),
            ('fuel_type'),
            ('engine_type'),
            ('transmission_type'),
            ('vehicle_class'),
            ('vehicle_model'),
            ('reason'),
            ('created_by'),
            ('updated_by'),
            ('created_at'),
            ('updated_at'),


        ];
    }
}
