<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use apiHelper;
use sHelper;
use  App\Http\Controllers\RimController;

class RimAjaxController extends Controller{
    
    public function get_action(Request $request , $action){
        $obj = new RimController;
        /*Remove Rim image script sart */
	   if($action == "remove_image"){
		    if(!empty($request->image_id)){
			   $image_details = \App\RimImage::find($request->image_id);
			   if($image_details != NULL){
				  $image_details->deleted_at = now(); 
				  if($image_details->save()){
					  echo 200;exit; 
					}
				  else{
					  echo '<div class="notice notice-success"><strong>Success </strong> Record Save successfull !!! </div>';exit; 
					}	
				 }
			   else{
				  echo '<div class="notice notice-danger"><strong>Wrong </strong> Image not exist !!! </div>';exit; 
				 } 	 
			 
			  }
			else{
			  echo '<div class="notice notice-success"><strong>Success </strong> Record Save successfull !!! </div>';exit;
			  }  
		 }
	  /*End*/
        /*Get Rim info script Start*/
		 if($action == "get_rim_info"){
		     if(!empty($request->rim)){
			    $rim_detail = \App\Rim::find($request->rim);
				if($rim_detail != FALSE){
					 echo "<pre>";
			         print_r($rim_detail);exit;
				  }
				else{
				    echo '<div class="notice notice-info notice-sm"><strong> Info </strong> Record Not found !!! </div>';exit;  
				  }  
			  }
			 else{
			     echo '<div class="notice notice-danger notice-sm"><strong> Wrong </strong> Something went wrong , please try again !!! </div>';exit; 
			  } 
		   }
		/*End*/
	    /*Get Rim From database*/
        if($action == "get_rim_from_database"){
           if(!empty($request->maker_name)){
                $maker_details = \App\Maker::where([['idMarca' , '=' , $request->maker_name]])->first(); 
                if($maker_details != NULL){
                    $maker_slug = sHelper::slug($maker_details->Marca);
                    $get_rim_list = \App\Rim::get_rim_response($maker_slug);
                    if($get_rim_list->count() > 0){
                        return view('rim.component.rim_list')->with(['get_rims'=>$get_rim_list , 'obj'=>$obj]); 
                    }
                    else{
                        echo '<div class="notice notice-danger notice-sm"><strong> Wrong </strong> No record found !!! </div>';exit;
                    } 
                }else{
                    echo '<div class="notice notice-danger notice-sm"><strong> Wrong </strong>Something went wrong please try again  !!! </div>';exit;
                 }
           }
           else{
              echo '<div class="notice notice-danger notice-sm"><strong> Wrong </strong>Something went wrong please try again  !!! </div>';exit;
           }
        }
        /*End*/
		/*Get Rim Script Start*/
		 if($action == "get_rim"){
			set_time_limit(1500);
			 if(!empty($request->maker_name)){
                $maker_details = \App\Maker::where([['idMarca' , '=' , $request->maker_name]])->first(); 
				if($maker_details != NULL){
                    $get_today_rim_response = collect();
                    //$get_today_rim_response = \App\Rim::get_today_response($maker_details);	
                    if($get_today_rim_response->count() <= 0){
                        $rim_response = apiHelper::get_rims($maker_details);
                        /*   echo "<pre>";
                        print_r($rim_response);exit; */
                        if($rim_response != FALSE){
                            $save_rim_response = \App\Rim::save_rim($maker_details , $rim_response);
                            if($save_rim_response != FALSE){
                                return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>')); 
                              }
                            else{
                              return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));   
                              }  
                         }
                        else{
                           return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));   
                         }					       
                     } 
                    else{
                        return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>')); 
                    } 	
                             				 
				  }
				else{
				   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong> Maker name was wrong !!! </div>'));    
				  }  
			   }
			 else{
			   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));   
			   }  
		 }
		/*End*/
        /*get_rim_workmanship_for_rim_type script start*/
        if($action == "get_rim_workmanship_for_rim_type"){
            if(!empty($request->rim_type)){    
                $tyre_type = (string)  $request->rim_type;           
                $check_tyre_type_details = \App\RimTypeManufacturer::where([['idMarca' , '=' , $request->maker]])
                ->where('get_rim_type_for_rim_response->rimType', 'like', '%'.$tyre_type.'%')
                ->first();
                    if($check_tyre_type_details != NULL){
                        $response = \App\RimWorkshopRimType::get_response($tyre_type , $request->maker);
                        if($response->count() < 1){
                            $rim_workshop_rim_type_response =  apiHelper::get_rim_workmanship_for_rim_type($request->rim_type);    
                            if($rim_workshop_rim_type_response != FALSE){
                                $save_response_from_database = \App\RimWorkshopRimType::save_response($rim_workshop_rim_type_response , $request->rim_type , $request->maker); 
                                if($save_response_from_database != FALSE){
                                    $response = \App\RimWorkshopRimType::get_response($tyre_type , $request->maker);
                                }  
                            }
                        }
                        if($response->count() > 0){
                            return json_encode(array("status"=>200 , "response"=>$response));   
                        }
                        else{
                            return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>')); 
                        }


                    
                    }
                    else{
                        return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));   
                    }
            }
            else{
                return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));  
            }
        }
        /*End*/
        /*Get Rim type for manufacturer*/
        if($action == "get_rim_type_manufacturar"){
            if(!empty($request->maker_name)){
                $maker_details = \App\Maker::where([['idMarca' , '=' , $request->maker_name]])->first(); 
                if($maker_details != NULL){
                    $get_rim_type_response = \App\RimTypeManufacturer::get_response($maker_details->idMarca , $maker_details->Marca);
                    if($get_rim_type_response == NULL){
                        $get_rim_type_manufacturer = apiHelper::get_rim_tyre_for_manufacturer($maker_details->Marca); 
                        if($get_rim_type_manufacturer != FALSE){
                            $get_tyre_response = json_encode($get_rim_type_manufacturer);
                            $save_response = \App\RimTypeManufacturer::save_rim_type_for_rim($get_tyre_response , $maker_details->idMarca , $maker_details->Marca);
                            if($save_response){
                                $get_rim_type_response = \App\RimTypeManufacturer::get_response($maker_details->idMarca , $maker_details->Marca);
                            }
                        }
                        else{
                            return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
                            
                        }
                    }
                    if($get_rim_type_response != NULL){
                        return json_encode(array("status"=>200 , "response"=>$get_rim_type_response->get_rim_type_for_rim_response)); 
                    }
                    else{
                        return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>')); 
                    }    
                }
            }
        }
        /*End*/
    }
    //Save Rim details post action
    public function post_action(Request $request , $action){
		if($action == 'save_rim_details'){
			$validator = \Validator::make($request->all() , [
                'seller_price' => 'required' , 'rim_id'=>'required' , 'size'=>'required',
				'et'=>'required','quantity'=>'required','stock_warning'=>'required','number_of_holes'=>'required'
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            if(!empty($request->rim_id)){
				/*Get Rim details*/
				 $rim_detail = \App\Rim::where([['deleted_at' , '=' , NULL] , ['id' , '=' , $request->rim_id]])->first();
				 echo "<pre>";
				 print_r($rim_detail);exit;
				 if($rim_detail != FALSE){
				     $save_rim_response = \App\RimDetails::save_rim_response($request , $rim_detail);
					 if($save_rim_response){
						if(!empty($request->rim_image)){
							$upload_rim_response = $this->upload_rim_image($request);
							if(count($upload_rim_response) > 0){
								$save_image = \App\RimImage::save_image($upload_rim_response , $rim_detail->rim_id , $rim_detail->alcar , $rim_detail->id);
							}
						   }
						  return  json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Record Save successfull !!! </div>'));
					} else {
						return  json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong !!! </div>'));
					}
				   }
				 else{
				   return  json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong !!! </div>'));  
				   }  
				 
				/*End*/
				
              
				
			}
		}
	}
}
