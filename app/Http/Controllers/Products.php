<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use sHelper;
use App\Model\Kromeda;
use App\Products_group;
use App\Users_category;
use kRomedaHelper;
use kromedaSMRhelper;
use App\ItemsRepairsServicestime;
use DB;
use kromedaDataHelper;
use App\BrandLogo;

class Products extends Controller{
    
	 //public $server_base_url = 'http://officine.augurstech.com/officineTop/';

	 public function index($page = "products",  $p1 = NULL){
		$data['title'] = "Officine top - ".$page;
        $data['page'] = $page;
		if (Auth::check()) {
          $data['users_profile'] = \App\User::find(Auth::user()->id);
          $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
            $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
        }else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
		
		if($page == "products"){
		   $data['parent_category'] = sHelper::get_parent_category();
		   $car_makers = Kromeda::get_response_api("getMakers");
		   if($car_makers == FALSE){
			    $sess_key = sHelper::generate_kromeda_session_key();
                $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'CP_GetMakers' , false , '');
                Kromeda::add_response("getMakers" , $third_party_response);
				$car_makers = Kromeda::get_response_api("getMakers");
			 }
		  // $data['cars__makers_category'] = $car_makers->result[1]->dataset;
		   $data['cars__makers_category'] = \App\Maker::all();
		}
		if(!view()->exists('products.'.$page))
		return view("404")->with($data);
		else 
		return view("products.".$page)->with($data);
     }
	 
	 public function page($page , $p1 = NULL){
		$data['title'] = "Officine top - ".$page;
		$data['page'] = $page;
		$data['cars__makers_category'] = \App\Maker::all();
		if (Auth::check()) {
          $data['users_profile'] = \App\User::find(Auth::user()->id);
          $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
           $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
            $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
        }else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
	    
	    /*Start List Custom*/
		 if($page == "list_custom_products") {
			$data['custom_products'] = DB::table('products_new')->where([['type' , '=' , "2"], ['deleted_at', '=', NULL]])->orderBy('created_at' , 'DESC')->paginate(15);
			if($data['custom_products']->count() > 0){
				foreach($data['custom_products'] as $product){
					/*Product details*/
					$product_details = sHelper::get_products_details($product);
					if($product_details != NULL){
					    $product->seller_price = $product_details->seller_price;
						$product->products_status = $product_details->products_status;
						$product->products_quantiuty = $product_details->products_quantiuty;
					    $product->our_products_description = $product_details->our_products_description;
					  }
					/*End*/
					$product->image = sHelper::get_product_image($product->id);
					$product->p_id = encrypt($product->id);
				}
			}
		}

		if($page == "remove_custom_produts") {
			$custom_product_detail = \App\ProductsNew::where('id', $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			if($custom_product_detail) {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Product delete successfull !!! </div>']);
			} else {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);	
			}
		}
		/*End*/

		if($page == "brand_logo") {
			$brand_obj = new BrandLogo;
			$data['brand_type_arr'] = $brand_obj->brand_logo_type;
			$data['brand_logo_details'] = \App\BrandLogo::get_brand_logo_details();
			$data['image'] = url("storage/products_image/no_image.jpg");
		}
		if($page == "remove_brand_logo") {
			$brand_details = \App\BrandLogo::where('id', $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			if($brand_details) {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Brand Logo delete successfull !!! </div>']);
			} else {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);	
			}
		}
		
		
			
		if($page == "edit_custom_products") {
			if(!empty($p1)){
				$p2 = decrypt($p1);
				// echo "<pre>";
				// print_r($p2);exit;
				$lang = sHelper::get_set_language(app()->getLocale());
				$data['product_details'] = \App\ProductsNew::get_custom_products_details($p2);
				if($data['product_details'] == NULL){  return redirect()->back();  } 
				$data['product_details']->product_categories = $data['product_details']->product_group_item = $data['product_details']->products_images = NULL;
				$product_categories = \serviceHelper::get_product_category($data['product_details']->products_groups_id);
				if(count($product_categories) > 0){
					$data['product_details']->product_categories = $product_categories;
				}
			   /*Get Product n3 category */
				$product_categories_n3 = \App\ProductsGroupsItem::find($data['product_details']->products_groups_items_id);
				if($product_categories_n3 != NULL){
				  $data['product_details']->product_group_item = $product_categories_n3;
				}
			   /*End*/
				$data['brands'] = \App\BrandLogo::get_brand_logo_details(1);
				if($data['product_details'] != NULL){
					$product_details = sHelper::get_products_details($data['product_details']);
					if($product_details != NULL){
						$data['product_details']->our_products_description = $product_details->our_products_description;
						$data['product_details']->bar_code = $product_details->bar_code;
						$data['product_details']->products_name1 = $product_details->products_name1;
						$data['product_details']->for_pair = $product_details->for_pair;
						$data['product_details']->meta_key_title = $product_details->meta_key_title;
						$data['product_details']->meta_key_words = $product_details->meta_key_words;
						$data['product_details']->seller_price = $product_details->seller_price;
						$data['product_details']->products_quantiuty = $product_details->products_quantiuty;
						$data['product_details']->minimum_quantity = $product_details->minimum_quantity;
						$data['product_details']->tax = $product_details->tax;
						$data['product_details']->tax_value = $product_details->tax_value;
						$data['product_details']->substract_stock = $product_details->substract_stock;
						$data['product_details']->unit = $product_details->unit;
						$data['product_details']->assemble_time = $product_details->assemble_time;
						$data['product_details']->assemble_status = $product_details->assemble_status;
					}
					$products_images = sHelper::get_products_image($data['product_details']);
					if($products_images->count() > 0){
					  $data['product_details']->products_images = $products_images;
					}
				  }
				$data['compatible_details'] = \App\ProductsCarCompatible::get_car_compitable($p2);
				$all_kromeda_n1 = Products_group::get_n1_categories(1 , $lang);
				$data['all_custom_n1'] = Products_group::get_n1_categories(2 , $lang);
				if($all_kromeda_n1->count() > 0){
					$data['category_list_new'] = $all_kromeda_n1->unique('group_id');
				}
				//echo "<pre>";
				//print_r($data['product_details']);exit;
				/*
				if(!empty($data['products_compatible']->version)){
					if($data['products_compatible']->version != "1"){
						$version_details = \App\Version::get_version($data['products_compatible']->version); 
						$data['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
						$data['version_value'] = $data['products_compatible']->version;
					}
					else{
					   	$data['version_name'] = "All Versions";
						$data['version_value'] = 1;
					}
				}
				if(!empty($data['products_compatible']->version)){
					if($data['products_compatible']->version != "1"){
						$version_details = \App\Version::get_version($data['products_compatible']->version); 
						$data['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
						$data['version_value'] = $data['products_compatible']->version;
					}
					else{
					   	$data['version_name'] = "All Versions";
						$data['version_value'] = 1;
					}
				}
				if($data['products_compatible']->group != 0) {
					if(!empty($data['products_compatible']->group)) {
						$group_details = \App\Products_group::get_group_first($data['products_compatible']->group); 
						$data['group_name'] = $group_details->group_name;
						$data['group_value'] = $data['products_compatible']->group;
					}
				} else {
					$data['group_name'] = "All Category";
					$data['group_value'] = "all";
				}
				if($data['products_compatible']->sub_group != 0) {
					if(!empty($data['products_compatible']->sub_group)) {
						if($data['products_compatible']->sub_group != "0"){
							$group_details = \App\Products_group::get_group_first($data['products_compatible']->sub_group); 
							$data['sub_group_name'] = $group_details->group_name;
							$data['sub_group_value'] = $data['products_compatible']->sub_group;
						}
					}
				} else {
					$data['sub_group_name'] = "All Sub Category";
					$data['sub_group_value'] = "all";
				}
				if($data['products_compatible']->item != 0) {
					if(!empty($data['products_compatible']->item)) {
						$item_details = \App\ProductsGroupsItem::get_group_item($data['products_compatible']->item); 
						$data['item_name'] = $item_details->item." ".$item_details->front_rear." ".$item_details->left_right;
						$data['item_value'] = $data['products_compatible']->item;
					}
				} else {
					$data['item_name'] = "All Category items";
					$data['item_value'] = "all";
				}
				*/
				// echo "<pre>";
				// print_r($data['products_compatible']);exit;
			}
		}
		
		if($page == "remove_n3_category") {
			$n3_category_details = \App\ProductsGroupsItem::where('id', $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			if($n3_category_details) {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Group delete successfull !!! </div>']);
			} else {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);	
			}
		}
	    /*propducts item script start */
		if($page == "products_items"){
			$data['parent_category'] = sHelper::get_parent_category();
			$car_makers = Kromeda::get_response_api("getMakers");
			if($car_makers == FALSE){
				 $sess_key = sHelper::generate_kromeda_session_key();
				 $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'CP_GetMakers' , false , '');
				 Kromeda::add_response("getMakers" , $third_party_response);
				 $car_makers = Kromeda::get_response_api("getMakers");
			  }
			//$data['cars__makers_category'] = $car_makers->result[1]->dataset;
		}
		/*End */
	    /*Remove Group start */
		if($page == "remove_group"){
		    $group_details = \App\Products_group::where('id', $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			if($group_details){
			   return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Group delete successfull !!! </div>']);
			   }
			 else{
			   return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);	
			  }  
		  }
		if($page == "manage_n3_category") {
			//$data['cars__makers_category'] = kRomedaHelper::get_makers();
		}
		if($page == "delete_n3_category") {
			if(!empty($p1)) {
				$result = \App\ProductsGroupsItem::delete_n3_category($p1);
				if($result) {
					return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Record Deleted Successfully !!! </div>']);
				} else {
					return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);
				}
			}
		}
		/*End*/
		/* if($page == "add_group"){
		   $car_makers = Kromeda::get_response_api("getMakers");
		   if($car_makers == FALSE){
			    $sess_key = sHelper::generate_kromeda_session_key();
                $third_party_response = sHelper::Get_kromeda_Request($sess_key , 'CP_GetMakers' , false , '');
                Kromeda::add_response("getMakers" , $third_party_response);
				$car_makers = Kromeda::get_response_api("getMakers");
			 }
		   $data['cars__makers_category'] = $car_makers->result[1]->dataset;
		  } */
		  
		  if($page == "category_list_new" || $page == "category_list_new1"){
		    $lang = sHelper::get_set_language(app()->getLocale());
		    $data['private_service_group'] = \App\MainCategory::where([['private', '=', 1], ['deleted_at', '=', NULL],['status', '=', 'A']])->get();
			$categories = Products_group::get_all_unique_category($lang);
			if($categories->count() > 0){
				foreach($categories as $category){
					$category = kromedaDataHelper::arrange_n1_category($category);
				 }
			  }
			 $data['category_for_listing'] = $categories->where('status' , '=' , 'A')->where('deleted_at' , '=' , NULL); 
			 $data['categories'] = $categories; 
		} 
		
		if($page == "category_list"){
	   	    $data['group_list'] = Products_group::get_group_list();
			$data['n1_category_list'] = Products_group::get_n1_category_list();
// 			echo "<pre>";
// 			print_r($data['n1_category_list']);exit;
			$data['n1_custom_category_list'] = Products_group::get_custom_n1_category_list();
		}
		
		if($page == "products_list"){
			set_time_limit(500);
		   	$data['products'] = \App\ProductsNew::get_unique_products();
			if($data['products']->count() > 0){
				foreach($data['products'] as $product){
					$product_details = sHelper::get_products_details($product);
					if($product_details != NULL){
						$product->seller_price = $product_details->seller_price;
						$product->products_status = $product_details->products_status;
						$product->products_quantiuty = $product_details->products_quantiuty;
						$product->our_products_description = $product_details->our_products_description;
					}
				}
			}
		}
		
		if($page == "remove_car_compatible") {
			if(!empty($p1)){ 
				$car_compatible = \App\ProductsCarCompatible::where('id', '=',  $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				if($car_compatible){
					return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Record delete successfull !!! </div>']);
				} else {
					return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);
				} 
			} else {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);   
			}
		}
		
	
		
		if($page == "add_new_products"){
			set_time_limit(500);
			$lang = sHelper::get_set_language(app()->getLocale());
			$data['products_details']  =  $data['products_images']  = $data['compatible_details'] = $data['times'] = NULL;
			if(!empty($p1)){
				$data['products_details'] = \App\ProductsNew::find(decrypt($p1));
				if($data['products_details'] == FALSE){ return redirect()->back(); }
				/* $product_number_detail = \App\ProductsItemNumber::where([['id', '=', $data['products_details']->products_item_numbers_id]])->first();
				if($product_number_detail != NULL) {
					$get_version_details = \App\Version::get_version($product_number_detail->version_id);
					if($get_version_details != NULL) {
						$model_details = \App\Models::get_model($get_version_details->model);

						if($model_details != NULL) {
							$save_response = kromedaDataHelper::get_groups_and_save($model_details->maker , $get_version_details->model , $get_version_details->idVeicolo , $lang);
							
							$get_all_n2 = \App\Products_group::where([['car_version', '=', $product_number_detail->version_id], ['parent_id', '!=', 0]])->get();

							if($get_all_n2->count() > 0) {
								foreach($get_all_n2 as $n2_category) {
									if($n2_category->type != 2){
										$product_item_response = \App\ProductsGroupsItem::check_today_execute($n2_category->id); 
										if($product_item_response->count() <= 0){
											$product_response = kromedaHelper::get_sub_products_by_sub_group($product_number_detail->version_id , $n2_category->group_id , $lang);
											if(is_array($product_response)){
												$response =  \App\ProductsGroupsItem::add_group_items_new($product_response , $n2_category->id , $lang , $product_number_detail->version_id , $n2_category->group_id);
											}
										}
									}
								}
							}
							
						}
					}
				} */
				$data['product_details'] = kromedaDataHelper::arrange_spare_product($data['products_details']);
				/*N3 category*/
				$data['product_details']->n3_category = \App\ProductsGroupsItem::find($data['products_details']->products_groups_items_id);
				/*End*/
				  /*assemble Time get*/
				  $assemble_time = kromedaDataHelper::save_car_maintinance_for_assemble($data['products_details'] , $data['product_details']->n3_category);
				  /* $assemble_times = kromedaDataHelper::get_car_kromeda_time($data['products_details']);
				  if(is_array($assemble_times)){
					  $save_assemble_time = DB::table('products_new')->where([['id' , '=' , decrypt($p1)]])->update(['assemble_kromeda_time'=>$assemble_times['assemble_time']]);  
				  }*/
				  /*End*/
				$data['product_details']->images = $data['product_details']->product_categories = NULL;
				/*Kromeda Car compatible*/
				$data['kromeda_car_compatible'] = kromedaDataHelper::kromeda_car_compatible( (string) $data['products_details']->products_name);
				/*End*/
				/*Get Products Images*/
				$products_images = sHelper::get_products_image($data['products_details']);
				if($products_images->count() > 0){
					$data['product_details']->images = $products_images;
				}
				$product_categories = \serviceHelper::get_product_category($data['products_details']->products_groups_id);
				if(count($product_categories) > 0){
				    $data['product_details']->product_categories = $product_categories;
				  }
				
				/*End*/
				$data['compatible_details'] = \App\ProductsCarCompatible::get_car_compitable(decrypt($p1));	
			/**Get all n1 category*/
				$category_list =  Products_group::get_all_unique_category($lang);
                if($category_list->count() > 0){
					$data['category_list_new'] = $category_list->where('deleted_at' , NULL)->where('status' , 'A');
				}
			/*End*/		
			 }
		} 
		
		if($page == "add_new_custom_products"){
		//	$data['cars__makers_category'] = kRomedaHelper::get_makers();
			if(empty($data['cars__makers_category'])){
				$data['cars__makers_category'] =array();	
			}
			$data['brands'] = \App\BrandLogo::get_brand_logo_details(1);
		 }
		 
        if(!view()->exists('products.'.$page))
		return view("404")->with($data);
		else 
		return view("products.".$page)->with($data);
	 }
	 
	  public function get_action(Request $request , $action){
	      if($action == "check_bar_code") {
				if(!empty($request->bar_code)){
					$response =  \DB::table('products_new')->where([['bar_code' , '=' , $request->bar_code]])->first(); 
					if($request->product_id != NULL) {
						if($response != NULL) {
							if($response->id == $request->product_id) {
								echo 2;exit;
							}
						}
					}
					if($response != FALSE){
						echo 1;exit;
					}
					else{
						echo 2;exit;
					}  
				} else {
					return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>')); 
				}
			}
	      if($action == "change_product_group_status") {
				if(!empty($request->group_id)){
					$result = \App\Products_group::find($request->group_id);
					if($result->parent_id == 0) {
						if($result->type == 1) {
							$res = \App\CategoriesDetails::updateOrCreate(['n1_n2_group_id' => $result->group_id], ['n1_n2_group_id' => $result->group_id,'status'=>$request->status]);
						} else {
							$res = \App\CategoriesDetails::updateOrCreate(['n1_n2_id' => $result->id], ['n1_n2_id' => $result->id,'status'=>$request->status]);
						}
					} else {
						if($result->type == 1) {
							$res = \App\CategoriesDetails::updateOrCreate(['n2_group_id' => $result->group_id], ['n2_group_id' => $result->group_id,'status'=>$request->status]);
						} else {
							$res = \App\CategoriesDetails::updateOrCreate(['n2_id' => $result->id], ['n2_id' => $result->id,'status'=>$request->status]);
						}
					}
					if($result != NULL){
						$result->status = $request->status;
						if($result->save()){
							echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
						} else {
							echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
						} 
					} else {
						echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
					}	 
				} else {
					echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
				}
			}
			if($action == "change_group_item_status") {
				// return $request;
				if(!empty($request->item_id)){
					$result = \App\ProductsGroupsItem::find($request->item_id);
					if($result->type == 1) {
						$res = \App\CategoriesDetails::updateOrCreate(['n3_item_id'=>$result->item_id],['n3_item_id' => $result->item_id,'status'=>$request->status]);
					} else {
						$res = \App\CategoriesDetails::updateOrCreate(['n3_id' => $result->id], ['n3_id' => $result->id,'status'=>$request->status]);
					}
					if($result != NULL){
						$result->status = $request->status;
						if($result->save()){
							echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
						} else {
							echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
						} 
					} else {
						echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
					}	 
				} else {
					echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
				}
			}
	       /*Change Assemble Products Status*/
		 if($action == "change_products_assemble_status"){
		     if(!empty($request->products_id) && !empty($request->products_status)){
			      $response = \App\Products_assemble::find($request->products_id);
				  $response->pa_status = $request->products_status;
				  if($response->save()){ echo 1;exit; }
				  else{ echo 2;exit; }
			  }
		   } 
		  /*End*/
	       if($action == "get_sub_category") {
			if(!empty($request->categoryId)){
			  $category_details = Products_group::find($request->categoryId);
			  $sub_category = Products_group::get_sub_category($request->categoryId);
			return view('products.component.sub_cat_details')->with(['category'=>$category_details , 'sub_category'=>$sub_category]);
			} 
			else {
				echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
			}
		}
        
        if($action == "edit_group_name") {
			if(!empty($request->categoryId) && !empty($request->categoryName)) {
				$success_response =  \App\Products_group::where('id', $request->categoryId)->update(['group_name' => $request->categoryName ]);
			    if($success_response){
			        echo '<div class="notice notice-success"><strong> Success </strong> Record Save Successfully !!! </div>';exit;  
			    }
			    else{
			    echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
			    }
			} else {
				echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
			}
		}
        
		 //echo $action;exit;
		  if($action == "change_products_status"){
		      if(!empty($request->products_id)){
				   $products_details = \App\ProductsNew::find($request->products_id);
				   if($products_details != NULL){
					   $product_details = sHelper::get_products_details($products_details);
					   if($product_details == NULL){
							$response = \App\ProductsNew_details::create(['product_id'=>$products_details->id  , 'products_kromeda_id'=>$products_details->products_name, 'products_status'=>$request->products_status]);
							if($response){
							   echo 1;exit;
							} 
							
						 }
					   else{
						   $product_details->products_status = $request->products_status;
						   if($product_details->save()){
							   echo 1;exit;
							} 
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
			
	    /*Get Assemble Products details script strta*/
		 if($action == "get_assemble_products_details"){
		    if(!empty($request->products_id)){
				 $p_assemble_details =  \App\Products_assemble::find($request->products_id);
				 if($p_assemble_details != NULL){
				     $data['products_details'] = \App\ProductsNew::get_products_details($p_assemble_details->products_id);
						if($data['products_details'] != NULL){
						$data['maker_name'] = kRomedaHelper::get_maker_name($data['products_details']->car_makers);
						$data['model_name'] = kRomedaHelper::get_model_name($data['products_details']->car_makers ,   $data['products_details']->car_model);
						$data['versions'] = kRomedaHelper::get_version_name($data['products_details']->car_model , $data['products_details']->car_version);
						return view('workshop.component.products_details')->with($data);
						}
					  else{
						 echo '<div class="notice notice-success"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
						}	
					 // echo "<pre>";
				 //print_r($data['products_details']);exit;
				  }
				 else{
				   echo '<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please try again !!! </div>';exit;  
				  } 
				// $products_details = \App\Products::get_products($request->products_id);
							   }
			 else{
			     echo '<div class="notice notice-success"><strong> Success </strong>Record Save successfully !!! </div>';exit;
			   }  
		   }
		 /*End*/		
	     if($action == "get_kpart_products_details") {
	       //  return $request;
	       if(!empty($request->products_id)){
	           $kpart_details = \App\ItemRepairsParts::get_item_repairs_details($request->products_id);
	           //echo "<pre>";
	           //print_r($kpart_details);exit;
	           if($kpart_details != NULL) {
	                $p_id = encrypt($kpart_details->id);
                    $kpart_details->image = sHelper::get_item_repair_image($kpart_details->id);
	               ?>
	               <table class="table">
                           <tr>
                             <th>Product Brand</th>
                             <td><?php echo $kpart_details->Listino; ?></td>
                           </tr>
                           <tr>
                             <th>Product Item</th>
                             <td><?php echo $kpart_details->CodiceArticolo ; ?></td>
                           </tr>
                           <tr>
                             <th>Description</th>
                             <td><?php echo !empty($kpart_details->Descrizione) ?  $kpart_details->Descrizione : "N/A"; ; ?></td>
                           </tr>
                           <tr>
                             <th>Price</th>
                             <td><?php if(!empty($kpart_details->Prezzo)) echo $kpart_details->Prezzo; else echo "N/A" ?></td>
                           </tr>
                           <tr>
                             <th>Product Image</th>
                             <td>
							  <?php if(!empty($kpart_details->image)){
							  ?>
								       <img src="<?php echo $kpart_details->image; ?>" class="img img-thumbnail" style="height:50px;"  />
						       <?php
							 } else  ?>
                             </td>
                           </tr>
                        </table>
	               <?php
	               exit;
	           } else{
				    echo '<div class="notice notice-success"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
			   }
	       }
		 }
		 
		/*Get Products Info script Start*/ 
		if($action == "get_products_details"){
			if(!empty($request->products_id)){
				$product_details = NULL;
				$product_details = \App\ProductsNew::get_products_details($request->products_id);
				$product_details = kromedaDataHelper::arrange_spare_product($product_details);
				if($product_details != NULL){
				  $maker_name = kRomedaHelper::get_maker_name($product_details->car_makers);
				  $model_name = kRomedaHelper::get_model_name($product_details->car_makers ,   $product_details->car_model);
				  $versions = kRomedaHelper::get_version_name($product_details->car_model , $product_details->car_version);
					?>
					<table class="table">
						<tr>
							<th>Brand Name</th>
							<td><?php echo $product_details->listino." (".$product_details->kromeda_products_id." )"; ?></td>
						</tr>
						<tr>
							<th>Makers / Model / Version</th>
							<td><?php echo (!empty($maker_name->Marca) ? $maker_name->Marca :  "N/A")."/".(!empty($model_name->Modello) ? $model_name->Modello : "N/A") ."/".$versions->Versione; ?></td>
						</tr>
						<tr>
							<th>Group Name</th>
							<td><?php echo !empty($product_details->group_name) ?  $product_details->group_name : "N/A"; ; ?></td>
						</tr>
						<tr>
							<th>Kromeda Description</th>
							<td><?php if(!empty($product_details->kromeda_description)) echo $product_details->kromeda_description; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>Products Description</th>
							<td>
							<?php if(!empty($product_details->products_description)) echo $product_details->products_description; else echo "N/A" ?>
							</td>
						</tr>
						<tr>
							<th>Front Rear / Left Rear</th>
							<th><?php if(!empty($product_details->front_rear)) echo $product_details->front_rear; else echo "N/A" ?> / <?php if(!empty($product_details->left_right)) echo $product_details->left_right; else echo "N/A" ?></th>
						</tr>
						<tr>
							<th>Kromeda Price</th>
							<td><?php if(!empty($product_details->price)) echo $product_details->price; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>Seller Price</th>
							<td><?php if(!empty($product_details->seller_price)) echo $product_details->seller_price; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>Products Quantity</th>
							<td><?php if(!empty($product_details->products_quantiuty)) echo $product_details->products_quantiuty; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>In Stock</th>
							<td><?php if(!empty($product_details->products_quantiuty)) echo $product_details->products_quantiuty; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>Tax Value</th>
							<td><?php if(!empty($product_details->tax_value)) echo $product_details->tax_value; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>Products Status </th>
							<td>
							<?php if(!empty($product_details->products_status)){
									if($product_details->products_status == "P"){
										echo "Save in draft"; 
										} 
									elseif($product_details->products_status == "A"){
										echo "Publish"; 
										} 
									}
								else echo "N/A" ?>
							</td>
						</tr>
						<tr>
							<th>Unit</th>
							<td><?php if(!empty($product_details->unit)) echo $product_details->unit; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>Assemble Status</th>
							<td>
							<?php if(!empty($product_details->assemble_status)){
									if($product_details->assemble_status == "Y"){
										echo "Yes"; 
										} 
									elseif($product_details->assemble_status == "N"){
										echo "Not"; 
										} 
									}
								else echo "N/A" ?>
							</td>
						</tr>
						
					</table>
					<?php
					exit;
				}
				else{
					echo '<div class="notice notice-success"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
				}	
			}
			else{
				echo '<div class="notice notice-success"><strong> Success </strong>Record Save successfully !!! </div>';exit;
			}  
		}
		/*End*/
		
		if($action == "get_product_n3_category") {
			if(!empty($request->products_id)){
				$product_details = \App\ProductsNew::find($request->products_id);
				if(!empty($product_details->products_name)) {
					$get_compatible_product = \App\ProductsCarCompatible::get_car_compatible_product($product_details);
					$get_product_item = \App\ProductsNew::get_product_item($product_details->products_name);
					if($get_product_item->count() > 0) {
						$product = $get_product_item->unique('n3_item_number');
					}
					return view('products.component.all_n3_category')->with(['product'=>$product, 'get_compatible_product' => $get_compatible_product ]);
				} else {
					echo '<div class="notice notice-success"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit;
				}
			}
		}

		if($action == "get_custom_products_details"){
			if(!empty($request->products_id)){
				$products_details = \App\ProductsNew::get_custom_products_details($request->products_id);
				if($products_details != NULL){
					$products_details->item = $products_details->front_rear = $products_details->left_right = $products_details->categories =
					$products_details->maker =  $products_details->model = $products_details->version  =  NULL;
					$p_detail = sHelper::get_products_details($products_details);
					if($p_detail != NULL){
						$products_details->our_products_description = $p_detail->our_products_description;
						$products_details->bar_code = $p_detail->bar_code;
						$products_details->for_pair = $p_detail->for_pair;
						$products_details->meta_key_title = $p_detail->meta_key_title;
						$products_details->meta_key_words = $p_detail->meta_key_words;
						$products_details->seller_price = $p_detail->seller_price;
						$products_details->products_quantiuty = $p_detail->products_quantiuty;
						$products_details->minimum_quantity = $p_detail->minimum_quantity;
						$products_details->tax = $p_detail->tax;
						$products_details->tax_value = $p_detail->tax_value;
						$products_details->substract_stock = $p_detail->substract_stock;
						$products_details->unit = $p_detail->unit;
						$products_details->assemble_time = $p_detail->assemble_time;
						$products_details->assemble_status = $p_detail->assemble_status;
						$products_details->products_status = $p_detail->products_status;
					}
					$n3_category = \App\ProductsGroupsItem::find($products_details->products_groups_items_id);
					if($n3_category != NULL){
						$products_details->item = $n3_category->item;
						$products_details->front_rear = $n3_category->front_rear;
						$products_details->left_right = $n3_category->left_right;
					}
					$product_categories = \serviceHelper::get_product_category($products_details->products_groups_id);
					if(count($product_categories) > 0){
						$products_details->categories = $product_categories;
						$n1_category = \App\Products_group::find($products_details->products_groups_id); 
						if($n1_category != NULL){
							$model_name = NULL;
							if(!empty($n1_category->car_model)) {
								$model_name = \App\Models::get_model($n1_category->car_model);
							}
							if($model_name != NULL){
								$products_details->model = $model_name->Modello." / ".$model_name->ModelloAnno;
								$products_details->maker = $model_name->makers_name;
							}
							$versions = \App\Version::get_version($n1_category->car_version);
							if($versions != NULL){
								$products_details->version = $versions->Versione." >> ".$versions->Body;
							}
						}
					}
					//$products_compatible = \App\ProductsCarCompatible::get_custom_compatible($products_details->id);
					/* if($products_compatible->maker == 1) {
						$maker_name = "All Makers";
					} else {
						$maker_name = \App\Maker::get_makers($products_compatible->maker);
					}
					if($products_compatible->model == 1) {
						$model_name = "All Models";
					} else {
						$model_name = \App\Models::get_model($products_compatible->model);
					}
					if($products_compatible->version == 1) {
						$versions = "All Versions";
					} else {
						$versions = \App\Version::get_version($products_compatible->version);
					} */
				    return view('products.component.product_info')->with(['products_details'=>$products_details]);
				}
				else{
					echo '<div class="notice notice-success"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
				}	
			}
			else{
				echo '<div class="notice notice-success"><strong> Success </strong>Record Save successfully !!! </div>';exit;
			}  
		}
		
		
		
	 }
	 
}
