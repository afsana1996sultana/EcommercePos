@extends('admin.admin_master')
@section('admin')
<?php
    use App\Models\User;
    use App\Models\Order;
    use App\Models\OrderDetail;
    use App\Models\Cashwithdraw;
    $id = Auth::guard('admin')->user()->id;
    $adminData = User::find($id);

    //vendor wallet
    $wallet = OrderDetail::where('vendor_id', Auth::guard('admin')->user()->id)->pluck('order_id')->toArray();
    $walletValue = Order::whereIn('id', $wallet)->sum('grand_total');

    //vendor commission
    $orderID = Order::whereIn('id', $wallet)->pluck('id');
    $matchedorderID = OrderDetail::whereIn('order_id', $orderID)->get();
    $commissionValue = $matchedorderID->sum('v_comission');

    //vendor wallet Value
    $vendorWalletValue = $walletValue - $commissionValue;
    
    //cash withdraw Value
    $withdraw = Cashwithdraw::where('vendor_id', Auth::guard('admin')->user()->id)->get();
    $withdraw_ammount = $withdraw->where('status', 1)->sum('amount');
    
    $leftWalletValue = $vendorWalletValue - $withdraw_ammount;
    //dd($withdraw_ammount);
?>
<section class="content-main">
    <div class="row justify-content-center">
    	<div class="col-sm-8">
    		<div class="card">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Cash Withdraw Option</h3>
                    </div>
                    <div class='mt-5 '>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="card single-withdraw">
                                    <div class="withdraw-photo">
                                        <img src="{{ asset('upload/withdraw/bkash.png') }}"
                                            class="card-img-top" alt="bkash" style="width: 75% !important;">
                                    </div>

                                    <div class="card-body">
                                        @if($leftWalletValue != 0)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Bkash">Apply</button>
                                        @else
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Bkash" disabled>Apply</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="card single-withdraw">
                                    <div class="withdraw-photo">
                                        <img src="{{ asset('upload/withdraw/nagod.png') }}"
                                            class="card-img-top" alt="nagod" style="width: 75% !important;">
                                    </div>

                                    <div class="card-body">
                                        @if($leftWalletValue != 0)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Nogod">Apply</button>
                                        @else
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Nogod" disabled>Apply</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="card single-withdraw">
                                    <div class="withdraw-photo">
                                        <img src="{{ asset('upload/withdraw/transaction.png') }}"
                                            class="card-img-top" alt="transaction" style="width: 75% !important;">
                                    </div>

                                    <div class="card-body">
                                        @if($leftWalletValue != 0)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bank">Apply</button>
                                        @else
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bank" disabled>Apply</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="card single-withdraw">
                                    <div class="withdraw-photo">
                                        <img src="{{ asset('upload/withdraw/cash.png') }}"
                                            class="card-img-top" alt="cash" style="width: 75% !important;">
                                    </div>

                                    <div class="card-body">
                                        @if($leftWalletValue != 0)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cash">Apply</button>
                                        @else
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cash" disabled>Apply</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
		        <!-- card body .// -->
		    </div>
		    <!-- card .// -->
    	</div>
    </div>
</section>
{{-- model for BKash--}}
<div class="modal fade" id="Bkash" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cash Withdraw from Bkash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="page-content pt-10 pb-10">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 m-auto">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="login_wrap widget-taber-content background-white">
                                            <div class="padding_eight_all bg-white">
                                                <form method="POST" action="{{route('cash.withdraw')}}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="input-group-sm col-md-6 mb-3">
                                                            <label for="name" class="fw-900">Name: <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" id="name" value="{{ $adminData->name }}" required readonly/>
                                                        </div>

                                                        <div class="form-group col-md-6 mb-3">
                                                            <label for="phone" class="fw-900">Phone:<span class="text-danger">*</span></label>
                                                            <input type="text" name="phone" class="form-control" id="phone" value="{{ $adminData->phone }}" required readonly/>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6 mb-3">
                                                            <label for="address" class="fw-900">Address : <span class="text-danger">*</span></label>
                                                            <input type="text" name="address" class="form-control" id="address" value="{{ $adminData->address }}" required readonly/>
                                                        </div>

                                                        <div class="form-group col-md-6 mb-3">
                                                            <label for="transition_number" class="fw-900">BKash Number: <span class="text-danger">*</span></label>
                                                            <input type="text" name="transition_number" class="form-control" id="transition_number" placeholder="017XX-XXXXXX" required/>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="account_type" class="fw-900">Account Type:<span class="text-danger">*</span></label>
                                                            <div class="custom_select">
                                                                <select class="form-control" name="account_type" id="account_type" required>
                                                                    <option value="">Select Type</option>
                                                                    <option value="personal">personal</option>
                                                                    <option value="Agent">Agent</option>
                                                                    <option value="Marchent">Marchent</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="amount" class="fw-900">Amount<span class="text-danger">*</span></label>
                                                            <input type="number" name="amount" class="form-control" id="amount" value="" required/>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" name="method" id="method" value="Bkash" required hidden/>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="number" name="user_id" id="user_id" value="{{ $adminData->id }}" required hidden/>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="number" name="user_type" id="user_type" value="0" required hidden/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-30 mt-20">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- model for Nagad --}}
<div class="modal fade" id="Nogod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cash Withdraw from Nagad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="page-content pt-10 pb-10">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 m-auto">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="login_wrap widget-taber-content background-white">
                                            <div class="padding_eight_all bg-white">
                                                <form method="POST" action="{{route('cash.withdraw')}}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="input-group-sm col-md-6 mb-3">
                                                            <label for="name" class="fw-900">Name: <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" id="name" value="{{ $adminData->name }}" required readonly/>
                                                        </div>

                                                        <div class="form-group col-md-6 mb-3">
                                                            <label for="phone" class="fw-900">Phone:<span class="text-danger">*</span></label>
                                                            <input type="text" name="phone" class="form-control" id="phone" value="{{ $adminData->phone }}" required readonly/>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6 mb-3">
                                                            <label for="address" class="fw-900">Address : <span class="text-danger">*</span></label>
                                                            <input type="text" name="address" class="form-control" id="address" value="{{ $adminData->address }}" required readonly/>
                                                        </div>

                                                        <div class="form-group col-md-6 mb-3">
                                                            <label for="transition_number" class="fw-900">Nagad Number: <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="transition_number" id="transition_number" placeholder="017XX-XXXXXX" required/>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="account_type" class="fw-900">Account Type:<span class="text-danger">*</span></label>
                                                            <div class="custom_select">
                                                                <select class="form-control" name="account_type" id="account_type" required>
                                                                    <option value="">Select Type</option>
                                                                    <option value="personal">personal</option>
                                                                    <option value="Agent">Agent</option>
                                                                    <option value="Marchent">Marchent</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="amount" class="fw-900">Amount<span class="text-danger">*</span></label>
                                                            <input type="number" name="amount" class="form-control" id="amount" value="" required/>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" name="method" id="method" value="Nagad" required hidden/>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="number" name="user_id" id="user_id" value="{{ $adminData->id }}" required hidden/>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="number" name="user_type" id="user_type" value="0" required hidden/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-30 mt-20">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- model for bank --}}
<div class="modal fade" id="bank" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cash Withdraw from Bank</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="page-content pt-10 pb-10">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 m-auto">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="login_wrap widget-taber-content background-white">
                                        <div class="padding_eight_all bg-white">
                                            <form method="POST" action="{{route('cash.withdraw')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="input-group-sm col-md-6 mb-3">
                                                        <label for="name" class="fw-900">Name: <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control" id="name" value="{{ $adminData->name }}" required readonly/>
                                                    </div>

                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="phone" class="fw-900">Phone:<span class="text-danger">*</span></label>
                                                        <input type="text" name="phone" class="form-control" id="phone" value="{{ $adminData->phone }}" required readonly/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="address" class="fw-900">Address : <span class="text-danger">*</span></label>
                                                        <input type="text" name="address" class="form-control" id="address" value="{{ $adminData->address }}" required readonly/>
                                                    </div>

                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="bank_name" class="fw-900">Bank Name: <span class="text-danger">*</span></label>
                                                        <input type="text" name="bank_name" class="form-control" id="bank_name" placeholder="Bank Name" required/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="bank_brunch" class="fw-900">Bank Brunch: <span class="text-danger">*</span></label>
                                                        <input type="text" name="bank_brunch" class="form-control" id="bank_brunch" placeholder="Bank Brunch" required/>
                                                    </div>

                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="account_no" class="fw-900">Account No: <span class="text-danger">*</span></label>
                                                        <input type="text" name="account_no" class="form-control" id="account_no" placeholder="Account No" required/>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="account_holder_name" class="fw-900">Account Holder Name: <span class="text-danger">*</span></label>
                                                        <input type="text" name="account_holder_name" class="form-control" id="account_holder_name" placeholder="Account Holder Name" required  maxlength="100"/>
                                                    </div>

                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="account_type" class="fw-900">Account Type:<span class="text-danger">*</span></label>
                                                        <div class="custom_select">
                                                            <input type="text" name="account_type" class="form-control" id="account_type" placeholder="Account Type" required/>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="amount" class="fw-900">Amount<span class="text-danger">*</span></label>
                                                        <input type="number" name="amount" class="form-control" id="amount" value="" required/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <input type="text" name="method" id="method" value="Bank" required hidden/>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="number" name="user_id" id="user_id" value="{{ $adminData->id }}" required hidden/>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="number" name="user_type" id="user_type" value="0" required hidden/>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-30 mt-20">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

{{-- Model for cash --}}
<div class="modal fade" id="cash" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cash Withdraw</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="page-content pt-10 pb-10">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 m-auto">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="login_wrap widget-taber-content background-white">
                                        <div class="padding_eight_all bg-white">
                                            <form method="POST" action="{{route('cash.withdraw')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="input-group-sm col-md-6 mb-3">
                                                        <label for="name" class="fw-900">Name: <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control" id="name" value="{{ $adminData->name }}" required readonly/>
                                                    </div>

                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="phone" class="fw-900">Phone:<span class="text-danger">*</span></label>
                                                        <input type="text" name="phone" class="form-control" id="phone" value="{{ $adminData->phone }}" required readonly/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="address" class="fw-900">Address : <span class="text-danger">*</span></label>
                                                        <input type="text" name="address" class="form-control" id="address" value="{{ $adminData->address }}" required readonly/>
                                                    </div>

                                                    <div class="form-group col-md-6 mb-3">
                                                        <label for="amount" class="fw-900">Amount<span class="text-danger">*</span></label>
                                                        <input type="number" name="amount" class="form-control" id="amount" value="" required/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-12">
                                                        <label for="purpose" class="fw-900">Purpose:<span class="text-danger">*</span></label>
                                                        <input type="text" name="purpose" id="purpose" class="form-control" placeholder="Enter Parpous" required/>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <input type="text" name="method" id="method" value="Cash" required hidden />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="number" name="user_id" id="user_id" value="{{ $adminData->id }}" required hidden/>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="number" name="user_type" id="user_type" value="0" required hidden/>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-30 mt-20">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection