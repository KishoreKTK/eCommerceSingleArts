<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    //

    public function ContactUsPost()
    {
        try
        {
            $validator = Validator::make(request()->all(),[
                'first_name' => 'required|max:25',
                'email' =>  'required|email',
                'mobile' => 'required|max:20',
                'message'=> 'required'
            ]);

            if($validator->fails())
            {
                $errors     = implode( ", ",$validator->errors()->all());
                throw new Exception($errors);
            }
            $insert_data                =   request()->all();
            $insert_data['created_at']  =   date('Y-m-d H:i:s');
            $insert_data['updated_at']  =   date('Y-m-d H:i:s');
            $contact_us_insert          =   DB::table('contact_us')->insert($insert_data);
            if(!$contact_us_insert){
                throw new Exception("Something Went Wrong. Try Again Later");
            }
            $result = ['status'=>true, "message"=>"Thank You For Contacting Us. Get in Touch With You Soon"];
        } catch (\Exception $e) {
            $result = ['status'=>false, "message"=>$e->getMessage()];
        }
        return response()->json($result);
    }
}
