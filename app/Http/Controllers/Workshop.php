<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use sHelper;
use App\Users_category;
use App\Products;
use App\Products_assemble;
use kRomedaHelper;
use App\ProductsNew;
use serviceHelper;
use DB;


class Workshop extends Controller{
   
   
     public function page($page , $para = NULL){
	   /* $data['page'] = $page;
	   $data['workshop_details'] = \App\User::find(decrypt($workshop_user_id));
	   $data['users_profile'] = \App\User::find(Auth::user()->id);
	   $data['categories'] = \App\Category::get_parent_category();
	   $data['title'] = "Officine Type vendor - ".$page;
		*/
		$data['page'] = $page;
		if(Auth::check()) {
           $data['users_profile'] = \App\User::find(Auth::user()->id);
            $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
           $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
            $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
			}else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
		 }
		$data['title'] = "Officine Top vendor - ".$page;
		
		if($page == "products_asseble"){
		    $data['checkbox'] = 1;
			$products = ProductsNew::get_assemble_products();
			$users_assemble_products = Products_assemble::get_users_products_id(Auth::User()->id);
			
			$data['products'] = $products;
			if($products->count() > 0){
			    if($users_assemble_products->count() > 0){
			    $assemble_products_arr = $users_assemble_products->pluck('products_id')->all();            foreach($products as $product){
					$all_filtered_assemble_products = $products->filter(function ($product) use ($assemble_products_arr) {
									return !in_array($product->id , $assemble_products_arr);
								 }); 
							} 
					$data['products'] = $all_filtered_assemble_products;		 
				}
			}
			 
		}
		
		/*if($page == "assemble_services"){
		     $data['checkbox'] = 2;
		    $data['cars__makers_category'] = kRomedaHelper::get_makers();
			$data['products'] = \App\Products_assemble::get_assemble_products();	
			$data['service_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);	
			$data['listed_services'] = \App\Services::assemble_service(Auth::user()->id);
			/*echo "<pre>";
			print_r($data['listed_services']);exit;*/	
// 		}*/
		
		if($page == "assemble_services"){
		    $data['cars__makers_category'] = kRomedaHelper::get_makers();
			$data['products'] = \App\Products_assemble::get_assemble_products();	
			$data['service_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);	
			$data['listed_services'] = \App\Services::assemble_service(Auth::user()->id);
			/* echo "<pre>";
			print_r($data['listed_services']);exit;*/ 	
		}
		
		if($page == "edit_assemble_services"){
			if(empty($para))return redirect()->back();
			$data['assemble_services_details'] = \App\Services::get_assemble_service_record(decrypt($para));
			if($data['assemble_services_details'] != NULL) {
				$data['cars__makers_category'] = kRomedaHelper::get_makers();
				$data['users_services_days'] = \App\Service_weekly_days::get_services_days($data['assemble_services_details']->id);
				$data['images_arr'] = \App\Gallery::get_service_image($data['assemble_services_details']->id);
				$data['service_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);	
			}
		}
        if($page == "assemble_service_categories") {
        	$data['workshop_status'] = serviceHelper::get_profile_status(Auth::user()->id);
			//$data['spare_groups'] = Users_category::spare_services_categories(Auth::user()->id);
			$data['spare_groups'] = Users_category::spare_services_categories(Auth::user()->id);
			
			$data['assemble_spare_groups'] = Users_category::get_assemble_service_details(Auth::user()->id);
			$data['assemble_service_details'] = \App\WorkshopServicesPayments::get_assemble_service_details(Auth::user()->id);
			if($data['assemble_service_details'] != NULL){
			     $data['hourly_rate'] = $data['assemble_service_details']->hourly_rate;
			     $data['maximum_appointment'] = $data['assemble_service_details']->maximum_appointment;
			  } 
			/*  echo "<pre>";
			 print_r($data['spare_groups']);exit; */
		}
        
        if($page == "list_spare_items"){
            $data['spare_groups'] = Users_category::spare_group_services(Auth::user()->id);
            if($data['spare_groups']->count() > 0){
                $groups_id_arr = $data['spare_groups']->pluck('id')->all();
            }
            $data['spare_items'] = \App\Spare_category_item::get_all_spare_items($groups_id_arr);
            //echo "<pre>";
            //print_r($data['spare_items']);exit;
			//$data['spare_groups'] = \App\MainCategory::get_all_valid_spares_group();
        }
        
        if($page == "products_assemble_list"){
            $data['checkbox'] = 2; 
		$data['products'] = Products_assemble::get_users_assemble_products_paginate(Auth::User()->id);
			$data['cars__makers_category'] = kRomedaHelper::get_makers();
		  }
        
		if(!view()->exists('workshop.'.$page))
		return view("404")->with($data);
		else 
		return view("workshop.".$page)->with($data);
	}
	
	public function post_action(Request $request , $action){
       if($action == "products_asseble"){
		 if(!empty($request->products)){
		     //echo "<pre>";
		     //print_r($request->products);exit;
			$result = Products_assemble::add_products($request);
			if($result != FALSE){
				return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Products save  successfull !!! </div>']);	
			}
			else{
				return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again !!! </div>']);	
			}

		 } 
		 else
			return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Select at least one products !!! </div>']);			
	   }
	}
}
