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
    </style>

    <section class="content-main">
        <div class="content-header">
            <div>
                <h2 class="content-title card-title">All Vendor Order List</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <!-- card-header end// -->
                    <div class="card-body">
                        @if (Auth::guard('admin')->user()->role == '1' ||
                                in_array('19', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                            <div class="row mb-1 pack_print" style="justify-content: space-between">
                                <div class="col-sm-3 col-6">
                                    <!--<button type="button" class="btn  btn-sm" id="all_package">Shipped</button>-->
                                    <button type="button" class="btn   btn-sm" id="all_print" target="blank">Package &
                                        Print</button>
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
                        @endif
                        <form class="" action="" method="GET">
                            <div class="form-group row " style="padding-bottom:30px">
                                <div class="col-md-2">
                                    <label class="col-form-label"><span>All Orders :</span></label>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <div class="custom_select">
                                        <select
                                            class="form-select d-inline-block select-active select-nice mb-lg-0 mr-5 mw-200"
                                            name="delivery_status" id="delivery_status">
                                            <option value="">Delivery Status</option>
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
                                            <option value="">Payment Status</option>
                                            <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>Unpaid
                                            </option>
                                            <option value="paid" @if ($payment_status == 'paid') selected @endif>Paid
                                            </option>
                                            <option value="partial paid" @if ($payment_status == 'partial paid') selected @endif>
                                                Partial Paid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2">
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
                                @include('backend.sales.all_orders.allVendor.all_vendor_products')
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
                    var condition = "allVendorOrder";
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
                        url: "{{ route('allVendororder.pagination') }}",
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
                    if (search.length > 1) {
                        $.ajax({
                            url: "{{ route('allVendororder.pro_search') }}",
                            method: "get",
                            data: {
                                search: search,
                                type: 'allVendorOrder',
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
                            url: "{{ route('allVendororder.pro_search') }}",
                            method: "get",
                            data: {
                                search: search,
                                type: 'allVendorOrder',
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
                            console.log(response.resultData);
                            if (response.status == 'success') {
                                toastr.success(response.message, 'message');
                                $.each(all_ids, function(key, val) {
                                    $('#order_ids' + val).remove();
                                });
                                //window.location.reload(true);
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
            });
        </script>
    @endpush
@endsection