@extends('admin.admin_master')
@section('admin')
@push('css')
<style>
    .hidden {
        display: none;
    }

</style>
@endpush
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">deliveryCoupon Edit</h2>
        <div class="">
            <a href="{{ route('deliveryCoupon.index') }}" class="btn btn-primary"><i class="material-icons md-plus"></i> deliveryCoupon List</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="row">
            <div class="col-md-8 mx-auto">
				<form method="post" action="{{ route('deliveryCoupon.update',$deliveryCoupon->id) }}" enctype="multipart/form-data">
					@csrf

					<div class="card">
						<div class="card-header">
							<h3>deliveryCoupon</h3>
						</div>
			        	<div class="card-body">
			        		<div class="row">
			                	<div class="col-md-6 mb-4">
			                        <label for="delivery_code" class="col-form-label" style="font-weight: bold;">deliveryCoupon Code: <span class="text-danger">*</span></label>
			                        <input class="form-control" id="delivery_code" type="text" name="delivery_code" placeholder="Write deliveryCoupon Code" required value="{{ $deliveryCoupon->delivery_code }}">
			                        @error('delivery_code')
			                            <p class="text-danger">{{$message}}</p>
			                        @enderror
			                    </div>

		                        <div class="col-md-6 mb-4">
		                          	<label for="limit_per_user" class="col-form-label" style="font-weight: bold;">Use Limit Per User: <span class="text-danger">*</span></label>
		                            <input class="form-control" id="limit_per_user" type="number" name="limit_per_user" placeholder="Write use limit per user" required min="0" value="{{ $deliveryCoupon->limit_per_user }}" required>
			                        @error('limit_per_user')
		                                <p class="text-danger">{{$message}}</p>
		                            @enderror
		                        </div>

		                        <div class="col-md-6 mb-4">
		                          	<label for="total_use_limit" class="col-form-label" style="font-weight: bold;">Total Use Limit: <span class="text-danger">*</span></label>
		                            <input class="form-control" id="total_use_limit" type="number" name="total_use_limit" placeholder="Enter total use limit" required min="0" value="{{ $deliveryCoupon->total_use_limit }}">
			                        @error('total_use_limit')
		                                <p class="text-danger">{{$message}}</p>
		                            @enderror
		                        </div>

								<div class="col-md-6 mb-4">
		                          	<label for="expire_date" class="col-form-label" style="font-weight: bold;">Expire Date: <span class="text-danger">*</span></label>
		                            <input class="form-control" id="expire_date" type="date" name="expire_date" min="0" value="{{ $deliveryCoupon->expire_date }}" required>
		                        </div>
		                        
		                        <div class="col-md-6 mb-4">
		                          	<label for="amount_range" class="col-form-label" style="font-weight: bold;">Amount Range:</label>
		                            <input class="form-control" id="amount_range" type="number" name="amount_range" min="0" value="{{ $deliveryCoupon->amount_range }}" required>
		                        </div>

		                        <div class="col-md-6 mb-4">
		                         	<label for="type" class="col-form-label" style="font-weight: bold;">deliveryCoupon For: <span class="text-danger">*</span></label>
					                <div class="custom_select" >
	                                    <select class="form-control select-active w-100 form-select select-nice"  name="type" id="type" onchange="toggleCustomerDropdown()" required>
	                                        <option value="" disabled>Select Customer</option>
	                                    	<option value="1" @if($deliveryCoupon->type == "1") selected @endif>All Customer</option>
	                                    	<option value="0" @if($deliveryCoupon->type == "0") selected @endif>Specific Customer</option>
	                                    </select>
	                                </div>
		                        </div>
                                <div class="col-md-6 mb-4" id="customerDropdown">
                                    <label for="user_id" class="col-form-label" style="font-weight: bold;">Customer:</label>
                                    <div class="custom_select cit-multi-select">
                                        <select class="form-control select-active w-100 form-select select-nice" name="user_id[]" id="user_id" multiple="multiple" data-placeholder="User id">
                                            @foreach($users as $user)
                                                @php
                                                    $isSelected = '';
                                                    if (is_string($deliveryCoupon->user_id)) {
                                                        $Array = explode(',', $deliveryCoupon->user_id);
                                                        if (in_array($user->id, $Array)) {
                                                            $isSelected = 'selected';
                                                        }
                                                    }
                                                @endphp
                                                <option value="{{ $user->id }}" {{ $isSelected }}>{{ $user->name }} - {{ $user->phone ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
			        		</div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="type" class="col-form-label" style="font-weight: bold;">deliveryCoupon For: <span class="text-danger">*</span></label>
                                    <div class="custom_select">
                                        <select class="form-control select-active w-100 form-select select-nice" name="producttype" id="producttype" onchange="toggleProductDropdown()" required>
                                            <option value="" disabled>Select Product</option>
                                            <option value="1" @if($deliveryCoupon->producttype == "1") selected @endif>All Product</option>
                                            <option value="0" @if($deliveryCoupon->producttype == "0") selected @endif>Specific Product</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4" id="productDropdown" >
                                    <label for="product_id" class="col-form-label" style="font-weight: bold;">Product:</label>
                                    <div class="custom_select cit-multi-select">
                                        <select class="form-control select-active w-100 form-select select-nice" name="product_id[]" id="product_id" multiple="multiple" data-placeholder="Product id" required>
                                            @foreach($Products as $Product)
                                                @php
                                                    $isSelected = '';
                                                    if (is_string($deliveryCoupon->product_id)) {
                                                        $Array = explode(',', $deliveryCoupon->product_id);
                                                        if (in_array($Product->id, $Array)) {
                                                            $isSelected = 'selected';
                                                        }
                                                    }
                                                @endphp
                                                <option value="{{ $Product->id }}" {{ $isSelected }}>{{ $Product->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
			        		<!-- Row //-->
			        		<div class="row">
		                        <div class="col-md-12 mb-4">
		                          	<label for="description" class="col-form-label" style="font-weight: bold;">Description:</label>
		                            <textarea name="description" id="description" rows="2" cols="2" class="form-control" placeholder="Write Description">{{$deliveryCoupon->description}}</textarea>
		                        </div>
		                        <!-- Description End -->
			        		</div>
			        		<div class="row">
                          		<div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input me-2 cursor" name="status" id="status" {{ $deliveryCoupon->status == 1 ? 'checked': '' }} value="1">
                                    <label class="form-check-label cursor" for="status">Status</label>
                                </div>
	                        </div>
	                        <!-- Row //-->
			        	</div>
			        	<!-- card body .// -->
				    </div>
				    <!-- card .// -->
				    <div class="row mb-4 justify-content-sm-end">
						<div class="col-lg-3 col-md-4 col-sm-5 col-6">
							<input type="submit" class="btn btn-primary" value="Update">
						</div>
					</div>
			    </form>
			</div>
        </div>
        <!-- .row // -->
    </div>
</section>
@push('footer-script')

<script>
    function toggleCustomerDropdown() {
        var customerDropdown = document.getElementById("customerDropdown");
        if (document.getElementById("type").value === "0") {
            customerDropdown.style.display = "block";
            document.getElementById("user_id").setAttribute("required", "required");
        } else {
            customerDropdown.style.display = "none";
            document.getElementById("user_id").removeAttribute("required");
        }
    }
    window.onload = function () {
        toggleCustomerDropdown();
    };
</script>
<script>
     window.onload = function () {
        toggleCustomerDropdown(); // Execute the function when the window loads
        toggleProductDropdown(); // Also execute the function for product dropdown
    };
    function toggleProductDropdown() {
        var productDropdown = document.getElementById("productDropdown");
        if (document.getElementById("producttype").value === "0") {
            productDropdown.style.display = "block";
            document.getElementById("product_id").setAttribute("required", "required");
        } else {
            productDropdown.style.display = "none";
            document.getElementById("product_id").removeAttribute("required");

        }
    }
</script>
@endpush
@endsection