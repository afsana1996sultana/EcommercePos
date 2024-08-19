@extends('layouts.frontend1')
@push('css')
    <style>
        .app-figure {
            width: 100% !important;
            margin: 0px auto;
            border: 0px solid red;
            padding: 20px;
            position: relative;
            text-align: center;
        }

        .MagicZoom {
            display: none;
        }

        .MagicZoom.Active {
            display: block;
            overflow: hidden;
        }

        .selectors {
            margin-top: 10px;
        }

        .selectors .mz-thumb img {
            max-width: 56px;
        }

        @media screen and (max-width: 1023px) {
            .app-figure {
                width: 99% !important;
                margin: 20px auto;
                padding: 0;
            }
        }

        .selectors {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .rating i {
            color: #ffb301;
        }

        .single-review-item {
            border-top: 1px solid #ffb301;
        }

        .single-review-item {
            padding: 10px 0;
        }

        .review_list {
            margin-top: 20px;
        }

        a[data-zoom-id],
        .mz-thumb,
        .mz-thumb:focus {
            margin-top: 0 !important;
        }
    </style>
    <!-- Image zoom -->
    <link rel="stylesheet" href="{{ asset('frontend/magiczoomplus/magiczoomplus.css') }}" />
@endpush
@section('meta')
    @parent <!-- This retains the parent content before adding additional content -->
    <meta property="og:title" content="{{ $product->name_en }}">
    <meta property="og:image" content="{{ asset($product->product_thumbnail) }}">
@endsection
@section('content-frontend')
    @include('frontend.common.maintenance')
    <main class="main">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="{{ route('product.category', $product->category->slug) }}" rel="nofollow"><i
                            class="fi-rs-home mr-5"></i>
                        @if (session()->get('language') == 'bangla')
                            {{ $product->category->name_bn ?? 'No Category' }}
                        @else
                            {{ $product->category->name_en ?? 'No Category' }}
                        @endif
                    </a>
                </div>
            </div>
        </div>
        <div class="container mb-30">
            <div class="row">
                <div class="col-xl-11 col-lg-12 m-auto">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="product-detail accordion-detail">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="row mb-50 mt-30">
                                            <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                                                <!-- Product Zoom Image -->
                                                <div class="app-figure" id="zoom-fig">
                                                    <a rel="selectors-effect-speed: 600; disable-zoom: true;" id="Zoom-1"
                                                        class="MagicZoom Active"
                                                        data-options="zoomWidth: 500; zoomHeight: 500; expandZoomMode: magnifier; expandZoomOn: always; variableZoom: true; rightClick: true;"
                                                        title="Show your product in stunning detail with {{ config('app.name') }} Zoom."
                                                        style="max-width: 438px; max-height: 438px;"
                                                        href="{{ asset($product->product_thumbnail) }}?h=1400"
                                                        data-zoom-image-2x="{{ asset($product->product_thumbnail) }}"
                                                        data-image-2x="{{ asset($product->product_thumbnail) }}">
                                                        <img id="product_zoom_img"
                                                            style="max-width: 438px; max-height: 438px;"
                                                            src="{{ asset($product->product_thumbnail) }}"
                                                            srcset="{{ asset($product->product_thumbnail) }}"
                                                            alt="">
                                                    </a>
                                                    <div class="selectors">
                                                        @foreach ($product->multi_imgs as $img)
                                                            <a rel="selectors-effect-speed: 600; disable-zoom: true;"
                                                                data-zoom-id="Zoom-1" href="{{ asset($img->photo_name) }}"
                                                                data-image="{{ asset($img->photo_name) }}"
                                                                data-zoom-image-2x="{{ asset($img->photo_name) }}"
                                                                data-image-2x="{{ asset($img->photo_name) }}">
                                                                <img srcset="{{ asset($img->photo_name) }}">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <!-- Product Zoom Image End -->
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="detail-info pr-30 pl-30">
                                                    @php
                                                        $discount = 0;
                                                        $amount = $product->regular_price;
                                                        if ($product->discount_price > 0) {
                                                            if ($product->discount_type == 1) {
                                                                $discount = $product->discount_price;
                                                                $amount = $product->regular_price - $discount;
                                                            } elseif ($product->discount_type == 2) {
                                                                $discount = ($product->regular_price * $product->discount_price) / 100;
                                                                $amount = $product->regular_price - $discount;
                                                            } else {
                                                                $amount = $product->regular_price;
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($product->discount_price > 0)
                                                        @if ($product->discount_type == 1)
                                                            <span class="stock-status out-stock"> ৳{{ $discount }} Off
                                                            </span>
                                                        @elseif ($product->discount_type == 2)
                                                            <span class="stock-status out-stock">
                                                                {{ $product->discount_price }}% Off </span>
                                                        @endif
                                                    @endif

                                                    <input type="hidden" id="discount_amount" value="{{ $discount }}">

                                                    <h2 class="title-detail">
                                                        @if (session()->get('language') == 'bangla')
                                                            {{ $product->name_bn }}
                                                        @else
                                                            {{ $product->name_en }}
                                                        @endif
                                                    </h2>
                                                    <div class="clearfix product-price-cover">
                                                        <div class="product-price primary-color float-left">
                                                            @if ($product->discount_price <= 0)
                                                                <span
                                                                    class="current-price">৳{{ $product->regular_price }}</span>
                                                                <input type="hidden" id="hidden-price"
                                                                    value="{{ $product->regular_price }}">
                                                            @else
                                                                <span class="current-price">৳{{ $amount }}</span>
                                                                <input type="hidden" id="hidden-price"
                                                                    value="{{ $amount }}">
                                                                @if ($product->discount_type == 1)
                                                                    <span class="save-price font-md color3 ml-15">
                                                                        ৳{{ $discount }} Off </span>
                                                                @elseif ($product->discount_type == 2)
                                                                    <span
                                                                        class="save-price font-md color3 ml-15">{{ $product->discount_price }}%
                                                                        Off</span>
                                                                @endif
                                                                <span
                                                                    class="old-price font-md ml-15">৳{{ $product->regular_price }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <form id="choice_form">
                                                        <div class="row mt-10" id="choice_attributes">
                                                            @if ($product->is_varient)
                                                                @php $i=0; @endphp
                                                                @foreach (json_decode($product->attribute_values) as $attribute)
                                                                    @php
                                                                        $attr = get_attribute_by_id($attribute->attribute_id);
                                                                        $i++;
                                                                    @endphp
                                                                    <div class="attr-detail attr-size mb-10">
                                                                        <strong class="mr-10">{{ $attr->name }}:
                                                                        </strong>
                                                                        <input type="hidden" name="attribute_ids[]"
                                                                            id="attribute_id_{{ $i }}"
                                                                            value="{{ $attribute->attribute_id }}">
                                                                        <input type="hidden" name="attribute_names[]"
                                                                            id="attribute_name_{{ $i }}"
                                                                            value="{{ $attr->name }}">
                                                                        <input type="hidden"
                                                                            id="attribute_check_{{ $i }}"
                                                                            value="0">
                                                                        <input type="hidden"
                                                                            id="attribute_check_attr_{{ $i }}"
                                                                            value="0">
                                                                        <ul class="list-filter size-filter font-small">
                                                                            @foreach ($attribute->values as $value)
                                                                                <li>
                                                                                    <a href="#"
                                                                                        onclick="selectAttribute('{{ $attribute->attribute_id }}{{ $attr->name ?? '' }}', '{{ $value }}', '{{ $product->id }}', '{{ $i }}')"
                                                                                        style="border: 1px solid #7E7E7E;">{{ $value }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                            <input type="hidden" name="attribute_options[]"
                                                                                id="{{ $attribute->attribute_id }}{{ $attr->name ?? '' }}"
                                                                                class="attr_value_{{ $i }}">
                                                                        </ul>
                                                                    </div>
                                                                @endforeach
                                                                <input type="hidden" id="total_attributes"
                                                                    value="{{ count(json_decode($product->attribute_values)) }}">
                                                            @endif
                                                        </div>
                                                    </form>

                                                    <div class="row" id="attribute_alert">

                                                    </div>

                                                    <div class="detail-extralink align-items-baseline d-flex border-0">
                                                        <div class="mr-10">
                                                            <span class="">Quantity:</span>
                                                        </div>
                                                        <div class="detail-qty border radius">
                                                            <a href="#" class="qty-down"><i
                                                                    class="fi-rs-angle-small-down"></i></a>
                                                            <input type="text" name="quantity" class="qty-val"
                                                                value="1" min="1" id="qty" readonly>
                                                            <a href="#" class="qty-up"><i
                                                                    class="fi-rs-angle-small-up"></i></a>
                                                        </div>

                                                        <div class="row" id="qty_alert">

                                                        </div>
                                                    </div>
                                                    <div class="detail-extralink border-0">
                                                        <div class="product-extra-link2">

                                                            <input type="hidden" id="product_id"
                                                                value="{{ $product->id }}">

                                                            <input type="hidden" id="pname"
                                                                value="{{ $product->name_en }}">

                                                            <input type="hidden" id="product_price"
                                                                value="{{ $amount }}">

                                                            <input type="hidden" id="minimum_buy_qty"
                                                                value="{{ $product->minimum_buy_qty }}">
                                                            <input type="hidden" id="stock_qty"
                                                                value="{{ $product->stock_qty }}">

                                                            <input type="hidden" id="pvarient" value="">

                                                            <input type="hidden" id="buyNowCheck" value="0">
                                                            @php
                                                                $maintenance = getMaintenance();
                                                            @endphp
                                                            @if ($maintenance == 1)
                                                                <button type="button"
                                                                    class="button button-add-to-cart text-white"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#maintenance"><i
                                                                        class="fi-rs-shoppi ng-cart"></i>Add to
                                                                    cart</button>
                                                            @else
                                                                <button type="submit"
                                                                    class="button button-add-to-cart text-white"
                                                                    onclick="addToCart()"><i
                                                                        class="fi-rs-shoppi ng-cart"></i>Add to
                                                                    cart</button>
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <div class="row mb-3" id="stock_alert">

                                                    </div>
                                                    <div class="font-xs">
                                                        <ul class="mr-50 float-start">
                                                            <li class="mb-5">Regular Price:
                                                                <span>{{ $product->regular_price }}</span></li>
                                                            @if ($product->show_stock == 1)
                                                                <li class="mb-5">Stock:
                                                                    <span>{{ $product->stock_qty }}</span></li>
                                                            @endif
                                                            <li class="mb-5">Category:<span>
                                                                    {{ $product->category->name_en ?? 'No Category' }}
                                                                </span></li>
                                                            @php
                                                                $couponCode = getCoupon();
                                                                $coupon = \App\Models\Coupon::where('coupon_code', $couponCode)->first();
                                                                if ($coupon && $coupon->product_id != null) {
                                                                    $couponProductIds = explode(',', $coupon->product_id);
                                                                    if (in_array($product->id, $couponProductIds)) {
                                                                        $appliedCoupon = $couponCode;
                                                                    }
                                                                }
                                                            @endphp

                                                            @if (isset($appliedCoupon))
                                                                <p>Coupon Code: {{ $appliedCoupon }}</p>
                                                            @endif
                                                        </ul>
                                                        <ul class="float-start">
                                                            @if ($product->wholesell_price > 0)
                                                                <li class="mb-5">
                                                                    Whole Sell Price:
                                                                    <a href="#">{{ $product->wholesell_price }}</a>
                                                                </li>
                                                                <li class="mb-5">
                                                                    Whole Sell Quantity:
                                                                    <a
                                                                        href="#">{{ $product->wholesell_minimum_qty }}</a>
                                                                </li>
                                                            @endif
                                                            <li class="mb-5">Brand:
                                                                <a href="#" rel="tag">
                                                                    {{ $product->brand->name_en ?? 'No Brand' }}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- Detail Info -->
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <div class="tab-style3">
                                                <ul class="nav nav-tabs text-uppercase">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="Description-tab"
                                                            data-bs-toggle="tab" href="#Description">Description</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="Additional-info-tab" data-bs-toggle="tab"
                                                            href="#Additional-info">Additional info</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        @php
                                                            $data = \App\Models\Review::where('product_id', $product->id)
                                                                ->where('status', 1)
                                                                ->get();
                                                        @endphp
                                                        <a class="nav-link" id="Vendor-info-tab" data-bs-toggle="tab"
                                                            href="#reviews">reviews ({{ $data->count() }})</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content shop_info_tab entry-main-content">
                                                    <div class="tab-pane fade show active" id="Description">
                                                        <div class="">
                                                            <p>
                                                                @if (session()->get('language') == 'bangla')
                                                                    {!! $product->description_en ?? 'No Product Long Descrption' !!}
                                                                @else
                                                                    {!! $product->description_bn ?? 'No Product Logn Descrption' !!}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="Additional-info">
                                                        <table class="font-md">
                                                            <tbody>
                                                                <tr class="stand-up">
                                                                    <th>Product Code</th>
                                                                    <td>
                                                                        <p>{{ $product->product_code ?? 'No Product Code' }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr class="folded-wo-wheels">
                                                                    <th>Product Size</th>
                                                                    <td>
                                                                        <p>{{ $product->product_size_en ?? 'No Product Size' }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr class="folded-w-wheels">
                                                                    <th>Product Color</th>
                                                                    <td>
                                                                        <p>{{ $product->product_color_en ?? 'No Product Color' }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="tab-pane fade" id="reviews">
                                                        <div class="product__review__system">
                                                            <h6>Youre reviewing:</h6>
                                                            <h5>
                                                                @if (session()->get('language') == 'bangla')
                                                                    {{ $product->name_bn }}
                                                                @else
                                                                    {{ $product->name_en }}
                                                                @endif
                                                            </h5>
                                                            <form action="{{ route('review.store') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product->id }}">
                                                                <input type="hidden" name="user_id"
                                                                    value="{{ Auth::user()->id ?? 'null' }}">
                                                                <div class="product__rating">
                                                                    <label for="rating">Rating <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="rating-checked">
                                                                        <input type="radio" name="rating"
                                                                            value="5" style="--r: #ffb301" />
                                                                        <input type="radio" name="rating"
                                                                            value="4" style="--r: #ffb301" />
                                                                        <input type="radio" name="rating"
                                                                            value="3" style="--r: #ffb301" />
                                                                        <input type="radio" name="rating"
                                                                            value="2" style="--r: #ffb301" />
                                                                        <input type="radio" name="rating"
                                                                            value="1" style="--r: #ffb301" />
                                                                    </div>
                                                                    @error('rating')
                                                                        <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="review__form">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="form-group">
                                                                                <label for="name">Name <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" name="name"
                                                                                    id="name"
                                                                                    value="{{ old('name') }}">
                                                                                @error('name')
                                                                                    <p class="text-danger">{{ $message }}
                                                                                    </p>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="form-group">
                                                                                <label for="summary">Summary <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" name="summary"
                                                                                    id="summary"
                                                                                    value="{{ old('summary') }}">
                                                                                @error('summary')
                                                                                    <p class="text-danger">{{ $message }}
                                                                                    </p>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-12">
                                                                            <div class="form-group">
                                                                                <label for="review">Review <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" name="review"
                                                                                    id="review"
                                                                                    value="{{ old('review') }}">
                                                                                @error('review')
                                                                                    <p class="text-danger">{{ $message }}
                                                                                    </p>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-info">Submit
                                                                        Review</button>
                                                                </div>
                                                            </form>
                                                            <div class="review_list">
                                                                @php
                                                                    $data = \App\Models\Review::where('product_id', $product->id)
                                                                        ->latest()
                                                                        ->get();
                                                                @endphp
                                                                @foreach ($data as $value)
                                                                    @if ($value->status == 1)
                                                                        <div class="single-review-item">
                                                                            <div class="rating">
                                                                                @if ($value->rating == '1')
                                                                                    <i class="fa fa-star"></i>
                                                                                @elseif($value->rating == '2')
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                @elseif($value->rating == '3')
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                @elseif($value->rating == '4')
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                @elseif($value->rating == '5')
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                @endif
                                                                            </div>
                                                                            <h5 class="review-title">{{ $value->summary }}
                                                                            </h5>
                                                                            <h6 class="review-user">{{ $value->name }}
                                                                            </h6>
                                                                            <span
                                                                                class="review-description">{!! $value->review !!}</span>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 d-none d-lg-block">
                                        <div class="slider__right single__product__right">
                                            @foreach ($home_banners as $item)
                                                <div class="single__category">
                                                    <a href="javascript:void(0)"><img
                                                            src="{{ asset($item->banner_img) }}" width="100%"
                                                            alt=""></a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-60">
                                    <div class="border">
                                        <div class="row g-0 align-items-center p-1" style="background: #f9f9f9">
                                            <div class="col-8">
                                                <div class="section__heading">
                                                    <h6>Related products</h6>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="related__roduct_arrow text-end"></div>
                                            </div>
                                        </div>
                                        <div class="related__product__active">
                                            @foreach ($relatedProduct as $product)
                                                <div class="single__product__item border">
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
                                                                            ৳{{ $product->discount_price }} off</div>
                                                                    @elseif($product->discount_type == 2)
                                                                        <div class="product__label sale__label">
                                                                            {{ $product->discount_price }}% off</div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="product__item__action">
                                                            {{-- <a href="#" id="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#quickViewModal" onclick="productView(this.id); return false;"><i class="fa fa-eye"></i></a> --}}
                                                        </div>
                                                    </div>
                                                    <div class="product__details">
                                                        <div class="product__details__top">
                                                            <strong class="product__name">
                                                                <a href="{{ route('product.details', $product->slug) }}"
                                                                    class="product__link">
                                                                    @if (session()->get('language') == 'bangla')
                                                                        {{ Str::limit($product->name_bn, 30) }}
                                                                    @else
                                                                        {{ Str::limit($product->name_en, 30) }}
                                                                    @endif
                                                                </a>
                                                            </strong>
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
                                                                            <i class="fa fa-star"
                                                                                style="color: #FFB811;"></i>
                                                                        @elseif ($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5)
                                                                            {{-- Display a half-star with gradient --}}
                                                                            <i class="fa fa-star"
                                                                                style="background: linear-gradient(to right, #FFB811 50%, gray 50%); -webkit-background-clip: text; color: transparent;"></i>
                                                                        @else
                                                                            <i class="fa fa-star"
                                                                                style="color: gray;"></i>
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
                                                                {{-- <a class="add" id="{{ $product->id }}" onclick="productView(this.id)" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a> --}}
                                                            @else
                                                                <input type="hidden" id="pfrom" value="direct">
                                                                <input type="hidden" id="product_product_id"
                                                                    value="{{ $product->id }}" min="1">
                                                                <input type="hidden"
                                                                    id="{{ $product->id }}-product_pname"
                                                                    value="{{ $product->name_en }}">
                                                                @if ($maintenance == 1)
                                                                    <a class="add"data-bs-toggle="modal"
                                                                        data-bs-target="#maintenance"><i
                                                                            class="fi-rs-shopping-cart mr-5"></i>Add to
                                                                        cart</a>
                                                                @else
                                                                    <a class="add"
                                                                        onclick="addToCartDirect({{ $product->id }})"><i
                                                                            class="fi-rs-shopping-cart mr-5"></i>Add to
                                                                        Cart </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('footer-script')
    <!-- Image zoom -->
    <script src="{{ asset('frontend/magiczoomplus/magiczoomplus.js') }}"></script>
    <script>
        var mzOptions = {
            zoomWidth: "400px",
            zoomHeight: "400px",
            zoomDistance: 15,
            expandZoomMode: "magnifier",
            expandZoomOn: "always",
            variableZoom: true,
            // lazyZoom: true,
            // selectorTrigger: "hover"
        };
    </script>
@endpush