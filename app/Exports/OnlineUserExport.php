<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class OnlineUserExport implements FromCollection
{



    public function collection()
    {
        return User::where('role',3)->where('customer_type',0)->select(
            "name",
            "phone",
            "address",
        )->get();
    }
    public function headings(): array
    {
        return [
            "Name",
            "Phone Number",
            "Address",
        ];
    }
}
