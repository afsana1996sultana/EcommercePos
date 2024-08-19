<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\DeliveryCoupon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class DeliveryCouponController extends Controller
{
    public function index()
    {
        $deliveryCoupons = DeliveryCoupon::latest()->get();
        return view('backend.deliveryCoupon.index', compact('deliveryCoupons'));
    }

    public function create()
    {
        $users = User::where('role',3)->latest()->get();
        $Products = Product::where('status',1)->latest()->get();
        return view('backend.deliveryCoupon.create', compact('users','Products'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'delivery_code' => 'required|max:50',
            'limit_per_user' => 'required',
            'total_use_limit' => 'required',
            'expire_date' => 'required',
            'type' => 'required',
            'producttype' => 'required',
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'description' => 'nullable',
        ]);

        $deliveryCoupon = new DeliveryCoupon();
        $deliveryCoupon->delivery_code = $request->delivery_code;
        $deliveryCoupon->amount_range = $request->amount_range;
        $deliveryCoupon->limit_per_user = $request->limit_per_user;
        $deliveryCoupon->total_use_limit = $request->total_use_limit;
        $deliveryCoupon->expire_date = $request->expire_date;
        $deliveryCoupon->type = $request->type;
        $deliveryCoupon->producttype = $request->producttype;
        $deliveryCoupon->description = $request->description;

        if (is_array($request->user_id)) {
            $deliveryCoupon['user_id'] = implode(',', $request->user_id);
        } else {
            $deliveryCoupon['user_id'] = '';
        }
        if (is_array($request->product_id)) {
            $deliveryCoupon['product_id'] = implode(',', $request->product_id);
        } else {
            $deliveryCoupon['product_id'] = '';
        }
        $deliveryCoupon->status = $request->status;
        $deliveryCoupon->save();

        Session::flash('success','deliveryCoupon Inserted Successfully');
        return redirect()->route('deliveryCoupon.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $deliveryCoupon = DeliveryCoupon::findOrFail($id);
        $users = User::where('role',3)->latest()->get();
        $Products = Product::where('status',1)->latest()->get();
        return view('backend.deliveryCoupon.edit',compact('deliveryCoupon','users','Products'));
    }


    public function update(Request $request, $id)
    {

        $deliveryCoupon = DeliveryCoupon::findOrFail($id);
        $this->validate($request,[
            'delivery_code' => 'required|max:50',
            'limit_per_user' => 'required',
            'total_use_limit' => 'required',
            'expire_date' => 'required',
            'type' => 'required',
            'producttype' => 'required',
            'user_id' => 'nullable',
            'description' => 'nullable',
        ]);

        $deliveryCoupon->delivery_code        = $request->delivery_code;
        $deliveryCoupon->amount_range         = $request->amount_range;
        $deliveryCoupon->limit_per_user     = $request->limit_per_user;
        $deliveryCoupon->total_use_limit    = $request->total_use_limit;
        $deliveryCoupon->expire_date        = $request->expire_date;
       // $deliveryCoupon['user_id']          = is_array($request->user_id) ? implode(',', $request->user_id) : $request->user_id;
       if (is_array($request->user_id)) {
        $deliveryCoupon['user_id'] = implode(',', $request->user_id);
        } else {
            $deliveryCoupon['user_id'] = '';
        }
       if (is_array($request->product_id)) {
        $deliveryCoupon['product_id'] = implode(',', $request->product_id);
        } else {
            $deliveryCoupon['product_id'] = '';
        }
        if($request->type==1){
            $deliveryCoupon['user_id'] = '';
        }
        if($request->producttype==1){
            $deliveryCoupon['product_id'] = '';
        }
        $deliveryCoupon->type               = $request->type;
        $deliveryCoupon->producttype        = $request->producttype;
        $deliveryCoupon->description        = $request->description;
        $deliveryCoupon->status = $request->status;
        $deliveryCoupon->save();

        Session::flash('success','deliveryCoupon Updated Successfully');
        return redirect()->route('deliveryCoupon.index');
    }


    public function destroy($id)
    {
        $deliveryCoupon = DeliveryCoupon::findOrFail($id);

        $deliveryCoupon->delete();

        $notification = array(
            'message' => 'deliveryCoupon Deleted Successfully.',
            'alert-type' => 'error'
        );
        return redirect()->back()->with($notification);
    }
    /*=================== Start Active/Inactive Methoed ===================*/
    public function active($id){
        $deliveryCoupon = DeliveryCoupon::find($id);
        $deliveryCoupon->status = 1;
        $deliveryCoupon->save();

        Session::flash('success','deliveryCoupon Active Successfully.');
        return redirect()->back();
    }

    public function inactive($id){
        $deliveryCoupon = DeliveryCoupon::find($id);
        $deliveryCoupon->status = 0;
        $deliveryCoupon->save();

        Session::flash('warning','deliveryCoupon Inactive Successfully.');
        return redirect()->back();
    }
}