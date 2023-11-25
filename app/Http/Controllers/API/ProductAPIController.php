<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Categories;
use App\Models\UserFavourites;
use App\Models\UserPreferredCategory;
use App\Models\UserSavedProducts;
use Illuminate\Support\Carbon;
use Exception;

class ProductAPIController extends Controller
{
    public function productlist()
    {
        try
        {
            $filtered_productid =   [];

            // if(request()->filled('filter') )
            // {
            //     $filter             =   json_decode(request()->filter);
            //     // dd($filter);
            //     if(count($filter)!= 0){
            //         foreach ($filter as $key => $value)
            //         {
            //             $filter_type        =   $value->filter_type;
            //             $filter_option      =   $value->filter_options;
            //             if($filter_type == '1'){
            //                 $product_ids    =   DB::table('product_attributes')
            //                                     ->where('sub_attr_id',$filter_option)
            //                                     ->pluck('product_id');
            //                 array_push($filtered_productid, $product_ids);
            //             }
            //             else{
            //                 $product_ids            =   DB::table('product_stocks')->where('price_type','1');
            //                 if (str_contains($filter_option, '-')) {
            //                     $filter_values  =   explode('-',$filter_option);
            //                     $product_ids->whereBetween('product_price',$filter_values);
            //                 } else {
            //                     $filter_value   =   str_replace('>', '', $filter_option);
            //                     $product_ids->where('product_price','>',$filter_value);
            //                 }
            //                 $filterd_ids     =    $product_ids->pluck('product_id');
            //                 array_push($filtered_productid, $filterd_ids);
            //             }
            //         }
            //         array_unique($filtered_productid);
            //         // $filtered_productid    = $filtered_productid;
            //         // dd($filtered_productid);
            //         if(count($filtered_productid) == 0){
            //             throw new Exception("No Products found under this filter");
            //         }
            //     }
            //     else {
            //         throw new Exception("Please Check the Exception");
            //     }
            // }
            $productlist    =   DB::table('products')
                                ->leftjoin('sellers','products.seller_id','=','sellers.id')
                                ->leftjoin('product_stocks','products.id','=','product_stocks.product_id')
                                ->leftJoin('categories','products.category_id','=','categories.id')
                                ->where('products.status','1')
                                ->where('sellers.is_active','1')
                                ->where('categories.is_active','1')
                                ->where('product_stocks.price_type','1')
                                ->select('products.id','products.image','products.name','products.category_id',
                                'categories.name as category_name','products.seller_id','sellers.sellername',
                                'product_stocks.product_price','products.is_featured');
            // if filter has values
            if(count($filtered_productid) > 0){
                // dd($filtered_productid);
                $productlist =  $productlist->whereIn('products.id', $filtered_productid);
                // ->whereIn('products.id',$filtered_productid);
            }
            // Featured Products
            if(request()->has('is_featured') && request()->is_featured == 1){
                $productlist =  $productlist->where('products.is_featured','=','1')->take(5);
            }

            // Products Based on Category
            if(request()->has('category_id') && request()->category_id != ''){
                $cate_id = request()->category_id;
                foreach($cate_id as $catid){
                    if(!Categories::find($catid)){
                        throw new Exception("Please Check Category Id");
                    }
                }
                $productlist =  $productlist->whereIn('products.category_id',$cate_id);
            }

            // Products Based on Seller
            if(request()->has('seller_id') && request()->seller_id != ''){
                $seller_id = request()->seller_id;
                foreach($seller_id as $sellerid){
                    if(!Seller::find($seller_id)){
                        throw new Exception("Please Check Seller Id");
                    }
                }
                $productlist =  $productlist->whereIn('products.seller_id',$seller_id);
            }

            // New Products Based on User Preffered Category. if Null then Lastest first
            if(request()->has('newproduct') && request()->newproduct == 1)
            {
                $user_id                =   auth()->guard('api')->user()->id;
                $user_preffered_cat     =   UserPreferredCategory::where('user_id',$user_id)->count();
                if($user_preffered_cat > 0)
                {
                    $productlist =  $productlist
                                    ->join(DB::raw('(SELECT
                                        category_id
                                    FROM
                                        user_preferred_categories
                                    WHERE user_id = '.$user_id.') as preferred_cat'),function($join){
                                        $join->on('preferred_cat.category_id','=','products.category_id');
                                    })->orderby('products.created_at','desc');
                } else{
                    $productlist =  $productlist->orderby('products.created_at','desc')->take(25);
                }
            }

            // Search Keyword Based Filter
            if(request()->has('keywords') && request()->keywords != ''){
                $productlist =  $productlist->Where("products.name","like",'%'.request()->keywords.'%');
            }

            if(request()->has('sort_val') && request()->sort_val != '')
            {
                // 1 - popularity (Based on Max Orers Placed);
                // 2- Alpha Asceding; 3- Alpha Descending;
                // 4- price high to low; 5-price lowertohigher
                $sort_val = request()->sort_val;
                if($sort_val == 1)
                {
                    $productlist =  $productlist->leftjoin(DB::raw('(SELECT
                                            productid,
                                            SUM(prod_qty) AS salecount
                                        FROM
                                            order_vendors AS OV
                                        GROUP BY productid
                                    ) AS product_sold_tbl'),
                                    function($join)
                                    {
                                        $join->on('products.id', '=', 'product_sold_tbl.productid');
                                    })->addSelect('salecount')->orderby('product_sold_tbl.salecount','Desc');
                } elseif($sort_val == 2){
                    $productlist =  $productlist->orderby('products.name','asc')
                                                ->addSelect('products.created_at');
                } elseif($sort_val == 3){
                    $productlist =  $productlist->orderby('products.name','desc')
                                                ->addSelect('products.created_at');
                } elseif($sort_val == 4){
                    $productlist =  $productlist->orderby('product_stocks.product_price','Desc');
                } elseif($sort_val == 5){
                    $productlist =  $productlist->orderby('product_stocks.product_price','Asc');
                } else{
                    throw new Exception("Plase Check Provided Sort Id");
                }
            }

            $productlist     =  $productlist->get();
            // dd($productlist);
            if(count($productlist) > 0)
            {
                foreach($productlist as $product)
                {
                    $product->image  = asset($product->image);
                }

                $result                 =   [
                                                'status'=>true,
                                                'count'=>count($productlist),
                                                'data'=> $productlist,
                                                'message'=>'Products Listed successfully'
                                            ];
            }
            else
            {
                $result         = ['status'=>false, 'message'=>'No Products found'];
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }

    public function product_detail($id){
        try
        {
            $product    = Product::join('categories','products.category_id','=','categories.id')
                            ->join('sellers','products.seller_id','=','sellers.id')
                            ->leftjoin('product_stocks','products.id','=','product_stocks.product_id')
                            ->select('products.id','products.image','products.name','products.category_id',
                                'categories.name as category_name','products.seller_id','sellers.sellername',
                                'product_stocks.product_price', 'product_stocks.quantities','products.description',
                                'products.is_featured','products.created_at','products.short_bio',
                                'products.shiiping_det','products.processing_time')
                            // ->select('products.id','categories.name as categoryname','sellers.sellername')
                            ->where('products.id','=',$id)->first();
            if($product)
            {
                $product->like_count    =   DB::table('user_favourites')->where('product_id',$id)->count();
                $user_id                =   auth()->guard('api')->user()->id;

                $user_saved             =   DB::table('user_saved_products')->where('user_id',$user_id)
                                            ->where('product_id',$id)->first();
                $user_liked             =   DB::table('user_favourites')->where('user_id',$user_id)
                                            ->where('product_id',$id)->first();

                if($user_saved){
                    $product->user_saved  = true;
                }else{
                    $product->user_saved  = false;
                }
                if($user_liked){
                    $product->user_fav  = true;
                }else{
                    $product->user_fav  = false;
                }

                $product->image         = asset($product->image);
                $product_banner_images  = [];
                $product_banner_images[]= $product->image;

                $product_images         = DB::table('product_images')->select('image_urls')->where('product_id','=',$id)->get();
                foreach($product_images as $images){
                    $product_banner_images[]  = asset($images->image_urls);
                }

                $product->product_banner_images = $product_banner_images;


                $productattributes =    DB::table('product_attributes')->select('attributes.name as attrname'
                                        ,DB::raw('group_concat(sub_attributes.sub_attr_name) as sub_attr_name'),
                                        'sub_attributes.custom','product_attributes.custom_values')
                                        ->join('attributes','product_attributes.attribute_id','=','attributes.id')
                                        ->leftjoin('sub_attributes','product_attributes.sub_attr_id','=','sub_attributes.id')
                                        ->where('product_attributes.product_id','=',$id)
                                        ->groupBy('product_attributes.attribute_id')->get();
                $attribute  = [];
                foreach($productattributes as $attr){
                    if($attr->custom == 0){
                        // $sub_attr_arr   =   explode(',',$attr->sub_attr_name);
                        // $sub_attr_count =   count($sub_attr_arr);
                        // if($sub_attr_count > 1){
                        //     $attrval =  'Available';
                        // }else{
                        //     $attrval =  $attr->sub_attr_name;
                        // }
                        $attrval =  $attr->sub_attr_name;
                    }else{
                        // $custom_attr_arr   =   explode(',',$attr->custom_values);
                        // $custom_attr_count =   count($custom_attr_arr);
                        // if($custom_attr_count > 1){
                        //     $attrval =  'Custom Values';
                        // }else{
                        //     $attrval =  $attr->custom_values;
                        // }
                        $attrval =  $attr->custom_values;
                    }
                    $attribute[$attr->attrname]  = $attrval;
                }
                $product->product_attributes = $attribute;

                // $product->created_date  =   Carbon::parse($product->created_at)->diffForHumans();
                $product->inserted_date=   Carbon::parse($product->created_at)->toFormattedDateString();
                unset($product->created_at);

                $result         = ['status'=>true,'data'=> $product, 'message'=>'Products Retrieved successfully'];
            }
            else{
                throw new Exception("Please Check the Product Id");
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }


    public function CheckFavorite(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),[
                'product_id' => 'required|exists:products,id',
                'is_favorite' => 'required'
            ]);
            if($validator->fails())
            {
                $result = ['status'=>false,'message'=> implode( ", ",$validator->errors()->all())];
            }
            else
            {
                $product_id     =   $request->product_id;
                $user_id        =   auth()->guard('api')->user()->id;
                $favorite       =   $request->is_favorite;
                $check_exist    =   DB::table('user_favourites')
                                    ->where('product_id',$product_id)
                                    ->where('user_id',$user_id)->first();
                $dt 			= new \DateTime();
                $datetime		= $dt->format('Y-m-d H:i:s');
                if($check_exist)
                {
                    if($favorite==1)
                    {
                        $result = ['status'=>false,'message'=> "You already added this Product as your favorite"];
                    }
                    else
                    {
                        UserFavourites::where("id",$check_exist->id)->delete();
                        $result = ['status'=>true,'message'=> "Successfully removed from your favorites."];
                    }
                }
                else
                {
                    if($favorite==1)
                    {
                       UserFavourites::insertGetId(["user_id"=>$user_id,"product_id"=>$product_id,"created_at"=>$datetime,"updated_at"=>$datetime]);
                       $result = ['status'=>true,'message'=> "Successfully Added to your favorites."];
                    }
                    else
                    {
                        $result = ['status'=>false,'message'=> "You already removed this Product from your favorites."];
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }


    public function CheckSaved(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),[
                'product_id' => 'required|exists:products,id',
                'is_saved' => 'required'
            ]);
            if($validator->fails())
            {
                $result = ['status'=>false,'message'=> implode( ", ",$validator->errors()->all())];
            }
            else
            {
                $product_id     =   $request->product_id;
                $user_id        =   auth()->guard('api')->user()->id;
                $saved          =   $request->is_saved;
                $check_exist    =   DB::table('user_saved_products')
                                    ->where('product_id',$product_id)
                                    ->where('user_id',$user_id)->first();
                $dt 			= new \DateTime();
                $datetime		= $dt->format('Y-m-d H:i:s');
                if($check_exist)
                {
                    if($saved==1)
                    {
                        $result = ['status'=>false,'message'=> "You already added this Product as your Saved List"];
                    }
                    else
                    {
                        UserSavedProducts::where("id",$check_exist->id)->delete();
                        $result = ['status'=>true,'message'=> "Successfully removed from your Saved list."];
                    }
                }
                else
                {
                    if($saved==1)
                    {
                        UserSavedProducts::insertGetId(["user_id"=>$user_id,"product_id"=>$product_id,"created_at"=>$datetime,"updated_at"=>$datetime]);
                       $result = ['status'=>true,'message'=> "Successfully Added to your Saved list."];
                    }
                    else
                    {
                        $result = ['status'=>false,'message'=> "You already removed this Product from your Saved Lists."];
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }


    public function SavedProductList()
    {
        try
        {

            $user_id        =   auth()->guard('api')->user()->id;
            $product_id     =   DB::table('user_saved_products')
                                ->where('user_id',$user_id)
                                ->pluck('product_id');
            if(count($product_id) > 0)
            {
                $productlist =   DB::table('products')
                                ->leftjoin('sellers','products.seller_id','=','sellers.id')
                                ->leftjoin('product_stocks','products.id','=','product_stocks.product_id')
                                ->leftJoin('categories','products.category_id','=','categories.id')
                                ->where('products.status','1')
                                ->where('sellers.is_active','1')
                                ->select('products.id','products.image','products.name','products.category_id',
                                'categories.name as category_name','products.seller_id','sellers.sellername',
                                'product_stocks.product_price','products.is_featured')
                                ->whereIn('products.id',$product_id)
                                ->groupBy('products.id')
                                ->get();

                foreach($productlist as $product){
                    $product->image  = asset($product->image);
                }
                $result = ['status'=>true,'count'=>count($productlist),'data'=> $productlist, 'message'=>'Products Listed successfully'];
            }
            else
            {
                $result = ['status'=>false, 'message'=>'No Products found'];
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }

    public function ProductFilterOptions(){
        try {
            $attributes =   DB::table('attributes')->join('sub_attributes','sub_attributes.attr_id','attributes.id')
                            ->select('attr_id','name',DB::raw('GROUP_CONCAT(sub_attributes.id) as sub_attribute_id'),
                                    DB::raw('GROUP_CONCAT(sub_attr_name) as sub_attribute_names'))
                            ->where('attributes.is_active','1')
                            ->where('sub_attributes.custom','0')
                            ->groupby('sub_attributes.attr_id')->get()->toArray();
            foreach ($attributes as $key => $attr) {
                $sub_attributes     =   [];
                $sub_attr_id    =   explode(',',$attr->sub_attribute_id);
                $sub_attr_val   =   explode(',',$attr->sub_attribute_names);
                foreach ($sub_attr_id as $key => $id) {
                    $sub_attributes[$id]    =   $sub_attr_val[$key];
                }
                unset($attr->attr_id);
                unset($attr->sub_attribute_id);
                unset($attr->sub_attribute_names);
                $attr->filter_options       =   $sub_attributes;
                $attr->filter_type          =   '1';
                // 1 - Dynamic Filter From Table; 2 - Static Filter From Table;
            }

            $static_price = [];
            $static_price['name']             =   'Price';
            $static_price['filter_options']   =   ['<1000', '1000-5000', '5000-10000','10000-20000', '>20000'];
            $static_price['filter_type']      =   '2';

            array_push($attributes, $static_price);
            $result = ['status'=>true,'count'=>count($attributes),'data'=> $attributes, 'message'=>'Products Filter List'];

        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);

    }
}
