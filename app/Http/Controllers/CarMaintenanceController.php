<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use kromedaSMRhelper;
use sHelper;
use App\ItemsRepairsTimeId;
use App\ItemsRepairsServicestime;
use App\ExcutedQuery;
use kromedaDataHelper;
use Auth;
use serviceHelper;


class CarMaintenanceController extends Controller{
    
	public function post_action(Request $request, $action) {
	    if($action == "add_product_item") {
			if($request->product_item_number){
				$product_item_details = \App\ProductsNew::get_product_item_details($request->product_item_number);
				if(!empty($product_item_details)) {
					$duplicate_exist = \App\OurCarMaintinanceProductItem::where([['item_number', '=', $request->product_item_number], ['item_repairs_parts_id', '=', $request->item_repair_part_id] , ['deleted_at' , '=' , NULL]])->first();
					if($duplicate_exist == NULL) {
						$add_product_items_details = \App\OurCarMaintinanceProductItem::add_car_maintainance_product_item($request->product_item_number, $request->item_repair_part_id, $product_item_details);
						if($add_product_items_details){
							return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong> Success , </strong> Record Added successfully !!!.</div>'));
						} else {
							return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
						}
					} else {
						return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> This Product is Already Added !!!.</div>'));
					}
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Product Not Exist !!!.</div>'));
				}
			}
		}
	    if($action == "edit_maintainance_service_details") {
			$validator = \Validator::make($request->all(), [
				'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
				'hourly_rate'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
            $result = \App\WorkshopCarMaintinanceServiceDetails::save_car_maintainance_details($request);
			if($result){
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong> Success , </strong> Record Updated successfully !!!.</div>'));
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
			}
		}
		if($action == "add_maintenance_services") {
			$validator = \Validator::make($request->all(), [
                'item_name' => 'required','front_rear' => 'required',
                'left_right' => 'required','our_description' => 'required', 
				'our_time' => "required|regex:/^\d+(\.\d{1,2})?$/",
                'info' => 'required',
				'language' => 'required'
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=>$validator->errors()->getMessages(), "status"=>400));
            }
		   $result = \App\ItemsRepairsServicestime::add_new_maintenance_service($request , 2);
		  // echo '<pre>'; print_r($result); die;
			if($result){
				$result = \App\items_repairs_servicestimes_details::add_new_maintenance_service_details($result , $request);
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong> Success , </strong> Record saved successfully !!!.</div>'));
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
			}
		}

        if($action == "edit_maintenance_services") {
        		if(!empty($request->maintenance_id)) {
        			$validator = \Validator::make($request->all(), [
                    'item_name' => 'required','front_rear' => 'required',
                    'left_right' => 'required', 'info' => 'required',
        			'language' => 'required'
                     ]); 
					$result = \App\ItemsRepairsServicestime::find($request->maintenance_id);
					if(!empty($request->edit_maintainance_version)) {
						$result->time_hrs = $request->kromeda_time;
						$result->our_time = $request->our_time;
					}
        			$result->item = $request->item_name;
        			$result->front_rear = $request->front_rear;
        			$result->left_right = $request->left_right;
        			$result->action_description = $request->kromeda_description;
        			//$result->our_description = $request->our_description;
        			$result->id_info = $request->edit_info;
        			$result->language = $request->edit_language;
        			$res = $result->save();
        		if($res){	
					$result = \App\items_repairs_servicestimes_details::add_new_maintenance_service_details($result , $request);
                    return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Service Details Updated successfully !!! </div>'));
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
               }
        	}
       	}
	}
	public function get_action(Request $request , $action){
		if($action == "search_by_item") {
			if(!empty($request->item_name)) {
				$lang = sHelper::get_set_language($request->language);
				$item_name = \App\ItemsRepairsServicestime::search_by_item($request->item_name,$lang);
				foreach ($item_name as $item) {
					$item = kromedaDataHelper::arrange_car_maintinance($item);
				}
				if($item_name != NULL){
					return view('admin.component.car_maintinance')->with(['car_maintinance_service_list' => $item_name]);
				}  {
				  	echo '<div class="notice notice-danger"><strong> Note </strong> No Product Available   !!! </div>';exit;
				}
			} else {
				return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Note </strong> Please enter the Item Name !!! </div>'));
			}
		}
		if($action == "get_car_maintainance_details") {
			if(!empty($request->car_maintainance_id)) {
				/*Get car maintinance */
				   $response = serviceHelper::car_maintinance_price_appoinment($request->car_maintainance_id , Auth::user()->id);
				/*End*/
                return json_encode(['status'=>200 , "hourly_cost"=>$response['hourly_cost'], "max_appointment" =>$response['max_appointment']]);
			}
		}

	    if($action == "get_maintenance_details"){
			if(!empty($request->serviceId)) {
				// $result = \App\ItemsRepairsServicestime::get_maintenance_services_details($request->serviceId);
				$result = \App\ItemsRepairsServicestime::where([['id', '=', $request->serviceId]])->first();
				if(!empty($result)) {
					$result = kromedaDataHelper::arrange_car_maintinance($result);
				}
                if($result){
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
			}
		}
	    
	    
	    if($action == "workshop_car_maintainance_details") {
			if(!empty($request->hourly_rate) && !empty($request->max_appointment)){		
				$validator = \Validator::make($request->all(), [
					'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
					'hourly_rate'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
				]);
				if($validator->fails()){
					return json_encode(array( "error"=> $validator->errors()->getMessages(), "status" => 400));
				}
				$response = \App\WorkshopServicesPayments::save_update_car_maintainance($request);
				$car_maintinance_service_list = \App\ItemsRepairsServicestime::get_workshop_active_items_services();
				if($car_maintinance_service_list->count() > 0){
					$request->items_repairs_servicestimes_id = NULL;
					foreach($car_maintinance_service_list as $keys => $car_maintinance_service){
						$request->items_repairs_servicestimes_id = $car_maintinance_service->id;
						$result = \App\WorkshopCarMaintinanceServiceDetails::save_car_maintainance_details($request);
					}
				}
				if($result){
				 	return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong> Success , </strong> Record saved successfully !!!.</div>'));
				} else {
				  	return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
				} 	   
			} else {
			 	return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
			} 
		}
	   /*Search Script start */

	   if($action == "search_item_services"){
		if(!empty($request->language)){
		  $lang = sHelper::get_set_language($request->language);
		  if(!empty($request->service_time_id)){
			 $car_maintinance_service_list = ItemsRepairsServicestime::get_items_services($request->service_time_id , $lang);
			}
		  else{
			 $car_maintinance_service_list = ItemsRepairsServicestime::items_services_by_version($request->version_id , $lang); 
			}  
		  foreach($car_maintinance_service_list as $service){
			$service = kromedaDataHelper::arrange_car_maintinance($service);
		  }	
		   /*End*/
		  return view('admin.component.car_maintinance_2')->with(['car_maintinance_service_list'=>$car_maintinance_service_list , 'type'=>1]);
		}
		else{
		 echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit;  
		}
	}  
	   
	 /*  if($action == "search_item_services"){
		   if(!empty($request->language)){
			 $lang = sHelper::get_set_language($request->language);
			 if(!empty($request->service_time_id)){
				 $car_maintinance_service_list = ItemsRepairsServicestime::get_items_services($request->service_time_id , $lang);
			   }
			 else{
			    $car_maintinance_service_list = ItemsRepairsServicestime::get_items_services_version($request->version_id , $lang); 
			   }  
			 return view('admin.component.car_maintinance')->with(['car_maintinance_service_list'=>$car_maintinance_service_list , 'type'=>1]);
		   }
		   else{
			echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit;  
		   }
	   }   */
	   
	  /*End */	
	    /*Change status Script start*/	
      	if($action == "change_item_service_status"){
			if(!empty($request->service_item_id)){
				$service_details = ItemsRepairsServicestime::find($request->service_item_id);
				if($service_details != NULL){
					$service_details->status = $request->status;
					if($service_details->save()){
						$result = \App\items_repairs_servicestimes_details::update_maintanance_status($service_details, $request->status);
						echo 1;exit;
					} 
				} else {
					echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
				}	 
			} else {
				echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
			}	
		}		
	  /*End*/
	    /*Get Service time id*/
	      if($action == "get_services_time_id"){
		   if(!empty($request->version_id) && !empty($request->language) && is_numeric($request->version_id)){
			   $lang = sHelper::get_set_language($request->language);
			   $item_times_response = ItemsRepairsTimeId::get_times_ids($request->version_id , $lang);
			   	if($item_times_response->count() > 0){
					if(count($item_times_response) < 2 ){
						$response =  kromedaDataHelper::save_service_times($item_times_response[0]->id , $item_times_response[0]->language);
						echo "<pre>";
						print_r($response);exit;
						return json_encode(array('status'=>300));	 
					} else { 
						return json_encode(array('status'=>200 , 'response'=>$item_times_response));
					}					 
				}
			    else{
				  return json_encode(array('status'=>404 , 'response'=>$item_times_response));
				 }	 
			  
			 }
		  }
	  /*End*/
	   
	   /*Save service time repairs id */
	   if($action == "save_services_time_id"){
		    if(!empty($request->version_id) && !empty($request->language) && is_numeric($request->version_id)){
			  $lang = sHelper::get_set_language($request->language);
			  $get_time_id_response = kromedaSMRhelper::kromeda_version_criteria($request->version_id , $lang);
			  $time_id_arr = json_decode($get_time_id_response);
			  if($time_id_arr->status == 200){
				  if(count($time_id_arr->response) > 0){
					  $item_times_response = ItemsRepairsTimeId::save_item_repairs_time_id($request->version_id , $time_id_arr->response , $lang);
					  return json_encode(array('status'=>200));
					}
				  else{
					  return json_encode(array('status'=>404 , 'msg'=> '<div class="notice notice-danger"><strong>Wrong , </strong> Kromeda vehicle ID not found !!!.</div>'));
					}	
				}
			  else{
				 return json_encode(array('status'=>100 , 'msg'=> '<div class="notice notice-danger"><strong>Wrong , </strong> Kromeda vehicle ID not found !!!.</div>'));	 
				}	
			 }
		    else{
			  return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));	
			}
	 
		 }
	   	 
	   /*End*/	 
	    /*save Service Time script start*/	 	
         if($action == "save_services_time"){
		   if(!empty($request->times_id) && !empty($request->language) && is_numeric($request->times_id)){
			  $lang = sHelper::get_set_language($request->language);
			  $item_times_response  = ItemsRepairsTimeId::find($request->times_id);
			  /*Check record is exist*/
	          $api_param = $item_times_response->version_id."/".$item_times_response->repair_times_id."/".$lang; 
		      $url = "version_repair_time/".$api_param;
			  $check_exist = ExcutedQuery::get_record($url);
			  /*End*/
			  if($check_exist == NULL){
				  if($item_times_response != NULL){
						$services_time_response = kromedaSMRhelper::kromeda_version_service_time($item_times_response->version_id , $item_times_response->repair_times_id , $lang);
						$services_time = json_decode($services_time_response);
						if($services_time->status == 200){
							$response = ExcutedQuery::add_record($url);
							if($lang == "ENG") {
								$time_response = ItemsRepairsServicestime::save_item_repairs_times_eng($item_times_response->version_id, $item_times_response , $services_time->response , $lang); 
							} else if($lang == "ITA") {
								$time_response = ItemsRepairsServicestime::save_item_repairs_times_ita($item_times_response->version_id, $item_times_response , $services_time->response , $lang); 
							}
							if($time_response){ echo 1;exit; }
							else{ echo 2;exit; } 		  
						}
						else{
						echo '<div class="notice notice-danger"><strong>Wrong , </strong> Kromeda vehicle ID not found !!!.</div>';exit;
						}
					}
				  else{
					 echo '<div class="notice notice-danger"><strong>Wrong , </strong> Kromeda vehicle ID not found !!!.</div>';exit;
					}	

			  }
			  else{ echo 1;exit; }
			 }
		    else{
			  echo '<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>';exit;
			}
	   } 	
	   /*End */ 
       if($action == "get_services_time"){
		   if(!empty($request->version_id) && !empty($request->language) && is_numeric($request->version_id)){
			  $lang = sHelper::get_set_language($request->language);
			  $get_time_id_response = kromedaSMRhelper::kromeda_version_criteria($request->version_id , $lang);
			  $time_id_arr = json_decode($get_time_id_response);
			  if($time_id_arr->status == 200){
				   foreach($time_id_arr->response as $time_id){
					  $services_time_response = kromedaSMRhelper::kromeda_version_service_time($request->version_id , $time_id->repair_times_id , $lang);
					  $services_time= json_decode($services_time_response);
					  if($services_time->status == 200){
						    //echo "<pre>";
		                    //print_r($services_time->response);exit;
						if($lang == "ENG"){
						  return view('admin.component.car_maintinance_eng')->with(['service_time'=>$services_time->response]);
						  }
						if($lang == "ITA"){
					        return view('admin.component.car_maintinance_ita')->with(['service_time'=>$services_time->response]);
						  }  
						  //echo "<pre>";
		                  //print_r($time_id->repair_times_id);exit;
						}
					  else{
						 echo '<div class="notice notice-danger"><strong>Wrong , </strong> Kromeda vehicle ID not found !!!.</div>';exit;
						}	
					 }
				}
			  else{
				 echo '<div class="notice notice-danger"><strong>Wrong , </strong> Kromeda vehicle ID not found !!!.</div>';exit;
				}	
			 }
		    else{
			  echo '<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>';
		
			}
	   } 
		
	}
}
