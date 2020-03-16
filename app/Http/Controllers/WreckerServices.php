<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use sHelper;

class WreckerServices extends Controller{

    public function post_action(Request $request, $action) { 
        /*if($action == "edit_wrecker_service_details") {
            $validator = \Validator::make($request->all(), [
                'edit_time_arrives' => 'required','service_hourly_rate' => 'required','cost_per_km' => 'required'
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            if(!empty($request->service_id)) {
                if($request->service_call_price == NULL) {
                    $call_cost = 0;
                } else {
                    $call_cost = $request->service_call_price;
                }
                $id = $request->service_id;
                $service = \App\WorkshopWreckerServices::where('id', $id)->first();
                $service->time_arrives_15_minutes = $request->edit_time_arrives;
                $service->call_price = $call_cost;
                $service->hourly_cost = $request->service_hourly_rate;
                $service->cost_per_km = $request->cost_per_km;
                $res = $service->save();
                if($res){
                    return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Service Details Updated successfully !!! </div>'));
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
                }
            }
        }*/
        if($action == "edit_wrecker_service_details") {
            $validator = \Validator::make($request->all(), [
                'wrecker_service_name' => 'required', 'service_time_arrives', 'emergency_time_arrives' => 'required','service_hourly_rate' => 'required', 'emergency_hourly_rate' => 'required', 'emergency_hourly_rate' => 'required', 'emergency_hourly_rate' => 'required', 'emergency_service_call_price' => 'required', 'service_max_appointment' => 'required',
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            $workshop_wrecker_service = \App\WorkshopWreckerServices::add_or_edit_services($request);
            if($workshop_wrecker_service) {
                if($request->service_service_call_price == NULL ) {
                    $service_call_price = 0;
                } else {
                    $service_call_price = $request->service_service_call_price;
                }
                if($request->emergency_max_appointment == NULL ) {
                    $emergency_max_appointment = 0;
                } else {
                    $emergency_max_appointment = $request->emergency_max_appointment;
                }
                $service_by_appointment = \App\WorkshopWreckerServiceDetails::add_Service_by_appointment_details($request, $workshop_wrecker_service->id, $service_call_price);
                $emergency_details = \App\WorkshopWreckerServiceDetails::add_emergency_Service_details($request, $workshop_wrecker_service->id, $emergency_max_appointment);
                if(!empty($service_by_appointment) && !empty($emergency_details)) {
                    return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Wrecker Service Details Added successfully !!! </div>'));
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
                }
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
            }
        }
        if($action == "add_wrecker_service_details") {
            $validator = \Validator::make($request->all(), [
                'service_name' => 'required', 'time_arrives', 'hourly_rate' => 'required','distance_cost' => 'required'
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }

            $wrecker_service_name = \App\WorkshopWreckerServices::get_wracker_services_name($request->service_name);

            if($wrecker_service_name ->count() > 0) {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Service Already Exist !!! </div>'));
            } else {
                if($request->call_price == NULL) {
                    $call_cost = 0;
                } else {
                    $call_cost = $request->call_price;
                }
                $service_details = \App\WorkshopWreckerServices::add_wrecker_services($request, $call_cost);
                if($service_details){
                    return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Service Details Added successfully !!! </div>'));
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
                }
            }
            
        }
        if($action == "upload_category_image") {
            if(count($request->cat_file_name) > 0){
                $category_images = $this->upload_category_image($request); 	  
                if(count($category_images) > 0){
                    $category_result =  \App\WrackerServices::edit_category_image($request->category_id , $category_images[0]); 
                    if($category_result){
                        foreach($category_images as $image){
                            $insert_category = \App\Gallery::add_wrecker_service_gallery($image , $request->category_id);
                        }
                        if($category_result != NULL){
                            return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Image uploaded successfully !!! </div>'));
                        } else {
                            return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
                        }	
                    }
                }
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong>Please Select at least one image  !!! </div>'));
            }	 
        }
        if($action == "add_wracker_service"){
            $validator = \Validator::make($request->all(), [
                'service_name' => 'required', 'weight_type_1' => 'required','time_per_km' => 'required', 'loading_unloading' => 'required', 'description' => 'required'
               
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            if(!empty($request)){
				$category_image = $this->upload_category_image($request); 
				if($category_image != 111){
                    $result = \App\WrackerServices::add_wracker_services($request, $category_image[0]);
                    if($request->cat_file_name){
                        foreach($category_image as $image){
                            $insert_category = \App\Gallery::add_wrecker_service_gallery($image , $result->id);
                        }
                    }
					if($result != NULL){
						return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
					} else {	
						return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
					} 
				} else {
					echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Wrong </strong> only , JPG , JPEG , PNG format supported !!! </div>')); 
				}
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong>Something went wrong , please try again .</div>' , "status"=>100));
			}
        }
    }

    public function get_action(Request $request, $action) {

        if($action == "remove_wrecker_service") {
            if(!empty($request->service_id)) {
                $wrecker_service_detail = \App\WrackerServices::where('id', $request->service_id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
                if($wrecker_service_detail) {
					return json_encode(array(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Service Deleted Successfully !!!.</div>']));
				} else {
					return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
				}
            } else {
				return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
			}
		}
        
        if($action == "view_wrecker_service_details") {
            if(!empty($request->serviceId)) {
                $result = \App\WorkshopWreckerServices::get_wrecker_service_details($request->serviceId);
                if($result != NULL){
                    $wrecker_service = \App\WrackerServices::get_wrecker_service($result->wracker_services_id);
					?>
					<table class="table table-bordered">
						<tr>
							<th rowspan="2">Service Type</th>
                            <th rowspan="2">Time Arrives&nbsp;(in minutes)</th>
                            <td rowspan="2">Max. Appointment</td>
							<th colspan="3">Weight Type 1 Cost</th>
							<th colspan="3">Weight Type 2 Cost</th>
						</tr>
						<tr>
							<th>Hourly Cost</th>
							<th>Cost/Km</th>
							<th>Call Price</th>
							<th>Hourly Cost</th>
							<th>Cost/Km</th>
							<th>Call Price</th>
						</tr>
						<tr>
                            <th>Service By Appointment</th>
                            <td><?php echo $result->total_time_arrives; ?></td>
                            <td><?php echo $result->max_appointment; ?></td>
                            <td><?php echo $result->hourly_cost; ?></td>
                            <td><?php echo $result->cost_per_km; ?></td>
                            <td>0</td>
                            <?php
                                if($wrecker_service->type_of_weight_2000_3000 != 0) {
                                    $call_price = 0;
                                    $hourly_cost = (20/100)*$result->hourly_cost;
                                    $cost_per_km = (20/100)*$result->cost_per_km;
                                } else {
                                    $call_price = 0;
                                    $hourly_cost = 0;
                                    $cost_per_km = 0;
                                }
                            ?>
                            <td><?php echo $call_price; ?></td>
                            <td><?php echo $hourly_cost; ?></td>
                            <td><?php echo $cost_per_km; ?></td>
                        </tr>
						<tr>
                            <th>Emergency Service</th>
                            <td><?php echo $result->e_total_time; ?></td>
                            <td>0</td>
                            <td><?php echo $result->e_hourly_cost; ?></td>
                            <td><?php echo $result->e_cost_per_km; ?></td>
                            <td><?php echo $result->e_call_cost; ?></td>
                            <?php
                                if($wrecker_service->type_of_weight_2000_3000 != 0) {
                                    $e_call_price = (80/100)*$result->e_call_cost;
                                    $e_hourly_cost = (80/100)*$result->e_hourly_cost;
                                    $e_cost_per_km = (80/100)*$result->e_cost_per_km;
                                } else {
                                    $e_call_price = 0;
                                    $e_hourly_cost = 0;
                                    $e_cost_per_km = 0;
                                }
                            ?>
                            <td><?php echo $e_call_price; ?></td>
                            <td><?php echo $e_hourly_cost; ?></td>
                            <td><?php echo $e_cost_per_km; ?></td>
                        </tr>
					</table>
					<?php
					exit;
				}
				else{
                    ?>
                    <table class="table">
                        <tr>
                            <td>Details not Avilable</td>
                        </tr>
                    </table>
                    <?php
				}
            }
        }
        if($action == "get_wrecker_service_details") {
            // echo "<pre>";
            // print_r($request->all());exit;
            if(!empty($request->serviceId)) {
                $result = \App\WorkshopWreckerServices::get_wrecker_service_details($request->serviceId);
                if($result != NULL) {
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
        }
        if($action == "get_category_details") {
            if(!empty($request->categoryId)) {
                $result = \App\WrackerServices::get_wracker_services_details($request->categoryId);
                if($result){
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
        }
        if($action == "change_wracker_service_status") {
            if(!empty($request->categoryId)){
                $result = \App\WrackerServices::find($request->categoryId);
                if($result != NULL){
                    $result->status = $request->status;
                    if($result->save()){
                        echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
                    }else{
                        echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
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
        if($action == "get_sos_image"){  
            if(!empty($request->category_id)){
                  $images = \App\Gallery::get_wrecker_images($request->category_id);
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
                                                 <a href='#' data-imageid="<?php echo $image->id; ?>" data-categoryid="<?php if(!empty($image->category_id)) echo $image->category_id; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_sos_images">
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
             else{
               echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
               }
        }
        if($action == "remove_image") {
            $image_details = \App\Gallery::find($request->delete_id);
            if($image_details != NULL){
                    $delte_img = \App\Gallery::where('id' ,'=' ,$request->delete_id)->update(['deleted_at'=>date('Y-m-d h:i:s')]);
                    $image_arr = \App\Gallery::get_sos_image($request->category_id);
                    if($image_arr->count() > 0){
                        $image_name = $image_arr[0]->image_name;
                        $newimage_url = url("storage/category/$image_name");
                        $result_image = \App\WrackerServices::find($request->category_id);
                        $result_image->cat_images = $image_name;
                        $result_image->cat_image_url  = $newimage_url;
                        $result_image->save();
                    }   
            }

        }

        if($action == "change_workshop_wrecker_status") {
            if(!empty($request->serviceId)){
                $result = \App\WorkshopWreckerServices::find($request->serviceId);
                if($result != NULL){
                    $result->status = $request->status;
                    if($result->save()){
                        echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
                    }else{
                        echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
                    } 
                }
                else{
                    echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
                }	 
            } else {
                echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
            }
        }
        if($action == "get_wrecker_details") {
            if(!empty($request->serviceId)){
                $result = \App\WorkshopWreckerServices::get_wracker_details($request->serviceId);
                // echo "<pre>";
                // print_r($result);exit;
                if($result){
                    return json_encode(['status'=>200 , "result"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
        }
    }
}