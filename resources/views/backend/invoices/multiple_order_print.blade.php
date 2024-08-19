<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Product</title>
</head>
<style>

    *{
        color:#000;
         font-family: 'Calibri';
    font-weight: normal;
    font-style: normal;
    }
    .poppins-regular {
      font-weight: 400;
      font-style: normal;
    }
    ul {
        padding: 0;
    }

    li {
        list-style: none;
    }

    table {
        border-collapse: collapse;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    td,
    th {
        padding: 3px;
    }

    th {
        font-weight: 700;
        font-size: 18px;
    }

    td,
    td,
    td {
        border: 1px solid #000;
    }

    p {
        margin: 0;
    }

 .aditional__info p {
    padding-top: 10px !important;
    font-size: 14px;
    color: #000;
    padding: 0;
}

    p.najmul {
        padding-top: 0 !important;
    }
    .aditional__info p:last-child{
        border-bottom:none;
    }
    .aditional__info p span {
    color: #5E23A6;
    position: relative;
    top: 3px;
}

    ul.customer__info li {
        font-size: 14px;
    font-weight: 600;
    }

    .product__info table tbody tr th {
        font-size: 15px;
        padding:0;
    }
.product__info {
    margin-top: 10px;
}
    .product__info table tbody tr td {
        font-size: 14px;
    }
    p.najmul a {
        font-weight: 600;
    }

</style>

<body style="width: 400px;margin: auto;">
    @foreach ($orders as $order)
        <div class="wrapper" style="background-color: white;border: 1px solid #ddd; padding: 10px;height:95vh ">
        <div>
            @if ($order->shipping_charge > 0)
                @if($order->shipping_type == 1)
                    Inside Dhaka
                @else
                    Outside Dhaka
                @endif
            @endif
        </div>
        <div class="wrapper__header" style="text-align: center;">
            <h1 style="margin: 0;font-weight: 600;font-size:24px;margin-top:5px">Beauty Products BD</h1>
            <p style="font-size: 14px;color:#000;font-weight:bold;font-family:sans-serif !important">Phone: {{ get_setting('phone')->value }}</p>
            <a href="{{ route('home') }}">
                <p style="text-decoration: underline;font-weight: 700;">www.beautyproductsbd.com</p>
            </a>
            <hr style="margin: 0; margin-top: 5px; border: 1px solid #000;">
        </div>
        
        <div style="display: flex; justify-content: space-between;margin-top:5px">
            <ul class="customer__info" style="margin: 0;">
                <li>Bill To: @if ($order->user->role == 4)
                        Walk-in Customer
                    @else
                        {{ $order->user->name ?? 'Walk-in Customer' }}
                    @endif
                </li>
                @if ($order->user->role != 4)
                    <li>Phone: {{ $order->phone ?? '' }}</li>
                @endif
                <li>Address :{{ $order->address ?? '' }}
                </li>
            </ul>
            <ul class="customer__info" style="margin:0;">
                <li>Invoice No: {{ $order->invoice_no }}</li>
                <li>Date: {{ date('d-m-Y', strtotime($order->created_at)) }}</li>
            </ul>
        </div>
        <div class="product__info">
            <table style="width: 100%;text-align: center;">
                <tr style="border:1px solid #000">
                    <th style="width: 70%;border: 1px solid #000;">Product Name</th>
                    <th style="width:10%;border: 1px solid #000;">Qty.</th>
                    <th style="width: 10%;border: 1px solid #000;">Rate</th>
                    <th style="width: 10%;border: 1px solid #000;">Total</th>
                </tr>
                @foreach ($order->order_details as $key => $orderDetail)
                    @if ($orderDetail->product != null)
                        <tr>
                            <td style="color:#000">{{ ucfirst($orderDetail->product->name_en ?? '') }} @if($orderDetail->gift_status == 1) (Gift) @endif</td>
                            <td style="color:#000">{{ $orderDetail->qty }}</td>
                            <td style="color:#000">{{ $orderDetail->price }}</td>
                            <td style="color:#000">{{ $orderDetail->price * $orderDetail->qty }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: end;">Sub Total</td>
                    <td>{{ $order->sub_total }}</td>
                </tr>

                @if($order->vat > 0)
                    <tr>
                        <td colspan="3" style="text-align: end;">Taxable Amount</td>
                        <td>{{ ($order->sub_total - $order->vat)  }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" style="text-align: end;">Vat 5%</td>
                        <td>{{ $order->vat ?? ' ' }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" style="text-align: end;">Total</td>
                        <td>{{ $order->sub_total ?? ' ' }}</td>
                    </tr>
                @endif

                @if ($order->shipping_charge > 0)
                    <tr>
                        <td colspan="3" style="text-align: end;">Shipping Charge (+)</td>
                        <td>{{ $order->shipping_charge }}</td>
                    </tr>
                @endif
                @if ($order->others > 0)
                    <tr>
                        <td colspan="3" style="text-align: end;">Others (+)</td>
                        <td>{{ $order->others }}</td>
                    </tr>
                @endif
                @if ($order->discount > 0)
                    <tr>
                        <td colspan="3" style="text-align: end;">Discount (-)</td>
                        <td>{{ $order->discount }}</td>
                    </tr>
                @endif
                 @if ($order->coupon_discount > 0)
                    <tr>
                        <td colspan="3" style="text-align: end;">Coupon Discount (-)</td>
                        <td>{{ $order->coupon_discount }}</td>
                    </tr>coupon_discount
                @endif
                @if($order->giftPrice > 0)
                <tr>
                    <td colspan="3" style="text-align: end;">Gift Price (-)</td>
                    <td>{{ $order->giftPrice }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3" style="text-align: end;"><strong>Grand Total</strong></td>
                    <td>{{ $order->grand_total }}</td>
                </tr>
               @if($order->order_by==1)
                    @if ($order->paid_amount > 0)
                        <tr>
                            <td colspan="3" style="text-align: end;">Paid</td>
                            <td>{{ $order->paid_amount }}</td>
                        </tr>
                    @endif
                    @if ($order->due_amount > 0)
                        <tr>
                            <td colspan="3" style="text-align: end;">Due</td>
                            <td>{{ $order->due_amount }}</td>
                        </tr>
                    @endif
                @endif  
                @if($order->order_by==0)
                    @if ($order->payment_status == "partial paid")
                        <tr>
                            <td colspan="3" style="text-align: end;">Paid</td>
                            <td>{{ $order->shipping_charge }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: end;">Due</td>
                            <td>{{ $order->grand_total - $order->shipping_charge }}</td>
                        </tr>
                    @elseif($order->payment_status == "unpaid")   
                         <tr>
                            <td colspan="3" style="text-align: end;">Paid</td>
                            <td>{{ $order->due_amount ?? '0.00' }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: end;">Due</td>
                            <td>{{ $order->grand_total}}</td>
                        </tr>
                    @endif 
                @endif
            </table>
        </div>

        <div style="text-align: center;" class="aditional__info">
            <p><span style="color: #000; font-weight: bold;">***</span>Cosmetics, accessories, and toys are not exchangeable<span style="color: #000; font-weight: bold;position:relative;top:3px">***</span></p>
            <p class="najmul">Developed By: <a href="https://classicit.com.bd"><span>Classic IT</span></a></p>
        </div>

    </div>
    @endforeach
</body>

</html>
<script>
    window.onload = function() {
        window.print();
    };
</script>