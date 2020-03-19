<?php
namespace App\Http\Controllers\API;
use App\Feedback;
use App\Gallery;
use App\Http\Controllers\Controller;
use App\Services_package;
use DB;
use Illuminate\Http\Request;
use sHelper;
use App\Library\kromedaHelper;
use Auth;
use App\ServiceBooking;
use kromedaSMRhelper;
use App\Library\apiHelper;


class CategoryController extends Controller {
    
     public static function get_assemble_workshop_package($workshop_id , $selected_date ,$product_id,$car_id , $user_id = 0){
		$special_condition_obj = new SpecialCondition;
	    if(!empty($workshop_id)){
		    if(!empty($selected_date)){
			  if(!empty($product_id)){
				 $workshop_user_details = \App\User::check_users_type($workshop_id , 2);
				 $find_all_workshop = \App\Users_category::get_users_category($workshop_id);
					if(empty($find_all_workshop)){
						 $find_all_workshop=NULL; 
					}
				 	if($workshop_user_details != NULL){
						
					 	$dayOfWeek = date("l", strtotime($selected_date));
					 	$get_days_value = \DB::table('common_weekly_days')->where('name', '=', trim($dayOfWeek))->first();
					   if($get_days_value != null) {
						 	 $workshop_service_days = \App\Workshop_user_day::get_service_weekly_days($workshop_id ,$get_days_value->id);
						  if($workshop_service_days != NULL){
							 	$workshop_days_timing = \App\Workshop_user_day_timing::get_packages($workshop_service_days->id);
							  if($workshop_days_timing->count() > 0){
								  
								  	$workshop_details = \App\User::get_workshop_details($workshop_id);
								  if($workshop_details != NULL){
									  /*Get price and average time code script start*/
									    $product_details = \App\ProductsNew::find_product_details($product_id);
										
										if($product_details != NULL){
										  if(!empty($product_details->assemble_kromeda_time)){
											  $average_time = $product_details->assemble_kromeda_time;
											} else {
											  $average_time = $product_details->assemble_time;
											} 
										  if(empty($average_time)){ $average_time = 1; } 
										  	$total_average_time = $average_time + 0.33;	
											 
										  	$products_groups_details = \App\Products_group::where([['deleted_at' , '=' , NULL] , ['status' , '=' , 'A'] , ['id','=' ,$product_details->products_groups_id]])->first();
										  
										  if($products_groups_details != NULL){
												//print_r($products_groups_details); die;
												$blongs_to_in_assemble_services = apiHelper::check_n2_belongs_in_spare($products_groups_details);
												// print_r('7'); die;	
												if($blongs_to_in_assemble_services != NULL){
													
													$workshop_service_details = apiHelper::get_assemble_workshop_details($workshop_details , $blongs_to_in_assemble_services->main_category_id);
													//$service_price_and_appointment =  \App\WorkshopAssembleServices::find_workshop_price($workshop_id , $blongs_to_in_assemble_services->main_category_id);
												    if($workshop_service_details != FALSE){
														if(!empty($workshop_service_details['max_appointment'])){
															$max_appointment = $workshop_service_details['max_appointment'];
														}
														else{ $max_appointment = 1; }
														$hourly_rate = (string) 0; 
														if(!empty($workshop_service_details['hourly_rate'])){
															$hourly_rate = $workshop_service_details['hourly_rate']; 
														}	 	 
														/*Manage workshop feedback api */
														$workshop_details = sHelper::manage_workshop_feedback_in_api($workshop_details , $workshop_id); 
														/*End*/
														$workshop_details->workshop_gallery = \App\Gallery::get_all_images($workshop_id);
														/*For Services Images*/
														$workshop_details->service_images = NULL;													
														$workshop_details->about_services = $blongs_to_in_assemble_services->description; 
														$workshop_details->main_category_id = $blongs_to_in_assemble_services->main_category_id; 
														/*End*/
														$workshop_details->users_id = $workshop_details->id;
														$workshop_details->profile_image_url = NULL;
														if(!empty($workshop_details->profile_image)){
															$workshop_details->profile_image_url = url("storage/profile_image/$workshop_details->profile_image");
															}
															$workshop_details->hourly_rate = (string) $workshop_service_details['hourly_rate'];
															$workshop_details->services_price = null;
															$workshop_details->maximum_appointment = $max_appointment;
															/*Set use less API Key*/
															$workshop_details->category_id = "0";
															$workshop_details->status = "0";
															$workshop_details->products_id = $product_id;
															$workshop_details->car_size = 0;
															$workshop_details->type = 0;
															$workshop_details->service_average_time = (string) $average_time;
															$workshop_details->service_detail = $find_all_workshop;
															/*End*/
															/*Set Key in packages*/
															if($workshop_days_timing->count() > 0){
																$workshop_days_timing->map(function ($packages) use($average_time , $hourly_rate , $max_appointment) {
																	$packages['available_status'] = 1;
																	$packages['maximum_appointment'] = $max_appointment;
																	$packages['price'] = (string) sHelper::calculate_service_price($average_time , $hourly_rate);
																	$packages['categories_id'] = 0;
																	$packages['hourly_price'] = (string) $hourly_rate;
																	return $packages;
																});
															}
															/*End*/
															/*Split Service package in booked or not booked*/
															if ($workshop_days_timing->count() > 0) {
																$new_new_slot = $booked_package_id_arr = [];
																$package_list =  [];
																foreach ($workshop_days_timing as $slot) {
																	$new_booked_list = $new_booked_arr = $opening_slot = $not_applicable_hour  = $not_applicable_hour_slot = $new_booked_list_slot =  [];
																	$opening_slot[] = array($slot->start_time, $slot->end_time);
																/*Check do not perform operation */
																$special_condition_status = $special_condition_obj->do_not_perform_operation_for_car_assemble($workshop_details->main_category_id , $selected_date , $workshop_id , $slot,$car_id);
																$special_condition_response = json_decode($special_condition_status);
																if($special_condition_response->status == 200){
																	if(is_array($special_condition_response->response)){
																		$not_applicable_hour = $special_condition_response->response;
																		foreach($not_applicable_hour as $n_applicable){
																			$not_applicable_hour_slot[] = [$n_applicable[0], $n_applicable[1]];
																			$not_applicable[0] = sHelper::change_time_format_2($n_applicable[0]);
																			$not_applicable[1] = sHelper::change_time_format_2($n_applicable[1]);
																			$not_applicable['start_time'] = sHelper::change_time_format_2($n_applicable[0]);
																			$not_applicable['end_time'] = sHelper::change_time_format_2($n_applicable[1]);
																			$not_applicable['id'] = $slot->id;
																			$not_applicable['categories_id'] = sHelper::change_time_format_2($n_applicable[1]);
																			$not_applicable['price'] =  (string) sHelper::calculate_service_price($average_time , $hourly_rate);
																			$not_applicable['hourly_price'] =  (string) $average_time;
																			$not_applicable['available_status'] = 2;
																			$new_booked_arr[] =  $not_applicable; 
																		}
																			
																	}
																}
																/*End*/	
														
																$new_generate_applicable_slot = sHelper::get_time_slot($not_applicable_hour_slot , $opening_slot);
																/*manage booking slot*/
																$query = \App\ServiceBooking::where([['workshop_user_id','=',(int) $workshop_id] ,['type' , '=' , 2] ,['status' ,'=' , 'C'],
																['services_id' , '=' ,$workshop_details->main_category_id]]);
																$query->whereDate('booking_date' , $selected_date);
																if($user_id != 0){
																	$query->orWhere([['users_id','=' ,$user_id],['workshop_user_id' ,'=' , (int)$workshop_id],['type' ,'=' ,2]])->whereIn('status',['CA','P'])->whereDate('booking_date' , $selected_date);
																}	
																$booked_list = $query->get();	
																$book_max_appointment = $booked_list->count();					
																if($booked_list->count() < $max_appointment){
																if ($booked_list->count() > 0) {
																	foreach($booked_list as $booked) {
																		$new_booked_list_slot[] = [$booked->start_time, $booked->end_time];
																		$booked_arr[0] = sHelper::change_time_format_2($booked->start_time);
																		$booked_arr[1] = sHelper::change_time_format_2($booked->end_time);
																		$booked_arr['start_time'] = sHelper::change_time_format_2($booked->start_time);
																		$booked_arr['end_time'] = sHelper::change_time_format_2($booked->end_time);
																		$booked_arr['id'] = $booked->workshop_user_day_timings_id;
																		$booked_arr['categories_id'] = $booked->services_id;
																		$booked_arr['price'] = (string) sHelper::calculate_service_price($average_time , $hourly_rate);;
																		$booked_arr['hourly_price'] = (string) $average_time;
																		$booked_arr['available_status'] = 0;
																		//$booked_arr['service_average_time'] = (string) $service_average_times;
																		$new_booked_arr[] =  $booked_arr;
																	}
																} 
															}	
												/*End*/
												//$new_generate_slot = sHelper::get_time_slot($new_booked_list , $opening_slot);
												$new_generate_slot = sHelper::get_time_slot($new_booked_list_slot, $new_generate_applicable_slot);
												if (count($new_generate_slot) > 0) {
													foreach ($new_generate_slot as $slot_details) {
														$slot_details['start_time'] = sHelper::change_time_format_2($slot_details[0]);
														$slot_details['end_time'] = sHelper::change_time_format_2($slot_details[1]);
														$slot_details['id'] = $slot->id;
														$slot_details['categories_id'] = $slot->categories_id;
														$slot_details['price'] = (string)  sHelper::calculate_service_price($average_time , $hourly_rate);;
														$slot_details['hourly_price'] = (string) $hourly_rate;
														$get_slot_time_in_hour = sHelper::get_number_of_hour($slot_details[0], $slot_details[1]);
														if($book_max_appointment < $max_appointment){
															if ($get_slot_time_in_hour < $average_time) {
																$slot_details['available_status'] = 0;
															} else {
																$slot_details['available_status'] = 1;
															}
														} else {
															$slot_details['available_status'] = 1;
														}
														$new_booked_arr[] = $slot_details;
													}
												}
														$package_list = array_merge($package_list, $new_booked_arr);	
											}
											$new_package_list = collect($package_list)->sortBy('start_time');
											$new_package_list_2  = [];
											foreach($new_package_list as $p_list){
												$new_package_list_2[] = $p_list;
											}
											$workshop_details->package_list = $new_package_list_2;
											/*End*/
											$workshop_details->min_price = null;
											$workshop_details->max_price = null;
							/*End*/	
											}
														//}
											return sHelper::get_respFormat(1 ,"", $workshop_details, null);   
											} else {
											return sHelper::get_respFormat(0 ," Workshop hourly rate not available !!!", null, null);   
											}
										} else {
											return sHelper::get_respFormat(0 ,"Something Went Wrong , please try again  !!!", null, null);   
										}
				                         } else {
									     return sHelper::get_respFormat(0 ,"Please select correct products !!!", null, null);   
										 } 
										 /*End*/
									} else {
									return sHelper::get_respFormat(0 ,"Workshop not available !!!", null, null);   
									}	
								} else {
								return sHelper::get_respFormat(0 ,"Packages not available !!!", null, null);  
								}	
							} else {
							    return sHelper::get_respFormat(0 ,"Packages not available !!!", null, null);        
							  }
						    //$get_weeks_days_id = $get_days_value->id;
							//  $get_service_details = \App\Services::get_assembly_services_workshop($products_id , $workshop_user_id);	
						   } else {
						   return sHelper::get_respFormat(0 ,"Something Went wrong please try again !!!", null, null);   
						 }	
					
				     } else {
				    return sHelper::get_respFormat(0 ,"This workshop is not valid !!!", null, null); 
				  }  
				} else {
				 return sHelper::get_respFormat(0 ,"Please select a date , when you want to service !!!", null, null);  
				}	
			} else {
			  return sHelper::get_respFormat(0 ,"Please select a date , when you want to service !!!", null, null);
			}   
		 }  else {
		  return sHelper::get_respFormat(0 ,"Please Enter the workshop id", null, null);
		}	 
	}
}	
		
    
    public function version_repair_time($version_id , $time_id , $lang){
	      $services_time_response = kromedaSMRhelper::kromeda_version_service_time($version_id , $time_id , $lang);
	      echo "<pre>";
	      print_r($services_time_response);exit;
	}
    
    public function get_selected_car($user_details_id) {
		if(!empty($user_details_id)) {
			$users_car_list = \App\Model\UserDetails::where('user_id' , '=' , Auth::user()->id)->get();
			if($users_car_list->count() > 0){
				$update_record = \App\Model\UserDetails::where('user_id' , '=' , Auth::user()->id)->update(['selected'=>0]);
			}
			$user_detail = \App\Model\UserDetails::find($user_details_id);
			if($user_detail != NULL) {
				$user_detail->selected = 1;
				$user_detail->save();
				return sHelper::get_respFormat(1, "Done", null, null);
			} else {
				return sHelper::get_respFormat(0, " Record Not Found.", null, null);
			}
		} else {
			return sHelper::get_respFormat(0, "Un-expected , please enter car id .", null, null);
		}
	}

   public function assemble_workshop_with_amount(Request $request){
	    DB::enableQueryLog();
		 $validator = \Validator::make($request->all(), [
		      'selected_date'=>'required' , 'type'=>'required','products_id'=>'required' 
	     ]);
		if($validator->fails()){
             return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL);		 }
		 $min_price = array(); $workshop_users_arr = []; $diff_allowed_workshop_users = [];
		 $selected_date = $request->selected_date;
		 /*products valid or not*/
		 //$product_details = \App\ProductsNew::where([['id','=',$request->products_id] , ['deleted_at' , '=' , NULL] , ['products_status','=' , 'A']])->first();
		 $product_details = \App\ProductsNew::where([['id','=',$request->products_id] , ['deleted_at' , '=' , NULL] , ['products_status' , '=' , 'A']])->first();
		 if($product_details != NULL){
		       $assemble_average_time = !empty($product_details->assemble_kromeda_time) ? $product_details->assemble_kromeda_time :  $product_details->assemble_time;
		         if(empty($assemble_average_time)) $assemble_average_time = 1; 
		   /*Get All assemble Worksgops*/
			$products_groups_details = \App\Products_group::where([['deleted_at' , '=' , NULL] , ['status' , '=' , 'A'] , ['id','=' ,$product_details->products_groups_id]])->first();
			   if($products_groups_details != NULL){
				  $blongs_to_in_assemble_services = apiHelper::check_n2_belongs_in_spare($products_groups_details);
				 if($blongs_to_in_assemble_services != NULL){
                       $main_category_id = $blongs_to_in_assemble_services->main_category_id;
				       $workshop_users_arr =  DB::table('users_categories')->where([['categories_id', '=',$blongs_to_in_assemble_services->main_category_id] , 
				       ['deleted_at' , '=' , NULL]])->get();
				       if($workshop_users_arr->count() < 1){
						 return sHelper::get_respFormat(0, "No , Workshop available !!!", null, null); 
					   } 
					}
				 else{
				    return sHelper::get_respFormat(0, "No , Workshop available !!!", null, null); 
				   }  		
				 }
				else{
				  return sHelper::get_respFormat(0, "please select correct products", null, null); 
				 } 
		   /*End*/
		   $main_service_prices = []; 
		   for($i = 0; $i < 30; $i++) {
			  $request->selected_date = date('Y-m-d', strtotime($selected_date . ' + ' . $i . ' day')); 
			  if(!empty($selected_date)){
				  $off_selected_date = \App\Workshop_leave_days::get_valid_workshop($request);
			      $off_days_workshop_users = $off_selected_date->pluck('users_id')->all();
				}
			   $selected_days_id = \sHelper::get_week_days_id($request->selected_date);
			   if($workshop_users_arr->count() > 0){
					$all_workshop_users_id_arr = $workshop_users_arr->pluck('users_id')->unique();
					
				    $diff_allowed_workshop_users = $all_workshop_users_id_arr->diff($off_days_workshop_users)->all();
				} 
			   /*Get Assemble Workshop*/
			   $all_selected_workshop = \App\User::get_assemble_workshop($diff_allowed_workshop_users , $main_category_id , $selected_days_id); 
			   $service_prices = [];
			   $min_price = 0;
			   $remove_assemble_workshop_user_arr = [];
			   if($all_selected_workshop->count() > 0){
				   foreach($all_selected_workshop as $workshop){
					   /*Check workshop assemble service details*/
					    $workshop_service_details = apiHelper::get_assemble_workshop_details($workshop , $blongs_to_in_assemble_services->main_category_id);
                        if($workshop_service_details == FALSE){
						   $remove_assemble_workshop_user_arr[] = $workshop->id;  
						   continue;
						 } 
					   /*End*/
					   $service_prices[] =  sHelper::calculate_service_price($assemble_average_time , $workshop_service_details['hourly_rate']);
					}	
				 $min_price = min($service_prices);  
			   }
				$main_service_prices[] = array('date' => $request->selected_date, 'price'=>(string) $min_price );
			}
			/*End*/
		   }
		  else{
		    return sHelper::get_respFormat(0, "products details is not valid !!!", null, null);
		   } 
		return sHelper::get_respFormat(1, " ", null, $main_service_prices);
		 /*End*/
	}
	
	
   	 
   

	public function get_next_seven_days_min_price(Request $request) {
		DB::enableQueryLog();
		 $validator = \Validator::make($request->all(), [
		      'selected_date'=>'required' , 'type'=>'required' , 'car_size'=>'required'
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
			if ($request->type == 1) {
				if(empty($request->category_id)) {
					return sHelper::get_respFormat(0, "please select any one category", null, null);
				}
				$category_details = DB::table('categories')->where('id' , $request->category_id)->first();
				if($category_details != NULL){
				   /*Get service average time */
					$average_timing = sHelper::get_car_wash_service_time($request->car_size , $category_details->id);
					/*End*/
				}
				else{
				 return sHelper::get_respFormat(0, "Please select valid category !!!", null, null); 
				}
				$all_selected_workshop = \App\Services::get_car_wash_services_workshop_new1(NULL , $request->car_size , $off_days_workshop_users);			    
			}
			$price = null;
			$remove_workshop_arr = [];
			if ($all_selected_workshop->count() > 0) {
				foreach ($all_selected_workshop as $workshop_users) {
					/*get workshop services details*/
					 $workshop_services_detail =   apiHelper::workshop_car_washing_details($request->category_id , $request->car_size , $workshop_users->users_id);
					 $service_details_response = json_decode($workshop_services_detail);
					 if($service_details_response->status == 100){
						$remove_workshop_arr[] = $workshop_users->users_id;
					   }
					 else{
							if($service_details_response->status == 200){
								$workshop_users->hourly_rate = $service_details_response->response->hourly_rate;
							}
					 }  
					 /*End*/
					$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $workshop_users->users_id] , ['deleted_at' , '=' , NULL]])->get();
					if($workshop_package_timing->count() == 0){
						$remove_workshop_arr[] =  $workshop_users->users_id;
					  } 					
				}
				$all_selected_workshop = $all_selected_workshop->whereNotIn('users_id' , $remove_workshop_arr);
				if($all_selected_workshop->count() > 0){
					$all_selected_workshop->map(function($workshop ) use ($average_timing){
                       $workshop->service_hourly_average_price = sHelper::calculate_service_price($average_timing , $workshop->hourly_rate);
					});
					$min_price_collect = $all_selected_workshop->min('service_hourly_average_price');
				}
				$min_price[] = array('date' => $request->selected_date, 'price'=>(string) $min_price_collect );
				/*  end */
			} else {
				$min_price[] = array('date' => $request->selected_date, 'price' =>(string) $min_price);
			}
		}
		return sHelper::get_respFormat(1, " ", null, $min_price);
	}


public function get_workshop_service_package($workshop_user_id, $category_service_id, $selected_date , $car_size,$selected_car_id , $user_id = 0){
	$special_condition_obj = new SpecialCondition;	
	$max_appointment = 0;
	$hourly_rate  = 0;
			if (!empty($workshop_user_id)) {
				if (!empty($category_service_id)) {
				     $category_details = DB::table('categories')->where([['id' , '=' , $category_service_id]])->first();
					  $find_all_workshop = \App\Users_category::get_users_category($workshop_user_id);
					  if(empty($find_all_workshop)){
						 $find_all_workshop=NULL; 
					  }
					  $business_details = DB::table('business_details')->select('about_business')->where([['users_id' , '=', $workshop_user_id]])->first();
					  if(!empty($business_details)){
						 $business_details = $business_details->about_business; 
					  }	else{
						 $business_details = NULL; 
					  }
				     $workshop_user_details = \App\User::check_users_type($workshop_user_id, 2);
					if ($workshop_user_details != null) {
						/*Change in days*/
						//Get the day of the week using PHP's date function.
						$dayOfWeek = date("l", strtotime($selected_date));
						$get_days_value = \DB::table('common_weekly_days')->where('name', '=', trim($dayOfWeek))->first();
						$get_weeks_days_id = null;
						if ($get_days_value != null) {
							$get_weeks_days_id = $get_days_value->id;
						}
						/*End*/
						$get_service_details = \App\Services::get_carwash_workshop_details($workshop_user_id);
						$get_service_details->service_images = null;
						if($get_service_details != NULL){
						   $service_details = \App\Services::where([['category_id' , '=' , $category_service_id] , ['car_size', '=', $car_size] , ['users_id', '=', $get_service_details->users_id]])->first();
						  	if($service_details == NULL){
								$service_payment_data =  \App\WorkshopServicesPayments::where([['category_type' , '=' , 1], ['workshop_id', '=', $workshop_user_id]])->first();
								if($service_payment_data != NULL){
								$get_service_details->hourly_rate= $service_payment_data->hourly_rate;
								$get_service_details->maximum_appointment = $service_payment_data->maximum_appointment;			
								}
							}else{
								$get_service_details->hourly_rate = $service_details->hourly_rate;
								$get_service_details->maximum_appointment = $service_details->max_appointment;
								//$images = \App\Gallery::get_category_image($category_service_id);
								//print_r($images); die;
							//	$get_serviceImage = Gallery::get_service_image($service_details->id);
								//if($images->count() > 0) {
								//$get_service_details->service_images = $images;
								//}
							}
						}
						//echo "<pre>";
						//echo "<pre>";
						//print_r($get_service_details);exit;
						$get_service_details->workshop_gallery = \App\Gallery::get_all_images($workshop_user_id);
						$images = \App\Gallery::get_category_image($category_service_id);
						if($images->count() > 0) {
							$get_service_details->service_images = $images;
						}
						$get_service_details->profile_image_url = NULL;
						$get_service_details->services_price = null;
						$get_service_details->products_id = null;
						$get_service_details->status = "1";
						$get_service_details->type = null;
						$get_service_details->is_deleted_at = null;
						//$get_service_details->about_services = $category_details->description;
						$get_service_details->category_id = $category_service_id;
						$get_service_details->car_size = $car_size;
						$get_service_details->main_category_id = null;
						$get_service_details->service_detail = $find_all_workshop;
						$get_service_details->about_business = $business_details;	
						if($get_service_details == NULL){
							return sHelper::get_respFormat(0, " Workshop Not Available !!! ", null, null);
						}
						/*Get Service images */
						$get_service_details->profile_image_url = NULL;
						$get_service_details->services_price = null;
						if(!empty($get_service_details->profile_image)){
							 $get_service_details->profile_image_url = url("storage/profile_image/$get_service_details->profile_image");
						   }
						/*End*/
						/*Manage workshop feedback api */
						$workshop = sHelper::manage_workshop_feedback_in_api($get_service_details , $workshop_user_id); 
						/*End*/
						$get_service_details->package_list = null;
						//$get_service_details->service_average_time =$service_average_times;
						$get_services_weekly_days = null;
						if ($get_service_details != null) {
							//$get_services_weekly_days = \App\Service_weekly_days::get_service_weekly_days($get_service_details->id, $get_weeks_days_id);
							$get_services_weekly_days = \App\Workshop_user_day::get_service_weekly_days($workshop_user_id ,$get_weeks_days_id);
							$packages_list = null;
							if ($get_services_weekly_days != null) {
								//$get_service_packages = Services_package::get_packages($get_services_weekly_days->id);
								$get_service_packages = \App\Workshop_user_day_timing::get_packages($get_services_weekly_days->id);
								/*Get service time  and prices*/
								$service_average_times = sHelper::get_car_wash_service_time($car_size , $category_service_id);
								/*$get_services_times = DB::table('service_time_prices')
								->where([['categories_id' , '=' ,$category_service_id]])->first();*/
								if($service_average_times == NULL){
									return sHelper::get_respFormat(0, " Something went wrong please try again ", null, null);
								}						
								$get_service_details->service_average_time = (string) $service_average_times;
								$max_appointment = $get_service_details->maximum_appointment;
								$price = sHelper::calculate_service_price($service_average_times , $get_service_details->hourly_rate);
								/*End*/
								if($get_service_packages->count() > 0){
								   $get_service_packages->map(function ($packages) use($max_appointment , $price , $service_average_times) {
									 $packages['maximum_appointment'] = $max_appointment;
									 $packages['price'] = (string) $price;
									 $packages['service_average_time'] = (string) $service_average_times;
									 return $packages;
								   });
								}
								if ($get_service_packages->count() > 0) {
									//$packages_list = $get_service_packages;
									//$booked_package_id_arr = [];
									//$new_new_slot = [];
									$new_new_slot = $booked_package_id_arr = [];
									$package_list =  [];
										foreach ($get_service_packages as $slot) {
												$new_booked_list = $new_booked_arr = $opening_slot = $not_applicable_hour  = $not_applicable_hour_slot = $new_booked_list_slot =  [];
												$opening_slot[] = array($slot->start_time, $slot->end_time);
											/*Check do not perform operation */
							   				$special_condition_status = $special_condition_obj->do_not_perform_operation_for_car_maintenance(1 , $selected_date ,$workshop_user_id , $slot,$selected_car_id,$category_service_id);
											$special_condition_response = json_decode($special_condition_status);
											if($special_condition_response->status == 200){
												if(is_array($special_condition_response->response)){
													$not_applicable_hour = $special_condition_response->response;
													foreach($not_applicable_hour as $n_applicable){
														$not_applicable_hour_slot[] = [$n_applicable[0], $n_applicable[1]];
														$not_applicable[0] = sHelper::change_time_format_2($n_applicable[0]);
														$not_applicable[1] = sHelper::change_time_format_2($n_applicable[1]);
														$not_applicable['start_time'] = sHelper::change_time_format_2($n_applicable[0]);
														$not_applicable['end_time'] = sHelper::change_time_format_2($n_applicable[1]);
														$not_applicable['id'] = $slot->id;
														$not_applicable['categories_id'] = sHelper::change_time_format_2($n_applicable[1]);
														$not_applicable['price'] = (string) $price;
														$not_applicable['hourly_price'] = (string) $get_service_details->hourly_rate;
														$not_applicable['available_status'] = 2;
														$new_booked_arr[] =  $not_applicable; 
													}
														
												}
											}
											/*End*/		
											$new_generate_applicable_slot = sHelper::get_time_slot($not_applicable_hour_slot , $opening_slot);
											/*manage booking slot*/
											$query  = \App\ServiceBooking::where([
											 ['workshop_user_id','=',(int) $workshop_user_id] ,
											 ['type' , '=' , 1] ,
											 ['status' , '=' , 'C'],
											 ['services_id' , '=' ,$category_service_id]]);
											 $query->whereDate('booking_date' ,$selected_date);
											 if($user_id != 0)
											 {
												$query->orWhere([['users_id','=', $user_id],['workshop_user_id' ,'=' , (int)$workshop_user_id],['type' ,'=' , 1]])->whereIn('status' ,['P' ,'CA'])
												->whereDate('booking_date' ,$selected_date);	
											 }
											 $booked_list = $query->get();
											$book_max_appointment = $booked_list->count();					
											if($booked_list->count() < $max_appointment){
											if ($booked_list->count() > 0) {
												foreach($booked_list as $booked) {
													$new_booked_list_slot[] = [$booked->start_time, $booked->end_time];
													$booked_arr[0] = sHelper::change_time_format_2($booked->start_time);
													$booked_arr[1] = sHelper::change_time_format_2($booked->end_time);
													$booked_arr['start_time'] = sHelper::change_time_format_2($booked->start_time);
													$booked_arr['end_time'] = sHelper::change_time_format_2($booked->end_time);
													$booked_arr['id'] = $booked->workshop_user_day_timings_id;
													$booked_arr['categories_id'] = $booked->services_id;
													$booked_arr['price'] = (string) $price;
													$booked_arr['hourly_price'] = (string) $get_service_details->hourly_rate;
													$booked_arr['available_status'] = 0;
													//$booked_arr['service_average_time'] = (string) $service_average_times;
													$new_booked_arr[] =  $booked_arr;
												}
											} 
										}	
										/*End*/
										//$new_generate_slot = sHelper::get_time_slot($new_booked_list , $opening_slot);
										$new_generate_slot = sHelper::get_time_slot($new_booked_list_slot, $new_generate_applicable_slot);
										if (count($new_generate_slot) > 0) {
											foreach ($new_generate_slot as $slot_details) {
												$slot_details['start_time'] = sHelper::change_time_format_2($slot_details[0]);
												$slot_details['end_time'] = sHelper::change_time_format_2($slot_details[1]);
												$slot_details['id'] = $slot->id;
												$slot_details['categories_id'] = $slot->categories_id;
												$slot_details['price'] = (string) $price;
												$slot_details['hourly_price'] = (string) $get_service_details->hourly_rate;
												$get_slot_time_in_hour = sHelper::get_number_of_hour($slot_details[0], $slot_details[1]);
												if($book_max_appointment < $max_appointment){
													if ($get_slot_time_in_hour < $service_average_times) {
														$slot_details['available_status'] = 0;
													} else {
														$slot_details['available_status'] = 1;
													}
												} else {
													$slot_details['available_status'] = 1;
												}
												
												$new_booked_arr[] = $slot_details;
											}
										}
									$package_list = array_merge($package_list, $new_booked_arr);	
							}
							$new_package_list = collect($package_list)->sortBy('start_time');
							$new_package_list_2  = [];
							foreach($new_package_list as $p_list){
								$new_package_list_2[] = $p_list;
							}
							$get_service_details->package_list = $new_package_list_2;
							/*End*/
							$get_service_details->min_price = null;
							$get_service_details->max_price = null;	
									// foreach ($get_service_packages as $package) {
									//    $package->hourly_price =  (string) $hourly_rate;   
									//    if(empty($max_appointment)) $max_appointment = 1;
									//    else $max_appointment = $max_appointment;
									// 	$opening_slot = array();
									// 	$opening_slot[] = array($package->start_time, $package->end_time);
									// 	$get_service_slot_time_min = sHelper::get_number_of_hour($package->start_time, $package->end_time);
									// 	/*End */
									// 	$booked_list = ServiceBooking::get_service_booked_package($package->id, $selected_date , $category_service_id , $car_size , 1);
									//    if($booked_list->count() < $max_appointment){
									// 		$slot_arr = [];
									// 		if ($booked_list->count() > 0) {
									// 			$booked_package_id_arr[] = $package->id;
									// 			$new_booked_list = [];
									// 			$booked_service_arr = [];
									// 			$booked_arr = [];
									// 			$new_booked_arr = [];
									// 			foreach ($booked_list as $booked) {
									// 				//$get_service_slot_time_min_1 = sHelper::get_number_of_hour($package->start_time , $booked->start_time);
									// 				$new_booked_list[] = [$booked->start_time, $booked->end_time];
									// 				$booked_arr[0] = sHelper::change_time_format_2($booked->start_time);
									// 				$booked_arr[1] = sHelper::change_time_format_2($booked->end_time);
									// 				$booked_arr['start_time'] = sHelper::change_time_format_2($booked->start_time);
									// 				$booked_arr['end_time'] = sHelper::change_time_format_2($booked->end_time);
									// 				$booked_arr['id'] = $package->id;
									// 				$booked_arr['categories_id'] = $package->categories_id;
									// 				$booked_arr['price'] = (string) $price;
									// 				$booked_arr['hourly_price'] = (string) $hourly_rate;
									// 				$booked_arr['available_status'] = 0;
									// 				//$booked_arr['service_average_time'] = (string) $service_average_times;
									// 				  $new_booked_arr[] =  $booked_arr;
									// 			}			
									// 			$new_generate_slot = sHelper::get_time_slot($new_booked_list, $opening_slot);
									// 			//$new_generate_slot = sHelper::get_time_slot();
									// 			//return $new_generate_slot;
									// 			if (count($new_generate_slot) > 0) {
									// 				$packages = [];
									// 				foreach ($new_generate_slot as $slot_details) {
									// 					$slot_details['start_time'] = sHelper::change_time_format_2($slot_details[0]);
									// 					$slot_details['end_time'] = sHelper::change_time_format_2($slot_details[1]);
									// 					$slot_details['id'] = $package->id;
									// 					$slot_details['categories_id'] = $package->categories_id;
									// 					$slot_details['price'] = (string) $price;
									// 					//$slot_details['service_average_time'] = (string) $service_average_times;
									// 					$slot_details['hourly_price'] = (string) $hourly_rate;
									// 					$get_slot_time_in_min = sHelper::get_number_of_hour($slot_details[0], $slot_details[1]);
									// 					$total_time = $service_average_times + 0.33;
									// 					if ($get_slot_time_in_min < $total_time) {
									// 						$slot_details['available_status'] = 0;
									// 					} else {
									// 						$slot_details['available_status'] = 1;
									// 					}
									// 					$new_booked_arr[] = $slot_details;
									// 					//$packages[] = $slot_details;
									// 				}
									// 				//array_push($packages, $booked_arr);
									// 				$get_slot = collect($new_booked_arr);
									// 				$new_slot = $get_slot->sortBy('start_time');
									// 				$new_new_slot = array_merge($new_new_slot, $new_slot->values()->all());
									// 			}
									// 		}  
									// 		else{
									// 			$package->available_status = 1;
									// 		} 
									//    }
									//   else{
									// 	$package->available_status = 0;
									// 	} 
									// }
									// $filtered = $get_service_packages->whereNotIn('id', $booked_package_id_arr);
									// $new_slot = $filtered->all();
									// $get_service_details->service_average_time = (string) $service_average_times;
									// $get_service_details->package_list = array_merge($new_new_slot, $new_slot);
									// $get_service_details->min_price = null;
									// $get_service_details->max_price = null;
								}
							}
							return sHelper::get_respFormat(1, " ", $get_service_details, null);
						} else {
							return sHelper::get_respFormat(0, " Services Details not available !!! ", null, null);
						}
	
					} else {
						return sHelper::get_respFormat(0, " Please Enter the correct workshop user id ", null, null);
					}
	
				} else {
					return sHelper::get_respFormat(0, " Please Enter the category service id ", null, null);
				}
	
			} else {
				return sHelper::get_respFormat(0 ,"Please Enter the workshop user id", null, null);
			}
	
		}
	
	


	
	
	
	
	public function service_cost($service_id, $first_cost = NULL, $second_cost = NULL) {
		if (!empty($service_id)) {
			$get_packages_list = Services_package::get_services_packasge($service_id);
			echo "<pre>";
			print_r($get_packages_list);exit;
		}
	}
	
	public function getPartsNumber($car_version , $products_id){
	      $response = kromedaHelper::get_part_number($car_version , $products_id);
		  return sHelper::get_respFormat(1 , null , $response , null);  
	  }
	  
	  public function getPartsImage($id , $oem_id){
	      $response = kromedaHelper::get_products_image($id , $oem_id);
		  
		  return sHelper::get_respFormat(1 , null , null , $response);  
	  } 
	  
	  public function getOthersPartsImage($parts_number_id , $oem_id){
	      $response = kromedaHelper::get_other_products_image($parts_number_id , $oem_id);
		  echo "<pre>";
		  print_r($response);exit;
		  return sHelper::get_respFormat(1 , null , null , $response);  
	  } 
	  
	  
}
