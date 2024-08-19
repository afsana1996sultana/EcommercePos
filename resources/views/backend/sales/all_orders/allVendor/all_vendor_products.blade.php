<table class="table table-bordered table-hover" id="" width="100%">
    <thead>
        <tr>
            @if(Auth::guard('admin')->user()->role == '1' || in_array('19', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
            <th style="width: 5%"><input type="checkbox" id="select_all_ids"></th>
            @endif
            <th style="width: 5%">No.</th>
            <th style="width: 5%">Order Code</th>
            <th style="width: 10%">Customer name</th>
            <th style="width: 10%">Vendor name</th>
            <th style="width: 5%" class="text-center">Amount</th>
            <th style="width: 5%" class="text-center">Paid</th>
            <th style="width: 5%" class="text-center">Due</th>
            <th style="width: 5%" class="text-center">Vendor Comission</th>
            <th style="width: 5%" class="text-center">Delivery Status</th>
            <th style="width: 5%" class="text-center">Payment Status</th>
            <th style="width: 5%" class="text-center">Sell By</th>
            <th style="width: 10%" class="text-end">Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $key => $order)
        <tr id="order_ids{{$order->id}}">
            @if(Auth::guard('admin')->user()->role == '1' || in_array('19', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                @if(in_array($order->delivery_status, ['Cancelled','Returned', 'Shipped', 'Delivered']))
                    <td><input type="checkbox"  disabled ></td>
                @else
                    <td><input type="checkbox" class="check_ids" name="ids" value="{{$order->id}}"></td>
                @endif
            @endif
            <td>{{ $startIndex + $key + 1 }}</td>
            <td>{{ $order->invoice_no }}</td>
            <td><b>{{ $order->name }}</b></td>
            <td>
                @php
                    $orderDetails = App\Models\OrderDetail::where('order_id', $order->id)->get();
                    $vendorIds = $orderDetails->pluck('vendor_id')->toArray();
                    $vendorNames = App\Models\User::whereIn('id', $vendorIds)->pluck('name');
                @endphp

               @foreach($vendorNames as $vendorName)
                    {{ $vendorName }}
                @endforeach
            </td>
            <td class="text-center">{{ $order->grand_total }}</td>
            <td class="text-center">
                @if($order->payment_status =='partial paid')
                    {{ $order->shipping_charge ?? ''}}
                @elseif($order->payment_status =='paid')
                    {{ $order->grand_total ?? ''}}
                @else
                    0.00
                @endif
            </td>

            <td class="text-center">
                @if($order->payment_status =='partial paid')
                    {{ ($order->grand_total - $order->shipping_charge) }}
                @elseif($order->payment_status =='unpaid')
                    {{ $order->grand_total ?? ''}}
                @else
                    0.00
                @endif
            </td>
            <td class="text-center">
                @if($order->payment_status == 'paid')
                    @php
                        $sum = 0;
                        $sum1 = 0;
                        $sum2 = 0;
                        $orderDetails = $order->order_details;
                        foreach($orderDetails as $key => $orderDetail)
                        {
                            $sum1+= $orderDetail->v_comission;
                            $sum2+= $orderDetail->qty;
                            $sum+= $orderDetail->v_comission * $orderDetail->qty;
                        }
                    @endphp
                    {{ $sum ?? ''}}
                @else
                    <span>0.00</span>
                @endif
            </td>
            <td class="text-center">
                @php
                    $status = $order->delivery_status;
                    if($order->delivery_status == 'Delivered') {
                        $status = '<span class="badge rounded-pill alert-success">Delivered</span>';
                    }
                    if($order->delivery_status == 'Cancelled') {
                        $status = '<span class="badge rounded-pill alert-danger">Cancelled</span>';
                    }

                @endphp
                {!! $status !!}
            </td>
            <td class="text-center">
                @php
                    $status = $order->payment_status;
                    if($order->payment_status == 'unpaid') {
                        $status = '<span class="badge rounded-pill alert-danger">Unpaid</span>';
                    }
                    elseif($order->payment_status == 'paid') {
                        $status = '<span class="badge rounded-pill alert-success">Paid</span>';
                    }
                    else{
                        $status = '<span class="badge rounded-pill alert-warning">Partial Paid</span>';
                    }

                @endphp
                {!! $status !!}
            </td>
            <td>
                @php
                    $staffId = $order->staff_id;
                    $staffName = App\Models\User::where('id', $staffId)->pluck('name')->first();
                @endphp

                {{ $staffName ?? 'Admin' }}
            </td>
            <td class="text-end">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('18', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                 <a  class="btn btn-primary btn-icon btn-circle btn-sm btn-xs" href="{{route('all_orders.show',$order->id) }}">
                    <i class="fa-solid fa-eye"></i>
                </a>
                @endif
                <a class="btn btn-primary btn-icon btn-circle btn-sm btn-xs" href="{{ route('invoice.download', $order->id) }}">
                    <i class="fa-solid fa-download"></i>
                </a>
                <!--<a href="{{ route('delete.orders',$order->id) }}" id="delete" class="btn btn-primary btn-icon btn-circle btn-sm btn-xs" data-href="#" >-->
                <!--    <i class="fa-solid fa-trash"></i>-->
                <!--</a>-->
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
