<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\User;

class VersionServicesOperation extends Model{
   
    protected  $table = "version_services_operations";
    protected $fillable = [
        'id', 'users_id','version_id', 'version_service_schedules_service_schedule_id', 'version_services_schedules_intervals_service_interval_id', 'version_service_schedules_id' , 'version_services_schedules_intervals_id' , 'group_sequence' , 'group_name' , 'sort_sequence','operation_id','operation_description','operation_action','service_note','at_additional_charge' , 'part_description' , 'ad_part_id'  , 'kr_parts_count','language',  'deleted_at' ,'created_at' , 'updated_at'];
		
		
    public static function add_service_opration($interval_details  , $interval_operation , $lang){
		if(count($interval_operation) > 0){
		   foreach($interval_operation as $operation){
				 VersionServicesOperation::updateOrcreate(['version_services_schedules_intervals_id'=>$interval_details->id,
				                                           'group_sequence'=>$operation->group_sequence,
								                           'sort_sequence'=>$operation->sort_sequence,
								                           'language'=>$lang],
					   [
					  'users_id'=>User::return_admin_id(), 
					  'version_id' =>$interval_details->version_id,
					  'version_service_schedules_service_schedule_id'=>$interval_details->version_service_schedules_schedules_id, 
					  'version_services_schedules_intervals_service_interval_id'=>$interval_details->service_interval_id,
					  
					  'version_service_schedules_id'=>$interval_details->version_service_schedules_id , 
					  'version_services_schedules_intervals_id'=>$interval_details->id,
					  
					  'group_sequence'=>$operation->group_sequence, 
					  'group_name'=>$operation->group_name , 
					  'sort_sequence'=>$operation->sort_sequence, 
					  'operation_id'=>$operation->operation_id, 
					  'operation_description'=>$operation->operation_description , 
					  'operation_action'=>$operation->operation_action , 
					  'service_note'=>$operation->service_note , 
					  'at_additional_charge'=>$operation->at_additional_charge ,						                 
					  'part_description'=>$operation->part_description , 
					  'ad_part_id'=>$operation->ad_part_id , 
					  'kr_parts_count'=> $operation->kr_parts_count, 
					  'language'=>$lang,
						]);
			  }
		 }
	}	
	
	public static function get_operation($interval_id , $lang){
	    return VersionServicesOperation::where([['version_services_schedules_intervals_id' , '=' , $interval_id] , ['language' , '=' , $lang]])->paginate(5);
	}	    

   
}
