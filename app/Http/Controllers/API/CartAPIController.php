<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductStocks;
use Exception;
// use Validator;
// use Dotenv\Validator;
use Validator;
use Illuminate\Support\Facades\DB;

class CartAPIController extends Controller
{
    public function CartList(){
        try
        {
            $user_id        =   auth()->guard('api')->user()->id;
            $product_id     =   Cart::where('user_id',$user_id)
                                ->pluck('product_id');
            if(count($product_id) > 0)
            {
                $productlist =   DB::table('products')
                                ->leftjoin('product_stocks','products.id','=','product_stocks.product_id')
                                ->join('sellers','sellers.id','=','products.seller_id')->where('products.status','1')
                                ->select('products.id','products.image','products.name','products.seller_id','sellers.sellername',
                                'product_stocks.quantities as avaialble_qty','product_stocks.product_price')
                                ->whereIn('products.id',$product_id)->get();

                foreach($productlist as $product){
                    $product->image         =   asset($product->image);
                    $cart_det               =   Cart::select('product_qty')->where('user_id',$user_id)->where('product_id',$product->id)->first();
                    $product->selected_qty  =   $cart_det->product_qty;
                    $product->total_price   =   $cart_det->product_qty * $product->product_price;
                }

                $result = ['status'=>true,'count'=>count($productlist),'data'=> $productlist, 'message'=>'Cart Products Listed successfully'];
            }
            else
            {
                $result = ['status'=>false, 'message'=>'No Products in Cart'];
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }

    public function AddToCart(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),[
                'product_id' => 'required|exists:products,id',
                'product_qty' => 'required'
            ]);
            if($validator->fails())
            {
                $result = ['status'=>false,'message'=> implode( ", ",$validator->errors()->all())];
            }
            else
            {
                $dt 			=   new \DateTime();
                $datetime		=   $dt->format('Y-m-d H:i:s');
                $product_id     =   $request->product_id;
                $user_id        =   auth()->guard('api')->user()->id;
                $product_qty    =   $request->product_qty;
                if($product_qty <= 0){
                    throw new Exception("Please Check the Product Quantity");
                }
                $check_available_product =   Cart::where('product_id',$product_id)->where('user_id',$user_id)->count();
                if($check_available_product > 0)
                {
                    $result = ['status'=>false,'message'=> "Product already Available in Cart"];
                }
                else
                {
                    $check_Quantity =   ProductStocks::where('product_id','=',$product_id)
                                        ->where('quantities','>', $product_qty)
                                        ->first();

                    if($check_Quantity)
                    {
                        $input  = ['product_id'=>$product_id ,'user_id'=>$user_id ,'product_qty'=>$product_qty ,"created_at"=>$datetime,"updated_at"=>$datetime];
                        Cart::create($input);
                        $result = ['status'=>true,'message'=> "Product Added to Cart"];
                    }
                    else
                    {
                        $result = ['status'=>false,'message'=> "Out of Stock / Please Reduce Quantity"];
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

    public function QuantityChanges(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),[
                'product_id' => 'required|exists:products,id',
                'product_qty' => 'required'
            ]);
            if($validator->fails())
            {
                $result = ['status'=>false,'message'=> implode( ", ",$validator->errors()->all())];
            }
            else
            {
                $dt 			=   new \DateTime();
                $datetime		=   $dt->format('Y-m-d H:i:s');
                $product_id     =   $request->product_id;
                $user_id        =   auth()->guard('api')->user()->id;
                $product_qty    =   $request->product_qty;
                if($product_qty <= 0){
                    throw new Exception("Please Check the Product Quantity");
                }
                $check_Quantity =   Product::where('products.id','=',$product_id)
                                    ->join('product_stocks','product_stocks.product_id','=','products.id')
                                    ->where('product_stocks.quantities','>', $product_qty)
                                    ->first();
                if($check_Quantity)
                {
                    $check_product_n_cart = Cart::where('product_id',$product_id)->where('user_id',$user_id)->first();
                    if(!$check_product_n_cart){
                        throw new Exception("Please Add the Produt to Cart to Update Qunantity");
                    }
                    else{
                        $input  = ['product_qty'=>$product_qty ,"updated_at"=>$datetime];
                        Cart::where('product_id',$product_id)->where('user_id',$user_id)->Update($input);
                        $result = ['status'=>true,'message'=> "Quantity Updated to Cart"];
                    }
                }
                else
                {
                    $result = ['status'=>false,'message'=> "Out of Stock / Please Reduce Quantity / Product  Not Available In Cart"];
                }
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }


    public function RemoveFromCart(Request $request){
        try
        {
            $validator = Validator::make($request->all(),[
                'product_id' => 'required|exists:products,id',
            ]);
            if($validator->fails())
            {
                $result = ['status'=>false,'message'=> implode( ", ",$validator->errors()->all())];
            }
            else
            {
                $product_id     =   $request->product_id;
                $user_id        =   auth()->guard('api')->user()->id;
                $cart = Cart::where('product_id',$product_id)->where('user_id',$user_id);
                if($cart->exists())
                {
                    $cart->delete();
                    $result = ['status'=>true,'message'=> "Procuct Removed from Cart"];
                }
                else
                {
                    $result = ['status'=>false,'message'=> "Procuct Not Found in Cart"];
                }
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }


    public function ClearCart(){
        try
        {
            $user_id        =   auth()->guard('api')->user()->id;
            $cart = Cart::where('user_id',$user_id);
            if($cart->exists())
            {
                $cart->delete();
                $result = ['status'=>true,'message'=> "Cart Items Cleared"];
            }
            else
            {
                $result = ['status'=>false,'message'=> "No Products in Cart"];
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }

}
