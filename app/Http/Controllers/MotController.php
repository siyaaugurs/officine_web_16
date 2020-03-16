<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use sHelper;
use App\Model\Kromeda;
use App\Products_group;
use App\Users_category;
use kRomedaHelper;
use kromedaSMRhelper;
use App\VersionServiceSchedules;
use App\VersionServicesSchedulesInterval;
use App\ExcutedQuery;
use kromedaDataHelper;
use App\ProductsGroupsItem;

class MotController extends Controller{
    
	public $success = 0;
	
	public static function post_action(Request $request , $action){
		if($action == "add_our_mot_services"){
			$validator = \Validator::make($request->all() , [
				'car_makers' => 'required', 'car_models' => 'required',
				'car_version' => 'required', 'service_name'=>'required', 'service_description'=>'required' , 'service_km'=>"required|regex:/^\d+(\.\d{1,2})?$/",'month'=>'required|numeric'
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$response = \App\Our_mot_services::save_mot_services($request);
			if($response){
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record save successful !!!.</div>'));	
			} 
			else{
			return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>'));
			} 	
		}
		if($action == "edit_mot_services") {
			// return $request;
			$validator = \Validator::make($request->all() , [
				'car_makers' => 'required', 'car_models' => 'required',
				'car_version' => 'required', 'service_name'=>'required', 'service_description'=>'required' , 'service_km'=>"required|regex:/^\d+(\.\d{1,2})?$/",'month'=>'required|numeric'
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$n3_exist_exist = \App\MotN3Category::get_n3_category($request->mot_service_id);
			if(!empty($n3_exist_exist)) {
				$result = \App\MotN3Category::where('our_mot_services_id' , $request->mot_service_id)->delete();
			}
			$response = \App\Our_mot_services::save_mot_services($request);
			if($response){
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record Updated successfully !!!.</div>'));	
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>'));
			}
		}
	}
	
	public function mot_spare_parts(Request $request , $action){
       /*Get Mot item script start*/
	    if($action == "get_parts"){
		     if(!empty($request->item_number)){
				$item_part_number_response = \App\ProductsItemNumber::where([['id' , '=' , $request->item_number]])->get();
				if($item_part_number_response != NULL){
					 if($item_part_number_response->count() > 0){
						foreach($item_part_number_response as $part_number){
						/*OE_Get_cross */
						 $get_products = kromedaHelper::oe_products_item((string) $part_number->CodiceListino , (string) $part_number->CodiceOE);
						    if(is_array($get_products) && count($get_products) > 0){
							 $add_products_response = \App\ProductsNew::add_products_by_mot_service($part_number , $get_products);
							}
							/*End*/   
						/*Oe Get other cross Api response script start*/
						$get_other_products = kromedaHelper::oe_getOtherproducts((string) $part_number->CodiceListino , (string)$part_number->CodiceOE);
						//$get_other_products = kromedaHelper::oe_getOtherproducts("023" , 34116767269);
						//For testing purpose
							if(is_array($get_other_products) && count($get_other_products) > 0){
								/*Custom Query Script Start*/
								$response = \App\ProductsNew::add_other_products_by_mot_service($part_number , $get_other_products);
								/*End*/
							}
					    }
					   }
						$data['products_response'] = kromedaDataHelper::find_products_by_item_number($item_part_number_response); 
						if(!view()->exists('admin.component.mot_part'))
						return view("404")->with($data);
						else  
						return view("admin.component.mot_part")->with($data);	  
				   }
				 else{
			      	echo '<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>';exit;	 
			  }   
			  }
			 else{
			   	echo '<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>';exit;	 
			  } 
		  }
	   /*End*/
	   
	  /*Get Mot Part list Script start*/
	  if($action == "get_part_number_list"){
		   if(!empty($request->kr_part_id)){
		   $data = [];   
		   $kr_part_list_data =  \App\KrPartList::find($request->kr_part_id);
		   if($kr_part_list_data != NULL){
			$data['items_numbers'] = \DB::table('products_item_numbers')->where([['version_id','=' , $kr_part_list_data->version_id] , ['products_groups_items_item_id' , '=' , $kr_part_list_data->idVoce]])->get(); 
			    if($data['items_numbers']->count() <= 0){
				   $get_item_number = kromedaHelper::get_part_number($kr_part_list_data->version_id , $kr_part_list_data->idVoce);
				   if(is_array($get_item_number) && count($get_item_number) > 0){
				    	$part_number_response = \App\ProductsItemNumber::save_product_item_number($get_item_number , $kr_part_list_data);  
					}
				}
			$data['items_numbers'] = \DB::table('products_item_numbers')->where([['version_id','=' , $kr_part_list_data->version_id] , ['products_groups_items_item_id' , '=' , $kr_part_list_data->idVoce]])->get(); 	 
			  if(!view()->exists('admin.component.item_number'))
				 return view("404")->with($data);
			    else  
			   return view("admin.component.item_number")->with($data);	 
			 }
		   else{
			 echo '<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>';exit;	
			  }	 
		 }
		}
	  /*End*/
	}
	
	
	public function get_action( Request $request , $action ){
		if($action == "get_mot_services") {
			if(!empty($request->language)){
				$lang = sHelper::get_set_language($request->language);
				if(!empty($request->version_id)){
					$response = VersionServicesSchedulesInterval::get_intervals_version($request->version_id , $lang);
					if($response) {
						return json_encode(array('status'=>200 , 'response'=>$response));
					} else {
						return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select Any one version  .</div>'));
					}
				}
			}
		}
		if($action == "get_all_group") {
			$lang = sHelper::get_set_language(app()->getLocale());
			if(!empty($request->version_id)) {
				$mot_n3_arr = [];
				$mot_n3_category = \App\MotN3Category::where([['our_mot_services_id' , '=', $request->mot_id]])->get();
				if($mot_n3_category->count() > 0){
					$mot_n3_arr = $mot_n3_category->pluck('n3_category_id')->all();
				}
				$results = \App\Products_group::get_all_groups($request->version_id);
				
				$id_arr = [];
				foreach ($results as $result) {
					array_push($id_arr, $result->id);
				} 
				if($request->version_id != "all") {
					$res = \App\ProductsGroupsItem::get_all_n3_category($id_arr);
				} else {
					$res = \App\ProductsGroupsItem::get_all_unique_n3_category($lang);
				}
				$html_content = "<option value='0'>Select Category</option>";
				if($res->count() > 0){
					foreach($res as $category){
						$selected = NULL;
						if(in_array($category->id , $mot_n3_arr)){
							$selected = "selected"; 
						}
						$html_content .= "<option value=".$category->id." ".$selected.">".$category->item." ".$category->front_rear." ".$category->left_right."</option>"; 
					}
				} else {
					$html_content .= '<option value="0">No Category Available !!!</option>';
				}
				if($res->count() > 0){
					return json_encode(array("status"=>200 , "response"=>$html_content, "n3_category" => $mot_n3_category));
			  	} else {
					return json_encode(array("status"=>100));
				}
			}
		}
	     /*Show all mot n3 category script start*/
	     if($action == "our_mot_service_category"){
	         if(!empty($request->id)){
	             $result = \DB::table('mot_n3_category as mn3')->leftjoin('products_groups_items as pit' , 'pit.id' , '=' , 'mn3.n3_category_id')
	                      ->select('mn3.*' , 'pit.item' , 'pit.front_rear' , 'pit.left_right')
	                       ->where([['our_mot_services_id'  , '=', $request->id]])->get();
	             if($result->count() > 0){
	                //echo "<pre>";
	                //print_r($result);
	                ?>
	                <table class="table table-bordered">
	                    <tr>
	                        <th>Sn.</th>
	                        <th>Category</th>
	                        <th>Front rear</th>
	                        <th>Left right</th>
	                    </tr>
	                    <?php
	                      $i = 1;
	                       foreach($result as $category){
	                          ?>
	                           <tr>
	                            <td><?php echo $i; ?></th>
	                            <td><?php if(!empty($category->item)) echo $category->item; else echo "N/A"; ?></td>
	                            <td><?php if(!empty($category->front_rear)) echo $category->front_rear; else echo "N/A"; ?></td>
	                            <td><?php if(!empty($category->left_right)) echo $category->left_right; else echo "N/A"; ?></td>
	                          </tr>
	                          <?php  
	                          $i++;
	                       }
	                    ?>
	                </table>
	                <?php 
	             }
	             else{
	                 echo '<div class="notice notice-danger"><strong>Note , </strong> Category not available   .</div>';exit; 
	             }
	         }
	         else{
	           echo '<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong , please try again  .</div>';exit;
	         }
	     }
	     /*End*/
	     if($action == "our_mot_service_info"){
		  if(!empty($request->id)){
			  $data['services_details'] = \App\Our_mot_services::find($request->id);
			 // echo "<pre>";
			 // print_r($data['services_details']);exit;
			  if($data['services_details'] != FALSE){
			      if($data['services_details']->car_makers == 1) {
			          $data['maker_details'] = "All Makers";
			      }
			      /*else if($data['services_details']->car_makers == 0) {
			          $data['maker_details'] = "N/A";
			      }*/
			      else {
			          $data['maker_details'] =  \App\Maker::get_makers($data['services_details']->car_makers);
			          
			      }
			      
			      if($data['services_details']->car_version == 1) {
			          $data['version_details'] = "All Versions";
			      } 
			      else if($data['services_details']->car_version == 0) {
			          $data['version_details'] = "N/A";
			      }
			      else {
			          $data['version_details'] = \App\Version::get_version($data['services_details']->car_version); 
			      }
			      if($data['services_details']->car_models == 1) {
			          $data['model_details'] = "All Models";
			      } 
			      else if($data['services_details']->car_models == 0) {
			          $data['model_details'] = "N/A";
			      }
			      else {
			          $data['model_details'] =  \App\Models::get_model($data['services_details']->car_models);
			      }
				}
			  return view('admin.info.our_mot_service')->with($data);	
			} 
		}
		if($action == "get_n3_service_get"){
		  if(!empty($request->language)){
			  echo "<pre>";
			  print_r($request->all());exit;
			}
		}
	    
	    if($action == "get_n3_service"){
		 $lang = sHelper::get_set_language($request->language);
		 $main_url = "get_n3_service/".$request->version."/".$lang;
		 $check_exist_main = ExcutedQuery::get_record($main_url);
		 if($check_exist_main != NULL){ return 1; }
		  set_time_limit(500);
		  if(!empty($request->version)){
			   $groups_response = kromedaDataHelper::get_groups_and_save_05_08($request, $lang);
			   $get_group = Products_group::get_groups($request->makers , $request->model  , $request->version ,  $lang);
			   if($get_group->count() > 0){
				  $kromeda_session_key = sHelper::create_session_key();
				  foreach($get_group as $group){
					  //echo "<pre>";
					  //print_r($group);exit;
					$api_param = $group->car_version."/".$group->id."/".$lang;
		            $execute_url = "group_item/".$api_param;
			        $check_exist = ExcutedQuery::get_record($execute_url);
					if($check_exist == NULL){
					  if($group->parent_id != 0){
					     $url = "getSubPartsItems/".$api_param;
					  /*Get Sub group Products script Start*/
					     $products_item = kromedaHelper::common_request($kromeda_session_key ,$url ,$api_param , 'OE_GetActiveItemsBySubgroup', $lang);
				          }
			          else{
					    /*Get  group Products script Start*/
					     $url2 = "getGroupsPartsItems/".$api_param;
					     $products_item = kromedaHelper::common_request($kromeda_session_key ,$url2 ,$api_param ,'OE_GetActiveItemsByGroup', $lang);
					/*End*/
			         	  } 
						if(!empty($products_item)){
							 $product_response = json_decode($products_item); 
							 if(count($product_response->response) > 0 && is_array($product_response->response)){
							$response =  ProductsGroupsItem::add_group_items_new($product_response->response , $group->id , $lang , $group->car_version);
							if($response){
								$response = ExcutedQuery::add_record($execute_url);
							  }
							 }
						  }
						//$check_exist_main = ExcutedQuery::add_record($main_url);
					  } 
					}
				 } 
			}
		  //echo "function is working";exit;
		}	
	  /*Get interval details script start*/
	   /*Get interval details script start*/
	  if($action == "interval_info"){
		   $data = [];
		   if(!empty($request->id)){
			  $data['interval_info'] = VersionServicesSchedulesInterval::find($request->id);
			  if($data['interval_info'] != NULL){
				  $data['schedule_info'] = VersionServiceSchedules::find($data['interval_info']->version_service_schedules_id);
				 if($data['schedule_info'] != NULL){
					$data['version_details'] = \App\Version::get_version($data['schedule_info']->version_id); 
					$data['model_details'] =  \App\Models::get_model($data['version_details']->model);
					//echo "<pre>";
					//print_r($data['model_details']);exit;
				  }
				}
			  return view('admin.info.service_interval')->with($data);
			 }
		}
	  /*End*/	
	     /*Get Service interval script start*/
	   if($action == "get_services_interval"){
			if(!empty($request->language)){
				$lang = sHelper::get_set_language($request->language);
				if(!empty($request->service_schedule_id)){
					$response = VersionServicesSchedulesInterval::get_intervals($request->service_schedule_id , $lang);
				} else {
					$response = VersionServicesSchedulesInterval::get_intervals_version($request->version_id , $lang); 
				}  
				return view('admin.component.service_interval')->with(['service_interval'=>$response]);
			}
			/* if($request->service_schedule_id != 0){
				$response = VersionServicesSchedulesInterval::get_intervals($request->service_schedule_id , $lang);
				}  */
		}
	  /*End*/
	  //echo $action;exit;
	  /*Save Service Interval script start*/
	     if($action == "save_services_interval"){
		   if(!empty($request->service_shedule) && !empty($request->language)){
			   $lang = sHelper::get_set_language($request->language);
			   /*check record already exists are not */
				 $excute_url = "services_interval/".$request->service_shedule."/".$lang;
				 $check_exist = ExcutedQuery::get_record($excute_url);
				 if($check_exist != NULL){  echo 1;exit; }
			   /*End */
			   $schedule_details = VersionServiceSchedules::find($request->service_shedule);
			   if($schedule_details != NULL){
				   $interval_response = kromedaSMRhelper::service_schedule_interval($schedule_details->version_id , $schedule_details->service_schedule_id , $lang);
				   $response = json_decode($interval_response);
				   if($response->status == 200){
					   if(count($response->response) > 0){
						  //echo "<pre>";
				          //print_r($response->response);exit;
						  foreach($response->response as $schedule_interval){
							 $response = VersionServicesSchedulesInterval::add_schedule_interval($schedule_details , $schedule_interval ,$lang);
							  $this->success = 1;
							}
						   $response = ExcutedQuery::add_record($excute_url);
						 }
					 }
				 }
			 }
		   else
		     return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select Any one version  .</div>'));	 
		  }
	  /*End*/	
	  
	   /*Save Service schedule script start*/
	   if($action == "save_services"){
		   	if(!empty($request->version_id) && !empty($request->language)){
				$lang = sHelper::get_set_language($request->language);
				$version_detail = \DB::table('versions')->where([['idVeicolo' , '=' , $request->version_id]])->first();
				if($version_detail != NULL){
				   $model_details = \App\Models::get_model($version_detail->model);
				   $response = kromedaDataHelper::get_groups_and_save($model_details->maker , $version_detail->model , $request->version_id , $lang);
				   $get_response = kromedaSMRhelper::mot_service_schedule(trim($request->version_id) , $lang);
					$response = json_decode($get_response);
					if($response->status == 200){
						foreach($response->response as $service){
							$response = VersionServiceSchedules::add_service_schedule($request , $service , $lang);
							$this->success = 1;
						}
						/*get service Scheduele*/
						$get_response =  VersionServiceSchedules::get_schedule(trim($request->version_id)  , $lang);
						if($get_response->count() < 2){
							foreach($get_response as $service_schedule){
								$save_response = $this->save_service_interval($service_schedule , $lang);
							}
							return json_encode(array('status'=>404));	
						} else {
							return json_encode(array('status'=>200 , 'response'=>$get_response));	
						} 
						/*End*/ 
					} else { 
						return $response;  
					}
				}
				else{
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select Any one version  .</div>'));
				}
		   }
		   else
		   return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select Any one version  .</div>'));
		}
		/*End*/ 
	} 


	public function save_service_interval($schedule_details , $lang){
		if($schedule_details != NULL){
			$excute_url = "services_interval/".$schedule_details->id."/".$lang;
			$check_exist = ExcutedQuery::get_record($excute_url);
		    if($check_exist == NULL){
				$interval_response = kromedaSMRhelper::service_schedule_interval($schedule_details->version_id , $schedule_details->service_schedule_id , $lang);
				$response = json_decode($interval_response);
				if($response->status == 200){
					if(count($response->response) > 0){
					foreach($response->response as $schedule_interval){
						$response = VersionServicesSchedulesInterval::add_schedule_interval($schedule_details , $schedule_interval ,$lang);
						$this->success = 1;
						}
						$response = ExcutedQuery::add_record($excute_url);
					}
				}
			}	
		  }
	}
	
	
}
