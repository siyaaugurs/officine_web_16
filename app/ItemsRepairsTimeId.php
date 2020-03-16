<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\CustomDatabase;
use App\Library\kromedaHelper;

class ItemsRepairsTimeId extends Model{
   
    protected  $table = "items_repairs_time_ids";
	protected $fillable = [
		'id', 'users_id','version_id','repair_times_id','repair_times_description','language', 'cron_executed_status', 
		'unique_id','created_at','updated_at'];
	
	
	public static function save_item_repairs_time_id($version_id , $time_arr, $lang){
		$created_at = date('Y-m-d h:i:s');
		$updated_at = date('Y-m-d h:i:s');
		$queries =  '';
		if(Auth::check()){ $uid = Auth::user()->id; }	
			else{ $uid = 3; } 
		foreach($time_arr as $time){
		$uniqueKey = $version_id.$time->repair_times_id.$lang;
		$description =  \DB::connection()->getPdo()->quote($time->repair_times_description); 
		$queries .=  "INSERT INTO `items_repairs_time_ids`(`id`, `users_id`, `version_id`, `repair_times_id`, `repair_times_description`, `language`, `unique_id`,  `created_at`, `updated_at`) VALUES (null ,'$uid', '$version_id', '$time->repair_times_id',$description, '$lang' ,'$uniqueKey','$created_at','$updated_at') ON DUPLICATE KEY UPDATE repair_times_id='$time->repair_times_id', repair_times_description=$description;\n";
			}
		//return $queries;	
		return CustomDatabase::custom_insertOrUpdate($queries); 
	}
	
	public static function save_item_repairs_id_new($version_id , $time_arr, $lang){
	  if(Auth::check()){ $uid = Auth::user()->id;}	
	  else{ $uid = 3; } 
	  return ItemsRepairsTimeId::updateOrcreate(['version_id'=>$version_id,
	                                             'repair_times_id'=>$time_arr->repair_times_id,
												 'language'=>$lang],
	                       ['users_id'=>$uid, 
						    'version_id'=>$version_id,
							'repair_times_id'=>$time_arr->repair_times_id,
							'language'=>$lang,
							'repair_times_description'=>\DB::connection()->getPdo()->quote($time_arr->repair_times_description)
						   ]);	
   
	}
		
		
    public static function save_item_repairs_id($request , $time_arr, $lang){
	  return ItemsRepairsTimeId::updateOrcreate(['version_id'=>$request->version_id,
	                                             'repair_times_id'=>$time_arr->repair_times_id,
												 'language'=>$lang],
	                       ['users_id'=>Auth::user()->id, 
						    'version_id'=>$request->version_id,
							'repair_times_id'=>$time_arr->repair_times_id,
							'language'=>$lang,
							'repair_times_description'=>\DB::connection()->getPdo()->quote($time_arr->repair_times_description)
						   ]);	
   
	}
	
	public static function get_times_ids($version_id , $lang){
       return ItemsRepairsTimeId::where([['version_id','=',$version_id],
										 ['language','=' ,$lang]])->get();	
	 }
	 
	 public static function get_items_id(){
       return ItemsRepairsTimeId::select('*')->get();	
	}
}
