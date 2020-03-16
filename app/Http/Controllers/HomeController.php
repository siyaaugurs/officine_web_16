<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use sHelper;
use App\Users_category;
use DB;
use Session;


class HomeController extends Controller{
    
    public function __construct(){
       //$this->middleware('guest')->except('logout');
	   //$this->middleware('auth');
    }
    
	
    public function index($page = "login"){
	  $data['title'] = "Officine Top - login";
      if(Auth::check()){
		   if(Auth::user()->roll_id == 1)
	         return redirect('seller');
	       elseif(Auth::user()->roll_id == 2)
	         return redirect('vendor/home');
		   elseif(Auth::user()->roll_id == 4)
	         return redirect('admin');	 
		} 
	  //else return redirect('/login');
      
      	if(!view()->exists($page))
			return view("404")->with($data);
		else 	
         return view($page)->with($data); 
	}
	
	
	// policy pages
	public function policy_pages($id){
		$data['title'] = "policy_pages";
		$data['edit_condition'] = \App\TermsCondition::get_records_terms_condition($id);
		return view("policy_pages")->with($data);
	 }
	
	public function page($page , $p1 = NULL){
	  $data['page'] = $page;	
	  $data['title'] = "Officine Top - Add Workshop";
        if (Auth::check()) {
           $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();	    
          $data['users_profile'] = \App\User::find(Auth::user()->id);
		  $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
		  $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
      }else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	   }
	   
      $data['users_profile'] = \App\User::find(Auth::user()->id);
       if($page == "home"){
		  return redirect('/logout');
		}  
        if($page == "edit_workshop" || $page == "gallery_workshop"){
		   if(empty($p1))return redirect()->back();
		   
           $data['workshop_details'] = \App\Workshop::get_workshop(Auth::user()->id , $p1 );
           if($data['workshop_details'] != NULL){
			   $data['gallery_image'] = \App\Gallery::get_workshop_image($data['workshop_details']->id);
		      }
	    }


	    if($page == "add_business_details" || $page == "bank_details"){
            $data['bank_details'] = NULL;
		    $data['business_details'] = NULL;
		    $data['page'] = $page;
		   if($page == "add_business_details"){
			  $data['business_details'] = \App\BusinessDetails::get_business_details(Auth::user()->id);
			 }
		   if($page == "bank_details"){
			  $data['bank_details'] = \App\Bankdetails::get_bank_details(Auth::user()->id);
			 }	 
		   if(!empty($p1)){ $data['fill_form'] = TRUE;
		     $data['page_name_bread']  = 'Edit'; 
		   }
		   else{ $data['fill_form'] = FALSE;
		     $data['page_name_bread']  = 'Add';
		   }

             if($data['business_details'] != NULL && $p1 == NULL){
			    $data['page_name_bread']  = 'List';
			 }
		     if($data['bank_details'] != NULL && $p1 == NULL){
			    $data['page_name_bread']  = 'List';
			 }	
			$data['page_type'] = $data['page_name_bread'];
		}


	
	  

      if($page == "add_workshop"){
		   $data['parent_category'] = sHelper::get_parent_category();
		}
	if($page == "add_contact_details"){
			$data['get_workshop_mobile'] = \App\Common_mobile::get_mobile(Auth::user()->id);
		}
	  if($page == "add_address_details"){
			$data['address_list'] = \App\Address::get_address(Auth::user()->id);
		}
	if($page == "add_new_address") {
		$data['page'] = "add_new_address";
		$data['page_type'] = "Add";
		$data['workshop_id'] = Auth::user()->id;
	}
	if($page == "edit_address_details") {
		$data['result'] = \App\Address::find($p1);
		$data['page'] = "edit_address_details";
		$data['page_type'] = "Edit";
	}
// 	  if($page == "add_time_details"){
// 			$data['get_workshop_weekly_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);
// 			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
// 		}
        if($page == "add_time_details"){
			$data['get_workshop_weekly_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);
			$data['get_workshop_weekly_days_details'] = \App\Workshop_leave_days::get_add_off_dates(Auth::user()->id);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
		}

       if($page == "workshop_details"){
		    $data['workshop_details'] = \App\Workshop::get_workshop_details($p1);
			if($data['workshop_details'] != NULL){
		       $data['users_categories'] = \App\Workshop_users_category::get_categories($data['workshop_details']->id);
			    $data['gallery_image'] = \App\Gallery::get_workshop_image($data['workshop_details']->id);
				$data['address_list'] = \App\Address::get_address($data['workshop_details']->id);
				$data['get_workshop_weekly_days'] = \App\Workshop_user_day::get_all_days($data['workshop_details']->id);
				$data['get_workshop_mobile'] = \App\Common_mobile::get_mobile($data['workshop_details']->id);
			  }
			$data['parent_category'] = sHelper::get_parent_category(); 
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
		  } 
		if($page == "coupon"){
			$data['coupons'] = \App\Coupon::get_all_coupon();
			// echo'<pre>';print_r($data['coupon']);die;
			}
		
		if($page == "coupons"){
			  $data['coupons_details']  = '';
				$data['page_bread_crum'] = "Add";
				if(!empty($p1)){
					$data['page_bread_crum'] = "Edit";
					$data['coupons_details'] = \App\Coupon::find(decrypt($p1));
				}
		}
     
     if($page == "manage_time_slot"){
			$data['get_workshop_weekly_days'] = \App\Workshop_user_day::get_all_days(Auth::user()->id);
			$data['get_workshop_weekly_days_details'] = \App\Workshop_leave_days::get_add_off_dates(Auth::user()->id);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
			//echo "<pre>";
			//print_r($data['all_weekly_day']);exit;
		}

		if($page == "gallery"){
			$data['images_arr'] = \App\Gallery::get_all_images();
			
		}
     
     if(!view()->exists('common.'.$page))
			return view("404")->with($data);
	 else  	
	 return view("common.$page")->with($data); 	
  }
  
	// Add mutiple images
	public function gallery(Request $request){
		$images = $this->upload_multiple_image($request);
		$workshop = Auth::user()->id;
		foreach($images as $image){
		 \App\Gallery::add_multiple_images($image , $workshop);	
		}
		return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>success , </strong>Insert Successfully !!!.</div>')); 
	  }
	
	
   public function workshop_time_slot(Request $request){
	   $selected_arr = [];
		foreach($request->daysData as $day_data) {
			$selected_arr[] = $day_data['selected'];
		}
		if(!in_array("true" , $selected_arr)){
			return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Select at least one day with package !!!.</div>'));
		}
		/*Check And Exist*/
		$get_workshop_weekly_days = \DB::table('workshop_user_days')->where('users_id' , Auth::user()->id)->get();
		if($get_workshop_weekly_days->count() > 0){
		    $delete_weekly_days = \DB::table('workshop_user_day_timings')->where('users_id' , Auth::user()->id)->update(['deleted_at'=>now()]);
			$delete_weekly_days = \DB::table('workshop_user_days')->where('users_id' , Auth::user()->id)->update(['deleted_at'=>now()]);
		   }
		/*End*/
		foreach($request->daysData as $day_data) {
			$whole_day = 0;
			if($day_data['whole_days'] == "true"){ $whole_day = 1; }
				if($day_data['selected'] == "true"){
					$days_result = \App\Workshop_user_day::save_workshop_users_days($day_data['day'] , $whole_day);
					 //echo "<pre>";
						//print_r($days_result);exit;
				   if($whole_day != 1){
					 if($days_result){
						foreach($day_data['records'] as $record){
							\App\Workshop_user_day_timing::create_update($days_result , $record);
							} 
					   }
					 }
					else{
					   
						\App\Workshop_user_day_timing::create_update_2($days_result);
					 } 	
				}
			}
			return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved successfully  !!!.</div>'));
   }	

  
	public function change_rolls($rolls){
      $alloted_roles = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id] , ['deleted_at' , '=' , NULL] , ['roll_id' , '=' , $rolls]])->first();
	  if($alloted_roles != NULL){
		  $users_details = \App\User::find($alloted_roles->users_id);
		  $users_details->roll_id = $rolls;
		  session(['users_roll_type' => $rolls]);
		  return redirect('admin/dashboard');
		}
	  else{
		  return redirect()->back()->with(['msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Sorry , you have not alloted this roll  !!!.</div>']);
		}	
	   
   }  
	
	
}
