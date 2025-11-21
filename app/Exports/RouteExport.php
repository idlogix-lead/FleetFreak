<?php

namespace App\Exports;

use App\Models\Route;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RouteExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Route::get();
    }
    public function map($route): array
    {
        return[
              $route->id,
              $route->name,
              $route->fromLoc->name,
              $route->toLoc->name,
              $route->distance,
              $route->is_flight,
              $route->distance_unit,
              $route->created_at,
              $route->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            ('#'),
            ('name'),
            ('from_loc'),
            ('to_loc'),
            ('distance'),
            ('is_flight'),
            ('distance_unit'),
            ('created_at'),
            ('updated_at')


        ];
    }
}
