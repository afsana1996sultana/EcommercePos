@extends('admin.admin_master')
@section('admin')
<style>
 .pass-btn-style{
     color: white;
    background: #049f04;
    padding: 3px 8px;
    border-radius: 5px;
    font-weight: bold;
 }
 .pas-btn {
    padding: 10px 0px;
}
</style>
<section class="content-main">
    <div>
        <h6 class="mb-2">User Name: <span class="badge rounded-pill alert-success" style="font-size:16px"> {{ $customer->name }} </span></h6>
        <h6 class="mb-2">Phone:{{ $customer->phone }}</h6>
        <h6 class="mb-2">Email:{{ $customer->email }}</h6>
        @php
            $address = App\Models\Address::where('user_id', $customer->id)->first();
            if($address){
                $district=App\Models\District::where('id',$address->district_id)->first();
                $upazilla=App\Models\Upazilla::where('id',$address->upazilla_id)->first();
            }
        @endphp
        <h6 class="mb-2">Address:{{ $customer->address }} ,{{ ucwords($upazilla->name_en ?? '') }},{{ ucwords($district->district_name_en ?? '') }}</h6>
    </div>
    <div class="pas-btn">
        <a data-bs-toggle="modal" data-bs-target="#staticBackdrop1{{ $customer->id }}"
            class="pass-btn-style">Edit Password</a>
    </div>
    <div class="content-header">
        <h2 class="content-title">User Order</h2>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive-sm">
               <table id="example" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl</th>
                            <th scope="col">Invoice No</th>
                            <th scope="col">Paid Amount</th>
                            <th scope="col">Due Amount</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Transection Amount</th>
                            @if(Auth::guard('admin')->user()->role != '2')
                                <th scope="col" class="text-end">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $order->invoice_no ?? '' }} </td>
                                <td> {{ $order->paid_amount ?? '' }} </td>
                                <td> {{ $order->due_amount ?? '' }} </td>
                                <td> {{ $order->grand_total ?? '' }} </td>
                                <td> {{ $order->transaction_no ?? '-' }} </td>
                                <td class="text-end">
                                    <a href="{{ route('customer.order.print',$order->id) }}" class="btn btn-md rounded font-sm"><i class="fa-solid fa-download"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive //end -->
        </div>
        <!-- card-body end// -->
    </div>
    @if(count($payments) > 0)
    <div class="content-header">
        <h2 class="content-title">User Payment</h2>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive-sm">
               <table id="example" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl</th>
                            <th scope="col">Invoice No</th>
                            <th scope="col">Paid Amount</th>
                            <th scope="col">Due Amount</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Payment Method</th>
                            <th scope="col">Transection No</th>
                            @if(Auth::guard('admin')->user()->role != '2')
                                <th scope="col" class="text-end">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $key => $payment)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $payment->invoice_no ?? '' }} </td>
                                <td> {{ $payment->paid ?? '' }} </td>
                                <td> {{ $payment->due ?? '' }} </td>
                                <td> {{ $payment->amount ?? '' }} </td>
                                <td> {{ $payment->payment_method ?? '' }} </td>
                                <td> {{ $payment->transaction_num ?? '-' }} </td>
                                <td class="text-end">
                                    <a href="{{ route('customer.payment.print',$payment->id) }}" class="btn btn-md rounded font-sm"><i class="fa-solid fa-download"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive //end -->
        </div>
        <!-- card-body end// -->
    </div>
    @endif
</section>
<!-- Customer Edit Modal -->
  <div class="modal fade" id="staticBackdrop1{{ $customer->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit PassWord</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('admin.update.user.pass', $customer->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="division_id" class="fw-bold text-black col-form-label"><span
                                    class="text-danger">*</span> New Password (minimum 8 )</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter new password"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection