<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Workshop_user_day extends Model{
     

    protected $table = "workshop_user_days";
    protected $fillable = ['id' , 'users_id' , 'common_weekly_days_id' , 'is_whole_opening' , 'created_at' , 'updated_at', 'deleted_at'];   
    
     public static function save_workshop_users_days($days_id , $whole_opening){
	   return  Workshop_user_day::updateOrcreate(['users_id'=>Auth::user()->id ,
	                                              'common_weekly_days_id'=>$days_id 
												 ],
	        ['users_id'=>Auth::user()->id, 
			 'common_weekly_days_id'=>$days_id, 
			 'is_whole_opening'=>$whole_opening,
			 'deleted_at'=>NULL
			 ]
			);
	}
    
    public static function get_all_days($user_id){
		$result = \DB::table('workshop_user_days as a')
		         ->join('common_weekly_days as b' , 'a.common_weekly_days_id' , '=' , 'b.id')
	             ->select('a.*' , 'b.name')
				 ->where([['users_id' , '=' , $user_id] , ['deleted_at' , '=' , NULL]])
				 ->get();
	   return $result;			 
	}
	
	public static function delete_record($con_arr){
	    $result = \DB::table('workshop_user_days')->where($con_arr)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
		if($result)return TRUE; else return FALSE; 
	}
	
	public static function delete_days($con_arr){
		$result = \DB::table('workshop_user_days')->where($con_arr)->delete();
		if($result)return TRUE; else return FALSE;
	   
	 }
	 
	 public static function get_service_weekly_days($workshop_id , $days_id = NULL){
		if(!empty($days_id)){
			return DB::table('workshop_user_days as s')
               ->join('common_weekly_days as c' , 'c.id' , '=' , 's.common_weekly_days_id')     
               ->select('s.*' , 'c.name')
               ->where([['s.users_id' , '=' , $workshop_id] , ['common_weekly_days_id','=',$days_id]])
			   ->where([['s.deleted_at','=',NULL]])
               ->first();
		  }
	   	
       return DB::table('workshop_user_days as s')
               ->join('common_weekly_days as c' , 'c.id' , '=' , 's.days_id')     
               ->select('s.*' , 'c.name')
               ->orderBy('c.id' , 'ASC')
               ->where('services_id' , '=' , $services_id)
               ->get();
    } 
    //Get workshop user days
	public static function get_workshop_user_days($users_id, $common_weekly_days_id){
		return Workshop_user_day::where([['users_id' , '=' , $users_id], ['common_weekly_days_id' , '=' , $common_weekly_days_id], ['deleted_at', '=', NULL]])->first();			
    }
}
