<?php
namespace App\Http\Controllers\API;
use sHelper;
use apiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Http\Controllers\API\SpecialCondition;
use App\Http\Controllers\Coupon;
use App\Model\UserDetails;
use App\Products_order;
use App\Models;
use App\Model\Kromeda;
use App\Workshop_user_day_timing;
use App\Library\kromedaHelper;
use App\Library\orderHelper;



class UserDetail extends Controller{
    public $successStatus = 200;

    public function save_users_address(Request $request){
        if(Auth::check()){
            $save_users_details = \App\Address::create(['users_id'=>Auth::user()->id , 'address_1'=>$request->address_1, 'address_2'=>$request->address_2, 'address_3'=>$request->address_3, 'landmark'=>$request->landmark, 'zip_code'=>$request->zip_code, 'country_name'=>$request->country_name, 'state_name'=>$request->state_name, 'city_name'=>$request->city_name, 'latitude'=>$request->latitude, 'longitude'=>$request->longitude, 'status'=>1 , 'is_deleted'=>0]);
            if($save_users_details){
                return sHelper::get_respFormat(1 , "Address Save Successfully !!!", NULL, NULL); 
              }
            else{
                return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
              }  
          } 
        else{
            return sHelper::get_respFormat(1 , "You have to logged in first !!!", NULL, NULL);
         }   
      } 
    
   public function check_service_booking(Request $request){
       $validator = \Validator::make($request->all(), [
		      'package_id'=>'required|numeric'
	     ]);
            if($validator->fails()){
                return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
            }
		    //$get_package_details = \App\Services_package::package_details($request->package_id);
		    $get_package_details = Workshop_user_day_timing::find($request->package_id);
			if($get_package_details != NULL){
			   $get_busy_workshop = \App\ServiceBooking::get_busy_hour($request , $get_package_details);
			   if($get_busy_workshop->count() < 1){
			      return sHelper::get_respFormat(1 , "Booking Slot available." , null , null);    
				 } else {
				  return sHelper::get_respFormat(0 , "Slot not available." , null , null); 
				 }	 
			  } else {
		      return sHelper::get_respFormat(0 , "Please select correct package id." , null , null); 
			} 
   }   
   
   public function service_booking(Request $request){
       $coupon_obj = new Coupon;
	    $validator = \Validator::make($request->all(), [
		      'package_id'=>'required|numeric' , 'start_time'=>'required' , 'end_time'=>'required' , 'price'=>'required' , 
		      'selected_date'=>'required', 'car_size'=>'required',
		      'category_id'=>'required'
	     ]);
		if($validator->fails()){
             return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		 }
		if(Auth::check()){
            $s_time = sHelper::change_time_formate($request->start_time);
			$e_time = sHelper::change_time_formate($request->end_time);
            //check time and date from current date
            $current_time_zones = sHelper::get_current_time_zones($request->ip());
            date_default_timezone_set($current_time_zones);
            $today_current_date_time = date('Y-m-d H:i');
            $service_date_time = $request->selected_date." ".$request->start_time;
            if($service_date_time < $today_current_date_time){
                return sHelper::get_respFormat(0 , "Please select correct booking date for booking , you not booked services in past time ." , null , null); 		
            }
            $condition_stutus = 0 ;
            $special_condition = \App\Service_special_condition::get_special_condition(1 ,$request->workshop_id);            
            if($special_condition != NUll){
				$special_condition_value =[];
				$special_condition_apply_status = 0;
                foreach($special_condition as $special_conditions){
						if($special_conditions->operation_type == 1){
							if(!empty($special_conditions->all_services != 1)){	
							    if($special_conditions->category_id != $request->category_id){
								    $special_condition_apply_status = 0;
							    } else {
								    $special_condition_apply_status = 1;
							    }	
						    } else {
								    $special_condition_apply_status = 1;
						    }
                        $user_details = UserDetails ::find($request->selected_car_id);
						$obj = new SpecialCondition; 
						$special_condition_apply_status = $obj->match_maker($special_conditions , $user_details);
						if($special_condition_apply_status == 1){
							$special_condition_apply_status = $obj->match_model($special_conditions , $user_details);	
						} 
						if($special_condition_apply_status == 1) {
							$special_condition_apply_status = $obj->match_version($special_conditions , $user_details);
						}
						if($special_condition_apply_status == 1) {
							$special_condition_apply_status = $obj->match_types($request->start_time , $request->end_time , $request->selected_date , $special_conditions);
                        }
                        //count max appoinment
						$count_booked_appointment = \App\ServiceBooking::count_car_booked_special_package($request->package_id , $special_conditions->workshop_id , $special_conditions->id  , 1); 
						if($count_booked_appointment->count() == $special_conditions->max_appointement){
							$special_condition_apply_status  = 0;
						}
						if($special_condition_apply_status != 0){
						    $special_condition_value = $special_conditions;
						    break;
						 }
					 }	
				}
			}
		    //$get_package_details = \App\Services_package::package_details($request->package_id);
		    $get_package_details = Workshop_user_day_timing::find($request->package_id);
		    if($get_package_details == NULL){
		         return sHelper::get_respFormat(0 , "package is not defined !!! ." , null , null); 
		    }
            /*Get Workshop Service Appointment and hourly rate*/
		    $service_details = \App\Services::where([['category_id' , '=' , $request->category_id] , ['car_size', '=', $request->car_size] , ['users_id', '=', $get_package_details->users_id]])->first();
            if($service_details == NULL){
		       $workshop_service_price = \DB::table('workshop_service_payments')->where([['workshop_id' , '=' ,$get_package_details->users_id] , ['category_type' , '=' , 1]])->first();
		      if($workshop_service_price != NULL){
		          $max_appointment = $workshop_service_price->maximum_appointment;
		      } else{
		          $max_appointment = 1;
		      }
		    } else{
		          $max_appointment = $service_details->max_appointment;
		    }
		    /*End*/
		    if($s_time >= $get_package_details->start_time && $s_time <= $get_package_details->end_time){
		       if($e_time >= $get_package_details->start_time && $e_time <= $get_package_details->end_time){
		          if($get_package_details != NULL){
			        /*Count booked Appointment*/
			          //$count_booked_appointment = \App\ServiceBooking::get_booked_package($request->package_id , $request->selected_date , $request->car_size ,  1);
                      $count_booked_appointment = \App\ServiceBooking::where([['workshop_user_id' , '=' , $request->workshop_id] , ['car_size' , '=' , $request->car_size] , ['type' , '=' , 1]])->whereDate('booking_date' , $request->selected_date)->get();
                      if($count_booked_appointment->count() == $max_appointment){
        			         return sHelper::get_respFormat(0 , " All appointment of this package is completely booked !!! ." , null , null); 
        			    }
			   /*End*/
			   $get_busy_workshop = \App\ServiceBooking::get_busy_hour($request , $get_package_details);
               if($get_busy_workshop == NUll){
					$discount_price = 0;
					$special_id = 0;
                     if(!empty($special_condition_value)){
                           // find discount for rp/per
                           $special_id = $special_condition_value->id;
                            if($special_condition_value->discount_type == 1){
                                $discount_price = $special_condition_value->amount_percentage;
                            } else {
                                $discount_price = ($request->price/ 100) * $special_condition_value->amount_percentage;
                            }
                    }
                    if(!empty($request->coupon_id)){
                      $coupon_response = json_decode($coupon_obj->check_coupon_validity($request->coupon_id , $request->selected_date ,$request->price)); 
                      if($coupon_response->status != 200){
                            return sHelper::get_respFormat(0 ,$coupon_response->msg , null ,null);
                      }else{
                          //save coupon amount in user wallet
                          if($coupon_response->status == 200){
                              $save_coupon_amount = apiHelper::manage_registration_time_wallet(Auth::user() , $coupon_response->price ,"Car Washing service coupon.");
                            }
                      }
                    }
                    /*Manage Order id*/
                        $request->workshop_id = $get_package_details->users_id;
                        $order_manage = \App\Products_order::save_order($request , 0,0,null);
                        if($order_manage){
                            $request->order_id = $order_manage->id;
                        }
                    /*End*/
                    $service_vat = orderHelper::calculate_vat_price($request->price);
                    $after_discount_price = ($service_vat + $request->price) - $discount_price;
                    $booking_result = \App\ServiceBooking::add_booking($request , $get_package_details,$discount_price,$special_id,$service_vat , $after_discount_price);
                    //order id data
                    //$order_manage = \App\Products_order::save_order($request , $discount_price , $request->price , NULL , $after_discount_price);
                    if(!empty($request->servicequotes_id)){
                        /*soft update Days */
                            $update_response = \App\Servicequotes::where([['id' , '=' , $request->servicequotes_id]])->update(['workshop_id'=>$request->workshop_user_id]);
                        /*End*/
                    }
                     if($booking_result){
                       return sHelper::get_respFormat(1 , "Booking successfully !!! " , $booking_result ,null ); 
                      }
                     else{
                      return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
                     }  
				 } else {
				  return sHelper::get_respFormat(0 , "This time is already busy ." , null , null); 
				 }	 
			  } else{
		              return sHelper::get_respFormat(0 , "Please select correct package id ." , null , null); 
			        } 
		        } else {
		             return sHelper::get_respFormat(0 , "please check you end time !!! ." , null , null);  
		        }
		    } else {
		       return sHelper::get_respFormat(0 , "please check you start time !!! ." , null , null);   
		    }
		   } else { 
			return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
		   }  
	  } 
	  
	  
    public function addCar(Request $request) { 
        $validator = Validator::make($request->all(), [ 
            'carMakeName'  => 'required', 
            'carModelName' => 'required', 
            'carVersion'   => 'required',
			'carBody'	   => 'required',
        ]);
        if($validator->fails()) {
            $error=$validator->errors();
            return $this->respFormat(0,$error->first(),null,null);
        }
        $input=$request->all();
        $getMakeTest = Kromeda::where(array('url'=>'getModels/'.$input['carMakeName']))->first('response');
        $getModelsTest = Kromeda::where(array('url'=>'getVersion/'.$input['carModelName']))->first('response');
        // return $this->respFormat(0,'TEST',$getMake->result,$getModel);
        if($getMakeTest == null)
        {
           return $this->respFormat(0,'Wrong car make name',null,null);     
        }
        $getMake=json_decode($getMakeTest);
        $getMake=json_decode($getMake->response);
        $modelExists=0;
        //return $this->respFormat(0,$getMake->result[0],null,null);
        if(strlen($getMake->result[0])>0){
           return $this->respFormat(0,'Wrong car model name',null,null);
        }else{

            //return $this->respFormat(0,$getMake->result[1]->dataset,null,null);
            foreach($getMake->result[1]->dataset as $key=>$make){
                    if($make->idModello.'/'.$make->ModelloAnno==$input['carModelName']){
                        ++$modelExists;
                    }
                }
        }
        if($modelExists==0)
        {
            return $this->respFormat(0,'Wrong car model name',null,null);
        }
        $version=0;
//return $this->respFormat(0,'Wrong car model name',$getModelsTest,null);
        if($getModelsTest==null)
        {
           return $this->respFormat(0,'Wrong car model name',null,null);     
        }
        $getModels=json_decode($getModelsTest);
        $getModels=json_decode($getModels->response);
        if(strlen($getModels->result[0])>0){
           return $this->respFormat(0,'Wrong car version name',null,null);
        }else{
            foreach($getModels->result[1]->dataset as $key=>$versions){
                    if($versions->idVeicolo == $input['carVersion'])
                    {
                        ++$version;
                    }
                }
        }
        if($version==0)
        {
            return $this->respFormat(0,'Wrong car version name',null,null);
        }
          //  return $this->respFormat(0,UserDetails::addCar($input,1 , $request),null,null);
        $input['number_plate']  = NULL;
        if(UserDetails::addCar($input,1 , $request)){ 
            return $this->respFormat(1,'Car details saved successfully',null,UserDetails::carListInfo()); 
        } else { 
            return $this->respFormat(0,'Car already exists in your Virtual Garage',null,null);
       
        }  
       
    } 
    
    public function deleteCar(Request $request) { 
        $validator = Validator::make($request->all(), [ 
            'carId'  => 'required', 
        ]);
        if($validator->fails()) {
            $error=$validator->errors();
            return $this->respFormat(0,$error->first(),null,null);
        }
        $input=$request->all();
       
        if(UserDetails::deleteCar($input)){
            if(count(UserDetails::carListInfo())==0){
              return $this->respFormat(1,'',null,null);
            }
            else{
              return $this->respFormat(1,'',null,UserDetails::carListInfo());
            }
            
        }
        else { 
            return $this->respFormat(0,'Your details is mismatch in our records',null,null);
        }  
       
    }
    public function editCar(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'carId'        => 'required',
            'carMakeName'  => 'required', 
            'carModelName' => 'required', 
            'carVersion'   => 'required',  
        ]);
        if($validator->fails()) {
            $error=$validator->errors();
            return $this->respFormat(0,$error->first(),null,null);
        }
        $input=$request->all();
        $resp = UserDetails::editCar($input,$request);
        switch($resp){
            case 1:
               return $this->respFormat(0,'Your details is mismatch in our records',null,null);
            break;

            case 2:
               return $this->respFormat(0,'Car already exists',null,null);
            break;

            case 3:
                return $this->respFormat(1,'Car details saved successfully',null,UserDetails::carListInfo());
            break;
        }
        
    }

    public function carList(Request $request) { 
        set_time_limit(100000);
        $user_list = UserDetails::carList();
        return $this->respFormat(1,'',null, $user_list);
      
    } 

    
    public function carListInfo(){ 
        $users_cars = UserDetails::where([['user_id','=',Auth::user()->id] , ['deleted_at' , '=' ,NULL]])->get();
        if($users_cars->count() > 0){
            foreach($users_cars as $car){
                 /*GAllery image*/
                 $car->images = \App\Gallery::users_details_image($car->id);
                 /*End*/
                 /*match maker*/
                  $maker = kromedaHelper::get_makers(); 
                  if(count($maker) > 0){
                      $maker_obj = collect($maker);
                      $maker_response = $maker_obj->where('idMarca' , $car->carMakeName)->first();
                      if($maker_response != NULL){
                          $car->carMake = $maker_response;
                        }
                    }
                 /*End*/
                 /*match model*/
                  $models = kromedaHelper::get_models($car->carMakeName);
                  if(count($models) > 0){
                       if(!empty($car->carModelName)){
                            $model_arr = explode('/',$car->carModelName);
                          }
                       $model_obj = collect($models);
                       //['idModello','=',$model_arr[0]
                       $model_response = $model_obj->where('ModelloAnno',$model_arr[1])->where('idModello' , $model_arr[0])->first();
                       if($model_response != NULL){
                            $car->carModel = $model_response;
                         }
                    }
                 /*End*/
                 /*version matched*/
                 $versions = kromedaHelper::get_versions($model_arr[0] , $model_arr[1]);
                 if(count($versions) > 0){
                     $version_collection = collect($versions);
                     $versions_new = $version_collection->where('idVeicolo' , $car->carVersion)->first();
                     $car->carVers = $versions_new;
                 }
                 /*End*/
                 /*Get model default script start*/
                 $car->original_image = \sHelper::car_model_details($maker_response->Marca , $model_response->Modello);
             /*End*/
               }
            return sHelper::get_respformat(1, null, null, $users_cars);
          }
        else{
             return sHelper::get_respformat(1, null, null, $users_cars);
          }	
      }
  

    public function respFormat($stcode,$msg,$data,$data_set)
    {
      $resp=array();
      $resp['status_code']=$stcode;
      $resp['message']=$msg;
      $resp['data']=$data;
      $resp['data_set']=$data_set;
      return response()->json($resp, $this-> successStatus); 
    }
    
    
    	public function insert_product_order(Request $request){	
		$validator = \Validator::make($request->all(), [
		  'product_id'=>'required' , 'users_id'=>'required','seller_id'=>'required'
		]);
		if($validator->fails()){
		 return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
		}
		$input =$request->all();
		if($input['users_id']){
		$insert_products = Products_order::insert_products($input);
		 return sHelper::get_respFormat(1 , "insert successfully !." , null , null); 	
		}else{
			 return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
		}		
		}
}
