<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class OnlineExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $orders = Order::where('show_hide', 1)->where('order_by',0)
            ->orderBy('created_at', 'desc')
            ->select(
                "orders.id",
                "orders.name",
                "orders.phone",
                "divisions.division_name_en as division_name",
                "districts.district_name_en as district_name",
                "upazillas.name_en as upazilla_name",
                "orders.address",
                "orders.sub_total",
                "orders.grand_total",
                "orders.totalqty",
                "orders.payment_status",
                "orders.delivery_status",
                "orders.order_by",
                "orders.comment"
            )
            ->join('divisions', 'orders.division_id', '=', 'divisions.id')
            ->join('districts', 'orders.district_id', '=', 'districts.id')
            ->join('upazillas', 'orders.upazilla_id', '=', 'upazillas.id')
            ->with('order_details')
            ->get();

        foreach ($orders as $order) {
            $order->order_by = $order->order_by == 1 ? 'Pos' : 'Ecommerce';
            $productNames = optional($order->order_details)->pluck('product_name')->toArray() ?? [];
            $order->comment = implode(', ', $productNames);
        }

        return $orders->map(function ($order) {
            return [
                'InvoiceNo' => $order->id ?? '',
                'Client-Name' => $order->name ?? '',
                'Client-Phone' => $order->phone ?? '',
                'Client-City' => $order->division_name ?? '',
                'Client-Zone' => $order->district_name ?? '',
                'Client-Area' => $order->upazilla_name ?? '',
                'Client-Address' => $order->address ?? '',
                'SubTotal' => $order->sub_total ?? 0,
                'GrandTotal' => $order->grand_total ?? 0,
                'Item-Qty' => $order->totalqty ?? 0,
                'Payment Status' => $order->payment_status ?? '',
                'Delivery Status' => $order->delivery_status ?? '',
                'Order-Type' => $order->order_by ?? '',
                'Products' => $order->comment ?? ''
            ];
        });
    }

    public function headings(): array
    {
        return [
            "InvoiceNo",
            "Client-Name",
            "Client-Phone",
            "Client-City",
            "Client-Zone",
            "Client-Area",
            "Client-Address",
            "SubTotal",
            "GrandTotal",
            "Item-Qty",
            "Payment Status",
            "Delivery Status",
            "Order-Type",
            "Products"
        ];
    }
}
