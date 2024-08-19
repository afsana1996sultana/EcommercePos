@extends('layouts.frontend1')
@section('content-frontend')
@include('frontend.common.add_to_cart_modal')

<main class="main">
	<div class="container mb-30 mt-60">
	    <div class="row">
	            <div class="row product-grid">
	            	@forelse($products as $product)
	                @include('frontend.common.product_grid_view')
	                @empty
                        @if(session()->get('language') == 'bangla') 
	                        <h5 class="text-danger">এখানে কোন পণ্য খুঁজে পাওয়া যায়নি!</h5> 
	                    @else 
	                       	<h5 class="text-danger">No products were found here!</h5> 
	                    @endif
	                @endforelse
	                <!--end product card-->
	            </div>
	            <!--product grid-->
                <div class="justify-content-center">
                    
                </div>
	        </div>
            <!-- Side Filter Start -->
	        <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
	        </div>
            <!-- Side Filter End -->
	    </div>
	</div>
</main>
@endsection