<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\ProductsNew;
use kromedaDataHelper;
use App\Http\Controllers\Coupon;
use apiHelper;
use sHelper;
use App\library\orderHelper; 
use Auth;
class Products_order_description extends Model{

    
    protected  $table = "products_order_descriptions";
    protected $fillable = ['id', 'users_id', 'seller_id', 'products_orders_id' , 'product_image' , 'product_image_url' ,'products_id', 'product_name', 'product_description', 'product_quantity','for_order_type','coupons_id', 'price','discount', 'total_price', 'final_order_price','single_product_calculate_price',  'pfu_tax', 'vat',  'status','for_assemble_service','service_booking_id', 'deleted_at' , 'created_at' , 'update_at'];
    public $product_type = [1=>'Spare parts' , 2=>'Tyre' , '3'=>'Rim']; 
   

    public static function get_product_description($order_id) {
        if(!empty($order_id)) {
            return DB::table('products_order_descriptions as a') 
						->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
						->where('a.products_orders_id' , '=' , $order_id)
						->select('a.*' , 'u.f_name')
						->first(); 
        }
    }
    public static function get_service_description($booking_id) {
        if(!empty($booking_id)) {
            return DB::table('products_order_descriptions as a') 
						->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
						->where('a.service_booking_id' , '=' , $booking_id)
						->select('a.*' , 'u.f_name')
						->get(); 
        }
    }
    public static function get_service_booking_description($booking_id) {
        if(!empty($booking_id)) {
            return DB::table('products_order_descriptions as a') 
						->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
						->where('a.service_booking_id' , '=' , $booking_id)
						->select('a.*' , 'u.f_name')
						->first(); 
        }
    }

    public static function spare_product_description($order_id) {
        if(!empty($order_id)) {
            return DB::table('products_order_descriptions as a') 
                        ->where([['a.products_orders_id' , '=' , $order_id], ['for_order_type', '=', 1] , ['for_assemble_service' , '=' ,Null], ['deleted_at' , '=' , NULL]])
                        ->select('a.*')
                        ->get(); 
        }
    }
     public static function spare_product_description_for_assemble($order_id) {
        if(!empty($order_id)) {
            return DB::table('products_order_descriptions as a') 
                        ->where([['a.service_booking_id' , '=' , $order_id], ['for_order_type', '=', 1] , ['for_assemble_service' , '=' ,1], ['deleted_at' , '=' , NULL]])
                        ->select('a.*')
                        ->first(); 
        }
    }
    public static function tyre_product_description_for_assemble($order_id) {
        if(!empty($order_id)) {
            return DB::table('products_order_descriptions as a') 
                        ->where([['a.service_booking_id' , '=' , $order_id], ['for_order_type', '=', 2] ,['for_assemble_service' , '=' ,1], ['deleted_at' , '=' , NULL]])
                        ->select('a.*')
                        ->first(); 
        }
    }
     public static function tyre_product_description($order_id) {
        if(!empty($order_id)) {
            return DB::table('products_order_descriptions as a') 
                        ->where([['a.products_orders_id' , '=' , $order_id], ['for_order_type', '=', 2] ,['for_assemble_service' , '=' ,NUll], ['deleted_at' , '=' , NULL]])
                        ->select('a.*')
                        ->get(); 
        }
    }
    
     public static function car_maintenace_description_for_part_list($order_id) {
        if(!empty($order_id)) {
            return DB::table('products_order_descriptions as a') 
                        ->where([['a.products_orders_id' , '=' , $order_id], ['deleted_at' , '=' , NULL]])
                        ->select('a.*')
                        ->get(); 
        }
    }
    public static function update_product_quantity($request){
        $result = Products_order_description::where('id',$request['product_id'])
        ->update(['product_quantity'=> $request['quantity'],
        'price'=>$request['price'],
        'total_price'=>$request['total_price'],
    ]);
    return $result;
    }
    public static function save_product_discription($request ,$part_arr , $order_id, $service_book_id ,$type_status){
       $coupon_obj = new Coupon;
        $find_product_info = ProductsNew::find($part_arr->part_id);
        if($find_product_info != NULL){
        $spare_product_detail = kromedaDataHelper::arrange_spare_product($find_product_info);
        //check part coupon validity script starts
        if(!empty($part_arr->part_coupon_id)){
            $coupon_response = json_decode($coupon_obj->check_coupon_validity($part_arr->part_coupon_id ,$request->selected_date,$spare_product_detail->seller_price));
           if($coupon_response != NULL)
           {
            if($coupon_response->status != 200){
                return sHelper::get_respFormat(0, $coupon_response->msg, null,null);
            } else {
                //save part coupon amount in user wallet
                if($coupon_response->status == 200){
                    if($type_status == 1){
                    $save_coupon_amount = apiHelper::manage_registration_time_wallet(Auth::user(),$coupon_response->price,"Car maintenance part coupon.");
                    }else{
                    $save_coupon_amount = apiHelper::manage_registration_time_wallet(Auth::user(),$coupon_response->price,"MOT service part coupon.");
                    }  
                }
            }
         }
        }
		$discount_price = 0;
		$service_vat = orderHelper::calculate_vat_price($spare_product_detail->seller_price);
		$final_order_price = ($service_vat + $spare_product_detail->seller_price ) - $discount_price;
		$part_total_price = $part_arr->quantity *$spare_product_detail->seller_price;
		
        $product_order_insert = Products_order_description ::create(['seller_id'=>$part_arr->seller_id,
            "products_orders_id" =>$order_id,
            // "product_image"=> $image->seller_price,
            "users_id" =>Auth::user()->id,
            "product_image_url" => $spare_product_detail->image,
            "products_id" =>$part_arr->part_id,
            "product_name" =>$spare_product_detail->products_name1,
            "product_description" =>$spare_product_detail->kromeda_description,
            "product_quantity" => $part_arr->quantity,
            "vat"=>$service_vat ,
            "coupons_id"=>$part_arr->part_coupon_id,
            "price" => $spare_product_detail->seller_price,
            "discount" =>0,
            "total_price" =>$part_total_price,
            "final_order_price"=>$final_order_price,
            "for_assemble_service" =>1,
            "service_booking_id" =>$service_book_id,
            ]);
            $order_manage = \App\Products_order::save_order($request,0 ,$spare_product_detail->seller_price,null ,$final_order_price ,1);
		   return $order_manage;
        }


    }

   // public static function spare_product_for_service($order_id){

  //  }
}
