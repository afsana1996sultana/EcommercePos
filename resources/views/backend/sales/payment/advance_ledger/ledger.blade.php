<table id="" class="table table-bordered table-striped" width="100%">
    <thead>
        <tr>
            <th scope="col">Sl</th>
            <th scope="col">Transaction Number</th>
            <th scope="col">Order Code.</th>
            <th scope="col">Received</th>
            <th scope="col">Date</th>
            <th scope="col">Agent Number</th>
            <th scope="col">Received By</th>
            <th scope="col">Action</th>
            {{-- @if (Auth::guard('admin')->user()->role != '2')
        <th scope="col" class="text-end">Action</th>
    @endif --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($advance as $key => $item)
            <tr>
                <td> {{ $startIndex+$key + 1 }} </td>
                <td> {{ $item->transaction_no ?? ''}} </td>
                <td> {{ $item->order_id ?? '' }} </td>
                <td>
                    {{ $item->received }} TK
                    @php
                        $total += $item->received;
                    @endphp
                </td>
                <td> {{ \Carbon\Carbon::parse($item->date)->format('d-m-y') }}  ({{ \Carbon\Carbon::parse($item->date)->format('h:i: A')}})</td>
                <td>
                    {{ $item->agent_number }}
                    @php
                        $operator = '';
                        if ($item->agent_number == '0153414032') {
                            $operator = '(Teletalk)';
                        } elseif ($item->agent_number == '01875523815') {
                            $operator = '(Robi)';
                        } elseif ($item->agent_number == '01775782602') {
                            $operator = '(GP)Merchant';
                        } else {
                            $operator = '(GP)Merchant online';
                        }
                    @endphp
                    {{ $operator }}
                </td>
                <td> {{ $item->user->name }} </td>
                @if (Auth::guard('admin')->user()->role != '2')
                    <td class="text-end">
                        <a class="btn btn-md rounded font-sm bg-danger"
                            href="{{ route('advanced.payment.destroy', $item->id) }}"
                            id="delete">Delete</a>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
<h4 class="mt-3">Total :{{ $total }} TK</h4>
{{ $advance->links() }}
