<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
    	$rules  =   [
                        "id"=>"exists:user_address,id,deleted_at,NULL",
                    ];
        $msg    =   [
                        "rating.required"=>"Rating is required"
                    ];
        $user_id=auth()->guard('api')->user()->id;
        if($request->id)
        {
        	$id =   $request->id;
        	$address=UserAddress::where("user_id",$user_id)->where("id",$id)->first();
            if($address->country != null){
                $country_name               =   DB::table('country')->where('iso',$address->country)->first();
                if($country_name){
                    $address->country       =   $country_name->nicename;
                    $address->iso           =   $country_name->iso;
                    $address->phonecode     =   $country_name->phonecode;
                } else {
                    $address->country   =   null;
                }
            }
        }
        else
        {
        	$address=UserAddress::where("user_id",$user_id)->get();
            foreach($address as $addr){
                if($addr->country != null){
                    $country_name       =   DB::table('country')->where('iso',$addr->country)->first();
                    if($country_name){
                        $addr->country          =   $country_name->nicename;
                        $addr->iso           =   $country_name->iso;
                        $addr->phonecode     =   $country_name->phonecode;
                    } else {
                        $addr->country   =   null;
                    }
                }
            }
        }
        if(isset($address))
        {
        	$return['status']   =   true;
            $return['data']     =   $address;
            $return['message']  =   "Your addresses listed successfully";
        }
        else
        {
            $return['status']=true;
            $return['message']="Sorry no records found ";
        }
        return $return;
    }

    public function add(Request $request)
    {
    	 $rules =   [
                        "first_name"=>"required",
                        "villa"=>"required",
                        "phone"=>"required",
                        "city"=>"required",
                        "country"=>"required",
                        "address"=>"required"
                    ];
        $msg    =   [
                        "address.required"=>"Address is required"
                    ];

        $validator=Validator::make($request->all(), $rules, $msg);

        if($validator->fails())
        {
        	$return['status']=false;
		    $return['message']=implode( ", ",$validator->errors()->all());
        }
        else
        {
	        $user_id       =    auth()->guard('api')->user()->id;
            $address_title =    1;
            $first_name    =    $request->first_name;
            $last_name     =    $request->last_name;
            $phone         =    $request->phone;
            $villa         =    $request->villa;
	        $address       =    $request->address;
	        $city         =    $request->city;
            $country       =    $request->country;
            $default_addr  =    $request->has('default_addr')?$request->default_addr:'0';
            $billing_addr  =    $request->has('bill_addr')?$request->bill_addr:'0';
			$time          =    Carbon::now();

            $insert_array  =    [
                                    'user_id'=>$user_id,
                                    "addr_title"=>$address_title,
                                    'first_name'=>$first_name,
                                    'last_name'=>$last_name,
                                    'phone_num'=>$phone,
                                    'villa'=>$villa,
                                    'address'=>$address,
                                    'city'=>$city,
                                    "country"=>$country,
                                    'default_addr'=>$default_addr ,
                                    'billing_addr'=>$billing_addr,
                                    'created_at'=> $time,
                                    "updated_at"=>$time
                                ];
            if($request->default_addr== '1')
            {
                $check     =    UserAddress::where("user_id",$user_id)->where('default_addr','1')
                                ->select('id','default_addr')->get();
                if(count($check) > 0)
                {
                    foreach($check as $c)
                    {
                        UserAddress::where("id",$c->id)->update(["default_addr"=>'0',"updated_at"=>$time]);
                    }
                }
                $add_address    =   UserAddress::insertGetId($insert_array);
            }
            else{
                $add_address    =   UserAddress::insertGetId($insert_array);
            }


		    if($add_address)
	        {
                $c_address=UserAddress::where('id',$add_address)->get();

                $return['status']  =       true;
                $return['data']=       $c_address;
                $return['message']    =       "Your address Added successfully";
	        }
	        else
	        {
	        	$return['status']   = false;
	            $return['message']     = "Sorry error occured";
	        }
	    }
	    return $return;
    }

	public function update(Request $request)
	{
		$rules=[
            "id"=>"required|exists:user_addresses,id",
            "first_name"=>"required",
            "villa"=>"required",
            "phone"=>"required",
            "city"=>"required",
            "country"=>"required",
            "address"=>"required"
            ];
        $msg=[

             ];
              $validator=Validator::make($request->all(), $rules, $msg);

        if($validator->fails())
        {
            $return['status']=false;
            $return['message']= implode( ", ",$validator->errors()->all());
            return $return;
        }
        else
        {
            $id             =   $request->id;
            $user_id        =   auth()->guard('api')->user()->id;
            $user_addres    =   $request->address;
            $time           =   Carbon::now();

            // $address_title  =   1;
            $name           =   $request->first_name;
            $phone          =   $request->phone;
            $villa          =    $request->villa;
	        $address        =    $request->address;
	        $city           =    $request->city;
            $country        =    $request->country;
            // $default_addr   =    $request->has('default_addr')?$request->default_addr:'0';
            // $billing_addr   =    $request->has('bill_addr')?$request->bill_addr:'0';
           // $default  = $request->default_addr;

           $update_address_arr  =   [
                                        // "addr_title"=>$address_title,
                                        'first_name'=>$name,
                                        'last_name'=>$request->last_name,
                                        'phone_num'=>$phone,
                                        'villa'=>$villa,
                                        'address'=>$address,
                                        'city'=>$city,
                                        "country"=>$country,
                                        // 'default_addr'=>$default_addr ,
                                        // 'billing_addr'=>$billing_addr,
                                        "updated_at"=>$time
                                    ];
        //    if($request->default_addr=='1')
        //     {
        //         $address        =   UserAddress::where('user_id',$user_id)->where('default_addr','1')->get();
        //         if(isset($address) && count($address)>0)
        //         {
        //             $update_address =   UserAddress::where("user_id",$user_id)
        //                         ->update(["default_addr"=>'0',"updated_at"=>$time]);
        //         }
        //         $update_address =   UserAddress::where("id",$id)
        //                             ->update($update_address_arr);
        //     }
        //     else
        //     {
                $update_address =   UserAddress::where("id",$id)
                                    ->update($update_address_arr);

            // }
           $c_address=UserAddress::where('id',$id)->get();
           if($update_address)
           {
               $return['status']=true;
               $return['data']=$c_address;
               $return['message']="user address has updated sucessfully";
           }
           else
           {
               $return['status']=false;
               $return['message']="Sorry error occured";
           }
        }
        return $return;
	}

    public function UpdateDefaultAddress($id){
        $address_id = UserAddress::find($id);
        if ($address_id == null) {
            $return['status']=true;
            $return['message']="Sorry no records found ";
        } else {
            $time           =    Carbon::now();
            $api_token      =   request()->header('User-Token');
	        $user_id        =   auth()->guard('api')->user()->id;
            $address        =   UserAddress::where('user_id',$user_id)->where('default_addr','1')->get();
            if(isset($address) && count($address)>0)
            {
                UserAddress::where("user_id",$user_id)
                            ->update(["default_addr"=>'0',"updated_at"=>$time]);
            }

            UserAddress::where("id",$id)
                            ->update(["default_addr"=>'1',"updated_at"=>$time]);

            $address=UserAddress::where("user_id",$user_id)->get();
            if(isset($address))
            {
                $return['status']=true;
                $return['data']=$address;
                $return['message']="Your addresses listed successfully";
            }
            else
            {
                $return['status']=true;
                $return['message']="Sorry no records found ";
            }
            return $return;
        }
    }


    public function delete(Request $request)
    {
    	 $rules=[
    	 	"id"=>"required|exists:user_addresses,id",
            ];
        $msg=[
            "id.required"=>"ID is required"
             ];
        $validator=Validator::make($request->all(), $rules, $msg);

        if($validator->fails())
        {
        	$return['status']=false;
		    $return['message']=implode( ", ",$validator->errors()->all());
        }
        else
        {
	        $user_id=auth()->guard('api')->user()->id;
	        $id=$request->id;
	        $delete=UserAddress::where("id",$id)->where("user_id",$user_id)->delete();
	        if($delete)
	        {
	        	$return['status']=true;
	        	$return['message']="Your address deleted successfully";
	        }
	        else
	        {
	        	$return['status']=false;
	            $return['message']="Sorry error occured";
	        }

	    }
	    return $return;
    }

    public function Countries(){
        try{
            $countries  =   DB::table('country')->select('iso','nicename','phonecode')->get();
            $result     =   ['status'=>true,'data'=>$countries,'message'=>"Countries Listed Successfully"];
        } catch(Exception $e){
            $result     =   ['status'=>false,'message'=>$e->getMessage()];
        }
        return response()->json($result);
    }
}
