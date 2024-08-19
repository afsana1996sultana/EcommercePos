<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;



class OrderRefundController extends Controller
{
    public function index(Request $request){
        $refund = OrderRefund::orderBy('created_at', 'desc')->get();
        return view('backend.sales.refund.index',compact('refund'));
    }

    public function create()
    {
        $refund = OrderRefund::get();
        return view('backend.sales.refund.create',compact('refund'));
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'invoice_no'        => 'nullable|string|max:255',
            'refund_amount'     => 'required|numeric',
            'payment_date'      => 'required|date',
            'payment_method'    => 'required|string|max:255',
            'transaction_id'   => 'nullable|string|max:255',
            'agent_number'      => 'nullable|string|max:255',
        ]);

        $refund = new OrderRefund();
        $refund->invoice_no = $request->invoice_no;
        $refund->refund_amount = $request->refund_amount;
        $refund->payment_date = $request->payment_date;
        $refund->payment_method = $request->payment_method;
        $refund->transaction_id = $request->transaction_id;
        $refund->agent_number = $request->agent_number;
        $refund->save();

        $notification = [
            'message' => 'Refund Created Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('refund.index')->with($notification);
    }


    public function edit($id)
    {
        $refund = OrderRefund::findOrFail($id);
        return view('backend.sales.refund.edit',compact('refund'));
    }

    public function update(Request $request, $id)
    {
        $refund = OrderRefund::findOrFail($id);
        $this->validate($request,[
            'invoice_no'        => 'nullable|string|max:255',
            'refund_amount'     => 'required|numeric',
            'payment_date'      => 'required|date',
            'payment_method'    => 'required|string|max:255',
            'transaction_id'    => 'nullable|string|max:255',
            'agent_number'      => 'nullable|string|max:255',
        ]);

        $refund->invoice_no = $request->invoice_no;
        $refund->refund_amount = $request->refund_amount;
        $refund->payment_date = $request->payment_date;
        $refund->payment_method = $request->payment_method;
        $refund->transaction_id = $request->transaction_id;
        $refund->agent_number = $request->agent_number;
        $refund->save();

        $notification = [
            'message' => 'Refund Edited Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('refund.index')->with($notification);
    }

    public function destroy($id)
    {
        $refund = OrderRefund::findOrFail($id);
        $refund->delete();
        $notification = array(
            'message' => 'Refund Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}