<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class KromedaLog extends Model{
	 protected  $table = "kromeda_logs";
	 protected $fillable = ['unique_id','method_name','deleted_at','created_at','updated_at'];
	
	
	public static function save_kromeda_api_log($method){
	  return KromedaLog::create(['unique_id'=>md5(uniqid()), 'method_name'=>$method]);  
	} 
 
}
