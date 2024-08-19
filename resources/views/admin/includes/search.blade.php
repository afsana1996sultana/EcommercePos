<ul>
    @foreach ($products->where('stock_qty', '!=', 0) as $product)
       @php
        if ($product->discount_type == 1) {
            $price_after_discount = $product->regular_price - $product->discount_price;
        } elseif ($product->discount_type == 2) {
            $price_after_discount = $product->regular_price - ($product->regular_price * $product->discount_price) / 100;
        }
        $Price = ($product->discount_price ? $price_after_discount : $product->regular_price);
       @endphp
       @if ($product->is_varient)
            @foreach ($product->stocks->where('qty', '!=', 0) as $key => $stock)
                    @php
                        if ($product->discount_type == 1) {
                            $price_after_discount = $stock->price - $product->discount_price;
                        } elseif ($product->discount_type == 2) {
                            $price_after_discount = $stock->price - ($stock->price * $product->discount_price) / 100;
                        }
                        $Price = ($product->discount_price ? $price_after_discount : $stock->price);
                    @endphp
                <li>
                    <div class="addToCartBtn" data-id={{ $stock->id }} data-product_id="{{ $product->id }}">
                        {{ $product->name_en }} ({{ $stock->varient }})
                                            ({{ $stock->qty }})={{ $Price }}৳
                    </div>
                </li>
            @endforeach
        @else
            <li>
                <div class="addToCartBtn" data-product_id="{{ $product->id }}">
                    {{ $product->name_en }}({{ $product->stock_qty }})={{ $Price }}৳
                </div>
            </li>
        @endif
    @endforeach
</ul>

