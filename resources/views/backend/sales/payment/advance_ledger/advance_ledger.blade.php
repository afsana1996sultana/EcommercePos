@extends('admin.admin_master')
@section('admin')
    <section class="content-main">
        <div class="content-header">
            <h3 class="content-title">Advance Payment Ledger List </h3>
        </div>
        </div>
        <div class="card mb-4">
            @php
                $total = 0;
            @endphp
            <form class="" action="" method="GET">
                <div class="form-group row mb-3 mt-4 ms-2 me-2">
                    <div class="col-md-2 mt-2 ">
                        <div class="custom_select">
                            @php
                                $uniqueAgent = array_unique($advance->pluck('agent_number')->toArray());
                            @endphp
                            <select class=" select-active select-nice form-select d-inline-block mb-lg-0 mr-5 mw-200"
                                name="agent_number" id="agent_number">
                                <option value="">Agent Number</option>
                                {{-- @foreach ($uniqueAgent as $agent)
                                        <option value="{{ $agent }}">{{ $agent }}</option>
                                      @endforeach --}}
                                <option value="0153414032" @if ($agent_number == '0153414032') selected @endif>0153414032
                                    (Teletalk)</option>
                                <option value="01875523815" @if ($agent_number == '01875523815') selected @endif>01875523815
                                    (Robi)</option>
                                <option value="01775782602" @if ($agent_number == '01775782602') selected @endif>01775782602
                                    (GP)Merchant</option>
                                <option value="01329657140" @if ($agent_number == '01329657140') selected @endif>01329657140
                                    (GP)Merchant online</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mt-2">
                        <div class="custom_select">
                            <input type="text" id="reportrange" class="form-control" name="selectdate"
                                placeholder="Filter by date" data-format="DD-MM-Y" value="{{ $date }}"
                                data-separator=" - " autocomplete="off">
                                <input type="hidden" id="dateadd" class="form-control " name="dateadd"
                                data-format="DD-MM-Y" value="{{ $date }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2 mt-2">
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                     <div class="col-md-3"></div>
                    <div class="col-md-3 mt-2 ">
                        <div class="custom_select">
                            <input type="text" id="ledger_search" class="form-control" name="ledger_search"
                                placeholder="search here..." value=""
                                 autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm ledger-item-show">
                       @include('backend.sales.payment.advance_ledger.ledger')
                    </div>
                    <!-- table-responsive //end -->
                </div>
            </form>
            <!-- card-body end// -->
        </div>
    </section>
@endsection

@push('footer-script')
    <script type="text/javascript">
          $(function() {
            var dateValue = $('input[name="selectdate"]').val();
            var start, end;
            if (dateValue) {
                var dates = dateValue.split(' - ');
                start = moment(dates[0], 'MM/DD/YYYY');
                end = moment(dates[1], 'MM/DD/YYYY');
            } else {
                start = moment();
                end = moment();
            }
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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);
            cb(start, end);
        });
    </script>
     <script>
         //pagination
         $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var condition = "advance_ledger";
                var search = $('#ledger_search').val();
                var agent_number = $('#agent_number').val();
                var dateadd = $('#dateadd').val();
                fetch_data(page, condition, search, agent_number,
                dateadd);
            });

            function fetch_data(page, condition, search, agent_number,dateadd) {
                $.ajax({
                    url: "{{ route('advance.ledger.pagination') }}",
                    data: {
                        page: page,
                        condition: condition,
                        search: search,
                        agent_number: agent_number,
                        dateadd: dateadd,
                    },
                    success: function(data) {
                        $('.ledger-item-show').html(data);
                        bindCheckboxEvents();
                    }
                });
            }
            //product search
            $(document).on('keyup', '#ledger_search', function() {
                var search = $(this).val();
                var agent_number = $('#agent_number').val();
                var dateadd = $('#dateadd').val();
                if (search.length > 1) {
                    $.ajax({
                        url: "{{ route('advance.ledger.search') }}",
                        method: "get",
                        data: {
                            search: search,
                            type: 'advance_ledger',
                            agent_number: agent_number,
                            dateadd: dateadd,
                        },
                        success: function(response) {
                            if (response) {
                                $(".ledger-item-show").html(response);
                                bindCheckboxEvents();
                            } else {
                                $('#empty_msg').html(
                                    ` <div class="text-center">Product Not Found</div>  `
                                );
                            }
                        }
                    })
                } else {
                    $.ajax({
                        url: "{{ route('advance.ledger.search') }}",
                        method: "get",
                        data: {
                            search: search,
                            type: 'advance_ledger',
                            agent_number: agent_number,
                            dateadd: dateadd,
                        },
                        success: function(response) {
                            if (response) {
                                $(".ledger-item-show").html(response);
                                bindCheckboxEvents();
                            }
                        }
                    })
                }
            });
    </script>
@endpush
