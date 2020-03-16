<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;
use sHelper;
use App\Workshop;
use App\Gallery;

class VendorAjax extends Controller{
  	public $imageArr = array("jpeg" , "png" , "jpg" , "JPEG" , "PNG" ,"JPG" );
    public function postAction(Request $request , $action){
    	if($action == "edit_mot_service_details") {
			$validator = \Validator::make($request->all() , [
				'service_max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
				'service_hourly_rate'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0']);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\WorkshopMotServiceDetails::mot_service_details($request->service_type, $request->service_id, $request->service_max_appointment, $request->service_hourly_rate);
			if($result){
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Service Details Updated successfully !!! </div>'));
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
			}
		}

		if($action == "edit_all_mot_service_details") {
			$validator = \Validator::make($request->all() , [
				'mot_max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
				'mot_hourly_rate'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0']);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\WorkshopServicesPayments::add_mot_servce_details($request);
			$our_mot_service = \App\Our_mot_services::get_workshop_mot_services();
			foreach($our_mot_service as $service) {
				$our_mot_service = \App\WorkshopMotServiceDetails::mot_service_details(2, $service->id, $request->mot_max_appointment, $request->mot_hourly_rate);
			}
			$k_mot_services = \App\VersionServicesSchedulesInterval::where([['deleted_at', '=', NULL]])->get();
			foreach($k_mot_services as $k_service) {
				$our_mot_service = \App\WorkshopMotServiceDetails::mot_service_details(1, $k_service->id, $request->mot_max_appointment, $request->mot_hourly_rate);
			}
			if($k_mot_services) {
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved successfully  !!!.</div>'));
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  !!!.</div>')); 
			}
		}
        /*if($action == "add_selected_services") {
			// return $request;exit;
			if(!empty($request->records)) {
				$flag = FALSE; 
				$booking_id = $request->booking_id;
				$booking_detail = \App\User_car_revision::get_bookings_details($booking_id);
				// echo "<pre>";
				// print_r($booking_detail->users_id);exit;
				$data_arr = '';
				foreach($request->records as $record){
					DB::table('car_revision_bookings')->insert(array(
						'users_id'=>$booking_detail->users_id ,
						'user_car_revisions_id'=>$request->booking_id ,
						'service_id'=>$record['service_id'] ,                  
						'service_name'=>$record['service_name'] , 
						'service_price'=>$record['service_price'] , 
						'created_at' => date('Y-m-d H:i:s') , 
						'updated_at' => date('Y-m-d H:i:s')
					));
					$flag = TRUE;												  
				}
				if($flag != FALSE){
					return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Services Added Successfully !!!.</div>']);
				} else {
					  return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>']);
				}
			}
		}*/
		if($action == "add_selected_services") {
			if(!empty($request->records)) {
				$flag = FALSE; 
				$booking_detail = \App\User_car_revision::get_bookings_details($request->booking_id);
				$duplicate_exit = \App\Car_revision_booking::get_added_services($request->booking_id);
				// echo "<pre>";
				// print_r($duplicate_exit);exit;
				if(!empty($duplicate_exit)) {
					// $duplicate_exit = \App\Car_revision_booking::delete_selected($request->booking_id);
					$result = \App\Car_revision_booking::where('user_car_revisions_id' , $request->booking_id)->delete();
				}
				$total_price = \App\User_car_revision::find($request->booking_id);
				$total_price->total_price = $request->total_price;
				$total_price->save();
				if($booking_detail != NULL){
				 $response = \App\Car_revision_booking::save_car_revision_services($request , $booking_detail->users_id);
				 $flag = TRUE;
				 
				}
				if($flag != FALSE){
					return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Services Added Successfully !!!.</div>']);
				} else {
					  return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>']);
				}
			}
		}
        if($action == "add_car_revision_category") {
			if(!empty($request->category_name && $request->price)) {
				$duplicate_exist = \App\Category::check_duplicate_category($request->category_name);
				// echo "<pre>";
				// print_r($duplicate_exist);exit;
				if($duplicate_exist != NULL){
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong> This Category Name is already listed !!!.</div>'));
				} else {
					$result = \App\Category::add_car_revision_service($request);
					if($result) {
						return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Service Added successfully .</div>'));
					} else {
						return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
					}
				}
			}
		}
		/*Add WorkShop timing script start*/
		  if($action == "add_workshop_timing"){
				if(count($request->week_days) > 0){
					   $loop_index = 0;
					   /*First Delete all workshop timing in table*/
					    $result = \App\Workshop_user_day_timing::delete_timing(array('users_id'=>Auth::user()->id));
			            $result_2 = \App\Workshop_user_day::delete_days(array('users_id'=>Auth::user()->id));   
					   /*End*/
					   
					   foreach($request->week_days as $key=>$day){
							 $is_opening_24 = 0;
							 if(!empty($request->day_24)){
							    foreach($request->day_24 as $key=>$opening_24){
							       $open_24Arr = explode("_" , $opening_24);
								   if($open_24Arr[1] == $day){
									   $is_opening_24 = 1;
									 }
								 }
							   }
						 $get_last_id = DB::table('workshop_user_days')->insertGetId(['users_id'=>Auth::user()->id ,'common_weekly_days_id'=>$day , 'is_whole_opening'=>$is_opening_24 , 'created_at'=>date('Y-m-d H:i:s') , 'updated_at'=>date('Y-m-d H:i:s')]);
						if($is_opening_24 != 1){
							DB::table('workshop_user_day_timings')->insert(array(
							         'users_id' =>Auth::user()->id ,
									 'workshop_user_days_id' => $get_last_id ,
							         'start_time' => $request->first_timing[$loop_index] , 
									 'end_time' => $request->second_timing[$loop_index] , 
									 'start_time_2' => $request->first_timing_1[$loop_index] ,
									 'end_time_2' => $request->second_timing_1[$loop_index],
									 'created_at' => date('Y-m-d H:i:s') , 
									 'updated_at' => date('Y-m-d H:i:s')
									 ));
						   }
						   $loop_index++;
						}
					}
					exit;
			}
		/*End*/
		
	    /*Edit Work Shop Script Start*/
		  if($action == "edit_workshop"){
			 $flag = 0;
			 $result =  Workshop::edit_workshop($request);
			 if($result != NULL){
				     return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Workshop edit successfully !!! </div>'));
				   }
				  else{
				    return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong> please try again  !!! </div>'));
			   }
		  }
		/*End*/ 


	    /*Add Workshop Code Script Start*/
		if($action == "add_workshop"){
		     $validator = \Validator::make($request->all(), [
                'category' => 'required',
                'title' => 'required' , 'amount'=>'required'
               ]);
			 if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
			 $flag = 0;
			 DB::transaction(function () use ($request){
                $result =  DB::table('workshops')->insertGetId(array(
											   'enctype_id'=>md5(rand(999,99999).time()),
											   'users_id'=>Auth::user()->id , 
											   'title'=>$request->title ,  
											   'paid_status'=>$request->work_shop_paid , 
                                               'description'=>nl2br($request->description) ,
                                               'amount'=>$request->amount ,
                                               'created_at'=>date('Y-m-d H:i:s') 
                                             , 'updated_at'=>date('Y-m-d H:i:s'))); 

				$workShop_details = Workshop::find($result);
				$enctype_id = $workShop_details->enctype_id;							   
				if($result != NULL){
				     if(count($request->category) > 0){
					   foreach($request->category  as $cat_id){
						    DB::table('workshop_users_categories')->insert([
							                               'users_id'=>Auth::user()->id ,
														   'workshops_id'=>$result , 
														   'categories_id'=>$cat_id , 
														   'created_at'=>date('Y-m-d H:i:s'),
														   'updated_at'=>date('Y-m-d H:i:s')
														    ]);
						  }
						  
						echo json_encode(array("status"=>200 , 'url'=>url("workshop_details/$enctype_id") , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Workshop added Successfully  !!! </div>')); 
					  }
					  else{
					    echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Please select more category again  !!! </div>'));
				    }
				  }
				 else{
				     echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Please try again  !!! </div>'));
				  } 						   
		     });
			
			 //$result =  Workshop::add_workshop($request);
			
			/* if($result != NULL){
				   if(!empty($request->image_gallery)){
						if(count($request->file('gallery_image')) > 0){
						   $image_path = public_path('storage/workshop/');
							 if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
							  foreach($request->file("gallery_image") as $image){
								  $ext = $image->getClientOriginalExtension();
								  if(in_array($ext , $this->imageArr)){
									 $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
									 if( $image->move($image_path , $file_name )){
										 Gallery::add_workshop_gallery($file_name , $result->id);
										 $flag = 1;
									   }
									}
								   else continue;	
								   }
						 }
					}
					else $flag = 1;
					
				 if($flag == 1){
				     return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Workshop added Successfully  !!! </div>'));
				   }
				  else{
				    return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> please try again  !!! </div>'));
				  } 	
			  }*/ 
		  }
		 /*WorkShop Add Script Start*/ 
	 }
	 
	 /*Get Action Script Start*/
	   public function getAction(Request $request , $action){
	   		if($action == "get_services_interval") {
				if(!empty($request->language)){
					$lang = sHelper::get_set_language($request->language);
					if(!empty($request->service_schedule_id)){
						$response = \App\VersionServicesSchedulesInterval::get_workshop_intervals($request->service_schedule_id , $lang);
					} else {
						$response = \App\VersionServicesSchedulesInterval::get_workshop_intervals_version($request->version_id , $lang); 
					} 
					$response->map(function($mot){
						$mot->type = 1;
						return $mot;
					}); 
					$data['services_details'] = \App\WorkshopServicesPayments::get_mot_service_details(Auth::user()->id , 3);
					foreach($response as $services) {
						$services->max_appointment = NULL;
						$services->hourly_cost = NULL;
						$services->service_price = NULL;
						$service_details = \App\WorkshopMotServiceDetails::get_service_details(Auth::user()->id, $services->id, 1);
						if($service_details != NULL) {
							$services->max_appointment = $service_details['max_appointment'];
							$services->hourly_cost = $service_details['hourly_cost'];
						} else {
							if($data['services_details'] != NULL) {
								$services->max_appointment = $data['services_details']->maximum_appointment;
								$services->hourly_cost = $data['services_details']->hourly_rate;
							}
						}
						$services->service_price = sHelper::calculate_service_price($services->standard_service_time_hrs, $services->hourly_cost);
					}
					return view('workshop.component.service_interval')->with(['service_interval'=>$response]);
				}
			}
			if($action == "get_mot_service_details") {
				if(!empty($request->type) && !empty($request->service_id)) {
					$services = \App\WorkshopMotServiceDetails::get_service_details(Auth::user()->id , $request->service_id , $request->type);
					if($services){
						return json_encode(['status'=>200 , "response"=>$services]);
					} else {
						return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
					}
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong. Please Try Again !!!.</div>'));
				}
			}
			if($action == "get_all_mot_service_details") {
				$services = \App\WorkshopServicesPayments::get_mot_service_details(Auth::user()->id , 3);
				if($services != NULL) {
					return json_encode(array('status'=>200 , 'response' => $services));
				} else {
					return json_encode(array('status'=>100)); 
				}
			}
	       if($action == "get_workshop_category") {
			$result = \App\Category::get_car_revision_category($request->category_id);
			if($result != NULL){
				return json_encode(array("status"=>200 , "response"=>$result));	
			}
			else{
				return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Record save successful !!! </div>'));	
			}
		}
	     if($action == "add_off_date"){
			    if(!empty($request->off_date)){
					$today_date = time();
					if(strtotime($request->off_date) > $today_date){
					     $result = \App\Workshop_leave_days::add_date($request);
						 if($result){
						     return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Record save successful !!! </div>'));						   }
						 else{
						   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went wrong please try again !!! </div>'));    
						   }  
					   }
					 else{
					   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Please select correct date  !!! </div>'));   
				    
					  }   
				  }
				 else{
				   return  json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Please Select valid date  !!! </div>'));
				  } 
			 }  
	         
         if($action == "remove_mobile_number"){
			  if(\App\Common_mobile::where('id' , '=' , $request->row_id)->delete() ){
				 echo json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Mobile number removed Successfully !!! </div>'));   
				}
			  else{
				   echo json_encode(array("status"=>100 ,  "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>'));
				}	
			 exit;
			 }
		   if($action == "add_mobile_number"){
				 $duplicate = \App\Common_mobile::where('mobile' , '=' , $request->mobile_number)->first();
				 if($duplicate == NULL){
						$result = \App\Common_mobile::create(['users_id'=>Auth::user()->id , 'workshops_id'=>$request->workshop_id , 'mobile'=>$request->mobile_number]);
						if($result != FALSE){
						echo json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Mobile number added Successfully  !!! </div>'));  
						
						} 
						else{
								echo json_encode(array("status"=>100 ,  "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>'));
						}
				 }
				 else{
					echo json_encode(array("status"=>100 ,  "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong>mobile Number is already exist !!! </div>'));
				 }
				 exit; 		  
			 }
		/*Remove days and timing script start*/
		   if($action == "remove_days_timing"){
		       $result = \App\Workshop_user_day_timing::delete_record(array("workshop_user_days_id"=>$request->workshop_user_day_id));
			   $result_2 = \App\Workshop_user_day::delete_record(array('id'=>$request->workshop_user_day_id));
			   	   if($result_2 != FALSE){
					  echo json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Time removed successfully !!! </div>'));  
					 } 
					else{
				      echo json_encode(array("status"=>100 ,  "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>')); 
					 } 
		   }
		/*End*/
		
		 /*WorkShop Image delete Script Start*/
		 if($action == "remove_workshop_image"){
			  $image_details = Gallery::find($request->delete_id);
			  if($image_details != NULL){
				  $image_url = public_path("storage/workshop/");
				  $filePath = $image_url."/".$image_details->image_name;
				  if(file_exists($filePath)){ 
				      $image_details->delete();
                      unlink($filePath );
				   }
				}
		  }
		 /*End*/   
		}
	 /*End*/
  
}
