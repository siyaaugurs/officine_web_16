<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use \App\Users_category;
use \App\Products_order as OrderStatus;
use App\Http\Controllers\API\DashboardController as fuel_type;
use sHelper;

use Notification as Mail_Notification;
use App\Notifications\OfficineNotification;

class CustomerReport extends Controller{

    public function index($page = "customer_list" , $p1 = NULL , $p2 = NULL){
	    //echo $p1."<br />".$page;exit;
		$data['title'] = "Officine Top  - ".$page;
        $data['page'] = $page;
        $data['cars__makers_category'] = \App\Maker::all();
		
		if (Auth::check() && Session::has('users_roll_type')) {
			$data['users_profile'] = \App\User::find(Auth::user()->id);
		     $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
			 $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
			 $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
			  
		}else{
		  return redirect('logout')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
        $data['current_users_roll_type'] = Session::get('users_roll_type'); 
		 if($page == "remove_ticket"){
		     if(Auth::user()->roll_id == 4){
				  $remove_response = \App\SupportTicket::find($p1);
				  if($remove_response != NULL){
					  $remove_response->status = 'C';
					  if($remove_response->save()){
					    return redirect()->back()->with(['msg'=>'<div class="notice notice-danger"><strong>Success  ,</strong> TicketID-'.$p1.' is closed successful !!! </div>']);
						}
					}
				}
			  
		   }
		 if($page == "customer_list"){
			 $data['all_customers'] = \App\User::where('roll_id' , '=' , 3)->paginate(20);
		  }
		 if($page == "support_tickets"){
		    $data['tickets'] = \App\SupportTicket::support_tickets();
		  }
		if($page == "messages"){
		   $data['support_complain_type'] = $this->support_complain_type;
		   if(empty($p1))return redirect()->back();
		   $data['ticket_detail'] = \App\SupportTicket::find(decrypt($p1));
		   if($data['ticket_detail'] != FALSE){
			$data['ticket_detail']->messages  = collect(); $data['ticket_detail']->ticket_creator = NULL;
				  $ticket_creator = \App\User::find($data['ticket_detail']->user_from);
				  if($ticket_creator != NULL){
					 $data['ticket_detail']->ticket_creator = $ticket_creator;
				  }
				  $messages = DB::table('support_ticket_messages as a')
								  ->join('users as b' , 'b.id' , '=' , 'a.sender_id')
								 ->where([['support_ticket_id' , '=' , $data['ticket_detail']->id]]) 
								  ->orderBy('b.created_at' , 'DESC')
								  ->get(); 
		        if($messages->count() > 0){
				    $data['ticket_detail']->messages = $messages;
				} 
			}
			/* echo "<pre>";
			print_r($data['ticket_detail']);exit; */
		}   
		/*Get assemble service workshop*/
		if($page == "assemble_service_detail"){
			$data['lebel_heading'] = "Assemble Service Booking";
			$data['service_booking_details'] = \App\ServiceBooking::assemble_service_bookings(sHelper::date_format_for_database($p1) , Auth::user()->id);
			$page = "service_booking_detail";
		 }
	   /*End*/
		/*Order list script start*/
		if($page == "order_list"){
			$data['lebel_heading'] = "Order List";
		    $data['orders_list'] = \App\Products_order::get_order(sHelper::date_format_for_database($p1) , Auth::user()->id);
		  }
		/*End*/
		if($page == "service_booking_detail"){
			$data['service_id'] = $p1; 	
			if(empty($p1)){ return redirect()->back(); }
			if($p1 == 1){
				$data['lebel_heading'] = "Car Washing Service Booking List";
			  }
			if($p1 == 3){
			   $data['lebel_heading'] = "Car Revision Service Booking List"; 
			  }	 
			if(!empty($p2)){
				$data['service_booking_details'] = \App\ServiceBooking::service_booking_details($p1 , sHelper::date_format_for_database($p2 , 1) , Auth::user()->id);
			  }
		 }
		 /*load view */
		if($page == "order_details"){
			if(empty($p1)) return redirect()->back();
			$data['order_deatil'] = \App\Products_order::get_order_detail($p1);
			$order_status = new OrderStatus;
			$data['order_status'] = $order_status->order_status[$data['order_deatil']->status];	
			$data['order_deatil']->seller_details = $data['order_deatil']->version = $data['order_deatil']->model =  $data['order_deatil']->shipping_address = $data['order_deatil']->ship_to = $data['order_deatil']->fuel_type = $data['order_deatil']->seller_address = NULL;
			if($data['order_deatil'] != NULL){
				$user_details = \App\Model\UserDetails::find($data['order_deatil']->user_details_id);  
				if(!empty($user_details->carVersion)) {
					$data['order_deatil']->version = \App\Version::get_version($user_details->carVersion);
				}
				if(!empty($data['order_deatil']->version->Alimentazione)) {
					$service_obj = new fuel_type;
					$data['order_deatil']->fuel_type = $service_obj->fuel_type_arr[$data['order_deatil']->version->Alimentazione];
				}
				if(!empty($data['order_deatil']->version->model)) {
					$data['order_deatil']->model = \App\Models::get_model($data['order_deatil']->version->model);
				}
				$data['order_deatil']->seller_details = \App\User::find($data['order_deatil']->seller_id);  
				if($data['order_deatil']->shipping_address_id != NULL) {
					$data['order_deatil']->shipping_address = \App\Address::find($data['order_deatil']->shipping_address_id);
				}
				$data['order_deatil']->seller_address = \App\User::get_workshop_company_details($data['order_deatil']->seller_details->id);
				if($data['order_deatil']->shipping_address != NULL) {
					$data['order_deatil']->ship_to =  \App\User::find($data['order_deatil']->shipping_address->users_id); 
				}
				//$data['order_deatil']->order_description = \App\Products_order_description::get_product_description($p1);
				$data['order_id'] = $p1;
			}
				
		}


		/*load view */
		if(!view()->exists('customer_report.'.$page))
			return view("404")->with($data);
		else  
		return view("customer_report.".$page)->with($data); 
		/*End*/  
   }


   public function get_action(Request $request , $action = NULL){
	if($action == "get_daily_service_booking"){
		 if(!empty($request->selected_date)){
			$selected_date = date('Y-m-d' , strtotime($request->selected_date));
			
			$total_order =  \App\Products_order::get_order($selected_date , Auth::user()->id ,1); 
			/*End*/
			$get_car_wasing_services = \App\ServiceBooking::get_service_booking($selected_date , 1 , Auth::user()->id);
			$get_assemble_services = \App\ServiceBooking::get_service_booking($selected_date , 2 , Auth::user()->id);
			/*Get  Revision Service Script Start*/
			$revision_services = \App\ServiceBooking::get_service_booking($selected_date , 3 , Auth::user()->id);
			/*End*/
			
			/*End*/
				 ?>
			   <div class="row">
				 <!--Total order script start-->
				 <div class="col-lg-4">
					 <div class="card">
						 <div class="card-body text-center">
						   <img height="100px;" src="<?php echo url('purchase_order.png') ?>" class="img img-responsive img-circle" />
						 
							 <h5 class="card-title">Total Order</h5>
							 <h3 class="mb-3">
							   <span class="badge bg-danger badge-pill"><?php if($total_order > 0) { echo $total_order; } else{ echo "0"; } ?></span>
							 </h3>
							 <a target="_blank" href="<?php echo url("customer_report/order_list/$selected_date") ?>" class="btn bg-success-400">View All</a>
						 </div>
					 </div>
				 </div>
				 <!--End-->
				 <div class="col-lg-4">
					 <div class="card">
						 <div class="card-body text-center">
						   <img height="100px;" src="<?php echo asset('service_icon.png') ?>" class="img img-responsive img-circle" />
						 
							 <h5 class="card-title">Car Washing Service</h5>
							 <h3 class="mb-3">
							   <span class="badge bg-danger badge-pill"><?php if($get_car_wasing_services->count() > 0) { echo " ". $get_car_wasing_services->count(); } else{ echo "0 "; } ?></span>
							 </h3>
							 <a target="_blank" href="<?php echo url("customer_report/service_booking_detail/1/$selected_date") ?>" class="btn bg-success-400">View All</a>
						 </div>
					 </div>
				 </div>
				 <!--Car assemble script section start-->
				 <div class="col-lg-4">
					 <div class="card">
						 <div class="card-body text-center">
						  <img height="100px;" src="<?php echo asset('service_icon.png') ?>" class="img img-responsive img-circle" />
							 <h5 class="card-title">Car Assemble Service</h5>
							 <h3 class="mb-3">
							   <span class="badge bg-danger badge-pill"><?php if($get_assemble_services->count() > 0) { echo " ". $get_assemble_services->count(); } else{ echo "0 "; } ?></span>
							 </h3>
							 <a target="_blank" href="<?php echo url("customer_report/assemble_service_detail/$selected_date") ?>" class="btn bg-success-400">View All</a>
						 </div>
					 </div>
				 </div>
				 <!--End-->
				 <!--Car revision service Section start-->
				 <div class="col-lg-4">
					 <div class="card">
						 <div class="card-body text-center">
						  <img height="100px;" src="<?php echo asset('service_icon.png') ?>" class="img img-responsive img-circle" />
							 <h5 class="card-title">Car Revision Service </h5>
							 <h3 class="mb-3">
							   <span class="badge bg-danger badge-pill"><?php if($revision_services->count() > 0) { echo " ". $revision_services->count(); } else{ echo "0 "; } ?></span>
							 </h3>
							 <a target="_blank" href="<?php echo url("customer_report/service_booking_detail/3/$selected_date") ?>" class="btn bg-success-400">View All</a>
						 </div>
					 </div>
				 </div>
				 <!--End-->
			 </div>
			   <?php  
		 }
		 else{
		   return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please tr again !!! </div>'));
		 }

	}
}
   
   /* public function load_pages($page = NULL , $p1 = NULL){
	$data = [];
	if($page == "messages"){
		if(empty($p1))return redirect()->back();
		$data['ticket_detail'] = \App\SupportTicket::find(decrypt($p1));

		echo "<pre>";
		print_r($data['ticket_detail']);exit; 
		if($data['ticket_detail'] != FALSE){
		 $data['ticket_detail']->messages  = collect();  
		 $messages = \App\SupportTicketMessages::where([['support_ticket_id' , '=' , $data['ticket_detail']->id]]);
			if($messages->count() > 0){
			  $data['ticket_detail']->messages = $messages;
			 } 	
		 }  
		return view('customer_report.component.messages')->with($data); 
	 }
   } */

   /*For Ajax request*/
   public function post_action(Request $request , $action){
     /*Send Mail MEssages script Start*/
		if($action == "send_mail_messages"){
			if(!empty($request->maker)){
				$request->images = NULL;
				$image = $this->upload_customer_report_pic($request);
				$where_clause = ['carMakeName'=>$request->maker] ;
				if(!empty($request->model)) {
					$where_clause['carModelName'] = $request->model;
				} 
				if(!empty($request->version)){
					$where_clause['carVersion'] = $request->version;
				}
				$response = DB::table('user_details')->groupBy('user_id')->where($where_clause)->get();
				if($response->count() > 0){
					$user_id_arr = [];
					$user_id_arr = $response->pluck('user_id');
					$users_details =  \App\User::whereIn('id' , $user_id_arr)->get();
					Mail_Notification::send($users_details  , new OfficineNotification($request, $image));
					echo '<div class="notice notice-success"><strong>Success , </strong> Message Send successfull  !!!.</div>';exit;
				}
			}
		}
   /*End*/


	  /*Send Messages */ 
	  if($action == "send_support_messages"){
		if(!empty($request->support_ticket_id)){
			$ticket_response = \App\SupportTicket::find($request->support_ticket_id);
			  if($ticket_response != FALSE){
				  if(!empty($request->images)){
					  $support_files = $this->upload_images($request);
					  if(count($support_files) > 0){
						  foreach($support_files as $key=>$image){
							  $upload_image_response = \App\SupportTicketMessages::save_images($image , $ticket_response->id); 
						  }
					  }
				  } 
				  if(!empty($request->message)){
					  $save_messages = \App\SupportTicketMessages::save_msg($request->message , $request->support_ticket_id);	
				  }
				  $ticket_response->updated_at = now();
				  $ticket_response->status = 'A';
				  
				  if( $ticket_response->save() ){
					return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong> Success </strong> Message Send successfully   !!! </div>')); 
				  }
				  else{
					return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please tr again !!! </div>'));   
				  }
			  }else{
				  return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please tr again !!! </div>')); 
			  }
		} 
		else{
			return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please tr again !!! </div>')); 
		} 
			
	  } 
	  /*End*/
      if($action == "get_users"){
		  if(!empty($request->car_makers)){
			 $where_clause = ['carMakeName'=>$request->car_makers] ;
			 if(!empty($request->car_models)) {
			      $where_clause['carModelName'] = $request->car_models;
			   } 
			 if(!empty($request->car_version)){
			       $where_clause['carVersion'] = $request->car_version;
			   }  
			 $response = DB::table('user_details')->groupBy('user_id')->where($where_clause)->get();
				if($response->count() > 0){
					$user_id_arr = [];
					$user_id_arr = $response->pluck('user_id');
					$users_details =  \App\User::whereIn('id' , $user_id_arr)->get();
					return view("customer_report.component.user_data")->with(['all_customers'=>$users_details]);  
				}
			} 
		  else{
			 echo '<div class="notice notice-danger"><strong>Wrong , </strong> Please  !!!.</div>';exit;
			} 	
		}
   }
   /*End*/
   
   
   
}
