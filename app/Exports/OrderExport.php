<?php

namespace App\Exports;


use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection, WithHeadings
{
    protected $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }
    public function collection()
    {
        Order::whereIn('id', $this->ids)->select(
            //"parcel",
           // "StoreName",
           // "MerchantOrderId",
            "user_id",
            "phone",
            "district_id",
            "upazilla_id",
            "",
            "address",
            "grand_total",
            "totalqty",
            "weight",
            "comment",
            "SpecialInstruction",
        )->get();
    }
    public function headings(): array
    {
        return [
           // "ItemType",
           // "StoreName",
          //  "MerchantOrderId",
            "RecipientName",
            "RecipientPhone",
            "RecipientCity",
            "RecipientZone",
            "RecipientArea",
            "RecipientAddress",
            "AmountToCollect",
            "ItemQuantity",
            "ItemWeight",
            "ItemDesc",
            "SpecialInstruction",
        ];
    }
}