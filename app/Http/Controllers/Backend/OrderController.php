<?php

namespace App\Http\Controllers\Backend;

use PDF;
use Session;

use App\Models\User;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Address;
use App\Models\Product;
use App\Models\District;
use App\Models\Shipping;
use App\Models\Upazilla;
use App\Models\Attribute;
use App\Exports\PosExport;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Exports\OnlineExport;
use App\Exports\OrdersExport;
use App\Exports\DeliverExport;
use App\Models\AttributeValue;
use Illuminate\Support\Carbon;
use App\Exports\OrderTrashBinExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Frontend\PathaoController;

class OrderController extends Controller
{
    protected $orders;
    public function __construct()
    {
        $this->orders = Order::query();
    }
    public function index(Request $request)
    {
        $date = $request->date;
        $startIndex = 0;
        $delivery_status = $request->delivery_status;
        $payment_status = $request->payment_status;
        $shipping_type = $request->shipping_type;
        $ordersQuery = $this->orders->where('order_by', 0)->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered');
        if ($date) {
            $dateRange = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
        }
        if ($date) {
            $ordersQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $ordersQuery->where('payment_status', $payment_status);
        }
        if ($shipping_type) {
            $ordersQuery->where('shipping_type', $shipping_type);
        }
        if ($delivery_status) {
            $ordersQuery->where('delivery_status', $delivery_status);
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(100);

        return view('backend.sales.all_orders.onlineOrder.index', compact('orders', 'delivery_status', 'date', 'payment_status', 'shipping_type', 'startIndex'));
    }
    public function indexPos(Request $request)
    {
        $date = $request->date;
        $startIndex = 0;
        $delivery_status = $request->delivery_status;
        $payment_status = $request->payment_status;
        $shipping_type = $request->shipping_type;
        $ordersQuery = $this->orders->where('order_by', 1)->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered');
        if ($date) {
            $dateRange = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
        }
        if ($date) {
            $ordersQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $ordersQuery->where('payment_status', $payment_status);
        }
        if ($shipping_type) {
            $ordersQuery->where('shipping_type', $shipping_type);
        }
        if ($delivery_status) {
            $ordersQuery->where('delivery_status', $delivery_status);
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(100);
        // return $orders;
        return view('backend.sales.all_orders.posOrder.posOrder', compact('orders', 'delivery_status', 'date', 'payment_status', 'shipping_type', 'startIndex'));
    }
    public function AllvendorSellView(Request $request)
    {
        $date = $request->date;
        $startIndex = 0;
        $delivery_status = $request->delivery_status;
        $payment_status = $request->payment_status;
        $shipping_type = $request->shipping_type;
        $vendor_id = null;
        $ordersQuery = $this->orders->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered')->latest();
        if ($date) {
            $dateRange = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
        }
        if ($date) {
            $ordersQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $ordersQuery->where('payment_status', $payment_status);
        }
        if ($shipping_type) {
            $ordersQuery->where('shipping_type', $shipping_type);
        }
        if ($delivery_status) {
            $ordersQuery->where('delivery_status', $delivery_status);
        }
        $vendors = Vendor::pluck('user_id')->toArray();
        $users = User::where('role', 2)->latest()->get();
        $orderIds = OrderDetail::whereIn('vendor_id', $vendors)->pluck('order_id');
        $orders = $ordersQuery->whereIn('id', $orderIds)->orderBy('created_at', 'desc')->paginate(100);
        return view('backend.sales.all_orders.allVendor.all_vendor_sale_index', compact('orders', 'orderIds', 'vendors', 'delivery_status', 'date', 'payment_status', 'users', 'startIndex'));
    }
    public function vendorSellView(Request $request)
    {
        $date = $request->date;
        $delivery_status = $request->delivery_status;
        $payment_status = $request->payment_status;
        $shipping_type = $request->shipping_type;
        $ordersQuery = $this->orders->Where('show_hide', 1)->where('delivery_status', '!=', 'Delivered')->latest();
        if ($date) {
            $dateRange = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
        }
        if ($date) {
            $ordersQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $ordersQuery->where('payment_status', $payment_status);
        }
        if ($shipping_type) {
            $ordersQuery->where('shipping_type', $shipping_type);
        }
        if ($delivery_status) {
            $ordersQuery->where('delivery_status', $delivery_status);
        }
        $orderIds = Order::Where('show_hide', 1)->latest()->pluck('id')->toArray();
        if (Auth::guard('admin')->user()->role == '2') {
            $vendor = Vendor::where('user_id', Auth::guard('admin')->user()->id)->first();
            $vendorIds = OrderDetail::where('vendor_id', $vendor->user_id)->pluck('order_id')->toArray();
            $orders = $ordersQuery->whereIn('id', $vendorIds)->orderBy('created_at', 'desc')->paginate(100)->appends(request()->query());
        } else {
            $orders = [];
        }
        return view('backend.sales.all_orders.authVendor.vendor_sale_index', compact('orders', 'orderIds', 'delivery_status', 'date', 'payment_status', 'shipping_type'));
    }

    public function create()
    {
    }
    public function store(Request $request)
    {
    }

    public function show(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $shippings = Shipping::where('status', 1)->get();
        $products = Product::get();
        $productadd = Product::find($request->product_id);
        return view('backend.sales.all_orders.show', compact('order', 'shippings', 'products', 'productadd'));
    }
    public function orderCancle($id)
    {
        $order = Order::findOrFail($id);
        $orderdetails = OrderDetail::where('order_id', $order->id);
        $shippings = Shipping::where('status', 1)->get();
        return view('backend.sales.all_orders.show', compact('order', 'shippings', 'orderdetails'));
    }

    public function edit($id)
    {
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        $notification = array(
            'message' => 'Order Deleted Successfully.',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function order_delete_byStatus($id)
    {
        $order = Order::findOrFail($id);
        $order->show_hide = 0;
        $order->deleted_by =Auth::user();
        $order->update();
        return redirect()->back();
    }

    public function order_all_delete(Request $request)
    {
        $ids = $request->ids;
        Order::whereIn('id', $ids)->update(['show_hide' => 0]);
        return response()->json([
            'status' => 'success',
            'message' => "Orders Deleted Successfully",
        ]);
    }

    /*================= Start update_payment_status Methoed ================*/
    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->payment_status = $request->status;
        $order->save();

        $order_detail = OrderDetail::where('order_id', $order->id)->get();
        foreach ($order_detail as $item) {
            $item->payment_status = $request->status;
            $item->save();
        }
        // dd($order);
        $orderstatus = OrderStatus::create([
            'order_id' => $order->id,
            'title' => 'Payment Status: ' . $request->status,
            'comments' => '',
            'created_at' => Carbon::now(),
        ]);
        return response()->json(['success' => 'Payment status has been updated']);
    }

    /*================= Start update_delivery_status Methoed ================*/
    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        if ($request->status == 'Cancelled' ||  $request->status == 'Returned') {
            foreach ($order->order_details as $orderDetail) {
                //for product stock update
                if ($orderDetail->is_varient) {
                    $jsonData = $orderDetail->variation;
                    $data = json_decode($jsonData, true);
                    $attributeValues = [];
                    foreach ($data as $item) {
                        $attributeValues[] = $item['attribute_value'];
                    }
                    $concatenatedValue = implode('-', $attributeValues);
                    $productStockId = ProductStock::where('product_id', $orderDetail->product_id)->get();
                    foreach ($productStockId as $productStock) {
                        if ($productStock->varient == $concatenatedValue) {
                            $productStock->qty = $productStock->qty + $orderDetail->qty;
                            $productStock->update();
                        }
                    }
                }
                $product = Product::find($orderDetail->product_id);
                $product->stock_qty = $product->stock_qty + $orderDetail->qty;
                $orderDetail->delivery_status = $request->status;
                $orderDetail->update();
                $product->update();
            }
        }
        $order->delivery_status = $request->status;
        $order->save();
        $order_detail = OrderDetail::where('order_id', $order->id)->get();
        foreach ($order_detail as $item) {
            $item->delivery_status = $request->status;
            $item->save();
        }
        $orderstatus = OrderStatus::create([
            'order_id' => $order->id,
            'title' => 'Delevery Status: ' . $request->status,
            'comments' => '',
            'created_at' => Carbon::now(),
        ]);
        return response()->json(['success' => 'Delivery status has been updated']);
    }



    /*================= Start admin_user_update Methoed ================*/
    public function admin_user_update(Request $request, $user_id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => ['required', 'regex:/(\+){0,1}(88){0,1}01(3|4|5|6|7|8|9)(\d){8}/', 'digits:11'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
        ]);
        User::where('id', $user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        Session::flash('success', 'Customer Information Updated Successfully');
        return redirect()->back();
    }

    /* ============= Start getdivision Method ============== */
    public function getdivision($division_id)
    {
        $division = District::where('division_id', $division_id)->orderBy('district_name_en', 'ASC')->get();

        return json_encode($division);
    }
    /* ============= Start getupazilla Method ============== */
    public function getupazilla($district_id)
    {
        $upazilla = Upazilla::where('district_id', $district_id)->orderBy('name_en', 'ASC')->get();

        return json_encode($upazilla);
    }
    /* ============= Start invoice_download Method ============== */

    // public function invoice_download($id){
    //     $order = Order::findOrFail($id);

    //     $pdf = PDF::loadView('backend.invoices.invoice',compact('order'))->setPaper('a4')->setOptions([
    //             'tempDir' => public_path(),
    //             'chroot' => public_path(),
    //     ]);
    //     return $pdf->download('invoice.pdf');
    // } // end method

    /* ============= Start invoice_download Method ============== */
    public function invoice_download($id)
    {
        $order = Order::findOrFail($id);
        // return view('backend.invoices.invoice', compact('order'));
        //dd(app('url')->asset('upload/abc.png'));
        $pdf = PDF::loadView('backend.invoices.invoice', compact('order'))->setPaper('a4');
        return $pdf->download('invoice.pdf');
    } // end method
    /* ============= End invoice_download Method ============== */
    public function invoice_print_download($id)
    {
        //dd($id);
        $order = Order::findOrFail($id);
        //dd(app('url')->asset('upload/abc.png'));
        // $pdf = PDF::loadView('backend.invoices.invoice',compact('order'))->setPaper('a4');
        // dd($pdf);
        return view('backend.invoices.invoice_print', compact('order'));
        // return $pdf->loadView('invoice.pdf');
    } // end method
    public function packages_index(Request $request)
    {
        $date = $request->date;
        $startIndex = 0;
        $delivery_status = $request->delivery_status;
        $payment_status = $request->payment_status;
        $shipping_type = $request->shipping_type;
        $ordersQuery = $this->orders->Where('show_hide', 1)->where('packaging_status', 1)->where('delivery_status', '!=', 'Delivered');
        if ($date) {
            $dateRange = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
        }
        if ($date) {
            $ordersQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $ordersQuery->where('payment_status', $payment_status);
        }
        if ($shipping_type) {
            $ordersQuery->where('shipping_type', $shipping_type);
        }
        if ($delivery_status) {
            $ordersQuery->where('delivery_status', $delivery_status);
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(100);
        return view('backend.sales.all_orders.packageOrder.packages', compact('orders', 'delivery_status', 'date', 'payment_status', 'shipping_type', 'startIndex'));
    }
    public function package_status($id)
    {
        $getstatus = Order::select('packaging_status')->where('id', $id)->first();
        if ($getstatus->packaging_status == 0) {
            $status = 1;
        } elseif ($getstatus->packaging_status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        Order::where('id', $id)->update(['packaging_status' => $status]);
        return back();
    }

    public function delete_order_product($id)
    {
        $orderdetail = OrderDetail::findOrFail($id);
        $order = Order::find($orderdetail->order_id);
        $product = Product::find($orderdetail->product_id);
        $buyingPrice = $product->purchase_price;
        // if (!$orderdetail->is_varient) {
        //     if ($product->discount_type == 1) {
        //         $price_after_discount = $product->regular_price - $product->discount_price;
        //     } elseif ($product->discount_type == 2) {
        //         $price_after_discount = $product->regular_price - ($product->regular_price * $product->discount_price) / 100;
        //     }
        //     $Price = ($product->discount_price ? $price_after_discount : $product->regular_price);
        // }
        // if ($orderdetail->is_varient == 1) {
        //     $jsonData = $orderdetail->variation;
        //     $data = json_decode($jsonData, true);
        //     $attributeValues = [];
        //     foreach ($data as $item) {
        //         $attributeValues[] = $item['attribute_value'];
        //     }
        //     $concatenatedValue = implode('-', $attributeValues);
        //     $productStockId = ProductStock::where('product_id', $orderdetail->product_id)->get();
        //     foreach ($productStockId as $productStock) {
        //         if ($productStock->varient == $concatenatedValue) {
        //             $productStock->qty = $productStock->qty + $orderdetail->qty;
        //             $productStock->update();
        //             if ($product->discount_type == 1) {
        //                 $price_after_discount = $productStock->price - $product->discount_price;
        //             } elseif ($product->discount_type == 2) {
        //                 $price_after_discount = $productStock->price - ($productStock->price * $product->discount_price) / 100;
        //             }
        //             $Price = ($product->discount_price ? $price_after_discount : $productStock->price);
        //         }
        //     }
        // }
        $product->stock_qty = $product->stock_qty + $orderdetail->qty;
        $product->update();
        if ($orderdetail->gift_status == 0) {
            if ($order->due_amount > 0) {
                if ($order->due_amount < ($orderdetail->price * $orderdetail->qty)) {
                    $order->due_amount = 0;
                } else {
                    $order->due_amount = ($order->due_amount - ($orderdetail->price * $orderdetail->qty));
                }
            } else {
                $order->due_amount = 0;
            }
        }
        if ($order->due_amount > 0) {
            if ($order->payment_status == 'partial paid') {
                $order->payment_status = 'partial paid';
            } else {
                $order->payment_status = 'unpaid';
            }
        } else {
            $order->payment_status = 'paid';
        }
        if ($orderdetail->gift_status == 0) {
            $order->sub_total = ($order->sub_total - ($orderdetail->price * $orderdetail->qty));
            $order->grand_total = ($order->grand_total - ($orderdetail->price * $orderdetail->qty));
        } else {
            $order->giftPrice = ($order->giftPrice - ($orderdetail->price * $orderdetail->qty));
        }
        $order->totalbuyingPrice = ($order->totalbuyingPrice - ($buyingPrice * $orderdetail->qty));
        $order->update();
        $orderdetail->delete();
        $order->csv_amount = $order->due_amount;
        $order->update();
        $notification = array(
            'message' => 'Order Item Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function order_quantity_update(Request $request)
    {
        $orderdetail_id = $request->input('orderdetail_id');
        $order_detail = OrderDetail::where('id', $orderdetail_id)->first();
        $product_id = $request->input('product_id');
        $stock_id = $request->input('stock_id');
        $type = $request->input('type');
        $qty = $request->input('qty');
        $prod_check = Product::where('id', $product_id)->first();
        $buyingPrice = $prod_check->purchase_price;
        $prod_attr = ProductStock::where('id', $stock_id)->where('product_id', $product_id)->first();
        /*if ($stock_id) {
                if ($prod_check->discount_type == 1) {
                    $price_after_discount = $prod_attr->price - $prod_check->discount_price;
                } elseif ($prod_check->discount_type == 2) {
                    $price_after_discount = $prod_attr->price - ($prod_attr->price * $prod_check->discount_price) / 100;
                }
                $Price = ($prod_check->discount_price ? $price_after_discount : $prod_attr->price);
            } else {
                if ($prod_check->discount_type == 1) {
                    $price_after_discount = $prod_check->regular_price - $prod_check->discount_price;
                } elseif ($prod_check->discount_type == 2) {
                    $price_after_discount = $prod_check->regular_price - ($prod_check->regular_price * $prod_check->discount_price) / 100;
                }
                $Price = ($prod_check->discount_price ? $price_after_discount : $prod_check->regular_price);
            }*/
        if ($order_detail->gift_status == 0) {
            $Price = $order_detail->price;
        } else {
            $Price = 0;
        }
        $detail_price = $order_detail->price;
        if ($type == '+') {
            if ($stock_id == 'undefined' && $stock_id == null) {
                if ($prod_check->stock_qty == $qty) {
                    return response()->json(['error' => 'Product stock limited']);
                }
                if ($qty > $prod_check->stock_qty) {
                    return response()->json(['error' => 'Product stock limited']);
                }
            }
            if ($stock_id !== 'undefined' && $stock_id !== null) {
                if ($prod_attr->qty == $qty) {
                    return response()->json(['error' => 'Product stock limited']);
                }
                if ($prod_attr->qty < $qty) {
                    return response()->json(['error' => 'Product stock limited']);
                }
            }
            $newQty = $qty += 1;
        } else {
            if ($qty == 1) {
                return response()->json(['error' => 'Product stock limited']);
            }
            $newQty = $qty - 1;
        }
        return response()->json([
            'status' => 'success',
            'message' => "Quantity update successfully",
            'type' => $type,
            'price' => $Price,
            'detail_price' => $detail_price,
            'qty' => $newQty,
            'buyingPrice' => $buyingPrice,
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id == 1) {
            $this->validate($request, [
                'payment_method' => 'nullable'
            ]);
        } else {
            $this->validate($request, [
                'payment_method' => 'nullable',
                'division_id' => 'required',
                'district_id' => 'required',
                'upazilla_id' => 'required',
                'address' => 'required',
            ]);
        }
        $order->division_id = $request->division_id;
        $order->district_id = $request->district_id;
        $order->upazilla_id = $request->upazilla_id;
        $order->address = $request->address;
        $order->payment_method = $request->payment_method;
        if ($order->order_details) {
            foreach ($order->order_details as $key => $orderdetail) {
                $requestqty = $request['qty' . $key];
                // this is for gift
                if ($orderdetail->qty != $requestqty) {
                    if ($orderdetail->gift_status == 1) {
                        $order->giftPrice = (($order->giftPrice - ($orderdetail->price * $orderdetail->qty)) + ($orderdetail->price * $requestqty));
                    }
                }
                $product = Product::find($orderdetail->product_id);
                if ($orderdetail->is_varient == 1) {
                    $jsonData = $orderdetail->variation;
                    $data = json_decode($jsonData, true);
                    $attributeValues = [];
                    foreach ($data as $item) {
                        $attributeValues[] = $item['attribute_value'];
                    }
                    $concatenatedValue = implode('-', $attributeValues);
                    //  dd( $concatenatedValue);
                    $productStockId = ProductStock::where('product_id', $orderdetail->product_id)->get();
                    foreach ($productStockId as $productStock) {
                        if ($productStock->varient == $concatenatedValue) {
                            if ($orderdetail->qty > $requestqty) {
                                $updateqty = $orderdetail->qty - $requestqty;
                                $productStock->qty = $productStock->qty + $updateqty;
                                $productStock->update();
                                $orderdetail->qty = $orderdetail->qty - $updateqty;
                                $orderdetail->update();
                                $product->stock_qty = $product->stock_qty + $requestqty;
                                $product->update();
                            }
                            if ($orderdetail->qty < $requestqty) {
                                $updateqty = $requestqty - $orderdetail->qty;
                                $productStock->qty = $productStock->qty - $updateqty;
                                $productStock->update();
                                $orderdetail->qty = $orderdetail->qty + $updateqty;
                                $orderdetail->update();
                                $product->stock_qty = $product->stock_qty - $updateqty;
                                $product->update();
                            }
                        }
                    }
                } else {
                    if ($orderdetail->qty > $requestqty) {
                        $updateqty = $orderdetail->qty - $requestqty;
                        $orderdetail->qty = $orderdetail->qty - $updateqty;
                        $orderdetail->update();
                        $product->stock_qty = $product->stock_qty + $updateqty;
                        $product->update();
                    }
                    if ($orderdetail->qty < $requestqty) {
                        $updateqty = $requestqty - $orderdetail->qty;
                        $orderdetail->qty = $orderdetail->qty + $updateqty;
                        $orderdetail->update();
                        $product->stock_qty = $product->stock_qty - $updateqty;
                        $product->update();
                    }
                }
            }
        }
        $discount_total = ($request->sub_total - $request->discount);
        $total_ammount = ($discount_total + $order->shipping_charge + $request->others);
        $order->grand_total = $total_ammount - $order->coupon_discount ?? 0;
        $order->sub_total = $request->sub_total;
        $order->totalbuyingPrice = $request->totalbuyingPrice;
        $order->discount = $request->discount;
        $order->others = $request->others;
        $order->update();
        $due = $order->grand_total - $order->paid_amount;
        if ($due > 0) {
            $order->due_amount = $due;
        } else {
            $order->due_amount = 0.00;
        }
        $order->update();
        if ($order->due_amount > 0) {
            if ($order->payment_status == 'partial paid') {
                $order->payment_status = 'partial paid';
            } else {
                $order->payment_status = 'unpaid';
            }
        } else {
            $order->payment_status = 'paid';
        }
        $order->csv_amount = $order->due_amount;
        $order->update();
        Session::flash('success', ' Orders Information Updated Successfully');
        return redirect()->back();
    }
    public function order_itemAdd(Request $request)
    {
        if ($request->product_id) {
            $orderold = OrderDetail::where('order_id', $request->order_id)->where('product_id', $request->product_id)->first();
            $orderUpdate = Order::where('id', $request->order_id)->first();
            if ($orderold) {
                if ($orderold->is_varient == 1) {
                    $jsonData = $orderold->variation;
                    $data = json_decode($jsonData, true);
                    $attributeValues = [];
                    foreach ($data as $item) {
                        $attributeValues[] = $item['attribute_value'];
                    }
                    $concatenatedValue = implode('-', $attributeValues);
                    $productStockId = ProductStock::where('product_id', $orderold->product_id)->where('varient', $concatenatedValue)->first();
                    if ($productStockId->id == $request->stock_id) {
                        return response()->json(['status' => 'error',  'message' => "Product varient Already Added",]);
                    }
                } else {
                    if ($orderold->product_id == $request->product_id) {
                        return response()->json(['status' => 'error',  'message' => "Product  Already Added",]);
                    }
                }
            }
            $prod_check = Product::where('id', $request->product_id)->first();
            if ($prod_check->stock_qty == 0) {
                return response()->json(['status' => 'error',  'message' => "Product Stock Out",]);
            }
            $stock_id = $request->stock_id;
            $prod_attr = ProductStock::where('id', $stock_id)->where('product_id', $request->product_id)->first();
            if ($stock_id = null) {
                if ($prod_check->stock_qty == 0) {
                    return response()->json(['status' => 'error',  'message' => "Product Stock Out",]);
                }
            }
            if (isset($stock_id)) {
                if ($prod_check->id = $prod_attr->product_id) {
                    if ($prod_attr->qty == 0) {
                        return response()->json(['status' => 'error',  'message' => "Product Stock Out",]);
                    }
                }
            }
            $productadd = Product::find($request->product_id);
            $buyprice = $productadd->purchase_price;
            if ($productadd->is_varient == 1) {
                if ($productadd->discount_type == 1) {
                    $price_after_discount = $prod_attr->price - $productadd->discount_price;
                } elseif ($productadd->discount_type == 2) {
                    $price_after_discount = $prod_attr->price - ($prod_attr->price * $productadd->discount_price) / 100;
                }
                $Price = ($productadd->discount_price ? $price_after_discount : $prod_attr->price);
            } else {
                if ($productadd->discount_type == 1) {
                    $price_after_discount = $productadd->regular_price - $productadd->discount_price;
                } elseif ($productadd->discount_type == 2) {
                    $price_after_discount = $productadd->regular_price - ($productadd->regular_price * $productadd->discount_price) / 100;
                }
                $Price = ($productadd->discount_price ? $price_after_discount : $productadd->regular_price);
            }

            if ($productadd->vendor_id == 0) {
                $vendor_comission = 0.00;
                $vendor = 0;
            } else {
                if ($orderUpdate->order_by == 0) {
                    $vendor = Vendor::where('user_id', $productadd->vendor_id)->select('vendors.commission', 'user_id')->first();
                    $vendor_comission = ($Price * $vendor->commission) / 100;
                } else {
                    $vendor_comission = 0.00;
                    $vendor = 0;
                }
            }
            if ($productadd->is_varient == 1) {
                $stockproductvarient = $prod_attr->varient;
                $varientdivided = explode('-', $stockproductvarient);
                $variations = array();
                foreach ($varientdivided as $onevarient) {
                    $attribute_value = AttributeValue::where('value', $onevarient)->first();
                    if ($attribute_value) {
                        $attribute_id = $attribute_value->attribute_id;
                        $attribute = Attribute::find($attribute_id);
                        if ($attribute) {
                            $item = [
                                'attribute_name' => $attribute->name,
                                'attribute_value' => $attribute_value->value,
                            ];
                            $variations[] = $item;
                        }
                    }
                }
                OrderDetail::insert([
                    'order_id' => $request->order_id,
                    'product_id' => $productadd->id,
                    'product_name' => $productadd->name_en,
                    'is_varient' => 1,
                    'vendor_id' => $vendor->user_id ?? 0,
                    'v_comission' => $vendor_comission,
                    'variation' => json_encode($variations, JSON_UNESCAPED_UNICODE),
                    'qty' => 1,
                    'price' => $Price,
                    'created_at' => Carbon::now(),
                ]);
                if ($prod_attr) {
                    // dd($stock);
                    $prod_attr->qty = $prod_attr->qty - 1;
                    $prod_attr->save();
                }
            } else {
                OrderDetail::insert([
                    'order_id' => $request->order_id,
                    'product_id' => $productadd->id,
                    'product_name' => $productadd->name_en,
                    'is_varient' => 0,
                    'vendor_id' => $vendor->user_id ?? 0,
                    'v_comission' => $vendor_comission,
                    'qty' => 1,
                    'price' => $Price,
                    'created_at' => Carbon::now(),
                ]);
            }
            $productadd->stock_qty = $productadd->stock_qty - 1;
            $productadd->save();
            $orderUpdate->sub_total = $orderUpdate->sub_total + $Price;
            $orderUpdate->grand_total = $orderUpdate->grand_total + $Price;
            $orderUpdate->totalbuyingPrice = $orderUpdate->totalbuyingPrice + $buyprice;
            $orderUpdate->due_amount = $orderUpdate->due_amount + $Price;
            if ($orderUpdate->payment_status == 'partial paid') {
                $orderUpdate->payment_status = 'partial paid';
            } else {
                $orderUpdate->payment_status = 'unpaid';
            }
            $orderUpdate->save();
            // $orderUpdate->paid_amount = $orderUpdate->grand_total - $orderUpdate->due_amount ;
            $orderUpdate->csv_amount = $orderUpdate->due_amount;
            $orderUpdate->save();
            return response()->json(['status' => 'success',  'message' => "Product added To Order Successfully",]);
        }
    }
    public function order_product_packaged(Request $request)
    {
        $ids = $request->ids;
        $orders = Order::whereIn('id', $ids)->get();
        $status = 'Shipped';
        Order::whereIn('id', $ids)->update(['delivery_status' => $status]);
        $requests = [];
        foreach ($orders as $order) {
            $item['merchant_order_id'] = $order->invoice_no;
            $item['recipient_name'] = $order->name;
            $item['recipient_phone'] = $order->phone;
            $item['recipient_address'] = $order->address;
            $item['recipient_city'] = $order->division_id;
            $item['recipient_zone'] = $order->district_id;
            $item['recipient_area'] = $order->upazilla_id;
            $item['item_quantity'] = $order->total_items;
            $item['item_weight'] = 0.5;
            $item['amount_to_collect'] = $order->grand_total;

            array_push($requests, $item);
        }
        $data['orders'] = $requests;
        $pathao = new PathaoController;
        $resultData = $pathao->init($data);
        return response()->json([
            'status' => 'success',
            'message' => "Products are Shipped",
            'resultData' => $resultData,
        ]);
    }
    public function order_lock_package(Request $request)
    {
        $ids = $request->ids;
        $status = 1;
        Order::whereIn('id', $ids)->whereIn('delivery_status', ['Shipped'])->update(['lock_status' => $status]);
        return response()->json([
            'status' => 'success',
            'message' => "Lock Packaged",
        ]);
    }
    public function order_product_csv(Request $request)
    {
        $ids = $request->ids;
        $arrayIds = explode(',', $ids);
        $status = 'Shipped';
        Order::whereIn('id', $arrayIds)->update(['delivery_status' => $status]);
        return Excel::download(new OrdersExport($arrayIds), 'order.csv');
    }
    public function order_product_Print(Request $request)
    {
        $ids = $request->ids;
        $status = 1;
        $dalivary_status = 'Processing';
        Order::whereIn('id', $ids)->update(['packaging_status' => $status]);
        Order::whereIn('id', $ids)->where('delivery_status', 'pending')->update(['delivery_status' => $dalivary_status]);
        $orders = Order::whereIn('id', $ids)->get();
        $request->session()->put('orders', $orders);
        return response()->json([
            'status' => 'success',
            'message' => "Products are Printed",
            'orders' => $orders,
            'redirect_url' => route('multiple.orderprint.page', ['orders' => $orders]),
        ]);
    }
    public function multiple_order_print_page(Request $request)
    {
        $orders = $request->session()->get('orders');
        $request->session()->forget('orders');
        if ($orders) {
            return view('backend.invoices.multiple_order_print', compact('orders'));
        } else {
            return redirect()->back()->with('error', 'Order are Printed');
        }
    }

    public function order_gift_status(Request $request)
    {
        //dd('liza hi');
        $Id = $request->detail_id;
        $changeProduct = OrderDetail::where('id', $Id)->first();
        if ($changeProduct) {
            $order = Order::where('id', $changeProduct->order_id)->first();
            if ($changeProduct->gift_status == 0) {
                $changeProduct->gift_status = 1;
                $order->sub_total = $order->sub_total - ($changeProduct->price * $changeProduct->qty);
                $order->grand_total = $order->grand_total - ($changeProduct->price * $changeProduct->qty);
                $order->giftPrice = $order->giftPrice + ($changeProduct->price * $changeProduct->qty);
            } else {
                $changeProduct->gift_status = 0;
                $order->sub_total = $order->sub_total + ($changeProduct->price * $changeProduct->qty);
                $order->grand_total = $order->grand_total + ($changeProduct->price * $changeProduct->qty);
                $order->giftPrice = $order->giftPrice - ($changeProduct->price * $changeProduct->qty);
            }
            $order->save();
            $changeProduct->save();
            if ($order->due_amount > 0) {
                $order->due_amount = $order->grand_total - $order->paid_amount;
            } else {
                $order->due_amount = 0.00;
            }
            $order->save();
            $order->csv_amount = $order->due_amount;
            $order->save();
            return response()->json([
                'status' => 'success',
                'message' => "Gift Status Changed",
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => "Something Went Wrong",
        ]);
    }

    public function delivered_order(Request $request)
    {
        $date = $request->date;
        $startIndex = 0;
        $delivery_status = $request->delivery_status;
        $payment_status = $request->payment_status;
        $shipping_type = $request->shipping_type;
        $ordersQuery = $this->orders->where('delivery_status', 'Delivered')->where('show_hide', 1);
        if ($date) {
            $dateRange = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
        }
        if ($date) {
            $ordersQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $ordersQuery->where('payment_status', $payment_status);
        }
        if ($shipping_type) {
            $ordersQuery->where('shipping_type', $shipping_type);
        }
        if ($delivery_status) {
            $ordersQuery->where('delivery_status', $delivery_status);
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(100);
        return view('backend.sales.all_orders.deliverOrder.deliveredOrder', compact('orders', 'startIndex', 'date', 'delivery_status', 'payment_status', 'shipping_type'));
    }

    public function deleted_order(Request $request)
    {
        $date = $request->date;
        $startIndex = 0;
        $delivery_status = $request->delivery_status;
        $payment_status = $request->payment_status;
        $shipping_type = $request->shipping_type;
        $ordersQuery = $this->orders->where('show_hide', 0);
        if ($date) {
            $dateRange = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
        }
        if ($date) {
            $ordersQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $ordersQuery->where('payment_status', $payment_status);
        }
        if ($shipping_type) {
            $ordersQuery->where('shipping_type', $shipping_type);
        }
        if ($delivery_status) {
            $ordersQuery->where('delivery_status', $delivery_status);
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(100);
        return view('backend.sales.all_orders.deletedOrder.deletedOrder', compact('orders', 'startIndex', 'date', 'delivery_status', 'payment_status', 'shipping_type'));
    }

    public function ShowHideactive($id)
    {
        $orders = Order::find($id);
        $orders->show_hide = 1;
        $orders->save();

        $notification = array(
            'message' => 'Order Restore Successfully.',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function online_order_export()
    {
        return Excel::download(new OnlineExport, 'online-order.csv');
    }
    public function pos_order_export()
    {
        return Excel::download(new PosExport, 'pos-order.csv');
    }
    public function deliver_export()
    {
        return Excel::download(new DeliverExport, 'deliver-order.csv');
    }
    public function orderTrash_export()
    {
        return Excel::download(new OrderTrashBinExport, 'trash-order.csv');
    }

    // public function order_pagination(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $condition = $request->get('condition');
    //         $data = $request->search;
    //         $shipping_type = $request->shipping_type;
    //         $dateadd = $request->dateadd;
    //         $payment_status = $request->payment_status;
    //         $delivery_status = $request->delivery_status;
    //         // Prepare base query
    //         $query = $this->orders;
    //         if ($condition == 'posOrder') {
    //             $query->where('order_by', 1)->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered');
    //             $html = 'backend.sales.all_orders.posOrder.pos_order_products';
    //         } elseif ($condition == 'delivered') {
    //             $query->where('show_hide', 1)->where('delivery_status', 'Delivered');
    //             $html = 'backend.sales.all_orders.deliverOrder.deliveredproducts';
    //         } elseif ($condition == 'packageOrder') {
    //             $query->where('show_hide', 1)->where('packaging_status', 1)->where('delivery_status', '!=', 'Delivered');
    //             $html = 'backend.sales.all_orders.packageOrder.packaged_products';
    //         } elseif ($condition == 'onlineOrder') {
    //             $query->where('show_hide', 1)->where('order_by', 0)->where('delivery_status', '!=', 'Delivered');
    //             $html = 'backend.sales.all_orders.onlineOrder.online_order_products';
    //         } elseif ($condition == 'allVendorOrder') {
    //             $ordersQuery = $this->orders->where('delivery_status', '!=', 'Delivered')->where('show_hide', 1)->latest();
    //             $vendors = Vendor::pluck('user_id')->toArray();
    //             $orderIds = OrderDetail::whereIn('vendor_id', $vendors)->pluck('order_id');
    //             $query = $ordersQuery->whereIn('id', $orderIds);
    //             $html = 'backend.sales.all_orders.allVendor.all_vendor_products';
    //         } else {
    //             $query->where('show_hide', 0);
    //             $html = 'backend.sales.all_orders.deletedOrder.deletedproducts';
    //         }
    //         // Apply filters
    //         if ($data) {
    //             $query->where(function ($q) use ($data) {
    //                 $q->where('invoice_no', 'LIKE', '%' . $data . '%')
    //                     ->orWhere('grand_total', 'LIKE', '%' . $data . '%')
    //                     ->orWhere('name', 'LIKE', '%' . $data . '%')
    //                     ->orWhere('created_at', 'LIKE', '%' . $data . '%')
    //                     ->orWhere('phone', 'LIKE', '%' . $data . '%');
    //             });
    //         }
    //       /* if ($data) {
    //             $query->where(function ($q) use ($data) {
    //                 $q->where('invoice_no', '=', $data)
    //                     ->orWhere('grand_total', '=', $data)
    //                     ->orWhere('name', '=', $data)
    //                     ->orWhere('phone', '=', $data)
    //                     ->orWhere('created_at', '=', $data);
    //             });
    //         }*/
    //         if ($dateadd) {
    //             $dateRange = explode(" - ", $dateadd);
    //             $startDate = date('Y-m-d', strtotime($dateRange[0]));
    //             $endDate = date('Y-m-d', strtotime($dateRange[1]));
    //             $query->where(function ($q) use ($startDate, $endDate) {
    //                 $q->whereBetween('created_at', [$startDate, $endDate])
    //                     ->orWhereDate('created_at', $startDate);
    //             });
    //         }
    //         if ($payment_status) {
    //             $query->where('payment_status', $payment_status);
    //         }
    //         if ($shipping_type) {
    //             $query->where('shipping_type', $shipping_type);
    //         }
    //         if ($delivery_status) {
    //             $query->where('delivery_status', $delivery_status);
    //         }
    //         $orders = $query->orderBy('created_at', 'desc')->paginate(100);
    //         $page = $request->input('page', 1);
    //         $startIndex = ($page - 1) * 100;
    //         return view($html, compact('orders', 'startIndex'))->render();
    //     }
    // }

    // public function pro_search(Request $request)
    // {
    //     $data = $request->search;
    //     $type = $request->type;
    //     $shipping_type = $request->shipping_type;
    //     $dateadd = $request->dateadd;
    //     $payment_status = $request->payment_status;
    //     $delivery_status = $request->delivery_status;

    //     $query = $this->orders->newQuery();
    //     if ($dateadd) {
    //         $dateRange = explode(" - ", $dateadd);
    //         $startDate = date('Y-m-d', strtotime($dateRange[0]));
    //         $endDate = date('Y-m-d', strtotime($dateRange[1]));
    //         $query->where(function ($q) use ($startDate, $endDate) {
    //             $q->whereBetween('created_at', [$startDate, $endDate])
    //                 ->orWhereDate('created_at', $startDate);
    //         });
    //     }
    //     if ($payment_status) {
    //         $query->where('payment_status', $payment_status);
    //     }

    //     if ($shipping_type) {
    //         $query->where('shipping_type', $shipping_type);
    //     }

    //     if ($delivery_status) {
    //         $query->where('delivery_status', $delivery_status);
    //     }
    //     // if ($data) {
    //     //     $query->where(function ($q) use ($data) {
    //     //         $q->where('invoice_no', 'LIKE', '%' . $data . '%')
    //     //             ->orwhere('grand_total', 'LIKE', '%' . $data . '%')
    //     //             ->orWhere('name', 'LIKE', '%' . $data . '%')
    //     //             ->orWhere('phone', 'LIKE', '%' . $data . '%')
    //     //             ->orWhere('created_at', 'LIKE', '%' . $data . '%');
    //     //     });
    //     // }
       


    
    //   /*  if ($data) {
    //         $query->where(function ($q) use ($data) {
    //             $q->where('invoice_no', '=', $data)
    //                 ->orWhere('grand_total', '=', $data)
    //                 ->orWhere('name', '=', $data)
    //                 ->orWhere('phone', '=', $data)
    //                 ->orWhere('created_at', '=', $data);
    //         });
    //     }*/
    //     switch ($type) {
    //         case 'posOrder':
    //             $query->where('order_by', 1)->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered');
    //             $html = 'backend.sales.all_orders.posOrder.pos_order_products';
    //             break;
    //         case 'delivered':
    //             $query->where('show_hide', 1)->where('delivery_status', 'Delivered');
    //             $html = 'backend.sales.all_orders.deliverOrder.deliveredproducts';
    //             break;
    //         case 'packageOrder':
    //             $query->where('show_hide', 1)->where('packaging_status', 1)->where('delivery_status', '!=', 'Delivered');
    //             $html = 'backend.sales.all_orders.packageOrder.packaged_products';
    //             break;
    //         case 'onlineOrder':
    //             $query->where('show_hide', 1)->where('order_by', 0)->where('delivery_status', '!=', 'Delivered');
    //             $html = 'backend.sales.all_orders.onlineOrder.online_order_products';
    //             break;
    //         case 'allVendorOrder':
    //             $ordersQuery = $this->orders->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered')->where('show_hide', 1)->latest();
    //             $vendors = Vendor::pluck('user_id')->toArray();
    //             $orderIds = OrderDetail::whereIn('vendor_id', $vendors)->pluck('order_id');
    //             $query = $ordersQuery->whereIn('id', $orderIds);

    //             $html = 'backend.sales.all_orders.allVendor.all_vendor_products';
    //             break;
    //         default:
    //             $query->where('show_hide', 0);
    //             $html = 'backend.sales.all_orders.deletedOrder.deletedproducts';
    //             break;
    //     }
    //     if ($data) {
    //         $query->where(function ($q) use ($data) {
    //             $q->where('invoice_no', 'LIKE', '%' . $data . '%')
    //                 ->orwhere('grand_total', 'LIKE', '%' . $data . '%')
    //                 ->orWhere('name', 'LIKE', '%' . $data . '%')
    //                 ->orWhere('phone', 'LIKE', '%' . $data . '%')
    //                 ->orWhere('created_at', 'LIKE', '%' . $data . '%');
    //         });
    //     }
    //     $orders = $query->orderBy('created_at', 'desc')->paginate(100);
    //     $page = $request->input('page', 1);
    //     $startIndex = ($page - 1) * 100;
    //     return view($html, compact('orders', 'startIndex'));
    // }
    
    
       //pos order
    public function Posorder_pro_search(Request $request)
    {
        $data = $request->search;
        $type = $request->type;
        $shipping_type = $request->shipping_type;
        $dateadd = $request->dateadd;
        $payment_status = $request->payment_status;
        $delivery_status = $request->delivery_status;
        $query = $this->orders->newQuery();
        $query->where('order_by', 1)->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered');
        if ($dateadd) {
            $dateRange = explode(" - ", $dateadd);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $query->where('payment_status', $payment_status);
        }

        if ($shipping_type) {
            $query->where('shipping_type', $shipping_type);
        }

        if ($delivery_status) {
            $query->where('delivery_status', $delivery_status);
        }
        if ($data) {
            $query->where(function ($q) use ($data) {
                $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                    ->orwhere('grand_total', 'LIKE', '%' . $data . '%')
                    ->orWhere('name', 'LIKE', '%' . $data . '%')
                    ->orWhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $data . '%');
            });
        }
        $html = 'backend.sales.all_orders.posOrder.pos_order_products';

        $orders = $query->orderBy('created_at', 'desc')->paginate(100);
        $page = $request->input('page', 1);
        $startIndex = ($page - 1) * 100;
        return view($html, compact('orders', 'startIndex'));
    }
    public function Posorder_pagination(Request $request)
    {
        if ($request->ajax()) {
            $condition = $request->get('condition');
            $data = $request->search;
            $shipping_type = $request->shipping_type;
            $dateadd = $request->dateadd;
            $payment_status = $request->payment_status;
            $delivery_status = $request->delivery_status;
            // Prepare base query
            $query = $this->orders->where('order_by', 1)->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered');
            $html = 'backend.sales.all_orders.posOrder.pos_order_products';
            // Apply filters
            if ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                        ->orWhere('grand_total', 'LIKE', '%' . $data . '%')
                        ->orWhere('name', 'LIKE', '%' . $data . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $data . '%')
                        ->orWhere('phone', 'LIKE', '%' . $data . '%');
                });
            }
            if ($dateadd) {
                $dateRange = explode(" - ", $dateadd);
                $startDate = date('Y-m-d', strtotime($dateRange[0]));
                $endDate = date('Y-m-d', strtotime($dateRange[1]));
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->orWhereDate('created_at', $startDate);
                });
            }
            if ($payment_status) {
                $query->where('payment_status', $payment_status);
            }
            if ($shipping_type) {
                $query->where('shipping_type', $shipping_type);
            }
            if ($delivery_status) {
                $query->where('delivery_status', $delivery_status);
            }
            $orders = $query->orderBy('created_at', 'desc')->paginate(100);
            $page = $request->input('page', 1);
            $startIndex = ($page - 1) * 100;
            return view($html, compact('orders', 'startIndex'))->render();
        }
    }
    //Online order
    public function Onlineorder_pro_search(Request $request)
    {
        $data = $request->search;
        $type = $request->type;
        $shipping_type = $request->shipping_type;
        $dateadd = $request->dateadd;
        $payment_status = $request->payment_status;
        $delivery_status = $request->delivery_status;
        $query = $this->orders->newQuery();
        $query->where('show_hide', 1)->where('order_by', 0)->where('delivery_status', '!=', 'Delivered');
        if ($dateadd) {
            $dateRange = explode(" - ", $dateadd);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $query->where('payment_status', $payment_status);
        }

        if ($shipping_type) {
            $query->where('shipping_type', $shipping_type);
        }

        if ($delivery_status) {
            $query->where('delivery_status', $delivery_status);
        }
        if ($data) {
            $query->where(function ($q) use ($data) {
                $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                    ->orwhere('grand_total', 'LIKE', '%' . $data . '%')
                    ->orWhere('name', 'LIKE', '%' . $data . '%')
                    ->orWhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $data . '%');
            });
        }
        $html = 'backend.sales.all_orders.onlineOrder.online_order_products';

        $orders = $query->orderBy('created_at', 'desc')->paginate(100);
        $page = $request->input('page', 1);
        $startIndex = ($page - 1) * 100;
        return view($html, compact('orders', 'startIndex'));
    }
    public function Onlineorder_pagination(Request $request)
    {
        if ($request->ajax()) {
            $condition = $request->get('condition');
            $data = $request->search;
            $shipping_type = $request->shipping_type;
            $dateadd = $request->dateadd;
            $payment_status = $request->payment_status;
            $delivery_status = $request->delivery_status;
            // Prepare base query
            $query = $this->orders->where('show_hide', 1)->where('order_by', 0)->where('delivery_status', '!=', 'Delivered');
            $html = 'backend.sales.all_orders.onlineOrder.online_order_products';
            // Apply filters
            if ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                        ->orWhere('grand_total', 'LIKE', '%' . $data . '%')
                        ->orWhere('name', 'LIKE', '%' . $data . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $data . '%')
                        ->orWhere('phone', 'LIKE', '%' . $data . '%');
                });
            }
            if ($dateadd) {
                $dateRange = explode(" - ", $dateadd);
                $startDate = date('Y-m-d', strtotime($dateRange[0]));
                $endDate = date('Y-m-d', strtotime($dateRange[1]));
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->orWhereDate('created_at', $startDate);
                });
            }
            if ($payment_status) {
                $query->where('payment_status', $payment_status);
            }
            if ($shipping_type) {
                $query->where('shipping_type', $shipping_type);
            }
            if ($delivery_status) {
                $query->where('delivery_status', $delivery_status);
            }
            $orders = $query->orderBy('created_at', 'desc')->paginate(100);
            $page = $request->input('page', 1);
            $startIndex = ($page - 1) * 100;
            return view($html, compact('orders', 'startIndex'))->render();
        }
    }
    //Package order
    public function Packageorder_pro_search(Request $request)
    {
        $data = $request->search;
        $type = $request->type;
        $shipping_type = $request->shipping_type;
        $dateadd = $request->dateadd;
        $payment_status = $request->payment_status;
        $delivery_status = $request->delivery_status;
        $query = $this->orders->newQuery();
        $query->where('show_hide', 1)->where('packaging_status', 1)->where('delivery_status', '!=', 'Delivered');
        if ($dateadd) {
            $dateRange = explode(" - ", $dateadd);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $query->where('payment_status', $payment_status);
        }

        if ($shipping_type) {
            $query->where('shipping_type', $shipping_type);
        }

        if ($delivery_status) {
            $query->where('delivery_status', $delivery_status);
        }
        if ($data) {
            $query->where(function ($q) use ($data) {
                $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                    ->orwhere('grand_total', 'LIKE', '%' . $data . '%')
                    ->orWhere('name', 'LIKE', '%' . $data . '%')
                    ->orWhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $data . '%');
            });
        }
        $html = 'backend.sales.all_orders.packageOrder.packaged_products';

        $orders = $query->orderBy('created_at', 'desc')->paginate(100);
        $page = $request->input('page', 1);
        $startIndex = ($page - 1) * 100;
        return view($html, compact('orders', 'startIndex'));
    }
    public function Packageorder_pagination(Request $request)
    {
        if ($request->ajax()) {
            $condition = $request->get('condition');
            $data = $request->search;
            $shipping_type = $request->shipping_type;
            $dateadd = $request->dateadd;
            $payment_status = $request->payment_status;
            $delivery_status = $request->delivery_status;
            // Prepare base query
            $query = $this->orders->where('show_hide', 1)->where('packaging_status', 1)->where('delivery_status', '!=', 'Delivered');
            $html = 'backend.sales.all_orders.packageOrder.packaged_products';
            // Apply filters
            if ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                        ->orWhere('grand_total', 'LIKE', '%' . $data . '%')
                        ->orWhere('name', 'LIKE', '%' . $data . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $data . '%')
                        ->orWhere('phone', 'LIKE', '%' . $data . '%');
                });
            }
            if ($dateadd) {
                $dateRange = explode(" - ", $dateadd);
                $startDate = date('Y-m-d', strtotime($dateRange[0]));
                $endDate = date('Y-m-d', strtotime($dateRange[1]));
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->orWhereDate('created_at', $startDate);
                });
            }
            if ($payment_status) {
                $query->where('payment_status', $payment_status);
            }
            if ($shipping_type) {
                $query->where('shipping_type', $shipping_type);
            }
            if ($delivery_status) {
                $query->where('delivery_status', $delivery_status);
            }
            $orders = $query->orderBy('created_at', 'desc')->paginate(100);
            $page = $request->input('page', 1);
            $startIndex = ($page - 1) * 100;
            return view($html, compact('orders', 'startIndex'))->render();
        }
    }
     //Deliver order
    public function Deliverorder_pro_search(Request $request)
    {
        $data = $request->search;
        $type = $request->type;
        $shipping_type = $request->shipping_type;
        $dateadd = $request->dateadd;
        $payment_status = $request->payment_status;
        $delivery_status = $request->delivery_status;
        $query = $this->orders->newQuery();
        $query->where('show_hide', 1)->where('delivery_status', 'Delivered');
        $html = 'backend.sales.all_orders.deliverOrder.deliveredproducts';
        if ($dateadd) {
            $dateRange = explode(" - ", $dateadd);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $query->where('payment_status', $payment_status);
        }

        if ($shipping_type) {
            $query->where('shipping_type', $shipping_type);
        }

        if ($delivery_status) {
            $query->where('delivery_status', $delivery_status);
        }
        if ($data) {
            $query->where(function ($q) use ($data) {
                $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                    ->orwhere('grand_total', 'LIKE', '%' . $data . '%')
                    ->orWhere('name', 'LIKE', '%' . $data . '%')
                    ->orWhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $data . '%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(100);
        $page = $request->input('page', 1);
        $startIndex = ($page - 1) * 100;
        return view($html, compact('orders', 'startIndex'));
    }
    public function Deliverorder_pagination(Request $request)
    {
        if ($request->ajax()) {
            $condition = $request->get('condition');
            $data = $request->search;
            $shipping_type = $request->shipping_type;
            $dateadd = $request->dateadd;
            $payment_status = $request->payment_status;
            $delivery_status = $request->delivery_status;
            // Prepare base query
            $query = $this->orders->where('show_hide', 1)->where('delivery_status', 'Delivered');
            $html = 'backend.sales.all_orders.deliverOrder.deliveredproducts';
            // Apply filters
            if ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                        ->orWhere('grand_total', 'LIKE', '%' . $data . '%')
                        ->orWhere('name', 'LIKE', '%' . $data . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $data . '%')
                        ->orWhere('phone', 'LIKE', '%' . $data . '%');
                });
            }
            if ($dateadd) {
                $dateRange = explode(" - ", $dateadd);
                $startDate = date('Y-m-d', strtotime($dateRange[0]));
                $endDate = date('Y-m-d', strtotime($dateRange[1]));
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->orWhereDate('created_at', $startDate);
                });
            }
            if ($payment_status) {
                $query->where('payment_status', $payment_status);
            }
            if ($shipping_type) {
                $query->where('shipping_type', $shipping_type);
            }
            if ($delivery_status) {
                $query->where('delivery_status', $delivery_status);
            }
            $orders = $query->orderBy('created_at', 'desc')->paginate(100);
            $page = $request->input('page', 1);
            $startIndex = ($page - 1) * 100;
            return view($html, compact('orders', 'startIndex'))->render();
        }
    }
    //Delete order
    public function Deleteorder_pro_search(Request $request)
    {
        $data = $request->search;
        $type = $request->type;
        $shipping_type = $request->shipping_type;
        $dateadd = $request->dateadd;
        $payment_status = $request->payment_status;
        $delivery_status = $request->delivery_status;
        $query = $this->orders->newQuery();
        $query->where('show_hide', 0);
        if ($dateadd) {
            $dateRange = explode(" - ", $dateadd);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $query->where('payment_status', $payment_status);
        }

        if ($shipping_type) {
            $query->where('shipping_type', $shipping_type);
        }

        if ($delivery_status) {
            $query->where('delivery_status', $delivery_status);
        }
        if ($data) {
            $query->where(function ($q) use ($data) {
                $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                    ->orwhere('grand_total', 'LIKE', '%' . $data . '%')
                    ->orWhere('name', 'LIKE', '%' . $data . '%')
                    ->orWhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $data . '%');
            });
        }
        $html = 'backend.sales.all_orders.deletedOrder.deletedproducts';

        $orders = $query->orderBy('created_at', 'desc')->paginate(100);
        $page = $request->input('page', 1);
        $startIndex = ($page - 1) * 100;
        return view($html, compact('orders', 'startIndex'));
    }
    public function Deleteorder_pagination(Request $request)
    {
        if ($request->ajax()) {
            $condition = $request->get('condition');
            $data = $request->search;
            $shipping_type = $request->shipping_type;
            $dateadd = $request->dateadd;
            $payment_status = $request->payment_status;
            $delivery_status = $request->delivery_status;
            // Prepare base query
            $query = $this->orders->where('show_hide', 0);
            $html = 'backend.sales.all_orders.deletedOrder.deletedproducts';
            // Apply filters
            if ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                        ->orWhere('grand_total', 'LIKE', '%' . $data . '%')
                        ->orWhere('name', 'LIKE', '%' . $data . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $data . '%')
                        ->orWhere('phone', 'LIKE', '%' . $data . '%');
                });
            }
            if ($dateadd) {
                $dateRange = explode(" - ", $dateadd);
                $startDate = date('Y-m-d', strtotime($dateRange[0]));
                $endDate = date('Y-m-d', strtotime($dateRange[1]));
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->orWhereDate('created_at', $startDate);
                });
            }
            if ($payment_status) {
                $query->where('payment_status', $payment_status);
            }
            if ($shipping_type) {
                $query->where('shipping_type', $shipping_type);
            }
            if ($delivery_status) {
                $query->where('delivery_status', $delivery_status);
            }
            $orders = $query->orderBy('created_at', 'desc')->paginate(100);
            $page = $request->input('page', 1);
            $startIndex = ($page - 1) * 100;
            return view($html, compact('orders', 'startIndex'))->render();
        }
    }
    //allVendororder order
    public function allVendororder_pro_search(Request $request)
    {
        $data = $request->search;
        $type = $request->type;
        $shipping_type = $request->shipping_type;
        $dateadd = $request->dateadd;
        $payment_status = $request->payment_status;
        $delivery_status = $request->delivery_status;
        $query = $this->orders->newQuery();
        $ordersQuery = $this->orders->where('show_hide', 1)->where('delivery_status', '!=', 'Delivered')->where('show_hide', 1)->latest();
        $vendors = Vendor::pluck('user_id')->toArray();
        $orderIds = OrderDetail::whereIn('vendor_id', $vendors)->pluck('order_id');
        $query = $ordersQuery->whereIn('id', $orderIds);
        $html = 'backend.sales.all_orders.allVendor.all_vendor_products';
        if ($dateadd) {
            $dateRange = explode(" - ", $dateadd);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($payment_status) {
            $query->where('payment_status', $payment_status);
        }

        if ($shipping_type) {
            $query->where('shipping_type', $shipping_type);
        }

        if ($delivery_status) {
            $query->where('delivery_status', $delivery_status);
        }
        if ($data) {
            $query->where(function ($q) use ($data) {
                $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                    ->orwhere('grand_total', 'LIKE', '%' . $data . '%')
                    ->orWhere('name', 'LIKE', '%' . $data . '%')
                    ->orWhere('phone', 'LIKE', '%' . $data . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $data . '%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(100);
        $page = $request->input('page', 1);
        $startIndex = ($page - 1) * 100;
        return view($html, compact('orders', 'startIndex'));
    }
    public function allVendororder_pagination(Request $request)
    {
        if ($request->ajax()) {
            $condition = $request->get('condition');
            $data = $request->search;
            $shipping_type = $request->shipping_type;
            $dateadd = $request->dateadd;
            $payment_status = $request->payment_status;
            $delivery_status = $request->delivery_status;
            // Prepare base query
            $ordersQuery = $this->orders->where('delivery_status', '!=', 'Delivered')->where('show_hide', 1)->latest();
            $vendors = Vendor::pluck('user_id')->toArray();
            $orderIds = OrderDetail::whereIn('vendor_id', $vendors)->pluck('order_id');
            $query = $ordersQuery->whereIn('id', $orderIds);
            $html = 'backend.sales.all_orders.allVendor.all_vendor_products';
            // Apply filters
            if ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('invoice_no', 'LIKE', '%' . $data . '%')
                        ->orWhere('grand_total', 'LIKE', '%' . $data . '%')
                        ->orWhere('name', 'LIKE', '%' . $data . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $data . '%')
                        ->orWhere('phone', 'LIKE', '%' . $data . '%');
                });
            }
            if ($dateadd) {
                $dateRange = explode(" - ", $dateadd);
                $startDate = date('Y-m-d', strtotime($dateRange[0]));
                $endDate = date('Y-m-d', strtotime($dateRange[1]));
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->orWhereDate('created_at', $startDate);
                });
            }
            if ($payment_status) {
                $query->where('payment_status', $payment_status);
            }
            if ($shipping_type) {
                $query->where('shipping_type', $shipping_type);
            }
            if ($delivery_status) {
                $query->where('delivery_status', $delivery_status);
            }
            $orders = $query->orderBy('created_at', 'desc')->paginate(100);
            $page = $request->input('page', 1);
            $startIndex = ($page - 1) * 100;
            return view($html, compact('orders', 'startIndex'))->render();
        }
    }
}