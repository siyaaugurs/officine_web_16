<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use sHelper;
use App\User;
use App\Products_group;
use App\Products;
use App\ProductsImage;
use App\ProductsNew;
use DB;
use \App\Library\apiHelper;
use \App\Library\kromedaDataHelper;
use App\Library\kromedaHelper;

class Sparepart extends Controller{
    
    public function get_product_brands(Request $request){
		$brands =  \App\BrandLogo::where([['deleted_at' , '=' , NULL]])->get();
		if(!empty($request->type)){
			$brands =  \App\BrandLogo::where([['deleted_at' , '=' , NULL], ['brand_type' ,'=', $request->type]])->get();
		}
		if($brands->count() > 0)
		   return sHelper::get_respFormat(1 , '' , null , $brands);
		else{
			return sHelper::get_respFormat(0 , 'Something went wrong , please try again !!!' , null , $brands);
		}   
	}
    
    public function  get_products_details($products_id , $selected_date = NULL){
        $selected_date = date('Y-m-d');
        if(!empty($products_id)){
			$products_details = \App\ProductsNew::where([['id','=',$products_id] , ['deleted_at' , '=' , NULL] , ['products_status' , '=' , 'A']])->first();
			if($products_details != NULL){
				/*Get Off workshop*/
				$off_days_workshop_users = [];
				$minPrice = $maxPrice = $product_min_price =  0;
				if (!empty($selected_date)) {
					$off_selected_date = \App\Workshop_leave_days::whereDate('off_date', $selected_date)->where([['status' , '=' , 'A']])->get();
					if($off_selected_date->count() > 0){
						$off_days_workshop_users = $off_selected_date->pluck('users_id')->all();
					}
				}
				/*End*/
			  	if($products_details->products_status == "A"){
					if(!empty($products_details->assemble_kromeda_time))
				          $assemble_time = $products_details->assemble_kromeda_time;
			     	else $assemble_time = $products_details->assemble_time;
				    if(empty($assemble_time)){ $assemble_time = 1; } 
						//$all_workshop_users_id_arr = [];
						$workshop_users_arr = [];
					    $products_groups_details = \App\Products_group::where([['deleted_at' , '=' , NULL] , ['id','=' ,$products_details->products_groups_id]])->first();	
					    	if($products_groups_details != NULL){
						    	$blongs_to_in_assemble_services = \App\Spare_category_item::get_assemble_service($products_groups_details->group_id);
								$workshop_users_arr = null;
								if($blongs_to_in_assemble_services != NULL){
									$workshop_users_arr =  DB::table('users_categories')
															    ->where([['categories_id' , '='  , $blongs_to_in_assemble_services->main_category_id] , 
																		['deleted_at' , '=' , NULL]])
																->whereNotIn('users_id' , $off_days_workshop_users)
																->get();
								} 
								
								if($workshop_users_arr->count() > 0){
									$min_price = [];
									foreach($workshop_users_arr as $workshop){
										$workshop->id = $workshop->users_id;
										$workshop_service_details = apiHelper::get_assemble_workshop_details($workshop , $blongs_to_in_assemble_services->main_category_id);
										if($workshop_service_details != FALSE){
											$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $workshop->users_id] , 
																													  ['deleted_at' , '=' , NULL]])->get();																			
											if($workshop_package_timing->count() > 0){
												$min_price[] = sHelper::calculate_service_price($assemble_time , $workshop_service_details['hourly_rate']);
											}
										}
									}
									$product_min_price  = (string) min($min_price); 
								} 
								$products_details->min_service_price = $product_min_price; 
								return sHelper::get_respFormat(1 , '' , $products_details, null);	 
							}
					        else
					        return sHelper::get_respFormat(0 , 'Something went wrong , please try again !!!' , null , null);
				}
				else{
					return sHelper::get_respFormat(0 , 'Please Select Product !!!' , null , null);
				}
	        }
        }
    }
    
    /*public function  get_products_details($products_id){
		if(!empty($products_id)){
			$products_details = ProductsNew::where("products_new.id",$products_id)->join('products_groups as pg' , 'products_new.products_groups_id' , '=' , 'pg.id')->select('products_new.id' , 'pg.car_makers as car_makers_name' , 'pg.car_model as models_name' , 'pg.car_version as car_version_id' , 'pg.id as category_id','products_new.products_status')->first();
			
			if($products_details != NULL){
			   if($products_details->products_status == "A"){
				$all_selected_workshop = \App\Services::where([['products_id' , '=' , $products_id] , ['is_deleted_at' , '=' , NULL] ,['status' , '=' , 1]])->get();
				if($all_selected_workshop->count() >  0){
				   $workshop_id_arr = $all_selected_workshop->pluck('id')->all();
				   if(count($workshop_id_arr) > 0){
					$min_price = null;
					$get_packages_list = \App\Services_package::whereIn('services_id' , $workshop_id_arr)->get();
					if($get_packages_list->count() > 0){
						$min_price = $get_packages_list->min('price');
					}
					$products_details->category_id = (string)$products_details->category_id;
					$products_details->min_service_price = number_format((float)$min_price, 2, '.', '');;
					return sHelper::get_respFormat(1 , '' , $products_details , null);		
				   }
				}
				else
				return sHelper::get_respFormat(0 , 'Workshop not available  !!!' , null , null);	
			   }	
			   else
			   return sHelper::get_respFormat(0 , 'This products is not approoved by admin !!!' , null , null);								
			}
			else
			return sHelper::get_respFormat(0 , 'Something went wrong , please try again !!!' , null , null);
		}
		else{
			return sHelper::get_respFormat(0 , 'Please Select Product !!!' , null , null);
		}

	}*/
	
	    
public static function get_products(Request $request){
	  if(!empty($request->car_version_id)){
		  if(!empty($request->category_id)){
			  $image_arr = null;
			  $products =  Products::get_version_products($request->car_version_id , $request->category_id);
			  if($products->count() > 0){
				 if(!empty($request->price_range)){
				   $price_arr = explode(',' , $request->price_range);
				   //$price_level_products = collect($new_products_arr);
				   $price_filtered = $products->whereBetween('price' , $price_arr);
				   $all_price_filtered = $price_filtered->all();
				   $new_products_arr = collect($all_price_filtered);	
				    } else {	
				   		$all_price_filtered = $products->all();
				   		$new_products_arr = collect($all_price_filtered);
				    }
					
				$all_price_filteredCalminmax = $products->all();
				$new_products_arrCalminmax = collect($all_price_filteredCalminmax);
						
				  if(!empty($request->price_level)){
				   if($request->price_level == 1){
					 $sorted = $new_products_arr->sortBy('price');
					 $newsorted_products_arr = $sorted->values()->all();
					}
				   if($request->price_level == 2){
					  $sorted = $products->sortByDesc('price');
					  $newsorted_products_arr = $sorted->values()->all();
					}
				  }
				  else{ 
				  
				   	 $sorted = $new_products_arr->sortBy('price');
					 $newsorted_products_arr = $sorted->values()->all();
				  
				    }
				 
				// echo "<pre>";
				 //print_r($newsorted_products_arr);exit;
				 $minPrcie=0;
				 $maxPrcie=0; 
				 
				 foreach($new_products_arrCalminmax as $key=>$product){	
					
					if($key==0){
						$minPrcie=$product->price;
					}
					$minPrcie=($product->price<$minPrcie)?$product->price:$minPrcie;
					$maxPrcie=($product->price>$maxPrcie)?$product->price:$maxPrcie;
				   } 
				 
				 foreach($newsorted_products_arr as $key=>$product){
					$product->images = null; 
					$product->minPrcie = $minPrcie; 
					$product->maxPrcie = $maxPrcie;
					
				    /*For Products Image Add*/
					$image_arr = ProductsImage::get_products_image($product->id);
					if($image_arr != FALSE){
					    $product->images = $image_arr;
					  }
					/*End*/ 
				   } 
				 return sHelper::get_respFormat(1 , '' , null , $newsorted_products_arr);
				}
			  else
			   return sHelper::get_respFormat(0 , 'No Products Available' , null , null); 	
			}
		  else
		  return sHelper::get_respFormat(0 , 'Please select any one group ' , null , null); 
		}
	  else  
	  return sHelper::get_respFormat(0 , 'Please select any one car ' , null , null);   
	}
	
	
public  function get_productsnew(Request $request){
	$products = collect();
	$cat_id_arr = [];
	  /* if(!empty($request->car_version_id)){ */
		  $image_arr = null;
		  $min_price = $max_price = 0;
		  if(!empty($request->category_type)){
		    if(!empty($request->category_id)){
		      	/*Type = 1 , N1 category id , type = 2 , N2 category id , type = 3 N3 category id , type = 4 car maintenance click on replace button*/  
    			if($request->category_type == 1){
    			   /*Get All N2 related to N1 */
    				$n1_details = DB::table('products_groups')->where([['id' , '=' , $request->category_id] , ['parent_id' , '=' , 0]])->first();
					if($n1_details != NULL){
    					$n2_category_response = sHelper::get_sub_categories($n1_details);   
    					if($n2_category_response->count() > 0){
    						$cat_id_arr[] = $n2_category_response->pluck('id')->all();
    						$cat_id_arr[] = 1;
    						/* if(count($n2_cat_id_arr) > 0){
    							$products =  ProductsNew::get_products($n2_cat_id_arr , 1);
    						} */
    						if(empty($request->product_keyword))
								  
						$products =  ProductsNew::get_products($request->product_keyword , $request->car_version_id , $cat_id_arr);	
						if(!empty($request->product_keyword)){
            			   	    $products = ProductsNew::get_products_with_keyword($request->product_keyword , $request->car_version_id , $cat_id_arr);
            			   	}
    					}
    				} else {
    				       return sHelper::get_respFormat(0 , 'Category id or Category type does not matched  !!!' , null , null); 	 
    				}
    			   /*End*/
    			}
    			elseif($request->category_type == 2){
                   /*Get All N3 related to N2*/
    				$n2_details = DB::table('products_groups')->where([['id' , '=' , $request->category_id] , ['parent_id' , '!=' , 0]])->get();
    				if($n2_details->count() > 0){
    					$cat_id_arr[] = $n2_details->pluck('id')->all();
    					$cat_id_arr[] = 2;
    					if(empty($request->product_keyword)){
        				     $products =  ProductsNew::get_products($request->product_keyword , $request->car_version_id , $cat_id_arr);
        				}
    			   	 	if(!empty($request->product_keyword)){
        			   	    $products = ProductsNew::get_products_with_keyword($request->product_keyword , $request->car_version_id , $cat_id_arr);
        			   	}
    				}
    				else{
    				   return sHelper::get_respFormat(0 , 'Category id or Category type does not matched  !!!' , null , null); 	 
    				}
        				
    			   /*End*/    
    			}
    			elseif($request->category_type == 3){
					$n3_details = DB::table('products_groups_items')->where([['id' , '=' , $request->category_id]])->first();
					if($n3_details != NULL){
						/*Check products available or not*/
						$products = DB::table('products_new')->where([['products_groups_items_id' , '=' , $n3_details->id]])->get();
						if($products->count() <= 0){
							$item_part_number_response = \App\ProductsItemNumber::get_part_item_number($n3_details->id);
							if($item_part_number_response->count() > 0){
								foreach($item_part_number_response as $part_number){
									/*OE_Get_cross */
									$get_products = kromedaHelper::oe_products_item((string) $part_number->CodiceListino , (string) $part_number->CodiceOE);
									if(is_array($get_products) && count($get_products) > 0){
										$add_products_response = \App\ProductsNew::add_products_by_kromeda_new($n3_details  , $part_number , $get_products);
									}
									/*End*/   
									/*Oe Get other cross Api response script start*/
									$get_other_products = kromedaHelper::oe_getOtherproducts((string) $part_number->CodiceListino , (string)$part_number->CodiceOE);
									//For testing purpose
									if(is_array($get_other_products) && count($get_other_products) > 0){
										/*Custom Query Script Start*/
										$response = \App\ProductsNew::add_other_products_by_kromeda_new($n3_details  , $part_number , $get_other_products);
										/*End*/
									}
								}
							}
						}
    					$cat_id_arr[] = $n3_details->id;
    					$cat_id_arr[] = 3;
    				}
    			   	if(empty($request->product_keyword))
					   $products =  ProductsNew::get_products($request->product_keyword , $request->car_version_id , $cat_id_arr);	
					
					   if(!empty($request->product_keyword)){
    			   	    $products = ProductsNew::get_products_with_keyword($request->product_keyword , $request->car_version_id , $cat_id_arr);
    			   	}
    			} elseif($request->category_type == 4){
					$n2_details = DB::table('products_groups')->where([['language' , '=' , $request->language] , ['car_version' , '=' , $request->version_id]])->get();
					$n2_details = $n2_details->pluck('id');
					if(!empty($request->item_id)){
						$n3_details = \App\ProductsGroupsItem::whereIn('id',$n2_details)->get();
						$n3_category = $n3_details->where('item_id',$request->item_id)->first();
						if($n3_category != NULL){
						$products = ProductsNew::where('products_groups_items_id',$n3_category->id)->get();	
						}else{
							return sHelper::get_respFormat(0 , 'no n3 item id !!!' , null , null); 
						}	
						
					} else {
						return sHelper::get_respFormat(0 , 'Please add insert item id !!!' , null , null); 
					}	
				}  
		    }
		  } else {
		    $group_item_details = \App\ProductsGroupsItem::find($request->category_id);
		    if($group_item_details != NULL){
				$products =  ProductsNew::get_version_products($group_item_details->id);  
		    }
		  }
		 
		  if(!empty($request->product_keyword)){
		     if(count($cat_id_arr) > 0){
				$products =  ProductsNew::get_products_with_keyword($request->product_keyword , $request->car_version_id , $cat_id_arr);
			 } 
			 else{
				$get_all_n2 = DB::table('products_groups')->where([['car_version' , '=' , $request->car_version_id] , ['parent_id' , '!=' , 0] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->get();
				   $n2_cat_id_arr = [];
    				if($get_all_n2->count() > 0){
    					$n2_cat_id_arr = $get_all_n2->pluck('id')->all();
    				}
    				$products =  ProductsNew::get_products_with_version($request->product_keyword , $n2_cat_id_arr);
			 }
		  }
		  //if(!empty($request->product_keyword)){
			 	if($products->count() > 0){
					//$products =  ProductsNew::get_version_products($group_item_details->id);
					if($products->count() > 0){
						$seller_price = [];
						if(!empty($request->brand)){
							$brand_name_arr = explode(',' , $request->brand);
							$products = $products->whereIn('listino' , $brand_name_arr);
						}
						foreach($products as $product){	
							$product->type = (string) $product->type;
							/*Get 3 servive for the workshop */
							$product->coupon_list = sHelper::get_coupon_product_list($product->id ,1 , $product->listino);
							/*end*/ 
							$product = \kromedaDataHelper::arrange_spare_product($product);
							$product->seller_price = (string) $product->seller_price;
							$seller_price[] = $product->seller_price;
							$product->brand_image = null;
							$product->images = null;
							$image_arr = sHelper::get_products_image($product);
							if($image_arr->count() > 0){
								$product->images = $image_arr;
							}
							/*Get Brand image logo */
							$brand_image = \App\BrandLogo::brand_logo($product->listino);
							if($brand_image != NULL){
								$product->brand_image = $brand_image->image_url; 
							} 
							/*End*/  
							$product->wish_list = 0;
							if(!empty($request->user_id)){
								$user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_product($product->id , $request->user_id , $request->product_type);
								if($user_wishlist_status == 1){
									$product->wish_list = 1;
								} else {
									$product->wish_list = 0;
								}	
							}
							$all_feed_back = null;
							$all_feed_back['rating'] = null;
							$all_feed_back['num_of_users'] = null;
							$all_feed_back = \App\Feedback::get_product_rating_list($product);
							if($all_feed_back != NULL) {
								$product->rating = $all_feed_back;
								$product->rating_star = $all_feed_back['rating'];
								$product->rating_count = $all_feed_back['num_of_users'];
							}
						}
						$min_price =  min($seller_price);
						$max_price = max($seller_price);
						$products->map(function($product) use ($min_price , $max_price){
							$product->min_price = (string) !empty($min_price) ? $min_price : "0";
							$product->max_price = (string) $max_price;
							return $product;
						}); 

						if(!empty($request->price_range)){
							$price_arr = explode(',' , $request->price_range);
							//$price_level_products = collect($new_products_arr);
							$price_filtered = $products->whereBetween('seller_price' , $price_arr);
							$products = $price_filtered;
							//$new_products_arr = collect($all_price_filtered);	
						}
						if(!empty($request->price_level)){
							if($request->price_level == 1){
								$sorted = $products->sortBy('seller_price');
								$products = $sorted->values();
								}
							if($request->price_level == 2){
								$sorted = $products->sortByDesc('seller_price');
								$products = $sorted->values();
								}
						}
						else{ 
							$sorted = $products->sortBy('seller_price');
							$products = $sorted->values();
						} 
						return sHelper::get_respFormat(1 , '' , null , $products); 	 
					} else {
						return sHelper::get_respFormat(0 , 'No products Available !!!' , null , null); 	 
					}
				} else {
					return sHelper::get_respFormat(0 , 'Products Not available  !!!' , null , null); 	 
				} 
		//	}
		 /* else
		  return sHelper::get_respFormat(0 , 'Please fill all required fields !!!' , null , null);*/ 
		/* }
	  else  
	  return sHelper::get_respFormat(0 , 'Please select any one car ' , null , null);   */
	}
	
    public function get_spare_n3_category($group_id){
		$sub_groups = Products_group::find($group_id);
		if($sub_groups != NULL){
			//$products_groups_item = sHelper::get_group_items_for_users($sub_groups);
			/*Get N3 categories*/
			 if($sub_groups->type == 1){
				 /*Check kromeda n3 available or not  */
				$product_group_item = \App\ProductsGroupsItem::where([['version_id' , '=' , $sub_groups->car_version] , ['n2_kromeda_group_id' , '=' , $sub_groups->group_id] , ['language' , '=' , $sub_groups->language]])->get();
					if($product_group_item->count() <= 0){
						$product_response = kromedaHelper::get_sub_products_by_sub_group($sub_groups->car_version , $sub_groups->group_id , $sub_groups->language);
						if(is_array($product_response) && count($product_response) > 0){
							$response =  \App\ProductsGroupsItem::add_group_items_new($product_response , $group_id , $sub_groups->language , $sub_groups->car_version , $sub_groups->group_id);
						}
					}
				/*End*/
				  $products_groups_item =  \App\ProductsGroupsItem::where([['version_id' , '=' , $sub_groups->car_version] , ['n2_kromeda_group_id' , '=' , $sub_groups->group_id] , ['language' , '=' , $sub_groups->language] , ['type' , '=' , 1]])
																  ->orWhere([['version_id' , '=' , NULL] , ['n2_kromeda_group_id','=' ,$sub_groups->group_id],['deleted_at' , '=' , NULL],
																  ['type','=',2]])->get();
			    }
				else{
				  $products_groups_item =  \App\ProductsGroupsItem::where([['products_groups_id' , '=' , $sub_groups->id] , ['deleted_at' , '=' , NULL]])->get(); 
				}
			/*End*/
			if($products_groups_item->count() > 0){
				foreach($products_groups_item as $group_item){
					$group_item = kromedaDataHelper::arrange_n3_category($group_item);
					/*Image Set script start*/
					$group_item->images = NULL;
					$images = sHelper::get_n3_category_images($group_item);
					if($images->count() > 0){
					   $group_item->images = $images;
					  }
					/*End*/
				}
				if($products_groups_item->count() > 0){
				    $products_groups_item = $products_groups_item->where('status' , '!=' , 'P'); 
				  }
				$sorted = $products_groups_item->sortBy('priority')->values();
				return sHelper::get_respFormat(1 , null , null , $sorted);   
			}
			else return sHelper::get_respFormat(0 , 'sub category not available ' , null , null);  
	    }
		else
	return sHelper::get_respFormat(0 , 'please select correct category' , null , null);	 }

    
   public function get_spare_sub_group($parent_id){
	   $group_details = Products_group::find($parent_id);
	   if($group_details != NULL){
		  if(!empty($group_details->group_id)){
			$sub_groups = Products_group::where([['car_version' , '=' , $group_details->car_version]  ,
			                                     ['products_groups_group_id' , '=' , $group_details->group_id], ['deleted_at' , '=' ,NULL] , ['type' , '=' ,1]])
                          ->orWhere([['products_groups_group_id','=' ,$group_details->group_id] , ['parent_id' , '!=' , 0] , ['car_version' , '=' , NULL] , ['type' , '=' ,2] , ['deleted_at' , '=' , NULL]])->get();
			}
		  else{
			$sub_groups = Products_group::where([['parent_id' , '=' , $group_details->id] , ['deleted_at' , '=' , NULL]])->get();
			 } 
			if($sub_groups->count() > 0){
			foreach($sub_groups as $group){
				$group = kromedaDataHelper::arrange_n2_category($group);
				$group->images = null;
				$images = sHelper::get_sub_group_image($group);
				if($images->count() > 0){ $group->images = $images;  } 
			}
			if($sub_groups->count() > 0){
					$sub_groups = $sub_groups->where('status' , '!=' , 'P');
			}
			$sorted = $sub_groups->sortBy('priority')->values()->all();
			return sHelper::get_respFormat(1 , null , null , $sorted);  
			//return sHelper::get_respFormat(1 , null , null , $sub_groups);   
			} 
		  else{
			return sHelper::get_respFormat(0 , 'No Group Available' , null , null);    
		  } 
	   }
	   else
	   return sHelper::get_respFormat(0 , 'please select correct category' , null , null);  
	}
	
    
    public function get_spare_parts($car_version , $lang){
	   if(!empty($car_version)){
			/*check kromeda groups available or not*/
			$groups = Products_group::where([['car_version' , '=' , $car_version]])->get();
			if($groups->count() <= 0){
			   $version = DB::table('versions')->where([['idVeicolo' , '=' , $car_version]])->first();
				if($version != NULL){
					$model = \App\Models::get_model($version->model);
					if($model != NULL){
						$response = kromedaDataHelper::get_groups_and_save($model->maker , $version->model , $car_version , $lang);
					}
				}
			}
			/*End*/
           $get_group_name = Products_group::get_parent_groups($car_version ,$lang);
		   if($get_group_name->count() > 0){
				foreach($get_group_name as $group){
					$group->images = null;
					$group = kromedaDataHelper::arrange_n1_category($group);
					$images = sHelper::get_group_image($group);
						if($images->count() > 0){
							$group->images = $images;
						} 
				}
					if($get_group_name->count() > 0){
						$get_group_name = $get_group_name->where('status' , '!=' , "P");
					}	
					$sorted = $get_group_name->sortBy('priority')->values();
					return sHelper::get_respFormat(1 , null , null , $sorted);  
			}        
	        else
            return sHelper::get_respFormat(0 , 'No Group Available' , null , null);  
       }
       else
       return sHelper::get_respFormat(0 , 'Please select any one car ' , null , null);  
    }


}
