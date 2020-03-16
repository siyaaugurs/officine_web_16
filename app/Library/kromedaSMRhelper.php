<?php
namespace App\Library;
use Auth;
use App\Category;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use App\Model\Kromeda;
use App\Library\sHelper;

class kromedaSMRhelper{
  
    static $notifications = null;
    
	public static function common_response($database_response){
      if(!empty($database_response)){
		 return json_encode(['status'=>200 , 'response'=>$database_response]);
		}
	   else return json_encode(['status'=>404]); 
	}
    
	public static function smr_response($database_response){
      if(!empty($database_response->result[1])){
		 $final_response = $database_response->result[1]->dataset;
		 return json_encode(['status'=>200 , 'response'=>$final_response]);
		}
	   else return json_encode(['status'=>404 , 'msg'=>$database_response->result[0]]); 
	}
	
	
	/*Find For Version Repair Time id*/
	 public static function  kromeda_version_criteria($version_id , $lang){
		$api_param = $version_id."/".$lang; 
		$url = "version_criteria/".$api_param;
		$database_response = Kromeda::get_response_api($url);
		 if($database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'SMR_GetVehicleCriteria' , false , $api_param);
			  $add_response =  Kromeda::add_response($url , $third_party_response , "SMR_GetVehicleCriteria");
			  if($add_response){
				 $database_response = Kromeda::get_response_api($url);
				}
			 }
		   return self::smr_response($database_response);	 
	 }
	/*End*/
	
	/*Find For Version Repair Time id*/
	 public static function  kromeda_version_service_time($version_id ,$repair_time_id,$lang){
		$api_param = $version_id."/".$repair_time_id."/".$lang; 
		$url = "version_repair_time/".$api_param;
		//return $url;
		$database_response = Kromeda::get_response_api($url);
		 if($database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'SMR_GetRepairTimes' , false , $api_param);
			  $add_response =  Kromeda::add_response($url , $third_party_response , "SMR_GetRepairTimes");
			  if($add_response){
				 $database_response = Kromeda::get_response_api($url);
				}
			 }
		   return self::smr_response($database_response);	 
	 }
	/*End*/
	
	/*Service Scheduled smr API start*/
    public static function  mot_service_schedule($version_id , $lang){
		$api_param = $version_id."/".$lang; 
		$url = "mot_services/".$api_param;
		$database_response = Kromeda::get_response_api($url);
		 if($database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'SMR_GetSchedules' , false , $api_param);
			  $add_response =  Kromeda::add_response($url , $third_party_response , "SMR_GetSchedules");
			  if($add_response){
				 $database_response = Kromeda::get_response_api($url);
				}
			 }
		   return self::smr_response($database_response);	 
	}
	/*End*/
	
	/*Service Schedule intarval API satrt*/
	 public static function  service_schedule_interval($version_id , $service_schedule_id , $lang){
		$api_param = $version_id."/".$service_schedule_id."/".$lang; 
		$url = "services_interval/".$api_param;
		$database_response = Kromeda::get_response_api($url);
		 if($database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'SMR_GetIntervals' , false , $api_param);
			  $add_response =  Kromeda::add_response($url , $third_party_response , "SMR_GetIntervals");
			  if($add_response){
				 $database_response = Kromeda::get_response_api($url);
				}
			 }
		   return self::smr_response($database_response);	 
	}
	/*End*/
	
     /*Service interval operation */
	public static function smr_operation_response($database_response){
      if(!empty($database_response->result[1])){
		 $final_response = $database_response->result[1];
		 return json_encode(['status'=>200 , 'response'=>$final_response]);
		}
	   else return json_encode(['status'=>404 , 'msg'=>$database_response->result[0]]); 
	}
	
	public static function  schedule_interval_operation($version_id,$service_schedule_id , $service_interval_id , $lang){
		$api_param = $version_id."/".$service_schedule_id."/".$service_interval_id."/".$lang; 
		$url = "interval_operation/".$api_param;
		$database_response = Kromeda::get_response_api($url);
		 if($database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'SMR_GetOperations' , false , $api_param);
			  $add_response =  Kromeda::add_response($url , $third_party_response , "SMR_GetOperations");
			  if($add_response){
				 $database_response = Kromeda::get_response_api($url);
				}
			 }
		   return self::smr_operation_response($database_response);	 
	}
      
	/*End*/ 
       
       
    
}