<?php
namespace App;
use Auth;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model{

    public  $table = "coupons";
    protected $fillable = [
        'id', 'users_id', 'coupon_type' , 'users_in_group','workshop_list' ,'coupon_title', 'coupon_quantity', 'per_user_allot', 'launching_date', 'closed_date', 'avail_date', 'avail_close_date', 'coupon_image',  'offer_type', 'amount' , 'discount_condition' , 'status', 'deleted_at','created_at' , 'updated_at'];

    public static function add_coupon($request , $coupon_image){
		$workshop_list = json_encode($request->workshop_list);
        return Coupon::updateOrCreate(['id'=>$request->edit_coupon_id] , [
            'users_id' => Auth::user()->id , 
            'coupon_type' => $request->coupon_type,
			'users_in_group'=>$request->number_of_user_in_group, 
            'coupon_title' => $request->coupon_title, 
            'coupon_quantity' => $request->coupon_quantity, 
            'per_user_allot' => $request->per_user_allot, 
            'launching_date' => date('Y-m-d H:i:s' , strtotime($request->launching_date)), 
            'closed_date' => date('Y-m-d H:i:s' , strtotime($request->closed_date)), 
            'avail_date' => date('Y-m-d H:i:s' , strtotime($request->avail_date)), 
            'avail_close_date' => date('Y-m-d H:i:s' , strtotime($request->avail_close_date)),           'offer_type'=>$request->offer_type,
			'amount'=>$request->amount,
			'discount_condition'=>$request->select_dscount_on_products_service,
			'coupon_image' =>$coupon_image,
			'workshop_list' => $workshop_list,
            'status' => 1
             ]);
    }
    
    public static function add_service_coupon($request , $coupon_image){
        return Coupon::updateOrCreate(['id'=>$request->edit_id] , [
            'users_id' => Auth::user()->id , 
            'coupon_type' => $request->coupon_type , 
            'coupon_title' => $request->coupon_title , 
            'coupon_quantity' => $request->coupon_quantity , 
            'per_user_allot' => $request->per_user_allot , 
            'offer_type' => $request->offer_type, 
            'amount' => $request->amount, 
            'launching_date' => date('Y-m-d H:i:s' , strtotime($request->launching_date)), 
            'closed_date' => date('Y-m-d H:i:s' , strtotime($request->closed_date)), 
            'avail_date' => date('Y-m-d H:i:s' , strtotime($request->avail_date)), 
            'avail_close_date' => date('Y-m-d H:i:s' , strtotime($request->avail_close_date)), 
            'coupon_image' =>$coupon_image, 
            'status' => 1
             ]);
    }
            
    public static function get_all_coupon(){
        //return Coupon::orderBy('created_at' , 'DESC')->get();
        return Coupon::where('deleted_at' , '=' , NULL)->orderBy('created_at' , 'DESC')->paginate(10);
    }

    public static function edit_coupon($request ,$id, $image){
	    $image_url = url("storage/coupon_image/$image");
        return Coupon::where('id' , '=' , $request->edit_id)
            ->update(['coupon_type'=>$request->coupon_type, 'coupon_title'=>$request->coupon_title, 'coupon_quantity'=>$request->coupon_quantity, 'coupon_image'=>$image, 'coupon_image_url'=>$image_url , 'status'=>1]);
    } 
    public static function get_all_coupon_list($request){
        return Coupon::where([['discount_condition' ,'=' ,$request['select_dscount_on_products_service']],['deleted_at' , '=' , NULL]])->get();
    }
	public static function get_coupon(){
        return Coupon::where([['status' ,'=' ,1],['deleted_at' , '=' , NULL]])->get();
    }
    public static function get_coupon_info($id){
        return Coupon::where([['id','=', $id]])->first();
    }

    
}