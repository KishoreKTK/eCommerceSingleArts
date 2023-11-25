<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\UserAddress;
use App\Models\OrderQuantity;
use App\Models\BookingAddress;
use App\Models\Order;

use App\Traits\OrderTrait;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderAPIController extends Controller
{
    use OrderTrait;
    //
    public function ProceedtoCheckOut()
    {
        try
        {
            if(request()->has('checkout_type')){

                if(request()->checkout_type == 'cart_checkout'){
                    $user_id                =   auth()->guard('api')->user()->id;
                    $check_existing_cart    =   Cart::where('user_id',$user_id)->exists();
                    if($check_existing_cart)
                    {
                        $product_id         =   Cart::where('user_id',$user_id)->pluck('product_id');
                    } else {
                        throw new Exception("Cart is Empty to Proceed further");
                    }
                } else if(request()->checkout_type == 'direct_checkout'){
                    if(request()->has('product_id') && request()->has('product_qty')){
                        $product_id[]   =   request()->product_id;
                    }
                    else{
                        throw new \Exception("ProdutId / Quantity Required");
                    }
                } else {
                    throw new Exception("Please Check CheckoutType value");
                }
            }
            else
            {
                throw new Exception("CheckoutType Required");
            }

            if(count($product_id) > 0)
            {
                $productlist =  DB::table('products')
                                ->leftjoin('product_stocks','products.id','=','product_stocks.product_id')
                                ->leftJoin('sellers','products.seller_id','=','sellers.id')
                                ->where('products.status','1')
                                ->select('products.id','products.image','products.name',
                                'product_stocks.quantities as avaialble_qty','products.seller_id','sellers.sellername',
                                'product_stocks.product_price')
                                ->whereIn('products.id',$product_id)
                                ->get();

                if(request()->checkout_type == 'cart_checkout'){
                // if($check_existing_cart)
                // {
                    foreach($productlist as $product){
                        $product->image         =   asset($product->image);
                        $cart_det               =   Cart::select('product_qty')->where('user_id',$user_id)
                                                    ->where('product_id',$product->id)->first();
                        $product->selected_qty  =   $cart_det->product_qty;
                        $product->total_price   =   $cart_det->product_qty * $product->product_price;
                    }
                }
                // }
                else
                {
                    $product_qty    =   request()->product_qty;
                    foreach($productlist as $product){
                        $product->image         =   asset($product->image);
                        $product->selected_qty  =   $product_qty;
                        $product->total_price   =   $product_qty * $product->product_price;
                    }
                }
                // $order_details['products']      = $productlist;
                // $order_details['UserAddress']   = UserAddress::where('user_id',$user_id)->where('default_addr','1')->first();
                $result = ['status'=>true,'count'=>count($productlist),'data'=> $productlist, 'message'=>'Check Out Details successfully'];
            }
            else
            {
                $result = ['status'=>false, 'message'=>'No Products Available'];
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }

    public function PlaceOrder(Request $request)
    {
        try
        {
            $validator  =   Validator::make($request->all(),[
                                "products"              => "required|array",
                                "products.*.product_id" => "required|distinct|exists:products,id",
                                "products.*.product_qty" => "required",
                                "address_id"            => "required|exists:user_addresses,id",
                                "payment_type"          => "required"
                            ]);
            if($validator->fails())
            {
                $errors     =   implode( ", ",$validator->errors()->all());
                // json_encode($validator->errors()->all());
                throw new \Exception($errors);
            }
            else
            {
                $products           =   $request->products;
                $user_id            =   auth()->guard('api')->user()->id;
                $product_qty_price  =  [];
                $product_qty_det    =  [];

                foreach ($products as $key => $product)
                {
                    $check_product =   DB::table('product_stocks')
                                        ->select('product_id as id','product_price as price','quantities')
                                        ->where('product_id',$product['product_id'])
                                        ->where('quantities','>=',$product['product_qty'])
                                        ->first();
                    if($check_product)
                    {
                        $product_qty_price[$check_product->id]  = $check_product->price * $product['product_qty'];
                        $product_qty_det[$key]["product_id"]    = $check_product->id;
                        $product_qty_det[$key]["product_price"] = $check_product->price;
                        $product_qty_det[$key]["avail_qty"]     = $check_product->quantities;
                        $product_qty_det[$key]["quantity"]      = $product['product_qty'];
                        $product_qty_det[$key]["total_price"]   = $check_product->price * $product['product_qty'];
                    }
                    else
                    {
                        throw new \Exception("Out of Stock / Please Reduce Quantity");
                    }
                }

                $order_id           =   $this->generateUniqueNumber();
                $address_id         =   $request->address_id;
                $check_address_id   =   DB::table('user_addresses')->where('user_id',$user_id)->where('id',$address_id)->first();
                if(!$check_address_id){
                    throw new Exception("Address Id Does not Match User");
                }
                $orderdata          =   ['order_id'=>$order_id, 'user_id'=>$user_id,'address_id'=>$address_id, 'products'=>$products];
                $NewOrder           =   $this->InsertNewOrder($orderdata);
                if($NewOrder['status']  == true){
                    // Clear Cart
                    $cart = Cart::where('user_id',$user_id);
                    if($cart->exists())
                    {
                        $cart->delete();
                    }

                    // Reduce Quantity of Product
                    foreach ($product_qty_det as $key => $product_det) {
                        $remaining_qty  = $product_det['avail_qty'] - $product_det['quantity'];
                        $update_stock =     DB::table('product_stocks')
                                            ->where('product_id',$product_det['product_id'])
                                            ->update(['quantities'=>$remaining_qty,'updated_at'=>date(('Y-m-d H:i:s'))]);
                        if(!$update_stock){
                            throw new \Exception("Something Went Wrong in Updating Stock");
                        }
                    }

                    $result = ['status'=>true, "Message"=>"Thank You For Ordering."];
                }
                else{
                    throw new Exception($NewOrder['message']);
                }
            }
        }
        catch (\Exception $e)
        {
            $result     = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }

    Public function ParticularOrderDet($id)
    {
        try{
            $CheckOrderId   = Order::find($id);
            if ($CheckOrderId) {
                // Check Weather This Order Belongs to My Order
                $order_user_id          =   $CheckOrderId->user_id;
                $my_user_id             =   auth()->guard('api')->user()->id;
                if($order_user_id       !=  $my_user_id){
                    throw new Exception("Please Check Your Order Id");
                }
                $result = ['status'=>true,'data'=> $this->OrderDetails($CheckOrderId->order_id) , "Message"=>"Order Details"];
            }
            else{
                throw new Exception("Please Check the Order Id");
            }
        }
        catch (\Exception $e)
        {
            $result     = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);
    }

    Public function MyOrders()
    {
        try
        {
            $user_id            =   Auth::guard('api')->user()->id;
            $my_orders          =   $this->CustomerOrders($user_id);

            if($my_orders['status'] == true)
            {
                $result = ['status'=>true, 'data'=>$my_orders['data'],'message'=>"Orders Listed Successfully"];
            }
            else
            {
                throw new Exception($my_orders['message']);
            }
        }
        catch (\Exception $e)
        {
            $result     = ['status'=>false,'message'=> $e->getMessage()];
        }
        return response()->json($result);

    }

}
