<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;

    }

    public function collection()
    {
        return Order::whereIn('orders.id', $this->ids)
            ->select(
                "orders.parcel",
                "orders.storename",
                "orders.invoice_no",
                "orders.name",
                "orders.phone",
               // "divisions.division_name_en as division_name",
                "districts.district_name_en as district_name",
                "upazillas.name_en as upazilla_name",
                "orders.area",
                "orders.address",
                "orders.csv_amount",
                "orders.totalqty",
                "orders.weight",
                "orders.comment",
                "orders.comment",
                // "SpecialInstruction",
            )
           // ->join('divisions', 'orders.division_id', '=', 'divisions.id')
            ->join('districts', 'orders.district_id', '=', 'districts.id')
            ->join('upazillas', 'orders.upazilla_id', '=', 'upazillas.id')
            ->get();
    }

    public function headings(): array
    {
        return [
            "ItemType",
            "StoreName",
            "MerchantOrderId",
            "RecipientName(*)",
            "RecipientPhone(*)",
            "RecipientCity(*)",
            "RecipientZone(*)",
            "RecipientArea",
            "RecipientAddress(*)",
            "AmountToCollect(*)",
            "ItemQuantity",
            "ItemWeight",
            "ItemDesc",
            "SpecialInstruction",
        ];
    }
}