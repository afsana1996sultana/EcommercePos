@extends('admin.admin_master')
@section('admin')
    <style type="text/css">
        table,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td {
            border: 1px solid #dee2e6 !important;
        }

        th {
            font-weight: bolder !important;
        }

        .icontext .icon i {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        .select2-container--default .select2-selection--single {
            background-color: #f9f9f9;
            border: 2px solid #eee;
            border-radius: 0 !important;
        }
    </style>
    <section class="content-main">
        <div class="content-header">
            <div>
                <h2 class="content-title card-title">Order detail</h2>
                {{-- <p>Details for Order ID: {{ $order->invoice_no ?? '' }}</p> --}}
            </div>
        </div>
        @if (!(Auth::guard('admin')->user()->role == '2'))
            @php
                $HasPermission =
                    Auth::guard('admin')->user()->role == '1' ||
                    in_array('62', json_decode(Auth::guard('admin')->user()->staff->role->permissions)) ||
                    in_array('63', json_decode(Auth::guard('admin')->user()->staff->role->permissions));
                $somePermission =
                    Auth::guard('admin')->user()->role == '1' ||
                    in_array('62', json_decode(Auth::guard('admin')->user()->staff->role->permissions));
            @endphp
        @endif
        <div class="card">
            <header class="card-header">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-4 mb-lg-0 mb-15">
                        <span class="text-white"> <i class="material-icons md-calendar_today"></i>
                           @php
                                $createdAt = new DateTime($order->created_at);
                                $formattedDate = $createdAt->format('j F Y');
                                $formattedTime = $createdAt->format('g.i A');
                           @endphp

                        <b>{{ $formattedDate ?? '' }}</b> , </span>  <span class="text-white"> <b>{{ $formattedTime ?? '' }}</b> </span> <br />
                        <small class="text-white">Order ID: {{ $order->invoice_no ?? '' }}</small>
                    </div>
                    @php
                        $payment_status = $order->payment_status;
                        $delivery_status = $order->delivery_status;
                    @endphp
                    @if (!(Auth::guard('admin')->user()->role == '2'))
                        <div class="col-lg-8 col-md-8 ms-auto text-md-end">
                            @if ($somePermission)
                                <select class="form-select d-inline-block mb-lg-0 mr-5 mw-200" id="update_payment_status">
                                    <option value="">-- select one --</option>
                                    <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>Unpaid</option>
                                    <option value="paid" @if ($payment_status == 'paid') selected @endif>Paid</option>
                                     @if ($order->payment_status == "partial paid")
                                     <option value="partial paid" @if ($payment_status == 'partial paid') selected @endif>Partial Paid</option>
                                     @endif
                                </select>
                            @endif
                            @if ($delivery_status != 'Delivered' && $delivery_status != 'Cancelled' && $delivery_status != 'Returned')
                                <select class="form-select d-inline-block mb-lg-0 mr-5 mw-200" id="update_delivery_status">
                                    <option value="Pending" @if ($delivery_status == 'Pending') selected @endif>Pending
                                    </option>
                                    <option value="Holding" @if ($delivery_status == 'Holding') selected @endif>Holding
                                    </option>
                                    <option value="Processing" @if ($delivery_status == 'Processing') selected @endif>Processing
                                    </option>
                                    <!--<option value="Shipped" @if ($delivery_status == 'Shipped') selected @endif>Shipped</option>-->
                                    @if (!(Auth::guard('admin')->user()->role == '2'))
                                        @if (Auth::guard('admin')->user()->role == '1' ||
                                                in_array('20', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                                            @if ($order->user_id != 1)
                                                <option value="Returned" @if ($delivery_status == 'Returned') selected @endif
                                                    style="color:red">Returned
                                                </option>
                                                <option value="Cancelled" @if ($delivery_status == 'Cancelled') selected @endif
                                                    style="color:red">Cancelled
                                                </option>
                                            @endif
                                        @endif
                                    @endif
                                </select>
                            @else
                                <input type="text" class="form-control d-inline-block mb-lg-0 mr-5 mw-200"
                                    value="{{ $delivery_status }}" disabled>
                            @endif
                             @if ($delivery_status != 'Delivered' && $delivery_status != 'Cancelled' && $delivery_status != 'Returned')
                                @if ($somePermission)
                                   <button class="btn btn-primary" style="background: green" id="deliveredStatus">Delivered</button>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </header>
            <!-- card-header end// -->
            <div class="card-body">
                <div class="row mt-20 order-info-wrap">
                    <div class="col-md-4">
                        <article class="icontext align-items-start">
                            <span class="icon icon-sm rounded-circle bg-primary-light">
                                <i class="text-primary material-icons md-person"></i>
                            </span>
                            <div class="text">
                                <h6 class="mb-1">Customer</h6>
                                <p class="mb-1">
                                    Name: {{ $order->user->name ?? '' }} <br />
                                    Email: {{ $order->user->email ?? '' }} <br />
                                    Phone:  @if(isset($order->user->phone))
                                                {{ $order->user->phone }}
                                            @else
                                                {{ $order->phone }}
                                            @endif
                                </p>
                                @if (!(Auth::guard('admin')->user()->role == '2'))
                                    <a data-bs-toggle="modal" data-bs-target="#staticBackdrop1{{ $order->user_id }}"
                                        style="color:blue">Edit Customer</a>
                                @endif
                            </div>
                        </article>
                    </div>
                    <!-- col// -->
                    <div class="col-md-4">
                        <article class="icontext align-items-start">
                            <span class="icon icon-sm rounded-circle bg-primary-light">
                                <i class="text-primary material-icons md-local_shipping"></i>
                            </span>
                            <div class="text">
                                <h6 class="mb-1">Order info</h6>
                                <p class="mb-1">
                                    Order Id: {{ $order->invoice_no ?? '' }} </br>
                                    {{-- Transection Number: {{ $order->transection_no ?? '0' }} </br> --}}
                                    Shipping: {{ $order->shipping_name ?? '' }} <br />
                                    Pay method: @if ($order->payment_method == 'cod')
                                        Cash On Delivery
                                    @else
                                        {{ $order->payment_method }}
                                    @endif <br />

                                    @if($order->trxID != NULL)
                                        TrxID: {{ $order->trxID }} <br/>
                                    @endif

                                    @if($order->transaction_no != NULL)
                                        Advance Transaction No: {{ $order->transaction_no }} <br/>
                                    @endif
                                    Status: @php
                                        $status = $order->delivery_status;
                                        if ($order->delivery_status == 'Delivered') {
                                            $status = '<span class="badge rounded-pill alert-success">Delivered</span>';
                                        }
                                        if ($order->delivery_status == 'Cancelled' || $order->delivery_status == 'Returned') {
                                            $status = '<span class="badge rounded-pill alert-danger">Cancelled</span>';
                                        }

                                    @endphp
                                    {!! $status !!}
                                </p>
                                   
                                
                                {{-- <a href="#">Download info</a> --}}
                            </div>
                        </article>
                    </div>
                    <!-- col// -->
                    <div class="col-md-4">
                        <article class="icontext align-items-start">
                            <span class="icon icon-sm rounded-circle bg-primary-light">
                                <i class="text-primary material-icons md-place"></i>
                            </span>
                            <div class="text">
                                <h6 class="mb-1">Deliver to</h6>
                                <p class="mb-1">
                                    Address: {{ $order->address ?? ''}}
                                </p>
                            <p style="display: inline;">Sale By:
                                @if($order->order_by == 1)
                                    @php
                                        $staff = App\Models\Staff::where('user_id', $order->staff_id)->first();
                                    @endphp
                                    <span style="font-size:18px;display: inline;font-weight:bold">
                                        {{ $staff ? $staff->user->name : 'Admin' }}
                                    </span>
                                @else
                                    <span style="font-size:18px;display: inline;font-weight:bold">
                                        Ecommerce
                                    </span>
                                @endif
                            </p>
                            </div>
                        </article>
                    </div>
                    <!-- col// -->
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="col-md-12 mt-40">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Invoice</th>
                                        <td>{{ $order->invoice_no ?? '' }}</td>
                                        <th>Email</th>
                                        <td><input type="" class="form-control" name="email"
                                                value="{{ $order->email ?? 'Null' }}"></td>
                                    </tr>
                                    <tr>
                                        <th class="col-2"><span class="text-danger">*</span> Division
                                        <td>
                                            <!--<label for="division_id" class="fw-bold text-black"></label>-->
                                            <select class="form-control select-active" name="division_id" id="division_id"
                                                required @if ($order->user_id == 1) disabled @endif>
                                                @if ($order->division_id > 0)
                                                @foreach (get_divisions($order->division_id) as $division)
                                                    <option value="{{ $division->id }}"
                                                        {{ $division->id == $order->division_id ? 'selected' : '' }}>
                                                        {{ ucwords($division->division_name_en) }}</option>
                                                @endforeach
                                                @else
                                                    <option value="">Select Division</option>
                                                    @foreach (get_divisions() as $division)
                                                    <option value="{{ $division->id }}">
                                                        {{ ucwords($division->division_name_en) }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <label for="district_id" class="fw-bold text-black"><span
                                                    class="text-danger">*</span> City</label>
                                        </td>
                                        <td>
                                            <select class="form-control select-active" name="district_id" id="district_id"
                                                required @if ($order->user_id == 1) disabled @endif>
                                                @if ($order->district_id > 0)
                                                    @foreach (get_district_by_division_id($order->division_id) as $district)
                                                        <option value="{{ $district->id }}"
                                                            {{ $district->id == $order->district_id ? 'selected' : '' }}>
                                                            {{ ucwords($district->district_name_en) }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Select City</option>
                                                @endif
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="upazilla_id" class="fw-bold text-black"><span
                                                class="text-danger">*</span>Zone</label>
                                        </td>
                                        <td>
                                            <select class="form-control select-active" name="upazilla_id" id="upazilla_id"
                                                @if ($order->user_id == 1) disabled @endif>
                                                @if ($order->upazilla_id > 0)
                                                    @foreach (get_upazilla_by_district_id($order->district_id) as $upazilla)
                                                        <option value="{{ $upazilla->id }}"
                                                            {{ $upazilla->id == $order->upazilla_id ? 'selected' : '' }}>
                                                            {{ ucwords($upazilla->name_en) }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Select Zone</option>
                                                @endif
                                            </select>
                                        </td>
                                        <th><span class="text-danger">*</span> House/Road/Area</th>
                                        <td>
                                            <input type="text" class="form-control" name="address"
                                                value="{{ $order->address ?? 'Null' }}"
                                                @if ($order->user_id == 1) readonly @endif>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method</th>
                                        <td>
                                            <select class="form-control select-active" name="payment_method"
                                                id="payment_method" @if ($order->user_id == 1) readonly @endif>
                                                <option value="cod" @if ($order->payment_method == 'cod') selected @endif>
                                                    Cash</option>
                                                <option value="bkash" @if ($order->payment_method == 'bkash') selected @endif>
                                                    Bkash</option>
                                                <option value="nagad" @if ($order->payment_method == 'nagad') selected @endif>
                                                    Nagad</option>
                                            </select>
                                        </td>
                                        <th>Payment Status</th>
                                        <td>
                                            @php
                                                $status = $order->delivery_status;
                                                if ($order->delivery_status == 'Delivered') {
                                                    $status =
                                                        '<span class="badge rounded-pill alert-success">Delivered</span>';
                                                }
                                                if ($order->delivery_status == 'Cancelled') {
                                                    $status =
                                                        '<span class="badge rounded-pill alert-danger">Cancelled</span>';
                                                }
                                            @endphp
                                            {!! $status !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Payment Date</th>
                                        <td>{{ date_format($order->created_at, 'Y/m/d') }}</td>
                                        @if (!(Auth::guard('admin')->user()->role == '2'))
                                            <th>Vendor Comission</th>
                                            <td>
                                                @php
                                                    $sum = 0;
                                                    $sum1 = 0;
                                                    $sum2 = 0;
                                                    $orderDetails = $order->order_details;
                                                    foreach ($orderDetails as $key => $orderDetail) {
                                                        $sum1 += $orderDetail->v_comission;
                                                        $sum2 += $orderDetail->qty;
                                                        $sum += $orderDetail->v_comission * $orderDetail->qty;
                                                    }
                                                @endphp
                                                {{ $sum ?? '' }}<strong>Tk</strong>
                                            </td>
                                        @endif
                                    </tr>
                                    @if (!(Auth::guard('admin')->user()->role == '2'))
                                        <tr>
                                            <th>Sub Total</th>
                                            <td>{{ $order->sub_total }} <strong>Tk</strong></td>

                                            <th>Total</th>
                                            <td>{{ $order->grand_total }} <strong>Tk</strong></td>
                                        </tr>
                                    @endif
                                    @if (!(Auth::guard('admin')->user()->role == '2'))
                                      @if($order->order_by==1)
                                        @if ($order->due_amount > 0)
                                            <tr>
                                                <th>Paid Amount</th>
                                                <td>{{ $order->paid_amount }} <strong>Tk</strong></td>

                                                <th>Due Amount</th>
                                                <td>{{ $order->due_amount }} <strong>Tk</strong></td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th>Paid Amount</th>
                                                <td>{{ $order->grand_total }} <strong>Tk</strong></td>

                                                <th>Due Amount</th>
                                                <td>{{ $order->due_amount }} <strong>Tk</strong></td>
                                            </tr>
                                        @endif
                                      @endif
                                      @if($order->order_by==0)
                                            @if ($order->payment_status == "partial paid")
                                                <tr>
                                                    <th>Paid Amount</th>
                                                    <td>{{ $order->shipping_charge }} <strong>Tk</strong></td>

                                                    <th>Due Amount</th>
                                                    <td>{{ $order->grand_total - $order->shipping_charge }} <strong>Tk</strong></td>
                                                </tr>

                                            @elseif($order->payment_status == "unpaid")
                                                <tr>
                                                    <th>Paid Amount</th>
                                                    <td>{{ $order->paid_amount ?? '0.00' }} <strong>Tk</strong></td>

                                                    <th>Due Amount</th>
                                                    <td>{{ $order->due_amount }} <strong>Tk</strong></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <th>Paid Amount</th>
                                                    <td>{{ $order->grand_total }} <strong>Tk</strong></td>

                                                    <th>Due Amount</th>
                                                    <td>{{ $order->due_amount ?? '0.00' }} <strong>Tk</strong></td>
                                                </tr>
                                            @endif
                                       @endif
                                    @endif
                                    <!--<tr>-->
                                    <!--    <th>Discount</th>-->
                                    <!--    <td><input type="number"-->
                                    <!--            @if (!(Auth::guard('admin')->user()->role == '2')) @if (!$somePermission) readonly @endif-->
                                    <!--            @endif-->
                                    <!--        class="form-control" name="discount" value="{{ $order->discount }}">-->
                                    <!--    </td>-->
                                    <!--    <th>Others</th>-->
                                    <!--    <td><input type="number"-->
                                    <!--            @if (!(Auth::guard('admin')->user()->role == '2')) @if (!$somePermission) readonly @endif-->
                                    <!--            @endif-->
                                    <!--        class="form-control" name="others" value="{{ $order->others }}">-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    @if ($HasPermission)
                                    <tr>
                                        <th>Discount</th>
                                        <td><input type="number"
                                            class="form-control" name="discount" value="{{ $order->discount }}">
                                        </td>
                                        <th>Others</th>
                                        <td><input type="number"
                                            class="form-control" name="others" value="{{ $order->others }}">
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- col// -->
                </div>
                <!-- row // -->
                @if (!(Auth::guard('admin')->user()->role == '2'))
                    @if ($HasPermission)
                        @if (
                            $delivery_status == 'Pending' ||
                            $delivery_status == 'Holding' ||
                            $delivery_status == 'Processing' ||
                            $delivery_status == 'Picked_up')
                            <div class="row mb-3 custom__select">
                            <div class="col-7 col-md-6"></div>
                            <div class="col-12 col-sm-12 col-md-6">
                                <select id="siteID" class="form-control selectproduct " style="width:100%">
                                    <option> Select Product To Order</option>
                                    @foreach ($products->where('stock_qty', '!=', 0) as $product)
                                            @php
                                                if ($product->discount_type == 1) {
                                                    $price_after_discount = $product->regular_price - $product->discount_price;
                                                } elseif ($product->discount_type == 2) {
                                                    $price_after_discount = $product->regular_price - ($product->regular_price * $product->discount_price) / 100;
                                                }
                                                $Price = $product->discount_price ? $price_after_discount : $product->regular_price;
                                            @endphp
                                                                    @if ($product->is_varient)
                                            @foreach ($product->stocks->where('qty', '!=', 0) as $key => $stock)
                                            @php
                                                if ($product->discount_type == 1) {
                                                    $price_after_discount = $stock->price - $product->discount_price;
                                                } elseif ($product->discount_type == 2) {
                                                    $price_after_discount = $stock->price - ($stock->price * $product->discount_price) / 100;
                                                }
                                                $Price = $product->discount_price ? $price_after_discount : $stock->price;
                                            @endphp
                                                                    <option class="addToOrder" data-order_id="{{ $order->id }}" data-id="{{ $stock->id }}"
                                                                        data-product_id="{{ $product->id }}"> {{ $product->name_en }} ({{ $stock->varient }})({{ $stock->qty }}) ={{ $Price }}৳</option>
                                            @endforeach
                                            @else
                                            <option class="addToOrder" data-order_id="{{ $order->id }}"
                                                                        data-product_id="{{ $product->id }}"> {{ $product->name_en }}({{ $product->stock_qty }})={{ $Price }}৳</option>
                                            @endif
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    @if (!(Auth::guard('admin')->user()->role == '2'))
                                    @if ($HasPermission)
                                    @if ($delivery_status != 'Cancelled' || $delivery_status !='Returned')
                                    <th width="5%">
                                        Delete
                                    </th>
                                    @endif
                                    @endif
                                    @endif
                                    <th width="30%">Product</th>
                                    <th width="20%" class="text-center">Unit Price</th>
                                    <th width="10%" class="text-center">Quantity</th>
                                    <th width="10%" class="text-center">Vendor Comission</th>
                                    <th width="15%" class="text-center">Vendor Name</th>
                                    <th width="10%" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    if (Auth::guard('admin')->user()->role == '2') {
                                        $orders = $order
                                            ->order_details()
                                            ->where('vendor_id', Auth::guard('admin')->user()->id)
                                            ->get();
                                    } else {
                                        $orders = $order->order_details()->get();
                                    }
                                @endphp
                                @foreach ($orders as $key => $orderDetail)
<tr>
                                    @if (!(Auth::guard('admin')->user()->role == '2'))
                                    @if ($HasPermission)
                                  @if ($delivery_status != 'Cancelled' || $delivery_status !='Returned')
                                    <td class="text-center">
                                        @if (count($orders) > 1)
                                        <a id="deleteproduct" href="{{ route('delete.order.product', $orderDetail->id) }}">
                                            <button type="button" class="btn_main misty-color">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </a>
@else
<button class="cart_actionBtn btn_main misty-color" disabled>
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        @endif
                                    </td>
                                    @endif
                                    @endif
                                    @endif
                                    <td>
                                        <a class="itemside">
                                            <div class="left">
                                                <img src="{{ asset($orderDetail->product->product_thumbnail ?? ' ') }}"
                                                    width="40" height="40" class="img-xs" alt="Item" />
                                            </div>
                                            <div class="info">
                                                <span class="text-bold">
                                                    {{ $orderDetail->product->name_en ?? ' ' }}
                                                </span>
                                                <span>
                                                    @if ($orderDetail->is_varient && count(json_decode($orderDetail->variation)) > 0)
                                                    @foreach (json_decode($orderDetail->variation) as $varient)
                                                    ( <span>{{ $varient->attribute_name }} :
                                                        {{ $varient->attribute_value }}</span>)
                                                    @endforeach
                                                    @endif
                                                </span>
                                                @if($order->order_by==1)
                                                <span>
                                                    @if($orderDetail->gift_status==0)
                                                    <span class="gift_status pl-5" data-detail_id={{ $orderDetail->id }}><i class="fa-solid fa-gift"></i></span>
                                                    @else
                                                    <span class="gift_status pl-5" data-detail_id={{ $orderDetail->id }} style="color:green"><i class="fa-solid fa-gift"></i></span>
                                                    @endif
                                                </span>
                                                @endif
                                                @php
                                                    if ($orderDetail->is_varient) {
                                                        $jsonString = $orderDetail->variation;
                                                        $combinedString = '';
                                                        $jsonArray = json_decode($jsonString, true);
                                                        foreach ($jsonArray as $attribute) {
                                                            if (isset($attribute['attribute_value'])) {
                                                                $combinedString .= $attribute['attribute_value'] . '-';
                                                            }
                                                        }
                                                        $combinedString = rtrim($combinedString, '-');
                                                        $stockId = App\Models\ProductStock::where(
                                                            'varient',
                                                            $combinedString,
                                                        )->first();
                                                    }
                                                @endphp
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $orderDetail->price ?? '0.00' }}</td>
                                    <td class="text-center qunatity_change">
                                        <input type="hidden" value="{{ $orderDetail->product_id }}" class="product_id">
                                        <input type="hidden" value="{{ $orderDetail->id }}" class="orderdetail_id">
                                        @if ($orderDetail->is_varient == 1)
                                        <input type="hidden" value="{{ $stockId->id }}" class="stock_id">
                                        @endif
                                        <!-- decress btn -->
                                        <button type="button" @if (!(Auth::guard('admin')->user()->role == '2')) @if (!$HasPermission) disabled @endif @endif
                                            class="input-group-text rounded-0 bg-navy add_btn @if (in_array($delivery_status, ['Pending', 'Holding', 'Processing', 'Picked_up','Shipped'])) decress_quantity changeQuantity @endif"
                                            data-type="-" style="background:#cf3636;color:white"><i
                                                class="fa-solid fa-minus"></i></button>
                                        <!-- quantity input -->
                                        <input class="form-control text-center quantity_input najmul__product__details"
                                            value="{{ $orderDetail->qty }}" min="1" max="" type="text"
                                            name="qty{{ $key }}" disabled>
                                        <!-- incress btn-->
                                        <button type="button" @if (!(Auth::guard('admin')->user()->role == '2')) @if (!$HasPermission) disabled @endif @endif
                                            class="input-group-text rounded-0 bg-navy add_btn @if (in_array($delivery_status, ['Pending', 'Holding', 'Processing', 'Picked_up','Shipped'])) incress_quantity changeQuantity @endif" data-type="+"
                                            style="background:#116a11;color:white" ><i
                                                class="fa-solid fa-plus"></i></button>
                                        <input type="hidden" type="text" name="qty{{ $key }}"
                                            value="{{ $orderDetail->qty }}">
                                    </td>
                                    <td class="text-center">{{ $orderDetail->v_comission * $orderDetail->qty }}</td>
                                    @php
                                        $user = App\Models\User::where('id', $orderDetail->vendor_id)->first();
                                        if ($user) {
                                            $v_name = $user->name;
                                        } else {
                                            $v_name = 'Admin';
                                        }
                                    @endphp
                                    <td class="text-center">{{ $v_name }}</td>
                                    <td class="text-end" id="item_totalPrice_{{ $key }}">{{ $orderDetail->price * $orderDetail->qty }}</td>
                                </tr>
@endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                     <td colspan="3">{{$order->comment ?? ' '}}</td>
                                    <td colspan="7">
                                        @php
                                            $sum = 0;
                                            $sum1 = 0;
                                            $sum2 = 0;
                                            $price = 0;
                                            $orderDetails = $order
                                                ->order_details()
                                                ->where('vendor_id', Auth::guard('admin')->user()->id)
                                                ->get();
                                            foreach ($orderDetails as $key => $orderDetail) {
                                                $sum1 += $orderDetail->v_comission;
                                                $sum2 += $orderDetail->qty;
                                                $price += $orderDetail->price * $orderDetail->qty;
                                                $sum += $orderDetail->v_comission * $orderDetail->qty;
                                            }
                                        @endphp
                                        <article class="float-end">
                                            @if (Auth::guard('admin')->user()->role == '2')
                                            <dl class="dlist">
                                                <dt>SubTotal:</dt>
                                                <dd>{{ $price ?? '0.00' }}</dd>
                                            </dl>
                                            <dl class="dlist">
                                                <dt>Vendor Comission:</dt>
                                                <dd>{{ $sum ?? '0.00' }}</dd>
                                            </dl>
                                            <dl class="dlist">
                                                <dt>Receiveable Amount:</dt>
                                                <dd>{{ $price - $sum }}</dd>
                                            </dl>
@else
<dl class="dlist">
                                                <dt>Subtotal:</dt>
                                                <dd id="subtotal">{{ $order->sub_total ?? '0.00' }}</dd>
                                            </dl>
                                            @endif
                                            @if( $order->vat > 0)
                                                <dl class="dlist">
                                                    <dt>Taxable Amount:</dt>
                                                    <dd>{{ ($order->sub_total - $order->vat)  }}</dd>
                                                </dl>
                                                <dl class="dlist">
                                                    <dt>Vat 5%</dt>
                                                    <dd>{{ $order->vat ?? ' ' }}</dd>
                                                </dl>
                                                <dl class="dlist">
                                                    <dt>Total</dt>
                                                    <dd>{{ $order->sub_total ?? '0.00' }}</dd>
                                                </dl>
                                            @endif
                                            @if (!(Auth::guard('admin')->user()->role == '2'))
                                            <dl class="dlist">
                                                <dt>Shipping cost:</dt>
                                                <dd>{{ $order->shipping_charge ?? '0.00' }}</dd>
                                            </dl>
                                            @if($order->discount >0)
                                            <dl class="dlist">
                                                <dt>Discount:</dt>
                                                <dd><b class="">{{ $order->discount }}</b></dd>
                                            </dl>
                                            @endif
                                            @if($order->coupon_discount >0)
                                            <dl class="dlist">
                                                <dt>Coupon Discount:</dt>
                                                <dd><b class="">{{ $order->coupon_discount }}</b></dd>
                                            </dl>
                                            @endif
                                            @if($order->delivery_coupon_amount >0)
                                            <dl class="dlist">
                                                <dt>Delivery Discount:</dt>
                                                <dd><b class="">{{ $order->delivery_coupon_amount }}</b></dd>
                                            </dl>
                                            @endif
                                            <dl class="dlist">
                                                <dt>Others:</dt>
                                                <dd><b class="">{{ $order->others }}</b></dd>
                                            </dl>
                                             @if($order->giftPrice > 0)
                                                <dl class="dlist">
                                                    <dt>Gift Price: (-)</dt>
                                                    <dd><b class="">{{ $order->giftPrice
                                                    }}</b></dd>
                                                </dl>
                                            @endif
                                            <dl class="dlist">
                                                <dt>Grand total:</dt>
                                                <dd id="grandtotal"><b class="h5">{{ $order->grand_total }}</b>
                                                <dd id="buyingprice" style="display: none"><b class="h5">{{ $order->totalbuyingPrice }}</b>
                                                </dd>
                                            </dl>
                                            @endif
                                            <dl class="dlist">
                                                <dt class="text-muted">Status:</dt>
                                                <dd>
                                                    @php
                                                        $status = $order->delivery_status;
                                                        if ($order->delivery_status == 'Delivered') {
                                                            $status = '<span
                                                        class="badge rounded-pill alert-success">Delivered</span>';
                                                        }
                                                        if ($order->delivery_status == 'Cancelled') {
                                                            $status = '<span
                                                        class="badge rounded-pill alert-danger">Cancelled</span>';
                                                        }

                                                    @endphp
                                                    {!! $status !!}
                                                </dd>
                                            </dl>
                                        </article>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- table-responsive// -->
                </div>
                <!-- col// -->
                <div class="col-lg-1"></div>
                <div>
                    <input type="hidden" name="sub_total" class="subtotalof" value="{{ $order->sub_total }}">
                    <input type="hidden" name="grand_total" class="grandtotalof" value="{{ $order->grand_total }}">
                    <input type="hidden" name="totalbuyingPrice" class="totalbuyingPriceof"
                        value="{{ $order->totalbuyingPrice }}">
                </div>
                @if (!(Auth::guard('admin')->user()->role == '2'))
                @if (in_array($delivery_status, ['Pending', 'Holding', 'Processing', 'Picked_up', 'Shipped']))
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </div>
@else
<div class="d-flex justify-content-end">
                    <button type="button" disabled class="btn btn-primary">Update Order</button>
                </div>
                @endif
                @endif
                <!-- col// -->
                </form>
            </div>
        </div>
        <!-- card-body end// -->
    </div>
    <!-- card end// -->
</section>
@endsection
@push('footer-script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name="shipping_id"]').on('change', function() {
                var shipping_cost = $(this).val();
                if (shipping_cost) {
                    $.ajax({
                        url: "{{ url('/checkout/shipping/ajax') }}/" + shipping_cost,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            //console.log(data);
                            $('#ship_amount').text(data.shipping_charge);

                            let shipping_price = parseInt(data.shipping_charge);
                            let grand_total_price = parseInt($('#cartSubTotalShi').val());
                            grand_total_price += shipping_price;
                            $('#grand_total_set').html(grand_total_price);
                            $('#total_amount').val(grand_total_price);
                        },
                    });
                } else {
                    alert('danger');
                }
            });
        });

        /* ============ Update Payment Status =========== */
        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                // console.log(data);
                // Start Message
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',

                    showConfirmButton: false,
                    timer: 1000
                })
                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success
                    })
                } else {
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error
                    })
                }
                // End Message
            });
        });

        /* ============ Update Delivery Status =========== */
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            if (status === 'Cancelled' || status === 'Returned') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will cancel or return the order!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateDeliveryStatus(order_id, status);
                    }
                });
            } else {
                updateDeliveryStatus(order_id, status);
            }
        });

        function updateDeliveryStatus(order_id, status) {
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success
                    });
                } else {
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error
                    });
                }
                location.reload();
            });
        }
         $('#deliveredStatus').on('click', function() {
            var order_id = {{ $order->id }};
            var status = 'Delivered';
            if (status === 'Delivered') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will delivered the order!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateDeliveryStatus(order_id, status);
                    }
                });
            } else {
                updateDeliveryStatus(order_id, status);
            }
        });
    </script>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

            <!--  Division To District Show Ajax -->
            <script type="text/javascript">
                $('select[name="division_id"]').on('change', function() {
                var division_id = $(this).val();
                if (division_id) {
                    $.ajax({
                        url: "{{ url('/division-district/ajax') }}/" + division_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="district_id"]').html(
                                '<option value="" selected="" disabled="">Select City</option>'
                            );
                            $.each(data, function(key, value) {
                                // console.log(value);
                                $('select[name="district_id"]').append(
                                    '<option value="' + value.id + '">' +
                                    capitalizeFirstLetter(value.district_name_en) +
                                    '</option>');
                            });
                            $('select[name="upazilla_id"]').html(
                                '<option value="" selected="" disabled="">Select Zone</option>'
                            );
                        },
                    });
                } else {
                    alert('danger');
                }
            });

                function capitalizeFirstLetter(string) {
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }
            </script>
            <!--  District To Upazilla Show Ajax -->
            <script type="text/javascript">
                $(document).ready(function() {
                    $('select[name="district_id"]').on('change', function() {
                var district_id = $(this).val();
                if (district_id) {
                    $.ajax({
                        url: "{{ url('/district-upazilla/ajax') }}/" + district_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="upazilla_id"]').html(
                                '<option value="" selected="" disabled="">Select Zone</option>'
                            );
                            $.each(data, function(key, value) {
                                $('select[name="upazilla_id"]').append(
                                    '<option value="' + value.id + '">' + value
                                    .name_en + '</option>');
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
                });

                function capitalizeFirstLetter(string) {
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }
            </script>

            <!-- Customer Edit Modal -->
            <div class="modal fade" id="staticBackdrop1{{ $order->user_id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Edit Customer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="{{ route('admin.user.update', $order->user_id) }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="division_id" class="fw-bold text-black col-form-label"><span
                                                class="text-danger">*</span> Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Enter the name"
                                            value="{{ $order->user->name ?? 'Null' }}">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="division_id" class="fw-bold text-black col-form-label"><span
                                                class="text-danger">*</span> Email</label>
                                        <input type="text" class="form-control" name="email" placeholder="Enter the email"
                                            value="{{ $order->user->email ?? 'Null' }}">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label for="division_id" class="fw-bold text-black col-form-label"><span
                                                class="text-danger">*</span> Phone</label>
                                        <input type="number" class="form-control" name="phone" placeholder="Enter the phone"
                                            value="{{ $order->user->phone ?? 'Null' }}" readonly>
                                    </div>
                                    <!-- <div class="form-group col-lg-6">
                                                                        <label for="division_id" class="fw-bold text-black col-form-label"><span class="text-danger">*</span> Password</label>
                                                                        <input type="password" class="form-control">
                                                                    </div> -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <script>
        $(document).on('click', '#deleteproduct', function(e) {
            e.preventDefault();
            var link = $(this).attr("href");
            Swal.fire({
                title: 'Are you sure?',
                text: "Delete This Data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        });
    </script>
            <script>
                //remove

                $(document).on('click', '.changeQuantity', function() {
                    var product_id = $(this).closest('.qunatity_change').find('.product_id').val();
                    var stock_id = $(this).closest('.qunatity_change').find('.stock_id').val();
                    var orderdetail_id = $(this).closest('.qunatity_change').find('.orderdetail_id').val();
                    var qtyInput = $(this).closest('.qunatity_change').find('.quantity_input');
                    var type = $(this).data('type');
                    var key = $(this).closest('tr').index();
                    var data = {
                        'product_id': product_id,
                        'stock_id': stock_id,
                        'orderdetail_id': orderdetail_id,
                        'type': type,
                        'qty': qtyInput.val(),
                    }

                    $.ajax({
                        method: "get",
                        url: '{{ route('order.quantity.update') }}',
                        data: data,
                        success: function(response) {
                            if (response.status == 'success') {
                                toastr.success(response.message, 'message');
                                var currentPrice = parseFloat($('#subtotal').text());
                                var currentgrandPrice = parseFloat($('#grandtotal').text());
                                var currentbuyingprice = parseFloat($('#buyingprice').text());
                                if (response.type == '+') {
                                    currentPrice += parseFloat(response.price);
                                    currentgrandPrice += parseFloat(response.price);
                                    currentbuyingprice += parseFloat(response.buyingPrice);
                                    qtyInput.val(parseInt(qtyInput.val()) + 1);
                                } else {
                                    currentPrice -= parseFloat(response.price);
                                    currentgrandPrice -= parseFloat(response.price);
                                    currentbuyingprice -= parseFloat(response.buyingPrice);
                                    qtyInput.val(parseInt(qtyInput.val()) - 1);
                                }
                                var itemTotalPrice = parseFloat(response.detail_price * qtyInput.val());
                                $('#item_totalPrice_' + key).text(itemTotalPrice.toFixed(2));
                                $('#subtotal').text(currentPrice);
                                $('#grandtotal').text(currentgrandPrice);
                                $('#buyingprice').text(currentbuyingprice);
                                $('.subtotalof').val(currentPrice);
                                $('.grandtotalof').val(currentgrandPrice);
                                $('.totalbuyingPriceof').val(currentbuyingprice);

                                var Quantity = response.qty;
                                var product_price = response.price;
                                var productnewprice = product_price * Quantity;
                                $('.price_qty').text(productnewprice);
                                var updatedQty = parseInt(qtyInput.val());
                                //console.log(updatedQty)
                                $('input[name="qty' + key + '"]').val(updatedQty);
                                // $('input[name="qty' + key + '"]').prop('disabled', false).val(updatedQty).prop('disabled', true);
                            } else {
                                toastr.error(response.error, 'Error');
                            }
                        }
                    });
                });

                /* add to cart */

                /* add to cart */

                $(document).on('change', '.selectproduct', function() {
                    var selectedOption = $(this).find(':selected');
                    var productId = selectedOption.data('product_id');
                    var stockId = selectedOption.data('id');
                    var orderId = selectedOption.data('order_id');
                    var data = {
                        product_id: productId,
                        stock_id: stockId,
                        order_id: orderId
                    }
                    $.ajax({
                        url: '{{ route('order.itemAdd') }}',
                        method: "Post",
                        data: data,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            console.log(response)
                            if (response.status == 'success') {
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                                toastr.success(response.message, 'message');
                            } else {
                                toastr.error(response.error, 'Error');
                            }
                        }
                    });
                });
                //$('.apeandField').append('<tr><td>' + response.product_id + '</td></tr>');
            </script>
            {{-- this link for option search --}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>
            <script>
                $(function() {
                    $(".selectproduct").select2();
                });
            </script>

            {{-- gift add --}}
            <script>
                $(document).on('click','.gift_status',function(){
                // console.log('liza')
                var detail_id = $(this).data('detail_id');
                    // console.log(detail_id);
                    $.ajax({
                        method:"get",
                        url:'{{ route('order.gift_status') }}',
                        data: {
                            detail_id:detail_id
                        },
                        success:function(response){
                            if (response.status == 'success') {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                    toastr.success(response.message, 'message');
                                } else {
                                    toastr.error(response.error, 'Error');
                                }
                            }
                    })
                })
            </script>
@endpush()