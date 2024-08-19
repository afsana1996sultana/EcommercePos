<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    @php
        $logo = get_setting('site_favicon');
    @endphp
    @if($logo != null)
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_setting('site_favicon')->value ?? ' ') }}" />
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css?v=5.3 ') }}" />
    <style>
        .invoice-3 .invoice-header {
            padding: 30px;
        }
        .invoice .invoice-top {
            padding: 15px 150px 5px 150px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice invoice-content invoice-3">
        <div class="back-top-home hover-up mt-30 ml-30">
            <a class="hover-up" href="{{ route('home') }}"><i class="mr-5 fi-rs-home"></i> Homepage</a>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner">
                        <div class="invoice-info" id="invoice_wrapper">
                            <div class="invoice-header">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="invoice-name">
                                            <div class="logo w-50">
                                                <a href="{{route('home')}}">
                                                    @php
                                                        $logo = get_setting('site_footer_logo');
                                                    @endphp
                                                    @if($logo != null)
                                                        <img src="{{ asset(get_setting('site_footer_logo')->value ?? 'null') }}" style="max-width: 200px !important;" alt="{{ env('APP_NAME') }}">
                                                    @else
                                                        <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}" style="max-width: 200px !important;">
                                                    @endif
                                                </a>
                                                <div>
                                                    <strong>{{ get_setting('business_name')->value ?? ''}}</strong> <br />
                                                    <abbr title="Phone">Phone:</abbr> {{ get_setting('phone')->value ?? ''}}<br>
                                                    <abbr title="Email">Email: </abbr>{{ get_setting('email')->value ?? ''}}<br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="invoice-numb">
                                            <h4 class="mt-20 mb-10 invoice-header-1">Invoice No:<span class="text-heading">{{ $order->invoice_no }}</span></h4>
                                            <strong class="text-mute">Invoice Data:</strong> {{ \Carbon\Carbon::parse($order->date)->isoFormat('MMM Do YYYY')}}<br />
                                            <strong class="text-mute">Payment Method:</strong> @if($order->payment_method == 'cod') Cash On Delivery @else {{ $order->payment_method }} @endif<br />
                                            @if($order->trxID)
                                            <strong class="text-mute">Transaction ID:</strong> {{ $order->trxID}}<br />
                                            @endif
                                            <strong class="text-mute">Status:</strong> <span class="">{{ $order->delivery_status }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="invoice-top">
                                <div class="row">
                                    <div class="col-md-8 col-12">
                                        <div class="invoice-number">
                                            <h4 class="mb-10 invoice-title-1">Bill To</h4>
                                            <p class="invoice-addr-1 text-capitalize">
                                                <strong>Name: {{ $order->name ?? '' }}</strong> <br />
                                                Address : {{ isset($order->address) ? $order->address . ',' : 'No address' }}
                                                {{ $order->upazilla->name_en ?? '' }}, {{ $order->district->district_name_en ?? '' }}, {{ $order->division->division_name_en ?? '' }}<br>
                                                <abbr >Phone:</abbr> {{ $order->phone ?? ''}}<br>
                                                <abbr >Email: </abbr>{{ $order->email ?? ''}}<br>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-center">
                                <div class="table-responsive">
                                    <table class="table table-striped invoice-table">
                                        <thead class="bg-active">
                                            <tr>
                                                <th>Item Item</th>
                                                <th class="text-center">Unit Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($order->order_details as $order_detail)
                                                <tr>
                                                    <td>
                                                        <div class="item-desc-1">
                                                            <span>{{$order_detail->product->name_en ?? ' '}}</span>
                                                            <span>
                                                                @if($order_detail->is_varient && count(json_decode($order_detail->variation))>0)
                                                                    @foreach(json_decode($order_detail->variation) as $varient)
                                                                        <span>{{ $varient->attribute_name }} : <span class="d-inline-block fw-normal ms-1">{{ $varient->attribute_value }}</span></span>
                                                                    @endforeach
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{$order_detail->
                                                    price ?? ''}}</td>
                                                    <td class="text-center">{{$order_detail->
                                                    qty ?? ''}}</td>
                                                    <td class="text-right">{{ ($order_detail->price * $order_detail->
                                                        qty) ?? ' ' }}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="text-end f-w-600">SubTotal</td>
                                                    <td class="text-right">{{ $order->sub_total ?? ' ' }}</td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="text-end f-w-600">Taxable Amount</td>
                                                    <td class="text-right">{{ ($order->sub_total - $order->vat)  }}</td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="text-end f-w-600">Vat 5%</td>
                                                    <td class="text-right">{{ $order->vat ?? ' ' }}</td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="text-end f-w-600">Total</td>
                                                    <td class="text-right">{{ $order->sub_total ?? ' ' }}</td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" class="text-end f-w-600">Shipping cost:</td>
                                                    <td class="text-right">{{ $order->shipping_charge ?? '' }}</td>
                                                </tr>
                                                @if($order->coupon_discount > 0)
                                                <tr>
                                                    <td colspan="3" class="text-end f-w-600">Coupon Discount:</td>
                                                    <td class="text-right">{{ $order->coupon_discount ?? '0.00' }}</td>
                                                </tr>
                                                @endif
                                                @if($order->delivery_coupon_amount > 0)
                                                <tr>
                                                    <td colspan="3" class="text-end f-w-600">Delivery Discount:</td>
                                                    <td class="text-right">{{ $order->delivery_coupon_amount ?? '0.00' }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="3" class="text-end f-w-600">Grand Total</td>
                                                    <td class="text-right f-w-600">{{ ($order->grand_total-$order->discount) ?? ' '}}</td>
                                                </tr>

                                                @if($order->payment_status =='partial paid')
                                                    <tr>
                                                        <td colspan="3" class="text-end f-w-600">Paid</td>
                                                        <td class="text-right f-w-600">{{ ($order->shipping_charge) ?? ' '}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="3" class="text-end f-w-600">Due</td>
                                                        <td class="text-right f-w-600">{{ ($order->grand_total-$order->shipping_charge) ?? ' '}}</td>
                                                    </tr>
                                                @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mb-5 invoice-bottom">
                                <div class="row">
                                    <div class="col-sm-6">
                                    </div>
                                    <div class="col-sm-6 col-offsite">
                                        <div class="text-end">
                                            <p class="mb-0 text-13">Thank you for your Order</p>
                                            <p><strong>{{ get_setting('business_name')->value ?? ' '}}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix invoice-btn-section d-print-none">
                            {{-- <a href="javascript:window.print()" class="btn btn-lg btn-custom btn-print hover-up"> <img src="{{ asset('frontend/assets/imgs/theme/icons/icon-print.svg') }}" alt="" /> Print </a> --}}
                            <a id="invoice_download_btn" class="btn btn-lg btn-custom btn-download hover-up"> <img src="{{ asset('frontend/assets/imgs/theme/icons/icon-download.svg') }}" alt="" /> Download </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor JS-->
    <script src="{{asset('frontend/assets/js/vendor/modernizr-3.6.0.min.js ') }}"></script>
    <script src="{{asset('frontend/assets/js/vendor/jquery-3.6.0.min.js ') }}"></script>
    <!-- Invoice JS -->
    <script src="{{asset('frontend/assets/js/invoice/jspdf.min.js ') }}"></script>
    <script src="{{asset('frontend/assets/js/invoice/invoice.js ') }}"></script>
</body>

</html>
