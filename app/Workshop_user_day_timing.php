<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;


class Workshop_user_day_timing extends Model{
    
   protected  $table = "workshop_user_day_timings";
   protected $fillable = [
        'id', 'users_id', 'workshops_id' ,'workshop_user_days_id' , 'start_time' , 'end_time', 'deleted_at',  'created_at','updated_at'
    ];
	
	
	public static function create_update($days_result , $record){
	  return Workshop_user_day_timing::updateOrcreate(['users_id'=>Auth::user()->id,
										         'workshop_user_days_id'=>$days_result->id,
												 'start_time'=>$record['start_time'],
												 'end_time'=>$record['end_time'],
												],
	                                            ['users_id'=>Auth::user()->id,
										         'workshop_user_days_id'=>$days_result->id,
												 'start_time'=>$record['start_time'],
												 'end_time'=>$record['end_time'],
												 'deleted_at'=>NULL,
												 'created_at'=>date('Y-m-d H:i:s'),
												 'updated_at' => date('Y-m-d H:i:s') 
												]);
	}
	
	public static function create_update_2($days_result){
		$start_time = "00:00"; $end_time = "23:59";
	  return Workshop_user_day_timing::updateOrcreate(['users_id'=>Auth::user()->id,
										         'workshop_user_days_id'=>$days_result->id,
												 'start_time'=>$start_time,
												 'end_time'=>$end_time,
												],
	                                            ['users_id'=>Auth::user()->id,
										         'workshop_user_days_id'=>$days_result->id,
												 'start_time'=>$start_time,
												 'end_time'=>$end_time,
												 'deleted_at'=>NULL,
												 'created_at'=>date('Y-m-d H:i:s'),
												 'updated_at' => date('Y-m-d H:i:s') 
												]);
	}
	
	 public static function save_workshop_users_days($days_id , $whole_opening){
	   return  Workshop_user_day::updateOrcreate(['users_id'=>Auth::user()->id ,
	                                              'common_weekly_days_id'=>$days_id,
												  'is_whole_opening'=>$whole_opening 
												 ],
	        ['users_id'=>Auth::user()->id, 
			 'common_weekly_days_id'=>$days_id, 
			 'is_whole_opening'=>$whole_opening,
			 'deleted_at'=>NULL
			 ]
			);
	}
	
	public static function delete_timing($con_arr){
	   	   $result = \DB::table('workshop_user_day_timings')->where($con_arr)->delete();
	 }
	 
	 public static function delete_record($con_arr){
	    $result = \DB::table('workshop_user_day_timings')->where($con_arr)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
	   	  if($result)return TRUE; else return FALSE;
	 }
	 
	 public static function get_packages($workshop_user_days_id){

		return Workshop_user_day_timing::select(DB::raw('* , TIME_FORMAT(start_time, "%H:%i") as start_time  , TIME_FORMAT(end_time, "%H:%i") as end_time'))->where([['workshop_user_days_id' , '=' , $workshop_user_days_id] , ['deleted_at' , '=' , NULL]])
		                                ->get();
    }
	 
	 /*public static function get_packages($workshop_user_days_id){ 
        return Workshop_user_day_timing::where([['workshop_user_days_id' , '=' , $workshop_user_days_id] , ['deleted_at' , '=' , NULL]])->get();
    }*/
    
    
}
