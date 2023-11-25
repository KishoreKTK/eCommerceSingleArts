<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;

class SellerCategoryController extends Controller
{

    public function CategoryList(){
        $seller_id      =   Auth::guard('seller')->user()->id;
        $categories     =   DB::table('categories')
                            ->leftjoin(DB::raw('(SELECT
                                    p.category_id,
                                    COUNT(p.id) AS productcount
                                FROM
                                products AS p
                                WHERE p.Seller_id ='.$seller_id.'
                                GROUP BY p.category_id
                            ) AS producttbl'),
                            function($join)
                            {
                                $join->on('categories.id', '=', 'producttbl.category_id');
                            })
                            ->select('categories.*',DB::raw("IFNULL(producttbl.productcount, '0') as ProductCount"))
                            ->where('categories.is_active','=','1')
                            ->latest('categories.id')->get();


        $my_category     =  DB::table('categories')
                            ->select('categories.*')
                            ->where('categories.uploaded_by',$seller_id)
                            ->latest('categories.id')->get();


        return view('dashboard.seller.categories.categories',compact('categories','my_category'));
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
            'remarks'       =>  "New Category Request",
            'is_active'     =>  '2',
            'reason'        =>  $request->reason,
            'uploaded_by'   =>  $request->seller_id,
            'approved_at'   =>  null,
            'created_at'    =>  $datetime,
            'updated_at'    =>  $datetime,
        ];
        Categories::create($insert_data);
        return back()->with('success','New Category Added');
    }

}
