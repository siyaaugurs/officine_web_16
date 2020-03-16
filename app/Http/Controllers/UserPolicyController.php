<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use sHelper;
use App\Users_category;
use DB;
use Session;


class UserPolicyController extends Controller{
    
 
		public function pages($page , $p1 = NULL){
		$data['page'] = $page;	
		$data['title'] = "Officine Top - $page";
        if (Auth::check()) {
		 $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();	
          $data['users_profile'] = \App\User::find(Auth::user()->id);
		  $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
		  $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
		}else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
		}
		$data['users_profile'] = \App\User::find(Auth::user()->id);
        if($page == "terms_condition"){
			//print_r($p1); die;
			$data['edit_condition'] = \App\TermsCondition::get_records_terms_condition($p1);
		   $data['terms_condition'] = \App\TermsCondition::get_terms_condition();
	    }
		 if(!view()->exists('userpolicy.'.$page))
				return view("404")->with($data);
		 else  	
		 return view("userpolicy.$page")->with($data); 	
	
  }
	public function post_action(Request $request , $action){

		if($action =='terms_condition')
		{
			$validator = \Validator::make($request->all(), [
				'title' => 'required', 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\TermsCondition::add_terms_condition($request->all());
				if($result){
					return redirect()->back()->with(['msg'=>'<div class="notice notice-success"><strong>Success ,</strong> saved Successfully. !! </div>']);; 
				} else {
					return redirect()->back(); 
					
				} 
		}
		   }
		   
	public function all_user_policy($page , $p1=null){
		$data['page'] = $page;	
		$data['title'] = "Officine Top - $page";
        if (Auth::check()) {
		 $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();	
          $data['users_profile'] = \App\User::find(Auth::user()->id);
		  $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
		  $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
		}else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
		}
		$data['edit_condition'] = \App\TermsCondition::get_records_terms_condition($p1);
	    $data['terms_condition'] = \App\TermsCondition::get_terms_condition();
			 if(!view()->exists('common.'.$page))
				return view("404")->with($data);
		 else  	
		 return view("common.$page")->with($data); 
	}

	



	
	
}
