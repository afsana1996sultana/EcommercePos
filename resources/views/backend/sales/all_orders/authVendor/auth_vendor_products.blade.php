<table class="table table-bordered table-hover" width="100%" id="">
    <thead>
        <tr>
            <th>Order Code</th>
            <th>Customer name</th>
            <th class="text-center">Total Amount</th>
            <th class="text-center">Comission</th>
            <th class="text-center">Receiveable Amount</th>
            <th class="text-center">Delivery Status</th>
            <th class="text-center">Payment Status</th>
            <th class="text-end">Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $key => $order)
            <tr>
                <td>{{ $order->invoice_no }}</td>
                <td><b>{{ $order->name }}</b></td>
                <td class="text-center">
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
                        }
                    @endphp
                    {{ $price }}
                </td>
                <td class="text-center">
                    {{-- @if ($order->payment_status == 'paid') --}}
                    @php
                        $sum = 0;
                        $sum1 = 0;
                        $sum2 = 0;
                        $orderDetails = $order
                            ->order_details()
                            ->where('vendor_id', Auth::guard('admin')->user()->id)
                            ->get();
                        foreach ($orderDetails as $key => $orderDetail) {
                            $sum1 += $orderDetail->v_comission;
                            $sum2 += $orderDetail->qty;
                            $sum += $orderDetail->v_comission * $orderDetail->qty;
                        }
                    @endphp
                    {{ $sum ?? '' }}
                    {{-- @else
                        <span>0.00 </span>
                    @endif --}}

                </td>
                <td class="text-center">
                    {{-- @if ($order->payment_status == 'paid') --}}
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
                            $sum += $orderDetail->v_comission * $orderDetail->qty;
                            $price += $orderDetail->price * $orderDetail->qty;
                        }
                    @endphp
                    {{ $price - $sum }}
                    {{-- @else
                        <span>0.00 </span>
                    @endif --}}
                </td>
                <td class="text-center">
                    @php
                        $status = $order->delivery_status;
                        if ($order->delivery_status == 'cancelled') {
                            $status = '<span class="badge rounded-pill alert-danger">Cancelled</span>';
                        }

                    @endphp
                    {!! $status !!}
                </td>
                <td class="text-center">
                    @php
                        $status = $order->payment_status;
                        if ($order->payment_status == 'unpaid') {
                            $status = '<span class="badge rounded-pill alert-danger">Unpaid</span>';
                        } elseif ($order->payment_status == 'paid') {
                            $status = '<span class="badge rounded-pill alert-success">Paid</span>';
                        } else {
                            $status = '<span class="badge rounded-pill alert-warning">Partial Paid</span>';
                        }
                    @endphp
                    {!! $status !!}
                </td>
                <td class="text-end">
                    <a target="_blank" class="btn btn-primary btn-icon btn-circle btn-sm btn-xs"
                        href="{{ route('all_orders.show', $order->id) }}">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
