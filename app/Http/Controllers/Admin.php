<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use sHelper;
use apiHelper;
use App\Products_order;
use kRomedaHelper;
use App\VersionServicesSchedulesInterval;
use kromedaSMRhelper;
use kromedaDataHelper;
use App\VersionServicesOperation;
use App\ItemsRepairsServicestime;
use App\ItemRepairsPartNumber;
use App\ManageAdverting;
use App\Address;
use DB;
use Session;
use \App\Users_category;
use App\Library\orderHelper;

class Admin extends Controller{
	public $directory  = 'storage/xml/';

	public function get_full_url(){
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
          $complete_url = "https"; 
			else
				$complete_url = "http"; 
			
			// Here append the common URL characters. 
			$complete_url .= "://"; 
			
			// Append the host(domain name, ip) to the URL. 
			$complete_url .= $_SERVER['HTTP_HOST']; 
			
			// Append the requested resource location to the URL 
			$complete_url .= $_SERVER['REQUEST_URI'];   
			// Print the link 
			return  $complete_url; 
	}
   
    public function index($page = "home",  $p1 = NULL,  $p2 = NULL){
		$data['cars__makers_category'] = \App\Maker::all();
		$data['title'] = "Officine Top  - ".$page;
        $data['page'] = $page;
        if (Auth::check()) {
          $data['users_profile'] = \App\User::find(Auth::user()->id);
          $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
          $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
           $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
        }else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
        
        if($page == "dashboard"){
		    $data['service_list'] = \App\ServiceBooking::get_all_services();
			$data['order_list'] = Products_order::get_all_orders();
			/*For Workshop*/
			$data['booked_services'] =  \App\ServiceBooking::booked_car_wash_service(1 , Auth::user()->id);
		    $data['booked_car_revision'] =  \App\ServiceBooking::get_all_revision_bookings(3, Auth::user()->id);
			$data['order_list'] = \App\Products_order::users_orders();
			$data['booked_sos'] =  \App\ServiceBooking::get_all_sos_bookings(6, Auth::user()->id); 
			$data['tickets'] = \App\SupportTicket::support_tickets();
			$data['request_quotes'] =  \App\ServiceBooking::get_all_service_quotes_bookings(Auth::user()->id); 
			$data['tyre_booking'] =  \App\ServiceBooking::get_all_tyre_bookings(Auth::user()->id);
			$data['assemble_booking'] =  \App\ServiceBooking::get_all_assemble_bookings(Auth::user()->id);
			/*End*/
		  }
		   if($page == "add_master_bonus"){
			 $data['selected_bonus_amount'] = \App\Master_bonus_amount::get_bouns_amount();
			}
		if($page == "delete_our_car_maintainance_product") {
			if(!empty($p1)) {
				$result = \App\OurCarMaintinanceProductItem::find($p1);
				if($result != NULL){
				   $result->deleted_at = date('Y-m-d H:i:s');
				   	if($result->save()){
						return redirect()->back()->with(["msg"=>'<div class="notice notice-success"><strong>Success , </strong> Record Deleted successfully !!!.</div>']);
					} else {
						return redirect()->back()->with(["msg"=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>']);
					} 
				}
			}
		}
		 //Notification module
		 if($page == "notification"){
			$data['notification_list'] = \App\Notification::get_all_notification_list();
		  } 
		  //manage_advertising module
		  if($page == "manage_advertising"){
			  $data['category_lists'] = DB::table('main_category')->get();
			$data['manage_advertising_list'] = \App\ManageAdverting::get_all_nmanage_advertising_admin();
		  }
		  
		 if($page == "mot_test_services"){
		     $data['service_interval'] = VersionServicesSchedulesInterval::paginate(15);
			 $data['our_mot_services'] = \App\Our_mot_services::OrderBy('id' , 'DESC')->where('deleted_at', '=', NULL)->get();
			 $data['n3_category'] = \App\ProductsGroupsItem::all();
		   }
			/*Generate order */
			if($page == "generate_xml"){
				$product_order_obj = new Products_order;
				$payment_mode_status = $product_order_obj->payment_mode_status; 
				$generate_response =  orderHelper::generate_order_xml($p1);
				///print_r($generate_response); die;
				return response()->view('admin.generate_xml', compact('generate_response' , 'payment_mode_status'))->header('Content-Type', 'text/xml');
			}
			/*End*/
		   if($page == "edit_mot_test_services") {
			$p2 = decrypt($p1);
		/* 	echo $p2;exit; */
			$data['mot_service_details'] = \App\Our_mot_services::get_mot_details($p2);
			$data['mot_n3_category'] = \App\MotN3Category::get_mot_n3_category($p2);
			/*Set makers*/
			  $data['mot_service_details']->maker_value  = $data['mot_service_details']->version_value  = $data['mot_service_details']->model_value = NULL; 
			/*End*/
			if($data['mot_service_details'] != NULL){
				/*set makers value*/
				if(!empty($data['mot_service_details']->car_makers)){
					if((int)$data['mot_service_details']->car_makers == 1){
						  $data['mot_service_details']->maker_value = 1;
					   }
					else{
						$data['mot_service_details']->maker_value = $data['mot_service_details']->car_makers;
					   }   
				  }
				/*End*/  
				/*Set model value*/
				 if(!empty($data['mot_service_details']->car_models)){
					if($data['mot_service_details']->car_models != "1"){
						$data['mot_service_details']->model_value = $data['mot_service_details']->car_models;
					   }
					else{
					   $data['mot_service_details']->model_value = 1;
						
					   }   
				  }
				/*End*/
				/*Set Version values*/
				if(!empty($data['mot_service_details']->car_version)){
				  if($data['mot_service_details']->car_version != "all"){
					$version_details = \App\Version::get_version($data['mot_service_details']->car_version); 
					if($version_details != NULL){
						$data['mot_service_details']->version_value = $data['mot_service_details']->car_version;
					  }
					}
					else{
						$data['mot_service_details']->version_value = "all";
					}
				  }
				/*End*/  
				 //echo "<pre>";
				 //print_r($data['mot_service_details']);exit;  
			}
		}
			if($page == "delete_mot_services") {
				if(!empty($p1)) {
					// $n3_exist_exist = \App\MotN3Category::get_n3_category($p1);
					// if(!empty($n3_exist_exist)) {
					// 	$result = \App\MotN3Category::where('our_mot_services_id' , $p1)->delete();
					// }	
					$result = \App\Our_mot_services::delete_mot_services($p1);
					if($result) {
						return redirect()->back()->with(["msg"=>'<div class="notice notice-success"><strong>Success , </strong> Record Deleted successfully !!!.</div>']);
					} else {
						return redirect()->back()->with(["msg"=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>']);
					}
				}
			}
		   
		   if($page == "car_revision"){
			$data['category_list'] = \App\Category::get_category();
		} 
		if($page == "spare_groups"){
            $data['spare_groups'] = \App\MainCategory::get_all_spares_group(1);
		} 
        if($page == "wrecker_services"){
			$data['wracker_service'] = \App\WrackerServices::get_wracker_services();
			foreach($data['wracker_service'] as $wracker) {
				$images = \App\Gallery::get_wrecker_images($wracker->id);
				if($images->count() > 0) {
					$wracker->service_image = $images[0]['image_name'];
					$wracker->service_image_url = $images[0]['image_url'];
				} else {
					$image_url = url("storage/products_image/no_image.jpg");
					$wracker->service_image = "no_image.jpg";
					$wracker->service_image_url = $image_url;
				}
			}
		}
    	if($page == "mapping_spare_group"){
			$lang =  sHelper::get_set_language(app()->getLocale());
			$data['spare_groups'] = \App\MainCategory::get_all_valid_spares_group(1);
			$selected_group_id_arr = [];
			$selected_groups = \App\Spare_category_item::get_selected_groups();
			/* echo "<pre>";
			print_r($selected_groups);exit; */
			if($selected_groups->count() > 0){
			   $selected_group_id_arr = $selected_groups->pluck('products_groups_group_id')->all(); 
			}
			$products_groups = \App\Products_group::get_all_products_group($lang);
			if($products_groups->count() > 0){
			  	$data['products_groups'] = $products_groups->whereNotIn('group_id', $selected_group_id_arr);
			}
			/* echo "<pre>";
			print_r($data['products_groups']);exit;   */
		} 

		if($page == "list_spare_items") {
		    $data['spare_groups'] = \App\MainCategory::get_all_valid_spares_group();
			$data['spare_items'] = \App\Spare_category_item::get_all_spare_items();
			/* echo '<pre>'; 
			print_r($data['spare_items']);
			die; */
		}
		if($page == "car_maintenance"){
		   $data['car_maintinance_service_list'] = ItemsRepairsServicestime::get_items_services();	
		    foreach($data['car_maintinance_service_list'] as $list) {
				$list = kromedaDataHelper::arrange_car_maintinance($list);
			}
		/* 	echo "<pre>";
			print_r($data['car_maintinance_service_list']);exit;  */
			$data['car_maintinance_time_id'] = \App\ItemsRepairsTimeId::get_items_id();
		   $data['type'] = 0;
		}  
        if($page == "sos"){
			$data['sos_category'] = \App\Category::get_sos_category();
			//echo "<pre>";
			//print_r($data['sos_category']);exit;
		}   
		if($page == "users_list"){
		  $data['type'] = $p1;
			if(!empty($p1))
			  $data['all_users'] = \App\User::where('roll_id' , '=' , $p1)->where([['deleted_at', '=' , NULL]])->get();
			else
			  $data['all_users'] = \App\User::where('roll_id' , '!=' , 4)->where([['deleted_at', '=' , NULL]])->get();
		  }  
        if($page == "mot_services_operation"){
			$interval_details = VersionServicesSchedulesInterval::find($p1);
			 if($interval_details != NULL){
				 $lang =  sHelper::get_set_language(app()->getLocale());
				/*Check Record Exist*/
				  $api_param = $interval_details->version_id."/".$interval_details->version_service_schedules_schedules_id."/".$interval_details->service_interval_id."/".$lang;
				  $execute_url = "interval_operation/".$api_param; 
				  $check_exist = \App\ExcutedQuery::get_record($execute_url);
				  $kr_part_list_response = FALSE;
				  $data['status_obj'] = $data['kPartsList'] = $data['interval_operation'] = FALSE;
				  if($check_exist == NULL){
					 $interval_operation_response = kromedaSMRhelper::schedule_interval_operation($interval_details->version_id , $interval_details->version_service_schedules_schedules_id  , $interval_details->service_interval_id , $lang);
					 $response = json_decode($interval_operation_response);
					 if($response->status == 200){
							$data['status_obj'] = TRUE;
							$opration_response = VersionServicesOperation::add_service_opration($interval_details , $response->response->dataset , $lang); 
							$kr_part_list_response = \App\KrPartList::add_kr_parts_list($interval_details , $response->response->kr_parts_list , $lang);
				    	} 
					}
				/*End*/
				 $data['kPartsList'] =  \App\KrPartList::get_kPartsList($interval_details , $lang);
				 $data['interval_operation'] =  VersionServicesOperation::get_operation($interval_details->id , $lang);
			   } 
		   }
        
        
        if($page == "add_category"){
		    $data['parent_category'] = sHelper::get_parent_category();
		  }
		if($page == "car_wash_categories"){
		    $data['category_list'] = \App\Category::where([['category_type' , '=' ,1] , ['status' , '=' , 0]])->orderBy('created_at' , 'DESC')->paginate(10); 
		}  
		
		if($page == "category_list"){
		    $data['parent_category'] = \DB::table('main_category')->get();
			 $data['category_list'] = \App\Category::where([['category_type' , '=' ,1] , ['status' , '=' , 0]])->orderBy('created_at' , 'DESC')->paginate(10);
		  }
		  
		if($page == "edit_category"){
		    $data['parent_category'] = sHelper::get_parent_category();
			$data['category_details'] = \App\Category::find($p1);
			$data['images_arr'] = \App\Gallery::get_category_image($p1);
			//echo "<pre>";
			//print_r($data['category_details']);exit;
		  }

		 if($page == "workshops"){
		    $data['workshops'] = \App\Workshop::get_all_workshop();
		  }
		if($page == "order_details") {
			if(!empty($p1)) {
				$data['order_deatil'] = \App\Products_order::get_order_detail($p1);
			     //echo "<pre>";
			    if($data['order_deatil'] != NULL){
					$data['seller_info'] = DB::table('business_details')->where([['users_id' ,'=' ,$data['order_deatil']->seller_id]])->first();
					$data['user_detail'] = DB::table('user_details')->where([['id' , '=' , $data['order_deatil']->user_details_id]])->first();
					$data['payments_address_details'] = \App\Address::get_address_details($data['order_deatil']->shipping_address_id);
			         
			        //echo "<pre>";
			        //print_r($data['shipping_address_details']);exit;
			        $data['company_name'] = sHelper::get_seller_owner($data['order_deatil']->seller_id);
					$data['order'] = \App\Products_order_description::get_product_description($p1);
					$data['service_details'] = \App\ServiceBooking::find($data['order']->service_booking_id);
					$data['service_name'] = orderHelper::find_service_name($data['service_details']);
					$data['shipping_address_details'] = \App\Address::get_address_details($data['service_details']->workshop_user_id);   
					$data['sub_total'] = ($data['service_details']->price + (!empty($data['order']) ? $data['order']->price : 0));
					$data['discount'] = ($data['service_details']->discount + (!empty($data['order']) ? $data['order']->discount : 0));
					$data['service_vat'] = ($data['service_details']->service_vat + (!empty($data['order']) ? $data['order']->vat : 0));
					//$data['service_vat'] = $data['order_deatil']->total_vat;
					//$data['pfu_price'] = sHelper::calculate_pfu_price($data['order_deatil']->id);
					$data['pfu_price'] =  (!empty($data['order']) ? $data['order']->pfu_tax : 0);

					$data['total_price'] = (($data['sub_total'] + $data['service_vat']) - $data['discount']) + $data['pfu_price'];
					//$data['pfu_price'] = sHelper::calculate_pfu_price($data['order']->products_orders_id);
			    }
			 }
		}
	if($page == "service_order_details") {
			$data['service_details'] = \App\ServiceBooking::find($p1);
			if($data['service_details'] != NULL) {
				$data['service_name'] = orderHelper::find_service_name($data['service_details']);
				$data['order_deatil'] = \App\Products_order::get_order_detail($data['service_details']->product_order_id);
				if($data['order_deatil'] != NULL){
					$data['seller_info'] = DB::table('business_details')->where([['users_id' ,'=' ,$data['order_deatil']->seller_id]])->first();
				//	print_r($data['seller_info'] ); die;
					$data['user_detail'] = DB::table('user_details')->where([['id' , '=' , $data['order_deatil']->user_details_id]])->first();
					$data['payments_address_details'] = \App\Address::get_address_details($data['order_deatil']->shipping_address_id);
					$data['shipping_address_details'] = \App\Address::get_address_shipping_details($data['service_details']->workshop_user_id);
					$data['company_name'] = sHelper::get_seller_owner($data['order_deatil']->seller_id);
					$data['order'] = \App\Products_order_description::get_service_booking_description($p1);
					$data['sub_total'] = ($data['service_details']->price + (!empty($data['order']) ? $data['order']->price : 0));
					$data['discount'] = ($data['service_details']->discount + (!empty($data['order']) ? $data['order']->discount : 0));
					$data['service_vat'] = ($data['service_details']->service_vat + (!empty($data['order']) ? $data['order']->vat : 0));
					//$data['service_vat'] = $data['order_deatil']->total_vat;
					//$data['pfu_price'] = sHelper::calculate_pfu_price($data['order_deatil']->id);
					$data['pfu_price'] =  (!empty($data['order']) ? $data['order']->pfu_tax : 0);

					$data['total_price'] = (($data['sub_total'] + $data['service_vat']) - $data['discount']) + $data['pfu_price'];
					//$data['total_price'] =$data['order_deatil']->total_price + $data['pfu_price'];
				}
			}
		}
		  /*if($page == "order_list"){
			 $data['order_list'] = Products_order::get_all_orders();
		}*/
     
          if($page == "company_profiles"){
			if(empty($p1))return redirect()->back();
			$data['business_details'] = \App\BusinessDetails::get_business_details(base64_decode($p1));
			$data['p2'] = $p1;
			$data['edit_status'] = $p2;
			$data['workshop_id'] = base64_decode($p1);
			$data['bank_details'] = \App\Bankdetails::get_bank_details(base64_decode($p1));
			
			$data['address_list'] = \App\Address::get_address(base64_decode($p1));
			$data['fill_form'] = FALSE;
		    //echo "<pre>";
			//print_r($data['bank_details']);exit;
		  }
		  
	    if($page == "customers_profile") {
			if(empty($p1))return redirect()->back();
			if(!empty(base64_decode($p1))) {
				$data['customer_detail'] = \App\User::get_customers_record((base64_decode($p1)));
				$data['garage_detail'] = \App\Model\UserDetails::get_customers_car_record((base64_decode($p1)));
				$data['customer_bonus_detail'] = \App\UserwalletHistory::get_customers_wallet_histories((base64_decode($p1)));
				$data['p2'] = $p1;
				$data['edit_status'] = $p2;
				$data['customer_id'] = base64_decode($p1);
			} else {
                return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);   
            }
		}
 
		 if($page == "dashboard"){
		    $data['all_users'] = \App\User::where([['roll_id' , '!=' , 4] , ['roll_id' , '!=' , 5]])->count();
			$data['all_vendors'] = \App\User::where('roll_id' , '=' , 2)->count();
			$data['all_sellers'] = \App\User::where('roll_id' , '=' , 1)->count();
			$data['all_customers'] = \App\User::where('roll_id' , '=' , 3)->count();
		  } 
          if($page == "feedback"){
			  $data['all_feedback'] = \App\Feedback::get_all_feedback(); 
		  } 
		  /* if($page == "service_booking_list"){
			$data['service_list'] = \App\ServiceBooking::get_all_services();
			if($p1 == 23){
			   $page = "admin_service_booking_list";  
				$data['service_booking'] = collect(orderHelper::service_booking_list(4));
			 }
			$data['revision_service_list'] = \App\ServiceBooking::get_all_revision_service_list();
		 } */
		if($page == "service_booking_list"){
			$data['service_list'] = \App\ServiceBooking::get_all_services();
			$data['booking_type'] = NULL;
			if($p1 == 23){
				$page = "admin_service_booking_list";  
				$data['service_booking'] = collect(orderHelper::service_booking_list(4));
			}
			if($p1 == 13) {
				$data['booking_type'] = 13;
				$page = "admin_service_booking_list";  
				$data['service_booking'] = collect(orderHelper::service_booking_list(6));
			}
			if($p1 == 12) {
				$page = "admin_service_booking_list";  
				$data['service_booking'] = collect(orderHelper::service_booking_list(5));
			}
			if($p1 == 20) {
				$page = "admin_service_booking_list";  
				$data['service_booking'] = collect(orderHelper::service_booking_list(2));
			}
			if($p1 == 1) {
				$page = "admin_service_booking_list";  
				$data['service_booking'] = collect(orderHelper::service_booking_list(1));
			}
			$data['revision_service_list'] = \App\ServiceBooking::get_all_revision_service_list();
			$data['tyre_service_list'] = orderHelper::service_booking_list(4);
			$data['wracker_service_list'] = orderHelper::service_booking_list(6);
			$data['maintainance_service_list'] = orderHelper::service_booking_list(5);
			$data['assemble_service_list'] = orderHelper::service_booking_list(2);
			$data['washing'] = orderHelper::service_booking_list(1);
		}

		if($page == "car_revision_servicebooking"){
			$data['revision_service_list'] = \App\ServiceBooking::get_all_revision_service_list();
		}
		if(!view()->exists('admin.'.$page))
			return view("404")->with($data);
		else  
		return view("admin.".$page)->with($data);
     }
     
      public function car_maintinance($page , $p1 = NULL){
		if(isset($_GET['type'])){
			$data['type'] = $_GET['type'];
		}
	    $data['title'] = "Officine Top  - ".$page;
        $data['page'] = $page;
        
         if (Auth::check()) {
		   $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();	
            $data['users_profile'] = \App\User::find(Auth::user()->id);
		    $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
		    $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
      }else{
		    return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	   }
		if($page == "kpart_list"){
		  if(empty($p1)){ return redirect()->back(); }
		  $item_repairs_details = ItemsRepairsServicestime::get_item_repair_details($p1);
		  $data['item_repair_id'] = $p1;
		  $data['product_item_details'] = \App\OurCarMaintinanceProductItem::get_car_compatible($p1);
		  $data['products_response'] = collect();
		  $data['flag'] = 2; 
		  if($item_repairs_details != NULL){
			  if($item_repairs_details->type == 1){
				$data['flag'] = 1;    
				$item_repairs_id_details = \App\ItemsRepairsTimeId::find($item_repairs_details->items_repairs_time_ids_id);
				/*Get Details*/
			    $data['version'] = \App\Version::get_version($item_repairs_id_details->version_id);
				$data['model'] = NULL;
				if($data['version'] != NULL){
					$data['model'] = \App\Models::get_model($data['version']->model);
				}
				if($item_repairs_id_details != NULL){
					/*Get Database record script start*/
					// $data['all_product_response'] = \App\ItemRepairsParts::get_parts($item_repairs_details->id);
						$data['all_product_response'] = \App\ProductsItemNumber::get_parts($item_repairs_details);
						if($data['all_product_response']->count() <= 0){
						$get_item_number = kromedaHelper::get_part_number($item_repairs_id_details->version_id , $item_repairs_details->item_id);
							if(is_array($get_item_number) && count($get_item_number) > 0){
								$part_number = \App\ProductsItemNumber::save_item_number($get_item_number , $item_repairs_details);  
								$part_number_response = \App\ProductsItemNumber::where([['version_id', '=', $item_repairs_details->version_id], ['products_groups_items_item_id', '=', $item_repairs_details->item_id]])->get();
									if($part_number_response->count() > 0){
										foreach($part_number_response as $part_number){
											/*OE_Get_cross */
											$get_products = kromedaHelper::oe_products_item((string) $part_number->CodiceListino , $part_number->CodiceOE);
											if(is_array($get_products) && count($get_products) > 0){
												$add_products_response = \App\ProductsNew::add_product_by_car_maintainance($part_number, $get_products);
											}
											$get_other_products = kromedaHelper::oe_getOtherproducts((string) $part_number->CodiceListino , $part_number->CodiceOE);
											if(is_array($get_other_products) && count($get_other_products) > 0){
												$add_other_products_response = \App\ProductsNew::add_other_product_by_car_maintainance($part_number , $get_other_products);
											}
										}
										/*End*/   
									}
							}
						}
						$data['all_itm_number'] = \App\ProductsItemNumber::get_parts($item_repairs_details);
						$data['products_response'] = kromedaDataHelper::find_products_by_item_number($data['all_itm_number']);
				}
				else{
					return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>')); 
				} 
			 }   
			}
		}
	
		if(!view()->exists('admin.'.$page))
			return view("404")->with($data);
		else  
		return view("admin.".$page)->with($data); 
	}
	  
	  
     
     
      public function get_action(Request $request , $action){
          if($action == "search_group_item") {
            if(!empty($request->group_item_id) && !empty($request->language)) {
                 $lang = sHelper::get_set_language($request->language);
                $selected_groups = \App\Spare_category_item::get_selected_groups();
			    if($selected_groups->count() > 0){
			      $selected_group_id_arr = $selected_groups->pluck('products_groups_id')->all(); 
			    }
                $products_groups = \App\Products_group::get_search_spares_details($request->group_item_id, $lang , $selected_group_id_arr);
            }
            return view('admin.component.spare_group_item')->with(['products_groups'=>$products_groups]);
		}
		if($action == "get_advertising_image") {
			if(!empty($request->id)) {
				$images = \App\Advertising_image::get_Advertising_images($request->id);
                  if($images->count() > 0){
                     ?>
                     <div class="row">
                     <?php
                     foreach($images as $image){
                         ?>
                         <div class="col-sm-4 col-md-3 col-lg-3">
                                     <div class="card">
                                         <div class="card-img-actions m-1">
                                             <img class="card-img img-fluid" src="<?php echo $image->image_url; ?>" alt="" />
                                             <div class="card-img-actions-overlay card-img">
                                                 <a href='#' data-imageid="<?php echo $image->id; ?>" data-advertising_id="<?php if(!empty($image->advertising_id)) echo $image->advertising_id; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_car_revision_images">
                                                     <i class="icon-trash"></i>
                                                 </a>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                         <?php
                       }
                     ?>
                      </div>
                     <?php  
                     exit;  
                  } 	
			}
		}
			if($action == "remove_advertising_image") {
            $image_details = \App\Advertising_image::find($request->delete_id);
            if($image_details != NULL){
                    $delte_img = \App\Advertising_image::where('id' ,'=' ,$request->delete_id)->update(['deleted_at'=>date('Y-m-d h:i:s')]);
                 //   $image_arr = \App\Advertising_image::get_Advertising_images($request->advertising_id);
                   // if($image_arr->count() > 0){
                      //  $image_name = $image_arr[0]->image;
                      //  $newimage_url = url("storage/advertising/$image");
                      //  $result_image = \App\Category::find($request->category_id);
                       // $result_image->cat_images = $image_name;
                       // $result_image->cat_image_url  = $newimage_url;
                       // $result_image->save();
                   // } 
            }
		} 
		

        if($action == "search_users") {
			// return $request;exit;
			if(!empty($request->usersId)) {
    			$id = substr($request->usersId, 8);
				$users = \App\User::get_serach_user($id);
				return view('admin.component.user_data')->with(['all_users'=>$users]);
		  	  }
		   }

		   if($action == 'delete_notification') {
			if(!empty($request->id)) {
				//$delete_data = \App\Notification::delete_notification($request->id);
				$delete_data = \App\Notification::where('id' ,'=' ,$request->id)->update(['deleted_at'=>date('Y-m-d h:i:s')]); 
				if($delete_data) {
					return json_encode(array(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Notification Deleted Successfully !!!.</div>']));
				} else {
					return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
				}
			} else {
				return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
			}
		}
		   if($action == 'delete_advertising') {
			if(!empty($request->id)) {
				//$delete_data = \App\ManageAdverting::delete_advertising($request->id);
				$delete_data = \App\ManageAdverting::where('id' ,'=' ,$request->id)->update(['deleted_at'=>date('Y-m-d h:i:s')]); 
				if($delete_data) {
					return json_encode(array(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Advertising Deleted Successfully !!!.</div>']));
				} else {
					return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
				}
			} else {
				return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
			}
		}
		if($action == "change_booking_status") {
			$result = \App\ServiceBooking::where('id' ,'=' ,$request->booking_id)->update(['status'=> $request->booking_status]); 
			if($result) {
				return json_encode(array(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Status Changed Successfully !!!.</div>']));
			} else {
				return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
			}
		}
		if($action == 'delete_user_list') {
			if(!empty($request->id)) {
				$delete_data = \App\User::where('id' ,'=' ,$request->id)->update(['deleted_at'=>date('Y-m-d h:i:s')]); 
				if($delete_data) {
					return redirect()->back()->with(["msg"=>'<div class="notice notice-success"><strong>Success , </strong> user data deleted successfully. !!!.</div>']);
				} else {
					return redirect()->back()->with(["msg"=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>']);
				}
			} else {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>']);
			}
		}


		  /*Change status Script start*/	
      if($action == "change_status"){
		if(!empty($request->id)){
			$advertising = ManageAdverting::find($request->id);
			if($advertising != NULL){
				$advertising->status =$request->status;
				if($advertising->save()){
					echo 1;exit;
				  } 
			  } else {
			  echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
			  }	 
		 }
	   else{
		   echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
		 }	
	  }		
	  /*End*/
          if($action == "view_service_details") {
				// return $request;exit;
				$service_list = \App\ServiceBooking::get_service_detail($request->serviceId);
				// echo "<pre>";
				// print_r($service_list);exit;
				if($service_list != NULL) {
					$workshop_owner = sHelper::get_workshop_owner($service_list->workshop_user_id);
					?>
						<table class="table">
							<tr>
								<th>Customer Name</th>
								<td><?php echo $service_list->f_name; ?></td>
							</tr>
							<tr>
								<th>Workshop Owner</th>
								<td><?php 
										echo $workshop_owner->company_name;
									?>
								</td>
							</tr>
							<tr>
								<th>Service Name</th>
								<td><?php echo $service_list->category_name; ?></td>
							</tr>
							<tr>
								<th>Price</th>
								<td>&euro;&nbsp;<?php echo $service_list->price; ?></td>
							</tr>
							<tr>
								<th>Total Price</th>
								<td>&euro;&nbsp;<?php 
									if(!empty($service_list->after_discount_price)) {
										echo $service_list->after_discount_price;
									} else {
										echo 0;
									}
								 ?></td>
							</tr>
							<tr>
								<th>For Booking Date</th>
								<td><?php echo $service_list->booking_date; ?></td>
							</tr>
							<tr>
								<th>Booking Date</th>
								<td><?php echo $service_list->created_at; ?></td>
							</tr>
							<tr>
								<th>Booking Start Time</th>
								<td><?php echo $service_list->start_time; ?></td>
							</tr>
							<tr>
								<th>Booking End Time</th>
								<td><?php echo $service_list->end_time; ?></td>
							</tr>
							<tr>
								<th>Service Average Time</th>
								<td><?php echo $service_list->time; ?></td>
							</tr>
							
							<tr>
								<th>About Services</th>
								<td><?php echo $service_list->about_services; ?></td>
							</tr>
							<tr>
								<th>Booking Status</th>
								<td><?php 
										if($service_list->status == "P") {
										?><span class="badge badge-danger">Pending</span><?php
										} else if($service_list->status == "C") {
										?><span class="badge badge-success">Complete</span><?php
										} else if($service_list->status == "D") {
										?><span class="badge badge-info">Dispatched</span><?php
										}
								?></td>
							</tr>
						</table>
					<?php
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
				}
			}
			if($action == "view_order") {
				if(!empty($request->orderId)) {
					$order = \App\Products_order::get_order_detail($request->orderId);
					if($order != NULL) {
						$comapny_name = sHelper::get_seller_owner($order->seller_id);
						$shipping_address_details = \App\Address::get_address_details($order->shipping_address_id);
						// echo "<pre>";
						// print_r($shipping_address_details->address_1);exit;
						?>
							<table class="table">
								<tr>
									<th>Customer Name</th>
									<td><?php echo $order->f_name; ?></td>
								</tr>
								<tr>
									<th>Company Name</th>
									<td><?php 
											echo $comapny_name;
										?>
									</td>
								</tr>
								<tr>
									<th>Order At</th>
									<td><?php echo $order->order_date; ?></td>
								</tr>
								<tr>
									<th>Transaction Id</th>
									<td><?php echo $order->transaction_id; ?></td>
								</tr>
								<tr>
									<th>Number of Products</th>
									<td><?php echo $order->no_of_products; ?></td>
								</tr>
								<tr>
									<th>Total Price</th>
									<td>&euro;&nbsp;<?php echo $order->total_price; ?></td>
								</tr>
								<tr>
									<th>Total Discount</th>
									<td>&euro;&nbsp;<?php echo $order->total_discount; ?></td>
								</tr>
								
								<tr>
									<th>Sipping Address</th>
									<!--<td><?php echo $order->shipping_address_id; ?></td>-->
									<td>
										<?php 
											if(!empty($shipping_address_details->address_1)) {
												echo $shipping_address_details->address_1 ;
												?>,&nbsp;<?php
											}
											if(!empty($shipping_address_details->address_2)) {
												echo $shipping_address_details->address_2 ;
												?>,&nbsp;<?php
											} 
											if(!empty($shipping_address_details->address_3)) {
												echo $shipping_address_details->address_3 ;
												?>,&nbsp;<?php
											} 
											if(!empty($shipping_address_details->address_3)) {
												echo $shipping_address_details->address_3 ;
												?>,&nbsp;<?php
											} 
											if(!empty($shipping_address_details->landmark)) {
												echo $shipping_address_details->landmark ;
												?>,&nbsp;<?php
											} 
											if(!empty($shipping_address_details->zip_code)) {
												echo $shipping_address_details->zip_code ;
												?>&nbsp;.<?php
											} 
										?>
									</td>
								</tr>
								<tr>
									<th>Address Type</th>
									<td><?php echo $order->address_type; ?></td>
								</tr>
								<tr>
									<th>Tracking Id</th>
									<td><?php echo $order->tracking_id; ?></td>
								</tr>
								<tr>
									<th>Courier Id</th>
									<td><?php echo $order->courier_id; ?></td>
								</tr>
								<tr>
									<th>Payment Status</th>
									<td>
										<?php
											if(!empty($order->payment_status)){
												if($order->payment_status == "P"){
													?>
														<span class="badge badge-danger">Pending</span>
													<?php
												} else if($order->payment_status == "C") {
													?>
														<span class="badge badge-success">Confirm</span>
													<?php
												}
											}
										?>
									</td>
								</tr>
								<tr>
									<th>Order Status</th>
									<td>	
										<div style="min-width: 120px">
											<div class="btn-group">
												<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
												<?php
													if($order->status == "I"){
														?>
															<span id="order_status">In Process</span>
														<?php
													} else if($order->status == "D"){
														?>
															<span id="order_status">Dispatched</span>
														<?php
													} else if($order->status == "IN") {
														?>
															<span id="order_status">Intransit</span>
														<?php
													} else if($order->status == "DE") {
														?>
															<span id="order_status">Delivered</span>
														<?php
													}
												?>
												</button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
														<a href="#" style="color:#333;" class="change_order_status" data-orderid="<?php echo $order->id?>" data-status="I">In Process</a>
													</li>
													<li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
														<a href="#" class="change_order_status" data-orderid="<?php echo $order->id?>" style="color:#333;" data-status="D"> Dispatched</a>
													</li>
													<li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
														<a href="#" class="change_order_status" data-orderid="<?php echo $order->id?>" style="color:#333;" data-status="IN"> Intransit</a>
													</li>
													<li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
														<a href="#" class="change_order_status" data-orderid="<?php echo $order->id?>" data-status="DE"style="color:#333;"> Delivered</a>
													</li>
												</ul>
											</div>
										</div>	
									</td>
								</tr>
							</table>
						<?php
					} else {
						return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
					}
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
				}
			}
			
			if($action == "view_product_description") {
				if(!empty($request->orderId)) {
					$product_desc = \App\Products_order_description::get_product_description($request->orderId);
					$i=0;
					if($product_desc != NULL) {
						?>
						<div class="card" id="user_data_body" style="overflow:auto">
							<table class="table">
								<thead>
									<tr>
										<th>S No.</th>
										<th>Customer Name</th>
										<th>Product Order Id</th>
										<th>Product Name</th>
										<th>Product Descriptions</th>
										<th>Coupan Id</th>
										<th>Price</th>
										<th>Discount</th>
										<th>Total Price</th>
										<th>Created At</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!empty($product_desc)) {
										foreach($product_desc as $product_desc){
										    $i++;
									   		?>
										    <tr>
											   <td><?php echo $i;?></td>
											   <td><?php echo $product_desc->f_name; ?></td>
											   <td><?php echo $product_desc->products_orders_id; ?></td>
											   <td><?php echo $product_desc->product_name; ?></td>
											   <td><?php echo $product_desc->product_description; ?></td>
											   <td><?php echo $product_desc->coupons_id; ?></td>
											   <td>&euro;<?php echo $product_desc->price; ?></td>
											   <td><?php echo $product_desc->discount; ?></td>
											   <td>&euro;<?php echo $product_desc->total_price; ?></td>
											   <td><?php echo $product_desc->created_at; ?></td>
											   <td>
												   <?php 
													   if($product_desc->status == "P") {
														   ?>
															   <span style="background:red;color:white">Pending</span>
														   <?php
													   } else if($product_desc->status == "A") {
														   ?>
															   <span style="background:green;color:white">Approved</span>
														   <?php
													   }
												   ?>
											   </td>
										   </tr>
									   <?php
										}
									} else {
										?>
											<tr><td colspan="7">No Product Avilable..</td></tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<?php
					} else {
						return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
					}
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
				}
			}
			
			if($action == "change_order_status") {
				$order_id = $request->orderId_id;
				$status = $request->status;
				// if( $request->status == 'P') {
					$arr = ['status' => $request->status];
					return \App\Products_order::where('id', $order_id)->update($arr);
				// }
			}
		if($action == "get_products_order_lists") {
			if(request()->ajax()) {
				$orders = Products_order::get_all_orders();
				if($orders->count() > 0){
					foreach($orders as $key => $order){
						$order->sNo = $key+1;
						$order->company_name = sHelper::get_seller_owner($order->seller_id);
					}
				}
				$start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
				$end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
				$status = (!empty($_GET["status"])) ? ($_GET["status"]) : ('');
					if($start_date && $end_date){
						$start_date = date('Y-m-d', strtotime($start_date));
						$end_date = date('Y-m-d', strtotime($end_date));
					   $orders = \App\Products_order::leftjoin('users' , 'products_orders.users_id' , '=' , 'users.id')->where([["products_orders.order_date",">=" , $start_date],["products_orders.order_date", "<=", $end_date]])->select('products_orders.*','users.f_name')->get();
					   if($orders->count() > 0){
						   foreach($orders as $key => $order){
							   $order->sNo = $key+1;
							   $order->company_name = sHelper::get_seller_owner($order->seller_id);
						   }
					   }
					}
					if($status){
					   $orders = \App\Products_order::leftjoin('users' , 'products_orders.users_id' , '=' , 'users.id')->where("products_orders.status","=" , $status)->select('products_orders.*','users.f_name')->get();
					   if($orders->count() > 0){
						   foreach($orders as $key => $order){
							   $order->sNo = $key+1;
							   $order->company_name = sHelper::get_seller_owner($order->seller_id);
						   }
					   }
					}
					if($start_date && $end_date && $status){
					$start_date = date('Y-m-d', strtotime($start_date));
					$end_date = date('Y-m-d', strtotime($end_date));
				   $orders = \App\Products_order::leftjoin('users' , 'products_orders.users_id' , '=' , 'users.id')->where([["products_orders.order_date",">=" , $start_date],["products_orders.order_date", "<=", $end_date]])->where("products_orders.status","=" , $status)->select('products_orders.*','users.f_name')->get();
				   if($orders->count() > 0){
					   foreach($orders as $key => $order){
						   $order->sNo = $key+1;
						   $order->company_name = sHelper::get_seller_owner($order->seller_id);
					   }
				   }
				}
				return  datatables()->of($orders)
				->addColumn('action', function($orders){
					$button = '
					<div style="min-width: 120px;float:right">
						<div class="btn-group"><a href="#" data-toggle="tooltip" title="View Order" data-orderid="'.$orders->id.'" class="btn btn-primary get_order_details"><i class="fa fa-eye"></i></a>
							<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
							</button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
									<a href="'.url("admin/order_details/$orders->id").'" target="_blank" style="color:#333;"><i class="fa fa-eye"></i> Order Deatils</a>
								</li>
							</ul>
						</div>
					</div>
					';
					/* $button .= '<a href="'.url("admin/view_product_desc/$orders->id").'" data-toggle="tooltip" data-placement="top" title="View Order Description" class="btn btn-warning"><i class="fa fa-eye"></i></a>'; */
					return $button;
				})
				->make(true);
			}
		}

		if($action == "get_revision_servicebooking") {
			if(request()->ajax()) {
				$orders = \App\ServiceBooking::get_all_revision_service_list();
				if($orders->count() > 0){
					foreach($orders as $key => $order){
						$order->sNo = $key+1;
						$workshop_owner = sHelper::get_workshop_owner($order->workshop_user_id);
						$order->company_name = $workshop_owner->company_name;
					}
				}	
				$start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
				$end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
				if($start_date && $end_date){
					$start_date = date('Y-m-d', strtotime($start_date));
					$end_date = date('Y-m-d', strtotime($end_date));
					$orders = DB::table('service_bookings as a')->leftjoin('services as s' , 's.id','=','a.services_id')->leftjoin('categories as c' , 's.category_id','=','c.id')->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')->where([['a.type' , '=' ,3],["a.booking_date", ">=" , $start_date],["a.booking_date", "<=", $end_date]])->select('a.*','u.f_name','c.category_name')->get();
					foreach($orders as $key => $order){
						$order->sNo = $key+1;
						$workshop_owner = sHelper::get_workshop_owner($order->workshop_user_id);
						$order->company_name = $workshop_owner->company_name;
					}
				}
				return  datatables()->of($orders)
				->addColumn('show_status', function($orders){
					$button1 = '
						<select id="change_booking_status" data-bookingid="'.$orders->id.'" class="form-control btn btn-default" style="width: 145px;">
							<option value="P" '.(($orders->status == "P") ? "selected" : '').' >Pending</option>
							<option value="CA" '.(($orders->status == "CA") ? "selected" : "").'>Canceled</option>
							<option value="C" '.(($orders->status == "C") ? "selected" : "").'>Paid</option>
							<option value="D" '.(($orders->status == "D") ? "selected" : "").'>Work Completed</option>
						</select>

					';
					return $button1;
				})
				->addColumn('action', function($orders){
					$button = '
							<ul class="">
								<li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
									  <a href="'.url("admin/service_order_details/$orders->id").'" target="_blank data-toggle="tooltip" data-serviceid="'.$orders->id.'" data-placement="top" title="View Services" class="btn btn-primary btn-xs"><i class="fa fa-eye" ></i></a> 
								</li>
							</ul>

					';
					return $button;
				})
				->rawColumns(array("show_status", "action"))
				->make(true);
			}
		}
 			if($action == "change_feed_status"){
				$result = \App\Feedback::find($request->id);
				$result->is_deleted = $request->status;
				 if($result->save()){
					   if($request->status == 1) $msg_status = "deleted";
					   else $msg_status = "Active";
				  return json_encode(array('status'=>200 , 'msg'=>"<div class='notice notice-success'><strong>Success , </strong> Feedback is $msg_status successfully .</div>"));
				 }
				 else{
				  return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong>Please try again .</div>'));
				 }
				 exit;	 
			} 
			if($action == 'change_business_status') {
				$business_id = $request->business_id;
				$status = $request->status;
				if( $request->status == 'P') {
					$arr = ['status' => 'A'];
					return \App\BusinessDetails::where('id', $business_id)->update($arr);
				}
				if( $request->status == 'A') {
					$arr = ['status' => 'P'];
					return \App\BusinessDetails::where('id', $business_id)->update($arr);
				}
			}
			if($action == 'change_bank_status') {
				$bank_id = $request->bank_id;
				$status = $request->status;
				if( $request->status == 'P') {
					$arr = ['status' => 'A'];
					return \App\Bankdetails::where('id', $bank_id)->update($arr);
				}
				if( $request->status == 'A') {
					$arr = ['status' => 'P'];
					return \App\Bankdetails::where('id', $bank_id)->update($arr);
				}
			}
			if($action == "change_customer_status") {
				$customer_id = $request->customer_id;
				$status = $request->status;
				if( $request->status == 'B') {
					$arr = ['users_status' => 'A'];
					return \App\User::where('id', $customer_id)->update($arr);
				}
				if( $request->status == 'A') {
					$arr = ['users_status' => 'B'];
					return \App\User::where('id', $customer_id)->update($arr);
				}
			}
			//Edit Notification
		if($action == "edit_notification_details") {
				$notification_detail = \App\Notification::edit_notification_details($request->id);	
				if($notification_detail != NUll){
					 return json_encode(array("status"=>200 , "response"=>$notification_detail));
				}else{
					 return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
				}
		 }
		 if($action == "edit_manage_details"){
			$edit_manage_detail = \App\ManageAdverting::edit_advertising_details($request->id);	
			if($edit_manage_detail != NUll){
				return json_encode(array("status"=>200 , "response"=>$edit_manage_detail));
			}else{
				return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
			}
		}

		if($action == "get_feedback_list"){
			$data = [];
			$start_date = \sHelper::date_format_for_database($request->start_date , 2);
			$end_date = \sHelper::date_format_for_database($request->end_date , 2);
			if(empty($start_date) || empty($end_date)){
				$data['all_feedback'] = \App\Feedback::get_all_feedback(); 
				return view("admin.component.feedback_list")->with($data);
			  } else {
				$data['all_feedback'] = DB::table('feedback as a')
               ->leftjoin('users as b' ,'b.id' , '=' ,'a.users_id' )
			   ->select('a.*' , 'b.f_name' , 'b.l_name' , 'b.id as uid')
			   ->whereBetween('a.created_at', [$start_date , $end_date])->get(); 
				
			   	return view("admin.component.feedback_list")->with($data);
			  }	 
		}
	
		} 
	//Add notification
	public function post_action(Request $request , $action){
				if($action =='add_notification')
				{
					$validator = \Validator::make($request->all(), [
						'notification_type' => 'required',
						'target_user' => 'required',
						'title' => 'required',
						'subject' => 'required',  
						'content' => 'required',  
						//'url' => 'required', 
						//'file' => 'required',   
					]);
					if($validator->fails()){
						return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
					}
						$image = $this->upload_notification_pic($request);
						$result = \App\Notification::add_notification($request,$image);
							if($result){
								return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
							} else {	
								return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
							} 
				}
				//Add advertising
			if($action == 'manage_advertising'){
				$validator=\Validator::make($request->all(),[
				'title' => 'required',
				//'description' => 'required',
				// 'url' => 'required',
				'add_location' => 'required',
				'main_category_id'=>'required',
				//'file' => 'required',
			]);
			if($validator->fails()){
				return json_encode(array("error" => $validator->errors()->getMessages(), "status" => 400));
			}
			//$image = $this->upload_notification_pic($request);
			$result = \App\ManageAdverting::add_manageadverting($request);
				if($result){
					return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
				}else{
					return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
				}
					
			}
		if($action == "upload_advertising_image") {
			if(count($request->cat_file_name) > 0){
				$category_images = $this->upload_advertising_image($request); 			
                if(count($category_images) > 0){
                        foreach($category_images as $image){
                            $insert_category = \App\Advertising_image::add_Advertising_gallery($image , $request->advertising_id);
                        }
                          return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Image uploaded successfully !!! </div>'));
                }
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong>Please Select at least one image  !!! </div>'));
            }
		}
		if($action == "add_customer"){
			$customer_user = \App\User::find($request->customer_id);
			$user_wallet = apiHelper::manage_registration_time_wallet($customer_user , $request->user_bouns , $request->user_bouns_detail);
			return redirect()->back();
			
		}
		if($action == "add_master_bonus"){
			$update_data = \App\Master_bonus_amount::where([])->update([
			'for_registration'=>$request->for_registration,
			'two_level_amount'=>$request->two_level_amount,
			'three_level_amount'=>$request->three_level_amount ]);
			 return redirect()->back();
		}	
				
		}		 
	 
	 
}
