<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use App\Models\OrderStatusTrack;
use App\Models\OrderVendor;
use App\Traits\OrderTrait;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SellerOrderController extends Controller
{
    use OrderTrait;

    public function OrderList(){
       $sellerid    =   session()->get('seller_id');
       $order_list  =   $this->SellerOrders($sellerid);
       return view('dashboard.seller.orders.orderlist',compact('order_list'));
    }

    public function GetOrderStatus(){
        try {
            $OrderStatus    =   OrderStatus::where('show_n_list','1')->where('active_status','1')->get();
            $result =   ['status'=>true, 'data'=>$OrderStatus];
        } catch (\Exception $e) {
            $result =   ['status'=>false, 'message'=>$e->getMessage()];
        }
        return response()->json($result);
    }

    public function UpdateOrderStatus(){
        try {
            $input  =   request()->all();
            // dd($input);
            if($input['curr_status_id'] == 1){
                $order_status = $input['verifyorder_status'];
            }
            else{
                $order_status = $input['order_status'];
            }

            $UpdateOrderVendors =   DB::table('order_vendors')
                                    ->where('id',$input['sub_order_id'])
                                    ->update([
                                        'orderstatus'=>$order_status,
                                        'updated_at'=>  date('Y-m-d H:i:s')
                                    ]);
            if(!$UpdateOrderVendors){
                throw new Exception("Something Went Wrong in Updating");
            }

            $status_track_data  =   [
                'sub_order_id'  => $input['sub_order_id'],
                'order_status'  => $order_status,
                'remarks'       => $input['remarks'],
                'created_at'    => date('Y-m-d H:i:s')
            ];

            if(request()->has('images')){
                $order_images   =   [];
                $url            =   'Uploads/Orders/'.$input['curr_order_id'].'/'.str_replace(' ', '_', $input['order_status_name']).'/';
                foreach(request()->images as $key=>$images)
                {
                    $imgname        = time().'_'.$key.'.'.$images->getClientOriginalExtension();
                    $images->move($url, $imgname);
                    array_push($order_images,$url.$imgname);
                }
                $images_url     = implode("|",$order_images);
                $status_track_data['images']    =   $images_url;
            }

            $track_insert = OrderStatusTrack::insert($status_track_data);
            $result = ['status'=>true, 'Message'=>"Track Updated Successfully"];
            if(!$track_insert){
                throw new Exception("Something Went Wrong in Updating");
            }
        } catch (\Exception $e) {
            $result = ['status'=>false, 'message'=>$e->getMessage()];
        }

        return response()->json($result);
    }

    public function GetOrderTrack(){
        // dd(request()->sub_order_id);
        try {
            OrderVendor::findorfail(request()->sub_order_id);
            $get_track_details  =   OrderStatusTrack::join('order_statuses','order_status_tracks.order_status','=','order_statuses.id')
                                    ->where('sub_order_id',request()->sub_order_id)
                                    ->select('order_status_tracks.*','order_statuses.name as status_name')->get();
            // $images_url  = '';
            // foreach ($get_track_details as $key => $value) {

            //     if($value->images != null){
            //         $imgarr = explode('|',$value->images);
            //         $new_images = [];
            //         foreach ($imgarr as $key => $imgs) {
            //             array_push($new_images, asset($imgs));
            //         }
            //         $images_url     = implode("|",$new_images);
            //     }
            //     $dt = new DateTime($get_track_details[$key]['created_at']);
            //     $get_track_details[$key]['image_url']      =   $images_url;
            //     $get_track_details[$key]['created_date']  =   $dt->format('Y-m-d H:i:s');

            // }
            $result = ['status'=>true, 'data'=>$get_track_details];
            if(!$get_track_details){
                throw new Exception("Something Went Wrong in Updating");
            }
        } catch (\Exception $e) {
            $result = ['status'=>false, 'message'=>$e->getMessage()];
        }

        // dd($result);
        return response()->json($result);
    }
}
