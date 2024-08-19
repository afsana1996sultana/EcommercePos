@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Advance Payment Update</h2>
        <div class="">
            <a href="{{ route('payment.index') }}" class="btn btn-primary"><i class="material-icons md-plus"></i> Payment List</a>
        </div>
    </div>
    <div class="row justify-content-center">
    	<div class="col-sm-12">
    		<div class="card">
		        <div class="card-body">
                    <form method="post" action="{{ route('advanced.update',$payment->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="advanced_type" value="1">
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <label for="user_id" class="col-form-label" style="font-weight: bold;">Customer Name:</label>
                                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" name="user_id" id="user_id" disabled>
                                        <option value="0">--Select Customer--</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if($payment->user_id == $user->id) selected @endif>{{ $user->name}}  {{ $user->phone }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="form-group col-md-3 mb-4">
                                <label for="phone" class="col-form-label" style="font-weight: bold;">Phone Number:</label>
                                <input type="number" name="phone" id="phone" class="form-control" value="{{ $payment->phone }}" >
                             </div> --}}
                            <div class="form-group col-md-3 mb-4">
                                <label for="payment_date" class="col-form-label" style="font-weight: bold;">Date:</label>
                                <?php $date = date('Y-m-d') ?>
                                <input type="date" name="payment_date" id="payment_date" value="{{ $payment->payment_date }}" class="form-control">

                                @error('payment_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3 mb-4">
                                <label for="receive_amount" class="col-form-label" style="font-weight: bold;">Receive:</label>
                                <input type="number" name="receive_amount" id="receive_amount"  class="form-control " value="{{ $payment->paid }}">
                                 @error('receive_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                 @enderror
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label for="transaction_num" class="col-form-label" style="font-weight: bold;">Transaction Number:</label>
                                <input type="text" name="transaction_num" id="transaction_num"  class="form-control " value="{{ $payment->transaction_num }}" readonly>
                                 @error('transaction_num')
                                    <span class="text-danger">{{ $message }}</span>
                                 @enderror
                            </div>

                            <div class="form-group col-md-6 mb-4">
                                <label for="payment_method" class="col-form-label" style="font-weight: bold;">Payment Method:</label>
                                <div class="custom_select">
                                    <select class="form-control select-active w-100 form-select select-nice" id="payment_method" name="payment_method" required>
                                        <option value="cash" @if($payment->payment_method == "cash" ) selected  @endif>Cash</option>
                                        <option value="bank" @if($payment->payment_method == "bank" ) selected  @endif>Bank</option>
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
@endpush
