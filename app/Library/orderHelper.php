<?php
namespace App\Library;
use Auth;
use App\Category;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use App\Library\kromedaHelper;
use App\Products;
use App\Model\Kromeda;
use App\ProductsGroupsItem;
use Session;
use DB;
use App\WorkshopServicesPayments;
use App\Products_order;
use App\ServiceBooking;
use App\Library\sHelper;
use App\Services;
use App\Userwallet;
use App\UserwalletHistory;

class orderHelper{

	
	

	public static function manage_service_final_discount($after_discount_price , $price){
		$discount = $price - $after_discount_price;
		return $discount;
   } 

   public static function find_service_name($service){
	  if($service['type'] == 1 || $service['type'] == 3 ||  $service['type'] == 4){
		 $services_detail = Category::where([['id' , '=' ,$service->services_id]])->first();
		  if($services_detail != NULL){
			 return $services_detail->category_name;
		  }
	  }
	  if($service['type'] == 2 || $service['type'] == 7){
		 $services_detail =  \App\MainCategory::find($service->services_id);
		 if($services_detail != NULL){
			 return $services_detail->main_cat_name;
		 }
	  }
	  if($service['type'] == 5){
		   $services_detail =  DB::table('items_repairs_servicestimes')->where([['id' , '=' , $service->services_id]])->first();
		   if($services_detail != NULL){
			   return $services_detail->item." ".$services_detail->front_rear." ".$services_detail->left_right;
		   }
	  }
	  if($service['type'] == 6){
		   $services_detail =  DB::table('wracker_services')->where([['id' , '=' , $service->services_id]])->first();
		   if($services_detail != NULL){
			   return $services_detail->services_name;
		   } 
	  }
   }

   public static function find_product_description($service){
	   $product = [];
	   $product_description = DB::table('products_order_descriptions')->where([['service_booking_id' , '=' , $service->id]])->first();
	   if($product_description != NULL){
		$product['product_image_url'] = $product_description->product_image_url;
		$product['product_name'] = $product_description->product_name;
		$product['product_description'] = $product_description->product_description;
		$product['product_quantity'] = $product_description->product_quantity;
		$product['pfu_tax'] = $product_description->pfu_tax;
		$product['price'] = $product_description->price;
		$product['discount'] = $product_description->discount;
		$product['total_price'] = $product_description->total_price;
	   }
	   return $product;
   }

	 public static function generate_order_xml($order_id){
	    $order_detail = \App\Products_order::where([['id' , '=' , $order_id]])->first();
		if($order_detail != NULL){
			$order_detail->shipping_address = DB::table('addresses')->find($order_detail->shipping_address_id);
			$order_detail->user_detail = DB::table('users')->find($order_detail->users_id);
			$order_detail->workshop_seller_detail = \App\User::company_profile_detail($order_detail->seller_id);
			$order_detail->seller_detail = \App\User::company_profile_detail($order_detail->seller_id);
			$order_detail->spare_parts = self::find__spare_products_in_order($order_detail->id);
			$order_detail->tyres = self::find_tyres_in_order($order_detail->id);
			$order_detail->services = self::find_services($order_detail->id);
			$order_detail->spare_parts_assemble = self::find_spare_assemble_services($order_detail->id);
			$order_detail->tyre_assemble = self::find_tyre_assemble_services($order_detail->id);
		  }
		return $order_detail;
	 }
	 
	 
	public static function find_tyre_assemble_services($order_id){
		$services_arr = [];
		$services = ServiceBooking::where('type',4)->where([['product_order_id' , '=' , $order_id]])->get();
		if($services->count() > 0){
			foreach($services as $service){
				$p_arr = [];
				$p_arr['service_name'] = self::find_service_name($service);
				$p_arr['booking_date'] = $service->booking_date;
				$p_arr['start_time'] = $service->start_time;
				$p_arr['end_time'] = $service->end_time;
				$p_arr['price'] = $service->price;
				$p_arr['discount'] = self::manage_service_final_discount($service->after_discount_price , $service->price);
				$p_arr['total_price'] = $service->after_discount_price;
				$p_arr['product_description'] = self::find_product_description($service);
				$services_arr[] = $p_arr;
			}
		}
		return $services_arr;
	}
	
	
	public static function find_spare_assemble_services($order_id){
		$services_arr = [];
		$services = ServiceBooking::where('type',2)->where([['product_order_id' , '=' , $order_id]])->get();
		if($services->count() > 0){
			foreach($services as $service){
				$p_arr = [];
				$p_arr['service_name'] = self::find_service_name($service);
				$p_arr['booking_date'] = $service->booking_date;
				$p_arr['start_time'] = $service->start_time;
				$p_arr['end_time'] = $service->end_time;
				$p_arr['price'] = $service->price;
				$p_arr['discount'] = self::manage_service_final_discount($service->after_discount_price , $service->price);
				$p_arr['total_price'] = $service->after_discount_price;
				$p_arr['product_description'] = self::find_product_description($service);
				$services_arr[] = $p_arr;

			}
		}
		return $services_arr;
	}

	public static function find_services($order_id){
		/*getting all service apart form assemble service  type = 2 spare assemble service , 4 tyre assemble service*/ 
		$services = ServiceBooking::whereNotIn('type' , [2,4])->where([['product_order_id' , '=' , $order_id]])->get();
		$services_arr = [];
		if($services->count() > 0){
		   foreach($services as $service){
			   $p_arr = [];
			   $p_arr['service_name'] = self::find_service_name($service);
			   $p_arr['booking_date'] = $service->booking_date;
			   $p_arr['start_time'] = $service->start_time;
			   $p_arr['end_time'] = $service->end_time;
			   if($service['type'] == 1){
				  $p_arr['car_size'] =   sHelper::get_car_size($service->car_size);
			   }
				$p_arr['price'] = $service->price;
				$p_arr['discount'] = self::manage_service_final_discount($service->after_discount_price , $service->price);
				$p_arr['total_price'] = $service->after_discount_price;
				$services_arr[] = $p_arr;
		   } 
		}
		return $services_arr;
	}

	 public static function find_tyres_in_order($order_id){
		$tyres =\App\Products_order_description::where([['products_orders_id','=',$order_id] , ['for_order_type' , '=' , 2] , ['for_assemble_service' , '=', NULL]])->get();
		$tyres_arr = [];
		if($tyres->count() > 0){
			foreach($tyres as $product){
				$p_arr = [];
				$p_arr['image'] = $product->product_image_url;
				$p_arr['product_name'] = $product->product_name;
				$p_arr['product_description'] = $product->product_description;
				$p_arr['product_quantity'] = $product->product_quantity;
				$p_arr['price'] = $product->price;
				$p_arr['discount'] = $product->discount;
				$p_arr['total_price'] = $product->total_price;
				$tyres_arr[] = $p_arr;
			 }
		}
		return $tyres_arr;

	 }


	 public static function find__spare_products_in_order($order_id){
	    $products_desc =\App\Products_order_description::where([['products_orders_id','=',$order_id] , ['for_order_type' , '=' , 1] , ['for_assemble_service' , '=', NULL]])->get();
		$products = [];
		if($products_desc->count() > 0){
			foreach($products_desc as $product){
			   $p_arr = [];
			   $p_arr['image'] = $product->product_image_url;
			   $p_arr['product_name'] = $product->product_name;
			   $p_arr['product_description'] = $product->product_description;
			   $p_arr['product_quantity'] = $product->product_quantity;
			   $p_arr['price'] = $product->price;
			   $p_arr['discount'] = $product->discount;
			   $p_arr['total_price'] = $product->total_price;
			   $products[] = $p_arr;
			}
		  }
		 return $products; 
	 }

	 /**/
	public static function calculate_tyre_total_price($total_price){
		$vat_percentage = 22;
		$vat_price =  ($total_price * $vat_percentage) / 100;
       return $vat_price;
	} 
	
	public static function calculate_vat_price($total_price){
		$vat_percentage = 22;
		$vat_price =  ($total_price * $vat_percentage) / 100;
       	return $vat_price;
	} 

	 
	 /*Find tyre service booking list*/
	 public static function service_booking_list($type , $user_id = NULL){
		if($user_id != NULL){
			$services = ServiceBooking::where([['workshop_user_id' , '=' , $user_id] , ['type' , '=' , $type]])->get();
		} else {
			$services = ServiceBooking::where([['type' , '=' , $type]])->get();
		}
	 	$services_arr = [];
		if($services->count() > 0){
		   	foreach($services as $service){
				$p_arr = [];
				$p_arr['id'] = $service->id;
				$p_arr['workshop_id'] = $service->workshop_user_id;
				$p_arr['workshop_name'] = self::find_workshop($service);
				$p_arr['customer_id'] = $service->users_id;
				$p_arr['customer_ids'] =  base64_encode($service->users_id);
				$p_arr['customer_name'] = self::find_customer($service);
				$p_arr['service_name'] = self::find_service_name($service);
				$p_arr['booking_date'] = sHelper::convert_italian_time($service->booking_date);
				$p_arr['start_time'] = sHelper::change_time_format_2($service->start_time);
				$p_arr['end_time'] = sHelper::change_time_format_2($service->end_time);
				if($service['type'] == 1){
					$p_arr['car_size'] =   sHelper::get_car_size($service->car_size);
				}
				$p_arr['price'] = $service->price;
				$p_arr['vat'] = $service->service_vat;
				$p_arr['discount'] = $service->discount;
				$p_arr['total_price'] = $service->after_discount_price;
				$p_arr['status'] = $service->status;
				$p_arr['wrecker_service_type'] = $service->wrecker_service_type;
				$p_arr['created_at'] = sHelper::convert_italian_time($service->created_at);
				$services_arr[] = (object) $p_arr;
		   } 
		}
		return  $services_arr;
	 }
	 /*End*/


	 public static function  find_workshop($service){
		$workshop =  DB::table('business_details')->where([['users_id' , $service->workshop_user_id]])->first();
		if($workshop != NULL){
			return $workshop->business_name;
		}
	}

	public static function  find_customer($service){
		$users = \App\User::find($service->users_id);
		if($users != NULL){
			return $users->f_name." ".$users->l_name;
		}
	}

}