<table class="table table-bordered table-hover" id="" width="100%">
    <thead>
        <tr>
            @if (Auth::guard('admin')->user()->role == '1' ||
                    in_array('61', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                <th><input type="checkbox" id="select_all_ids"></th>
            @endif
            <th>No.</th>
            <th>Order Code</th>
            <th>Customer name</th>
            <th>Customer Phone</th>
            <th>Order Description</th>
            <th class="text-center">Amount</th>
            <th class="text-center">Paid Amount</th>
            <th class="text-center">Due Amount</th>
            <th class="text-center">Csv Amount</th>
            <th class="text-center">Shipping Type</th>
            <th class="text-center">Shipping Address</th>
            <th class="text-center">Delivery Status</th>
            <th class="text-center">Payment Status</th>
            <th class="text-end">Options</th>
        </tr>
    </thead>
    <tbody>
        @if ($orders->count() > 0)
            @foreach ($orders as $key => $order)
                <tr id="order_ids{{ $order->id }}">
                    @if (Auth::guard('admin')->user()->role == '1' ||
                            in_array('61', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        @if (in_array($order->delivery_status, ['Cancelled', 'Returned', 'Delivered']) || $order->lock_status == 1)
                            <td><input type="checkbox" disabled></td>
                        @else
                            <td><input type="checkbox" class="check_ids" name="ids" value="{{ $order->id }}">
                            </td>
                        @endif
                    @endif
                    <td>{{ $startIndex + $key + 1 }}</td>
                    <td>{{ $order->invoice_no }}</td>
                    <td><b>
                            @if ($order->user->role == 4)
                                Walk-in Customer
                            @else
                                {{ $order->user->name ?? 'Walk-in Customer' }}
                            @endif
                        </b></td>
                    <td>{{ $order->phone }}</td>
                    <td class="text-center">
                        @foreach ($order->order_details as $object)
                            {{ Str::limit($object->product_name, 30) }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                    <td class="text-center">{{ $order->grand_total }}</td>
                    <td class="text-center">
                        @if ($order->order_by == 1)
                            @if ($order->due_amount > 0)
                                {{ $order->paid_amount ?? '0.00' }}
                            @else
                                {{ $order->grand_total }}
                            @endif
                        @endif
                        @if ($order->order_by == 0)
                            @if ($order->payment_status == 'partial paid')
                                {{ $order->shipping_charge }}
                            @elseif($order->payment_status == 'unpaid')
                                {{ $order->grand_total }}
                            @else
                                {{ $order->due_amount ?? '0.00' }}
                            @endif
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($order->order_by == 1)
                            @if ($order->due_amount > 0)
                                {{ $order->due_amount }}
                            @else
                                {{ $order->due_amount }}
                            @endif
                        @endif
                        @if ($order->order_by == 0)
                            @if ($order->payment_status == 'partial paid')
                                {{ $order->grand_total - $order->shipping_charge }}
                            @elseif($order->payment_status == 'unpaid')
                                {{ $order->grand_total }}
                            @else
                                {{ $order->due_amount }}
                            @endif
                        @endif
                    </td>
                    <td class="text-center">{{ $order->csv_amount }}</td>
                    <td class="text-center">
                        @if ($order->shipping_type == 1)
                            Inside Dhaka
                        @elseif($order->shipping_type == 2)
                            Outside Dhaka
                        @else
                            Outside Dhaka City
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            $address = App\Models\Address::where('user_id', $order->user_id)->first();
                        @endphp
                        {{ $order->address }}
                    </td>
                    <td class="text-center">
                        @php
                            $status = $order->delivery_status;
                            if ($order->delivery_status == 'Delivered') {
                                $status = '<span class="badge rounded-pill alert-success">Delivered</span>';
                            }
                            if ($order->delivery_status == 'Cancelled') {
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
                        @if (Auth::guard('admin')->user()->role == '1' ||
                                in_array('60', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                            @if ($order->packaging_status == 1)
                                @if (
                                    $order->delivery_status == 'Processing' ||
                                        $order->delivery_status == 'Holding' ||
                                        $order->delivery_status == 'Pending')
                                    <a class="btn btn-primary btn-icon btn-circle btn-sm btn-xs"
                                        href="{{ route('packages.status', $order->id) }}">
                                        <i class="fa fa-scissors" aria-hidden="true"></i> <i
                                            class="fa-solid fa-gift"></i>
                                    </a>
                                @endif
                            @endif
                        @endif
                        <a class="btn btn-primary btn-icon btn-circle btn-sm btn-xs"
                            href="{{ route('invoice.download', $order->id) }}">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <th colspan="13" class="text-center">Order not Found</th>
            </tr>
        @endif
    </tbody>
</table>
{{ $orders->links() }}
