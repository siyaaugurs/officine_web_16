<?php
namespace App\Http\Controllers\API;
use sHelper;
use apiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ManageAdverting;
use App\Coupon;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class CouponController extends Controller{

/*get all coupon */
	public function get_all_coupon(Request $request){
		$get_all_coupon = Coupon::get_coupon();
		if($get_all_coupon){
			return sHelper::get_respFormat(1, null, null, $get_all_coupon);
		} else {
			return sHelper::get_respFormat(0, "Un-expected , please try again .", null, null);
		}
	}
	
/*get all coupon using by select_dscount_on_products_service */
	public function get_coupon(Request $request){
		$get_coupon = Coupon::get_all_coupon_list($request->all());
		if($get_coupon){
			return sHelper::get_respFormat(1, "Get  Coupon", null, $get_coupon);
		} else {
			return sHelper::get_respFormat(0, "Un-expected , please try again .", null, null);
		}
	}
}
