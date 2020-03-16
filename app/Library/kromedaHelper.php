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

class kromedaHelper {
        
        
        public static function save_version_response($model_details , $versions){
            $get_version_record = \App\Version::get_versions($model_details);
			if($get_version_record->count() > 0){
				$get_model_details = \App\Models::get_model($model_details ,$versions);
				if($get_model_details){
				  $save_version = \App\Version::add_version($model_details , $versions);
				 	return $save_version; 
				     
				}
			}
		}
        
        
        public static function model_details($maker_id , $cars_models_response){
			$model_response = \App\Models::where('maker' , $maker_id)->get();
			$maker_details = \App\Maker::get_makers($maker_id);
			if($model_response->count() == NULL){
				$models = \App\Models::save_all_model($cars_models_response, $maker_details);   
			}
			
		}
		
          public static function get_response($response){
		  if(is_object($response)){
			  if(is_array($response->result)){
				 if(is_object($response->result[1])){
					if(is_array($response->result[1]->dataset) && count($response->result[1]->dataset) > 0){
					return kromedaSMRhelper::common_response($response->result[1]->dataset); 
					}
				  }
				}
			}
	   } 
       
       /*Common Function */
	    public static function common_request($session_key , $url  , $api_param , $method , $lang = NULL){
		 $response = Kromeda::js_get_response_api($url); 
		 if($response == FALSE){
		     // $sess_key = sHelper::generate_kromeda_session_key();
		      $create_at = now();
			  $third_party_response = sHelper::Get_kromeda_Request($session_key , $method , false , $api_param);
			  Kromeda::add_response($url , $third_party_response , $create_at);
			  $response = Kromeda::js_get_response_api($url); 
		   }
		  return kromedaHelper::get_response($response);
		}
	  /*End*/ 
      
    /*Get sub Products Item Api Start*/
	 public static function get_groups_items_by_group($version_id , $group_id , $lang){
		$api_param = $version_id."/".$group_id."/".$lang;  
		$url = "getGroupsPartsItems/".$api_param;
		$get_parts_database_response = Kromeda::js_get_response_api($url);
		  if($get_parts_database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			 if($sess_key != 500){
				 $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetActiveItemsByGroup' , false , $api_param);
				 Kromeda::add_response($url,$third_party_response , "OE_GetActiveItemsByGroup");
			 } 
			}
		$get_parts_database_response = Kromeda::js_get_response_api($url);
		if($get_parts_database_response != FALSE){
			return $get_parts_database_response->result[1]->dataset;
		}
	   }
	 /*End*/
	  
	 

	 	/*Get response from search plate number */
		 public static function search_plate_number($plate_number , $lang = "ENG"){
			$car_response = NULL; 
			$api_param = $plate_number."/".$lang;  
			$url = 'getSearchPlate'.'/'.$api_param;
			$get_database_response = Kromeda::js_get_response_api($url);
			if($get_database_response == FALSE){
				$sess_key = sHelper::generate_kromeda_session_key();
			   if($sess_key != 500){
				   $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'CP_SearchPlate' , false , $api_param);
				   Kromeda::add_response($url , $third_party_response , "CP_SearchPlate");
			   } 
			  }
			  $get_parts_database_response = Kromeda::js_get_response_api($url);
			  if($get_parts_database_response != FALSE){
				  if(!empty($get_parts_database_response->result[1]->vehicles)){
					  return  $get_parts_database_response->result[1]->vehicles[0];
				  }
				  else{
					  return $car_response;
				  }
			     
				  /* $sub_group_item = $get_parts_database_response->result[1]->dataset;
				  return $sub_group_item; */
			  }
		  }
		/*End*/  
	  
	 
       /*Get sub Products Item Api Start*/
	  public static function get_sub_products_by_sub_group($version_id , $group_id , $lang){
		$api_param = $version_id."/".$group_id."/".$lang;  
		$url = "getSubPartsItems/".$api_param;
		$get_parts_database_response = Kromeda::js_get_response_api($url);
		  if($get_parts_database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  if($sess_key != 500){
				  $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetActiveItemsBySubgroup' , false , $api_param);
				  Kromeda::add_response($url , $third_party_response , "OE_GetActiveItemsBySubgroup");
			  } 
			}
		$get_parts_database_response = Kromeda::js_get_response_api($url);
		if($get_parts_database_response != FALSE){
			$sub_group_item = $get_parts_database_response->result[1]->dataset;
			return $sub_group_item;
		}
	   }
	 /*End*/
	

      /*Get Products group satrt*/
	  public static function get_products_group($car_version_id , $lang){
	    $api_param = $car_version_id."/".$lang; 
		$url = "getParts/".$api_param;
		$get_parts_database_response = Kromeda::get_response_api($url);
		  if($get_parts_database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetActiveGroups' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "OE_GetActiveGroups");
			  } 
			}
		$get_parts_database_response = Kromeda::js_get_response_api($url);
		if($get_parts_database_response != FALSE){
			$car_spare_parts_cat = $get_parts_database_response->result[1]->dataset;
			return $car_spare_parts_cat;
		} 
	  } 
	 /*End*/
	 
     
      /*Get Products Item Api Start*/
	  public static function get_products_by_group($version_id , $group_id , $lang){
	    $api_param = $version_id."/".$group_id."/".$lang;  
		$url = "getPartsItems/".$api_param;
		$get_parts_database_response = Kromeda::js_get_response_api($url);
		  if($get_parts_database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetActiveItems' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "OE_GetActiveItems");
			  } 
			}
		$get_parts_database_response = Kromeda::js_get_response_api($url);
		if($get_parts_database_response != FALSE){
			$car_spare_parts_item = $get_parts_database_response->result[1]->dataset;
			return $car_spare_parts_item;
		}
	   }
	 
	 /*End*/
     
     /*Get Criteria Script Start */
	 public static function get_products_criteria($car_version_id , $item_id){
		$api_param = $car_version_id."/".$item_id;
		$url = "productsCriteria/".$api_param;
		$products_details = Kromeda::js_get_response_api($url);
		if($products_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetCriterias' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "OE_GetCriterias");
			}

		}
		$products_details = Kromeda::js_get_response_api($url);  
		if($products_details != FALSE){
			$products_details_arr = $products_details->result[1]->dataset[0];	
			return $products_details_arr;
		}
	 } 
	 /*End */ 
     
	 public static function get_part_number($car_version_id , $item_id){
		$api_param = $car_version_id."/".$item_id;
		$url = "getItemNo/".$api_param;
		$products_details = Kromeda::js_get_response_api($url);
		if($products_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetPartNumber' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "OE_GetPartNumber");
			} 
		}
		$products_details = Kromeda::js_get_response_api($url);  
		if($products_details != FALSE){
			return $products_details->result[1]->dataset;	
		}
	 } 
     
      public static function oe_products_item($idList , $OEPartNumber){
		$api_param = $idList."/".$OEPartNumber;
		$url = "oe_products_item/".$api_param;
		$products_image_details = Kromeda::js_get_response_api($url);
		if($products_image_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetCross' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "OE_GetCross");
			}
		}
		$products_image_details = Kromeda::js_get_response_api($url); 
		if($products_image_details != FALSE){
			$products_image_details = $products_image_details->result[1]->dataset;	
			return $products_image_details;
		} 
	 } 
     
     public static function get_products_image($idList , $OEPartNumber){
		$api_param = $idList."/".$OEPartNumber;
		$url = "products_image/".$api_param;
		$products_image_details = Kromeda::js_get_response_api($url);
		if($products_image_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetCross' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "OE_GetCross");
			}
		}
		$products_image_details = Kromeda::js_get_response_api($url);  
		if($products_image_details != FALSE){
			$products_image_details = $products_image_details->result[1]->dataset;	
			return $products_image_details;
		}
	 } 
	 
	 
	 public static function oe_getOtherproducts($idList , $OEPartNumber){
		$api_param = $idList."/".$OEPartNumber;
		$url = "oe_others_products_item/".$api_param;
		$products_image_details = Kromeda::js_get_response_api($url);
		if($products_image_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetOtherCross' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "OE_GetOtherCross");
			}
		}
		$products_image_details = Kromeda::js_get_response_api($url);  
		if($products_image_details == FALSE){
			$products_image_details = $products_image_details->result[1]->dataset;	
			return $products_image_details;
		}
	 } 
	 
	 public static function get_other_products_image($idList , $OEPartNumber){
		$api_param = $idList."/".$OEPartNumber;
		$url = "products_other_image/".$api_param;
		$products_image_details = Kromeda::js_get_response_api($url);
		if($products_image_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
				if($sess_key != 500){
					$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetOtherCross' , false , $api_param);
					Kromeda::add_response($url , $third_party_response , "OE_GetOtherCross");
				}
			}
		$products_image_details = Kromeda::js_get_response_api($url);  
        if($products_image_details != FALSE){
			$products_image_details = $products_image_details->result[1]->dataset;	
			return $products_image_details;
		}
	 } 
	 
	 
     
      public static function get_group_name($group_id){
		$group_details =  \App\Products_group::find($group_id);
		if($group_details != NULL){
	       return $group_details->group_name;
		 }
	 }
     
	 public static function get_makers(){
		$maker = [];
	   $car_makers = Kromeda::js_get_response_api("getMakers");
	   if($car_makers == FALSE){
		  $sess_key = sHelper::generate_kromeda_session_key();
		  if($sess_key != 500){
			 $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'CP_GetMakers' , false , '');
			 Kromeda::add_response("getMakers" , $third_party_response , "CP_GetMakers");
		   } 
			$car_makers = Kromeda::js_get_response_api("getMakers");
		 }
	   if($car_makers != FALSE){
		   if(!empty($car_makers->result[1]->dataset)){
			   $maker =  $car_makers->result[1]->dataset;  
		   }
		}
		return $maker;
	}
	  
	  /*Model API */
	  public static function get_models($makers_id){
		$model = []; 
	    $api_param = $makers_id;
		$url = "getModels/".$api_param;
		$models_details = Kromeda::js_get_response_api($url);
		if($models_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'CP_GetModels' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "CP_GetModels");
			}
		}
		$models_details = Kromeda::js_get_response_api($url);  
		if($models_details != FALSE){
			if(!empty($models_details->result[1]->dataset)){
				$model = $models_details->result[1]->dataset;
			}
		}
		return $model;
	   }
	 /*End*/ 
     
      /*Get Products LS_GetLists */
	 public static function get_ls_list(){
		$api_param = "";
		$url = "get_lists/".$api_param;
		$ls_details = Kromeda::js_get_response_api($url);
		if($ls_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'LS_GetLists' , false , $api_param);
			Kromeda::add_response($url , $third_party_response , "LS_GetLists");
			$ls_details = Kromeda::get_response_api($url);  
			}
		$ls_details_arr = $ls_details->result[1]->dataset;	
		return $ls_details_arr;
	 } 
	 /*End*/
     
      /*Get picture Url API */
	 public static function get_picture_url($ls_id , $image_id){
		$api_param = $ls_id."/".$image_id;
		$url = "get_picture_url/".$api_param;
		$picture_url_details = Kromeda::js_get_response_api($url);
		if($picture_url_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'LS_GetPictureURL' , false , $api_param);
			Kromeda::add_response($url , $third_party_response , "LS_GetLists");
			$picture_url_details = Kromeda::get_response_api($url); 
			}
		if(!empty($picture_url_details->result[1])){
			return $picture_url_details->result[1];
		}
		else return NULL;
	 } 
	 /*End*/
     
	 /*Version  API */
	 public static function get_versions($model_id , $year){
		$model_version_details = [];  
	    $api_param = $model_id."/".$year;
		$url = "getVersion/".$api_param;
		$version_details = Kromeda::js_get_response_api($url);
		if($version_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'CP_GetVersions' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "CP_GetVersions");
			}
		}
		$version_details = Kromeda::js_get_response_api($url);  
		if($version_details != FALSE){
			if(!empty($version_details->result[1]->dataset)){
				$model_version_details = $version_details->result[1]->dataset;	
			}
		}
		return $model_version_details;
	  }
	 /*End*/
	 
	  /*Version  API */
	  public static function get_groups($varsion_id , $lang = "ENG"){
	    $api_param = $varsion_id."/".$lang;
		$url = "getParts/".$api_param;
		$groups_details = Kromeda::js_get_response_api($url);
		if($groups_details == FALSE){
			$sess_key = sHelper::generate_kromeda_session_key();
			if($sess_key != 500){
				$third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetActiveGroups' , false , $api_param);
				Kromeda::add_response($url , $third_party_response , "OE_GetActiveGroups");
			}
		}
		$groups_details = Kromeda::js_get_response_api($url);
		if($groups_details != FALSE){
			$version_group_details = $groups_details->result[1]->dataset;	
			return $version_group_details;
		}  
	  }
	 /*End*/
	 
	/*Get Products group satrt*/
	 public static function get_sub_group($car_version_id  , $group_id , $lang){
		$sub_group = [];
		$api_param = $car_version_id."/".$group_id."/".$lang;
		$url = "getSubParts/".$api_param;
		$get_sub_parts_database_response = Kromeda::js_get_response_api($url);
		if($get_sub_parts_database_response == FALSE){
			  $sess_key = sHelper::generate_kromeda_session_key();
			  if($sess_key != 500){
				  $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'OE_GetActiveSubGroups' , false , $api_param);
				  Kromeda::add_response($url , $third_party_response , "OE_GetActiveSubGroups");
			}
		}
		$get_sub_parts_database_response = Kromeda::js_get_response_api($url);
		if($get_sub_parts_database_response != FALSE){
			if(!empty($get_sub_parts_database_response->result[1]->dataset)){
				$sub_group = $get_sub_parts_database_response->result[1]->dataset;
			}
			return $sub_group;
		}
	  } 
	 /*End*/
     
     public static function get_maker_name($makers_name){
	    $get_makers =  Kromeda::js_get_response_api('getMakers');
		if($get_makers != FALSE){
		    $new_collect_makers = collect($get_makers->result[1]->dataset);
			return $new_collect_makers->firstWhere('idMarca' , $makers_name);
		  }
		//return json_decode($get_makers);
	 }
	 
	 
	 public static function get_model_name($makers_name , $model_name){
		$url = 'getModels/'.$makers_name;
	    $get_models =  Kromeda::js_get_response_api($url);
		if($get_models != FALSE){
		    if(!empty($model_name)){
			    $model_arr = explode('/' , $model_name);
			  }
			$new_collect_models = collect($get_models->result[1]->dataset);
			return $new_collect_models->firstWhere('idModello' , $model_arr[0]);
		  }
	 }
	 
	 
	 public static function get_version_name($model_name , $version_id){
		$url = 'getVersion'.'/'.$model_name;
	    $get_version =  Kromeda::js_get_response_api($url);
		if($get_version != FALSE){
			$new_collect_version = collect($get_version->result[1]->dataset);
			return $new_collect_version->firstWhere('idVeicolo' , $version_id);
		  }
	 }
	 
	 
}


?>