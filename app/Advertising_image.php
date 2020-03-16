<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertising_image extends Model
{
    //
	protected $table = "site_images";
	protected $fillable = ['id', 'advertising_id'  , 'support_ticket_id', 'image', 'image_url' ,'servicequotes_id','created_at' , 'updated_at' , 'deleted_at'];


	public static function get_Advertising_images($id){
		return Advertising_image::where([['advertising_id', '=' ,$id],['deleted_at' , '=' ,NULL]])->get();	
	}

	public static function add_Advertising_gallery($image , $id){
		$image_url = url("storage/advertising/$image");
		return Advertising_image ::create(['advertising_id'=>$id , 'image'=>$image , 'image_url'=>$image_url]); 
	}
	public static function add_service_quotes_gallery($image , $id){
		$image_url = url("public/storage/$image");
		return Advertising_image ::create(['servicequotes_id'=>$id , 'image'=>$image , 'image_url'=>$image_url]); 
	}
	
}
