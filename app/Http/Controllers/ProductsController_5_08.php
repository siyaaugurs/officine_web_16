<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Library\kromedaHelper;
use App\Library\sHelper;
use App\Library\kromedaDataHelper;
use Session;
use App\Products_group;
use App\ProductsGroupsItem;
use App\ExcutedQuery;


class ProductsController_5_08 extends Controller{
    
    
    public function get_groups_04_09(Request $request){
       if(!empty($request->language) && !empty($request->version)){
		  $lang = sHelper::get_set_language($request->language);
		  $get_group = Products_group::get_parent_groups($request->version , $lang);
		  ///echo "<pre>";
		  //print_r($get_group);exit;
		  $html_content = "<option value='0'>Select Category</option>";
		   if($get_group->count() > 0){
			   foreach($get_group as $group){
				  $html_content .= "<option value=".$group->id.">".$group->group_name."</option>"; 
			   }
			}
		  else{
			  $html_content .= '<option value="0">No Category Available !!!</option>';
			}	
		  if($get_group->count() > 0){
			  return json_encode(array("status"=>200 , "response"=>$html_content));
			}
		   else{
			  return json_encode(array("status"=>100));
		   }
	   }
	}
    
    public function get_sub_groups_04_09(Request $request){
	  /*Working*/	
	  if(!empty($request->group_id)){
		   $lang = sHelper::get_set_language($request->language);  
		   $sub_groups_details = Products_group::where([['id' , '=' , $request->group_id] , ['deleted_at' , '=' , NULL] , ['language' , '=' , $lang]])->first();
		   if($sub_groups_details != NULL){
			  $sub_groups = sHelper::get_sub_categories($sub_groups_details);
			   $html_content = "<option value='0'>Select Sub Category (N2)</option>";	 
			   if($sub_groups->count()> 0){
				   foreach($sub_groups as $s_group){
				        $html_content .= "<option value=".$s_group->id.">".$s_group->group_name."</option>"; 
			           }  
				 }
				else{
			     $html_content .= "<option value='0'>No Sub category available !!!</option>"; 
				 } 
			   	if($sub_groups->count() > 0){
			       return json_encode(array("status"=>200 , "response"=>$html_content));
			      }
		         else{
			       return json_encode(array("status"=>100));
		          }
			 } 
			else{
		      return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>Something went wrong please try again  !!! </div>'));  
			 } 
		}
	  else{
		return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>Something went wrong please try again  !!! </div>'));    
		}	
	}
	
	
    
	public function save_sub_groups_04_09(Request $request){
	    if(!empty($request->group_id)){
			$lang = sHelper::get_set_language($request->language);  
			$group_details =  Products_group::find($request->group_id);
			if($group_details != NULL){
			  if($group_details->type == 1){
				 $exists_record = Products_group::check_subgroups_today_execute($group_details->id);
				 if($exists_record->count() <= 0){
					$get_sub_groups = kromedaHelper::get_sub_group($group_details->car_version , $group_details->group_id  , $lang);
					if(is_array($get_sub_groups)  && count($get_sub_groups) > 0){
				        $add_sub_groups = Products_group::add_kromeda_sub_groups($group_details , $get_sub_groups , $lang);
						echo "<pre>";
				    	print_r($add_sub_groups);exit;
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
		else{
		  return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));  
		 } 
	}
	
	
	public function save_groups_04_09(Request $request){
		set_time_limit(300);
       if(!empty($request->version) || !empty($request->language)){
		   $lang = sHelper::get_set_language($request->language);  
		   $get_groups = kromedaHelper::get_products_group($request->version , $lang);
			  if(is_array($get_groups)  && count($get_groups) > 0){
				  $save_response = Products_group::add_kromeda_groups($get_groups , $request->makers ,$request->model, $request->version , $lang);
				  echo "<pre>";
				  print_r($save_response);exit;
				  if($save_response) {
					 return json_encode(array("status"=>200));  
				  }
				  else{
				   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));  
				  } 
			  }
		 }
	   else{
		 	 return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));  
		 }	 
	}
	
	public function get_model(Request $request){
	    if(!empty($request->makers_id)){
		   $cars_models_response = kromedaHelper::get_models($request->makers_id);
		   return json_encode(['status'=>200 , 'response'=>$cars_models_response]);
		}
	}
	
	public function version(Request $request){
	   if(!empty($request->model_value)){
			$modeL_arr = explode('/' , $request->model_value);
			if(is_array($modeL_arr)){
				 $car_version_response = kromedaHelper::get_versions($modeL_arr[0] , $modeL_arr[1]);
				 if(count($car_version_response) > 0)
				 return json_encode(array("status"=>200 , "response"=>$car_version_response));	
				 else return json_encode(array("status"=>400));	
				}
			else
			 return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
    	    }  
	} 
	
	 public function save_groups_subgroups(Request $request){
	   $validator = \Validator::make($request->all() , [
          'version' => 'required','language'=>'required', 
       ]);
		if($validator->fails()){
			return json_encode(array("error"=>$validator->errors()->getMessages(), "status"=>400));
		 }
			   $lang = sHelper::get_set_language($request->language); 
			   $groups_response = kromedaDataHelper::get_groups_and_save_05_08($request, $lang);
			  if($groups_response == 1){
				  return json_encode(['status'=>200]);
				}
			  else{
				   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
				} 	
			  //return $response; 
	 }
	 
	 
	 public function get_groups(Request $request){
	   if(!empty($request->language) || !empty($request->car_version_id)){
		  $lang = sHelper::get_set_language($request->language);
		  $get_group = Products_group::get_parent_groups($request->car_version_id ,  $lang);
		  $html_content = "<option value='0'>Select Category</option>";
		  if($get_group->count() > 0){
			   foreach($get_group as $group){
				  $html_content .= "<option value=".$group->id.">".$group->group_name."</option>"; 
				  $get_child_groups = Products_group::get_sub_category($group->id);
				  if($get_child_groups->count() > 0){
					 foreach($get_child_groups as $child_group)
					  $html_content .= "<option value=".$child_group->id.">".$group->group_name." >> ".$child_group->group_name."</option>";   
					}
			   }
			}
		  else{
			  $html_content .= '<option value="0">No Category Available !!!</option>';
			}	
		  if($get_group->count() > 0){
			  return json_encode(array("status"=>200 , "response"=>$html_content));
			}
		   else{
			  return json_encode(array("status"=>100));
		   }
		}
		else{
	 return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
	}	 
	 }
	 
	 
	 
	public function get_and_save_products_item(Request $request){
		 set_time_limit(300);
	     if(!empty($request->group_id)){
		   $group_details = Products_group::find($request->group_id);
		   if($group_details != NULL){
			   $lang = sHelper::get_set_language($request->language);
			   if($group_details->type != 2){
				   $product_item_response = ProductsGroupsItem::check_today_execute($request->group_id); 
				if($product_item_response->count() <= 0){
					if($group_details->parent_id != 0){
					  $product_response = kromedaHelper::get_sub_products_by_sub_group($group_details->car_version , $group_details->group_id , $lang);
					  /*Get Sub group Products script Start*/
				     }
			       else{
					  $product_response = kromedaHelper::get_groups_items_by_group($group_details->car_version , $group_details->group_id , $lang);
					} 	
					/*Save in database*/
					if(is_array($product_response)){
					 $response =  ProductsGroupsItem::add_group_items_new($product_response , $request->group_id , $lang , $group_details->car_version , $group_details->group_id);
					 if($response){
						return json_encode(array("status"=>200 , "response"=>'<div class="notice notice-success"><strong> Success </strong> Products items save successfully  !!! </div>')); 
					   }
					 else{
						return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong please try again   !!! </div>'));				   }   
					}
				   else{
						return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Note </strong> Products items not available  !!! </div>'));
					}	
				 /*End*/	
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
		 else{
		     return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
		   }  
	 } 
	 
	 
	 
     public  function get_products_item(Request $request){
		if(!empty($request->group_id) && !empty($request->language)){
			$language = sHelper::get_set_language($request->language);
		  $group_details = Products_group::find($request->group_id);
		   if($group_details != NULL){
			  $group_items = ProductsGroupsItem::get_groups_items($request->group_id , $language);       
			  if($group_items->count() > 0)
				 return json_encode(array("status"=>200 , "response"=>$group_items));  
			  else return json_encode(array("status"=>404)); 
			}
	 }
	 else{
	   return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>'));
	 }
	 }

	 public function save_cross_and_otherCrossProducts(Request $request){
	    set_time_limit(300);
		   if(!empty($request->group_item_id)){
				$item_details = ProductsGroupsItem::find($request->group_item_id);
				try{
				  if($item_details != NULL){
					 $item_part_number_response = \App\ProductsItemNumber::get_part_item_number($item_details->id);
					 if($item_part_number_response->count() > 0){
						 foreach($item_part_number_response as $part_number){
						 /*OE_Get_cross */
							$get_products = kromedaHelper::oe_products_item((string) $part_number->CodiceListino , (string) $part_number->CodiceOE);
							if(is_array($get_products) && count($get_products) > 0){
								$add_products_response = \App\ProductsNew::add_products_by_kromeda_new($item_details  , $part_number , $get_products);
							}
					    /*End*/   
						/*Oe Get other cross Api response script start*/
						    $get_other_products = kromedaHelper::oe_getOtherproducts((string) $part_number->CodiceListino , (string)$part_number->CodiceOE);
					        //$get_other_products = kromedaHelper::oe_getOtherproducts("023" , 34116767269);
						//For testing purpose
							if(is_array($get_other_products) && count($get_other_products) > 0){
								/*Custom Query Script Start*/
								$response = \App\ProductsNew::add_other_products_by_kromeda_new($item_details  , $part_number , $get_other_products);
								/*End*/
							}
					    }
					   }
					  else{
					     return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>'));  
					   } 
					 }
				  else{
					  return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>')); 
				  } 
			   }
			  catch (RequestException $e) {
					  return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>'));
				}
			 }
			else{
		       return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>')); 
			 } 

	 }
	
	/*Search Products Script start*/
	 public function search_products(Request $request){
		  if(!empty($request->item_id)){
		       $products = \App\ProductsNew::get_products_list($request->item_id);
			//$data['products_item_number'] = \App\ProductsItemNumber::get_part_item_number($request->item_id);
			return view('products.component.products_list')->with(['products'=>$products]);
			//return view('products.component.item_number')->with(['items_numbers'=>$data['products_item_number']]);
			 /*if($data['products_item_number']->count() > 1){
			    return view('products.component.item_number')->with(['items_numbers'=>$data['products_item_number']]);			                    
			  }
			  else{
			      $products = \App\ProductsNew::get_products_list($request->item_id);
			     return view('products.component.products_list')->with(['products'=>$products]);			       
			     } */
		   }
		 else{
			 echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
		  }  
	 }
	/*End*/
	
	
	/*Search Proiducts by item number id*/
	 public function search_products_by_item_number($item_number_id){
		 if(!empty($item_number_id)){
		   $products = \App\ProductsNew::where([['products_item_numbers_id' , '=' , $item_number_id]])->get();
			if($products->count() > 1) {
				return view('products.component.products_list')->with(['products'=>$products]);		  
			}
		 }
	      
	  }
	/*End*/
	public function get_all_groups(Request $request){
		if(!empty($request->language) || !empty($request->car_version_id)){
			$lang = sHelper::get_set_language($request->language);
			$get_group = Products_group::get_all_parent_groups($request->car_version_id ,  $lang);
			$html_content = "<option value='0'>Select Category</option>";
			if($get_group->count() > 0){
				foreach($get_group as $group){
					$html_content .= "<option value=".$group->id.">".$group->group_name."</option>"; 
					$get_child_groups = Products_group::get_sub_category($group->id);
					if($get_child_groups->count() > 0){
						foreach($get_child_groups as $child_group)
						$html_content .= "<option value=".$child_group->id.">".$group->group_name." >> ".$child_group->group_name."</option>";   
					}
				}
			} else {
				$html_content .= '<option value="0">No Category Available !!!</option>';
			}	
		  	if($get_group->count() > 0){
			  	return json_encode(array("status"=>200 , "response"=>$html_content));
			} else {
			  	return json_encode(array("status"=>100));
		   	}
		} else {
			return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
		}	 
	}
	public function search_n3_category(Request $request){
		if(!empty($request->group_id)){
			$data['group_items'] = ProductsGroupsItem::get_all_groups_items($request->group_id);
			return view('products.component.n3_category')->with(['group_item'=>$data['group_items']]);
		} else {
			echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
		}
	}
}
