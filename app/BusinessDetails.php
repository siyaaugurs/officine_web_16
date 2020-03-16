<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BusinessDetails extends Model{
    
      protected  $table = "business_details";
	  
	  //protected  $table = "business_details_New_1";
	  protected $fillable = [
        'id', 'users_id',  'owner_name', 'business_name' , 'registration_proof' , 'address_proof' , 'address_1' , 'address_2' , 'address_3' , 'registered_office' , 'postal_code', 'country_name', 'country_id' , 'state_name'  , 'state_id' , 'city_name' , 'city_id' ,  'fiscal_code' , 'vat_number' , 'sdi_recipient_code' , 'pec'  , 'latitude', 'langitude',  'landmark' ,'status', 'about_business','created_at' ,'term_and_condition' , 'updaetd_at'
    ];
	 
	 
	  public static function add_business_details($request,$add_proof = NULL , $reg_proof = NULL){
		//echo "<pre>";
		//print_r($request->all());exit; 
		$country_id = 0;
		$country_name = '';
		$city_name = '';
		$city_id = 0; 
		$state_name = '';
		$state_id = 0;
		/* if(!empty($request->country)){
		     $country_arr = explode('@' , $request->country);
			 $country_id = $country_arr[0];
			 $country_name = $country_arr[1];
		   }
		if(!empty($request->state)){
		   $state_arr =  explode('@' , $request->state);
		   $state_name =$state_arr[1];
		   $state_id =$state_arr[0];		    
		   }
		   
		if(!empty($request->city)){
			$city_arr =  explode('@' , $request->city); 
			$city_name = $city_arr[1];
			$city_id = $city_arr[0]; 
		} */   
		
        return  BusinessDetails::updateOrCreate(['users_id' =>Auth::user()->id] , 
										  ['users_id'=>Auth::user()->id , 'owner_name'=>$request->owner_name ,
										  'business_name'=>$request->business_name , 'registration_proof'=>$reg_proof ,
										  'address_proof'=>$add_proof,
										  'address_1'=>$request->address_1,
										  'address_2'=>$request->address_2,
										  'address_3'=>$request->address_3,
										  'landmark'=>$request->landmark,
										  'about_business'=>$request->about_business,
										  'postal_code'=>$request->postal_code,
										  'registered_office'=>$request->registered_office,
										  'fiscal_code'=>$request->fiscal_code, 'vat_number'=>$request->vat_number,
										  'sdi_recipient_code'=>$request->sdi_recipient_code,
										  'pec'=>$request->pec,
										//   'country_name'=>$country_name,
										//   'country_id'=>$country_id,
										//   'state_name'=>$state_name,'state_id'=>$state_id,
										//   'city_name'=>$city_name, 'city_id'=>$city_id,
										  'latitude'=>$request->latitude,'langitude'=>$request->longitude,
										  'term_and_condition'=>$request->term_condition , 'status'=>'P']);
	  }
	 
	 
	/* public static function add_business_details($request , $add_proof = NULL , $reg_proof = NULL){
		
		 
        return  BusinessDetails::updateOrCreate(['users_id' =>Auth::user()->id] , 
		                                  ['users_id'=>Auth::user()->id , 'owner_name'=>$request->owner_name , 'business_name'=>$request->business_name , 'registration_proof'=>$reg_proof , 'address_proof'=>$add_proof, 'address_1'=>$request->address_1 , 'address_2'=>$request->address_2 , 'address_3'=>$request->address_3 , 'landmark'=>$request->landmark , ,   'about_business'=>$request->about_business , 'term_and_condition'=>$request->term_condition]);
	  }*/
	  
	  
	  public static function add_business_detailsByAdmin($request , $add_proof = NULL , $reg_proof = NULL){
		$country_id = 0;
		$country_name = '';
		$city_name = '';
		$city_id = 0; 
		$state_name = '';
		$state_id = 0;
		if(!empty($request->country)){
		     $country_arr = explode('@' , $request->country);
			 $country_id = $country_arr[0];
			 $country_name = $country_arr[1];
		   }
		if(!empty($request->state)){
		   $state_arr =  explode('@' , $request->state);
		   $state_name =$state_arr[1];
		   $state_id =$state_arr[0];		    
		   }
		   
		if(!empty($request->city)){
			$city_arr =  explode('@' , $request->city); 
			$city_name = $city_arr[1];
			$city_id = $city_arr[0]; 
		}     
		return  BusinessDetails::updateOrCreate(['users_id'=>$request->workshop_id], 
										  ['users_id'=>$request->workshop_id, 
										   'owner_name'=>$request->owner_name,
										   'business_name'=>$request->business_name, 
										   'registration_proof'=>$reg_proof,
										   'address_proof'=>$add_proof,
										   'address_1'=>$request->address_1,
										   'address_2'=>$request->address_2,
										   'address_3'=>$request->address_3,
										   'landmark'=>$request->landmark,
										   'about_business'=>$request->about_business, 
										   'postal_code'=>$request->postal_code,
										   'registered_office'=>$request->registered_office, 'fiscal_code'=>$request->fiscal_code, 
										   'vat_number'=>$request->vat_number, 'sdi_recipient_code'=>$request->sdi_recipient_code,
										   'pec'=>$request->pec,'country_name'=>$country_name, 'country_id'=>$country_id, 'state_name'=>$state_name,
										   'state_id'=>$state_id,'city_name'=>$city_name, 'city_id'=>$city_id, 'latitude'=>$request->latitude,'langitude'=>$request->longitude , 
										   'term_and_condition'=>1,  'status'=>'A']);
	  }
	 
	  
	  public static function get_business_details($user_id = NULL){
	     if($user_id != NULL)
		   return BusinessDetails::where('users_id' , '=' , $user_id)->first();
		   
	   }
	 
}
