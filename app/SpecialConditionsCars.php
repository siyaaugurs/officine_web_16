<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SpecialConditionsCars extends Model
{
    public  $table = "special_conditions_cars";
    protected $fillable = ['id', 'service_special_conditions_id', 'cars_id', 'cars_name','created_at', 'updated_at', 'deleted_at'];

    public static function get_special_service_cars($service_id) {
        if(!empty($service_id)) {
            $result = DB::table('special_conditions_cars as sc')
                            ->select('sc.*' )
                            ->where([['sc.service_special_conditions_id' , $service_id], ['sc.deleted_at', '=', NULL]])->get();
            return $result;
        }
    }

    public static function delete_cars($car_id) {
        return SpecialConditionsCars::where('id' , '=' , $car_id)
            ->update(['deleted_at'=>date('Y-m-d H:i:s')]);
    }
}
