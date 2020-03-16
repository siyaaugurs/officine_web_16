<?php
namespace App\Http\Controllers\API;
use sHelper;
use apiHelper;
use kromedaDataHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tyre;
use App\Tyre24;
use App\Models_details;
use App\User_tyre_detail;
use App\Tyre24_details;
use App\Version;
use App\Models;
use App\Address;
use App\WrackerServices;
use App\ServiceBooking;
use App\Services;
use App\WorkshopWreckerServices;
use App\Workshop_user_day_timing;
use App\Feedback;
use App\WorkshopServicesPayments;
use App\WorkshopWreckerServiceDetails;
use App\WorkshopTyre24Details;
use App\Users_category;
use App\Tyre_pfu;
use App\Tyre_workshop_pfu;
use App\BrandLogo;
use App\Http\Controllers\API\SpecialCondition;
use App\Model\UserDetails;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use App\Http\Controllers\Coupon;
use App\TyreImage;
use App\Gallery;
use App\Library\orderHelper;



class Tyre24Controller extends Controller{

	public $speed_index_arr =  [["name" => "H", "code" => "H"], ["name" => "T", "code" => "T"], ["name" => "V", "code" => "V"], ["name" => "ZR", "code" => "ZR"], ["name" => "Y", "code" => "Y"], ["name" => "W", "code" => "W"]];
	public $tyre_type = [["name" => "All" , "code" => "a"],["name" => "Summer tyre", "code" => "s"], ["name" => "Winter tyre", "code" => "w"], ["name" => "2-Wheel/Quad tyre", "code" => "m"], ["name" => "All-season tyre", "code" => "g"], ["name" => "Off-road tyre", "code" => "o"], ["name" => "Truck tyre", "code" => "i"]];
	public $tyre_type2 = [["name" => "Summer tyre", "code" => "s"], ["name" => "Winter tyre", "code" => "w"], ["name" => "2-Wheel/Quad tyre", "code" => "m"], ["name" => "All-season tyre", "code" => "g"], ["name" => "Off-road tyre", "code" => "o"], ["name" => "Truck tyre", "code" => "i"]];

	public $tyre_type_1 = [["name" => "2-Wheel/Quad tyre", "code" => "m"], ["name" => "Off-road tyre", "code" => "o"], ["name" => "Truck tyre", "code" => "i"], ["name" => "Car tyre", "code" => "c"]];
	public $tyre_type_for_api = [["name" =>'All',"code" =>'a'],["name" => "2-Wheel/Quad tyre", "code" => "m"], ["name" => "Off-road tyre", "code" => "o"], ["name" => "Truck tyre", "code" => "i"], ["name" => "Car tyre", "code" => "c"]];
	public $tyre_type_status_for_api = ["a" ,"m" ,"o", "i", "c"];

	public $season_tyre = [["name" => "Summer tyre", "code" => "s"], ["name" => "Winter tyre", "code" => "w"], ["name" => "All-Season", "code" => "g"]];
	public $tyre_main_category = 23;

	public function get_tyre_list(Request $request){
	    $coupon_obj = new Coupon;
		set_time_limit(500);
		$tyre_pfu_arr = $min_price = $max_price = $tyre_type_arr = [];
		$tyre_list = json_decode(Tyre24::tyre_list($request));
		if($tyre_list->status == 200){
			if (count($tyre_list->response) > 0){	
				/* print_r($tyre_list);exit;	 */
				foreach ($tyre_list->response as $get_tyre) {
						$get_tyre = kromedaDataHelper::arrange_tyre_detail($get_tyre);
						$get_tyre->pfu = $get_tyre->brand_image = null;
						if(!empty($get_tyre->ean_number)){ $item_number = $get_tyre->ean_number; }
						else{ $item_number = $get_tyre->itemId; }
						if(!empty($get_tyre->manufacturer_description)) {
							$brand = $get_tyre->manufacturer_description; 
						}
						$get_tyre->seller_price = (string) $get_tyre->seller_price;
						/*Manage tyre image*/
						$tyre_images =  TyreImage::get_all_tyre_image($get_tyre , 1);
						$tyre_images->push(['image_url'=>$get_tyre->imageUrl,'id'=>0, 'tyre24_id' => '' ,  'tyre_item_id' => '' , 'image_name' => '' , 'deleted_at' => '' , 'created_at' => '', 'updated_at' => '' ]);
						/*End*/
						/*manage tyre label images*/
						$tyre_label_images =  TyreImage::get_all_tyre_image($get_tyre , 2);
						$tyre_label_images->push(['image_url'=>$get_tyre->tyreLabelUrl,'id'=>0, 'tyre24_id' => '' ,  'tyre_item_id' => '' , 'image_name' => '' , 'deleted_at' => '' , 'created_at' => '', 'updated_at' => '' ]);
						/*End*/
						$get_tyre->images = $tyre_images; 
						$get_tyre->tyre_label_images = $tyre_label_images; 
						$tyre_final_price = $get_tyre->seller_price;
						/*Manage pfu for tyre admin*/
						$tyre_pfu_detail = DB::table('tyre_pfus')
						                       ->select('admin_price as price')
						                       ->where([['deleted_at' , '=' , NULL] , ['tyre_type_description' , '=' ,$get_tyre->tireClass] , ['deleted_at' , '=' , NULL]])->first();
						if($tyre_pfu_detail != NULL){
							$get_tyre->pfu =  $tyre_pfu_detail;
							$tyre_pfu_detail->price = (string) $tyre_pfu_detail->price;
							//$tyre_final_price = 0;
							$tyre_final_price =  (int) $get_tyre->seller_price + (int) $tyre_pfu_detail->price;
						}
						$get_tyre->coupon_list = NULL;
						/*Manage coupons*/
						$coupon_response = $coupon_obj->find_product_coupon($item_number , $brand  , $tyre_final_price , 2 );
						if($coupon_response['status'] == 1){
							$get_tyre->coupon_list = $coupon_response['response'];
						}
						/*End*/
						/* echo "<pre>";
						print_r($coupon_response);exit;  */
						/*End*/	
						$get_tyre->wish_list = 0;
						if(!empty($request->user_id)){
						   $product_id = $get_tyre->id;
						   $user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_product($product_id , $request->user_id , $request->product_type);
							if($user_wishlist_status == 1){
								$get_tyre->wish_list = 1;
							} else {
								$get_tyre->wish_list = 0;
							}	
						}
						/*add brand image script start*/
						$brand_image = \App\BrandLogo::brand_logo_tyre($get_tyre->manufacturer_description);
						if($brand_image != NULL){
							$get_tyre->brand_image = $brand_image->image_url;
						}
						/*End*/
						/*manage rating*/
						$rating = sHelper::get_parts_feedback_api($get_tyre , $get_tyre->id , 2); 
						/*End*/
					}
				   return sHelper::get_respFormat(1, null, null, $tyre_list->response);
			}
		   else{
			return sHelper::get_respFormat(0, "No record found !!!", null, null); 
		   }
		}
		else{
			return sHelper::get_respFormat(0, "No record found !!!", null, null);
		}
	}
	
	public function get_tyre_details(Request $request){
		$coupon_obj = new Coupon;
		$min_price = $max_price = [];
		$validator = Validator::make($request->all(), ['seller_id'  => 'required','tyre_id'  => 'required']);
		if ($validator->fails()) {
			return sHelper::get_respFormat(0, $validator->errors()->first(), NULL, NULL);
		}
		$get_tyre = Tyre24::where([['id' , '=' , $request->tyre_id]])->first();
		if($get_tyre != NULL) {
			$get_tyre = kromedaDataHelper::arrange_tyre_detail($get_tyre);
			$get_tyre->pfu = $get_tyre->brand_image = null;
			if(!empty($get_tyre->ean_number)){ $item_number = $get_tyre->ean_number; }
			else{ $item_number = $get_tyre->itemId; }
			if(!empty($get_tyre->manufacturer_description)) {
				$brand = $get_tyre->manufacturer_description; 
			}
			$get_tyre->seller_price = (string) $get_tyre->seller_price;
			/*Manage tyre image*/
			$tyre_images =  TyreImage::get_all_tyre_image($get_tyre , 1);
			$tyre_images->push(['image_url'=>$get_tyre->imageUrl,'id'=>0, 'tyre24_id' => '' ,  'tyre_item_id' => '' , 'image_name' => '' , 'deleted_at' => '' , 'created_at' => '', 'updated_at' => '' ]);
			/*End*/
			/*manage tyre label images*/
			$tyre_label_images =  TyreImage::get_all_tyre_image($get_tyre , 2);
			$tyre_label_images->push(['image_url'=>$get_tyre->tyreLabelUrl,'id'=>0, 'tyre24_id' => '' ,  'tyre_item_id' => '' , 'image_name' => '' , 'deleted_at' => '' , 'created_at' => '', 'updated_at' => '' ]);
			$get_tyre->images = $tyre_images; 
			$get_tyre->tyre_label_images = $tyre_label_images; 
			$tyre_final_price = $get_tyre->seller_price;
			/*Manage pfu for tyre admin*/
			$tyre_pfu_detail = DB::table('tyre_pfus')
								   ->where([['deleted_at' , '=' , NULL] , ['tyre_type_description' , '=' ,$get_tyre->tireClass] , ['users_id' , '=' , $request->seller_id]])->first();
			if($tyre_pfu_detail != NULL){
				$get_tyre->pfu_detail = ['pfu_price'=>(string) $tyre_pfu_detail->admin_price , 'seller_id'=>$tyre_pfu_detail->users_id];  
			}
			$get_tyre->coupon_list = NULL;
			/*Manage coupons*/
			$coupon_response = $coupon_obj->find_product_coupon($item_number , $brand  , $tyre_final_price , 2 );
			if($coupon_response['status'] == 1){
				$get_tyre->coupon_list = $coupon_response['response'];
			}
			/*End*/
			$get_tyre->wish_list = 0;
			if(!empty($request->user_id)){
			   $product_id = $get_tyre->id;
			   $user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_product($product_id , $request->user_id , $request->product_type);
				if($user_wishlist_status == 1){
					$get_tyre->wish_list = 1;
				} else {
					$get_tyre->wish_list = 0;
				}	
			}
			$tyre_groups_details = DB::table('categories')->where([['deleted_at' , '=' , NULL] , ['status' , '=' , 0] , ['category_type' , '=' , 23] ])
																->where([['range_from', '<=',$get_tyre->max_diameter] , ['range_to' ,'>=',$get_tyre->max_diameter]])
																->first();		
			if($tyre_groups_details != NULL){
				/*Wokrshop minimum price*/
				$current_time_zones = sHelper::get_current_time_zones($request->ip());
				date_default_timezone_set($current_time_zones);
				$selected_date = date('Y-m-d');
				$off_workhops_on_that_day = sHelper::get_off_users_on_date($selected_date);
				$workshops = \App\Users_category::get_workshop_list($off_workhops_on_that_day , $this->tyre_main_category);
				$services_price = [];
				foreach ($workshops as $workshop) {
					/*Find workshop service detail*/
					$service_details = WorkshopTyre24Details::where([['category_id' , '=' , $tyre_groups_details->id] , ['workshop_user_id', '=', $workshop->users_id]])->first();
					/*End*/
					if($service_details != NULL){
						$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop->users_id);
						if($time_slots->count() > 0){
							$services_price[] = (string) sHelper::calculate_service_price( $service_details->hourly_rate , $tyre_groups_details->time);
						}
					}
				}
				if(count($services_price) > 0){
					$min_price = min($services_price);
				}else{
					$min_price = 0;
				}
				/*End*/
			}	
			$get_tyre->min_prices = (string) !empty($min_price) ? $min_price : 0;												
			return sHelper::get_respFormat(1, null, $get_tyre, null);
		} else {
			return sHelper::get_respFormat(0, "No data match.", null, null);
		}	
	}


	public function get_tyre_specification(Request $request){
        $tyre_measurement = apiHelper::tyre_measurement();
		$tyre_measurement['image'] = "http://officine.augurstech.com/officineTop/public/storage/brand_logo_image/tire-size-explained.jpg";
		$tyre_measurement ['discription'] = "Refer below measurements to enter correct tyre data.";
		$price = Tyre24::get_min_max_seller_price();
		$tyre_measurement ['price'] = ['min_price'=>$price[0] , 'max_price'=>$price[1]];
		return sHelper::get_respFormat(1, null, $tyre_measurement , null);	
	}




	public function save_user_tyre_details(Request $request){
		$speed_index =  $vhicle_type = $season_type = NULL;
		if (count($request->all()) > 0) {
			/*manage speed index */
			if(!empty($request->speedindex)){ 
				$speed_index = explode(',' , $request->speedindex);
				$sped_index_response = DB::table('master_tyre_measurements')
				->wherein('id' , $speed_index)->get();
				if($sped_index_response->count() > 0){
				  $speed_index_arr = $sped_index_response->pluck('name')->all();
				  if(count($speed_index_arr) > 0){
					  $speed_index = $speed_index_arr[0];
				  }
				}
			  }
			/*End*/
			  /*Maange vhicle type script start*/
			  if(!empty($request->vehicle_type)){ 
					$vhicle_tyre_type = DB::table('master_tyre_measurements')
					->where('id' , $request->vehicle_type)->first();
					if($vhicle_tyre_type != NULL){
						$vhicle_tyre_type_arr = json_decode($vhicle_tyre_type->code);
						if(count($vhicle_tyre_type_arr) > 0){
						$vhicle_type = $vhicle_tyre_type_arr[0];
						}
					}
			   } 
			  /*End*/
			  /*Manage season type*/
			 /*  if(!empty($request->season)){ 
				$season_response = DB::table('master_tyre_measurements')->where('id' , $request->season)->first();
				if($season_response != NULL){
					$season_type = $season_response->code2;
			   }
			  }  */
			  /*End*/
			$save_user_tyre_details = User_tyre_detail::save_user_tyre_details($request ,$speed_index ,  $vhicle_type , $season_type);
			if($save_user_tyre_details){
						/* echo "<pre>";
		    print_r($save_user_tyre_details);exit; */
				return sHelper::get_respFormat(1, "insert successfully !.", $save_user_tyre_details , null);
			}
			else{
				return sHelper::get_respFormat(0, "Something went wrong , please try again !!!.", null, null);
			}
	
			
		} else {
			return sHelper::get_respFormat(0, "Unauthenticate , please login first .", null, null);
		}
	}

	public function get_user_tyre_details(Request $request){
		if (!empty($request->user_id)) {
			if (!empty($request->car_version_id)){
				$get_user_tyre_details = User_tyre_detail::get_user_tyre_version($request);
				if ($get_user_tyre_details->count() > 0) {
					/* echo "<pre>";
					print_r($get_user_tyre_details);exit; */
					foreach ($get_user_tyre_details as $tyre_detail) {
						$tyre_detail->speedindex_status =  $tyre_detail->vehicle_type_status = $tyre_detail->season_status = NULL;
					        	/*manage speed index */
								if(!empty($tyre_detail->speedindex)){ 
									$sped_index_response = DB::table('master_tyre_measurements')
									->where([['id' , '=' ,$tyre_detail->speedindex]])->first();
									if($sped_index_response != NULL){
											$tyre_detail->speedindex_status = $sped_index_response->name; 
									}
								}
		               	       /*End*/
								/*Maange vhicle type script start*/
								if(!empty($tyre_detail->vehicle_type)){ 
										$vhicle_tyre_type = DB::table('master_tyre_measurements')
										->where('id' , $tyre_detail->vehicle_type)->first();
										if($vhicle_tyre_type != NULL){
											$tyre_detail->vehicle_type_status = $vhicle_tyre_type->name; 
										}
								} 
								/*End*/
			                   /*Manage season type*/
								if(!empty($tyre_detail->season)){ 
									$season_response = DB::table('master_tyre_measurements')->where([['id','=', $tyre_detail->season]])
									                                                     ->first();
									if($season_response != NULL){
										$tyre_detail->season_status = $season_response->name;
								    }
								}  
			                  /*End*/
						/* $get_tyre_detail['get_tyre_detail'] = Version::get_version($get_tyre_detail['car_version_id']); */
					}
					return sHelper::get_respFormat(1, null, null, $get_user_tyre_details);
				} else {
					return sHelper::get_respFormat(0, "no data", null, null);
				}
			} else {
				$get_user_tyre_detail = User_tyre_detail::get_user_tyre_details($input);
				return sHelper::get_respFormat(1, null, null, $get_user_tyre_detail);
			}
		} else {
			return sHelper::get_respFormat(0, "Unauthenticate , please login first .", null, null);
		}
	}

	public function get_workshop_address_info(Request $request){
		$lat = $request->latitude;
		$lng = $request->longitude;
		if (!empty($lat) && !empty($lng)) {
			$circle_radius = 3959;
			$max_distance = 10;
			$candidates = DB::select(
				'SELECT * FROM(SELECT id,latitude, longitude, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(' . $lng . ')) +
                    sin(radians(' . $lat . ')) * sin(radians(latitude))))
                    AS distance
                    FROM addresses) AS distances WHERE distance < ' . $max_distance . ' ORDER BY distance LIMIT 20;'
			);
			foreach ($candidates as $candidate) {
				$user_id=$candidate->id;
				$candidate->active_users = Address::find_workshop_users_location($user_id);
			 }
			return sHelper::get_respFormat(0, null, null, $candidates);
		} else {
			return sHelper::get_respFormat(0, "no data", null, null);
		}
	}


	public function get_tyre_workshop(Request $request){
		$coupon_obj = new Coupon;
			$off_days_workshop_users = [];
			$minPrice = $maxPrice = $flag2 = 0;
			if (!empty($request->selected_date)) {
				$off_days_workshop_users = sHelper::get_off_users_on_date($request->selected_date);
			}
			$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
			if(!empty($request->products_id)){
				$tyre_info = Tyre24::find($request->products_id);
				if($tyre_info != NULL){
				   $tyre_detail = kromedaDataHelper::arrange_tyre_detail($tyre_info);  
				   /*Find tyre group*/
						$tyre_groups_details = DB::table('categories')->where([['deleted_at' , '=' , NULL] , ['status' , '=' , 0] , ['category_type' , '=' , 23] ])
																	  ->where([['range_from', '<=',$tyre_detail->max_diameter] , ['range_to' ,'>=',$tyre_detail->max_diameter]])
																	  ->first();
																	 /*  echo "<pre>";
																	  print_r($tyre_groups_details);exit; */
						if($tyre_groups_details){
							/*Find workshop*/
							 $workshops = \App\Users_category::get_workshop_list($off_days_workshop_users , $this->tyre_main_category);
							/*End*/
							    $new_workshop_list = [];
								foreach ($workshops as $workshop) {
									$workshop->user_fav_status = 0;
									/*Find workshop service detail*/
									$workshop->service_images = $workshop->package_list = $workshop->profile_image_url = $workshop->package_list  =  NULL;
									$service_details = WorkshopTyre24Details::where([['category_id' , '=' , $tyre_groups_details->id] , ['workshop_user_id', '=', $workshop->users_id]])->first();
									/*End*/
										if($service_details != NULL){
											$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop->users_id);
											if($time_slots->count() > 0){
												$workshop->package_list = null;
												$workshop->hourly_rate = (string) $service_details->hourly_rate;
												$workshop->max_appointment = (string) $service_details->max_appointment;
												$request->main_category_id = $workshop->main_category_id = $this->tyre_main_category; 
												$workshop->service_id = $request->service_id = (string) $tyre_groups_details->id;
												$workshop->products_id = (string) $request->products_id;
												$workshop->quantity =  $workshop->quantity = !empty($request->quantity) ? (string) $request->quantity : 0;
												$workshop->workshop_id = $request->workshop_id =  (string) $workshop->users_id;
												if(!empty($workshop->profile_image)){
													 $workshop->profile_image_url = url("storage/profile_image/$workshop->profile_image");
												}
											   /*workshop lat long*/
												$workshop->latitude = $workshop->longitude = 0.0;
												$lat_long_details = \App\Address::get_primary_address($workshop->users_id);
												if($lat_long_details != NULL) {
													$workshop->latitude = $lat_long_details->latitude;
													$workshop->longitude = $lat_long_details->longitude;
												}
											   /*End*/
												/*check available status or not */
												$workshop->available_status = sHelper::check_trye_time_slot($time_slots , $selected_days_id , $request, $service_details, $tyre_groups_details , $tyre_detail);
												/*End*/
												$workshop->services_price = (string) sHelper::calculate_service_price($workshop->hourly_rate , $tyre_groups_details->time);
												/*Workshop coupons start*/
												$workshop->coupon_list =  $coupon_obj->find_workshop_coupon($workshop->users_id ,  $this->tyre_main_category  , $request->selected_date , $tyre_groups_details->id);
												/*End*/
												/*Manage workshop feedback api */
												$workshop = sHelper::manage_workshop_feedback_in_api($workshop , $workshop->users_id); 
												/*End*/
												//$workshop->workshop_gallery = Gallery::get_all_images($workshop->users_id);
												/*Manage favorite workshop start*/
												$workshop->service_images = NULL;
												if(!empty($request->user_id)){
													$workshop->user_fav_status = \App\User_wish_list::fav_workshop($workshop->users_id , $request->user_id);
												}
												/*End*/
												$new_workshop_list[] = $workshop;
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
									return sHelper::get_respFormat(1 , "Workshop not available !!!", null, null);		
								}
                               
						}	
						else{
						    return sHelper::get_respFormat(0, " Groups not available for this tyres !!!. ", null, null);	
						}  										
				   /*End*/
				}
			}
			else{
				return sHelper::get_respFormat(0, "Please select any one products", null, null);
			}
		}

			
		public function get_next_seven_days_min_price_for_tyre(Request $request) {
			$validator = \Validator::make($request->all(), [ 'selected_date'=>'required' , 'products_id'=>'required' ]);
			if($validator->fails()){
				return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
			} 
			$selected_date = $request->selected_date;
			$min_price = [];
			for ($i = 0; $i < 30; $i++) {
				$request->selected_date = date('Y-m-d', strtotime($selected_date . ' + ' . $i . ' day'));
				$off_days_workshop_users = [];
				$minPrice = $maxPrice = $flag2 = 0;
				if (!empty($request->selected_date)) {
					$off_workhops_on_that_day = sHelper::get_off_users_on_date($request->selected_date);
				}
				$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
				if(!empty($request->products_id)){
					$tyre_info = Tyre24::find($request->products_id);
					if($tyre_info != NULL){
					   $tyre_detail = kromedaDataHelper::arrange_tyre_detail($tyre_info);  
					   /*Find tyre group*/
							$tyre_groups_details = DB::table('categories')->where([['deleted_at' , '=' , NULL] , ['status' , '=' , 0] , ['category_type' , '=' , 23] ])
																		  ->where([['range_from', '<=',$tyre_detail->max_diameter] , ['range_to' ,'>=',$tyre_detail->max_diameter]])
																		  ->first();
							if($tyre_groups_details){
								/*Find workshop*/
								 $workshops = \App\Users_category::get_workshop_list($off_days_workshop_users , $this->tyre_main_category);
								/*End*/
									$new_workshop_list = [];
									foreach ($workshops as $workshop) {
										$workshop->user_fav_status = 0;
										/*Find workshop service detail*/
										$workshop->service_images = $workshop->package_list = $workshop->profile_image_url = $workshop->package_list  =  NULL;
										$service_details = WorkshopTyre24Details::where([['category_id' , '=' , $tyre_groups_details->id] , ['workshop_user_id', '=', $workshop->users_id]])->first();
										/*End*/
											if($service_details != NULL){
												$time_slots = sHelper::workshop_time_slot($request->selected_date , $workshop->users_id);
												if($time_slots->count() > 0){
													/*check available status or not */
													$workshop->available_status = sHelper::check_trye_time_slot($time_slots , $selected_days_id , $request, $service_details, $tyre_groups_details , $tyre_detail);
													/*End*/
													$workshop->services_price = (string) sHelper::calculate_service_price( $service_details->hourly_rate , $tyre_groups_details->time);
													$new_workshop_list[] = $workshop;
												}
												
											}
									}
									$new_workshop_list = collect($new_workshop_list);
									$min_price_value = $new_workshop_list->min('services_price');
									$min_price[] = array('date'=>$request->selected_date, 'price'=>(string) $min_price_value);
									
							}	
							else{
								return sHelper::get_respFormat(0, " Groups not available for this tyres !!!. ", null, null);	
							}  										
					   /*End*/
					}else{
						return sHelper::get_respFormat(0, " Tyre not available in our database !!!", null, null);	
					} 
				}
				else{
					return sHelper::get_respFormat(0, "Please select any one products", null, null);
				}
			}	
			return sHelper::get_respFormat(1, " ", null, $min_price);
		}
	
		
 
	
	public function get_tyre_workshop_package(Request $request){
		$validator = \Validator::make($request->all(), [
			'selected_date'=>'required' , 'products_id'=>'required' , 'workshop_id'=>'required' , 'service_id'=>'required' ,'main_category_id'=>'required'
	     ]);
		if($validator->fails()){
			return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		} 
		$tyre_info = Tyre24::find($request->products_id);
		if($tyre_info != NULL){
			$tyre_detail = kromedaDataHelper::arrange_tyre_detail($tyre_info);  
			$special_condition_obj = new SpecialCondition;
			$max_appointment = $hourly_rate  = 0;
			$category_details = DB::table('categories')->where([['id' , '=' , $request->service_id] , ['deleted_at' , '=' , NULL]])->first();
			if($category_details != NULL){
			   $workshop = \App\User::get_workshop_details($request->workshop_id);
			   if($workshop != NULL){
				   $workshop->profile_image_url = NULL;
				   $service_details = WorkshopTyre24Details::where([['category_id' , '=' , $request->service_id] , ['workshop_user_id', '=', 
				   $request->workshop_id]])->first();
				  
				   if($service_details != NULL){
					   if(!empty($workshop->profile_image)){
							 $workshop->profile_image_url = url("storage/profile_image/$workshop->profile_image");
						 }
					   $workshop->service_average_time = (string) $category_details->time;
					   $workshop->hourly_rate = (string) $service_details->hourly_rate;
					   $workshop->service_id = (string) $request->service_id;
					   $workshop->main_category_id = $this->tyre_main_category; 
					   $workshop->products_id = (string) $request->products_id;
					   $workshop->quantity = !empty($request->quantity) ? (string) $request->quantity : 0;
					   $workshop->workshop_id = (string) $request->workshop_id;
					   $workshop->max_appointment = (string) $service_details->max_appointment;  
					   $time_slots = sHelper::workshop_time_slot($request->selected_date , $request->workshop_id);
					   $final_time_slots = [];  
					   if($time_slots->count() > 0){
						   foreach ($time_slots as $slot_details) {
							   $new_booked_list = $new_booked_arr = $opening_slot = $not_applicable_hour  = $not_applicable_hour_slot = $new_booked_list_slot =  $not_applicable_hour_arr = [];
							   $opening_slot[] = [$slot_details->start_time, $slot_details->end_time];
							   $special_condition_status = $special_condition_obj->do_not_perform_operation_for_tyre($request , $opening_slot , $tyre_detail , 2);
							   $decode_response = json_decode($special_condition_status);
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
																			$query->orWhere([['users_id','=',$request->user_id] , ['type' , '=' , 4]])
																			->whereIn('status',['CA' , 'P'])
																			->whereDate('booking_date' , $request->selected_date);
																		} 	
									$booked_list = $query->get(); 
									if($booked_list->count() > 0) {
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
											$slot['price'] = (string) $service_details->hourly_rate;
											$slot['hourly_price'] = (string) $service_details->hourly_rate;
                                            if($booked_list->count() < $service_details->max_appointment){
												$get_slot_time_in_hour = sHelper::get_number_of_hour($slot[0], $slot[1]);
												 if ($get_slot_time_in_hour < $category_details->time) {
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
									/*  $slots_available = $slots_available->values(); */
									 /* echo "<pre>";
									 print_r($slots_available);exit; */
							   }
							   
								$workshop->package_list = $slots_available;
						   $workshop->workshop_gallery = Gallery::get_all_images($request->workshop_id);
						   $workshop = sHelper::manage_workshop_feedback_in_api($workshop , $request->workshop_id); 
						   return sHelper::get_respFormat(1, null, $workshop , null);
					   
					   }
					   else{
						   return sHelper::get_respFormat(0, " Time slots not available for this workshop !!! ", null, null);
					   }
				   }
				   else{
					   return sHelper::get_respFormat(0, " Service detail not fill by workshop  !!! ", null, null);
				   }         
			   }
			   else{
				   return sHelper::get_respFormat(0, " Workshop detail is not correct , please try again  !!! ", null, null);
			   }
			}
			else{
			   return sHelper::get_respFormat(0, " Something went wrong , please try again !!! ", null, null);
			}	
		}
		else{
			return sHelper::get_respFormat(0, "Something went wrong , product not find in officine top database !!! ", null, null);
		}
				   /*Find tyre group*/
	}


		
		public function tyre_service_booking(Request $request){
			$coupon_obj = new Coupon;  
			$discount = 0;
			$validator = \Validator::make($request->all(), [
				'package_id'=>'required|numeric', 'start_time'=>'required' , 'end_time'=>'required' , 'price'=>'required' , 
				'selected_date'=>'required','quantity'=>'required','service_id'=>'required','products_id'=>'required' , 'seller_id'=>'required' ,'selected_car_id'=>'required'
               ]);
				if($validator->fails()){
				   return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
				}
				if(Auth::check()){
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
					if($get_package_details != NULL){
						/*End*/
						if($s_time >= $get_package_details->start_time && $s_time <= $get_package_details->end_time){
							if($e_time >= $get_package_details->start_time && $e_time <= $get_package_details->end_time){
							if($get_package_details != NULL){
								$service = DB::table('categories')->where([['id' , '=' , $request->service_id] , ['deleted_at' , '=' , NULL]])->first();
								if($service != NULL){
									$service_details = WorkshopTyre24Details::where([['category_id' , '=' , $request->service_id] , ['workshop_user_id', '=', $get_package_details->users_id]])->first();
									if($service_details != NULL){
										$check_busy_hour = \App\ServiceBooking::get_busy_hour_for_tyre($request , $get_package_details , $request->service_id);
										if($check_busy_hour == NULL){
										   /*match operation check for tyre*/
										   $special_condition_obj = new SpecialCondition;
										   $request->workshop_id = $get_package_details->users_id;
										   $match_special_condition = json_decode($special_condition_obj->match_tyre_service_for_special_condition($request));
										   $special_condition_arr = $special_condition_id = $after_discount_price = NULL;  
										   if($match_special_condition->status == 200){
											   $special_condition_response = $match_special_condition->special_response;
											   $discount = \sHelper::get_discount_price($request->price , $special_condition_response->discount_amount , $special_condition_response->discount_type);
											   $special_condition_id = $special_condition_response->special_condition_id;
										   } 
										   /*End*/
										   /*check coupon validity script start*/
										   if(!empty($request->coupon_id)){
												$coupon_response = json_decode($coupon_obj->check_coupon_validity($request->coupon_id , $request->selected_date , $request->price));
												if($coupon_response->status != 200){
													return sHelper::get_respFormat(0 ,  $coupon_response->msg, null , null); 
												} 
												else{
													/*Save coupon amount in wallet */
													if($coupon_response->status == 200){
														$save_coupon_response = apiHelper::manage_registration_time_wallet(Auth::user() ,$coupon_response->price , "Tyre service booking .");
													}
													/*End*/
												}
                                           }
										   /*End*/
										   /*Check products coupon validity*/
										   if(!empty($request->products_coupon_id)){
												$coupon_response = json_decode($coupon_obj->check_coupon_validity($request->products_coupon_id , date('Y-m-d H:i') , $request->price));
												if($coupon_response->status != 200){
													return sHelper::get_respFormat(0 , $coupon_response->msg, null , null); 
												} 
												else{
													/*Save coupon amount in wallet */
													if($coupon_response->status == 200){
														$save_coupon_response = apiHelper::manage_registration_time_wallet(Auth::user() ,$coupon_response->price , "Tyre parts coupon discount .");
													}
													/*End*/
												}
										   }  
										   /*End*/
											
											$transaction_response =  DB::transaction(function() use ($request  , $get_package_details  , $special_condition_id , $discount) {
												/*Check order if order is exixst in already cart*/
												$user_last_order = \App\Products_order::save_order($request , $discount , $request->price , NULL);
												/*End*/
												 $service_vat = orderHelper::calculate_vat_price($request->price);
												 $after_discount_price = ( $service_vat +  $request->price ) - $discount;
												//  $save_response = \App\ServiceBooking::create(
												 $save_response = \App\ServiceBooking::updateOrCreate(
													 										[
																								'users_id'=>Auth::user()->id,
																								'product_order_id'=>(int) $user_last_order->id,
																								'workshop_user_id'=>$request->workshop_id,
																								'product_id'=>$request->products_id,
																								'services_id'=>$request->service_id,
																							],
													 										
													 										['users_id'=>Auth::user()->id,
																							'product_order_id'=>(int) $user_last_order->id,
																							'workshop_user_id'=>$request->workshop_id,
																							'workshop_address_id'=>$request->address_id,
																							'services_id'=>$request->service_id,
																							'special_condition_id'=>$special_condition_id,
																							'booking_date'=>$request->selected_date,
																							'workshop_user_days_id' =>$get_package_details->workshop_user_days_id,
																							'workshop_user_day_timings_id'=>$get_package_details->id,
																							'start_time'=>$request->start_time,
																							'end_time'=>$request->end_time,
																							'price'=>$request->price,
																							'service_vat'=>$service_vat,
																							'product_id'=>$request->products_id,
																							'after_discount_price'=>$after_discount_price,
																							'discount'=>$discount,
																							'status'=>'P',
																							'type'=>4,
																					]); 
												//$save_response = 1;
												if($save_response){
													/*Products details */
													$tyre_details = Tyre24::find($request->products_id);
													if($tyre_details != NULL){
														$tyre_detail = kromedaDataHelper::arrange_tyre_detail($tyre_details);
													}
													/*End*/
													/*save products response*/
													$product_discount = 0;
													/*calculate tyre total price*/
													$vat_tax_price = orderHelper::calculate_vat_price($request->product_total_price);
													$after_discount_product_price = ( $vat_tax_price + $request->product_total_price )  - $product_discount;
													$final_order_price = $after_discount_product_price; 
													/*End*/
													//$total_product_price = $request->product_price +  
													$save_product_response =  ['users_id'=>Auth::user()->id , 
																				  'seller_id'=>$request->seller_id,
																				  'products_orders_id'=>(int) $user_last_order->id,
																				  'product_image'=>$request->product_image,
																				  'product_image_url'=>$tyre_detail->imageUrl, 
																				  'products_id'=>$request->products_id,
																				  'product_name'=>$request->product_name,
																				  'product_quantity'=>$request->quantity,
																				 /*pfu add for single products*/ 
																				  'pfu_tax'=>$request->pfu_tax,
																				  'product_description'=>$request->product_description,
																				  'vat'=>$vat_tax_price,
																				  'coupons_id'=>$request->coupon_id,
																				  'price'=>$request->product_price,
																				  'total_price'=>$request->product_total_price,   
																				  'discount'=>$product_discount,
																				  'final_order_price'=>$final_order_price,
																				  'status'=>'P',
																				  'for_assemble_service'=>1,
																				  'for_order_type'=>2,
																				  'service_booking_id'=>$save_response->id
																			   ];
													   //$save_product_response =  DB::table('products_order_descriptions')->insert($save_product_response);
													   $save_product_response =  \App\Products_order_description::create(/*[ 'products_id' => $request->products_id, 'users_id' => Auth::user()->id, 'products_orders_id' => (int) $user_last_order->id, 'for_order_type' => 2, 'for_assemble_service' => 1,  'service_booking_id' => $save_response->id]*/$save_product_response);
													   if($save_product_response){
															$user_last_order = \App\Products_order::save_order($request , $product_discount , $request->product_total_price , NULL , $after_discount_product_price);
														   return ['status'=>200 , 'response'=>$save_response];
													   }else{
														return ['status'=>100];
													   }
												}	
												else{
													return ['status'=>100];
												}	
											});
												if(count($transaction_response) > 0){
													return sHelper::get_respFormat(1 , "Service booking successfull  !!!" ,$transaction_response['response']  , null); 
												}	
												else{
													return sHelper::get_respFormat(0 , "This time is already busy !!!" , null , null); 
												}

										}
										else{
											return sHelper::get_respFormat(0 , "This time is already busy !!!" , null , null); 
										}
									}
									else{
										return sHelper::get_respFormat(0 , "Something went wrong  , please try again !!!" , null , null); 
									}
								}
							    }
								else{
								return sHelper::get_respFormat(0 , "Please select correct package id ." , null , null); 
								} 
							}
							else{
								return sHelper::get_respFormat(0 , "Please check you end time !!! ." , null , null);  
							}
						}
						else{
						return sHelper::get_respFormat(0 , "please check you start time !!! ." , null , null);   
						}
					}
					else{
						return sHelper::get_respFormat(0 , " package is not defined !!! ." , null , null);   
					}
				}else{
						return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
				}
		}

		
		
		public function remove_tyre_detail($id = NULL){
			if(Auth::check()){
				if(!empty($id)){
					$tyre_detail = \App\User_tyre_detail::where([['id' , '=' , $id] , ['user_id' , '=' , Auth::user()->id]])->first();
					if($tyre_detail != FALSE){
						$tyre_detail->deleted_at = now();
						if($tyre_detail->save()){
						 return sHelper::get_respFormat(1 , "Record delete successful !!!" , null , null);    
						}else{
						 return sHelper::get_respFormat(0 , "You are not delete this tyre detail !!!" , null , null);    
						}
					}
					else{
					 return sHelper::get_respFormat(0 , "You are not delete this tyre detail !!!" , null , null);   
					}
				}
			} else {
			 return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
			}	
		}

}
