<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\TempOrder;

class AamarpayController extends Controller
{
        public function index(Request $request){
        //dd($request);
        $email = $request->email;
        $name = $request->name;
        $phone = $request->phone;

        $url = 'https://secure.aamarpay.com/request.php';
        //return $url;

        $amount = 0;
        if(Session::has('payment_amount')){
            //dd('okk');
            $amount = round(Session::get('payment_amount'));
            //dd($amount);
        }
        
        $checkout_request_text = '';
        
        if(Session::has('checkout_request')){
            //dd(Session::get('checkout_request'));
            //dd($checkout_request->_token);
            // $checkout_request = Session::get('checkout_request');
            $checkout_request = new Request;
            $checkout_request = new Request(Session::get('checkout_request'));
            //dd($checkout_request);
            $checkout_request_text = $checkout_request->_token."%".$checkout_request->name."%".$checkout_request->phone."%".$checkout_request->email."%".$checkout_request->address_id."%".$checkout_request->division_id."%".$checkout_request->district_id."%".$checkout_request->upazilla_id."%".$checkout_request->address."%".$checkout_request->shipping_id."%".$checkout_request->comment."%".$checkout_request->shipping_charge."%".$checkout_request->shipping_type."%".$checkout_request->shipping_name."%".$checkout_request->sub_total."%".$checkout_request->grand_total."%".$checkout_request->agreeBox."%".$checkout_request->paymentBox."%".$checkout_request->payment_option;
        }
        //dd($checkout_request_text);
        
        $fields = array(
            'store_id' => env('AAMARPAY_STORE_ID'), //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
            'amount' => $amount, //transaction amount
            'payment_type' => 'VISA', //no need to change
            'currency' => 'BDT',  //currenct will be USD/BDT
            'tran_id' => rand(1111111,9999999), //transaction id must be unique from your end
            'cus_name' => $name,  //customer name
            'cus_email' => $email, //customer email address
            'cus_add1' => '',  //customer address
            'cus_add2' => '', //customer address
            'cus_city' => '',  //customer city
            'cus_state' => '',  //state
            'cus_postcode' => '', //postcode or zipcode
            'cus_country' => 'Bangladesh',  //country
            'cus_phone' => $phone, //customer phone number
            'cus_fax' => 'Not¬Applicable',  //fax
            'ship_name' => '', //ship name
            'ship_add1' => '',  //ship address
            'ship_add2' => '',
            'ship_city' => '',
            'ship_state' => '',
            'ship_postcode' => '',
            'ship_country' => 'Bangladesh',
            'desc' => env('APP_NAME').' payment',
            'success_url' => route('aamarpay.success'), //your success route
            'fail_url' => route('aamarpay.fail'), //your fail route
            'cancel_url' => route('checkout'), //your cancel url
            'opt_a' => 'Aamarpay',  //optional paramter
            'opt_b' => Session::get('invoice_no'),
            'opt_c' => $checkout_request_text,
            'opt_d' => '',
            'signature_key' => env('AAMARPAY_SIGNATURE_KEY') //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key
        );

        $fields_string = http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));
        curl_close($ch);

        $this->redirect_to_merchant($url_forward);
    }

    function redirect_to_merchant($url) {
        $base_url = 'https://secure.aamarpay.com/';

        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
          <head><script type="text/javascript">
            function closethisasap() { document.forms["redirectpost"].submit(); }
          </script></head>
          <body onLoad="closethisasap();">
            <form name="redirectpost" method="post" action="<?php echo $base_url.$url; ?>"></form>
          </body>
        </html>
        <?php
        exit;
    }

    
    public function success(Request $request){
        //dd($request);
        $checkout_request_text = $request->opt_c;
        $checkout_request_arr = explode("%", $checkout_request_text);
        $checkout_request = new Request;
        $checkout_request->merge(['_token' => $checkout_request_arr[0]]);
        $checkout_request->merge(['name' => $checkout_request_arr[1]]);
        $checkout_request->merge(['phone' => $checkout_request_arr[2]]);
        $checkout_request->merge(['email' => $checkout_request_arr[3]]);
        $checkout_request->merge(['address_id' => $checkout_request_arr[4]]);
        $checkout_request->merge(['division_id' => $checkout_request_arr[5]]);
        $checkout_request->merge(['district_id' => $checkout_request_arr[6]]);
        $checkout_request->merge(['upazilla_id' => $checkout_request_arr[7]]);
        $checkout_request->merge(['address' => $checkout_request_arr[8]]);
        $checkout_request->merge(['shipping_id' => $checkout_request_arr[9]]);
        $checkout_request->merge(['comment' => $checkout_request_arr[10]]);
        $checkout_request->merge(['shipping_charge' => $checkout_request_arr[11]]);
        $checkout_request->merge(['shipping_type' => $checkout_request_arr[12]]);
        $checkout_request->merge(['shipping_name' => $checkout_request_arr[13]]);
        $checkout_request->merge(['sub_total' => $checkout_request_arr[14]]);
        $checkout_request->merge(['grand_total' => $checkout_request_arr[15]]);
        $checkout_request->merge(['agreeBox' => $checkout_request_arr[16]]);
        $checkout_request->merge(['paymentBox' => $checkout_request_arr[17]]);
        $checkout_request->merge(['payment_option' => $checkout_request_arr[18]]);
        $checkout_request->merge(['transaction_id' => $request->pg_txnid]);
        $checkout_request->merge(['account_number' => $request->card_number]);
        $checkout_request->merge(['payment_info' => 'card_type:'.$request->card_type.'; '.'mer_txnid:'.$request->mer_txnid.'; '.'bank_txn:'.$request->bank_txn.'; '.'ip_address:'.$request->ip_address.'; '.'bank_txn:'.$request->pay_time]);
        $checkoutController = new CheckoutController;
        return $checkoutController->store($checkout_request);
    }


    public function fail(Request $request){
        $checkout_type = Session::get('payment_type');

        if($checkout_type == 'cart_checkout'){
            return redirect()->route('checkout')->with('checkoutError', 'Payment Failed');
        }elseif($checkout_type == 'buy_checkout'){
            $checkout_request = new Request;
            $checkout_request = new Request(Session::get('buy_checkout_request'));
            notify()->warning("Payment failed", "Failed!");
            return redirect()->route('buy.product', $checkout_request)->with('checkoutError', 'Payment Failed');
        }elseif($checkout_type == 'partial'){
            return redirect()->route('order.pay.form', Session::get('order_order_id'))->with('checkoutError', 'Payment Failed');
        }elseif($checkout_type == 'wallet'){
            notify()->warning("Sorry, please try again", "Something Wrong");
            return redirect()->route('wallet');
        }else{
            return "not match";
        }
    }
}