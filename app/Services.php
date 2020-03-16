<?php

namespace App;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class Services extends Model {
	protected $table = "services";
	protected $fillable = [
		'id', 'users_id', 'category_id', 'products_id', 'about_services','car_size', 'hourly_rate', 'max_appointment','status', 'is_deleted_at', 'type', 'created_at', 'updated_at'];
	/*
		Car Washing  type = 1
		assemble type = 2
	*/
	public static function get_service_details($workshop_id , $service_id , $car_size) {
		return Services::where([['users_id' , '=' ,$workshop_id] , ['category_id', '=', $service_id] , ['car_size' , '=' , $car_size]])->first();
	}
	
	public static function car_wash_deatails($request){
	    return  Services::updateOrcreate(
										[   'users_id'=>Auth::user()->id,
											'category_id' =>$request->category_id,
											'car_size' =>$request->car_size
										] ,
										[	'users_id'=>Auth::user()->id,
											'category_id'=>$request->category_id,
											'car_size' =>$request->car_size,
											'hourly_rate'=>$request->hourly_rate, 
											'max_appointment'=>$request->max_appointment,
											'type' =>1
										]); 
	}

	
	public static function update_service_details($request) {
		return Services::where('id' , '=' , $request->washing_service_id)
            ->update(['hourly_rate'=>$request->hourly_rate, 'max_appointment'=>$request->max_appointment]);
	}
	
	public static function get_car_wash_services_workshop_new($service_id , $car_size ){
		return DB::table('users_categories as uc')
		    ->join('services as s' , 's.users_id' , '=' , 'uc.users_id')
			->join('users as u', 'u.id', '=', 's.users_id')
			->join('business_details as bd', 'bd.users_id', '=', 'u.id')
			->select("s.*", 'u.created_at', 'u.updated_at', 'u.f_name', 'u.l_name','u.profile_image', 'u.mobile_number', 'u.company_name', 'bd.owner_name',
			 'bd.business_name', 'bd.registered_office', 'bd.about_business')
			->where([['s.category_id' , '=' , $service_id], ['s.car_size', '=', $car_size] , ['categories_id' , '=' , 1] , ['uc.deleted_at' , '=' , NULL]])
			->get();
   	}
   	
   	public static function get_carwash_workshop_details($workshop_id){
	/*Working*/	
		return DB::table('users_categories as uc')
			->join('users as u', 'u.id', '=', 'uc.users_id')
			->join('business_details as bd', 'bd.users_id', '=', 'u.id')
			->select('u.created_at', 'uc.users_id','u.updated_at', 'u.f_name', 'u.l_name','u.profile_image', 'u.mobile_number', 'u.company_name', 'bd.owner_name',
			 'bd.business_name', 'bd.registered_office', 'bd.about_business')
			->where([['categories_id' , '=' , 1] , ['uc.deleted_at' , '=' , NULL] , ['uc.users_id' , '=' , $workshop_id]])
			->first();
   	}
   	
	public static function get_car_wash_services_workshop_new1($service_id , $car_size ,$off_days_workshop_users){
		return DB::table('users_categories as uc')
			->join('users as u', 'u.id', '=', 'uc.users_id')
			->join('business_details as bd', 'bd.users_id', '=', 'u.id')
			->select('u.created_at', 'uc.users_id','u.updated_at', 'u.f_name', 'u.l_name','u.profile_image', 'u.mobile_number', 'u.company_name', 'bd.owner_name',
			 'bd.business_name', 'bd.registered_office', 'bd.about_business')
			->where([['categories_id' , '=' , 1] , ['uc.deleted_at' , '=' , NULL]])
			->whereNotIn('u.id', $off_days_workshop_users)
			->get();
	   }
	   
	   public static function get_car_wash_services_workshop_service_quotes($service_id , $car_size , $off_days_workshop_users , $request){
		return DB::table('users_categories as uc')
			->join('users as u', 'u.id', '=', 'uc.users_id')
			->join('business_details as bd', 'bd.users_id', '=', 'u.id')
			->select('u.created_at', 'uc.users_id','u.updated_at', 'u.f_name', 'u.l_name','u.profile_image', 'u.mobile_number', 'u.company_name', 'bd.owner_name',
			 'bd.business_name', 'bd.registered_office', 'bd.about_business')
			->where([['categories_id' , '=' ,$request->category_id] ,['for_quotes','=' ,1] ,['uc.deleted_at' , '=' , NULL]])
			->whereNotIn('u.id', $off_days_workshop_users)
			->get();
   	}
	public static function add_service_real_time($car_washing_service , $key) {
		return Services::updateOrcreate(
		    ['users_id' => Auth::user()->id,
			'category_id' =>$car_washing_service->id,
			'car_size' =>$key] 
			,
			['users_id' => Auth::user()->id,
			'category_id' =>$car_washing_service->id,
			'car_size' =>$key,
			'status' =>1,
			'type' =>1,
		]);
	}
	
	public static function add_assemble_services($request, $category_id) {
		return Services::create([
			'users_id' => Auth::user()->id,
			'category_id' => $category_id,
			'products_id' => $request->inventory_product,
			'about_services' => $request->about_services,
			'service_average_time' => $request->service_average_time,
			'type' => 2,
			'status' => 1,
		]);
	}
	
	
	public static function add_services($category_id , $car_size) {
		return Services::updateOrcreate(['users_id'=>Auth::user()->id , 'category_id'=>$category_id , 'car_size'=>$car_size],
		 [
			'users_id' => Auth::user()->id,
			'category_id' => $category_id,
			'car_size' => $car_size,
			'status' => 1,
			'type' => 1,
		]);
	}
	
	public static function add_car_wash_service($data_arr , $request){
	  return  Services::updateOrcreate(['users_id' => Auth::user()->id ,
	                                    'category_id'=>$request->category_id , 
	                                    'car_size' =>$data_arr['car_size'],
									   ] ,
	       ['users_id' => Auth::user()->id,
			'category_id' =>$request->category_id,
			'about_services'=>$request->about_services,
			'service_average_time'=>$data_arr['time'],
			'service_hourly_rate'=>$request->hourly_rate,
			'calculated_price'=>$data_arr['price'],
			'max_appointment'=>$data_arr['appointment'] ,
			'car_size' =>$data_arr['car_size'],
			'status' =>1,
			'type' =>1,
		]);
	}

	public static function get_services_record($category_id , $car_size = NULL) {
		return Services::where([['users_id', '=', Auth::user()->id], ['category_id', '=', $category_id], ['car_size', '=', $car_size] , ['is_deleted_at' , '=' , NULL] ])->first();
	}
	
	
	public static function get_assembly_services_workshop($products_id , $workshop_user_id = NULL) {
			return DB::table('services as s')
				->join('users as u', 'u.id', '=', 's.users_id')
				->join('business_details as b', 'b.users_id', '=', 's.users_id')
				->select("s.*", 'b.owner_name', 'b.business_name', 'b.address_2', 'b.address_3', 'b.about_business', 'b.registered_office', 'u.f_name', 'u.l_name',
					'u.profile_image', 'u.mobile_number', 'u.company_name')
				->where([['s.products_id', '=', $products_id], ['s.users_id', '=', $workshop_user_id], ['u.users_status', '!=', 'B'] , ['s.is_deleted_at' , '=' , NULL]])
				->first();
		}
   
   
   public static function get_car_wash_services_workshop($workshop_id = NULL , $off_days_workshop_users = NULL){
	   if($workshop_id != NULL){
		   return DB::table('users_categories as a')
					   //->join('workshop_service_payments as b' , [['workshop_id' , '=' , 'a.users_id'] , ['b.category_type' ,'=' , 'a.categories_id']])
					   ->join('users as u', 'u.id', '=', 'a.users_id')
					   ->join('business_details as bd', 'bd.users_id', '=', 'a.users_id')
					   ->select('a.id' , 'a.users_id' , 'bd.owner_name', 'bd.business_name', 'bd.registered_office', 'bd.about_business','bd.address_2' ,'u.created_at','u.updated_at', 'bd.address_3', 'u.f_name', 'u.l_name',
				'u.profile_image', 'u.mobile_number', 'u.company_name')
				       ->where([['categories_id' , '=' , 1] , ['deleted_at' , '=' , NULL] , ['u.users_status', '=', 'A'] , ['a.users_id' , '=',  $workshop_id]])
					   ->first();
		 }
		 return DB::table('users_categories as a')
				   ->join('workshop_service_payments as b' , [['workshop_id' , '=' , 'a.users_id'] , ['b.category_type' ,'=' , 'a.categories_id']])
				   ->join('users as u', 'u.id', '=', 'a.users_id')
				   ->join('business_details as bd', 'bd.users_id', '=', 'u.id')
				   ->select('a.id' , 'a.users_id' , 'bd.owner_name', 'bd.business_name', 'bd.registered_office', 'bd.about_business','u.created_at','u.updated_at','u.f_name', 'u.l_name',
			'u.profile_image', 'u.mobile_number', 'u.company_name', 'b.hourly_rate' , 'b.maximum_appointment')
			       ->where([['categories_id' , '=' , 1] , ['deleted_at' , '=' , NULL] , ['u.users_status', '=', 'A']])
				   ->whereNotIn('u.id', $off_days_workshop_users)
				   ->get(); 
   }
   
  /*  public static function get_car_wash_services_workshop($services_id, $workshop_user_id = NULL, $off_workshop_users = NULL, $selected_date = NULL, $car_size = null) {
		if ($workshop_user_id != NULL) {
			return DB::table('services as s')
				->join('users as u', 'u.id', '=', 's.users_id')
				->join('business_details as b', 'b.users_id', '=', 's.users_id')
				->join('workshop_service_payments as wsp' , 'wsp.workshop_id' , '=' , 's.users_id')
				->select("s.*", 'b.owner_name', 'b.business_name', 'b.address_2', 'b.address_3', 'b.about_business', 'b.registered_office', 'u.f_name', 'u.l_name',
					'u.profile_image', 'u.mobile_number', 'u.company_name' , 'wsp.hourly_rate' , 'wsp.maximum_appointment')
				->where([['wsp.category_type' , '=' , 1]])
				->where([['s.category_id', '=', $services_id], ['s.users_id', '=', $workshop_user_id], ['u.users_status', '!=', 'B'] , ['s.is_deleted_at' , '=' , NULL]])
				->first();
		}
		
	  	return DB::table('services as s')
			->join('services_weekly_days as swd', 'swd.services_id', '=', 's.id')
			->join('users as u', 'u.id', '=', 's.users_id')
			->join('business_details as b', 'b.users_id', '=', 's.users_id')
			->join('workshop_service_payments as wsp' , 'wsp.workshop_id' , '=' , 's.users_id')
			->select("s.*", 'swd.days_id', 'b.owner_name', 'b.business_name', 'b.registered_office', 'b.about_business', 'u.f_name', 'u.l_name',
				'u.profile_image', 'u.mobile_number', 'u.company_name', 'wsp.hourly_rate' , 'wsp.maximum_appointment')
			->where([['s.category_id', '=', $services_id],['u.users_status', '!=', 'B'], ['swd.days_id', '=', $selected_date], ['car_size', '=', $car_size] ,  ['s.is_deleted_at' , '=' , NULL]])
			->where([['wsp.category_type' , '=' , 1]])
			->whereNotIn('u.id', $off_workshop_users)
			->get();	
	}*/
	
	
	/*public static function get_services_workshop($services_id, $workshop_user_id = NULL, $off_workshop_users = NULL, $selected_date = NULL, $car_size = null) {
		if ($workshop_user_id != NULL) {
			return DB::table('services as s')
				->join('users as u', 'u.id', '=', 's.users_id')
				->join('business_details as b', 'b.users_id', '=', 's.users_id')
				->select("s.*", 'b.owner_name', 'b.business_name', 'b.address_2', 'b.address_3', 'b.about_business', 'b.registered_office', 'u.f_name', 'u.l_name',
					'u.profile_image', 'u.mobile_number', 'u.company_name')
				->where([['s.category_id', '=', $services_id], ['s.users_id', '=', $workshop_user_id], ['u.users_status', '!=', 'B'] , ['s.is_deleted_at' , '=' , NULL]])
				->first();
		}
     
        
       	return DB::table('services as s')
			->join('services_weekly_days as swd', 'swd.services_id', '=', 's.id')
			->join('users as u', 'u.id', '=', 's.users_id')
			->join('business_details as b', 'b.users_id', '=', 's.users_id')
			->select("s.*", 'swd.days_id', 'b.owner_name', 'b.business_name', 'b.address_2', 'b.address_3', 'b.registered_office', 'b.about_business', 'u.f_name', 'u.l_name',
				'u.profile_image', 'u.mobile_number', 'u.company_name')
			->where([['s.category_id', '=', $services_id], ['u.users_status', '!=', 'B'], ['swd.days_id', '=', $selected_date], ['car_size', '=', $car_size] ,  ['s.is_deleted_at' , '=' , NULL]])
			->whereNotIn('u.id', $off_workshop_users)
			->get();

	}
   */
	public static function get_assemble_services_workshop($products_id, $workshop_user_id = NULL, $off_workshop_users = NULL, $selected_date = NULL) {
		//return $selected_date;
		if ($workshop_user_id != NULL) {
			return DB::table('services as s')
				->join('users as u', 'u.id', '=', 's.users_id')
				->join('business_details as b', 'b.users_id', '=', 's.users_id')
				->select("s.*", 'b.owner_name', 'b.business_name', 'b.address_2', 'b.address_3', 'b.about_business', 'b.registered_office', 'u.f_name', 'u.l_name', 'u.profile_image', 'u.mobile_number', 'u.company_name')
				->where([['s.products_id', '=', $products_id], ['s.users_id', '=', $workshop_user_id], ['u.users_status', '!=', 'B'], ['s.type', '=', 2] ,  ['s.is_deleted_at' , '=' , NULL] ])
				->first();
		}

		return DB::table('services as s')
			->join('services_weekly_days as swd', 'swd.services_id', '=', 's.id')
			->join('users as u', 'u.id', '=', 's.users_id')
			->join('business_details as b', 'b.users_id', '=', 's.users_id')
			->select("s.*", 'swd.days_id', 'b.owner_name', 'b.business_name', 'b.address_2', 'b.address_3', 'b.registered_office', 'b.about_business', 'u.f_name', 'u.l_name', 'u.profile_image', 'u.mobile_number', 'u.company_name')

			->where([['s.products_id', '=', $products_id], ['u.users_status', '!=', 'B'], ['swd.days_id', '=', $selected_date], ['s.type', '=', 2] , ['s.is_deleted_at' , '=' , NULL] ])
			->whereNotIn('u.id', $off_workshop_users)
			->get();

	}
    
    public static function assemble_service($workshop_user_id){
	  return DB::table('services as s')
	           ->join('products_new as p' , 'p.id','=','s.products_id')
               ->select('s.*' , 'p.listino' )
			   ->where([['s.users_id','=',$workshop_user_id],['s.type','=',2], ['is_deleted_at', '=', NULL]])->paginate(10); 
	}
	
	public static function get_workshop_users_services($workshop_user_id) {
		return DB::table('services as s')
			->join('categories as c', 'c.id', '=', 's.category_id')
			->join('service_time_prices as stp', 'stp.categories_id', '=', 'c.id')
			 ->where([['s.users_id' ,'=' , $workshop_user_id] , ['is_deleted_at' , '=' , NULL]])
			->select('s.*', 'c.category_name' , 'c.description' , 'c.time' , 'stp.small_price' , 'stp.average_price' , 'stp.big_price' , 'stp.small_time' , 'stp.average_time' , 'stp.big_time')
			->paginate(10);
	}
	
	
	
	public static function get_workshop_users_services_all($workshop_user_id) {
		return DB::table('services as s')
			->join('categories as c', 'c.id', '=', 's.category_id')
			 ->join('service_time_prices as stp', 'stp.categories_id', '=', 'c.id')
			 ->where([['s.users_id' ,'=' , $workshop_user_id] , ['is_deleted_at' , '=' , NULL]])
			->select('s.*', 'c.category_name' , 'c.description' , 'c.time' , 'stp.small_price' , 'stp.average_price' , 'stp.big_price' , 'stp.small_time' , 'stp.average_time' , 'stp.big_time')
			->get();
	}
	
	
	
	
	
	public static function get_all_services($user_id) {
	    return Services::join('products_groups','products_groups.id', '=', 'services.category_id')->where([['users_id' , $user_id],['type',2]])->get();
	}
	
	public static function get_category($service_id) {
		return Services::where('id', '=', $service_id)->first();
	}
	
	public static function get_assemble_record($user_id, $product_id = NULL) {
		if ($product_id == NULL) {
			return Services::where([['users_id', '=', $user_id]])->first();
		}
		return Services::where([['users_id', '=', $user_id], ['products_id', '=', $product_id] , ['is_deleted_at' , '=' , NULL] ])->first();
	}
	
	public static function get_assemble_service_record($services_id) {
		if (!empty($services_id)) {
			return DB::table('services as s')->join('products_new as p', 'p.id', '=', 's.products_id' )->select('s.*', 'p.listino')->where([['s.id', '=', $services_id], ['s.type', '=', 2], ['s.is_deleted_at', '=', NULL]])->first();
		}
	}
	
	public static function get_search_services($category_id, $workshop_user_id) {
		if(!empty($category_id)) {
			return DB::table('services as s')
			->join('categories as c', 'c.id', '=', 's.category_id')
			->join('service_time_prices as stp', 'stp.categories_id', '=', 'c.id')
			->where([['s.users_id' ,'=' , $workshop_user_id] ,['s.category_id' ,'=' , $category_id] , ['s.is_deleted_at' , '=' , NULL]])
			->select('s.*', 'c.category_name' , 'c.description' , 'c.time' , 'stp.small_price' , 'stp.average_price' , 'stp.big_price' , 'stp.small_time' , 'stp.average_time' , 'stp.big_time')
			->get();
		}
	}
	public static function save_update($car_washing_service , $key, $request){
	    return  Services::updateOrcreate(
										[   'users_id'=>Auth::user()->id,
											'category_id' =>$car_washing_service->id,
											'car_size' =>$key
										] ,
										[	'users_id'=>Auth::user()->id,
											'category_id'=>$car_washing_service->id,
											'car_size' =>$key,
											'hourly_rate'=>$request->hourly_rate, 
											'max_appointment'=>$request->max_appointment,
											'type' =>1
										]);
	}

}
