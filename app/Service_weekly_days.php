<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
class Service_weekly_days extends Model{
    
	protected  $table = "services_weekly_days";
    protected $fillable = [
        'id', 'users_id', 'services_id', 'days_id', 'created_at', 'updated_at'];
     
    
	public static function get_workshop_services_days($service_id){
	   return Service_weekly_days::where('services_id' ,'=' , $service_id)->get();
	}
	   
    public static function get_services_days($services_id){
        return $service = \DB::table('services_weekly_days as s')
                            ->join('common_weekly_days as c' , 's.days_id' , '=' , 'c.id')
                            ->where('s.services_id' , '=' , $services_id)
                            ->select('s.id' , 'c.name')
							->orderBy('c.id' , 'ASC')
                            ->get();
    }
     
    public static function get_action ($action){
        if($action == "delete_pakages"){
            if(!empty($action)){
               $packages_result = \App\Service_weekly_days::find($services_id);
               if( $packages_result->save() ){
                return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Packages deleted successfully !!! </div>']);
                 }
               else {
                 return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> danger </strong> something went wrong please try again !!! </div>']);	
                }
            }
       }
    }
        
    public static function get_service_weekly_days($services_id , $days_id = NULL){
		if(!empty($days_id)){
		    return DB::table('services_weekly_days as s')
               ->join('common_weekly_days as c' , 'c.id' , '=' , 's.days_id')     
               ->select('s.*' , 'c.name')
               ->where([['services_id' , '=' , $services_id] , ['days_id','=',$days_id]])
               ->first();
		  }
	   	
       return DB::table('services_weekly_days as s')
               ->join('common_weekly_days as c' , 'c.id' , '=' , 's.days_id')     
               ->select('s.*' , 'c.name')
               ->orderBy('c.id' , 'ASC')
               ->where('services_id' , '=' , $services_id)
               ->get();
    }  
	
	public static function delete_weekly_days($service_id){
	  return Service_weekly_days::where('services_id' , $service_id)->delete();
	}  
}
