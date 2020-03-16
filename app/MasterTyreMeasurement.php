<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterTyreMeasurement extends Model
{
    protected $table = "master_tyre_measurements";
    protected $fillable = ['id', 'name'  , 'code', 'code2', 'value', 'type','created_at' , 'updated_at' , 'deleted_at'];

    public $tyre_measurement_type = [1 => "Tyre Type" , 2 => 'Season Type', 3 => 'Speed Index', 4 => 'Aspect Index', 5 => 'Diameter' , 6=>'Width'];
  
    
    public static function add_tyre_type_measure($request) {
        return MasterTyreMeasurement::updateOrcreate(
            ['id'=>$request->tyre_type_id],

            [
                'name' => $request->tyre_type_name ,
                'code' => json_encode($request->type_code) ,                  
                'type' => 1 
            ]
           
        );
    }

    public static function get_type_measurement($type) {
        return MasterTyreMeasurement::where([['type' , '=' , $type], ['deleted_at', '=', NULL]])->get();
    }
    public static function get_tyre_measurement_details($request) {
        return MasterTyreMeasurement::where([['type' , '=' , $request->measure_type], ['id', '=', $request->measure_id],['deleted_at', '=', NULL]])->first();
    }

    public static function delete_tyre_measure($measure_id) {
        return MasterTyreMeasurement::where([['id' , '=' , $measure_id]])->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }

    public static function add_season_type_measure($request) {
        return MasterTyreMeasurement::updateOrcreate(
            ['id'=>$request->season_type_id],

            [
                'name' => $request->season_type_name ,
                'code2' => $request->season_code ,                  
                'type' => 2 
            ]
           
        );
    }
    public static function add_aspect_ratio_measure($aspect_ratio, $request) {
        return MasterTyreMeasurement::updateOrcreate(
            ['id'=>$request->aspect_id],

            [
                'value' => $aspect_ratio ,                
                'type' => 4 
            ]
           
        );
    }
    public static function add_speed_index_measure($speed_index, $request) {
        return MasterTyreMeasurement::updateOrcreate(
            ['id'=>$request->speed_index_id],

            [
                'name' => $speed_index ,                
                'type' => 3 
            ]
           
        );
    }
    public static function add_diameter_measure($diameter_value, $request) {
        return MasterTyreMeasurement::updateOrcreate(
            ['id'=>$request->speed_index_id],

            [
                'value' => $diameter_value ,                
                'type' => 5 
            ]
           
        );
    }
    public static function add_width_measure($width_value, $request) {
        return MasterTyreMeasurement::updateOrcreate(
            ['id'=>$request->width_id],

            [
                'value' => $width_value ,                
                'type' => 6 
            ]
           
        );
    }

}


