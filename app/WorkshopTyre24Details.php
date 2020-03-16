<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class WorkshopTyre24Details extends Model {
    protected  $table = "workshopTyre24Details";
	protected $fillable = ['id', 'workshop_user_id', 'category_id',   'hourly_rate', 'max_appointment', 'deleted_at', 'created_at' , 'updated_at'];

	//Edit hourly Rate of Workshop Tyre24
	public static function edit_workshop_tyre24_group_price($request) {
	    return WorkshopTyre24Details::updateOrcreate(
		    ['workshop_user_id' =>Auth::user()->id,
			 'category_id' =>$request->group_id, ],
			['workshop_user_id'=>Auth::user()->id,
			'category_id' =>$request->group_id,
			'hourly_rate' => $request->hourly_rate,
			'max_appointment'=>$request->max_appointment
		]);
	}
	/*Get hourly price */
	public static function get_workshop_tyre24_service_price($service_id , $users_id){
		return WorkshopTyre24Details::where([['workshop_user_id' , '=' , $users_id] , ['category_id' , '=' , $service_id], ['deleted_at', '=', NULL]])->first();
		
	}
	/*End */
}
