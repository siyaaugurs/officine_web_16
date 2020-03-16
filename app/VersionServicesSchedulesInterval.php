<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Library\sHelper;
use DB;

class VersionServicesSchedulesInterval extends Model{
	
     protected  $table = "version_services_schedules_intervals";
     protected $fillable = [
        'id', 'users_id', 'version_service_schedules_id', 'version_service_schedules_schedules_id','version_id','service_interval_id','additional','sort_order', 'service_kms', 'service_months',  'interval_description_for_kms' , 'service_advisory_message' , 'standard_service_time_hrs' , 'automatic_transmission_time_hrs' , 'extra_time_hrs' , 'extra_time_description' , 'language','deleted_at','created_at' ,'updated_at'];
     
	 public static function get_intervals($version_service_schedules_id , $lang){
	     return VersionServicesSchedulesInterval::where([['version_service_schedules_id' ,  '=' , $version_service_schedules_id] , ['language' , '=' , $lang]])->get(); 
	 }
	 
	 public static function get_intervals_version($version_id , $lang){
		return VersionServicesSchedulesInterval::where([['version_id' ,  '=' , $version_id] , ['language' , '=' , $lang]])->get(); 
	}
	 
	 
	 public static function add_schedule_interval($schedule_details , $schedule_interval ,$lang){
		if (Auth::check()) {
			$admin_detail = DB::table('users')->where([['roll_id' , '=' , 4]])->first();
			if($admin_detail != NULL){
				$uid = $admin_detail->id;
			}
		} else { $uid = 3; }
	    $extra_time_hrs = $stan_ser_time = $automatic_transmission_time_hrs = 0;
	     if(!empty($schedule_interval->extra_time_hrs) && $schedule_interval->extra_time_hrs != 0){
		    $extra_time_hrs = sHelper::replace_comman_with_dot($schedule_interval->extra_time_hr);
		  }
		 if(!empty($schedule_interval->standard_service_time_hrs)){
		    $stan_ser_time = sHelper::replace_comman_with_dot($schedule_interval->standard_service_time_hrs);
		  }
		 if(!empty($schedule_interval->automatic_transmission_time_hrs)){
		   $automatic_transmission_time_hrs = sHelper::replace_comman_with_dot($schedule_interval->automatic_transmission_time_hrs);
		 }
	   return  VersionServicesSchedulesInterval::updateOrCreate([
	                       ['version_service_schedules_id','=',$schedule_details->id],
						   ['language' , '=' , $lang],
						   ['service_interval_id' , '=' ,$schedule_interval->service_interval_id], ['sort_order' , '=' , $schedule_interval->sort_order]
						   ],
						   [
						   'users_id'=>$uid,
						   'version_id'=>$schedule_details->version_id ,
						   'version_service_schedules_id'=>$schedule_details->id, 
						   'version_service_schedules_schedules_id'=>$schedule_details->service_schedule_id,
						   'service_interval_id'=>$schedule_interval->service_interval_id, 
						   'additional'=>$schedule_interval->additional,
						   'sort_order'=>$schedule_interval->sort_order,
						   'service_kms'=>$schedule_interval->service_kms,
						   'service_months'=>$schedule_interval->service_months,
						   'interval_description_for_kms'=>$schedule_interval->interval_description_for_kms, 
						   'service_advisory_message'=>$schedule_interval->service_advisory_message, 
						   'standard_service_time_hrs'=>$stan_ser_time, 
						   'automatic_transmission_time_hrs'=>$automatic_transmission_time_hrs,                           
						   'extra_time_hrs'=>$extra_time_hrs, 
						   'extra_time_description'=>$schedule_interval->extra_time_description, 
						   'language'=>$lang, 
						   ]);
	}	 
	public static function get_workshop_intervals($version_service_schedules_id , $lang){
		return VersionServicesSchedulesInterval::where([['version_service_schedules_id' ,  '=' , $version_service_schedules_id] , ['language' , '=' , $lang], ['deleted_at', '=', NULL]])->get(); 
	}
	 
	public static function get_workshop_intervals_version($version_id , $lang){
		return VersionServicesSchedulesInterval::where([['version_id' ,  '=' , $version_id] , ['language' , '=' , $lang], ['deleted_at', '=', NULL]])->get(); 
	}
}
