<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use sHelper;
use Auth;
use serviceHelper;
use DB;

class CarRevisionController extends Controller {
    //Get list of Revision services
    public $main_category = 2;
    public function car_revision_next_schedule(){
        if(Auth::check()){
            $response = \App\ServiceBooking::get_last_revision_service(Auth::user()->id , 3);
            if($response != NULL){
                  $response->next_revision_date = date('d-m-Y' , strtotime("+24 months", strtotime($response->booking_date)));
                  return sHelper::get_respFormat(1, "", null, $response);
              } else {
                return sHelper::get_respFormat(0, "No record found !!!", null, null);
              }	 
        } else {
            return sHelper::get_respFormat(0, "Please login first !!!", null, null);
        }
     }
      
     public function get_revision_services(Request $request) {
        $revision_services = \App\Category::where([['deleted_at' , '=' , NULL] , ['category_type' , '=' , $this->main_category] , ['status' , '=' , 0]])->get();
        $get_users = \App\Users_category::where([['categories_id' , '=' , $this->main_category], ['deleted_at', '=', NULL]])->get();
        if ($revision_services->count() > 0) {
            foreach($revision_services as $services){
                /*Store price in services */
                    $min_price = [];
                    if ($get_users->count() > 0) {
                        foreach($get_users as $user){
                            $profile_status = serviceHelper::get_profile_status($user->users_id);
                            if($profile_status == 100){
                                $get_price = \App\WorkshopCarRevisionServices::get_service_price($services->id , $user->users_id);
                                if($get_price != NULL){
                                    $min_price[] = $get_price->price;
                                }
                            }
                        }
                     $services->min_price = (string) min($min_price);
                     $services->main_category = $this->main_category;
                    }
                /*End*/
                /*Store multiple Images */
                    $images = null;
                    $get_images = \App\Gallery::get_car_revision_images($services->id);
                    if($get_images->count() > 0) {
                        $services->images = $get_images;
                    }
                /* End */
            }
            return sHelper::get_respFormat(1, " ", null, $revision_services);
        } else {
            return sHelper::get_respFormat(0, "Services Details not available !!! ", null, null);
        }
    }
    
    //List of workshops with Revision facility
    public function car_revision_workshop_list(Request $request) {
        $validator = \Validator::make($request->all(), [
            'selected_date'=>'required' , 'service_id' => 'required'
        ]);
        if($validator->fails()){
           return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
        }
        $minPrice = 0;
	    $maxPrice = 0;
        if (!empty($request->selected_date) && !empty($request->service_id)) {
            //Get users array with shop off days
            $off_selected_date = \App\Workshop_leave_days::get_valid_workshop($request);
			$users_arr = $off_selected_date->pluck('users_id');
            $off_days_workshop_users = $users_arr->all();
            //Get week days id from selected date
            $selected_days_id = \sHelper::get_week_days_id($request->selected_date);
            //Get users from user category
            $get_users = \App\Users_category::get_users();
            if($get_users->count() > 0){
                $all_selected_users = \App\Users_category::get_car_revision_services($off_days_workshop_users);
                $remove_users_arr = [];
                 $available_status = 0;
                if($all_selected_users->count() > 0){
                    foreach($all_selected_users as $users) {
                        $users->services_price = (string) 0;
                       
                        $workshop_user_days = \App\Workshop_user_day::get_workshop_user_days($users->users_id, $selected_days_id);
                        if($workshop_user_days == NULL) {
                            $remove_users_arr[] = $users->users_id;
                        }
                        //Get price
                        $get_price = \App\WorkshopCarRevisionServices::get_service_price($request->service_id , $users->users_id);
                        if($get_price == NULL) {
                            $remove_users_arr[] = $users->users_id;
                        } if($get_price == NULL) {
                            $get_price = \App\WorkshopServicesPayments::get_service_price_max($users->users_id , 2);    
                        }
                        /*Get 3 servive for the workshop */
                        $users->coupon_list = sHelper::get_coupon_list($users->users_id ,3 , $request->service_id,2 , $get_price->price);
                        /*end*/
                        $users->max_appointment = (string)$get_price->max_appointment;
                        //Get booked packages and get available status
                        $get_revision_weekly_days = \App\Workshop_user_day::get_service_weekly_days($users->users_id ,$selected_days_id);
                        if ($get_revision_weekly_days != null) {
                            $get_revision_packages = \App\Workshop_user_day_timing::get_packages($get_revision_weekly_days->id);
                            if ($get_revision_packages->count() > 0) {
                                if(empty($users->max_appointment)) $users->max_appointment = 1;
                                else $users->max_appointment = $users->max_appointment;
                               $booked_list = \App\ServiceBooking::get_service_booked_car_revision_package($users->users_id,$request->selected_date, $request->service_id, 3);
                                if($booked_list->count() < $users->max_appointment){
                                    $available_status = 1;
                                } else {
                                    $available_status = 0;
                                }   
                            }
                        }
                        $users->available_status = $available_status;
                        $users->services_price = (string)$get_price->price;
                        $users->service_id = $request->service_id;
                        $users->days_id = $selected_days_id;
                        $users->latitude = 0.0;
                        $users->longitude = 0.0;
                        $lat_long_details = \App\Address::get_primary_address($users->users_id);
                        if($lat_long_details != NULL) {
                            $users->latitude = $lat_long_details->latitude;
                            $users->longitude = $lat_long_details->longitude;
                        }
                        //Profile image URL
                        $users->profile_image_url = NULL;
                        if(!empty($users->profile_image)){
                            $users->profile_image_url = url("storage/profile_image/$users->profile_image");
                        }
                         /*Manage workshop feedback api */
				         $users = sHelper::manage_workshop_feedback_in_api($users , $users->users_id); 
				         /*End*/
                        $users->wish_list = 0;
                        if(!empty($request->user_id)){
                                $user_wishlist_status = \App\User_wish_list::get_user_wish_list_for_workshop($users->users_id , $request->user_id);
                                if($user_wishlist_status == 1){
                                    $users->wish_list = 1;
                                } else {
                                    $users->wish_list = 0;
                                }	
                        }

                    }
                    $all_selected_users = $all_selected_users->whereNotIn('users_id' , $remove_users_arr);
                    /*Get min price and max price*/
                        $minPrice = $all_selected_users->min('services_price');
                        $maxPrice = $all_selected_users->max('services_price');  
                    /*End*/
                    //Filter and sorting
                    if (!empty($request->rating)) {
                        $rating_arr = explode(',', $request->rating);
                        $all_selected_users =  $all_selected_users->whereBetween('rating_star', $rating_arr);
                    } else {
                       $all_selected_users->sortByDesc('rating_star'); 
                    }
                    if (!empty($request->price_range)) {
                        $price_arr = explode(',', $request->price_range);
                        $all_selected_users = $all_selected_users->whereBetween('services_price', $price_arr);
                    }
                    if (!empty($request->price_level)) {
                        if ($request->price_level == 1) {
                            $all_selected_users = $all_selected_users->sortBy('services_price')->values();
                        } else if ($request->price_level == 2) {
                            $all_selected_users = $all_selected_users->sortByDesc('services_price')->values();
                        }
                    } else {
                       $all_selected_users = $all_selected_users->sortBy('services_price')->values();
                    } 
                    $all_selected_users->map(function($users) use ($minPrice , $maxPrice){
                        $users->min_price = (string)$minPrice;
                        $users->max_price = (string)$maxPrice;
                        return $users;
                    }); 
                    return sHelper::get_respFormat(1, " ", null, $all_selected_users);
                }
            }
            
		} else {
			return sHelper::get_respFormat(0, " Please select any date  !!!. ", null, null);	
		}
    }

    //Get 30 days minimum price
    public function get_next_thirty_days_min_price(Request $request) {
        $validator = \Validator::make($request->all(), [
            'selected_date'=>'required', 'service_id' => 'required'
        ]);
		if($validator->fails()){
            return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
        }
        $min_price = [];
        $min_price1 = [];
        if (!empty($request->selected_date) && !empty($request->service_id)) {
            $selected_date = $request->selected_date;
            for ($i = 0; $i < 30; $i++) {
                $request->selected_date = date('Y-m-d', strtotime($selected_date . ' + ' . $i . ' day'));
                $off_days_workshop_users = [];
                if (!empty($request->selected_date)) {
                    $off_selected_date = \App\Workshop_leave_days::get_valid_workshop($request);
                    $users_arr = $off_selected_date->pluck('users_id');
                    $off_days_workshop_users = $users_arr->all();
                }
                //Get selected days
                $selected_days_id = \sHelper::get_week_days_id($request->selected_date);
                //Get users list
                $get_users = \App\Users_category::get_users();
                if($get_users->count() > 0){
                    $all_selected_users = \App\Users_category::get_car_revision_services($off_days_workshop_users);
                    $remove_users_arr = [];
                    if($all_selected_users->count() > 0){
                        foreach($all_selected_users as $users) {
                            $users->price = null;
                            $workshop_user_days = \App\Workshop_user_day::get_workshop_user_days($users->users_id, $selected_days_id);
                            if($workshop_user_days == NULL) {
                                $remove_users_arr[] = $users->users_id;
                            }
                            //Get price list
                            $get_price = \App\WorkshopCarRevisionServices::get_service_price($request->service_id , $users->users_id);
                            if($get_price == NULL) {
                                $get_price = \App\WorkshopServicesPayments::get_service_price_max($users->users_id , 2);
                                if($get_price == NULL) {
                                    $remove_users_arr[] = $users->users_id;
                                }
                            }
                            $min_price[] = $get_price->price;
                        }
                        $min_price1[] = array('date' => $request->selected_date, 'price'=>(string) min(array_filter($min_price, 'strlen')) );
                    }
                }
            }
            return sHelper::get_respFormat(1, " ", null, $min_price1);
        } else {
            return sHelper::get_respFormat(0, " Please select any date  !!!. ", null, null);
        }
    }

    public function get_car_revision_service_package($users_id, $service_id, $selected_date,$selected_car_id ,$user_id = 0){
        $max_appointment = 0;
        if(!empty($users_id)){
            if (!empty($service_id)) {
                if(!empty($selected_date)) {
                    //Get selected days id
                    $selected_days_id = \sHelper::get_week_days_id($selected_date);
                    //Get user details
                    $get_revision_details = \App\Users_category::get_car_revision_details($users_id);
                    //Get price for car revision service 
                    $get_price = \App\WorkshopCarRevisionServices::get_service_price($service_id , $users_id);
                    if($get_price == NULL) {
                        $get_price = \App\WorkshopServicesPayments::get_service_price_max($users_id , 2);
                    }
                    $get_revision_details->maximum_appointment = $get_price->max_appointment;
                    $get_revision_details->price = (string)$get_price->price;
                    $get_revision_details->profile_image_url = NULL;
                    $get_revision_details->service_id = $service_id;
                    $get_revision_details->services_price = (string)0;
                    if(!empty($get_revision_details->profile_image)){
                        $get_revision_details->profile_image_url = url("storage/profile_image/$get_revision_details->profile_image");
                    }
                    /*End*/
                    /*Rating API start*/
                    /*Manage workshop feedback api */
                    $get_revision_details = sHelper::manage_workshop_feedback_in_api($get_revision_details , $users_id); 
                    /*End*/
                    $get_revision_details->workshop_gallery = \App\Gallery::get_all_images($users_id);
                    /*End*/
                    $get_revision_details->package_list = null;
                    $get_services_weekly_days = null;
                    if ($get_revision_details != null) {
                        $get_revision_weekly_days = \App\Workshop_user_day::get_service_weekly_days($users_id ,$selected_days_id);
                        $packages_list = null;
                        if ($get_revision_weekly_days != null) {
                            $get_revision_packages = \App\Workshop_user_day_timing::get_packages($get_revision_weekly_days->id);
                            $max_appointment = $get_price->max_appointment;
                            $price = $get_price->price;
                            if ($get_revision_packages->count() > 0) {
                                $packages_list = $get_revision_packages;
                                $booked_package_id_arr = [];
                                $new_new_slot = [];
                                if(empty($max_appointment)) $max_appointment = 1;
                                    else $max_appointment = $max_appointment;
                               // $booked_list = \App\ServiceBooking::get_service_booked_car_revision_package($users_id,$selected_date, $service_id, 3);
                                $query = \App\ServiceBooking::where([['workshop_user_id' , '=', (int)$users_id],['status' ,'=' ,'C'] 
                                ,['services_id' ,'=' , $service_id] ,['type','=', 3]]); 
                                $query->whereDate('booking_date', $selected_date);
                                if(!empty($user_id)){
                                    $query->orWhere([['users_id' , '=' , $user_id] ,['status' ,'=', 'P'], ['status' ,'=' ,'CA'] ,['type' , '=' , 3]])->where('booking_date' ,'=' , $selected_date);
                                }
                                $booked_list = $query->get();
                                if($booked_list->count() < $max_appointment){
                                    $available_status = 1;
                                } else {
                                    $available_status = 0;
                                }
                                if($get_revision_packages->count() > 0){
                                    $get_revision_packages->map(function ($packages) use($max_appointment , $price, $available_status) {
                                        $packages['maximum_appointment'] = $max_appointment;
                                        $packages['price'] = (string) $price;
                                        $packages['available_status'] = $available_status;
                                        return $packages;
                                    });
                                }
                                $filtered = $get_revision_packages->whereNotIn('id', $booked_package_id_arr);
                                $new_slot = $filtered->all();
                                $get_revision_details->package_list = array_merge($new_new_slot, $new_slot);
                                $get_revision_details->min_price = null;
                                $get_revision_details->max_price = null;
                              
                            }
                        }
                        return sHelper::get_respFormat(1, " ", $get_revision_details, null);
                    } else {
                        return sHelper::get_respFormat(0, " Car Revision Details are not available !!! ", null, null);
                    }
                } else {
                    return sHelper::get_respFormat(0 ,"Please select any date!!", null, null);
                }
            } else {
                return sHelper::get_respFormat(0 ,"Please Enter the car revision service id!!", null, null);
            }
        } else {
            return sHelper::get_respFormat(0 ,"Please Enter user id!!", null, null);
        }
    }
}
