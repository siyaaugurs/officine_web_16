<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class items_repairs_servicestimes_details extends Model
{
    //
	protected $table = 'items_repairs_servicestimes_details';
	protected $fillable = ['id','items_repairs_servicestimes_id','items_repairs_servicestimes_item_id' ,'our_description','k_time','our_time','priority','language','status','deleted_at','created_at','updated_at'];


	public static function add_new_maintenance_service_details($response , $request) {
			if($response->type == 1){
				$where_clause = ['items_repairs_servicestimes_item_id'=> $response->item_id];
				$item_id = $response->item_id;
			}else{
				$where_clause = ['items_repairs_servicestimes_id'=> $response->id];
				$item_id = NULL;
			}	
			if(!empty($request->edit_maintainance_version)) {
				$kromeda_time = $request->kromeda_time;
				$our_time = $request->our_time;
			} else {
				$kromeda_time = $response->time_hrs;
				$our_time = $response->our_time;
			}
			return items_repairs_servicestimes_details::updateOrcreate($where_clause,
				[   'items_repairs_servicestimes_id' => $response->id,
					'items_repairs_servicestimes_item_id' => $item_id,
					'our_description'=>$request->our_description,
					'k_time' =>$kromeda_time,
					'our_time'=>$our_time,
					'priority' => $request->priority,
					'language' => $request->language,
					'status' =>'A',
				]);
	}

	public static function update_maintanance_status($service_details, $status) {
		if($service_details->type == 2) {
			$where_clause = ['items_repairs_servicestimes_id'=> $service_details->id];
			$item_id = NULL;
		} else {
			$where_clause = ['items_repairs_servicestimes_item_id' => $service_details->item_id];
			$item_id = $service_details->item_id;
		}
		return items_repairs_servicestimes_details::updateOrcreate($where_clause,
				[   'items_repairs_servicestimes_id' => $service_details->id,
					'items_repairs_servicestimes_item_id' => $item_id,
					'status' => $status,
				]);
	}
	
	
}
