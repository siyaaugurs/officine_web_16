<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class WorkshopWreckerServices extends Model
{
    public  $table = "workshop_wrecker_services";
    protected $fillable = ['id', 'users_id', 'wracker_services_id', 'time_arrives_15_minutes', 'call_price', 'hourly_cost', 'cost_per_km','status', 'deleted_at', 'created_at', 'updated_at'];


    public static function get_wrecker_service_details($service_id) {
        $response = \DB::table('workshop_wrecker_services as a')
                                ->leftjoin('workshop_wrecker_service_details as b' , 'b.workshop_wrecker_services_id' , '=' , 'a.id')
                                ->leftjoin('workshop_wrecker_service_details as c' , 'c.workshop_wrecker_services_id' , '=' , 'a.id')
                                ->select('a.*' , 'b.*' , 'c.total_time_arrives as e_total_time', 'c.hourly_cost as e_hourly_cost', 'c.cost_per_km as e_cost_per_km', 'c.call_cost as e_call_cost', 'c.wrecker_service_type as e_service_type', 'c.max_appointment as e_max_appointment')
                                ->where([['a.users_id' , '=' , Auth::user()->id] , ['a.wracker_services_id' , '=' , $service_id] , ['b.wrecker_service_type' , '=' , 1] , ['c.wrecker_service_type' , '=' , 2]])
                                ->first();
        return $response;
    }
    public static function add_wrecker_services($request, $call_cost) {

		return WorkshopWreckerServices::updateOrCreate(
            ['id'=>$request->wracker_service_id] ,
            [   
                'users_id' => Auth::user()->id,
				'wracker_services_id' => $request->service_name,
				'time_arrives_15_minutes' => $request->time_arrives,
				'call_price' => $call_cost,
				'hourly_cost' => $request->hourly_rate ,
				'cost_per_km' => $request->distance_cost ,
				'status' =>'A',
            ]
        );
    }
    
    public static function add_or_edit_services($request) {

		return WorkshopWreckerServices::updateOrCreate(
            [   'users_id' => Auth::user()->id,
                'wracker_services_id' => $request->wrecker_service_id,
                'status' => 'A' 
            ],
            [   
                'users_id' => Auth::user()->id,
                'wracker_services_id' => $request->wrecker_service_id,
                'status' => 'A'
            ]
        );
    }

    public static function get_wracker_services_name($service_name) {
        return WorkshopWreckerServices::select('wracker_services_id')->where([['wracker_services_id', '=', $service_name]])->get();
    }

    public static function get_wracker_services_deatils() {
        $result = DB::table('workshop_wrecker_services as ws')
                        ->leftjoin('wracker_services as s' , 'ws.wracker_services_id' , '=' , 's.id')->select('ws.*' , 's.services_name', 's.wracker_service_type', 's.type_of_weight_1_2000' , 's.type_of_weight_2000_3000')
                        ->where([['s.deleted_at' ,'=' , NULL]])->get();
        return $result;
    }

    public static function get_wracker_details($service_id) {
        if(!empty($service_id)){
            $result = DB::table('workshop_wrecker_services as ws')
                        ->leftjoin('wracker_services as s' , 'ws.wracker_services_id' , '=' , 's.id')->select('ws.*' , 's.wracker_service_type' )
                        ->where([['ws.id' ,'=' , $service_id]])->first();
            return $result;
        }
    }
    public static function get_total_times($service_name) {
        return WorkshopWreckerServices::where([['wracker_services_id', '=', $service_name]])->first();
    }
}
