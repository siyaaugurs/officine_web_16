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
use App\Workshop_user_day;
use App\Workshop_user_day_timing;
use App\ServiceBooking;
use App\Address;
use App\TyreImage;
use Session;
use DB;
use App\WorkshopCarRevisionServices;
use App\WorkshopTyre24Details;
use App\Tyre24_details;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\SpecialCondition;

class sHelper{
  
    static $notifications = null;
	public static $num_of_row = 1;


	public static function get_current_time_zones($ip){
			//$ip = file_get_contents("http://ipecho.net/plain");
			//return $ip;
			//echo $ip;exit;
			$url = 'http://ip-api.com/json/'.$ip;
			$tz = file_get_contents($url);
			$tz = json_decode($tz,true)['timezone'];
			return $tz;
	}

	public static function manage_workshop_feedback_in_api($workshop , $workshop_id){
        /*manage rating*/
		$all_feed_back = $all_feed_back['rating'] = $all_feed_back['num_of_users'] = null;
		$all_feed_back = \App\Feedback::get_workshop_rating($workshop_id);
		if($all_feed_back != NULL) {
			$workshop->rating = $all_feed_back;
			$workshop->rating_star = !empty($all_feed_back['rating']) ?  (string) $all_feed_back['rating'] : null;
			$workshop->rating_count = !empty($all_feed_back['num_of_users']) ? (int) $all_feed_back['num_of_users'] : null;
		}
		/*End*/
	   return $workshop;			
	}

	public static function get_parts_feedback_api($parts , $parts_id , $type){
		$all_feed_back = $all_feed_back['rating'] = $all_feed_back['num_of_users'] = null;
		$all_feed_back = \App\Feedback::parts_feedback($parts_id , $type);
		if($all_feed_back != NULL) {
			$parts->rating = $all_feed_back;
			$parts->rating_star =  !empty($all_feed_back['rating']) ? (string) $all_feed_back['rating'] : null;
			$parts->rating_count = !empty($all_feed_back['num_of_users']) ? (int) $all_feed_back['num_of_users'] : null;
		}
		/*End*/
	   return $parts; 
	}
    
	public static function manage_product_feedback_in_api($product , $product_id){
        /*manage rating*/
		$all_feed_back = $all_feed_back['rating'] = $all_feed_back['num_of_users'] = null;
		$all_feed_back = \App\Feedback::get_workshop_rating($product_id);
		if($all_feed_back != NULL) {
			$product->rating = $all_feed_back;
			$product->rating_star = $all_feed_back['rating'];
			$product->rating_count = $all_feed_back['num_of_users'];
		}
		/*End*/
	   return $product;			
	}
	
	public static function get_support_msg($status , $msg){
		$obj = new Controller;
		if($status == 1){
			$ext_arr = explode('.' , $msg);
			$ext =  end($ext_arr);
			if(in_array($ext , $obj->image_ext)){
				return '<a href="'.$msg.'" target="_blank"><img src="'.$msg.'" height="50px;" width="50px;" /></a>';
			}else{
				return '<a href="'.$msg.'" target="_blank"><img src="'.url('file_icon.png').'" height="50px;" width="50px;" /></a>';
			}
		}
		if($status == 2){
           return $msg;    
		}
	}
	
	public static function image_url($status , $image_name = NULL){
		if(!empty($image_name)){
			switch ($status) {
				case 2:
				return url("storage/profile_image/$image_name");;
				break;
				case 'C':
				return '<span class="badge badge-danger">Closed</span>';
				break;
				default:
				return '';
			 }
		}
		else{
			return "http://services.officinetop.com/public/storage/products_image/no_image.jpg";
		}
	}

	public static function support_ticket_status($status){
		switch ($status) {
			case 'A':
			return '<span class="badge badge-info">Active</span>';
			break;
			case 'C':
			return '<span class="badge badge-danger">Closed</span>';
			break;
		 }
	}

	public static function get_main_category($main_cat , $category_id = NULL){
		$data_arr = [];
		if($category_id != NULL){
		   $service_category = DB::table('categories')->where([['id' , '=' , $category_id]])->first();
		   if($service_category != NULL){
			   $data_arr['service_category'] = $service_category->category_name;
			 }
			//$data['service_category'] =   
		  } 
		 $main_category = DB::table('main_category')->where([['id' , '=' , $main_cat]])->first();
		 if($main_category != NULL){
			$data_arr['service'] = $main_category->main_cat_name;  
		  }
		return $data_arr;  
	  }

	  public static function get_brand($brand_id){
		return DB::table('brand_logos')->where([['id' , '=' , $brand_id]])->first();
	  }
	
	public static function get_rim_image($rim){
		if($rim->type = 1){
		   return \App\RimImage::where([['rim_alcar' , '=' , $rim->alcar]])-> get();
		 }
		return \App\RimImage::where([['rim_ids' , '=' , $rim->id]])->first();  
	 }
   
	public static function get_tyre_detail($tyre){
		if($tyre->type_status == 1){
			return \App\Tyre24_details::where([['tyre24s_itemId' , '=' , $tyre->itemId]])-> first();
		}
		return Tyre24_details::where([['tyre24_id' , '=' , $tyre->id]])->first();
	  }


	  public static function get_rim_detail($rim){
		if($rim->type == 1){
			$decode_response = json_decode($rim->rim_response);
             return \App\RimDetails::where([['rim_id_id' , '=' , $decode_response->id]])->first();
		  }
		 return \App\RimDetails::where([['rim_id' , '=' , $rim->id]])->first();
	  }	
		

   /*Get Rim details*/
    Public static function get_rim_image_main_image($rim){
		if($rim->type == 1){
			$rim_detail = \App\RimDetails::where([['rim_id_id' , '=' , $rim->rim_id]])->first();
			if($rim_detail != NULL){
				$decode_response = json_decode($rim_detail->rim_details_response);
					if(!empty($decode_response->imageUrl)){
						if(!is_object($decode_response->imageUrl)){
							return $decode_response->imageUrl;				 
						}
						else{
							return "http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg";  
						}
						 //"http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg"; 
					   //return $decode_response->items->imageUrl;
					  }
					else{
						return "http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg";  
					  }	
				/* if($rim_detail->rim_details_response != "[]"){
				  }
				else{
				   return "http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg"; 
				  }	 */	  
			  }
			else{
				return "http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg";
			  } 		
		}
		else{
			return "http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg";
		  } 			
	}	
   
    /*Set Discount Type*/
   public static function set_discount_type($discount_type = NULL){
      $discount_type_arr = [1=>'Price per hour' , 2=>'Discount Percentage'];
	   if($discount_type != NULL){
		 if(array_key_exists($discount_type , $discount_type_arr))
		     return $discount_type_arr[$discount_type]; 
		 else return "No defined";
		} 
   }
   /*End*/
   
   /*Get Vheicle Type script Start*/
   public static function get_vehicle_type($vehicle_type = NULL){
      $vehicle_arr = [1=>'All Car' , 2=>'Car' , 3=>'Truck'];
	  if($vehicle_type != NULL){
		 if(array_key_exists($vehicle_type , $vehicle_arr))
		     return $vehicle_arr[$vehicle_type]; 
		 else return "All Car";
		} 
      else{
		  return "All Car";
		}		
   }
   /*End*/

   /*get tyre by measurement */
	public static function get_vehicle_tyre($type = NULL) {
		if($type != "all" ) {
			$vehicle_details = \App\MasterTyreMeasurement::where([['id', '=', $type], ['type', '=', 1]])->first();
			if(!empty($vehicle_details)) {
				return $vehicle_details->name;
			}
		} else {
			return "All Cars";
		}
	}
   /*End */
   public static function get_tyre_season_type($type = NULL) {
	if($type != NULL ) {
		$season_details = \App\MasterTyreMeasurement::where([['code2', '=', $type], ['type', '=', 2]])->first();
		if(!empty($season_details)) {
			return $season_details->name;
		}
	}
}
   
   
   /*Get and set model name and model value for edit task special condition*/	
      public static function get_and_set_model($model_value){
		$model_arr = [];
		if($model_value == "1") {
			$model_arr['model_name'] = "All Models";
		    $model_arr['model_value'] = 1;
		} else if($model_value == "0") {
			$model_arr['model_name'] = "";
		    $model_arr['model_value'] = "";
		} else {
			$model_details =  \App\Models::get_model($model_value);
			$model_arr['model_name'] = $model_details->idModello." >> ".$model_details->ModelloAnno;
			$model_arr['model_value'] = $model_value;
		}
		/* if($model_value != "1"){
		  $model_details =  \App\Models::get_model($model_value);
			$model_arr['model_name'] = $model_details->idModello." >> ".$model_details->ModelloAnno;
			$model_arr['model_value'] = $model_value;
		  }
		 else {
		    $model_arr['model_name'] = "All Models";
		    $model_arr['model_value'] = 1;
		  }*/
		 return $model_arr;  
	  } 
   /*End*/
   
   
    /*Get and Set version name and version value for edit task special condition*/
   public static function get_and_set_version($version_value){
		$version_arr = [];
		if($version_value == "all") {
			$version_arr['version_name'] = "All Versions";
			$version_arr['version_value'] = "all";
		} else if($version_value == 0) {
			$version_arr['version_name'] = "";
			$version_arr['version_value'] = "";
		} else {
			$version_details = \App\Version::get_version($version_value); 
		   	if($version_details != NULL)
			 	$version_arr['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
				$version_arr['version_value'] = $version_value;
		}
		/* if($version_value != "all"){
		   	$version_details = \App\Version::get_version($version_value); 
		   	if($version_details != NULL)
			 	$version_arr['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
				$version_arr['version_value'] = $version_value;
		} else {
			$version_arr['version_name'] = "All Versions";
			$version_arr['version_value'] = "all";
		} */
		return $version_arr;  
	  }
   /*End*/	
   
   /*Find Speed index value*/
    public static function find_speed_index ($description){
	  $speed_index = "H";
	  if(preg_match('( H | T | V | ZR | Y | W )', $description) === 1) { 
	      $desc_arr = explode(' ' , $description);
		  if(count($desc_arr) > 0){
			   foreach($desc_arr as $key=>$srt_value){
				    $srt_value = trim($srt_value);
				    if($srt_value == "H" || $srt_value == "T" || $srt_value == "V" || $srt_value == "ZR" || $srt_value == "Y" || $srt_value == "W"){
					        return $srt_value;
					   }
				 }
			} 
		}		
	  else{
		  return $speed_index;
		}	
	}
   /*End*/
   
   /*Get Tyre Type*/
   public static function get_tyre_type($tyre_type){
	  $tyre_type_arr = ['s'=>'Summer tyre' ,'S'=>'Summer tyre' ,'w'=>'Winter tyre' , 'W'=>'Winter tyre' , 'm'=>'2-Wheel / Quad tyre' , 'M'=>'2-Wheel / Quad tyre' , 'g'=>'All-season tyre' , 'G'=>'All-season tyre' , 'o'=>'Off-road tyre' , 'O'=>'Off-road tyre' , 'i'=>'Truck tyre' , 'I'=>'Truck tyre'];
      if(array_key_exists($tyre_type , $tyre_type_arr)){
		   return $tyre_type_arr[$tyre_type];
		}
	  else{
		  return "Other";
		}	
   }
   /*End*/
   
   public static function get_car_size_via_body($carBody){
		if (!empty($carBody)) {
			if (strpos('microcar,uitilitaria', strtolower(explode(",", $carBody)[0])) !== false) {
				return $car_size = 1;
			}
			else if (strpos('berlina 2 volumi, berlina 3 volumi, station wagon, crossover, coupe, cabriolet', strtolower(explode(",", $carBody)[0])) !== false) {
				return $car_size = 2;
			}

			else if (strpos('suv, fuoristrada, monovolume, auto di lusso, multispace', strtolower(explode(",", $carBody)[0])) !== false) {
				return $car_size = 3;
			}
			else{
			  return $car_size = 2;  
			}
		} else {
			return $car_size = 2;
		}

	}
   
   
   /*Get Car revision details */
     public static function get_car_revision_service_detail($user_id , $category_id){
          return WorkshopCarRevisionServices::where([['workshop_id' , '=' , $user_id] , ['category_id' , '=' , $category_id] , ['deleted_at' , '=' , NULL]])->first();
	  }
   /*End*/
   
   /*Check time slot script status */
      public static function check_time_slot($time_slots ,  $service_average_times , $request , $service_id,$workshop_id){
		 $opening_slot = $not_aplicable_slot = $new_booked_list = [];
		 foreach($time_slots as $slot){
			 $opening_slot[] = [$slot->start_time , $slot->end_time]; 
		 }
		$special_condition_obj = new SpecialCondition;
		$special_condition_status = $special_condition_obj->do_not_perform_operation_for_car_maintenance(12 , $request->selected_date ,$workshop_id,$time_slots ,$request->selected_car_id ,$service_id , 1);
		$decode_response = json_decode($special_condition_status);
        if($decode_response->status == 200){
		   $not_aplicable_slot = $decode_response->response;
		}
		$new_generate_slots = sHelper::get_time_slot($not_aplicable_slot, $opening_slot);
		/*Get Booked slots*/
		  $booked_slots = \App\ServiceBooking::where([['workshop_user_id','=',(int) $request->workshop_id] ,
														['type' , '=' , 5] ,
														['services_id' , '=' ,$service_id]])
												->whereDate('booking_date' , $request->selected_date)->get();
		/*End*/
	      if($booked_slots->count() > 0){
			  foreach($booked_slots as $booked){
				  $new_booked_list[] = [$booked->start_time, $booked->end_time];
			  }
		  }
		  $new_generate_slots = sHelper::get_time_slot($new_booked_list, $new_generate_slots);
		  if(count($new_generate_slots) > 0){
			  foreach($new_generate_slots as $slot){
				 $get_slot_time_in_hour = sHelper::get_number_of_hour($slot[0], $slot[1]);
				 if ($get_slot_time_in_hour >= $service_average_times)  $flag = 1;
				 else  $flag = 0; 
			  }
		  }
		  else { $flag = 0; }
     return $flag;  
		//  $flag = 0;
		//  $get_services_weekly_days = Workshop_user_day::get_service_weekly_days($workshop_user_id , $selected_days_id);
		//  if($get_services_weekly_days != null) {
		// 	$get_service_packages = Workshop_user_day_timing::get_packages($get_services_weekly_days->id);
		//     if($get_service_packages->count() > 0){
		// 	   foreach($get_service_packages as $package){
		// 		  $opening_slot = [];
		// 		  $new_booked_list = [];
		// 		  $opening_slot[] = array($package->start_time, $package->end_time);
		// 		  /*Get Booked packages*/
		// 		  $booked_list = ServiceBooking::get_service_booked_package($package->id , $selected_date ,  $service_id , $car_size , 1);
		// 	      	if($booked_list->count() < $max_appointment){
		// 			 if($booked_list->count() > 0) {
		// 				foreach ($booked_list as $booked){
		// 				  $new_booked_list[] = [$booked->start_time, $booked->end_time];
		// 			    }
		// 			   }
		// 			  $new_generate_slot = sHelper::get_time_slot($new_booked_list, $opening_slot);
		// 			  if(count($new_generate_slot) > 0) {
		// 				foreach ($new_generate_slot as $slot_details) {
		// 					$slot_details['start_time'] = sHelper::change_time_format_2($slot_details[0]);
		// 					$slot_details['end_time'] = sHelper::change_time_format_2($slot_details[1]);
		// 					$get_slot_time_in_hour = sHelper::get_number_of_hour($slot_details[0], $slot_details[1]);
		// 					if(!empty($get_slot_time_in_hour)){
		// 					    $new_time_in_hour = round($get_slot_time_in_hour , 2);   
		// 					    $total_service_time = $service_time + 0.33;
		// 						if ($new_time_in_hour >= $total_service_time) {
		// 							return 1;
		// 						}
		// 					  }
		// 					else{ return $flag; }  
		// 				}
		// 			}
		// 			}
		// 			else{
		// 			   return $flag;
		// 			 }
		// 		  /*End*/
		// 		  }
		// 	  } 
		// 	else{
		// 	    return $flag;
		// 	  }  
			 	 
		//    }
	  }
   /*End*/
   
    /*Get Products Details */
    public static function get_products_details($products_details){
		if($products_details != NULL){
			if($products_details->type == 1){
				return \App\ProductsNew_details::where([['products_kromeda_id' , '=' , $products_details->products_name]])->first();
			}
			return \App\ProductsNew_details::where([['product_id' , '=' , $products_details->id]])->first();
		}
	}  

	/*Get Products Image Script Start*/
	public static function get_products_image($product_details){	
		if($product_details != NULL){
			if($product_details->type == 1){
				return \App\ProductsImage::where([['products_id' , '=' , $product_details->id] ,['deleted_at' , '=' , NULL]])
				                    ->orWhere([['product_kromeda_id' , '=' , (string) $product_details->products_name] , ['ls_CodiceListino' , '=' , (string) $product_details->CodiceListino] ,['deleted_at' , '=' , NULL]])->get();
			}
			return \App\ProductsImage::where([['products_id' , '=' , $product_details->id] ,['deleted_at' , '=' , NULL]])->get();
		}
	}
	/*End*/
   
   
   
   /*Get Group Details script Start*/
    public static function get_categories_details($group_details){
	   if($group_details->type == 1){
		     return DB::table('categories_details')->where([['n1_n2_group_id' , '=' , $group_details->group_id]])->first();  
		  }
	   	  return DB::table('categories_details')->where([['n1_n2_id' , '=' , $group_details->id]])->first();    
	} 
   /*End*/
   
   /*Get N2 Group Details script Start*/
    public static function get_n2_categories_details($group){
	   if($group->type == 1){
		     return DB::table('categories_details')->where([['n2_group_id' , '=' , $group->group_id]])->first();  
		  }
	   	  return DB::table('categories_details')->where([['n2_id' , '=' , $group->id]])->first();    
	} 
   /*End*/

   /*Get N3 Group Details script Start*/
    public static function get_n3_categories_details($group_item){
	   	if($group_item->type == 1){
     		return DB::table('categories_details')->where([['n3_item_id', '=',$group_item->item_id]])->first();  
	  	}
   	  	return DB::table('categories_details')->where([['n3_id', '=',$group_item->id]])->first();    
	} 
   /*End*/
   
   
   /*Get N3 sub category images */
     public static function get_n3_category_images($n3_category_details){
	    if($n3_category_details->type == 1){
		   return \DB::table('galleries')->where([['products_groups_items_item_id' , '=' , $n3_category_details->item_id] ,['deleted_at' , '=' , NULL]])->get();  
		  }
		return \DB::table('galleries')->where([['products_groups_items_id' , '=' , $n3_category_details->id] , ['deleted_at' , '=' , NULL]])->get();	  
		  
	 }
   /*End*/
   
   
     /*Get sub category group images*/
    public static function  get_sub_group_image($group_details){
	     if($group_details->type == 1){
		       return \DB::table('galleries')->where([['product_sub_group_group_id' , '=' , $group_details->group_id] ,['deleted_at' , '=' , NULL]])->get();
			}
		  return \DB::table('galleries')->where([['group_id' , '=' , $group_details->id] , ['deleted_at' , '=' , NULL]])->get();	
		 
	  }  
   /*End*/
   
   /*Get Products group image */
     public static function  get_group_image($group_details){
	     if($group_details->type == 1){
		       return \DB::table('galleries')->where([['product_group_group_id' , '=' , $group_details->group_id] ,['deleted_at' , '=' , NULL]])->get();
			}
		  return \DB::table('galleries')->where([['group_id' , '=' , $group_details->id] , ['deleted_at' , '=' , NULL]])->get();	
		 
	  }
   /*End*/
   
   
   /*Get N2 items*/
   public static function get_sub_categories($group){
	if($group->type == 1){
		return	\App\Products_group::where([['parent_id' , '=' , $group->id] , ['deleted_at' , '=' ,NULL] , ['status' , '=' , 'A'] , ['type' , '=' , 1]])
				          ->orWhere([['products_groups_group_id', '=' ,$group->group_id] , ['type' , '=' , 2] , ['deleted_at' , '=' ,NULL] , ['status' , '=' , 'A']])
				          ->get();
	   }
	 return	\App\Products_group::where([['parent_id' , '=' , $group->id] , ['deleted_at' , '=' ,NULL] , ['status' , '=' , 'A']])->get();                 
   }  
   
   /*For users End*/
   public static function get_sub_group($groups_details){
	   if($groups_details->type == 1){
		  return \App\Products_group::where([['parent_id' , '=' , $groups_details->id] , ['deleted_at' , '=' ,NULL] , ['type' , '=' , 1]])
                            ->orWhere([['products_groups_group_id' , '=' ,$groups_details->group_id] ,  ['deleted_at' , '=' , NULL] , ['type' , '=' , 2]])
                            ->get(); 
		 }
		  return \App\Products_group::where([['parent_id' , '=' , $groups_details->id] , ['deleted_at' , '=' ,NULL]])->get(); 							
   }
   /*End*/
   
   /*End*/
   
    /*Get N3 category script start*/
      /*For users*/
	   public static function get_group_items_for_users($sub_group_details){
          if($sub_group_details->type == 1){
    	     return \App\ProductsGroupsItem::where([['products_groups_id' , '=' , $sub_group_details->id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])
    				                        ->orWhere([['n2_kromeda_group_id' , '=' , $sub_group_details->group_id], ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])
    										->get();
    		}
    	    return \App\ProductsGroupsItem::where([['products_groups_id' , '=' , $sub_group_details->id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->get();	
      } 
	  /*End*/
    
      public static function get_group_items($sub_group_details){
          if($sub_group_details->type == 1){
    	     return \App\ProductsGroupsItem::where([['products_groups_id' , '=' , $sub_group_details->id] , ['deleted_at' , '=' , NULL]])
    				                        //->orWhere([['n2_kromeda_group_id','=',$sub_group_details->group_id]])
											//->groupBy('item_id')
											->get();
    		}
    	     return \App\ProductsGroupsItem::where([['products_groups_id' , '=' , $sub_group_details->id] , ['deleted_at' , '=' , NULL]])->get();	
      }
  /*End*/
   
   
   /*public static function save_in_spare_group($sub_group_id){
		$spare_category_id = \DB::table('main_category')->where([['private' , '=', 1] , ['type' , '=' , 1]])->first();
		if($spare_category_id != NULL){
		    return \App\Spare_category_item::save_in_spare_groups($sub_group_id , $spare_category_id->id);
			
		}   
   }*/

   public static function get_product_info($kromeda_products_id){
	  return DB::table('products_new')->where([['products_name' , '=' , $kromeda_products_id]])->first();
   }
   
   public static function get_n3_category($n2){
	  return  DB::table('products_groups_items')->where([['products_groups_id' , '=' , $n2] , ['deleted_at' , '=' , NULL]])->get();    
   } 
   
   public static function get_groups_details($id){
       return DB::table('products_groups')->where([['id' , '=' , $id] , ['deleted_at' , '=' , NULL]])->first();
   }
   
   public static function get_sub_groups($group_id){
       return DB::table('products_groups')->where([['parent_id' , '=' , $group_id] , ['deleted_at' , '=' , NULL]])->get();
   }

   
   /*Hold task on */
   /*public static function get_category_detail($category_id){
      if($category_id != NULL){
		   $category_detail =  \App\Products_group::where([['id' , '=' , $category_id]])->first();
		   if($category_detail != NULL){
			   if(!empty($category_detail->group_id)){
				   return \App\CategoriesDetails::where([['n1_n2_group_id' , '=' , $category_detail->group_id]])->first();  
				 }
			   return \App\CategoriesDetails::where([['n1_n2_id' , '=' , $category_id]])->first();  
			 }
		   return $category_detail; 
		 } 
   } */

   public static function get_parent_groups_details($parent_id){
		return DB::table('products_groups')->where([['id' , '=' , $parent_id] , ['parent_id' ,'=',0] , ['deleted_at' , '=' , NULL]])->first();
   }
   
   public static function new_get_group_details($group_id = NULL , $products_group_id = NULL){
	if($products_group_id != NULL){
		return DB::table('products_groups')->where([['id' , '=' , $products_group_id] , ['parent_id' , '!=' , 0] , ['deleted_at' , '=' , NULL]])->first();
	  }
	return DB::table('products_groups')->where([['group_id' , '=' , $group_id] ,  ['parent_id' , '!=' , 0] ,  ['deleted_at' , '=' , NULL]])->first();
  }

   public static function get_group_details($group_id = NULL , $products_group_id = NULL){
	   if($products_group_id != NULL){
		   return DB::table('products_groups')->where([['id' , '=' , $products_group_id] , ['deleted_at' , '=' , NULL]])->first();
		 }
       return DB::table('products_groups')->where([['group_id' , '=' , $group_id] , ['deleted_at' , '=' , NULL]])->first();
   }

   public static function get_assemble_workshop($request){
	  $products_details = \App\ProductsNew::where([['id','=',$request->products_id] , ['deleted_at' , '=' , NULL] , ['products_status' , '=' , 'A']])->first();
	  if($products_details != NULL){
		$assemble_time = 0;
		if(!empty($products_details->assemble_kromeda_time)){
			$assemble_time = $products_details->assemble_kromeda_time;
		}
		else{
			$assemble_time = $products_details->assemble_time;
		}
		/*Products Groups details*/
	   $products_groups_details = \App\Products_group::where([['deleted_at' , '=' , NULL] , ['id','=' ,$products_details->products_groups_id]])->first();
	   if($products_groups_details != NULL){
		   $blongs_to_in_assemble_services = \App\Spare_category_item::get_assemble_service($products_groups_details->group_id);
		   if($blongs_to_in_assemble_services != NULL){
			   $workshop_users_arr =  DB::table('users_categories')
								   ->where('categories_id' , $blongs_to_in_assemble_services->main_category_id)->get();
			   return $workshop_users_arr;    
			}
		   else{
			 return sHelper::get_respFormat(0, "No , Services available !!!", null, null);   
			 } 	 
		   
		 }
		else{
		   return sHelper::get_respFormat(0, "Something Went Wrong . please try again ", null, null);  
		 } 
	   /*End*/
	  }
	else{
	  return sHelper::get_respFormat(0, "please select correct products", null, null); 
	  }  
   }	
   
   /*Get car revision services detailsscdript start*/
     public static function get_car_revision($workshop_id , $category_id){
	     return \App\WorkshopCarRevisionServices::get_service_details($workshop_id , $category_id);
	  }
   /*End*/

    public static function get_car_wash_service_time($car_size , $category_id){
     $get_services_times = DB::table('service_time_prices')
							->where([['categories_id' , '=' ,$category_id]])
							->first();
	 if($get_services_times != NULL){
	     if($car_size == 1){
	     return $get_services_times->small_time;  
			}
		 else if($car_size == 2){
			return $get_services_times->average_time; 
			}	
		  else if($car_size == 3){
			   return $get_services_times->big_time; 
			}
		  else{
			   return 0;
			}
		}						
   }
   
   public static function get_mot_part_image($id) {
		$product_image =  DB::table('mot_parts_image')->where('mot_item_parts_id' , '=' ,$id)->first();
		 if($product_image != NULL) {
			 return $product_image->image_url;
		  } 
		 else {
			 return url("storage/products_image/no_image.jpg");
		 }
	}
   
   public static function create_session_key(){
		if (!Session::has('kromeda_session_key')){
              Session::put('kromeda_session_key', sHelper::generate_kromeda_session_key());
			}
		else{
		  $session_key = session::get('kromeda_session_key');
		  $check_resp = self::Get_kromeda_Request($session_key , 'CheckSessionKey' , true , '');
		  if($check_resp->result[1] == "False"){
			    $session_key_response = self::Get_kromeda_Request(false , 'CreateSessionKey' , true , '');
			   if(!empty($session_key_response)){
				$session_key = $session_key_response->result[1];
				Session::put('kromeda_session_key', $session_key); 
				 return $session_key;
				 }
		     }
			else{
			   return session::get('kromeda_session_key'); 
			 } 
		  }
		
	  }
   public static function get_item_repair_image($id) {
		$product_image =  DB::table('item_repairs_parts_image')->where('item_repairs_parts_id' , '=' ,$id)->first();
		 if($product_image != NULL) {
			 return $product_image->image_url;
		 } else {
			 return url("storage/products_image/no_image.jpg");
		 }
	}   
   public static function calculate_service_price($time , $price){
      return $time*$price;
   }
   public static function change_in_hour($min){
	  // return $min;
	return $min / 60; 
   }
   
/*Get Time slot */
public static function workshop_time_slot($selected_date , $workshop_id){
	$time_slot = collect();
	$selected_days_id = self::get_week_days_id($selected_date);
	$workshop_user_day = \App\Workshop_user_day::where([['users_id', '=', $workshop_id], ['common_weekly_days_id', '=', $selected_days_id], ['deleted_at', '=', NULL]])->first();
	//return $workshop_user_day;exit;
	if($workshop_user_day != NULL){
		return  \App\Workshop_user_day_timing::select(DB::raw('* , TIME_FORMAT(start_time, "%H:%i") as start_time  , TIME_FORMAT(end_time, "%H:%i") as end_time'))->where([['workshop_user_days_id' , '=' , $workshop_user_day->id] , ['deleted_at' , '=' , NULL]])
		->get();
		//return \App\Workshop_user_day_timing::where([['workshop_user_days_id', '=', $workshop_user_day->id], ['deleted_at', '=', NULL]])->get();
	}
	return $time_slot;
} 
/*End*/

   /*Calculate Distance */
	public static function calculate_distance($lat1, $lon1, $lat2, $lon2, $unit) {
		if (($lat1 == $lat2) && ($lon1 == $lon2)) {
		  return 0;
		}
		else {
		  $theta = $lon1 - $lon2;
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  $unit = strtoupper($unit);
		  if ($unit == "K") {
			return ($miles * 1.609344);
		  } else if ($unit == "N") {
			return ($miles * 0.8684);
		  } else {
			return $miles;
		  }
		}
	  }
	
	/*End*/

  /*Calculate Wrackjer Services script start*/
	public static function calculate_wrecker_service_price($service , $workshop_service_detail , $type , $selected_car_id , $distance_in_km) {
		/* echo "<pre>";
		print_r($workshop_service_detail);exit; */
		//$weight = 1500;
		$user_car_detail = DB::table('user_details')->where([['id' , '=' , $selected_car_id]])->first();
		if($user_car_detail != NULL){
			$version = DB::table('versions')->where([['idVeicolo' , '=' , $user_car_detail->carVersion]])->first(); 
			if($version != NULL){
				$response = (array) json_decode($version->version_response);
                if(count($response) > 0){
					$weight = $response['Cm3'];
					if($type == 2){
						/*on emergency call service price*/
						$time_according_to_distance_in_min = $service->time_per_km * round($distance_in_km , 2); 
						$time_according_to_distance_in_min = $time_according_to_distance_in_min + $workshop_service_detail->total_time_arrives;
						$service_price_according_to_distance_call_cost = self::calculate_service_price(self::change_in_hour($time_according_to_distance_in_min) , $workshop_service_detail->cost_per_km) + $workshop_service_detail->call_cost;
						$service_price_hourly_cost = self::calculate_service_price(self::change_in_hour($time_according_to_distance_in_min) , $workshop_service_detail->cost_per_km);
						$final_price = $service_price_according_to_distance_call_cost + $service_price_hourly_cost;
						return ['final_price'=>$final_price , 'time'=>self::change_in_hour($time_according_to_distance_in_min) , 'time_in_min'=>$time_according_to_distance_in_min];	
						/*End*/
					}
					if($type == 1){
						/*On Service Appointment*/
						$time_according_to_distance = $service->time_per_km * round($distance_in_km , 2); 
						$time_according_to_distance = $time_according_to_distance + $workshop_service_detail->total_time_arrives;
						$service_price_according_to_distance = self::calculate_service_price(self::change_in_hour($time_according_to_distance) , $workshop_service_detail->cost_per_km);
						/*Service */
						$service_price_hourly_cost = self::calculate_service_price(self::change_in_hour($time_according_to_distance) , $workshop_service_detail->cost_per_km);
						$final_price = $service_price_according_to_distance + $service_price_hourly_cost;
						return ['final_price'=>$final_price , 'time'=>self::change_in_hour($time_according_to_distance) , 'time_in_min'=>$time_according_to_distance];	
						/*End*/
					}
				}
				else{ return ['final_price'=>NULL, 'time'=>NULL];	
				 }
			} 
		}
		else{ return ['final_price'=>NULL, 'time'=>NULL]; }
	}
	/*End*/

   public static function get_maintainance_details($service_time_id){
		return \App\WorkshopCarMaintinanceServiceDetails::where([['items_repairs_servicestimes_id', '=', $service_time_id], ['workshop_id', '=', Auth::user()->id ]])->first();
	}
       
   public static function replace_comman_with_dot($value){
	      if(strpos($value , ',') !== FALSE){
			   $real_value = str_replace(',' , '.' , $value); 
			}
		  else{ $real_value = $value; }
		 return $real_value; 
   }
    
    public static function get_product_image($id) {
		$product_image =  \App\ProductsImage::where([['products_id' , '=' ,$id], ['deleted_at', '=', NULL]])->get();
		// return $product_image;
		 if($product_image->count() > 0) {
			 return $product_image[0]['image_url'];
		 } else {
			 return url("storage/products_image/no_image.jpg");
		 }
	}
    
    
    public static function get_groups_item_by_groups($group_deatails  , $lang){
	  if($group_deatails->parent_id != 0){
		   $get_groups_item = kromedaHelper::get_sub_products_by_sub_group($group_deatails->car_version , $group_deatails->group_id , $lang);
		    }
	  else{
		   $get_groups_item = kromedaHelper::get_products_by_group($group_deatails->car_version , $group_deatails->group_id  , $lang);
		 
		}  
	  $item_parts = collect($get_groups_item);
	  $get_groups_items_database = ProductsGroupsItem::get_groups_items($group_deatails->id , $lang);
	  //$get_products_database = Products::get_admin_products($req);
				 if($get_groups_items_database->count() > 0){
						 $all_filterd_data_arr = $get_groups_items_database->pluck('item_id')->all();
						 foreach($item_parts as $kromeda_parts_item){
							$all_filtered_parts = $item_parts->filter(function ($kromeda_parts_item) use ($all_filterd_data_arr) {
							 return !in_array($kromeda_parts_item->idVoce , $all_filterd_data_arr);
							}); 
							$item_parts = $all_filtered_parts;
						}
				 }
			return $item_parts;
    }
    
    /*
    public static function get_products_by_sub_group($car_version , $group_id , $lang , $req){
      $car_spare_parts_item = kromedaHelper::get_sub_products_by_sub_group($car_version , $group_id, $lang);
	  $item_parts = collect($car_spare_parts_item);
	  $get_products_database = Products::get_admin_products($req);
				 if($get_products_database->count() > 0){
						 $all_filterd_data_arr = $get_products_database->pluck('kromeda_products_id')->all();
						 foreach($item_parts as $kromeda_parts_item){
							$all_filtered_parts = $item_parts->filter(function ($kromeda_parts_item) use ($all_filterd_data_arr) {
							 return !in_array($kromeda_parts_item->idVoce , $all_filterd_data_arr);
							}); 
							$item_parts = $all_filtered_parts;
						}
				 }
			return $item_parts;
   }*/
   
 /*  public static function get_products_by_group($car_version , $group_id , $lang , $req){
	 $car_spare_parts_item = kromedaHelper::get_products_by_group($car_version , $group_id, $lang);
	  $item_parts = collect($car_spare_parts_item);
	  $get_products_database = Products::get_admin_products($req);
				 if($get_products_database->count() > 0){
						 $all_filterd_data_arr = $get_products_database->pluck('kromeda_products_id')->all();
						 foreach($item_parts as $kromeda_parts_item){
							$all_filtered_parts = $item_parts->filter(function ($kromeda_parts_item) use ($all_filterd_data_arr) {
							 return !in_array($kromeda_parts_item->idVoce , $all_filterd_data_arr);
							}); 
							$item_parts = $all_filtered_parts;
						}
				 }
			return $item_parts;	 
   }*/
   
   public static function date_format_for_database($date , $type = NULL){
	/* Type == 1 => for front web*/
	  if($type != NULL){
		 $final_date = (date("Y-m-d H:i", strtotime($date))); 
		 return $final_date; 
		}
	  $final_date = (date("Y-m-d", strtotime($date)));
	  return $final_date;
  }

   public static function change_time_formate($time){
       $time = strtotime($time);
       return date('H:i:s',$time);
   }
   
   public static function change_time_format_2($time){
     if(!empty($time)){
	   $time_arr = explode(":" , $time);
	   return $time_arr[0].":".$time_arr[1];
	 }
	 else{ return "00:00";  }
   }
   
   public static function get_seller_owner($seller_id){
	  /*  return \App\User::find($seller_id); */
	   	$company_name =  \App\User::where('id' , '=' ,$seller_id)->get();
	     if($company_name->count() > 0) {
			 return $company_name[0]['company_name'];
		 } else {
			 return "N/A";
		 }
	}
       
   public static function timeToNum($time) {
	   preg_match('/(\d\d):(\d\d)/', $time, $matches);
	   return 60*$matches[1] + $matches[2];
	}
	
	public static function numToTime($num) {
	   $m  = $num%60;
	   $h = intval($num/60) ;
	   return ($h>9? $h:"0".$h).":".($m>9? $m:"0".$m);
	
	}
	
	// substraction interval $b from interval $a
	public static function sub($a,$b) {
		// case A: $b inside $a
		if($a[0]<=$b[0] and $a[1]>=$b[1]) return [ [$a[0],$b[0]], [$b[1],$a[1]] ];
	
		// case B: $b is outside $a
		if($b[1]<=$a[0] or $b[0]>=$a[1]) return [ [$a[0],$a[1]] ];
	
		// case C: $a inside $b
		if($b[0]<=$a[0] and $b[1]>=$a[1]) return [[0,0]]; // "empty interval"
	
		// case D: left end of $b is outside $a
		if($b[0]<=$a[0] and $b[1]<=$a[1]) return [[$b[1],$a[1]]];
	
		// case E: right end of $b is outside $a
		if($b[1]>=$a[1] and $b[0]>=$a[0]) return [[$a[0],$b[0]]];
	}


	public static function cutOpeningHours($op_h, $occ_slot) {
		$subsn = [];
		foreach($op_h as $oh) {
			$ohn = [self::timeToNum($oh[0]), self::timeToNum($oh[1])];
			$osn = [self::timeToNum($occ_slot[0]), self::timeToNum($occ_slot[1])];
			$subsn[] = self::sub($ohn, $osn);
		}
		return $subsn;
	 }
	 
	public static function flatAndClean($interwals) {
		$result = [];
		foreach($interwals as $inter) {
			foreach($inter as $i) {
				if($i[0]!=$i[1]) {
					//$result[] = $i;
					$result[] = [self::numToTime($i[0]), self::numToTime($i[1])];
				}
			}
		}
		return $result;
	} 


	public static function get_time_slot($booked_slot , $opening_slot){
	    //$opening_hours   = [['03:00','06:00']];
		$opening_hours = $opening_slot;
		$occupied_slots = $booked_slot;
		//$occupied_slots  = [['03:30','04:00'], ['04:10','05:35']];
		//$expected_result = [['08:00','09:30'], ['11:00','12:00'], ['14:00','15:10'], ['16:35','18:00']];
		$valid_timeslots = [];
		#find empty timeslots during opening hours given occupied slots
		 
		 // flat array and change numbers to time and remove empty (zero length) interwals e.g. [100,100]
		 // [[ [167,345] ], [ [433,644], [789,900] ]] to [ ["07:00","07:30"], ["08:00","08:30"], ["09:00","09:30"] ] 
		 // (number values are not correct in this example)
		 
		 
		 // calculate new_opening_hours = old_opening_hours - occupied_slot
		 
		 
		 $oph = $opening_hours;
		 foreach($occupied_slots as $os) {
			 $oph = self::flatAndClean(self::cutOpeningHours($oph, $os ));
		 }
		 
		 $valid_timeslots = $oph;
		 return $valid_timeslots;
	}


    
    public static function get_number_of_hour($start_time , $end_time){
		//$interval =  $end_time - $start_time;
		$str_start_time = strtotime($start_time);
		$str_end_time = strtotime($end_time);
		$diff = abs($str_end_time - $str_start_time) / 60;
		$hour = $diff / 60;
		//$diff = abs($str_end_time - $str_start_time);
		return $hour;
	}
    
    public static function get_set_language($language){
		if(!empty($language) || !empty($language)){
		    if($language == "en") return  "ENG";
			else return "ITA";
		}
	}
    
	/*Get Car wash services maximum appointment  and hourly rate */
public static function car_wash_price_max_appointment($user_id , $service_id , $car_size){
      $service_price = \DB::table('services')->where([['users_id' , '=' , $user_id] , ['category_id' , '=' , $service_id] , ['car_size' , '=' , $car_size]])->first();
	  if($service_price != NULL){
		  return array('hourly_rate'=>$service_price->hourly_rate , 'max_appointment'=>$service_price->max_appointment);
		}
	  else{
		  $workshop_payment_details = \App\WorkshopServicesPayments::get_service_price_max(Auth::user()->id , 1);
		  if($workshop_payment_details != NULL){
			 return array('hourly_rate'=>$workshop_payment_details->hourly_rate , 'max_appointment'=>$workshop_payment_details->maximum_appointment);
			}
		}	
	}
  /*End*/	
	
	public static function get_car_size($car_size){
	   if(!empty($car_size)){
		  $car_size_arr = [1=>"Small" , 2=>"Average" , 3=>"Big"];
	      if(array_key_exists($car_size , $car_size_arr)){
	         return $car_size_arr[$car_size];	  
	      }
	      else{
	         return "Average"; 
	      }
	     }
	   return "N/A";	 
	}
    
	public static function get_workshop_owner($workshop_owner_id){
	   return \App\User::find($workshop_owner_id);
	}
    
    
    /*Kromeda Api Get Response*/
	public static function Get_kromeda_Request($sessKey , $func , $auth , $str = ''){
            $base_url='https://krws.autosoft.it/ws/krwsrest_v12.dll/datasnap/rest/tkrm/';
            $base_urllast='ws-officinetop/tphs82ja92/';
            $url=$base_url.$func.'/'.($auth==true?$base_urllast:'').($sessKey==false?'':$sessKey.'/').$str;
            $client = new \GuzzleHttp\Client();
            $request = $client->get($url);
            $response = $request->getBody()->getContents();
            $response = trim($response);
            $response = json_decode($response);
            return $response;
    }
	/*End*/
    
    
    /*Generate Session Key Script start*/
	public static function generate_kromeda_session_key(){
		$session_key = FALSE;
	   //$session_key = Kromeda::get_response_api("sessionKey");
	   if($session_key == FALSE){
		   $session_key_response = self::Get_kromeda_Request(false , 'CreateSessionKey' , true , '');
		   if($session_key_response != null || !empty($session_key_response->result[1])){
			   $add_result = Kromeda::add_response("sessionKey" ,$session_key_response);
				$session_key = $session_key_response->result[1];
				if(!empty($session_key)){
					$check_resp = self::Get_kromeda_Request($session_key , 'CheckSessionKey' , true , '');
					if($check_resp->result[1]){
						return $session_key;
					  }
					 else{
						return 500;
					  }
				  }
				 else{
					   return 500;
				  }
			 }
		   else{
				return 500;
			  }
		 }
	   else{
			   $session_key_new = $session_key->result[1];
				if(!empty($session_key_new)){
					$check_resp = self::Get_kromeda_Request($session_key_new , 'CheckSessionKey' , true , '');
					if($check_resp->result[1]){
						return $session_key_new;
					  }
					 else{
						return 500;
					  }
				  }
				 else{
					 return 500;
				 }
		 }
	 }
	 /*End*/

    
    public static function get_api_data($method , $url){
	        $client = new \GuzzleHttp\Client();
	        $response = $client->request($method , $url);
	        $response = $response->getBody()->getContents();
	        $response = trim($response);
            $response = json_decode($response);
		    return $response;
	}
	
	public static function get_discount_price($total_price , $discount_amount , $type){
		if($type == 1){
		  return  $discount_amount;
		}
		elseif($type == 2){
		  $discount_price =  ($total_price * $discount_amount) / 100 ;
		  if(!empty($discount_price)){
			  return $discount_price;
		  }
		}
		else{
			return 	$total_price;
		}
  }


  public static function make_discount_price($total_price , $discount_amount , $type){
	if($type == 1){
	  return   $total_price - $discount_amount;
	}
	else{
	  $discount_price =  ($total_price * $discount_amount) / 100 ;
	  if(!empty($discount_price)){
		  return $total_price - $discount_price;
	  }
	}
}

  public static function find_day_from_date($selected_date){
	return date("d", strtotime($selected_date));  
  }
  
	public static function find_month_from_date($selected_date){
		return date("m", strtotime($selected_date));  //F for month in letter like (December)
	}

	public static function get_next_time($start_time , $time_in_min){
		$time_in_sec = $time_in_min * 60;
		$timestamp = strtotime($start_time) + $time_in_sec;
		$time = date('H:i', $timestamp);
		return $time;
	}



	
	

    public static function get_week_days_id($selected_date){
	   $dayOfWeek = date("l", strtotime($selected_date));
       $days_arr =  \DB::table('common_weekly_days')->where('name' , '=' , trim($dayOfWeek))->first();
	   if($days_arr != NULL){
		   return $days_arr->id;
		 }
	   else return FALSE;	 
	} 
	
   public static function get_service_packages($service_days_id){
        return \App\Services_package::get_services_packages($service_days_id);
     }
   	
   
   public static function get_respFormat_2($status , $msg , $data , $dates , $data_set){
       return response()->json(array('status_code'=>$status , 'message'=>$msg ,  'data'=>$data   , 'dates'=>$dates ,'data_set'=>$data_set));
    }
   
   
   public static function get_respFormat($status , $msg , $data , $data_set){
       return response()->json(array('status_code'=>$status , 'message'=>$msg ,  'data'=>$data  ,'data_set'=>$data_set));
	}
	

	public static function get_car_wash($parent_cat_id){
        return DB::table('categories')
		           ->where([['category_type' , '=' , $parent_cat_id] ,  ['status' , '!=' , 1]])->orderBy('category_name' , 'DESC')
				   ->orderBy('category_name' , 'DESC')
				   ->get();
    }
   
   
   public static function get_subcategory_1($parent_cat_id){
        return DB::table('categories as a')
		           ->leftjoin('service_time_prices as b' , 'b.categories_id' , '=' , 'a.id')
		           ->leftjoin('services as s' , 's.category_id' , '=' , 'a.id')
				   ->select('a.*' , 'b.small_time' , 'b.average_time' , 'b.big_time', 's.hourly_rate', 's.max_appointment', 's.car_size', 's.id as service_id')
				   ->where([['a.category_type' , '=' , $parent_cat_id] , ['s.users_id', '=', Auth::user()->id], ['a.status' , '!=' , 1]])->orderBy('a.category_name' , 'DESC')
				   ->orderBy('a.category_name' , 'DESC')
				   ->get();
    }
   
   public static function get_subcategory($parent_cat_id){
	    return Category::where([['parent_cat_id' , '=' , $parent_cat_id] , ['status' , '!=' , 1]])->orderBy('category_name' , 'DESC')->get();
	  
        /* $get_child_cat = Category::where([['parent_cat_id' , '=' , $parent_cat_id] , ['status' , '!=' , 1]])->orderBy('category_name' , 'DESC')->get();
	   if($get_child_cat->count() > 0){
		   return $get_child_cat;
		 }
	   else{
		  return FALSE;
		  } */	 
    }
	
        
    public static function car_model_details($car_makers , $model_name){
		if(!empty($car_makers) && !empty($model_name)){
			$car_makers_slug = sHelper::slug($car_makers);
			$car_model_slug = sHelper::model_slug($model_name);
			$base_url = "https://api.wheel-size.com/v1/";
			$database_url = "models/$car_makers_slug/$car_model_slug/";
			$model_image_response = \App\ModelImage::where([['model_slug' , '=' , $database_url]])->first();
			if($model_image_response == NULL){
				$get_database_response = Kromeda::js_get_response_api($database_url);
				if($get_database_response == FALSE){
					 $last_url = "?user_key=b8f5788d768c1823dd920c2576b644f9";
					 $url = $base_url.$database_url.$last_url; 
					 $client = new Client(['base_uri' =>$base_url]);
					 try {
							 $response = $client->request('GET', $url);
							 $response = $response->getBody()->getContents();
							 $response = trim($response);
							 $response = json_decode($response);
							 Kromeda::add_response($database_url , $response);
							 
						 } catch (RequestException $e) {
							 $response = 404;
							 Kromeda::add_response($database_url , 404);
						 }
						 $get_database_response = Kromeda::js_get_response_api($database_url);
				  }
				  if(is_object($get_database_response)){
					  if(!empty($get_database_response->generations[0]->bodies[0]->image)){
						  $image_name = $get_database_response->generations[0]->bodies[0]->image;
						  if(!empty($image_name)){
							  $model_image_response = \App\ModelImage::updateOrCreate(['model_slug'=>$database_url] , ['model_slug'=>$database_url , 'image_url'=>$image_name]);
							  return $model_image_response->image_url;
						  }
					  }else{
						 return null;
					  }
				  }
				else return null; 	 
			}
			else{
				return $model_image_response->image_url;
			}
		  }
		else return null; 	 
	  }
    
    public static function get_parent_category($category_type = 1){
	   $category = Category::where([['parent_cat_id' , '=' , 0] , ['status' , '!=' , 1], ['category_type' , '=' , $category_type]])->orderBy('category_name' , 'DESC')->get();
	   if($category != NULL){
		    $category_response = '';
			foreach($category as $cat){
				 //$category_response   .= self::get_child_cat_print($cat->category_name , $cat->id); 
				 $category_response .= "<option value='".$cat->id."'>".$cat->category_name."</option>";
			     $category_response .= self::get_child_cat($cat->category_name , $cat->id); 
			  }
			return $category_response;  
		 }	
    }
	
	public static function get_child_cat($cat_name , $parent_id){
		  $category_response = '';
		  $get_child_cat = Category::where([['parent_cat_id' , '=' , $parent_id] , ['status' , '!=' , 1]])->orderBy('category_name' , 'DESC')->get();
		  if($get_child_cat != NULL){
				$parent_cat_name = ''; $final_cat_name = '';
				foreach($get_child_cat as $subcat){
				     //$category_response .= self::get_child_cat_print($subcat->category_name, ">>"); 
					 $parent_cat_name = $cat_name." >> ";
					 $final_cat_name = $parent_cat_name.$subcat->category_name;
					 $category_response .= "<option value='".$subcat->id."'>".$final_cat_name."</option>";
				     $category_response .= self::get_child_cat($final_cat_name , $subcat->id);
				   }
			 }
		  else { $symbol = ""; } 
		 return $category_response;   	 
	}
	
	
   
   public static function get_category_table(){
	  $category = Category::where([['parent_cat_id','=',0] , ['status', '!=' , 1]])->get();
	   
if($category != NULL){
		    $category_response_table = '';
            foreach($category as $cat){
				$category_response_table .= self::print_category_table($cat->description , $cat->category_name , $cat->id  ,  $cat->cat_image_url); 			
				$category_response_table .= self::get_child_cat_table($cat->category_name , $cat->id ); 
			  self::$num_of_row++;
              }
			return $category_response_table;  
		 }	
    } 
	
	
	public static function get_child_cat_table($cat_name , $parent_id){
		  $category_response_table = '';
		  $get_child_cat = Category::where([['parent_cat_id' , '=' , $parent_id] , ['status' , '!=' , 1]])->get();
		  if($get_child_cat != NULL){
				$parent_cat_name = ''; $final_cat_name = '';
				foreach($get_child_cat as $subcat){
				     self::$num_of_row++;
                     $parent_cat_name = $cat_name." >> ";
					 $final_cat_name  = $parent_cat_name.$subcat->category_name;
					 $category_response_table .= self::print_category_table($subcat->description , $final_cat_name , $subcat->id  , $subcat->cat_image_url); 
					  
				     $category_response_table .= self::get_child_cat_table($final_cat_name , $subcat->id);
				   }
			 }
		  else { $symbol = ""; } 
		 return $category_response_table;   	 
	}
	
	public static function print_category_table($description ,$final_cat_name ,$cid  ,  $image_name = NULL ){
	  $category_response_table = '';	
      $category_response_table .= '<tr>
                                                <td>'.self::$num_of_row.'</td>
												 <td><img src='.$image_name.' style="height:50px;"/></td>
                                                 <td>'.$final_cat_name.'</td>
                                                 <td>'.$description.'</td>
                                                 <td class="text-center">
                                                      <a href="'.url("admin/edit_category/$cid").'" class="btn btn-primary">'.__('messages.Edit').'</a>
                    								 
													  <a href="#" data-toggle="tooltip" data-placement="top" title="Add Images" data-catid="'.$cid.'" class="btn btn-primary add_car_wash_image_btn btn-sm" ><i class="fa fa-picture-o"></i></a>
														
													  <a  data-toggle="tooltip" data-placement="top" title="Remove Category" href="'.url("master/delete_cat/$cid").'" class="btn btn-danger delete_cat"><i class="fa fa-trash" ></i></a>
                                                 </td>
                                              </tr>';
      return $category_response_table;											  
	}    
    
   public static function get_percentage($total_users , $number_of_user){
	  $percentage =  ($number_of_user*100)/$total_users;
	  return round($percentage , 2)."%";
   }
   
    public static function get_users_category($workshop_id){
       $categories = \App\Workshop_users_category::get_categories($workshop_id);
	   $html_content = '';
	   $html = '';
	   if($categories != NULL){
		   $catArr = [];
		   foreach($categories as $cat){
	   //$html .= '<a class="font-weight-semibold mr-3">'.ucfirst($cat->category_name).' , </a>';
		      $catArr[] = ucfirst($cat->category_name);
			  //$html .= ucfirst($cat->category_name)." , ";
			  // $html_content .='<h6 class="m-0 font-weight-semibold">'.ucfirst($cat->category_name).'</h6>'; 
			 }
		 }
		return implode(',', $catArr);
		//return trim($catArr, ','); 
		//print_r($catArr);exit;
		//return $html;
     }
   
   public static function get_workshop_timing($workshop_user_days_id){
     return  \App\Workshop_user_day_timing::where([['workshop_user_days_id' , '=' , $workshop_user_days_id] , ['deleted_at' , '=' , NULL]])->get();
   } 
  
   
   public static function print_other_profile_image($image , $gender ,  $user_id = NULL , $paid_status , $my_profile_status){
       if(!empty($image)){
             $paid_status_value = 0;
		      if(!empty($paid_status) && !empty($my_profile_status)){
		         $paid_status_value = 1;     
		        }
           
		   if($user_id == session()->get('knpuser')){
		     ?>
		      <img src="<?php echo $image; ?>"  class=" img-responsive transition" alt="profile image">
		     <?php           
		    }
		   else{
		      
		       ?>
		       <img src="<?php echo $image; ?>"  class="img-responsive transition <?php if(empty($paid_status_value)) echo "blur_css"; ?>" alt="profile image" draggable = "FALSE" oncontextmenu="return false; " >
		       <?php
		   } 
		 }
	   else{
	       
		   if($gender == "M"){
		       //echo asset('cdn/images/icon/men.jpg');exit;
		      ?>
			 <img src="https://kanpurize.com/cdn/images/icon/men.jpg" class=" img-responsive transition" alt="profile image" draggable = "FALSE"  oncontextmenu="return false; " />
              <?php
			 }
		   if($gender == "F"){
			   ?>
			    <img src="https://kanpurize.com/cdn/images/icon/female.jpg" class="img-responsive transition" alt="profile image" draggable = "FALSE"  oncontextmenu="return false; ">
			   <?php
			 } 
         }
   }
   
  
   

  
   
    public static function  short_profile($myid , $profile_id){
       $result =   Matrimonial_model::get_duplicate_shortlist($myid , $profile_id);
	   if($result != FALSE){
		   ?>
		    <a  style="float:right;" title="shortlist" ><span style="color:#FF5722;" class="fa fa-heart"></span></a>
		   <?php
		 }
		else{
		   ?>
		   <a class="mat_profile_short_listed" style="float:right; cursor:pointer;  title="shortlist" data-shortprofileid="<?php if(!empty($profile_id))echo $profile_id; ?>"><span  class="fa fa-heart"></span></a>
		   <?php
		} 
  }
   
  
 
  
  

  public static function change_in_array($religion){
     $arr = explode("@" , $religion);
     if(count($arr) > 0){
         return $arr; 
     } else FALSE;
  }
  
  
  public static function change_in_string($string){
     if(!empty($string)){
         $str =  str_replace("@" , " , " , $string);
          return $str;
     }
     else{ 
         return FALSE;
     }
    
  }    
    
  public static function get_how_many_year_old($dob){
    //  return $dob;
    $today = date("Y-m-d");
    $diff = date_diff(date_create($dob), date_create($today)); 
    return $diff->format('%y');
   }
        
    
   
	
	
	public static function slug($text){
	   $text = preg_replace('~[^\pL\d]+~u', '-', $text);
	   $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		  $text = preg_replace('~[^-\w]+~', '', $text);
		  $text = trim($text, '-');
		  $text = preg_replace('~-+~', '-', $text);
		  $text = strtolower($text);
		if (empty($text)) {
			return 'n-a';
		  }
		return $text;
	}
	
	public static function is_english($str){
		if (strlen($str) != strlen(utf8_decode($str))) {
			return FALSE;
		} else {
			return $str;
		}
	}


	

	public static function model_slug_2($model_name){
		$str_arr = explode(' ',$model_name);
		$new_str = [];
		 $other_language_exists = FALSE;	
		foreach($str_arr as $key=>$str){
			$str_result = self::is_english($str);
			if($str_result != FALSE){
				$new_str[] = strtolower($str_result);
			}
			else{
		        $other_language_exists = TRUE;	    
			}
		}
		if($other_language_exists == TRUE){
		     return $new_str[0];
		  }
		else{
		    $implode_string = implode('-' ,$new_str);
	        return $implode_string;
		  }  
	}


     public static function model_slug($model_name){
		//$model_name1 = "100 Â«4A2Â»";
		$str_arr = explode(' ',$model_name);
		$new_str = [];
		foreach($str_arr as $key=>$str){
			$str_result = self::is_english($str);
			if($str_result != FALSE){
				$new_str[] = strtolower($str_result);
			}
		}
		$implode_string = implode('-' ,$new_str);
	    return $implode_string;
	}
    
    
   
   
    public static function distance($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $km = $miles * 1.609344;
        if ($km < 1){
            return round($miles * 1609.344).' Meter';
        }
        return round($km, 2).' Km';
	}
	
    public static function notifications(){
        if (self::$notifications == null){
            $notifications = [];
            $user = Auth::user();
            $followers = $user->follower()->where('allow', 0)->count();
            if ($followers > 0){
                $notifications[] = [
                    'url' => url('/followers/pending'),
                    'icon' => 'fa-user-plus',
                    'text' => $followers.' follower requests'
                ];
            }
            $relatives = $user->relatives()->where('allow', 0)->count();
            if ($relatives > 0){
                $notifications[] = [
                    'url' => url('/relatives/pending'),
                    'icon' => 'fa-user-circle-o',
                    'text' => $relatives.' relatives requests'
                ];
            }
            $comments = PostComment::where('seen', 0)->with('user')->join('posts', 'posts.id', '=', 'post_comments.post_id')
                ->where('posts.user_id', $user->id)->where('user_id', '!=', $user->id)->select('post_comments.*')->orderBy('id', 'DESC');
            if ($comments->count() > 0){
                foreach ($comments->get() as $comment){
                    $notifications[] = [
                        'url' => url('/post/'.$comment->post_id),
                        'icon' => 'fa-commenting',
                        'text' => $user->name.' left a comment on your post.'
                    ];
                }
            }
            $likes = PostLike::where('seen', 0)->with('user')->join('posts', 'posts.id', '=', 'post_likes.post_id')
                ->where('posts.user_id', $user->id)->where('user_id', '!=', $user->id)->select('post_likes.*')->orderBy('id', 'DESC');
            if ($likes->count() > 0){
                foreach ($likes->get() as $likne){
                    $notifications[] = [
                        'url' => url('/post/'.$likne->post_id),
                        'icon' => 'fa-heart',
                        'text' => $user->name.' liked your post.'
                    ];
                }
            }
            $follow= UserNotification::where(['read_at' => NULL, 'notifiable_id' => $user->id])->orderBy('created_at', 'DESC')->take(10); 
            if ($follow->count() > 0){
                foreach ($follow->get() as $row){
                    $notifications[] = [
                        'url' => url('#'),
                        'icon' => 'fa-user',
                        'text' => $row->data
                    ];
                }
                UserNotification::where('read_at', NULL)->update(['read_at' => date('Y-m-d h:i:s')]); 
            }
            
            self::$notifications = $notifications;
        }
        return self::$notifications;
	}
	
    public static function ip($request){
        $ip = $request->headers->get('CF_CONNECTING_IP');
        if (empty($ip))$ip = $request->ip();
        return $ip;
	}
	
    public static function alternativeAddress($ip, $id){
        $query = IPAPI::query($ip);
        if ($query->status == "success") {
            $country_name = $query->country;
            $lat = $query->lat;
            $lon = $query->lon;
            $city = $query->city;
            $country_code = $query->countryCode;
            $find_country = Country::where('shortname', $country_code)->first();
            $country_id = 0;
            if ($find_country) {
                $country_id = $find_country->id;
            } else {
                $country = new Country();
                $country->name = $country_name;
                $country->shortname = $country_code;
                if ($country->save()) {
                    $country_id = $country->id;
                }
            }
            $city_id = 0;
            if ($country_id > 0) {
                $find_city = City::where('name', $city)->where('country_id', $country_id)->first();
                if ($find_city) {
                    $city_id = $find_city->id;
                } else {
                    $city = new City();
                    $city->name = $city;
                    $city->zip = "1";
                    $city->country_id = $country_id;
                    if ($city->save()) {
                        $city_id = $city->id;
                    }
                }
            }
            if (!empty($lat) && !empty($lon) && !empty($city) && !empty($country_code) && !empty($city_id) && !empty($country_id)) {
                self::updateLocation($id, $city_id, $lat, $lon, $city);
            }
        }
	}
	
    public static function updateLocation($id, $city_id, $lat, $long, $address){
        $find_location = UserLocation::where('user_id', $id)->first();
        if (!$find_location) {
            $find_location = new UserLocation();
            $find_location->user_id = $id;
        }
        $find_location->city_id = $city_id;
        $find_location->latitud = $lat;
        $find_location->longitud = $long;
        $find_location->address = $address;
        $find_location->save();
    }
    
    /*Wheel Size Api authentication and response*/
	    /*Wheelsize Authentication */
		  public static function get_wheelsize_response($sring_param){
			$status = 200; 
			$url = "https://api.wheel-size.com/v1/$sring_param";
			   try{
				   $client = new \GuzzleHttp\Client();
				   $request = $client->get($url);
				   $response = $request->getBody()->getContents();
				   $status = 200;
			   }
			   catch(RequestException  $e){ 
				 $response = 500;
			 }
			 return json_encode(['status'=>$status, 'response'=>$response]);
		}	 
		/*End*/
    
    /*Public static function get_model_details*/
		  public static function get_model_details($maker_slug , $model){
			   $user_key = "user_key=b8f5788d768c1823dd920c2576b644f9";
			   $model_slug = self::model_slug_2($model->Modello);
			   $url = "models/$maker_slug/$model_slug/$model->ModelloAnno";
			 /*Get Response From Database*/
				$result_response = \App\Model\Kromeda::get_wheelsize_response($url);
				if($result_response == NULL){
					$main_url = $url."/?".$user_key;
					$api_response = self::get_wheelsize_response($main_url);
					$decode_response = json_decode($api_response);
					if($decode_response->status == 200){
						$response = $decode_response->response;
						if($response != 500){
						 $save_in_database_response = \App\Model\Kromeda::save_wheel_size_response($url , $response);
    						if($save_in_database_response){
    							return json_encode(['status'=>200 , 'response'=>$save_in_database_response->response]);
    						}
    						else{
    							return json_encode(['status'=>100]);
    						}   	
						}
						else{
    							return json_encode(['status'=>100]);
    						} 
						
					 }
					else{
						return json_encode(['status'=>100 , 'response'=>$save_in_database_response->response]);
					}
				}
				else{
					return json_encode(['status'=>200 , 'response'=>$result_response->response]);
				}
			 /*End*/  
			     
			}
		/*End*/
    
    
    /*Get All Tyres*/
		public static function get_tyres($model_details){
			$user_key = "user_key=b8f5788d768c1823dd920c2576b644f9";
			$url = "tires";
			$result_response = \App\Model\Kromeda::get_wheelsize_response($url);
			if($result_response == NULL){
				$main_url = $url."/?".$user_key;
				$api_response = self::get_wheelsize_response($main_url);
				$decode_response = json_decode($api_response);
				if($decode_response->status == 200){
					$response = $decode_response->response;
					$save_in_database_response = \App\Model\Kromeda::save_wheel_size_response($url , $response);
					if($save_in_database_response){
					   return json_encode(['status'=>200 , 'response'=>$save_in_database_response->response]);
					}
					else{
						return json_encode(['status'=>100]);
					}
				 }
				else{
					return json_encode(['status'=>100 , 'response'=>$save_in_database_response->response]);
				}
			}
			else{
				return json_encode(['status'=>200 , 'response'=>$result_response->response]);
			}
		}
		/*End*/
    
     /*Save Tyre 24 get tyere API response*/
	 public static function save_tyre24response($width , $aspect_ration , $dia_meter){
		set_time_limit(1500);
		$search_string = $width.$aspect_ration.$dia_meter;
			$string_arr = $width."/".$aspect_ration."/".$dia_meter;
			$param_arr = ['ns1:searchString'=>$search_string, 'ns1:minAvailability'=>1];
			/*Method name , min availaibility , max_tyre_size*/
			//$max_size_tyre = str_replace(" " , "" , $max_tyre_size[0]);
			$url = "get_tyre24/1/".$search_string;
			/*Get response from kromeda*/
			$response = \App\Model\Kromeda::get_tyre24_response($url);
			/*End*/
			if($response == NULL){
				$response = apiHelper::get_soap_response($param_arr , "getTyres");
				if($response != FALSE){
					$xml = simplexml_load_string($response);
					$body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->getTyresResponse;
					$detail_response = json_encode((array)$body); 
					$save_response = \App\Model\Kromeda::save_tyre24_response($url , $detail_response);
					if($save_response){
						$response = \App\Model\Kromeda::get_tyre24_response($url);
					}
				}

			}
			if($response != NULL){
				$tyre_response = json_decode($response->response);
				if(count((array) $tyre_response) > 0){
					$save_response =  \App\Tyre24::save_tyre_response_2($search_string , $tyre_response, $string_arr);
					if($save_response != FALSE){
							return json_encode(array("status"=>200)); 
						}
						else{
							return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Record Not found  from tyre24 !!! </div>')); 
						}
					}
					else{
						return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong  </strong> Record Not found  from tyre24 !!! </div>')); 
					}
				}
				else{
					return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Record Not found from tyre24 !!! </div>')); 
				}
	  }
	/*End*/






		public static function get_maker_helper($maker_name){
		   $sring_param = "brands=".$maker_name;
		   $method = "makes";
		   return self::get_wheelsize_response($method,$sring_param);
		}
		/*End*/
		/*Get Workshop tyre24 service details */
		public static function get_workshop_tyre24_service_detail($user_id , $category_id){
			return WorkshopTyre24Details::where([['workshop_user_id' , '=' , $user_id] , ['category_id' , '=' , $category_id] , ['deleted_at' , '=' , NULL]])->first();
		}
 		/*End*/
 		 public static function get_sos_workshop_service_time($category_id){
        $get_services_times = DB::table('service_time_prices')
							->where([['categories_id' , '=' ,$category_id]])
							->first();
	     if($get_services_times != NULL){
			   return $get_services_times;
			}
	 }


	  /*Check time slots for car revision*/
	  public static function check_time_slots_for_car_revision($time_slots , $service_detail){
		 return 1;
      }
      /*End*/
 
	 

     public static function check_time_slot_for_emergency($time_slots ,  $service_average_times , $request){
		 $opening_slot = $not_aplicable_slot = $new_booked_list = [];
		 foreach($time_slots as $slot){
			 $opening_slot[] = [$slot->start_time , $slot->end_time]; 
		 }
		$special_condition_obj = new SpecialCondition;
		$special_condition_status = $special_condition_obj->do_not_perform_operation_for_emergency($request , $time_slots , $service_average_times);
		$decode_response = json_decode($special_condition_status);
        if($decode_response->status == 200){
		   $not_aplicable_slot = $decode_response->response;
		}
		$new_generate_slots = sHelper::get_time_slot($not_aplicable_slot, $opening_slot);
		/*Get Booked slots*/
		  $booked_slots = \App\ServiceBooking::where([['workshop_user_id','=',(int) $request->workshop_id] ,
														['type' , '=' , 6] ,
														['wrecker_service_type' , '=' , 2] ,
														['services_id' , '=' ,$request->service_id]])
												->whereDate('booking_date' , $request->selected_date)->get();
		/*End*/
	      if($booked_slots->count() > 0){
			  foreach($booked_slots as $booked){
				  $new_booked_list[] = [$booked->start_time, $booked->end_time];
			  }
		  }
		  $new_generate_slots = sHelper::get_time_slot($new_booked_list, $new_generate_slots);
		  if(count($new_generate_slots) > 0){
			  foreach($new_generate_slots as $slot){
				 $get_slot_time_in_hour = sHelper::get_number_of_hour($slot[0], $slot[1]);
				 if ($get_slot_time_in_hour >= $service_average_times)  $flag = 1;
				 else  $flag = 0; 
			  }
		  }
		  else { $flag = 0; }
     return $flag;  

	 }


     /*Check time slot script status */
	 public static function check_time_slot_sos($time_slots , $workshop_wracker_service_details , $request , $service_average_times ,$wracker_service_type){
		$flag = 0;
		//print_r($service_average_times); die;
		$opening_slot = $not_aplicable_slot = $new_booked_list = [];
		foreach($time_slots as $slot){
			$opening_slot[] = [$slot->start_time , $slot->end_time]; 
		}
		$special_condition_obj = new SpecialCondition;
		$special_condition_status = $special_condition_obj->do_not_perform_operation_sp_cond($request ,  $request->workshop_id , $service_average_times);
	    $decode_response = json_decode($special_condition_status);
	   if($decode_response->status == 200){
		  $not_aplicable_slot = $decode_response->response;
	   }
	   $new_generate_slots = sHelper::get_time_slot($not_aplicable_slot, $opening_slot);
	   /*Get Booked slots*/
		/* $booked_slots = \App\ServiceBooking::where([['workshop_user_id','=',(int) $request->workshop_id] ,
													   ['type' , '=' , 6] ,
													   ['wrecker_service_type' , '=' , 1] ,
													   ['services_id' , '=' ,$request->service_id]])
											   ->whereDate('booking_date' , $request->selected_date)->get(); */
			$query = \App\ServiceBooking::where([['workshop_user_id','=',(int) $request->workshop_id] ,
											   ['type' , '=' , 6] ,
											   ['wrecker_service_type' , '=' , 1] ,
											   ['services_id' , '=' ,$request->service_id]]);
			$query->whereDate('booking_date' , $request->selected_date);
			if(!empty($request->user_id)){
				$query->orWhere([['users_id' , '=' ,$request->user_id] , ['status' , '=' ,'P'], ['status', '=', 'CA'] , ['type' , '=' , 4]]);
			}
			$booked_slots = $query->get();
		/*End*/
		// print_r($booked_slots); die;
		 if($booked_slots->count() > 0){
			 if($booked_slots->count() >= $workshop_wracker_service_details->max_appointment){
				 foreach($booked_slots as $booked){
					  $new_booked_list[] = [$booked->start_time, $booked->end_time];
				  }
				  $new_generate_slots_new = sHelper::get_time_slot($new_booked_list, $new_generate_slots);
				 
				  if(count($new_generate_slots_new) > 0){
					  foreach($new_generate_slots_new as $slot){
						 $get_slot_time_in_hour = sHelper::get_number_of_hour($slot[0], $slot[1]);
						 if ($get_slot_time_in_hour >= $service_average_times) 
						  $flag = 1;
						 else  $flag = 0; 
					  }
				  }
				  else { $flag = 0; }
			 }
			 else{
				$flag = 0; 
			 }
		 }
	    return $flag;  
	 }
	 
	 /*Check time slot script status */
	public static function check_trye_time_slot($time_slots , $selected_days_id , $request, $service_details, $tyre_groups_details , $tyre_detail){
		$opening_slot = $not_aplicable_slot = $new_booked_list = [];
		$flag = 0;
		foreach($time_slots as $slot){
			$opening_slot[] = [$slot->start_time , $slot->end_time]; 
		}
		$special_condition_obj = new SpecialCondition;
		$special_condition_status = $special_condition_obj->do_not_perform_operation_for_tyre($request , $time_slots , $tyre_detail ,$tyre_groups_details->time);
		$decode_response = json_decode($special_condition_status);
		if($decode_response->status == 200){
			$not_aplicable_slot = $decode_response->response;
		} 
		 
		$new_generate_slots = sHelper::get_time_slot($not_aplicable_slot, $opening_slot);
		$query = \App\ServiceBooking::where([['workshop_user_id','=',(int) $request->workshop_id] ,
											['type' , '=' , 4],['services_id' , '=' ,$request->service_id] , ['status' , '=' , 'C']]);
		$query->whereDate('booking_date' , $request->selected_date);
		if(!empty($request->user_id)){
			$query->orWhere([['users_id' , '=' ,$request->user_id] , ['status' , '=' ,'P'], ['status', '=', 'CA'] , ['type' , '=' , 4]]);
		}
		$booked_list = $query->get();
		if($booked_list->count() > 0){
			if($booked_list->count() < $service_details->max_appointment){
				foreach($booked_list as $booked){
					$new_booked_list[] = [$booked->start_time, $booked->end_time];
				}
			}
			else{  return 0;  }
		}
		$new_generate_slots_new = sHelper::get_time_slot($new_booked_list, $new_generate_slots);
		if(count($new_generate_slots_new) > 0){
			foreach($new_generate_slots_new as $slot){
				$get_slot_time_in_hour = sHelper::get_number_of_hour($slot[0], $slot[1]);
				if ($get_slot_time_in_hour >= $tyre_groups_details->time){ return 1; }
				else  { return 0; } 
			}
		}
		else { return 0; }
	}


	  	/* Car maintenance time slot*/
	  public static function check_car_maintenance_time_slot($time_slots ,  $service_average_times , $request , $service_id,$workshop_id){
		 $opening_slot = $not_aplicable_slot = $new_booked_list = [];
		 foreach($time_slots as $slot){
			 $opening_slot[] = [$slot->start_time , $slot->end_time]; 
		 }
		$special_condition_obj = new SpecialCondition;
		$special_condition_status = $special_condition_obj->do_not_perform_operation_for_car_maintenance(12 , $request->selected_date ,$workshop_id,$time_slots ,$request->selected_car_id ,$service_id , 1);
		$decode_response = json_decode($special_condition_status);
        if($decode_response->status == 200){
		   $not_aplicable_slot = $decode_response->response;
		}
		$new_generate_slots = sHelper::get_time_slot($not_aplicable_slot, $opening_slot);
		/*Get Booked slots*/
		  $booked_slots = \App\ServiceBooking::where([['workshop_user_id','=',(int) $request->workshop_id] ,
														['type' , '=' , 5] ,
														['services_id' , '=' ,$service_id]])
												->whereDate('booking_date' , $request->selected_date)->get();
		/*End*/
	      if($booked_slots->count() > 0){
			  foreach($booked_slots as $booked){
				  $new_booked_list[] = [$booked->start_time, $booked->end_time];
			  }
		  }
		  $new_generate_slots = sHelper::get_time_slot($new_booked_list, $new_generate_slots);
		  if(count($new_generate_slots) > 0){
			  foreach($new_generate_slots as $slot){
				 $get_slot_time_in_hour = sHelper::get_number_of_hour($slot[0], $slot[1]);
				 if ($get_slot_time_in_hour >= $service_average_times)  $flag = 1;
				 else  $flag = 0; 
			  }
		  }
		  else { $flag = 0; }
     return $flag;  

	  }
	/* Car maintenance time slot*/
	  public static function check_car_maintenance_time_slot123($workshop_id,$selected_days_id,$selected_date,$max_appointment,$service_id,$service_time){
		$flag = 0;
		$get_services_weekly_days = Workshop_user_day::get_service_weekly_days($workshop_id , $selected_days_id);
		 if($get_services_weekly_days != null) {
			$get_service_packages = Workshop_user_day_timing::get_packages($get_services_weekly_days->id);
		    if($get_service_packages->count() > 0){
			   foreach($get_service_packages as $package){
				  $opening_slot = [];
				  $new_booked_list = [];
				  $opening_slot[] = array($package->start_time, $package->end_time);
				  /*Get Booked packages*/
				  $booked_list=ServiceBooking::get_booked_package_car_maintenance($package->id, $selected_date ,5, $service_id);
				  if($booked_list->count() < $max_appointment){
					 if($booked_list->count() > 0) {
						foreach ($booked_list as $booked){
						  $new_booked_list[] = [$booked->start_time, $booked->end_time];
					    }
					   }
					  $new_generate_slot = sHelper::get_time_slot($new_booked_list, $opening_slot);
					  if(count($new_generate_slot) > 0) {
						foreach ($new_generate_slot as $slot_details) {
							$slot_details['start_time'] = sHelper::change_time_format_2($slot_details[0]);
							$slot_details['end_time'] = sHelper::change_time_format_2($slot_details[1]);
							$get_slot_time_in_hour = sHelper::get_number_of_hour($slot_details[0], $slot_details[1]);
							if(!empty($get_slot_time_in_hour)){
							    $new_time_in_hour = round($get_slot_time_in_hour , 2);
							    $total_service_time = $service_time + 0.33;
								if ($new_time_in_hour >= $total_service_time) {
									return 1;
								}
							  }
							else{ return $flag; }
						}
					}
					}
					else{
					   return $flag;
					 }
				  /*End*/
				  }
			  }
			else{
			    return $flag;
			  }

		   }
	  }
	  
	  public static function get_off_users_on_date($selected_date = NULL){
		$users_arr = [];
		if($selected_date == NULL){  $selected_date = date('Y-m-d'); }
		//$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
		//return $selected_date;
		$off_selected_date = \App\Workshop_leave_days::where([['off_date' , '=' , $selected_date] , ['status' , '=' , 'A'] , ['deleted_at' , '=' ,NULL]])->get();
		if($off_selected_date->count() > 0){
			$users_arr = $off_selected_date->pluck('users_id')->all();
		}
		return $users_arr;
	}

	  public static function get_near_by_users($latitude , $longitude){
		$circle_radius = 3959;
		return Address::select(DB::raw('id,users_id,latitude, longitude,address_1,address_2,address_3,zip_code, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
				->having('distance', '<', 10)
				//->groupBy('users_id')
				->get();
      }


	  /* public static function get_near_by_users($latitude , $longitude){
		$circle_radius = 3959;
		return Address::select(DB::raw('id,users_id,latitude, longitude,address_1,address_2,address_3,zip_code, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
				->having('distance', '<', 10)
				->groupBy('users_id')
				->get();
      }   */
   
	public static function get_location($request){
	    $latitude = $request->latitude;
		$longitude = $request->longitude;
		if (!empty($latitude) && !empty($longitude)) {
			$circle_radius = 3959;
			$max_distance = 10;
			/* $candidates = DB::select(
				'SELECT * FROM(SELECT id,users_id,latitude, longitude,address_1,address_2,address_3,zip_code, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(' . $lng . ')) +
                    sin(radians(' . $lat . ')) * sin(radians(latitude))))
                    AS distance
                    FROM addresses) AS distances join users as u on u.id = distances.users_id WHERE distance < ' . $max_distance . ' AND u.roll_id = 2 AND u.users_status="A" ORDER BY distance;'
			); */
			$candidates = Address::select(DB::raw('id,users_id,latitude, longitude,address_1,address_2,address_3,zip_code, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
			->having('distance', '<', 10)
			->groupBy('users_id')
			->get();
			return $candidates;
		}
	}

	public static function get_tyre_image($tyre){
		if($tyre->type_status = 1){
		 $tyre_detail = \App\TyreImage::where([['tyre_item_id' , '=' , $tyre->itemId]])->get();
	   }
	   return TyreImage::where([['tyre24_id' , '=' , $tyre->id]])->get();  
		
	}
	//workshop images
	public static function get_workshop_image($id){	
		$get_images = \App\Gallery::get_worshop_images($id);
		return $get_images;
			
		}

		public static function get_car_maintenance_min_prce($service_id , $time){
			$min_price_arr = []; $min_price = 0;	
			$user_exists = \App\Users_category::where([['categories_id' , '=' , 12] ,['deleted_at' , '=' , NULL]])->get();
				foreach($user_exists as $user_exist){
					$service_details= serviceHelper::car_maintinance_price_appoinment($service_id, $user_exist->users_id);
						if(!empty($service_details['hourly_cost'])){
							$hourly_cost = $service_details['hourly_cost'];
						}
					$min_price_arr[] =  $hourly_cost * $time;
				}
			if(count($min_price_arr) > 0){
				$min_price =  min($min_price_arr);	
			}	
		   return $min_price;
		}	

	public static function convert_italian_time($date) {
		setlocale(LC_TIME, "it_IT.utf8");
		$converted_date = ucwords(strftime("%a %d %B %Y", strtotime($date)));
		return $converted_date;
	}

	//coupon list
	public static function get_coupon_list($users_id , $type , $service_id , $service_category_id = null ,$price = null){	
		$find_limited_service = \App\ServiceBooking::get_three_workshop_services($users_id , $type);
				$new_coupon_list = null;
				if($find_limited_service->count() >=3){
						$date = date('Y-m-d');
						$coupon_lists = DB::table('coupons as c')->select('c.*','cd.services_id','cd.service_category_id')
						->leftjoin('coupon_details as cd', 'c.id' ,'=','cd.coupons_id')
						->where([['c.status' ,'=' ,1],['c.deleted_at' , '=' , NULL]])
						->whereJsonContains('c.workshop_list', (string)$users_id)->where('c.launching_date', '<=', $date)
									->where('c.closed_date', '>=', $date)->get();
						//offer type for 1 % and type 2 amount 
						//discount 1 and 2,3,4,5,6
						foreach($coupon_lists as $coupon_list){
							$offer_type = $coupon_list->offer_type ;
							if($offer_type == 2){
								$amount = $coupon_list->amount;
							} else { 
								$amount = $coupon_list->amount / $price * 100;	
							}
							$coupon_list->amounts = $amount;
							if($coupon_list->discount_condition == 1){
								$new_coupon_list[] = $coupon_list;
							}

							if($coupon_list->discount_condition == 2){
								$new_coupon_list[] = $coupon_list;
							}
							if($coupon_list->discount_condition == 4){
								if($coupon_list->services_id == $service_id){
									$new_coupon_list[] = $coupon_list;
								}		
							}
							if($coupon_list->service_category_id != null){
								if($coupon_list->discount_condition == 5){
									if($coupon_list->services_id == $service_id){
										if($coupon_list->service_category_id == $service_category_id ){
											$new_coupon_list[] = $coupon_list;
										}	
									}
								}
							}
					}
					//$collect_list_values = [];
					$collect_list = collect($new_coupon_list);
					$collect_list_value = $collect_list->sortBy('amounts')->values()->all();

					if(!empty($collect_list_value)){
						$collect_list_values = $collect_list_value[0];
					}else{
						$collect_list_values = null;
					}
					return $collect_list_values;
		} else {
				return $new_coupon_list;
		}				
	}
	
	public static function get_coupon_product_list($product_id , $product_type,$brand = null ,$price = null){
		$coupon_lists = DB::table('coupons as c')->select('c.*','cd.services_id','cd.service_category_id','cd.product_type','cd.product_product_id','cd.brand')->leftjoin('coupon_details as cd', 'c.id' ,'=','cd.coupons_id')->where([['c.status' ,'=' ,1],['c.deleted_at' , '=' , NULL],['cd.product_type','=',$product_type] , ['cd.product_product_id','=',$product_id]])->whereIn('c.discount_condition' ,[3,6])->get();
		if(!empty($coupon_lists)){
				$new_coupon_list = null;
				foreach($coupon_lists as $coupon_list){
					$get_offer = $coupon_list->offer_type;
					if($get_offer == 2){
						$get_coupon_discount = $coupon_list->amount;
					} else {
						$get_coupon_discount = $coupon_list->amount / $price *100;
					}
					if($coupon_list->discount_condition == 3){
						if($product_type == $coupon_list->product_type){
							if($product_id == $coupon_list->product_product_id){
								$new_coupon_list[] = $coupon_list; 
							}
						}
					}
					if($coupon_list->discount_condition == 6){
						if($product_type == $coupon_list->product_type){
							if($product_id == $coupon_list->product_product_id && !empty($coupon_list->brand)){
								$get_brand_name = \App\BrandLogo::get_brand_name($coupon_list->brand);
								if($get_brand_name->brand_name == $coupon_list->brand){
									$new_coupon_list[] = $coupon_list; 
								}
							}
						}

					}
				} 
				$collect_coupon_lists = collect($new_coupon_list);
				$collect_coupon_list = $collect_coupon_lists->sortBy('amounts')->values()->all();
				if(!empty($collect_coupon_list)){
					$final_coupon = $collect_coupon_list[0];
				}else{
					$final_coupon = null;
				}
				return $final_coupon;
			}else{
				return $new_coupon_list;
			}			
	}
	//Mot services
	public static function get_feedback_images($feedback_id) {
		if($feedback_id != NULL) {
			return  \App\Gallery::where([['feedback_id' , '=' , $feedback_id ]])->get(); 
		}
	}
	  public static function check_mot_service_time_slot($time_slots ,$service_average_times,$request,$service_id,$workshop_id){
		$opening_slot = $not_aplicable_slot = $new_booked_list = [];
		 foreach($time_slots as $slot){
			 $opening_slot[] = [$slot->start_time , $slot->end_time]; 
		 }
		$special_condition_obj = new SpecialCondition;
		$special_condition_status = $special_condition_obj->do_not_perform_operation_for_car_maintenance(3 , $request->selected_date ,$workshop_id,$time_slots ,$request->selected_car_id ,$service_id , 1);
		$decode_response = json_decode($special_condition_status);
        if($decode_response->status == 200){
		   $not_aplicable_slot = $decode_response->response;
		}
		$new_generate_slots = sHelper::get_time_slot($not_aplicable_slot, $opening_slot);
		/*Get Booked slots*/
		  $booked_slots = \App\ServiceBooking::where([['workshop_user_id','=',(int) $request->workshop_id] ,
														['type' , '=' , 8] ,
														['services_id' , '=' ,$service_id]])
												->whereDate('booking_date' , $request->selected_date)->get();
		/*End*/
	      if($booked_slots->count() > 0){
			  foreach($booked_slots as $booked){
				  $new_booked_list[] = [$booked->start_time, $booked->end_time];
			  }
		  }
		  $new_generate_slots = sHelper::get_time_slot($new_booked_list, $new_generate_slots);
		  if(count($new_generate_slots) > 0){
			  foreach($new_generate_slots as $slot){
				 $get_slot_time_in_hour = sHelper::get_number_of_hour($slot[0], $slot[1]);
				 if ($get_slot_time_in_hour >= $service_average_times)  $flag = 1;
				 else  $flag = 0; 
			  }
		  } else { 
			  $flag = 0;
		  }
     	return $flag;
		// $flag = 0;
		// $get_services_weekly_days = Workshop_user_day::get_service_weekly_days($workshop_id , $selected_days_id);
		//  if($get_services_weekly_days != null) {
		// 	$get_service_packages = Workshop_user_day_timing::get_packages($get_services_weekly_days->id);
		//     if($get_service_packages->count() > 0){
		// 	   foreach($get_service_packages as $package){
		// 		  $opening_slot = [];
		// 		  $new_booked_list = [];
		// 		  $opening_slot[] = array($package->start_time, $package->end_time);
		// 		  /*Get Booked packages*/
		// 		  $booked_list = ServiceBooking::get_booked_package_mot_service($package->id, $selected_date ,8, $service_id);
		// 		  if($booked_list->count() < $max_appointment){
		// 			 if($booked_list->count() > 0) {
		// 				foreach ($booked_list as $booked){
		// 				  $new_booked_list[] = [$booked->start_time, $booked->end_time];
		// 			    }
		// 			   }
		// 			  $new_generate_slot = sHelper::get_time_slot($new_booked_list, $opening_slot);
		// 			  if(count($new_generate_slot) > 0) {
		// 				foreach ($new_generate_slot as $slot_details) {
		// 					$slot_details['start_time'] = sHelper::change_time_format_2($slot_details[0]);
		// 					$slot_details['end_time'] = sHelper::change_time_format_2($slot_details[1]);
		// 					$get_slot_time_in_hour = sHelper::get_number_of_hour($slot_details[0], $slot_details[1]);
		// 					if(!empty($get_slot_time_in_hour)){
		// 					    $new_time_in_hour = round($get_slot_time_in_hour , 2);
		// 					    $total_service_time = $service_time + 0.33;
		// 						if ($new_time_in_hour >= $total_service_time) {
		// 							return 1;
		// 						}
		// 					  }
		// 					else{ return $flag; }
		// 				}
		// 			}
		// 			}
		// 			else{
		// 			   return $flag;
		// 			 }
		// 		  /*End*/
		// 		  }
		// 	  }
		// 	else{
		// 	    return $flag;
		// 	  }

		//    }
	  }

	  
	 public static function spare_part_list_by_car_maintenance($item_service_times){
			if($item_service_times->type == 1){	
				/*Find parts item numbers*/
				$items_numbers = \DB::table('products_item_numbers')->where([['version_id','=' , $item_service_times->version_id] , ['products_groups_items_item_id' , '=' , $item_service_times->item_id]])->get(); 
				if($items_numbers->count() > 0){
					$products_response = kromedaDataHelper::find_products_by_item_number($items_numbers); 
					if($products_response->count() > 0){
						$products =  kromedaDataHelper::return_spare_parts_for_mot($products_response);
						if($products->count() > 0){
							return json_encode(['status'=>200 ,'responce'=>$products]);
						}
						else{
							return json_encode(['status'=>100 ,'responce'=>$products]);
						}
					}
				}
				return json_encode(['status'=>100]);
				/*End*/
			} else { 
				//print_r($service_id); die;
				$car_maintinance_product_items = DB::table('our_car_maintinance_product_items as CM')->where([['CM.item_repairs_parts_id','=',$service_id]])->get();
				if($car_maintinance_product_items->count() >0){	
									$new_part_list = [];
									foreach($car_maintinance_product_items as $car_maintinance_product_item){
										$product = DB::table('products_new')->where([['products_name' ,'=' ,(string)$car_maintinance_product_item->item_number]])->orderBy('id','desc')->first();
										if($product != NULL){		
										$product_image = sHelper::get_products_image($product);
										$product->product_image_url = NULL;
										if($product_image->count() > 0){
											$product->product_image_url =  $product_image[0]['image_url'];	
										}
										$new_part_list[] = kromedaDataHelper::arrange_spare_product($product);	
										}
									}
									if(count($new_part_list) > 0){

										return json_encode(['status'=>200 ,'responce'=>$new_part_list]);
									}else{
										return json_encode(['status'=>100 ,'responce'=>"item id is empty !!!"]);
									}										
				}else{
					return json_encode(['status'=>100 ,'responce'=>"pary is empty !!!"]);
				}	
			}
		 } 
		 
/******************************************************************/
	public static function calculate_vat_price($total_price){
		$vat_price = ($total_price * 22 ) / 100;
		return $total_price + $vat_price;
	}

	public static function calculate_pfu_price($products_orders_id){
	 $get_product_data = \App\Products_order_description::where([['products_orders_id' , '=' ,$products_orders_id]])->get();
	 $get_pfu =[];
	 foreach($get_product_data as $get_product){
		 $get_pfu[] = $get_product->pfu_tax;
	 }
		 return array_sum($get_pfu);
		//return $pfu_price = $total_price + 2.66;
	}
	  
}