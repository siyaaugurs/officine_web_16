<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_tyre_detail extends Model
{
	protected  $table = "user_tyre_details";
    protected $fillable = [
        'id','user_id' ,'vehicle_type', 'season' , 'width' , 'speedindex' , 'run_flat' , 'reinforced','execute','aspect_ratio','rim_diameter','car_version_id','created_at', 'deleted_at',  'updated_at']; 
		
	public static function save_user_tyre_details($request ,$speed_index  = NULL,  $vhicle_type = NULL , $season_type = NULL){
		return  User_tyre_detail::updateOrcreate([
			'user_id'=> $request->user_id,
			'vehicle_type'=>$request->vehicle_type,
			'season' =>$request->season,
			'width' =>$request->width,
			'speedindex' =>$request->speedindex,
			'run_flat' =>$request->run_flat,
			'reinforced' =>$request->reinforced,
			'aspect_ratio' =>$request->aspect_ratio,
			'rim_diameter' =>$request->rim_diameter,
			'car_version_id'=>$request->car_version_id,
		],
		[
			'user_id'   => $request->user_id,
			'vehicle_type' =>$request->vehicle_type,
			'season' =>$request->season,
			'width' =>$request->width,
			'speedindex' =>$request->speedindex,
			'run_flat' =>$request->run_flat,
			'reinforced' =>$request->reinforced,
			'aspect_ratio' =>$request->aspect_ratio,
			'rim_diameter' =>$request->rim_diameter,
			'car_version_id'=>$request->car_version_id,
			'deleted_at' => NULL,
			
		]);
	}

	public static function get_user_tyre_details($input){
			return User_tyre_detail::where('user_id',$input['user_id'])->get();
	}
	
	public static function get_user_tyre_version($request){
		 return User_tyre_detail::where([['user_id', '=' , $request->user_id] ,['car_version_id' , '=' ,$request->car_version_id] , ['deleted_at' , '=' , NULL]])->get();
	}
}
