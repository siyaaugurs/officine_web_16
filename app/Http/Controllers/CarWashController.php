<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\WorkshopServicesPayments;
use App\Service_weekly_days;
use Auth;
use DB;
use sHelper;

class CarWashController extends Controller{
    public $car_size_arr = [1=>'Small' , 2=>'Average' , 3=> 'Big'];
   
   /*Edit Car washing hourly rate and max appointment single*/
	public function car_wash_edit_service_details(Request $request) {
		$validator = \Validator::make($request->all() , [
			'category_id' => 'required', 'car_size' => 'required',
			'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
			'hourly_rate'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0']);
		if($validator->fails()){
			return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
		}
		$result = \App\Services::car_wash_deatails($request);
		if($result){
			return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Service Details Updated successfully !!! </div>'));
		} else {
			return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
		}
	}
	/*End*/
   
   public function car_wash_time_slot(Request $request ){
      if(!empty($request->service_id) && !empty($request->car_size)){
		   $response = \App\Services::add_services($request->service_id , $request->car_size);
		   if($response){
			    $selected_arr = [];
				foreach($request->daysData as $day_data) {
					$selected_arr[] = $day_data['selected'];
				}
				if(!in_array("true" , $selected_arr)){
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>'));
				}
			$get_service_weekly_days = \App\Service_weekly_days::get_workshop_services_days($response->id);
		    if($get_service_weekly_days->count() > 0){
				$delete_services_weekly_days = Service_weekly_days::delete_weekly_days($response->id);
				$delete_services_packages = \App\Services_package::delete_packages($response->id);
			}
			$flag = FALSE; 
			$data_arr = '';
			foreach($request->daysData as $day_data) {
				if($day_data['selected'] == "true"){
					$services_days_result = Service_weekly_days::create(array('users_id'=>Auth::user()->id ,'services_id'=>$response->id , 'days_id'=>$day_data['day']));
					if($services_days_result){
						 $services_weekly_days_id = $services_days_result->id;
						 foreach($day_data['records'] as $record){
							DB::table('services_packages')->insert(
							         array('users_id'=>Auth::user()->id,
									       'categories_id'=>$response->category_id,
										   'services_id'=>$response->id,
										'services_weekly_days_id'=>$services_weekly_days_id ,
										   'start_time'=>$record['start_time'],
										   'end_time'=>$record['end_time'],
										   'created_at'=>date('Y-m-d H:i:s'),
										   'updated_at' => date('Y-m-d H:i:s') ));
							$flag = TRUE;												  
						}
					} 			 											 
				}
			}
			return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved successfully  !!!.</div>')); 
		  }
		}
	  else{
	      return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  !!!.</div>')); 
		}
	   
	}
   
   public function get_action(Request $request , $action){
      /*Get Washing service details script start */
		  if($action == "get_washing_details"){
			  $result = WorkshopServicesPayments::get_service_price_max(Auth::user()->id , 1);              if($result != NULL){
				  return json_encode(array('status'=>200 , 'response'=>$result));
				}
			  else{
				  return json_encode(array('status'=>100)); 
				}	 
			 }
		/*End*/ 
       
      /*Save and update car wash */
		if($action == "workshop_carwash_details"){
			if(!empty($request->hourly_rate) && !empty($request->max_appointment)){
				$validator = \Validator::make($request->all(), [
					'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
					'hourly_rate'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
				]);
				if($validator->fails()){
					return json_encode(array( "error"=> $validator->errors()->getMessages(), "status" => 400));
				}
			    $response = WorkshopServicesPayments::save_car_wash_update($request);
				$get_car_washing = sHelper::get_subcategory(1);
				$data['car_size'] = $this->car_size_arr;
				if($get_car_washing->count() > 0){
					foreach($get_car_washing as $car_washing_service){
					   	foreach($this->car_size_arr as $key=>$value)
						  	$response = \App\Services::save_update($car_washing_service , $key, $request);
					}
				}
			   	if($response){
					return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong> Success , </strong> Record saved successfully !!!.</div>'));
				} else {
				  return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
				} 	   
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
			}	
		}
		/*End*/
		/*Get Single car washing service details*/
		if($action == "get_service_details") {
			if(!empty($request->service_id)) {
				$services = \App\Services::get_service_details(Auth::user()->id , $request->service_id , $request->size);
				if($services){
                    return json_encode(['status'=>200 , "response"=>$services]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
			}
		}
		/*End*/
	}   
}
