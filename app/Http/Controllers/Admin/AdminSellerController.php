<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SellerExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

// use Maatwebsite\Excel\Facades\Excel;
class AdminSellerController extends Controller
{

    // Seller Request Page
    public function SellerRequestPage(){
        $new_sellers = Seller::where('approval','=','0')->get();
        return view('dashboard.admin.seller.SellerRequest',compact('new_sellers'));
    }

    // Requested Seller Detail Page
    public function VerifySellerPage($id){
        $seller_det = Seller::where('id','=',$id)->where('approval','=','0')->first();
        if($seller_det){
            return view('dashboard.admin.seller.SellerRequestVerification',compact('seller_det'));
        }
        else{
            return redirect()->route('admin.sellerrequest')->with('error','No Pending Approval for this Seller ID');
        }
    }

    // Seller Approve/Reject Function
    public function Approval(Request $request)
    {
        if($request->sellermembership == '1'){
            $active_status  =   '1';
        }
        else{
            $active_status  =   '0';
        }
        if(is_null($request->actionremarks)){
            $actionremarks  =   null;
        }else{
            $actionremarks  =   $request->actionremarks;
        }
        $dt 			= new \DateTime();
		$datetime		= $dt->format('Y-m-d H:i:s');
        $update_data    = [
                            'commission'    => $request->commission,
                            'remarks'       => $actionremarks,
                            'approval'      => $request->sellermembership,
                            'is_active'     => $active_status,
                            'updated_at'    => $datetime
                          ];
        $approve        = DB::table('sellers')->where('id',$request->sellerid)->update($update_data);
        if($approve){
            return redirect()->route('admin.sellerrequest')->with('success','Action Completed Successfully');
        }
        else{
            return back()->with('error','Something Went Wrong');
        }

    }

    // Seller List Page
    public function SellerList(){
        $seller     =   Seller::select('id',
                        'sellername','selleremail','seller_full_name_buss','seller_buss_type',
                        'is_active','seller_trade_license','seller_trade_exp_dt',
                        'commission')
                        ->where('approval','=','1')->where('is_active','!=','2');

        if(request()->has('business_type') && request()->business_type != ''){
            $seller = $seller->where('seller_buss_type',request()->business_type);
            $business_type = request()->business_type;
        } else {
            $business_type = '';
        }
        if(request()->has('status') && request()->status != ''){
            $seller     =   $seller->where('is_active',request()->status);
            $status     =   request()->status;
        } else {
            $status     =   '';
        }
        if(request()->has('keyword') && request()->keyword != ''){
            $keyword    =   request()->keyword;
            $seller     =   $seller->where(function ($q) use ($keyword) {
                            $q->where("sellername","like",'%'.$keyword.'%')
                            ->orWhere("selleremail","like",'%'.$keyword.'%');
                            });
        } else {
            $keyword    =   '';
        }
        $sellerlist =   $seller->paginate(20);
        return view('dashboard.admin.seller.sellerlist',compact('sellerlist','status','business_type','keyword'));
    }

    // Create New Seller from Admin Panel
    public function CreateNewSeller(Request $request)
    {
        //Validate inputs
        $request->validate([
            'sellername'=>'required',
            'selleremail'=>'required|email|unique:sellers',
            'commission'=>'required',
        ]);

        $input              = $request->except('_token','password_confirmation','password','SellerProfile','seller_trade_license');
        $dt 			    = new DateTime();
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

        $input['password']              =   Hash::make($request->selleremail);
        $input['approval']              =   '1';
        $input['is_active']             =   '1';
        $input['remarks']               =    "Created By Admin";
        $input['register_type']         =    '1';
        $input['created_at']            =    $datetime;
        $input['updated_at']            =    $datetime;

        $save  = DB::table('sellers')->insert($input);

        $data   = ['to'=>$request->selleremail, 'name'=>$name,'mail'=>encrypt($request->email)];

        Mail::send('emails.NewSellerCreated', ["data"=>$data], function($message) use($data) {
                $message->to("kishore@designfort.com");
                $message->from('bazaartportal@gmail.com', 'Bazaart');
                $message->subject('Welcome to Bazaart');
        });
        if($save) {
            return redirect()->back()->with('success','Success, Notification Mail Sent to Seller');
        }else{
            return redirect()->back()->with('fail','Something went Wrong, failed to register');
        }
    }

    // EditSeller Page
    public function EditSeller($sellerid){
        $sellerdet  =   Seller::findorfail($sellerid);
        return view('dashboard.admin.seller.editSeller',compact('sellerdet'));
    }

    // // Edit Seller Action
    public function UpdateSeller(Request $request   ){
        $request->validate([
            'sellername'=>'required',
            'selleremail'=>'required|email|unique:sellers,id,'. $request->seller_id,
            'commission'=>'required',
        ]);
        $update_data        =   $request->except('_token','seller_id','SellerProfile','seller_trade_license');

        $name               =   $request->sellername;
        if(request()->hasFile('SellerProfile'))
        {
            $old_pic    = Seller::where('id',request()->seller_id)->first();
            $old_pic_path = $old_pic->sellerprofile;
            if($old_pic_path)
            {
                File::delete($old_pic_path);
            }
            $sellerimageurl     =   'Uploads/Sellers/'.str_replace(' ', '_', $name).'/profile';
            $sellername         =   time().'_'.str_replace(' ', '_',$request->file('SellerProfile')->getClientOriginalName());
            $request->SellerProfile->move(public_path($sellerimageurl), $sellername);
            $update_data['sellerprofile']         =   $sellerimageurl.'/'.$sellername;
        }

        if(request()->hasFile('seller_trade_license'))
        {
            $old_pic    = Seller::where('id',request()->seller_id)->first();
            $old_pic_path = $old_pic->seller_trade_license;
            if($old_pic_path)
            {
                File::delete($old_pic_path);
            }
            $sellerlicense      = 'Uploads/Sellers/'.str_replace(' ', '_', $name).'/tradelicense';
            $tradename          = time().'_'.str_replace(' ', '_',$request->file('seller_trade_license')->getClientOriginalName());
            $request->seller_trade_license->move(public_path($sellerlicense), $tradename);
            $update_data['seller_trade_license']  =   $sellerlicense.'/'.$tradename;
        }
        $update_data['updated_at']  =   date('Y-m-d H:i:s');

        // dd($update_data);
        $update                     =   Seller::where('id',$request->seller_id)->update($update_data);
        if($update){
            return redirect()->route('admin.SellerList')->with('success','Seller Updated Completed Successfully');
        }
        else{
            return redirect()->back()->with('error','Something Went Wrong.');
        }
    }

    // Active | Inactive | Delete Seller Action
    public function ChangeSellerStatus(Request $request){
        $dt 			= new \DateTime();
		$datetime		= $dt->format('Y-m-d H:i:s');
        $update_data    = [
                            'is_active'     => $request->active_status,
                            'updated_at'    => $datetime
                          ];
                        //   print("<pre>");print_r($request->all());die;
        $approve        = DB::table('sellers')->where('id',$request->sellerid)->update($update_data);
        if($approve){
            return back()->with('success','Action Completed Successfully');
        }
        else{
            return back()->with('error','Something Went Wrong');
        }
    }

    // View Seller Detail
    public function SellerDet($id)
    {
        Seller::where('is_active','!=','2')->findorfail($id);
        $seller_det         =   Seller::where('id','=',$id)->where('approval','=','1')->where('is_active','!=','2')->first();
        $product_list       =   DB::table('products')
                                ->select('products.id','products.name','products.image','product_price as price',
                                        'quantities as available_qty','is_featured','categories.name as categoryname')
                                ->join('categories','products.category_id','=','categories.id')
                                ->join('product_stocks','product_stocks.product_id','=','products.id')
                                ->where('seller_id',$id)
                                ->where('status','1')
                                ->get();
        $seller_categories  =   DB::table('categories')
                                ->select('categories.*')
                                ->where('categories.uploaded_by',$id)
                                ->latest('categories.id')->get();

        $seller_transactions= ["status"=>false,"Message"=> "No Transactions Yet"];

        return view('dashboard.admin.seller.ViewSeller',compact('seller_det','product_list',
        'seller_categories','seller_transactions'));
    }

    public function exportSellers(){
        DB::statement(DB::raw('set @rownum=0'));
        $seller     =   Seller::select(
                        DB::raw("(@rownum:=@rownum + 1) AS sno"),
                        'sellername','selleremail','mobile','sellerprofile',
                        'sellerabout','seller_buss_type',
                        DB::raw("IFNULL(seller_full_name_buss, '-') as seller_full_name_buss"),
                        DB::raw("IFNULL(seller_trade_license, '-') as seller_trade_license"),
                        DB::raw("IFNULL(seller_trade_exp_dt, '-') as seller_trade_exp_dt"),
                        DB::raw("
                            (
                                CASE
                                    WHEN is_active='0' THEN 'Inactive'
                                    WHEN is_active='1' THEN 'Active'
                                    ELSE '-'
                                END
                        ) AS is_active"),
                        DB::raw("
                            (
                                CASE
                                    WHEN approval='1' THEN 'Approved'
                                    WHEN approval='2' THEN 'Rejected'
                                    ELSE '-'
                                END
                        ) AS approval"),
                        'commission','remarks','created_at')
                        ->where('approval','=','1')->where('is_active','!=','2');

        if(request()->has('business_type') && request()->business_type != ''){
            $seller = $seller->where('seller_buss_type',request()->business_type);
        }

        if(request()->has('status') && request()->status != ''){
            $seller     =   $seller->where('is_active',request()->status);
        }

        if(request()->has('keyword') && request()->keyword != ''){
            $keyword    =   request()->keyword;
            $seller     =   $seller->where(function ($q) use ($keyword) {
                            $q->where("sellername","like",'%'.$keyword.'%')
                            ->orWhere("selleremail","like",'%'.$keyword.'%');
                            });
        }
        $sellerlist =   $seller->get();

        foreach($sellerlist as $sellers){
            $sellers->sellerprofile =   asset($sellers->sellerprofile);
            if($sellers->seller_buss_type == 'Business'){
                $sellers->seller_trade_license =   asset($sellers->seller_trade_license);
            } else {
                $sellers->seller_trade_license =   '-';
            }
            $sellers->created_at  =   Carbon::parse($sellers->created_at)->toFormattedDateString();
        }

        return Excel::download(new SellerExport($sellerlist), 'SellerReport.xlsx');
    }

}
