<span class="mobile__filter d-md-none">filter</span>
<form action="{{ URL::current() }}" method="GET" id="search-form" class="d-none d-md-block filter__show">
    <div class="card">
        <div class="sidebar-widget price_range range border-0">
            <h5 class="mb-20">By Price</h5>
            <div class="price-filter mb-30">
                <div class="price-filter-inner">
                    <div id="slider-range" class="mb-20"></div>
                    <div class="d-flex justify-content-between">
                        <div class="caption">From: <strong id="slider-range-value1">@if(isset($_GET['filter_price_start'])) {{ $_GET['filter_price_start'] }} @endif</strong></div>
                        <div class="caption">To: <strong id="slider-range-value2"></strong></div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="filter_price_start" name="filter_price_start" @if(isset($_GET['filter_price_start'])) value="{{ $_GET['filter_price_start'] }}" @else value="1" @endif>
            <input type="hidden" id="filter_price_end" name="filter_price_end" @if(isset($_GET['filter_price_end'])) value="{{ $_GET['filter_price_end'] }}" @else value="200000" @endif>

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
                    @php
                        $conditions = ['status' => 1];
                        $category_ids = App\Utility\CategoryUtility::children_ids($category->id);
                        $category_ids[] = $category->id;
                        $products = App\Models\Product::where($conditions)->whereIn('category_id', $category_ids)->orderBy('id','DESC')->get();
                    @endphp
                    <span class="float-end">{{ count($products) }}</span>
                    <br>
                </div>
                @endforeach
            </div>


            <button type="submit" class="btn btn-sm btn-default mt-20 mb-10" onclick="sort_price_filter()"><i class="fi-rs-filter mr-5"></i> Fillter</button>
        </div>
    </div>
</form>

<script src="{{asset('frontend/assets/js/vendor/jquery-3.6.0.min.js')}}"></script>
<script>
    // jQuery to toggle the visibility of the filter form
    $(document).ready(function() {
        $(".mobile__filter").click(function() {
            $(".filter__show").toggleClass("d-none"); // Toggles the visibility class
        });
    });

</script>
