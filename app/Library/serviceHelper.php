<?php
namespace App\Library;
use Auth;
use App\Category;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use App\Library\kromedaHelper;
use App\Products;
use App\Model\Kromeda;
use App\ProductsGroupsItem;
use Session;
use DB;
use App\WorkshopServicesPayments;
use App\WorkshopAssembleServices;
use App\ServiceBooking;
use App\Library\sHelper;
use App\Services;
use App\Http\Controllers\API\Tyre24;
use App\ServiceBooking as ServiceType;


class serviceHelper{
	
	
	
	public static function car_maintinance_for_workshop(){
		$data['car_maintinance_service_list'] = \App\ItemsRepairsServicestime::get_workshop_active_items_services();	
			   $data['car_maintainance_details'] = \App\WorkshopServicesPayments::where([['workshop_id' , '=' , Auth::user()->id], ['category_type', '=', 12]])->first();
			   foreach($data['car_maintinance_service_list'] as $car_maintinance){
				   $car_maintinance->service_average_time = $car_maintinance->hourly_cost = $car_maintinance->max_appointment =  $car_maintinance->price = $car_maintinance->description = null;
				   $car_maintinance->description = $car_maintinance->action_description;
				   $car_maintinance->service_average_time = $car_maintinance->time_hrs;
				   $service_detail = self::car_maintinance_service_details($car_maintinance);
				   if($service_detail != NULL){
					   if(!empty($service_detail->our_time)){
						   $car_maintinance->service_average_time = $service_detail->our_time;
					   }
					   if(!empty($service_detail->our_description)){
						  $car_maintinance->description = $service_detail->our_description;
					   }
				   }
				   /*car maintinance service detail*/
				   $workshop_service_detail = sHelper::get_maintainance_details($car_maintinance->id);
				   if($workshop_service_detail != NULL){
					   $car_maintinance->max_appointment = $workshop_service_detail->max_appointment;
					   $car_maintinance->hourly_cost = $workshop_service_detail->hourly_cost;
					   $car_maintinance->price = sHelper::calculate_service_price($car_maintinance->service_average_time , $car_maintinance->hourly_cost); 				    
				   } else {
					   if($data['car_maintainance_details'] != NULL){
						   $car_maintinance->max_appointment = $data['car_maintainance_details']->maximum_appointment;
						   $car_maintinance->hourly_cost = $data['car_maintainance_details']->hourly_rate;
						   $car_maintinance->price = sHelper::calculate_service_price($car_maintinance->service_average_time , $car_maintinance->hourly_cost); 
					   }
				   }
			   }	
		   return $data['car_maintinance_service_list'];	
	   }

	public static function car_maintinance_service_details($service){
        if($service->type == 1){
            return DB::table('items_repairs_servicestimes_details')->where([['items_repairs_servicestimes_item_id' , '=' , $service->item_id]])->first();
		}  
		return DB::table('items_repairs_servicestimes_details')->where([['items_repairs_servicestimes_id' , '=' , $service->id]])->first();
	}
	
	public static function set_tyre_description($description){
	   if(!empty($description)){
		   $description_arr = explode(' ' , $description);
		   return $description_arr;  
		   if(count($description_arr) > 0){
			 return $description_arr[3]." ".$description_arr[4]." ".$description_arr[5]." ".$description_arr[6];          
		   }
		   else{ return ""; }  
	   }
	   return "";
	}

	public static function set_tyre_image($image_name){
		if(!empty($image_name)){
			$image_component_arr = explode('/' , $image_name);
			$image_name_new = end($image_component_arr);
			/*Get image extension*/
			  $img_ext_arr = explode('.' , $image_name_new); 
			  $ext = $img_ext_arr[1];
            /*End*/
			$img_name_arr = explode('-' , $img_ext_arr[0]);
			array_pop($img_name_arr);
			array_push($img_name_arr , 1606510719);
			if(count($img_name_arr)){
			   $image_name_ = implode('-' , $img_name_arr);
			}
			$final_image_name =  $image_name_.".".$ext;
			array_pop($image_component_arr); array_push($image_component_arr , $final_image_name);
			$officine_tyre_image_url = implode('/' , $image_component_arr);
			return $officine_tyre_image_url;
		  }
	  }
	  
	
	public static function get_parts($part_type_id){
		$parts_type_arr = [1=>'Spare Parts' , 2=>'Tyre' , 3=>'Rim'];
		return $parts_type_arr[$part_type_id];
	 }
    
      public static function car_maintinance_price_appoinment($service_id , $workshop_id){
		$price = $max_appointment = 0;
		/*Get car maintinace specific services */
		$service_detail = DB::table('workshop_car_maintinance_service_details')->where([['items_repairs_servicestimes_id', '=' , $service_id] , ['workshop_id', '=' , $workshop_id]])->first();
		if($service_detail == NULL){
		    $service_detail_new = \App\WorkshopServicesPayments::where([['workshop_id' , '=' , $workshop_id], ['category_type', '=', 12]])->first(); 		
			if($service_detail_new != NULL){
			    return ['hourly_cost'=>$service_detail_new->hourly_rate, 'max_appointment'=>$service_detail_new->maximum_appointment];
			} 
			 return ['hourly_cost'=>'', 'max_appointment'=>'']; 
		  } else {
		     return ['hourly_cost'=>$service_detail->hourly_cost, 'max_appointment'=>$service_detail->max_appointment];
		  } 
	}
	
	
	public static function get_product_category($product_category_id){
		$data = [];
		$category_detail =  \App\Products_group::find($product_category_id); 
		if($category_detail != NULL){
			if($category_detail->parent_id == 0){
				$data['product_category_n1'] = $category_detail->id;   
				$data['product_category_n1_name'] = $category_detail->group_name;  
				$data['product_group_n1_type'] = $category_detail->type;    
				$data['product_group_group_id'] = $category_detail->group_id;  
				$data['product_category_n2'] = NULL;  
				$data['product_category_n2_name'] = NULL;   
				$data['product_group_n2_type'] = NULL;    
				$data['product_group_n2_group'] = NULL;    
			} else {
				/*Get N1 category detail*/
				$parent_category = \App\Products_group::find($category_detail->parent_id); 
				if($parent_category != NULL){
					$data['product_category_n1'] = $parent_category->id;   
					$data['product_category_n1_name'] = $parent_category->group_name;  
					$data['product_group_n1_type'] = $parent_category->type;
					$data['product_group_group_id'] = $parent_category->group_id;
				}
				
				/*End*/
				$data['product_category_n2'] = $category_detail->id;  
				$data['product_category_n2_name'] = $category_detail->group_name;   
				$data['product_group_n2_type'] = $category_detail->type;    
				$data['product_group_n2_group'] = $category_detail->group_id;    
			}  
		}
		return $data; 
	}	


   
   /*For Special Condition */
   
   public static function get_maker_name($conditions){
	if(!empty($conditions->makers)){
		if($conditions->makers == 1){
			return "All Maker";
		}
		else{
		$maker = \App\Maker::get_makers($conditions->makers);
			if($maker != NULL){
				return $maker->Marca;
			}
			else return "";
		}  
		return "";
	} 
   }

   public static function get_model_name($conditions){
	if(!empty($conditions->models)){  
		if($conditions->models == "1"){
			return "All Model"; 
		} 
		else{
			$model_details =  \App\Models::get_model($conditions->models);
			if($model_details != NULL){
				return  $model_details->idModello." >> ".$model_details->ModelloAnno;
			}
			else{
				return "";
			}
		} 
	}
   }	

   public static function get_version_name($conditions){
		if(!empty($conditions->versions)){
			if($conditions->versions == "all"){
			   return  "All Version";
			}
			else{
			   $version_details = \App\Version::get_version($conditions->versions);
			   if($version_details != NULL){
				 return  $version_details->Versione." >> ".$version_details->ModelloCodice;
			   } 
			   else{
				   return "";
			   }
			}
			return ""; 
		 }
	}  
   /*End*/

   public static function spare_product_description($order_id) {
		$order_description = \App\Products_order_description::spare_product_description($order_id); 
		if($order_description->count() > 0) {
			?> 
				<tr>
					<table border="1" width="100%" align="center" style="margin-top: 30px;">
						<tr>
							<th colspan="6">Spare Part</th>
						</tr>
						<tr>
							<th>#</th>
							<th>Category (N2)</th>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Brand</th>
							<th>Price</th>
						</tr>
						<?php
							foreach($order_description as $key => $description) {
								$product_details = \App\ProductsNew::get_category_details($description->products_id);
								$category_details = \App\ProductsGroupsItem::get_n3_category_details($product_details->products_groups_items_id);
								?>
									<tr>
										<td><?= $key+1 ?></td>
										<td><?= $category_details->item ?  $category_details->item.' '.$category_details->front_rear.' '.$category_details->left_right : '' ?></td>
										<td><?= $description->product_name ? $description->product_name : '' ?></td>
										<td><?= $description->product_quantity ? $description->product_quantity : '' ?></td>
										<td><?= $product_details->listino ? $product_details->listino : '' ?></td>
										<td>&euro; <?= $description->price ? $description->price : '0' ?></td>
									</tr>
								<?php
							}
						?>
					</table>
				</tr>
			<?php
		}
		
	}

	public static function tyre_product_description($order_id) {
		$order_description = \App\Products_order_description::tyre_product_description($order_id); 
		if($order_description->count() > 0) {
			?>
				<tr>
					<table border="1" width="100%" align="center" style="margin-top: 30px;">
						<tr>
							<th colspan="6">Tyres</th>
						</tr>
						<tr>
							<th>#</th>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Brand</th>
							<th>PFU Tax</th>
							<th>Total Price</th>
						</tr>
						<?php
							foreach($order_description as $key => $description) {
								$product_details = \App\Tyre24::get_tyre_details($description->products_id);
								$response = json_decode($product_details->tyre_response);
								$total_price = $description->price + $description->pfu_tax;
								?>
									<tr>
										<td><?= $key+1 ?></td>
										<td><?= $description->product_name ? $description->product_name : '' ?></td>
										<td><?= $description->product_quantity ? $description->product_quantity : '' ?></td>
										<td><?= $response->manufacturer_description ? $response->manufacturer_description : '' ?></td>
										<td><?= $description->pfu_tax ? $description->pfu_tax : '' ?></td>
										<td>&euro; <?= $total_price ?></td>
									</tr>
								<?php
							}
						?>
					</table>
				</tr>
			<?php
		}
	}

	public static function service_product_description($order_id) {
		$order_description = \App\ServiceBooking::service_order_description($order_id); 
		if($order_description->count() > 0) {
			?>
				<tr>
					<table border="1" width="100%" align="center" style="margin-top: 30px;">
						<tr>
							<th colspan="6">Tyres</th>
						</tr>
						<tr>
							<th>#</th>
							<th>Service Type</th>
							<th>Service Name</th>
							<th>Appointment</th>
							<th>Price</th>
						</tr>
						<?php
							// if($order_description->count() > 0) {
								$service_obj = new ServiceType;
								foreach($order_description as $key => $description) {
									$type = $service_obj->type[$description->type];	
									$service_name = self::get_service_details($description->type, $description->services_id);
									?>
										<tr>
											<td><?= $key+1 ?></td>
											<td><?= $type ? $type : ''?></td>
											<td><?= $service_name ? $service_name : '' ?></td>
											<td><?= $description->booking_date ? $description->booking_date : ''  ?>&nbsp;&nbsp;<?= $description->start_time ? $description->start_time : ''  ?></td>
											<td>&euro; <?= $description->price ? $description->price : '0'  ?></td>
										</tr>
									<?php
								}
							// }
						?>
					</table>
				</tr>
			<?php
		}
	}

	public static function get_service_details($type, $service_id) {
		if($type == 1 || $type == 3) {
			$service = NULL;
			if($service_id != NULL) {
				$service_name = \App\Category::get_service_category($service_id);
				return $service_name->category_name;
			}
		}
		if($type == 2) {			
			$service_name = \App\MainCategory::get_assemble_details($service_id);
			return $service_name->main_cat_name;
		}
	}
	
	public static function get_profile_status($user_id){
		$status = 0;
		$get_business_details = \App\BusinessDetails::where([['users_id','=', $user_id]/* ,['status','=','A'] */])->get();
		if($get_business_details->count() > 0) {
			$status += 25;
		}
		$get_bank_details = \App\Bankdetails::where([['users_id', '=', $user_id]])->get();
		if($get_bank_details->count() > 0) {
			$status += 25;
		}
		$get_address_details = \App\Address::where([['users_id', '=', $user_id]])->get();
		if($get_address_details->count() > 0) {
			$status += 25;
		}
		$get_time_slot = \App\Workshop_user_day_timing::where([['users_id', '=', $user_id]])->get();
		if($get_time_slot->count() > 0) {
			$status += 25;
		}
		return $status;
	}

	public static function check_time_slot_for_service_quotes($request , $workshop_service_detail){
		$booked_services = ServiceBooking::get_service_booking($request->selected_date , 7 , $workshop_service_detail->user_id);
		if($booked_services->count() > 0){
            if($booked_services->count() < $workshop_service_detail->max_appointment){
				return 1;
			}else{ return 0; }
		}
		else{ return 1; }
	}



	/*Apply coupon code script start*/
	public static function apply_coupon($request){
		$coupon_detail = DB::table('coupons')->where([['id' , '=' , $request->coupon_id] , ['status' , '=' , 1] , ['deleted_at' , '=' , NULL]])->first();
		/* return $coupon_detail; */
        if($coupon_detail != NULL){
            if($request->selected_date >= $coupon_detail->avail_date && $request->selected_date <= $coupon_detail->avail_close_date){
				return ['coupon_id'=>$coupon_detail->id , 'discount'=>$coupon_detail->amount , 'discount_type'=>$coupon_detail->offer_type];
			}
			else{
				return ['coupon_id'=>NULL , 'discount'=>NULL , 'discount_type'=>NULL];
			}
		}
		else{
			return ['coupon_id'=>NULL , 'discount'=>NULL , 'discount_type'=>NULL];
		}
	}
	/*End*/
	/* mot service*/
	public static function mot_service_price_appoinment($service_id , $workshop_id , $main_category){
		$price = $max_appointment = 0;
		/*Get cmot specific services */
		$service_detail = DB::table('workshop_mot_service_details')->where([['service_id', '=' , $service_id] , ['workshop_id', '=' , $workshop_id]])->first();
		if($service_detail == NULL){
		    $service_detail_new = \App\WorkshopServicesPayments::where([['workshop_id' , '=' , $workshop_id], ['category_type', '=', $main_category]])->first(); 		
			if($service_detail_new != NULL){
			    return ['status'=>200 , 'hourly_cost'=>$service_detail_new->hourly_rate, 'max_appointment'=>$service_detail_new->maximum_appointment];
			} 
			else{
				return ['status'=>100 , 'hourly_cost'=>0, 'max_appointment'=>0]; 
			}
		  } else {
		     return ['status'=>200 ,'hourly_cost'=>$service_detail->hourly_cost, 'max_appointment'=>$service_detail->max_appointment];
		  } 
	}
/*End*/



}