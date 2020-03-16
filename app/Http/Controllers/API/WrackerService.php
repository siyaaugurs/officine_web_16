<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use sHelper;
use Validator;
use Auth;
use App\Http\Controllers\API\SpecialCondition;
use App\Workshop_user_day_timing;
use App\ServiceBooking;
use App\Gallery;
use App\library\orderHelper;
use App\Http\Controllers\Coupon;



class WrackerService extends Controller{

	public $main_category_id = 13;
	public $wracker_main_category_id = 13;
	

	public function get_all_wracker_workshop_services(Request $request){
		$main_category_id = 13;
		$remove_workshop_user_arr = $exit_workshop_user = $all_selected_workshop = $unique_user_arr  = [];
		$off_days_workshop_users = sHelper::get_off_users_on_date();
		/*.....get wrackerService.....*/
		//$get_wracker_services = WrackerServices::get_wracker_services_sos();
		/*....Get user around 10 meter range...*/
		$near_users = sHelper::get_near_by_users($request->latitude , $request->longitude);
		if($near_users->count() > 0){
			//$unique_user_arr = $near_users->unique('users_id')->pluck('users_id')->all();
			/*Get Wracker service service details*/
			$workshop_list =   \App\Users_category::get_workshop_user_list($main_category_id);
			$workshop_list = $workshop_list->whereNotIn('users_id' , $off_days_workshop_users);
			if($workshop_list->count() > 0){
				$subscribed_users = $workshop_list->pluck('users_id')->all();
				$all_workshop_users = $near_users->whereIn('users_id' , $subscribed_users)->all();
				if($all_workshop_users){
					$workshop_users  = [];
					foreach($all_workshop_users as $w_user){
						$workshop_time_slot = sHelper::workshop_time_slot(date('Y-m-d') , $w_user->users_id);
						if($workshop_time_slot->count() > 0){
							/*Check service insert or not */
							$service_detail = DB::table('workshop_wrecker_services')->where([['users_id' , '=' , $w_user->users_id] , ['status' , '=' , 'A'] , ['deleted_at' , '=' , NULL]])->get();
							/*End*/
							if($service_detail->count() > 0){
								$user_detail = \App\User::find($w_user->users_id);
								if($user_detail != NULL){
									$w_user->mobile_number =  $user_detail->mobile_number; 
								}
								$workshop_users[] = $w_user;
							}
						}
					}
					return sHelper::get_respformat(1, "", null, $workshop_users);
				}
				else{
					return sHelper::get_respformat(1, "No Workshop available in at your range !!!", null, null);
				}	
			}
			else{
				return sHelper::get_respformat(1, "No Workshop available in at your range !!!", null, null);
			}
			/*End*/             
		}
		else{
			return sHelper::get_respformat(1, "No Workshop available in at your range !!!", null, null);
		}
	}



	public function emergency_sos_service_booking(Request $request){
		$package_id  = $workshop_user_day_id = NULL;
		$discount = 0;
		if(Auth::check()){
		    $validator = \Validator::make($request->all(), [
			   'selected_date'=>'required','address_id'=>'required','price'=>'required','selected_car_id'=>'required','longitude'=>'required','latitude'=>'required',
			   'end_time'=>'required','start_time'=>'required','workshop_id'=>'required','package_id'=>'required'
			]);
			if($validator->fails()){
				return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
			}
		/*Service Details*/	
		$s_time = sHelper::change_time_formate($request->start_time);
		$e_time = sHelper::change_time_formate($request->end_time); 
		//$current_time_zones = sHelper::get_current_time_zones($request->ip());
		//date_default_timezone_set($current_time_zones);
		//$today_current_date_time = date('Y-m-d H:i');
		//$service_date_time = $request->selected_date." ".$request->start_time;
		//if($service_date_time < $today_current_date_time){
		//	return sHelper::get_respFormat(0 , "Please select correct booking date for booking , you not booked services in past time ." , null , null); 		
		//}
		$service = \App\WrackerServices::where([['id' , '=' , $request->service_id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])
		->first();
			if($service != NULL){
				/*Find workshop package*/
				$workshop_time_slot = Workshop_user_day_timing::where([['id' , '=' , $request->package_id]])->first();
				if($workshop_time_slot != NULL){
					if($s_time >= $workshop_time_slot->start_time && $s_time <= $workshop_time_slot->end_time){
						if($e_time >= $workshop_time_slot->start_time && $e_time <= $workshop_time_slot->end_time){
						   $get_busy_workshop = \App\ServiceBooking::get_busy_hour_for_sos_service($request , $workshop_time_slot , $request->service_id , 2);
						   if($get_busy_workshop->count() <= 0){
							   /*Match Special Condition script Start*/
								$special_condition_obj = new SpecialCondition;
								$match_special_condition = json_decode($special_condition_obj->match_wracker_service_for_emergency($request));
								$special_condition_arr = $special_condition_id = $after_discount_price = NULL;
								if($match_special_condition->status == 200){
									$special_condition_response = $match_special_condition->special_response;
									$discount = \sHelper::get_discount_price($request->price , $special_condition_response->discount_amount , $special_condition_response->discount_type);
									$special_condition_id = $special_condition_response->special_condition_id;
								}
								/*End*/
								/*manage order id*/
								$order_manage = \App\Products_order::save_order($request , $discount , $request->price);
								if($order_manage){ $request->order_id = $order_manage->id; }
							   /*End*/
							   /*calculate vat*/
							   $service_vat = orderHelper::calculate_vat_price($request->price);
							   $after_discount_price = ( $service_vat +  $request->price ) - $discount;
							   /*End*/
								$save_response = \App\ServiceBooking::create(['users_id'=>Auth::user()->id,'users_latitude'=>$request->latitude , 'users_longitude'=>$request->longitude,
																				'product_order_id'=>(int) $request->order_id,
																				'workshop_user_id'=>$request->workshop_id,
																				'workshop_address_id'=>$request->address_id,
																				'services_id'=>$request->service_id,
																				'special_condition_id'=>$special_condition_id,
																				'booking_date'=>$request->selected_date,
																				'workshop_user_days_id' =>$workshop_time_slot->workshop_user_days_id,
																				'workshop_user_day_timings_id'=>$workshop_time_slot->id,
																				'start_time'=>$request->start_time,
																				'end_time'=>$request->end_time,
																				'price'=>$request->price,
																				'service_vat'=>$service_vat,
																				'after_discount_price'=>$after_discount_price,
																				'discount'=>$discount,
																				'status'=>'P',
																				'type'=>6,
																				'wrecker_service_type'=>2
																			]);
								if($save_response){
									return sHelper::get_respFormat(1 , "Booking successfully !!! " , $save_response ,null ); 
								} else{
									return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
								}  
						   }
						   else{
							return sHelper::get_respFormat(0 , "This time is already busy !!! " , null ,null ); 
						   }
						   
						
							
						}
						else{
							return sHelper::get_respFormat(0 , "please check you end time !!! ." , null , null);  
						}
					}
					else{
						return sHelper::get_respFormat(0 , "please check you start time !!! ." , null , null);   
					}
					
				}	
					/*End*/ 
			}
			else{
				return sHelper::get_respFormat(0 , " Service is not corrcet !!! ." , null , null); 	
			}
		 }
		else{
			return sHelper::get_respFormat(0 , " Unauthenticate , please login first ." , null , null); 
		} 
	}
    
    

	
	/*next_thirty_days_for_sos*/
	public function next_thirty_days_for_sos(Request $request){
		$validator = Validator::make($request->all(), ['selected_date'=>'required' , 
		'latitude'=>'required' , 'longitude'=>'required' , 'service_id'=>'required' , 'selected_car_id'=>'required']);
		if ($validator->fails()) {
			return sHelper::get_respFormat(0, $validator->errors()->first(), NULL, NULL);
		}
		$service_booking_obj = new ServiceBooking;
		$service = \App\WrackerServices::where([['id' , '=' , $request->service_id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])
		->first();
			if($service != NULL){
				$selected_date = $request->selected_date;
				  for ($i = 0; $i < 30; $i++) {
					$request->selected_date = date('Y-m-d', strtotime($selected_date . ' + ' . $i . ' day'));
					$all_workshops_list = DB::table('workshop_wrecker_services as a')
													->join('users as b' , 'b.id' , '=' ,'a.users_id')
													->join('business_details as bd' , 'bd.users_id' , '=' ,'a.users_id')
													->select('a.*')
													->where([['wracker_services_id' , '=' , $request->service_id] , ['a.status' , '=' , 'A'] , ['b.deleted_at' , '=' , NULL] , ['users_status' , '=' , 'A'] , ['a.deleted_at' , '=' , NULL]])
													->get();
						$min_price = [];
						foreach($all_workshops_list as $workshop){
							/*Check time slots available or not in workshop*/
							$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop->users_id);
							if($time_slots->count() > 0){
								/*Get Address*/
								$address_list = \App\Address::user_location_with_distance($workshop->users_id , $request->latitude ,$request->longitude);
								if($address_list->count() > 0){
									$workshop_address_detail = $address_list[0];
									/*Workshop wracker service detail*/
										$workshop_wracker_service_details = DB::table('workshop_wrecker_service_details')->where([['wrecker_service_type' , '=' , 1] , ['workshop_wrecker_services_id' , '=' , $workshop->id]])->first();
										if($workshop_wracker_service_details != NULL){
											$distance_in_km = sHelper::calculate_distance($request->latitude ,$request->longitude , $workshop_address_detail->latitude , $workshop_address_detail->longitude , 'K');
											$workshop->distance = $distance_in_km;
											$service_average_times_price = sHelper::calculate_wrecker_service_price($service , $workshop_wracker_service_details , 1 , $request->selected_car_id , $distance_in_km);
											$min_price[] = (string) round($service_average_times_price['final_price'] , 2);
										}
									/*End*/
								}
								/*End*/
							}
						}
						$workshop_min_service_price = collect($min_price);
						$min_price = $workshop_min_service_price->min();
						if(empty($min_price)){
							$min_price = 0;
						}
						$main_service_prices[] = array('date' => $request->selected_date, 'price'=>(string) $min_price);
					}
					return sHelper::get_respFormat(1, " ", null, $main_service_prices
				);	
											
				}
				else{
					return sHelper::get_respFormat(0, "Service is not correct !!! ", null, null);
				}			
	}
	/*End*/
	
	
	
	public  function sos_service_booking(Request $request){
		$validator = \Validator::make($request->all(), [
			'package_id'=>'required' , 'start_time'=>'required' , 'end_time'=>'required', 'selected_date'=>'required','price'=>'required',
		]);
		if($validator->fails()){
			return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		}
		if(Auth::check()){
			/*Match Special Condition */
			$special_condition_obj = new SpecialCondition;
			$match_special_condition = json_decode($special_condition_obj->match_wracker_service_special_condition($request));
			$special_condition_arr = NULL;
			if($match_special_condition->status == 200){
				$special_condition_arr = $match_special_condition->special_response;
			}
			/*End*/
			$s_time = sHelper::change_time_formate($request->start_time);
			$e_time = sHelper::change_time_formate($request->end_time);
			$current_time_zones = sHelper::get_current_time_zones($request->ip());
			date_default_timezone_set($current_time_zones);
			$today_current_date_time = date('Y-m-d H:i');
			$service_date_time = $request->selected_date." ".$request->start_time;
			if($service_date_time < $today_current_date_time){
				return sHelper::get_respFormat(0 , "Please select correct booking date for booking , you not booked services in past time ." , null , null); 		
			}
			$get_package_details = \App\Workshop_user_day_timing::find($request->package_id);
			$service = \App\WrackerServices::where([['id' , '=' , $request->service_id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->first();
			if($service != NULL){
				if($get_package_details != NULL){
					/*Price and max Appointment*/
					$workshop_details = collect();
					$workshop_details->id = $get_package_details->users_id;
					$workshop_service_details = \App\WorkshopWreckerServiceDetails::where([['workshop_wrecker_services_id','=',$request->workshop_wrecker_id] , ['wrecker_service_type' , '=' , 1],  ['deleted_at','=',NULL]])->first();
					/*End*/
					if($s_time >= $get_package_details->start_time && $s_time <= $get_package_details->end_time){
						if($e_time >= $get_package_details->start_time && $e_time <= $get_package_details->end_time){
							if(empty($workshop_service_details->max_appointment)){ $max_appointment = 1; }
							else{ $max_appointment = $workshop_service_details->max_appointment; }
							if($get_package_details != NULL){
							/*Count booked Appointment*/
								$count_booked_appointment = \App\ServiceBooking::get_booked_sos_package($request->package_id, $request->selected_date , $request->service_id);
								if($count_booked_appointment->count() == $max_appointment){
									return sHelper::get_respFormat(0 , " All appointment of this package is completely booked !!! ." , null , null); 
								} 
							/*End*/
								  $get_busy_workshop = \App\ServiceBooking::get_busy_hour_for_sos_service($request , $get_package_details , $request->service_id);
									if($get_busy_workshop->count() < 1){
										/*Manage Order id*/
										if(empty($request->order_id)){
											$order_manage = \App\Products_order::save_order($request);
											if($order_manage){
												$request->order_id = $order_manage->id;
											}
										}
										/*End*/
										$booking_result = \App\ServiceBooking::save_sos_booking($request , $get_package_details , $special_condition_arr);
										if($booking_result){
											/*manage referal service*/
											/*End*/
											return sHelper::get_respFormat(1 , "Booking successfully !!! " , $booking_result ,null ); 
										} else {
											return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
										}  
									} else{
										return sHelper::get_respFormat(0 , "This time is already busy ." , null , null); 
									}	 
							} else{
								return sHelper::get_respFormat(0 , "Please select correct package id ." , null , null); 
							} 
						} else{
							return sHelper::get_respFormat(0 , "please check you end time !!! ." , null , null);  
						}
					} else{
						return sHelper::get_respFormat(0 , "please check you start time !!! ." , null , null);   
					}
				} else{
					return sHelper::get_respFormat(0 , " package is not defined !!! ." , null , null);    
				}
			}
			else{
				return sHelper::get_respFormat(0 , "Service is not available in our database !!!." , null , null); 
			}
		} else{
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
		}
	} 


	public function get_wrackerservices(Request $request){
		if(!empty($request->workshop_id)){
			$workshop_detail = \App\User::find($request->workshop_id);
			if($workshop_detail != NULL){
				$workhop_wrecker_service = DB::table('workshop_wrecker_services as a')
												->join('wracker_services as b' , 'b.id' , '=' ,'a.wracker_services_id')
												->select('b.*' , 'a.id as workshop_wrecker_id' , 'a.users_id  as workshop_id')
												->where([['a.users_id','=',$request->workshop_id] , ['b.deleted_at' , '=' , NULL] , 
												['b.status' , '=' , 'A'] , ['a.status' , '=' , 'A'] , ['a.deleted_at' , '=' , NULL]])
												->get();
				if($workhop_wrecker_service->count() > 0){
					foreach($workhop_wrecker_service as $get_wracker){
						$get_wracker->total_time_arrives = $get_wracker->hourly_cost =  $get_wracker->cost_per_km =  
						$get_wracker->call_cost =  $get_wracker->mobile_number  =  $get_wracker->service_average_price = $get_wracker->selected_car_id = $get_wracker->address_id = null;
						$workshop_service_detail = \App\WorkshopWreckerServiceDetails::where([['workshop_wrecker_services_id' , '=' , $get_wracker->workshop_wrecker_id] , ['wrecker_service_type' , '=' , 2]])->first();
						if($workshop_service_detail != NULL){
							$get_wracker->mobile_number = $workshop_detail->mobile_number;
							$get_wracker->total_time_arrives = $workshop_service_detail->total_time_arrives;
							$get_wracker->hourly_cost =  $workshop_service_detail->hourly_cost;
							$get_wracker->cost_per_km =  $workshop_service_detail->cost_per_km;
							$get_wracker->call_cost =  $workshop_service_detail->call_cost;
							$get_wracker->selected_car_id = $request->selected_car_id;
							$get_wracker->address_id =  $request->address_id;
						}
						/*End*/
						$get_wracker->images = \App\WrackerServices::get_wracker_services_image($get_wracker->id);
					}
					return sHelper::get_respFormat(1, null, null, $workhop_wrecker_service);
				}	
				else{
					return sHelper::get_respFormat(1, "Service not available !!!" , null, null);
				}									     
			}
			else{
				return sHelper::get_respFormat(0, "Workshop not available !!!. ", null, null);
			}
		  }else{
			$workhop_wrecker_service = DB::table('wracker_services as ws')->where([['ws.deleted_at' ,'=' , NULL] , ['status' , '=' , 'A']])->get();
			$workhop_wrecker_service->map(function($service){
				$service->total_time_arrives = $service->hourly_cost =  $service->cost_per_km = $service->call_cost = $service->workshop_wrecker_id = $service->workshop_id = $service->mobile_number = null;
				return $service;
			}); 
			if($workhop_wrecker_service->count() > 0){
				foreach($workhop_wrecker_service as $get_wracker){
					$get_wracker->images = \App\WrackerServices::get_wracker_services_image($get_wracker->id);
				}
				return sHelper::get_respFormat(1, null, null, $workhop_wrecker_service);
			}
		  }  	
	}

  
	
		
	/*End */

/*SOS workshop list for service by emergency*/
public function sos_workshop_list_for_emergency(Request $request){
	$coupon_obj = new Coupon;
	$request->main_category_id = $this->main_category_id;
	$service_booking_obj = new ServiceBooking;
	$valid_workshop = [];
	$validator = \Validator::make($request->all(), [
		  'selected_car_id'=>'required','service_id'=>'required', 'latitude'=>'required', 'longitude'=>'required' 
	]);
	if($validator->fails()){
		  return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
	  }
	if(empty($request->selected_date)){ $selected_date = date('Y-m-d'); }	
	else{ $selected_date = $request->selected_date; }
	if(empty($request->start_time)){ $start_time = date("H:i"); }else{  $start_time =  $request->start_time; }
	$selected_days_id = \sHelper::get_week_days_id($selected_date);
	$service = \App\WrackerServices::find($request->service_id);
	$off_workhops_on_that_day = sHelper::get_off_users_on_date($request->selected_date);
	$near_users = sHelper::get_near_by_users($request->latitude , $request->longitude);
	if($near_users->count() > 0){
		 $all_workshops_list = DB::table('workshop_wrecker_services as a')
								   ->join('workshop_user_days as wd' , function($join) use ($selected_days_id){
									   $join->on('a.users_id', '=', 'wd.users_id')
									  ->where([['wd.common_weekly_days_id' , '=' , $selected_days_id] , ['wd.deleted_at' , '=' , NULL]]);
								   })
								   ->join('users as u' , function($join){
								    	$join->on('a.users_id','=' , 'u.id')->where([['u.deleted_at' , '=' , NULL] , ['u.users_status' , '=' , 'A']]);
								   })
								   ->join('business_details as b' , function($join){
									$join->on('u.id','=' , 'b.users_id');
							       })
								   ->select('a.*','u.id','u.f_name', 'u.l_name', 'u.profile_image', 'u.mobile_number', 'u.company_name' ,'b.owner_name', 'b.business_name', 'b.registered_office', 'b.about_business')
								  ->where([['a.wracker_services_id','=',$request->service_id]])
								  ->get();
		 if($near_users->count() > 0){
			 $user_arr = $near_users->unique('users_id')->pluck('users_id')->all();
		   }
		 if($all_workshops_list->count() > 0){
			$all_workshops_list = $all_workshops_list->whereIn('users_id' , $user_arr);
			//$all_workshops_list = \App\User::users_details($all_workshops_list);
			
			$new_workshop_service_list = [];
			if($all_workshops_list->count() > 0){
				foreach($all_workshops_list as $workshop){
					  $workshop->users_id = $workshop->id;
					  $workshop->services_price = (string) 0;
					  $workshop->average_time = $workshop->service_images = $workshop->package_list = $workshop->workshop_address_lat_long = null;
					  $addresses = $near_users->where('users_id')->sortBy('distance');
					  if($addresses->count() > 0){
						  $workshop_address_detail = \App\Address::get_primary_address($workshop->users_id);
						  if($workshop_address_detail != NULL){
							  $workshop->address_id = $workshop_address_detail->id;
							  $workshop->profile_image_url = NULL;
								  if(!empty($workshop->profile_image)){
										$workshop->profile_image_url = url("storage/profile_image/$workshop->profile_image");
								  }	
								  /*manage Service price*/
								  $workshop_wracker_service_details = DB::table('workshop_wrecker_service_details')->where([['wrecker_service_type' , '=' , 2] , ['workshop_wrecker_services_id' , '=' , $workshop->id]])->first();
								  /*End*/ 
								  if($workshop_wracker_service_details != NULL){
									  $distance_in_km = sHelper::calculate_distance($request->latitude ,$request->longitude , $workshop_address_detail->latitude , $workshop_address_detail->longitude , 'K');
									  $workshop->workshop_address_lat_long = ['lattitude'=>$workshop_address_detail->latitude , 'longtitude'=> $workshop_address_detail->latitude, 'distance_in_km'=>round($workshop_address_detail->distance , 2)]; 
									  $service_average_times_price = sHelper::calculate_wrecker_service_price($service , $workshop_wracker_service_details , 2 , $request->selected_car_id , $distance_in_km);
									  if(is_array($service_average_times_price)){
										  $workshop->services_price = (string) round($service_average_times_price['final_price'] , 2);
										  $workshop->average_time = (string) $service_average_times_price['time']; 
										  $workshop->average_time_in_min = (string) $service_average_times_price['time_in_min'];
									  }
									  //get single coupon with 3 time of user's
									  $workshop->coupon_list =  $coupon_obj->find_workshop_coupon($workshop->users_id , $this->wracker_main_category_id  , $request->selected_date , $request->service_id);
												
									  /*Check time slots for */
									  $time_slots = sHelper::workshop_time_slot($selected_date , $workshop->users_id);
									  if($time_slots->count() > 0){
										  $request->workshop_id =  $workshop->users_id;
										  $workshop->available_status =  sHelper::check_time_slot_for_emergency($time_slots ,  $service_average_times_price['time'] , $request);
										  /*service images*/
										  $workshop->service_images = Gallery::get_wrecker_images($request->service_id);
										  /*End*/
										  $workshop->workshop_gallery = Gallery::get_all_images($workshop->users_id);
										  $workshop->wish_list = 0;
										  if(!empty($request->user_id)){
											  $user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_workshop($workshop->users_id , $request->user_id);
											  if($user_wishlist_status == 1){
												  $workshop->wish_list = 1;
											  } else {
												  $workshop->wish_list = 0;
											  }	
										  }
										  $workshop = sHelper::manage_workshop_feedback_in_api($workshop , $request->users_id);  
										  $new_workshop_service_list[] = $workshop;
									}
								} 
						  }
							  /*End*/
						}
				   }
				   $all_workshops_list = collect($new_workshop_service_list);
				  // print_r($all_workshops_list); die;
				   if($all_workshops_list->count() > 0){
					   /*For min price and max price*/
					   $min_price = $all_workshops_list->min('services_price');
					   $max_price = $all_workshops_list->max('services_price');
					   $all_workshops_list->map(function($workshop) use ($min_price , $max_price){
						   $workshop->min_price = $min_price;
						   $workshop->max_price = $max_price;
						   return $workshop;
					   }); 	
					   /*Filtered by rating */
					   if (!empty($request->rating)) {
						   $rating_arr = explode(',', $request->rating);
						   $all_workshops_list =  $all_workshops_list->whereBetween('rating_star', $rating_arr);
					   }
					   else{
					       $all_workshops_list->sortByDesc('rating_star'); 
					   }  
					   /*End*/
					   if (!empty($request->price_range)) {
						   $price_arr = explode(',', $request->price_range);
						   $all_workshops_list = $all_workshops_list->whereBetween('services_price', $price_arr);
					   }
					   if (!empty($request->price_level)) {
						   if ($request->price_level == 1) {
							   $all_workshops_list = $all_workshops_list->sortBy('services_price')->values();
						   }
						   else if ($request->price_level == 2) {
						   $all_workshops_list = $all_workshops_list->sortByDesc('services_price')->values();
						   }
					   }
					   else{
					     $all_workshops_list = $all_workshops_list->sortBy('services_price')->values();
					   }	
					   return sHelper::get_respFormat(1, null, null, $all_workshops_list);    

				   }
				   else{
					return sHelper::get_respFormat(0, "No Workshop available !!!", null, null);    
				   }
						/*End*/
			  }
			 else{
				return sHelper::get_respFormat(0, "No Workshop available !!!", null, null); 
			 } 
		   } 
		   else{
			return sHelper::get_respFormat(0, "No Workshop available !!!", null, null); 
		 } 
	  }
	else{
	   return sHelper::get_respFormat(0, "No Workshop available !!!", null, null); 
	} 	
  }
  /*End*/

	/*SOS workshop packages for emergency*/
	public function sos_workshop_packages_for_emergency(Request $request){
		$special_condition_obj = new SpecialCondition;
		$request->main_category_id = $this->main_category_id;
		$service_booking_obj = new ServiceBooking;
		$opening_slot = $not_applicable_hour = $new_booked_list = [];
		$validator = \Validator::make($request->all(), [
			  'selected_car_id'=>'required','service_id'=>'required', 'latitude'=>'required', 'longitude'=>'required','address_id'=>'required' 
		]);
		if($validator->fails()){
			  return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		}
		if(empty($request->selected_date)){ $selected_date = date('Y-m-d'); }	
		else{
			 $selected_date = $request->selected_date; 
		}
		if(empty($request->start_time)){ $start_time = date("H:i"); }else{  $start_time =  $request->start_time; }
		/*Check Workshop off or not that day*/
		  $check_workshop_off_status = \App\Workshop_leave_days::whereDate('off_date' , $selected_date)->where([['users_id' , '=' , $request->workshop_id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->first();
		/*End*/
		if($check_workshop_off_status == NULL){
			$selected_days_id = \sHelper::get_week_days_id($selected_date);
			$service = \App\WrackerServices::find($request->service_id);
			$workshop = \App\User::get_workshop_details($request->workshop_id);
			if($workshop != NULL){
						$workshop->users_id = $workshop->id;
						$workshop->services_price = (string) 0;
						$workshop->average_time = $workshop->service_images = $workshop->package_list = $workshop->min_price = $workshop->max_price = null;
						$workshop_address_detail = DB::table('addresses')->where([['id' , '=' , $request->address_id]])->first(); 
						$workshop->profile_image_url = NULL;
						if(!empty($workshop->profile_image)){
							$workshop->profile_image_url = url("storage/profile_image/$workshop->profile_image");
						}	
						/*manage Service price for sos*/
						$workshop_wracker_service_details = DB::table('workshop_wrecker_service_details')->where([['wrecker_service_type' , '=' , 2] , ['workshop_wrecker_services_id' , '=' , $workshop->id]])->first();
						if($workshop_wracker_service_details != NULL){
							/*End*/  
							$distance_in_km = sHelper::calculate_distance($request->latitude ,$request->longitude , $workshop_address_detail->latitude , $workshop_address_detail->longitude , 'K');
							$workshop->distance_in_km = round($distance_in_km , 2);
							$service_average_times_price = sHelper::calculate_wrecker_service_price($service , $workshop_wracker_service_details , 2 , $request->selected_car_id , $distance_in_km);
							if(is_array($service_average_times_price)){
								$workshop->services_price = (string) round($service_average_times_price['final_price'] , 2);
								$workshop->average_time = $service_average_times_price['time']; 
							}
							/*add rating*/
							$workshop = sHelper::manage_workshop_feedback_in_api($workshop , $workshop->users_id);

							/*Check time slots for */
							$time_slots = sHelper::workshop_time_slot($selected_date , $workshop->users_id);
							if($time_slots->count() > 0){
								/*manage time slot*/
								 foreach($time_slots as $slot){
									 $opening_slot[] = [$slot->start_time , $slot->end_time];
								 }
								/*End*/
								$special_con_flag = 0;
								$special_condition_status = $special_condition_obj->do_not_perform_operation_for_emergency($request , $time_slots , $service_average_times_price['time']);
								$decode_response = json_decode($special_condition_status);
								$not_applicable_arr = $not_applicable_hour_arr = [];
								if($decode_response->status == 200){
									$special_con_flag = 1;
									$not_applicable_hour = $decode_response->response;
									if(count($not_applicable_hour) > 0){
										foreach($not_applicable_hour as $n_applicable){
											$not_applicable_hour_arr[] = [$n_applicable[0], $n_applicable[1]];
											$not_applicable[0] = sHelper::change_time_format_2($n_applicable[0]);
											$not_applicable[1] = sHelper::change_time_format_2($n_applicable[1]);
											$not_applicable['start_time'] = sHelper::change_time_format_2($n_applicable[0]);
											$not_applicable['end_time'] = sHelper::change_time_format_2($n_applicable[1]);
											$not_applicable['id'] = null;
											$not_applicable['categories_id'] = sHelper::change_time_format_2($n_applicable[1]);
											$not_applicable['price'] = (string) $service_average_times_price['final_price'];
											$not_applicable['hourly_price'] = (string) $service_average_times_price['final_price'];
											$not_applicable['available_status'] = 2;
											$not_applicable_arr[] =  $not_applicable; 
										}
									}
								}
								
								$not_applicable_collection = collect($not_applicable_arr);
								/*Manage booking slot*/
								$package_list = [];
								foreach($time_slots as $slot){
									$opening_slot = [];
									$opening_slot[] = [$slot->start_time , $slot->end_time];
									/*manage not applicable slot start*/
									$not_applicable_slot = $not_applicable_collection->where('start_time');

									/* echo "<pre>";
									print_r($not_applicable_slot);exit; */
									/*End*/
									$new_generate_applicable_slot = sHelper::get_time_slot($not_applicable_hour_arr, $opening_slot);
									  /*Get Booked time slots*/
									 // $booked_list = \App\ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $slot->id] , ['workshop_user_id','=',(int) $request->workshop_id] ,
										//										['type' , '=' , 6] ,['wrecker_service_type' , '=' ,2] ,['services_id' , '=' ,$request->service_id]])
										//								  ->whereDate('booking_date' , $request->selected_date)->get();
									$query = \App\ServiceBooking::where([['workshop_user_id' ,'=' ,(int)$request->workshop_id] , ['wrecker_service_type' , '=' ,2] , ['services_id' ,'=' , $request->service_id] ,['type' ,'=' , 6] ,['status' ,'=' ,'C']]);
										$query->whereDate('booking_date', $request->selected_date);
										if(!empty($request->user_id)){
											$query->orWhere([['users_id' , '=' , $request->user_id] ,['type' , '=' , 6]])->WhereIn('status' ,['CA' ,'P'])->whereDate('booking_date' , $request->selected_date);
										}
									 	$booked_list =	$query->get();
										$new_booked_arr = $booked_slot = [];
										if($booked_list->count() > 0){
											foreach($booked_list as $booked){
												$booked_slot[] =  [$booked->start_time, $booked->end_time];
												$s_time[0] = sHelper::change_time_format_2($booked->start_time);
												$s_time[1] = sHelper::change_time_format_2($booked->end_time);
												$s_time['start_time'] = sHelper::change_time_format_2($booked->start_time);
												$s_time['end_time'] = sHelper::change_time_format_2($booked->end_time);
												$s_time['id'] = $slot->id;
												$s_time['categories_id'] = $request->service_id;
												$s_time['price'] = (string) $service_average_times_price['final_price'];
												$s_time['hourly_price'] = (string) $service_average_times_price['final_price'];
												$s_time['available_status'] = 0;
												$new_booked_arr[] = $s_time;	
											}
										}
										/*End*/
										$new_generate_slot = sHelper::get_time_slot($booked_slot, $opening_slot);
										 /*ec ho "<pre>";
										 print_r($new_generate_slot);exit;  */
										
										/*manage new generate slots*/
										 foreach($new_generate_slot as $new_slot){
											 $new_slots_available[0] = sHelper::change_time_format_2($new_slot[0]);
											 $new_slots_available[1] = sHelper::change_time_format_2($new_slot[1]);
											 $new_slots_available['start_time'] = sHelper::change_time_format_2($new_slot[0]);
											 $new_slots_available['end_time'] = sHelper::change_time_format_2($new_slot[1]);
											 $new_slots_available['id'] = $slot->id;
											 $new_slots_available['categories_id'] = $request->service_id;
											 $new_slots_available['price'] = (string) $service_average_times_price['final_price'];
											 $new_slots_available['hourly_price'] = (string) $service_average_times_price['final_price'];
											 $get_slot_time_in_hour = round( sHelper::get_number_of_hour($new_slot[0], $new_slot[1]) , 2);
												 if ($get_slot_time_in_hour < $service_average_times_price['time']) {
													$new_slots_available['available_status'] = 0;
												} else {
													$new_slots_available['available_status'] = 1;
												} 
											 $new_booked_arr[] = $new_slots_available;					
										 }					
										/*End*/
										$get_slot = collect($new_booked_arr);
										$new_slot = $get_slot->sortBy('start_time');
										$package_list = array_merge($package_list, $new_slot->values()->all());
								}
								
								$workshop->package_list = $package_list;
								/*End*/
								/*Manage remaining slots*/
								//$available_slots = sHelper::get_time_slot($new_booked_list , $new_generate_applicable_slot);
								/*End*/  
								/*manage Coupon list*/
								//$workshop->coupon_list = sHelper::get_coupon_list($workshop->users_id , 6 , $this->main_category_id , $request->service_id);
								/*End*/	
								$workshop->workshop_gallery = Gallery::get_all_images($workshop->users_id);
								return sHelper::get_respFormat(1, null, $workshop, null);		 
							}
							else{
								return sHelper::get_respFormat(0, "Time slot not available for that day ", null, null);	
							}
						}
						else{
							return sHelper::get_respFormat(0, "Service details not available !!! ", null, null);	
						}
						/*End*/
				/*End*/
			} 
			else{
				return sHelper::get_respFormat(0, "No Workshop not correct !!!", null, null); 
			}
		}
		else{
			return sHelper::get_respFormat(0, "No Workshop available !!!", null, null); 
		} 	
	}
		

	/*End*/


  

	/*SOS workshop list for the case of service by appointment*/
	public function sos_workshop_list_for_appontment(Request $request){
		$service_booking_obj = new ServiceBooking;
		$coupon_obj = new Coupon;
		$validator = \Validator::make($request->all(), [
			'selected_date'=>'required' , 'selected_car_id'=>'required','service_id'=>'required', 'latitude'=>'required', 'longitude'=>'required' 
		]);
		if($validator->fails()){
			return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		}
		$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
		$service = \App\WrackerServices::find($request->service_id);
		if($service != NULL){
			$off_workhops_on_that_day = sHelper::get_off_users_on_date($request->selected_date);
			$all_workshops_list = DB::table('workshop_wrecker_services as a')
									 ->join('users as b' , 'b.id' , '=' ,'a.users_id')
									 ->join('business_details as bd' , 'bd.users_id' , '=' ,'a.users_id')
									 ->select('a.*' , 'b.f_name' , 'b.l_name' , 'b.company_name' , 'b.user_name' , 'b.mobile_number' , 'b.profile_image' , 'b.users_status', 'bd.business_name', 'bd.registered_office', 'bd.about_business')
									 ->where([['wracker_services_id' , '=' , $request->service_id] , ['a.status' , '=' , 'A'] , ['b.deleted_at' , '=' , NULL] , ['users_status' , '=' , 'A'] , ['a.deleted_at' , '=' , NULL]])
									 ->get();
			if($all_workshops_list->count() > 0){
				$remove_workshop_users = $selected_workshop_list = [];
				foreach($all_workshops_list as $workshop){
					/*Check time slots available or not in workshop*/
					$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop->users_id);
					/*End*/
					if($time_slots->count() > 0){
						$workshop->services_price = $workshop->distance =  $workshop->workshop_address_lat_long = NULL;
						$workshop->coupon_list = [];
						$workshop->service_id = $request->service_id;
						$workshop->main_category_id = $this->main_category_id;
						$request->main_category_id = $this->main_category_id;
						$workshop->selected_car_id = $request->selected_car_id;
						$workshop->selected_date = $request->selected_date;
						$workshop->profile_image_url = NULL;
						if(!empty($workshop->profile_image)){
						   $workshop->profile_image_url = url("storage/profile_image/$workshop->profile_image");
						}						
						/*manage workshop feedback*/
						$workshop = sHelper::manage_workshop_feedback_in_api($workshop , $workshop->users_id);  
						/*End*/
						$workshop->services_price = (string) 0;
						/*Get Address*/
						$address_list = \App\Address::user_location_with_distance($workshop->users_id , $request->latitude ,$request->longitude);
						if($address_list->count() > 0){
							$workshop_address_detail = $address_list[0];
							/*Workshop wracker service detail*/
							$workshop_wracker_service_details = DB::table('workshop_wrecker_service_details')->where([['wrecker_service_type' , '=' , 1] , ['workshop_wrecker_services_id' , '=' , $workshop->id]])->first();
							if($workshop_wracker_service_details != NULL){
								$workshop->workshop_wrecker_id = $workshop_wracker_service_details->id;
								$distance_in_km = sHelper::calculate_distance($request->latitude ,$request->longitude , $workshop_address_detail->latitude , $workshop_address_detail->longitude , 'K');
								$workshop->address_id = $workshop_address_detail->id;
								$workshop->distance = $distance_in_km;
								$workshop->workshop_address_lat_long = ['lattitude'=>$workshop_address_detail->latitude , 'longtitude'=> $workshop_address_detail->latitude, 'distance_in_km'=>round($workshop_address_detail->distance , 2)];
								$service_average_times_price = sHelper::calculate_wrecker_service_price($service , $workshop_wracker_service_details , 1 , $request->selected_car_id , $distance_in_km);
								$workshop->services_price = (string) round($service_average_times_price['final_price'] , 2);
								//$workshop->services_price = $service_average_times_price;
								/*manage coupon list*/
									$workshop->coupon_list =  $coupon_obj->find_workshop_coupon($workshop->users_id , $this->wracker_main_category_id  , $request->selected_date , $request->service_id);
								/*End*/
								$request->workshop_id = $workshop->users_id;
								//print_r($service_average_times_price['time']); die;
								$workshop->available_status =  sHelper::check_time_slot_sos($time_slots , $workshop_wracker_service_details , $request , $service_average_times_price['time'] , $service_booking_obj->wracker_service_type[1]);
								//print_r($workshop->available_status); die;
								$workshop->service_images = $workshop->package_list = NULL;
								$selected_workshop_list[] = $workshop;
								/*manage wish list*/
								$workshop->wish_list = 0;
								if(!empty($request->user_id)){
									$user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_workshop($workshop->users_id , $request->user_id);
									if($user_wishlist_status == 1){
										$workshop->wish_list = 1;
									} else {
										$workshop->wish_list = 0;
									}	
								}
								/*End*/
							}
							/*End*/
						}
					}
				}
				$all_workshops_list = collect($selected_workshop_list);
				$min_price = $all_workshops_list->min('services_price');
				$max_price = $all_workshops_list->max('services_price');
			    $all_workshops_list->map(function($workshop) use ($min_price , $max_price){
						$workshop->min_price = $min_price;
						$workshop->max_price = $max_price;
						return $workshop;
				});
				/*Filtered by rating */
				if (!empty($request->rating)) {
					$rating_arr = explode(',', $request->rating);
					$all_workshops_list =  $all_workshops_list->whereBetween('rating_star', $rating_arr);
				}
				else{
				   $all_workshops_list->sortByDesc('rating_star'); 
				}  
				/*End*/
				if (!empty($request->price_range)) {
					$price_arr = explode(',', $request->price_range);
					$all_workshops_list = $all_workshops_list->whereBetween('services_price', $price_arr);
				}
				if (!empty($request->price_level)) {
					if ($request->price_level == 1) {
						$all_workshops_list = $all_workshops_list->sortBy('services_price')->values();
					}
					else if ($request->price_level == 2) {
					 $all_workshops_list = $all_workshops_list->sortByDesc('services_price')->values();
					}
				  }
				else{
				   $all_workshops_list = $all_workshops_list->sortBy('services_price')->values();
				  }
				return sHelper::get_respFormat(1, null, null, $all_workshops_list);
			}	
			else{
				return sHelper::get_respFormat(0, "No Workshop available !!!", null, null);
			}					 
		}
		else{
			return sHelper::get_respFormat(0, "Service is not correct !!!", null, null);
		}
    }
	/*End*/


	/*SOS workshop packages for service by apoointment */
	 /*sos package list */
	 public function sos_workshop_packages(Request $request){
		$special_condition_obj = new SpecialCondition;
		$validator = Validator::make($request->all(), ['workshop_id'=>'required' ,'selected_date'=>'required' , 
		'latitude'=>'required' , 'longitude'=>'required' , 'service_id'=>'required' , 'selected_car_id'=>'required' , 'address_id'=>'required' , 'workshop_wrecker_id'=>'required']);
		if ($validator->fails()) {
			return sHelper::get_respFormat(0, $validator->errors()->first(), NULL, NULL);
		}
		$max_appointment = $hourly_rate = 0;
		$service = \App\WrackerServices::where([['id' , '=' , $request->service_id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])
		->first();
		if($service != NULL){
			$workshop = \App\User::get_workshop_details($request->workshop_id);
			if($workshop != NULL){
				$workshop->profile_image_url = 	$workshop->hourly_rate = $workshop->max_appointment = $workshop->services_price = $workshop->main_category_id = $workshop->category_id = $workshop->min_price = 	$workshop->max_price = 
				$workshop->rating_star = $workshop->rating_count = $workshop->service_average_time = null;			
				$workshop->address_id = $request->address_id; 
				$workshop->workshop_wrecker_id = $request->workshop_wrecker_id;
				$workshop->workshop_id = $request->workshop_id; 
				$workshop->latitude = $request->latitude; 
				$workshop->longitude = $request->longitude; 
				$workshop->service_id = $request->service_id; 
				$workshop->selected_car_id = $request->selected_car_id; 
				$workshop->package_list   = $workshop->service_images =  []; 
				/*Workshop rating */
				$workshop = sHelper::manage_workshop_feedback_in_api($workshop , $request->workshop_id);  
				/*End*/	
				$images = \App\WrackerServices::get_wracker_services_image($request->service_id);
					if($images->count() > 0){
						$workshop->service_images = $images;
					}
		 			if(!empty($workshop->profile_image)){
						$workshop->profile_image_url = url("storage/profile_image/$workshop->profile_image");
					}
					$workshop_wracker_service_details = \App\WorkshopWreckerServiceDetails::where([['workshop_wrecker_services_id','=',
					$request->workshop_wrecker_id] , ['wrecker_service_type' , '=' , 1],  ['deleted_at','=',NULL]])->first();
					if($workshop_wracker_service_details != NULL){ 
						$max_appointment = $workshop_wracker_service_details->max_appointment;
						/*Workshop address*/
						$workshop_address_detail = DB::table('addresses')->where([['id' , '=' , $request->address_id]])->first();
						/*End*/
						//$service_average_times
						$distance_in_km = sHelper::calculate_distance($request->latitude ,$request->longitude , $workshop_address_detail->latitude , $workshop_address_detail->longitude , 'K');
						$service_average_times_price = sHelper::calculate_wrecker_service_price($service , $workshop_wracker_service_details , 1 , $request->selected_car_id , $distance_in_km);
						
						$workshop->services_price = (string) $service_average_times_price['final_price'];
						$workshop->main_category_id = 13;
						$workshop->category_id = $request->service_id;
						$workshop->hourly_rate = $workshop_wracker_service_details->hourly_cost;
						/*Workshop gallery image manage*/
						$workshop->workshop_gallery = \App\Gallery::get_all_images($request->workshop_id);
                        /*End*/
						$time_slot = sHelper::workshop_time_slot($request->selected_date , $request->workshop_id);
						if($time_slot->count() > 0){
							/*Manage Time slot manage*/
							$new_new_slot = $booked_package_id_arr = [];
							$package_list =  [];
							foreach($time_slot as $slot){
								$new_booked_list = $new_booked_arr = $opening_slot = $not_applicable_hour  = $not_applicable_hour_slot = $new_booked_list_slot =  [];
								$opening_slot[] = array($slot->start_time, $slot->end_time);
								/*Check do not perform operation */
								   $special_condition_status = $special_condition_obj->do_not_perform_operation_sp_cond($request , $request->workshop_id , $slot);
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
											  $not_applicable['price'] = (string) $service_average_times_price['final_price'];
											  $not_applicable['hourly_price'] = (string) $service_average_times_price['final_price'];
											  $not_applicable['available_status'] = 2;
											  $new_booked_arr[] =  $not_applicable; 
										  }
									   }
								   }
								/*End*/
								$new_generate_applicable_slot = sHelper::get_time_slot($not_applicable_hour_slot , $opening_slot);
								/*manage booking slot*/
								$query = \App\ServiceBooking::where([['workshop_user_id' ,'=' ,(int)$request->workshop_id] , ['services_id' ,'=' , $request->service_id] ,['type' ,'=' , 6] ,['status' ,'=' , 'C']]);
								$query->whereDate('booking_date', $request->selected_date);
                                if(!empty($request->user_id)){
                                    $query->orWhere([['users_id' , '=' , $request->user_id],['type' , '=' , 6]])->whereIn('status',['CA' ,'P'])->wheredate('booking_date' , $request->selected_date);
                                }
									        $booked_list =	$query->get();
											if ($booked_list->count() > 0) {
											foreach($booked_list as $booked) {
												$new_booked_list_slot[] = [$booked->start_time, $booked->end_time];
												$booked_arr[0] = sHelper::change_time_format_2($booked->start_time);
												$booked_arr[1] = sHelper::change_time_format_2($booked->end_time);
												$booked_arr['start_time'] = sHelper::change_time_format_2($booked->start_time);
												$booked_arr['end_time'] = sHelper::change_time_format_2($booked->end_time);
												$booked_arr['id'] = $booked->workshop_user_day_timings_id;
												$booked_arr['categories_id'] = $booked->services_id;
												$booked_arr['price'] = (string) $service_average_times_price['final_price'];
												$booked_arr['hourly_price'] = (string) $service_average_times_price['time'];
												$booked_arr['available_status'] = 0;
												//$booked_arr['service_average_time'] = (string) $service_average_times;
												$new_booked_arr[] =  $booked_arr;
											}
										} 	
								/*End*/
								//$new_generate_slot = sHelper::get_time_slot($new_booked_list , $opening_slot);
								$new_generate_slot = sHelper::get_time_slot($new_booked_list_slot, $new_generate_applicable_slot);
								//print_r($service_average_times_price['time']); die;
								if (count($new_generate_slot) > 0) {
									foreach ($new_generate_slot as $slot_details) {
										$slot_details['start_time'] = sHelper::change_time_format_2($slot_details[0]);
										$slot_details['end_time'] = sHelper::change_time_format_2($slot_details[1]);
										$slot_details['id'] = $slot->id;
										$slot_details['categories_id'] = $slot->categories_id;
										$slot_details['price'] = (string) $service_average_times_price['final_price'];
										$slot_details['hourly_price'] = (string) $hourly_rate;
										$get_slot_time_in_hour = sHelper::get_number_of_hour($slot_details[0], $slot_details[1]);
										if ($get_slot_time_in_hour < $service_average_times_price['time']) {
											$slot_details['available_status'] = 0;
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
							/*End*/
							$workshop->min_price = null;
							$workshop->max_price = null;
							/*End*/
						}
						$workshop->package_list = $new_package_list_2;
					}
					return sHelper::get_respFormat(1, null, $workshop, null);
		   }
		   else{
			return sHelper::get_respFormat(0, "Workshop is not correct !!! ", null, null);
		   }
			} else {
				return sHelper::get_respFormat(0, "Service is not correct !!! ", null, null);
			}
       }		
	/*End */
}
