<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkshopWreckerServiceDetails extends Model{

    public  $table = "workshop_wrecker_service_details";
    protected $fillable = ['id', 'workshop_wrecker_services_id', 'total_time_arrives', 'hourly_cost', 'cost_per_km', 'call_cost', 'max_appointment','wrecker_service_type', 'status', 'deleted_at', 'created_at', 'updated_at'];
    

    public static function add_Service_by_appointment_details($request, $workshop_wrecker_Service_id, $service_call_price) {
        return WorkshopWreckerServiceDetails::updateOrCreate(
            [   
                'workshop_wrecker_services_id' => $workshop_wrecker_Service_id,
				'wrecker_service_type' => 1
            ] ,
            [   
                'workshop_wrecker_services_id' => $workshop_wrecker_Service_id,
				'total_time_arrives' => $request->service_time_arrives,
				'hourly_cost' => $request->service_hourly_rate,
				'cost_per_km' => $request->servicecost_per_km,
				'max_appointment' => $request->service_max_appointment ,
				'call_cost' => $service_call_price ,
				'wrecker_service_type' => 1 ,
				'status' =>'A',
            ]
        );
    }
    
    public static function add_emergency_Service_details($request, $workshop_wrecker_Service_id, $emergency_max_appointment) {
        return WorkshopWreckerServiceDetails::updateOrCreate(
            [  
                'workshop_wrecker_services_id' => $workshop_wrecker_Service_id,
				'wrecker_service_type' => 2
            ] ,
            [   
                'workshop_wrecker_services_id' => $workshop_wrecker_Service_id,
				'total_time_arrives' => $request->emergency_time_arrives,
				'hourly_cost' => $request->emergency_hourly_rate,
				'cost_per_km' => $request->emergencycost_per_km,
				'call_cost' => $request->emergency_service_call_price ,
				'max_appointment' => $emergency_max_appointment ,
				'wrecker_service_type' => 2,
				'status' =>'A',
            ]
        );
    }
}
