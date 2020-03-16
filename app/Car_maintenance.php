<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car_maintenance extends Model
{
    public  $table = "car_maintenances";
    protected $fillable = ['id', 'users_id', 'service_name', 'hourly_rate', 'status', 'deleted_at', 'created_at' , 'updated_at'];
    
    public static function get_all_services() {
        return Car_maintenance::where([['deleted_at', '=', NULL]])->get();
    }
}
