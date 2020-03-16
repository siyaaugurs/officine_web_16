<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car_revision_booking extends Model
{
    public  $table = "car_revision_bookings";
    protected $fillable = ['id', 'users_id', 'user_car_revisions_id', 'service_id', 'service_name', 'service_price', 'created_at' , 'updated_at'];

    public static function get_added_services($booking_id) {
        if(!empty($booking_id)){
            return Car_revision_booking::where([['user_car_revisions_id' , '=' , $booking_id]])->get();
        }
    }
    public static function save_car_revision_services($request, $booking_detail) {
        foreach($request->records as $record){
            Car_revision_booking::updateOrcreate(
                ['user_car_revisions_id'=>$request->booking_id , 'service_id'=>$record['service_id']],

                ['user_car_revisions_id'=>$request->booking_id ,
                'users_id'=>$booking_detail ,                  
                'service_id'=>$record['service_id'] ,                  
                'service_name'=>$record['service_name'] , 
                'service_price'=>$record['service_price'] , 
                ]
               
            );	 							  
        }
    }
    public static function delete_selected($booking_id) {
        if(!empty($booking_id)) {
            return Car_revision_booking::where([['user_car_revisions_id' , '=' , $booking_id]])->delete();
        }
    }
}
