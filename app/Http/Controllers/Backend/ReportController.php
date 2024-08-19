<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Order;
use Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        $categories = Category::where('id', $request->category_id)->select('id', 'name_en')->first();
        
        if (Auth::guard('admin')->user()->role == '2') {
            $products = Product::orderBy('created_at', 'desc')->where('vendor_id', Auth::guard('admin')->user()->id);
        
            // Check if category_id is present in the request
            if ($request->has('category_id')) {
                $sort_by = $request->category_id;
                $products->where('category_id', $sort_by);
            }
            $vendor = Vendor::where('user_id', Auth::guard('admin')->user()->id)->first();
        
            if ($vendor) {
                $products->where('vendor_id', $vendor->user_id)->latest();
            }
            //return $products->get();
        } else {
            $products = Product::orderBy('created_at', 'desc');
        
            if ($request->has('category_id')) {
                $sort_by = $request->category_id;
                $products->where('category_id', $sort_by);
            }

            //return $products->get();
        }
        $products = $products->paginate(20);
        return view('backend.reports.index', compact('products', 'categories'));
    }


    public function revenueIndex(Request $request)
    {
        $order_by = null;
        $orders = Order::get();
        // dd($orders);
        return view('backend.reports.revenue_index', compact('orders', 'order_by'));
    }


    // public function revenueDateFilter(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $orderBy = $request->input('order_by');
    
    //     $query = Order::whereBetween('created_at', [$startDate, $endDate]);
    
    //     if ($orderBy !== null) {
    //         $query->where('order_by', $orderBy);
    //     }
    
    //     $orders = $query->get();
    
    //     $result = $orders->map(function($order) {
    //         return [
    //             'revenue_amount' => $order->sub_total,
    //             'vat_amount' => $order->sub_total * 5 /100,
    //         ];
    //     });
    
    //     return response()->json($result);
    // }


    public function revenueDateFilter(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $orderBy = $request->input('order_by');

        $query = Order::whereBetween('created_at', [$startDate, $endDate]);

        if ($orderBy !== null && $orderBy !== '') {
            $query->where('order_by', $orderBy);
        }

        $revenueAmount = $query->sum('sub_total');
        $vatAmount = $revenueAmount * 0.05;

        $result = [
            [
                'revenue_amount' => $revenueAmount,
                'vat_amount' => $vatAmount,
            ]
        ];

        return response()->json($result);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}