<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use App\Library\orderHelper;

class Products_order extends Model{

    protected  $table = "products_orders";
    protected $fillable = ['id', 'users_id','user_details_id','seller_id','workshop_id','transaction_id' , 'no_of_products','total_price', 'total_vat',  'total_discount','order_date', 'shipping_address_id' , 'address_type' , 'tracking_id', 'payment_status', 'courier_id', 'status', 'deleted_at' , 'created_at' , 'update_at'];

	public $order_status = ["I" => "In Process", "D" => "Dispatched", "IN" => "Intransit", "F" => "Delivered", "P" => "Pending"];
	public $payment_mode_status = [1=>'Online' , 2=>'Cash on delivery '];

		
	public static function orders($request , $columns){
	    $column = $request->input('order.0.column');
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');	
		if(!empty($request->start_date) && !empty($request->end_date)){
			return DB::table('products_orders as a')
				->join('users as u' , [['u.id' , '=' , 'a.users_id']])
				->leftJoin('users as w' , [['w.id' , '=' , 'a.seller_id']])
				->select('a.*' , 'u.f_name as customer_fname' , 'u.l_name as customer_lname' , 'u.mobile_number as customer_mobile' , 'u.email as customer_email' , 'u.user_name as customer_username', 'w.f_name as workshop_f_name' , 'w.l_name as workshop_l_name' , 'w.company_name as workshop_company_name' , 'w.mobile_number as workshop_mobile_number' , 'w.email as workshop_email') 
				->offset($start)->limit($limit)->orderBy($order , $dir)
				->whereBetween('order_date' , [$request->start_date , $request->end_date])->where([['status' , '=' , $request->status]]) 
				->get();
		}
		return DB::table('products_orders as a')
				->join('users as u' , [['u.id' , '=' , 'a.users_id']])
				->leftJoin('users as w' , [['w.id' , '=' , 'a.seller_id']])
				->select('a.*' , 'u.f_name as customer_fname' , 'u.l_name as customer_lname' , 'u.mobile_number as customer_mobile' , 'u.email as customer_email' , 'u.user_name as customer_username', 'w.f_name as workshop_f_name' , 'w.l_name as workshop_l_name' , 'w.company_name as workshop_company_name' , 'w.mobile_number as workshop_mobile_number' , 'w.email as workshop_email') 
				->offset($start)->limit($limit)->orderBy($order , $dir)
				->get();
	}
	

	public  static function users_orders($user_id = NULL){
		if($user_id == NULL){
		    return DB::table('products_orders as a')
			->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
			->select('a.*' , 'u.f_name')
			->get();
		  }
		
		return DB::table('products_orders as a')
			->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
			->where([['seller_id' , '=' , $user_id]])
			->select('a.*' , 'u.f_name')
			->get();
	  }

    public static function get_all_orders($product_order_id = NULL) {
		if(!empty($product_order_id)){
            return DB::table('products_orders as a') 
				  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
				  ->where('a.users_id' , '=' , $product_order_id)
				  ->select('a.*' , 'u.f_name')
				  ->first(); 
		}
        return DB::table('products_orders as a')
		  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
		  ->select('a.*' , 'u.f_name')
		  ->get();
	}

	public static function get_seller_orders($seller_id) {
		if(!empty($seller_id)){
            return DB::table('products_orders as a') 
				  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
				  ->where('a.seller_id' , '=' , $seller_id)
				  ->select('a.*' , 'u.f_name')
				  ->get(); 
		}
	}

	/*public static function get_order_detail($order_id) {
		if(!empty($order_id)) {
			return DB::table('products_orders as a') 
						->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
						->where('a.id' , '=' , $order_id)
						->select('a.*' , 'u.f_name')
						->first(); 
		}
	}*/
	public static function get_order_detail($order_id) {
		if(!empty($order_id)) {
			return DB::table('products_orders as a') 
						->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
						->where('a.id' , '=' , $order_id)
						->select('a.*' , 'u.f_name', 'u.roll_id', 'u.email', 'u.mobile_number')
						->first(); 
		}
	}
	public static function insert_products($input ,$request = null){
			$data=Products_order::create([
			'users_id' => $input['users_id'],
			'seller_id' => $input['seller_id'],
			'transaction_id' => $input['transaction_id'],
			'no_of_products' => $input['no_of_products'],
			'total_price' =>	$input['total_price'],
			'order_date' =>	$input['order_date'],
			'shipping_address_id' =>	$input['shipping_address_id'],
			'address_type' =>	$input['address_type'],
			'tracking_id' => $input['tracking_id'],
			'payment_mode' => $input['payment_mode'],
			'payment_status'=> $input['payment_status'],
			'courier_id'=> $input['courier_id'],
			'status'=> $input['status']	
		]);
		//$last_id=$data->id;
		//foreach($input['product_key'] as $product){
		//}
		return true;
	}
	public static function best_seller_products_id(){
		$product_data= DB::table('products_order_descriptions')->get();
		return $product_data->toArray();
		
	}
	
	public static function best_seller_products_count($product_id){
		$product_data= DB::table('products_order_descriptions')->where('products_id',$product_id)->count();
		return $product_data;
		
	}
	public static function get_order($selected_date , $user_id = NULL,  $count = NULL){
		if($count != NULL){
		   if($user_id != NULL){
			    return DB::table('products_orders')->whereDate('created_at' , $selected_date)->where([['seller_id' , '=' , $user_id]])->count(); 
			  }	
		   return DB::table('products_orders')->whereDate('created_at' , $selected_date)->count();
		} 
		
		if($user_id != NULL){
		  return  DB::table('products_orders as a')
				->leftjoin('users as cust' , 'cust.id' , '=' , 'a.users_id')
				->leftjoin('users as u' , 'u.id' , '=' , 'a.seller_id')
				->select('a.*' , 'u.company_name','cust.f_name' , 'cust.l_name')
				->where([['a.seller_id' , '=' , $user_id]])
				->whereDate('a.created_at' , $selected_date)->get();
		}
		
		return  DB::table('products_orders as a')
				->leftjoin('users as cust' , 'cust.id' , '=' , 'a.users_id')
				->leftjoin('users as u' , 'u.id' , '=' , 'a.seller_id')
				->leftjoin('users as u' , 'u.id' , '=' , 'a.seller_id')
				->select('a.*' , 'u.company_name','cust.f_name' , 'cust.l_name')
				->whereDate('a.created_at' , $selected_date)->get();
	  }

	   /*Manage Order on service boking*/
		public static function save_order($request , $discount = 0 , $price = 0 , $for_assemble = NULL , $after_discount_price = 0 , $car_maintenace = 0){
			$product_order = Products_order::where([['users_id' ,'=' ,Auth::user()->id] ,['status' ,'=' ,'P']])->first();
			
			$vat = orderHelper::calculate_vat_price($price);
			if($product_order == NULL){
					if($for_assemble != NULL){
						$number_of_product =  2;
					} else {
						$number_of_product =  1;
					}
                    return  Products_order::Create([
							'users_id' =>Auth::user()->id,
							'seller_id' =>$request->seller_id,
							'workshop_id' =>$request->workshop_id,
							'no_of_products'=>$number_of_product,
							'total_price'=>$price,
							'total_vat'=>$vat,
							'total_discount'=>$discount,
							'order_date' =>date('Y-m-d H:i:s'),
							'user_details_id'=>$request->selected_car_id,
							'payment_status'=>'P',
							'status'=>'P',
						]);
			}
			$discount = $product_order->total_discount + $discount;
			$price = $product_order->total_price + $after_discount_price;
			$total_vat = $product_order->total_vat + $vat;
			if($car_maintenace == 0){
				if($for_assemble != NULL){
					$number_of_product = $product_order->no_of_products + 2;
				} else {
					$number_of_product = $product_order->no_of_products + 1;
				}	
			}else{
				$number_of_product = $product_order->no_of_products;
			}
			$product_order->update(['total_discount'=>$discount , 'total_price'=>$price ,  'total_vat'=>$total_vat, 'no_of_products'=>$number_of_product]);	
			return $product_order;
		    //  return  $product_order->increment('no_of_products')->increment('total_price' ,$discount)->increment('total_discount' , $price);	
			}
		 /*End*/ 
	


}
