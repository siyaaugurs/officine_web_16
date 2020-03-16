<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Servicequotes;
use App\Feedback;
use App\Services;
use App\WorkshopServicesPayments;
use sHelper;
use apiHelper;
use DB;

class ServiceQuotesController extends Controller{
	
	
	public function get_service_quotes_list(Request $request){
			$result = \App\Servicequotes::add_service_quotes($request);
			$category_data = DB::table('servicequotes')->select('id')->where([['id','=', $result->id],['status', '=' , 'A'],['deleted_at' ,'=' , NULL]])->first();
		//$category_list = DB::table('users_categories')->where([['categories_id','=', $request->category_id],['for_quotes', '=' , 1],['deleted_at' ,'=' , NULL]])->get();
			$services_id = $request->category_id;
			if(empty($request->car_size) ){
				return sHelper::get_respFormat(0, " Car size is required !!!. ", null, null);
			}
			/*Selected workshop those are off this selected days and dates*/
			$off_days_workshop_users = [];
			$minPrice = 0;
			$maxPrice = 0;
			if (!empty($request->selected_date)) {
				$off_selected_date = \App\Workshop_leave_days::get_valid_workshop($request);
				$users_arr = $off_selected_date->pluck('users_id');
				$off_days_workshop_users = $users_arr->all();
			}
			else{
				return sHelper::get_respFormat(0, " Please select one date  !!!. ", null, null);	
			}
			$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
				$price = null;
		$all_selected_workshop = \App\Services::get_car_wash_services_workshop_service_quotes($services_id , $request->car_size , $off_days_workshop_users,$request);		
		$service_time = 0;
		$remove_workshop_arr = [];
		$service_payment_data  = collect();
		$flag = 0;
		/*Get Service Time script start*/
		$service_time = sHelper::get_car_wash_service_time($request->car_size , $request->category_id);
		if ($all_selected_workshop->count() > 0) {
			$price = 0;
		foreach ($all_selected_workshop as $workshop_users) {
			$workshop_users->max_appointment = 0;
			$workshop_users->hourly_rate = (string) 0;
				/*Check Service details in service table*/
				$service_details = Services::where([['category_id' , '=' , $request->category_id] , ['car_size', '=', $request->car_size] , ['users_id', '=', $workshop_users->users_id]])->first();
				if($service_details == NULL){
					$service_payment_data =  WorkshopServicesPayments::where([['category_type' , '=' , 1], ['workshop_id', '=', $workshop_users->users_id]])->first();
					if($service_payment_data != NULL){
						$workshop_users->hourly_rate = $service_payment_data->hourly_rate;
						$workshop_users->max_appointment = $service_payment_data->maximum_appointment;				
					   }
				}else{
					$workshop_users->hourly_rate = $service_details->hourly_rate;
					$workshop_users->max_appointment = $service_details->max_appointment;
				}
				/*workshop user id push in remove array*/
				if($service_details == NULL && $service_payment_data == NULL){
					 $remove_workshop_arr[] =  $workshop_users->users_id;
				   }
				/*End*/
				$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $workshop_users->users_id] , ['deleted_at' , '=' , NULL]])->get();
				if($workshop_package_timing->count() == 0){
				  $remove_workshop_arr[] =  $workshop_users->users_id;
				} 
				$workshop_users->category_id = $services_id;
				$price = sHelper::calculate_service_price($workshop_users->hourly_rate , $service_time);
				/*Get PAckages availablke or not*/
				   $timing_slot_status = sHelper::check_time_slot($workshop_users->users_id , $selected_days_id ,$request->selected_date ,  $workshop_users->max_appointment , $services_id  , $request->car_size  , $service_time);
				/*End*/
				$workshop_users->available_status = $timing_slot_status;	
				$workshop_users->services_price = (string) $price;	
				$workshop_users->service_average_time = (string) $service_time;
				$workshop_users->products_id = null;
				$workshop_users->about_services = '';
				$workshop_users->car_size = $request->car_size;
				$workshop_users->status = "1";
				$workshop_users->type = $request->type;
				$workshop_users->days_id = $selected_days_id;
				$workshop_users->is_deleted_at = NULL;
				$workshop_users->hourly_rate = (string) $workshop_users->hourly_rate;
			$workshop_users->service_images = NULL;
			$workshop_users->package_list = NULL;
			$workshop_users->profile_image_url = NULL;

			if(!empty($workshop_users->profile_image)){
			   $workshop_users->profile_image_url = url("storage/profile_image/$workshop_users->profile_image");
			   }
			$all_feed_back = null;
			$all_feed_back['rating'] = null;
			$all_feed_back['num_of_users'] = null;
			$all_feed_back = Feedback::get_workshop_rating($workshop_users->users_id);
			if ($all_feed_back != NULL) {
				$workshop_users->rating = $all_feed_back;
				$workshop_users->rating_star = $all_feed_back['rating'];
				$workshop_users->rating_count = $all_feed_back['num_of_users'];
			}
		}
		$all_selected_workshop = $all_selected_workshop->whereNotIn('users_id' , $remove_workshop_arr);
		/*Set Min price and max price*/
		$minPrice = $all_selected_workshop->min('services_price');
		$maxPrice = $all_selected_workshop->max('services_price'); 

		//$all_filtered_workshop = $sorted->values()->all();
		if (!empty($request->rating)) {
			$rating_arr = explode(',', $request->rating);
			$all_selected_workshop =  $all_selected_workshop->whereBetween('rating_star', $rating_arr);
		}
		else{
		   $all_selected_workshop->sortByDesc('rating_star'); 
		}
		if (!empty($request->price_range)) {
			$price_arr = explode(',', $request->price_range);
			$all_selected_workshop = $all_selected_workshop->whereBetween('services_price', $price_arr);
		}
		if (!empty($request->price_level)) {
			if ($request->price_level == 1) {
				$all_selected_workshop = $all_selected_workshop->sortBy('services_price')->values();
			}
			else if ($request->price_level == 2) {
			 $all_selected_workshop = $all_selected_workshop->sortByDesc('services_price')->values();
			}
		  }
		else{
		   $all_selected_workshop = $all_selected_workshop->sortBy('services_price')->values();
		  } 
		 $all_selected_workshop->map(function($workshop) use ($minPrice , $maxPrice){
			 $workshop->min_price = $minPrice;
			 $workshop->max_price = $maxPrice;
			 return $workshop;
		 }); 	
			//return sHelper::get_respFormat(1, " ", null, $all_selected_workshop);
			return sHelper::get_respFormat(1, 'Show list !!!', $category_data, $all_selected_workshop);
		} else {
			return sHelper::get_respFormat(0, " No Workshop Available for this service !!!. ", null, null);
		}

	}
	 public function get_next_seven_days_min_price_for_service(Request $request) {
			//DB::enableQueryLog();
		 $validator = \Validator::make($request->all(), [
		      'selected_date'=>'required' , 'type'=>'required' , 'car_size'=>'required'
	     ]);
		if($validator->fails()){
             return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		 }
		$min_price = [];
		/*Selected workshop those are off this selected days and dates*/
		$selected_date = $request->selected_date;
		for ($i = 0; $i < 30; $i++) {
			$request->selected_date = date('Y-m-d', strtotime($selected_date . ' + ' . $i . ' day'));
			$off_days_workshop_users = [];
			if (!empty($request->selected_date)) {
				$off_selected_date = \App\Workshop_leave_days::get_valid_workshop($request);
				$users_arr = $off_selected_date->pluck('users_id');
				$off_days_workshop_users = $users_arr->all();
			}
			$selected_days_id = \sHelper::get_week_days_id($request->selected_date);
			if ($request->type == 1) {
				if(empty($request->category_id)) {
					return sHelper::get_respFormat(0, "please select any one category", null, null);
				}
				$category_details = DB::table('categories')->where('id' , $request->category_id)->first();
				if($category_details != NULL){
				   /*Get service average time */
					$average_timing = sHelper::get_car_wash_service_time($request->car_size , $category_details->id);
					/*End*/
				} else {
				 return sHelper::get_respFormat(0, "Please select valid category !!!", null, null); 
				}
				$all_selected_workshop = \App\Services::get_car_wash_services_workshop_new1(NULL , $request->car_size , $off_days_workshop_users);			    
			}
			$price = null;
			$remove_workshop_arr = [];
			if ($all_selected_workshop->count() > 0) {
				foreach ($all_selected_workshop as $workshop_users) {
					
					/*get workshop services details*/
					 $workshop_services_detail = apiHelper::workshop_car_washing_details($request->category_id , $request->car_size , $workshop_users->users_id);
					 $service_details_response = json_decode($workshop_services_detail);
						if($service_details_response->status == 100){
						$remove_workshop_arr[] = $workshop_users->users_id;
						} else{
							if($service_details_response->status == 200){
								$workshop_users->hourly_rate = $service_details_response->response->hourly_rate;
								}
						}  
					 /*End*/
					$workshop_package_timing = DB::table('workshop_user_day_timings')->where([['users_id' , '=' , $workshop_users->users_id] , ['deleted_at' , '=' , NULL]])->get();
					if($workshop_package_timing->count() == 0){
						$remove_workshop_arr[] =  $workshop_users->users_id;
					  } 					
				}
				$all_selected_workshop = $all_selected_workshop->whereNotIn('users_id' , $remove_workshop_arr);
				if($all_selected_workshop->count() > 0){
					$all_selected_workshop->map(function($workshop ) use ($average_timing){
                       $workshop->service_hourly_average_price = sHelper::calculate_service_price($average_timing , $workshop->hourly_rate);
					});
					$min_price_collect = $all_selected_workshop->min('service_hourly_average_price');
				}
				$min_price[] = array('date' => $request->selected_date, 'price'=>(string) $min_price_collect );
				/*  end */
			} else {
				$min_price[] = array('date' => $request->selected_date, 'price' =>(string) $min_price);
			}
		}
		//print_r($min_price); die;
		return sHelper::get_respFormat(1, " ", null, $min_price);
		
	}


	public function get_main_category(Request $request){	
		$ctegories = \DB::table('main_category')->where([['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->get();
			if($ctegories){
				return sHelper::get_respFormat(1, 'category list', null, $ctegories);
			} else {	
				return sHelper::get_respFormat(0, 'Something Went wrong please try again .', null, null);
			} 
	}
    

}
