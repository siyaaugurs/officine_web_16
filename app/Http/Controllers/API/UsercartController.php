<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use sHelper;
use kromedaDataHelper;
use App\Model\UserDetails;
use App\User;
use App\Gallery;
use App\Address;
use apiHelper;
use app\library\orderHelper;
use Illuminate\Support\Facades\Auth;
use DB; 
use Hash;

class UsercartController extends Controller{
	
	//get coustmer profile
	public function get_user_profile(Request $request){
		if(Auth::user()->id){
			$user_profile = [];
			$user_profile['user_details'] = DB::table('users')->where([['roll_id' ,'=',3], ['id', '=', Auth::user()->id] ,['users_status', '=' , 'A']])->get();
			$user_profile['user_address'] = DB::table('addresses')->where([['users_id', '=', Auth::user()->id] ,['status', '=' , 1]  ,['is_deleted' , '=' , 0]])->orderby('id','desc')->get();
			$user_profile['user_contact'] = DB::table('common_mobiles')->where([['users_id', '=', Auth::user()->id],['deleted_at' , '=' , NULL] ])->orderby('id','desc')->get();
			$user_profile['user_wallet'] = DB::table('userwallets')->where([['user_id', '=', Auth::user()->id]])->first();
		if($user_profile['user_wallet'] != NULL){
			$user_profile['user_wallet']->wallet_history = DB::table('userwallet_histories')->where([['user_id', '=', Auth::user()->id]])->get();
		} 
		  return sHelper::get_respFormat(1 , "User Detail !!" , $user_profile , null);
		} else {
		  return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
		}
	}
	//update coustmer profile
	public function update_coustmer_profile(Request $request){
		if(Auth::user()->id){
			if($request->profile_pic != NULL){
				$image = $this->profile_upload_image($request);
				$update_profile = \App\User::save_profile_data($request,$image);
			} else {
				$image = null;
				$update_profile = \App\User::save_profile_data($request,$image);
			}
			return sHelper::get_respFormat(1 , "Update user profile !!" , null , null);
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
		}
	}
	//update address profile
	public function update_coustmer_address(Request $request){
		if(Auth::user()->id){
			$update_profile = \App\Address::save_profile_address($request);
			return sHelper::get_respFormat(1 , "Update user Address !!" , null , null);
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
		}
	}	
	//update coustomer contact
	public function update_coustmer_contact(Request $request){
		if(Auth::user()->id){
			$update_profile = \App\Common_mobile::save_profile_contact($request);
			return sHelper::get_respFormat(1 , "Update user Contact !!" , null , null);
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
		}
	}
		//update coustomer change password
	public function update_coustmer_change_password(Request $request){
		$validator = \Validator::make($request->all(), [
		      'old_password'=>'required' , 'new_password'=>'required' , 'confirm_password'=>'required'
		 ]);
		if($validator->fails()){
            return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		}
		if(Auth::user()->id){
			$old_password =  $request->old_password;
			$user = DB::table('users')->where([['id', '=',Auth::user()->id]])->first();
			if (Hash::check($old_password, $user->password)) {
				$new_password = $request->new_password;
				$confirm_password = $request->confirm_password;
			if($new_password == $confirm_password){
				$update_profile = \App\User::save_password($new_password);
				return sHelper::get_respFormat(1 , "Password change successfully.", null , null);	
			} else {
				return sHelper::get_respFormat(0 , "Please check your Confirm Password !!!", null , null);	
			}
			} else {
				return sHelper::get_respFormat(0 , "Please correct your current Password !!!", null , null);		
			}
		} else {
				return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
		}
	}
	//upload image folder 
	public function profile_upload_image($request){
	    $notification = public_path('storage/profile_image/');
		if(!is_dir($notification)){ 
			mkdir($notification, 0755 , true); 
		}
		if(!empty($request->profile_pic)){
			$fileName = md5(time().uniqid()).".".$request->file('profile_pic')->getClientOriginalExtension();
			$extension = $request->file('profile_pic')->getClientOriginalExtension();
			if(in_array($extension , $this->image_ext)){
				$request->file('profile_pic')->move($notification , $fileName);
				return $fileName;
			} else { 
				return 111; 
			}	
		} else { 
				return $request->file;  
		}  
	}
	public function add_user_contact_list(Request $request){
		if(Auth::user()->id){
			$add_contact = \App\Common_mobile::add_user_contact($request);
			return sHelper::get_respFormat(1 , "Add Contact Successfully.", null , null);
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
		}
	}
	public function delete_user_contact_list(Request $request){
		if(Auth::user()->id){
			$mobile_detail = \App\Common_mobile::where([['users_id','=' ,Auth::user()->id],['id','=',$request->contact_id]])->first(); 
			$mobile_detail->deleted_at = now();
			$mobile_detail->save();
			return sHelper::get_respFormat(1 , "Delete contact Successfully.", null , null);
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);
		}
	}
	//delete user address 
	public function delete_user_address(Request $request){
		if(Auth::user()->id){
			$mobile_detail = \App\Address::where([['users_id','=' ,Auth::user()->id],['id','=',$request->address_id]])->first(); 
			$mobile_detail->is_deleted = 1;
			$mobile_detail->save();
			return sHelper::get_respFormat(1 , "Delete contact Successfully.", null , null);
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);
		}	
	}

	//add user address
	public function add_user_address_list(Request $request){
		if(Auth::user()->id){
			$add_contact = \App\Address::add_profile_address($request);
			return sHelper::get_respFormat(1 , "Add Address Successfully.", null , null);
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
		}
	}  
	
	//get all type service name
	public  function get_service_details($type, $service_id) {
		if($type == 1 || $type == 3 || $type == 4) {
			$service = NULL;
			if($service_id != NULL) {
				$service_name = \App\Category::get_service_category($service_id);
				$image = NULL;
				if($service_name  != NULL){
					$service_images = \App\Gallery::get_service_category_image($service_name->id);
					if(count($service_images) > 0) {
						$image = $service_images[0]['image_url'];
					}
					$service_name = ['service_name' =>$service_name->category_name , 'cat_image_url' =>$image ,'price' =>$service_name->price];
				}
				return $service_name;
			}
		}
		if($type == 2 || $type == 7) {
		//$service_name = \App\Servicequotes::get_service_quotes_name($service_id);
			//$service_name = \App\MainCategory::get_assemble_details($service_id);
			//$service_name = ['service_name' =>$service_name->main_cat_name , 'cat_image_url' =>null ,'price' =>null];	
			//return $service_name;
			$service_name =  \App\MainCategory::find($service_id);
			if($service_name != NULL){
				$service_name = ['service_name' =>$service_name->main_cat_name , 'cat_image_url' =>null ,'price' =>null];	
				return $service_name;
			}
		}
		// if($type == 4){
		// 	$service_name = \App\Tyre24::get_tyre_details($service_id);
		// 	$TyreResponse = json_decode($service_name->tyre_response);
		// 	$service_name = ['service_name' =>$TyreResponse->description , 'cat_image_url' =>$TyreResponse->imageUrl ,'price' =>$TyreResponse->price];	
		// 	return $service_name;
		// }
		if($type == 5){
			$service_name = DB::table('items_repairs_servicestimes')->where([['id' ,'=',$service_id]])->first();
			$service_name =['service_name' => $service_name->item." ".$service_name->front_rear." ".$service_name->left_right ,'cat_image_url' =>null ,'price' =>null];
			//$service_name = \App\ItemsRepairsServicestime::get_item_repair_details($service_id);
			//$service_name = ['service_name' =>$service_name->action_description , 'cat_image_url' =>null ,'price' => null];	
			return $service_name;
		}
		if($type == 8){
			$service_name = \App\Our_mot_services::get_mot_details($service_id);
			$service_name = ['service_name' =>$service_name['service_name'] , 'cat_image_url' =>null ,'price' => null];	
			return $service_name;
		}
		if($type == 6){
			$service_name = \App\WrackerServices::get_wrecker_service($service_id);
			$images = NULL;
			if(!empty($service_name)) {
				$service_images = \App\Gallery::get_wrecker_images($service_name->id);
				if(count($service_images) > 0) {
					$images = $service_images[0]['image_url'];
				}
			}
			$service_name = ['service_name' =>$service_name->services_name , 'cat_image_url' =>$images ,'price' => null];	
			return $service_name;
		}
		//if($type == 7){
		//	$service_name = \App\Servicequotes::get_service_quotes_name($service_id);
		//	$service_name = ['service_name' =>$service_name->text , 'cat_image_url' =>null ,'price' => null];	
			//return $service_name;			
		//}
	}
	public function check_service_avilability($services) {
		$status = 1;
		$check_package_timing = DB::table('service_bookings')->where([['workshop_user_day_timings_id', '=', $services->workshop_user_day_timings_id], ['start_time', '=', $services->start_time], ['end_time', '=', $services->end_time], ['status', '=', 'C']])->first();
		if(!empty($check_package_timing)) {
			$status = 0;
		}
		if($services->type == 1) {
			$service_details = DB::table('services')->where([['users_id' , '=' , $services->workshop_user_id] , ['category_id' , '=' , $services->services_id] , ['car_size' , '=' , $services->car_size]])->first();

			$get_all_bookings = DB::table('service_bookings')->where([['type', '=', $services->type], ['car_size', '=', $services->car_size],['workshop_user_id', '=', $services->workshop_user_id], ['services_id', '=', $services->services_id],['booking_date', '=', $services->booking_date]])->get();
			if($service_details != NULL) {
				if($service_details->max_appointment < count($get_all_bookings)) {
					$status = 0;
				}
			}
		}
		if($services->type == 3) {
			$service_details = \App\WorkshopCarRevisionServices::where([['workshop_id' , '=' , $services->workshop_user_id] , ['category_id' , '=' , $services->services_id]])->first();

			$get_all_bookings = DB::table('service_bookings')->where([['type', '=', $services->type],['workshop_user_id', '=', $services->workshop_user_id], ['services_id', '=', $services->services_id],['booking_date', '=', $services->booking_date]])->get();
			if($service_details != NULL) {
				if($service_details->max_appointment < count($get_all_bookings)) {
					$status = 0;
				}
			}
			
		}
		if($services->type == 4) {
			$service_details = \App\WorkshopTyre24Details::where([['workshop_user_id' , '=' , $services->workshop_user_id] , ['category_id' , '=' , $services->services_id]])->first();

			$get_all_bookings = DB::table('service_bookings')->where([['type', '=', $services->type],['workshop_user_id', '=', $services->workshop_user_id], ['services_id', '=', $services->services_id],['booking_date', '=', $services->booking_date]])->get();
			if($service_details != NULL) {
				if($service_details->max_appointment < count($get_all_bookings)) {
					$status = 0;
				}
			}

		}
		if($services->type == 5) {
			$service_details = \App\WorkshopCarMaintinanceServiceDetails::where([['items_repairs_servicestimes_id', '=', $services->services_id], ['workshop_id', '=', $services->workshop_user_id ]])->first();

			$get_all_bookings = DB::table('service_bookings')->where([['type', '=', $services->type],['workshop_user_id', '=', $services->workshop_user_id], ['services_id', '=', $services->services_id],['booking_date', '=', $services->booking_date]])->get();
			if($service_details != NULL) {
				if($service_details->max_appointment < count($get_all_bookings)) {
					$status = 0;
				}
			}
		}
		if($services->type == 6) {
			if($services->wrecker_service_type == 1) {
				$wracker_service = DB::table('workshop_wrecker_services')->where([['wracker_services_id', '=', $services->services_id], ['users_id', '=', $services->workshop_user_id]])->first();
				
				$service_details = DB::table('workshop_wrecker_service_details')->where([['workshop_wrecker_services_id', '=', $wracker_service->id], ['wrecker_service_type', '=', 1]])->first();

				$get_all_bookings = DB::table('service_bookings')->where([['type', '=', $services->type],['workshop_user_id', '=', $services->workshop_user_id], ['services_id', '=', $services->services_id], ['wrecker_service_type', '=', $services->wrecker_service_type],['booking_date', '=', $services->booking_date]])->get();

				if($service_details != NULL) {
					if($service_details->max_appointment < count($get_all_bookings)) {
						$status = 0;
					}
				}
			}
		}
		if($services->type == 7) {
			$service_details = DB::table('service_quotes_details as a')->where([['main_category_id', '=', $services->services_id], ['user_id', '=', $services->workshop_user_id ]])->first();

			$get_all_bookings = DB::table('service_bookings')->where([['type', '=', $services->type],['workshop_user_id', '=', $services->workshop_user_id], ['services_id', '=', $services->services_id],['booking_date', '=', $services->booking_date]])->get();
			if($service_details != NULL) {
				if($service_details->max_appointment < count($get_all_bookings)) {
					$status = 0;
				}
			}
		}
		if($services->type == 8) {
			if($services->mot_service_type = 1) {
				$service_details = \App\WorkshopMotServiceDetails::where([['workshop_id' , '=' ,$services->workshop_user_id] , ['service_id', '=', $services->services_id] , ['type' , '=' , 1]])->first();

				$get_all_bookings = DB::table('service_bookings')->where([['type', '=', $services->type],['workshop_user_id', '=', $services->workshop_user_id], ['services_id', '=', $services->services_id], ['mot_service_type', '=', $services->mot_service_type],['booking_date', '=', $services->booking_date]])->get();
				if($service_details != NULL) {
					if($service_details->max_appointment < count($get_all_bookings)) {
						$status = 0;
					}
				}

			}
			if($services->mot_service_type == 2) {
				$service_details = \App\WorkshopMotServiceDetails::where([['workshop_id' , '=' ,$services->workshop_user_id] , ['service_id', '=', $services->services_id] , ['type' , '=' , 2]])->first();

				$get_all_bookings = DB::table('service_bookings')->where([['type', '=', $services->type],['workshop_user_id', '=', $services->workshop_user_id], ['services_id', '=', $services->services_id], ['mot_service_type', '=', $services->mot_service_type],['booking_date', '=', $services->booking_date]])->get();
				if($service_details != NULL) {
					if($service_details->max_appointment < count($get_all_bookings)) {
						$status = 0;
					}
				}
			}

		}
		return $status;
	}

	public function get_cart_list(Request $request){
		if(Auth::user()->id){
			$get_cart_items = \App\Products_order::where([['users_id','=' ,Auth::user()->id] ,['status','=','P'],['deleted_at' , '=' , NULL] ])->get();
			if($get_cart_items->count() >0){
				foreach($get_cart_items as $get_cart_item){
					$get_cart_item->spare_product_description = 0;
					$get_cart_item->tyre_product_description = 0;
					$get_cart_item->service_product_description = 0;
					$get_cart_item->total_price = (string)$get_cart_item->total_price;
					$get_cart_item->total_discount = (string)$get_cart_item->total_discount;
					$get_cart_item->spare_product_description = \App\Products_order_description::spare_product_description($get_cart_item->id); 
					
					if($get_cart_item->spare_product_description->count() >0){
						foreach ($get_cart_item->spare_product_description as $spare_products) {
							$find_product_detail = \App\ProductsNew::find($spare_products->products_id);
							$spare_product_detail = kromedaDataHelper::arrange_spare_product($find_product_detail);
							$spare_products->avilability = 1;
							if($spare_products->product_quantity > $spare_product_detail->products_quantiuty) {
								$spare_products->avilability = 0;
							} 
							$spare_products->final_order_price = (string) $spare_products->final_order_price;
							$spare_products->product_image_url = $spare_product_detail->image;
							$spare_products->max_products_quantity = $spare_product_detail->products_quantiuty;
							$spare_products->price = (string)$spare_products->price;
							$spare_products->discount = (string)$spare_products->discount;
							$spare_products->total_price = (string)$spare_products->total_price;
							//$spare_products->final_order_price = (string) $spare_products->final_order_price;

							$spare_products->pfu_tax = (string)$spare_products->pfu_tax;
						}
					}
					$get_cart_item->tyre_product_description = \App\Products_order_description::tyre_product_description($get_cart_item->id);
					if($get_cart_item->tyre_product_description->count() > 0){	
						foreach ($get_cart_item->tyre_product_description as $tyre_products) {	
							$find_tyre_detail = \App\Tyre24::find($tyre_products->products_id);
							$tyre_product_detail = kromedaDataHelper::arrange_tyre_detail($find_tyre_detail);
							$tyre_products->avilability = 1;
							if($tyre_products->product_quantity > $tyre_product_detail->quantity) {
								$tyre_products->avilability = 0;
							}
							$tyre_products->final_order_price = (string) $tyre_products->final_order_price;
							$tyre_products->max_products_quantity= $tyre_product_detail->quantity;
							$tyre_products->product_image_url = $tyre_product_detail->imageUrl;
							$tyre_products->price = (string)$tyre_products->price;
							$tyre_products->discount = (string)$tyre_products->discount;
							$tyre_products->total_price = (string)$tyre_products->total_price;
							$tyre_products->pfu_tax = (string)$tyre_products->pfu_tax;
						}
					}
					$get_cart_item->service_product_description = \App\ServiceBooking::service_order_description($get_cart_item->id); 
					if($get_cart_item->service_product_description->count() > 0){
							foreach($get_cart_item->service_product_description as $service_product){
								$service_product->avilability = self::check_service_avilability($service_product);
								$product_info = [];
								$service_product->workshop_details = \App\User::get_workshop_details($service_product->workshop_user_id);
								$service_product->workshop_details->profile_image_url = NULL;
								if(!empty($service_product->workshop_details->profile_image )){
									$profile_image = $service_product->workshop_details->profile_image;
									$service_product->workshop_details->profile_image_url = url("public/storage/profile_image/$profile_image");   
								}
								$service_product->assembly_service_product_description = null;

								if($service_product->type == 2){	
									//$service_product->assembly_service_product_description->max_product_quantity = null;
									$service_product->assembly_service_product_description = \App\Products_order_description::spare_product_description_for_assemble($get_cart_item->id);
									if(!empty($service_product->assembly_service_product_description )){
										$service_product->assembly_service_product_description->final_order_price = (string) $service_product->assembly_service_product_description->final_order_price;
										$service_product->assembly_service_product_description->pfu_tax = (string) $service_product->assembly_service_product_description->pfu_tax;
										$service_product->assembly_service_product_description->price = (string) $service_product->assembly_service_product_description->price;
										$service_product->assembly_service_product_description->total_price = (string) $service_product->assembly_service_product_description->total_price;
										$find_product_detail = \App\ProductsNew::find($service_product->assembly_service_product_description->products_id);
										$spare_product_detail = kromedaDataHelper::arrange_spare_product($find_product_detail);
										$service_product->product_image_url = $spare_product_detail->image; 
										$service_product->assembly_service_product_description->max_products_quantity = $spare_product_detail->products_quantiuty;
									}
								}
								if($service_product->type == 4){
									//$service_product->assembly_service_product_description->max_product_quantity = null;
									$service_product->assembly_service_product_description = \App\Products_order_description::tyre_product_description_for_assemble($get_cart_item->id);
									if(!empty($service_product->assembly_service_product_description)){
										$service_product->assembly_service_product_description->final_order_price = (string) $service_product->assembly_service_product_description->final_order_price;
										$service_product->assembly_service_product_description->pfu_tax = (string) $service_product->assembly_service_product_description->pfu_tax;
										$service_product->assembly_service_product_description->price = (string) $service_product->assembly_service_product_description->price;
										$service_product->assembly_service_product_description->total_price = (string) $service_product->assembly_service_product_description->total_price;
										$find_tyre_detail = \App\Tyre24::find($service_product->assembly_service_product_description->products_id);
										$tyre_product_detail = kromedaDataHelper::arrange_tyre_detail($find_tyre_detail);
										$service_product->product_image_url = $tyre_product_detail->imageUrl;
										$service_product->assembly_service_product_description->max_products_quantity= $tyre_product_detail->quantity;
									}
								}
								$service_product->quantity = (int)$service_product->quantity;
								$service_product->price = (string)$service_product->price;
								$service_product->after_discount_price = (string)$service_product->after_discount_price;	
								$service_product->service_detail = $this->get_service_details($service_product->type,$service_product->services_id);
							}
					}
				}
				return sHelper::get_respformat(1,"show user cart item", null,$get_cart_items);

			} else {
				return sHelper::get_respFormat(0 , "No product item", null , null);
			}	
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
		}
	}

	public function remove_cart_items(Request $request) {
		if(Auth::user()->id){
			$order_details = \App\Products_order::where([['status', '=', 'P'], ['users_id', '=', Auth::user()->id]])->first();
			if($order_details != NULL) {
				$get_all_orders = \App\Products_order_description::where([['products_orders_id', '=', $order_details->id], ['status', '=', 'P'], ['users_id', '=', Auth::user()->id]])->get();
				if($get_all_orders->count() > 0) {
					foreach($get_all_orders as $orders) {
						$orders->delete();
					}
				}
			}
			$order_details->delete();
			return sHelper::get_respFormat(1, "Cart Items Removed Successfully", null, null);
		} else {
			return sHelper::get_respFormat(0,"Unauthenticate , please login first.",null,null);
		}
	}
	
	public function add_to_cart(Request $request){
		if(Auth::user()->id){
			$validator = \Validator::make($request->all(), [
				'product_id'=>'required' , 'price'=>'required' ,'product_quantity'=>'required'
			]); 
			if($validator->fails()){
				return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
			}
			$order_manage = \App\Products_order::save_order($request ,0 , 0);
			if($order_manage !=  NULL ){
				$request->order_id = $order_manage->id;
			}
			$product_info = \App\ProductsNew::where([['id', '=', $request->product_id]])->first();
			$product_image = sHelper::get_product_image($request->product_id);
			$product_vat = orderHelper::calculate_vat_price($request->total_price); 
			// $after_discount_price = (($product_vat + $request->price) * $request->product_quantity) - $request->discount;
			$after_discount_price = (($product_vat + $request->total_price) - $request->discount);
			if($request->for_order_type == 2) {
				$single_product_price = ($request->price + $request->pfu_tax + $product_vat);
			} else {
				$single_product_price = ($request->price + $product_vat);
			}
			$insert_data = \App\Products_order_description::updateOrCreate(['products_id' => $request->product_id, 'users_id'=> Auth::user()->id, 'products_orders_id' => $order_manage->id, 'for_order_type' => $request->for_order_type, 'for_assemble_service' => NULL],['products_orders_id'=> $request->order_id,
				'product_quantity'=> $request->product_quantity,
				'vat' =>$product_vat,
				'final_order_price' =>$after_discount_price,
				'pfu_tax'=>$request->pfu_tax,
				'status'=>'P',
				'price'=>$request->price,
				'total_price'=>$request->total_price,
				'for_order_type'=>$request->for_order_type,
				'coupons_id'=>$request->coupons_id,
				'discount'=>$request->discount,
				'products_id'=>$request->product_id,
				'product_name'=> $request->product_name,
				'product_description'=>$request->product_description,
				'product_image_url'=>$product_image,
				'single_product_calculate_price'=>$single_product_price,
				'users_id'=>Auth::user()->id,
			]);
			$update_wish_list = \App\User_wish_list::where([['user_id' ,'=',Auth::user()->id],['product_type' ,'=', $request->for_order_type],['product_id','=' ,$request->product_id] , ['deleted_at' , '=' ,NULL]])->first();
			if($update_wish_list != NULL){
				$update_wish_list->deleted_at = now();
				$update_wish_list->save();
			}
			return sHelper::get_respFormat(1,"add To cart successfully !!!",$insert_data,null);
		} else {
			return sHelper::get_respFormat(0,"Unauthenticate , please login first.",null,null);
		}	
	}
	
	//click checkout
	public function check_user_cart_items(Request $request){
		$validator = \Validator::make($request->all(), [
		    'product_order_id' => 'required', 'address_id' => 'required', 'contact_id' => 'required'
		]);
		if($validator->fails()){
            return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		}
		if(Auth::user()->id){
			$order_id = $request->product_order_id;
			$check_orders = \App\Products_order::where([['users_id','=',Auth::user()->id], ['id', '=', $order_id] ,['status' ,'=' ,'P']] )->get();
			if($check_orders->count() >0){
				foreach($check_orders as $check_order){
					$response = DB::table('products_orders')->where('id' , '=' , $check_order->id)->update(['shipping_address_id' => $request->address_id, 'address_type' => 'H', 'contact_id' => $request->contact_id]);
					$get_product_orders = DB::table('products_order_descriptions')->where('products_orders_id',$check_order->id)->get();
					if($get_product_orders->count() >0){
						foreach($get_product_orders as $get_product_order){
							$flag = 1;
							if($get_product_order->for_order_type == 1) {
								$get_product = \App\ProductsNew::where([['id', '=', $get_product_order->products_id]])->first();
								if(!empty($get_product)) {
									$get_product_details = kromedaDataHelper::arrange_spare_product($get_product);
									if($get_product_order->product_quantity > $get_product_details->products_quantiuty) {
										$flag = 0;
									} 
								}
							}
							if($get_product_order->for_order_type == 2) {
								$get_product = \DB::table('tyre24s')->where([['id', '=', $get_product_order->products_id]])->first();
								if(!empty($get_product)) {
									$get_product_details = kromedaDataHelper::arrange_tyre_detail($get_product);
									if($get_product_order->product_quantity > $get_product_details->quantity) {
										$flag = 0;
									}
								}
							}
						}
					}
					$get_book_orders = DB::table('service_bookings')->where('product_order_id',$check_order->id)->get();
					if($get_book_orders->count() >0){
						foreach($get_book_orders as $get_book_order){
							$check_package_timing = DB::table('service_bookings')->where([['workshop_user_day_timings_id', '=', $get_book_order->workshop_user_day_timings_id], ['start_time', '=', $get_book_order->start_time], ['end_time', '=', $get_book_order->end_time], ['status', '=', 'C']])->first();
							$flag = 1;
							if(!empty($check_package_timing)) {
								$flag = 0;
							}
						}
					}
				}
				if ($flag == 0) {
					return sHelper::get_respFormat(0 , "Product / Service Not Avilable in Stock.", null , null);
				} else {
					return sHelper::get_respFormat(1 , null, null , null);
				}
			} else {
				return sHelper::get_respFormat(0, "please check No order id" ,null,null);
			}
		} else {
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
		}
	}
	
	public function update_product_quantity(Request $request){
		if(Auth::user()->id){
		$product_id = $request->product_id;
		$get_product_info = \App\Products_order_description::where('id',$product_id)->first();
		if($get_product_info != NULL){
		if(!empty($get_product_info->price)){
			$products_order = \App\Products_order::where('id', $get_product_info->products_orders_id)->first();
			$old_total_price = $get_product_info->total_price;
			$new_price_for_product_order = $request->total_price - $old_total_price;
			$new_total_price = $products_order->total_price + $new_price_for_product_order;
			$products_order->total_price = $new_total_price;
			$products_order->save();
		}
		$update_product =\App\Products_order_description::update_product_quantity($request->all());
		//add coupon dicount price in wallet
		$description ='coupon discount';
		$user_wallet = apiHelper::manage_registration_time_wallet(Auth::user(),$products_order->total_discount ,$description);
			return sHelper::get_respFormat(1,"Update successfully!!!",null,null);
		} else {
			return sHelper::get_respFormat(0,"No product there!!!",null,null);
		}
		} else {
			return sHelper::get_respFormat(0,"Unauthenticate , please login first.",null,null);
		}
	}
	//delete user add item
	public function delete_user_add_item(Request $request){
		if(Auth::user()->id){
			if($request->type == 1){
				$products_order_description = \App\Products_order_description::where([['users_id','=' ,Auth::user()->id],['id','=',$request->book_id]])->first(); 
				if($products_order_description['products_orders_id'] != NULL){
					$products_order_description->deleted_at = now();
					$products_order_description->save();
					$products_order = \App\Products_order::where([['users_id','=' ,Auth::user()->id],['id','=',$products_order_description['products_orders_id']]])->first();
					$products_order->no_of_products =  DB::raw('no_of_products - 1');
					$products_order->total_price = $products_order->total_price - $request->total_price;
					$products_order->save();
					return sHelper::get_respFormat(1,"Service delete successfully !!!",null , null);
				}else{
					return sHelper::get_respFormat(0,"No product item !!!",null , null);
				}
			} else {
				$find_product_order_id = \App\ServiceBooking::where([['users_id','=' ,Auth::user()->id],['id','=',$request->book_id]])->first();	
				if($find_product_order_id != NULL){
					$products_order_description = \App\Products_order_description::where([['users_id','=' ,Auth::user()->id],['products_orders_id','=',$find_product_order_id->product_order_id]])->first(); 
				if($products_order_description != NULL){
					$products_order_description->deleted_at = now();
					$products_order_description->save();
					$products_order = \App\Products_order::where([['users_id','=' ,Auth::user()->id],['id','=',$products_order_description->products_orders_id]])->first();
					$products_order->no_of_products =  DB::raw('no_of_products - 1');
					$products_order->total_price = $products_order->total_price - $request->total_price;
					$products_order->save();
				} else {
					$products_order = \App\Products_order::where([['users_id','=' ,Auth::user()->id],['id','=',$find_product_order_id->product_order_id]])->first();
					$products_order->no_of_products =  DB::raw('no_of_products - 1');
					$products_order->total_price = $products_order->total_price - $request->total_price;
					$products_order->save();
				}
				$find_product_order_id->deleted_at = now();
				$find_product_order_id->save();	
				return sHelper::get_respFormat(1,"Service delete successfully !!!",null , null);
			} else {
				return sHelper::get_respFormat(0,"No product item !!!",null , null);
			}
			}
			} else {
				return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
			}
	}

	
	public function get_user_order_details(Request $request){
		if(Auth::user()->id){
			$user_order_details = DB::table('products_orders')->where([['users_id','=',Auth::user()->id],['payment_status' ,'=' ,'C'],['deleted_at','=',NULL]])->orderBy('id' ,'desc')->get();
			if($user_order_details->count() > 0){
				$product_order_detail = [];
				foreach($user_order_details as $user_order){
					$address = Address::find($user_order->shipping_address_id);
					$user_order->address = $address;
					$user_order->spare_product_description = \App\Products_order_description::spare_product_description($user_order->id); 
					if($user_order->spare_product_description->count() >0){
						foreach($user_order->spare_product_description as $spare_products){
							$spare_products->price = (string)$spare_products->price;
							$spare_products->discount = (string)$spare_products->discount;
							$spare_products->pfu_tax = (string)$spare_products->pfu_tax;
							$spare_products->total_price = (string)$spare_products->total_price;	
						}
					}
					$user_order->tyre_product_description = \App\Products_order_description::tyre_product_description($user_order->id);
					if($user_order->tyre_product_description->count() > 0)	{
						foreach($user_order->tyre_product_description as $tyre_products){
							$tyre_products->price = (string)$tyre_products->price;
							$tyre_products->discount = (string)$tyre_products->discount;
							$tyre_products->pfu_tax = (string)$tyre_products->pfu_tax;
							$tyre_products->total_price = (string)$tyre_products->total_price;
						}
					}
					$user_order->service_product_description = \App\ServiceBooking::service_order_description($user_order->id); 
					if($user_order->service_product_description->count() > 0){
						foreach($user_order->service_product_description as $service_product){
							$product_info = [];
							$service_product->workshop_details = \App\User::get_workshop_details($service_product->workshop_user_id);
							$service_product->workshop_details->profile_image_url = NULL;
							if(!empty($service_product->workshop_details->profile_image )){
								$profile_image = $service_product->workshop_details->profile_image;
								$service_product->workshop_details->profile_image_url = url("public/storage/profile_image/$profile_image");   
							}
							$service_product->assembly_service_product_description = null;
							if($service_product->type == 2){
								$service_product->assembly_service_product_description = \App\Products_order_description::spare_product_description_for_assemble($user_order->id); 	
							}
							if($service_product->type == 4){
								$service_product->assembly_service_product_description = \App\Products_order_description::tyre_product_description_for_assemble($user_order->id);
							}
							$service_product->quantity = (int)$service_product->quantity;
							$service_product->price = (string)$service_product->price;
							$service_product->after_discount_price = (string)$service_product->after_discount_price;
							$service_product->service_detail = $this->get_service_details($service_product->type,$service_product->services_id);
							}
						}
					}
					return sHelper::get_respFormat(1,"Show user order details",null , $user_order_details);
				} else {
					return  sHelper::get_respFormat(0,"No Data",null,null);	
				}
			} else {
					return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);	
			}
		}
		
	//update payment status 
	public function update_payment_status(Request $request){
		$validator = \Validator::make($request->all(), [
		    'payment_mode'=>'required','transaction_id'=>'required', 'product_order_id' => 'required', 'amount' => 'required'
		]); 
		if($validator->fails()){
             return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		}
		if(Auth::user()->id){
			$transaction_response =  DB::transaction(function() use ($request) {	
				$get_order_details = \App\Products_order::where([['users_id','=',Auth::user()->id],['status', '=' ,'P'] ])->get();		
				if($get_order_details->count() > 0){
					foreach ($get_order_details as $get_order_detail) {
						$get_order_detail->payment_status = 'C';
						$get_order_detail->order_date = date('Y-m-d');
						$get_order_detail->transaction_id = $request->transaction_id;
						$get_order_detail->payment_mode = $request->payment_mode;
						$get_order_detail->status = 'C';
						$get_order_detail->save();
						$get_all_product = \App\Products_order_description::where([['products_orders_id', '=', $get_order_detail->id],['users_id','=',Auth::user()->id], ['status', '=', 'P']])->whereIn('for_order_type', [1])->get();
						if($get_all_product->count() > 0) {
							foreach($get_all_product as $product) {
								if($product->for_order_type == 1) {
									$find_product = \App\ProductsNew::find($product->products_id);
									$product_details  =  sHelper::get_products_details($find_product);
									if($product_details != NULL) {
										if($product_details->products_quantiuty > $product->product_quantity) {
											$product_details->products_quantiuty = $product_details->products_quantiuty - $product->product_quantity;
											$product_details->save();
										}
									}
								}
								if($product->for_order_type == 2) {
									$find_tyre = \App\Tyre24::find($product->products_id);
									if($find_tyre != NULL) {
										if($find_tyre->quantity > $product->product_quantity) {
											$find_tyre->quantity = $find_tyre->quantity - $product->product_quantity;
											$find_tyre->save();
										}
									}
								}
							}
						}
						$product_detail = \App\Products_order_description::where([['products_orders_id', '=' ,$get_order_detail->id],['users_id','=',Auth::user()->id]])->update(['status' =>'C']);
						$select_sevices = \App\ServiceBooking::where([['product_order_id','=',$get_order_detail->id],['users_id','=',Auth::user()->id]])->update(['status' => 'C']);
					}
					$count_order = \App\Products_order::where([['users_id', '=' ,Auth::user()->id]])->count();	
					if($count_order == 1){
						$get_user_detail = \App\User::where([['id','=', Auth::user()->id]])->first();
						if(!empty($get_user_detail->referel_code)){
							$get_user = \App\User::where('own_referal_code',$get_user_detail->referel_code)->first();
							$Add_user_Amount = apiHelper::manage_registration_time_wallet($get_user ,$request->amount , $request->description);
						}
					}	
					return ['status'=>200];
				} else {
					return ['status'=>100];
				}
			});
			if($transaction_response['status'] == 200){
				return sHelper::get_respformat(1,"Update payment status successfull.",null,null); 
			}
		} else {
			   return sHelper::get_respFormat(0 , "Unauthenticate , please login first.", null , null);
		}
	}
	//user wish list
	public function add_user_wish_list(Request $request){
		if(Auth::user()->id){
			if(!empty($request->workshop_id)){
				$check_workshop = \App\User_wish_list::where([['workshop_id' , '=' ,$request->workshop_id],['user_id' ,'=', Auth::user()->id],['wishlist_type' , '=' ,2] ,['deleted_at' , '=' ,NULL]])->count();
				if($check_workshop != 1){
					$add_wish_list = \App\User_wish_list::create(['user_id' => Auth::user()->id , 'workshop_id' => $request->workshop_id,'wishlist_type' => 2]);  	
				}else{
					return sHelper::get_respFormat(0,"This workshop already insert.",null ,null);	
				}
			} else {
				$check_product = \App\User_wish_list::where([['product_id' , '=' ,$request->product_id],['user_id' ,'=' ,Auth::user()->id],['wishlist_type' , '=' ,1],['product_type' , '=' ,$request->product_type]  ,['deleted_at' , '=' ,NULL]])->count();
				if($check_product != 1){
					$add_wish_list = \App\User_wish_list::create(['user_id' => Auth::user()->id , 'product_id' => $request->product_id,'product_type' => $request->product_type]);  	
				}else{
					return sHelper::get_respFormat(0,"This product already insert.",null ,null);	
				}
			}
		  	return sHelper::get_respFormat(1, "Add to wish list !!!", null , null);
	 	} else {
			return sHelper::get_respFormat(0,"Unauthenticate , please login first.",null ,null);
		}
	}
	//usr wish list
	public function get_user_wish_list(Request $request){
		if(Auth::user()->id){
				$get_user_wish_lists = \App\User_wish_list::select('id','product_id','workshop_id','product_type','wishlist_type')->where([['user_id','=',Auth::user()->id] ,['deleted_at' , '=', NULL]])->get();
					if($get_user_wish_lists->count() >0){
						foreach($get_user_wish_lists as $get_user_wish){
						if($get_user_wish->product_type == 1){
							$get_user_wish->spare_product_detail = \App\User_wish_list::get_user_wish_list_for_product_list($get_user_wish->product_id ,Auth::user()->id ,$get_user_wish->product_type);
							if($get_user_wish->spare_product_detail  !=  NULL){
							$brand_image = \App\BrandLogo::brand_logo($get_user_wish->spare_product_detail->listino);
							if($brand_image != NULL){
								$get_user_wish->spare_product_detail->brand_image = $brand_image->image_url; 
							} 
							$all_feed_back = null;
							$all_feed_back['rating'] = null;
							$all_feed_back['num_of_users'] = null;
							$all_feed_back = \App\Feedback::get_product_rating_list($get_user_wish);
							if($all_feed_back != NULL) {
								$get_user_wish->spare_product_detail->rating = $all_feed_back;
								$get_user_wish->spare_product_detail->rating_star = $all_feed_back['rating'];
								$get_user_wish->spare_product_detail->rating_count = $all_feed_back['num_of_users'];
							}
							$get_user_wish->tyre_product_detail = null;
						 }
						}
						if($get_user_wish->product_type == 2){
							$get_user_wish->tyre_product_detail = \App\User_wish_list::get_user_wish_list_for_product_list($get_user_wish->product_id ,Auth::user()->id ,$get_user_wish->product_type);
						if($get_user_wish->tyre_product_detail   !=  NULL){				
							$tyre_detail = json_decode($get_user_wish->tyre_product_detail->tyre_response);
							$brands = $tyre_detail->manufacturer_description;
							if(!empty($brands)){
								$brand_logo = \App\BrandLogo::brand_logo_tyre($brands);
								if($brand_logo != NULL){
									$get_user_wish->tyre_product_detail->brand_image = (string) $brand_logo->image_url;
								}
							}                                                                                                                                                                                                     
							$get_user_wish->tyre_product_detail->max_width = (string) $get_user_wish->tyre_product_detail->max_width;
							$get_user_wish->tyre_product_detail->max_aspect_ratio = (string) $get_user_wish->tyre_product_detail->max_aspect_ratio;
							$get_user_wish->tyre_product_detail->max_diameter = (string) $get_user_wish->tyre_product_detail->max_diameter;
							$get_user_wish->tyre_product_detail->price = $get_user_wish->tyre_product_detail->manufacturer_description = $get_user_wish->tyre_product_detail->ean_number = $get_user_wish->tyre_product_detail->description =
							$get_user_wish->tyre_product_detail->wholesalerArticleNo = $get_user_wish->tyre_product_detail->extRollingNoiseDb = $get_user_wish->tyre_product_detail->wetGrip = $get_user_wish->tyre_product_detail->rollingResistance = $get_user_wish->tyre_product_detail->tyreLabelUrl =
							$get_user_wish->tyre_product_detail->brand_image  = $get_user_wish->tyre_product_detail->pr_description = $get_user_wish->tyre_product_detail->vhicle_type ='';
							$get_user_wish->tyre_product_detail->images = [];
							$get_user_wish->tyre_product_detail->seller_price = (string) $get_user_wish->tyre_product_detail->seller_price;
							/*Find Tyre tyre response*/
							if(!empty($get_user_wish->tyre_product_detail->type)){
								$tyre_type_response = \App\Tyre_pfu::where([['tyre_type' , '=' , $get_user_wish->type]])->first();
								if($tyre_type_response != NULL){
									$get_user_wish->tyre_product_detail->vhicle_type = $tyre_type_response->category;
									if(array_key_exists($tyre_type_response->category , $this->category_type2)){
										$get_user_wish->tyre_product_detail->vehicle_name = $this->category_type2[$get_user_wish->vhicle_type];
									}
								}
							}
							if(array_key_exists($get_user_wish->tyre_product_detail->type , $this->tyre_type_arr)){
								$get_user_wish->tyre_product_detail->season_name = $this->tyre_type_arr[$get_user_wish->tyre_product_detail->type];
							}
							/*End*/
							$tyre_detail = json_decode($get_user_wish->tyre_product_detail->tyre_response);
							//$get_tyre->tyre_response = $tyre_detail;
							//$get_tyre->tyre_detail_response = null;
							$tyre_detail_response = sHelper::get_tyre_detail($get_user_wish->tyre_product_detail);
							$tyre_detail_response_data = \App\Tyre24::get_Tyre24_detail($get_user_wish->tyre_product_detail->itemId);						
							if($tyre_detail_response_data != NULL){
								$tyre_detail_response_data = $tyre_detail_response_data->tyre_detail_response;
								$tyre_detail_response_data_json = json_decode($tyre_detail_response_data);	
							}
							if(!empty($tyre_detail_response_data_json->extRollingNoiseDb)){
								if(!is_object($tyre_detail_response_data_json->extRollingNoiseDb)){
									$get_user_wish->tyre_product_detail->extRollingNoiseDb =  (string) $tyre_detail_response_data_json->extRollingNoiseDb;
								}
							}
							if(!empty($tyre_detail_response_data_json->tyreLabelUrl)){
								if(!is_object($tyre_detail_response_data_json->tyreLabelUrl)){
									$get_user_wish->tyre_product_detail->tyreLabelUrl =  (string) $tyre_detail_response_data_json->tyreLabelUrl;
								}	
							}
							if(!empty($tyre_detail_response_data_json->wetGrip)){
								if(!is_object($tyre_detail_response_data_json->wetGrip)){
									$get_user_wish->tyre_product_detail->wetGrip =  (string) $tyre_detail_response_data_json->wetGrip;
								}	
							}
							if(!empty($tyre_detail_response_data_json->rollingResistance)){
								if(!is_object($tyre_detail_response_data_json->rollingResistance)){
									$get_user_wish->tyre_product_detail->rollingResistance =  (string) $tyre_detail_response_data_json->rollingResistance;
								}
							}	 
							if(!empty($tyre_detail->price)){
								if(!is_object($tyre_detail->price)){
									$get_user_wish->tyre_product_detail->price = (string)$tyre_detail->price;
								}
							}
							if(!empty($tyre_detail->is3PMSF)){
								if(!is_object($tyre_detail->is3PMSF)){
									$get_user_wish->tyre_product_detail->is3PMSF = (string)$tyre_detail->is3PMSF;
								}
							}
							if(!empty($tyre_detail->manufacturer_description)){
								if(!is_object($tyre_detail->manufacturer_description)){
									$get_user_wish->tyre_product_detail->manufacturer_description =  (string) $tyre_detail->manufacturer_description;
								}
							}
							if(!empty($tyre_detail->ean_number)){
								if(!is_object($tyre_detail->ean_number)){
									$get_user_wish->tyre_product_detail->ean_number =  (string) $tyre_detail->ean_number;
								}
							}
							/*Get 3 service for the workshop */
								$get_user_wish->tyre_product_detail->coupon_list = sHelper::get_coupon_product_list($get_user_wish->tyre_product_detail->ean_number,2 , $request->brand);
							/*end*/ 
							if(!empty($tyre_detail->description)){
								if(!is_object($tyre_detail->description)){
									$get_user_wish->tyre_product_detail->short_notation_description = \serviceHelper::set_tyre_description($tyre_detail->description);
									$get_user_wish->tyre_product_detail->description =  (string) $tyre_detail->description;
								}
							}
							if(!empty($tyre_detail->imageUrl)){
								if(!is_object($tyre_detail->imageUrl)){
									$get_user_wish->tyre_product_detail->imageUrl =  \serviceHelper::set_tyre_image($tyre_detail->imageUrl);
								}		   
							}else{
							$image_data = \App\TyreImage::get_tyre_image($get_user_wish->tyre_product_detail);
								if($image_data->count() > 0){
									$get_user_wish->tyre_product_detail->imageUrl = $image_data->image_url;
								}
							}
							$image_data = collect();
						
							$image_data = \App\TyreImage::get_tyre_image($get_user_wish->tyre_product_detail->itemId);
							if($image_data->count() > 0 || !empty($get_user_wish->tyre_product_detail->imageUrl) ){	
								$get_user_wish->tyre_product_detail->images = $image_data;
							}
							$image_data->push(['image_url'=>$get_user_wish->tyre_product_detail->imageUrl,'id'=>0, 'tyre24_id' => '' ,  'tyre_item_id' => '' , 'image_name' => '' , 'deleted_at' => '' , 'created_at' => '', 'updated_at' => '' ]);
							$image_data->push(['image_url'=>$get_user_wish->tyre_product_detail->tyreLabelUrl,'id'=>0,'tyre24_id'=>'','tyre_item_id'=>'','image_name'=>'','deleted_at'=>'','created_at'=>'','updated_at'=>'']);
							if(!empty($tyre_detail->wholesalerArticleNo)){
								if(!is_object($tyre_detail->wholesalerArticleNo)){ 
									$get_user_wish->tyre_product_detail->wholesalerArticleNo =  (string) $tyre_detail->wholesalerArticleNo;
								}
							}
							if(!empty($tyre_detail->pr_description)){
								if(!is_object($tyre_detail->pr_description)){ 
									$get_user_wish->tyre_product_detail->pr_description =  (string) $tyre_detail->pr_description;
								}
							}
							$get_user_wish->tyre_product_detail->brands = $tyre_detail->manufacturer_description;
							if(!empty($get_user_wish->tyre_product_detail->brands)){
								$brand_logo = \App\BrandLogo::brand_logo_tyre($get_user_wish->tyre_product_detail->brands);
								if($brand_logo != NULL){
									$get_user_wish->tyre_product_detail->brand_image = (string) $brand_logo->image_url;
								} 
							}
							$get_user_wish->tyre_product_detail->wish_list = 1;
							//if(!empty($request->user_id)){
							//$product_id = $get_user_wish->tyre_product_detail->id;
							//$user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_product($product_id , $request->user_id , $request->product_type);
								//if($user_wishlist_status == 1){
									//$get_user_wish->tyre_product_detail->wish_list = 1;
							//	} 
							//}
							$all_feed_back = null;
							$all_feed_back['rating'] = null;
							$all_feed_back['num_of_users'] = null;
							$all_feed_back = \App\Feedback::get_product_rating_list_for_tyre($get_user_wish->product_id);
							if($all_feed_back != NULL) {
								$get_user_wish->tyre_product_detail->rating = $all_feed_back;
								$get_user_wish->tyre_product_detail->rating_star = $all_feed_back['rating'];
								$get_user_wish->tyre_product_detail->rating_count = $all_feed_back['num_of_users'];
							}
							//$get_tyre->min_price = $tyre_detail->price;
							//$get_tyre->max_price = $tyre_detail->price;
							//$get_tyre->min_price = $get_tyre->seller_price;
							//$get_tyre->max_price = $get_tyre->seller_price;
					
						// $min_price = $get_tyre_info->min('seller_price');
						// if(empty($min_price)){
						// 	$min_price = "0";
						// }


						// $max_price = $get_tyre_info->max('seller_price');
						// $get_tyre_info->map(function($tyre) use ($min_price , $max_price){
						// 	$tyre->min_price = $min_price;
						// 	$tyre->max_price = $max_price;
						// 	return $tyre;
						// });
									//}
								$get_user_wish->spare_product_detail = null;
							}
						}
					$get_user_wish->workshop_detail = \App\User_wish_list::get_user_wish_list_for_workshop_list($get_user_wish->workshop_id ,Auth::user()->id);
						if($get_user_wish->workshop_detail != NULL){
							$get_user_wish->tyre_product_detail = null;
							$get_user_wish->spare_product_detail = null;
							$get_user_wish->workshop_detail->profile_image_url = NULL;
						if(!empty($workshop_users->profile_image)){
							$get_user_wish->workshop_detail->profile_image_url = url("public/storage/profile_image/$workshop_users->profile_image");
						}
						$all_feed_back = null;
						$all_feed_back['rating'] = null;
						$all_feed_back['num_of_users'] = null;
						$all_feed_back = \App\Feedback::get_workshop_rating($get_user_wish->workshop_id);
						if($all_feed_back != NULL) {
							$get_user_wish->workshop_detail->rating = $all_feed_back;
							$get_user_wish->workshop_detail->rating_star = $all_feed_back['rating'];
							$get_user_wish->workshop_detail->rating_count = $all_feed_back['num_of_users'];
						}
					}
						// if($request->wishlist_type == 1){
						// 	if($get_user_wish->product_type == 1){
						// 		$get_user_wish->product_detail = \App\ProductsNew::get_category_details($get_user_wish->product_id);	
						// 	}
						// 	if($get_user_wish->product_type == 2){
						// 		$get_user_wish->product_detail = \App\Tyre24::get_Tyre_id($get_user_wish->product_id);	
						// 	}
						// } else {
						// 	$get_user_wish->workshop_details = \App\User::get_workshop_details($get_user_wish->workshop_id);
						// }
						}	
					}
		    return sHelper::get_respFormat(1, "get user wish list ." , null  ,$get_user_wish_lists);
		}else{
			return sHelper::get_respFormat(0,"Unauthenticate , please login first.", null ,null);
		}
	}

	public function delete_user_wish_list(Request $request){
		if(Auth::user()->id){
			if($request->product_id)
			{
				$get_user_wish = \App\User_wish_list::where([['user_id','=',Auth::user()->id] , ['product_id' ,'=', $request->product_id]])->update(['deleted_at' => date('Y-m-d H:i:s')]); 
			}else{
				$get_user_wish = \App\User_wish_list::where([['user_id','=',Auth::user()->id] , ['workshop_id' ,'=', $request->workshop_id]])->update(['deleted_at' => date('Y-m-d H:i:s')]); 
				
			}
		return sHelper::get_respFormat(1, "Delete wish list successfully !!" , null , null);
		} else {
			return sHelper::get_respFormat(0,"Unauthenticate , please login first.", null ,null);
		}

	}


	
	
}
