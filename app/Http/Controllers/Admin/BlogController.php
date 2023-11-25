<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function index(){
        $blogs       =   Blog::where('status','!=','2')->latest()->get();
        return view('dashboard.admin.blog.index',compact('blogs'));
    }

    public function AddBlog(){
        $data           =   request()->except('_token','files','blog_image');
        $slug           =   trim(Str::lower(str_replace(' ', '-',request()->title)));
        $data['slug']   =   $slug;
        $check_exists   =   Blog::where('slug',$slug)->exists();
        if(!$check_exists){

            $image_url      = 'Uploads/Blog/'.$slug.'/';

            // if(request()->has('files')){
            //     $filename     = time() . '.'.request()->file('files')->getClientOriginalExtension();
            //     request()->files->move(public_path($image_url), $filename);
            // }

            if(request()->has('blog_image')){
                $image_name     = time() . '.'.request()->file('blog_image')->getClientOriginalExtension();
                request()->blog_image->move(public_path($image_url), $image_name);
                $data['blog_image'] =   $image_url.$image_name;
            }

            $insert         =   Blog::create($data);
            if($insert == true){
                $result = ['status'=>true, 'message'=>"Inserted Succssfully"];
            }else{
                $result = ['status'=>false, 'message'=>"Somthing Went Wrong Try Again Later"];
            }
        } else{
            $result = ['status'=>false, 'message'=>"Blog Already Exists"];
        }
        if($result['status'] == true){
            return redirect()->back()->with('success',$result['message']);
        }else{
            return redirect()->back()->with('error',$result['message']);
        }
    }

    public function ChangeStatus(){
        $update = Blog::where('id',request()->post_id)->Update(['status'=>request()->active_status,'updated_at'=>date('Y-m-d H:i:s')]);
        if($update){
            if(request()->active_status){
                $statusname =   'Activated';
            } else {
                $statusname =   'Inactivated';
            }
            return redirect()->back()->with('success', $statusname.' Successfully');
        }else{
            return redirect()->back()->with('error','Something Went Wrong Try Again Later');
        }

    }

    public function Deletepost(){
        $delete = Blog::where('id',request()->post_id)->Update(['status'=>'2','updated_at'=>date('Y-m-d H:i:s')]);
        if($delete)
            return redirect()->back()->with('success','Deleted Successfully');
        else
            return redirect()->back()->with('error','Something Went Wrong Try Again Later');
    }

    public function UpdateBlog()
    {
        try{
            $check_id   =   Blog::where('id',request()->post_id)->first();
            if(!$check_id) {
                throw new Exception("please check Post ID");
            }
            $slug           =   trim(Str::lower(str_replace(' ', '-',request()->title)));
            if($check_id->slug != $slug){
                $check_exists   =   Blog::where('slug',$slug)->exists();
                if($check_exists){
                    throw new Exception("Post Title Already Exists");
                }
            }

            $update_data    =   request()->except("_token", "post_id");
            if(request()->hasFile('blog_image'))
            {
                $old_pic_path = $check_id->blog_image;
                if($old_pic_path)
                {
                    File::delete($old_pic_path);
                }

                $slug           =   $check_id->slug;
                $image_url      =   'Uploads/Blog/'.$slug.'/';
                $image_name     =   time() . '.'.request()->file('blog_image')->getClientOriginalExtension();
                request()->blog_image->move(public_path($image_url), $image_name);
                $update_data['blog_image']   = $image_url.$image_name;
            }
            $update_data['slug']   =   $slug;

            $update_data['updated_at']    =   date('Y-m-d H:i:s');
            $update     =   Blog::where('id',request()->post_id)->Update($update_data);
            if($update) {
                $result =   ['status'=>true,'message'=>'Post Updated Succesfully'];
            } else {
                throw new Exception('Something went Wrong Try Updating Later');
            }
        }catch(Exception $e){
            $result =   ['status'=>false,'message'=>$e->getMessage()];
        }
        if($result['status'] == true){
            return redirect()->back()->with('success',$result['message']);
        } else {
            return redirect()->back()->with('error',$result['message']);
        }
    }

}
