<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KrPartList extends Model{
    
	protected  $table = "kr_part_lists";
    protected $fillable = [
       'id', 'version_id' , 'version_service_schedules_service_schedule_id' , 'version_services_schedules_intervals_service_interval_id',  'version_interval_id','ad_part_id','idVoce','Voce_ENG','ap_ENG','ds_ENG', 'language',  'created_at', 'updated_at'];
     
	
	public static function add_kr_parts_list($interval_details , $krPartsList , $lang){
		foreach($krPartsList as $parts){
		   if($lang == "ENG"){
			   $data_arr = ['version_id'=>$interval_details->version_id, 
			                'version_service_schedules_service_schedule_id'=>$interval_details->version_service_schedules_schedules_id, 
			                'version_services_schedules_intervals_service_interval_id'=>$interval_details->service_interval_id,
				               'version_interval_id'=>$interval_details->id, 
		                       'ad_part_id'=>$parts->ad_part_id, 
							   'idVoce'=>$parts->idVoce,
							   'Voce_ENG'=>$parts->Voce_ENG,
							   'ap_ENG'=>$parts->ap_ENG,
							   'ds_ENG'=>$parts->ds_ENG,
							   'language'=>$lang
						  ];
			 }
		   if($lang == "ITA"){
			   $data_arr = ['version_id'=>$interval_details->version_id, 
				           'version_service_schedules_service_schedule_id'=>$interval_details->version_service_schedules_schedules_id, 
			          	   'version_services_schedules_intervals_service_interval_id'=>$interval_details->service_interval_id,
							'version_interval_id'=>$interval_details->id, 
							'ad_part_id'=>$parts->ad_part_id, 
							'idVoce'=>$parts->idVoce,
							'Voce_ENG'=>$parts->Voce,
							'ap_ENG'=>$parts->ap,
							'ds_ENG'=>$parts->ds,
							'language'=>$lang
						  ];
			 } 	 	
		   KrPartList::updateOrcreate(
						  ['version_id'=>$interval_details->version_id, 
						   'version_service_schedules_service_schedule_id'=>(string) $interval_details->version_service_schedules_schedules_id,
						  'version_services_schedules_intervals_service_interval_id'=> $interval_details->service_interval_id,
						   'ad_part_id'=>$parts->ad_part_id, 
						   'idVoce'=>$parts->idVoce,
						   'language'=>$lang
						  ],
						  $data_arr
		                 );
		 }
	}
	
	public static function get_kPartsList($interval_details , $lang){
		return KrPartList::where([['version_id', '=' , $interval_details->version_id] , 
		                          ['version_service_schedules_service_schedule_id' , '=' ,$interval_details->version_service_schedules_schedules_id], 
		                          ['version_services_schedules_intervals_service_interval_id' , '=' , $interval_details->service_interval_id],
		                          ['language' , '=' ,$lang] 
								 ])->get();
	}
	
}
