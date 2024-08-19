<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Cashwithdraw;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use Auth;
use Session;

class CashwithdrawController extends Controller
{
    public function index()
    {
        $withdraw = Cashwithdraw::latest()->get();
        return view('backend.cash-withdraw.index',compact('withdraw'));
    }

    public function store(Request $request){
        //return $request;
        $adminData = User::select("users.*")->where('users.id',$request->user_id)->first();

        //vendor wallet
        $wallet = OrderDetail::where('vendor_id', Auth::guard('admin')->user()->id)->pluck('order_id')->toArray();
        $walletValue = Order::whereIn('id', $wallet)->sum('grand_total');

        //vendor commission
        $orderID = Order::whereIn('id', $wallet)->pluck('id');
        $matchedorderID = OrderDetail::whereIn('order_id', $orderID)->get();
        $commissionValue = $matchedorderID->sum('v_comission');

        //vendor wallet Value
        $vendorWalletValue = $walletValue - $commissionValue;

        if($request->amount>$vendorWalletValue){
            $notification = array(
                'message' => 'Your Requested amount is incufficient',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
           
        }else{
            $withdraw = new Cashwithdraw();
            $withdraw->vendor_id = $request->user_id;
            $withdraw->method = $request->method;
            $withdraw->account_type = $request->account_type;
            $withdraw->user_type = $request->user_type;
            $withdraw->phone = $request->transition_number ?? 0;
            $withdraw->account_holder_name = $request->account_holder_name;
            $withdraw->account_no = $request->account_no;
            $withdraw->bank_name = $request->bank_name;
            $withdraw->bank_brunch = $request->bank_brunch;
            $withdraw->purpose = $request->purpose ?? 0;
            $withdraw->amount = $request->amount;
            $withdraw->status = 0;
            $withdraw->save();

            $notification = array(
                'message' => 'Request Submit Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function Bkash(){
        $bkash = Cashwithdraw::select('cashwithdraws.*','users.name')
                ->join('users','users.id','cashwithdraws.vendor_id')
                ->where('method','Bkash')
                ->get();

        return view('backend.cash-withdraw.bkash',compact('bkash'));
    }

    public function Nagad(){
        $nagad = Cashwithdraw::select('cashwithdraws.*','users.name')
                ->join('users','users.id','cashwithdraws.vendor_id')
                ->where('method','Nagad')
                ->get();

        return view('backend.cash-withdraw.nagad',compact('nagad'));
    }

    public function Bank(){
        $bank = Cashwithdraw::select('cashwithdraws.*','users.name')
                ->join('users','users.id','cashwithdraws.vendor_id')
                ->where('method','Bank')
                ->get();

        return view('backend.cash-withdraw.bank',compact('bank'));
    }

    public function Cash(){
        $cash = Cashwithdraw::select('cashwithdraws.*','users.name')
                ->join('users','users.id','cashwithdraws.vendor_id')
                ->where('method','cash')
                ->get();
        return view('backend.cash-withdraw.cash',compact('cash'));
    }


    public function withdraw_history(){
        $withdraw_list = Cashwithdraw::select("cashwithdraws.*")->where('cashwithdraws.vendor_id',Auth::guard('admin')->user()->id)->get();
        return view('backend.cash-withdraw.withdraw_list',compact('withdraw_list'));
    }


    public function withdrawStatus($id){
        $withdraw = Cashwithdraw::findOrFail($id);
        $user = User::findOrFail($withdraw->vendor_id);

        $order = OrderDetail::where('vendor_id', $user->id)->pluck('order_id')->toArray();
        $orderID = Order::whereIn('id', $order)->sum('grand_total');
        $wallet = OrderDetail::where('vendor_id', $user->id)->sum('v_comission');
        $vendorWalletValue = $orderID - $wallet;
        
        $user->income = $vendorWalletValue - $withdraw->amount;
        $user->save();

        $withdraw->status = 1;
        $withdraw->save();

        $notification = array(
            'message' => 'Cash withdraw successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }

}
