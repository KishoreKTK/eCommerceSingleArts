<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Attributes;
use App\Models\Product;
use App\Models\Categories;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductStocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\ProductTrait;

class SellerProductController extends Controller
{
    use ProductTrait;

    public function ProductList(){
        $attributes     =   $this->AttributeList();
        $products       =   Product::select('products.*','categories.name as categoryname'
                            ,'product_stocks.product_price','product_stocks.quantities')
                            // ,'products.total_qty as quantities')
                            ->join('categories','products.category_id','=','categories.id')
                            ->join('sellers','products.seller_id','=','sellers.id')
                            ->join('product_stocks','product_stocks.product_id','=','products.id')
                            ->where('product_stocks.price_type','1')
                            ->where('categories.is_active','1')
                            ->where('products.seller_id',Auth::guard('seller')->user()->id)
                            ->orderby('products.id','desc')->paginate('25');

        return view('dashboard.seller.products.index',compact('products'));
    }

    public function AddProductPage()
    {
        if(request()->has('category_id')){
            $category_id    = request()->category_id;
            Categories::findorfail($category_id);
        }
        else{
            $category_id    = 0;
        }
        if(request()->ajax())
        {
            return view('dashboard.commonly_used.product_form_attribute_section')
                    ->with(['attributelist' =>  $this->SelectAttributeList($category_id)]);
        }
        return view('dashboard.seller.products.AddProduct')
                ->with([
                    'categorylist'  =>  $this->SelectCategoryList(),
                    'attributelist' =>  $this->SelectAttributeList($category_id)
                ]);
    }

    public function GetAttributes(){
        try {
            $category_id    = request()->category_id;
            $result     =   ['status'=>true, 'data'=>$this->SelectAttributeList($category_id)];
        } catch (\Throwable $th) {
            $result     =   ['status'=>false, 'message'=>$th->getMessage()];
        }
        return response()->json($result);
    }

    public function CreateProduct(Request $request)
    {
        try
        {
            // Insert Table Main Records
            $product_insert_det = $request->except('_token','product_attr','image','banner','price_stock');
            $banner_images      = $request->banner;
            $product_attr       = $request->product_attr;
            $price_stock        = $request->price_stock;

            // File Upload Path
            $seller             =  DB::table('sellers')->select('sellername')->where('id',$request->seller_id)->first();
            $sellername         =  $seller->sellername;
            $product_img        = 'Uploads/Sellers/'.str_replace(' ', '_', $sellername).'/Products/'.str_replace(' ', '_', $request->name).'/';
            $image_name         = time() . '.'.$request->file('image')->getClientOriginalExtension();
            $banner_img_url     = $product_img.'banner/';


            // Product Table Additional Data
            $product_insert_det['image']        = $product_img.$image_name;
            $product_insert_det['status']       = '1';
            $product_insert_det['created_at']   = $this->currentdatetime();
            $product_insert_det['updated_at']   = $this->currentdatetime();

            // Move Image to Folder
            $request->image->move(public_path($product_img), $image_name);
            $product_id     = Product::insertGetId($product_insert_det);
            if($product_id)
            {
                // Banner Image Storage Data
                $product_banner = $this->InsertProductBannerImages($product_id,$banner_images, $banner_img_url);
                ProductImage::insert($product_banner);

                // Product Attribute Data
                $product_attr_insert_data = $this->InsertProductAttributes($product_id,$product_attr);
                ProductAttribute::insert($product_attr_insert_data);

                // Product Stock Data
                $product_stock  = $this->InserProductStock($product_id, $price_stock);
                ProductStocks::insert($product_stock);
            }
            return back()->with('success','New Product Added');
        }
        catch(\Exception $e){
            return back()->with('error',$e->getMessage());
        }
    }

    public function ProductDetails($id)
    {
        $result =   $this->GetProductDetail($id);
        if($result['status'] == true)
            return view('dashboard.seller.products.productviewdet')->with($result['data']);
        else
            return redirect()->back()->with('error',$result['message']);
    }

    public function CutomPrice($id){
        $result =   $this->GetCutomPrices($id);
        if($result['status'] == true)
            return view('dashboard.seller.products.CustomPrice')->with($result['data']);
        else
            return redirect()->back()->with('error',$result['message']);
    }

    public function PostCustomPrice()
    {
        $input  =   request()->all();
        // dd($input);
        $result =   $this->AddCustomPrice($input);
        // dd($result);
        if($result['status'] == true)
            return redirect()->back()->with('success',$result['message']);
        else
            return redirect()->back()->with('error',$result['message']);

        // // print("<pre>");
        // // print_r(request()->all());
        // // $input = request()->all();
        // $price_combo = request()->price_combo;

    }
    function featuredproducts(){
        $dt 			=   new \DateTime();
		$datetime		=   $dt->format('Y-m-d H:i:s');
        $update_data    =   [
                                'is_featured'       => request()->is_featured,
                                'updated_at'        => $datetime
                            ];
        $approve        =   DB::table('products')
                            ->where('id',request()->product_id)->update($update_data);
        if($approve){
            return back()->with('success','Action Completed Successfully');
        }
        else{
            return back()->with('error','Something Went Wrong');
        }
    }

    function productstatus(){
        $dt 			=   new \DateTime();
		$datetime		=   $dt->format('Y-m-d H:i:s');
        $update_data    =   [
                                'status'        => request()->active_status,
                                'updated_at'    => $datetime
                            ];
        $approve        =   DB::table('products')
                            ->where('id',request()->product_id)->update($update_data);
        if($approve){
            return back()->with('success','Action Completed Successfully');
        }
        else{
            return back()->with('error','Something Went Wrong');
        }
    }

}
