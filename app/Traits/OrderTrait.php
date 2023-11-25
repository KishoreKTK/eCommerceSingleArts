<?php
namespace App\Traits;

use App\Models\Order;
use App\Models\OrderBookingAddress;
use App\Models\OrderPaymentAddress;
use App\Models\OrderShippingAddress;
use App\Models\OrderStatusTrack;
use App\Models\OrderVendor;
use App\Models\User;
use App\Models\UserAddress;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

trait OrderTrait{

    function OrderColorCode(){
        $color_code     = [
            "primary",
            "secondary","success","danger","warning","info","light","dark"];
        return $color_code;
    }

    function generateUniqueNumber()
    {
        $dt         = new \DateTime();
        $date       = $dt->format('ymd');
        $orderObj   = DB::table("orders")->select('order_id')->latest('id')->first();
        if ($orderObj) {
            $orderNr            =   $orderObj->order_id;
			$removed1char       =   substr($orderNr,9);
            $dateformat         =   str_pad($removed1char + 1, 3, "0", STR_PAD_LEFT);
            $uid                =   $date. $dateformat;
            $generateOrder_nr   =   "BZT".$uid;
        } else {
            $generateOrder_nr   =   "BZT".$date . str_pad(1, 3, "0", STR_PAD_LEFT);
        }
        return $generateOrder_nr;
	}

    function GetAddressData($orderId,$user_id,$address_id){
        $InsertAddress   =   UserAddress::select('addr_title','first_name','last_name',
                            'phone_num','villa','address','city','country')
                            ->where('user_id',$user_id)->where('id',$address_id)
                            ->first();
        $InsertAddress->order_id    =   $orderId;
        $InsertAddress->created_at  =   date('Y-m-d H:i:s');
        $InsertAddress->updated_at  =   date('Y-m-d H:i:s');

        return $InsertAddress;
    }

    function InsertNewOrder($orderdata){
        try {
            $orderId            =   $orderdata['order_id'];
            $user_id            =   $orderdata['user_id'];
            $products           =   $orderdata['products'];
            $address_id         =   $orderdata['address_id'];
            $ordervendordata    =   [];
            $product_qty_price  =   [];
            $product_commission =   [];
            foreach ($products as $key => $product) {
                $product_det    =   DB::table('products')->select('products.id as productid',
                                    'sellers.id as sellerid', 'sellers.commission','product_stocks.quantities',
                                    'product_stocks.product_price')
                                    ->join('sellers','products.seller_id','=','sellers.id')
                                    ->join('product_stocks','products.id','=','product_stocks.product_id')
                                    ->where('products.id',$product['product_id'])->first();

                $product_qty_price[$product_det->productid]  = $product_det->product_price * $product['product_qty'];
                $product_commission[$product_det->productid]  = $product_det->commission * $product['product_qty'];

                $ordervendordata[$key]['orderid']           =    $orderId;
                $ordervendordata[$key]['sellerid']          =    $product_det->sellerid;
                $ordervendordata[$key]['productid']         =    $product_det->productid;
                $ordervendordata[$key]['produt_entry_id']   =    null;
                $ordervendordata[$key]['prod_qty']          =    $product['product_qty'];
                $ordervendordata[$key]['price_per_unit']    =    $product_det->product_price;
                $ordervendordata[$key]['product_commission']=    0;
                $ordervendordata[$key]['seller_commission'] =    $product_det->commission;
                $ordervendordata[$key]['seller_commission_perc'] =    $product_det->commission;
                $ordervendordata[$key]['seller_tax']        =    0;
                $ordervendordata[$key]['seller_tax_percent']=    0;
                $ordervendordata[$key]['shipping_charges']  =    0;
                $ordervendordata[$key]['total_amount']      =    $product_det->product_price * $product['product_qty'];
                $ordervendordata[$key]['orderstatus']       =    '1';
                $ordervendordata[$key]['created_at']        =    date('Y-m-d H:i:s');
                $ordervendordata[$key]['updated_at']        =    date('Y-m-d H:i:s');
            }
            $total_price            =   array_sum($product_qty_price);

            $insert_data            =   [
                'order_id'          =>  $orderId,
                'user_id'           =>  $user_id,
                'address_id'        =>  $address_id,
                'payment_addr_id'   =>  $address_id,
                'billing_addr_id'   =>  $address_id,
                'order_status_id'   =>  1,
                'tax'               =>  0,
                'tax_percentage'    =>  0,
                'shipping_charge'   =>  0,
                'commission'        =>  0,
                'discount'          =>  0,
                'promocode'         =>  0,
                'grand_total'       =>  $total_price,
                'payment_type'      =>  '1',
                'created_at'        =>  date('Y-m-d H:i:s'),
                'updated_at'        =>  date('Y-m-d H:i:s')
            ];
            // print_r($ordervendordata);die;
            $InsertOrder        = Order::insert($insert_data);
            if(!$InsertOrder){
                throw new Exception("Something Went Wrong");
            }
            $InsertOrderVendor  = DB::table('order_vendors')->insert($ordervendordata);
            if(!$InsertOrderVendor){
                throw new \Exception("Something Went Wrong in Adding Order Vendors Data");
            }


            $order_vendor_data  =   DB::table('order_vendors')->select('id')->where('orderid',$orderId)->get();
            $OrderStatusData    =   [];
            foreach ($order_vendor_data as $key => $value) {
                $OrderStatusData[$key]['sub_order_id']  =   $value->id;
                $OrderStatusData[$key]['order_status']  =   "1";
                $OrderStatusData[$key]['remarks']       =   "New Order Added";
                $OrderStatusData[$key]['created_at'] = date('Y-m-d H:i:s');
            }

            $InsertOrderStatus  = OrderStatusTrack::insert($OrderStatusData);
            if(!$InsertOrderStatus){
                throw new \Exception("Something Went Wrong in Adding Order Status Track");
            }

            $OrderBookingAddress  =   OrderBookingAddress::insert(json_decode($this->GetAddressData($orderId,$user_id,$address_id), true));
            if(!$OrderBookingAddress){
                throw new \Exception("Something Went Wrong in Adding Bookding Address");
            }

            $OrderPaymentAddress  =   OrderPaymentAddress::insert(json_decode($this->GetAddressData($orderId,$user_id,$address_id), true));
            if(!$OrderPaymentAddress){
                throw new \Exception("Something Went Wrong in Adding Payment Address");
            }

            $OrderShippingAddress  =   OrderShippingAddress::insert(json_decode($this->GetAddressData($orderId,$user_id,$address_id), true));
            if(!$OrderShippingAddress){
                throw new \Exception("Something Went Wrong in Adding Shipping Address");
            }

            $result =   ['status'=>true, 'message'=> 'Order Inserted Successfully'];
        } catch (\Exception $e) {
            $result =   ['status'=>false, 'message'=>$e->getMessage()];
        }
        return $result;
    }

    function OrderDetails($OrderId)
    {
        $Order_Details_Arr  =   [];

        $Order_Details_Arr['id']        =   DB::table('orders')->where('order_id',$OrderId)->first()->id;
        $Order_Details_Arr['order']     =   DB::table('orders')
                                            ->select('orders.order_id','orders.user_id','orders.order_status_id',
                                            'order_statuses.name as statusname','orders.grand_total','orders.created_at')
                                            ->join('order_statuses','orders.order_status_id','=','order_statuses.id')
                                            ->where('order_id',$OrderId)->first();
        $Order_Details_Arr['order']->created_at = Carbon::parse($Order_Details_Arr['order']->created_at)->toDayDateTimeString();
        $Order_Details_Arr['address']   =   DB::table('order_booking_addresses')
                                            ->select('first_name','phone_num','villa',
                                            'address','city','country.nicename')
                                            ->join('country','country.iso','order_booking_addresses.country')
                                            ->where('order_id',$OrderId)->first();
        $Order_Details_Arr['user_det']  =   DB::table('users')->select('id','name','email','phone')
                                            ->where('id',$Order_Details_Arr['order']->user_id)
                                            ->first();
        $Order_Details_Arr['qty_det']   =   DB::table('order_vendors')
                                            ->select('order_vendors.id as suborderid',
                                                    'products.id as prdt_id',
                                                    'products.image',
                                                    'products.name as prod_name',
                                                    'sellers.id as seller_id',
                                                    'sellers.sellername',
                                                    'order_vendors.price_per_unit',
                                                    'order_vendors.prod_qty'
                                                    ,'order_vendors.total_amount',
                                                    'order_vendors.orderstatus as vendoerorderstatusid',
                                                    'order_statuses.name as orderstatusname')
                                            ->join('products','products.id','=','order_vendors.productid')
                                            ->join('sellers','sellers.id','=','order_vendors.sellerid')
                                            ->join('order_statuses','order_vendors.orderstatus','=','order_statuses.id')
                                            ->where('orderid',$OrderId)
                                            ->get();
        foreach ($Order_Details_Arr['qty_det'] as $key => $value) {
            $Order_Details_Arr['qty_det'][$key]->image  = asset($value->image);
        }

        return $Order_Details_Arr;
    }

    function CustomerOrders($customer_id)
    {
        try
        {
            $customer_orders    = Order::where('user_id',$customer_id)->get();
            // dd($customer_orders);
            $order_details      =   [];
            if(count($customer_orders) > 0)
            {
                foreach ($customer_orders as $key => $myorder)
                {
                    $order_details[$key]  =   $this-> OrderDetails($myorder->order_id);
                    unset($order_details[$key]['address']);
                }

                $result = ['status'=>true, 'data'=>$order_details];
            }
            else
            {
                throw new Exception("No Orders Placed Yet");
            }
        }
        catch(Exception $e)
        {
            $result     =   ['status'=> false, 'message'=>$e->getMessage()];
        }

        return $result;
    }

    function OrderLists()
    {
        try
        {
            $order_query    =   DB::table('orders')
                                ->select('orders.id','orders.order_id','users.name as username','orders.order_status_id',
                                'order_statuses.name as statusname','orders.tax','orders.shipping_charge',
                                'orders.discount','orders.grand_total','orders.payment_type',
                                'orders.created_at')
                                ->join('users','orders.user_id','=','users.id')
                                ->join('order_statuses','orders.order_status_id','=','order_statuses.id')
                                ->latest('orders.created_at')->paginate(25);

            $result =   ['status'=>true,'orderlist'=>$order_query];
        } catch (\Exception $e) {
            $result =   ['status'=>false,'message'=>$e->getMessage()];
        }
        return $result;
    }

    function SellerOrders($sellerid){
        $seller_orders  =   DB::table('order_vendors')
                            ->select('order_vendors.*', 'products.name as productname','order_statuses.name as statusname')
                            ->join('products','order_vendors.productid','=','products.id')
                            ->join('order_statuses','order_statuses.id','=','order_vendors.orderstatus')
                            ->where('order_vendors.sellerid',$sellerid)
                            ->paginate(25);
        return $seller_orders;
    }
}
