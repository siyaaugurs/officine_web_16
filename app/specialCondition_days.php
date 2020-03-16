<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class specialCondition_days extends Model
{
    public  $table = "special_condition_days";
    protected $fillable = ['id', 'service_special_conditions_id', 'days_id', 'status','created_at', 'updated_at', 'deleted_at'];

    public static function get_special_service_days($service_id) {
        if(!empty($service_id)) {
            $result = DB::table('special_condition_days as sd')
                            ->leftjoin('common_weekly_days as d' , 'sd.days_id' , '=' , 'd.id')->select('sd.*' , 'd.name' )
                            ->where([['sd.service_special_conditions_id' , $service_id], ['sd.deleted_at', '=', NULL]])->get();
            return $result;
        }
    }

    public static function delete_weekly_days($day_id) {
        return specialCondition_days::where('id' , '=' , $day_id)
            ->update(['deleted_at'=>date('Y-m-d H:i:s'), 'status' => 'P']);
    }
    public static function special_weekly_days($day_id,$service_id) {
        $result = DB::table('special_condition_days as sd')
                          ->leftjoin('common_weekly_days as d' , 'sd.days_id' , '=' , 'd.id')->select('sd.*' , 'd.name' )
                          ->where([['sd.service_special_conditions_id' , $service_id], ['sd.days_id' , $day_id], ['sd.deleted_at', '=', NULL]])->get();
          return $result;       
  }
}
