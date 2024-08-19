@php
$maintenance = getMaintenance();
@endphp
@include('frontend.common.maintenance')
 <div class="col-lg-1-5 col-md-4 col-sm-6 col-12">
     <div class="single__product__item border">
         <div class="product__image position-relative">
             <a href="{{ route('product.details',$product->slug) }}" class="product__item__photo">
                 <img src="{{ asset($product->product_thumbnail) }}" alt="">
             </a>

             <div class="product__discount__price d-flex">
                @if ($product->created_at >= Carbon\Carbon::now()->subWeek())
                 <div class="product__labels">
                     <div class="product__label new__label">New</div>
                 </div>
                @endif

                @if($product->discount_price > 0)
                 <div class="product__labels d-flex">
                     @if($product->discount_type == 1)
                     <div class="product__label sale__label">৳{{ $product->discount_price }} off</div>
                     @elseif($product->discount_type == 2)
                     <div class="product__label sale__label">{{ $product->discount_price }}% off</div>
                     @endif
                 </div>
                @endif
                
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

                @if(isset($appliedCoupon))
                    <span class="coupon_code">Coupon : {{ $appliedCoupon }}</span>
                @endif
                
             </div>

             <div class="product__item__action">
                 <a href="#" id="{{ $product->id }}" onclick="productView(this.id)" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fa fa-eye"></i></a>
             </div>
         </div>
         <div class="product__details">
             <div class="product__details__top">
                 <strong class="product__name">
                     <a href="{{ route('product.details',$product->slug) }}" class="product__link">
                         @if(session()->get('language') == 'bangla')
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
                        $price_after_discount = $product->regular_price - ($product->regular_price *
                        $product->discount_price) / 100;
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
                 <a href="{{ route('product.details',$product->slug) }}">View Details</a>
                 @if($product->is_varient == 1)
                    @if($maintenance==1)
                        <a class="add" data-bs-toggle="modal" data-bs-target="#maintenance"><i class="fi-rs-shopping-cart mr-5"></i>Add to cart</a>
                    @else
                       <a class="add" id="{{ $product->id }}" onclick="productView(this.id)" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a>
                    @endif
                 @else
                 <input type="hidden" id="pfrom" value="direct">
                 <input type="hidden" id="product_product_id" value="{{ $product->id }}" min="1">
                 <input type="hidden" id="{{ $product->id }}-product_pname" value="{{ $product->name_en }}">
                    @if($maintenance==1)
                        <a class="add"  data-bs-toggle="modal" data-bs-target="#maintenance"><i class="fi-rs-shopping-cart mr-5"></i>Add to cart</a>
                    @else
                       <a class="add" onclick="addToCartDirect({{ $product->id }})"><i class="fi-rs-shopping-cart mr-5"></i>Add to Cart </a>
                    @endif
                 @endif
             </div>
         </div>
     </div>
 </div>