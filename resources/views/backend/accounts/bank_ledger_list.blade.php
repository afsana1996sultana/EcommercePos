
@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h3 class="content-title">Bank Ledger list </h3>
        <div class="row">
            <div class="col-12">
                <a href="{{ route('bank.ledgers.create') }}" class="btn btn-primary"><i class="material-icons md-plus"></i>Bank Ledger Create</a>

            </div>
        </div>
    </div>
    </div>
    <div class="card mb-4">
        @php
            $total =0;
        @endphp
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
                                <th scope="col">Receive Amount</th>
                                <th scope="col">Trasnsection Id</th>
                                <th scope="col">Bank Name</th>
                                <th scope="col">Date</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bankledgers as $key => $val)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $val->invoice_no}}</td>
                                <td>
                                    {{ $val->receive_amount ?? '' }}
                                    @php
                                        $total += $val->receive_amount;
                                    @endphp
                                </td>
                                <td> {{ $val->transaction_num ?? '-' }} </td>
                                <td> {{ $val->bank_name ?? '-' }} </td>
                                <td> {{ $val->payment_date ?? ''}} </td>
                                @if(Auth::guard('admin')->user()->role == '1')
                                    <td class="text-center">
                                        <a class="btn btn-md rounded font-sm" href="{{ route('bank.ledgers.edit',$val->id)}}">Edit</a>
                                        <a class="btn btn-md rounded font-sm bg-danger" href="{{ route('bank.ledgers.destroy',$val->id)}}" id="delete">Delete</a>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <h4 class="mt-3">Total :{{ $total }} TK</h4>
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

