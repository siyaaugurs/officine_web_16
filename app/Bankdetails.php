<?php
namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Bankdetails extends Model{
    
	  protected  $table = "bankdetails";
	   protected $fillable = [
		'id', 'users_id', 'account_holder_name' , 'iban_code' , 'swift_code' , 'bank_address', 'country_name' , 'country_id',  'status','created_at' , 'updated_at'
  ];
	
	 public static function add_bank_details($request){
	  if(!empty($request->country))
		   $country_arr = explode('@' , $request->country);
		   
		return  Bankdetails::updateOrCreate(['users_id' =>Auth::user()->id] , 
		                                  ['users_id'=>Auth::user()->id , 'account_holder_name'=>$request->owner_name ,
		                                  'iban_code'=>$request->iban_code, 'country_name'=>$country_arr[1] , 'country_id'=>$country_arr[0] , 
		                                  'bank_address'=>$request->bank_address ,   'swift_code'=>$request->swift_code ,  'status'=>'P']);
	  }
	  
	 
	 public static function add_bank_details_by_admin($request){
	  if(!empty($request->country))
		   $country_arr = explode('@' , $request->country);
		   
		return  Bankdetails::updateOrCreate(['users_id' =>$request->workshop_id] , 
		                                  ['users_id'=>$request->workshop_id, 'account_holder_name'=>$request->owner_name , 'iban_code'=>$request->iban_code, 'country_name'=>$country_arr[1] , 'country_id'=>$country_arr[0] , 'bank_address'=>$request->bank_address ,   'swift_code'=>$request->swift_code ,  'status'=>'A']);
	  } 
	  
	  public static function get_bank_details($user_id = NULL){
	     if($user_id != NULL)
		   return Bankdetails::where('users_id' , '=' , $user_id)->first();
		   
	   }
	
}
