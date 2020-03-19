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
use App\Library\kromedaHelper;
use App\Products;
use App\Model\Kromeda;
use App\ProductsGroupsItem;
use Session;
use DB;
use App\WorkshopServicesPayments;
use App\WorkshopAssembleServices;
use App\ServiceBooking;
use App\Library\sHelper;
use App\Services;
use App\Userwallet;
use App\UserwalletHistory;
use App\MasterTyreMeasurement;


class apiHelper{
   
    /*return Tyre measurement*/
    public static function tyre_measurement(){
		$tyre_measurement = [];
	    $tyre_measurement['width'] = MasterTyreMeasurement::where([['type' , '=' , 6] , ['deleted_at','=',NULL]])->get();
		$tyre_measurement['aspect_ratio'] = MasterTyreMeasurement::where([['type' , '=' , 4] , ['deleted_at','=',NULL]])->get();
		$tyre_measurement['diameter'] = MasterTyreMeasurement::where([['type','=',5] , ['deleted_at','=',NULL]])->get();
		$tyre_measurement['speed_index'] = MasterTyreMeasurement::where([['type','=',3] , ['deleted_at','=',NULL]])->get();
		$tyre_measurement['tyre_type'] = MasterTyreMeasurement::where([['type','=',1] , ['deleted_at','=',NULL]])->get();
		$tyre_measurement['season_tyre_type'] = MasterTyreMeasurement::where([['type','=',2] , ['deleted_at','=',NULL]])->get();
		return $tyre_measurement;
    }
    /*End*/
    
  /*manage registration  wallet*/
  public static function manage_registration_time_wallet($user , $amount , $description = NULL){
    if($user != NULL){
        if($description == NULL){
            $description = "For registration";
          }
        $save_user_wallet_hotory = UserwalletHistory::create(['user_id'=>$user->id , 'title'=>$description , 'description'=>$description, 'amount'=>$amount]);
        if($save_user_wallet_hotory){
           $user_wallet =  Userwallet::where([['user_id' , '=' ,$user->id]])->first();
           if($user_wallet == NULL){
              $user_wallet =  Userwallet::updateOrCreate(['user_id'=>$user->id], ['user_id'=>$user->id]);                 
           }
           $total_amount = $user_wallet->amount + $amount;
           $user_wallet->amount = $total_amount;
           $user_wallet->save();
        }
    }   
 }
/*End*/

   /*Get number for holes script */
  public static function rim_number_of_holes(){
     $arr = [];
	 $url = "get_rim_number_of_holes"; 
	 $response_from_database = \App\Model\Kromeda::get_tyre24_response($url);
		if($response_from_database == NULL){
			 $response = apiHelper::get_soap_response($arr, "getAlloyRimBindings" , 2);
			  if($response != FALSE){
				 $xml = simplexml_load_string($response);
				 $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->getAlloyRimBindingsResponse;
				 
				 $detail_response = json_encode((array)$body);
				 $save_response = \App\Model\Kromeda::save_tyre24_response($url , $detail_response);
				 if($save_response){
				   $response_from_database = \App\Model\Kromeda::get_tyre24_response($url);
				  } 
				}  
			   }
	 if($response_from_database != NULL){
	      $decode_response =  json_decode($response_from_database->response);
		  if(count($decode_response->alloyRimBinding) > 0){
			  return $decode_response->alloyRimBinding;
			}
	   }
  }
/*End*/

    
    /*Soap API Execution Area start*/
	  public static function check_authentication(){
        $base_url = "https://api.tyre-shopping.com/soap/v20101201/";
        $auth_param = "<s11:Envelope xmlns:s11='http://schemas.xmlsoap.org/soap/envelope/'>
                        <s11:Body>
                        <ns1:authenticate xmlns:ns1='https://api.tyre-shopping.com/soap/v20101201/'>
                            <ns1:userId>1606510719</ns1:userId>
                            <ns1:password>23rv10</ns1:password>
                        </ns1:authenticate>
                        </s11:Body>
                    </s11:Envelope>"; 
        try{
            $client = new \GuzzleHttp\Client();
            $request = $client->get($base_url , ['body'=>$auth_param]);
            $response = $request->getBody()->getContents();
            $response = trim($response);
        }
        catch(RequestException  $e){ 
           $response = 500;
        }
        
       //return $response;
        if($response != 500){
            $xml = simplexml_load_string($response);
            $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->securityToken;
            $soap_authenticate_response = json_decode(json_encode((array)$body), TRUE); 
            if(count($soap_authenticate_response) > 0){
               return $soap_authenticate_response['token'];
            }
            else{
               return FALSE;
            }
        }   
      }

    
    
    public static function get_tyre24_api_response($request_param){
        $base_url = "https://api.tyre-shopping.com/soap/v20101201/";
        try{
            $client = new \GuzzleHttp\Client();
            $request = $client->get($base_url , ['body'=>$request_param]);
            $response = $request->getBody()->getContents();
            $response = trim($response);
         }
        catch(RequestException  $e){ 
           $response = 500;
        }
        return $response;
    }   
     
    public static function create_soap_request($request_arr = NULL , $methods){
        //$result = ArrayToXml::convert($arr);
        $soap_request =  "<s11:Envelope xmlns:s11='http://schemas.xmlsoap.org/soap/envelope/'>";
        $soap_request .=    "<s11:Body>";
            $soap_request .=      "<ns1:$methods xmlns:ns1='https://api.tyre-shopping.com/soap/v20101201/'>";      
                foreach($request_arr as $key=>$value){
                    $soap_request .= "<$key>".$value."</$key>";
                 }                
        
            $soap_request .= "</ns1:$methods>";
        $soap_request .=    "</s11:Body>";
        $soap_request .=  "</s11:Envelope>";
        return $soap_request;
    }
    
    
     /*Get tyre details script start*/
	public static function get_soap_response($request_param_arr = NULL , $method , $type = NULL){
        if(!empty($type) && $type == 2){
            $request_param_arr['ns:token'] = self::check_authentication(); 
        }
        else{
            $request_param_arr['ns1:token'] = self::check_authentication(); 
        }
        $request_param = self::create_soap_request($request_param_arr , $method);
        if($request_param){
            $api_response =  self::get_tyre24_api_response($request_param);
            if($api_response != 500){
               return $api_response;          
           }
           else{  return FALSE; }
        }
        else{
            return FALSE;
        }    
    }
    /*End*/
    
    /*Tyre 24 Api response*/
      public static function get_tyre_details($tyre_item_id){
         if(!empty($tyre_item_id)){
               $request_arr = ['ns1:itemId'=>$tyre_item_id , 'ns1:minAvailability'=>'1'];
               $url = "get_tyre24_details/1/".$tyre_item_id;
               /*Get response from kromeda*/
				 $response = \App\Model\Kromeda::get_tyre24_response($url);
               /*End*/
                 if($response == NULL){
                      $response = self::get_soap_response($request_arr, "getDetails");
                      if($response != FALSE){
                              $xml = simplexml_load_string($response);
                              $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->detailPage;
                              $detail_response = json_encode((array)$body);
                              $save_response = \App\Model\Kromeda::save_tyre24_response($url , $detail_response);
                                if($save_response){
                                    $response = \App\Model\Kromeda::get_tyre24_response($url);
                                }
                      }
                 }
                if($response != NULL){
                    return $response->response;
                }  
                else{
                    return FALSE;
                }
         }
      }
    /*End*/
 
public static function get_rim_workmanship_for_rim_type($tyre_type){
    if(!empty($tyre_type)){
        $request_param = ['ns1:rimType'=>$tyre_type];
        $url = "get_rim_workmanship_for_rim_type/$tyre_type";
        $response_from_database = \App\Model\Kromeda::get_tyre24_response($url);
            if($response_from_database == NULL){
                $response = apiHelper::get_soap_response($request_param, "getRimWorkmanshipForRimType");
                    if($response != FALSE){
                        $xml = simplexml_load_string($response);
                        $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->rimWorkmanshipList;
                        $detail_response = json_encode((array)$body);
                            $save_response = \App\Model\Kromeda::save_tyre24_response($url , $detail_response);
                            if($save_response){
                                $response_from_database = \App\Model\Kromeda::get_tyre24_response($url);
                            } 
                    }

            }
            if($response_from_database != NULL){
                $rim_workship_rim_type =  collect(json_decode($response_from_database->response));
                return $rim_workship_rim_type;
            }
            else{
                return FALSE;
            }

    }
    else return FALSE;
}
   
    public static function workshop_car_washing_details($category_id , $car_size , $workshop_id){
        /*
        Success  = 200;
        for remove user = 100;
        */
        $details_collect = [];
        $remove_workshop_users = [];
        $service_details = Services::where([['category_id' , '=' , $category_id] , ['car_size', '=', $car_size] , ['users_id', '=', $workshop_id]])->first();
        if($service_details == NULL){
            $service_payment_data =  WorkshopServicesPayments::where([['category_type' , '=' , 1], ['workshop_id', '=', $workshop_id]])->first();
            if($service_payment_data != NULL){
                $details_collect['hourly_rate'] = $service_payment_data->hourly_rate;
                $details_collect['max_appointment'] = $service_payment_data->maximum_appointment;				
                return json_encode(['status'=>200 , 'response'=>$details_collect]);
             }
            else{
                return json_encode(['status'=>100]);
            }  
        }
        else{
            $details_collect['hourly_rate'] = $service_details->hourly_rate;
            $details_collect['max_appointment'] = $service_details->max_appointment;	
            return json_encode(['status'=>200 , 'response'=>$details_collect]);
        }       		   
    }


    
    
    
    public static function check_assemble_workshop_time_slot($workshop_id , $selected_days_id , $selected_date , $main_category_id , $max_appointment , $assemble_time){
        $flag = 0;
        $workshop_users_days_details = \App\Workshop_user_day::where([['users_id' ,'=', $workshop_id], ['common_weekly_days_id' ,'=',$selected_days_id] , ['deleted_at' , '=' , NULL]])->first();
        if($workshop_users_days_details != NULL){
           /*Get packages or time slot */ 
           $packages_time_slot = \App\Workshop_user_day_timing::where([['users_id' ,'=', $workshop_id], ['workshop_user_days_id' ,'=',$workshop_users_days_details->id] ,['deleted_at' , '=' , NULL]])->get();
           /*End*/ 
         if($packages_time_slot->count() > 0){
             foreach($packages_time_slot as $package){
                    $opening_slot = [];
                    $new_booked_list = [];
                    $opening_slot[] = array($package->start_time, $package->end_time);
                    $booked_assembly_list = ServiceBooking::get_booked_assembly_package($package->id, $selected_date , $main_category_id  , 2);
                    if($booked_assembly_list->count() < $max_appointment) {
                        if($booked_assembly_list->count() > 0){
                            foreach ($booked_assembly_list as $booked) {
                                $new_booked_list[] = [$booked->start_time, $booked->end_time];
                            }
                        }
                        $new_generate_slot = sHelper::get_time_slot($new_booked_list, $opening_slot);
                        if (count($new_generate_slot) > 0) {
                            foreach ($new_generate_slot as $slot_details) {
                                $slot_details['start_time'] = sHelper::change_time_format_2($slot_details[0]);
                                $slot_details['end_time'] = sHelper::change_time_format_2($slot_details[1]);
                                $get_slot_time_in_hour = sHelper::get_number_of_hour($slot_details[0], $slot_details[1]);
                                $total_time = $assemble_time + 0.33;
                                    if($get_slot_time_in_hour >= $total_time) {
                                        return 1;
                                    }
                                    else{
                                       return $flag;
                                    }
                            }
                        }
                        else{
                            return $flag;
                        }
                     }
                     else{
                         return $flag;
                     }                  
           }
        }
        else{ return $flag; }	
     }
    }
    
    public static function check_n2_belongs_in_spare($sub_group_details){
       if($sub_group_details->type == 1){
         return DB::table('spare_category_items as a')
                        ->join('main_category as m' , 'm.id' , '=' , 'a.main_category_id') 
                        ->select('a.*' , 'm.description')
                        ->where([['a.products_groups_group_id' , '=' , $sub_group_details->group_id]])->first();    
       }
       return DB::table('spare_category_items')
                  ->join('main_category as m' , 'm.id' , '=' , 'a.main_category_id') 
                  ->select('a.*' , 'm.description') 
                  ->where([['a.products_groups_id' , '=' , $sub_group_details->id]])->first(); 
    }

    public static function get_assemble_workshop_details($workshop , $main_category_id){
        $workshop_service_details = WorkshopAssembleServices::where([['workshop_id' , $workshop->id] , ['categories_id' , '=' , $main_category_id]])->first();
        if($workshop_service_details != NULL){
            return ['max_appointment'=>$workshop_service_details->max_appointment , 'hourly_rate'=>$workshop_service_details->hourly_rate];
        }
        else{
           $workshop_assemble_service_details =  WorkshopServicesPayments::where([['type' , '=' , 2] , ['workshop_id' , '=' , $workshop->id]])->first();  
           if($workshop_assemble_service_details != NULL){
               return ['max_appointment'=>$workshop_assemble_service_details->maximum_appointment , 'hourly_rate'=>$workshop_assemble_service_details->hourly_rate];
           }
           else{
              return FALSE;
           }   

        }
    }
  /*Get Rims from tyre24 script start*/
   public static function get_rims($maker_details){
      $request_param = ['ns1:rimManufacturer'=>$maker_details->Marca];
	   $maker_slug = sHelper::slug($maker_details->Marca);
       $url = "get_rim/$maker_details->idMarca/$maker_slug"; 
       /*Get Data from database*/
	     $response_from_database = \App\Model\Kromeda::get_tyre24_response($url);
	   /*End*/
	     if($response_from_database == NULL){
		    $response = apiHelper::get_soap_response($request_param , "getRims");	
			  if($response != FALSE){
				 $xml = simplexml_load_string($response);
				 $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->rimList;
				 $detail_response = json_encode((array)$body);
				 $save_response = \App\Model\Kromeda::save_tyre24_response($url , $detail_response);
				 if($save_response){
				   $response_from_database = \App\Model\Kromeda::get_tyre24_response($url);
				  } 
				}  
		   }
		/*Return Data from database*/
		 if($response_from_database != NULL){
		      $decode_rims = json_decode($response_from_database->response);
			  if(count((array) $decode_rims) > 0){
				  return $decode_rims;
				}
			  else{
				  return FALSE; 
				}	
		   }
		 else return FALSE;  
		/*End*/     
   }
/*End*/

/*Get Rim detaisl Script Start*/
  public static function get_rim_details($rim_id){
       /*Get From Database*/
	     $rim_details = \App\RimDetails::get_rim_detail($rim_id);
	   /*End*/
	     if($rim_details == NULL){
			   $request_param = ['ns1:alcar'=>4095, 'ns1:minAvailability'=>1];
		       $response = apiHelper::get_soap_response($request_param , "searchRims");  
			   if($response != FALSE){
				   $xml = simplexml_load_string($response);
				   $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->searchRimsResponse;
                   $detail_response = json_encode((array)$body);
                   if((array) $detail_response){
					    return $detail_response;  
					 }
				    else return FALSE;
				 }
		   }
		  else{
		     return $rim_details->rim_details_response;
		   } 
   }
/*End*/

public static function get_rim_tyre_for_manufacturer($maker_name){
    if(!empty($maker_name)){
        $request_param = ['ns1:rimManufacturer'=>$maker_name];
        $maker_slug = sHelper::slug($maker_name);
        //$url = "get_rim_type_for_rim_manufacturer/$maker_slug";
        $response = apiHelper::get_soap_response($request_param, "getRimTypesForRimManufacturer");
        if($response != FALSE){
            $xml = simplexml_load_string($response);
            $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->rimTypeList;
            $detail_response = json_decode(json_encode((array)$body), TRUE); 
            try{
                if(count($detail_response) > 0){
                    return $detail_response;
               }
            } 
            catch(RequestException  $e){ 
                return FALSE;
             } 
       }
    }
     
}


	
    
}