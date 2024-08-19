@extends('admin.admin_master')
@section('admin')
    <section class="content-main">
        <div class="content-header">
            <h2 class="content-title">Online User List <span class="badge rounded-pill alert-success"> {{ $count }}
                </span></h2>
            <div>
                <a href="{{ route('online.user.print') }}" class="btn btn-primary"><i class="material-icons md-print"></i></a>
                {{-- <a href="{{ route('customer.create') }}" class="btn btn-primary"><i class="material-icons md-plus"></i> Create Customer</a> --}}
            </div>
        </div>
        </div>
        <div class="card mb-4">
            <div class="row mt-2 ms-2 me-2 " style="justify-content: space-between">
                <div class="col-sm-3 col-6">
                    <a href="{{ route('online_user.export') }}" class="btn btn-success">Export</a>
                </div>
                <div class="col-sm-3 col-6">
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" name="user_search" id="user_search" placeholder='Search Here...'
                            class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                        <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa fa-search"
                                aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm ajax_table">
                    @include('backend.customer.user_table')
                </div>
                <!-- table-responsive //end -->
            </div>
            <!-- card-body end// -->
        </div>
    </section>
@endsection
@push('footer-script')
    <script>
        //pagination
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var condition = "onlineUser";
            var search = $('#user_search').val();
            fetch_data(page, condition, search);
        });

        function fetch_data(page, condition, search) {
            $.ajax({
                url: "{{ route('user.pagination') }}",
                data: {
                    page: page,
                    condition: condition,
                    search: search
                },
                success: function(data) {
                    $('.ajax_table').html(data);
                }
            });
        }
        //product search
        $(document).on('keyup', '#user_search', function() {
            var search = $(this).val();
            if (search.length > 1) {
                $.ajax({
                    url: "{{ route('user.search') }}",
                    method: "get",
                    data: {
                        search: search,
                        type: 'onlineUser',
                    },
                    success: function(response) {
                        if (response) {
                            $(".ajax_table").html(response);
                        } else {
                            $('#empty_msg').html(
                                ` <div class="text-center">Product Not Found</div>  `
                            );
                        }
                    }
                })
            } else {
                $.ajax({
                    url: "{{ route('user.search') }}",
                    method: "get",
                    data: {
                        search: search,
                        type: 'onlineUser'
                    },
                    success: function(response) {
                        if (response) {
                            $(".ajax_table").html(response);
                        }
                    }
                })
            }
        });
    </script>
@endpush
