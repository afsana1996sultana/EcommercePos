<?php

namespace App\Http\Controllers\Backend;

use Image;
use Exception;
use App\Models\Product;
use App\Models\Campaing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CampaingProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CampaingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaings = Campaing::latest()->get();
        return view('backend.campaing.index',compact('campaings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.campaing.create');
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'name_en' => 'required',
            'campaing_image' => 'required',
            'date_range' => 'required',
            'products' => 'required',
        ]);

        if($request->hasfile('campaing_image')){
            $image = $request->file('campaing_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->save('upload/campaing/'.$name_gen);
            $save_url = 'upload/campaing/'.$name_gen;
        }else{
            $save_url = '';
        }
        $oldCampaignCount=Campaing::count();
        if ($oldCampaignCount >= 1) {
            $notification = array(
                'message' => 'One Campaign already Exist',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        $campaing = new Campaing();

        $date_var = explode(" - ", $request->date_range);
        // dd($date_var[0]);
        $campaing->flash_start  = $date_var[0];
        $campaing->flash_end    = $date_var[1];

        $campaing->name_en = $request->name_en;
        if($request->name_bn == ''){
            $campaing->name_bn = $request->name_en;
        }else{
            $campaing->name_bn = $request->name_bn;
        }
        $campaing->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($request->name_en)));

        if($request->status == Null){
            $request->status = 0;
        }
        $campaing->status = $request->status;
        $campaing->campaing_image = $save_url;
        $campaing->created_at = Carbon::now();

        // dd($campaing);
        if($campaing->save()){
            foreach ($request->products as $key => $product) {
                $campaing_product = new CampaingProduct;
                $campaing_product->campaing_id = $campaing->id;
                $campaing_product->product_id = $product;
                $campaing_product->discount_price = $request['discount_'.$product];
                $campaing_product->discount_type = $request['discount_type_'.$product];
                $root_product = Product::findOrFail($product);
                $root_product->discount_price = $request['discount_'.$product];
                $root_product->discount_type = $request['discount_type_'.$product];
                $campaing_product->save();
                $root_product->save();
            }
            $notification = array(
                'message' => 'Campaing Inserted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('campaing.index')->with($notification);
        }
        else{
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $campaing = Campaing::find($id);
        return view('backend.campaing.edit',compact('campaing'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name_en' => 'required',
            'date_range' => 'required',
            'products' => 'required',
        ]);

        $campaing = Campaing::find($id);
        //Campaing Photo Update
        if($request->hasfile('campaing_image')){
            try {
                if(file_exists($campaing->campaing_image)){
                    unlink($campaing->campaing_image);
                }
            } catch (Exception $e) {

            }
            $campaing_image = $request->campaing_image;
            $img = time().$campaing_image->getClientOriginalName();
            $campaing_image->move('upload/campaing/',$img);
            $campaing->campaing_image = 'upload/campaing/'.$img;
        }else{
            $img =$campaing->campaing_image  ??  '';
        }

        // Campaing table update
        $date_var = explode(" - ", $request->date_range);
        // dd($date_var[0]);
        $campaing->flash_start  = $date_var[0];
        $campaing->flash_end    = $date_var[1];

        $campaing->name_en = $request->name_en;
        if($request->name_bn == ''){
            $campaing->name_bn = $request->name_en;
        }else{
            $campaing->name_bn = $request->name_bn;
        }
        $campaing->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($request->name_en)));

        $campaing->status = $campaing->status;
        $campaing->updated_at = Carbon::now();

        // dd($campaing);
        foreach ($campaing->campaing_products as  $campaing_product) {
            $root_product = Product::findOrFail($campaing_product->product_id);
            $root_product->discount_price = $root_product->old_discount_price;
            $root_product->discount_type = $root_product->old_discount_type;
            $root_product->save();
            $campaing_product->delete();
        }
        if($campaing->save()){
            foreach ($request->products as $key => $product) {
                $campaing_product = new CampaingProduct;
                $campaing_product->campaing_id = $campaing->id;
                $campaing_product->product_id = $product;
                $campaing_product->discount_price = $request['discount_'.$product];
                $campaing_product->discount_type = $request['discount_type_'.$product];
                $root_product = Product::findOrFail($product);
                $root_product->discount_price = $request['discount_'.$product];
                $root_product->discount_type = $request['discount_type_'.$product];
                // $root_product->discount_start_date = $date_var[0];
                // $root_product->discount_end_date   = $date_var[1];
                $campaing_product->save();
                $root_product->save();
            }

            $notification = array(
                'message' => 'Campaing Update Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('campaing.index')->with($notification);
        }
        else{
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function destroy($id)
    {
        $campaing = Campaing::findOrFail($id);
        try {
            if(file_exists($campaing->campaing_image)){
                unlink($campaing->campaing_image);
            }
        } catch (Exception $e) {

        }

        $campaing->delete();

        $notification = array(
            'message' => 'Campaing Deleted Successfully.',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /*=================== Start Active/Inactive Methoed ===================*/
     public function active($id){
        $now = Carbon::now();
        $campaing = Campaing::find($id);
        $campaing->status = 1;
        $campaing->save();
        $flash_end = Carbon::parse($campaing->flash_end);
        if($flash_end->gt($now)){
            $campaignProducts = CampaingProduct::where('campaing_id', $campaing->id)->get();
            foreach ($campaignProducts as $campaignProduct){
                $root_product = Product::findOrFail($campaignProduct->product_id);
                $root_product->discount_price = $campaignProduct->discount_price;
                $root_product->discount_type = $campaignProduct->discount_type;
                $root_product->save();
            }
        }
        Session::flash('success','Campaing Active Successfully.');
        return redirect()->back();
    }

    public function inactive($id){
        $now = Carbon::now();
        $campaing = Campaing::find($id);
        $campaing->status = 0;
        $campaing->save();
        $flash_end = Carbon::parse($campaing->flash_end);
        $campaignProducts = CampaingProduct::where('campaing_id', $campaing->id)->get();
        foreach ($campaignProducts as $campaignProduct){
            $root_product = Product::findOrFail($campaignProduct->product_id);
            $root_product->discount_price = $root_product->old_discount_price;
            $root_product->discount_type = $root_product->old_discount_type;
            $root_product->save();
        }
        Session::flash('warning','Campaing Inactive Successfully.');
        return redirect()->back();
    }

    // campaing product show
    public function product_discount(Request $request){
        $product_ids = $request->product_ids;

        // dd($product_ids);
        return view('backend.campaing.flash_deal_discount', compact('product_ids'));
    }
    // campaing product Edit
    public function product_discount_edit(Request $request){
        $product_ids = $request->product_ids;
        $campaing_id = $request->campaing_id;

        return view('backend.campaing.flash_deal_discount_edit', compact('product_ids', 'campaing_id'));
    }

}