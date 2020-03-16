<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use kRomedaHelper;
use App\Users_category;
use sHelper;
use apiHelper;
//use App\TraitsHelper\Rim;

trait  Rim{
	public function rim_list($rim){
		$rim->ET = $rim->size = $rim->rim_type =  $rim->workmanship = $rim->typeDescription = $rim->image = NULL;
		$decode_rim_response = json_decode($rim->rim_response); 	
		if(is_object($decode_rim_response)){
				if(!is_object($decode_rim_response->ET))
				$rim->ET = $decode_rim_response->ET;
				if(!is_object($decode_rim_response->size))
				$rim->size = $decode_rim_response->size;
				if(!is_object($decode_rim_response->type))
				$rim->rim_type = $decode_rim_response->type;
				if(!is_object($decode_rim_response->workmanship))
				$rim->workmanship = $decode_rim_response->workmanship;
				if(!is_object($decode_rim_response->typeDescription))
				$rim->typeDescription = $decode_rim_response->typeDescription;
				if(!is_object($decode_rim_response->alcar))
				$rim->alcar = $decode_rim_response->alcar;
			}
		$rim->image = sHelper::get_rim_image_main_image($rim);
		  /* echo "<pre>";
          print_r($rim->image);exit; */
    	return $rim;
	}
 }

class RimController extends Controller{
  
  use Rim;
    
   public function page($page , $p1 = NULL){
		$data['cars__makers_category'] = \App\Maker::all();
        $data['title'] = "Officine Top  - ".$page;
		$data['page'] = $page;
		$data['obj'] = $this;
        if (Auth::check() && Session::has('users_roll_type')) {
            $data['users_profile'] = \App\User::find(Auth::user()->id);
            $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
            $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
            $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
        }else{
        return redirect('logout')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
        }
        /*Manage rim section*/
        if($page == "manage_rim"){
            $data['get_rims'] = \App\Rim::get_rim_response();
		}
		/*End*/
		if($page == "edit_rim_details"){
		    if(empty($p1))return redirect()->back(); 
			$data['rim_details_response']  = $data['rim_details'] = NULL;
			$data['rim_image'] = collect();
			/*For number of holes*/
			$data['holes_list'] = apiHelper::rim_number_of_holes();
			/*End*/
			$data['rim'] = \App\Rim::where([['id' , '=' , $p1]])->first();
			$data['rim']->decode_rim_response = $data['rim']->images = $data['rim']->image = $data['rim']->rim_detail = NULL;
			if($data['rim'] != NULL){
				/*Rim response decoded*/
				$data['rim']->decode_rim_response = json_decode($data['rim']->rim_response);
				/*End*/
				$data['rim']->images = $data['rim']->rim_detail = NULL;
				/*Get Rim image script start*/
				$rim_image = sHelper::get_rim_image($data['rim']);
				if($rim_image->count() > 0){
					$data['rim']->images = $rim_image;
				}
				/*End*/
				/*Manage Rim Detail*/
				$rim_details = sHelper::get_rim_detail($data['rim']);
				if($rim_details != NULL){
                     $rim_details->decoded_response = json_decode($rim_details->rim_details_response);
				}
				$data['rim']->rim_detail = $rim_details;
				$data['rim']->image = sHelper::get_rim_image_main_image($data['rim']);
				/*End*/
			  }
			 //echo "<pre>";
			 //print_r($data['rim']) ;exit;
		  }
        /*manage_custom_rim  script start*/
		if($page == "manage_custom_rim"){
		     echo "<pre>";
			 print_r($page);exit;
		  }
		/*End*/ 
		 
        /*Load View Section start*/
        if(!view()->exists('rim.'.$page))
			return view("404")->with($data);
		else  
        return view("rim.".$page)->with($data); 
        /*End*/
   }

}
