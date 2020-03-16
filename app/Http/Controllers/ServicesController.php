<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Service_weekly_days;
use Auth;

class ServicesController extends Controller{
	
	public function get_action(Request $request , $action){
	    if($action == "remove_services"){
		     if(!empty($request->serviceid)){
			     $service_details = \App\Services::find($request->serviceid);
				 if($service_details != NULL){
				     $service_details->is_deleted_at = date('Y-m-d H:i:s');
					 if($service_details->save()){
					     echo 1; exit;
					   }
					 else{
					     echo '<div class="notice notice-danger"><strong>Wrong , </strong> something went wrong , please try again  .</div>';exit;
					  }  
				   }
			  }
			 else{
			   echo '<div class="notice notice-danger"><strong>Wrong , </strong> something went wrong , please try again  .</div>';exit;
			  } 
		  }
		  
	     /*Remove Service image script start*/
	     if($action == "remove_service_image"){
		     $image_data = \App\Gallery::find($request->delete_id);
			  $image_path = public_path('storage/services/');;
			  $filethumbPath = $image_path."/".$image_data->image_name;
			  if(file_exists($filethumbPath)){ unlink($filethumbPath); }
		      if( $image_data->delete()){
				   return json_encode(array('status'=>200));
				}
			  else{
				   return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>'));
				}	
		  }
		  /*End*/
		 	if($action == "remove_packages"){
				 if(!empty($request->service_weekly_days_id)){
				     $package_details = \App\Services_package::get_services_packages($request->service_weekly_days_id);	   
					 if($package_details->count() == 1){
					   $result = Service_weekly_days::where('id' , $request->service_weekly_days_id)->delete(); 
					   }
					  $result = \App\Services_package::where('id' , $request->packagesid)->delete();
					  if($result){
						return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record delete successful .</div>')); 
						}
					  else{
						return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
						}	
					}	
			 } 
			 
		  if($action == "remove_services_days"){
			 $result = Service_weekly_days::where('id' , $request->services_days_id)->delete(); 
			  if($result){
				$result = \App\Services_package::where('services_weekly_days_id' , $request->services_days_id)->delete();	
				  return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record delete successful .</div>')); 
					}
				  else{
				  return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
					}
			}
			
      if($action == "edit_about_services"){
		    if(!empty($request->services_id)){
			  	if(!empty($request->about_services)){
					$result = \App\Services::find($request->services_id);
					$result->about_services = nl2br($request->about_services);
					//  $result->service_average_time = $request->average_time;
					//  $result->car_size = $request->car_size;
					if( $result->save() ){
						echo '<div class="notice notice-success"><strong>Success , </strong> Record save successful .</div>';exit;
						}
					else{
						echo '<div class="notice notice-danger"><strong>Wrong , </strong> something went wrong , please try again  .</div>';exit;
						} 
				} else {
				  	echo '<div class="notice notice-danger"><strong>Wrong , </strong> Please Enter the service Average timing .</div>';exit;
				}		
			}
		}
    }
    
    public function post_ajax_action(Request $request , $action){
      if($action == "add_service_coupon"){
			  $validator = \Validator::make($request->all(), [
				'coupon_type' => 'required', 'coupon_title' => 'required',
				'coupon_quantity' => 'required' , 'per_user_allot'=>'required' , 'launching_date'=>'required' , 'closed_date'=>'required' , 'avail_date'=>'required' , 'avail_close_date'=>'required', 'offer_type' => 'required', 'amount' =>'required'
			   ]);    
			  if($validator->fails()){
				  return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
				}
				$coupon_image = $this->upload_coupon_image($request); 
				if($coupon_image != 111){
						$result = \App\Coupon::add_service_coupon($request , $coupon_image);
                        if($result){
                          $response_service_coupon = \App\Services_coupon::add_coupons_details($result->id , $request->service_id , $request->service_package_id);
                          if($response_service_coupon){
					        return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Coupon save Successful !!! </div>')); 	
					        }
						 else{
							    return  json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Something went wrong please try again .!!! </div>')); 
						  }	 
						}
				}else{
						return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Wrong </strong> only , jpg , png format supported !!! </div>')); 
				}
			}    
        
	   if($action == "upload_category_image"){
	       if(empty($request->gallery_image)){
	       	   return json_encode(array('status'=>100  , "msg"=>'<div class="notice notice-danger"><strong>Note </strong> Select at least one image   !!! </div>'));
		   	}	 
		
		  if(!empty($request->category_id) && !empty($request->car_size)){
		   $response = \App\Services::add_services($request->category_id , $request->car_size);
			if($response){
				if(count($request->file('gallery_image')) > 0 || !empty($request->gallery_image)){
					$image_path = public_path('storage/services/');
					  if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
					   foreach($request->file("gallery_image") as $image){
						   $ext = $image->getClientOriginalExtension();
						   if(in_array($ext , $this->imageArr)){
							  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
							  if( $image->move($image_path , $file_name )){
								  \App\Gallery::add_service_gallery($file_name , $response->id);
								  $flag = 1;
								}
							 }
							else continue;	
							}
					 if($flag == 1){
					  return json_encode(array('status'=>200  , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Image Upload  Successful  !!! </div>'));
					}
				   else{
					 return json_encode(array('status'=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> please try again  !!! </div>'));
				   } 	   
				  }
				 else{
					 return json_encode(array('status'=>100 , "msg"=>'<div class="notice notice-danger"><strong> Note </strong> please select al least one image  !!! </div>'));
				   }
			}
		  }	
		   
		 }
	}
	
    public function edit_services(Request $request){
       /*Packages Edit code */
	    $selected_arr = [];
		 foreach($request->daysData as $day_data) {
			$selected_arr[] = $day_data['selected'];
		  }
		 if(!in_array("true" , $selected_arr)){
			 return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>'));
		  }
	     /*Delete service weekly days and services pakages*/
		  $get_service_weekly_days = Service_weekly_days::get_workshop_services_days($request->service_id);
		  if($get_service_weekly_days->count() > 0){
			  $delete_services_weekly_days = Service_weekly_days::delete_weekly_days($request->service_id);
			  $delete_services_packages = \App\Services_package::delete_packages($request->service_id);
			}
		 /*End*/	  
		$flag = FALSE; 
		$service_id = $request->service_id;
        $data_arr = '';
		foreach($request->daysData as $day_data) {
		  if($day_data['selected'] == "true"){
			  $services_days_result = Service_weekly_days::create(array('users_id'=>Auth::user()->id ,
			                                                 'services_id'=>$service_id , 
															 'days_id'=>$day_data['day']
															 ));
               if($services_days_result){
                   $services_weekly_days_id = $services_days_result->id;
                   foreach($day_data['records'] as $record){
                       DB::table('services_packages')->insert(array('users_id'=>Auth::user()->id ,
					   'categories_id'=>$request->category_id ,
					   'services_id'=>$service_id ,                                                        
					   'services_weekly_days_id'=>$services_weekly_days_id ,
                                                              'start_time'=>$record['start_time'] , 
                                                              'end_time'=>$record['end_time'] , 
                                                              'price'=>$record['price'] , 
															  'max_appointment'=>$record['max_appointment'] ,
                                                              'created_at' => date('Y-m-d H:i:s') , 
									                          'updated_at' => date('Y-m-d H:i:s')
                                                              ));
			         $flag = TRUE;												  
                   }
                } 			 											 
			}
        }
		
	if($flag != FALSE){
        return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Services Packages Added Successfully !!!.</div>']);
    }
    else{
      return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>']);
    }
	   /*End*/	
	   	
	}
	
	public function post_action (Request $request , $action){
	    if($action == "add_car_wash_services"){
		  if(!empty($request->category_id)){
			 foreach($request->dataArr as $data_arr) {
			     $add_response = \App\Services::add_car_wash_service($data_arr , $request);
			  }
			  return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong> Success , </strong> Service Added Successfully !!!.</div>'));
			}
		  else{
			return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong> please select any one category !!!.</div>'));
			}	
	      }
	  
	    if($action == "add_services_new") {
			$duplicate_exixt = \App\Services::get_services_record($request->service_id ,  $request->car_size);
			if($duplicate_exixt != NULL){
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong> This service is already listed !!!.</div>'));
			}
			 $insert_result = \App\Services::add_services($request->about_services , $request->service_id , $request->service_average_time  , $request->car_size);
			$flag = TRUE;
			if($flag != FALSE){
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Services Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>']);
			}
		}
		
	if($action == "add_time_slot") {
			$selected_arr = [];
			foreach($request->daysData as $day_data) {
				$selected_arr[] = $day_data['selected'];
			}
			if(!in_array("true" , $selected_arr)){
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>'));
			}
			$get_service_weekly_days = Service_weekly_days::get_workshop_services_days($request->service_id);
			if($get_service_weekly_days->count() > 0){
				$delete_services_weekly_days = Service_weekly_days::delete_weekly_days($request->service_id);
				$delete_services_packages = \App\Services_package::delete_packages($request->service_id);
			}
			$flag = FALSE; 
			$service_details = \App\Services::find($request->service_id);
			//$monthly_date = date('d-m-Y H:i:s' , strtotime($record['monthly_date'] ));
			// echo "<pre>";
			// print_r($category_id->category_id);exit;
			$data_arr = '';
			foreach($request->daysData as $day_data) {
				if($day_data['selected'] == "true"){
					$services_days_result = Service_weekly_days::create(array('users_id'=>Auth::user()->id ,'services_id'=>$service_details->id , 'days_id'=>$day_data['day'] ));
					if($services_days_result){
						 $services_weekly_days_id = $services_days_result->id;
						 foreach($day_data['records'] as $record){
							$monthly_date = $record['monthly_date'] !== null ? date('Y-m-d H:i:s', strtotime($record['monthly_date'])) : null;
							
							DB::table('services_packages')->insert(
							         array('users_id'=>Auth::user()->id,
									       'categories_id'=>$service_details->category_id,
										   'services_id'=>$service_details->id,
										'services_weekly_days_id'=>$services_weekly_days_id ,
										   'start_time'=>$record['start_time'],
										   'end_time'=>$record['end_time'],
										   'discount_type'=>$record['discount_type'],  
										   'discount'=>$record['discount'],  
										   'special_time_slot_type'=>$record['special_time_slot_type'],
										   'monthly_date'=>$monthly_date,
										   'created_at'=>date('Y-m-d H:i:s'),
										   'updated_at' => date('Y-m-d H:i:s') ));
							$flag = TRUE;												  
						}
					} 			 											 
				}
			}
			if($flag != FALSE){
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Services Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>']);
			}
		}
		
	 $selected_arr = [];
	 foreach($request->daysData as $day_data) {
	    $selected_arr[] = $day_data['selected'];
	  }
	 if(!in_array("true" , $selected_arr)){
		 return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>'));
		}
		$flag = FALSE; 
		$duplicate_exixt = \App\Services::get_services_record($request->service_id ,  $request->car_size);
		if($duplicate_exixt != NULL){
		   return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong> This service is already listed !!!.</div>'));
		 }
        $insert_result = \App\Services::add_services($request->about_data , $request->service_id , $request->service_average_time  , $request->car_size);
		$service_id = $insert_result->id;
        $data_arr = '';
		foreach($request->daysData as $day_data) {
		  if($day_data['selected'] == "true"){
              $services_days_result = Service_weekly_days::create(array('users_id'=>Auth::user()->id ,
			                                                 'services_id'=>$service_id , 
															 'days_id'=>$day_data['day']
															 ));
               if($services_days_result){
                   $services_weekly_days_id = $services_days_result->id;
                   foreach($day_data['records'] as $record){
                       DB::table('services_packages')->insert(array('users_id'=>Auth::user()->id ,
					   'categories_id'=>$request->service_id ,
					   'services_id'=>$service_id ,                                                        
					   'services_weekly_days_id'=>$services_weekly_days_id ,
                                                              'start_time'=>$record['start_time'] , 
                                                              'end_time'=>$record['end_time'] , 
                                                              'price'=>$record['price'] , 
                                                              'max_appointment'=>$record['max_appointment'] ,
                                                              'created_at' => date('Y-m-d H:i:s') , 
									                          'updated_at' => date('Y-m-d H:i:s')
                                                              ));
			         $flag = TRUE;												  
                   }
                } 			 											 
			}
        }
	if($flag != FALSE){
        return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Services Added Successfully !!!.</div>']);
    }
    else{
      return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>']);
    }
	
   }
}
