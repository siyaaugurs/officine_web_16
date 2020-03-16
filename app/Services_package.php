<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Services_package extends Model{
         
	protected  $table = "services_packages";
    protected $fillable = [
        'id', 'users_id','categories_id' , 'services_id', 'services_weekly_days_id', 'start_time' , 'end_time' , 'price' ,'max_appointment' ,  'created_at', 'updated_at'];
    
    public static function get_services_packages($services_weekly_days_id){
        return Services_package::where('services_weekly_days_id' , '=', $services_weekly_days_id)->get();
    }    
    
    public static function package_details($package_id){
	  return Services_package::where([['id','=',$package_id]])->first();
	} 
    
	public static function get_workshop_service_package($category_id , $workshop_user_id){
	  //return $category_id." ".$workshop_user_id;
	  return Services_package::where([['categories_id' , '=' , $category_id] , ['users_id' , '=' ,$workshop_user_id]])->get();
	}
	
    public static function get_services_packasge($category_id){
        return Services_package::where('categories_id' , '=' , $category_id)->get();
    }
    
    public static function get_workshop_services_packasge($service_id){
        return Services_package::where('services_id' , '=' , $service_id)->get();
    }
    
    

    public static function get_packages($service_weekly_days_id){ 
        return Services_package::where('services_weekly_days_id' , '=' , $service_weekly_days_id)->get();
    }
	
	public static function delete_packages($service_id){
	    return Services_package::where('services_id' , $service_id)->delete();	
	}
}
