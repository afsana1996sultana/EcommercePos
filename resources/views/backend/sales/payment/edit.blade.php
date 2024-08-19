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
                    <form method="post" action="{{ route('payment.update',$payment->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <label for="user_id" class="col-form-label" style="font-weight: bold;">Customer Name:</label>
                                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" name="user_id" id="user_id" disabled>
                                        <option value="0">--Select Customer--</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if($payment->user_id == $user->id) selected @endif>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="due_amount" class="col-form-label" style="font-weight: bold;">Pre Due Amount:</label>
                                <input type="number" name="due" id="due_amount" class="form-control" value="{{ $payment->paid + $payment->due}}" readonly>
                             </div>
                            <div class="col-md-3 form-group mb-4">
                                <label for="amount" class="col-form-label" style="font-weight: bold;"> Amount:</label>
                                <input class="form-control" id="amount" type="text" name="amount" value="{{ $payment->amount }}" readonly>
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="payment_date" class="col-form-label" style="font-weight: bold;">Date:</label>
                                <?php $date = date('Y-m-d'); ?>
                                <input type="date" name="payment_date" id="payment_date" value="{{ $payment->payment_date }}" class="form-control">
                                @error('payment_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3 mb-4">
                                <label for="receive_amount" class="col-form-label" style="font-weight: bold;">Receive:</label>
                                <input type="number" name="receive_amount" id="receive_amount"  class="form-control " value="{{ $payment->paid }}" >
                                 @error('receive_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                 @enderror
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="ndue_amount" class="col-form-label" style="font-weight: bold;">Next Due Amount:</label>
                                <input type="number" id="ndue_amount" name="ndue_amount"  class="form-control" value="{{ $payment->due }}" readonly>
                             </div>

                             {{-- <div class="form-group col-md-6 mb-4">
                                <label for="discount" class="col-form-label" style="font-weight: bold;">Discount:</label>
                                <input type="number" name="discount" id="discount"  class="form-control" value="{{ $payment->discount }}">
                             </div> --}}

                            <div class="form-group col-md-6 mb-4">
                                <label for="payment_method" class="col-form-label" style="font-weight: bold;">Payment Method:</label>
                                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" id="payment_method" name="payment_method" required>
                                        <option value="cash" @if($payment->payment_method == "cash" ) selected  @endif>Cash</option>
                                        <option value="cank" @if($payment->payment_method == "cank" ) selected  @endif>Bank</option>
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
<script type="text/javascript">
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
</script>
<script type="text/javascript">
    /* =============== Next Due Amount Show ============== */
     $(document).on('keyup', '#receive_amount',function(){
        var due_amount =  $('#due_amount').val();
        var receive_amount =  $('#receive_amount').val();
        var total_due_amount = parseFloat(due_amount-receive_amount);
        // alert(total_due_amount)

        $('#ndue_amount').val(parseFloat(total_due_amount));
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
