<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Services_coupon extends Model{
    
    protected  $table = "services_coupons";
    protected $fillable = [
        'id', 'users_id','services_id' , 'services_packages_id', 'coupons_id', 'deleted_at',  'created_at', 'updated_at'];

    public static function add_coupons_details($coupon_id, $service_id, $package_id){
        //return $coupon_id."/".$service_id."/".$package_id;
        return Services_coupon::create([
            'users_id' => Auth::user()->id , 
            'services_id' => $service_id,
            'services_packages_id' => $package_id,
            'coupons_id' => $coupon_id
        ]);
    }
        
}
