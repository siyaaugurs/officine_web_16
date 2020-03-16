<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsCarCompatible extends Model
{
    protected  $table = "products_car_compatibles";
    protected $fillable = ['id', 'product_id', 'maker', 'model', 'version' , 'group' , 'all_group' , 'sub_group', 'all_sub_group', 'item' , 'all_item', 'our_time', 'k_time', 'status', 'type' , 'item_number' , 'deleted_at' , 'created_at' , 'updated_at' ];
    
    public static function add_custom_car_compatible($request , $products_id){
	    if($request->groups == 'all'){
            $groups = 0;
            $all_groups = 1;
        } else {
            $all_groups = 0;
            $groups = $request->groups;
        }
        if($request->sub_groups == 'all'){
            $sub_groups = 0;
            $all_sub_groups = 1;
        } else {
            $all_sub_groups = 0;
            $sub_groups = $request->sub_groups;
        }
        if($request->items == 'all'){
            $items = 0;
            $all_items = 1;
        } else {
            $all_items = 0;
            $items = $request->items;
        }
	    return ProductsCarCompatible::create(['product_id'=>$products_id , 
	                                 'maker'=>$request->car_makers , 
									 'model'=>$request->car_models , 
									 'version'=>$request->car_version,
									 'group'=>$groups,
									 'all_group'=>$all_groups,
									 'sub_group'=>$sub_groups,
									 'all_sub_group'=>$all_sub_groups,
									 'item'=>$items,
									 'all_item'=>$all_items,
									 'our_time'=>$request->assemble_time,
                                     'k_time'=>$request->assemble_kromeda_time,
                                     'type' => 2,
                                     'status'=>'A'
								   ]);
	}
    
    public static function add_car_compatible_details($request, $groups, $all_groups,$sub_groups, $all_sub_groups, $items, $all_items, $k_time, $type){
        return ProductsCarCompatible::create(
            [
                'product_id'=>$request->product_id , 
                'maker'=>$request->makers , 
                'model'=>$request->models  , 
                'version'=>$request->versions, 
                'group'=>$groups , 
                'all_group'=>$all_groups,
                'sub_group'=>$sub_groups,
                'all_sub_group'=>$all_sub_groups,
                'item'=>$items,
                'all_item'=>$all_items,
                'our_time'=>$request->our_time,
                'k_time'=>$k_time,
                'type' => $type,
                'item_number'=>$request->item_number,
                'status'=>'A'
            ]);
    }
    
    public static function add_custom_car_compatible_details($request, $groups, $all_groups,$sub_groups, $all_sub_groups, $items, $all_items){
        return ProductsCarCompatible::create(
            [
                'product_id'=>$request->product_id , 
                'maker'=>$request->makers , 
                'model'=>$request->models  , 
                'version'=>$request->versions, 
                'group'=>$groups , 
                'all_group'=>$all_groups,
                'sub_group'=>$sub_groups,
                'all_sub_group'=>$all_sub_groups,
                'item'=>$items,
                'all_item'=>$all_items,
                'our_time'=>$request->our_time,
                'type' => 2,
                'status'=>'A'
            ]);
    }
    
	public static function find_products_compatible($compatible_id){
	   return ProductsCarCompatible::where([['id' , '=' , $compatible_id], ['deleted_at' , '=' , NULL]])->first();
	}
	
    public static function get_car_compitable($product_id) {
        return ProductsCarCompatible::where([['product_id' , '=' , $product_id], ['deleted_at' , '=' , NULL]])->get();
    }

    public static function get_custom_compatible($product_id) {
        return ProductsCarCompatible::where([['product_id' , '=' , $product_id], ['type', '=', 2]])->OrderBy('id' , 'DESC')->get();
    }
    
    public static function get_car_compatible_product($product_details) {
        //return ProductsCarCompatible::where([['item_number' , '=' , $product_details->products_name], ['product_id' , '!=' , $product_details->id],  ['deleted_at' , '=' , NULL], ['item', '!=', 0]])->orWhere([['item_number' , '=' , $product_details->products_name], ['product_id' , '!=' , $product_details->id],  ['deleted_at' , '=' , NULL], ['all_item', '!=', 0]])->get();
        return ProductsCarCompatible::where([['item_number' , '=' , $product_details->products_name], ['product_id' , '!=' , $product_details->id],  ['deleted_at' , '=' , NULL]])->get();
        
    }
}
