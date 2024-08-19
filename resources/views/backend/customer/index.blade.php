@extends('admin.admin_master')
@section('admin')
    <section class="content-main">
        <div class="content-header">
            <h2 class="content-title">Customer List <span class="badge rounded-pill alert-success"> {{ $count }}
                </span></h2>
            <div>
                <a href="{{ route('all.customer.print') }}" class="btn btn-primary"><i class="material-icons md-print"></i></a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importExcel">
                    Import Customer
                </button>
                <a href="{{ route('customer.create') }}" class="btn btn-primary"><i class="material-icons md-plus"></i>
                    Create Customer</a>

            </div>
        </div>
        </div>
        <div class="card mb-4">
            <div class="row mt-2 ms-2 me-2 " style="justify-content: space-between">
                <div class="col-sm-3 col-6">
                    <a href="{{ route('pos_user.export') }}" class="btn btn-success">Export</a>
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
                    @include('backend.customer.customer_table')
                </div>
                <!-- table-responsive //end -->
            </div>
            <!-- card-body end// -->
        </div>
    </section>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="importExcel" tabindex="-1" aria-labelledby="importExcelLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelLabel">Import New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class=" mb-2">
                            <label for="username" class="col-form-label" style="font-weight: bold;">Excel file:</label>
                            <input class="form-control" type="file" name="file">
                            @error('username')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="mb-4">A file should be a CSV file with columns named name,email,phone and address.</p>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">back</button>
          <button type="button" class="btn btn-primary">Save</button>
        </div> --}}
                </form>
            </div>
        </div>
    </div>
@endsection
@push('footer-script')
    <script>
        //pagination
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var condition = "posUser";
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
                        type: 'posUser',
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
                        type: 'posUser'
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
