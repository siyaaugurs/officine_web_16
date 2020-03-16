<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use sHelper;

class Tyre24Ajax extends Controller{
    
	public function get_action(Request $request , $action){
		if($action == "get_tyre_info"){
			$data = [];
			if(!empty($request->tyre_value)){
			   $data['tyre'] = \App\Tyre24::find($request->tyre_value);
			   $data['tyre']->tyre_details_response = $data['tyre']->tyre_resp = "";
			   if($data['tyre'] != NULL){
				   $data['tyre']->tyre_resp = json_decode($data['tyre']->tyre_response);
				   $tyre_detail = sHelper::get_tyre_detail($data['tyre']);
				   if($tyre_detail != NULL){
					   $data['tyre']->tyre_details_response = json_decode($tyre_detail->tyre_detail_response); 
						}
				   if($data['tyre'] != NULL){
					  return view('tyre.component.tyre_detail')->with($data);
					 } 
				   else{
						echo '<div class="notice notice-danger"><strong>Wrong </strong> Tyre Content not available .!!! </div>';exit; 
					 }   
			   }
			   else{
				 echo '<div class="notice notice-danger"><strong>Wrong </strong> Tyre Content not available .!!! </div>';exit;  
			   }
			  }
			else{
			  echo '<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong please try again .!!! </div>';exit; 
			 }  
		   }
	  /*Remove Tyre image scrpt sart */
	   if($action == "remove_tyre_image"){
		    if(!empty($request->image_id)){
			   $tyre_details = \App\TyreImage::find($request->image_id);
			   if($tyre_details != NULL){
				  $tyre_details->deleted_at = now(); 
				  if($tyre_details->save()){
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
	
	}
	
	// public function post_action(Request $request , $action){
 //        $validator = \Validator::make($request->all() , [
 //            'seller_price' => 'required'
 //          ]);
 //        if($validator->fails()){
 //          return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
 //         }
 //        /*Save Car records */
 //           $save_tyre_response = \App\Tyre24::save_tyre_response($request);
 //           if($save_tyre_response){
 //            if(!empty($request->tyre_image)){
	// 		    $upload_tyre_response = $this->upload_tyre_image($request);
	// 			if(count($upload_tyre_response) > 0){
	// 				$save_image = \App\TyreImage::save_image($upload_tyre_response , $request->tyre_item_id);
	// 			}
	// 		  }
 //            return  json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Record Save successfull !!! </div>'));
 //           }
 //           else{
 //            return  json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong !!! </div>'));
	// 	   }
 //        /*End*/ 
 //    }
	public function post_action(Request $request , $action){
        $validator = \Validator::make($request->all() , [
            // 'seller_price' => 'required'
          ]);
        if($validator->fails()){
          return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
         }
		/*Save Car records */
		$save_tyre_response = \App\Tyre24::save_tyre_response($request);
		if($save_tyre_response){
			if(!empty($request->tyre_image)){
				$upload_tyre_response = $this->upload_tyre_image($request);
				if(count($upload_tyre_response) > 0){
					$save_image = \App\TyreImage::save_image($upload_tyre_response , $request->tyres_id , 1 , $request->tyre_item_id);
				}
			}
			if(!empty($request->tyre_label_image)) {
				$upload_tyre_label_response = $this->upload_tyre_label_image($request);
				
				if(count($upload_tyre_label_response) > 0){
					$save_image = \App\TyreImage::save_image($upload_tyre_label_response , $request->tyres_id , 2 , $request->tyre_item_id);
					//$save_label_image = \App\TyreImage::save_label_image($upload_tyre_label_response , $request);
				}
			}
			return  json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Record Save successfull !!! </div>'));
		}
		else{
		return  json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong !!! </div>'));
		}
        /*End*/ 
    }
}
