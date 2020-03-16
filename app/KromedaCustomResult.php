<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class KromedaCustomResult extends Model{
    
	public  $table = "kromeda_custom_results";
    protected $fillable = [
        'id', 'url', 'response', 'created_at', 'updated_at'];
	
    public static function save_response($url , $response , $type = NULL){
	    return  KromedaCustomResult::updateOrcreate(array('url'=>$url) , ['response'=>$response , 'type'=>$type]); 
		
	}
	
	 public static function get_response($url){
	    return KromedaCustomResult::where('url' ,'=', $url)->first('response'); 
	}
	
}
