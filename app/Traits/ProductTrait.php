<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
Use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\Attributes;
use App\Models\AttributeCategory;
use App\Models\SubAttribute;
use App\Models\Seller;
use App\Models\Categories;
use App\Models\ProductStocks;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
trait ProductTrait
{
//=========================================================================//
//============ Commonly Used Functions & Select Box Queries ===============//
//=========================================================================//

    function currentdatetime(){
        $dt 			=   new \DateTime();
		$datetime		=   $dt->format('Y-m-d H:i:s');
        return $datetime;
    }

    function generateUniqueNumber($prestr, $table_model_name)
    {
        $dt         = new \DateTime();
        $date       = $dt->format('ymd');
        $orderObj   = DB::table($table_model_name)->select('product_entry as unique_num')->latest('id')->first();
        if ($orderObj) {
            $orderNr            =   $orderObj->unique_num;
			$removed1char       =   substr($orderNr,9);
            $dateformat         =   str_pad($removed1char + 1, 3, "0", STR_PAD_LEFT);
            $uid                =   $date. $dateformat;
            $generateOrder_nr   =   $prestr.$uid;
        } else {
            $generateOrder_nr   =   $prestr.$date . str_pad(1, 3, "0", STR_PAD_LEFT);
        }
        return $generateOrder_nr;
	}

    function SelectCategoryList(){
        $select_options  =   DB::table('categories')->select('id','name')
                                ->where('is_active','=','1')->get();
        return $select_options;
    }

    function SelectSellerList(){
        $select_options  =   DB::table('sellers')->select('id','sellername')
                                ->where('is_active','=','1')->get();
        return $select_options;
    }

    function SelectAttributeList($category_id){
        $attributes     =   DB::table('attributes')
                            ->select('attributes.id','attributes.name')
                            ->join('attribute_category','attributes.id','=','attribute_category.attr_id')
                            ->where('is_active','1');
        if($category_id != 0){
            $filtered_attributes = $attributes->where('attribute_category.category_id','=',$category_id)->get();
        }
        else{
            $filtered_attributes = $attributes->get();
        }

        $select_options =   [];
        foreach($filtered_attributes as $key=>$attr){
            $select_options[$key]['id']         =   $attr->id;
            $select_options[$key]['name']       =   $attr->name;
            $select_options[$key]['sub_attr']   =   DB::table('sub_attributes')
                                                    ->where('status','1')
                                                    ->where('attr_id',$attr->id)
                                                    ->where('custom','0')
                                                    ->get();
            $select_options[$key]['custom_attr']=   DB::table('sub_attributes')
                                                    ->where('status','1')
                                                    ->where('attr_id',$attr->id)
                                                    ->where('custom','1')
                                                    ->get();
        }
        return $select_options;
    }

//=========================================================================//
//========================== Attribute Works  =============================//
//=========================================================================//

    function AttributeList(){
        $login_type =   session()->get('login_type');
        $attribute  =   DB::table('attributes')
                        ->select('attributes.id','attributes.name','attributes.is_active',
                        'AC_table.categories as cat_name',"SA_table.sub_attr_name")
                        ->leftjoin(DB::raw('(SELECT
                                SA.attr_id, GROUP_CONCAT(SA.sub_attr_name)  AS sub_attr_name
                                FROM sub_attributes AS SA GROUP BY SA.attr_id) AS SA_table'),
                            function($join)
                            {
                                $join->on('attributes.id', '=', 'SA_table.attr_id');
                            })
                        ->leftjoin(DB::raw('(SELECT
                                AC.attr_id, GROUP_CONCAT(C.name)  AS categories
                            FROM
                            attribute_category AS AC
                            JOIN categories AS C
                                ON C.id = AC.category_id
                            GROUP BY AC.attr_id
                            ) AS AC_table'),
                            function($join)
                            {
                            $join->on('attributes.id', '=', 'AC_table.attr_id');
                        });
        if($login_type == 'admin'){
            $attr_list =    $attribute->where('attributes.is_active','!=','2')->get();
        }
        else{
            $attr_list =    $attribute->where('attributes.is_active','=','1')->get();
        }
        // dd($attr_list);
        return $attr_list;
    }

    // Create Attribute & $request Attribute;
    function CreateAttribute($input)
    {
        $login_type     =   session()->get('login_type');
        $insert_data    =   [
                                'name'          => $input['name'],
                                'created_at'    => $this->currentdatetime(),
                                'updated_at'    => $this->currentdatetime(),
                            ];
        if($login_type == 'admin'){
            $insert_data['is_active'] =     "1";
        }
        else{
            $insert_data['is_active'] =     "3";
        }

        $attribute_id = Attributes::insertGetId($insert_data);
        if($attribute_id){
            //Attribute Category Insert
            $insert_attribute_categeory = [];
            foreach($input['category_id'] as $key=>$cat_id){
                $insert_attribute_categeory[$key]['attr_id']        = $attribute_id;
                $insert_attribute_categeory[$key]['category_id']    = $cat_id;
                $insert_attribute_categeory[$key]['created_at']     = $this->currentdatetime();
                $insert_attribute_categeory[$key]['updated_at']     = $this->currentdatetime();
            }

            AttributeCategory::insert($insert_attribute_categeory);

            // Sub Category Insert
            $insert_sub_attributes = [];
            if($input['suggesstions'] != null)
            {
                $sub_attr_arr   = explode(',', $input['suggesstions']);
                foreach($sub_attr_arr as $key=>$sub_attr){
                    $insert_sub_attributes[$key]['attr_id']         = $attribute_id;
                    $insert_sub_attributes[$key]['sub_attr_name']   = trim(Str::lower($sub_attr));
                    $insert_sub_attributes[$key]['created_at']      = $this->currentdatetime();
                    $insert_sub_attributes[$key]['updated_at']      = $this->currentdatetime();
                }
            }
            else
            {
                $insert_sub_attributes['attr_id']         = $attribute_id;
                $insert_sub_attributes['sub_attr_name']   = $input['name'];
                $insert_sub_attributes['summary']         = $input['summary'];
                $insert_sub_attributes['custom']          = "1";
                $insert_sub_attributes['created_at']      = $this->currentdatetime();
                $insert_sub_attributes['updated_at']      = $this->currentdatetime();
            }

            // dd($insert_sub_attributes);
            SubAttribute::insert($insert_sub_attributes);

            $result =   ['status'=>true, 'message'=>"New Attribute Inserted Successfully"];
        }
        else{
            $result =   ['status'=>true, 'message'=>"Error in Inserting New Attribute"];
        }
        return $result;
    }

    // Edit Attrubutes & SubAttributes

    // Change Status(Active|Inactive|Suspend) for Attributes & SubAttributes & Approve Attributes

    // Delete Attributes & SubAttributes


//=========================================================================//
//============================ Products Work  =============================//
//=========================================================================//

    function InsertProductBannerImages($product_id,$banner_images, $banner_img_url)
    {
        $product_banner = [];
        foreach($banner_images as $key=>$images)
        {
            $bannerimage_name     = str_replace(' ', '_',$key.'_'.$images->getClientOriginalName());
            $images->move(public_path($banner_img_url), $bannerimage_name);
            $product_banner[$key]['product_id'] = $product_id;
            $product_banner[$key]['image_urls'] = $banner_img_url.$bannerimage_name;
            $product_banner[$key]['created_at'] = $this->currentdatetime();
            $product_banner[$key]['updated_at'] = $this->currentdatetime();
        }

        return $product_banner;

    }

    function InsertProductAttributes($product_id, $product_attr)
    {
        $product_insert_arr = [];
        $loop_count     = 0;
        $unique_entry_id    = $this->generateUniqueNumber("PDE",'product_stocks');

        foreach($product_attr as $key=>$attr)
        {
            $loop_count++;
            if(Arr::exists($product_attr[$key], 'custom_values'))
            {
                $product_insert_arr[$loop_count]['product_id']       = $product_id;
                $product_insert_arr[$loop_count]['attribute_id']     = $attr['attribute_id'];
                $product_insert_arr[$loop_count]['sub_attr_id']      = $attr['sub_attr_id'];
                $product_insert_arr[$loop_count]['custom_values']    = $attr['custom_values'];
                $product_insert_arr[$loop_count]['product_entry_id'] = $unique_entry_id;
                $product_insert_arr[$loop_count]['created_at']       = $this->currentdatetime();
                $product_insert_arr[$loop_count]['updated_at']       = $this->currentdatetime();
            }
            else
            {
                if(Arr::exists($product_attr[$key], 'sub_attr_id'))
                {
                    foreach ($attr['sub_attr_id'] as $e => $value)
                    {
                        $loop_count++;
                        $product_insert_arr[$loop_count]['product_id']           = $product_id;
                        $product_insert_arr[$loop_count]['attribute_id']         = $attr['attribute_id'];
                        $product_insert_arr[$loop_count]['sub_attr_id']          = $value;
                        $product_insert_arr[$loop_count]['custom_values']        = null;
                        $product_insert_arr[$loop_count]['product_entry_id']     = $unique_entry_id;
                        $product_insert_arr[$loop_count]['created_at']           = $this->currentdatetime();
                        $product_insert_arr[$loop_count]['updated_at']           = $this->currentdatetime();
                    }
                }
            }
        }
        return $product_insert_arr;
    }

    function InserProductStock($product_id, $price_stock)
    {
        $product_stock      =    [];
        $entry_ids          =   ProductAttribute::select('product_entry_id')
                                ->where('product_id',$product_id)
                                ->groupby('product_entry_id')->get();

        foreach ($entry_ids as $key => $value) {
            $product_stock[$key]['product_id']    = $product_id;
            $product_stock[$key]['product_entry'] = $value->product_entry_id;
            $product_stock[$key]['product_price'] = $price_stock['price'];
            $product_stock[$key]['quantities']    = $price_stock['available_qty'];
            $product_stock[$key]['created_at']    = $this->currentdatetime();
            $product_stock[$key]['updated_at']    = $this->currentdatetime();
        }

        return $product_stock;
    }

    function ProductLists(){

    }

    function GetProductDetail($id)
    {
        try
        {
            Product::findorfail($id);
            $product_details            =   DB::table('products')->join('categories','products.category_id','=','categories.id')
                                            ->join('sellers','products.seller_id','=','sellers.id')
                                            ->join('product_images','products.id','=','product_images.product_id')
                                            ->join('product_attributes','products.id','=','product_attributes.product_id')
                                            ->join('product_stocks','products.id','=','product_stocks.product_id')
                                            ->select('products.*','categories.name as categoryname','sellers.sellername',
                                            'product_stocks.product_price','product_stocks.quantities')
                                            ->where('products.id','=',$id)
                                            ->where('product_stocks.price_type','1')
                                            ->groupby('products.id')->first();

            $product_images             =   DB::table('product_images')->select('image_urls')->where('product_id','=',$id)->get();
            $product_banner_img         =   [];
            $product_banner_img[]       =   $product_details->image;
            foreach ($product_images as $key => $image) {
                $product_banner_img[]   =   $image->image_urls;
            }

            $product_details->like_count    = DB::table('user_favourites')->where('product_id',$id)->count();
            $product_details->save_count    = DB::table('user_saved_products')->where('product_id',$id)->count();

            $total_sold_products            = DB::table('order_vendors')->where('productid',$id)->pluck('prod_qty');
            $sold_count                     = 0;
            foreach ($total_sold_products as $key => $value) {
                $sold_count                 = $sold_count + $value;
            }
            $product_details->order_count = $sold_count;


            $productattributes =    DB::table('product_attributes')->select('attributes.name as attrname'
                                    ,DB::raw('group_concat(sub_attributes.sub_attr_name) as sub_attr_name'),
                                    'sub_attributes.custom','product_attributes.custom_values')
                                    ->join('attributes','product_attributes.attribute_id','=','attributes.id')
                                    ->leftjoin('sub_attributes','product_attributes.sub_attr_id','=','sub_attributes.id')
                                    ->where('product_attributes.product_id','=',$id)
                                    ->groupBy('product_attributes.attribute_id')->get();
            $attribute  = [];
            foreach($productattributes as $attr){
            if($attr->custom == 0){
            // $sub_attr_arr   =   explode(',',$attr->sub_attr_name);
            // $sub_attr_count =   count($sub_attr_arr);
            // if($sub_attr_count > 1){
            //     $attrval =  'Available';
            // }else{
            //     $attrval =  $attr->sub_attr_name;
            // }
            $attrval =  $attr->sub_attr_name;
            }else{
            // $custom_attr_arr   =   explode(',',$attr->custom_values);
            // $custom_attr_count =   count($custom_attr_arr);
            // if($custom_attr_count > 1){
            //     $attrval =  'Custom Values';
            // }else{
            //     $attrval =  $attr->custom_values;
            // }
            $attrval =  $attr->custom_values;
            }
            $attribute[$attr->attrname]  = $attrval;
            }
            // $product->product_attributes = $attribute;
            $product_stocks     =   DB::table('product_stocks')->where('product_id',$id)->get();
            $data = ['product_det'=> $product_details, 'product_images'=> $product_banner_img,
                    'attributes'=>$attribute, 'product_stocks'=>$product_stocks];

            $result =   ['status'=>true, 'data'=>$data];
        } catch (\Exception $th) {
            $result =   ['status'=>false, 'message'=>$th->getMessage()];
        }
        // dd($result);
        return $result;
    }

    function GetCutomPrices($id){
        try
        {
            Product::findorfail($id);
            $stocklist          =   DB::table('product_stocks')
                                    ->select(
                                    'product_stocks.id',
                                    'product_stocks.price_type',
                                    'product_stocks.product_price',
                                    'product_stocks.quantities',
                                    'product_stocks.product_entry',
                                    'PA_table.product_id',
                                    'PA_table.attribute_ids',
                                    'PA_table.sub_attr_ids',
                                    'PA_table.product_entry_id',
                                    'PA_table.custom_values',
                                    'PA_table.sub_attr_names',
                                    'PA_table.custom',
                                    'PA_table.attr_names')
                                    ->leftjoin(DB::raw('(SELECT
                                            PA.`product_id`,
                                            PA.`product_entry_id`,
                                            GROUP_CONCAT(PA.`attribute_id`) AS attribute_ids,
                                            GROUP_CONCAT(PA.`sub_attr_id`) AS sub_attr_ids,
                                            GROUP_CONCAT(IFNULL(PA.`custom_values`, 0)) AS custom_values,
                                            GROUP_CONCAT(SA.`sub_attr_name`) AS sub_attr_names,
                                            GROUP_CONCAT(SA.`custom`) AS custom,
                                            GROUP_CONCAT(DISTINCT(A.`name`)) AS attr_names
                                        FROM
                                            product_attributes AS PA
                                            JOIN sub_attributes AS SA
                                            ON SA.`id` = PA.`sub_attr_id`
                                            JOIN attributes AS A
                                            ON A.`id` = PA.`attribute_id`
                                        WHERE PA.`product_id` = '.$id.'
                                        GROUP BY PA.`product_entry_id`) AS PA_table'),
                                    function($join)
                                    {
                                        $join->on('product_stocks.product_entry', '=', 'PA_table.product_entry_id');
                                    })->where('product_stocks.product_id',$id)->get();

            // print("<pre>");print_r($stocklist);die;

            $product_det        =   Product::where('products.id',$id)
                                    ->select('products.id','product_stocks.product_price','product_stocks.quantities')
                                    ->join('product_stocks','product_stocks.product_id','=','products.id')
                                    ->where('product_stocks.price_type','1')->first();

            $product_attributes =       DB::table('product_stocks')
                                        ->select('product_stocks.product_price',
                                                'product_stocks.quantities',
                                                'product_stocks.product_entry',
                                                'PA_table.product_id',
                                                'PA_table.attribute_id',
                                                'PA_table.sub_attr_ids',
                                                'PA_table.product_entry_id',
                                                'PA_table.custom_values',
                                                'PA_table.sub_attr_names',
                                                'PA_table.custom',
                                                'PA_table.attr_name')
                                        ->leftjoin(DB::raw('(SELECT
                                                    PA.`product_id`,
                                                    PA.`attribute_id`,
                                                    PA.`product_entry_id`,
                                                    GROUP_CONCAT(DISTINCT(PA.`sub_attr_id`)) AS sub_attr_ids,
                                                    PA.`custom_values`,
                                                    GROUP_CONCAT(DISTINCT (SA.`sub_attr_name`)) AS sub_attr_names,
                                                    SA.`custom`,
                                                    A.`name` AS attr_name
                                                FROM
                                                    product_attributes AS PA
                                                    JOIN sub_attributes AS SA
                                                    ON SA.`id` = PA.`sub_attr_id`
                                                    JOIN attributes AS A
                                                    ON A.`id` = PA.`attribute_id`
                                                WHERE PA.`product_id` = '.$id.'
                                                GROUP BY PA.`attribute_id`) AS PA_table'),
                                        function($join)
                                        {
                                            $join->on('product_stocks.product_entry', '=', 'PA_table.product_entry_id');
                                        })
                                        ->where('product_stocks.product_id',$id)
                                        ->where('price_type','1')
                                        ->get();

                                // print("<pre>");print_r($product_attributes);die;
            // $selected_attributes    = [];
            // foreach ($product_attributes as $key => $attr) {

            // }
            $data   =   [
                            'product_stocks'=>$stocklist,
                            'product_det'=>$product_det,
                            'product_attributes'=> $product_attributes
                        ];

            $result =   ['status'=>true, 'data'=>$data];
        } catch (\Exception $th) {
            $result =   ['status'=>false, 'message'=>$th->getMessage()];
        }
        return $result;
    }

    function AddCustomPrice($input)
    {
        try {
            $price_combo        = $input['price_combo'];
            // dd($price_combo);
            $unique_entry_id    = $this->generateUniqueNumber("PDE",'product_stocks');
            $product_attributes = [];
            // print("<pre>");print_r($price_combo);die;
            foreach ($price_combo as $key => $combo) {
                if(array_key_exists('attribute_id',$combo)){
                    if(array_key_exists('custom_values',$combo)){
                        $combo['custom_values']  = $combo['custom_values'];
                    }else{
                        $combo['custom_values']  = null;
                    }
                    // $get_attr_id
                    $combo['product_entry_id']  = $unique_entry_id;
                    $combo['created_at']        = date('Y-m-d h:i:s');
                    $combo['updated_at']        = date('Y-m-d h:i:s');
                    $product_attributes[$key]   = $combo;
                }
            }

            // dd($product_attributes);
            $insert_prod_data = DB::table('product_attributes')->insert($product_attributes);
            if(!$insert_prod_data){
                throw new Exception("Something Went Wrong in Adding Product Attributes");
            }
            $product_stock                  =   $input['product_stock'];
            $product_stock['product_entry'] =   $unique_entry_id;
            $product_stock['price_type']    =   '2';
            $product_stock['created_at']    =   date('Y-m-d H:i:s');
            $product_stock['updated_at']    =   date('Y-m-d H:i:s');

            $insert_prod_stock  =   ProductStocks::insert($product_stock);
            if(!$insert_prod_stock){
                throw new Exception("Something Went Wrong in Adding Product Stocks");
            }

            $result =   ['status'=>true, 'message'=>"Custom Price Added Successfully"];
        } catch (\Exception $th) {
            $result =   ['status'=>false, 'message'=>$th->getMessage()];
        }
        return $result;
    }

    function featuredproducts(){

        $update_data    =   [
                                'is_featured'       => request()->is_featured,
                                'updated_at'        => $this->currentdatetime()
                            ];
        $approve        =   DB::table('products')
                            ->where('id',request()->product_id)->update($update_data);
        if($approve){
            return back()->with('success','Action Completed Successfully');
        }
        else{
            return back()->with('error','Something Went Wrong');
        }
    }

    function productstatus(){
        $update_data    =   [
                                'status'        => request()->active_status,
                                'updated_at'    => $this->currentdatetime()
                            ];
        $approve        =   DB::table('products')
                            ->where('id',request()->product_id)->update($update_data);
        if($approve){
            return back()->with('success','Action Completed Successfully');
        }
        else{
            return back()->with('error','Something Went Wrong');
        }
    }

    public function ProductReviewList(){}

}
