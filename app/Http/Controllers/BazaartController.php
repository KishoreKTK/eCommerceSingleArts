<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\FAQ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
class BazaartController extends Controller
{
    //
    public function HomePage(){
        $blog_posts     =   Blog::orderBy('created_at', 'desc')->where('status','1')->take(3)->get();

        return view('website.index',compact('blog_posts'));
    }

    public function CheckAuthenticated(){
        if( Auth::guard('seller')->check() )
        {
            return redirect()->route('seller.home');
        }
        else
        {
            return redirect()->route('seller.login');
        }
    }

    public function BlogPage(){
        $blog_posts     =   Blog::orderBy('created_at', 'desc')->where('status','1')->paginate(9);
        return view('website.blog',compact('blog_posts'));
    }


    public function BlogDetails($title){
        $check_slug     =   Blog::where('slug',$title)->where('status','1')->first();
        $recent_posts   =   Blog::latest()->where('status','1')->take(10)->get();

        if($check_slug) {
            return view('website.blog-detail',compact('check_slug','recent_posts'));
        } else {
            return abort(404);
        }
    }


    public function faqsupport(){
        $faqlist    = FAQ::select('question','answer')->get();
        $result     = ['status'=>true,'data'=>$faqlist];
        return view('website.faq-support',compact('result'));
    }


    public function TestMail(){
        try{
            Mail::raw('Hello World!', function($msg) {
                $msg->to('ktkkishore@gmail.com')->subject('Test Email');
            });

            echo "Mail Sent sucessfully";
        }
        catch(\Exception $e){
            echo "error : ". $e;
        }
    }
}
