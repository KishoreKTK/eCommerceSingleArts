<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class AdminCustomerController extends Controller
{
    //
    public function CustomerList(){
        $customer_list  = DB::table('users')->whereNull("users.deleted_at")->paginate(20);
        return view('dashboard.admin.customers.index',compact('customer_list'));
    }


    public function CustomerDetails($id)
    {
        User::findorfail($id);
        try {
            $customer           =   DB::table('users')->where('id',$id)->first();
            $customer_address   =   DB::table('user_addresses')->where('user_id',$id)->get();
            $saved_products     =   DB::table('user_saved_products')->where('user_id',$id)
                                    ->select('products.id','products.image','products.name',
                                        'product_stocks.product_price as price')
                                    ->join('products','products.id','=','user_saved_products.product_id')
                                    ->join('product_stocks','product_stocks.product_id','=','products.id')
                                    ->get();

            // print("<pre>");print_r($saved_products);die;
            return  view('dashboard.admin.customers.customerdet',
                    compact('customer','customer_address','saved_products'));
        } catch (\Exception $e) {
            return back()->with('error',$e->getMessage());
        }
    }


    // Activate / Inactivate Customer Action
    public function ChangeUserStatus(Request $request){
        $dt 			= new \DateTime();
        $datetime		= $dt->format('Y-m-d H:i:s');
        $update_data    = [
                            'is_active'     => $request->active_status,
                            'updated_at'    => $datetime
                            ];
                        //   print("<pre>");print_r($request->all());die;
        $approve        = DB::table('users')->where('id',$request->userid)->update($update_data);
        if($approve){
            return back()->with('success','Action Completed Successfully');
        }
        else{
            return back()->with('error','Something Went Wrong');
        }
    }

}
