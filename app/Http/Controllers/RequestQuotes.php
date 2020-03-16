<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use serviceHelper;
use DB;
use \App\Users_category;

class RequestQuotes extends Controller{
    
	
	public function index($page = "request_for_quotes" , $p1 = NULL){
	  $data['page'] = $page;
		$data['title'] = "Officine Top Workshop - ".$page;
        if(Auth::check()) {
           $data['users_profile'] = \App\User::find(Auth::user()->id);
            $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
           $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
           $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
			}else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
		 }
	  if($page == "request_for_quotes"){
	  	$data['workshop_status'] = serviceHelper::get_profile_status(Auth::user()->id);
		  $data['main_category'] = \App\MainCategory::where([['id' , '!=' , 25] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->get();
		  /*List*/
		  $data['service_list'] = DB::table('service_quotes_details as a')
		                             ->join('main_category as b' , 'b.id' , '=' , 'a.main_category_id')->where([['user_id' , '=' , Auth::user()->id] , ['b.deleted_at' , '=' , NULL], ['b.status' , '=' , 'A']])->select('a.*' , 'b.main_cat_name')->get();
		  /*End*/
		  //echo "<pre>";
		  //print_r($data['service_list']);exit;
		} 
	   
	  /*Load View script start*/
       if(!view()->exists('workshop.'.$page))
			return view("404")->with($data);
	   else
       return view("workshop.".$page)->with($data);
	  /*End*/
	}
	
	public function get_action(Request $request , $action){
	  if($action == "save_service_detail"){
		   $validator = \Validator::make($request->all(), [
			   'max_appointment'=>'required|not_in:0',
			   'hourly_rate'=>'regex:/^\d*(\.\d{2})?$/|not_in:0'
			]);
			if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
		} 
		
	  $save_response =  \App\Service_for_quotes::updateOrcreate(['main_category_id'=>$request->category , 'user_id'=>Auth::user()->id] , 
	    ['main_category_id'=>$request->category , 
		 'user_id'=>Auth::user()->id,
		 'max_appointment'=>$request->max_appointment,
		 'hourly_cost'=>$request->hourly_rate,
		]);
	   if($save_response){
		   return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong> Success </strong> Record Save Succesfull !!! </div>'));
		 }
	   else{
		 return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong !!! </div>'));
		 }	 	
	}
}
