<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use sHelper;
use App\Model\Kromeda;
use App\Products;
use App\Products_group;
use App\ProductsImage;
use kRomedaHelper;
use kromedaDataHelper;
use App\ProductsGroupsItem;
use App\ExcutedQuery;

class ProductsCategory extends Controller{
     
     public function post_action(Request $request ,  $action){
      /*Save groups items  */
			if($action == "save_groups_items"){
				if(empty($request->group_id) || empty($request->language)){
					return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>'));
				  }
				$group_details = Products_group::find($request->group_id);
				if($group_details != NULL){
				  $language = sHelper::get_set_language($request->language);
				 }
				if(count($request->item_details) > 0 && is_array($request->item_details)){
				  foreach($request->item_details as $item){
					   $get_item_number =   kromedaHelper::get_part_number($group_details->car_version , $item['item_description']['idVoce']);
					   $response =  ProductsGroupsItem::add_group_items_custom($item , $group_details , $get_item_number , $language);
					 }
				  	return json_encode(array("status"=>200 , "response"=>'<div class="notice notice-success"><strong> Success </strong> Products items save successfully  !!! </div>')); 
				}
			   else{
				  	return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Note </strong> Products items not available  !!! </div>'));
				}
				/*Save in database*/
			 }
			/**End */
   }
     
     
     public function products_categories(Request $request ,  $action){
		/*Get and save Group*/ 
	    if($action == "get_and_save_group"){
		      $validator = \Validator::make($request->all() , [
                'makers_id' => 'required', 'models' => 'required','car_version_id' => 'required', 'language'=>'required', 
              ]);
			if($validator->fails()){
                return json_encode(array("error"=>$validator->errors()->getMessages(), "status"=>400));
             }
			  $lang = sHelper::get_set_language($request->language); 
			  $response = kromedaDataHelper::get_groups_and_save($request->makers_id , $request->models , $request->car_version_id , $lang);
			  return $response;
		 }
		/*End*/ 
	 } 
     
   	 public function get_action(Request $request ,  $action){
   	   /*Get and Save Other Cross products*/
	   if($action == "get_and_save_otherCrossProducts"){
		  set_time_limit(300);
		   if(!empty($request->group_item_id)){
				$item_details = ProductsGroupsItem::find($request->group_item_id);
				//$ls_list = kromedaHelper::get_ls_list();
				  /*check record already exists are not */
				   /*  $api_param = 'get_products'."/".$request->group_item_id;
		             $check_exist = ExcutedQuery::get_record($api_param);
					 if($check_exist != NULL){  echo 1;exit; }
					 $response = ExcutedQuery::add_record($api_param);*/
				  /*End*/
			  try{
				  if($item_details != NULL){
					 $item_part_number_response = \App\ProductsItemNumber::get_part_item_number($item_details->id);
					 if($item_part_number_response->count() > 0){
					     foreach($item_part_number_response as $part_number){
						   /*OE_Get_cross */
						   $get_products = kromedaHelper::oe_products_item((string) $part_number->CodiceListino , $part_number->CodiceOE);
						  // echo "<pre>";
						  // print_r($get_products);exit;
						   $get_products = kromedaHelper::oe_products_item("023" , 34116767269);
							if(is_array($get_products) && count($get_products) > 0){
							   $add_products_response = \App\ProductsNew::add_products_by_kromeda_new($item_details  , $part_number , $get_products);
							}
					    /*End*/   
						/*Oe Get other cross Api response script start*/
						$get_other_products = kromedaHelper::oe_getOtherproducts((string) $item_details->CodiceListino , $item_details->CodiceOE);
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
		   //echo "Function is working";exit;
		 }
	   /*End*/
	   if($action == "get_groups_items_database"){
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
		 
	  if($action == "get_and_save_products_item"){
		   set_time_limit(300);
	 	   if(empty($request->group_id) || empty($request->language)){
		     return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>'));
		   }
		   $group_details = Products_group::find($request->group_id);
		   if($group_details != NULL){
			 $language = sHelper::get_set_language($request->language);
			   /*Check record exist or not  */
		       $api_param = "group_item/".$request->group_id."/".$language;
			    $check_exist = ExcutedQuery::get_record($api_param);
			   if($check_exist != FALSE){
				 return json_encode(array("status"=>200 , "response"=>'<div class="notice notice-success"><strong> Success </strong> Products items save successfully  !!! </div>'));
				 }
			 
		       /*End*/
			  
			  if($group_details->parent_id != 0){
					/*Get Sub group Products script Start*/
					$products_item = kromedaHelper::get_sub_products_by_sub_group($group_details->car_version , $group_details->group_id , $language , $group_details);
				 }
			   else{
					/*Get  group Products script Start*/
					$products_item = kromedaHelper::get_groups_items_by_group($group_details->car_version , $group_details->group_id , $language , $group_details);
					/*End*/
				 } 	
			 /*Save in database*/
			  if(count($products_item) > 0 && is_array($products_item)){
				 $response =  ProductsGroupsItem::add_group_items_new($products_item , $request->group_id , $language , $group_details->car_version);
				 if($response){
					 $response = ExcutedQuery::add_record($api_param);
				   	return json_encode(array("status"=>200 , "response"=>'<div class="notice notice-success"><strong> Success </strong> Products items save successfully  !!! </div>')); 
				   }
				 else{
				 		return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong please try again   !!! </div>')); 
				   }   
				}
			   else{
				  	return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Note </strong> Products items not available  !!! </div>'));
				}	
			 /*End*/
		   }
		 }
		  
   	  /*Search products by products id script start*/
	   if($action == "search_products_by_id"){
		   if(!empty($request->products_id)){
			   $products = collect([]);
			   $product = \App\ProductsNew::get_products_coe_id( (string) $request->products_id);
			   if($product != NULL){
				 $product = \kromedaDataHelper::arrange_spare_product($product);
				  $products[] = $product;
				  return view('products.component.products_list')->with(['products'=>$products]);
			   }  
			   else{
				echo '<div class="notice notice-danger"><strong> Note </strong> No Product Available   !!! </div>';exit;
			   }
			 }
		    else{
			return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Note </strong> Please enter the products id !!! </div>'));
			}	 
		 }
	     
	   /*End*/
    	if($action == "get_part_category_database_new"){
    		   if(!empty($request->language) || !empty($request->car_version_id)){
    		    if($request->language == "en") $lang = "ENG";
    		    else $lang = "ITA";
    			  $get_group = Products_group::get_parent_groups($request->makers_id , $request->models ,  $request->car_version_id ,  $lang);
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
    		/*End*/ 
	  /*Get Products And save script start*/
	    if($action == "get_and_save_products"){
		    if(!empty($request->group_id)){
			   $group_details = Products_group::find($request->group_id);
			   if($group_details != NULL){
				   $language = sHelper::get_set_language($request->language);
				   if($group_details->parent_id != 0){
					    /*Get Sub group Products script Start*/
						$products_sub_item = kromedaDataHelper::get_sub_products_and_save($group_details->car_version , $group_details->group_id , $language , $group_details);
						//$products_sub_item = kromedaHelper::get_sub_products_by_sub_group($group_details->car_version , $group_details->group_id , $language);
						/*End*/
					 }
				   else{
					    /*Get  group Products script Start*/
						$products_item = kromedaDataHelper::get_products_and_save($group_details->car_version , $group_details->group_id , $language , $group_details);
						//$products_item = kromedaHelper::get_products_by_group( $group_details->car_version , $group_details->group_id , $language);
						//echo "<pre>";
						//print_r($products_item);exit;
						/*End*/
					 } 	 
				 }
			   else{
				    echo'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>';exit;
				 }	 
			 }
			else{
			   echo'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>';exit;
			 } 
		  }
	  /*End */ 
	  
	  
	 if($action == "get_and_save_group"){
		  if(!empty($request->makers_id) || !empty($request->models) || !empty($request->car_version_id) ){
			 $lang = sHelper::get_set_language($request->language); 
			 $response = kromedaDataHelper::get_groups_and_save($request->makers_id , $request->models , $request->car_version_id , $lang);
			}
		} 
   }
   
}

