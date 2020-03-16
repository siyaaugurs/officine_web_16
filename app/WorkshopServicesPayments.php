<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class WorkshopServicesPayments extends Model{
   
      protected  $table = "workshop_service_payments";
	  protected $fillable = [
        'id', 'workshop_id', 'category_type', 'type','type_status','hourly_rate', 'maximum_appointment' , 'price' , 'created_at' , 'updated_at'];
      
      
   /*
     type  = 1 for assemble workshop details  
     category_type = null
   */
      
      public static function save_car_wash_update($request){
	    return  WorkshopServicesPayments::updateOrcreate(
											[   'workshop_id'=>Auth::user()->id , 
		                                        'category_type'=>1
											] ,
											['workshop_id'=>Auth::user()->id,
											 'category_type'=>1,
											 'hourly_rate'=>$request->hourly_rate, 
											 'maximum_appointment'=>$request->max_appointment
											]);
	  }
      
	  public static function save_update($request){
	    return  WorkshopServicesPayments::updateOrcreate(['workshop_id'=>Auth::user()->id , 
		                                        'category_type'=>1
											   ] ,
										   ['workshop_id'=>Auth::user()->id,
										    'category_type'=>1,
										    'hourly_rate'=>$request->hourly_rate, 
											'maximum_appointment'=>$request->max_appointment
										   ]);
	  }
	  
	  public static function save_update_car_revision($request){
	    return  WorkshopServicesPayments::updateOrcreate(['workshop_id'=>Auth::user()->id , 
		                                        'category_type'=>2
											   ] ,
										   ['workshop_id'=>Auth::user()->id,
										    'category_type'=>2, 
											'maximum_appointment'=>$request->max_appointment,
											'price' => $request->price
										   ]);
	  }
	  public static function save_update_car_maintainance($request){
		return  WorkshopServicesPayments::updateOrcreate(
			['workshop_id'=>Auth::user()->id , 
			'category_type'=>12
			] ,
			['workshop_id'=>Auth::user()->id,
			'category_type'=>12,
			'hourly_rate'=>$request->hourly_rate, 
			'maximum_appointment'=>$request->max_appointment
			]);
	}
	
	
	public static function workshop_assemble_service_details($request){
		return  WorkshopServicesPayments::updateOrcreate(
			['workshop_id'=>Auth::user()->id , 
			 'type'=>2
			] ,
			['workshop_id'=>Auth::user()->id,
			'type'=>2,
			'type_status'=>'Workshop Assemble details',
			'hourly_rate'=>$request->hourly_cost, 
			'maximum_appointment'=>$request->max_appointment
			]);
	}
	
	public static function get_service_price_max($workshop_id , $category_id){
	  return WorkshopServicesPayments::where([['workshop_id' , '=' ,$workshop_id] , ['category_type' , '=' , $category_id]])->first();
	}
	
	
	public static function get_assemble_service_details($workshop_id){
	  return WorkshopServicesPayments::where([['workshop_id' , '=' ,$workshop_id] , ['type' , '=' , 2]])->first();
	}
	//Save and update workshop tyre24 group service price details
	public static function save_update_workshop_tyre24_group_details($request){
		return  WorkshopServicesPayments::updateOrcreate(['workshop_id'=>Auth::user()->id ,
														'category_type'=>23] ,
														['workshop_id'=>Auth::user()->id,
														'category_type'=>23, 
														'maximum_appointment'=>$request->max_appointment,
														'hourly_rate' => $request->hourly_rate
														]);
	}
	//End
	public static function get_mot_service_details($workshop_id, $category_type) {
		return WorkshopServicesPayments::where([['workshop_id' , '=' ,$workshop_id] , ['category_type' , '=' , $category_type]])->first();
	}
	public static function add_mot_servce_details($request) {
		return  WorkshopServicesPayments::updateOrcreate(
		[
			'workshop_id' => Auth::user()->id,
			'category_type' => 3
		], 
		[
			'workshop_id' => Auth::user()->id,
			'category_type' => 3,
			'maximum_appointment' => $request->mot_max_appointment,
			'hourly_rate' => $request->mot_hourly_rate
		]);
	}
}
