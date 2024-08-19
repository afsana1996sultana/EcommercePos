@extends('admin.admin_master')
@section('admin')
    <style type="text/css">
        table,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td {
            border: 1px solid #dee2e6 !important;
        }

        th {
            font-weight: bolder !important;
        }

        table#example thead tr th.sorting:nth-child(4) {
            width: 100px !important;
        }

        table#example thead tr th.sorting:nth-child(6) {
            width: 200px !important;
        }

        table.dataTable thead>tr>th.sorting {
            padding-right: 0px !important;
        }

        table.dataTable thead th {
            padding: 1px !important;
        }
    </style>

    <section class="content-main">
        <div class="content-header">
            <div>
                <h2 class="content-title card-title">Pos Order List</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <!-- card-header end// -->
                    <div class="card-body">
                        <div class="row mb-1 pack_print" style="justify-content: space-between">
                            <div class="col-sm-3 col-6">
                                @if (Auth::guard('admin')->user()->role == '1' ||
                                        in_array('19', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                                    <button type="button" class="btn   btn-sm" id="all_print" target="blank">Package &
                                        Print</button>
                                @endif
                                <!--@if (Auth::guard('admin')->user()->role == '1' ||
                                        in_array('20', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
    -->
                                <!--   <button type="button" class="btn   btn-sm" id="all_delete" style="background:#ff0000">Delete All</button>-->
                                <!--
    @endif-->
                                <a href="{{ route('pos_order.export') }}" class="btn btn-success">Pos Export</a>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="input-group input-group-sm mb-3">
                                    <input type="text" name="pro_search" id="pro_search" placeholder='Search Here...'
                                        class="form-control" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-sm">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa fa-search"
                                            aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                        <form class="" action="" method="GET">
                            <div class="form-group row mb-4">
                                <div class="col-md-2">
                                    <label class="col-form-label"><span>All Orders :</span></label>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="custom_select">
                                        <select
                                            class=" select-active select-nice form-select d-inline-block mb-lg-0 mr-5 mw-200"
                                            name="shipping_type" id="shipping_type">
                                            <option value="" selected="">Shipping Type</option>
                                            <option value="1" @if ($shipping_type == '1') selected @endif>Inside
                                                Dhaka (Pathao)</option>
                                            <option value="2" @if ($shipping_type == '2') selected @endif>Outside
                                                Dhaka (Pathao )</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="custom_select">
                                        <select
                                            class="form-select d-inline-block select-active select-nice mb-lg-0 mr-5 mw-200"
                                            name="delivery_status" id="delivery_status">
                                            <option value="" selected="">Delivery Status</option>
                                            <option value="Pending" @if ($delivery_status == 'Pending') selected @endif>
                                                Pending</option>
                                            <option value="Holding" @if ($delivery_status == 'Holding') selected @endif>
                                                Holding</option>
                                            <option value="Processing" @if ($delivery_status == 'Processing') selected @endif>
                                                Processing</option>
                                            <option value="Shipped" @if ($delivery_status == 'Shipped') selected @endif>
                                                Shipped</option>
                                            <option value="Delivered" @if ($delivery_status == 'Delivered') selected @endif>
                                                Delivered</option>
                                            <option value="Cancelled" @if ($delivery_status == 'Cancelled') selected @endif>
                                                Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="custom_select">
                                        <select
                                            class=" select-active select-nice form-select d-inline-block mb-lg-0 mr-5 mw-200"
                                            name="payment_status" id="payment_status">
                                            <option value="" selected="">Payment Status</option>
                                            <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                                Unpaid</option>
                                            <option value="paid" @if ($payment_status == 'paid') selected @endif>Paid
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    {{-- <div class="custom_select">
                                        <input type="text" id="reportrange" class="form-control" name="date"
                                            placeholder="Filter by date" data-format="DD-MM-Y" value="{{ $date }}"
                                            data-separator=" - " autocomplete="off">
                                    </div> --}}
                                    <div class="custom_select">
                                        <input type="text" id="reportrange" class="form-control" name="date"
                                            placeholder="Filter by date" data-format="DD-MM-Y" value="{{ $date }}"
                                            data-separator=" - " autocomplete="off">
                                        <input type="hidden" id="dateadd" class="form-control " name="dateadd"
                                            data-format="DD-MM-Y" value="{{ $date }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <button class="btn btn-primary" type="submit">Filter</button>
                                </div>
                            </div>


                            <div class="table-responsive-sm order-product-show">
                                @include('backend.sales.all_orders.posOrder.pos_order_products')
                            </div>
                        </form>
                        <!-- table-responsive //end -->
                    </div>
                    <!-- card-body end// -->
                </div>
                <!-- card end// -->
            </div>
        </div>
    </section>
@endsection

@push('footer-script')
    <script type="text/javascript">
        $(function() {
            var dateValue = $('input[name="date"]').val();
            var start, end;

            if (dateValue) {
                var dates = dateValue.split(' - ');
                start = moment(dates[0], 'MM/DD/YYYY');
                end = moment(dates[1], 'MM/DD/YYYY');
            } else {
                start = moment();
                end = moment();
            }

            $('input[name="date"]').daterangepicker({
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
        $(document).ready(function() {
            function bindCheckboxEvents() {
                function updateSelectAll() {
                    var allChecked = $('.check_ids:checked').length === $('.check_ids').length;
                    $('#select_all_ids').prop('checked', allChecked);
                }
                $('.check_ids').change(function() {
                    updateSelectAll();
                });
                $('#select_all_ids').change(function() {
                    $('.check_ids').prop('checked', $(this).prop('checked'));
                });
            }
            bindCheckboxEvents();
            //pagination
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var condition = "posOrder";
                var search = $('#pro_search').val();
                var shipping_type = $('#shipping_type').val();
                var delivery_status = $('#delivery_status').val();
                var payment_status = $('#payment_status').val();
                var dateadd = $('#dateadd').val();
                fetch_data(page, condition, search, shipping_type, delivery_status, payment_status,
                    dateadd);
            });

            function fetch_data(page, condition, search, shipping_type, delivery_status, payment_status, dateadd) {
                $.ajax({
                    url: "{{ route('Posorder.pagination') }}",
                    data: {
                        page: page,
                        condition: condition,
                        search: search,
                        shipping_type: shipping_type,
                        delivery_status: delivery_status,
                        payment_status: payment_status,
                        dateadd: dateadd,
                    },
                    success: function(data) {
                        $('.order-product-show').html(data);
                        bindCheckboxEvents();
                    }
                });
            }
            //product search
            $(document).on('keyup', '#pro_search', function() {
                var search = $(this).val();
                var shipping_type = $('#shipping_type').val();
                var delivery_status = $('#delivery_status').val();
                var payment_status = $('#payment_status').val();
                var dateadd = $('#dateadd').val();
                if (search.length > 3) {
                    $.ajax({
                        url: "{{ route('Posorder.pro_search') }}",
                        method: "get",
                        data: {
                            search: search,
                            type: 'posOrder',
                            shipping_type: shipping_type,
                            delivery_status: delivery_status,
                            payment_status: payment_status,
                            dateadd: dateadd,
                        },
                        success: function(response) {
                            if (response) {
                                $(".order-product-show").html(response);
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
                        url: "{{ route('Posorder.pro_search') }}",
                        method: "get",
                        data: {
                            search: search,
                            type: 'posOrder',
                            shipping_type: shipping_type,
                            delivery_status: delivery_status,
                            payment_status: payment_status,
                            dateadd: dateadd,
                        },
                        success: function(response) {
                            if (response) {
                                $(".order-product-show").html(response);
                                bindCheckboxEvents();
                            }
                        }
                    })
                }
            });
        });
    </script>
    <script>
        $(function(e) {
            $("#all_package").click(function(e) {
                e.preventDefault();
                var all_ids = [];
                $('input:checkbox[name=ids]:checked').each(function() {
                    all_ids.push($(this).val());
                });
                $.ajax({
                    url: "{{ route('order.product.packaged') }}",
                    type: "GET",
                    data: {
                        ids: all_ids,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            toastr.success(response.message, 'message');
                            $.each(all_ids, function(key, val) {
                                $('#order_ids' + val).remove();
                            });
                            window.location.reload(true);
                        } else {
                            toastr.error(response.error, 'Error');
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(function(e) {
            $("#all_print").click(function(e) {
                e.preventDefault();
                var all_ids = [];
                $('input:checkbox[name=ids]:checked').each(function() {
                    all_ids.push($(this).val());
                });
                $.ajax({
                    url: "{{ route('order.product.Print') }}",
                    type: "GET",
                    data: {
                        ids: all_ids,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        window.location.href = response.redirect_url;
                        $.each(all_ids, function(key, val) {
                            $('#order_ids' + val).remove();
                        });
                    }
                });
            });
            $("#all_delete").click(function(e) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will Delete Selected order!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        e.preventDefault();
                        var all_ids = [];
                        $('input:checkbox[name=ids]:checked').each(function() {
                            all_ids.push($(this).val());
                        });
                        $.ajax({
                            url: "{{ route('order.all.delete') }}",
                            type: "GET",
                            data: {
                                ids: all_ids,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                location.reload()
                            }
                        });
                    }
                });

            });
        });
    </script>

    <script type="text/javascript">
        $(function() {
            $(document).on('click', '#deleteOrder', function(e) {
                e.preventDefault();
                var link = $(this).attr("href");

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete This Data!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link
                        Swal.fire(
                            'Deleted!',
                            'Your Order has been deleted.',
                            'success'
                        )
                    }
                })
            });
        });
    </script>
@endpush