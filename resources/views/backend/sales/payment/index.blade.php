@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h3 class="content-title">Payment list </h3>
        <div class="row">
            {{-- <div class="col-6">
                <a href="{{ route('advanced.create') }}" class="btn btn-primary"><i class="material-icons md-plus"></i>Payment Advanced</a>

            </div> --}}
            <div class="col-12">
                <a href="{{ route('payment.create') }}" class="btn btn-primary"><i class="material-icons md-plus"></i>Payment Create</a>

            </div>
        </div>
    </div>
    </div>
    <div class="card mb-4">
        <form class="" action="" method="GET">
            <div class="form-group row mb-3 ms-5 mt-4">
                <div class="col-md-2 mt-2">
                    <div class="custom_select">
                        <input type="text" id="reportrange" class="form-control" name="selectdate" placeholder="Filter by date" data-format="DD-MM-Y" value="Filter by date" data-separator=" - " autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2 mt-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
            </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table id="example" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl</th>
                            <th scope="col">Order Invoice No</th>
                            <th scope="col">Cusomer Number</th>
                            <th scope="col">Received</th>
                            <th scope="col">Payment Method</th>
                            <th scope="col">Trasnsection No</th>
                            <th scope="col">Agent Number</th>
                            <th scope="col">Date</th>
                            @if(Auth::guard('admin')->user()->role != '2')
                                {{-- <th scope="col" class="text-end">Action</th> --}}
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderpayments as $key => $orderpayment)
                        <tr>
                            <td> {{ $key+1}} </td>
                            <td> {{ App\Models\Order::find($orderpayment->order_id)?->invoice_no}} </td>
                            <td> {{ $orderpayment->user->phone ?? '' }} </td>
                            <td> {{ $orderpayment->paid ?? '' }} </td>
                            <td> {{ $orderpayment->payment_method ?? '' }} </td>
                            <td > {{ $orderpayment->transaction_num ?? '-' }} </td>
                            <td>
                                {{ $orderpayment->agent_number}}
                                @php
                                  $operator = '';
                                    if ($orderpayment->agent_number == '0153414032') {
                                        $operator = '(Teletalk)';
                                    } else if ($orderpayment->agent_number == '01875523815') {
                                        $operator = '(Robi)';
                                    } else if ($orderpayment->agent_number == '01775782602') {
                                        $operator = '(GP)Merchant';
                                    } else {
                                        $operator = '(GP)Merchant online';
                                    }
                                 @endphp
                                  {{$operator}}

                            </td>
                            <td> {{ $orderpayment->created_at->format('d-m-y') }} </td>
                            @if(Auth::guard('admin')->user()->role != '2')
                                {{-- <td class="text-end">
                                    @if($orderpayment->advanced_type == 1)
                                      <a class="btn btn-md rounded font-sm" href="{{ route('advanced.edit',$orderpayment->id) }}">Edit</a>
                                    @else
                                      <a class="btn btn-md rounded font-sm" href="{{ route('payment.edit',$orderpayment->id) }}">Edit</a>
                                    @endif
                                    @if($orderpayment->advanced_type == 1)
                                      <a class="btn btn-md rounded font-sm bg-danger" href="{{ route('advanced.destroy',$orderpayment->id) }}" id="delete">Delete</a>
                                    @else
                                     <a class="btn btn-md rounded font-sm bg-danger" href="{{ route('payment.destroy',$orderpayment->id) }}" id="delete">Delete</a>
                                    @endif

                                </td> --}}
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive //end -->
        </div>
    </form>
    </div>
</section>
@endsection
@push('footer-script')
<script type="text/javascript">
    $(function() {
        var start = moment();
        var end = moment();

        $('input[name="selectdate"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        function cb(start, end) {
            $('#reportrange').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
    });
</script>
@endpush
