<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class CategoryController extends Controller
{

    public function index()
    {
        $categories    =   DB::table('categories')
                            ->leftjoin('sellers','categories.uploaded_by','=','sellers.id')
                            ->leftjoin(DB::raw('(SELECT
                                    p.category_id,
                                    COUNT(p.id) AS productcount
                                FROM
                                products AS p
                                GROUP BY p.category_id
                            ) AS producttbl'),
                            function($join)
                            {
                                $join->on('categories.id', '=', 'producttbl.category_id');
                            })
                            ->select('categories.*',DB::raw("IFNULL(producttbl.productcount, '0') as ProductCount"),'sellers.sellername')
                            ->whereNotIn('categories.is_active',['3','4'])
                            // ->where('categories.is_active','!=','4')
                            ->latest('id')->paginate(20);
        return view('dashboard.admin.categories.index',compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = array(
            'name'   =>    'required|unique:categories',
        );

        $request->validate($rules);

        $dt 			= new \DateTime();
		$datetime		= $dt->format('Y-m-d H:i:s');
        $image_url      = 'Uploads/Categories/';
        $image_name     = time() . '.'.$request->file('image')->getClientOriginalExtension();
        // str_replace(' ', '_', $request->file('image')->getClientOriginalName());
        $request->image->move(public_path($image_url), $image_name);

        $insert_data = [
            'name'          =>  $request->name,
            'image_url'     =>  $image_url.$image_name,
            'description'   =>  $request->description,
            'remarks'       =>  "Added By Admin",
            'reason'        =>  'This Category Needed',
            'is_active'     =>  '1',
            'approved_at'   =>  $datetime,
            'created_at'    =>  $datetime,
            'updated_at'    =>  $datetime,
        ];
        Categories::create($insert_data);
        return back()->with('success','New Category Added');
    }

    public function VerfiyCategory(){
        try{
            $dt 			= new \DateTime();
            $datetime		= $dt->format('Y-m-d H:i:s');
            $UpdateArray = [
                'remarks'       =>  request()->approval_remarks,
                'is_active'     =>  request()->approval_type,
                'approved_at'   =>  $datetime,
            ];
            $update_category    =   DB::table('categories')->where('id',request()->cat_id)
                                    ->update($UpdateArray);
            if($update_category){
                $category_approved = DB::table('categories')->where('id',request()->cat_id)->first();
                $result = ['status'=>true, "data"=>$category_approved];
            }
            else{
                throw new Exception("Error in Approval. Please Try again Later....");
            }
        }
        catch (\Exception $e)
        {
            $result = ['status'=>false, "message"=>$e->getMessage()];
        }
        return response()->json($result);
    }

    public function UpdateCategory(){
        try {
            // print("<pre>");print_r(request()->all());die;
            $update_data = array(
                'name'          => request()->name,
                'description'   => request()->description,
                'updated_at'    => date('Y-m-d H:i:s')
            );

            if(request()->hasFile('image'))
            {
                $old_pic    = Categories::select('image_url')->where('id',request()->category_id)->first();

                $old_pic_path = $old_pic->image_url;

                if($old_pic_path)
                {
                    File::delete($old_pic_path);
                }
                $image_url      = 'Uploads/Categories/';
                $image_name     = time() . '.'.request()->file('image')->getClientOriginalExtension();
                request()->image->move(public_path($image_url), $image_name);
                $update_data['image_url']   = $image_url.$image_name;
            }
            $update         =   Categories::where('id',request()->category_id )
                                ->update($update_data);
            if($update){
                $result   =   ['status'=>true, 'message'=>"Updated Successfully"];
            }
            else{
                throw new Exception('Something Went Wrong. Try Again later');
            }

        } catch (\Exception $e) {
            $result =   ['status'=>false, 'message'=>$e->getMessage()];
        }

        return response()->json($result);
    }

    public function ChangeStatus()
    {
        $dt 			= new \DateTime();
		$datetime		= $dt->format('Y-m-d H:i:s');
        $status         =   request()->is_active;
        $update_data    = [
                            'is_active'     => $status,
                            'updated_at'    => $datetime
                          ];

        $approve        = Categories::where('id',request()->cat_id)->update($update_data);
        if($approve) {
            if($status == '1') {
                $statusname =   'Activated';
            } else if($status == '0') {
                $statusname =   'InActivated';
            } else {
                $statusname =   'Deleted';
            }
            return back()->with('success', $statusname.' Successfully');
        }
        else {
            return back()->with('error','Something Went Wrong');
        }
    }

}
