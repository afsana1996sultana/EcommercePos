@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Refund Add</h2>
        <div class="">
            <a href="{{ route('refund.index') }}" class="btn btn-primary"><i class="material-icons md-plus"></i> Refund List</a>
        </div>
    </div>
    <div class="row justify-content-center">
    	<div class="col-sm-12">
    		<div class="card">
		        <div class="card-body">
                    <form method="post" action="{{ route('refund.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <label for="invoice_no" class="col-form-label" style="font-weight: bold;">Invoice No:</label>
                                <input type="number" name="invoice_no" id="invoice_no" class="form-control">
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
                                <label for="refund_amount" class="col-form-label" style="font-weight: bold;">Refund Amount: <span class="text-danger"> *</span></label>
                                <input type="number" name="refund_amount" id="refund_amount"  class="form-control">
                                 @error('refund_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                 @enderror
                            </div>

                            <div class="form-group col-md-3 mb-4">
                              <label for="transaction_id" class="col-form-label" style="font-weight: bold;">Trasnsection ID: <span class="text-danger"> *</span></label>
                              <input type="text" id="transaction_id" name="transaction_id"  class="form-control"  >
                            </div>

                            <div class="form-group col-md-3 mb-4">
                                <label for="payment_method" class="col-form-label" style="font-weight: bold;">Payment Method: <span class="text-danger"> *</span></label>
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
                                      0153414032 (Telitalk)
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
                                        01775782602 (GP)marchant
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="01329657140" id="flexRadioDefault4" >
                                    <label class="form-check-label" for="flexRadioDefault4">
                                        01329657140 (GP)marchant online
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
</script>
@endpush