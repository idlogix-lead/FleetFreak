<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportCompletedOrder implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return  OrderDetail::where('status', 'ILIKE', 'completed' . '%')->get();
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->order_id,
            $order->rate_list_id,
            $order->driver_id,
            $order->vehicle_id,
            $order->rate,
            $order->status,
            $order->adult,
            $order->child,
            $order->bags,
            $order->data,
            $order->pickup_time,
            $order->checkout_time,
            $order->is_ac,
            $order->driver_rate,
            $order->driver_pickup_Ioc,
            $order->driver_dropoff_Ioc,
            $order->ride_start_mileage,
            $order->ride_end_mileage,
            $order->created_at,
            $order->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            ('#'),
            ('order_id'),
            ('rate_list_id'),
            ('driver_id'),
            ('vehicle_id'),
            ('rate'),
            ('status'),
            ('adult'),
            ('child'),
            ('bags'),
            ('data'),
            ('pickup_time'),
            ('chehckout_time'),
            ('is_ac'),
            ('driver_rate'),
            ('driver_pickup_Ioc'),
            ('driver_dropoff_Ioc'),
            ('ride_start_mileage'),
            ('ride_end_mileage'),
            ('created_at'),
            ('updated_at ')


        ];
    }
}
