<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class CouponDetails extends Model{
	
	public  $table = "coupon_details";
    protected $fillable = [
        'id', 'coupons_id', 'shipping_amount', 'product_type', 'product_product_id', 'services_id', 'service_category_id', 'brand', 'deleted_at', 'created_at', ' updated_at'];
	
   
	
    
}
