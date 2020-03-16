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
use App\Library\kromedaDataHelper;
use Auth;
use App\ServiceBooking;
use kromedaSMRhelper;
use App\Services;
use App\WorkshopServicesPayments;
use App\Library\apiHelper;

class CarwashController extends Controller{
	
	
	public function get_workshop(Request $request) {
		$services_id = $request->category_id;
        if(empty($request->car_size) ){
			return sHelper::get_respFormat(0, " Car size is required !!!. ", null, null);
		}
		/*Selected workshop those are off this selected days and dates*/
		$off_days_workshop_users = [];
		$minPrice = 0;
	    $maxPrice = 0;
		if (!empty($request->selected_date)) {
			$off_selected_date = \App\Workshop_leave_days::get_valid_workshop($request);
			$users_arr = $off_selected_date->pluck('users_id');
			$off_days_workshop_users = $users_arr->all();
		} else {
			return sHelper::get_respFormat(0, " Please select one date  !!!. ", null, null);	
		}
		$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
		if(empty($request->type)){
			return sHelper::get_respFormat(0, " Type is required !!!. ", null, null);
		}
		else{
			/*Type = 1 for car wash  only*/
			if ($request->type == 1) {
			    $category_details = DB::table('categories')->where([['id' , '=' , $services_id] , ['category_type' , '=' , 1]])->first();
			    if($category_details == NULL){
				return sHelper::get_respFormat(0, "Select correct category  !!!", null, null); 
				  }
				if(empty($request->car_size)){
				return sHelper::get_respFormat(0, "Car size is required !!!", null, null); 
				}
				 //$all_selected_workshop = \App\Services::get_car_wash_services_workshop(NULL , $off_days_workshop_users);
				$all_selected_workshop = \App\Services::get_car_wash_services_workshop_new1($services_id , $request->car_size , $off_days_workshop_users);
				//$all_selected_workshop = \App\Services::get_car_wash_services_workshop($services_id, null, $off_days_workshop_users , $selected_days_id, $request->car_size);
				$service_time = 0;
				$remove_workshop_arr = [];
				$service_payment_data  = collect();
				$flag = 0;
				/*Get Service Time script start*/
				$service_time = sHelper::get_car_wash_service_time($request->car_size , $request->category_id);
				/*End*/
				foreach($all_selected_workshop as $workshop_users){
					$workshop_users->max_appointment = 0;
					$workshop_users->hourly_rate = (string) 0;
					/*Check Service details in service table*/
					$service_details = Services::where([['category_id' , '=' , $request->category_id] , ['car_size', '=', $request->car_size] , ['users_id', '=', $workshop_users->users_id]])->first();
				    if($service_details == NULL){
						$service_payment_data =  WorkshopServicesPayments::where([['category_type' , '=' , 1], ['workshop_id', '=', $workshop_users->users_id]])->first();
						if($service_payment_data != NULL){
						    $workshop_users->hourly_rate = $service_payment_data->hourly_rate;
						    $workshop_users->max_appointment = $service_payment_data->maximum_appointment;				
						   }
					}else{
						$workshop_users->hourly_rate = $service_details->hourly_rate;
						$workshop_users->max_appointment = $service_details->max_appointment;
					}
					/*workshop user id push in remove array*/
					if($service_details == NULL && $service_payment_data == NULL){
					     $remove_workshop_arr[] =  $workshop_users->users_id;
					   }
					/*End*/
					$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $workshop_users->users_id] , ['deleted_at' , '=' , NULL]])->get();
					if($workshop_package_timing->count() == 0){
					  $remove_workshop_arr[] =  $workshop_users->users_id;
					} 
					$workshop_users->category_id = $services_id;
					$price = sHelper::calculate_service_price($workshop_users->hourly_rate , $service_time);
					/*Get 3 servive for the workshop */
					$workshop_users->coupon_list = sHelper::get_coupon_list($workshop_users->users_id ,1 , $request->category_id,1 ,$price);
					/*end*/
					/*Check time slots for */
					$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop_users->users_id);
					// $timing_slot_status = sHelper::check_time_slot($workshop_users->users_id , $selected_days_id ,$request->selected_date ,  $workshop_users->max_appointment , $services_id  , $request->car_size  , $service_time);
					/*Get PAckages availablke or not*/
					   $timing_slot_status = sHelper::check_time_slot($time_slots ,  $service_time , $request ,$workshop_users->category_id ,$workshop_users->users_id,$service_time);
					/*End*/	
					$workshop_users->available_status = $timing_slot_status;	
					$workshop_users->services_price = (string) $price;	
					$workshop_users->service_average_time = (string) $service_time;
					$workshop_users->products_id = null;
					$workshop_users->about_services = $category_details->description;
					$workshop_users->car_size = $request->car_size;
					$workshop_users->status = "1";
					$workshop_users->type = $request->type;
					$workshop_users->days_id = $selected_days_id;
					$workshop_users->is_deleted_at = NULL;
					$workshop_users->hourly_rate = (string) $workshop_users->hourly_rate;
					$workshop_users->wish_list = 0;
					if(!empty($request->user_id)){
						$user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_workshop($workshop_users->users_id , $request->user_id);
						if($user_wishlist_status == 1){
							$workshop_users->wish_list = 1;
						} else {
							$workshop_users->wish_list = 0;
						}	
					}
					/* get lat long */
					$workshop_users->latitude = 0.0;
					$workshop_users->longitude = 0.0;
					$lat_long_details = \App\Address::get_primary_address($workshop_users->users_id);
					if($lat_long_details != NULL) {
						$workshop_users->latitude = $lat_long_details->latitude;
						$workshop_users->longitude = $lat_long_details->longitude;
					}
					/*End */
				}
				//die;
				$all_selected_workshop = $all_selected_workshop->whereNotIn('users_id' , $remove_workshop_arr);	
				/*Set Min price and max price*/
				$minPrice = $all_selected_workshop->min('services_price');
				$maxPrice = $all_selected_workshop->max('services_price');  
			 /*End*/
			}

			if($request->type == 2) {
				/*Type = 2 for car assembly only*/
				$flag2 = 0;
				$remove_assemble_workshop_user_arr = [];
				if (empty($request->products_id)) {
				  return sHelper::get_respFormat(0, "please select any one products", null, null);
				}
				$products_details = \App\ProductsNew::where([['id','=',$request->products_id] , ['deleted_at' , '=' , NULL] , ['products_status' , '=' , 'A']])->first();
				if($products_details != NULL){
					//echo "<pre>";
					//print_r($products_details);exit; 
					$product_detail = sHelper::get_products_details($products_details);
					if($product_detail != NULL){
						if(!empty($product_detail->assemble_time)){
							$assemble_time = $product_detail->assemble_time;
						}else{
							$assemble_time = $products_details->assemble_kromeda_time;
						}
					}

					if(!empty($products_time_detail->assemble_time)){
						$assemble_time = $products_time_detail->assemble_time;
					} elseif(!empty($products_details->assemble_kromeda_time) && $products_details->assemble_kromeda_time == 0) {
						$assemble_time = $products_details->assemble_kromeda_time;
					}else{
						$get_assemble_time = kromedaDataHelper::get_car_kromeda_time_for_app($products_details);
						$assemble_time = $get_assemble_time['assemble_time'];
					}
					if(empty($assemble_time)){ $assemble_time = 1; } 
					/*Products Groups details*/
					//print_r($products_details->products_groups_id); die;
				   $products_groups_details = \App\Products_group::where([['deleted_at' , '=' , NULL] , ['status' , '=' , 'A'] , ['id','=' ,$products_details->products_groups_id]])->first();
				   if($products_groups_details != NULL){
					   $blongs_to_in_assemble_services = apiHelper::check_n2_belongs_in_spare($products_groups_details);
					   if($blongs_to_in_assemble_services != NULL){
						   $workshop_users_arr =  DB::table('users_categories')->where([['categories_id' , '=' , $blongs_to_in_assemble_services->main_category_id] , ['deleted_at' , '=' , NULL]])->get();
						   if($workshop_users_arr->count() > 0){
							  $all_workshop_users_id_arr = $workshop_users_arr->pluck('users_id')->unique();
							  $diff_allowed_workshop_users = $all_workshop_users_id_arr->diff($off_days_workshop_users)->all();
							  if(count($diff_allowed_workshop_users) > 0){
								 $all_selected_workshop = \App\User::get_assemble_workshop($diff_allowed_workshop_users , $blongs_to_in_assemble_services->main_category_id , $selected_days_id); 
								 if($all_selected_workshop->count() > 0){	
								    foreach($all_selected_workshop as $workshop){
											/*Get hourly rate for assemble services*/
											$workshop_service_details = apiHelper::get_assemble_workshop_details($workshop , $blongs_to_in_assemble_services->main_category_id);
											if($workshop_service_details == FALSE){
												$remove_assemble_workshop_user_arr[] = $workshop->id;  
											} 
											/*End*/
											/* get lat long */
											$workshop->latitude = 0.0;
											$workshop->longitude = 0.0;
											$lat_long_details = \App\Address::get_primary_address($workshop->id);
											if($lat_long_details != NULL) {
												$workshop->latitude = $lat_long_details->latitude;
												$workshop->longitude = $lat_long_details->longitude;
											}
											/*End */
											$workshop->category_id = NULL;
											$workshop->about_services = NULL;
											$workshop->users_id = $workshop->id;
											$price = sHelper::calculate_service_price($assemble_time, $workshop_service_details['hourly_rate']);
											/*Get 3 servive for the workshop */
											$workshop->coupon_list = sHelper::get_coupon_list($workshop->id ,2 , $blongs_to_in_assemble_services->main_category_id ,$price);
											/*end*/
											$workshop->services_price = (string) $price;	
											$workshop->service_average_time = (string) $assemble_time;  
											/*Check workshop service time availability*/
										//	$timing_slot_status = apiHelper::check_assemble_workshop_time_slot($workshop->id , $selected_days_id , $request->selected_date , $blongs_to_in_assemble_services->main_category_id , $workshop_service_details['max_appointment'] , $assemble_time);
											$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop->users_id);
											/*Get PAckages availablke or not*/
											$timing_slot_status = sHelper::check_time_slot($time_slots ,  $assemble_time , $request ,$blongs_to_in_assemble_services->main_category_id ,$workshop->users_id,$assemble_time);
											/*End*/	
											$workshop->available_status = $timing_slot_status;  
											/*End*/
										   /*Workshop users days details */
											$workshop->wish_list = 0;
											if(!empty($request->user_id)){
												$user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_workshop($workshop->users_id , $request->user_id);
												if($user_wishlist_status == 1){
													$workshop->wish_list = 1;
												} else {
													$workshop->wish_list = 0;
												}	
											}
										}
									$all_selected_workshop = $all_selected_workshop->whereNotIn('users_id' , $remove_assemble_workshop_user_arr);		
								   }
								   /*Set Min price and max price*/
			                      $minPrice = $all_selected_workshop->min('services_price');
			                      $maxPrice = $all_selected_workshop->max('services_price');  
			                     /*End*/ 
								}
							  else{
								return sHelper::get_respFormat(0, "No Workshop  available !!!", null, null);     
								}	
							
							 }
						   else{
							return sHelper::get_respFormat(0, "No Workshop  available !!!", null, null);   
							 }	 					   
						          
						 }
					   else{
						 return sHelper::get_respFormat(0, "No , Workshop available !!!", null, null);   
						 } 	 
					   
					 }
					else{
					   return sHelper::get_respFormat(0, "Something Went Wrong . please try again ", null, null);  
					 } 
				   /*End*/
				  }	else{
				  return sHelper::get_respFormat(0, "please select correct products", null, null); 
				  }  
			}
		}
		$price = null;
		if ($all_selected_workshop->count() > 0) {
			$price = 0;
			foreach ($all_selected_workshop as $workshop_users) {
				$workshop_users->service_images = NULL;
				$workshop_users->package_list = NULL;
				$workshop_users->profile_image_url = NULL;
				if(!empty($workshop_users->profile_image)){
				   $workshop_users->profile_image_url = url("storage/profile_image/$workshop_users->profile_image");
				   }
				   /*Manage workshop feedback api */
				   $workshop_users = sHelper::manage_workshop_feedback_in_api($workshop_users , $workshop_users->users_id); 
				   /*End*/
			}
			//
			//$all_filtered_workshop = $sorted->values()->all();
			if (!empty($request->rating)) {
				$rating_arr = explode(',', $request->rating);
			    $all_selected_workshop =  $all_selected_workshop->whereBetween('rating_star', $rating_arr);
			}
			else{
			   $all_selected_workshop->sortByDesc('rating_star'); 
			}
			if (!empty($request->price_range)) {
				$price_arr = explode(',', $request->price_range);
				$all_selected_workshop = $all_selected_workshop->whereBetween('services_price', $price_arr);
			}
			if (!empty($request->price_level)) {
				if ($request->price_level == 1) {
					$all_selected_workshop = $all_selected_workshop->sortBy('services_price')->values();
				}
				else if ($request->price_level == 2) {
				 $all_selected_workshop = $all_selected_workshop->sortByDesc('services_price')->values();
				}
			  }
			else{
			   $all_selected_workshop = $all_selected_workshop->sortBy('services_price')->values();
			  } 
			 $all_selected_workshop->map(function($workshop) use ($minPrice , $maxPrice){
				 $workshop->min_price = $minPrice;
				 $workshop->max_price = $maxPrice;
				 return $workshop;
			 }); 	
			return sHelper::get_respFormat(1, " ", null, $all_selected_workshop);
		} else {
			return sHelper::get_respFormat(0, " No Workshop Available for this service !!!. ", null, null);
		}
	}	
	
    public function get_services($category_id , $car_size) {
    	if(!empty($category_id) || !empty($car_size)) {
    	    if(!is_numeric($car_size)){
    	        return sHelper::get_respFormat(1, "please Select correct car size , is always a numeric value ", null, null);
    	    }
			$response_arr = [];
			$price = null;
			$allowed_workshop_users = [];
			$services = sHelper::get_car_wash($category_id);
			$workshop_user = DB::table('users_categories')->where([['categories_id' , '=' , 1] , ['deleted_at' , '=' , NULL]])->get();
			if($workshop_user->count() > 0){
				foreach($workshop_user as $w_user){
					$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $w_user->users_id] , ['deleted_at' , '=' , NULL]])->get();
					if($workshop_package_timing->count() > 0){
						$allowed_workshop_users[] = $w_user->users_id;
					} 
				}
			}
			if ($services->count() > 0) {
				foreach ($services as $service) {
					/*Get package minimum price script start*/
					 $service_time = sHelper::get_car_wash_service_time($car_size , $service->id);
					/*End*/
					/*Get Car wash Price */
					$service_hourly_min_price_arr = [];
					if(count($allowed_workshop_users) > 0){
					    foreach($allowed_workshop_users as $key=>$workshop_user){
						   $hourly_cost = 0;
							$single_service_details = DB::table('services')->where([['users_id' , '=' , $workshop_user] , ['category_id' , '=' , $service->id] , ['car_size' , '=' , $car_size]])->first();
							if($single_service_details != NULL){
								$hourly_cost = $single_service_details->hourly_rate;
							}
							else{
								$get_bulk_details = DB::table('workshop_service_payments')->where([['workshop_id' , '=' , $workshop_user] , ['category_type' , '=' , 1]])->first();
								if($get_bulk_details != NULL){
								    $hourly_cost = $get_bulk_details->hourly_rate;
								}
							}
							$service_min_price = sHelper::calculate_service_price($service_time , $hourly_cost);
							if(!empty($service_min_price)){
								$service_hourly_min_price_arr[] = $service_min_price;
							}
						}
					}
					 /*End*/
					/*get Services images*/
					$service->images = null;
					$image_response = Gallery::get_category_image($service->id);
					if ($image_response->count() > 0) {
						$service->images = $image_response;
					}
					/*End*/
					// $service->services_price = (string) min($service_hourly_min_price_arr);
					 if(count($service_hourly_min_price_arr) > 0){
					  $service->services_price = (string) min($service_hourly_min_price_arr);					  }
					else{
						$service->services_price = '0';
					  }  
					$response_arr[] = $service;
				}
				return sHelper::get_respFormat(1, "", null, $response_arr);
			}
			return sHelper::get_respFormat(0, " No Service Available. ", null, null);
		} else {
			return sHelper::get_respFormat(0, "Un-expected , please try again .", null, null);
		}
	}
}
