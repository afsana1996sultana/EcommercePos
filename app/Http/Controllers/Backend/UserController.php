<?php

namespace App\Http\Controllers\Backend;

use Image;
use Session;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\Setting;
use App\Imports\UsersImport;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Exports\PosUserExport;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Exports\OnlineUserExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Frontend\PathaoController;
use Maatwebsite\Excel\Validators\ValidationException;

class UserController extends Controller
{
    protected $users;
    public function __construct()
    {
        $this->users = User::query();
    }
    public function index()
    {
        $startIndex=0;
        $customers = $this->users->where('role', 3)->where('customer_type',1)->orderBy('id', 'desc')->paginate(100);
        $count =$this->users->where('role', 3)->where('customer_type',1)->count();
        $setting=Setting::where('name','premium_membership')->first();
        $member=$setting->value;
    	return view('backend.customer.index',compact('customers','member','count','startIndex'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'name'                  => 'required',
            'username'              => 'required',
            'email'                 => 'nullable|email|max:191|unique:users',
            'address'               => 'required',
            'phone'                 => ['required','regex:/(\+){0,1}(88){0,1}01(3|4|5|6|7|8|9)(\d){8}/','digits:11','unique:users'],
            'profile_image'         => 'nullable',
            'status'                => 'nullable',
            'division_id'           => 'required',
            'district_id'           => 'required',
            'upazilla_id'           => 'required',
        ]);

        if($request->hasfile('profile_image')){
            $image = $request->file('profile_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(160,160)->save('upload/admin_images/'.$name_gen);
            $save_url = 'upload/admin_images/'.$name_gen;
        }else{
            $save_url = '';
        }

        $customer = new User();
        $customer->name = $request->name;
        $customer->username = $request->username;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->password =  Hash::make("12345678");
        $customer->profile_image = $save_url;
        $customer->role = 3;
        $customer->status = $request->status;
        $customer->customer_type = 1;
        $customer->save();
        $address= new Address;
        $address->division_id = $request->division_id;
        $address->district_id = $request->district_id;
        $address->upazilla_id = $request->upazilla_id;
        $address->address = $request->address;
        $address->user_id = $customer->id;
        $address->is_default = 0;
        $address->status = 1;
        $address->save();
		Session::flash('success','Customer Create Successfully');
		return redirect()->route('customer.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = User::findOrFail($id);
        $orders = Order::where('user_id', $id)->get();
        $payments = OrderPayment::where('user_id', $id)->get();
    	return view('backend.customer.show',compact('customer','orders','payments'));
    }
    public function onlineUserdetails($id)
    {
        $customer = User::findOrFail($id);
        $orders = Order::where('user_id', $id)->get();
        $payments = OrderPayment::where('user_id', $id)->get();
    	return view('backend.customer.onlineuser_details',compact('customer','orders','payments'));
    }
    public function customerPrint(){
        $customers = User::where('role', 3)->where('customer_type',1)->latest()->get();
        return view('backend.customer.customer_print',compact('customers'));
    }
    public function online_user_Print(){
        $customers = User::where('role', 3)->where('customer_type',0)->latest()->get();
        return view('backend.customer.customer_print',compact('customers'));
    }

    public function customerOrderPrint($id)
    {
        $order = Order::findOrFail($id);
        return view('backend.customer.order_report_print',compact('order'));
    }

    public function customerPaymentPrint($id)
    {
        $payment = OrderPayment::findOrFail($id);
        return view('backend.customer.payment_report_print',compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = User::findOrFail($id);
        $address=Address::where('user_id',$customer->id)->first();
    	return view('backend.customer.edit',compact('customer','address'));
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

        $customer = User::find($id);
        $this->validate($request,[
            'name'                  => 'required',
            'address'               => 'required',
            'phone'                 => ['required','regex:/(\+){0,1}(88){0,1}01(3|4|5|6|7|8|9)(\d){8}/','digits:11',Rule::unique('users')->ignore($customer->id),],
            'status'                => 'nullable',
            'division_id'           => 'required',
            'district_id'           => 'required',
            'upazilla_id'           => 'required',
        ]);

        if($request->hasfile('profile_image')){
            try {
                if(file_exists($customer->profile_image)){
                    unlink($customer->profile_image);
                }
            } catch (Exception $e) {

            }
            $profile_image = $request->profile_image;
            $profile_save = time().$profile_image->getClientOriginalName();
            $profile_image->move('upload/admin_images/',$profile_save);
            $customer->profile_image = 'upload/admin_images/'.$profile_save;
        }else{
            $profile_save = '';
        }

        $customer->name = $request->name;
        $customer->username = $request->username;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->role = 3;
        $customer->status = $request->status;
        $customer->membership = $request->membership? 1 : 0;
        $customer->save();
        $address=Address::where('user_id',$customer->id)->first();
        if($address){
            $address->division_id = $request->division_id;
            $address->district_id = $request->district_id;
            $address->upazilla_id = $request->upazilla_id;
            $address->address = $request->address;
            $address->user_id = $customer->id;
            $address->is_default = 0;
            $address->status = 1;
            $address->save();
        }else{
        $address = new Address;
        $address->division_id = $request->division_id;
        $address->district_id = $request->district_id;
        $address->upazilla_id = $request->upazilla_id;
        $address->address = $request->address;
        $address->user_id = $customer->id;
        $address->is_default = 0;
        $address->status = 1;
        $address->save();
        }

		Session::flash('success','Customer Update Successfully');
		return redirect()->back();
    }

    public function admin_update_user_pass(Request $request, $id)
    {
        $this->validate($request, [
            'password'           => 'required|min:8',
        ]);
        $customer = User::find($id);
        $customer->password = bcrypt($request->password);
        $customer->save();
        Session::flash('success', 'Password Updated Successfully');
        return redirect()->back();
    }

    public function update_pass(Request $request, $id)
    {
        $this->validate($request,[
            'oldpassword'           => 'required',
            'newpassword'           => 'required',
            'confirm_password'      => 'required|same:newpassword',
        ]);
        $customer = User::find($id);
        $hashedPassword = $customer->password;
        //  dd($hashedPassword);
        if (Hash::check($request->oldpassword,$hashedPassword )) {
            $customer->password = bcrypt($request->newpassword);
            $customer->save();

            Session::flash('success','Password Updated Successfully');
            return redirect()->back();
        }else{
            Session::flash('error','Old password is not match');
            return redirect()->back();
        }

    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        if ($customer) {
            try {
                if (file_exists($customer->profile_image)) {
                    unlink($customer->profile_image);
                }
            } catch (Exception $e) {
                // Handle exception if necessary
            }

            $customer->delete();
            // Delete associated addresses
            $address = Address::where('user_id', $id)->first();
            if ($address) {
                $address->delete();
            }
            $notification = array(
                'message' => 'Customer Deleted Successfully.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } else {
            // Handle case where user is not found
            $notification = array(
                'message' => 'Customer not found.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function status($id)
    {
        $customer = User::find($id);
        if($customer->status == 1){
            $customer->status = 0;
        }else{
            $customer->status = 1;
        }
        $customer->save();
        $notification = array(
            'message' => 'Customer Feature Status Changed Successfully.',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function online_user_list(){
        $startIndex=0;
        $customers =$this->users->where('role', 3)->where('customer_type',0)->orWhereNull('customer_type')->orderBy('id', 'desc')->paginate(100);
        $count =$this->users->where('role', 3)->where('customer_type',0)->count();
        $setting=Setting::where('name','premium_membership')->first();
        $member=$setting->value;
    	return view('backend.customer.onlineUser',compact('customers','member','count','startIndex'));
    }
    public function online_user_export(){
        return Excel::download(new OnlineUserExport, 'onlineCustomer.csv');
    }
    public function pos_user_export(){
        return Excel::download(new PosUserExport, 'posCustomer.csv');
    }
    public function import(Request $request)
    {
        $this->validate($request,[
            'file'           => 'required|file',
        ]);
        $file = $request->file('file');
        $mime = $file->getMimeType();
       try {
        Excel::import(new UsersImport, $request->file('file'));
        return redirect()->back()->with('success', 'User Imported Successfully');
        } catch (ValidationException $e) {
            //return redirect()->back()->withErrors($e->errors())->withInput();
            return redirect()->back()->with('error', 'There Are Some Error Please Check');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function user_pagination(Request $request)
    {
        if ($request->ajax()) {
            $condition = $request->get('condition');
            $data = $request->search;
            $setting=Setting::where('name','premium_membership')->first();
            $member=$setting->value;
            // Prepare base query
            $query = $this->users;
            if ($condition == 'posUser') {
                $query->where('role', 3)->where('customer_type',1);
                $html = 'backend.customer.customer_table';
            }else {
                $query->where('role', 3)->where('customer_type',0);
                $html = 'backend.customer.user_table';
            }
            // Apply filters
            if ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('name', 'LIKE', '%' . $data . '%')
                    ->orwhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('address', 'LIKE', '%' . $data . '%');
                });
            }
            $customers = $query->orderBy('id', 'desc')->paginate(100);
            $page = $request->input('page', 1);
            $startIndex = ($page - 1) * 100;
            return view($html, compact('customers', 'startIndex','member'))->render();
        }
    }

    public function user_search(Request $request)
    {
        $data = $request->search;
        $type = $request->type;
        $query = $this->users->newQuery();
        $setting=Setting::where('name','premium_membership')->first();
        $member=$setting->value;
        if ($data) {
            $query->where(function ($q) use ($data) {
                $q->where('name', 'LIKE', '%' . $data . '%')
                    ->orwhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('address', 'LIKE', '%' . $data . '%');
            });
        }
        switch ($type) {
            case 'posUser':
                $query->where('role', 3)->where('customer_type',1);
                $html = 'backend.customer.customer_table';
                break;
            default:
                $query->where('role', 3)->where('customer_type',0);
                $html = 'backend.customer.user_table';
                break;
        }
        $customers = $query->orderBy('id', 'desc')->paginate(100);
        $page = $request->input('page', 1);
        $startIndex = ($page - 1) * 100;
        return view($html, compact('customers', 'startIndex','member'));
    }
}
