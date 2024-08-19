<table class="table table-bordered table-hover" id="" width="100%">
    <thead>
        <tr>
            @if (Auth::guard('admin')->user()->role == '1' ||
                    in_array('19', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                <th><input type="checkbox" id="select_all_ids"></th>
            @endif
            <th>No.</th>
            <th>Order Code</th>
            <th>Customer name</th>
            <th>Customer Phone</th>
            <th>Order Description</th>
            <th>Amount</th>
            <th>Paid Amount</th>
            <th>Due Amount</th>
            <th>Shipping Type</th>
            <th>Shipping Address</th>
            <th>Delivery Status</th>
            <th>Payment Status</th>
            <th>Sell By</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody id="empty_msg">
        @foreach ($orders as $key => $order)
            <tr id="order_ids{{ $order->id }}">
                @if (Auth::guard('admin')->user()->role == '1' ||
                        in_array('19', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <td><input type="checkbox" class="check_ids" name="ids" value="{{ $order->id }}"></td>
                @endif
                <td>{{ $startIndex + $key + 1 }}</td>
                <td>{{ $order->invoice_no }}</td>
                <td>
                    @if ($order->user->role == 4)
                        Walk-in Customer
                    @else
                        {{ $order->user->name ?? 'Walk-in Customer' }}
                    @endif
                </td>
                <td> {{ $order->phone }} </td>
                <td>
                    @foreach ($order->order_details as $object)
                        {{ Str::limit($object->product_name, 30) }}
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </td>
                <td>{{ $order->grand_total }}</td>
                <td>{{ $order->paid_amount ?? 0.0 }}</td>
                <td>{{ $order->due_amount ?? 0.0 }}</td>
                <!--<td>{{ $order->csv_amount }}</td>-->
                <td>
                    @if ($order->user->role == 4)
                        -
                    @else
                        @if ($order->shipping_type == 1)
                            Inside Dhaka ({{ $order->shipping_name }})
                        @elseif($order->shipping_type == 2)
                            Outside Dhaka ({{ $order->shipping_name }})
                        @elseif($order->shipping_type == 3)
                            Outside Dhaka City ({{ $order->shipping_name }})
                        @else
                            -
                        @endif
                    @endif
                </td>
                <td>
                    @if ($order->user->role == 4)
                        shop
                    @else
                        {{ isset($order->address) ? ucwords($order->address) : '' }}
                    @endif
                </td>
                <td>
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
                <td>
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
                <!--<td>{{ $order->staff_id ?? '' }}</td>-->

                <td>
                    @php
                        $staffId = $order->staff_id;
                        $staffName = App\Models\User::where('id', $staffId)->pluck('name')->first();
                    @endphp

                    {{ $staffName ?? 'Admin' }}
                </td>
                <td class="text-center">
                    <div class="dropdown">
                        <a type="button" class="btn btn-block" id="dropdownMenuButton" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu order__action" aria-labelledby="dropdownMenuButton">
                            @if (Auth::guard('admin')->user()->role == '1' ||
                                    in_array('18', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                                <li>
                                    <a target="_blank" class="dropdown-item"
                                        href="{{ route('all_orders.show', $order->id) }}">
                                        <i class="fa-solid fa-eye" style="color:#3BB77E"></i>Details
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
