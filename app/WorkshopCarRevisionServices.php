<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class WorkshopCarRevisionServices extends Model
{
    protected  $table = "workshop_car_revision_services";
    protected $fillable = ['id', 'workshop_id', 'category_id',   'price', 'max_appointment',  'description', 'status' , 'deleted_at', 'created_at' , 'updated_at'];

    public static function add_car_revision_service_real_time($car_revision_service , $request) {
		return WorkshopCarRevisionServices::updateOrcreate(
		    ['workshop_id' => Auth::user()->id,
			'category_id' =>$car_revision_service->id,
            ],
			['workshop_id' => Auth::user()->id,
			'category_id' =>$car_revision_service->id,
			'price'=>$request->price, 
			'max_appointment'=>$request->max_appointment,
			'status' =>'A',
		]);
    }
    
    public static function get_car_revision_services() {
        $result = DB::table('workshop_car_revision_services as c')
					 ->leftjoin('categories as s' , 's.id' , '=' , 'c.category_id')
					 ->where([['c.workshop_id', '=', Auth::user()->id]])
					 ->where([['s.status', '!=', 1]])
					 ->select('c.*' , 's.category_name' )
	                 ->paginate(10);
	   return $result;
	}
	
	public static function edit_service_price($request) {
	    return WorkshopCarRevisionServices::updateOrcreate(
		    ['workshop_id' =>Auth::user()->id,
		 	'category_id' =>$request->service_id, ],
			['workshop_id'=>Auth::user()->id,
			 'category_id' =>$request->service_id,
			 'price' => $request->price,
			 'max_appointment'=>$request->max_appointment
		]);
	}
	public static function get_service_price($service_id , $users_id){
		return WorkshopCarRevisionServices::where([['workshop_id' , '=' , $users_id] , ['category_id' , '=' , $service_id], ['deleted_at', '=', NULL]])->first();
		
	}
}
