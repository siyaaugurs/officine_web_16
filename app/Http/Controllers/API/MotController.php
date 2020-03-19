<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use sHelper;
use apiHelper;
use App\Model\UserDetails;
use App\User;
use App\Our_mot_services;
use App\VersionServicesOperation;
use App\KrPartList;
use App\VersionServicesSchedulesInterval;
use App\Feedback;
use Illuminate\Support\Facades\Auth;
use App\WorkshopMotServiceDetails;
use App\Workshop_user_day_timing;
use App\Http\Controllers\Coupon;
use App\Http\Controllers\MotController as mot_controller;
use serviceHelper;
use App\Library\orderHelper;
use DB; 
use App\Gallery;
use kromedaDataHelper;
use kromedaSMRhelper;

class MotController extends Controller{
	
	public $main_category = 3;
	public $mot_main_category = 3;

	public function get_mot_service(Request $request){
		$lang = sHelper::get_set_language($request->language);
		$user_details = UserDetails ::find($request->selected_car_id);
		if(!empty($request->service_km)){
			if($user_details != NULL){
				$request->version_id = $user_details->carVersion;
				$mot_services_schedules_intervals = VersionServicesSchedulesInterval::where([['version_id','=',$user_details->carVersion],['deleted_at', '=', NULL] , ['language','=', $lang]])->get();
					if($mot_services_schedules_intervals->count() <= 0) {
						$version_detail = \DB::table('versions')->where([['idVeicolo' , '=' , $request->version_id]])->first();
						if($version_detail != NULL){
							$model_details = \App\Models::get_model($version_detail->model);
							$response = kromedaDataHelper::get_groups_and_save($model_details->maker , $version_detail->model , $request->version_id , $lang);
							if(!empty($user_details->carVersion) && !empty($lang)){
								$get_response = kromedaSMRhelper::mot_service_schedule(trim($user_details->carVersion) , $lang);
								$response = json_decode($get_response);
								if($response->status == 200){
									foreach($response->response as $service){
										$response = \App\VersionServiceSchedules::add_service_schedule($request , $service , $lang);
										$this->success = 1;
									}
									$get_response =  \App\VersionServiceSchedules::get_schedule(trim($user_details->carVersion)  , $lang);
									if($get_response->count()){
										$mot_obj = new mot_controller;
										foreach($get_response as $service_schedule){
											$save_response = $mot_obj->save_service_interval($service_schedule , $lang);
										}	
									}
								}
							}		
						}	
					}
					if(!empty($request->edit_status)){
						DB::table('user_details')->where([['id' , '=' ,$user_details->id]])->update(['km_of_cars'=>$request->service_km]);
					}
				/*Get our mot services*/
					$new_our_mot_services = [];
					if(!empty($user_details->carVersion)){
						$our_mot_services = Our_mot_services::whereIn('car_version',[$user_details->carVersion, 'all' ,0])->where('deleted_at', '=', NULL)->get();  												
					}
				/*End*/
					if($our_mot_services->count()>0){
						foreach($our_mot_services as $our_mot){
							$new_our_mot_services[] = kromedaDataHelper::arrange_mot_service($our_mot);	
						}
					}
					$our_mot_service_obj = collect($new_our_mot_services);
					$mot_services_schedules_intervals = VersionServicesSchedulesInterval::where([['version_id','=',$user_details->carVersion],['deleted_at', '=', NULL] , ['language','=',$lang]])->get();
					$mot_services_schedules_intervals = $mot_services_schedules_intervals->map(function($service){
						$service->type = 1;
						$service->type_status = 'Kromeda MOT';
						$service->service_name = null;
						$service->min_price = null;
						return $service;
					});  
				$our_mot_service_obj = $our_mot_service_obj->merge($mot_services_schedules_intervals);
				$our_mot_service_obj = $our_mot_service_obj->map(function($service){
					$service->main_category_id = $this->main_category;
					return $service;
				});  
				if(empty($request->service_km)){
					$request->service_km = 0;
				} 
				$to_km_range = $request->service_km + 5000;
				$our_mot_service_obj = $our_mot_service_obj->whereBetween('service_kms', [$request->service_km , $to_km_range])->values();
				return sHelper::get_respFormat(1 , null , null , $our_mot_service_obj);
			} else {
				return sHelper::get_respFormat(0 , "Please check your car , something went wrong !!!" , null , null);
			}
		}
		else{
			return sHelper::get_respFormat(0 , "Please add service KM. !!!" , null , null);
		}
	}
	
	public function get_mot_service_operation(Request $request){
		set_time_limit(500);
		$validator = \Validator::make($request->all(), ['mot_id'=>'required|numeric' , 'type'=>'required']);
			if($validator->fails()){
				return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
			}
			if($request->type == 1){
				/*Mot save  */
				$service = VersionServicesSchedulesInterval::where([['id','=',$request->mot_id] , ['deleted_at', '=', NULL]])->first();
				/*Check service operations*/
				$operations = VersionServicesOperation::where([['version_services_schedules_intervals_id' , '=' , $request->mot_id] , ['deleted_at' ,'=', NULL]])->get();
				if($operations->count() <= 0){
					kromedaDataHelper::save_mot_spare_parts($service , $service->language);
				}
				/*End*/
			}
			else if($request->type == 2){
				$service = Our_mot_services::where([['id','=',$request->mot_id] , ['deleted_at', '=', NULL]])->first();
				if($service != NULL)
				  $service = kromedaDataHelper::arrange_mot_service($service);	
			}
			else{
				return sHelper::get_respFormat(1 , "Something Went wrong , please try again !!!" , null , null);
			}
			if($service != NULL){
				$service->operations = $service->k_partList = $service->mot_part_numbers = $service->service_average_time  = NULL;
				if($request->type == 1){
					$service->operations = VersionServicesOperation::where([['version_services_schedules_intervals_id' , '=' , $request->mot_id]])->where('deleted_at', '=', NULL)->get();
				}
				/*Find parts list*/
			   $k_partList_response = json_decode(kromedaDataHelper::find_mot_part_list($service , $request->type  , $request->version));
			   if($k_partList_response->status == 200){
					$service->k_partList = $k_partList_response->product_response;
					$service->service_average_time = !empty($k_partList_response->service_average_time) ? (string) $k_partList_response->service_average_time : null;
				}
				return sHelper::get_respFormat(1 ,null , $service , null);
				/*End*/
			}
			else{
				return sHelper::get_respFormat(1 , "Something Went wrong , please try again !!!" , null , null);
			}
	}


 
public function get_workshop_for_mot_service(Request $request){
	$coupon_obj = new Coupon;
	$main_category = 3;
	$off_days_workshop_users = $new_workshop_list = [];
	$minPrice = $maxPrice = $service_time = $flag = 0;
	$validator = \Validator::make($request->all(), [
		'selected_date'=>'required','service_id'=>'required','type'=>'required'
	]);
	if($validator->fails()){
		return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
	}
	$off_workhops_on_that_day = sHelper::get_off_users_on_date($request->selected_date);
	if($request->type == 1){
		$service = VersionServicesSchedulesInterval::where([['id','=',$request->service_id] , ['deleted_at', '=', NULL]])->first();
	}
	elseif($request->type == 2){
		$service = Our_mot_services::where([['id','=',$request->service_id] , ['deleted_at', '=', NULL]])->first();
	}
	/* if($service != NULL){
		 $service = kromedaDataHelper::arrange_car_maintinance($service);
	} */
	$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
	$find_mot_wrokshop = \App\Users_category::get_workshop_list($off_days_workshop_users,$main_category);
				if($find_mot_wrokshop->count()>0){
					$service_id = $request->service_id;
					foreach($find_mot_wrokshop as $workshop){
						$workshop->users_id = $workshop->users_id;
						$service_details = serviceHelper::mot_service_price_appoinment($request->service_id , $workshop->users_id ,$main_category);
						if($service_details['status'] == 200){
							$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop->users_id);
							if($time_slots->count() > 0){
								$workshop->profile_image_url = null;
								if(!empty($user_detail->profile_image)){
									$workshop->profile_image_url = url("storage/profile_image/$user_detail->profile_image");
								  }
								$mot_time_status = sHelper::check_mot_service_time_slot($time_slots ,  $service_time , $request ,$service_id,$workshop->users_id);
								$workshop->latitude = $workshop->longitude = 0.0;
								$primary_address = \App\Address::get_primary_address($workshop->users_id);
								if($primary_address != NULL){
									$workshop->wish_list = 0;
									$workshop->latitude = $primary_address->latitude;
									$workshop->longitude = $primary_address->longitude;
									$workshop->available_status = 1;
									$workshop->service_id = (string) $request->service_id;
									$workshop->service_average_time = (string) $request->service_average_time;
									/*service price manage */
									$workshop->services_price = (string) sHelper::calculate_service_price($service_details['hourly_cost'] , $request->service_average_time);
									/*End*/
									$workshop->coupon_list =  $coupon_obj->find_workshop_coupon($workshop->users_id ,  $this->mot_main_category , $request->selected_date);
								
									$workshop->workshop_gallery = Gallery::get_all_images($workshop->users_id);
									/*Manage workshop feedback api */
									$workshop = sHelper::manage_workshop_feedback_in_api($workshop , $workshop->users_id); 
									/*End*/
									if(!empty($request->user_id)){
										$user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_workshop($workshop->users_id , $request->user_id);
										if($user_wishlist_status == 1){
											$workshop->wish_list = 1;
										}	
									}
									$new_workshop_list[] = $workshop;
								}
							}
						}
				}
				$new_workshop_list = collect($new_workshop_list);
				$minPrice = $new_workshop_list->min('services_price');
				$maxPrice = $new_workshop_list->max('services_price');  
				if($new_workshop_list->count() > 0){
					if(!empty($request->rating)) {
						$rating_arr = explode(',', $request->rating);
						$new_workshop_list =  $new_workshop_list->whereBetween('rating_star', $rating_arr);
					}
					else{
						$new_workshop_list->sortByDesc('rating_star'); 
					}
					if (!empty($request->price_range)) {
						$price_arr = explode(',', $request->price_range);
						$new_workshop_list = $new_workshop_list->whereBetween('services_price', $price_arr);
					}
					if(!empty($request->price_level)) {
						if ($request->price_level == 1) {
							$new_workshop_list = $new_workshop_list->sortBy('services_price')->values();
						}	else if ($request->price_level == 2) {
						$new_workshop_list = $new_workshop_list->sortByDesc('services_price')->values();
						}
					}else {
						$new_workshop_list = $new_workshop_list->sortBy('services_price')->values();
					 }
					$new_workshop_list->map(function($workshop) use ($minPrice , $maxPrice){
						$workshop->min_price = $minPrice;
						$workshop->max_price = $maxPrice;
						return $workshop;
					});	  
					if($new_workshop_list->count() > 0){
						return sHelper::get_respFormat(1 , null, null, $new_workshop_list);		
					}
					else{
						return sHelper::get_respFormat(0 , "No workshop available !!!", null, null);		
					}	  
			}
			else{
				return sHelper::get_respFormat(0 , "No Workshop available !!!" , null , null);
			}
		}	

}

	
	   //get next seven_days_min_price_for_service
	public function get_next_seven_days_min_price_for_mot_service(Request $request) {
		$main_category = 3;
		 $validator = \Validator::make($request->all(), [
		      'selected_date'=>'required' , 'type'=>'required'
	     ]);
		if($validator->fails()){
             return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		 }
		$min_price = [];
		/*Selected workshop those are off this selected days and dates*/
		$selected_date = $request->selected_date;
		for ($i = 0; $i < 30; $i++) {
			$request->selected_date = date('Y-m-d', strtotime($selected_date . ' + ' . $i . ' day'));
			$off_days_workshop_users = [];
			if (!empty($request->selected_date)) {
				$off_selected_date = \App\Workshop_leave_days::get_valid_workshop($request);
				$users_arr = $off_selected_date->pluck('users_id');
				$off_days_workshop_users = $users_arr->all();
			}
			$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
			if(empty($request->service_id)) {
					return sHelper::get_respFormat(0, "please select service", null, null);
			}
			$all_selected_workshop = \App\Users_category::get_workshop_list($off_days_workshop_users,$main_category);
			$price = null;
			$service_time = 0;
			$remove_workshop_arr = [];
			$service_id = $request->service_id;
			if ($all_selected_workshop->count() > 0) {
				foreach ($all_selected_workshop as $workshop_users) {
					$workshop_users->max_appointment = 0;
					$workshop_users->hourly_cost =  (string)  0;
					if($request->type == 1){
						$our_mot_services_schedules_intervals = VersionServicesSchedulesInterval::where('id',$request->service_id)->where('deleted_at', '=', NULL)->first();
						$service_time = $our_mot_services_schedules_intervals->standard_service_time_hrs;
					}else{
						$mot_n3_category = DB::table('mot_n3_category')->where('our_mot_services_id', $request->service_id)->get();
						foreach($mot_n3_category as $n3_category){
							$n3_category_id = $n3_category->n3_category_id;
							$get_service_time = DB::table('items_repairs_servicestimes')->where('id', $n3_category_id)->first();
							$get_service_time_detail = DB::table('items_repairs_servicestimes_details')->where('items_repairs_servicestimes_item_id' ,$get_service_time->item_id)->first();
							if($get_service_time != NULL){		
							/*Get Service Time script start*/
							if(!empty($get_service_time_detail->our_time)){
								$service_time = $get_service_time_detail->our_time;
							} elseif(!empty($get_service_time_detail->k_time)) {
								$service_time = $get_service_time_detail->k_time;
							}else{
								$service_time = $get_service_time->time_hrs;
							}
							} else {
								return sHelper::get_respFormat(0, "Please select valid service !!!", null, null); 
							}
						}
					}
					/*max appointment and hourly cost*/
					$service_details = serviceHelper::mot_service_price_appoinment($service_id, $workshop_users->users_id ,$main_category);
						if(!empty($service_details['hourly_cost'])){
							$workshop_users->hourly_cost = $service_details['hourly_cost'];
						}
						if(!empty($service_details['max_appointment'])){
							$workshop_users->max_appointment = $service_details['max_appointment'];
						}
						if($service_details['hourly_cost'] = 0 || $service_details['max_appointment'] == 0 ){
						$remove_workshop_arr[] =  $workshop_users->users_id;
						}
						$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $workshop_users->users_id] , ['deleted_at' , '=' , NULL]])->get();
						if($workshop_package_timing->count() == 0){
						$remove_workshop_arr[] =  $workshop_users->users_id;
						}		
				}	
				$all_selected_workshop = $all_selected_workshop->whereNotIn('users_id' , $remove_workshop_arr);
				if($all_selected_workshop->count() > 0){
					$hourly_cost = $workshop_users->hourly_cost;
					$all_selected_workshop->map(function($workshop) use ($service_time,$hourly_cost){
                        $workshop->service_hourly_average_price = sHelper::calculate_service_price($service_time , $hourly_cost);
					});
					$min_price_collect = $all_selected_workshop->min('service_hourly_average_price');
					$min_price[] = array('date' => $request->selected_date, 'price' =>(string) $min_price_collect);
				} else {
					$min_price[] = array('date' => $request->selected_date, 'price'=>(string) $min_price );
				}
			/*  end */
		}
	}
	return sHelper::get_respFormat(1, " ", null, $min_price);		
	}

	//service booking for mot
	public function mot_service_booking(Request $request){
		$coupon_obj =  new Coupon;
		$main_category = 3;
		$validator = \Validator::make($request->all(), [
		      'package_id'=>'required|numeric' , 'start_time'=>'required' , 'end_time'=>'required' , 'price'=>'required' , 
		      'selected_date'=>'required',
			  'service_id'=>'required',
			  'selected_car_id'=>'required'
		]);
		if($validator->fails()){
             return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		}
		if(Auth::user()->id){
			/* Get package details*/
			$get_package_details = Workshop_user_day_timing::find($request->package_id);
			if($get_package_details == NULL){
				return sHelper::get_respFormat(0 , "package is not defined !!! ." , null , null); 
			}
			//check time and date from current date
			$s_time = sHelper::change_time_formate($request->start_time);
			$e_time = sHelper::change_time_formate($request->end_time);
			$current_time_zones = sHelper::get_current_time_zones($request->ip);
			date_default_timezone_set($current_time_zones);
			$today_current_timezone = date('Y-m-d H:i');
			$service_date_time_zone = $request->selected_date." ".$request->start_time;
			if($service_date_time_zone < $today_current_timezone){
				return sHelper::get_respformat(0,"Please select correct booking date for booking , you not booked services in past time ." ,null, null);
			}
			
			//Apply special condition
			$special_condition = \App\Service_special_condition::get_special_condition($main_category ,$request->workshop_id);  
		   
			if($special_condition != NUll){
				$special_condition_value =[];
				$special_condition_apply_status = 0;
                foreach($special_condition as $special_conditions){
					if($special_conditions->operation_type == 1){
					
						if(!empty($special_conditions->all_services != 1)){	
							
							if($special_conditions->category_id != $request->service_id){
								$special_condition_apply_status = 0;
							} else {
								$special_condition_apply_status = 1;
							}	
						} else {
								$special_condition_apply_status = 1;
						}
							
						$user_details = UserDetails ::find($request->selected_car_id);
						$obj = new SpecialCondition; 
						$special_condition_apply_status = $obj->match_maker($special_conditions , $user_details);
						
						if($special_condition_apply_status == 1){
								$special_condition_apply_status = $obj->match_model($special_conditions , $user_details);	
							} 
							
							if($special_condition_apply_status == 1) {
								$special_condition_apply_status = $obj->match_version($special_conditions , $user_details);
							}
						
							if($special_condition_apply_status == 1) {
								$special_condition_apply_status = $obj->match_types($request->start_time , $request->end_time , $request->selected_date , $special_conditions);
							}
							//print_r($special_condition_apply_status);
							//die;
						//count max appoinment
						$count_booked_appointment = \App\ServiceBooking::count_car_booked_special_package($request->package_id , $special_conditions->workshop_id , $special_conditions->id  , 8); 
							if($count_booked_appointment->count() == $special_conditions->max_appointement){
								$special_condition_apply_status  = 0;
							}
							if($special_condition_apply_status != 0){
								$special_condition_value = $special_conditions;
								break;
							}	
						}
					}	
				}
				$s_time = sHelper::change_time_formate($request->start_time);
				$e_time = sHelper::change_time_formate($request->end_time);
				/*Get Workshop Service Appointment and hourly rate*/
				$service_details = serviceHelper::mot_service_price_appoinment($request->service_id, $get_package_details->users_id ,$main_category);
						if(!empty($service_details['max_appointment'])){
							$max_appointment = $service_details['max_appointment'];
						} else {
							$max_appointment = 1; 
						}
				if($s_time >= $get_package_details->start_time && $s_time <= $get_package_details->end_time){
						if($e_time >= $get_package_details->start_time && $e_time <= $get_package_details->end_time){
						    if($get_package_details != NULL){
							    /*Count booked Appointment*/
							    $count_booked_appointment = \App\ServiceBooking::get_booked_package_mot_service($request->package_id , $request->selected_date ,8 ,$request->service_id);
								 	if($count_booked_appointment->count() == $max_appointment){
									 	return sHelper::get_respFormat(0 , "All appointment of this package is completely booked !!! ." , null , null); 
								 	}
					   /*End*/
						//check special condition
						$discount_price = 0;
						$special_id = 0;
						if(!empty($special_condition_value)){
							// find discount for rp/per
							$special_id = $special_condition_value->id;
							if($special_condition_value->discount_type == 1){
								$discount_price = $special_condition_value->amount_percentage;
							} else {
								$discount_price = ($request->price/ 100) * $special_condition_value->amount_percentage;
							}
						}
						// check validity coupon for mot service
						if(!empty($request->coupon_id)){
							$coupon_resp = json_decode($coupon_obj->check_coupon_validity($request->coupon_id ,$request->selected_date , $request->price));
							if($coupon_resp->status != 200){
								return sHelper::get_respFormat(0 ,$coupon_resp->msg,null,null);
							}else{
								if($coupon_resp->status == 200){
									$save_amount = apiHelper::manage_registration_time_wallet(Auth::user() , $coupon_resp->price , "MOT service coupon.");	
								}
							}
						}
						$order_manage = \App\Products_order::save_order($request ,0,0 ,null ,0,1);
						if($order_manage){
							$request->order_id = $order_manage->id;
						}
						$service_vat = orderHelper::calculate_vat_price($request->price);
						
						$after_discount_price = ( $service_vat +  $request->price ) - $discount_price;
						$booking_result = \App\ServiceBooking::add_booking_for_mot_service($request , $get_package_details,$discount_price ,$special_id , $service_vat , $after_discount_price);
						$order_manage = \App\Products_order::save_order($request ,$request->discount,$request->price, null,$after_discount_price);
						//save parts for mot service 
						$parts = json_decode($request->parts);
						if(!empty($parts)){
							foreach($parts as $part){
								$save_part_order = \App\Products_order_description::save_product_discription($request ,$part ,$request->order_id , $booking_result->id ,2);
							}
						}
					} else{
						return sHelper::get_respFormat(0 , "Please select correct package id ." , null , null); 
					}
					} else {
						return sHelper::get_respFormat(0 , "please check you end time !!! ." , null , null);  
					}
					} else {
						return sHelper::get_respFormat(0 , "please check you start time !!! ." , null , null);   
					}
				 	if($booking_result){
						   return sHelper::get_respFormat(1 , "Booking successfully !!! " , $booking_result ,null); 
					} else {
						  return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
					}  
				} else {
					return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null);  
				}
			}
			
	//get MOT service pacakges	
	public function mot_services_package(Request $request){
		$validator = \Validator::make($request->all(), ['selected_date'=>'required' , 'workshop_id'=>'required' , 'service_id'=>'required' , 'selected_car_id'=>'required']);
		if($validator->fails()){
			return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		} 
		$special_condition_obj = new SpecialCondition;
		$selected_days_id = \sHelper::get_week_days_id($request->selected_date); 
		$workshop = \App\User::get_workshop_details($request->workshop_id);
			if($workshop != NULL){
				$workshop->users_id = $workshop->id;
				/*find service details */
				if((int) $request->type == 1){
					$service_detail = VersionServicesSchedulesInterval::where([['id' , '=', $request->service_id] , ['deleted_at', '=', NULL]])->first();																		 
				}
				if((int) $request->type == 2) {
					$service_detail = Our_mot_services::where([['id','=',$request->service_id] , ['deleted_at', '=', NULL]])->first();
				}
				/*End*/
				if($service_detail != NULL){
					$workshop_service_details = DB::table('workshop_mot_service_details')->where([['service_id', '=' , $request->service_id] , ['workshop_id', '=' , $request->workshop_id]])->first();
					if($workshop_service_details != NULL){
						$workshop->profile_image_url = NULL;
						if(!empty($workshop->profile_image)){
							$workshop->profile_image_url = url("storage/profile_image/$workshop->profile_image");
						}
						$workshop->hourly_rate = (string) $workshop_service_details->hourly_cost;
						$workshop->service_id = (string) $request->service_id;
						$workshop->main_category_id = $request->main_category_id  = $this->mot_main_category; 
						$workshop->workshop_id = (string) $request->workshop_id;
						$workshop->max_appointment = (string) $workshop_service_details->max_appointment;  
						$time_slots = sHelper::workshop_time_slot($request->selected_date , $request->workshop_id);
						if($time_slots->count() > 0){
							foreach ($time_slots as $slot_details) {
								$new_booked_list = $new_booked_arr = $opening_slot = $not_applicable_hour  = $not_applicable_hour_slot = $new_booked_list_slot =  $not_applicable_hour_arr = [];
								$opening_slot[] = [$slot_details->start_time, $slot_details->end_time];
								$decode_response = json_decode($special_condition_obj->do_not_perform_operation_for_mot($request , $opening_slot));
								if($decode_response->status == 200){
										$not_applicable_hour = $decode_response->response;
										if(count($not_applicable_hour) > 0){
											foreach($not_applicable_hour as $n_applicable){
												$not_applicable_hour_arr[] = [$n_applicable[0], $n_applicable[1]];
												$not_applicable[0] = sHelper::change_time_format_2($n_applicable[0]);
												$not_applicable[1] = sHelper::change_time_format_2($n_applicable[1]);
												$not_applicable['start_time'] = sHelper::change_time_format_2($n_applicable[0]);
												$not_applicable['end_time'] = sHelper::change_time_format_2($n_applicable[1]);
												$not_applicable['id'] = $slot_details->id;
												$not_applicable['price'] = null;
												$not_applicable['hourly_price'] = null;
												$not_applicable['categories_id'] = $request->service_id;
												$not_applicable['available_status'] = 2;
												$new_slots[] =  $not_applicable; 
											}
										}
									}
									$new_generate_applicable_slot = sHelper::get_time_slot($not_applicable_hour_arr , $opening_slot);
								   
									$query = \App\ServiceBooking::where([['workshop_user_id','=',(int) $request->workshop_id] ,
																		 ['type' , '=' , 4],['services_id' , '=' ,$request->service_id] , ['status' , '=' , 'C']]);
										$query->whereDate('booking_date' , $request->selected_date);
										if(!empty($request->user_id)){
											$query->orWhere([['users_id' , '=' ,$request->user_id], ['type' , '=' , 8]])->whereIn('status' ,['CA' ,'P'])->whereDate('booking_date' , $request->selected_date);
										} 	
									 $booked_list = $query->get();  
									 if ($booked_list->count() > 0) {
										 foreach($booked_list as $booked) {
											 $new_booked_list_slot[] = [$booked->start_time, $booked->end_time];
											 $booked_arr[0] = sHelper::change_time_format_2($booked->start_time);
											 $booked_arr[1] = sHelper::change_time_format_2($booked->end_time);
											 $booked_arr['start_time'] = sHelper::change_time_format_2($booked->start_time);
											 $booked_arr['end_time'] = sHelper::change_time_format_2($booked->end_time);
											 $booked_arr['id'] = $booked->workshop_user_day_timings_id;
											 $booked_arr['categories_id'] = $booked->services_id;
											 $booked_arr['price'] = null;
											 $booked_arr['hourly_price'] = null;
											 $booked_arr['available_status'] = 0;
											 $new_slots[] =  $booked_arr;
										 }
									 } 
									 $new_generate_slot = sHelper::get_time_slot($new_booked_list_slot, $new_generate_applicable_slot);
									 if (count($new_generate_slot) > 0) {
										 foreach ($new_generate_slot as $slot) {
											 $slot['start_time'] = sHelper::change_time_format_2($slot[0]);
											 $slot['end_time'] = sHelper::change_time_format_2($slot[1]);
											 $slot['id'] = $slot_details->id;
											 $slot['categories_id'] =  $request->service_id;
											 $slot['price'] = (string) $workshop_service_details->hourly_cost;
											 $slot['hourly_price'] = (string) $workshop_service_details->hourly_cost;
											 if($booked_list->count() < $workshop_service_details->max_appointment){
												 $get_slot_time_in_hour = sHelper::get_number_of_hour($slot[0], $slot[1]);
												  if ($get_slot_time_in_hour < $request->service_average_time) {
													 $slot['available_status'] = 0;
												 } else {
													 $slot['available_status'] = 1;
												 }
											 }
											 else{
												 $slot['available_status'] = 0;
											 }
											 $new_slots[] = $slot;
										 }
									 }
								 }
								 $new_new_slots =  collect($new_slots);
								 if($new_new_slots->count() > 0){
										 $slots_available = $new_new_slots->sort()->values();
								 }
								
								 $workshop->package_list = $slots_available;
								 $workshop->workshop_gallery = Gallery::get_all_images($request->workshop_id);
								 $workshop = sHelper::manage_workshop_feedback_in_api($workshop , $request->workshop_id); 
								 return sHelper::get_respFormat(1, null, $workshop , null);
							 
						} else {
							return sHelper::get_respFormat(0 ,"Time slots not available for this day !!!", null, null);
						}   

					}
					else{

					}

				}
				else{
					sHelper::get_respFormat(0 ,"Something went wrong , please select a valid service  !!!", null, null);  
				}
				
				}
				else{
					return sHelper::get_respFormat(0 ,"Something went wrong , workshop not available !!!", null, null);
				}
	}


	
}
