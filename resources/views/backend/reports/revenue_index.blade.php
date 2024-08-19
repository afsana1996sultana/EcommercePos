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
</style>

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Monthly Revenue</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="javascript:void(0);" id="searchForm">
                        <div class="form-group row mb-3">
                            <div class="col-md-3">
                                <label class="col-form-label"><span>All Revenue :</span></label>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="custom_select">
                                    <select class="select-active select-nice form-select d-inline-block mb-lg-0 mr-5 mw-200" name="order_by" id="order_by">
                                        <option value="" selected="">Revenue Type</option>
                                        <option value="0">Ecommerce</option>
                                        <option value="1">POS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 mt-2">
                                <div class="custom_select">
                                    <input type="text" id="reportrange" class="form-control" name="date" placeholder="Filter by date" data-format="DD-MM-Y" value="Filter by date" data-separator=" - " autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
                                <button class="btn btn-primary" type="submit">Filter</button>
                            </div>

                            <div class="col-md-2 mt-2"></div>
                        </div>
                    </form>

                    <div class="table-responsive-sm">
                        <table class="table table-bordered table-hover" id="example" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Revenue Amount</th>
                                    <th>Vat Amount(5%)</th>
                                </tr>
                            </thead>
                            <tbody id="RevenueData"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('footer-script')
<script type="text/javascript">
    $(function() {
        var start = moment();
        var end = moment();

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
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        // Date Search Input
        $('#searchForm').submit(function(e) {
            e.preventDefault();
            var order_by = $('#order_by').val();
            var dateRange = $('#reportrange').data('daterangepicker');
            var startDate = dateRange.startDate.format('YYYY-MM-DD');
            var endDate = dateRange.endDate.format('YYYY-MM-DD');

            var formData = {
                start_date: startDate,
                end_date: endDate,
                order_by: order_by
            };

            $.ajax({
                url: '{{ route("report.revenue.dateFilter") }}',
                type: 'GET',
                data: formData,
                success: function(response) {
                    if (response.length > 0) {
                        showRevenueData(response);
                    } else {
                        $('#RevenueData').empty();
                        $('#RevenueData').append('<tr><td colspan="3">No records found</td></tr>');
                    }
                },
            });
        });

        cb(start, end);
    });

    function showRevenueData(data) {
        var html = '';
        $.each(data, function(index, row) {
            html += '<tr>';
            html += '<td>' + (index + 1) + '</td>';
            html += '<td>' + row.revenue_amount + '</td>';
            html += '<td>' + row.vat_amount + '</td>';
            html += '</tr>';
        });
        $('#RevenueData').html(html);
    }
</script>
@endpush
