<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OurCarMaintinanceProductItem extends Model
{
    protected  $table = "our_car_maintinance_product_items";
    protected $fillable = [
        'id', 'item_repairs_parts_id', 'maker' , 'model' , 'version', 'n1_category', 'n2_category', 'n3_category', 'item_number' , 'created_at' , 'updated_at']; 
        
    public static function add_car_maintainance_product_item($product_item_number,$item_repair_part_id,  $product_item_details) {
       return  DB::table('our_car_maintinance_product_items')->insert(
                                    array(
                                        'item_repairs_parts_id'=>$item_repair_part_id ,
                                        'maker'=>$product_item_details->maker ,
                                        'model'=>$product_item_details->model , 
                                        'version'=>$product_item_details->version ,
                                        'n1_category'=>$product_item_details->n1_category , 
                                        'n2_category'=>$product_item_details->n2_category , 
                                        'n3_category'=>$product_item_details->n3_category , 
                                        'item_number'=>$product_item_number,
                                        'created_at'=> date('Y-m-d H:i:s') 
                                    ));
    }
	
    public static function get_our_car_maintainance_details($item_repair_part_id) {
        return DB::table('our_car_maintinance_product_items as a')
                    ->leftjoin('products_new as b' , 'b.kromeda_products_id' , '=' , 'a.item_number')
                    ->select('b.*', 'a.item_number as our_product_item_id' , 'a.id as a_id')
                    ->where([['a.item_repairs_parts_id', '=', $item_repair_part_id], ['a.deleted_at', '=', NULL]])
                    ->get();
    }
	
	public static function get_car_compatible($item_repairs_part_id){
         return OurCarMaintinanceProductItem::where([['item_repairs_parts_id' , '=' , $item_repairs_part_id] , ['deleted_at' , '=' , NULL]])->get();
	}
	
	
}
