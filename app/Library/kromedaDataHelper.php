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
use App\Model\Kromeda;
use App\Products_group;
use App\Products;
use App\ExcutedQuery;
use Session;
use App\ItemsRepairsTimeId;
use App\ItemsRepairsServicestime;
use sHelper;
use DB;
use App\VersionServicesOperation;


class kromedaDataHelper {
      
	  public static $success = 0;

	public static function  save_mot_services_parts($kr_part_list_data_response){
		foreach($kr_part_list_data_response as $kr_part_list_data){
			$items_numbers = \DB::table('products_item_numbers')->where([['version_id','=' , $kr_part_list_data->version_id] , ['products_groups_items_item_id' , '=' , $kr_part_list_data->idVoce]])->get(); 
			if($items_numbers->count() <= 0){
				$get_item_number = kromedaHelper::get_part_number($kr_part_list_data->version_id , $kr_part_list_data->idVoce);
				if(is_array($get_item_number) && count($get_item_number) > 0){
					 $part_number_response = \App\ProductsItemNumber::save_product_item_number($get_item_number , $kr_part_list_data);  
				 }
			 }
			 $items_numbers = \DB::table('products_item_numbers')->where([['version_id','=' , $kr_part_list_data->version_id] , ['products_groups_items_item_id' , '=' , $kr_part_list_data->idVoce]])->get(); 
			 if($items_numbers->count() > 0){
				$products_response = kromedaDataHelper::find_products_by_item_number($items_numbers); 
				if($products_response->count() <= 0){
					foreach($items_numbers as $item_number){
						if($part_number_response->count() > 0){
							foreach($part_number_response as $part_number){
								/*OE_Get_cross */
								$get_products = kromedaHelper::oe_products_item((string) $part_number->CodiceListino , (string) $part_number->CodiceOE);
								if(is_array($get_products) && count($get_products) > 0){
									$add_products_response = \App\ProductsNew::add_products_by_mot_service($part_number, $get_products);
								}
								$get_other_products = kromedaHelper::oe_getOtherproducts((string) $part_number->CodiceListino , (string) $part_number->CodiceOE);
								if(is_array($get_other_products) && count($get_other_products) > 0){
									$add_other_products_response = \App\ProductsNew::add_other_products_by_mot_service($part_number , $get_other_products);
								}
							}
							/*End*/   
						}
					 }
				}
				else{
                    return $products_response;
				}
			 }
		}
	}  
	 
	public static function  save_car_maintinance_parts($item_service_time){
		$product_item_number = \App\ProductsItemNumber::get_parts($item_service_time);
		if($product_item_number->count() <= 0){
			$get_item_number = kromedaHelper::get_part_number($item_service_time->version_id , $item_service_time->item_id);
			if(is_array($get_item_number) && count($get_item_number) > 0){
					$part_number = \App\ProductsItemNumber::save_item_number($get_item_number , $item_service_time); 
					$part_number_response = \App\ProductsItemNumber::where([['version_id', '=', $item_service_time->version_id], ['products_groups_items_item_id', '=', $item_service_time->item_id]])->get();
					if($part_number_response->count() > 0){
							foreach($part_number_response as $part_number){
								/*OE_Get_cross */
								$get_products = kromedaHelper::oe_products_item((string) $part_number->CodiceListino , (string) $part_number->CodiceOE);
								if(is_array($get_products) && count($get_products) > 0){
									$add_products_response = \App\ProductsNew::add_product_by_car_maintainance($part_number, $get_products);
								}
								$get_other_products = kromedaHelper::oe_getOtherproducts((string) $part_number->CodiceListino , (string) $part_number->CodiceOE);
								if(is_array($get_other_products) && count($get_other_products) > 0){
									$add_other_products_response = \App\ProductsNew::add_other_product_by_car_maintainance($part_number , $get_other_products);
								}
							}
							/*End*/   
						}
				}
			}
		return 	$product_item_number;
	}


	  public static function find_products_by_item_number($item_number){
		$collect_products = collect();
		foreach($item_number as $i_number){
			$products = DB::table('products_new')->where([['products_item_numbers_CodiceListino' , '=' , $i_number->CodiceListino] , ['products_item_numbers_CodiceOE' , '=' , $i_number->CodiceOE] , ['deleted_at' , '=' , NULL]])->get();
			if($products->count() > 0){
              foreach($products as $product){
                 $collect_products[] = $product;
			  }
			}
		}
		return $collect_products;
	  }


	public static function save_mot_spare_parts($interval_details , $lang ){
		$interval_operation_response = kromedaSMRhelper::schedule_interval_operation($interval_details->version_id , $interval_details->version_service_schedules_schedules_id  , $interval_details->service_interval_id , $lang);
		$response = json_decode($interval_operation_response);
			if($response->status == 200){
				$data['status_obj'] = TRUE;
				$opration_response = VersionServicesOperation::add_service_opration($interval_details , $response->response->dataset , $lang); 
				$kr_part_list_response = \App\KrPartList::add_kr_parts_list($interval_details , $response->response->kr_parts_list , $lang);
			} 
	}
	  
	  public static function kromeda_car_compatible($product_id){
		$get_kromeda_compatible = \App\ProductsNew::get_kromeda_compatible( (string) $product_id);
		$html_content = '';
		$html_content .= '<table class="table table-bordered" id="products_list">';
		$html_content .= '<thead>';       
		$html_content .= '<tr>';
		$html_content .= '<th>SN.</th>';
		$html_content .=  '<th>Car Compatible</th>';
		$html_content .= '<th>KTime</th>';
		$html_content .= '<th>Our Time</th>';
		$html_content .= '<th>Action</th>';            
		$html_content .= '</tr>';
		$html_content .= '</thead>';    
		$html_content .= '  <tbody>';  
		if($get_kromeda_compatible->count() > 0){
			$i = 0;
			foreach($get_kromeda_compatible as $compatible){
			  $save_assemble_time = self::get_car_kromeda_time($compatible); 
					  if(is_array($save_assemble_time['assemble_time'])){
						  if(!empty($save_assemble_time['assemble_time'])){
							  $save_assemble_time = DB::table('products_new')->where([['id' , '=' , $compatible->id]])->update(['assemble_kromeda_time'=>$save_assemble_time['assemble_time']]);  
						  }
					  }
				  $compatible->assemble_kromeda_time =  $save_assemble_time['assemble_time'];
				  $kromeda_time = 0; $version_name = NULL;
				  $i++;
				  $model = \App\Models::get_model($compatible->car_model);
				  $versions = \App\Version::get_version($compatible->car_version);
				  if($compatible->parent_id == 0){
					  $n1_category = $compatible->group_name;
					  $n2_category = NULL;
				  }
				  else{
					  $response_n1 = \App\Products_group::find($compatible->parent_id);
					  if($response_n1 != NULL){
						  $n2_category = $compatible->group_name;
						  $n1_category = $response_n1->group_name; 
					  }
				  }
				  $html_content .= '<tr>';
				  $html_content .= '<td>'.$i.'</td>';
				  if($model != NULL){ $model_name =  $model->makers_name." / ".$model->Modello; }
				  if($versions != NULL){ $version_name = $versions->Versione.' '.$versions->ModelloCodice." ".$versions->Al." ".$versions->Body;}
				  $html_content .= '<td>'.$model_name ." / " .$version_name ." / ". $n1_category." /".$n2_category." / ".$compatible->item." ".$compatible->front_rear." ".$compatible->left_right .'</td>';
				  if(!empty($compatible->assemble_kromeda_time)) {  $kromeda_time = $compatible->assemble_kromeda_time; } 
				  $html_content .= '<td>'.$kromeda_time.'</td>'; 
				  $html_content .= '<td>'.$compatible->assemble_time.'</td>';
				  $html_content .= '<td><a href="javascript::void()" data-toggle="tooltip" data-productid="'.$compatible->id .'" data-ktime="'.$compatible->assemble_kromeda_time.'" data-ourtime="'.$compatible->assemble_time.'" data-placement="top" title="Edit" class="btn btn-primary btn-sm edit_car_kromeda_assemble_time" ><i class="glyphicon glyphicon-edit"></i>
				  </a></td>';
				  $html_content .= '</tr>';
					 }
		  }
		  else{
			 $html_content .= '<tr>';
			 $html_content .= '<td colspan="4">No record found !!!</td>';  
			 $html_content .= '</tr>';
		  }
	  $html_content .= '</tbody>';
	  $html_content .= '</table>';
	  return $html_content;	
	} 
	
	
	public static function get_product_k_time($products_item , $s_time_id_arr){
		$times = \App\ItemsRepairsServicestime::where([['item_id','=',$products_item->item_id] , ['language','=',$products_item->language]])->whereIn('items_repairs_time_ids_id' , $s_time_id_arr)
		->first();
	    if($times != NULL){
			if($times->type == 1){
				$times_detail =  DB::table('items_repairs_servicestimes_details')->where([['items_repairs_servicestimes_item_id' , '=' , $times->item_id]])->first();
			}
			if($times->type == 2){
				$times_detail =  DB::table('items_repairs_servicestimes_details')->where([['items_repairs_servicestimes_id' , '=' , $times->id]])->first();
			}
		    if($times_detail != NULL){
				$times->time_hrs = $times_detail->our_time;
			}
			return $times;
		}
	}

	public static function save_car_maintinance_for_assemble($products_details , $products_item){
		$s_time_id_arr = [];
		if($products_item != NULL){
			$times = NULL;
			$product_group = DB::table("products_groups")->where([['id' , '=' , $products_item->products_groups_id]])->first();
			if($product_group != NULL){
				$service_time_ids = DB::table('items_repairs_time_ids')->where([['version_id' , '=' , $product_group->car_version]])->get();
				if($service_time_ids->count() > 0){
					$s_time_id_arr = $service_time_ids->pluck('id')->all();
				}
			}
			$times = self::get_product_k_time($products_item ,$s_time_id_arr);
			if($times == NULL){
				/*Doing Custom here*/
				$get_time_id_response = kromedaSMRhelper::kromeda_version_criteria($product_group->car_version , $products_item->language);
				$time_id_arr = json_decode($get_time_id_response);
				if(count($time_id_arr->response) > 0){
					foreach($time_id_arr->response as $time_id){
					$item_times_response = \App\ItemsRepairsTimeId::save_item_repairs_id_new($product_group->car_version , $time_id , $products_item->language);
					if($item_times_response){
						$services_time_response = kromedaSMRhelper::kromeda_version_service_time($item_times_response->version_id , $item_times_response->repair_times_id , $products_item->language);
						$services_time = json_decode($services_time_response);
						if($services_time->status == 200){
							if($products_item->language == "ENG"){
								$time_response = ItemsRepairsServicestime::save_item_repairs_times_eng($product_group->car_version , $item_times_response , $services_time->response , $products_item->language); 
							}
							if($products_item->language == "ITA")
							$time_response = ItemsRepairsServicestime::save_item_repairs_times_ita($product_group->car_version , $item_times_response , $services_time->response , $products_item->language); 
						}
						}
					}
				}
				$times = self::get_product_k_time($products_item ,$s_time_id_arr);
			    return $times;
				/*End*/ 
			}
			else{
				return $times;
			}
		}
	  } 
      

	public static function get_car_kromeda_time($product_details){
		$service_time_id = DB::table('items_repairs_time_ids')->where([['version_id' , '=' , $product_details->car_version] , ['language' , '=', $product_details->lang]])->get();
		if($service_time_id->count() > 0){
			$service_time_id_arr = $service_time_id->pluck('id')->all();
			$service_times = DB::table('items_repairs_servicestimes')->whereIn('items_repairs_time_ids_id' , $service_time_id_arr)
			->where([['item_id' , '=' , $product_details->item_id]])														 
			->first();

			if($service_times != NULL)
			return ['status'=>200 , 'assemble_time'=>$service_times->time_hrs];	 
			else return ['status'=>200 , 'assemble_time'=>0];	 
		}
		else{
			return ['status'=>100 , 'assemble_time'=>0];
		}															   
	  }

	  public static function get_car_kromeda_time_for_app($product_details){
		$service_time_id = DB::table('items_repairs_time_ids')->where([['version_id' , '=' , $product_details->car_version] , ['language' , '=', $product_details->lang]])->get();
		if($service_time_id->count() > 0){
			$service_time_id_arr = $service_time_id->pluck('id')->all();
			$service_times = DB::table('items_repairs_servicestimes')->whereIn('items_repairs_time_ids_id' , $service_time_id_arr)
			->where([['item_id' , '=' , $product_details->item_id]])														 
			->first();

			if($service_times != NULL)
			return ['status'=>200 , 'assemble_time'=>$service_times->time_hrs];	 
			else return ['status'=>200 , 'assemble_time'=>0];	 
		}
		else{
			return ['status'=>100 , 'assemble_time'=>0];
		}															   
	  }
      
       public static function save_service_times($times_id , $lang){
		  $item_times_response  = ItemsRepairsTimeId::find($times_id);
			  /*Check record is exist*/
	          $api_param = $item_times_response->version_id."/".$item_times_response->repair_times_id."/".$lang; 
		      $url = "version_repair_time/".$api_param;
			  $check_exist = ExcutedQuery::get_record($url);
			  /*End*/
			  $check_exist = null;
			  if($check_exist == NULL){
				  if($item_times_response != NULL){
						$services_time_response = kromedaSMRhelper::kromeda_version_service_time($item_times_response->version_id , $item_times_response->repair_times_id , $lang);
						$services_time = json_decode($services_time_response);
						if($services_time->status == 200){
							$response = ExcutedQuery::add_record($url);
							if($lang == "ENG") {
								$time_response = ItemsRepairsServicestime::save_item_repairs_times_eng($item_times_response->version_id, $item_times_response , $services_time->response , $lang); 
							    return $time_response;
							} else if($lang == "ITA") {
								$time_response = ItemsRepairsServicestime::save_item_repairs_times_ita($item_times_response->version_id, $item_times_response , $services_time->response , $lang); 
							}
							if($time_response){ return 1; }
						}
					}
				  	
			  }
	  }
      
      public static function get_groups_and_save1($request){
         try{ set_time_limit(500);
		  $lang = sHelper::get_set_language($request->language); 
		  /*Generate Session Key script start*/
		  if (!Session::has('kromeda_session_key')){
              Session::put('kromeda_session_key', sHelper::generate_kromeda_session_key());
			  $kromeda_session_key = session::get('kromeda_session_key');
            }
		  else{
			  $kromeda_session_key = session::get('kromeda_session_key');
			} 	
		  //echo "<pre>";
		  //print_r($kromeda_session_key);exit;  
		  /*End*/
		  /*check record already exists are not */
		  //$api_param = 'getParts'."/".$version."/".$lang;
		  //$check_exist = ExcutedQuery::get_record($api_param);
		  /*End*/
		 //if($check_exist == NULL){
			  //$get_groups = kromedaHelper::get_groups($request , $lang);
			  $api_param = $request->car_version_id."/".$lang;
		      $url = "getParts/".$api_param; 
			  $groups_details = FALSE;
			  //$groups_details = \App\KromedaCustomResult::get_response($url);
			  //$groups_details = Kromeda::js_get_response_api($url);
			   if($groups_details == FALSE){
				  $get_groups = kromedaHelper::common_request($kromeda_session_key , $url , $api_param , "OE_GetActiveGroups",  $lang);
				  $response = json_decode($get_groups);
				  if($response->status == 200 && count($response->response) > 0){
					 $get_sub_groups = [];
					 foreach(collect($response->response) as $group){
					    $api_param2 = $request->car_version_id."/".$group->idGruppo."/".$lang;
		                $url2 = "getSubParts/".$api_param2;
						$get_sub_groups = kromedaHelper::common_request($kromeda_session_key ,$url2 ,$api_param2,'OE_GetActiveSubGroups', $lang);
						 $group->subgroups = $get_sub_groups;
					 }
					 //$save_response = \App\KromedaCustomResult::save_response($url , json_encode($response->response));
					 //return json_encode(['status'=>200 , 'response'=>$response->response);
					 if($response->response){
						return json_encode(['status'=>200,'response'=>$response->response]);
					    //return json_encode(['status'=>200,'response'=> \App\KromedaCustomResult::get_response($url)]);
					 //$response = Products_group::add_kromeda_group_2($get_groups,$maker,$model,$version , $lang);
					   }
			     	}else 
					   return json_encode(['status'=>100]);	
				 }
			   else{
				   return json_encode(['status'=>200 , 'response'=>$groups_details]);
				 }	  
			//}
		  //else self::$success = 1;
		/*End*/
		 }
		 catch(Exception $e){
		   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>response time out  !!! </div>'));
		 }
	   }
	
      	
	public static function get_groups_and_save_05_08($request , $lang){
		set_time_limit(300);
		/*check record already exists are*check record already exists are not */
		$api_param = $request->version."/".$lang;
		$url = 'getParts'."/".$api_param;
		$check_exist = ExcutedQuery::get_record($url);
	   /*End*/
	   if($check_exist == NULL){
		$kromeda_session_key = sHelper::create_session_key();
	       $get_groups = kromedaHelper::common_request($kromeda_session_key , $url , $api_param , "OE_GetActiveGroups",  $lang);
		   $response = json_decode($get_groups);	
			  if($response->status == 200){
				  $save_response = Products_group::add_kromeda_group_2($response->response , $request->makers ,$request->model, $request->version , $lang);
				  if($save_response) {
					$exe_response = ExcutedQuery::add_record($url);
					return self::$success = 1;
				  }
				  else return self::$success = 0;
			  }
		  }
		else self::$success = 1;
		if(self::$success == 1) return self::$success;  		
	  /*End*/
	   }
	  
	  public static function get_and_save_products_item($version , $sub_group_id , $lang){
		$product_response = [];
		$group_detail = Products_group::find($sub_group_id);
		if($group_detail != NULL){
			if($group_detail->type == 1){
				$product_item_response = \App\ProductsGroupsItem::check_today_execute($sub_group_id); 
				if($group_detail->parent_id != 0){
					$product_response = kromedaHelper::get_sub_products_by_sub_group($group_detail->car_version , $group_detail->group_id , $lang);
					/*Get Sub group Products script Start*/
				}
				else{
					$product_response = kromedaHelper::get_groups_items_by_group($group_detail->car_version , $group_detail->group_id , $lang);
				}
				
				if(count($product_response) > 0 && is_array($product_response)){
					$response =  \App\ProductsGroupsItem::add_group_items_new($product_response , $sub_group_id , $lang , $group_detail->car_version , $group_detail->group_id);
					if($response){
					  return json_encode(array("status"=>200 , "response"=>'<div class="notice notice-success"><strong> Success </strong> Products items save successfully  !!! </div>')); 
					}
					else{
						return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Note </strong> Products items not available  !!! </div>'));
					}	   
				}
			}
		}
	} 
	  
	  public static function save_sub_groups($group_id){
	      $group_details =  Products_group::find($group_id);
		  if($group_details != NULL){
			  if($group_details->type == 1){
				 $exists_record = Products_group::check_subgroups_today_execute($group_details->id);
				 if($exists_record->count() <= 0){
					$get_sub_groups = kromedaHelper::get_sub_group($group_details->car_version , $group_details->group_id  , $group_details->language);
					if(is_array($get_sub_groups)  && count($get_sub_groups) > 0){
				        $add_sub_groups = Products_group::add_kromeda_sub_groups($group_details , $get_sub_groups , $group_details->language);
						if($add_sub_groups) {
					      return json_encode(array("status"=>200));  
				         }
					  }
				   }
				  else{
				    return json_encode(array("status"=>200));  
				   } 
			  }
			else{
				return json_encode(array("status"=>200));  
			}
		 }
	  }
	  
      public static function get_groups_and_save($maker , $model , $version , $lang){
		set_time_limit(300);
		  /*check record already exists are not */
		  $api_param = 'getParts'."/".$version."/".$lang;
		  $check_exist = ExcutedQuery::get_record($api_param);
		  /*End*/
		 if($check_exist == NULL){
			$get_groups = kromedaHelper::get_groups($version , $lang);
				if(!empty($get_groups)){
					$response = Products_group::add_kromeda_group_2($get_groups,$maker,$model,$version , $lang);
					if($response) {
						$response = ExcutedQuery::add_record($api_param);
						return self::$success = 1;
					}
					else return self::$success = 0;
				}
			}
		  else self::$success = 1;
		  if(self::$success == 1) return self::$success;  		
		/*End*/
	   }
	
	
	public static function get_products_and_save($version_id , $group_id , $lang , $group_details){
		set_time_limit(300);
	    $products_item = kromedaHelper::get_products_by_group($version_id , $group_id , $lang);
		
		if(!empty($products_item)){
		   foreach($products_item as $product){
		      $product->item_description = null;
			  $get_products_details = kromedaHelper::get_part_number($version_id, $product->idVoce);
			  if($get_products_details != NULL){
				  $product->item_description = $get_products_details;
				}
				$response =  Products::add_products_by_kromeda_on_real_time($group_details , $product , $lang); 
			   if($response){
				 $products_images_details = kromedaHelper::get_products_image((string) $response->CodiceListino  , $response->CodiceOE);
				 //$products_images_details = kromedaHelper::get_products_image("023" ,34116767269);
				 if(count($products_images_details) > 0){
					$new_collect_image_details = collect($products_images_details);
					$filtered = $new_collect_image_details->where('Foto', '!=' , "");
					if(count($filtered->all()) > 0){
					  foreach($filtered->all() as $image_details){
						//$add_image_response = KromedaProductsImage::add_kromeda_image($response->id , "023" , "34116767269"  ,$image_details);
						 $add_image_response = KromedaProductsImage::add_kromeda_image($response->id , (string) $response->CodiceListino
						 , $response->CodiceOE  ,$image_details);
						 $ls_list = kromedaHelper::get_ls_list();
						 foreach($ls_list as $list){
							  $get_picture_url = kromedaHelper::get_picture_url($list->CodiceListino , $image_details->CodiceArticolo);
							  if(!empty($get_picture_url) || $get_picture_url != NULL){
								$image_url_response =  ProductsImage::add_products_kromeda_image_url($response->id ,  $image_details->CodiceArticolo , $get_picture_url , $list->CodiceListino); 
							  }
							}		
					  }
					} 
					} 
				}
		   }
		 } 
	}
	
      
    	public static function get_sub_products_and_save($version_id , $group_id , $lang , $group_details){
		set_time_limit(300);
		$products_sub_item = kromedaHelper::get_sub_products_by_sub_group($version_id , $group_id , $lang);
		if(!empty($products_sub_item)){
		   foreach($products_sub_item as $product){
		      $product->item_description = null;
			  $get_products_details =   kromedaHelper::get_part_number($version_id, $product->idVoce);
			  if($get_products_details != NULL){
				  $product->item_description = $get_products_details;
				}
				$response =  Products::add_products_by_kromeda_on_real_time($group_details , $product , $lang);
			   if($response){
				 $products_images_details = kromedaHelper::get_products_image((string) $response->CodiceListino  , $response->CodiceOE);
				 //$products_images_details = kromedaHelper::get_products_image("023" ,34116767269);
				 if(count($products_images_details) > 0){
					$new_collect_image_details = collect($products_images_details);
					$filtered = $new_collect_image_details->where('Foto', '!=' , "");
					if(count($filtered->all()) > 0){
					  foreach($filtered->all() as $image_details){
						//$add_image_response = KromedaProductsImage::add_kromeda_image($response->id , "023" , "34116767269"  ,$image_details);
						 $add_image_response = KromedaProductsImage::add_kromeda_image((string) $response->id
						 , $response->CodiceOE  , $image_details);
						 $ls_list = kromedaHelper::get_ls_list();
						 foreach($ls_list as $list){
							  $get_picture_url = kromedaHelper::get_picture_url($list->CodiceListino , $image_details->CodiceArticolo);
							  if(empty($get_picture_url) || $get_picture_url == NULL){
								  continue;
							  }
							  $image_url_response =  ProductsImage::add_products_kromeda_image_url($response->id ,  $image_details->CodiceArticolo , $get_picture_url , $list->CodiceListino); 
						    }
					  }
					} 
					} 
				}
		   }
		 }
	}
       /*Get Products group satrt*/
	  public static function get_groups($groups , $maker , $model , $version ,   $lang = "ENG" ){
		 foreach($groups as $group){
		    $add_response =  Products_group::add_kromeda_group($group , $maker , $model , $version ,   $lang = "ENG");
			}	 
	  } 
	 /*End*/
      
      /*Get Products group satrt*/
	  public static function add_groups($groups , $maker , $model , $version ,   $lang = "ENG" ){
		 foreach($groups as $group){
		    $add_response =  Products_group::add_kromeda_group($group , $maker , $model , $version ,   $lang = "ENG");
			}	 
	  } 
	 /*End*/
	 
     public static function arrange_spare_product($product){	
		$product->our_products_description =  $product->bar_code = $product->for_pair = $product->meta_key_title   = $product->meta_key_words = $product->seller_price = $product->products_quantiuty = $product->minimum_quantity = $product->out_of_stock_status =  $product->tax = $product->tax_value = $product->substract_stock = $product->unit= $product->for_pair  = $product->assemble_status = NULL;
		$product->products_name1 = NULL; 
		$product->image = NULL;
		$product_details  =  sHelper::get_products_details($product);
		$image = sHelper::get_product_image($product->id);
		if($product_details != NULL){
		   $product->our_products_description = $product_details->our_products_description;
		   $product->bar_code = $product_details->bar_code; 
		   $product->products_name1 = $product_details->products_name1; 
		   $product->for_pair = $product_details->for_pair; 
		   $product->meta_key_title = $product_details->meta_key_title; 
		   $product->meta_key_words = $product_details->meta_key_words; 
		   $product->seller_price = $product_details->seller_price; 
		   $product->products_quantiuty = $product_details->products_quantiuty; 
		   $product->minimum_quantity = $product_details->minimum_quantity; 
		   $product->out_of_stock_status =  $product_details->out_of_stock_status; 
		   $product->tax = $product_details->tax; 
		   $product->tax_value = $product_details->tax_value; 
		   $product->substract_stock = $product_details->substract_stock; 
		   $product->unit = $product_details->unit; 
		   $product->products_status = $product_details->products_status; 
		   $product->assemble_status = $product_details->assemble_status; 
		   $product->assemble_time = $product_details->assemble_time; 
		   $product->deleted_at = $product_details->deleted_at; 
		   $product->products_name1 = $product_details->products_name1;
		   $product->image = $image;
	   }

	return $product;
}


public static function arrange_n1_category($category){
	$category_detail = sHelper::get_categories_details($category);
	if($category_detail != NULL){
		$category->description = $category_detail->description;
		$category->priority = $category_detail->priority;
		$category->status = $category_detail->status;
		$category->deleted_at = $category_detail->deleted_at;
	  }
	return $category;  
}

public static function arrange_n2_category($s_category){
	$category_detail = sHelper::get_n2_categories_details($s_category);
	if($category_detail != NULL){
	   $s_category->description = $category_detail->description; 
	   $s_category->priority = $category_detail->priority; 
	   $s_category->status = $category_detail->status;
	   $s_category->deleted_at = $category_detail->deleted_at;
	  } 
	return $s_category;	 
}

public static function arrange_n3_category($n3_category){
	$category_detail = sHelper::get_n3_categories_details($n3_category);
	if($category_detail != NULL){
		 $n3_category->our_description = $category_detail->description;
		 $n3_category->priority = $category_detail->priority;
		 $n3_category->status  = $category_detail->status;
		 $n3_category->deleted_at = $category_detail->deleted_at;
	   }
	 return $n3_category;  
 } 
	  
 
 
 public static function arrange_tyre_detail($tyre){
	if($tyre != NULL){
	   $decode_tyre_response = json_decode($tyre->tyre_response);
	  /*  echo "<pre>";
	   print_r($decode_tyre_response->pic);exit; */
	   $tyre->pic = $tyre->type = $tyre->price = $tyre->stock = $tyre->itemId = 
	   $tyre->weight = $tyre->date_de = $tyre->is3PMSF = $tyre->kbprice = $tyre->pic_t24 = $tyre->text_de =
	   $tyre->discount = $tyre->itemDate = $tyre->itemText = $tyre->ownStock = $tyre->matchcode = $tyre->org_price =
	   $tyre->source_de = $tyre->ean_number = $tyre->itemSource = $tyre->description = $tyre->shortFeedback =  $tyre->pr_description =  
       $tyre->wholesalerArticleNo =  $tyre->manufacturer_description =  $tyre->manufacturer_item_number =  $tyre->wetGrip  = $tyre->description1  = $tyre->feedback_de = $tyre->noiseDb = $tyre->rollingResistance = $tyre->tireClass = $tyre->tyreLabelUrl =  $tyre->imageUrl = null;
				if(empty($tyre->vehicle_tyre_type)){
					$tyre->vehicle_tyre_type = $tyre->type;
				}
				if(empty($tyre->season_tyre_type)){
					$tyre->season_tyre_type = $tyre->type;
				}
	            if(!empty($decode_tyre_response->pic)){
					if(!is_object($decode_tyre_response->pic)){
							$tyre->pic = $decode_tyre_response->pic;
						}
				}
				if(!empty($decode_tyre_response->type)){
					if(!is_object($decode_tyre_response->type)){
							$tyre->type = $decode_tyre_response->type;
						}
				}  
				if(!empty($decode_tyre_response->price)){
					if(!is_object($decode_tyre_response->price)){
						$tyre->price = (string) $decode_tyre_response->price;
					}
				}
				if(!empty($decode_tyre_response->stock)){
					if(!is_object($decode_tyre_response->stock)){
						$tyre->stock = $decode_tyre_response->stock;
					}
				}
				if(!empty($decode_tyre_response->itemId)){
					if(!is_object($decode_tyre_response->itemId)){
						$tyre->itemId = $decode_tyre_response->itemId;
					}
				}
				if(!empty($decode_tyre_response->weight)){
					if(!is_object($decode_tyre_response->weight)){
						$tyre->weight = $decode_tyre_response->weight;
					}
				}
				if(!empty($decode_tyre_response->date_de)){
					if(!is_object($decode_tyre_response->date_de)){
						$tyre->date_de = $decode_tyre_response->date_de;
					}
				}
				if(!empty($decode_tyre_response->is3PMSF)){
					if(!is_object($decode_tyre_response->is3PMSF)){
						$tyre->is3PMSF = (string) $decode_tyre_response->is3PMSF;
					}
				} else {
					$tyre->is3PMSF = $tyre->peak_mountain_snowflake;
				}
				if(!empty($decode_tyre_response->kbprice)){
					if(!is_object($decode_tyre_response->kbprice)){
						$tyre->kbprice = (string) $decode_tyre_response->kbprice;
					}
				}
				if(!empty($decode_tyre_response->pic_t24)){
					if(!is_object($decode_tyre_response->pic_t24)){
						$tyre->pic_t24 = $decode_tyre_response->pic_t24;
					}
				}
				if(!empty($decode_tyre_response->text_de)){
					if(!is_object($decode_tyre_response->text_de)){
						$tyre->text_de = $decode_tyre_response->text_de;
					}
				}
				if(!empty($decode_tyre_response->discount)){
					if(!is_object($decode_tyre_response->discount)){
						$tyre->discount = $decode_tyre_response->discount;
					}
				}	 
				if(!empty($decode_tyre_response->itemDate)){
					if(!is_object($decode_tyre_response->itemDate)){
						$tyre->itemDate = $decode_tyre_response->itemDate;
					}
				}
				if(!empty($decode_tyre_response->itemText)){
					if(!is_object($decode_tyre_response->itemText)){
						$tyre->itemText = $decode_tyre_response->itemText;
					}
				}
				if(!empty($decode_tyre_response->ownStock)){
					if(!is_object($decode_tyre_response->ownStock)){
						$tyre->ownStock = $decode_tyre_response->ownStock;
					}
				}
				if(!empty($decode_tyre_response->matchcode)){
					if(!is_object($decode_tyre_response->matchcode)){
						$tyre->matchcode = $decode_tyre_response->matchcode;
					}
				}
				if(!empty($decode_tyre_response->org_price)){
					if(!is_object($decode_tyre_response->org_price)){
						$tyre->org_price = $decode_tyre_response->org_price;
					}
				}
				if(!empty($decode_tyre_response->source_de)){
					if(!is_object($decode_tyre_response->source_de)){
						$tyre->source_de = $decode_tyre_response->source_de;
					}
				} 
				if(!empty($decode_tyre_response->ean_number)){
					if(!is_object($decode_tyre_response->ean_number)){
						$tyre->ean_number = (string) $decode_tyre_response->ean_number;
					}
				}	
				if(!empty($decode_tyre_response->itemSource)){
					if(!is_object($decode_tyre_response->itemSource)){
						$tyre->itemSource = $decode_tyre_response->itemSource;
					}
				}
				if(!empty($decode_tyre_response->description)){
					if(!is_object($decode_tyre_response->description)){
						$tyre->description = $decode_tyre_response->description;
					}
				}
				if(!empty($decode_tyre_response->feedback_de)){
					if(!is_object($decode_tyre_response->feedback_de)){
					$tyre->feedback_de = $decode_tyre_response->feedback_de;
					}
				}
				if(!empty($decode_tyre_response->description1)){
						if(!is_object($decode_tyre_response->description1)){
						$tyre->description1 = $decode_tyre_response->description1;
						}
				}
				if(!empty($decode_tyre_response->shortFeedback)){
						if(!is_object($decode_tyre_response->shortFeedback)){
						$tyre->shortFeedback = $decode_tyre_response->shortFeedback;
						}
				}
				if(!empty($decode_tyre_response->pr_description)){
					if(!is_object($decode_tyre_response->pr_description)){
						$tyre->pr_description = $decode_tyre_response->pr_description;
					}
				}	
				if(!empty($decode_tyre_response->wholesalerArticleNo)){
					if(!is_object($decode_tyre_response->wholesalerArticleNo)){
						$tyre->wholesalerArticleNo = (string) $decode_tyre_response->wholesalerArticleNo;
					}
				}
				if(!empty($decode_tyre_response->manufacturer_description)){
					if(!is_object($decode_tyre_response->manufacturer_description)){
						$tyre->manufacturer_description = $decode_tyre_response->manufacturer_description;
					}
				}
				if(!empty($decode_tyre_response->manufacturer_item_number)){
					if(!is_object($decode_tyre_response->manufacturer_item_number)){
						$tyre->manufacturer_item_number = $decode_tyre_response->manufacturer_item_number;
					}
				}  
				if(!empty($decode_tyre_response->imageUrl)){
					if(!is_object($decode_tyre_response->imageUrl)){
						$tyre->imageUrl = $decode_tyre_response->imageUrl;
					}
				} 
	  }
	  /*For Tyre details*/
	  $tyre_detail = sHelper::get_tyre_detail($tyre);
	 // return $tyre_detail;
	  if($tyre_detail != NULL){
		$tyre_detail_response = json_decode($tyre_detail->tyre_detail_response); 
		if(!empty($tyre_detail_response->wetGrip)) {
			if(!is_object($tyre_detail_response->wetGrip)){
				$tyre->wetGrip = $tyre_detail_response->wetGrip;
			}
		}
		if(!empty($tyre_detail->noise_db)){
			$tyre->noiseDb = $tyre_detail->noise_db;
		}  else if(!empty($tyre_detail_response->extRollingNoiseDb)) {
				if(!is_object($tyre_detail_response->extRollingNoiseDb)) {
					$tyre->noiseDb = $tyre_detail_response->extRollingNoiseDb;
				}
		} 
		if(!empty($tyre_detail->tyre_class)){
			$tyre->tireClass = $tyre_detail->tyre_class;
			
		} 
		else if(!empty($tyre_detail_response->tireClass)) {
			if(!is_object($tyre_detail_response->tireClass)){
				$tyre->tireClass = $tyre_detail_response->tireClass;
			}
		} 
		if(!empty($tyre_detail->rolling_resistance)){
			$tyre->rollingResistance = $tyre_detail->rolling_resistance;
		}else if(!empty($tyre_detail_response->rollingResistance)) {
			if(!is_object($tyre_detail_response->rollingResistance)) {
				$tyre->rollingResistance = $tyre_detail_response->rollingResistance;
			}
		} 
		if(!empty($tyre_detail->tyreLabelUrl)){
			if(!is_object($tyre_detail->tyreLabelUrl)){
				$tyre->tyreLabelUrl = $tyre_detail->tyreLabelUrl;
			}
		}
	}
	  /*End*/
	  return $tyre;
}


/*Arrange car main*/

/*Arrange car maintinance services*/
public static function arrange_car_maintinance($item_service_times){
	$item_service_times->service_average_time = null;  
	$item_service_times_detail = \serviceHelper::car_maintinance_service_details($item_service_times); 
	  if($item_service_times_detail != NULL){
		  if(!empty($item_service_times_detail->our_time)){
			  $item_service_times->service_average_time = $item_service_times_detail->our_time;
		  }
		  else{
			  $item_service_times->service_average_time =  $item_service_times->time_hrs;
		  }
		  /*mange other fields*/
		   $item_service_times->our_description = $item_service_times_detail->our_description;
		   if(!empty($item_service_times_detail->status)) {
			   $item_service_times->status = $item_service_times_detail->status;
		   } 
		   	if(!empty($item_service_times_detail->language)) {
				$item_service_times->language = $item_service_times_detail->language;
			} 
			if(!empty($item_service_times_detail->k_time)) {
				$item_service_times->time_hrs = $item_service_times_detail->k_time;
			} 
			if(!empty($item_service_times_detail->our_time)) {
				$item_service_times->our_time = $item_service_times_detail->our_time;
			} 
			if(!empty($item_service_times_detail->priority)) {
				$item_service_times->priority = $item_service_times_detail->priority;
			}
		  /*End*/
		} 
	  else{
		$item_service_times->service_average_time =  $item_service_times->time_hrs;
	  }
	  return $item_service_times;
  }
/*End*/


/*Arrange Mot Service like kromeda mot services*/
public static function arrange_mot_service($our_mot){
	$new_our_mot = [];  
	if($our_mot != NULL){
			$new_our_mot['id'] = $our_mot->id;
			$new_our_mot['users_id'] = null;
			$new_our_mot['version_service_schedules_id'] = null;
			$new_our_mot['version_id'] = null;
			$new_our_mot['service_interval_id'] = null;
			$new_our_mot['additional'] = null;
			$new_our_mot['sort_order'] = null;
			$new_our_mot['service_kms'] = $our_mot->service_km;
			$new_our_mot['service_months'] = null;
			$new_our_mot['service_name'] = $our_mot->service_name;
			$new_our_mot['interval_description_for_kms'] = $our_mot->service_description;
			$new_our_mot['service_advisory_message'] = null;
			$new_our_mot['standard_service_time_hrs'] = null;
			$new_our_mot['extra_time_hrs'] = 0;
			$new_our_mot['automatic_transmission_time_hrs'] = null;
			$new_our_mot['extra_time_description'] = null;
			$new_our_mot['language'] = null;
			$new_our_mot['min_price'] = null;
			$new_our_mot['type'] = 2;
			$new_our_mot['type_status'] = 'Our MOT';
			$new_our_mot['deleted_at'] = $new_our_mot['created_at'] = $new_our_mot['updated_at'] = NULL;
	  }
	  return (object) $new_our_mot;
  }
/*End*/
public static function find_n3_fron_product_item($version_id , $n3_arr){
	$products_groups = $product_group_item_n3 =  [];
	$products_groups = DB::table('products_groups')->where([['car_version','=',$version_id] , ['parent_id' , '!=',0]])->pluck('id')->toArray();
	if(count($products_groups) > 0){
		$product_group_item_n3 =  DB::table('products_groups_items')->whereIn('products_groups_id' , $products_groups)->whereIn('item_id' , $n3_arr)->pluck('id')->toArray();  
		if(count($product_group_item_n3) > 0){
			 $products = DB::table('products_new')->whereIn('products_groups_items_id' , $product_group_item_n3)->get();
			 if($products->count() > 0){
				 foreach($products as $product){
					$product = self::arrange_spare_product($product);
				 }
				 return json_encode(['status'=>200 , 'response'=>$products]);   
			}
			 else return json_encode(['status'=>100]);
		} 
		else return json_encode(['status'=>100]);
		
	} else return json_encode(['status'=>100]);
}

public static function return_spare_parts_for_mot($products){
	if($products->count() > 0){
		foreach($products as $product){
			$product->brand_image_url = $product->product_image_url = null;
			   /*manage product image */
			   $product_images = sHelper::get_products_image($product); 
			   if($product_images->count() > 0){
				  $product->product_image_url = $product_images[0]->image_url; 
			   } 
			   /*End*/
			/*Get products brand*/
			  $brand = DB::table('brand_logos')->where([['brand_name','=',$product->listino] , ['brand_type' , '=' , 1] , ['deleted_at' , '=' , NULL]])->first();
			  if($brand != NULL){
				 $product->brand_image_url = $brand->image_url; 
			  }
			  /*End*/
			$product = self::arrange_spare_product($product);
		}
		$new_products = $products->where('deleted_at' , '=' , NULL)->where('products_status' , '=' , 'A');
		return $new_products;
	}else return NULL;
 }
  
	public static function find_mot_part_list($service , $type , $version){
		$mot_n3 = $products = [];
		$spare_parts = collect();
			if($type == 1){
				$version_id = $service->version_id;
				$mot_n3_category = \App\KrPartList::get_kPartsList($service , $service->language);
				/*Save parts list from kromeda in our database*/
				$save_parts_list_response = self::save_mot_services_parts($mot_n3_category);
				/*End*/
				if($mot_n3_category->count() > 0){
					$mot_n3 = $mot_n3_category->pluck('idVoce')->all();
				}
				if(count($mot_n3) > 0){
				$products_item_number = DB::table('products_item_numbers')->where([['version_id' , '=' , $version_id]])->whereIn('products_groups_items_item_id' , $mot_n3)->get();
				if($products_item_number->count() > 0){
					$spare_parts = self::find_products_by_item_number($products_item_number);
					}
				}
				return json_encode(['status'=>200 , 'product_response'=>self::return_spare_parts_for_mot($spare_parts), 'service_average_time'=>$service->standard_service_time_hrs]);  
			}
		 if($type == 2){
			$our_mot_n3_category = \App\MotN3Category::where([['our_mot_services_id','=',$service->id] , ['deleted_at' , '=' , NULL]])->pluck('n3_category_id')->toArray();
			if(count($our_mot_n3_category) > 0){
				/*Get Time for n3*/
				$product_group_item =  DB::table('products_groups_items')->whereIn('id',$our_mot_n3_category)->get();
				if($product_group_item->count() > 0){
					$n3_times = $n3_item_id = $spare_parts = []; 
					foreach($product_group_item as $group_item){
						$products_item_number = DB::table('products_item_numbers')->where([['version_id' , '=' , $group_item->version_id] ,
																						   ['products_groups_items_item_id' , '=' , $group_item->item_id]])->get();
																						   
						if($products_item_number->count() > 0){
							$spare_partss = self::find_products_by_item_number($products_item_number);
							if($spare_partss->count() > 0){
								$spare_parts[] = self::return_spare_parts_for_mot($spare_partss);
							}
						}																   
						$service_times =  self::pick_n3_service_times($group_item->version_id , [$group_item->item_id]); 
						if($service_times != NULL){
							$n3_times[] = $service_times->service_average_time;
						} 
					}
					/*manage spare parts*/
					$products = [];
					if(count($spare_parts) > 0){
						foreach($spare_parts as $parts){
							foreach($parts as $part){
								$products[] = $part;
							}
						}
					}
					/*End*/
				}   
				return json_encode(['status'=>200 , 'product_response'=>$products, 'service_average_time'=>array_sum($n3_times)]); 

			}else return  json_encode(['status'=>200 , 'product_response'=>null, 'service_average_time'=>null]);
		 }
	}

	public static function pick_n3_service_times($version , $n3_item_id){
		$item_service_times = DB::table('items_repairs_servicestimes')->where([['version_id' , '=' , $version] , ['item_id' , '=' , $n3_item_id]])->first();
		if($item_service_times != NULL){
			$item_service_times = self::arrange_car_maintinance($item_service_times);
		}
        return $item_service_times;
	  }
	 
}


?>