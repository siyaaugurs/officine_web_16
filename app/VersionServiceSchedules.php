<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class VersionServiceSchedules extends Model{
   
   
    protected  $table = "version_service_schedules";
    protected $fillable = [
        'id', 'users_id', 'version_id', 'sort_order' , 'service_schedule_id', 'service_schedule_description', 'language' , 'deleted_at' , 'created_at' ,'updated_at'];
   
    
	public static function add_service_schedule($request , $service , $lang){
		if (Auth::check()) {
			$admin_detail = DB::table('users')->where([['roll_id' , '=' , 4]])->first();
			if($admin_detail != NULL){
				$uid = $admin_detail->id;
			}
		} else { 
			$uid = 3; 
		}
	   return  VersionServiceSchedules::updateOrCreate([['version_id','=',$request->version_id] 
	                                                  , ['language' , '=' , $lang] ,
													    ['service_schedule_id' , '=' ,$service->service_schedule_id ] 
													   ],
													   [
													   'users_id'=>$uid,
													   'version_id'=> $request->version_id ,
													   'sort_order'=>$service->sort_order, 
													   'service_schedule_id'=>$service->service_schedule_id, 
													   'service_schedule_description'=>$service->service_schedule_description, 
													   'language'=>$lang, 
													   ]);
	}		
	
	
	public static function get_schedule($version_id , $lang){
	   return VersionServiceSchedules::where([['version_id' , '=' ,$version_id] , ['language' , '=' , $lang]])->get();
	}
		
   
   
}
