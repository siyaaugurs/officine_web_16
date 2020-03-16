<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use sHelper;
use apiHelper;
use Carbon\Carbon;

class Rim extends Model{
    
	protected  $table = "rims";
    protected $fillable = [
        'id', 'maker_id' , 'maker_slug', 'maker_name', 'rim_id','type' ,'rim_response', 'unique_id',   'deleted_at' , 'created_at' , 'updated_at'];
		
    
	public static function get_today_response($maker_details){
	   $maker_slug = sHelper::slug($maker_details->Marca);
	   return Rim::where([['maker_slug' , '=' , $maker_slug]])
	                    ->whereDate('updated_at', Carbon::today())
						->get(); 
	}
	
	
	public static function save_rim($maker_details , $response){
		$maker_slug =  sHelper::slug($maker_details->Marca);
        $created_at = $updated_at = date('Y-m-d h:i:s');
        $queries = '';
        foreach($response->items as $response){
			$encode_response = json_encode($response);
			$uniqueKey = $maker_slug.$response->id;
			$queries .=  "INSERT INTO `rims`(`id`,`maker_id`, `maker_slug`,`maker_name`, `rim_id`,`rim_response`,`created_at`, `updated_at`,`unique_id`) VALUES (null ,'$maker_details->idMarca', '$maker_slug','$maker_details->Marca','$response->id', '$encode_response','$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE rim_response='$encode_response';\n";
			$brand_unique_key =  "3".sHelper::slug($response->manufacturer); 
			$queries .= "INSERT INTO `brand_logos`(`id` , `brand_type`, `brand_name`,`unique_id`, `created_at`) VALUES (null, 3,  '$response->manufacturer', '$brand_unique_key','$created_at')
			ON DUPLICATE KEY UPDATE brand_name='$response->manufacturer';";
			$get_rim_details = apiHelper::get_rim_details($response->alcar);
			if($get_rim_details != FALSE){
				$decode_rim_details = json_decode($get_rim_details);
				if(is_object($decode_rim_details)){
					if(!empty($decode_rim_details->items)){
					   if(is_array($decode_rim_details->items)){
						    $rim_items_detail =  $decode_rim_details->items[1];				  
					   }
					}
					$uniqueKey2 = $response->id.$response->alcar;
					$get_rim_details  = \DB::connection()->getPdo()->quote(json_encode($rim_items_detail));
					$queries .= "INSERT INTO `rim_details`(`id` , `rim_id_id`,  `rim_alcar`, `rim_details_response`, `created_at`, `updated_at` , `unique_id`) VALUES (null ,'$response->id','$response->alcar',$get_rim_details,'$created_at','$updated_at','$uniqueKey2') ON DUPLICATE KEY UPDATE rim_details_response= $get_rim_details;\n";

					} 
			 } 
		}
	    return CustomDatabase::custom_insertOrUpdate($queries);
	}			
	
	
	
	
	
	public static function get_rim_response($maker_slug = NULL){
		if($maker_slug != NULL){
			return Rim::where([['maker_slug' , '=' , $maker_slug] , ['deleted_at' , '=' , NULL]])->orderBy('created_at' , 'DESC')->get();	
		}
	   return Rim::where([['deleted_at' , '=' , NULL]])->orderBy('created_at' , 'DESC')->paginate(10);
	}
}
