<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcommerceAPIController extends Controller
{
    // Search API
    public function SearchAPI(){
        try {
            $result = ['status'=>true, "message"=>"Search API Listed Successfully"];
        } catch (\Exception $e) {
            $result = ['status'=>false, "message"=>$e->getMessage()];
        }
        return $result;
    }

    // Filter API
    public function FilterAPI(){

    }

    public function Banners(){
        try {
            $data   = [];
            $data['1']['title']         =   "Recent Featured Products";
            $data['1']['data']          =    DB::table('products')
                                            ->leftjoin('sellers','products.seller_id','=','sellers.id')
                                            ->leftjoin('product_stocks','products.id','=','product_stocks.product_id')
                                            ->leftJoin('categories','products.category_id','=','categories.id')
                                            ->where('products.status','1')
                                            ->where('sellers.is_active','1')
                                            ->where('categories.is_active','1')
                                            ->select('products.id','products.image','products.name','products.category_id',
                                            'categories.name as category_name','products.seller_id','sellers.sellername',
                                            'product_stocks.product_price','products.is_featured')->where('products.is_featured','=','1')
                                            ->orderby('products.created_at','desc')->take(5)->get();

            $data['2']['title']         =   "Top Sold Products";
            $data['2']['data']          =    DB::table('products')
                                                ->leftjoin('sellers','products.seller_id','=','sellers.id')
                                                ->leftjoin('product_stocks','products.id','=','product_stocks.product_id')
                                                ->leftJoin('categories','products.category_id','=','categories.id')
                                                ->where('products.status','1')
                                                ->where('sellers.is_active','1')
                                                ->where('categories.is_active','1')
                                                ->select('products.id','products.image','products.name','products.category_id',
                                                'categories.name as category_name','products.seller_id','sellers.sellername',
                                                'product_stocks.product_price','products.is_featured','salecount as ordercount')
                                                ->join(DB::raw('(SELECT
                                                    productid,
                                                    SUM(prod_qty) AS salecount
                                                FROM
                                                    order_vendors AS OV
                                                GROUP BY productid
                                            ) AS product_sold_tbl'),
                                            function($join)
                                            {
                                                $join->on('products.id', '=', 'product_sold_tbl.productid');
                                            })->orderby('product_sold_tbl.salecount','Desc')
                                            ->take(5)->get();

            $data['3']['title']         =   "Popular Sellers";
            $data['3']['data']          =   DB::table('sellers')
                                            ->select("id","sellername","sellerprofile", 'sellercount as ordercount')
                                            ->where('sellers.approval','=','1')
                                            ->where('sellers.is_active','=','1')
                                            ->join(DB::raw('(SELECT
                                                sellerid,
                                                COUNT(sellerid) AS sellercount
                                            FROM
                                                order_vendors AS OV
                                                GROUP BY sellerid
                                            ) AS popular_selelr_tbl'),
                                        function($join)
                                        {
                                            $join->on('sellers.id', '=', 'popular_selelr_tbl.sellerid');
                                        })->orderby('popular_selelr_tbl.sellercount','Desc')
                                        ->take(5)->get();
            $data['4']['title']         =   "Popular Categories";
            $data['4']['data']          =   DB::table('categories')
                                            ->leftJoin(DB::raw('(SELECT
                                                category_id,
                                                COUNT(category_id) AS category_count
                                            FROM
                                                user_preferred_categories
                                            GROUP BY category_id) as preferred_categories'),
                                            function($join)
                                            {
                                                $join->on('categories.id','=','preferred_categories.category_id');
                                            })
                                            ->where('categories.is_active','1')
                                            ->orderBy('category_count','Desc')
                                            ->select('categories.id','categories.name','categories.image_url','preferred_categories.category_count as user_preferences')
                                            ->take(5)->get();

            $result = ['status'=>true, "data"=>$data, "message"=>"Banners API Listed Successfully"];
        } catch (\Exception $e) {
            $result = ['status'=>false, "message"=>$e->getMessage()];
        }
        return $result;
    }
    // Common API comes Here.

}
