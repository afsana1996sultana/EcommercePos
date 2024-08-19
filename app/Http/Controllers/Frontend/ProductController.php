<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Brand;
use App\Utility\CategoryUtility;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        // Header Category Start
        $categories = Category::orderBy('name_en', 'DESC')->where('status', 1)->get();
        $sort_by = $request->sort_by;
        $brand_id = $request->brand;

        $conditions = ['status' => 1];
        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        $products_sort_by = Product::where($conditions);

        switch ($sort_by) {
            case 'newest':
                $products_sort_by->orderBy('created_at', 'desc')->paginate(30)->appends(request()->query());
                break;
            case 'oldest':
                $products_sort_by->orderBy('created_at', 'asc')->paginate(30)->appends(request()->query());
                break;
            case 'price-asc':
                $products_sort_by->orderBy('regular_price', 'asc')->paginate(30)->appends(request()->query());
                break;
            case 'price-desc':
                $products_sort_by->orderBy('regular_price', 'desc')->paginate(30)->appends(request()->query());
                break;
            default:
                $products_sort_by->orderBy('id', 'desc')->paginate(30)->appends(request()->query());
                break;
        }
        $products = Product::where($conditions)->orderBy('name_en', 'ASC')->latest()->paginate(30)->appends(request()->query());

        $min_price = $request->get('filter_price_start');
        $max_price = $request->get('filter_price_end');
        if ($min_price != null && $max_price != null) {
            $products = Product::orderBy('name_en', 'ASC')->where('status', 1)->where('regular_price', '>=', $min_price)->where('regular_price', '<=', $max_price)->paginate(30)->appends(request()->query());
        }


        if ($request->has('filtercategory')) {
            $checked = $request->input('filtercategory');
            $category_filter = Category::whereIn('name_en', $checked)->get();
            $conditions = ['status' => 1];

            $category_ids = [];
            foreach ($category_filter as $cat) {
                $category_ids = array_merge($category_ids, CategoryUtility::children_ids($cat->id));
                $category_ids[] = $cat->id;
            }

            $products = Product::where($conditions)->whereIn('category_id', $category_ids)->latest()->paginate(30);
        }
        // Category Filter End

        //dd($products);
        $attributes = Attribute::orderBy('name', 'DESC')->where('status', 1)->latest()->get();
        return view('frontend.product.product_shop', compact('categories', 'attributes', 'products', 'sort_by', 'brand_id'));
    }

    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }

    public function getVarient($id, $varient)
    {
        //dd($varient);
        $stock = ProductStock::where('product_id', $id)->where('varient', $varient)->with('product')->first();
        //dd($stock->price);
        if ($stock) {
            return $stock;
        } else {
            return null;
        }
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    public function wholesell_price(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        if ($product->is_wholesell == 1) {
            if ($product->wholesell_minimum_qty == $request->qty || $product->wholesell_minimum_qty < $request->qty) {
                $wholePrice = $product->wholesell_price;
                return response()->json(['wholePrice' => $wholePrice]);
            } elseif ($product->wholesell_minimum_qty > $request->qty) {
                $wholePrice = $request->product_price;
                return response()->json(['wholePrice' => $wholePrice]);
            }
        } else {
            $wholePrice = $request->product_price;
            return response()->json(['wholePrice' => $wholePrice]);
        }
    }
}