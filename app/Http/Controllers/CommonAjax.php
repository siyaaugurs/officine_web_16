<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;

class CommonAjax extends Controller{
   public function get_action(Request $request , $action){
	if($action == "remove_off_days"){
		if(!empty($request->off_days_id)){
			$response = \App\Workshop_leave_days::find($request->off_days_id);
			if($response != NULL){
				$response->deleted_at = now();
				if($response->save()){
					echo 200;exit;
				  }
				else{
				   echo 200;exit;
				  }	  
			  }
		  }
	  }
       if($action == "get_workshop_feedback") {
			$feedback = \App\Feedback::get_workshop_feedback_detail($request->feedbackId);
			$images = \App\Gallery::get_feedback_images($request->feedbackId);
			return view('common.component.workshop_feedback_detail')->with(['feedback'=>$feedback, 'images'=>$images]);
		}
		if($action == "get_seller_feedback") {
			$feedback = \App\Feedback::get_workshop_feedback_detail($request->feedbackId);
			$feedback->workshop_user_id = NULL;
			if($feedback->products_id != NULL) {
				$products = \App\ProductsNew::get_feedback_product($feedback->products_id);
				if(!empty($products)) {
					$product_name = $products->listino;
				}
			} else {
				$feedback->workshop_user_id = $feedback->workshop_id;
				$product_name = \App\Library\orderHelper::find_workshop($feedback);
			}
			$product = !empty($product_name) ? $product_name : "N/A";
			$images = \App\Gallery::get_feedback_images($request->feedbackId);
			return view('common.component.seller_feedback')->with(['product'=>$product , 'feedback'=>$feedback, 'images'=>$images]);
		}
        if($action == "getTimePrice"){
		   if(!empty($request->category_id)){
			   $response = \App\ServiceTimePrice::get_time_price($request->category_id);
			   if($response){
				    return json_encode(array('status'=>200 , 'response'=>$response));
				  }
				else{
				     return json_encode(array('status'=>404));
				  }  
			 }
		   else{
			   return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Wrong , please try again .</div>'));
			 }	 
		 }
       if($action == "change_status"){
		   if(!empty($request->workshop_id)){
			   $work_shop_details = \App\Workshop::find($request->workshop_id);
			   $work_shop_details->status = $request->status;
			   if( $work_shop_details->save() ){
				   return json_encode(array('status'=>200));
				 }
			   else{
				    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Wrong , please try again .</div>'));
				 }	 
			 }  
		}
		
      
       if($action == "change_category"){
		  if(!empty($request->category_type)){
				$categories = \sHelper::get_parent_category($request->category_type);
			    return json_encode(array('result'=>$categories , 'status'=>200));
			}
		} 
     
        if($action == "get_aaddress_details"){
		   $result = \App\Address::find($request->address_id);
		   if($result != NULL){
			   return json_encode(array('result'=>$result , 'status'=>200));
			 }
			else{
			  return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Wrong , please try again .</div>' , 'status'=>100));
			 } 
		}
	  /*Remove address script start*/
	    if($action == "remove_address"){
		    if(!empty($request->address_id)){
			    $result = \App\Address::find($request->address_id);
				if($result != NULL){
				   $result->is_deleted = 1;
				   if($result->save()){
					     echo '<div class="notice notice-success"><strong>Success , </strong> Address removed successfully.</div>';exit;
					 }
					else{
				      echo '<div class="notice notice-danger"><strong>Wrong , </strong> something went wrong , please try again  .</div>';exit; 
					} 
				 }
			 }
		  }
	  /*End*/ 
      /*Delete category script start*/
	    if($action == "edit_about_workshop"){
		    if(!empty($request->workshop_id)){
			      $result = \App\Workshop::find($request->workshop_id); 
				  $result->description = nl2br($request->about_workshop);
				  if( $result->save() ){
					   echo '<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>';exit;
					}
				  else{
					  echo '<div class="notice notice-danger"><strong>Wrong , </strong> something went wrong , please try again  .</div>';exit;
					}	
			  }
			 
		  }
		  
	    if($action == "delete_category"){
		     if(!empty($request->category_id)){
			     $result = \App\Workshop_users_category::category_delete($request->category_id);
				 if($result != NULL){
				     echo '<div class="notice notice-success"><strong>Success , </strong> Category deleted successfully.</div>';exit;
				   }
				 else{
				    echo '<div class="notice notice-danger"><strong>Wrong , </strong>Something went wrong ,  please try again  .</div>';exit;  
				   }  
			   }
		   }
	  /*End*/

       /*Get State names*/
	     if($action == "get_state"){
		     if(!empty($request->country_id)){
			    $state = DB::table("states")->where(array("country_id"=>$request->country_id))->get();
				return json_encode(array("status"=>200 , "states"=>$state));
			   }
		   }
	   /*End*/
	    /*Get the city name */
	     if($action == "get_cities"){
			   if(!empty($request->stateID)){
				   $cities =  DB::table("cities")->where("state_id" , $request->stateID)->get();
			       if($cities->count() > 0){
			          return json_encode(array("status"=>200 , "cities"=>$cities));
			        }
			       else{
				     return json_encode(array("status"=>100));
			     	}
				 }
				else{
			    return json_encode(array("status"=>500));
				} 
		   }
	   /*End*/ 
	  /*Get Country name script start*/
	     if($action == "get_city_name"){
		    if(!empty($request->city_id)){
			  	$city_name = DB::table("cities")->where('id' ,$request->city_id)->pluck('name')->first();
			   if($city_name)return $city_name;else return 101;
			  }
		  }
	   /*End*/
	  /*Get Country name script start*/
	     if($action == "get_country_name"){
		    if(!empty($request->country_id)){
			  	$country_name = DB::table("countries")->where('id' ,$request->country_id)->pluck('name')->first();
			   if($country_name)return $country_name;else return 101;
			  }
		  }
	   /*End*/
      /*Get country script start*/
	   if($action == "get_country"){
		   $country = DB::table("countries")->get();
		    if($country->count() > 0){
			    return json_encode(array("status"=>200 , "countries"=>$country));
			  }
			else{
				return json_encode(array("status"=>100 , "countries"=>$country));
			  }  
		 }
	  /*End*/
	  /*Get city script start*/	 
	   if($action == "get_city"){
		   $state = DB::table("states")->where(array("country_id"=>$request->country_id))->pluck('id');
			if($state->count() > 0){
			   $cities =  DB::table("cities")->whereIn("state_id" , $state)->get();
			    if($cities->count() > 0){
			       return json_encode(array("status"=>200 , "cities"=>$cities));
			     }
			    else{
				return json_encode(array("status"=>100));
			  }
			 }
		 }
	 /*End*/	 	 	 
   }
   
   public function post_action(Request $request , $action){
         /*Add users catyegory script start*/
	     if($action == "add_users_category"){
		      if(!empty($request->category)){
				 $result = \App\Workshop_users_category::add_category_workshop($request);
				 if($result != NULL){
				     return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> category saved Successfully.</div>' , "status"=>200));
				   }
				 else{
				    return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong>something went wrong ,  please try again .</div>' , "status"=>100));
					}   
				}
			  else{
				 return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong  , </strong> Please select any one of category to add.</div>' , "status"=>100)); 
				}	
		   }
		 //if(!empty($request->al()))
	   /*End*/
	   /*Add workshop script start*/
	    if($action == "add_workshop_adrs"){
		     $validator = \Validator::make($request->all(), [
                'address_1' => 'required', 
				// 'zip_code' => 'required' , 'country'=>'required' , 'state'=>'required' , 'city'=>'required' , 
				'latitude'=>'required' , 'longitude'=>'required',
                'zip_code' => 'required' /* , 'country'=>'required' , 'state'=>'required' , 'city'=>'required' */ 
               ]);
			if($validator->fails()){
              	return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
			if(!empty($request->edit_address_id)){
			    $result = \App\Address::add_workshop_address($request , $request->edit_address_id);
			} else {
				$result = \App\Address::add_workshop_address($request);
			}  
			 
			if($result != FALSE){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Address saved Successfully.</div>' , "status"=>200));
			} else {
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}  
		}
	   /*End*/
       
       
      
	    /*Add Bank Detials scdriot start*/
	    if($action == "add_bank_details"){
		     $validator = \Validator::make($request->all(), [
                'owner_name' => 'required', 'iban_code' => 'required',
                'bank_address' => 'required', 'country'=>'required', 
             ]);
			if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
			/* echo "<pre>";
			 print_r($request->all());exit;*/
			 if(!empty($request->country)){
				  $result = \App\Bankdetails::add_bank_details($request);
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
	
        if($action == "add_business_details"){
		    $validator = \Validator::make($request->all(), [
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
			  
			$result = \App\BusinessDetails::add_business_details($request , $adrs_proof_result  , $reg_proof_result );
			
			if($result != NULL){
				  $save_address = \App\Address::save_address_details($result);
				 return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
						 }
			else{	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			   }
		  }
		  
        if($action == "post_profile_image"){
		     /*Online Code script start*/
			 $image = $request->image;
             list($type, $image) = explode(';', $image);
             list(, $image)      = explode(',', $image);
              $image = base64_decode($image);
              $image_name = md5(time()).'.png';
              //$path = public_path('storage/'.$image_name);
			  $path = public_path('storage/profile_image/'.$image_name);
			
              file_put_contents($path, $image);
			 /*End*/
            $update_profile_image = \App\User::edit_profile(['profile_image'=>$image_name]);
				 
				    if($update_profile_image != NULL){
					     $result = \App\Gallery::add_profile_image($image_name);
						 if($result != NULL){
					        return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Profile Image Set Successfully.</div>' , "status"=>100));
						 }
					        else{	
					        return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Un-expected try again .</div>' , "status"=>200));
						}
					}
			}
   }
}
