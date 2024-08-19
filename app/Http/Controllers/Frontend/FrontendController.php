<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use Carbon\Carbon;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\MultiImg;
use App\Models\Page;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Blog;
use App\Models\Vendor;
use App\Models\Contact;
use Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Utility\CategoryUtility;


class FrontendController extends Controller
{
    /*=================== Start Index Methoed ===================*/
    public function index(Request $request)
    {
        $products = Product::where('status', 1)->where('is_featured', 1)->orderBy('id', 'DESC')->limit(15)->get();
        // Search Start
        $sort_search = null;
        if ($request->has('search')) {
            $sort_search = $request->search;
            $products = $products->where('name_en', 'like', '%' . $sort_search . '%');
            // dd($products);
        } else {
            $products = Product::where('status', 1)->where('is_featured', 1)->orderBy('id', 'DESC')->limit(15)->get();
        }
        // Header Category Start
        $categories = Category::orderBy('name_en', 'DESC')->where('status', '=', 1)->where('is_featured',1)->limit(15)->get();
        // Header Category End
        // Category Featured all
        $featured_category = Category::orderBy('name_en', 'DESC')->where('is_featured', '=', 1)->where('status', '=', 1)->get();
        //Slider
        $sliders = Slider::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
        // Product Top Selling
        $product_top_sellings = Product::where('status', 1)->orderBy('id', 'ASC')->limit(2)->get();
        //Product Trending
        $product_trendings = Product::where('status', 1)->orderBy('id', 'ASC')->skip(2)->limit(2)->get();
        //Product Recently Added
        $product_recently_adds = Product::where('status', 1)->latest()->skip(2)->limit(2)->get();

        $product_top_rates = Product::where('status', 1)->orderBy('regular_price')->limit(2)->get();
        // Home Banner
        $home_banners = Banner::where('status', 1)->limit(3)->where('position', 1)->orderBy('id', 'DESC')->get();
        $home_banners_1 = Banner::where('status', 1)->skip(3)->limit(3)->where('position', 1)->orderBy('id', 'DESC')->get();
        $home_banners_2 = Banner::where('status', 1)->skip(6)->limit(3)->where('position', 1)->orderBy('id', 'DESC')->get();
        $home_banners_3 = Banner::where('status', 1)->skip(9)->limit(3)->where('position', 1)->orderBy('id', 'DESC')->get();
        // Daily Best Sells
        $todays_sale  = OrderDetail::where('created_at', 'like', '%' . date('Y-m-d') . '%')->get();
        $todays_sale = $todays_sale->unique('product_id');
        //Home2 featured category
        $home2_featured_categories = Category::orderBy('name_en', 'DESC')->where('is_featured', '=', 1)->where('status', '=', 1)->get();

        // $catWiseProducts = Category::with('products')->limit(2)->get();

        $trending_cats = Category::where('trending', '=', 1)->where('status', '=', 1)->latest()->limit(1)->get();
        $special_cats = Category::with('products')->where('special', '=', 1)->where('status', '=', 1)->latest()->limit(2)->get();

        $categoriesIds = $trending_cats->pluck('id')->toArray();
        $trending__subcategory = Category::whereIn('parent_id', $categoriesIds)->get();

        $subcategoriesIds = $trending__subcategory->pluck('id')->toArray();
        $subcategory_trending_products = Product::whereIn('category_id', $subcategoriesIds)->orderBy('id', 'DESC')->limit(10)->get();

        $blogs = Blog::latest('id', 'DESC')->limit(4)->get();


        $home_view = 'frontend.home';

        return view($home_view, compact('categories', 'sliders', 'featured_category', 'trending_cats', 'subcategory_trending_products', 'trending__subcategory', 'products', 'product_top_sellings', 'product_trendings', 'product_recently_adds', 'product_top_rates', 'home_banners', 'sort_search', 'todays_sale', 'home2_featured_categories', 'home_banners_1', 'home_banners_2', 'special_cats', 'blogs', 'home_banners_3'));
    } // end method

    public function blogDetails($slug)
    {
        $blogs = Blog::limit(15)->latest('id', 'asc')->get();
        $blog__details = Blog::where('slug', $slug)->first();
        // return $blog__details;
        return view('frontend.blog.blog__details', compact('blog__details', 'blogs'));
    }

    /* ========== Start ProductDetails Method ======== */
    public function productDetails($slug)
    {

        $product = Product::where('slug', $slug)->first();
        if ($product) {
            if ($product->id) {
                $multiImg = MultiImg::where('product_id', $product->id)->get();
            }

            /* ================= Product Color Eng ================== */
            $color_en = $product->product_color_en;
            $product_color_en = explode(',', $color_en);

            /* ================= Product Size Eng =================== */
            $size_en = $product->product_size_en;
            $product_size_en = explode(',', $size_en);

            /* ================= Realted Product =============== */
            $cat_id = $product->category_id;
            $relatedProduct = Product::where('category_id', $cat_id)->where('id', '!=', $product->id)->orderBy('id', 'DESC')->get();

            $categories = Category::orderBy('name_en', 'ASC')->where('status', '=', 1)->limit(5)->get();
            $new_products = Product::orderBy('name_en')->where('status', '=', 1)->limit(3)->latest()->get();
            $home_banners = Banner::where('status', 1)->limit(3)->where('position', 1)->orderBy('id', 'DESC')->get();

            return view('frontend.product.product_details', compact('product', 'multiImg', 'categories', 'new_products', 'product_color_en', 'product_size_en', 'relatedProduct', 'home_banners'));
        }

        return view('frontend.product.productNotFound');
    }

    /* ========== Start CatWiseProduct Method ======== */
    public function CatWiseProduct(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->first();
        // Top filter Start
        $sort_by = $request->sort_by;
        $brand_id = $request->brand;

        $conditions = ['status' => 1];

        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        } elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        $products_sort_by = Product::where($conditions);

        switch ($sort_by) {
            case 'newest':
                $products_sort_by->orderBy('created_at', 'desc')->paginate(20);
                break;
            case 'oldest':
                $products_sort_by->orderBy('created_at', 'asc')->paginate(20);
                break;
            case 'price-asc':
                $products_sort_by->orderBy('regular_price', 'asc')->paginate(20);
                break;
            case 'price-desc':
                $products_sort_by->orderBy('regular_price', 'desc')->paginate(20);
                break;
            default:
                $products_sort_by->orderBy('id', 'desc')->paginate(20);
                break;
        }
        // Top filter End

        $min_price = $request->get('filter_price_start');
        $max_price = $request->get('filter_price_end');

        $conditions = ['status' => 1];

        if ($request->brand_id != null && $request->brand_id > 0) {
            $conditions = array_merge($conditions, ['brand_id' => $request->brand_id]);
        }

        $products = Product::where($conditions);

        if ($min_price != null && $max_price != null) {
            $products->where('regular_price', '>=', $min_price)->where('regular_price', '<=', $max_price);
        }

        $category_ids = CategoryUtility::children_ids($category->id);
        $category_ids[] = $category->id;
        $products->whereIn('category_id', $category_ids);

        $products = $products->orderBy('created_at', 'desc')->paginate(20);

        $categories = Category::orderBy('name_en', 'ASC')->where('status', '=', 1)->get();
        // dd($products);
        $subcategories = Category::orderBy('name_en', 'ASC')->where('status', 1)->where('parent_id', $category->id)->get();

        return view('frontend.product.category_view', compact('products', 'categories', 'category', 'sort_by', 'brand_id', 'subcategories'));
    } // end method
    /* ========== End CatWiseProduct Method ======== */

    /* ========== Start CatWiseProduct Method ======== */
    public function VendorWiseProduct(Request $request, $slug)
    {

        $vendor = Vendor::where('slug', $slug)->first();
        // dd($category);

        $products = Product::where('status', 1)->where('vendor_id', $vendor->id)->orderBy('id', 'DESC')->paginate(20);
        // Price Filter
        if ($request->get('filter_price_start') !== Null && $request->get('filter_price_end') !== Null) {
            $filter_price_start = $request->get('filter_price_start');
            $filter_price_end = $request->get('filter_price_end');

            if ($filter_price_start > 0 && $filter_price_end > 0) {
                $products = Product::where('status', '=', 1)->where('vendor_id', $vendor->id)->whereBetween('regular_price', [$filter_price_start, $filter_price_end])->paginate(20);
                // dd($products);
            }
        }

        $categories = Category::orderBy('name_en', 'ASC')->where('status', '=', 1)->get();
        // dd($products);

        return view('frontend.product.vendor_view', compact('products', 'categories', 'vendor'));
    } // end method
    /* ========== End CatWiseProduct Method ======== */

    /* ========== Start TagWiseProduct Method ======== */
    // public function TagWiseProduct($id,$slug){

    //     $products = Product::where('status','=',1)->where('tag_id',$id)->orderBy('id','DESC')->paginate(5);
    //     $categories = Category::orderBy('name_en','ASC')->where('status','=',1)->limit(5)->get();
    //     $tags = Tag::orderBy('name_en','ASC')->where('status','=',1)->limit(5)->get();
    //     $tag = Tag::find($id);
    //     $new_products = Product::orderBy('name_en')->where('status','=',1)->limit(3)->latest()->get();

    //     return view('frontend.product.tag_view',compact('products','categories','tags','tag','new_products'));
    // } // end method
    /* ========== End TagWiseProduct Method ======== */


    /* ================= Start ProductViewAjax Method ================= */
    public function ProductViewAjax($id)
    {

        $product = Product::with('category', 'brand')->findOrFail($id);
        //dd($product);
        $attribute_values = json_decode($product->attribute_values);

        $attributes = new Collection;
        foreach ($attribute_values as $key => $attribute_value) {
            $attr = Attribute::select('id', 'name')->where('id', $attribute_value->attribute_id)->first();
            // $attribute->name = $attr->name;
            // $attribute->id = $attr->id;
            $attr->values = $attribute_value->values;
            $attributes->add($attr);
        }


        return response()->json(array(
            'product' => $product,
            'attributes' => $attributes,
        ));
    }
    /* ================= END PRODUCT VIEW WITH MODAL METHOD =================== */
    public function pageAbout($slug)
    {
        $page = Page::where('slug', $slug)->first();
        return view('frontend.settings.page.about', compact('page'));
    }
    
    public function privacyPolicy(){
        return view('frontend.settings.page.privacy_policy');
    }
    
    public function termCondition(){
        return view('frontend.settings.page.term_condition');
    }
    
    public function termService(){
        return view('frontend.settings.page.term_service');
    }
    
    public function ReturnRefundPolicy(){
        return view('frontend.settings.page.return_policy');
    }
    
    
    public function CancellationPolicy(){
        return view('frontend.settings.page.cancellation_policy');
    }
    
    public function orderTracking()
    {
        return view('frontend.settings.page.order_tracking');
    }


    public function orderTrack(Request $request)
    {
        $this->validate($request, [
            'invoice_no' => 'required',
            'phone' => 'required',
        ]);
        $order = Order::where('invoice_no', $request->invoice_no)->where('phone', $request->phone)->first();
        if (!$order) {
            $notification = array(
                'message' => 'Required Data Not Found.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        // dd($order);
        return view('frontend.settings.page.track', compact('order'));
    }

    /* ================= Start Product Search =================== */
    public function ProductSearch(Request $request)
    {

        //$request->validate(["search" => "required"]);
        $sort_by = $request->sort_by;
        $brand_id = $request->brand;

        $conditions = ['status' => 1];

        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        } elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        $products_sort_by = Product::where($conditions);

        switch ($sort_by) {
            case 'newest':
                $products_sort_by->orderBy('created_at', 'desc')->paginate(20);
                break;
            case 'oldest':
                $products_sort_by->orderBy('created_at', 'asc')->paginate(20);
                break;
            case 'price-asc':
                $products_sort_by->orderBy('regular_price', 'asc')->paginate(20);
                break;
            case 'price-desc':
                $products_sort_by->orderBy('regular_price', 'desc')->paginate(20);
                break;
            default:
                $products_sort_by->orderBy('id', 'desc')->paginate(20);
                break;
        }

        $item = $request->search;
        $category_id = $request->searchCategory;
        // echo "$item";

        // Header Category Start //
        $categories = Category::orderBy('name_en', 'DESC')->where('status', 1)->get();
        if ($category_id == 0) {
            $products = Product::where('name_en', 'LIKE', "%$item%")->where(
                'status',
                1
            )->latest()->get();
        } else {
            $products = Product::where('name_en', 'LIKE', "%$item%")->where('category_id', $category_id)->where(
                'status',
                1
            )->latest()->get();
        }

        $attributes = Attribute::orderBy('name', 'DESC')->where('status', 1)->latest()->get();

        return view('frontend.product.search', compact('products', 'categories', 'attributes', 'sort_by', 'brand_id'));
    } // end method

    /* ================= End Product Search =================== */

    /* ================= Start Advance Product Search =================== */
    public function advanceProduct(Request $request)
    {

        // return $request;

        $request->validate(["search" => "required"]);

        $item = $request->search;
        $category_id = $request->category;
        // echo "$item";

        // Header Category Start //
        $categories = Category::orderBy('name_en', 'DESC')->where('status', 1)->get();

        if ($category_id == 0) {
            $products = Product::where('name_en', 'LIKE', "%$item%")->where(
                'status',
                1
            )->latest()->get();
        } else {
            $products = Product::where('name_en', 'LIKE', "%$item%")->where('category_id', $category_id)->where(
                'status',
                1
            )->latest()->get();
        }

        $attributes = Attribute::orderBy('name', 'DESC')->where('status', 1)->latest()->get();

        return view('frontend.product.advance_search', compact('products', 'categories', 'attributes'));
    } // end method

    /* ================= End Advance Product Search =================== */

    /* ================= Start Hot Deals Page Show =================== */
    public function hotDeals(Request $request)
    {

        $sort_by = $request->sort_by;
        $brand_id = $request->brand;

        $conditions = ['status' => 1];

        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        } elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        $products_sort_by = Product::where($conditions);

        switch ($sort_by) {
            case 'newest':
                $products_sort_by->orderBy('created_at', 'desc')->paginate(20);
                break;
            case 'oldest':
                $products_sort_by->orderBy('created_at', 'asc')->paginate(20);
                break;
            case 'price-asc':
                $products_sort_by->orderBy('regular_price', 'asc')->paginate(20);
                break;
            case 'price-desc':
                $products_sort_by->orderBy('regular_price', 'desc')->paginate(20);
                break;
            default:
                $products_sort_by->orderBy('id', 'desc')->paginate(20);
                break;
        }
        // Hot deals product
        $products = Product::where('status', 1)->where('is_deals', 1)->paginate(20);

        // Category Filter Start
        if ($request->get('filtercategory')) {

            $checked = $_GET['filtercategory'];
            // filter With name start
            $category_filter = Category::whereIn('name_en', $checked)->get();
            $catId = [];
            foreach ($category_filter as $cat_list) {
                array_push($catId, $cat_list->id);
            }
            // filter With name end

            $products = Product::whereIn('category_id', $catId)->where('status', 1)->where('is_deals', 1)->latest()->paginate(20);
            // dd($products);
        }
        // Category Filter End

        $attributes = Attribute::orderBy('name', 'DESC')->where('status', 1)->latest()->get();
        // End Shop Product //
        return view('frontend.deals.hot_deals', compact('attributes', 'products', 'sort_by', 'brand_id'));
    } // end method

    public function contact()
    {
        return view('frontend.contact');
    } // end method

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]);

        Contact::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'created_at' => Carbon::now()
        ]);


        Session::flash('success', 'Thank you for Contact us');
        return redirect()->back();
    }

    public function hot_deals()
    {
        $hot_deals = Product::where('status', 1)->where('is_deals', 1)->orderBy('id', 'desc')->get();
        // return $hot_deals;
        return view('frontend.deals.hot_deals', compact('hot_deals'));
    }
    
    
    /* ================= Start Campaign Page Show =================== */
    public function Campaign(Request $request)
    {
        // Header Category Start
        $categories = Category::orderBy('name_en','DESC')->where('status', 1)->get();
        
        // Sort and brand filter Start
        $sort_by = $request->sort_by;
        $brand_id = $request->brand;

        $conditions = ['status' => 1];

        if($brand_id != null){
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }elseif ($request->brand != null) {
            $brand_id = (Brand::where('id', $request->brand)->first() != null) ? Brand::where('id', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }
        
        $products_sort_by = Product::where($conditions);
        switch ($sort_by) {
            case 'newest':
                $products_sort_by = $products_sort_by->orderBy('created_at', 'desc')->paginate(30)->appends(request()->query());
                break;
            case 'oldest':
                $products_sort_by = $products_sort_by->orderBy('created_at', 'asc')->paginate(30)->appends(request()->query());
                break;
            case 'price-asc':
                $products_sort_by = $products_sort_by->orderBy('regular_price', 'asc')->paginate(30)->appends(request()->query());
                break;
            case 'price-desc':
                $products_sort_by = $products_sort_by->orderBy('regular_price', 'desc')->paginate(30)->appends(request()->query());
                break;
            default:
                $products_sort_by = $products_sort_by->orderBy('id', 'desc')->paginate(30)->appends(request()->query());
                break;
        }
        
        $products = Product::orderBy('name_en', 'ASC')->where('brand_id', $brand_id)->latest()->paginate(30)->appends(request()->query());
        
        $min_price = $request->get('filter_price_start');
        $max_price = $request->get('filter_price_end');
        //dd($min_price);
        if($min_price != null && $max_price != null){
            $products = Product::orderBy('name_en', 'ASC')->where('status', 1)->where('regular_price', '>=', $min_price)->where('regular_price', '<=', $max_price)->paginate(30)->appends(request()->query());
            // dd($products);
        }
        
        // Category Filter Start
        if ($request->get('filtercategory')){

            $checked = $_GET['filtercategory'];
            // filter With name start
            $category_filter = Category::whereIn('name_en', $checked)->get();
            $catId = [];
            foreach($category_filter as $cat_list){
                array_push($catId, $cat_list->id);
            }
            // filter With name end

            $products = Product::whereIn('category_id', $catId)->where('status', 1)->latest()->paginate(30)->appends(request()->query());
            //dd($products);
            // dd($products);
        }

        $attributes = Attribute::orderBy('name', 'DESC')->where('status', 1)->latest()->get();
        // End Shop Product //

        return view('frontend.product.campaing', compact('categories','attributes','products','sort_by','brand_id'));
    }
}