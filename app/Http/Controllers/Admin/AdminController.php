<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Traits\AuthenticateTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use AuthenticateTrait;
    function check(Request $request){
         //Validate Inputs
         $request->validate([
            'email'=>'required|email|exists:admins,email',
            'password'=>'required|min:5|max:30'
         ],[
             'email.exists'=>'This email is not exists.'
         ]);

         $creds = $request->only('email','password');

         if( Auth::guard('admin')->attempt($creds) ){
            session()->put('login_type', 'admin');
            return redirect()->route('admin.home');
         }
         else
         {
             return redirect()->route('admin.login')->with('fail','Incorrect credentials');
         }
    }

    function logout(){
        Auth::guard('admin')->logout();
        return redirect('/');
    }

    public function Dashboard(){
        $dashboard  = [];
        $dashboard['counts']['products']    =   Product::where('status','1')->count();
        $dashboard['counts']['sellers']     =   Seller::where('is_active','1')->where('approval','1')->count();
        $dashboard['counts']['customers']   =   User::where('is_active','1')->count();
        $dashboard['counts']['orders']      =   Order::count();

        $dashboard['featured_products']     =   Product::where('status','1')
                                                ->join('sellers','sellers.id','=','products.seller_id')->where('is_featured','1')
                                                ->select('products.id','products.image','products.name','sellers.sellername')
                                                ->orderBy('products.updated_at','desc')->paginate(6);

        $dashboard['popular_sellers']       =   DB::table('sellers')->where('is_active','1')->where('approval','1')
                                                ->join(DB::raw('(SELECT
                                                        OV.sellerid, count(OV.sellerid) as sellerorders
                                                        FROM
                                                        order_vendors as OV
                                                        GROUP BY OV.sellerid
                                                        ) AS OV_table'),
                                                        function($join)
                                                        {
                                                        $join->on('sellers.id', '=', 'OV_table.sellerid');
                                                    })
                                                ->select('sellers.id','sellers.sellername as name','sellers.sellerprofile as image',
                                                'OV_table.sellerorders','sellers.created_at')
                                                ->orderBy('OV_table.sellerorders','desc')->paginate(6);

        $dashboard['order_vendors']         =   DB::table('order_vendors')
                                                ->select('order_vendors.*', 'products.name as productname', 'sellers.sellername',
                                                'order_statuses.name as statusname','products.image as productimage',
                                                'sellers.sellerprofile as sellerimage')
                                                ->join('products','order_vendors.productid','=','products.id')
                                                ->join('order_statuses','order_statuses.id','=','order_vendors.orderstatus')
                                                ->join('sellers','sellers.id','=','order_vendors.sellerid')
                                                ->orderBy('order_vendors.created_at','desc')
                                                ->paginate(10);

        return view('dashboard.admin.home',compact('dashboard'));
    }


    public function getforgetpassword(){
        return view('dashboard.admin.forgetpass');
    }
    
    public function ForgetPassword(Request $request){
        try {
            $request->validate([
                'email'=>'required|email|exists:admins,email',
             ],[
                 'email.exists'=>'This email is not exists.'
             ]);

            $user_details   =   Admin::where('email',$request->email)->first();
            $mail           =   $request->email;
            $name           =   $user_details->name;
            $usertype       =   "Admin";
            if($user_details->forget_pass_token == '1'){
                throw new Exception("Mail Already Sent. Please Check.");
            }
            $SendForgetMail =   $this->SendForgetMail($mail,$name,$usertype);
            if($SendForgetMail['status'] == true)
            {
                $result     =   ['status' => true, 'message'=>"Mail Sent, Please Check."];
            }
            else
            {
                throw new Exception($SendForgetMail['message']);
            }
        } catch (\Exception $e) {
            $result =   ['status' => false, 'message'=>$e->getMessage()];
        }
        if($result['status'] == true)
        {
            return redirect()->route('admin.login')->with('success',$result['message']);
        }
        else
        {
            return redirect()->back()->with('fail',$result['message']);
        }
        return response()->json($result);
    }
}
