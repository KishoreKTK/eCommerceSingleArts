<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderVendor;
use App\Models\Product;
use App\Models\ProductStocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    function create(Request $request)
    {
        //Validate inputs
        $request->validate([
            'sellername'=>'required',
            'selleremail'=>'required|email|unique:sellers',
            'password'=>'required|min:5|max:30',
            'password_confirmation'=>'required|min:5|max:30|same:password'
        ]);

        $input              = $request->except('_token','password_confirmation','password','SellerProfile','seller_trade_license');
        $dt 			    = new \DateTime();
        $datetime		    = $dt->format('Y-m-d H:i:s');
        $name               = $request->sellername;
        $sellerimageurl     = 'Uploads/Sellers/'.str_replace(' ', '_', $name).'/profile';
        $sellername         = time().'_'.str_replace(' ', '_',$request->file('SellerProfile')->getClientOriginalName());
        $request->SellerProfile->move(public_path($sellerimageurl), $sellername);
        $input['sellerprofile']         =   $sellerimageurl.'/'.$sellername;

        if($input['seller_buss_type'] == "Business")
        {
            if(request()->has('seller_trade_license') && request()->seller_trade_license != '')
            {
                $sellerlicense      = 'Uploads/Sellers/'.str_replace(' ', '_', $name).'/tradelicense';
                $tradename          = time().'_'.str_replace(' ', '_',$request->file('seller_trade_license')->getClientOriginalName());
                $request->seller_trade_license->move(public_path($sellerlicense), $tradename);
                $input['seller_trade_license']  =   $sellerlicense.'/'.$tradename;
            } else {
                return redirect()->back()->with('fail','Please Upload Trade License to Continue');
            }
        }

        $input['password']              =   Hash::make($request->password);
        $input['approval']              =   '0';
        $input['is_active']             =   '0';
        $input['register_type']         =   '0';

        $save  = Seller::create($input);

        if( $save ){
            return redirect()->back()->with('success','Thanks For Registering, We will Contact You Soon');
        }else{
            return redirect()->back()->with('fail','Something went Wrong, failed to register');
        }
    }

    function check(Request $request)
    {
        $request->validate([
           'selleremail'=>'required|email|exists:sellers',
           'password'=>'required|min:5|max:30'
        ],[
            'selleremail.exists'=>'This email is not exists in sellers table'
        ]);

        $userDetails =  Seller::where('selleremail', $request->selleremail)->where('approval','=','1')->first();
        if($userDetails)
        {
            $creds  = ['selleremail' => $request->selleremail, 'password' => $request->password];
            if( Auth::guard('seller')->attempt($creds) ){
                session()->put('login_type', 'seller');
                session()->put('seller_id', Auth::guard('seller')->user()->id);
                return redirect()->route('seller.home');
            }
            else
            {
                return redirect()->route('seller.login')->with('fail','Incorrect Credentials');
            }
        }
        else{
            return redirect()->route('seller.login')->with('fail','Incorrect Credentials.');
        }
    }

    public function Dashboard(){
        $seller_id      =   session()->get('seller_id');

        $dashboard      =   [];
        $dashboard['counts']['products']    =   Product::where('seller_id',$seller_id)->where('status','1')->count();
        $dashboard['counts']['orders']      =   OrderVendor::where('sellerid',$seller_id)->count();
        $dashboard['counts']['revenues']    =   OrderVendor::where('sellerid',$seller_id)
                                                ->select(DB::raw('SUM(order_vendors.total_amount) as totalrevenue'))
                                                ->groupBy('order_vendors.sellerid')->first()->totalrevenue;

        $dashboard['seller']['profile']     =   Seller::where('id',$seller_id)->first();
        $dashboard['seller']['stocks']      =   ProductStocks::leftjoin('products','products.id','=','product_stocks.product_id')
                                                ->select('product_stocks.product_id as id','products.name','product_stocks.quantities',
                                                'product_stocks.product_price')
                                                ->where('products.seller_id',$seller_id)->get();
        $dashboard['seller']['products']    =   Product::where('products.seller_id',$seller_id)
                                                ->where('products.status','=','1')->paginate(6);

        $dashboard['order_vendors']         =   DB::table('order_vendors')
                                                ->select('order_vendors.*', 'products.name as productname', 'sellers.sellername',
                                                'order_statuses.name as statusname','products.image as productimage',
                                                'sellers.sellerprofile as sellerimage')
                                                ->join('products','order_vendors.productid','=','products.id')
                                                ->join('order_statuses','order_statuses.id','=','order_vendors.orderstatus')
                                                ->join('sellers','sellers.id','=','order_vendors.sellerid')
                                                ->where('order_vendors.sellerid','=',$seller_id)
                                                ->orderBy('order_vendors.created_at','desc')
                                                ->paginate(6);

        return view('dashboard.seller.home',compact('dashboard'));
    }

    function logout(){
        Auth::guard('seller')->logout();
        return redirect('/');
    }
}
