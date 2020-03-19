<?php

namespace App;

use Auth;

use DB;

use Illuminate\Database\Eloquent\Model;



class ServiceBooking extends Model{

    

	protected $table = "service_bookings";

	protected $fillable = ['id','users_id' , 'users_latitude' , 'users_longitude','workshop_user_id' , 'workshop_address_id', 'product_order_id',  'special_condition_id', 'services_id' , 'part_id','car_size', 'product_id', 'booking_date',  'workshop_user_days_id'  , 'workshop_user_day_timings_id' , 'coupons_id' , 'start_time' , 'end_time','price'  , 'service_vat', 'after_discount_price'  , 'discount', 'type' , 'wrecker_service_type' ,'service_type','status','servicequotes_id','coupon_id','service_coupon_id','part_coupon_id' ,'quantity','mot_service_type' ,'deleted_at' , 'created_at' , 'updated_at'];

	public $type_status = [1 => "Car Washing", 2 => "Assemble Services", 3 => "Car Revision", 4 => "Tyre", 5 => "Car Maintainance", 6 =>'SOS Service Booking' , 7=>'Service for request quotes',8 =>'Mot service'];
	public $wracker_service_type = [1=>'Service Booking for appointment' , 2=>'Service Booking For Emergency'];

	public $status_type = ['P'=>'Pending' , 'A'=>'Approved' , 'C'=>'Confirm' , 'CA'=>'Cancel'];


	public static function add_booking($request , $package_details , $discount_price ,$special_id , $service_vat , $after_discount_price){
	
	 return ServiceBooking::updateOrCreate(
		 [
			'users_id'=>Auth::user()->id,
			'product_order_id'=>(int) $request->order_id,
			'workshop_user_id'=>$package_details->users_id,
			'services_id'=>$request->category_id,
		], 
		['users_id'=>Auth::user()->id ,
	 								'product_order_id'=>(int)$request->order_id,
	                                'workshop_user_id'=>$package_details->users_id ,  
									'services_id'=>$request->category_id , 
									'car_size'=>$request->car_size,
									'booking_date'=>$request->selected_date ,                       
									'workshop_user_days_id' =>$package_details->workshop_user_days_id,
									'workshop_user_day_timings_id'=>$request->package_id ,                            
									'start_time'=>$request->start_time ,
									'end_time'=>$request->end_time ,
									'price'=>$request->price ,  
									'status'=>'P',
									'type'=>1,
									'special_condition_id'=>$special_id,
									'after_discount_price' =>$after_discount_price,
									'discount' =>$discount_price,
									'service_vat'=>$service_vat,
									'servicequotes_id' => $request->servicequotes_id,
									'coupon_id'=>$request->coupon_id
		]);
	}
		/* tyre booking*/
		public static function get_busy_hour_for_tyre($request , $package_details , $service_id){
			return  ServiceBooking::where([['booking_date','=',$request->selected_date],
									       ['workshop_user_id' , '=' , $package_details->users_id]	 ,
									 	   ['workshop_user_day_timings_id' , '=' , $request->package_id] , ['status' , '=!' , 'P'] , ['status' , '=!' , 'CA']])
									->where([['services_id' , '=' , $service_id] , ['type' ,'=', 4] , ['start_time', '<', $request->end_time] , ['end_time', '>', $request->start_time]])
									->first();
		}
		
       /*End*/

		/*save sos service booking api */
		public static function get_busy_hour_for_sos_service($request , $package_details , $service_id , $wracker_service_type = 1){
			return  ServiceBooking::where([['booking_date','=',$request->selected_date],
									  ['workshop_user_id' , '=' , $package_details->users_id]	 ,
									  ['workshop_user_day_timings_id' , '=' , $request->package_id]							                              ])
									->where('start_time', '<', $request->end_time)
									->where('end_time', '>', $request->start_time)
									->where([['services_id' , '=' , $service_id] , ['type' ,'=', 6] ,['wrecker_service_type','=',$wracker_service_type]])
								   ->get();
		  }
	
		public static function get_booked_sos_package($package_id , $selected_date , $service_id){
			   return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id] , ['booking_date' , '=' , $selected_date], ['type' , '=' ,6] , ['wrecker_service_type' , '=' , 1] , ['services_id' , '=' ,$service_id ]])->get();   
		} 


        
		public static function save_sos_booking($request , $package_details , $special_condition_response){
			$special_condition_id = $after_discount_price = NULL;
			if($special_condition_response != NULL){
				  $discount = \sHelper::make_discount_price($request->price , $special_condition_response->discount_amount , $special_condition_response->discount_type);
				  $special_condition_id = $special_condition_response->special_condition_id;
				  $after_discount_price = $request->price - $discount;
			}
			return ServiceBooking::create(['users_id'=>Auth::user()->id,'users_latitude'=>$request->latitude , 'users_longitude'=>$request->longitude,
											'product_order_id'=>(int)$request->order_id,
											'workshop_user_id'=>$package_details->users_id,
											'workshop_address_id'=>$request->address_id,
											'services_id'=>$request->service_id,
											'special_condition_id'=>$special_condition_id,
											'booking_date'=>$request->selected_date ,
											'workshop_user_days_id' =>$package_details->workshop_user_days_id,
											'workshop_user_day_timings_id'=>$request->package_id ,
											'start_time'=>$request->start_time ,
											'end_time'=>$request->end_time ,
											'price'=>$request->price,
											'after_discount_price'=>$after_discount_price,
											'status'=>'P',
											'type'=>6,
											'wrecker_service_type'=>1
										]);
		}
		/*End*/
	
	
	//add booking for car maintenance
	public static function add_booking_for_car_maintenance($request , $package_details, $service_specification ,$discount_price,$special_id, $service_vat , $after_discount_price){
		//$part_ids = explode(' ' ,$service_specification->part_id);
		//$part_id = json_encode($part_ids); 
		return ServiceBooking::updateOrCreate(['users_id' =>Auth::user()->id,
											   'product_order_id' =>$request->order_id ,
											   'workshop_user_id' =>$package_details->users_id,
											   'services_id' =>$service_specification->service_id],
									['users_id'=>Auth::user()->id ,
									'product_order_id'=>(int)$request->order_id,
	                                'workshop_user_id'=>$package_details->users_id ,  
									'services_id'=>$service_specification->service_id , 
									//'part_id'=>$part_id,
									'booking_date'=>$request->selected_date ,                       
									'workshop_user_days_id' =>$package_details->workshop_user_days_id,
									'workshop_user_day_timings_id'=>$request->package_id ,                            
									 'start_time'=>$request->start_time ,
									 'end_time'=>$request->end_time ,
									 'price'=>$service_specification->price ,  
									 'status'=>'P',
									 'type'=>5,
									 'special_condition_id'=>$special_id,
									 'discount' =>$discount_price,
									 'after_discount_price' =>$after_discount_price,
									 'servicequotes_id' => $request->servicequotes_id,
									 'coupon_id'=>$request->coupon_id,
									 //'part_coupon_id' => $service_specification->part_coupon_id,
									 'service_vat' => $service_vat,
									]);
	
								}

	public static function get_service_booking($selected_date ,$type , $user_id = NULL ){
		if($user_id != NULL){
		    return ServiceBooking::whereDate('booking_date' , $selected_date)->where([['type' , '=' , $type] , ['workshop_user_id' , '=' , $user_id]])->get();
		}
		return ServiceBooking::whereDate('booking_date' , $selected_date)->where([['type' , '=' , $type]])->get();
	}

	/* service type=2 for use emergency  */
	/*........Add emgency booking..........*/
	public static function add_booking_emergency($request , $all_selected){
		$get_time = date("h:i:s"); 
		$workshop_package_timing = DB::table('workshop_user_day_timings')->select('id')
		->where([['users_id' , '=' , $all_selected->users_id],['workshop_user_days_id' , '=' , $all_selected->days_id ],['deleted_at' , '=' , NULL]])
		 ->where('start_time', '<', $get_time)
		->where('end_time', '>', $get_time)
		->first();	
		if(!empty($workshop_package_timing->workshop_user_day_timings_id)){
			$workshop_package_timing = $workshop_package_timing->id;
		}else{
			$workshop_package_timing = 0;
		}
	return ServiceBooking::create(['users_id'=>Auth::user()->id ,
	                                'workshop_user_id'=>$all_selected->users_id ,  
									 'services_id'=>$request->service_id , 
									 'booking_date'=>$request->selected_date ,                       
									 'workshop_user_days_id' =>$all_selected->days_id,
									 'workshop_user_day_timings_id'=>$workshop_package_timing ,                            
									 'price'=>$all_selected->services_price ,  
									 'status'=>'P',
									 'type'=>1,
									 'service_type'=>2,
									]);	
	}
	/*public static function add_booking($request , $package_details){

		return ServiceBooking::create(['users_id'=>Auth::user()->id , 'workshop_user_id'=>$package_details->users_id ,  'services_id'=>$package_details->services_id , 'booking_date'=>$request->selected_date ,

		 'services_weekly_days_id'=>$package_details->services_weekly_days_id, 'services_packages_id'=>$request->package_id ,'start_time'=>$request->start_time , 'end_time'=>$request->end_time , 'price'=>$request->price , 'status'=>'P']);

	}*/
	  public static function get_busy_hour_car_maintenance($request , $package_details){

	  return  ServiceBooking::where([['booking_date','=',$request->selected_date],
	                            ['workshop_user_id' , '=' , $package_details->users_id],                               
	                            ['workshop_user_day_timings_id' , '=' , $request->package_id]							                              ])
							  ->where('start_time', '<', $request->end_time)
							  ->where('end_time', '>', $request->start_time)
							  ->where([['type' , '=', 5]])
							  ->get();

  }
public static function get_busy_hour($request , $package_details){
	  return  ServiceBooking::where([['booking_date','=',$request->selected_date],
	                            ['workshop_user_id' , '=' , $package_details->users_id]	 ,                               
								['workshop_user_day_timings_id' , '=' , $request->package_id ]	,
								['status' ,'=!' , 'P'],['status' ,'=!' ,'CA']						                              ])
							  ->where('start_time', '<', $request->end_time)
							  ->where('end_time', '>', $request->start_time)
							   ->where([['type' , '=', 1] , ['car_size' , '=', $request->car_size]])

							 ->first();

  }
  public static function get_busy_hour_for_revision($request ,$package_details , $main_category_detail){
		return ServiceBooking :: where([['booking_date' ,'=' , $request->selected_date] ,
		['workshop_user_id' ,'=' , $package_details->user_id] , ['status' ,'!=' , 'P'] ,['status' ,'!=' ,'CA'] ,  ['workshop_user_day_timings_id' , '=' , $request->package_id]])->where('start_time', '<', $request->end_time)
							  ->where('end_time', '>', $request->start_time)
							  ->where([['services_id' , '=' , $main_category_detail] , ['type' ,'=', 3]])->first();
  } 
  public static function get_busy_hour_for_assemble($request , $package_details , $main_category_id){
	  return  ServiceBooking::where([['booking_date','=',$request->selected_date],
								['workshop_user_id' , '=' , $package_details->users_id]	,
								['status' ,'!=' ,'P'],['status' ,'!=' , 'CA'],
	                            ['workshop_user_day_timings_id' , '=' , $request->package_id ] ])
							  ->where('start_time', '<', $request->end_time)
							  ->where('end_time', '>', $request->start_time)
							  ->where([['services_id' , '=' , $main_category_id] , ['type' ,'=', 2]])
							  ->get();
	}
	public static function get_all_revision_service_list($workshop_user_id = NULL){
	    if($workshop_user_id != NULL){
		   return DB::table('service_bookings as a')
				  ->leftjoin('services as s' , 'a.services_id','=','s.id')
				  ->leftjoin('categories as c' , 's.category_id','=','c.id') 
				  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
				  ->where('a.workshop_user_id' , '=' , $workshop_user_id)
				  ->where('a.type' , '=' ,3)
				  ->select('a.*' , 's.category_id' , 's.about_services' , 'c.category_name' , 'u.f_name','a.workshop_user_id')
				  ->get(); 

		 }
		return DB::table('service_bookings as a')
		  ->leftjoin('services as s' , 'a.services_id','=','s.id')
		  ->leftjoin('categories as c' , 's.category_id','=','c.id')
		  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
		  ->where('a.type' , '=' ,3)
		  ->select('a.*' , 's.category_id' , 's.about_services' , 'c.category_name' , 'u.f_name')

		  ->get();

	}
	

  

  	

    

	/*

	public static function get_busy_hour($request){

		//DB::enableQueryLog();

		return  ServiceBooking::where([['booking_date' , '=' , $request->selected_date] , ['workshop_id' , '=' ,$request->workshop_user_id],

		                               ['services_id' , '=' , $request->services_id] 

									 ])

									 ->where('start_time', '<', $request->end_time)->where('end_time', '>', $request->start_time)

									 ->get();

		/* return  ServiceBooking::where([['booking_date' , '=' , $request->selected_date] , ['workshop_id' , '=' ,$request->workshop_id],

		['services_id' , '=' , $request->services_id] 

									 ])

									 ->whereRaw("? between start_time and end_time" , [$request->start_time])

									 ->orwhereRaw("? between start_time and end_time" , [$request->end_time] )

									 ->get(); 
	}*/

	public static function get_booked_package($package_id , $selected_date , $car_size ,  $type){
	   return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id] , ['car_size' , '=' , $car_size] , ['type' , '=' ,$type]])->get();

	}
	public static function count_booked_special_package($package_id , $workshop_id , $special_id , $car_size , $type){
		return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id],['workshop_user_id' , '=' , $workshop_id] ,['special_condition_id' , '=' , $special_id], ['car_size' , '=' , $car_size] , ['type' , '=' ,$type]])->get();

	}
	
	public static function get_booked_tyre_package($package_id , $selected_date , $workshop_user_id,$services_id){

	   return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id] , ['booking_date' , '=' , $selected_date], ['workshop_user_id' , '=' ,$workshop_user_id], ['services_id','=', $services_id]])->get();

	}
	public static function count_car_booked_special_package($package_id , $workshop_id , $special_id  , $type){
		return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id],['workshop_user_id' , '=' , $workshop_id] 
		,['special_condition_id' , '=' , $special_id] , ['type' , '=' ,$type]])->get();
	}

	

	public static function get_booked_Assembly_package($package_id , $selected_date , $main_category_id , $type){
		return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id] ,['services_id' , '=' ,$main_category_id],['type', '=',$type]])

		                        ->whereDate('booking_date', '=', $selected_date)->get();

	}



	public static function get_booked_package_new($service_id , $selected_date , $type){
	   return ServiceBooking::where([['services_id' , '=' , $service_id] , ['booking_date' , '=' , $selected_date] , ['type' , '=' ,$type]])->get();

	}
		public static function get_booked_package_car_maintenance($package_id , $selected_date ,  $type ,$category_id){

	   return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id] , ['services_id' , '=' ,$category_id],['type' , '=' ,$type]])->whereDate('booking_date', '=', $selected_date)->get();

	}

	

	public static function get_assemble_booked_package($package_id , $selected_date , $type){

	   return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id] , ['booking_date' , '=' , $selected_date] , ['type' , '=' ,$type]])->get();

	}



	public static function booked_car_wash_service($type , $workshop_user_id = NULL){
	    if($workshop_user_id != NULL){
		    return DB::table('service_bookings as a')
				  ->leftjoin('categories as c' , 'c.id','=','a.services_id')
				  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
				  ->where([['a.workshop_user_id' , '=' , $workshop_user_id] , ['type' , '=' , $type]])
				  ->select('a.*','c.category_name','u.f_name')
				  ->get();
		  } 
		 
	}



	public static function get_all_services($workshop_user_id = NULL){

	    if($workshop_user_id != NULL){

		   return DB::table('service_bookings as a')

				  ->leftjoin('services as s' , 'a.services_id','=','s.id')

				  ->leftjoin('categories as c' , 's.category_id','=','c.id') 

				  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')

				  ->where('a.workshop_user_id' , '=' , $workshop_user_id)

				  ->select('a.*' , 's.category_id' , 's.about_services' , 'c.category_name' , 'u.f_name')

				  ->get(); 

		 }

		return DB::table('service_bookings as a')

		  ->leftjoin('services as s' , 'a.services_id','=','s.id')

		  ->leftjoin('categories as c' , 's.category_id','=','c.id') 

		  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')

		  ->select('a.*' , 's.category_id' , 's.about_services' , 'c.category_name' , 'u.f_name')

		  ->get();

	}

	

	public static function get_service_detail($service_id){

	    if($service_id != NULL){

		   	return DB::table('service_bookings as a')

				->leftjoin('services as s' , 'a.services_id','=','s.id')

				->leftjoin('categories as c' , 's.category_id','=','c.id') 

				->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')

				->where('a.id' , '=' , $service_id)

				->select('a.*' , 's.category_id' , 's.about_services' ,'c.time', 'c.category_name' , 'u.f_name')

				->first(); 

		}

	}

	public static function add_assemble_service_booking($request , $package_details,$main_category_id , $discount_price, $special_id , $service_vat, $after_discount_price){

	  return ServiceBooking::create(['users_id'=>Auth::user()->id ,
									 'product_order_id'=>(int) $request->order_id,
	                                 'workshop_user_id'=>$package_details->users_id ,  
	                                 'services_id'=>$main_category_id,
									 'product_id'=>$request->product_id ,    
									 'booking_date'=>$request->selected_date ,                       
									 'workshop_user_days_id' =>$package_details->workshop_user_days_id,
									 'workshop_user_day_timings_id'=>$request->package_id,                            
									 'start_time'=>$request->start_time ,
									 'end_time'=>$request->end_time ,
									 'price'=>$request->price ,  
									 'status'=>'P' ,
									 'quantity'=>$request->quantity,
									 'type'=>2,
									 'special_condition_id'=>$special_id,
									 'after_discount_price' =>$after_discount_price,
									 'discount' =>$discount_price,
									 'service_vat' =>$service_vat,
									 'coupon_id'=>$request->coupon_id,
									 ]);

	}

	

	/*For API */

	  public static function get_service_booked_package($package_id , $selected_date , $service_id , $car_size , $type){

	   return ServiceBooking::where([['workshop_user_day_timings_id' , '=',$package_id] , 
	                                ['services_id' , '=' , $service_id] ,
									['type' , '=' , $type], 
									['car_size' , '=' , $car_size]
									])
									->whereDate('booking_date' , $selected_date)
									->get();

	  }

	/*End*/

	public static function get_tyre_service_booked_package($package_id , $selected_date , $service_id,$type){

	 return ServiceBooking::where([['workshop_user_day_timings_id' , '=',$package_id] , 
	                                ['services_id' , '=' , $service_id] ,  ['type' , '=' , $type] 
									])
									->whereDate('booking_date' , $selected_date)
									->get();	

	}

	

	

	

	//Car revision service booking

	/*Get booked car revision */

	public static function get_booked_car_revision_package($package_id , $selected_date , $service_id , $type){

		return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id] ,['services_id' , '=' ,$service_id],['type', '=',$type]])->whereDate('booking_date', '=', $selected_date)->get();

	}

	/*End */

	/*Add car revision service booking*/
public static function add_car_revision_service_booking($request , $package_details , $special_id,$discount_price,$service_vat , $after_discount_price){
		
		return ServiceBooking::updateOrCreate(
		[
			'users_id'=>Auth::user()->id,
			'product_order_id'=>(int) $request->order_id,
			'workshop_user_id'=>$package_details->users_id,
			//'product_id'=>$request->products_id,
			'services_id'=>$request->service_id,
		],
		['users_id'=>Auth::user()->id ,
											'workshop_user_id'=>$package_details->users_id ,
											'product_order_id'=>(int)$request->order_id,	
											'services_id'=>$request->service_id,
											'booking_date'=>$request->selected_date ,                       
											'workshop_user_days_id' =>$package_details->workshop_user_days_id,
											'workshop_user_day_timings_id'=>$request->package_id,                            
											'start_time'=>$request->start_time ,
											'price'=>$request->price ,  
											'status'=>'P' ,
											'type'=>3,
											'special_condition_id'=>$special_id,
											'after_discount_price' =>$after_discount_price,
											'service_vat'=>$service_vat,
											"discount" =>$discount_price,
											'coupon_id'=>$request->coupon_id,
									  	]);

	}
	/*End */

	/*Get service booked car revisions */

	public static function get_service_booked_car_revision_package($workshop_user_id,$selected_date , $service_id , $type){
		return ServiceBooking::where([['workshop_user_id' , '=',$workshop_user_id] , 
									 ['services_id' , '=' , $service_id] ,
									 ['type' , '=' , $type],
									 ])
									 ->whereDate('booking_date' , $selected_date)
									 ->get();

	}
	
	public static function get_last_revision_service($user_id , $type){
		return ServiceBooking::where([['users_id' , '=',$user_id] ,
									  ['type' , '=' , $type],
									  ])
									  ->orderBy('booking_date' , 'ASC')
									  ->first();
	 }


	/*End */

	   public static function get_service_booked_sos_package($package_id , $selected_date , $service_id , $type){

	   return ServiceBooking::where([['workshop_user_day_timings_id' , '=',$package_id] , 

	                                ['services_id' , '=' , $service_id] ,

									['type' , '=' , $type], 

									])

									->whereDate('booking_date' , $selected_date)

									->get();

	  }


	  
	   
	  /*Get Car wash service booking detail*/
	  public static function service_booking_details($type , $selected_date , $user_id = NULL){
		if($user_id != NULL){
		    return  DB::table('service_bookings as a')
		            ->leftjoin('categories as c' , 'c.id' , '=' , 'a.services_id')
					->leftjoin('users as cust' , 'cust.id' , '=' , 'a.users_id')
					->leftjoin('users as u' , 'u.id' , '=' , 'a.workshop_user_id')
					->select('a.*' , 'u.company_name' ,'c.category_name', 'cust.f_name' , 'cust.l_name')
					->where([['a.type' , '=' , $type] , ['workshop_user_id' , '=' , $user_id]])->whereDate('a.booking_date' , $selected_date)->get();
		  }
		return  DB::table('service_bookings as a')
		            ->leftjoin('categories as c' , 'c.id' , '=' , 'a.services_id')
					->leftjoin('users as cust' , 'cust.id' , '=' , 'a.users_id')
					->leftjoin('users as u' , 'u.id' , '=' , 'a.workshop_user_id')
					->select('a.*' , 'u.company_name' ,'c.category_name', 'cust.f_name' , 'cust.l_name')
					->where([['a.type' , '=' , $type]])->whereDate('a.booking_date' , $selected_date)->get();
		//ServiceBooking::whereDate('booking_date' , $selected_date)->where([['type' , '=' , $type]])->get(); 
	  }
	  /*End*/
	  

	 
	  /*Get Assemble Service booking api*/
	  public static function assemble_service_bookings($selected_date , $user_id = NULL){
		if($user_id != NULL){
		    return  DB::table('service_bookings as a')
		            ->leftjoin('main_category as main' , 'main.id' , '=' , 'a.services_id')
					->leftjoin('users as cust' , 'cust.id' , '=' , 'a.users_id')
					->leftjoin('users as u' , 'u.id' , '=' , 'a.workshop_user_id')
					->select('a.*' , 'u.company_name' ,'main.main_cat_name as category_name', 'cust.f_name' , 'cust.l_name')
					->where([['a.type' , '=' , 2] , ['workshop_user_id' , '=' , $user_id]])->whereDate('a.booking_date' , $selected_date)->get();
		  }
		return  DB::table('service_bookings as a')
		            ->leftjoin('main_category as main' , 'main.id' , '=' , 'a.services_id')
					->leftjoin('users as cust' , 'cust.id' , '=' , 'a.users_id')
					->leftjoin('users as u' , 'u.id' , '=' , 'a.workshop_user_id')
					->select('a.*' , 'u.company_name' ,'main.main_cat_name as category_name', 'cust.f_name' , 'cust.l_name')
					->where([['a.type' , '=' , 2]])->whereDate('a.booking_date' , $selected_date)->get();
	  }
	  /*End*/

	  public static function service_order_description($order_id) {
		return DB::table('service_bookings as a') 
						->where([['a.product_order_id' , '=' , $order_id], ['deleted_at', '=', NULL]])
						->select('a.*')
						->get(); 
	}

	public static function get_all_revision_bookings($type , $workshop_user_id = NULL){
	    if($workshop_user_id != NULL){
		    return DB::table('service_bookings as a')
				  ->leftjoin('categories as c' , 'c.id','=','a.services_id')
				  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
				  ->where([['a.workshop_user_id' , '=' , $workshop_user_id] , ['type' , '=' , $type]])
				  ->select('a.*','c.category_name','u.f_name')
				  ->get();
		  } 
		 
	}
	public static function get_all_sos_bookings($type , $workshop_user_id = NULL){
	    if($workshop_user_id != NULL){
		    return DB::table('service_bookings as a')
				  ->leftjoin('wracker_services as w' , 'w.id','=','a.services_id')
				  ->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
				  ->where([['a.workshop_user_id' , '=' , $workshop_user_id] , ['type' , '=' , $type]])
				  ->select('a.*','w.services_name','u.f_name')
				  ->get();
		  } 
		 
	}
	public static function get_three_workshop_services($workshop_user_id , $type){
		return DB::table('service_bookings')
				  ->where([['workshop_user_id' , '=' , $workshop_user_id] , ['type' , '=' , $type]])
				  ->select('*')
				  ->get();
	}
	/*mot service*/
	public static function get_booked_package_mot_service($package_id , $selected_date ,  $type ,$category_id){

	   return ServiceBooking::where([['workshop_user_day_timings_id' , '=' , $package_id] , ['services_id' , '=' ,$category_id],['type' , '=' ,$type]])->whereDate('booking_date', '=', $selected_date)->get();
	}
	//add booking for mot service
	public static function add_booking_for_mot_service($request , $package_details ,$discount_price,$special_id ,$service_vat, $after_discount_price){
		$parts_id = explode(' ' , $request->part_id);
		$part_id = json_encode($parts_id);
		return ServiceBooking::create(['users_id'=>Auth::user()->id ,
									'product_order_id'=>(int)$request->order_id,
	                                'workshop_user_id'=>$package_details->users_id ,  
									'services_id'=>$request->service_id , 
									'part_id'=>$part_id,
									'booking_date'=>$request->selected_date ,                       
									'workshop_user_days_id' =>$package_details->workshop_user_days_id,
									'workshop_user_day_timings_id'=>$request->package_id ,                            
									'start_time'=>$request->start_time ,
									'end_time'=>$request->end_time ,
									'price'=>$request->price ,  
									'status'=>'P',
									'type'=>8,
									'special_condition_id'=>$special_id,
									'after_discount_price' =>$after_discount_price,
									'discount' => $discount_price,
									'service_vat' =>$service_vat,
									'mot_service_type' =>$request->mot_service_type,
									 //'servicequotes_id' => $request->servicequotes_id,
									 'coupon_id'=>$request->coupon_id,
									]);
	}

	public static function delete_booking($request){
		return ServiceBooking::where([['users_id','=' ,Auth::user()->id],['id','=',$request->book_id]])->delete();
	}
	public static function get_all_service_quotes_bookings($workshop_id) {
		return DB::table('service_bookings as a')
					->where([['a.workshop_user_id', '=', $workshop_id], ['a.type', '=', 7]])
					->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
					->leftjoin('main_category as main' , 'main.id' , '=' , 'a.services_id')
					->select('a.*', 'u.f_name', 'u.id as booking_users_id', 'main.main_cat_name')
					->orderBy('a.id' , 'DESC')
					->get();
	}
	public static function get_all_tyre_bookings($workshop_id) {
		return DB::table('service_bookings as a')
					->where([['a.workshop_user_id', '=', $workshop_id], ['a.type', '=', 4]])
					->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
					->leftjoin('categories as c' , 'c.id' , '=' , 'a.services_id')
					->select('a.*', 'u.f_name', 'u.id as booking_users_id', 'c.category_name as service_name')
					->orderBy('a.id' , 'DESC')
					->get();
	}

	public static function get_all_assemble_bookings($workshop_id) {
		return DB::table('service_bookings as a')
					->where([['a.workshop_user_id', '=', $workshop_id], ['a.type', '=', 2]])
					->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')
					->leftjoin('main_category as main' , 'main.id' , '=' , 'a.services_id')
					->select('a.*', 'u.f_name', 'u.id as booking_users_id', 'main.main_cat_name')
					->orderBy('a.id' , 'DESC')
					->get();
	}

}

