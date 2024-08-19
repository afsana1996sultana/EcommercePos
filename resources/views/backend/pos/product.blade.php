@foreach ($products as $product)
@if ($product->is_varient)
    @foreach ($product->stocks as $key => $stock)
        <div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3 col-xxl-2 product-thumb product_row_loader product_row_loader addToCartBtn"
            data-id={{ $stock->id }} data-product_id="{{ $product->id }}">
            <div class="card single__product__najmul" style="cursor: pointer">
                <div class="card-body product__body">
                    <div class="product-image">
                        @if ($stock->image && $stock->image != '' && $stock->image != 'Null')
                            <img class="default-img" src="{{ asset($stock->image) }}"
                                alt="" />
                        @else
                            <img class="default-img"
                                src="{{ asset('upload/no_image.jpg') }}"
                                alt="" />
                        @endif
                    </div>
                    <h6 class="product__name">
                        <?php $p_name_en = strip_tags(html_entity_decode($product->name_en)); ?>
                        {{ Str::limit($p_name_en, $limit = 30, $end = '. . .') }}
                    </h6>
                    <p class="product_size">
                        Size: {{ $stock->varient }}
                    </p>
                    <p class="product_stock">
                        Stock: {{ $stock->qty }}
                    </p>
                    <div>

                        @if ($product->discount_price > 0)
                            @php
                                if ($product->discount_type == 1) {
                                    $price_after_discount = $stock->price - $product->discount_price;
                                } elseif ($product->discount_type == 2) {
                                    $price_after_discount = $stock->price - ($stock->price * $product->discount_price) / 100;
                                }
                            @endphp
                            <div class="product-price">
                                <del class="old-price">৳{{ $stock->price }}</del>
                                <span
                                    class="price text-primary">৳{{ $price_after_discount }}</span>
                            </div>
                        @else
                            <div class="product-price">
                                <span
                                    class="price text-primary">৳{{ $stock->price }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3 col-xxl-2 product-thumb  product_row_loader addToCartBtn"
        data-product_id="{{ $product->id }}">
        <div class="card single__product__najmul">
            <div class="card-body product__body" style="cursor: pointer">
                <div class="product-image">
                    @if ($product->product_thumbnail && $product->product_thumbnail != '' && $product->product_thumbnail != 'Null')
                        <img class="default-img"
                            src="{{ asset($product->product_thumbnail) }}"
                            alt="" />
                    @else
                        <img class="default-img"
                            src="{{ asset('upload/no_image.jpg') }}"
                            alt="" />
                    @endif
                </div>
                <h6 class="product__name">
                    <?php $p_name_en = strip_tags(html_entity_decode($product->name_en)); ?>
                    {{ Str::limit($p_name_en, $limit = 30, $end = '. . .') }}
                </h6>
                <p class="product_stock">
                    Stock: {{ $product->stock_qty }}
                </p>
                <div>
                    @if ($product->discount_price > 0)
                        @php
                            if ($product->discount_type == 1) {
                                $price_after_discount = $product->regular_price - $product->discount_price;
                            } elseif ($product->discount_type == 2) {
                                $price_after_discount = $product->regular_price - ($product->regular_price * $product->discount_price) / 100;
                            }
                        @endphp
                        <div class="product-price">
                            <del
                                class="old-price">৳{{ $product->regular_price }}</del>
                            <span
                                class="price text-primary">৳{{ $price_after_discount }}</span>
                        </div>
                    @else
                        <div class="product-price">
                            <span
                                class="price text-primary">৳{{ $product->regular_price }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endforeach