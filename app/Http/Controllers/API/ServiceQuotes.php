<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use sHelper;
use serviceHelper;
use \App\Users_category;
use DB;
use App\Feedback;
use Auth;

class ServiceQuotes extends Controller{

    public function check_time_slot_available_for_service_quotes($request){
          echo "<pre>";
          print_r($request->all());
    }

    public function workshop_package_for_service_quotes(Request $request){
        $validator = \Validator::make($request->all(), [
            'category_type'=>'required', 'workshop_user_days_id'=>'required','workshop_user_id'=>'required', 'selected_date'=>'required','service_quotes_inserted_id'=>'required','main_category_id'=>'required'
			]);
            if($validator->fails()){
                return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
            }
        $selected_day = sHelper::get_week_days_id($request->selected_date);
        $user_detail =  \App\User::find($request->workshop_user_id);
        if($user_detail != NULL){
            $user_detail->category_id = $request->category_type;
            $user_detail->main_category_id = (int) $request->main_category_id;
            $user_detail->rating =  $user_detail->rating_star = $user_detail->rating_count =  $user_detail->profile_image_url = $user_detail->days_id  = $user_detail->services_price = $user_detail->service_average_time = $user_detail->products_id = $user_detail->status = $user_detail->package_list = NULL;
            $workshop_timing_response = sHelper::workshop_time_slot($request->selected_date , $request->workshop_user_id);
                 if($workshop_timing_response->count() > 0){
                    $user_detail->days_id =  $selected_day;
                    $sos_workshop_service_detail =  DB::table('service_quotes_details')->where([['main_category_id' , '=' , $request->category_type] , ['status' , '=' , 'A'] , ['deleted_at' ,'=' , NULL]])->first();
                    if($sos_workshop_service_detail != NULL){
                        if(!empty($user_detail->profile_image)){
                          $user_detail->profile_image_url = url("storage/profile_image/$user_detail->profile_image");
                        }
                        /*manage slots */
                          $booked_number_of_appointment = DB::table('service_bookings')->whereDate('booking_date' , $request->selected_date)
                          ->where([['type' , '=' , 7] , ['workshop_user_id','=',$request->workshop_user_id] , ['services_id' , '=' ,$request->category_type] , ['deleted_at' , '=' , NULL]])                                                             
                          ->get();
                          $available_status = 1;
                          if($booked_number_of_appointment->count() >= $sos_workshop_service_detail->max_appointment){
                             $available_status = 0;
                          }
                        $new_booked_arr = [];
                        foreach($workshop_timing_response as $slot){
                          /*   echo "<pre>";
                            print_r($slot);exit; */
                            $new_slots_available = [];
                            $new_slots_available[0] = sHelper::change_time_format_2($slot->start_time);
                            $new_slots_available[1] = sHelper::change_time_format_2($slot->end_time);
                            $new_slots_available['start_time'] = sHelper::change_time_format_2($slot->start_time);
                            $new_slots_available['end_time'] = sHelper::change_time_format_2($slot->end_time);
                            $new_slots_available['price'] = null;
                            $new_slots_available['hourly_price'] = null;
                            $new_slots_available['id'] = $slot->id;
                            $new_slots_available['available_status'] = $available_status;
                           $new_booked_arr[] = $new_slots_available;
                        }
                        /*End*/
                        $user_detail->package_list = $new_booked_arr;
                        /*in the case of service quotes hourly rate not matter */
                        $user_detail->hourly_rate = 0;
                        $user_detail->max_appointment = $sos_workshop_service_detail->max_appointment;
                        /*Manage Rating*/
                        $user_detail = sHelper::manage_workshop_feedback_in_api($user_detail , $request->workshop_user_id);  
                        /*Check Time Slot available or not*/
                        //$user_detail->available_status = serviceHelper::check_time_slot_for_service_quotes($request , $sos_workshop_service_detail);
                        /*End*/
                        /*Workshop gallery image manage*/
                        $user_detail->workshop_gallery = \App\Gallery::get_all_images($request->workshop_user_id);
                        $user_detail->service_images = null;
                        /*End*/
                        return sHelper::get_respFormat(1, null , $user_detail , null); 
                        /*End*/                                
                    }
                 }                              
          
           }

    }

    public function service_booking_request_quotes(Request $request){
        $validator = \Validator::make($request->all(), [
         'selected_date'=>'required', 'category_type'=>'required','service_quotes_inserted_id'=>'required','main_category_id'=>'required',
         'selected_car_id'=>'required','workshop_id'=>'required' 
        ]);
        if($validator->fails()){
            return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
        }
        if(Auth::check()){
            $workshop_service_detail =  DB::table('service_quotes_details')->where([['main_category_id' , '=' , $request->category_type] , ['status' , '=' , 'A'] , ['deleted_at' ,'=' , NULL]])->first();
            if($workshop_service_detail != NULL){
                $selected_days_id = sHelper::get_week_days_id($request->selected_date);
                $workshop_days = DB::table('workshop_user_days')->where([['users_id' , '=' , $request->workshop_id] ,['common_weekly_days_id' , '=' , $selected_days_id] ,  ['deleted_at' , '=' , NULL]])->first();
                $booked_services = \App\ServiceBooking::where([['type' , '=' , 7] ,['workshop_user_id' , '=' ,$request->workshop_id] ,  ['services_id' , '=' , $request->category_type]])->whereDate('booking_date' , $request->selected_date)->get();
                if($booked_services->count() < $workshop_service_detail->max_appointment){
                               
                                /*apply coupon script start*/
                                $coupon_id = NULL;
                                if(!empty($request->coupon_id)){
                                    $coupon_response = \serviceHelper::apply_coupon($request);
                                    if(!empty($coupon_response['coupon_id'])){
                                        $coupon_id = $coupon_response['coupon_id'];
                                        //sHelper::make_discount_price();
                                    }
  
                                }
                                /*End*/
                                 /*manage order id*/
                                    $order_manage = \App\Products_order::save_order($request);
                                    if($order_manage){
                                        $request->order_id = $order_manage->id;
                                    }
                                /*End*/
                                $booking_result = \App\ServiceBooking::create(['users_id'=>Auth::user()->id,
                                                'product_order_id'=>(int) $request->order_id,
                                                'workshop_user_id'=>(int) $request->workshop_id,
                                                'services_id'=>$request->category_type,
                                                'booking_date'=>$request->selected_date,
                                                'workshop_user_days_id' =>$workshop_days->id,
                                                'workshop_user_day_timings_id'=>$request->package_id,
                                                'coupons_id'=>$coupon_id,
                                                'start_time'=>$request->start_time,
                                                'servicequotes_id'=>$request->service_quotes_inserted_id,
                                                'status'=>'P',
                                                'type'=>7,
                                               ]);
                        if($booking_result){
                            $update_service_quotes = DB::table('servicequotes')->where([['id' , '=' , $request->service_quotes_inserted_id]])->update(['workshop_id'=>$request->workshop_id , 'service_booking_date'=>$request->selected_date]);
                            return sHelper::get_respFormat(1 , "Booking successfully !!! " , $booking_result ,null ); 
                        } else{
                            return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
                        }  
                } else{
                    return sHelper::get_respFormat(0 , "All appointment completely booked  !!!." , null , null); 
                }	                
            }
            else{
                return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
            }
          
            }
         else{
            return sHelper::get_respFormat(0 , "Unauthenticate , please login first ." , null , null); 
        }
    }
    
    public function request_quotes_workshops($request , $off_days_workshop_users){
        $workshops_arr =  $remove_workshop_user = [];
        $selected_day = sHelper::get_week_days_id($request->selected_date);
        $workshops = DB::table('users_categories as a')
        ->join('users_categories as b' , 'b.users_id' , 'a.users_id')
        ->where([['a.categories_id' , '=' , $request->main_category_id] , ['a.deleted_at' , '=' , NULL] , ['b.categories_id' , '=' ,$request->category_type] , ['b.deleted_at' , '=' , NULL]])
                        ->whereNotIn('a.users_id' , $off_days_workshop_users)            
                        ->get();
         if($workshops->count() > 0){
             foreach($workshops as $workshop){ 

            
                $workshop_profile_status = serviceHelper::get_profile_status($workshop->users_id); 
                    if($workshop_profile_status == 100){

                       $user_detail =  \App\User::get_workshop_details($workshop->users_id);
                       if($user_detail != NULL){
                        $user_detail->created_at = $workshop->created_at;
                        $user_detail->updated_at = $workshop->updated_at;
                        $user_detail->users_id = $workshop->users_id;
                        $user_detail->category_id = $request->category_type;
                        $user_detail->main_category_id = (int) $request->main_category_id;
                        $user_detail->service_quotes_inserted_id = (int) $request->service_quotes_inserted_id;
                        $user_detail->rating =  $user_detail->rating_star = $user_detail->rating_count =  $user_detail->profile_image_url = $user_detail->days_id  = $user_detail->services_price = $user_detail->service_average_time = $user_detail->products_id = $user_detail->status = $user_detail->package_list = NULL;
                        $user_detail->coupon_list = [];
                        $user_detail->latitude = $user_detail->longitude = 0.0;
                        $address_detail = \App\Address::get_primary_address($workshop->users_id);
                        if($address_detail != NULL){
                            $user_detail->latitude = $address_detail->latitude;
                            $user_detail->longitude = $address_detail->longitude; 
                        }
                       
                         $workshop_user_day_response = DB::table('workshop_user_days')->where([['common_weekly_days_id' , '=' , $selected_day] , ['users_id' , '=' , $workshop->users_id] , ['deleted_at' , '=' , NULL]])->first();
                         if($workshop_user_day_response != NULL){
                             $workshop_timing_response = DB::table('workshop_user_day_timings')->where([['workshop_user_days_id' , '=' , $workshop_user_day_response->id] , ['deleted_at' , '=' , NULL]])->get();
                             if($workshop_timing_response->count() > 0){
                                $user_detail->days_id =  $selected_day;
                                $user_detail->workshop_user_days_id =  $workshop_user_day_response->id;
                                $sos_workshop_service_detail =  DB::table('service_quotes_details')->where([['main_category_id' , '=' , $request->category_type] , ['status' , '=' , 'A'] , ['deleted_at' ,'=' , NULL]])->first();
                                if($sos_workshop_service_detail != NULL){
                                    if(!empty($user_detail->profile_image)){
                                      $user_detail->profile_image_url = url("storage/profile_image/$user_detail->profile_image");
                                    }
                                    $user_detail->hourly_rate = (string) $sos_workshop_service_detail->hourly_cost;
                                    $user_detail->max_appointment = $sos_workshop_service_detail->max_appointment;
                                    /*Manage Rating*/
                                    $user_detail = sHelper::manage_workshop_feedback_in_api($user_detail , $workshop->users_id);
                                    /*Check Time Slot available or not*/
                                    $user_detail->available_status = serviceHelper::check_time_slot_for_service_quotes($request , $sos_workshop_service_detail);
                                    /*End*/
                                    /*Find Coupon list*/
                                    $user_detail->coupon_list = sHelper::get_coupon_list($workshop->users_id , 7 , $request->main_category_id);
                                    /*End*/
                                    $workshops_arr[] = $user_detail;
                                    /*End*/                                
                                }
                             }                              
                         } 
                       }
                    }
             }
             if(count($workshops_arr) > 0){
                 return json_encode(['status'=>200 , 'response'=>$workshops_arr]);
             }
             else{
                return json_encode(['status'=>404]);
             }
         }else{
             return json_encode(['status'=>404]);
         }
    }

    public function request_quotes_workshop(Request $request){
        $validator = \Validator::make($request->all(), [
            'category_type'=>'required','selected_date'=>'required','service_quotes_inserted_id'=>'required','main_category_id'=>'required'
			]);
            if($validator->fails()){
                return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
            }
        //$main_category_id = 25;
        //$request->main_category_id = $main_category_id;
        $off_days_workshop_users = sHelper::get_off_users_on_date($request->selected_date);
        /*Get Car Washing workshops */
           $workshops = json_decode($this->request_quotes_workshops($request , $off_days_workshop_users));
          
           if($workshops->status == 200){
                /*Get Min and max price*/
               
      
                    $workshop_collection = collect($workshops->response);
                    $minPrice = $workshop_collection->min('hourly_rate');
                    $maxPrice = $workshop_collection->max('hourly_rate'); 
                    $workshop_collection->map(function($workshop) use ($minPrice , $maxPrice){
                        $workshop->min_price = $minPrice;
                        $workshop->max_price = $maxPrice;
                        return $workshop;
                    }); 	  
                /*End*/ 
                /*Order values */
                if (!empty($request->price_range)) {
                    $price_arr = explode(',', $request->price_range);
                    $workshop_collection = $workshop_collection->whereBetween('services_price', $price_arr);
                }
                if (!empty($request->price_level)) {
                    if ($request->price_level == 1) {
                        $workshop_collection = $workshop_collection->sortBy('hourly_rate')->values();
                    }
                    else if ($request->price_level == 2) {
                     $workshop_collection = $workshop_collection->sortByDesc('hourly_rate')->values();
                    }
                  }
                else{
                   $workshop_collection = $workshop_collection->sortBy('hourly_rate')->values();
                  } 
                /*End*/
                if (!empty($request->rating)) {
                    $rating_arr = explode(',', $request->rating);
                    $workshop_collection =  $workshop_collection->whereBetween('rating_star', $rating_arr);
                }
                else{
                   $workshop_collection->sortByDesc('rating_star'); 
                }
                 
                return sHelper::get_respFormat(1, null , null, $workshop_collection); 
            }
            else if($workshops->status == 404){
                return sHelper::get_respFormat(0, "No Workshop  available !!!", null, null); 
            }
       /*End*/        
    }

     /*Get Next Seven days api */
     public function next_seven_days_request_quotes(Request $request){
        $main_category_id = 25;
        $validator = \Validator::make($request->all(), [
            'selected_date'=>'required' , 'category_type'=>'required']);
        if($validator->fails()){
            return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
        }
        $min_price_arr = [];
      /*Selected workshop those are off this selected days and dates*/
      $selected_date = $request->selected_date;
      $request->main_category_id = $main_category_id;
      for($i = 0; $i < 30; $i++) {
          $request->selected_date = date('Y-m-d', strtotime($selected_date . ' + ' . $i . ' day'));
          $off_days_workshop_users = sHelper::get_off_users_on_date($request->selected_date);
          $workshops = json_decode($this->request_quotes_workshops($request , $off_days_workshop_users));
          if($workshops->status == 200){
              $workshop_collection = collect($workshops->response);
                if($workshop_collection->count() > 0){
                   $min_price = $workshop_collection->min('hourly_rate');
                  }
                else{
                    $min_price = 0;
                }  
          }
          else{
            $min_price = 0;
          }
          $min_price_arr[] = array('date'=>$request->selected_date, 'price'=>(string) $min_price);
      }
      return sHelper::get_respFormat(1, " ", null, $min_price_arr);
    }
    /*End*/


    public function service_quotes(Request $request){	
        $main_category_id = 25;
        $request->main_category_id = $main_category_id;
        if(Auth::check()){
            $validator = \Validator::make($request->all(), [
                'category_type'=>'required','button_type'=>'required'
             ]);
            if($validator->fails()){
                 return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
             }
             $result = \App\Servicequotes::add_service_quotes($request);
             if($result){
                 $result->main_category_id = $main_category_id;
                 /* echo "<pre>";
                 print_r($request->images);exit; */
                 if(!empty($request->images)){
                    $image_arr = $this->upload_images($request);	
                   // echo "<pre>";
                   // print_r($image_arr);exit;			
                      if(!empty($image_arr)){
                              foreach($image_arr as $image){
                                  $insert_category = \App\Advertising_image::add_service_quotes_gallery($image , $result->id);
                              }      
                          }
                  }
                return sHelper::get_respFormat(1, 'Insert successfully !!!', $result, null);  
             }
        }
        else{
            return sHelper::get_respFormat(0 , "First you have to logge in !!!" , NULL, NULL); 
        }
      }

    
}
