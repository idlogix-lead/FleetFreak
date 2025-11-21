<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OverallExportAgentPendingOrder implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::where('overall_status','pending')->where('business_partner_id',auth()->user()->partner_id)->get();
    }

    public function map($order): array
    {
        return[
              $order->id,
              $order->order_no,
              $order->partner_customer->name,
              $order->partner_business->name,
              $order->overall_status,
              $order->overall_adult,
              $order->overall_child,
              $order->overall_bags,
              $order->booking_amount,
              $order->vehicle_Class->name,
              $order->vehicleModel->name,
              $order->created_at,
              $order->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            ('#'),
            ('order_no'),
            ('customer_partner'),
            ('business_partner'),
            ('overall_status'),
            ('overall_adult'),
            ('overall_child'),
            ('overall_bags'),
            ('booking_amount'),
            ('vehicle_class'),
            ('vehicle_model'),
            ('created_at'),
            ('updated_at ')


        ];
    }
}
