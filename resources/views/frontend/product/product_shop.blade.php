 @extends('layouts.frontend1')
 @section('content-frontend')
     @include('frontend.common.add_to_cart_modal')
     @include('frontend.common.maintenance')
     @php
         $maintenance = getMaintenance();
     @endphp
     <main class="main product-shop">
         <div class="container mb-30 mt-60">
             <div class="row">
                 <!-- Side Filter Start -->
                 <div class="col-xl-3 col-md-4 primary-sidebar sticky-sidebar">
                     @include('frontend.common.sidebar__filter__category')
                 </div>
                 <!-- Side Filter End -->
                 <div class="col-xl-9 col-md-8">
                     <form class="" id="search-form" action="" method="GET">
                         <div class="shop-product-fillter">
                             <div class="totall-product">
                                 <p>We found <strong class="text-brand">{{ count($products) }}</strong> items for you!</p>
                             </div>
                             <div class="sort-by-product-area">
                                 <div class="sort-by-cover d-flex gap-2 mb-2">
                                     <div class="custom_select">
                                         <select class="form-control select-active" onchange="filter()" name="brand"
                                             id="brand_id">
                                             <option value="">All Brands</option>
                                             @foreach (\App\Models\Brand::all() as $brand)
                                                 <option value="{{ $brand->id }}"
                                                     @if ($brand_id == $brand->id) selected @endif>
                                                     {{ $brand->name_en ?? 'Null' }}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <div class="custom_select">
                                         <select class="form-control select-active" name="sort_by" onchange="filter()">
                                             <option value="newest" @if ($sort_by == 'newest') selected @endif>Newest
                                             </option>
                                             <option value="oldest" @if ($sort_by == 'oldest') selected @endif>Oldest
                                             </option>
                                             <option value="price-asc" @if ($sort_by == 'price-asc') selected @endif>
                                                 Price Low to High</option>
                                             <option value="price-desc" @if ($sort_by == 'price-desc') selected @endif>
                                                 Price High to Low</option>
                                         </select>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="row product-grid g-2">
                             @forelse($products as $product)
                                 <div class="col-xl-3 col-md-6 col-sm-6 col-12">
                                     <div class="single__product__item border">
                                         <div class="product__image position-relative">
                                             <a href="{{ route('product.details', $product->slug) }}"
                                                 class="product__item__photo">
                                                 <img src="{{ asset($product->product_thumbnail) }}" alt="">
                                             </a>

                                             <div class="product__discount__price d-flex">
                                                 @if ($product->created_at >= Carbon\Carbon::now()->subWeek())
                                                     <div class="product__labels">
                                                         <div class="product__label new__label">New</div>
                                                     </div>
                                                 @endif

                                                 @if ($product->discount_price > 0)
                                                     <div class="product__labels d-flex">
                                                         @if ($product->discount_type == 1)
                                                             <div class="product__label sale__label">
                                                                 ৳{{ $product->discount_price }} off</div>
                                                         @elseif($product->discount_type == 2)
                                                             <div class="product__label sale__label">
                                                                 {{ $product->discount_price }}% off</div>
                                                         @endif
                                                     </div>
                                                 @endif

                                                 @php
                                                     $couponCode = getCoupon();
                                                     $coupon = \App\Models\Coupon::where('coupon_code', $couponCode)->first();
                                                     $showCoupon = false;
                                                     if ($coupon && $coupon->product_id != null) {
                                                         $couponProductIds = explode(',', $coupon->product_id);
                                                         if (in_array($product->id, $couponProductIds)) {
                                                             $showCoupon = true;
                                                         }
                                                     }
                                                 @endphp

                                                 @if ($showCoupon)
                                                     <span class="coupon_code">Coupon : {{ $couponCode }}</span>
                                                 @endif
                                             </div>

                                             <div class="product__item__action">
                                                 <a href="#" id="{{ $product->id }}" onclick="productView(this.id)"
                                                     data-bs-toggle="modal" data-bs-target="#quickViewModal"><i
                                                         class="fa fa-eye"></i></a>
                                             </div>
                                         </div>
                                         <div class="product__details">
                                             <div class="product__details__top">
                                                 <strong class="product__name">
                                                     <a href="{{ route('product.details', $product->slug) }}"
                                                         class="product__link">
                                                         @if (session()->get('language') == 'bangla')
                                                             {{ Str::limit($product->name_bn, 70) }}
                                                         @else
                                                             {{ Str::limit($product->name_en, 80) }}
                                                         @endif
                                                     </a>
                                                 </strong>
                                                 <div class="product-category">
                                                     <span rel="tag">
                                                         {{ $product->brand->name_en ?? 'No Brand' }}
                                                     </span>
                                                 </div>
                                                 @php
                                                     $reviews = \App\Models\Review::where('product_id', $product->id)
                                                         ->where('status', 1)
                                                         ->get();
                                                     $averageRating = $reviews->avg('rating');
                                                     $ratingCount = $reviews->count(); // Add this line to get the rating count
                                                 @endphp

                                                 <div class="product__rating">
                                                     @if ($reviews->isNotEmpty())
                                                         @for ($i = 1; $i <= 5; $i++)
                                                             @if ($i <= floor($averageRating))
                                                                 <i class="fa fa-star" style="color: #FFB811;"></i>
                                                             @elseif ($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5)
                                                                 {{-- Display a half-star with gradient --}}
                                                                 <i class="fa fa-star"
                                                                     style="background: linear-gradient(to right, #FFB811 50%, gray 50%); -webkit-background-clip: text; color: transparent;"></i>
                                                             @else
                                                                 <i class="fa fa-star" style="color: gray;"></i>
                                                             @endif
                                                         @endfor
                                                     @else
                                                         @for ($i = 1; $i <= 5; $i++)
                                                             <i class="fa fa-star" style="color: gray;"></i>
                                                         @endfor
                                                     @endif
                                                     <span
                                                         class="rating-count">({{ number_format($averageRating, 1) }})</span>
                                                 </div>

                                                 @php
                                                     if ($product->discount_type == 1) {
                                                         $price_after_discount = $product->regular_price - $product->discount_price;
                                                     } elseif ($product->discount_type == 2) {
                                                         $price_after_discount = $product->regular_price - ($product->regular_price * $product->discount_price) / 100;
                                                     }
                                                 @endphp

                                                 <div class="product__price d-flex justify-space-between">
                                                     @if ($product->discount_price > 0)
                                                         <div class="special__price">৳{{ $price_after_discount }}</div>
                                                         <div class="old__price">
                                                             <del>৳{{ $product->regular_price }}</del>
                                                         </div>
                                                     @else
                                                         <div class="special__price">৳{{ $product->regular_price }}</div>
                                                     @endif
                                                 </div>
                                             </div>
                                             <div class="product__view__add">
                                                 <a href="{{ route('product.details', $product->slug) }}">View Details</a>
                                                 @if ($product->is_varient == 1)
                                                     @if ($maintenance == 1)
                                                         <a class="add" data-bs-toggle="modal"
                                                             data-bs-target="#maintenance"><i
                                                                 class="fi-rs-shopping-cart mr-5"></i>Add to cart</a>
                                                     @else
                                                         <a class="add" id="{{ $product->id }}"
                                                             onclick="productView(this.id)" data-bs-toggle="modal"
                                                             data-bs-target="#quickViewModal"><i
                                                                 class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a>
                                                     @endif
                                                 @else
                                                     <input type="hidden" id="pfrom" value="direct">
                                                     <input type="hidden" id="product_product_id"
                                                         value="{{ $product->id }}" min="1">
                                                     <input type="hidden" id="{{ $product->id }}-product_pname"
                                                         value="{{ $product->name_en }}">
                                                     @if ($maintenance == 1)
                                                         <a class="add" data-bs-toggle="modal"
                                                             data-bs-target="#maintenance"><i
                                                                 class="fi-rs-shopping-cart mr-5"></i>Add to cart</a>
                                                     @else
                                                         <a class="add"
                                                             onclick="addToCartDirect({{ $product->id }})"><i
                                                                 class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a>
                                                     @endif
                                                 @endif
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             @empty
                                 @if (session()->get('language') == 'bangla')
                                     <h5 class="text-danger">এখানে কোন পণ্য খুঁজে পাওয়া যায়নি!</h5>
                                 @else
                                     <h5 class="text-danger">No products were found here!</h5>
                                 @endif
                             @endforelse
                             <!--end product card-->
                         </div>
                         <!--product grid-->
                         <div class="pagination-area mt-20 mb-20">
                             <nav aria-label="Page navigation example">
                                 <ul class="pagination justify-content-end">
                                     {{ $products->links() }}
                                 </ul>
                             </nav>
                         </div>
                     </form>
                     <!--End Deals-->
                 </div>
             </div>
         </div>
     </main>
 @endsection
 @push('footer-script')
     <script type="text/javascript">
         function filter() {
             $('#search-form').submit();
         }
     </script>

     <script type="text/javascript">
         function sort_price_filter() {
             var start = $('#slider-range-value1').html();
             var end = $('#slider-range-value2').html();
             $('#filter_price_start').val(start);
             $('#filter_price_end').val(end);
             $('#search-form').submit();
         }
     </script>

     <script type="text/javascript">
         (function($) {
             ("use strict");
             // Slider Range JS
             if ($("#slider-range").length) {
                 $(".noUi-handle").on("click", function() {
                     $(this).width(50);
                 });
                 var rangeSlider = document.getElementById("slider-range");
                 var moneyFormat = wNumb({
                     decimals: 0,
                     // thousand: ",",
                     // prefix: "$"
                 });
                 var start_price = document.getElementById("filter_price_start").value;
                 var end_price = document.getElementById("filter_price_end").value;
                 noUiSlider.create(rangeSlider, {
                     start: [start_price, end_price],
                     step: 1,
                     range: {
                         min: [1],
                         max: [10000]
                     },
                     format: moneyFormat,
                     connect: true
                 });

                 // Set visual min and max values and also update value hidden form inputs
                 rangeSlider.noUiSlider.on("update", function(values, handle) {
                     document.getElementById("slider-range-value1").innerHTML = values[0];
                     document.getElementById("slider-range-value2").innerHTML = values[1];
                     document.getElementsByName("min-value").value = moneyFormat.from(values[0]);
                     document.getElementsByName("max-value").value = moneyFormat.from(values[1]);
                 });
             }
         })(jQuery);
     </script>
 @endpush
