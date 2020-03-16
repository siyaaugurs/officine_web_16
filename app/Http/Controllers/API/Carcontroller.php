<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use sHelper;
use App\User;
use Illuminate\Support\Facades\Auth; 

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;


use App\Gallery;

class Carcontroller extends Controller{
   
    public function car_model_details(Request $request){
       if(!empty($request->car_makers) && !empty($request->model_name)){
		   $car_makers_slug = sHelper::slug($request->car_makers);
	       $car_model_slug = sHelper::slug($request->model_name);
		   $base_url = "https://api.wheel-size.com/v1/models/";
		   $url = "$car_makers_slug/$car_model_slug/?user_key=b8f5788d768c1823dd920c2576b644f9";
		    $client = new Client(['base_uri' => $base_url]);
			try {
					$response = $client->request('GET', $url);
					 //$result = sHelper::get_api_data("GET" , $base_url);
					$response = $response->getBody()->getContents();
					$response = trim($response);
					$response = json_decode($response);
					
				} catch (RequestException $e) {
						$response = 404;
				}
		   $image_name = '';
		   $title = '';
		   if(is_object($response)){
			   foreach($response->generations as $images){
			       $image_name =  $images->bodies[0]->image;
				   $title = $images->bodies[0]->title;
				  break;
				 }
			  $data_set = ['image'=>$image_name , 'title'=>$title]; 	 
			  return sHelper::get_respFormat(1 , null , null , $data_set);  
			 }
		   else{
			  return sHelper::get_respFormat(0 , "Image Not found." , null , null);  
			 }	 
		 }
	   else	 
	  return sHelper::get_respFormat(0 , "Please enter the car makers name ." , null , null); 
	 }

   
   public function get_action(Request $request , $action){
	  if($action == "remove_car_image"){
				 if(!empty($request->image_row_id) || is_numeric($request->image_row_id)){
					 $get_detaild = Gallery::get_image_details($request->image_row_id);
					 if($get_detaild != NULL){
						if($get_detaild->users_id == Auth::user()->id){
						   $thumbImage_path = public_path('carlogo');
						   $car_image_path = $thumbImage_path."/".$get_detaild->image_name;
							if(file_exists($car_image_path )){ 
							   /*Set default image*/
								 if((int) $get_detaild->primary_image == 1){
									$all_image_set = Gallery::users_details_image($get_detaild->user_details_id);
                                    $num =  $all_image_set->count() - 2; 
									
                                     if($num < 0) { $num = 0; }
									 //echo $all_image_set->count();exit;
                                     if($all_image_set->count() > 1){
									  /*Set default image if gallery are does not exist*/ 
									   $image_name = $all_image_set[$num]->image_name;
                                        $set_primary_image = Gallery::find($all_image_set[$num]->id);
									    $set_primary_image->primary_image = 1;
									    $set_primary_image->save();  
									 }
									 else{ $image_name = "default.jpg"; }
									$image_set = \App\Model\UserDetails::set_default_image(                                $get_detaild->user_details_id , $image_name); 
								   }
							   /*End*/
							   $result = Gallery::remove_image($request->image_row_id);
							   unlink($car_image_path );
							   return sHelper::get_respFormat(1 , "Image delete Successful." , null , null);  
							   }
							else
							  return sHelper::get_respFormat(0 , "Unexpected try again ." , null , null); 
						  }
						else 
						 return sHelper::get_respFormat(0 , "You have not permission to delete this image ." , null , null); 
						 
					   }
					 else
						return sHelper::get_respFormat(0 , "Image record not found." , null , null); 
				   }
				 else
				   return sHelper::get_respFormat(0 , "Something went wrong , please try again ." , null , null); 
					 
			   }
	}
    
   public  function upload_car_pic(Request $request){
	  if(!empty($request->users_details_id) || is_numeric($request->users_details_id)){
		  $upload_image = $this->upload_pic($request);
		  if($upload_image != 111){
			 /*Change all image status */ 
			 if(!empty($request->default_image)){
			   $change_status = Gallery::update_status_all_image(['user_details_id'=>$request->users_details_id]);
			   }
			/*End*/
			 $result = Gallery::add_car_image($upload_image , $request);
			 if($result != NULL){
				if(!empty($request->default_image)){
				   $image_set = \App\Model\UserDetails::set_default_image($request->users_details_id , $result->image_name);
				
				  }
				return sHelper::get_respFormat(1 , "Image Upload Successful" , $result , null); 
			   }
			  else{
			     return response()->json(array('status_code'=>0 , 'message'=>'Something Went wrong please try again .'));
			  } 
		  }
		  else{
			   return response()->json(array('status_code'=>0 , 'message'=>'only jpg , png , jpeg  file supported .'));
		  }
		}
	  else{
		 return response()->json(array('status_code'=>0 , 'message'=>'please try again .'));
		}	
   }
   
   
     
  public function get_user_details_pic(Request $request){	
if(!empty($request->users_details_id) || is_numeric($request->users_details_id)){
	     $result = Gallery::users_details_image($request);
		 if($result != null){
			return sHelper::get_respFormat(1 , null , $result , null);
		   }
		 else{
		     return sHelper::get_respFormat(0 , "Image not found" , null , null);
		   }      
	   }
	 else{
       return response()->json(array('status'=>0 , 'message'=>'Something Went Wrong .'));
	  }  
   }
   
     public  function set_default_car_pic(Request $request){
      if(!empty($request->image_row_id)){
		   $result = Gallery::find($request->image_row_id);
		   if($result != NULL){
			   if(!empty($result->user_details_id)){
				  $image_set = \App\Model\UserDetails::set_default_image($result->user_details_id , $result->image_name);
				   if($image_set){
                      $result->primary_image = 1;
					  if( $result->save() ){
						 return sHelper::get_respFormat(1 , "Image Set Successful  !!!" , null , null);
						}
					  else	
					   return sHelper::get_respFormat(0 , "Unexpected try again !!!" , null , null);
					 }
				   else
					  return sHelper::get_respFormat(0 , "Unexpected try again !!!" , null , null);
				 }
			   else
				  return sHelper::get_respFormat(0 , "Record Not Found" , null , null);
			 }
			else
		     return sHelper::get_respFormat(0 , "Record Not Found" , null , null);
		}
	  else
        return sHelper::get_respFormat(0 , "Something Went Wrong" , null , null);
   }
   
   
}
