@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
    .customer_show {
        position: absolute;
        top: 100%;
        left: 1px;
        background: #e6e6e7;
        width: 100%;
        height: auto;
        z-index: 9;
    }
   .customer_show ul li div {
        font-size: 16px;
        cursor: pointer;
        line-height: 16px;
        padding: 7px 13px;
    }
    .customer_show ul li div:hover{
        background: aliceblue;
    }
</style>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Advanced Payment Add</h2>
        <div class="">
            <a href="{{ route('payment.index') }}" class="btn btn-primary"><i class="material-icons md-plus"></i> Payment List</a>
        </div>
    </div>
    <div class="row justify-content-center">
    	<div class="col-sm-12">
    		<div class="card">
		        <div class="card-body">
                    <form method="post" action="{{ route('advanced.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="advanced_type" value="1">
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <label for="user_id" class="col-form-label" style="font-weight: bold;">Customer Add:</label>
                                <div class="custom_select">
                                    {{-- <select class="form-control select-active w-100 form-select select-nice" name="user_id" id="user_id">
                                        <option value="0">--Select Customer--</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id')== $user->id ? 'selected' : '' }}>{{ $user->name }} - {{ $user->phone }}</option>
                                        @endforeach
                                    </select> --}}
                                    <input type="search" id="customerList" class="form-control" name="search" autocomplete="off" required>
                                        <div class="customer_show">
                                        </div>
                                    <input type="hidden" id="User_Id" name="user_id">
                                    <input type="hidden" id="User_name" name="name">
                                    <input type="hidden" id="User_phone" name="phone">
                                        @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>
                            {{-- <div class="form-group col-md-3 mb-4">
                                <label for="phone" class="col-form-label" style="font-weight: bold;">Phone Number:</label>
                                <input type="number" name="phone" id="phone" class="form-control" value="" >
                             </div> --}}
                             <div class="form-group col-md-3 mb-4">
                                <label for="receive_amount" class="col-form-label" style="font-weight: bold;">Receive:</label>
                                <input type="number" name="receive_amount" id="receive_amount"  class="form-control " value="" required>
                                 @error('receive_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                 @enderror
                            </div>
                             <div class="form-group col-md-3 mb-4">
                                <label for="transaction_num" class="col-form-label" style="font-weight: bold;">Transaction Number:</label>
                                <input type="text" name="transaction_num" id="transaction_num"  class="form-control " autocomplete="off" required>
                                 @error('receive_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                 @enderror
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="payment_date" class="col-form-label" style="font-weight: bold;">Date:</label>
                                <?php $date = date('Y-m-d') ?>
                                <input type="datetime-local" name="payment_date" id="payment_date" value="<?= $date ?>" class="form-control">
                                @error('payment_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="payment_method" class="col-form-label" style="font-weight: bold;">Payment Method:</label>
                                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" id="payment_method" name="payment_method" required>
                                        <option value="">Select a Payment</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-4 justify-content-sm-end">
                            <div class="col-lg-3 col-md-4 col-sm-5 col-6">
                                <input type="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </form>
		        </div>
		        <!-- card body .// -->
		    </div>
		    <!-- card .// -->
    	</div>
    </div>
</section>

@endsection

@push('footer-script')
{{-- <script type="text/javascript">
    $(document).ready(function() {
      $('select[name="user_id"]').on('change', function(){
          var user_id = $(this).val();
          if(user_id) {
              $.ajax({
                  url: "{{  url('/admin/payment/customer_number/ajax') }}/"+user_id,
                  type:"GET",
                  dataType:"json",
                  success:function(data) {
                    $('#phone').val(data.phone);
                  },
              });
          } else {
              alert('danger');
          }
      });
  });
</script> --}}

<script>
    $("body").on("keyup", "#customerList", function() {
            let text = $("#customerList").val();
            if (text.length > 0) {

                $.ajax({
                    data: {
                        search: text
                    },
                    url: "{{ route('advancedPayment.searchCustomer') }}",
                    method: 'get',
                    beforSend: function(request) {
                        return request.setReuestHeader('X-CSRF-Token', (
                            "meta[name='csrf-token']"))

                    },
                    success: function(result) {
                        if (result) {
                            $(".customer_show").html(result);
                        } else {
                            $('.customer_show').html("No results found.");
                        }
                    }

                }); // end ajax
            } // end if
            if (text.length < 1) $(".customer_show").html("");
        });

        $(document).on('click', '.user', function() {
        let userId = $(this).data('id');
        let userName = $(this).data('name');
        let userPhone = $(this).data('phone');
        $("#customerList").val(userName + "  " + userPhone);
        $('input[name="user_id"]').val( userId);
        $("#User_phone").val('');
        $("#User_name").val('');
        $("#userList").hide();
    });

    $(document).on('input', '#customerList', function() {
    let inputVal = $(this).val();

    let selectedUserName = $("#User_name").val();
    let selectedUserPhone = $("#User_phone").val();

    if (!isNaN(inputVal)) {
        $("#User_phone").val(inputVal);
        $('input[name="user_id"]').val('');
    } else {
        $("#User_name").val(inputVal);
        $('input[name="user_id"]').val('');
    }
});
</script>
@endpush
