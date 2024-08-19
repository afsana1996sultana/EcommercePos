@extends('admin.admin_master')
@section('admin')
    <section class="content-main">
        <div class="content-header">
            <h3 class="content-title">Advance Payment list </h3>
            <div class="row">
                <div class="col-12">
                    {{-- <a href="{{ route('advanced.create') }}" class="btn btn-primary"><i class="material-icons md-plus"></i>Advance Payment Advanced</a> --}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="material-icons md-plus"></i> Create
                    </button>

                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="form-group row mb-3 mt-4 ms-2 me-2">
                <div class="col-md-2 mt-2 ">
                </div>
                <div class="col-md-2 mt-2">
                </div>
                <div class="col-md-2 mt-2">
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3 mt-2 ">
                    <div class="custom_select">
                        <input type="text" id="ledger_search" class="form-control" name="ledger_search"
                            placeholder="search here..." value="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm ledger-item-show">
                    @include('backend.sales.payment.advance_payment.paymentlist')
                </div>
                <!-- table-responsive //end -->
            </div>

            <!-- card-body end// -->
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Advance Payment Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('advanced.payment.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::guard('admin')->user()->id }}">
                        <div class="row p-3">
                            <div class="col-md-6 form-group mb-4">
                                <label for="amount" class="col-form-label" style="font-weight: bold;"> Transaction
                                    Number:</label>
                                <input class="form-control" id="transaction_no" type="number" required
                                    name="transaction_no" value="{{ old('transaction_no') }}">
                                @error('transaction_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group mb-4">
                                <label for="amount" class="col-form-label" style="font-weight: bold;"> Received
                                    Advance Amount:</label>
                                <input class="form-control" id="advance_amount" type="text" required
                                    name="advance_amount" value="{{ old('advance_amount') }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('advance_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <input class="form-control" id="received" type="hidden" required name="received"
                                    value="{{ old('received') }}">
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="payment_date" class="col-form-label" style="font-weight: bold;">Date:</label>
                                <?php $date = date('Y-m-d'); ?>
                                <input type="datetime-local" name="date" id="date" value="<?= $date ?>"
                                    class="form-control">
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input agent_number" type="radio" name="agent_number"
                                        value="0153414032" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        0153414032 (Teletalk)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input agent_number" type="radio" name="agent_number"
                                        value="01875523815" id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        01875523815 (Robi)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input agent_number" type="radio" name="agent_number"
                                        value="01775782602" id="flexRadioDefault3">
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        01775782602 (GP)Merchant
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input agent_number" type="radio" name="agent_number"
                                        value="01329657140" id="flexRadioDefault4">
                                    <label class="form-check-label" for="flexRadioDefault4">
                                        01329657140 (GP)Merchant online
                                    </label>
                                </div>
                                @error('agent_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="checkbtn" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('footer-script')
    <script>
        $(document).on('keyup', "#advance_amount", function() {
            // console.log('lizzzzzza')
            var advance_amount = $(this).val();
            $('#received').val(advance_amount);
        });
    </script>

    <script>
        $(document).on('click', '#surebtn', function(e) {
            e.preventDefault();
            var link = $(this).attr("href");
            Swal.fire({
                title: 'Are you sure?',
                text: "Refunded This Data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Refunded!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link
                    Swal.fire(
                        'Refunded!',
                        'This Order has been Refunded.',
                        'success'
                    )
                }
            })
        });
    </script>

    <script>
        //pagination
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var condition = "advance_payment";
            var search = $('#ledger_search').val();
            var agent_number = $('#agent_number').val();
            var dateadd = $('#dateadd').val();
            fetch_data(page, condition, search, agent_number,
                dateadd);
        });

        function fetch_data(page, condition, search, agent_number, dateadd) {
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
                        type: 'advance_payment',
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
                        type: 'advance_payment',
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
<script>
    $(document).on('click', '#checkbtn', function(e) {
        e.preventDefault();
        var user_id = $('#user_id').val();
        var advance_amount = $('#advance_amount').val();
        var transaction_no = $('#transaction_no').val();
        var received = $('#received').val();
        var date = $('#date').val();
        var agent_number = $('input[name="agent_number"]:checked').val();
        $.ajax({
            url: "{{ route('old.advance.payment.check') }}",
            method: "get",
            data: {
                transaction_no: transaction_no,
                user_id: user_id,
                advance_amount: advance_amount,
                received: received,
                date: date,
                agent_number: agent_number,
            },
            success: function(response) {
                if (response.status === 'error') {
                    toastr.error(response.error, 'Error');
                } else if (response.status === 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Duplicate Data!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Duplicate!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('form').submit();
                        }
                    });
                } else if (response.status === 1) {
                    $('form').submit();
                }
            }
        });
    });
</script>
@endpush
