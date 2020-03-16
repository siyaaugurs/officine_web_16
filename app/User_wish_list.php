<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class User_wish_list extends Model
{
    //
	protected $table = 'user_wish_lists';
	protected $fillable = ['id','user_id' ,'product_id' , 'product_type','workshop_id','wishlist_type' ,'deleted_at' ,'created_at' ,'updated_at'];
	public $wishlist_type = [2=>'Workshop list' , 1=>'Products']; 

	public static function get_user_wish_list_for_product($product_id, $user_id, $product_type){
		$wish_list_status = DB::table('user_wish_lists')->where([['product_id','=',$product_id],['user_id','=',$user_id],['product_type','=',$product_type] , ['wishlist_type' , '=' , 1] , ['deleted_at', '=' ,NULL]])->count();
		if($wish_list_status > 0){
			return 1;
		} else {
			return 0;
		}
	}


	public static function fav_workshop($workshop_id , $user_id){
		$wish_list_status = DB::table('user_wish_lists')->where([['workshop_id','=',$workshop_id],
																 ['user_id','=',$user_id],
																 ['deleted_at', '=' ,NULL]])->count();
		if($wish_list_status > 0){ return 1;
		} else { return 0; } 
	}
	
	public static function get_user_wish_list_for_product_list($product_id, $user_id , $product_type){
		if($product_type == 1){
			return $wish_list_status = DB::table('user_wish_lists as uwl')->select('uwl.*','pn.*' ,'pi.image_url' ,'pi.image_name')->join('products_new as pn' ,'uwl.product_id' , '=','pn.id')->leftjoin('products_images as pi' ,'uwl.product_id' ,'=','pi.products_id')->
			where([['uwl.product_id','=',$product_id],['uwl.user_id','=',$user_id] ,['uwl.deleted_at', '=' ,NULL]])->first();
		} else {
			return $wish_list_status = DB::table('user_wish_lists as uwl')->select('uwl.*','tr.*','td.tyre_detail_response')->join('tyre24s as tr' ,'uwl.product_id' , '=','tr.id')->leftjoin('products_images as pi' ,'uwl.product_id' ,'=','pi.products_id')->leftjoin('tyre24_details as td','tr.id','=','td.tyre24_id')->
			where([['uwl.product_id','=',$product_id],['uwl.user_id','=',$user_id] ,['uwl.deleted_at', '=' ,NULL]])->first();
		}
		
	}
	public static function get_user_wish_list_for_workshop_list($workshop_id, $user_id){
		return 	$wish_list_status = DB::table('user_wish_lists as uwl')->select('uwl.*' ,'bd.*','u.*')->join('business_details as bd','uwl.workshop_id','=','bd.users_id')->join('users as u' , 'uwl.workshop_id' ,'=','u.id')
		->where([['uwl.workshop_id','=',$workshop_id],['uwl.user_id','=',$user_id] ,['uwl.wishlist_type','=', 2] ,['uwl.deleted_at', '=' ,NULL]])->first();
		
	}

	public static function get_user_wish_list_for_workshop($workshop_id, $user_id){
		$wish_list_status = DB::table('user_wish_lists')->where([['workshop_id','=',$workshop_id],['user_id','=',$user_id] ,['wishlist_type','=', 2] ,['deleted_at', '=' ,NULL]])->count();
		if($wish_list_status > 0){
			return 1;
		} else {
			return 0;
		}
	}
	
}
