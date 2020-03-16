<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class User_car_revision extends Model
{
    public  $table = "user_car_revisions";
    protected $fillable = ['id', 'users_id', 'user_details_id', 'workshop_user_id', 'again_revision_date', 'status', 'created_at' , 'updated_at'];
    
    public static function get_all_bookings($workshop_user_id) {

        if($workshop_user_id != NULL){
            return DB::table('user_car_revisions as a')
                   ->leftjoin('users as u' , 'a.users_id','=','u.id')
                   ->leftjoin('user_details as ud' , 'a.user_details_id','=','ud.id') 
                   ->where('a.workshop_user_id' , '=' , $workshop_user_id)
                   ->select('a.*' , 'u.f_name' ,'u.mobile_number' ,'u.email' , 'ud.km_of_cars' , 'ud.km_traveled_annually' , 'ud.revision_date_km' , 'ud.revision_date', 'ud.revesion_km')
                   ->paginate(10); 
        }
    }
    public static function get_bookings_details($booking_id) {
        return DB::table('user_car_revisions as a')
                   ->leftjoin('users as u' , 'a.users_id','=','u.id')
                   ->leftjoin('user_details as ud' , 'a.user_details_id','=','ud.id') 
                   ->where('a.id' , '=' , $booking_id)
                   ->select('a.*' , 'u.f_name' ,'u.mobile_number' ,'u.email' , 'ud.km_of_cars' , 'ud.km_traveled_annually' , 'ud.revision_date_km' , 'ud.revision_date', 'ud.revesion_km')
                   ->first(); 
    }

}
