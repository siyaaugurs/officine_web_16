<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use sHelper;
use App\Library\kromedaHelper;
use App\ProductsImage;
use Auth;

class SpareProductsController extends Controller{
   
   
    public function  save_custom_products(Request $request){
		if(empty($request->car_makers) || empty($request->car_models) || empty($request->car_version) || empty($request->product_groups)|| empty($request->sub_groups) || empty($request->items)){
           return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Select all required field !!! </div>'));   
		  }
		else{
            $validator = \Validator::make($request->all(), [
                'products_name'=>'required' ,
                // 'products_description'=>'required' ,
                'product_groups'=>'required',
                'sub_groups'=>'required',
                'items'=>'required',
                // 'seller_price'=>'required',
                // 'stock_warning'=>'required|numeric',
                // 'quantity'=>'required|numeric',
                // 'assemble_time'=>'required',
            ]); 
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            $for_pair = 0;
            if(!empty($request->for_pair)) {
                $for_pair = $request->for_pair;
            }
            /*Find all category details like n1  , n2 , n3*/
              $group_id = $group_item_item_id = NULL;
              $group = \App\Products_group::where([['id' , '=' , $request->sub_groups] , ['type' , '=' , 1]])->first();
              if($group != NULL){
                      $group_id =  $group->group_id; 
                }
              $group_item = \App\ProductsGroupsItem::where([['id' , '=' , $request->items] , ['type' , '=' , 1]])->first();
              if($group_item != NULL){
                      $group_item_item_id =  $group_item->item_id; 
                }
            /*End*/
            $products_response = \App\ProductsNew::add_new_custom_products($request , $group_id , $group_item_item_id, $for_pair);  
           if($products_response){
                if($products_response){
                    $save_product_details = \App\ProductsNew_details::save_custom_products_details($products_response , $request , $for_pair);
                    if(!empty($request->products_gallery_image)){
                        $products_item_images = $this->upload_products_image($request); 
                        $products_image_response = ProductsImage::save_custom_product_image($products_item_images , $products_response->id);
                    }
                } 
                /*For car compatible script start*/
                    //$add_response_car_compatible =  $this->car_compatible($request , $products_response->id);
                /*End*/
                
            } 
            return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong> Success </strong>Record Save successfully !!! </div>'));  
		  }
    } 

    
   /*Edit Custom Products*/
    public function  edit_custom_products(Request $request){
        /*if(empty($request->car_makers) || empty($request->car_models) || empty($request->car_version) || empty($request->product_groups)|| empty($request->sub_groups) || empty($request->items)){
           return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Select all required field !!! </div>'));   
        } else {*/
            $validator = \Validator::make($request->all(), [
                    'products_name'=>'required' ,
    				// 'products_description'=>'required' ,
    				// 'seller_price'=>'required|regex:/^(\d+(,\d{1,2})?)?$/',
    				// 'stock_warning'=>'required|numeric',
    				// 'quantity'=>'required|numeric',
    				// 'assemble_time'=>'required',
                ]); 
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
    		    $for_pair = 0;
    			/*Find all category details like n1  , n2 , n3*/
                $group_id = $group_item_item_id = NULL;
                  $group = \App\Products_group::where([['id' , '=' , $request->custom_sub_group] , ['type' , '=' , 1]])->first();
                  if($group != NULL){
                          $group_id =  $group->group_id; 
                    }
                  $group_item = \App\ProductsGroupsItem::where([['id' , '=' , $request->custom_items] , ['type' , '=' , 1]])->first();
                  if($group_item != NULL){
                          $group_item_item_id =  $group_item->item_id; 
                    }
                /*End*/
                if(!empty($request->for_pair)) {
    				$for_pair = $request->for_pair;
    			}
    		$products_response = \App\ProductsNew::update_product_new($request,$for_pair, $group_id, $group_item_item_id);
    	  if($products_response){
    		  $save_product_details = \App\ProductsNew_details::save_custom_products_details($products_response , $request , $for_pair);
               /* $compatible_exist = \App\ProductsCarCompatible::get_custom_compatible($request->id);
                if(!empty($compatible_exist)) {
    				$result = \App\ProductsCarCompatible::where('product_id' , $request->id)->delete();
    			}*/
               /* $add_response_car_compatible =  $this->car_compatible($request , $products_response->id);*/
                if(!empty($request->products_gallery_image)){
                    $products_item_images = $this->upload_products_image($request); 
                    $products_image_response = ProductsImage::save_custom_product_image($products_item_images , $products_response->id);
                }
            } 
            return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong> Success </strong>Record Updated successfully !!! </div>'));  	 
        //}
    } 
    /*End*/
	
    
	public function car_compatible($request , $product_id){
	   $result = \App\ProductsCarCompatible::add_custom_car_compatible($request , $product_id);
	   return $result;
	}

    public function post_action(Request $request, $action) { 
        /*Save car compatible script start*/
		  if($action == "save_kromeda_assemble_time"){
            if(!empty($request->product_id) && !empty($request->assemble_time) && !empty($request->kromeda_assemble_time)){
                $products_details = \App\ProductsNew::find($request->product_id);
                if($products_details != NULL){
                    $update_record = \DB::table('products_new')->where([['id' , '=' , $request->product_id]])->update(['assemble_time'=>$request->assemble_time, 'assemble_kromeda_time' => $request->kromeda_assemble_time]);
                    //$products_details->assemble_time = $products_details->kromeda_assemble_time;
                    if($update_record){
                        return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record save successfully .</div>')); 
                    } else {
                        return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note  , </strong> Soemthing Went Wrong Please Try Again !!!.</div>'));
                    }
                }
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note  , </strong> Please enter required field !!!.</div>')); 
            }
            
        }
		/*End*/
        /*Add Car Compatible */ 
        if($action == "add_car_compatible") {
            if(empty($request->makers) && empty($request->groups) && empty($request->item_number)){
                return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-info"><strong>Note , </strong> Please select some required fields !!!.</div>']);  
            } else {
                if(!empty($request->item_number)){
                    $products_details = \App\ProductsNew::where([['products_name' , '=' , (string) $request->item_number]])->first();
                    if($products_details == NULL) {
                        return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-info"><strong>Note , </strong> This products is not exists in our database !!!.</div>']);  
                    }
                }   
                $k_time = NULL;
                $type = 2;
                if($request->k_time != NULL) {
                    $k_time = $request->k_time;
                    $type = 1;
                } 
                if($request->groups == 'all'){
                    $groups = 0;
                    $all_groups = 1;
                } else {
                    $all_groups = 0;
                    $groups = $request->groups;
                }
                if($request->sub_groups == 'all'){
                    $sub_groups = 0;
                    $all_sub_groups = 1;
                } else {
                    $all_sub_groups = 0;
                    $sub_groups = $request->sub_groups;
                }
                
                if($request->items == 'all'){
                    $items = 0;
                    $all_items = 1;
                } else {
                    $all_items = 0;
                    $items = $request->items;
                }
                if(!empty($request->item_number) && ($request->groups == 'all') && ($request->sub_groups == 'all') && ($request->items == 'all')  ) {
                    $car_compatible = \App\ProductsCarCompatible::where('item_number', '=',  $request->item_number)->update(['deleted_at' => date('Y-m-d H:i:s')]);
                } 
                $check_all_n3 = \App\ProductsCarCompatible::where([['all_group', '=', 1], ['all_sub_group', '=', 1], ['all_item', '=', 1], ['item_number', '=', $request->item_number]])
                ->orWhere([['group', '=', $request->groups], ['all_sub_group', '=', 1], ['all_item', '=', 1], ['item_number', '=', $request->item_number]])
                ->orWhere([['group', '=', $request->groups], ['sub_group', '=', $request->sub_groups], ['all_item', '=', 1], ['item_number', '=', $request->item_number]])
                ->orWhere([['group', '=', $request->groups], ['sub_group', '=', $request->sub_groups], ['item', '=', $request->items], ['item_number', '=', $request->item_number]])
                ->first();
                if($check_all_n3 == NULL ) {
                    $insert_result = \App\ProductsCarCompatible::add_car_compatible_details($request, $groups, $all_groups,$sub_groups, $all_sub_groups, $items, $all_items, $k_time, $type);
                    if($insert_result) {
                        return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Car Compatible Added Successfully !!!.</div>']);
                    } else {
                        return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
                    }
                } else {
                    return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> This Product is Already Compatible For This N3 Category !!!.</div>']);
                }
                
            }
        }
        /*End */
        
        /*Products Groups Add in spare category start */    
		if($action == "add_selected_service_group"){
				if(!empty($request->records) && count($request->records) > 0) {
					$response = \App\Spare_category_item::save_selected_service_group($request);
                    if($response != FALSE){
						return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Services Group Added Successfully !!!.</div>']);
					} else {
						  return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>']);
					}
                }
                else{
                    return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one Groups !!!.</div>']);
                }
            }
        /*Products Groups Add in spare category end */    
        if($action == "add_spare_group") {
            // return $request;exit;
            $validator = \Validator::make($request->all(), [
                'spare_group_name' => 'required', 
                'description' => 'required' , 
            ]);
			if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            //$duplicate_exixt = \App\MainCategory::get_spare_group_record($request->spare_group_name);
           	//if($duplicate_exixt == NULL){
                $result = \App\MainCategory::add_spare_group($request);
                if($result) {
                   return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Service Group Added successfully .</div>'));
                } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
                }
			//}else{
			  // return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> This service group is already listed !!!.</div>'));   
			//}
        }
    }

    public function get_action(Request $request, $action) {
        /*Remove car compatible script start*/
		if($action == "remove_car_compatible"){
			if(!empty($request->compatible_id)){
			    $response = \App\ProductsCarCompatible::find($request->compatible_id); 
			    if($response != NULL){
				    $response->deleted_at = now();
					if( $response->save() ){
					       echo 200;exit;
					  }
					else { echo 100; exit; }
				  }
			  }
		  }
		/*End*/
         /*Set Defaul Service Group */
        if($action == "set_default_service_group") {
            $private = 0;
            $msg = '<div class="notice notice-success"><strong>Success! </strong> Service Default Group Removed successfully  .</div>';
            if($request->status == 1) {
                $private = 1;
                $msg = '<div class="notice notice-success"><strong>Success! </strong> Service Default Group Added successfully .</div>';
            }
            $update_record = \DB::table('main_category')->where([['id' , '=' , $request->service_id]])->update(['private' => $private]);
            if($update_record) {
                return json_encode(array('status'=>200 , 'msg'=>$msg));
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
            }

        }
        /*End */
		
        /*Save Car compatible script start*/
		 if($action == "save_assemble_timing"){
			if(!empty($request->car_compatible_id)){
			   if( !empty($request->our_time)){
                    $k_time = NULL;
                    if($request->kromeda_time != NULL) {
                        $k_time = $request->kromeda_time;
                    }
                      $result = \App\ProductsCarCompatible::where([['id' , '=' , $request->car_compatible_id]])->update(['our_time'=>$request->our_time, 'k_time'=>$k_time , 'item_number'=>$request->item_number]);
                      if($result){
                      return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record save successfully .</div>')); 
                      }
                     else{
                      return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
                      }
                 }
			   else{
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Fill the required fields .</div>'));  
				 }	 
			  }
			 else{
			    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
			  } 
		  }
		/*End*/
		/*Get Car compatible */
		if($action == "get_compatible_details"){
		   if(!empty($request->id)){
			  $result = \App\ProductsCarCompatible::find_products_compatible($request->id);
			  if($result != NULL){
				 return json_encode(array('status'=>200 , 'response'=>$result));   
				}
			  else{
				 return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>'));   
				} 	
			 }
		   else{
			   return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>'));   
			 }	 
		 }
        /*Remove Group List Script Start*/
        if($action == "remove_group_list"){
            if(!empty($request->records) || count($request->records) > 0){
                  $real_records = collect($request->records);
                  $group_id_arr = $real_records->pluck('group_id')->all();
                  if(count($group_id_arr) > 0){
                    $record_delete = \App\Spare_category_item::whereIn('id' , $group_id_arr)->delete();
                    if($record_delete){ 
                         return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record delete successfully !!!  .</div>')); 
                     }
                    else{
                        return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
                        //echo '<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!  .</div>';exit;
                    }
                  }
            }
            else{
              return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one groups .</div>'));     
            }
        }
        /*Remove Group List Script End*/
        
        
        if($action == "search_spare_item_list") {
            /* $default_sub_cat = \DB::table('products_groups')->where([['type' , '=' , '2'] , ['parent_id' , '!=' , 0] , ['status' , '=' , 'A']])->get();
			if($default_sub_cat->count() > 0){
                foreach($default_sub_cat as $sub_group){
                    $result =  \App\Spare_category_item::updateOrinsert(['main_category_id'=>11 , 'products_groups_id'=>$sub_group->id] , 
                    ['users_id'=>Auth::user()->id , 'main_category_id'=>11 , 'products_groups_id'=>$sub_group->id , 'status'=>'A']); 
                }
            } */
			
			if($request->main_cat_id == 0){
				$spare_items = \App\Spare_category_item::get_serach_spare_items();
            } else {
			   $spare_items = \App\Spare_category_item::all_spare_category_item($request->main_cat_id);
            }  
            return view('admin.component.list_spare_items')->with(['spare_items'=>$spare_items]);
        }
        
       
        if($action == "search_group_item") {
            if(!empty($request->version_id) && !empty($request->language)) {
			    $lang = sHelper::get_set_language($request->language);
                $selected_groups = \App\Spare_category_item::get_selected_groups($request->version_id);
			    $selected_group_id_arr = [];
				if($selected_groups->count() > 0){
			     $selected_group_id_arr = $selected_groups->pluck('products_groups_id')->all(); 
				}
                $products_groups = \App\Products_group::get_search_spares_details($request->version_id, $lang , $selected_group_id_arr);
            }
			//echo "<pre>";
			//print_r($products_groups);exit;
           return view('admin.component.spare_group_item')->with(['products_groups'=>$products_groups]);
        }
        
        
        if($action == "get_spare_details") {
            if(!empty($request->spareId)) {
                $result = \App\MainCategory::get_spares_details($request->spareId);
                if($result){
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
        }
        if($action == "change_spare_group_status") {
            // $result->status = $request->status;
            if(!empty($request->spareId)){
                $result = \App\MainCategory::find($request->spareId);
                if($result != NULL){
                    $result->status = $request->status;
                    if($result->save()){
                        echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
                    }else{
                        echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
                    } 
                }
                else{
                    echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
                }	 
            }
            else{
                echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
            }
        }
    }
}
