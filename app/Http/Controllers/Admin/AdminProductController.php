<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attributes;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Categories;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductStocks;
use App\Traits\ProductTrait;
use Attribute;
use Illuminate\Support\Facades\DB;


class AdminProductController extends Controller
{
    use ProductTrait;

    public function ProductAttributesList()
    {
        $attributes     =   $this->AttributeList();
        $category_list  =   $this->SelectCategoryList();
        return view('dashboard.admin.products.ProductAttribute',
                    compact('attributes','category_list'));
    }

    public function createattributes(Request $request){
        $rules = array(
            'name'          =>      'required|unique:attributes',
            'category_id'   =>      'required'
        );
        $request->validate($rules);
        $input              =   $request->all();
        $insert_attribute   =   $this->CreateAttribute($input);
        if($insert_attribute['status'] == true){
            return back()->with('success',$insert_attribute['message']);
        }
        return back()->with('Error',$insert_attribute['message']);
    }

    public function ProductList(){
        $products = Product::select('products.*','categories.name as categoryname','sellers.sellername'
                    ,'product_stocks.quantities')
                    // ,'products.total_qty as quantities')
                    ->join('categories','products.category_id','=','categories.id')
                    ->join('sellers','products.seller_id','=','sellers.id')
                    ->join('product_stocks','product_stocks.product_id','=','products.id')
                    ->where('product_stocks.price_type','1')
                    ->orderby('products.id','desc')->paginate('25');

        return view('dashboard.admin.products.productlist',compact('products'));
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
        return view('dashboard.admin.products.AddProduct')
            ->with([
                'sellerlist'    =>  $this->SelectSellerList(),
                'categorylist'  =>  $this->SelectCategoryList(),
                'attributelist' =>  $this->SelectAttributeList($category_id)
            ]);
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

    function ProductDetails($id)
    {
        $result =   $this->GetProductDetail($id);
        if($result['status'] == true)
            return view('dashboard.admin.products.ViewProduct')->with($result['data']);
        else
            return redirect()->back()->with('error',$result['message']);
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



    public function GetFilterOptionsPage()
    {
        $attributes =   DB::table('attributes')->join('sub_attributes','sub_attributes.attr_id','attributes.id')
                        ->select('attr_id','name',DB::raw('GROUP_CONCAT(sub_attributes.id) as sub_attribute_id'),
                                DB::raw('GROUP_CONCAT(sub_attr_name) as sub_attribute_names'),'custom')
                        ->where('attributes.is_active','1')
                        ->groupby('sub_attributes.attr_id')->get();
        // dd($attributes);
      
        $filters    =   [];
        return view('dashboard.admin.products.filters',compact('filters','attributes'));
    }


    public function ProductReviewList(){}

}
