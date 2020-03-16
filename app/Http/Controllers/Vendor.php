<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Users_category;
use sHelper;
use serviceHelper;
use DB;
use Session;
use App\Servicequotes as Service_quotes;

class Vendor extends Controller{
      public $car_size_arr = [1=>'Small' , 2=>'Average' , 3=> 'Big'];
     public function __construct(){
      //$this->middleware('auth');
	 }
   
    public function index($page = "home"){
	   if(Auth::check()) {
			$data['users_profile'] = \App\User::find(Auth::user()->id);
			 $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
			$data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
			$data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
		}else{
	      return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
	   $data['title'] = "Officine Top Workshop - ".$page;

       if(!view()->exists('workshop.'.$page))
			return view("404")->with($data);
	   else
       return view("workshop.".$page)->with($data);
    }

   public function  page($page , $p1 = NULL , $p2 = NULL){
		$data['page'] = $page;
		$data['cars__makers_category'] = \App\Maker::all();
		$data['title'] = "Officine Top Workshop - ".$page;
        if(Auth::check()) {
           $data['users_profile'] = \App\User::find(Auth::user()->id);
            $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
           $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
           $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
		  //echo "<pre>";
		  //print_r( $data['spare_group_selected_services']);exit;
			}else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
		 }
        
		if($page == "dashboard"){
		    // $data['booked_services'] =  \App\ServiceBooking::get_all_services(Auth::user()->id);
		    $data['booked_services'] =  \App\ServiceBooking::booked_car_wash_service(1 , Auth::user()->id); 
			//$data['booked_car_revision'] =  \App\User_car_revision::get_all_bookings(Auth::user()->id); 
			$data['booked_car_revision'] =  \App\ServiceBooking::get_all_revision_bookings(3, Auth::user()->id);
			$data['order_list'] = \App\Products_order::users_orders(Auth::user()->id);
			$data['booked_sos'] =  \App\ServiceBooking::get_all_sos_bookings(6, Auth::user()->id);
			$data['request_quotes'] =  \App\ServiceBooking::get_all_service_quotes_bookings(Auth::user()->id);
			$data['tyre_booking'] =  \App\ServiceBooking::get_all_tyre_bookings(Auth::user()->id);
			$data['assemble_booking'] =  \App\ServiceBooking::get_all_assemble_bookings(Auth::user()->id);
		   }
		   if($page == "car_revision_booking_list"){ 
			//$data['booked_car_revision'] =  \App\User_car_revision::get_all_bookings(Auth::user()->id); 
			$data['booked_car_revision'] =  \App\ServiceBooking::get_all_revision_bookings(3, Auth::user()->id);
		}
		if($page == "sos_service_booking") {
			$data['booked_sos'] =  \App\ServiceBooking::get_all_sos_bookings(6, Auth::user()->id); 
		}
		if($page == "request_quotes_list") {
			$data['request_quotes'] =  \App\ServiceBooking::get_all_service_quotes_bookings(Auth::user()->id); 
		}
		if($page == "tyre_booking_list") {
			$data['tyre_booking'] =  \App\ServiceBooking::get_all_tyre_bookings(Auth::user()->id); 
		}
		if($page == "assemble_booking_list") {
			$data['assemble_booking'] =  \App\ServiceBooking::get_all_assemble_bookings(Auth::user()->id);
		}
		if($page == "view_car_revision_booking"){ 
			if(!empty(decrypt($p1))) {
				$data['booked_details'] =  \App\User_car_revision::get_bookings_details(decrypt($p1));
				$data['category'] = \App\Category::get_all_category();
				$data['services'] = \App\Car_revision_booking::get_added_services($data['booked_details']->id); 
				if($data['services'] != FALSE){
					$users_selected_service = $data['services']->pluck('service_id')->all(); 
					$data['unselected_service'] = $data['category']->whereNotIn('id', $users_selected_service);
				} else {
					$data['unselected_service'] = $data['category'];
				}
			}
		}

		if($page == "car_maintenance"){
			$data['workshop_status'] = serviceHelper::get_profile_status(Auth::user()->id);
			$data['hourly_cost'] = 0;
			$data['max_appointment'] = 0;
			$data['car_maintainance_details'] = \App\WorkshopServicesPayments::where([['workshop_id' , '=' , Auth::user()->id], ['category_type', '=', 12]])->first();
			/*Get Car maintinance services for*/
			$data['car_maintinance_service_list'] = \serviceHelper::car_maintinance_for_workshop();
			// echo "<pre>";
			// print_r($data['car_maintainance_details']);exit; 
			if($data['car_maintainance_details'] != NULL) {
				$data['hourly_cost'] = $data['car_maintainance_details']->hourly_rate;
				$data['max_appointment'] = $data['car_maintainance_details']->maximum_appointment;
			} 
			/*End*/
		}

		if($page == "service_booking_list"){
		    $data['booked_services'] =  \App\ServiceBooking::booked_car_wash_service(1 , Auth::user()->id);
		}
		if($page == "wrecker_services") {
			$data['workshop_status'] = serviceHelper::get_profile_status(Auth::user()->id);
			$data['wrecker_services'] = \App\WrackerServices::where([['deleted_at' , '=' ,NULL] , ['status' ,'=','A']])->get();
		}
		if($page == "workshop_mot_services") {
			$data['workshop_status'] = serviceHelper::get_profile_status(Auth::user()->id);
			$data['mot_services'] = \App\Our_mot_services::get_workshop_mot_services();
			$data['services_details'] = \App\WorkshopServicesPayments::get_mot_service_details(Auth::user()->id , 3);
			$data['mot_services']->map(function($mot){
				$mot->type = 2;
				return $mot;
			});
			foreach($data['mot_services'] as $services) {
				$services->max_appointment = NULL;
				$services->hourly_cost = NULL;
				$services->service_price = NULL;
				$service_details = \App\WorkshopMotServiceDetails::get_service_details(Auth::user()->id, $services->id, 2);
				if($service_details != NULL) {
					$services->max_appointment = $service_details['max_appointment'];
					$services->hourly_cost = $service_details['hourly_cost'];
				} else {
					if($data['services_details'] != NULL) {
						$services->max_appointment = $data['services_details']->maximum_appointment;
						$services->hourly_cost = $data['services_details']->hourly_rate;
					}
				}
			}
		}
        if($page == "workshops"){
			$data['workshops'] = \App\Workshop::get_workshop(Auth::user()->id);
		  }
		  if($page == "feedback") {
            // $data['all_feedback'] = \App\Feedback::get_all_feedback();
            $data['all_feedback'] = \App\Feedback::get_workshop_feedback(Auth::user()->id);
		}

		if($page == "workshop_revision") {
			$data['workshop_status'] = serviceHelper::get_profile_status(Auth::user()->id);
            $data['price'] = 0;
			$data['max_appointment'] = 0;
			$data['category_list'] = \App\Category::car_revision_service(Auth::user()->id);
			$data['service_detail'] = \App\WorkshopServicesPayments::where([['workshop_id' , '=' , Auth::user()->id] , ['category_type' , '=' ,2]])->first();
			if($data['service_detail'] != NULL){
				$data['price'] = $data['service_detail']->price;
				$data['max_appointment']= $data['service_detail']->max_appointment;
			}
		}
		
		/* if($page == "workshop_revision") {
            $data['category_list'] = \App\Category::car_revision_service(Auth::user()->id);
			$data['service_detail'] = \App\WorkshopServicesPayments::where([['workshop_id' , '=' , Auth::user()->id] , ['category_type' , '=' ,2]])->first();
		} */
		
				
        if($page == "edit_workshop" || $page == "gallery_workshop"){
           if(empty($p1))return redirect()->back();
           $data['workshop_details'] = \App\Workshop::get_workshop(Auth::user()->id , $p1 );
           if($data['workshop_details'] != NULL){
			     $data['gallery_image'] = \App\Gallery::get_workshop_image($data['workshop_details']->id);
			 }
		}
		if($page == "select_category"){
			//$data['categories'] = \App\Category::get_parent_category();
			$data['categories'] = \DB::table('main_category')->where([['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']/*, ['private', '!=', 1]*/])->get();
			$data['users_registered_cat'] = Users_category::get_users_category(Auth::user()->id);
				if($data['users_registered_cat'] != FALSE){
					$parent_cat = $data['categories']->pluck('id');
				    $users_registered_cat = $data['users_registered_cat']->pluck('id'); 
					
					$diff = $parent_cat->diff($users_registered_cat);
					$data['unregistered_cat'] = \DB::table('main_category')->whereIn('id' , $diff)->get();
					//$data['unregistered_cat'] = \App\Category::get_cat_arr($diff->all());
			 }
			 else{
				 $data['unregistered_cat'] = $data['categories'];
			 }	
		 }
		 
		if($page == "workshopServices"){
			$data['workshop_status'] = serviceHelper::get_profile_status(Auth::user()->id);
			$data['service_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);	
			//$data['workshop_payment_details'] = \App\WorkshopServicesPayments::get_service_price_max(Auth::user()->id , 1);
		    
			//echo "<pre>";
			//print_r($data['workshop_payment_details']);exit;
			
			//$data['workshop_payment_details'] = \DB::table('services')->where([['users_id' , '=' , Auth::user()->id] , ['type' , '=' , 1]])->first();
			
			$data['car_washing_category'] = sHelper::get_subcategory(1);
			$data['car_size'] = $this->car_size_arr;
			
			//$data['service_details'] = \App\Services::where([['users_id' , '=' , Auth::user()->id] , ['type' , '=' ,1]])->first();
		 }
		
		if($page == "view_services"){
			if(empty($p1))return redirect()->back();
			$service_code = base64_decode($p1);
			$service_arr = explode('/', $service_code);
			$data['users_services_details'] = \App\Services::get_services_record($service_arr[0] , $service_arr[1]);
			$data['images_arr'] = NULL; 
			$data['users_services_days'] = NULL;
			$data['service_details'] = NULL;
		   	if($data['users_services_details'] != NULL){
				$data['users_services_days'] = \App\Service_weekly_days::get_services_days($data['users_services_details']->id);
				$data['images_arr'] = \App\Gallery::get_category_image($service_arr[0]);
				$data['service_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);	
				$data['service_time_price'] = \App\ServiceTimePrice::get_time_price($data['users_services_details']->category_id);
			}
			$data['service_details'] = \App\WorkshopServicesPayments::where([['workshop_id' , '=' , Auth::user()->id] , ['category_type' , '=' ,1]])->first();
			$data['category_details'] = \App\Category::get_category_details($service_arr[1]);
			$data['car_size'] = $service_arr[1];
			$data['category_id'] = $service_arr[0];
			$data['service_average_time'] =  sHelper::get_car_wash_service_time($service_arr[1] , $service_arr[0]);
			$service_price = sHelper::car_wash_price_max_appointment(Auth::user()->id , $service_arr[0] , $service_arr[1]);
			$data['price'] =  sHelper::calculate_service_price($data['service_average_time'] ,$service_price['hourly_rate']);
		  }
		  
		//Workshop tyre24
		if($page == 'workshop_tyre24') {
			$data['workshop_status'] = serviceHelper::get_profile_status(Auth::user()->id);
			$price = $max_appointment = 0;
			$data['workshop_tyre24_category'] = DB::table('categories')->where([['category_type' , '=' , 23] , ['status','!=',1], ['deleted_at','=',NULL]])->get();
			$data['workshop_tyre24_detail'] = \App\WorkshopServicesPayments::where([['workshop_id' , '=' , Auth::user()->id] , ['category_type' , '=' ,23]])->first();
			foreach($data['workshop_tyre24_category'] as $cat){
				  $cat->hourly_rate = $cat->max_appointment =  $cat->service_price = 0;
				  $workshop_tyre24_details = sHelper::get_workshop_tyre24_service_detail(Auth::user()->id , $cat->id);
			       if($workshop_tyre24_details != NULL){
					     $cat->hourly_rate = $workshop_tyre24_details->hourly_rate;
						 $cat->max_appointment = $workshop_tyre24_details->max_appointment;
						 $cat->service_price = sHelper::calculate_service_price($cat->time , $workshop_tyre24_details->hourly_rate); 
					 }
			  }
		}
		
		
        if(!view()->exists('workshop.'.$page))
			return view("404")->with($data);
	   else
       return view("workshop.".$page)->with($data);
    }
	
	public function  get_action(Request $request , $action){
		if($action == "get_service_quotes_details") {
			if(!empty($request->quotes_id)) {
				$service_quotes_details = Service_quotes::service_quotes_detail($request->quotes_id); 
				if($service_quotes_details != NULL){
					$service_quotes_details->image = NULL;
					$service_quotes_details->service_booking_date = \sHelper::convert_italian_time($service_quotes_details->created_at);
					$image = DB::table('site_images')->where([['servicequotes_id' , '=' , $service_quotes_details->id]])->get();
					if($image->count() > 0){
						$service_quotes_details->image = $image;
					}
				}
				return view("workshop.component.service_quotes_details")->with(['quotes_details'=>$service_quotes_details]);
			} else {
				return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
			}
		}
	     if($action == "search_by_category") {
		  $data_2['service_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);
		  $data_2['car_size'] = $this->car_size_arr;
		   if(!empty($request->category_id)){
			  $data_2['car_washing_category'] = \DB::table('categories')->where([['id' , $request->category_id]])->get();
		   }
		   else{
			 $data_2['car_washing_category'] = sHelper::get_subcategory(1);
		   }	
		 return view('workshop.component.category_list')->with($data_2);
		}
		
		if($action == "view_service_details") {
			// return $request;exit;
			$service_list = \App\ServiceBooking::get_service_detail($request->serviceId);
			// echo "<pre>";
			// print_r($service_list);exit;
			if($service_list != NULL) {
				$workshop_owner = sHelper::get_workshop_owner($service_list->workshop_user_id);
				?>
					<table class="table">
						<tr>
							<th>Customer Name</th>
							<td><?php echo $service_list->f_name; ?></td>
						</tr>
						<tr>
							<th>Workshop Owner</th>
							<td><?php 
									echo $workshop_owner->company_name;
								?>
							</td>
						</tr>
						<tr>
							<th>Service Name</th>
							<td><?php echo $service_list->category_name; ?></td>
						</tr>
						<tr>
							<th>Price</th>
							<td>&euro;&nbsp;<?php echo $service_list->price; ?></td>
						</tr>
						<tr>
							<th>Total Price</th>
							<td>&euro;&nbsp;<?php 
								if(!empty($service_list->after_discount_price)) {
									echo $service_list->after_discount_price;
								} else {
									echo 0;
								}
							 ?></td>
						</tr>
						<tr>
							<th>For Booking Date</th>
							<td><?php echo $service_list->booking_date; ?></td>
						</tr>
						<tr>
							<th>Booking Date</th>
							<td><?php echo $service_list->created_at; ?></td>
						</tr>
						<tr>
							<th>Booking Start Time</th>
							<td><?php echo $service_list->start_time; ?></td>
						</tr>
						<tr>
							<th>Booking End Time</th>
							<td><?php echo $service_list->end_time; ?></td>
						</tr>
						
						<tr>
							<th>About Services</th>
							<td><?php echo $service_list->about_services; ?></td>
						</tr>
						<tr>
							<th>Booking Status</th>
							<td><?php 
									if($service_list->status == "P") {
									?><span class="badge badge-danger">Pending</span><?php
									} else if($service_list->status == "C") {
									?><span class="badge badge-success">Complete</span><?php
									} else if($service_list->status == "D") {
									?><span class="badge badge-info">Dispatched</span><?php
									}
							?></td>
						</tr>
					</table>
				<?php
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
			}
		}
		if($action == "change_service_status") {
			$service_id = $request->service_id;
				$status = $request->status;
				if($status == "C") {
					$arr = ['status' => "D"];
					\App\ServiceBooking::where('id', $service_id)->update($arr);
					return \App\ServiceBooking::where('id', $service_id)->first();
				}
		}
	}
	public function  postAction(Request $request , $action){
	    if($action == "edit_car_revision_services") {
			if(!empty($request->price) && !empty($request->service_id) && !empty($request->max_appointment)){
				$validator = \Validator::make($request->all(), [
					'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
					'price'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
				]);
				if($validator->fails()){
					return json_encode(array( "error"=> $validator->errors()->getMessages(), "status" => 400));
				}
				$result = \App\WorkshopCarRevisionServices::edit_service_price($request);
				if($result != NULL) {
					return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Record save successfully  !!! </div>'));
				} else {
					return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong.  Please Try Again. !!! </div>'));
				}
			}
		}
	    /* workshop  owner select category*/
	    // return $request;
	    if($action == "add_user_workshop_cat"){
	        $flag = 0;
	       	if(!empty($request->work_shop_paid) > 0){
				$delete_cat = \App\Users_category::delete_users_cat(Auth::user()->id);
				  foreach($request->work_shop_paid as $cat_id){
				      /*$get_category = \App\MainCategory::find($cat_id);
    					if($get_category->type == 1) {
    						$flag = 1;
    					}*/
					  $result = \App\Users_category::add_user_category($cat_id);
					}
					/*if($flag == 1) {
    					$get_private_category = \App\MainCategory::where([['type', '=', 1], ['private', '=', 1]])->get();
    					foreach($get_private_category as $private_cat_id) {
    						$result = \App\Users_category::add_user_category($private_cat_id->id);
    					}
    				}*/
				  if($result){
				      if(!empty($request->subscribe_quotes)){
						\App\Users_category::Where([['users_id' , '=' , Auth::user()->id]])->whereIn('categories_id' , $request->subscribe_quotes)->update(['for_quotes'=>1]);
					   } 
			     	return redirect()->back()->with(array("msg"=>'<div class="notice notice-success"><strong> Success </strong> Record save Successfully !!! </div>')); 
		     	  }
               }
            else{
         	   return redirect()->back()->with(array("msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Please Select at least one category  !!! </div>'));
	       }
		 }  
		/*End*/
		if($action == "upload_workshop_gallery"){
			if(count($request->file('gallery_image')) > 0){
				$image_path = public_path('storage/workshop/');
				if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
					foreach($request->file("gallery_image") as $image){
						$ext = $image->getClientOriginalExtension();
						if(in_array($ext , $this->imageArr)){
							$file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
							if( $image->move($image_path , $file_name )){
								\App\Gallery::add_workshop_gallery($file_name , $request->workshop_id);
								 $flag = 1;
							}
						} else continue; 
					}
					if($flag == 1){
						return redirect()->back()->with(array("msg"=>'<div class="notice notice-success"><strong>Success </strong> Image Uploaded Successfully !!! </div>'));
					}
				else{
				    return  redirect()->back()->with(array("msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> please try again  !!! </div>'));
				} 	   
			}
		}
	 	/* Edit Workshop Tyre24 Group service Price Details */
		if($action == "edit_workshop_tyre24_group_details") {
			$validator = \Validator::make($request->all(), [
				'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
				'hourly_rate'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status" => 400));
			}
			$result = \App\WorkshopTyre24Details::edit_workshop_tyre24_group_price($request);
			if($result != NULL) {
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Record save successfully  !!! </div>'));
			} else {
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong.  Please Try Again. !!! </div>'));
			}
		}
		/* End */
    }
	
}
