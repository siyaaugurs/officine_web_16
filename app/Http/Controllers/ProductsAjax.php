<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use sHelper;
use App\Model\Kromeda;
use App\Products;
use App\Products_group;
use App\ProductsImage;
use kRomedaHelper;
use App\CategoriesDetails;
use DB;
use kromedaDataHelper;

class ProductsAjax extends Controller{
    
    public $server_base_url = 'http://officine.augurstech.com/officineTop/';
     
	public function post_action(Request $request , $action){
		$flag = 0;
		/*upload brand logo image */
		if($action == "upload_brand_logo") {
		    if(!empty($request->brand_id) && !empty($request->brand_type)){
				$brand_details = \App\BrandLogo::find($request->brand_id);
				if(empty($request->images)){
					$images = $brand_details->image;
				}
				else{
				  $images = $this->upload_brand_logo_image($request);     
				}
				if($images != 111){
					$result = \App\BrandLogo::upload_brand_logo($images , $request->brand_name,  $request->brand_id , $request->brand_type);
					if($result) {
						echo json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Brand Logo uploaded successfully !!! </div>')); 
					} else {
						echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong !!! </div>'));
					}
				} else {
					echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> only , jpg , png format supported !!! </div>')); 
				}
			}
			else{
			    	echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Select all required fields !!! </div>')); 
				
			}
		}
		/*End */
		
		/*Add New Custom Brand */
		if($action == "add_new_brand") {
		   	$images = NULL;
			if(!empty($request->brand_name) && !empty($request->brand_type)){
				if(!empty($request->images)) {
					$images = $this->upload_brand_logo_image($request);  
				}
				if($images != 111){
					$result = \App\BrandLogo::add_new_brand($request, $images);
					if($result) {
						return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Brand Added successfully !!! </div>')); 
					} else {
						return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong !!! </div>'));
					}
				} else {
					return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> only , jpg , png format supported !!! </div>')); 
				}
			}
			else{
			   		return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Fill all required fields !!! </div>')); 
			}
		}
		/*End */
		
		/* Upload Group images */
		/*Add Custom Group*/
		if($action == "update_n3_category"){
			// return $request;
			if(!empty($request->group_name)) {
				$result = \App\ProductsGroupsItem::update_custom_n3_category($request);
				if($result) {
					return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> N3 Category Updated successfully .</div>'));
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>'));
				}
			}
		}
		
		/*Edit Sub group start*/
		if($action == "edit_n2_category_details") {
			if(is_numeric($request->priority)){
			    if(!empty($request->groups) && !empty($request->edit_sub_group_name)) {
					$group_details = \App\Products_group::find($request->groups);
					
					$sub_group_details = \App\Products_group::find($request->edit_n2_category_id); 
					if($sub_group_details->type == 2) {
						$save_in_custom_spare_group_services = \App\Spare_category_item::update_in_spare_groups($request->edit_n2_category_id, $request->service_group);
					}
					if($sub_group_details != NULL){
					    if($sub_group_details->type == 2){
						 $result = \App\Products_group::update_custom_n2_sub_group($request, $group_details);  
						  }
						if($sub_group_details->type == 1){
						  $result = \App\Products_group::update_kromeda_n2_sub_group($request); 
						  }  
					  }
					if($result){
						$response = CategoriesDetails::save_sub_category_details($request , $sub_group_details->id , $sub_group_details->group_id);
						return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> N2 Category Updated successfully .</div>'));
					} else {
						return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>'));
					}
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Fill all required fields !!! .</div>'));
				}
			}
			else{
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Priority is always numeric !!! .</div>'));
				
			}
		}

		/*End*/
		
		if($action == "edit_kromeda_n3_category"){
			// return $request;
			$item_id = NULL;
			$n1_group_id = NULL;
			$n2_group_id = NULL;
			$language = app()->getLocale();
			$lang = sHelper::get_set_language($language);
			$product_group_items = \App\ProductsGroupsItem::find($request->edit_n3_category_id);
			if($product_group_items->type == 2) {
				$n2_category_details = \App\Products_group::find($product_group_items->products_groups_id);
				if($n2_category_details->parent_id == 0) {
					$n1_group_id = $n2_category_details->id;
				} else {
					$n2_group_id = $n2_category_details->id;
					$n1_group_id = $n2_category_details->parent_id;
				}
			} 
			if($product_group_items->item_id != NULL) {
				$item_id = $product_group_items->item_id;
			}
			if(!empty($request->group_name)) {
				$result = \App\ProductsGroupsItem::edit_kromeda_n3_category($request, $n1_group_id, $n2_group_id, $lang);
				if($result) {
					$response = CategoriesDetails::save_item_details($request->description , $request->priority , $product_group_items->id , $item_id );
					return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> N3 Category Updated successfully .</div>'));
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>'));
				}
			}
		}
		/*Add N3 script start*/
		/*Add N3 script start*/
		if($action == "add_n3_category"){
			if(!empty($request->group_name) && !empty($request->sub_category)){
			   $products_category = \App\Products_group::find($request->group_name);
				if($products_category != NULL){
					$products_sub_category = \App\Products_group::find($request->sub_category);
					if(!empty($request->sub_category_n3)) {
						$result = \App\ProductsGroupsItem::add_custom_n3_category($request , $products_category->group_id ,  $products_sub_category->group_id  , $products_category->language);
						if($result) {
							$response = CategoriesDetails::save_item_details($request->description , $request->priority , $result->id , NULL);
							return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> N3 Category Added successfully .</div>'));
						} else {
							return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>'));
						}
					}
				} 
			  }
			  else{
			   	return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong>Please fill all required field !!!.</div>'));
			  }
			
		}
		/*End*/
		if($action == "edit_category_details") {
			if(!empty($request->edit_group_name) &&  !empty($request->category_id)) {
				$category_details = \App\Products_group::find($request->category_id);
				if($category_details != NULL){
					$edit_group_response = \App\Products_group::edit_category_details($category_details->id , $request);
					if($edit_group_response){
						$group_id = NULL;
						if($category_details->type == 2){
						    $where_clause = ['id'=>$category_details->id];
						  }
					     if($category_details->type == 1){
						     $where_clause = ['n1_n2_group_id'=>$category_details->group_id];
						     $group_id = $category_details->group_id;
						  }  
						$response = \App\CategoriesDetails::updateOrCreate($where_clause ,
						                                                   ['n1_n2_id'=>$category_details->id , 'n1_n2_group_id'=>$group_id, 'description'=>$request->description , 'priority'=>$request->priority]);
					   if($edit_group_response) {
					      return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Category Details Updated successfully .</div>'));
				} else {
					      return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Fill all required field !!!.</div>'));
				}
					   }
				   }
				
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Ctaegory Name is required  .</div>'));
			}
		}
		
		if($action == "add_n1_category") {
			if(!empty($request->group_name)) {
			    $language = app()->getLocale();
		        $lang = sHelper::get_set_language($language);
				$result = \App\Products_group::add_custom_n1_group($request, $lang);
				if($result) {
				   $response = CategoriesDetails::save_group_details($request , $result->id , NULL);
					return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> N1 Category Added successfully .</div>'));
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>'));
				}
			}
		}
		
		if($action == "add_n2_category") {
			$language = app()->getLocale();
			$lang = sHelper::get_set_language($language);
			if(is_numeric($request->priority)){
				if(!empty($request->group_name) && !empty($request->sub_group_name)) {
					$group_details = \App\Products_group::find($request->group_name);
					$result = \App\Products_group::add_custom_n2_sub_group($request, $group_details , $lang);
					if($result) {
						$response = CategoriesDetails::save_sub_category_details($request , $result->id , NULL);
						// $save_in_custom_spare_group_services = sHelper::save_in_spare_group($result->id);
						$save_in_custom_spare_group_services = \App\Spare_category_item::save_in_spare_groups($result->id, $request->service_group);
						return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> N2 Category Added successfully .</div>'));
					} else {
						return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>'));
					}
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong , please try again .</div>'));
				}
			}
			else{
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Priority is always numeric !!! .</div>'));
				
			}
		}
		
		if($action == "add_group_name"){
			 if(empty($request->marker_id) || empty($request->models_id) || empty($request->version_id)){
			  return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong , please try again .</div>'));
			}
			if(!empty($request->group_name)){
			   $result = \App\Products_group::create_new_group($request);	
			   if($result) {
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Category Added successfully .</div>'));
			   } else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>'));			                }
			}
			else{
			  return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>'));
			}
		  }
		/*End*/
	   /* Upload Group images */
		if($action == "add_group_images"){
           if(!empty($request->group_id)){
               $sub_group_group_id =  NULL;
				$group_group_id =  NULL;
               $group_detials = \App\Products_group::find($request->group_id);	
			   if($group_detials != NULL){
			     if(empty($request->images)){
				   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Please select at least one image !!! </div>'));
				   }  
				  $images = $this->upload_multiple_image($request);
				  if($group_detials->parent_id != 0) {
						$sub_group_group_id = $group_detials->group_id;
						$group_group_id = NULL;
					} else {
						$group_group_id = $group_detials->group_id;
					}
				  if($images != 111){
        				foreach($images as $image) { 
                            $result = \App\Gallery::add_group_images($image, $request->group_id , $group_group_id, $sub_group_group_id);
                         }
                    if($result) {
                        echo json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Category image uploaded successfully !!! </div>')); 
                      } else {
                        echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong !!! </div>'));
                      }
		      	   }else{
					echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Wrong </strong> only , jpg , png format supported !!! </div>')); 
			    } 
			   }
			 } 
		   else{
			   echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Wrong </strong> Something Went Wrong , please try again  !!! </div>'));
			 }	 
	    }
	    
	    if($action == "add_n3_group_images"){
           if(!empty($request->products_item_id)){
			   $product_item_details = \App\ProductsGroupsItem::find($request->products_item_id);
			  	if(empty($request->images)){
				   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Please select at least one image !!! </div>'));
				}
				$images = $this->upload_multiple_image($request);  
				if($images != 111){
				foreach($images as $image) { 
                    $result = \App\Gallery::add_n3_group_images($image, $request->products_item_id, $product_item_details);
                 }
                    if($result) {
						echo json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Category image uploaded successfully !!! </div>')); 
                    } else {
                        echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong !!! </div>'));
                    }
			}else{
					echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> only , jpg , png format supported !!! </div>')); 
			    } 
			 } 
		   else{
			   echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong , please try again  !!! </div>'));
			 }	 
	    }
        /* End */
        
	  /*Edit Products By admin start*/
		if($action == "edit_products_by_admin"){
		     $validator = \Validator::make($request->all() , [
                'products_name'=>'required',
				'kromeda_price'=>'required',
				// 'seller_price'=>'required|regex:/^(\d+(,\d{1,2})?)?$/',
				'group_item'=>'required',
				// 'quantity'=>'required|numeric' 
			  ]);
			if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
			$for_pair = 0;
			$products_item_images = NULL;
			if(!empty($request->products_gallery_image)){
			   $products_item_images = $this->upload_products_image($request); 
			}
			if(!empty($request->for_pair)) {
			   $for_pair = $request->for_pair;
			}
			$product_details = \App\ProductsNew::find($request->products_id);
			if($product_details != NULL){
				$update_products_details = \App\ProductsNew_details::save_products_details($product_details->products_name, $request , $for_pair);
				//$products_response = \App\ProductsNew::products_edit($request, $for_pair);
				if($update_products_details){
					$products_assemble_time = \App\ProductsNew::products_assemble_edit($request);
						if(!empty($products_item_images)){
						   $products_image_response = ProductsImage::add_products_image($products_item_images , $product_details); 
						}
					return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong> Success </strong>Record Save successfully !!! </div>'));    
					}
			  }
			else{
			   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Success </strong>Products Details not found !!! </div>'));  
			  }  
		  }
		/*Edit Products By admin End*/  
		
		
		
		if($action == "save_group"){
		    $lang = sHelper::get_set_language($request->language);
		  foreach($request->groups as $group){
				Products_group::add_group($request , $group , $lang);
			  $flag = 1;
			}	
			 if($flag == 1){ echo 200;exit; }
			 else{ echo 100; exit; }
		  }
			
			/*Save Products Script start*/
			if($action == "save_products"){
			    $lang = sHelper::get_set_language($request->language);
				if(empty($request->car_makers) || empty($request->car_models) || empty($request->car_version) || empty($request->group_item)){
				 	echo 100;exit;   
				}
				$flag = 0;
			foreach($request->item_details as $description){
			   	$item_id = $description['item_description']['idVoce'];
				$products_name = $description['item_description']['Voce'];
				
				/*Get Products Description */
				$get_products_details =   kromedaHelper::get_part_number($request->car_version , $item_id);
				/*End*/
				if($get_products_details != NULL){
					$description['item_description']['products_description'] = $get_products_details;
				 }
				$response =  Products::add_products_by_kromeda($request , $description , $lang);
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
				$flag = 1;
			}
			if($flag == 1){ echo 200;exit; }
			else{ echo 100;exit; }
		}  
	}
	 
    public function get_action(Request $request , $action){
		if($action == "get_spare_parts_by_ean"){
			if(!empty($request->spare_ean_number)) {
				$check_ean = \App\ProductsNew_details::where([['bar_code', '=',(string) $request->spare_ean_number], ['deleted_at', '=', NULL]])->first();
				if($check_ean != NULL) {
					$product_new_detail_id = $check_ean->product_id;
					$get_product_new_detail = \App\ProductsNew::where([['id', '=', $product_new_detail_id]])->get();
					if(!empty($get_product_new_detail)) {
						foreach($get_product_new_detail as $product){
							
							$product_details = sHelper::get_products_details($product);
							if($product_details != NULL){
								$product->seller_price = $product_details->seller_price;
								$product->products_status = $product_details->products_status;
								$product->products_quantiuty = $product_details->products_quantiuty;
								$product->our_products_description = $product_details->our_products_description;
							}
							$product->image = sHelper::get_product_image($product->id);
							$product->p_id = encrypt($product->id);
						}
						return view('products.component.custom_products_list')->with(['custom_products'=>$get_product_new_detail]);
					}
					$bar_code = $check_ean->bar_code;
				} else {
					echo '<div class="notice notice-danger"><strong>Note , </strong>This EAN Number is not Valid .</div>';
				}
				
			}
			
		}
		if($action == "get_spare_parts_by_item"){
			if(!empty($request->spare_item_name)) {
				$check_by_item_number = \App\ProductsNew::where([['products_name', '=', $request->spare_item_name], ['deleted_at', '=', NULL]])->get();
				if(!empty($check_by_item_number)) {
					foreach($check_by_item_number as $product){
						$product_details = sHelper::get_products_details($product);
						if($product_details != NULL){
							$product->seller_price = $product_details->seller_price;
							$product->products_status = $product_details->products_status;
							$product->products_quantiuty = $product_details->products_quantiuty;
							$product->our_products_description = $product_details->our_products_description;
						}
						$product->image = sHelper::get_product_image($product->id);
						$product->p_id = encrypt($product->id);
					}
					return view('products.component.custom_products_list')->with(['custom_products'=>$check_by_item_number]);
				} else {
					echo '<div class="notice notice-danger"><strong>Note , </strong>This ITEM Name & Number is not Valid .</div>';
				}
			}
		}
        /*if($action == "get_n2_category_id") {
			if(!empty($request->parent_id)) {
				$n2_category_details = \App\Products_group::find($request->parent_id);
				if($n2_category_details){
                    return json_encode(['status'=>200 , "response"=>$n2_category_details]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
			}
		}*/
        if($action == "get_n2_category_details") {
			$get_service_group = $n1_group_id = $n1_id = NULL;
			if(!empty($request->n2_category_id)) {
				$n2_category_details = \App\Products_group::find_product__group_details($request->n2_category_id);
				if($n2_category_details != NULL){
					$n2_category_details = kromedaDataHelper::arrange_n2_category($n2_category_details);
				 	if($n2_category_details->type == 2) {
							$service_group = \App\Spare_category_item::get_service_group($n2_category_details->id);
							if($service_group != NULL){
								$get_service_group = $service_group->main_category_id;
							}
						}
					if(empty($n2_category_details->products_groups_group_id)){
						 $n1_details = \App\Products_group::where([['id','=' ,$n2_category_details->parent_id]])->first();
						 if($n1_details != NULL){
						     if($n1_details->type == 2){
								   $n1_id = $n1_details->id;
							   }
							 else{
							     $n1_group_id = $n1_details->group_id;
							   }
						   }
					  }
					 /*  echo "<pre>"; 
					  print_r($n1_id);exit; */
					return json_encode(['status'=>200 , "response"=>$n2_category_details , 'description'=>$n2_category_details->description , 'priority'=>$n2_category_details->priority , 'n1_id'=>$n1_id , 'n1_group_id'=>$n1_group_id, 'service_group' =>$get_service_group]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
			}
		}
		
		if($action == "get_n3_category") {
			if(!empty($request->n3_category_id)) {
				$description = NULL; 
				$priority = NULL;
				$result = \App\ProductsGroupsItem::get_group_item($request->n3_category_id);
				if($result != NULL){
				 $n1_category = \App\Products_group::find($result->products_groups_id);
				 $category_details = sHelper::get_n3_categories_details($result);
				 if($category_details != NULL){
				     $description  = $category_details->description;
				     $priority  = $category_details->priority;
				 }
				 if($result){
                    return json_encode(['status'=>200 , "response"=>$result, "description"=>$description , 'priority'=>$priority, "n1_category" => $n1_category]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                   } 
				  }
				else{
				    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
				  }				
			}
		}
		
        if($action == "get_products_inventory") {
            // return $request;exit;
            if( !empty($request->group_item_id)){
			  	$total_products = \App\ProductsNew::get_inventory_products($request->group_item_id);
			 //	echo "<pre>";
			 //	print_r($total_products);exit;
				if($total_products->count() > 0){
					return json_encode(array("status"=>200 , "response"=>$total_products));
				} else {
					return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong> Products Not Avilable  !!! </div>'));
				}
			} else {
				return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
			}	  
        }
        	/*Search assemble Products */
		if($action == "search_products_asseble"){
		    $data['checkbox'] = 1;
		    if(!empty($request->groupid) && !empty($request->group_item_id)){
			   $products = \App\ProductsNew::get_user_assemble_products($request->group_item_id);
			   //$products = \App\Products::get_products_by_group_item($request->groupid);
			   if($products->count() > 0){
				    $products_arr = $products->pluck('id')->all();
				    $products_assemble = \App\Products_assemble::get_products_assemble($products_arr);
				    // echo "<pre>";
				    // print_r($products_assemble);exit;
					return view('workshop.component.products_asseble')->with(['products'=>$products_assemble,'checkbox' => 1]);
				 }
			   else{
				   return view('workshop.component.products_asseble')->with(['products'=>$products,'checkbox' => 1]);
				 }	 
			  }
			else{
			   echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit; 
			 }  
		  }
		  /*End*/

           /*Search Products by group script start*/ 
          if($action == "search_productsBy_group"){
			  if(!empty($request->item_id)){
				//$products = \App\Products::get_products_by_group_item($request->makers_id ,$request->models , $request->car_version_id ,  $request->groupid);
		        $products = \App\ProductsNew::get_products_list($request->item_id);
		      //  echo "<pre>";
		      //  print_r($products);exit;
			    return view('products.component.products_list')->with(['products'=>$products]);
			   }
			 else{
			     echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
			  }  
			 }   
	    /*End*/	
		/*Serach Products Group Script start*/	 
		 if($action == "search_products_group"){
		    $validator = \Validator::make($request->all(), [
                'makers_id' => 'required',
                'models' => 'required' , 'car_version_id'=>'required'
               ]);
			 if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
            $language = sHelper::get_set_language($request->language);
			//$group_list = Products_group::get_parent_groups($request->makers_id , $request->models , $request->car_version_id , $language);
			$group_list = Products_group::get_serached_parent_groups( $request , $language);
			return view('products.component.category_list')->with(['group_list'=>$group_list]);
		   }
		 /*End*/  
		/*Get Group */
		 if($action == "search_group"){
		     /*$validator = \Validator::make($request->all(), [
                'makers_id' => 'required',
                'models' => 'required' , 'car_version_id'=>'required'
               ]);
			 if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }*/
			 $get_all_group = Products_group::get_group();
			 if($get_all_group->count() > 0){
				 $sn = 1;
				 foreach($get_all_group as $group){
				     $maker_name = kRomedaHelper::get_maker_name($group->car_makers);
                     $model_name = kRomedaHelper::get_model_name($group->car_makers , $group->car_model);
                     $versions = kRomedaHelper::get_version_name($group->car_model , $group->car_version);
					  $group->makers_name_default = $maker_name->Marca;
					  $group->model_name_default = $model_name->Modello;
					  $group->car_version_name = $versions->Versione;
					  $group->new_version_name = $group->group_name."(".$group->group_id.")";
					  $group->sn = $sn;
					  $group->action = '<a href="'
					  .url("products/remove_group/$group->id").'" class="btn btn-danger delete_group">Remove</a>
                        <a href="#" data-groupid="'.$group->id.'" class="btn btn-primary add_group_image_btn">Add Images</a>';
                   
					  $sn++;
					}
				 return json_encode(array("data"=>$get_all_group));
			   }
		   }
		/*End*/
	    
       /* Remove group image */
         if($action == "remove_products_image") {
	      if(!empty($request->image_id)){
			 $image_details = \App\ProductsImage::find($request->image_id);
			 if($image_details != NULL){
				$image_details->deleted_at = now(); 
				  if( $image_details->save() ){ 
				     echo 200;exit;
				    }
				  else{ echo 101;exit; } 
			   }
			}
		  else{  echo 100;exit; }	
        }
        /* End */
        /*Remove Group start */
		if($action == "remove_group"){
		    $group_details = \App\Products_group::where('id', $request->groupId)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			if($group_details){
			   return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Group delete successfull !!! </div>']);
			   }
			 else{
			   return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);	
			  }  
		  }
		/*End*/
        /* Remove group image */
        if($action == "remove_group_image") {
          if(!empty($request->imageId)){
			 $image_details = \App\Gallery::find($request->imageId);
			 if($image_details != NULL){
			    $image_details->deleted_at = now();
				if( $image_details->save() ){
				    echo 200;
				  }
				else{
				    echo 100;exit;
				  }  
			    //$image_url = url("storage/group_image/");
				//$filePath = $image_url."/".$image_details->image_name;
				  //$image_details->delete();
				  /*if(file_exists($filePath)){ 
				     unlink($filePath );
				   }
				  else{ echo 101;exit; }*/ 
			   }
			}
		  else{  echo 100;exit; }	
        }
        /* End */
        /* Show group image Images */
		if($action == "get_group_image") {
			if(!empty($request->groupId)){
			     $group_detials = \App\Products_group::find($request->groupId);	
			     if($group_detials != NULL){
			       if($group_detials->parent_id != 0){
					   $images = sHelper::get_sub_group_image($group_detials);
					  }
				   else{
					    $images = sHelper::get_group_image($group_detials);
					  } 	  
    				 if($images->count() > 0){
    			        foreach($images as $image){
    					    ?>
    						<div class="col-sm-4 col-md-3 col-lg-3">
    									<div class="card">
    										<div class="card-img-actions m-1">
    											<img class="card-img img-fluid" src="<?php echo $image->image_url; ?>" alt="" />
    											<div class="card-img-actions-overlay card-img">
    												<a href='<?php echo url("vendor_ajax/delete_image/$image->id"); ?>' data-groupid="<?php echo $request->groupId ?>" data-imageid="<?php echo $image->id; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_group_image">
    													<i class="icon-trash"></i>
    												</a>
                                                </div>
    										</div>
    									</div>
    								</div>
                            <?php
    					  }
    					exit;  
    				 }   
			     }
			  }
			else{
			  echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
			  } 
		}
	  /*End*/
		
		if($action == "get_n3_group_image") {
			if(!empty($request->groupId)){
			     $product_group_item = \App\ProductsGroupsItem::find($request->groupId);
				 $images = sHelper::get_n3_category_images($product_group_item);
				 if($images->count() > 0){
			        foreach($images as $image){
					    ?>
						<div class="col-sm-4 col-md-3 col-lg-3">
									<div class="card">
										<div class="card-img-actions m-1">
											<img class="card-img img-fluid" src="<?php echo $image->image_url; ?>" alt="" />
											<div class="card-img-actions-overlay card-img">
												<a href='<?php echo url("vendor_ajax/delete_image/$image->id"); ?>' data-groupid="<?php echo $request->groupId ?>" data-imageid="<?php echo $image->id; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_products_item_image">
													<i class="icon-trash"></i>
												</a>
                                            </div>
										</div>
									</div>
								</div>
                        <?php
					  }
					exit;  
				 } 				
			  }
			else{
			  echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
			  } 
		}
        /* End */
        /*Remove N3 category image Script Start*/
		   if($action == "remove_products_item_image"){
				if(!empty($request->imageId)){
					$image_details = \App\Gallery::find($request->imageId);
					if($image_details != NULL){
					   $image_details->deleted_at = date('Y-m-d H:i:s');
					   if($image_details->save()){
                          echo 200;exit;     
					   }
					   else{
						echo 100;exit;   
					   }
					}
				}
				else{  echo 100;exit; }   
		   }
		/*End*/
		
		if($action == "get_model_name"){
          if(!empty($request->makers_id)){
			   $cars_models_response = kromedaHelper::get_models($request->makers_id);
			   $model_response = kromedaHelper::model_details($request->makers_id , $cars_models_response);
			   return json_encode(['status'=>200 , 'response'=>$cars_models_response]);
			 }
        }
        /*get car version script start*/
        if($action == "get_version_name"){
		   if(!empty($request->model_value)){
			$modeL_arr = explode('/' , $request->model_value);
			if(is_array($modeL_arr)){
				 $car_version_response = kromedaHelper::get_versions($modeL_arr[0] , $modeL_arr[1]);
				 if(count($car_version_response) > 0){
					 $save_response = kromedaHelper::save_version_response($request->model_value , $car_version_response);
					 return json_encode(array("status"=>200 , "response"=>$car_version_response));	
				 }
				 else return json_encode(array("status"=>400));	
				}
			else
			 return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
    	    }  
		}
		/*End*/
		/*Get Spare parts category database start*/
		if($action == "get_part_category_database"){
			if(!empty($request->language) || !empty($request->car_version_id)){
		    if($request->language == "en") $lang = "ENG";
		    else $lang = "ITA";
		      
			  $get_group = Products_group::get_group($request->car_version_id);
			  if($get_group->count() > 0){
				  return json_encode(array("status"=>200 , "response"=>$get_group));
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
	   
		 /*Get PArt category */
		 if($action == "get_part_category"){
		     
	       if(!empty($request->language) || !empty($request->car_version_id)){
			 if($request->language == "en") $lang = "ENG";
			 else $lang = "ITA";
			   
			   $car_spare_parts_cat = kromedaHelper::get_products_group($request->car_version_id , $lang);
			
			
				 $get_kromeda_group_collection = collect($car_spare_parts_cat);
		    	$get_group_from_database = Products_group::get_groups($request->makers_id , $request->models , $request->car_version_id , $lang);
				 $all_filtered_group = '';
				 if($get_group_from_database->count() > 0){
					   $group_arr_copy = $get_group_from_database->pluck('group_id')->all();                      $group_arr = array_filter($group_arr_copy);
					     foreach($get_kromeda_group_collection as $kromeda_group){
								 $all_filtered_group = $get_kromeda_group_collection->filter(function ($kromeda_group) use ($group_arr) {
									return !in_array($kromeda_group->idGruppo , $group_arr);
								 }); 
								 $get_kromeda_group_collection = $all_filtered_group;
							}
					}
					return json_encode(array("status"=>200 , "response"=>$get_kromeda_group_collection));					
				}
		   else{
			return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
			 }	  
		 } 
		/*End*/
		
		
		/*Get active item sub group*/
		if($action == "get_group_item"){
		     if(!empty($request->language) || !empty($request->group_id)){
			 $lang = sHelper::get_set_language($request->language);
			 $group_details = Products_group::find($request->group_id);
			  if($group_details != NULL){
					    /*Get Sub group Products script Start*/
						$item_parts = sHelper::get_groups_item_by_groups($group_details , $lang);
						/*End*/
					return json_encode(array("status"=>200 , "response"=>$item_parts));
			   }
			 }
		   else{
			return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
			 }
		  } 
		/*End*/
	}
}
