@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Advance Payment Update</h2>
        <div class="">
            <a href="{{ route('advanced.index') }}" class="btn btn-primary"><i class="material-icons md-plus"></i>Advanced Payment List</a>
        </div>
    </div>
    <div class="row justify-content-center">
    	<div class="col-sm-12">
    		<div class="card">
		        <div class="card-body">
                    <form method="post" action="{{route('advanced.payment.update',$item->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <input type="hidden" name="user_id" value="{{ Auth::guard('admin')->user()->id }}">
                        <div class="row p-3">
                            <div class="col-md-6 form-group mb-4">
                                <label for="amount" class="col-form-label" style="font-weight: bold;"> Transaction Number:</label>
                                <input class="form-control" id="" type="number" required name="transaction_no" value="{{$item->transaction_no}}" readonly>
                                    @error('transaction_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                            <div class="col-md-6 form-group mb-4">
                                <label for="amount" class="col-form-label" style="font-weight: bold;"> Received Amount:</label>
                                <input class="form-control" id="advance_amount" type="text" required name="advance_amount" value="{{$item->advance_amount}}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('advance_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input class="form-control" id="received" type="hidden" required name="received" value="{{old('received')}}" value="{{$item->received}}">
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="payment_date" class="col-form-label" style="font-weight: bold;">Date:</label>
                                <input type="text" name="date" id="date" value="{{$item->date}}" class="form-control" readonly>
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="0153414032" {{ ($item->agent_number == '0153414032') ? 'checked' : ''  }} disabled>
                                    <label class="form-check-label" >
                                      0153414032 (Teletalk)
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="01875523815" {{ ($item->agent_number == '01875523815') ? 'checked' : ''  }} disabled>
                                    <label class="form-check-label" for="">
                                        01875523815 (Robi)
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="01775782602" {{ ($item->agent_number == '01775782602') ? 'checked' : ''  }} disabled>
                                    <label class="form-check-label" for="">
                                        01775782602 (GP)Merchant
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="agent_number" value="01329657140" {{ ($item->agent_number == '01329657140') ? 'checked' : ''  }} disabled>
                                    <label class="form-check-label" for="">
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
<script>
    $(document).on('keyup', "#advance_amount", function(){
        var advance_amount = $(this).val();
        $('#received').val(advance_amount);
    });
</script>
@endpush
