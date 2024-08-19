@extends('layouts.frontend1')
@section('content-frontend')
    @include('frontend.common.add_to_cart_modal')
@section('title')
    Category Nest Online Shop
@endsection
@include('frontend.common.maintenance')
@php
    $maintenance = getMaintenance();
    $couponCode = getCoupon();
@endphp
<main class="main">
    <div class="page-header">
        <div class="container mt-30 mb-30">
            <div class="row">
                <div class="col-lg-4-5">
                    <div class="shop-product-fillter">
                        <div class="totall-product">
                            <p>We found <strong class="text-brand">{{ count($products) }}</strong> items for you!</p>
                        </div>
                        <div class="sort-by-product-area">
                            <div class="sort-by-cover d-flex gap-2 mb-2">
                                <div class="custom_select">
                                    <select class="form-control select-active" onchange="filter()" name="brand">
                                        <option value="">All Brands</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                            <option value="{{ $brand->slug }}"
                                                @if ($brand_id == $brand->id) selected @endif>
                                                {{ $brand->name_en ?? '' }}</option>
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
                                            Price High to Low
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row product-grid g-2">

                        <div class="category__main__image">
                            @if ($category->banner_image)
                                <img src="{{ asset($category->banner_image) }}" alt="">
                            @else
                                <img src="{{ asset($category->image) }}" alt="">
                            @endif
                        </div>


                        @if ($subcategories->count() > 0)
                            <div class="subcategory__show toggle_nav" id="toggle_nav">
                                <h6>Subcategory List</h6>
                            </div>
                        @endif
                        <div class="mobile_nav" id="mobile_nav">
                            <div class="mobile_navWrapper" id="mobile_navWrapper">
                                <div class="mobile_nav_content">
                                    <ul class="subcategory__item__show">
                                        <h5 class="pb-2 mb-2 border-bottom">Subcategory List</h5>
                                        @foreach ($subcategories as $key => $subcategory)
                                            <li><a
                                                    href="{{ route('product.category', $subcategory->slug) }}">{{ $subcategory->name_en }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @forelse($products as $product)
                            @include('frontend.common.product_grid_view', ['product' => $product])
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
                </div>
                <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
                    <!-- Fillter By Price -->
                    @include('frontend.common.filterby')
                    <!-- SideCategory -->
                    @include('frontend.common.sidecategory')
                </div>
            </div>
        </div>
</main>
@endsection
@push('footer-script')
<script type="text/javascript">
    // function sort_price_filter(){
    //    var start = $('#slider-range-value1').html();
    //    var end = $('#slider-range-value2').html();
    //    // alert(start+'--'+end);
    //    $('#filter_price_start').val(start);
    //    $('#filter_price_end').val(end);
    //    $('#search-form').submit();
    // }
</script>

<script type="text/javascript">
    function filter() {
        $('#search-form').submit();
    }
</script>

<script>
    const mobileNav = document.getElementById("mobile_nav");
    const toggleNav = document.getElementById("toggle_nav");
    const navWrapper = document.getElementsByClassName("mobile_navWrapper")[0];

    // Function to remove classes from elements
    function removeClasses() {
        toggleNav.classList.remove("activeToggle");
        mobileNav.classList.remove("showMobileNav");
    }

    // Add event listener to window
    window.addEventListener("click", function(event) {
        // Check if click occurred outside of navWrapper and toggleNav
        if (!navWrapper.contains(event.target) && !toggleNav.contains(event.target)) {
            removeClasses();
        }
    });

    // Add event listener to toggleNav
    toggleNav.addEventListener("click", function(event) {
        // Prevent event from bubbling up to window
        event.stopPropagation();
        // Toggle classes on elements
        toggleNav.classList.toggle("activeToggle");
        mobileNav.classList.toggle("showMobileNav");
    });
</script>
@endpush
