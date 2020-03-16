<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Session;
use DB;
use App\Users_category;

class Coupon extends Controller{
	
	public $on_discount_arr = [1=>'Discount on Total Price' , 2=>'Discount on shipments ' , 3=>'Discount on the Single Product' , 4=>'Discount on the single Service' , 5=>'Discount on the Service Category' , 6=>'Discount on a Specific Brand'];
	public $coupon_type_arr = [1=>"Coupon" , 2=>"Coupon Group"];
	
	
	
	
	 public function get_action ($page , $p1 = NULL){
		$data['title'] = "Officine Top  - ".$page;
        $data['page'] = $page;
		$data['on_discount_arr'] = $this->on_discount_arr;
	    $data['coupon_type_arr'] = $this->coupon_type_arr;
        if (Auth::check() && Session::has('users_roll_type')) {
			$data['users_profile'] = \App\User::find(Auth::user()->id);
		     $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
			 $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
			 $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
		}else{
		  return redirect('logout')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
        $data['current_users_roll_type'] = Session::get('users_roll_type');
		/*Add n ew coupon */
		if($page == "add_new_coupon"){
			$data['seller_lists'] = DB::table('users')->where([['roll_id' , '=' , 1],['users_status' , '=' ,'A']])->get();
			$data['workshop_lists'] = DB::table('users')->where([['users_status' , '=' ,'A'] , ['deleted_at', '=' , NULL]])
			->whereIn('roll_id',[2,4])							
			->get();
		  }
		/*End*/
		/*Add n ew coupon */
		if($page == "edit_coupon"){
			if(empty($p1)) return redirect()->back();
			$data['seller_lists'] = DB::table('users')->where([['roll_id' , '=' , 1],['users_status' , '=' ,'A']])->get();
			// $data['workshop_lists'] = DB::table('users')->where([['roll_id' , '=' ,2],['users_status' , '=' ,'A']])->get();
			$data['workshop_lists'] = DB::table('users')->where([['users_status' , '=' ,'A'] , ['deleted_at', '=' , NULL]])
			->whereIn('roll_id',[2,4])							
			->get();
			$data['coupon_details'] = \App\Coupon::find(decrypt($p1));
			if(!empty($data['coupon_details'])) {
				$coupon_detail = \App\CouponDetails::where([['coupons_id', '=', decrypt($p1)]])->first();
				if(!empty($coupon_detail)) {
					$data['coupon_details']->details = $coupon_detail;
				}
			}
			 
		  }
		/*End*/
        /* Coupon list */
		if($page == "coupon_list"){
			//$data['on_discount_arr'] = $this->on_discount_arr;
		    $data['coupons'] = \App\Coupon::get_all_coupon();
		  }
		/*End*/
		if($page == "delete_coupon"){
            if(!empty($p1)){
               $coupon_result = \App\Coupon::find($p1);
               $coupon_result->deleted_at = date('Y-m-d H:i:s');
               if( $coupon_result->save() ){
                return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Coupon delete successfully !!! </div>']);
                 }
               else {
                 return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> danger </strong> something went wrong please try again !!! </div>']);	
                }
            }
       }
	   /*load view page script start*/
	   if(!view()->exists('coupon.'.$page))
		  return view("404")->with($data);
	   else  
		  return view("coupon.".$page)->with($data);
	   
    }
	
    public function post_action (Request $request , $action){
		if($action == "add_coupon"){
		    $validator = \Validator::make($request->all(), [
                'coupon_type' => 'required', 'coupon_title' => 'required',
                'coupon_quantity'=>'required' , 'per_user_allot'=>'required' , 'launching_date'=>'required' , 'closed_date'=>'required', 'avail_date'=>'required' , 'avail_close_date'=>'required'
            ]);
            if($validator->fails()){
              return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
            } 
			$coupon_image = NULL;
		 	//$coupon_image = $this->upload_coupon_image($request); 
			/*For discount  = 3 validation*/
			 if($request->select_dscount_on_products_service == 3){
				if(!empty($request->product_type)){
				   if(empty($request->product_item_id)){
					   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Info </strong> Please select Product type !!! </div>')); 
				   }	
				}
				else{
					return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Info </strong> Please select Product type !!! </div>')); 
				}
			}
			/*End*/
				$result = \App\Coupon::add_coupon($request , $coupon_image);
				if($result){
					if($result->discount_condition != 1){
						$insert_arr = [];
						$insert_arr['coupons_id'] = $result->id;
						/*Save sipping amount*/
						if($result->discount_condition == 2){
							$insert_arr['shipping_amount'] = $request->on_total__amount;
						  }
						/*End*/  
						else if($result->discount_condition == 3){
						   $insert_arr['product_type'] = $request->product_type; 
						   $insert_arr['product_product_id'] = $request->product_item_id; 	
						}  
						else if($result->discount_condition == 4){
							$insert_arr['services_id'] = $request->services;
						}
						else if($result->discount_condition == 5){
							$insert_arr['services_id']= $request->services;
							$insert_arr['service_category_id'] = $request->service_sub_category;
						}
						else if($result->discount_condition == 6){
							$insert_arr['product_type'] = $request->product_type;
							$insert_arr['brand'] = $request->brand;
						}
						$where_clause = $insert_arr;
						/*Remove Coupon detail */
						  if(!empty($request->edit_coupon_id)){
							 $coupon_details = \App\CouponDetails::where([['coupons_id' , '=' , $request->edit_coupon_id]])->update(['deleted_at'=>now()]);
							 $insert_arr['deleted_at'] = NULL;  
							}
						/*End*/
						//echo "<pre>";
						//print_r($insert_arr);
						//print_r($where_clause);
						//exit;
						$response = \App\CouponDetails::updateOrCreate($where_clause , $insert_arr);
					}
				   return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Successfully </strong> Coupon Save Successful !!! </div>')); 
				}
				else{
                    return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>')); 
				}
		}
    }
    
	
	public function ajax_get_action(Request $request , $action){
	  $data['on_discount_arr'] = $this->on_discount_arr;
	  $data['coupon_type_arr'] = $this->coupon_type_arr;
	  if($action == "get_content_for_edit"){
		   if(!empty($request->edit_coupon_id)){
			  $coupon_detail = \App\Coupon::find($request->edit_coupon_id);
			  if($coupon_detail != NULL){
				  if($coupon_detail->discount_condition != 1){
				     $coupon_info = \App\CouponDetails::where([['coupons_id' , '=' , $coupon_detail->id] , ['deleted_at' , '=' , NULL]])->first(); 
					 if($coupon_info != NULL){
					     $coupon_details_response =  \serviceHelper::coupon_response();
						 
					   }
					 else{
					    return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>')); 
					   }  
				  }
				}
			  else{
				 return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>'));  
				}	
			 }
		   else{
			  return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>'));			 }
		   
		}
	  if($action == "coupon_info"){
		 $data['coupon_details'] = NULL;
		  if(!empty($request->coupon_id)){
			  $data['coupon_detail'] = \App\Coupon::find($request->coupon_id);
			  if($data['coupon_detail'] != NULL){
				  if($data['coupon_detail']->discount_condition != 1){
					 $data['coupon_details'] = \App\CouponDetails::where([['coupons_id' , '=' , $request->coupon_id] , ['deleted_at' , '=' , NULL]])->first();
					   //echo "<pre>";
					   //print_r($data['coupon_details']);exit; 
					}
				  return view('coupon.component.coupon_info')->with($data);
				}
			  else{
				 return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>')); 
				}	
			}
		}	
	  /*Remove coupon script start*/
	   if($action == "remove_coupon"){
		  if(!empty($request->coupon_id)){
			  $response = \App\Coupon::find($request->coupon_id);
			  if($response != NULL){
				 $response->deleted_at = now();
				 if($response->save()){
				     echo 200;exit;
				   }
				}
			  else{ echo 100; exit; }	
			}
		 }
	  /*End*/	
	  /*Get Product brands*/
	  if($action == "get_product_brand"){
		if(!empty($request->brand_type)){
			$get_brands = \App\BrandLogo::get_brand($request->brand_type);
		    if($get_brands->count() > 0) return json_encode(["status"=>200 , "response"=>$get_brands]); 
			else return json_encode(["status"=>100]); 	
		}  
	  } 
	  /*End*/	
	  /*Get service category script start*/
	  if($action == "get_service_category"){
		  if(!empty($request->service_id)){
			  $category_details = DB::table('main_category')->where([['id' ,'=' , $request->service_id] , ['deleted_at' , '=' , NULL]])->first();
			  if($category_details != NULL){
			      if($category_details->id == 2 || $category_details->id == 1){
					  $categories = \App\Category::where([['category_type' , '=' , $category_details->id], ['status', '=', 0]])->get();
					   if($categories->count() > 0)
						 return json_encode(['status'=>200 , 'response'=>$categories]); 
					   else return json_encode(['status'=>100]);   
					} 
				  else if($category_details->id == 23){
					$html_content = '';  
					  $tyre_services = \App\Category::where([['category_type' , '=' , $category_details->id] , ['deleted_at' , '=' , NULL]])->get();
					   if($tyre_services->count() > 0){
							$html_content .= '<option value="0">--Select--Tyre--Group--</option>'; 
							foreach($tyre_services as $tyre_group){
							     $html_content .= '<option value="'.$tyre_group->id.'">'.$tyre_group->category_name.'</option>';	 
							  }
						  } 
					   else{
						    $html_content .= '<option> No Category Available !!!</option>';
						  }	  
					return json_encode(['status'=>300 , 'response'=>$html_content]);
				  }
				  else if($category_details->id == 13){
					  $wracker_service = \App\WrackerServices::get_wracker_services();
					  $html_content = ''; 
					  if($wracker_service->count() > 0){
						$html_content .= '<option value="0">--Select--Wracker--Services--</option>'; 
						foreach($wracker_service as $w_service){
							$html_content .= '<option value="'.$w_service->id.'">'.$w_service->services_name.'</option>';	 
						  }
					  }
					  else{
						$html_content .= '<option> No Category Available !!!</option>';
					  }
					return json_encode(['status'=>300 , 'response'=>$html_content]);  
				  }	
				  else if($category_details->id == 12){
					$html_content = ''; 
					$car_maintinance_services = DB::table('items_repairs_servicestimes')->groupBy('item_id')->get(); 
					  if($car_maintinance_services->count() > 0){
						$html_content .= '<option>--Select--Car--Maintenance--Service--</option>';
						foreach($car_maintinance_services as $main_service){
							$html_content .= '<option value="'.$main_service->item_id.'">'.$main_service->item." ". $main_service->front_rear ." ". $main_service->left_right ." ".$main_service->action_description.'</option>';	 
						  }
					  }
					  else{
						$html_content .= '<option> No Category Available !!!</option>';
					  }
					  return json_encode(['status'=>300 , 'response'=>$html_content]);  
				  }
				}
			  else{
				  return json_encode(['status'=>100]);    
				}	
			}
		  else{
			  return json_encode(['status'=>100]);    
			}	
		}
	  /*End*/	
	  /*Get All main Services*/
		if($action == "get_all_services"){
			if($request->value == 5) {
				$category =  DB::table('main_category')->where([['deleted_at' , '=' , NULL], ['type', '=', NULL], ['id', '!=', 12], ['id', '!=', 3], ['id', '!=', 25]])->get();
			} else {
				$category =  DB::table('main_category')->where([['deleted_at' , '=' , NULL]])->get();
			}
			if($category->count() > 0){
				return json_encode(['status'=>200 , 'response'=>$category]); 
			} else {
				return json_encode(['status'=>100]);  
			}	 				  
		}
	  /*End*/	
	}
   
	public function calculate_coupon_price($coupon , $price = NULL){
		if($coupon->offer_type == 1){
		    return  $coupon->amount * (int) $price / 100;
		}
		if($coupon->offer_type == 2) {
			return  $coupon->amount;
		}
	}
	
	public function return_use_number_of_coupon($coupon_id){
		$coupon_apply_service_booking = DB::table('service_bookings')->where([['coupon_id' , '=' , $coupon_id] , ['status' , '!=' , 'P']])->count();
		$coupon_apply_parts = DB::table('products_order_descriptions')->where([['coupons_id' , '=' , $coupon_id] , ['status' , '!=' , 'P']])
																	  ->count();
		return 	$coupon_apply_service_booking + $coupon_apply_parts;														  
	}

	public function return_user_use_number_of_coupon($coupon_id , $user_id){
		$coupon_apply_service_booking = DB::table('service_bookings')->where([['coupon_id' , '=' , $coupon_id] , ['users_id' , '=' ,$user_id] , ['status' , '!=' , 'P']])->count();
		$coupon_apply_parts = DB::table('products_order_descriptions')->where([['coupons_id' , '=' , $coupon_id] , ['status' , '!=' , 'P'] , ['users_id' , '=' ,$user_id]])
																	  ->count();
		return 	$coupon_apply_service_booking + $coupon_apply_parts;														  
	}

	public function check_coupon_validity($coupon_id , $selected_date , $price){
		$coupon_detail = DB::table('coupons')->where([['id' , '=' , $coupon_id] , ['deleted_at' , '=' , NULL]])
		                                    ->where([['avail_date' , '<=' , $selected_date] , ['avail_close_date','>=',$selected_date]])
											->first();
		if($coupon_detail != NULL){
			/*get coupon booking*/
			if($this->return_use_number_of_coupon($coupon_id) < $coupon_detail->coupon_quantity){
				if($this->return_user_use_number_of_coupon($coupon_id , Auth::user()->id) <  $coupon_detail->per_user_allot){
					$coupon_price = $this->calculate_coupon_price($coupon_detail , $price);
					if($coupon_price < $price){
						return json_encode(['status'=>200 , 'price'=>$coupon_price]);
					}else{

					}	
				}
				else{
					return json_encode(['status'=>100 , 'msg'=>'Invalid this coupon !!!']);	
				}
			}
			else{
				return json_encode(['status'=>100 , 'msg'=>' Invalid this coupon !!!']);
			}
			/*End*/
		}	
		else{
			return json_encode(['status'=>100 , 'msg'=>'Invalid this coupon !!!']);
		}								

	}

	public function find_product_coupon($item_number , $brand, $price ,  $product_type = NULL){
		$brand = NULL; $coupon_list = [];
		$coupons = DB::table('coupons as a')
						->leftjoin('coupon_details as b' , 'b.coupons_id' , '=' , 'a.id')
						->whereNotIn('a.discount_condition', [4 , 5])	
						->where([['a.deleted_at' , '=' , NULL]])	   
						->get();
		if($coupons->count() > 0){
			foreach($coupons as $coupon){ 
				$coupon->total_amount = $this->calculate_coupon_price($coupon , $price);
				if($coupon->discount_condition == 1 || $coupon->discount_condition == 2){
					$coupon_list[] = $coupon; 
				}
				elseif($coupon->discount_condition == 3){
					if($coupon->product_type == $product_type){
						if($coupon->product_product_id == $item_number){
						$coupon_list[] = $coupon; 
						}
					}	 
				}
				elseif($coupon->discount_condition == 6){
					if($coupon->product_type == $product_type){
						if($coupon->brand == $brand){
							$coupon_list[] = $coupon; 
						}
					}	 
					}
			}
		}	
		$coupon_list_collection = collect($coupon_list);
		$coupon_lists = $coupon_list_collection->sortBy('amounts')->values()->all();
		if(count($coupon_lists) > 0){
			 return ['status'=>1 , 'response'=>$coupon_lists[0]];
		}
		else{
			return ['status'=>0];
		}
	}

	public function find_workshop_coupon($workshop_id , $main_category_id , $service_id = NULL){
		$new_coupons_list = [];
		$coupons = DB::table('coupons as a')
						->leftjoin('coupon_details as b' , 'b.coupons_id' , '=' , 'a.id')
						->whereIn('a.discount_condition', [4 , 5])	
						->where([['a.deleted_at' , '=' , NULL] , ['services_id' , '=' ,$main_category_id]])->orWhere([['service_category_id' , '=' , $service_id]])
						->whereJsonContains('workshop_list', (string) $workshop_id)	   
						->get();
		$collect_list = collect($coupons);
		$coupon_list_values = $collect_list->sortBy('amounts')->values()->all();
		if(!empty($coupon_list_values)){
			$coupon_lists_value = $coupon_list_values[0];
		}else{
			$coupon_lists_value = null;
		}				
		return $coupon_lists_value; 

	}
   

    
}
