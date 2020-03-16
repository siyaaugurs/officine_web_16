<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class WorkshopCarMaintinanceServiceDetails extends Model
{
    protected  $table = "workshop_car_maintinance_service_details";
    protected $fillable = [
        'id', 'workshop_id', 'items_repairs_servicestimes_id','hourly_cost','max_appointment', 'deleted_at','created_at','updated_at'];

   public static function save_car_maintainance_details($request) {
        return WorkshopCarMaintinanceServiceDetails::updateOrcreate(
                                    [
                                        'items_repairs_servicestimes_id'=>$request->items_repairs_servicestimes_id,
                                        'workshop_id' => Auth::user()->id,
                                    ],
                                    [
                                        'workshop_id' => Auth::user()->id,
                                        'items_repairs_servicestimes_id'=>$request->items_repairs_servicestimes_id, 
                                        'hourly_cost'=>$request->hourly_rate,
                                        'max_appointment'=>$request->max_appointment,
                                    ]);
    }
    public static function get_maintainance_details($car_maintainance_id) {
        return WorkshopCarMaintinanceServiceDetails::where([['items_repairs_servicestimes_id', '=', $car_maintainance_id], ['workshop_id', '=', Auth::user()->id ]])->first();
    }
    public static function get_workshop_maintainance_details($car_maintainance_id) {
        return WorkshopCarMaintinanceServiceDetails::where([['items_repairs_servicestimes_id', '=', $car_maintainance_id], ['workshop_id', '=', Auth::user()->id ]])->first();
    }

    public static function update_car_maintainance_details($request) {
        return WorkshopCarMaintinanceServiceDetails::where('items_repairs_servicestimes_id' , '=' , $request->items_repairs_servicestimes_id)
            ->update(['hourly_cost'=>$request->hourly_rate, 'max_appointment'=>$request->max_appointment]);
    }
}
