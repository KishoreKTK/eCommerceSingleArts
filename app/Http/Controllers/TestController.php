<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function TestMail(){
        try{
            $test = Mail::raw('Hello World!', function($msg) {
                $msg->to('ktkkishore@gmail.com')->subject('Test Email');
            });
            echo "Mail Sent sucessfully";
        }
        catch(\Exception $e){
            echo "error : ". $e;
        }
    }
}
