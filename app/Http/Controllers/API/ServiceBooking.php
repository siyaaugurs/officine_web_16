<?php
	namespace App\Http\Controllers\API;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use sHelper;
	use App\Workshop_user_day_timing;
	use App\Http\Controllers\API\SpecialCondition;
	use App\Model\UserDetails;
	use App\Http\Controllers\Coupon;
	use kromedaDataHelper;
	use Auth;
	use App\Library\apiHelper;
	use App\Library\orderHelper;
	class ServiceBooking extends Controller{

		public function assemble_service_booking(Request $request){
			$coupon_obj = new Coupon;
			$validator = \Validator::make($request->all(), [
				'product_id'=>'required|numeric' , 'package_id'=>'required' , 'start_time'=>'required' , 'end_time'=>'required', 'selected_date'=>'required','price'=>'required',
				'main_category_id'=>'required|numeric','product_id'=>'required' ,'price'=>'required','quantity'=>'required','product_total_price'=>'required'
			]);
			if($validator->fails()){
				return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
			}
			if(Auth::check()){
				$s_time = sHelper::change_time_formate($request->start_time);
				$e_time = sHelper::change_time_formate($request->end_time); 
				$get_package_details = Workshop_user_day_timing::find($request->package_id);
				$request->workshop_id = $get_package_details->users_id;
				
				if($get_package_details != NULL){
					/*Price and max Appointment*/
					$workshop_details = collect();
					$workshop_details->id = $get_package_details->users_id;
					$workshop_service_details = apiHelper::get_assemble_workshop_details($workshop_details , $request->main_category_id);
					//$workshop_service_price = \App\WorkshopAssembleServices::find_workshop_price($get_package_details->users_id , $request->main_category_id);
					/*End*/
					//
					//check time and date from current date
					$current_time_zones = sHelper::get_current_time_zones($request->ip());
					date_default_timezone_set($current_time_zones);
					$today_current_date_time = date('Y-m-d H:i');
					$service_date_time = $request->selected_date." " .$request->start_time;
					if($service_date_time < $today_current_date_time){
						return sHelper::get_respformat(0 , "Please select correct booking date for booking , you not booked services in past time ." , null , null );

					}
					$special_condition = \App\Service_special_condition::get_special_condition(2 ,$get_package_details->users_id);  
					if($special_condition != NUll){
						$special_condition_value =[];
						$condition_status = 1;
						foreach($special_condition as $special_conditions){
							if($special_conditions->operation_type == 1){
								if(!empty($special_conditions->all_services != 1)){	
									if($special_conditions->category_id != $request->product_id){
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
								$count_booked_appointment = \App\ServiceBooking::count_car_booked_special_package($request->package_id , $special_conditions->workshop_id , $special_conditions->id  , 2); 
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
						if($s_time >= $get_package_details->start_time && $s_time <= $get_package_details->end_time){
							if($e_time >= $get_package_details->start_time && $e_time <= $get_package_details->end_time){
								if(empty($workshop_service_details['max_appointment'])){ 
									$max_appointment = 1; 
								} else { 
									$max_appointment = $workshop_service_details['max_appointment']; 
								}
								if($get_package_details != NULL){
									/*Count booked Appointment*/
									$count_booked_appointment = \App\ServiceBooking::get_booked_assembly_package($request->package_id , $request->selected_date , $request->main_category_id , 2);
									if($count_booked_appointment->count() == $max_appointment){
										return sHelper::get_respFormat(0 , " All appointment of this package is completely booked !!! ." , null , null); 
									}
									/*End*/
									$get_busy_workshop = \App\ServiceBooking::get_busy_hour_for_assemble($request , $get_package_details , $request->main_category_id);
									if($get_busy_workshop->count() < 1){
										/*End*/
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
										if(!empty($request->coupon_id)){
											$coupon_response = json_decode($coupon_obj->check_coupon_validity($request->coupon_id , $request->selected_date , $request->price));
											if($coupon_response->status != 200){
												return sHepler::get_respFormat(0,$coupon_response->msg ,null, null);
											}else{
												if($coupon_response->status == 200){
													// save amount in suer wallet
													$save_amount = apiHelper::manage_registration_time_wallet(Auth::user(), $coupon_response->price , "Assemble service coupon");
												}
											}
										}
										//$total_order_price = $request->price + $request->product_total_price;
										/*End*/
										//$product_details = \App\ProductsNew::get_product_detail($request->product_id);
										//if($product_details != NULL){	
											//$product_detail = kromedaDataHelper::arrange_spare_product($product_details);
										//}
										$find_product_info = \App\ProductsNew::find($request->product_id);
										if($find_product_info != NULL){
										$spare_product_detail = kromedaDataHelper::arrange_spare_product($find_product_info);
										}
										$order_manage = \App\Products_order::save_order($request,0 , 0  ,null ,0);
										if($order_manage != NULL){
												$request->order_id = $order_manage->id; 
										}
										$service_vat = orderHelper::calculate_vat_price($request->price);	
										$after_discount_price = ($service_vat + $request->price)- $discount_price;									
										$booking_result = \App\ServiceBooking::add_assemble_service_booking($request , $get_package_details,$request->main_category_id ,$discount_price,$special_id, $service_vat , $after_discount_price);
										/*manage order id*/
										//$order_manage = \App\Products_order::save_order($request , $request->discount , $request->product_total_price , 1,$after_discount_price);
										/*save products response*/
										$product_discount = 0;
										/*calculate tyre total price*/
										$vat_tax_price = orderHelper::calculate_vat_price($request->product_total_price);
										$after_discount_product_price = ( $vat_tax_price + $request->product_total_price )  - $product_discount;
										$final_order_price = $after_discount_product_price; 
										/*End*/
										$save_product_order = \App\Products_order_description::create([ 'users_id'=>Auth::user()->id ,'products_orders_id' => $request->order_id ,
										'products_id' =>$request->product_id,
										"product_image_url" => $spare_product_detail->image,
										"product_name" =>$spare_product_detail->products_name1,
										"product_description" =>$spare_product_detail->kromeda_description,
										"product_quantity" => $request->quantity,
										'pfu_tax' => $request->pfu_tax,
										'coupons_id' =>$request->coupon_id,
										'price' =>$request->product_price,
										'total_price' =>$request->product_total_price,
										'discount'=>$product_discount,
									    'final_order_price'=>$final_order_price,
										'for_order_type' => 1,
										'status' =>'P',
										'vat' =>$vat_tax_price,
										'for_assemble_service' =>1,
										'service_booking_id'=>$booking_result->id,
										]);
										//$user_last_order = \App\Products_order::save_order($request , $product_discount , $request->product_total_price , NULL , $after_discount_product_price);				  
									if($booking_result){
										return sHelper::get_respFormat(1 , "Booking successfully !!! " , $booking_result ,null ); 
									} else{
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
			} else{
				return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
			}
		}
		//Car revision service booking
		public function car_revision_service_booking(Request $request){
			$coupon_obj = new Coupon;
			$validator = \Validator::make($request->all(), [
				'service_id'=>'required|numeric' , 'package_id'=>'required' , 'start_time'=>'required' , 'selected_date'=>'required','price'=>'required',
				'workshop_id'=>'required|numeric' 
			]);
			if($validator->fails()){
				return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
			}
			if(Auth::check()){
			$s_time = sHelper::change_time_formate($request->start_time);
			$e_time = sHelper::change_time_formate($request->end_time);
			//check time and date from current date
			$current_time_zones = sHelper::get_current_time_zones($request->ip());
			date_default_timezone_set($current_time_zones);
			$today_current_date_time = date('Y-m-d H:i');
			$service_date_time = $request->selected_date." ".$request->start_time;
			if($service_date_time < $today_current_date_time){
				return sHelper::get_respFormat(0 , "Please select correct booking date for booking , you not booked services in past time ." , null , null);
			}
			$special_condition = \App\Service_special_condition::get_special_condition(3 ,$request->workshop_id);   			
            if($special_condition != NUll){
				$special_condition_value =[];
				$special_condition_apply_status = 0;
                foreach($special_condition as $special_conditions){
					if($special_conditions->operation_type == 1){
						if(!empty($special_conditions->all_services != 1)){	
							if($special_conditions->category_id != $request->service_id){
								$condition_status = 0;
							}else{
								$condition_status = 1; 
							}	
						}else{
							$condition_status = 1;
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
						$count_booked_appointment = \App\ServiceBooking::count_car_booked_special_package($request->package_id , $special_conditions->workshop_id , $special_conditions->id  , 3); 
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
				$get_package_details = Workshop_user_day_timing::find($request->package_id);
				if($get_package_details != NULL){
					/*Price and max Appointment*/
					$car_revision_details = collect();
					$car_revision_details->id = $get_package_details->users_id;
					$car_revision_service_details = \App\WorkshopCarRevisionServices::get_service_price($request->service_id , $car_revision_details->id);
                    if($car_revision_service_details == NULL) {
                        $car_revision_service_details = \App\WorkshopServicesPayments::get_service_price_max($car_revision_details->id, 2);
					}
					/*End*/
					/*Get Workshop Service Appointment and hourly rate*/
					if($s_time >= $get_package_details->start_time && $s_time <= $get_package_details->end_time){
						/* if($e_time >= $get_package_details->start_time && $e_time <= $get_package_details->end_time){ */
						if(empty($car_revision_service_details->max_appointment)){ 
							$max_appointment = 1; 
						} else { $max_appointment = $car_revision_service_details->max_appointment; 
							
						}
							if($get_package_details != NULL){
								/*Count booked Appointment*/
							$count_booked_appointment = \App\ServiceBooking::get_booked_car_revision_package($request->package_id , $request->selected_date , $request->service_id , 3);
							if($count_booked_appointment->count() == $max_appointment){
									return sHelper::get_respFormat(0 , " All appointment of this package is completely booked !!! ." , null , null); 
							} else {
								$discount_price = 0;
								$special_id = 0;	
								if(!empty($special_condition_value)){
									// find discount for rp/per
									$special_id = $special_condition_value->id;
									if($special_condition_value->discount_type == 1){
										$discount_price = $special_condition_value->amount_percentage;
									} else {
										$discount_price = ($request->price/ 100) * $special_condition_value->amount_percentage;
										//$discount_price = $request->price - $total_price;
									}
								}
								$get_busy_workshop = \App\ServiceBooking::get_busy_hour_for_revision($request , $get_package_details , $request->main_category_id);
								if($get_busy_workshop == NULL){

								
									// if(!empty($request->coupon_id) && !empty($special_condition_value)){
									// 		$get_coupon_amount = \App\Coupon::get_coupon_info($request->coupon_id);
									// 		$discount_price = $discount_price - $get_coupon_amount->amount;
									// }elseif(!empty($request->coupon_id)){
									// 		$get_coupon_amount = \App\Coupon::get_coupon_info($request->coupon_id);
									// 		$discount_price = $request->price - $get_coupon_amount->amount;	
									// }
									//add coupon in user wallet in car washing service
									if(!empty($request->coupon_id)){
										$coupon_response = json_decode($coupon_obj->check_coupon_validity($request->coupon_id,$request->selected_date,$request->price));
										if($coupon_response->status != 200){
											return sHelper::get_respFormat(0,$coupon_response->msg,null,null);
										} else {
											//save service coupon amount in user wallet
											if($coupon_response->status == 200){
												$save_coupon_amount = apiHelper::manage_registration_time_wallet(Auth::user(),$coupon_response->price,"Car Revision service coupon.");
											}
										}
									}//end
									$order_manage = \App\Products_order::save_order($request ,0,0,null);
									if($order_manage){
										$request->order_id = $order_manage->id;
									}
									$service_vat = orderHelper::calculate_vat_price($request->price);
									$after_discount_price = ($service_vat + $request->price) - $discount_price;
									
									$booking_result = \App\ServiceBooking::add_car_revision_service_booking($request , 
									$get_package_details , $special_id ,$discount_price ,$service_vat ,$after_discount_price);
									//$order_manage = \App\Products_order::save_order($request ,$discount_price,$request->price, null,$after_discount_price);
						
									if($booking_result){
										return sHelper::get_respFormat(1 , "Booking successfully !!! " , $booking_result ,null ); 
									} else{
										return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
									}
								}else{
									return sHelper::get_respFormat(0 , "Time is busy !!" , null , null);
								}
								
								}
							/*End*/
						} else{
							return sHelper::get_respFormat(0 , "Please select correct package id ." , null , null); 
						}
					/* } else{
						return sHelper::get_respFormat(0 , "please check you end time !!! ." , null , null);   
					} */
					} else{
						return sHelper::get_respFormat(0 , "please check you start time !!! ." , null , null);   
					}
				} else{
					return sHelper::get_respFormat(0 , " package is not defined !!! ." , null , null);    
				}
			} else{
				return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
			}
		}
	}

