<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use kRomedaHelper;
use kromedaSMRhelper;
use App\ItemsRepairsServicestime;
use App\ItemsRepairsTimeId;
use App\WorkshopTyre24Details;
use App\WorkshopServicesPayments;
use App\Feedback;
use App\Models;
use App\Workshop_user_day_timing;
use App\Http\Controllers\API\SpecialCondition;
use App\Http\Controllers\Coupon;
use App\Model\UserDetails;
use sHelper;
use apiHelper;
use Auth;
use serviceHelper;
use App\Library\orderHelper;
use DB;
use Validator;
use kromedaDataHelper;
class CarMaintenanceController extends Controller {
	
      //Get list of CarMaintenanceController
  	public function car_maintenance_services(Request $request){
		set_time_limit(0);
		$minPrice = $maxPrice = 0;
		$validator = Validator::make($request->all(), ['version_id'  => 'required','language'  => 'required']);
		if($validator->fails()) {
			return sHelper::get_respFormat(0, $validator->errors()->first(), NULL, NULL);
		} 
		$lang = sHelper::get_set_language($request->language);
		/*Check car maintinance service available or not */
		$service_time_id_arr = [];
		$car_maintinance_services_times = ItemsRepairsTimeId::where([['version_id' , '=' , $request->version_id] , ['language' , '=' , $lang]])->get();
		if($car_maintinance_services_times->count() <= 0 ){
			/*Save kromeda n1 , n2 */
			$get_model_details = \App\Version::get_version($request->version_id);
			if($get_model_details != NULL) {
				$maker_details = \App\Models::get_model($get_model_details->model);
				if($maker_details != NULL) {
					$save_response = kromedaDataHelper::get_groups_and_save($maker_details->maker , $get_model_details->model , $get_model_details->idVeicolo , $lang);
					$get_all_n2 = \App\Products_group::where([['car_version', '=', $request->version_id], ['parent_id', '!=', 0]])->get();
					if($get_all_n2->count() > 0) {
						foreach($get_all_n2 as $n2_category) {
							if($n2_category->type != 2){
								$product_item_response = \App\ProductsGroupsItem::check_today_execute($n2_category->id);
								if($product_item_response->count() <= 0){
									$product_response = kromedaHelper::get_sub_products_by_sub_group($request->version_id , $n2_category->group_id , $lang);
									if(is_array($product_response)){
										$response =  \App\ProductsGroupsItem::add_group_items_new($product_response , $n2_category->id , $lang , $request->version_id , $n2_category->group_id);
									}
								}
							}
						}
					}
				}
			}
			/*End*/
			$get_time_id_response = kromedaSMRhelper::kromeda_version_criteria($request->version_id , $lang);
			$time_id_arr = json_decode($get_time_id_response);
			if($time_id_arr->status == 200){
				if(count($time_id_arr->response) > 0){
					$item_times_response = ItemsRepairsTimeId::save_item_repairs_time_id($request->version_id , $time_id_arr->response , $lang);
				}
			}
		}
		$car_maintinance_services = ItemsRepairsTimeId::where([['version_id' , '=' , $request->version_id] , ['language' , '=' , $lang]])->get();
		$service_time_id_arr = $car_maintinance_services->pluck('id')->all();
		$car_maintinance_services = ItemsRepairsServicestime::whereIn('items_repairs_time_ids_id' , $service_time_id_arr)->get(); 
		if($car_maintinance_services->count() <= 0){
			foreach($service_time_id_arr as $key=>$value){
				$item_times_response  = ItemsRepairsTimeId::find($value);
				if($item_times_response != NULL){
					$services_time_response = kromedaSMRhelper::kromeda_version_service_time($item_times_response->version_id , $item_times_response->repair_times_id , $lang);
					$services_time = json_decode($services_time_response);
						if($services_time->status == 200){
							if($item_times_response->language == "ENG") {
								$time_response = ItemsRepairsServicestime::save_item_repairs_times_eng($item_times_response->version_id , $item_times_response , $services_time->response , $lang); 
							} else if($item_times_response->language == "ITA") {
								$time_response = ItemsRepairsServicestime::save_item_repairs_times_ita($item_times_response->version_id  , $item_times_response , $services_time->response , $lang); 
							}
						}
				}
			}
		}
		/*End*/
		$car_maintinance_data = ItemsRepairsServicestime::where([['version_id','=',$request->version_id],['language' , '=' , $lang] , ['type' , '=' , 1]])
									->select('id','version_id' , 'users_id','item' ,'items_repairs_time_ids_id','item_id','front_rear','left_right','action_description','time_hrs','id_info','type','status')		
									->orWhere([['type' , '=' , 2]])	
									->get();

		if($car_maintinance_data->count() > 0){
			if(!empty($request->front_rear)){
				$car_maintinance_data = $car_maintinance_data->where('front_rear',$request->front_rear);
			}
			if(!empty($request->left_right)){
				$car_maintinance_data = $car_maintinance_data->where('left_right',$request->left_right);
			}
			
			if($car_maintinance_data->count() > 0){
				foreach($car_maintinance_data as $car_maintinance_part){ 
					$car_maintinance_part = kromedaDataHelper::arrange_car_maintinance($car_maintinance_part);
					if($car_maintinance_part->type == 1){
						/*Check kromeda parts item number for */
							//$save_parts_response =  kromedaDataHelper::save_car_maintinance_parts($car_maintinance_part);
						/*End*/
					}
					if(!empty($car_maintinance_part->our_time)){
						$service_time = $car_maintinance_part->our_time;
						} elseif(!empty($car_maintinance_part->k_time)) {
						$service_time = $car_maintinance_part->k_time;
						} else {
						$service_time = $car_maintinance_part->time_hrs;
					}
					$car_maintinance_part->price = round(sHelper::get_car_maintenance_min_prce($car_maintinance_part->id,$service_time)  , 2);
					$car_maintinance_part->part_list = NULL;
					$part_list = sHelper::spare_part_list_by_car_maintenance($car_maintinance_part);
					$part_list = json_decode($part_list);
					if($part_list->status == 200){
						foreach($part_list->responce as $product){
							$product = kromedaDataHelper::arrange_spare_product($product);
							$product = sHelper::get_parts_feedback_api($product , $product->id,  2);
							$product->brand_image = $product->images = null;
							$image_arr = sHelper::get_products_image($product);
							if($image_arr->count() > 0){
								$product->images = $image_arr;
							}
							/*Get Brand image logo */
							$brand_image = \App\BrandLogo::brand_logo($product->listino);
							if($brand_image != NULL){
								$product->brand_image = $brand_image->image_url; 
							} 
							/*End*/  
							/*Get 3 servive for the workshop */
							$product->coupon_list = sHelper::get_coupon_product_list($product->id ,1 , $product->listino); 
							/*end*/ 
						}
						$min_seller_price = $max_seller_price= 0; 
						$products_list = collect($part_list->responce);
						$min_seller_price = $products_list->min('seller_price');
						$max_seller_price = $products_list->max('seller_price');
						$products = $products_list->map(function($product) use ($min_seller_price , $max_seller_price){
							$product->min_seller_price = $min_seller_price;
							$product->max_seller_price = $max_seller_price;
							return $product;
						});
						$car_maintinance_part->part_list = $products;	
					}	
				}
					
				if(!empty($request->price_range)){
					$price_arr = explode(',', $request->price_range);
					$car_maintinance_data = $car_maintinance_data->whereBetween('price',$price_arr);
				}
				if (!empty($request->price_level)) {
					if ($request->price_level == 1) {
						$car_maintinance_data = $car_maintinance_data->sortBy('price')->values();
					} elseif($request->price_level == 2) {
						$car_maintinance_data = $car_maintinance_data->sortByDesc('price')->values();
					}
				} else {
					$car_maintinance_data = $car_maintinance_data->sortBy('price')->values();
				}
					
				$minPrice = $car_maintinance_data->min('price');
				$maxPrice = $car_maintinance_data->max('price');
				$car_maintinance_data->map(function($car_maintinance_data) use ($minPrice , $maxPrice){
				$car_maintinance_data->min_price = $minPrice;
				$car_maintinance_data->max_price = $maxPrice;
					return $car_maintinance_data;
				});
				$car_maintinance_data = array_values($car_maintinance_data->toArray());
				return sHelper::get_respformat(1, null, null, $car_maintinance_data);
			} else {
				return sHelper::get_respformat(0, null, "No data !!!", null);	
			}
		} else {
			return sHelper::get_respformat(0, null, "No service available !!!", null);	
		}
	}
			
	public function car_maintenance_workshop(Request $request){
			//send selected_date
			$off_days_workshop_users = [];
			$minPrice = 0;
			$maxPrice = 0;
			if (!empty($request->selected_date)) {
				$off_selected_date = \App\Workshop_leave_days::get_valid_workshop($request);
				$users_arr = $off_selected_date->pluck('users_id');
				$off_days_workshop_users = $users_arr->all();
			}
			$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
			$flag2 = 0;
			$remove_assemble_workshop_user_arr = [];
			$all_selected_workshop = \App\Users_category::get_workshop_list($off_days_workshop_users,12);
			//$service_time = 0;
			$remove_workshop_arr = [];
			$service_payment_data  = collect();
			$flag = 0;
			$workshop_users_status =[];
			if($all_selected_workshop->count() > 0){
				$service_ids = $request->service_id;
				$service_id  = explode(',' ,$service_ids);
			   	foreach($all_selected_workshop as $workshop_users){
				    $workshop_users->max_appointment = 0;
				    $workshop_users->hourly_cost =  (string)  0;
					$price = [];
					$service_times = [];
					$workshop_users->latitude = 0.0;
					$workshop_users->longitude = 0.0;
					$workshop_users->distance = 0.0;
					//select multiple service
					foreach($service_id as $service){
						$get_car_list = ItemsRepairsServicestime::get_maintenance_services_details($service);
						/*Get Service Time script start*/
						if(!empty($get_car_list->our_time)){
							$service_time = $get_car_list->our_time;
						} elseif(!empty($get_car_list->k_time)) {
							$service_time = $get_car_list->k_time;
						} else {
							$service_time = $get_car_list->time_hrs;
						}
						$service_times[] = $service_time;
						$service_details= serviceHelper::car_maintinance_price_appoinment($service, $workshop_users->users_id);
						if(!empty($service_details['hourly_cost'])){
							$workshop_users->hourly_cost = $service_details['hourly_cost'];
						}
						if(!empty($service_details['max_appointment'])){
							$workshop_users->max_appointment = $service_details['max_appointment'];
						}
						/*Check time slots for */
						$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop_users->users_id);
						$car_maintenance_time_status = sHelper::check_car_maintenance_time_slot($time_slots ,  $service_time , $request ,$service,$workshop_users->users_id);
						//$workshop->available_status =  sHelper::check_time_slot_sos($time_slots , $workshop_wracker_service_details->max_appointment , $request->service_id , $service_average_times_price['time'] , $service_booking_obj->wracker_service_type[1] , $request->selected_date);
						$price[] = sHelper::calculate_service_price($workshop_users->hourly_cost , $service_time);
					}
					
						$address_list = \App\Address::get_primary_address($workshop_users->users_id); 
					//	echo '<pre>' ;print_r($address_list->latitude); die;
						if($address_list  != NULL) {
							$workshop_users->latitude = $address_list->latitude;
							$workshop_users->longitude = $address_list->longitude;
							$distance_in_km = sHelper::calculate_distance($request->latitude ,$request->longitude , $address_list->latitude , $address_list->longitude , 'K');
							$workshop_users->distance = $distance_in_km;
						}
					/*workshop user id push in remove array*/
					if($service_details == NULL){
					    $remove_workshop_arr[] =  $workshop_users->users_id;
					}
					$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $workshop_users->users_id] , ['deleted_at' , '=' , NULL]])->get();
					if($workshop_package_timing->count() == 0){
					  	$remove_workshop_arr[] =  $workshop_users->users_id;
					}
					$sum_of_price = array_sum($price);	
					/*Get 3 servive for the workshop */
						$workshop_users->coupon_list = sHelper::get_coupon_list($workshop_users->users_id ,5 ,$service, 12 ,$sum_of_price);
					/*end*/ 				
				    $workshop_users->available_status = $car_maintenance_time_status;
					$workshop_users->services_price = (string)$sum_of_price;
					//$workshop_users->service_average_time = (string) $service_time;
					$workshop_users->products_id = null;
					//$workshop_users->about_services = $category_details->description;
					$workshop_users->status = "1";
					$workshop_users->type = $request->type;
					$workshop_users->days_id = $selected_days_id;
					$workshop_users->is_deleted_at = NULL;
					//$workshop_users->hourly_rate = (string) $workshop_users->hourly_cost;
					$workshop_users->service_id = $request->service_id;
					$workshop_users->wish_list = 0;
					if(!empty($request->user_id)){
						$user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_workshop($workshop_users->users_id , $request->user_id);
						if($user_wishlist_status == 1){
							$workshop_users->wish_list = 1;
						} else {
							$workshop_users->wish_list = 0;
						}	
					}
				}
				
				  $all_selected_workshop = $all_selected_workshop->whereNotIn('users_id' , $remove_workshop_arr);
				  $minPrice = $all_selected_workshop->min('services_price');
				  $maxPrice = $all_selected_workshop->max('services_price');
				  $price = null;
				  if ($all_selected_workshop->count() > 0) {
						$price = 0;
						foreach($all_selected_workshop as $workshop_users) {
							$workshop_users->service_images = NULL;
							$workshop_users->package_list = NULL;
							$workshop_users->profile_image_url = NULL;
							if(!empty($workshop_users->profile_image)){
							   $workshop_users->profile_image_url = url("storage/profile_image/$workshop_users->profile_image");
							}
							$workshop_users = sHelper::manage_workshop_feedback_in_api($workshop_users , $workshop_users->users_id);
							//$all_feed_back = null;
							//$all_feed_back['rating'] = null;
							//$all_feed_back['num_of_users'] = null;
							//$all_feed_back = Feedback::get_workshop_rating($workshop_users->users_id);
							//if ($all_feed_back != NULL) {
								//$workshop_users->rating = $all_feed_back;
								//$workshop_users->rating_star = $all_feed_back['rating'];
								//$workshop_users->rating_count = $all_feed_back['num_of_users'];
							//}
						}
						if (!empty($request->rating)) {
							$rating_arr = explode(',', $request->rating);
							$all_selected_workshop =  $all_selected_workshop->whereBetween('rating_star', $rating_arr);
						} else {
						   	$all_selected_workshop->sortByDesc('rating_star');
						}
						if (!empty($request->price_range)) {
							$price_arr = explode(',', $request->price_range);
							$all_selected_workshop = $all_selected_workshop->whereBetween('services_price', $price_arr);
						}
						if (!empty($request->price_level)) {
							if ($request->price_level == 1) {
								$all_selected_workshop = $all_selected_workshop->sortBy('services_price')->values();
							} else if($request->price_level == 2) {
								$all_selected_workshop = $all_selected_workshop->sortByDesc('services_price')->values();
							}
						} else {
						    $all_selected_workshop = $all_selected_workshop->sortBy('services_price')->values();
						}
						$all_selected_workshop->map(function($workshop) use ($minPrice , $maxPrice){
							$workshop->min_price = (string)$minPrice;
							$workshop->max_price = (string) $maxPrice;
							return $workshop;
						 });
						$sum_of_array = array_sum($service_times);
						$all_selected_workshop->map(function($workshop) use ($sum_of_array){
                        $workshop->service_average_time = (string) $sum_of_array;
						});
						return sHelper::get_respFormat(1, " ", null, $all_selected_workshop);
					} else {
						return sHelper::get_respFormat(0, "No Workshop Available for this service !!!. ", null, null);
					}
			}
		  	return sHelper::get_respformat(1, null, null, $all_selected_workshop);
   }
   //get next seven_days_min_price_for_service
	public function get_next_seven_days_min_price_for_car_maintenance(Request $request) {
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
					return sHelper::get_respFormat(0, "please select category", null, null);
			}
			$all_selected_workshop = \App\Users_category::get_workshop_list($off_days_workshop_users,12);
			$price = null;
			$remove_workshop_arr = [];
			$service_id = $request->service_id;
			$service_ids = explode(',', $service_id);
			if ($all_selected_workshop->count() > 0) {
				foreach ($all_selected_workshop as $workshop_users) {
					$workshop_users->max_appointment = 0;
					$workshop_users->hourly_cost =  (string)  0;
				/*select multiple service id's*/	
				foreach($service_ids as $service){
				if ($request->type == 1) {
					$get_car_list = ItemsRepairsServicestime::get_maintenance_services_details($service);
					if($get_car_list != NULL){
					/*Get service average time */
					if(!empty($get_car_list->our_time)){
						$average_timing = $get_car_list->our_time;
					} elseif(!empty($get_car_list->k_time)) {
						$average_timing = $get_car_list->k_time;
					} else {
						$average_timing = $get_car_list->time_hrs;
					}
					/*End*/
					} else {
					return sHelper::get_respFormat(0, "Please select valid category !!!", null, null); 
					}
					}
					$service_details= serviceHelper::car_maintinance_price_appoinment($service, $workshop_users->users_id);
					if(!empty($service_details['hourly_cost'])){
					$workshop_users->hourly_cost = $service_details['hourly_cost'];
					}
					if(!empty($service_details['max_appointment'])){
					$workshop_users->max_appointment = $service_details['max_appointment'];
					}
					/*workshop user id push in remove array*/
					if($service_details == NULL){
						$remove_workshop_arr[] =  $workshop_users->users_id;
					}
					$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $workshop_users->users_id] , ['deleted_at' , '=' , NULL]])->get();
					if($workshop_package_timing->count() == 0){
						$remove_workshop_arr[] =  $workshop_users->users_id;
					} 					
				}
		 	}
			$all_selected_workshop = $all_selected_workshop->whereNotIn('users_id' , $remove_workshop_arr);
				if($all_selected_workshop->count() > 0){
					$hourly_cost = $workshop_users->hourly_cost;
					$all_selected_workshop->map(function($workshop) use ($average_timing,$hourly_cost){
                        $workshop->service_hourly_average_price = sHelper::calculate_service_price($average_timing , $hourly_cost);
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
   //get all service_booking with special condition
	public function service_booking_for_car_maintenance(request $request){		
		$coupon_obj = new Coupon;
	    $validator = \Validator::make($request->all(), [
		      'package_id'=>'required|numeric' , 'start_time'=>'required' , 'end_time'=>'required', 
		      'selected_date'=>'required',
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
			$current_time_zones = sHelper::get_current_time_zones($request->ip());
			date_default_timezone_set($current_time_zones);
			$today_current_date_time = date('Y-m-d H:i');
			$service_date_time = $request->selected_date." ".$request->start_time;
			if($service_date_time < $today_current_date_time){
				return sHelper::get_respFormat(0 , "Please select correct booking date for booking , you not booked services in past time ." , null , null); 		
			}
			$service_specifications = $request->service_specification;
			$service_specifications = json_decode($service_specifications);
			//$service_specifications = (object) $service_specifications1;
			//echo '<pre>';print_r($service_specifications); die;	
			$all_service_final_price = [];
			foreach($service_specifications as $service_specification){	
			$all_service_final_price[] = $service_specification->price;
			$special_condition = \App\Service_special_condition::get_special_condition(12 ,$request->workshop_id);  
            if($special_condition != NUll){
				$special_condition_value =[];
				$special_condition_apply_status = 0;
                foreach($special_condition as $special_conditions){
					if($special_conditions->operation_type == 1){
						if(!empty($special_conditions->all_services != 1)){	
							if($special_conditions->category_id != $service_specification->service_id){
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
						//count max appoinment
						$count_booked_appointment = \App\ServiceBooking::count_car_booked_special_package($request->package_id , $special_conditions->workshop_id , $special_conditions->id  , 5); 
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
				/*Get Workshop Service Appointment and hourly rate*/
				$service_details = \App\Services::where([['category_id' , '=' , $service_specification->service_id] , ['users_id', '=', $get_package_details->users_id]])->first();
				if($service_details == NULL){
					$workshop_service_price = \DB::table('workshop_service_payments')->where([['workshop_id' , '=' ,$get_package_details->users_id] , ['category_type' , '=' , 1]])->first();
					if($workshop_service_price != NULL){
						$max_appointment = $workshop_service_price->maximum_appointment;
					} else{
						$max_appointment = 1; 
					}
				} else{
					$max_appointment = $service_details->max_appointment;
				}
				/*End*/
				if($s_time >= $get_package_details->start_time && $s_time <= $get_package_details->end_time){
					if($e_time >= $get_package_details->start_time && $e_time <= $get_package_details->end_time){
						if($get_package_details != NULL){
							/*Count booked Appointment*/
							 $count_booked_appointment = \App\ServiceBooking::get_booked_package_car_maintenance($request->package_id , $request->selected_date ,5 ,$service_specification->service_id);
								if($count_booked_appointment->count() == $max_appointment){
									return sHelper::get_respFormat(0 , "All appointment of this package is completely booked !!! ." , null , null); 
								}
					   /*End*/
						$discount_price = 0;
						$special_id = 0;
						if(!empty($special_condition_value)){
							// find discount for rp/per
							$special_id = $special_condition_value->id;
							if($special_condition_value->discount_type == 1){
								$discount_price = $special_condition_value->amount_percentage;
							} else {
								$discount_price = ($service_specification->price/ 100) * $special_condition_value->amount_percentage;
							}
						}
						$order_manage = \App\Products_order::save_order($request ,0,0 ,null ,0,1);
						if($order_manage){
							$request->order_id = $order_manage->id;
						}
						$service_vat = orderHelper::calculate_vat_price($service_specification->price);
						$after_discount_price = ( $service_vat +  $service_specification->price ) - $discount_price;
						$booking_result = \App\ServiceBooking::add_booking_for_car_maintenance($request , $get_package_details , $service_specification ,$discount_price ,$special_id ,$service_vat ,$after_discount_price);	 
						$order_manage = \App\Products_order::save_order($request ,$discount_price,$service_specification->price, null,$after_discount_price);
						//save service part
						$parts_arr = $service_specification->parts;
						if(!empty($parts_arr)){
							foreach($parts_arr as $part_arr){
								$product_order_manage = \App\Products_order_description::save_product_discription($request , $part_arr ,$request->order_id ,$booking_result->id , 1);
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

				}
				 //check service coupon validity script start
					if(!empty($request->coupon_id)){
						$all_service_price = array_sum($all_service_final_price);
						$coupon_response = json_decode($coupon_obj->check_coupon_validity($request->coupon_id,$request->selected_date,$all_service_price));
						if($coupon_response->status != 200){
							return sHelper::get_respFormat(0,$coupon_response->msg,null,null);
						} else {
							//save service coupon amount in user wallet
							if($coupon_response->status == 200){
								$save_coupon_amount = apiHelper::manage_registration_time_wallet(Auth::user(),$coupon_response->price,"Car maintenance service coupon.");
							}
						}
					}//end
				
					if($booking_result){
							return sHelper::get_respFormat(1 , "Booking successfully !!! " , $booking_result ,null); 
					} else {
							return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
					}  
			    } else {
						return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null);  
				}  
	  		}

	  //get car maintenance service pacakges	
	 public static function car_maintenance_services_package($workshop_id,$category_service_ids,$selected_date,$car_id ,$user_id = 0){
		$special_condition_obj = new SpecialCondition;
		if(!empty($workshop_id)){
			if(!empty($selected_date)){
				if(!empty($category_service_ids)){
					$workshop_user_details = \App\User::check_users_type($workshop_id , 2);
					$selected_days_id = \sHelper::get_week_days_id($selected_date);		  
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
							$category_service_id = explode(',' ,$category_service_ids);
							$price = [];
							$single_price=[];
							foreach ($category_service_id as  $service_id) {
							/*Get price and averagess time code script start*/
									$get_car_list = ItemsRepairsServicestime::get_maintenance_services_details($service_id);
									if($get_car_list != NULL){
									/*Get Service Time script start*/
									if(!empty($get_car_list->our_time)){
										$average_time = $get_car_list->our_time;
									} elseif(!empty($get_car_list->k_time)) {
										$average_time = $get_car_list->k_time;
									}else{
										$average_time = $get_car_list->time_hrs;
									}
									$workshop_details->max_appointment = 0;
									$workshop_details->hourly_rate =  (string)  0;
									$workshop_service_details= serviceHelper::car_maintinance_price_appoinment($service_id, $workshop_id);
									if($workshop_service_details != FALSE){
										if(!empty($workshop_service_details['hourly_cost'])){
											$workshop_details->hourly_rate = $workshop_service_details['hourly_cost'];
										}
										if(!empty($workshop_service_details['max_appointment'])){
											$workshop_details->max_appointment = $workshop_service_details['max_appointment'];
										}
										}else{
											return sHelper::get_respFormat(0 ," Workshop hourly rate not available !!!", null, null); 
										}
										} else {
											return sHelper::get_respFormat(0 ,"Something Went Wrong , please try again  !!!", null, null);
										}
										$price[] = sHelper::calculate_service_price($workshop_details->hourly_rate , $average_time);
										$calculated_price = $workshop_details->hourly_rate * $average_time;
										$single_price[] = array('service_id' => (string)$service_id , 'price' => (string)round($calculated_price ,2)  ,'hourly_rate'=>(string)$workshop_details->hourly_rate , 'average_time'=>(string)round($average_time , 2));
										// $car_maintenance_time_status = sHelper::check_car_maintenance_time_slot($workshop_id , 
										// $selected_days_id ,$selected_date ,  $workshop_details->max_appointment , $service_id ,$average_time);
										// if($car_maintenance_time_status == 0){
										// 	return sHelper::get_respFormat(0 ,"service is not available !!!", null, null);	
										// }
									$all_feed_back['rating'] = null;
									$all_feed_back['num_of_users'] = null;
									$all_feed_back = Feedback::get_workshop_rating($workshop_id);
									if ($all_feed_back != null) {
										$workshop_details->rating_star = $all_feed_back['rating'];
										$workshop_details->rating_count = $all_feed_back['num_of_users'];
									}
									/*For Services Images*/
									$workshop_details->service_images = NULL;													
									//$workshop_details->about_services = $blongs_to_in_assemble_services->description; 
									$workshop_details->main_category_id = 12;
									/*End*/
									$workshop_details->users_id = $workshop_details->id;
									$workshop_details->profile_image_url = NULL;
									$workshop_details->workshop_gallery = \App\Gallery::get_all_images($workshop_id);
									$prices = array_sum($price);
										if(!empty($workshop_details->profile_image)){
											$workshop_details->profile_image_url = url("storage/profile_image/$workshop_details->profile_image");
										}
											$workshop_details->hourly_rate = (string) $workshop_details->hourly_rate;
											$workshop_details->services_price =  (string) round($prices , 2);
											$workshop_details->maximum_appointment = $workshop_details->max_appointment;
											$workshop_details->service_specification = $single_price;
											/*Set use less API Key*/
											$workshop_details->category_id = "0";
											$workshop_details->status = "0";
											$workshop_details->service_id = $category_service_ids;
											$workshop_details->car_size = 0;
											$workshop_details->type = 0;
											$workshop_details->service_average_time = (string) $average_time;
											$max_appointment = $workshop_details->max_appointment;
										 	$hourly_rate = $workshop_details->hourly_rate;
											//$workshop_details->service_detail = $find_all_workshop;
										/*End*/
										/*Set Key in packages*/
										// if($workshop_days_timing->count() > 0){
										// 	$max_appointment = $workshop_details->max_appointment;
										// 	$hourly_rate = $workshop_details->hourly_rate;
										// 	$workshop_days_timing->map(function ($packages) use($average_time , $hourly_rate ,$max_appointment) {
										// 		$packages['available_status'] = 1;
										// 		$packages['maximum_appointment'] = $max_appointment;
										// 		$packages['price'] = (string) sHelper::calculate_service_price($average_time , $hourly_rate);
										// 		$packages['categories_id'] = 0;
										// 		$packages['hourly_price'] = (string) $hourly_rate;
										// 		return $packages;
										// 	});
										// }
											/*End*/
									/*Split Service package in booked or not booked*/
									if ($workshop_days_timing->count() > 0) {
										//$packages_list = $workshop_days_timing;
										$new_new_slot = $booked_package_id_arr = [];
										$package_list =  [];
										foreach ($workshop_days_timing as $slot) {
												$new_booked_list = $new_booked_arr = $opening_slot = $not_applicable_hour  = $not_applicable_hour_slot = $new_booked_list_slot =  [];
												$opening_slot[] = array($slot->start_time, $slot->end_time);
											/*Check do not perform operation */
							   				$special_condition_status = $special_condition_obj->do_not_perform_operation_for_car_maintenance(12 , $selected_date , $workshop_id , $slot,$car_id,$service_id);
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
														$not_applicable['price'] = (string) $prices;
														$not_applicable['hourly_price'] = (string) $prices;
														$not_applicable['available_status'] = 2;
														$new_booked_arr[] =  $not_applicable; 
													}	
												}
											}
											/*End*/		
											$new_generate_applicable_slot = sHelper::get_time_slot($not_applicable_hour_slot , $opening_slot);
											/*manage booking slot*/
											$booked_list = \App\ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $slot->id] , ['workshop_user_id','=',(int) $workshop_id] ,
															   ['type' , '=' , 5] ,
															   ['services_id' , '=' ,$service_id]])
														->whereDate('booking_date' , $selected_date)->get();
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
													$booked_arr['price'] = (string) $prices;
													$booked_arr['hourly_time'] = (string) $average_time;
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
												$slot_details['price'] = (string) $prices;
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
						}
					
							return sHelper::get_respFormat(1 ,"", $workshop_details, null);   
						} else {
						return sHelper::get_respFormat(0 ,"Packages not available !!!", null, null);  
						}	
					} else {
						return sHelper::get_respFormat(0 ,"Packages not available !!!", null, null);        
						}
					} else {
					return sHelper::get_respFormat(0 ,"Packages not available !!!", null, null);   
					}
				} else {
			return sHelper::get_respFormat(0 ,"This workshop is not valid !!!", null, null); 
			}  
		} else {
			return sHelper::get_respFormat(0 ,"Please select a date , when you want to service !!!", null, null);  
		}	
	}
	else{
		return sHelper::get_respFormat(0 ,"Please select a date , when you want to service !!!", null, null);
	}   
	} else {
	return sHelper::get_respFormat(0 ,"Please Enter the workshop id", null, null);
	}	 
	}	
	}
	 
	 //brand for car maintenance (get different band data in ptoduct new table)
	 public  static function get_brand_car_maintenance(Request $request){
		if(!empty($request->product_item)){
			$products = DB::table('products_new')
			               ->where([['products_name' ,'=' ,(string) $request->product_item]])
						   ->groupBy('listino')->get();
			if($products->count() > 0){
				foreach($products as  $product){
					$product = kromedaDataHelper::arrange_spare_product($product);
					$product = sHelper::get_parts_feedback_api($product , $product->id, 2);
					$product->brand_image_url = $product->images = $product->product_image_url = null;
					$image_arr = sHelper::get_products_image($product);
					if($image_arr->count() > 0){
						$product->images = $image_arr;
						$product->product_image_url = $image_arr[0]->image_url;
					}
					/*Get Brand image logo */
					$brand_image = \App\BrandLogo::brand_logo($product->listino);
					if($brand_image != NULL){
						$product->brand_image_url = $brand_image->image_url; 
					} 
					/*End*/  
					/*Get 3 servive for the workshop */
					$product->coupon_list = sHelper::get_coupon_product_list($product->id ,1 , $product->listino);
					/*end*/ 
				}
				return sHelper::get_respFormat(1, null ,null ,$products);					
			}	
			else{
				return sHelper::get_respFormat(1, "No Products available !!!" ,null ,$products);	
			}		   
		} 
		else {
			return sHelper::get_respFormat(0,"please select product item no",null ,null);
		}

	}

}
