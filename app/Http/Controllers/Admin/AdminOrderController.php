<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderStatus;
use App\Traits\OrderTrait;
use Exception;
use Illuminate\Support\Facades\DB;

use validator;
class AdminOrderController extends Controller
{
    use OrderTrait;
    //
    public function OrderStatusList(){
        return view('dashboard.admin.orders.orderstatus')
        ->with(['OrderStatusList'=>OrderStatus::all()]);
    }

    public function OrderStatusCreate(Request $request){
        $rules = array(
            'name'   =>    'required|unique:order_statuses,name',
        );

        $request->validate($rules);
        $dt 			= new \DateTime();
		$datetime		= $dt->format('Y-m-d H:i:s');
        $insert_data    = [
                            'name'=> $request->name,
                            'active_status'=>'1',
                            'pic_to_verify'=>$request->pic_to_verify,
                            'show_n_list'=>$request->show_n_list,
                            'created_at'=> $datetime,
                            'updated_at'=> $datetime,
                        ];
        OrderStatus::create($insert_data);
        return back()->with('success','New Order Status Created');
    }

    public function OrderList(Request $request)
    {
        $get_orders     = $this->OrderLists();
        $GetOrderDetails=   $get_orders['orderlist'];
        if($get_orders['status'] == true){
            return view('dashboard.admin.orders.index',compact('GetOrderDetails'));
        }else{
            redirect()->back()->with('error',$get_orders['message']);
        }
    }

    public function OrderDetail($orderid){
        try{
            $CheckOrderId   = Order::find($orderid);
            if ($CheckOrderId) {
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

        if($result['status'] == true) {
            $order_det      = $result['data'];
            // dd($order_det);

            return view('dashboard.admin.orders.order_det',compact('order_det'));
        }else{
            redirect()->back()->with('error',$result['message']);
        }
    }
}
