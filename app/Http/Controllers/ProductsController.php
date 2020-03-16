<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Library\kromedaHelper;
use App\Library\sHelper;
use App\Library\kromedaDataHelper;
use Session;
use App\KromedaCustomResult;


class ProductsController extends Controller{
    public function get_category_n2(Request $request){
       if(!empty($request->group)){
		   $lang = sHelper::get_set_language($request->language); 
		   $sub_groups = \App\Products_group::where([['parent_id' , '=' , $request->group]])->get();
		   if($sub_groups->count() > 0){
			  return json_encode(array('status'=>200 , 'response'=>$sub_groups));   
			 }
			else{
			    return json_encode(array('status'=>400));   
			 } 
		    	 
		  }
	 }
	public function get_all_n1_category(Request $request) {
		$lang = sHelper::get_set_language($request->language); 
		$all_category = \App\Products_group::where([['language' , '=' , $lang] , ['parent_id' , '=' , 0]])->get();
		if($all_category->count() > 0){
			return json_encode(array('status'=>200 , 'response'=>$all_category));   
		} else {
			return json_encode(array('status'=>400));   
		} 
	}
	public function get_category_n1(Request $request){
       if(!empty($request->version)){
		   $lang = sHelper::get_set_language($request->language); 
		    $groups = \App\Products_group::where([['car_version' , '=' , $request->version] , ['language' , '=' , $lang] , ['parent_id' , '=' , 0]])
		              ->orWhere([['car_version' , '=' , NULL] , ['parent_id' , '=' , 0]])
					  ->get();
		   if($groups->count() > 0){
			  return json_encode(array('status'=>200 , 'response'=>$groups));   
			 }
			else{
			    return json_encode(array('status'=>400));   
			 } 
		    	 
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
                'makers_id' => 'required', 'models' => 'required',
                'car_version_id' => 'required', 'language'=>'required', 
       ]);
		if($validator->fails()){
			return json_encode(array("error"=>$validator->errors()->getMessages(), "status"=>400));
		 }
			  $response = json_decode(kromedaDataHelper::get_groups_and_save1($request));
			  if($response->status != 100){
				  return json_encode(['status'=>200 , 'response'=>$response->response]);
				}
			  else{
				   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
				} 	
	 }
	 
	 public function get_and_save_products_item(Request $request){
		 if(!empty($request->group_id)){
			if(!Session::has('kromeda_session_key')){
              Session::put('kromeda_session_key', sHelper::generate_kromeda_session_key());
			  $kromeda_session_key = session::get('kromeda_session_key');
            }
		   else{
			  $kromeda_session_key = session::get('kromeda_session_key');
			} 	
		
		      if($request->type != 0){
					/*Get Sub group Products script Start*/
					//$products_item = kromedaHelper::get_sub_products_by_sub_group($group_details->car_version , $group_details->group_id , $language , $group_details);
					 $products_item = kromedaHelper::common_request($kromeda_session_key , "getSubPartsItems/".$request->group_id , $request->group_id , "OE_GetActiveItemsBySubgroup");
				 }
			   else{
					/*Get  group Products script Start*/
					//$products_item = kromedaHelper::get_groups_items_by_group($group_details->car_version , $group_details->group_id , $language , $group_details);
					 $products_item = kromedaHelper::common_request($kromeda_session_key , "getGroupsPartsItems/".$request->group_id , $request->group_id , "OE_GetActiveItemsByGroup");
					/*End*/
				 } 
			    $product_response = json_decode($products_item);
				if($product_response->status == 200){
				   return json_encode(['status'=>200 , 'response'=>$product_response->response]);
				 }
				else{
				 return json_encode(['status'=>404 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>']); 
				 } 
		   }
		 else{
		     return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
		   }  
	 } 
	 
	 public function save_cross_and_otherCrossProducts(Request $request){
	    if($request->group_item_id){
			if(!Session::has('kromeda_session_key')){
              Session::put('kromeda_session_key', sHelper::generate_kromeda_session_key());
			  $kromeda_session_key = session::get('kromeda_session_key');
            }
		   else{
			  $kromeda_session_key = session::get('kromeda_session_key');
			} 
			$item_number_url = "getItemNo/".$request->group_item_id;
		    $products_item_number = kromedaHelper::common_request($kromeda_session_key , $item_number_url , $request->group_item_id , "OE_GetPartNumber");
			$final_obj = collect(); $final_obj1 = collect(); 	$final_obj2 = collect();  $final_products_collection = collect();
			$productsNumberResponse = json_decode($products_item_number);
			if($productsNumberResponse->status == 200){
			   if(count($productsNumberResponse->response) > 0){
			      foreach($productsNumberResponse->response as $part_number){
					   //$CodiceOE = "34116767269";
					   //$api_param = "023/".$CodiceOE;
					   $api_param = $part_number->CodiceListino."/".$part_number->CodiceOE;
		               $url_2 = "oe_products_item/".$api_param;
					   /*Get cross products script start*/
					   $get_products = kromedaHelper::common_request($kromeda_session_key,$url_2 , $api_param , 'OE_GetCross');
					    if(!empty($get_products)){
							$get_products_response = json_decode($get_products);
							if($get_products_response->status == 200){
								 $final_obj1 = collect($get_products_response->response);
								 //echo "<pre>";
								 //print_r($get_products_response->response); exit;
							   }
						}	
					   /*End*/
					   /*Get Other cross products start*/
					   $url_3 = "oe_others_products_item/".$api_param;
					   $get_other_products = kromedaHelper::common_request($kromeda_session_key, $url_3 , $api_param , 'OE_GetOtherCross');
					   if(!empty($get_other_products)){
						$get_other_products_response = json_decode($get_other_products);
						if($get_other_products_response->status == 200){
							$final_obj2 = collect($get_other_products_response->response);
						  }
					   }    
					   /*End*/
					   $final_obj = $final_obj->merge($final_obj1);
					   $final_obj = $final_obj->merge($final_obj2);
					   if($final_obj->count() > 0){
						 $final_products_collection = $final_obj->unique('CodiceListino' , 'CodiceArticolo');
                         $final_products_collection->values()->all();
						   if($final_products_collection->count() > 0){
							  foreach($final_products_collection as $collection){
								$collection->image_url = '';  
								$image_api_param = $collection->CodiceListino."/".$collection->CodiceArticolo;
								  $image_url = "get_picture_url/".$image_api_param;
								  $get_picture_url = kromedaHelper::common_request($kromeda_session_key ,$image_url , $image_api_param , "LS_GetPictureURL");  
								  if(!empty($get_picture_url)){
									  $collection->image_url = $get_picture_url;
								  }
								} 
							  //$save_response = \App\KromedaCustomResult::save_response($url , json_encode($final_products_collection));
							  //return json_encode(['status'=>200 , 'response'=>$final_products_collection]);
							 }
						 }
						 $part_number->products = $final_products_collection;	
						}
				  
				 }
				$save_response = \App\KromedaCustomResult::save_response($item_number_url , json_encode($productsNumberResponse->response));
				return json_encode(['status'=>200 , 'response'=>json_encode($productsNumberResponse->response)]);
			  }
		  }
		 else{
		     return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
		   }   
	 }
	
	/*Search Products Script start*/
	 public function search_products(Request $request){
		if(!empty($request->item_id)){
			//$url = 'getItemNo/95794/1538';
			$url = "getItemNo/".$request->item_id;
			$groups_details = \App\KromedaCustomResult::get_response($url);
			if($groups_details != NULL){
			 return json_encode(['status'=>200 , 'response'=>$groups_details]);
			}
			else{
			  return json_encode(['status'=>400]);
			}
			//$save_response = \App\KromedaCustomResult::save_response($item_number_url , json_encode($productsNumberResponse->response)); 
		}

		//echo "<pre>";
		//print_r($request->all());
		//exit;
		
	 }
	/*End*/
}
