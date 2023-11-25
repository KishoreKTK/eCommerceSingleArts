<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use Exception;

class SellerAPIController extends Controller
{
    //

    public function sellers()
    {
        try
        {
            $sellers =   DB::table('sellers')
                            ->select('sellers.id as sellerid','sellers.sellername',
                                'sellers.sellerprofile')
                            ->where('sellers.approval','=','1')
                            ->where('sellers.is_active','=','1');

            if(request()->has('keyword') && request()->keyword != ''){
                $sellers->Where("sellers.sellername","like",'%'.request()->keyword.'%');
            }
            // if(request()->has('category_id') && request()->category_id != ''){
            //     $category_id    =   request()->category_id;
            //     $Check_Cat_id   =   Categories::where('is_active','1')->find($category_id);
            //     if(!$Check_Cat_id){
            //         throw new Exception("Please Check Category Id");
            //     }
            //     $seller_ids     =   SellerCategories::where('category_id',$category_id)->pluck('seller_id')->toArray();
            //     if(count($seller_ids) == 0){
            //         throw new Exception("No Shops Available in this Category");
            //     }
            //     $sellers->whereIn('id',$seller_ids);
            // }

                            // ->get();
            $sellerlist =   $sellers->get();

            if(count($sellerlist) > 0)
            {
                foreach($sellerlist as $data){
                    $data->sellerprofile    = asset($data->sellerprofile);
                }
                $result         = ['status'=>true,'count'=>count($sellerlist),'data'=> $sellerlist, 'message'=>'Seller Listed successfully'];
            }
            else
            {
                $result         = ['status'=>false, 'message'=>'No Seller found'];
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }

    public function sellerdetail($id)
    {
        try
        {
            $seller = Seller::select(   "id",
                                        "sellername",
                                        "sellerprofile")
                                ->where('sellers.approval','=','1')
                                ->where('sellers.is_active','=','1')
                                ->find($id);
            if(!$seller){
                throw new Exception("Please Check Seller Id");
            }
            $seller->sellerprofile    = asset($seller->sellerprofile);
            $products   =   DB::table('products')
                            ->leftjoin('product_stocks','products.id','=','product_stocks.product_id')
                            ->leftJoin('categories','products.category_id','=','categories.id')
                            ->where('products.status','1')
                            ->select('products.id','products.image','products.name','products.category_id',
                            'categories.name as category_name','products.seller_id',
                            'product_stocks.product_price','products.is_featured')
                            ->where('products.seller_id','=',$seller->id)->get();

            $seller->products   = $products;
            foreach( $seller->products as $prd){
                $prd->image  =   asset($prd->image);
            }
            $result         = [
                                'status'=>true,
                                'data'=> $seller,
                                'message'=>'Seller Retrieved successfully'
                            ];
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }
}
