<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class WorkshopMotServiceDetails extends Model
{
    protected  $table = "workshop_mot_service_details";
    protected $fillable = [
        'id',
        'workshop_id',
        'service_id',
        'max_appointment',
        'hourly_cost',
        'type',
        'type_status',
        'status',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static function get_service_details($workshop_id , $service_id , $type) {
		return WorkshopMotServiceDetails::where([['workshop_id' , '=' ,$workshop_id] , ['service_id', '=', $service_id] , ['type' , '=' , $type]])->first();
    }
    
    public static function mot_service_details($service_type, $service_id, $max_appointment, $hourly_rate) {
        if($service_type == 1) {
            $type_status = "Kromeda MOT";
        } else {
            $type_status = "Our MOT";
        }
        return  WorkshopMotServiceDetails::updateOrcreate([
            'workshop_id' => Auth::user()->id,
            'service_id' => $service_id,
            'type' => $service_type
        ], 
        [
            'workshop_id' => Auth::user()->id,
            'service_id' => $service_id,
            'max_appointment' => $max_appointment,
            'hourly_cost' => $hourly_rate,
            'type' => $service_type,
            'type_status' => $type_status,
        ]);
    }
}
