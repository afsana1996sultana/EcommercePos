<?php

namespace App\Http\Controllers;

use Auth;
use Cache;
use Artisan;
use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use App\Models\Staff;
use App\Models\OrderDetail;
use App\Models\Cashwithdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /*=================== Start Index Login Methoed ===================*/
    public function Index(){

        if(Auth::check()){
            abort(404);
        }

    	return view('admin.admin_login');
    } // end method

    /*=================== End Index Login Methoed ===================*/

    /*=================== Start Dashboard Methoed ===================*/
    public function Dashboard(){

        $vendor = Vendor::where('user_id', Auth::guard('admin')->user()->id)->first();

        $userCount = DB::table('users')
            ->select(DB::raw('count(*) as total_users'))
            ->where('status', 1)
            ->where('customer_type', 0)
            ->where('role', 3)
            ->first();
        $customerCount = DB::table('users')
            ->select(DB::raw('count(*) as total_users'))
            ->where('status', 1)
            ->where('customer_type', 1)
            ->where('role', 3)
            ->first();
        $staffCount = DB::table('users')
            ->select(DB::raw('count(*) as total_users'))
            ->where('status', 1)
            ->where('role', 5)
            ->first();

        if(Auth::guard('admin')->user()->role == '2'){
            $productCount = DB::table('products')
                ->select(DB::raw('count(*) as total_products'))
                ->where('vendor_id', Auth::guard('admin')->user()->id)
                ->where('status', 1)
                ->first();
            //return $productCount;

            if($vendor){
                $productCount = DB::table('products')
                    ->select(DB::raw('count(*) as total_products'))
                    ->where('vendor_id', $vendor->user_id)
                    ->where('status', 1)
                    ->first();
                //return $productCount;
            }
        }else{
            $productCount = DB::table('products')
                ->select(DB::raw('count(*) as total_products'))
                ->where('status', 1)
                ->first();
            //return $productCount;
        }

        $categoryCount = DB::table('categories')
            ->select(DB::raw('count(*) as total_categories'))
            ->where('status', 1)
            ->first();

        $brandCount = DB::table('brands')
            ->select(DB::raw('count(*) as total_brands'))
            ->where('status', 1)
            ->first();

        $vendorCount = DB::table('vendors')
            ->select(DB::raw('count(*) as total_vendors'))
            ->where('status', 1)
            ->first();

        $AllOrderCount = DB::table('orders')
            ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
            ->first();
        
        $id = Auth::guard('admin')->user()->id;
        $staffData = User::find($id);
        $staffUserId = Staff::where('user_id', $staffData->id)->pluck('id')->first(); // Use first() to get the actual value
        
        $staffOrderCount = Order::where('staff_id', $staffUserId)
            ->selectRaw('count(*) as total_orders, sum(sub_total) as total_sell')
            ->first();


        //return $staffOrderCount;
            
        $WebOrderCount = DB::table('orders')->where('order_by',0)
            ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
            ->first();
        $vatRate = 5;
        $WebOrderVat = $WebOrderCount->total_sell * $vatRate / 100;
        $WebOrderCount1 = $WebOrderCount->total_sell - $WebOrderVat;
        //dd($WebOrderCount);
        $WebOrderToday = DB::table('orders')->where('order_by',0)->whereDate('created_at', Carbon::today())
        ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
        ->first();
        $WebOrderThisMonth = DB::table('orders')->where('order_by',0)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)
        ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
        ->first();
        $WebOrderPreviousMonth = DB::table('orders')->where('order_by',0)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->subMonth()->month)
        ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
        ->first();
        $WebOrderThisYear = DB::table('orders')->where('order_by',0)->whereYear('created_at', Carbon::now()->year)
        ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
        ->first();
        $Webbuytoday=DB::table('orders')->where('order_by',0)->whereDate('created_at',Carbon::today())->whereMonth('created_at',Carbon::now()->month)
             ->select(DB::raw('count(*) as total_orders, sum(totalbuyingPrice) as total_buy'))
             ->first();
        $WebbuyThisMonth=DB::table('orders')->where('order_by',0)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at',Carbon::now()->month)
             ->select(DB::raw('count(*) as total_orders , sum(totalbuyingPrice) as total_buy'))
             ->first();
        $WebbuyPreviousMonth =DB::table('orders')->where('order_by',0)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth()->month)->select(DB::raw('count(*) total_orders, sum(totalbuyingPrice) as total_buy'))->first();
        $WebbuyThisYear = DB::table('orders')->where('order_by',0)->whereYear('created_at',Carbon::now()->year)
             ->select(DB::raw('count(*) as total_orders, sum(totalbuyingPrice) as total_buy'))->first();



        //pos
        $PosorderCount = DB::table('orders')->where('order_by',1)
            ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
            ->first();
        $PosOrderToday = DB::table('orders')->where('order_by',1)->whereDate('created_at', Carbon::today())
        ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
        ->first();
        $PosOrderThisMonth = DB::table('orders')->where('order_by',1)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)
        ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
        ->first();
        $PosOrderPreviousMonth = DB::table('orders')->where('order_by',1)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->subMonth()->month)
        ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
        ->first();
        $PosOrderThisYear = DB::table('orders')->where('order_by',1)->whereYear('created_at', Carbon::now()->year)
        ->select(DB::raw('count(*) as total_orders, sum(sub_total) as total_sell'))
        ->first();
        $PosbuyToday = DB::table('orders')->where('order_by',1)->whereDate('created_at', Carbon::today())
        ->select(DB::raw('count(*) as total_orders, sum(totalbuyingPrice) as total_buy'))
        ->first();
        $PosbuyThisMonth =DB::table('orders')->where('order_by',1)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->month)->select(DB::raw('count(*) as total_orders ,sum(totalbuyingPrice) as total_buy'))
        ->first();
        $PosbuyPreviousMonth =DB::table('orders')->where('order_by',1)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',Carbon::now()->subMonth()->month)
        ->select(DB::raw('count(*) as total_orders,sum(totalbuyingPrice) as total_buy'))
        ->first();
        $PosbuyThisYear = DB::table('orders')->where('order_by',1)->whereYear('created_at', Carbon::now()->year)
        ->select(DB::raw('count(*) as total_orders, sum(totalbuyingPrice) as total_buy'))
        ->first();

        $StockCount = DB::table('products')->where('status',1)
            ->select(DB::raw('sum(stock_qty) as stock_qty'))
            ->first();
            
        if($vendor){
            $lowStockCount = DB::table('product_stocks as s')
                ->leftjoin('products as p', 's.product_id', '=', 'p.id')
                ->select(DB::raw('count(s.id) as total_low_stocks'))
                ->where('p.vendor_id', $vendor->id)
                ->where('s.qty', '<=', 5)
                ->first();
        }
        $products=Product::whereColumn('low_qty', '>=', 'stock_qty')->latest()->get();
        $countLowProducts = Product::whereColumn('low_qty', '>=', 'stock_qty')->count();
        
        //vendor wallet
        $wallet = OrderDetail::where('vendor_id', Auth::guard('admin')->user()->id)->pluck('order_id')->toArray();
        $walletValue = Order::whereIn('id', $wallet)->sum('grand_total');
        //return $walletValue;

        //vendor commission
        $orderID = Order::whereIn('id', $wallet)->pluck('id');
        $matchedorderID = OrderDetail::whereIn('order_id', $orderID)->get();  
        $commissionValue = $matchedorderID->sum('v_comission');
        //return $walletValue;

        //vendor wallet Value
        $vendorWalletValue = $walletValue - $commissionValue;
        //return $vendorWalletValue;

        //cash withdraw Value
        $withdraw = Cashwithdraw::where('vendor_id', Auth::guard('admin')->user()->id)->get();
        $withdraw_ammount = $withdraw->where('status', 1)->sum('amount');

    	return view('admin.index', compact('userCount', 'productCount', 'categoryCount', 'brandCount', 'vendorCount','AllOrderCount', 'WebOrderCount', 'WebOrderCount1', 'WebOrderToday','WebOrderThisMonth','WebOrderPreviousMonth','WebOrderThisYear','customerCount','PosorderCount','PosOrderToday','PosOrderThisMonth','PosOrderPreviousMonth','PosOrderThisYear','StockCount','PosbuyToday','PosbuyThisMonth','PosbuyPreviousMonth','PosbuyThisYear','Webbuytoday','WebbuyThisMonth','WebbuyPreviousMonth','WebbuyThisYear','products','countLowProducts','staffCount','vendorWalletValue', 'withdraw_ammount', 'withdraw', 'staffOrderCount', 'WebOrderVat'));
    } // end method

    /*=================== End Dashboard Methoed ===================*/

    /*=================== Start Admin Login Methoed ===================*/
    public function Login(Request $request){

    	$this->validate($request,[
    		'email' =>'required',
    		'password' =>'required'
    	]);

    	// dd($request->all());
    	$check = $request->all();
    	if(Auth::guard('admin')->attempt(['email' => $check['email'], 'password'=> $check['password'] ])){

            if(Auth::guard('admin')->user()->role == "1" || Auth::guard('admin')->user()->role == "5" || Auth::guard('admin')->user()->role == "2"){
                return redirect()->route('admin.dashboard')->with('success','Admin Login Successfully.');
            }else{
                $notification = array(
                    'message' => 'Invaild Email Or Password.',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }

    	}else{
            $notification = array(
                'message' => 'Invaild Email Or Password.',
                'alert-type' => 'error'
            );
    		return back()->with($notification);
    	}

    } // end method

    /*=================== End Admin Login Methoed ===================*/

    /*=================== Start Logout Methoed ===================*/
    public function AminLogout(Request $request){

    	Auth::guard('admin')->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $notification = array(
            'message' => 'Admin Logout Successfully.',
            'alert-type' => 'success'
        );
    	return redirect()->route('login_form')->with($notification);
    } // end method
    /*=================== End Logout Methoed ===================*/

    /*=================== Start AdminRegister Methoed ===================*/
    public function AdminRegister(){

    	return view('admin.admin_register');
    } // end method
    /*=================== End AdminRegister Methoed ===================*/

     /*=================== Start AdminForgotPassword Methoed ===================*/
    public function AdminForgotPassword(){

        return view('admin.admin_forgot_password');
    } // end method
    /*=================== End AdminForgotPassword Methoed ===================*/

    /*=================== Start AdminRegisterStore Methoed ===================*/
    public function AdminRegisterStore(Request $request){

    	$this->validate($request,[
    		'name' =>'required',
    		'email' =>'required',
    		'password' =>'required',
    		'password_confirmation' =>'required'
    	]);
    	// dd($request->all());
    	User::insert([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => Hash::make($request->password),
    		'created_at' => Carbon::now(),
    	]);
    	return redirect()->route('login_form')->with('success','Admin Created Successfully.');
    } // end method
    /*=================== End AdminRegisterStore Methoed ===================*/

    /* =============== Start Profile Method ================*/
    public function Profile(){
        $id = Auth::guard('admin')->user()->id;
        $adminData = User::find($id);

        // dd($adminData);
        return view('admin.admin_profile_view',compact('adminData'));

    }// End Method

    /* =============== Start EditProfile Method ================*/
    public function EditProfile(){

        $id = Auth::guard('admin')->user()->id;
        $editData = User::find($id);
        return view('admin.admin_profile_edit',compact('editData'));
    }//

     /* =============== Start StoreProfile Method ================*/
    public function StoreProfile(Request $request){
        $id = Auth::guard('admin')->user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('profile_image')) {
           $file = $request->file('profile_image');

           $filename = date('YmdHi').$file->getClientOriginalName();
           $file->move(public_path('upload/admin_images'),$filename);
           $data['profile_image'] = $filename;
        }
        $data->save();

        Session::flash('success','Admin Profile Updated Successfully');

        return redirect()->route('admin.profile');

    }// End Method

    /* =============== Start ChangePassword Method ================*/
    public function ChangePassword(){

        return view('admin.admin_change_password');

    }//

    /* =============== Start UpdatePassword Method ================*/
    public function UpdatePassword(Request $request){

        $validateData = $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required',
            'confirm_password' => 'required|same:newpassword',

        ]);

        $hashedPassword = Auth::guard('admin')->user()->password;

        // dd($hashedPassword);
        if (Hash::check($request->oldpassword,$hashedPassword )) {
            $id = Auth::guard('admin')->user()->id;
            $admin = User::find($id);
            $admin->password = bcrypt($request->newpassword);
            $admin->save();

            Session::flash('success','Password Updated Successfully');
            return redirect()->back();
        }else{
            Session::flash('error','Old password is not match');
            return redirect()->back();
        }

    }// End Method

    /* =============== Start clearCache Method ================*/
    function clearCache(Request $request){
        Artisan::call('optimize:clear');
        Session::flash('success','Cache cleared successfully.');
        return redirect()->back();
    } // end method

    /* =============== End clearCache Method ================*/
}