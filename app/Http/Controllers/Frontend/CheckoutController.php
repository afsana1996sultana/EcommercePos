<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Vendor;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use App\Models\District;
use App\Models\Shipping;
use App\Models\Upazilla;
use App\Models\AccountHead;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\SmsTemplate;
use App\Utility\SmsUtility;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use Illuminate\Support\Carbon;
use App\Utility\SendSMSUtility;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Controllers\Frontend\PathaoController;
use App\Http\Controllers\Frontend\PublicSslCommerzPaymentController;
use App\Models\DeliveryCoupon;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!guest_checkout() && !auth()->check()){
            return redirect()->route('login');
        }
        $addresses = Address::where('status', 1)->get();
        $shippings = Shipping::where('status', 1)->get();

        $carts = Cart::content();
        //return $carts;
        //dd($carts);
        $cartTotal = Cart::total();

        //dd($cities);

        //dd($carts);

        return view('frontend.checkout.index',compact('addresses','shippings', 'carts', 'cartTotal'));
    } // end method

    public function shippingAjax($shipping_id){
        $shipping = Shipping::find($shipping_id);
        return json_encode($shipping);
    }

    /* ============== Start GetCheckoutProduct Method ============= */
    public function getCheckoutProduct(){
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,
            'cartTotal' => $cartTotal,
        ));

    } //end method
    /* ============= End GetCheckoutProduct Method ============== */

    /* ============= Start getdivision Method ============== */
    public function getdivision($division_id){
        $division = District::where('division_id', $division_id)->orderBy('district_name_en','ASC')->get();

        return json_encode($division);
    }
    /* ============= End getdivision Method ============== */

    /* ============= Start getupazilla Method ============== */
    public function getupazilla($district_id){
        $upazilla = Upazilla::where('district_id', $district_id)->orderBy('name_en','ASC')->get();

        return json_encode($upazilla);
    }
    /* ============= End getupazilla Method ============== */

    public function getZones($city_id){
        //$division = District::where('division_id', $division_id)->orderBy('district_name_en','ASC')->get();
        $pathao = new PathaoController;
        $zoneResult = $pathao->getZones($city_id);
        $zones = $zoneResult->data->data;

        return json_encode($zones);
    }

    public function getAreas($zone_id){
        //$division = District::where('division_id', $division_id)->orderBy('district_name_en','ASC')->get();
        $pathao = new PathaoController;
        $areaResult = $pathao->getAreas($zone_id);
        $areas = $areaResult->data->data;

        return json_encode($areas);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $payment_info = json_decode(Session::get('payment_details'));
        $data = $request->validate([
            'name' => 'required|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'required|digits:11',
            'division_id' => 'required',
            'district_id' => 'required',
            'upazilla_id' => 'required',
            'address' => 'required|max:10000',
            'payment_option'=>'required',
            'comment' => 'nullable|max:2000',
            'shipping_id' => 'required',
        ]);

        $carts = Cart::content();

        if($carts->isEmpty()){
            $notification = array(
                'message' => 'Your cart is empty.',
                'alert-type' => 'error'
            );
            return redirect()->route('home')->with($notification);
        }

       // dd($request->all());

        if(Auth::check()){
            $user_id = Auth::id();
            $type = 1;
        }else{
            $user_id = 1;
            $type = 2;
        }
        $now = now();
        $todayOrder = Order::where('user_id', $user_id)->whereDate('created_at', $now->toDateString())->latest()->first();
        if ($todayOrder && !$todayOrder->delivery_status == 'Cancelled') {
            $notification = array(
                'message' => 'You have already placed an order today.',
                'alert-type' => 'error'
            );
            return redirect()->route('checkout')->with($notification);
        }

        $oldOrder=Order::where('user_id',$user_id )->get();
        if($oldOrder){
          foreach($oldOrder as $item){
              if($item->delivery_status == 'Pending' || $item->delivery_status == 'Holding' || $item->delivery_status == 'Processing'){
                  $notification = array(
                      'message' => 'Please Confirm Your Previous Order Should be Shipped.',
                      'alert-type' => 'error'
                  );
                  return redirect()->route('checkout')->with($notification);
          }
          }
        }
        if($request->payment_option == 'cod'){
            $payment_status = 'unpaid';
        }elseif($request->paymentBox == 'delivery_charge'){
            $payment_status = 'partial paid';
        }else{
            $payment_status = 'paid';
        }

        $invoice_data = Order::orderBy('id','desc')->first();
        if($invoice_data){
            $lastId = $invoice_data->id;
            // $id = str_pad($lastId + 1, 7, 0, STR_PAD_LEFT);
            $id = str_pad($lastId + 1, 2, 0, STR_PAD_LEFT);
            $invoice_no = $id;
        }else{
            $invoice_no = "01";
        }

        // $vatAmount = $request->sub_total*5/100;
        $vatRate = 5; // VAT rate as a percentage
        $vatAmount = $request->sub_total * $vatRate / 100;
        //dd($vatAmount);

        // order add //
        //return $user_id;
        $order = Order::create([
            'user_id' => $user_id,
            'name' => $request->name,
            'sub_total' => $request->sub_total,
            'grand_total' => $request->grand_total,
            'vat' => $vatAmount ?? NULL,
            'totalbuyingPrice' => $request->totalbuyingPrice ?? '0.00',
            'shipping_charge' => $request->shipping_charge,
            'shipping_name' => $request->shipping_name,
            'shipping_type' => $request->shipping_type,
            'payment_method' => $request->payment_option,
            'payment_status' => $payment_status,
            'invoice_no' => $invoice_no,
            'delivery_status' => 'Pending',
            'phone' => $request->phone,
            'email' => $request->email,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'upazilla_id' => $request->upazilla_id,
            'address' => $request->address,
            'comment' => $request->comment,
            'type' => $type,
            'coupon_id' =>$request->coupon_id,
            'trxID' =>$payment_info->trxID ?? $request->transaction_id ?? NULL,
            'coupon_discount' =>$request->coupon_discount,
            'order_by'              => 0,
            'total_items'    => count($carts),
        ]);
        if($request->delivery_coupon==1){
            $order->grand_total=$order->grand_total - $order->shipping_charge;
            $order->delivery_coupon = 1;
            $order->delivery_coupon_amount=$order->shipping_charge;
            $order->shipping_charge=0;
            $order->save();
        }

        if($order->payment_status == 'unpaid'){
            $order->csv_amount=$order->grand_total;
            $order->due_amount=$order->grand_total;
            $order->paid_amount=0.00;
        }elseif($order->payment_status =="paid"){
            $order->csv_amount=0.00;
            $order->due_amount=0.00;
            $order->paid_amount=$order->grand_total;
        }else{
            $order->csv_amount=$order->grand_total - $order->shipping_charge;
            $order->due_amount=$order->grand_total - $order->shipping_charge;
            $order->paid_amount=$order->shipping_charge;
        }
        $order->save();

        if(get_setting('otp_system')){
            $sms_template = SmsTemplate::where('name','order_message')->where('status',1)->first();
            if($sms_template){
                $sms_body       = $sms_template->body;
                $sms_body       = str_replace('[[order_code]]', $order->invoice_no, $sms_body);
                $sms_body       = str_replace('[[order_amount]]', $order->grand_total, $sms_body);
                $sms_body       = str_replace('[[site_name]]', env('APP_NAME'), $sms_body);

                if($order->pay_status == 1){
                    $payment_info = json_decode($order->payment_info);
                    $sms_body     = str_replace('[[payment_details]]', 'পেমেন্ট স্ট্যাটাসঃ paid'.', ট্রান্সেকশন আইডিঃ '.$order->transaction_id.', মাধ্যমঃ '.$order->payment_method.' ', $sms_body);
                }else{
                    $sms_body       = str_replace('[[payment_details]]', '', $sms_body);
                }

                if(substr($order->phone,0,3)=="+88"){
                    $phone = $order->phone;
                }elseif(substr($order->phone,0,2)=="88"){
                    $phone = '+'.$order->phone;
                }else{
                    $phone = '+88'.$order->phone;
                }
                //dd($phone);
                SendSMSUtility::sendSMS($phone, $sms_body);

                // $sms_body = str_replace('আপনার', 'নতুন', $sms_body);
                // $setting = Setting::where('name', 'phone')->first();
                // if($setting->value != null){
                //     $admin_phone=$setting->value;

                //     if(substr($admin_phone,0,3)=="+88"){
                //         $phone = $admin_phone;
                //     }elseif(substr($admin_phone,0,2)=="88"){
                //         $phone = '+'.$admin_phone;
                //     }else{
                //         $phone = '+88'.$admin_phone;
                //     }
                //     SendSMSUtility::sendSMS($admin_phone, $sms_body);
                // }
            }
        }

        // order details add //
        foreach ($carts as $cart) {
            $product = Product::find($cart->id);
            if($cart->options->vendor == 0){
                $vendor_comission = 0.00;
                $vendor = 0;
            }
            else {
                $vendor = Vendor::where('user_id', $cart->options->vendor)->select('vendors.commission','user_id')->first();
                $vendor_comission = ($cart->price * $vendor->commission)/100;
            }

            if($cart->options->is_varient == 1){
                $variations = array();
                for($i=0; $i<count($cart->options->attribute_names); $i++){
                    $item['attribute_name'] = $cart->options->attribute_names[$i];
                    $item['attribute_value'] = $cart->options->attribute_values[$i];
                    array_push($variations, $item);
                }
                OrderDetail::insert([
                    'order_id' => $order->id,
                    'product_id' => $cart->id,
                    'product_name' => $cart->name,
                    'is_varient' => 1,
                    'vendor_id' => $vendor->user_id ?? 0,
                    'v_comission' => $vendor_comission,
                    'variation' => json_encode($variations, JSON_UNESCAPED_UNICODE),
                    'qty' => $cart->qty,
                    'price' => $cart->price,
                    'tax' => $cart->tax,
                    'created_at' => Carbon::now(),
                ]);

                // stock calculation //
                $stock = ProductStock::where('varient', $cart->options->varient)->first();
                // dd($cart);
                if($stock){
                    // dd($stock);
                    $stock->pre_qty = $stock->qty;
                    $stock->qty = $stock->qty - $cart->qty;
                    $stock->save();
                }

            }else{
                OrderDetail::insert([
                    'order_id' => $order->id,
                    'product_id' => $cart->id,
                    'product_name' => $cart->name,
                    'is_varient' => 0,
                    'vendor_id' => $vendor->user_id ?? 0,
                    'v_comission' => $vendor_comission,
                    'qty' => $cart->qty,
                    'price' => $cart->price,
                    'tax' => $cart->tax,
                    'created_at' => Carbon::now(),
                ]);
            }
            $product->previous_stock = $product->stock_qty;
            $product->stock_qty = $product->stock_qty - $cart->qty;
            $product->save();
        }
        Cart::destroy();
        //OrderStatus Entry
        $orderstatus = OrderStatus::create([
            'order_id' => $order->id,
            'title' => 'Order Placed',
            'comments' => '',
            'created_at' => Carbon::now(),
        ]);

        $orderstatus = OrderStatus::create([
            'order_id' => $order->id,
            'title' => 'Payment Status: '.$payment_status,
            'comments' => '',
            'created_at' => Carbon::now(),
        ]);

        $orderstatus = OrderStatus::create([
            'order_id' => $order->id,
            'title' => 'Delevery Status: Pending',
            'comments' => '',
            'created_at' => Carbon::now(),
        ]);

        //Ledger Entry
        $ledger = AccountLedger::create([
            'account_head_id' => 2,
            'particulars' => 'Invoice No: '.$invoice_no,
            'credit' => $order->grand_total,
            'order_id' => $order->id,
            'type' => 2,
        ]);
        $ledger->balance = get_account_balance() + $order->grand_total;
        $ledger->save();

        $amount = 0;
        $totalqty = 0;

        foreach ($order->order_details as $order_detail) {
           // $product_purchase_price = $order_detail->product->purchase_price;
            $amount += $order_detail->product->purchase_price;
            $totalqty += $order_detail->qty;
        }
        $order->totalbuyingPrice = $amount;
        $order->totalqty = $totalqty;
        $order->save();
        // if($order->payment_status=='paid'){
        //     $order->csv_amount=0.00;
        // }elseif($order->payment_status=='partial paid'){
        //     $order->csv_amount=$order->grand_total-$order->shipping_charge;
        // }else{
        //     $order->csv_amount=$order->grand_total;
        // }

        $address=Address::where('user_id',$user_id)->first();
        if(!$address){
            $address=new Address;
            $address->user_id=$user_id;
            $address->division_id=$order->division_id;
            $address->district_id=$order->district_id;
            $address->upazilla_id=$order->upazilla_id;
            $address->address=$order->address;
            $address->status=1;
            $address->save();
        }
        $notification = array(
            'message' => 'Your order has been placed successfully.',
            'alert-type' => 'success'
        );
        return redirect()->route('checkout.success', $order->invoice_no)->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::where('invoice_no', $id)->first();

        $notification = array(
            'message' => 'Your Order Successfully.',
            'alert-type' => 'success'
        );

        return view('frontend.order.order_confirmed', compact('order'))->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function payment(Request $request){

          $request->validate([
            'name' => 'required|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'required|digits:11',
            'address' => 'required|max:10000',
            'payment_option'=>'required',
            'comment' => 'nullable|max:2000',
            'shipping_id' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'upazilla_id' => 'required',
        ]);
        //dd($request->all());
        Session::put('checkout_request', $request->all());
        $payment_method = $request->payment_option;
        if($request->paymentBox =="full_payment"){
            $total_amount = $request->grand_total;
        }else if($request->paymentBox =="delivery_charge"){
            $total_amount = $request->shipping_charge;
        }else{
            $total_amount = $request->grand_total;
        }
        $last_order = Order::orderBy('id','DESC')->first();
        if($last_order){
            $order_id = $last_order->id+1;
        }else{
            $order_id =1;
        }
        $invoice_no = date('YmdHis') . rand(10, 99);
        Session::put('invoice_no', $invoice_no);
        if($request->payment_option == 'cod'){
            $checkout = new CheckoutController;
            return $checkout->store($request);
        }else{
            if(Auth::check()){
            $user_id = Auth::id();
            $type = 1;
        }else{
            $user_id = 1;
            $type = 2;
        }
            $now = now();
            $todayOrder = Order::where('user_id', $user_id)->whereDate('created_at', $now->toDateString())->latest()->first();

            if ($todayOrder && !$todayOrder->delivery_status == 'Cancelled') {
                $notification = array(
                    'message' => 'You have already placed an order today.',
                    'alert-type' => 'error'
                );
                return redirect()->route('checkout')->with($notification);
            }

            $oldOrders = Order::where('user_id', $user_id)->get();
            if ($oldOrders->isNotEmpty()) {
                foreach ($oldOrders as $item) {
                    if ($item->delivery_status == 'Pending' || $item->delivery_status == 'Holding' || $item->delivery_status == 'Processing') {
                        $notification = array(
                            'message' => 'Please Confirm Your Previous Order Should be Shipped.',
                            'alert-type' => 'error'
                        );
                        return redirect()->route('checkout')->with($notification);
                    }
                }
            }

            Session::put('payment_method', $request->payment_option);
            Session::put('payment_type', 'cart_payment');
            Session::put('payment_amount', $total_amount);
            if($request->payment_option == 'nagad'){
                $nagad = new NagadController;
                return $nagad->getSession();
            }else if($request->payment_option == 'bkash'){
                $bkash = new BkashController;
                return $bkash->pay($request);
                // return $bkash->pay();
            }elseif ($request->payment_option == 'sslcommerz') {
                $sslcommerz = new PublicSslCommerzPaymentController;
                return $sslcommerz->index($request);
            }elseif($payment_method =='aamarpay'){
                //dd('okk');
                $aamarpay = new AamarpayController;
                return $aamarpay->index($request);

            }
        }

        return view('frontend.checkout.payment', compact('payment_method', 'total_amount', 'invoice_no'));
    }

    public function success($orderId, $json){
        //dd(json_decode($json));
        json_decode($json);
    }

    public function coupon_get(Request $request){
        $carts = Cart::content();
        $coupon=Coupon::where('coupon_code',$request->coupon)->first();
        $now=Carbon::now();
        if($coupon){
            //status check
            if($coupon->status == 0){
                return response()->json(['error'=> 'Coupon Is Now InActive.']);
            }
            //expire date
            if($coupon->expire_date != ""){
                  $enddate= $coupon->expire_date;
                  if($now->gt($enddate)){
                    return response()->json(['error'=> 'Coupon Expired date.']);
                  }
            }
            //selected user
            if($coupon->user_id != null){
                $couponUserIds = explode(',', $coupon->user_id);
                foreach($couponUserIds as $userId){
                    $userId==Auth::user()->id;
                    if(!$userId){
                        return response()->json(['error'=> 'This coupon is intended for specific users']);
                    }
                }
            }
            //max uses
            $couponUsed=Order::where('coupon_id',$coupon->id)->count();
            if($couponUsed >=$coupon->total_use_limit){
                return response()->json(['error'=> 'Coupon Uses Out of Limit.']);
            }
            //max user uses
            $couponUserUsed=Order::where(['coupon_id'=>$coupon->id ,'user_id'=>Auth::user()->id])->count();
            if($couponUserUsed >=$coupon->limit_per_user){
                return response()->json(['error'=> 'You reached the maximum limit for using the coupon.']);
            }

              //if amount_range
            if($coupon->amount_range > 0){
                $totalprice=0;
                foreach ($carts as $cart) {
                    $totalprice += $cart->price;
                }
                if($totalprice < $coupon->amount_range){
                  return response()->json(['error'=> 'To qualify for a coupon, please increase the quantity of items in your order.']);
                }
            }

            //if product Id
            if ($coupon->product_id != null) {
                $couponProductIds = explode(',', $coupon->product_id);
                $totalDiscount = 0;
                foreach ($carts as $cart) {
                    if (in_array($cart->id, $couponProductIds)) {
                        if ($coupon->discount_type == 1) {
                            $totalDiscount += $coupon->discount;
                        } else {
                            $discount = $cart->price * $coupon->discount / 100;
                            $totalDiscount += $discount;
                        }
                    }
                }
                if ($totalDiscount > 0) {
                    return response()->json([
                        'success' => 'Added your coupon',
                        'discount' => $totalDiscount,
                        'coupon' => $coupon
                    ]);
                } else {
                    return response()->json(['error' => 'No matching products for the coupon']);
                }
            }

            return response()->json(['success' => 'Added your coupon','coupon' => $coupon]);
        }else{
            return response()->json(['error'=> 'Coupon not found.']);
        }
    }
    public function delivery_coupon_get(Request $request){
        $carts = Cart::content();
        $coupon=DeliveryCoupon::where('delivery_code',$request->coupon)->first();
        $now=Carbon::now();
        if($coupon){
            //status check
            if($coupon->status == 0){
                return response()->json(['error'=> 'Delivery Coupon Is Now InActive.']);
            }
            //expire date
            if($coupon->expire_date != ""){
                  $enddate= $coupon->expire_date;
                  if($now->gt($enddate)){
                    return response()->json(['error'=> 'Delivery Coupon Expired date.']);
                  }
            }
            //selected user
            if($coupon->user_id != null){
                $couponUserIds = explode(',', $coupon->user_id);
                foreach($couponUserIds as $userId){
                    $userId==Auth::user()->id;
                    if(!$userId){
                        return response()->json(['error'=> 'This coupon is intended for specific users']);
                    }
                }
            }
            //max uses
            $couponUsed=Order::where('coupon_id',$coupon->id)->count();
            if($couponUsed >=$coupon->total_use_limit){
                return response()->json(['error'=> 'Delivery Coupon Uses Out of Limit.']);
            }
            //max user uses
            $couponUserUsed=Order::where(['coupon_id'=>$coupon->id ,'user_id'=>Auth::user()->id])->count();
            if($couponUserUsed >=$coupon->limit_per_user){
                return response()->json(['error'=> 'You reached the maximum limit for using the Delivery coupon.']);
            }
            
            //if amount_range
            if($coupon->amount_range > 0){
                $totalprice=0;
                foreach ($carts as $cart) {
                    $totalprice += $cart->price;
                }
                if($totalprice < $coupon->amount_range){
                  return response()->json(['error'=> 'To qualify for a coupon, please increase the quantity of items in your order.']);
                }
            }

            //if product Id
            if ($coupon->product_id != null) {
                $couponProductIds = explode(',', $coupon->product_id);
                foreach ($carts as $cart) {
                    if (in_array($cart->id, $couponProductIds)) {
                        return response()->json([  'success' => 'Added your Delivery coupon','coupon' => $coupon ]);
                    } else {
                        return response()->json(['error' => 'No matching products for the coupon']);
                    }
                }
            }

            return response()->json(['success' => 'Added your Delivery coupon','coupon' => $coupon]);
        }else{
            return response()->json(['error'=> 'Delivery Coupon not found.']);
        }
    }
}