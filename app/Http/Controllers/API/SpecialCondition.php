<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service_special_condition;
use App\Model\UserDetails;
use DB;
use sHelper;

class SpecialCondition extends Controller{
    
    

    public function match_maker($s_condition , $user_car_detail){
        $special_condition_apply_status = 0;
        if(!empty($s_condition->makers)){
            if($s_condition->makers == 1){
               $special_condition_apply_status = 1;   
            }
            else{
               if($s_condition->makers == $user_car_detail->carMakeName){
                  $special_condition_apply_status = 1;   
               } 
               else{ $special_condition_apply_status = 0;  }   
            }
        }
        return $special_condition_apply_status;
    }

    public function match_model($s_condition , $user_car_detail){
        $special_condition_apply_status = 0;
        if(!empty($s_condition->models)){
            if($s_condition->models == 1){
            $special_condition_apply_status = 1;   
            }
            else{
            if($s_condition->models == $user_car_detail->carModelName){
                $special_condition_apply_status = 1;   
            } 
            else{ $special_condition_apply_status = 0;  }   
            }
       }
       return $special_condition_apply_status;
    }

    public function match_version($s_condition , $user_car_detail){
        $special_condition_apply_status = 0;
        if(!empty($s_condition->versions)){
            if($s_condition->versions == "all"){
               $special_condition_apply_status = 1;   
            }
            else{
                if($s_condition->versions == $user_car_detail->carVersion){
                    $special_condition_apply_status = 1;   
                } 
                else{ $special_condition_apply_status = 0;  }   
            }
       }
       return $special_condition_apply_status;
    }

    public function match_max_appointment($s_condition){
        $used_special_condition = DB::table('service_bookings')->where([['special_condition_id' , '=' , $s_condition->id]])->get(); 
        if($used_special_condition->count() <= $s_condition->max_appointement){
            $special_condition_apply_status = 1;
        }
        else{
            $special_condition_apply_status = 0;
        }
        return $special_condition_apply_status;
    }

    public function match_types($start_time , $end_time , $selected_date ,  $s_condition){
        $s_time = sHelper::change_time_formate($start_time);
        $e_time = sHelper::change_time_formate($end_time);
        
        if($s_time >= $s_condition->start_hour && $s_time <= $s_condition->end_hour){
            if($e_time >= $s_condition->start_hour && $e_time <= $s_condition->end_hour){
                /*Check types*/
                if($s_condition->select_type == 1){
                    //if($request->selected_date >= $s_condition->start_date &&  $request->selected_date <= $s_condition->expiry_date){
                        $special_condition_apply_status = 1;   
                    //}
                    //else{  $special_condition_apply_status = 0;  }
                }
                else if($s_condition->select_type == 2){
                    //if($request->selected_date >= $s_condition->start_date &&  $request->selected_date <= $s_condition->expiry_date){
                        //$special_condition_apply_status = 1;   
                        $days_id =  \sHelper::get_week_days_id($selected_date);
                        /*Get Special days */
                        $special_days_active = DB::table('special_condition_days')->where([['service_special_conditions_id' , '=' , $s_condition->id] , ['days_id' , '=' , $days_id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->first();
                        /*End*/
                        if($special_days_active != NULL){
                            $special_condition_apply_status = 1;
                        }
                        else{  $special_condition_apply_status = 0; }
                    //}
                    //else{ $special_condition_apply_status = 0; }  
                }
                else if($s_condition->select_type == 3){
                    $selected_day_of_the_date = sHelper::find_day_from_date($selected_date);
                    $selected_day_of_the_condition = sHelper::find_day_from_date($s_condition->start_date);
                    if($selected_day_of_the_date == $selected_day_of_the_condition){
                        $special_condition_apply_status = 1;
                    }
                    else{   $special_condition_apply_status = 0; }
                }
                else if($s_condition->select_type == 4){
                    $selected_day_of_the_date  = sHelper::find_day_from_date($selected_date);
                    $selected_month_of_the_date = sHelper::find_month_from_date($selected_date);
                    /*get special condition date ,month */
                    $con_date = sHelper::find_day_from_date($s_condition->start_date);
                    $con_month = sHelper::find_month_from_date($s_condition->start_date);
                    /*End*/
                    if($selected_day_of_the_date == $con_date && $selected_month_of_the_date == $con_month){
                        $special_condition_apply_status = 1; 
                    }
                    else{ $special_condition_apply_status = 0;  }
                }
            /*End*/
        }
        else{  $special_condition_apply_status = 0; }
        }
        else{  $special_condition_apply_status = 0; }
       
        return $special_condition_apply_status;      
    }

    
    public static function match_types_for_do_not_performed($selected_date ,  $s_condition){
        if($s_condition->select_type == 1){
                $special_condition_apply_status = 1;   
        }
        else if($s_condition->select_type == 2){
           
                $days_id =  \sHelper::get_week_days_id($selected_date);
                /*Get Special days */
                $special_days_active = DB::table('special_condition_days')->where([['service_special_conditions_id' , '=' , $s_condition->id] , ['days_id' , '=' , $days_id] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->first();
                /*End*/
                if($special_days_active != NULL){
                    $special_condition_apply_status = 1;
                }
                else{  $special_condition_apply_status = 0; }
        }
        else if($s_condition->select_type == 3){
            $selected_day_of_the_date = sHelper::find_day_from_date($selected_date);
            $selected_day_of_the_condition = sHelper::find_day_from_date($s_condition->start_date);
            if($selected_day_of_the_date == $selected_day_of_the_condition){
                $special_condition_apply_status = 1;
            }
            else{   $special_condition_apply_status = 0; }
        }
        else if($s_condition->select_type == 4){
            $selected_day_of_the_date  = sHelper::find_day_from_date($selected_date);
            $selected_month_of_the_date = sHelper::find_month_from_date($selected_date);
            /*get special condition date ,month */
            $con_date = sHelper::find_day_from_date($s_condition->start_date);
            $con_month = sHelper::find_month_from_date($s_condition->start_date);
            /*End*/
            if($selected_day_of_the_date == $con_date && $selected_month_of_the_date == $con_month){
                $special_condition_apply_status = 1; 
            }
            else{ $special_condition_apply_status = 0;  }
        }
       return $special_condition_apply_status;  
    }

    
    public function match_service_and_all_services($s_condition , $service_id){
        if($s_condition->all_services == 1){
            $special_condition_apply_status = 1;
        }
        else{
            if($s_condition->category_id == $service_id){
                $special_condition_apply_status = 1;
            }else{
                $special_condition_apply_status = 0;
            }
        }
        return $special_condition_apply_status;
    }
    
    public function match_all_condition($request , $special_conditions , $user_car_detail){
        if($special_conditions->count() > 0){
            foreach($special_conditions as $s_condition){
                $special_condition_apply_status = 0;
                
                $special_condition_apply_status = $this->match_max_appointment($s_condition);
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $user_car_detail);
                    }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
                }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
                }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
                }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_types($request->start_time , $request->end_time , $request->selected_date , $s_condition);
                }
                /*Break loop if special condition matched*/
                if($special_condition_apply_status == 1){
                    return json_encode(['status'=>200 , 'special_response'=>['special_condition_id'=>$s_condition->id , 'discount_amount'=>$s_condition->amount_percentage , 'discount_type'=>$s_condition->discount_type]]);  
                    //$special_condition_arr = $s_condition;
                break;
                }
                /*End*/
            } 
            return json_encode(['status'=>100 , 'special_response'=>['special_condition_id'=>NULL, 'discount_amount'=>NULL , 'discount_type'=>NULL]]); 
            
        }
        else{
            return json_encode(['status'=>100 , 'special_response'=>['special_condition_id'=>NULL, 'discount_amount'=>NULL , 'discount_type'=>NULL]]); 
        } 
    }

    /*match spacial condition for tyre service */
    public function match_tyre_service_for_special_condition($request){
        $user_car_detail = UserDetails::find($request->selected_car_id);
        $special_conditions = DB::table('service_special_conditions')->where([['main_category_id' , '=' , $request->main_category_id] , ['users_id' , '=' ,$request->workshop_id]])
                                        ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date] , 
                                        ['deleted_at' , '=' , NULL]])
                                        ->get();
        return $this->match_all_condition($request , $special_conditions , $user_car_detail);                               
    }
    /*End*/

     
    public function match_wracker_service_for_emergency($request){
        $main_category_id = 13;
        $special_condition_data = [];
        $user_car_detail = UserDetails::find($request->selected_car_id);
        $special_conditions = DB::table('service_special_conditions')->where([['main_category_id' , '=' , $main_category_id] ,
         ['operation_type' , '=' , 1]])->whereIn('wracker_service_type' , [0,2])
                                        ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date] , 
                                        ['deleted_at' , '=' , NULL]])
                                        ->get();
       
            if($special_conditions->count() > 0){
                foreach($special_conditions as $s_condition){
                    $special_condition_apply_status = 0;
                    
                    $special_condition_apply_status = $this->match_max_appointment($s_condition);
                    if($special_condition_apply_status == 1){
                        $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $user_car_detail);
                     }
                    if($special_condition_apply_status == 1){
                       $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
                    }
                    if($special_condition_apply_status == 1){
                        $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
                    }
                    if($special_condition_apply_status == 1){
                        $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
                    }
                    if($special_condition_apply_status == 1){
                        $special_condition_apply_status = $this->match_types($request->start_time , $request->end_time , $request->selected_date , $s_condition);
                    }
                    /*Break loop if special condition matched*/
                    if($special_condition_apply_status == 1){
                        return json_encode(['status'=>200 , 'special_response'=>['special_condition_id'=>$s_condition->id , 'discount_amount'=>$s_condition->amount_percentage , 'discount_type'=>$s_condition->discount_type]]);  
                        //$special_condition_arr = $s_condition;
                    break;
                    }
                    /*End*/
                } 
                return json_encode(['status'=>100 , 'special_response'=>['special_condition_id'=>NULL, 'discount_amount'=>NULL , 'discount_type'=>NULL]]); 
               
            }
            else{
                return json_encode(['status'=>100 , 'special_response'=>['special_condition_id'=>NULL, 'discount_amount'=>NULL , 'discount_type'=>NULL]]); 
            }
    }


    

    
    public function match_wracker_service_special_condition($request){
       $main_category_id = 13;
       $special_condition_data = [];
       /*Get car detail*/
       $user_car_detail = UserDetails::find($request->selected_car_id);
       /*End*/ 
       /*Get All special condition*/
       $special_condition_arr = [];
       $special_conditions = DB::table('service_special_conditions')->where([['main_category_id' , '=' , $main_category_id] , ['operation_type' , '=' , 1]])
                                    ->whereIn('wracker_service_type' , [0,1])
                                    ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date] , 
                                    ['deleted_at' , '=' , NULL]])
                                    ->get();
       if($special_conditions->count() > 0){
          foreach($special_conditions as $s_condition){
             $special_condition_apply_status = 0;
             $special_condition_apply_status = $this->match_max_appointment($s_condition);
              if($special_condition_apply_status == 1){
                 $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $request->service_id);
              }
              if($special_condition_apply_status == 1){
                 $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
              } 
              if($special_condition_apply_status == 1){
                  $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
              }
              if($special_condition_apply_status == 1){
                  $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
              }
              if($special_condition_apply_status == 1){
                  $special_condition_apply_status = $this->match_types($request->start_time , $request->end_time , $request->selected_date , $s_condition);
              }
            /*Break loop if special condition matched*/
              if($special_condition_apply_status == 1){
                return json_encode(['status'=>200 , 'special_response'=>['special_condition_id'=>$s_condition->id , 'discount_amount'=>$s_condition->amount_percentage , 'discount_type'=>$s_condition->discount_type]]);  
                //$special_condition_arr = $s_condition;
               break;
              }
            /*End*/
          } 
        return json_encode(['status'=>100 , 'special_response'=>['special_condition_id'=>NULL, 'discount_amount'=>NULL , 'discount_type'=>NULL]]); 
       }
       else{
              return json_encode(['status'=>100 , 'special_response'=>['special_condition_id'=>NULL, 'discount_amount'=>NULL , 'discount_type'=>NULL]]); 
       }
       /*End*/
    }
     
  
    /*do not match special condition for tyre */
     /*match vhicle tyre for tyre special condition*/ 
     public function match_tyre_vhicle_type($s_condition , $tyre_detail){
        $special_condition_apply_status = 0;  
        if($s_condition->vehicle_type == "all"){
            $special_condition_apply_status = 1;  
        }
        else{
            $vhicle_type_response = DB::table('master_tyre_measurements')->where([['id' , '=' , $s_condition->vehicle_type]])->first(); 
            if($vhicle_type_response != NULL){
                $special_condition_apply_status = 1;  
            }
            else{
                $special_condition_apply_status = 0;  
            }
        }
        return $special_condition_apply_status;
      }
     /*End*/
     
    /*match all condition for do not perform operation */
    public function match_all_condition_for_do_not_operation($request , $special_conditions , $user_car_detail){
        $non_working_slot = [];
        if($special_conditions->count() > 0){
            $user_car_detail = UserDetails::find($request->selected_car_id);
            foreach($special_conditions as $s_condition){
                $special_condition_apply_status = 0;
                $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $request->service_id);
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
                } 
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
                }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
                }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_types_for_do_not_performed($request->selected_date , $s_condition);
                }
                if($special_condition_apply_status == 1){
                        $non_working_slot[] = [$s_condition->start_hour , $s_condition->end_hour];
                }
            } 
              return json_encode(['status'=>200 , 'response'=>$non_working_slot]);
        }
        else{
            return json_encode(['status'=>100 , 'response'=>$non_working_slot]); 
        }   
     }
     /*End*/


      /*do not match special condition match for mot   */
      public function do_not_perform_operation_for_mot($request , $opening_slot){
        $user_car_detail = UserDetails::find($request->selected_car_id);
        $special_conditions = DB::table('service_special_conditions')
                                    ->where([['main_category_id' , '=' , $request->main_category_id] , ['operation_type' , '=' , 2] ,
                                    ['users_id' , '=' ,$request->workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                    ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date]]) 
                                    ->get();  
        return $this->match_all_condition_for_do_not_operation($request , $special_conditions , $user_car_detail);                                                      
     }
     /*End*/

     public function do_not_perform_operation_for_tyre($request , $opening_slot , $tyre_detail ,  $service_average_time = NULL){
        $non_working_slot = [];
        // print_r($request->main_category_id);exit;
          $special_conditions = DB::table('service_special_conditions')
                                     ->where([['main_category_id' , '=' , $request->main_category_id] , ['operation_type' , '=' , 2] ,
                                      ['users_id' , '=' ,$request->workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                     ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date] , ['season_type' , '=' , $tyre_detail->type]]) 
                                     ->get();
            if($special_conditions->count() > 0){
                    $user_car_detail = UserDetails::find($request->selected_car_id);
                    foreach($special_conditions as $s_condition){
                        $special_condition_apply_status = 0;
                        $special_condition_apply_status = $this->match_tyre_vhicle_type($s_condition , $tyre_detail);
                        if($special_condition_apply_status == 1){
                            $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $request->service_id);
                        } 
                        if($special_condition_apply_status == 1){
                            $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
                        } 
                        if($special_condition_apply_status == 1){
                            $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
                        }
                        if($special_condition_apply_status == 1){
                            $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
                        }
                        if($special_condition_apply_status == 1){
                            $special_condition_apply_status = $this->match_types_for_do_not_performed($request->selected_date , $s_condition);
                        }
                        if($special_condition_apply_status == 1){
                                $non_working_slot[] = [$s_condition->start_hour , $s_condition->end_hour];
                        }
                    } 
                return json_encode(['status'=>200 , 'response'=>$non_working_slot]);
        }
        else{
            return json_encode(['status'=>100 , 'response'=>$non_working_slot]); 
        }                             
     } 
    /*End*/


   /*Match Special condition for emergency*/
   public function do_not_perform_operation_for_emergency($request , $opening_slot ,  $service_average_time = NULL){
    $non_working_slot = [];
    if($service_average_time != NULL){
        $time_in_min = $service_average_time * 60;
        $end_time = sHelper::get_next_time($request->start_time , $time_in_min);
        $special_conditions = DB::table('service_special_conditions')
                                    ->where([['main_category_id' , '=' , $request->main_category_id] , ['operation_type' , '=' , 2] , ['users_id' , '=' ,$request->workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                    ->whereIn('wracker_service_type' , [0,2])
                                    ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date] ]) 
                                    //->where([['start_hour' , '>=' , $start_time] , ['end_hour' , '<=', $end_time]])
                                    ->get();
    } else {
        $start_time = $opening_slot[0];
        $end_time = $opening_slot[1];
        $special_conditions = DB::table('service_special_conditions')
                                            ->where([['main_category_id' , '=' , $request->main_category_id] , ['operation_type' , '=' , 2] , ['users_id' , '=' ,$request->workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                            ->whereIn('wracker_service_type' , [0,2])
                                            ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date] ]) 
                                            ->where([['start_hour' , '>=' , $start_time] , ['end_hour' , '<=', $end_time]])
                                            ->get();
    }
        if($special_conditions->count() > 0){
            $user_car_detail = UserDetails::find($request->selected_car_id);
            foreach($special_conditions as $s_condition){
                $special_condition_apply_status = 0;
                    $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $request->service_id);
                    if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
                } 
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
                }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
                    }
                    if($special_condition_apply_status == 1){
                        $special_condition_apply_status = $this->match_types_for_do_not_performed($request->selected_date , $s_condition);
                    }
                    if($special_condition_apply_status == 1){
                            $non_working_slot[] = [$s_condition->start_hour , $s_condition->end_hour];
                    }
            } 
                return json_encode(['status'=>200 , 'response'=>$non_working_slot]);
        }
        else{
            return json_encode(['status'=>100 , 'response'=>$non_working_slot]); 
        }
                                             
                                      
}
/*End*/

public function do_not_perform_operation_sp_cond($request , $workshop_id , $time_slot , $for_workshop_list = 1){
    $user_car_detail = UserDetails::find($request->selected_car_id);
    $main_category_id = $request->main_category_id;
    /*Get car detail*/
    $special_condition_arr =  $non_working_slot = [];
    if($for_workshop_list == 1){
        $special_conditions = DB::table('service_special_conditions')
        ->where([['main_category_id' , '=' , $main_category_id] , ['operation_type' , '=' , 2] , ['users_id' , '=' ,$workshop_id] ,  ['deleted_at' , '=' , NULL]])
        ->whereIn('wracker_service_type' , [0,1])
        ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date]])
        //->where([['start_hour' , '>=' , $time_slot_start_time] , ['end_hour' , '<=', $time_slot_end_time]])
        ->get();
    }
    else{
        $time_slot_start_time =  sHelper::change_time_formate($time_slot->start_time);
        $time_slot_end_time =  sHelper::change_time_formate($time_slot->end_time);
        $special_conditions = DB::table('service_special_conditions')
                                            ->where([['main_category_id' , '=' , $main_category_id] , ['operation_type' , '=' , 2] , ['users_id' , '=' ,$workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                            ->whereIn('wracker_service_type' , [0,2])
                                            ->where([['start_date' , '<=' , $request->selected_date] , ['expiry_date','>=' ,$request->selected_date] , 
                                        ])
                                        ->where([['start_hour' , '>=' , $time_slot_start_time]])
                                        ->get();
    }


    if($special_conditions->count() > 0){
        foreach($special_conditions as $s_condition){
            $special_condition_apply_status = 0;
             $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $request->service_id);
             if($special_condition_apply_status == 1){
                $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
            } 
            if($special_condition_apply_status == 1){
                $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
            }
            if($special_condition_apply_status == 1){
                $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
             }
             if($special_condition_apply_status == 1){
                 $special_condition_apply_status = $this->match_types_for_do_not_performed($request->selected_date , $s_condition);
             }
             if($special_condition_apply_status == 1){
                     $non_working_slot[] = [$s_condition->start_hour , $s_condition->end_hour];
             }
        } 
          return json_encode(['status'=>200 , 'response'=>$non_working_slot]);
    }
    else{
        return json_encode(['status'=>100 , 'response'=>$non_working_slot]); 
    }
    /*End*/
}


     public function do_not_perform_operation_for_car_maintenance($main_category_id,$selected_date , $workshop_id , $time_slot ,$car_id,$service_id,$check_time_slot = NULL){
        $non_working_slot = [];
        if($check_time_slot != NULL){
       // $time_in_min = $service_time * 60;
      //  $end_time = sHelper::get_next_time($request->start_time , $time_in_min);
        $user_car_detail = UserDetails::find($car_id);
        $special_condition_arr = [];
        $special_conditions = DB::table('service_special_conditions')
                                    ->where([['main_category_id' , '=' , $main_category_id] , ['operation_type' , '=' , 2] , ['users_id' , '=' ,$workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                    ->where([['start_date' , '<=' , $selected_date] , ['expiry_date','>=' ,$selected_date] ]) 
                                    //->where([['start_hour' , '>=' , $start_time] , ['end_hour' , '<=', $end_time]])
                                    ->get();

        }else{
			
        $time_slot_start_time =  sHelper::change_time_formate($time_slot->start_time);
        $time_slot_end_time =  sHelper::change_time_formate($time_slot->end_time);
        /*Get car detail*/
        $user_car_detail = UserDetails::find($car_id);
        $special_condition_arr = [];
        $special_conditions = DB::table('service_special_conditions')
                                        ->where([['main_category_id' , '=' , $main_category_id] , ['operation_type' , '=' , 2] , ['workshop_id' , '=' ,$workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                        ->where([['start_date' , '<=' , $selected_date] , ['expiry_date','>=' ,$selected_date]])
                                    ->where([['start_hour' , '>=' , $time_slot_start_time] , ['end_hour' , '<=', $time_slot_end_time]])
                                    ->get();
        }        
        if($special_conditions->count() > 0){
            foreach($special_conditions as $s_condition){
                $special_condition_apply_status = 0;
                 $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $service_id);
                 if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
                } 
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
                }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
                 }
                 if($special_condition_apply_status == 1){
                     $special_condition_apply_status = $this->match_types_for_do_not_performed($selected_date , $s_condition);
                 }
                 if($special_condition_apply_status == 1){
                         $non_working_slot[] = [$s_condition->start_hour , $s_condition->end_hour];
                 }
            }
              return json_encode(['status'=>200 , 'response'=>$non_working_slot]);
        }
        else{
            return json_encode(['status'=>100 , 'response'=>$non_working_slot]); 
        }

        /*End*/
    }
	
	 public function do_not_perform_operation_for_car_assemble($main_category_id,$selected_date , $workshop_id , $time_slot ,$car_id , $check_time_slot = NULL){
        $non_working_slot = [];
        if($check_time_slot != NULL){
       // $time_in_min = $service_time * 60;
      //  $end_time = sHelper::get_next_time($request->start_time , $time_in_min);
        $user_car_detail = UserDetails::find($car_id);
        $special_condition_arr = [];
        $special_conditions = DB::table('service_special_conditions')
                                    ->where([['main_category_id' , '=' , $main_category_id] , ['operation_type' , '=' , 2] , ['users_id' , '=' ,$workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                    ->where([['start_date' , '<=' , $selected_date] , ['expiry_date','>=' ,$selected_date] ]) 
                                    //->where([['start_hour' , '>=' , $start_time] , ['end_hour' , '<=', $end_time]])
                                    ->get();

        }else{
			
        $time_slot_start_time =  sHelper::change_time_formate($time_slot->start_time);
        $time_slot_end_time =  sHelper::change_time_formate($time_slot->end_time);
        /*Get car detail*/
        $user_car_detail = UserDetails::find($car_id);
        $special_condition_arr = [];
        $special_conditions = DB::table('service_special_conditions')
                                        ->where([['operation_type' , '=' , 2] , ['workshop_id' , '=' ,$workshop_id] ,  ['deleted_at' , '=' , NULL]])
                                        ->where([['start_date' , '<=' , $selected_date] , ['expiry_date','>=' ,$selected_date]])
                                    ->where([['start_hour' , '>=' , $time_slot_start_time] , ['end_hour' , '<=', $time_slot_end_time]])
                                    ->get();
        }        
        if($special_conditions->count() > 0){
            foreach($special_conditions as $s_condition){
                $special_condition_apply_status = 0;
                 $special_condition_apply_status = $this->match_service_and_all_services($s_condition , $main_category_id);
                 if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_maker($s_condition , $user_car_detail);
                } 
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_model($s_condition , $user_car_detail);
                }
                if($special_condition_apply_status == 1){
                    $special_condition_apply_status = $this->match_version($s_condition , $user_car_detail);
                 }
                 if($special_condition_apply_status == 1){
                     $special_condition_apply_status = $this->match_types_for_do_not_performed($selected_date , $s_condition);
                 }
                 if($special_condition_apply_status == 1){
                         $non_working_slot[] = [$s_condition->start_hour , $s_condition->end_hour];
                 }
            }
              return json_encode(['status'=>200 , 'response'=>$non_working_slot]);
        }
        else{
            return json_encode(['status'=>100 , 'response'=>$non_working_slot]); 
        }
        /*End*/
    }

}
