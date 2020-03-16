<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service_weekly_days;
use Auth;
use DB;

class AssembleController extends Controller{
    
    public function post_action(Request $request, $action) {
        if($action == "edit_all_assemble_service_details") {
			$validator = \Validator::make($request->all(), [
            	'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
				'hourly_cost'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$response = \App\WorkshopServicesPayments::workshop_assemble_service_details($request);
			$assemble_services = \App\Users_category::spare_services_categories(Auth::user()->id);
			$request->category_id = NULL;
			$request->description = NULL;
			foreach($assemble_services as $key => $spare_groups) {
				$request->category_id = $spare_groups->categories_id;
				$request->description = $spare_groups->description;
				$insert_result = \App\WorkshopAssembleServices::update_assemble_services($request);
			}
			if($insert_result){
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Record Updated successfully !!! </div>'));
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
			}
		}
		if($action == "add_assemble_services") {
			$duplicate_exixt = \App\Services::get_assemble_record(Auth::user()->id ,  $request->inventory_product);
			if($duplicate_exixt != NULL){
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong> This service is already listed !!!.</div>'));
			}
			$products_details = \App\ProductsNew::get_product($request->inventory_product);
			// echo "<pre>";
			// print_r($products_details->products_groups_items_id);exit;
			if($products_details == NULL){
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Wrong please try again !!! .</div>']);
			}
			$insert_result = \App\Services::add_assemble_services($request ,$products_details->products_groups_items_id);
			$flag = TRUE;
			if($flag != FALSE){
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Services Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
		if($action == "add_assemble_time_slot") {
			// return $request;exit;
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
			$service_id = $request->service_id;
			$category_id = \App\Services::get_category($service_id);
			/* echo "<pre>";
			print_r($category_id->category_id);exit; */
			$data_arr = '';
			foreach($request->daysData as $day_data) {
				if($day_data['selected'] == "true"){
					$services_days_result = Service_weekly_days::create(array('users_id'=>Auth::user()->id , 'services_id'=>$service_id , 'days_id'=>$day_data['day'] ));
					if($services_days_result){
						$services_weekly_days_id = $services_days_result->id;
						foreach($day_data['records'] as $record){
							$monthly_date = $record['monthly_date'] !== null ? date('Y-m-d H:i:s', strtotime($record['monthly_date'])) : null;
							DB::table('services_packages')->insert(array('users_id'=>Auth::user()->id ,
									'categories_id'=>$category_id->category_id ,
									'services_id'=>$service_id ,
									'type'=>2 ,                                                        
									'services_weekly_days_id'=>$services_weekly_days_id ,
									'start_time'=>$record['start_time'] , 
									'end_time'=>$record['end_time'] , 
									'price'=>$record['price'] , 
									'max_appointment'=>$record['max_appointment'] ,
									'discount_type'=>$record['discount_type'] ,
									'discount'=>$record['discount'] ,
									'special_time_slot_type'=>$record['special_time_slot_type'] ,
									'monthly_date'=> $monthly_date,
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
			} else {
			  	return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>']);
			}
		}
		if($action == "edit_assemble_services") {
			// return $request;exit;
			if(!empty($request->services_id)){
				if(!empty($request->average_time)){
					$result = \App\Services::find($request->services_id);
					// echo "<pre>";
					// print_r($result);exit;
					$result->about_services = nl2br($request->about_services);
					$result->service_average_time = $request->average_time;
					$result->products_id = $request->product_id;
					if( $result->save() ){
						echo '<div class="notice notice-success"><strong>Success , </strong> Record save successful .</div>';exit;
					} else {
						echo '<div class="notice notice-danger"><strong>Wrong , </strong> something went wrong , please try again  .</div>';exit;
					} 
				} else {
					echo '<div class="notice notice-danger"><strong>Wrong , </strong> Please Enter the service Average timing .</div>';exit;
				}		
				}
		}
		if($action == "edit_assemble_service_details") {
			// return $request;
			$validator = \Validator::make($request->all(), [
                /* 'description' => 'required', */ 
				'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
				'hourly_cost'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$insert_result = \App\WorkshopAssembleServices::update_assemble_services($request);
			if($insert_result){
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Record Added successfully !!! </div>'));
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
			}
		}
	}
   
   
  /* public function add_services(Request $request){
	 $selected_arr = [];
	 foreach($request->daysData as $day_data) {
	    $selected_arr[] = $day_data['selected'];
	  }
	 if(!in_array("true" , $selected_arr)){
		 return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>'));
		}
		$flag = FALSE; 
		$products_details = \App\Products::find($request->products_id);
		if($products_details == NULL){
		   return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Wrong please try again !!! .</div>']);
		 }
		
        $insert_result = \App\Services::add_assemble_services($request , $products_details->category_id);
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
					   'categories_id'=>$products_details->category_id ,
					   'services_id'=>$service_id ,
					   'type'=>2 ,                                                        
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
   }*/
  
   public function get_action(Request $request, $action) {
		if($action == "get_assemble_category_details") {
			
			if(!empty($request->id)) {
				$user_category_details = \App\Users_category::find($request->id);
				if($user_category_details != NULL){
				  $result = \App\WorkshopAssembleServices::get_assemble_service_details($user_category_details); 	
					if($result){
						return json_encode(['status'=>200 , "response"=>$result]);
					} 
					else if($result == NULL) {
						return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
					} 
				} else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
			}

		}
	}
}
