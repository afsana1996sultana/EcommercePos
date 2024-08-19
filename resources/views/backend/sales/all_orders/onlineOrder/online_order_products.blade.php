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
            <th class="text-center">Amount</th>
            <th class="text-center">Paid</th>
            <th class="text-center">Due</th>
            <th class="text-center">Csv Amount</th>
            <th class="text-center">Shipping Type</th>
            <th class="text-center">Shipping Address</th>
            <th class="text-center">Delivery Status</th>
            <th class="text-center">Payment Status</th>
            <!--<th class="text-center">Created By</th>-->
            <th class="text-end">Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $key => $order)
            <tr id="order_ids{{ $order->id }}">
                @if (Auth::guard('admin')->user()->role == '1' ||
                        in_array('19', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    @if (in_array($order->delivery_status, ['Cancelled', 'Returned', 'Shipped', 'Delivered']))
                        <td><input type="checkbox" disabled></td>
                    @else
                        <td><input type="checkbox" class="check_ids" name="ids" value="{{ $order->id }}"></td>
                    @endif
                @endif
                <td>{{ $key + 1 }}</td>
                <td>{{ $order->invoice_no }}</td>
                <td><b> {{ $order->user->name ?? 'N/A' }}</b></td>
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
                {{-- <td class="text-center">
                @if ($order->payment_method == 'cod')
                    {{ $order->grand_total }} TK
                @else
                    {{ $order->paid_amount }} TK
                @endif
            </td> --}}

                <td class="text-center">
                    @if ($order->payment_status == 'partial paid')
                        {{ $order->shipping_charge }}
                    @elseif($order->payment_status == 'unpaid')
                        {{ $order->paid_amount ?? '0.00' }}
                    @else
                        {{ $order->grand_total }}
                    @endif
                </td>

                <td class="text-center">
                    @if ($order->payment_status == 'partial paid')
                        {{ $order->grand_total - $order->shipping_charge }}
                    @elseif($order->payment_status == 'unpaid')
                        {{ $order->due_amount }}
                    @else
                        {{ $order->due_amount }}
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
                    ({{ $order->shipping_name }})
                </td>

                <td>
                    {{ isset($order->address) ? ucwords($order->address) : '' }}
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
                <!--<td>{{ $order->staff_id ?? '' }}</td>-->
                <td class="text-center">
                    @if ($order->packaging_status == 1)
                        <a @disabled(true)>
                            <i class="fa-solid fa-gift" style="color:rgb(2, 68, 210)"></i>
                        </a>
                    @endif
                    <div class="dropdown">
                        <a type="button" class="btn btn-block" id="dropdownMenuButton" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu order__action" aria-labelledby="dropdownMenuButton">
                            <!-- Dropdown items go here -->
                            <!--<li>-->
                            <!--    @if ($order->packaging_status == 0)
-->
                            <!--        <a  class=" dropdown-item" href="{{ route('packages.status', $order->id) }}">-->
                            <!--            <i class="fa-solid fa-gift" style="color:#3BB77E"></i>Add Package-->
                            <!--        </a>-->
                            <!--
@endif-->
                            <!--</li>-->
                            <li>
                                <a class="dropdown-item" target="blank"
                                    href="{{ route('print.invoice.download', $order->id) }}"><i
                                        class="fa-solid fa-print" style="color:#3BB77E"></i>Invoice Print</a>
                            </li>
                            @if (Auth::guard('admin')->user()->role == '1' ||
                                    in_array('18', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                                <li>
                                    <a target="_blank" class="dropdown-item"
                                        href="{{ route('all_orders.show', $order->id) }}">
                                        <i class="fa-solid fa-eye" style="color:#3BB77E"></i>Details
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a title="Download" href="{{ route('invoice.download', $order->id) }}"
                                    class="dropdown-item">
                                    <i class="fa-solid fa-download" style="color:#3BB77E"></i> Invoice Download
                                </a>
                            </li>
                            {{-- <li>
                        <a id="delete" class="dropdown-item" href="{{ route('delete.orders',$order->id) }}">
                            <i class="fa-solid fa-trash" style="color:#3BB77E"></i> Delete
                        </a>
                    </li> --}}
                            @if (Auth::guard('admin')->user()->role == '1' ||
                                    in_array('20', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                                <li>
                                    <a title="Delete" style="color:#ff0000"
                                        href="{{ route('order.delete.byStatus', $order->id) }}" class="dropdown-item "
                                        id="deleteOrder">
                                        <i class="fa-solid fa-trash" style="color:#ff0000"></i> Delete
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
