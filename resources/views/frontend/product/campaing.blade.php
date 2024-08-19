@extends('layouts.frontend')
@section('content-frontend')
@include('frontend.common.add_to_cart_modal')
@php
    $campaign = \App\Models\Campaing::where('status', 1)->where('is_featured', 1)->first();
    $maintenance = getMaintenance();
@endphp
<main class="main">
    <div class="container mb-30 mt-20">
        <div class="row"></div>
        <div class="row">
            <div class="col-lg-4-5">
                <div class="hot_deals_image mb-4">
                    <img src="{{ asset($campaign->campaing_image ?? '') }}" width="100%" alt=""></div>
                <div class="shop-product-fillter">
                    <div class="sort-by-product-area">
                        <!--<div class="sort-by-cover d-flex gap-2 mb-2">-->
                        <!--    <div class="custom_select">-->
                        <!--        <select class="form-control select-active" onchange="filter()" name="brand">-->
                        <!--            <option value="">All Brands</option>-->
                        <!--            @foreach (\App\Models\Brand::all() as $brand)-->
                        <!--            <option value="{{ $brand->slug }}" @if ($brand_id==$brand->id) selected @endif >{{ $brand->name_en ?? 'Null' }}</option>-->
                        <!--            @endforeach-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--    <div class="custom_select">-->
                        <!--        <select class="form-control select-active" name="sort_by" onchange="filter()">-->
                        <!--            <option value="newest" @if ($sort_by=='newest' ) selected @endif>Newest</option>-->
                        <!--            <option value="oldest" @if ($sort_by=='oldest' ) selected @endif>Oldest</option>-->
                        <!--            <option value="price-asc" @if ($sort_by=='price-asc' ) selected @endif>Price Low to High</option>-->
                        <!--            <option value="price-desc" @if ($sort_by=='price-desc' ) selected @endif>Price High to Low</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </div>
                <div class="row product-grid gutters-5">
                    {{-- fatafati__combo product start --}}
                    @php
                        $campaign = \App\Models\Campaing::where('status', 1)
                            ->where('is_featured', 1)
                            ->first();
                    @endphp
                    @if ($campaign)
                        @php
                            $start_diff = date_diff(date_create($campaign->flash_start), date_create(date('d-m-Y H:i:s')));
                            $end_diff = date_diff(date_create(date('d-m-Y H:i:s')), date_create($campaign->flash_end));
                        @endphp
                        @if ($start_diff->invert == 0 && $end_diff->invert == 0)
                            <div class="fatafati__combo__product">
                                        <div class="row g-0 align-items-center" style="background: #f9f9f9">
                                            <div class="col-9">
                                                <div class="section__heading">
                                                    <h6>
                                                        @if (session()->get('language') == 'bangla')
                                                            {{ $campaign->name_bn }}
                                                        @else
                                                            {{ strtoupper($campaign->name_en) }}
                                                        @endif
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="fatafaty__product__arrow text-end"></div>
                                            </div>
                                        </div>
                
                                        <!--<div class="position-relative">-->
                                        <div class="row">
                                            @foreach ($campaign->campaing_products as $campaing_product)
                                                @php
                                                    $product = \App\Models\Product::find($campaing_product->product_id);
                                                @endphp
                                                @if ($product != null && $product->status != 0)
                                                    <div class="col-lg-3 col-12 col-sm-6">
                                                    <div class="single__product__item border">
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
                                                        @if($showCoupon)
                                                            <span class="coupon_code">Coupon : {{ $couponCode }}</span>
                                                        @endif
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
                                                            </div>
                                                            <div class="product__item__action">
                                                                <a href="#" id="{{ $product->id }}"
                                                                    onclick="productView(this.id)" data-bs-toggle="modal"
                                                                    data-bs-target="#quickViewModal"><i class="fa fa-eye"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="product__details">
                                                            <strong class="product__name">
                                                                <a href="{{ route('product.details', $product->slug) }}"
                                                                    class="product__link">
                                                                    @if (session()->get('language') == 'bangla')
                                                                        {{ Str::limit($product->name_bn, 50) }}
                                                                    @else
                                                                        {{ Str::limit($product->name_en, 50) }}
                                                                    @endif
                                                                </a>
                                                            </strong>
                                                            <div class="product-category">
                                                                <span rel="tag">
                                                                    {{ $product->brand->name_en ?? 'No Brand'}}
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
                                                                            <i class="fa fa-star" style="background: linear-gradient(to right, #FFB811 50%, gray 50%); -webkit-background-clip: text; color: transparent;"></i>
                                                                        @else
                                                                            <i class="fa fa-star" style="color: gray;"></i>
                                                                        @endif
                                                                    @endfor
                                                                @else
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i class="fa fa-star" style="color: gray;"></i>
                                                                    @endfor
                                                                @endif
                                                                <span class="rating-count">({{ number_format($averageRating, 1) }})</span>
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
                                                            <div class="discount__time">
                                                                <div class="deals-countdown-wrap">
                                                                    <div class="deals-countdown"
                                                                        data-countdown="{{ date('Y-m-d H:i:s', strtotime($campaign->flash_end)) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="product__view__add">
                                                                <a href="{{ route('product.details', $product->slug) }}">View
                                                                    Details</a>
                                                                @if ($product->is_varient == 1)
                                                                @if($maintenance==1)
                                                                <a class="add" data-bs-toggle="modal" data-bs-target="#maintenance"><i class="fi-rs-shopping-cart mr-5"></i>Add to cart</a>
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
                                                                    @if($maintenance==1)
                                                                    <a class="add" data-bs-toggle="modal" data-bs-target="#maintenance"><i class="fi-rs-shopping-cart mr-5"></i>Add to cart</a>
                                                                    @else
                                                                    <a class="add"
                                                                        onclick="addToCartDirect({{ $product->id }})"><i
                                                                            class="fi-rs-shopping-cart mr-5"></i>Add to Cart</a>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <!--</div>-->
                                    
                                </div>
                        @endif
                    @endif
                    {{-- fatafati__combo product end --}}
                    <!--end product card-->
                </div>
                <!--product grid-->
            </div>
            <!-- Side Filter Start -->
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
                <form action="{{ URL::current() }}" method="GET">
                    <div class="card">
                        <div class="sidebar-widget price_range range border-0">
                            <h5 class="mb-20">By Price</h5>
                            <div class="price-filter mb-20">
                                <div class="price-filter-inner">
                                    <div id="slider-range" class="mb-20"></div>
                                    <div class="d-flex justify-content-between">
                                        <div class="caption">From: <strong id="slider-range-value1" class="text-brand"></strong></div>
                                        <div class="caption">To: <strong id="slider-range-value2" class="text-brand"></strong></div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mb-20">Category</h5>
                            <div class="custome-checkbox">
                                @foreach(get_categories() as $category)
                                <div class="mb-2">
                                    @php
                                    $checked = [];
                                    if(isset($_GET['filtercategory'])){
                                    $checked = $_GET['filtercategory'];
                                    }
                                    @endphp
                                    <input class="form-check-input" type="checkbox" name="filtercategory[]" id="category_{{$category->id}}" value="{{$category->name_en}}" @if(in_array($category->name_en, $checked)) checked @endif
                                    />
                                    <label class="form-check-label" for="category_{{$category->id}}">
                                        <span>
                                            @if(session()->get('language') == 'bangla')
                                            {{ $category->name_bn }}
                                            @else
                                            {{ $category->name_en }}
                                            @endif
                                        </span>
                                    </label>
                                    <span class="float-end">{{ count($category->products) }}</span>
                                    <br>
                                </div>
                                @endforeach
                            </div>
                            <button type="submin" class="btn btn-sm btn-default mt-20 mb-10"><i class="fi-rs-filter mr-5"></i> Fillter</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Side Filter End -->
        </div>
    </div>
</main>
@endsection