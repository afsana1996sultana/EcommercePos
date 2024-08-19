@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Payment Add</h2>
        <div class="">
            <a href="{{ route('payment.index') }}" class="btn btn-primary"><i class="material-icons md-plus"></i> Payment List</a>
        </div>
    </div>
    <div class="row justify-content-center">
    	<div class="col-sm-12">
    		<div class="card">
		        <div class="card-body">
                    <form method="post" action="{{ route('payment.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <label for="user_id" class="col-form-label" style="font-weight: bold;">Invoice No:</label>
                                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" name="invoice_no" id="invoice_no">
                                        <option value="0">--Select Invoice No--</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->invoice_no }}" {{ old('invoice_no')== $order->invoice_no ? 'selected' : '' }}>{{ $order->invoice_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="due_amount" class="col-form-label" style="font-weight: bold;">Due Amount:</label>
                                <input type="number" name="due" id="due_amount" class="form-control" value="" readonly>
                             </div>
                            <div class="col-md-3 form-group mb-4">
                                <label for="amount" class="col-form-label" style="font-weight: bold;">Total Amount:</label>
                                <input class="form-control" id="amount" type="text" name="amount" value="" readonly>
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="payment_date" class="col-form-label" style="font-weight: bold;">Date:</label>
                                <?php $date = date('Y-m-d'); ?>
                                <input type="date" name="payment_date" id="payment_date" value="<?= $date ?>" class="form-control">
                                @error('payment_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3 mb-4">
                                <label for="receive_amount" class="col-form-label" style="font-weight: bold;">Receive:</label>
                                <input type="number" name="receive_amount" id="receive_amount"  class="form-control " value="" >
                                 @error('receive_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                 @enderror
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="ndue_amount" class="col-form-label" style="font-weight: bold;">Next Due Amount:</label>
                                <input type="number" id="ndue_amount" name="ndue_amount"  class="form-control" value="0" readonly>
                             </div>


                                <input type="hidden" name="order_id" id="order_id"  class="form-control">
                                <input type="hidden" name="user_id" id="user_id"  class="form-control">

                            <div class="form-group col-md-3 mb-4">
                              <label for="transaction_num" class="col-form-label" style="font-weight: bold;">Transection Num:</label>
                              <input type="number" id="transaction_num" name="transaction_num"  class="form-control"  >
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="payment_method" class="col-form-label" style="font-weight: bold;">Payment Method:</label>
                                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" id="payment_method" name="payment_method" required>
                                        <option value="">Select a Payment</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                        <option value="bkash">Bkash</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6 mb-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="0153414032" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                      0153414032 (Teletalk)
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="01875523815" id="flexRadioDefault2" >
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        01875523815 (Robi)
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="01775782602" id="flexRadioDefault3" >
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        01775782602 (GP)Merchant
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="01329657140" id="flexRadioDefault4" >
                                    <label class="form-check-label" for="flexRadioDefault4">
                                        01329657140 (GP)Merchant online
                                    </label>
                                  </div>
                                @error('agent_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
<script type="text/javascript">
    $(document).ready(function() {
      $('select[name="invoice_no"]').on('change', function(){
          var invoice_no = $(this).val();
          if(invoice_no) {
              $.ajax({
                  url: "{{  url('/admin/payment/customer-payment/ajax') }}/"+invoice_no,
                  type:"GET",
                  dataType:"json",
                  success:function(data) {
                    $('#due_amount').val(data.total_due);
                    $('#amount').val(data.total_amount);
                    $('#user_id').val(data.user_id);
                    $('#order_id').val(data.order_id);
                  },
              });
          } else {
              alert('danger');
          }
      });
  });
</script>
{{-- <script type="text/javascript">
    $(document).ready(function() {
      $('select[name="user_id"]').on('change', function(){
          var user_id = $(this).val();
          if(user_id) {
              $.ajax({
                  url: "{{  url('/admin/payment/customer-payment/ajax') }}/"+user_id,
                  type:"GET",
                  dataType:"json",
                  success:function(data) {
                    $('#due_amount').val(data.total_due);
                    $('#amount').val(data.total_amount);
                  },
              });
          } else {
              alert('danger');
          }
      });
  });
</script> --}}
<script type="text/javascript">
    /* =============== Next Due Amount Show ============== */
     $(document).on('keyup', '#receive_amount',function(){
        var due_amount =  $('#due_amount').val();
        var receive_amount =  $('#receive_amount').val();
        var next_due_amount = parseFloat(due_amount-receive_amount);
        // alert(total_due_amount)

        $('#ndue_amount').val(parseFloat(next_due_amount));
        // if(due_amount>receive_amount){
        //     $('#ndue_amount').val(parseFloat(total_due_amount));
        // }else{
        //     const Toast = Swal.mixin({
        //         toast:true,
        //         position: 'top-end',
        //         icon: 'error',
        //         showConfirmButton: false,
        //         timer: 1200
        //     })

        //     Toast.fire({
        //         type:'error',
        //         title: "Not allow"
        //     })
        // }
    });

    /* =============== Next Disocunt Amount Show ============== */
     $(document).on('blur', '#discount',function(){
        var ndue_amount =  $('#ndue_amount').val();
        var discount =  $('#discount').val();
        var total_discount_amount = ndue_amount-discount;
        // alert(total_discount_amount)

        if(discount ==''){
            var due_amount = $('#due_amount').val();
            var receive_amount = $('#receive_amount').val();
            var total = due_amount-receive_amount;
          $('#ndue_amount').val(total);
        }else{
            $('#ndue_amount').val(total_discount_amount);
            // if(ndue_amount>discount){
            //     $('#ndue_amount').val(total_discount_amount);
            // }else{
            //     const Toast = Swal.mixin({
            //         toast:true,
            //         position: 'top-end',
            //         icon: 'error',
            //         showConfirmButton: false,
            //         timer: 1200
            //     })

            //     Toast.fire({
            //         type:'error',
            //         title: "Not allow"
            //     })
            //     $('#due_amount').val(0);
            // }
        }

    });
  </script>
@endpush
