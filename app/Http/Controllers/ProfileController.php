<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ProfileController extends Controller{
    
	public function post_action(Request $request , $action){
	    if($action == "edit_customer_details"){
			// echo "<pre>";
			// print_r($request->all());exit;
			$validator = \Validator::make($request->all(), [
			'email' => 'required', 'user_name' => 'required',
			'mobile_number' => 'required', 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if(!empty($request->customer_id)){
				$profile_image = $this->upload_profile_image($request); 
				if($profile_image != 111){
					$result = \App\User::edit_customer_details($request, $profile_image);
					if($result != NULL){
						return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
					} else {	
						return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
					} 
				}else{
					echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Wrong </strong> only , JPG , JPEG , PNG format supported !!! </div>')); 
				}
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong>Something went wrong , please try again .</div>' , "status"=>100));
			}
		}
	   /*Bank  details edit by admin script start*/
	   if($action == "edit_bank_details"){
		     $validator = \Validator::make($request->all(), [
                'owner_name' => 'required', 'iban_code' => 'required',
                'bank_address' => 'required', 'country'=>'required', 
             ]);
			if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
			if(empty($request->workshop_id)){
		        return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong>Something went wrong , please try again .</div>' , "status"=>100));
			  }
			 if(!empty($request->country)){
				  $result = \App\Bankdetails::add_bank_details_by_admin($request);
					if($result != NULL){
						 return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
								 }
					else{	
						return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
								}
			   }
			  else {
			    return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please select , country .</div>' , "status"=>500));
			  }
		  }
	   /*End*/	
	   if($action == "edit_business_details"){
	       //return $request;exit;
		  $validator = \Validator::make($request->all(), [
		        'workshop_id'=>'required',
                'owner_name' => 'required', 'business_name' => 'required',
                'about_business' =>'required' , 'postal_code'=>'required' , /*'country'=>'required' , 'state'=>'required' ,*/ 'registered_office'=>'required']);
				
			if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
			
			$adrs_proof_result = $this->upload_address_proof($request);
			$reg_proof_result = $this->upload_reg_proof($request);
			if($adrs_proof_result == 111 || $reg_proof_result == 111){
			   return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Note , </strong> only jpg , png , pdf file are allowed  .</div>' , "status"=>100));
		    }
			  
			$result = \App\BusinessDetails::add_business_detailsByAdmin($request , $adrs_proof_result  , $reg_proof_result );
			if($result != NULL){
				 return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
						 }
			else{	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			   }
		 }
	}
	
}
