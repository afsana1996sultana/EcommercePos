@extends('layouts.frontend')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <style>
        .preloader1 {
            background-color: #fff;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999999;
            -webkit-transition: .6s;
            transition: .6s;
            margin: 0 auto;
        }


        .preloader-active1 {
            position: absolute;
            top: 100px;
            width: 100%;
            height: 100%;
            z-index: 100;
        }

        .owl-carousel .owl-dots.disabled,
        .owl-carousel .owl-nav.disabled {
            display: block !important;
        }
    </style>
@endpush
@section('content-frontend')
    @php
      $maintenance = getMaintenance();
    @endphp
    @include('frontend.common.add_to_cart_modal')
    @include('frontend.common.maintenance')
    <section class="home-slider position-relative mb-30">
        <div class="container">
            {{-- slider start --}}
            <div class="slider__area">
                <div class="container m-0 p-0">
                    <div class="row align-items-stretch g-2">
                        <div class="col-xl-2 col-lg-2 d-lg-block d-none">
                            <div class="sidebar__menu__content">
                                    <ul class="sidebar__menu__system">
                                    @foreach (get_categories() as $category)
                                        @if ($category->has_sub_sub > 0)
                                            <li class="show__item__category">
                                                <a href="{{ route('product.category', $category->slug) }}">
                                                    @if (session()->get('language') == 'bangla')
                                                        {{ $category->name_bn }}
                                                    @else
                                                        {{ $category->name_en }}
                                                    @endif
                                                    @if ($category->sub_categories && count($category->sub_categories) > 0)
                                                        <i class="fi-rs-angle-right"></i>
                                                    @endif
                                                </a>
                                                @if ($category->sub_categories && count($category->sub_categories) > 0)
                                                    <ul class="single__submenu__item">
                                                        @foreach ($category->sub_categories as $sub_category)
                                                            <li class="child__category__menu__item">
                                                                <a
                                                                    href="{{ route('product.category', $sub_category->slug) }}">
                                                                    @if (session()->get('language') == 'bangla')
                                                                        {{ $sub_category->name_bn }}
                                                                    @else
                                                                        {{ $sub_category->name_en }}
                                                                    @endif
                                                                </a>
                                                                @if ($sub_category->sub_sub_categories && count($sub_category->sub_sub_categories) > 0)
                                                                    <ul class="chile__menu__system">
                                                                        @foreach ($sub_category->sub_sub_categories as $sub_sub_category)
                                                                            <li><a
                                                                                    href="{{ route('product.category', $sub_sub_category->slug) }}">
                                                                                    @if (session()->get('language') == 'bangla')
                                                                                        {{ $sub_sub_category->name_bn }}
                                                                                    @else
                                                                                        {{ $sub_sub_category->name_en }}
                                                                                    @endif
                                                                                </a>
                                                                            </li>
                                                                        @endforeach

                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @else
                                            <li class="show__item__category">
                                                <a href="{{ route('product.category', $category->slug) }}">
                                                    @if (session()->get('language') == 'bangla')
                                                        {{ $category->name_bn }}
                                                    @else
                                                        {{ $category->name_en }}
                                                    @endif
                                                    @if ($category->sub_categories && count($category->sub_categories) > 0)
                                                        <i class="fi-rs-angle-right"></i>
                                                    @endif
                                                </a>

                                                @if ($category->sub_categories && count($category->sub_categories) > 0)
                                                    <ul class="only__sub__category">
                                                        @foreach ($category->sub_categories as $sub_category)
                                                            <li><a
                                                                    href="{{ route('product.category', $sub_category->slug) }}">{{ $sub_category->name_en }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="mt-20 col-xl-8 col-lg-8 col-md-9 col-sm-12 col-12">
                            <div class="home__slider">
                                @foreach ($sliders as $slider)
                                    <div class="single__slider">
                                        <a class="h-100" href="{{ $slider->slider_url }}">
                                            <img class="h-100" src="{{ asset($slider->slider_img) }}" alt="">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-20 col-xl-2 col-lg-2 col-md-3 col-sm-12 col-12">
                            <div class="slider__right ">
                                @foreach ($home_banners as $banner)
                                    <div class="single__category border__radius">
                                        <a href="{{ $banner->banner_url }}"><img src="{{ asset($banner->banner_img) }}"
                                                width="100%" alt=""></a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- slider end --}}
        </div>
    </section>
    <!--End hero slider-->
    
    @php
            $couponCode = getCoupon();
        @endphp
        
       <div class="container">
           <div class="row">
                @if ($couponCode)
               <div class="col-12">
                    <div class="maintain-sms shoppers__coupon">
                        <h6 style="color:black">Coupon: {{ $couponCode }}</h6>
                    </div>
                </div>
                @endif
            </div>
        </div>

    {{-- benifit start --}}
    <div class="benifit-area section-padding">
        <div class="container">
            <div class="single__benefit benifit__active">
                <div class="benifi__item">
                    <div class="benefit__icon">
                        <img src="{{ asset('upload/benifit/exchange.png') }}" alt="">
                    </div>
                    <div class="benefit__info">
                        <div class="item-title">EXCHANGE POLICY
                        </div>
                        <div class="item-des">Fast & Hassle Free
                        </div>
                    </div>
                </div>
                <div class="benifi__item">
                    <div class="benefit__icon">
                        <img src="{{ asset('upload/benifit/support.png') }}" alt="">
                    </div>
                    <div class="benefit__info">
                        <div class="item-title">ONLINE SUPPORT
                        </div>
                        <div class="item-des">24/7 Everyday
                        </div>
                    </div>
                </div>
                <div class="benifi__item">
                    <div class="benefit__icon">
                        <img src="{{ asset('upload/benifit/payment.png') }}" alt="">
                    </div>
                    <div class="benefit__info">
                        <div class="item-title">PAYMENT METHOD
                        </div>
                        <div class="item-des">bKash, Credit Card
                        </div>
                    </div>
                </div>
                <div class="benifi__item">
                    <div class="benefit__icon">
                        <img src="{{ asset('upload/benifit/halal.png') }}" alt="">
                    </div>
                    <div class="benefit__info">
                        <div class="item-title">100% Original Products
                        </div>
                    </div>
                </div>
                <div class="benifi__item">
                    <div class="benefit__icon">
                        <img src="{{ asset('upload/benifit/fast.png') }}" alt="">
                    </div>
                    <div class="benefit__info">
                        <div class="item-title">Fast Delivery
                        </div>
                    </div>
                </div>
                <div class="benifi__item">
                    <div class="benefit__icon">
                        <img src="{{ asset('upload/benifit/track.png') }}" alt="">
                    </div>
                    <div class="benefit__info">
                        <div class="item-title">Track Parcel
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- benifit end --}}

    {{-- //banner start --}}
    <!--End category slider-->
    <section class="banners mb-25">
        <div class="container">
            <div class="row gy-3">
                @foreach ($home_banners_1->take(3) as $banner)
                    <div class="col-md-4 col-12">
                        <div class="banner-img wow animate__animated animate__fadeInUp w-100" data-wow-delay="0">
                            <a href="{{ $banner->banner_url }}">
                                <img src="{{ asset($banner->banner_img) }}" class="img-fluid w-100"
                                    alt="
                        @if (session()->get('language') == 'bangla') {{ $banner->title_bn }}
                        @else
                        {{ $banner->title_en }} @endif
                        ">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--End banners-->
    {{-- //banner end --}}

    {{-- popular category start --}}

    <div class="popular__category">
        <div class="container">
            <div class="border">
                <div class="row g-0 align-items-center p-1" style="background: #f9f9f9">
                    <div class="col-9">
                        <div class="section__heading">
                            <h6>
                                @if (session()->get('language') == 'bangla')
                                    জনপ্রিয় বিভাগ
                                @else
                                    POPULAR CATEGORIES
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="slideroffer_arrow text-end"></div>
                    </div>
                </div>
                <div class="position-relative">
                    <div class="popular__category__active">
                        @foreach ($categories as $category)
                            <a href="{{ route('product.category', $category->slug) }}" class="single__category__item">
                                @if ($category->image)
                                    <img src="{{ asset($category->image) }}" width="100%" alt="">
                                @else
                                    <img src="{{ asset('upload/product-default.jpg') }}" width="100%" alt="">
                                @endif
                                <span>
                                    @if (session()->get('language') == 'bangla')
                                        {{ Str::limit($category->name_bn, 20) }}
                                    @else
                                        {{ Str::limit($category->name_en, 20) }}
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- popular category end --}}

    {{-- banner start --}}
    <!--End category slider-->
    <section class="banners mb-25 mt-30">
        <div class="container">
            <div class="row gy-3">
                @foreach ($home_banners_2 as $banner)
                    <div class="col-md-4 col-12">
                        <div class="banner-img wow animate__animated animate__fadeInUp w-100" data-wow-delay="0">
                            <a href="{{ $banner->banner_url }}">
                                <img src="{{ asset($banner->banner_img) }}" class="img-fluid w-100" alt="">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--End banners-->
    {{-- banner end --}}

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
                <div class="container">
                    <div class="border">
                        <div class="row g-0 align-items-center p-1" style="background: #f9f9f9">
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

                        <div class="position-relative">
                            <div class="fatafaty__product__active p-3">
                                @foreach ($campaign->campaing_products as $campaing_product)
                                    @php
                                        $product = \App\Models\Product::find($campaing_product->product_id);
                                    @endphp
                                    @if ($product != null && $product->status != 0)
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
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    {{-- fatafati__combo product end --}}

    {{-- banner start --}}
    <!--End category slider-->
    <section class="banners mb-25 mt-30">
        <div class="container">
            <div class="row gy-3">
                @foreach ($home_banners_3 as $banner)
                    <div class="col-md-4 col-12">
                        <div class="banner-img wow animate__animated animate__fadeInUp" data-wow-delay="0">
                            <a href="{{ $banner->banner_url }}">
                                <img src="{{ asset($banner->banner_img) }}" class="img-fluid" alt="">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--End banners-->
    {{-- banner end --}}

    {{-- category product start --}}
    @foreach ($trending_cats as $trending_cat)
        <div class="category__product">
            <div class="container">
                <div class="row category__item__nav align-items-center">
                    <div class="col-12 col-lg-3">
                        <div class="section__heading section__heading__style">
                            <h6>
                                @if (session()->get('language') == 'bangla')
                                    {{ $trending_cat->name_bn }}
                                @else
                                    {{ $trending_cat->name_en }}
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="col-12 col-lg-9">
                        <ul class="nav nav-tabs product__category__title" id="myTab" role="tablist">
                            @foreach ($trending__subcategory as $key => $subcategory)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $key === 0 ? 'active' : '' }}"
                                        id="category{{ $subcategory->id }}" data-bs-toggle="tab"
                                        data-bs-target="#category{{ $subcategory->id }}-pane" type="button"
                                        role="tab" aria-controls="category{{ $subcategory->id }}-pane"
                                        aria-selected="true">{{ $subcategory->name_en }}</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row border m-0 p-0 product__category__border__top">
                    <div class="col-xl-3 col-lg-4 col-md-4 p-3 h-100 order-last order-md-first same__height ">
                        <div class="category__main__thumbnail same__align">
                            <a href="{{ route('product.category', $trending_cat->slug) }}"><img
                                    src="{{ asset($trending_cat->image) }}" width="100%" height="100%"
                                    alt=""></a>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8 col-md-8 order-first order-md-last same__height ">
                        <div class="tab-content same__align pt-3" id="myTabContent">
                            @foreach ($trending__subcategory as $key => $subcategory)
                                <div class="tab-pane fade  {{ $key === 0 ? 'show active' : '' }}"
                                    id="category{{ $subcategory->id }}-pane" role="tabpanel"
                                    aria-labelledby="category{{ $subcategory->id }}" tabindex="0">
                                    <div class="category__product__slider owl-carousel owl-theme">
                                        @foreach ($subcategory_trending_products->where('category_id', $subcategory->id)->chunk(2) as $product__data)
                                            <div>
                                                @foreach ($product__data as $product)
                                                    <div class="single__product__item border"
                                                        style="margin-bottom: 10px;">
                                                        <div class="product__image position-relative">
                                                            <a href="{{ route('product.details', $product->slug) }}"
                                                                class="product__item__photo">
                                                                <img src="{{ asset($product->product_thumbnail) }}"
                                                                    alt="">
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
                                                                                ৳{{ $product->discount_price }}
                                                                                off</div>
                                                                        @elseif($product->discount_type == 2)
                                                                            <div class="product__label sale__label">
                                                                                {{ $product->discount_price }}%
                                                                                off</div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="product__item__action">
                                                                <a href="#" id="{{ $product->id }}"
                                                                    onclick="productView(this.id)" data-bs-toggle="modal"
                                                                    data-bs-target="#quickViewModal"><i
                                                                        class="fa fa-eye"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="product__details">
                                                            <div class="product__details__top">
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
                                                                    <p>Coupon : {{ $couponCode }}</p>
                                                                @endif
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
                                                                        <div class="special__price">
                                                                            ৳{{ $price_after_discount }}</div>
                                                                        <div class="old__price">
                                                                            <del>৳{{ $product->regular_price }}</del>
                                                                        </div>
                                                                    @else
                                                                        <div class="special__price">
                                                                            ৳{{ $product->regular_price }}</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="product__view__add">
                                                                <a href="{{ route('product.details', $product->slug) }}">View
                                                                    Details</a>
                                                                @if ($product->is_varient == 1)
                                                                   @if($maintenance==1)
                                                                     <a class="add"data-bs-toggle="modal" data-bs-target="#maintenance"><i class="fi-rs-shopping-cart mr-5"></i>Add to cart</a>
                                                                    @else
                                                                      <a class="add" id="{{ $product->id }}"
                                                                        onclick="productView(this.id)" data-bs-toggle="modal"
                                                                        data-bs-target="#quickViewModal"><i class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a>
                                                                    @endif
                                                                @else
                                                                    <input type="hidden" id="pfrom" value="direct">
                                                                    <input type="hidden" id="product_product_id"
                                                                        value="{{ $product->id }}" min="1">
                                                                    <input type="hidden"
                                                                        id="{{ $product->id }}-product_pname"
                                                                        value="{{ $product->name_en }}">
                                                                        @if($maintenance==1)
                                                                        <a class="add"data-bs-toggle="modal" data-bs-target="#maintenance"><i class="fi-rs-shopping-cart mr-5"></i>Add to cart</a>
                                                                       @else
                                                                          <a class="add"
                                                                              onclick="addToCartDirect({{ $product->id }})"><i
                                                                                class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a>
                                                                        @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- category product end --}}


    {{-- feature start --}}
    <div class="feature__product section-padding">
        <div class="container">
            <div class="row g-0 align-items-center p-1">
                <div class="col-12">
                    <div class="row">
                        <div class="col-8 col-sm-8 col-md-6 col-lg-3">
                            <div class="section__heading section__heading__style">
                                <h6>
                                    @if (session()->get('language') == 'bangla')
                                        বৈশিষ্ট্যযুক্ত পণ্য
                                    @else
                                        featured productS
                                    @endif
                                </h6>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4 col-md-6 col-lg-9">
                            <div class="featured__product__arrow text-end"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="featured__product border p-3">
                @foreach ($products as $product)
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
                                    <span class="coupon_code">Coupon: {{ $couponCode }}</span>
                                @endif
                        <div class="product__image position-relative">
                            <a href="{{ route('product.details', $product->slug) }}" class="product__item__photo">
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
                                            <div class="product__label sale__label">৳{{ $product->discount_price }} off
                                            </div>
                                        @elseif($product->discount_type == 2)
                                            <div class="product__label sale__label">{{ $product->discount_price }}% off
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="product__item__action">
                                <a href="#" id="{{ $product->id }}" onclick="productView(this.id)"
                                    data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fa fa-eye"></i></a>
                            </div>
                        </div>
                        <div class="product__details">
                            <div class="product__details__top">
                                <strong class="product__name">
                                    <a href="{{ route('product.details', $product->slug) }}" class="product__link">
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

                            </div>
                            <div class="product__view__add">
                                <a href="{{ route('product.details', $product->slug) }}">View Details</a>
                                @if ($product->is_varient == 1)
                                    @if($maintenance==1)
                                      <a class="add" data-bs-toggle="modal" data-bs-target="#maintenance"><i
                                            class="fi-rs-shopping-cart mr-5"></i>Add to Cart</a>
                                      @else
                                      <a class="add" id="{{ $product->id }}" onclick="productView(this.id)" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i
                                             class="fi-rs-shopping-cart mr-5"></i>Add to Cart</a>
                                    @endif
                                @else
                                    <input type="hidden" id="pfrom" value="direct">
                                    <input type="hidden" id="product_product_id" value="{{ $product->id }}"
                                        min="1">
                                    <input type="hidden" id="{{ $product->id }}-product_pname"
                                        value="{{ $product->name_en }}">
                                        @if($maintenance==1)
                                         <a class="add" data-bs-toggle="modal" data-bs-target="#maintenance"><i
                                        class="fi-rs-shopping-cart mr-5"></i>Add to Cart</a>
                                        @else
                                        <a class="add" onclick="addToCartDirect({{ $product->id }})"><i class="fi-rs-shopping-cart mr-5"></i>Add to Cart</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- feature end --}}

    {{-- bottom product category start --}}
    <div class="bottom__category__product__area section-padding">
        <div class="container">
            <div class="row gy-4">
                @foreach ($special_cats as $category)
                    <div class="col-xl-6 col-lg-6 mobile__space">
                        <div class="row g-0 align-items-center p-1">
                            <div class="col-6">
                                <div class="section__heading section__heading__style">
                                    <h6>
                                        @if (session()->get('language') == 'bangla')
                                            {{ $category->name_bn }}
                                        @else
                                            {{ $category->name_en }}
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <img class="bottom__category__thumbnail w-100" src="{{ asset($category->image) }}"
                            alt="">
                        <div class="bottom__product">
                            <div class="bottom__category__product owl-carousel">
                                @foreach ($category->products as $product)
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
                                                                ৳{{ $product->discount_price }} off
                                                            </div>
                                                        @elseif($product->discount_type == 2)
                                                            <div class="product__label sale__label">
                                                                {{ $product->discount_price }}% off
                                                            </div>
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
                                            <div class="product__details__top">
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
                                                    <p>Coupon : {{ $couponCode }}</p>
                                                @endif
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
                                            </div>
                                            <div class="product__view__add">
                                                <a href="{{ route('product.details', $product->slug) }}">View Details</a>
                                                @if ($product->is_varient == 1)
                                                    @if($maintenance==1)
                                                        <a class="add"data-bs-toggle="modal" data-bs-target="#maintenance"><i class="fi-rs-shopping-cart mr-5"></i>Add to cart</button>
                                                    @else
                                                     <a class="add" id="{{ $product->id }}"
                                                        onclick="productView(this.id)" data-bs-toggle="modal"
                                                        data-bs-target="#quickViewModal"><i class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a>
                                                    @endif
                                                @else
                                                    <input type="hidden" id="pfrom" value="direct">
                                                    <input type="hidden" id="product_product_id"
                                                        value="{{ $product->id }}" min="1">
                                                    <input type="hidden" id="{{ $product->id }}-product_pname"
                                                        value="{{ $product->name_en }}">
                                                        @if($maintenance==1)
                                                        <a class="add"data-bs-toggle="modal" data-bs-target="#maintenance"><i class="fi-rs-shopping-cart mr-5"></i>Add to cart</button>
                                                        @else
                                                        <a class="add" onclick="addToCartDirect({{ $product->id }})"><i
                                                            class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a>
                                                        @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- bottom product category end --}}


    {{-- blog start --}}
    <div class="blog-area section-padding">
        <div class="container">
            <div class="row mb-10">
                <div class="col-12">
                    <div class="row">
                        <div class="col-8 col-sm-3">
                            <div class="section__heading section__heading__style">
                                <h6>
                                    @if (session()->get('language') == 'bangla')
                                        ব্লগ
                                    @else
                                        BLOG
                                    @endif
                                </h6>
                            </div>
                        </div>
                        <div class="col-4 col-sm-9">
                            <div class="blog_arrow text-end"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="blog__active">
                @foreach ($blogs as $blog)
                    <div class="single__blog">
                        <a href="{{ route('blog.details', $blog->slug) }}"><img src="{{ asset($blog->blog_img) }}" width="100%" alt=""></a>
                        <div class="blog__content">
                            <a href="{{ route('blog.details', $blog->slug) }}" class="blog__title">
                                @if (session()->get('language') == 'bangla')
                                    {{ Str::limit($blog->title_bn, 30) }}
                                @else
                                    {{ Str::limit($blog->title_en, 30) }}
                                @endif
                            </a>
                            <a class="blog__btn" href="{{ route('blog.details', $blog->slug) }}">read more</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- blog end --}}

    {{-- <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script> --}}
    <script src="{{ asset('frontend/assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $('.category__product__slider').owlCarousel({
            loop: false,
            margin: 10,
            items: 5,
            nav: true,
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 2
                },
                1200: {
                    items: 4
                },
                1400: {
                    items: 5
                }
            }
        });

        // bottom category product active
        $('.bottom__category__product').owlCarousel({
            loop: true,
            margin: 5,
            items: 3,
            autoplay: false,
            dots: false,
            nav: true,
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: false
                },
                990: {
                    items: 2,
                    nav: false
                },
                1200: {
                    items: 2,
                    nav: true,
                    loop: false
                },
                1300: {
                    items: 3,
                    nav: true,
                    loop: false
                }
            }
        })
    </script>

    <?php
        $maintenance = getMaintenance(); // Replace this with your actual variable value
        if ($maintenance == 1) {
            echo '<script type="text/javascript">
                $(window).on("load", function() {
                    if ($(window).width() <= 991) {
                        $("#myModal").modal("show");
                    }
                });
            </script>';
        }
    ?>
@endsection