<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Users_category;
use sHelper;
use kRomedaHelper;
use DB;
use Illuminate\Support\Facades\Validator;
use apiHelper;
use serviceHelper;

class SpecialCondition extends Controller{
 
 
   public function pages($page, $p1 = NULL){
	//   echo "hbzskjcnbdjk";exit;
	  $data['cars__makers_category'] = \App\Maker::all();
	   if(Auth::check()) {
			$data['users_profile'] = \App\User::find(Auth::user()->id);
			$data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
			$data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
			$data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
			$data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
		}else{
	      return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
	   $data['title'] = "Officine Top || ".$page;
	   $data['page'] = $page; 
	  
	    if($page == "remove_special_condition") {
			if(!empty($p1)){ 
				$days_exist = \App\specialCondition_days::get_special_service_days($p1);
				if(!empty($days_exist)) {
					$special_condition_days = \App\specialCondition_days::where('service_special_conditions_id', '=',  $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				}
				$special_condition = \App\Service_special_condition::find($p1);
				$special_conditions = \App\Service_special_condition::where('id', '=',  $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
				if($special_conditions){
					return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Record delete successfull !!! </div>']);
				} else {
					return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);
				} 
			} else {
				return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);   
			}
				
	    }
	  
	  if($page == "washing"){
		  $data['car_washing_category'] = sHelper::get_subcategory(1);
		  $data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
		  $data['cars__makers_category'] = \App\Maker::all();
	      $data['special_conditions'] = \App\Service_special_condition::get_special_service_condition(Auth::user()->id);
	      if($data['special_conditions']->count() > 0){
			foreach($data['special_conditions'] as $conditions){
				$conditions->maker_name =  $conditions->model_name = $conditions->version_name = ""; 
				$conditions->enctype_id =  encrypt($conditions->id);
				/*Set makers*/
				$conditions->maker_name = serviceHelper::get_maker_name($conditions);
				/*End*/
				/*Set Model Name */
				$conditions->model_name = serviceHelper::get_model_name($conditions);
				/*End*/
				/*Set Versions Name*/
				$conditions->version_name = serviceHelper::get_version_name($conditions);
				/*End*/
			}	
		}
	      
	  }
		if($page == "revision") {
			$data['car_revision_category'] = \App\WorkshopCarRevisionServices::get_car_revision_services();
			$data['special_conditions'] = \App\Service_special_condition::get_revision_special_service_condition(Auth::user()->id);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days(); 
			if($data['special_conditions']->count() > 0){
				foreach($data['special_conditions'] as $conditions){
					$conditions->maker_name =  $conditions->model_name = $conditions->version_name = ""; 
					$conditions->enctype_id =  encrypt($conditions->id);
					/*Set makers*/
					$conditions->maker_name = serviceHelper::get_maker_name($conditions);
					/*End*/
					/*Set Model Name */
					$conditions->model_name = serviceHelper::get_model_name($conditions);
					/*End*/
					/*Set Versions Name*/
					$conditions->version_name = serviceHelper::get_version_name($conditions);
					/*End*/
				}	
			}	
		}
		if($page == "request_quots") {
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days(); 
			$data['main_category'] = \App\MainCategory::where([['id' , '!=' , 25] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->get();
			$data['special_conditions'] = \App\Service_special_condition::get_request_quotes_special_condition(Auth::user()->id);
			foreach($data['special_conditions'] as $condition) {
				if($condition->category_id == 0) {
					$condition->service = "All Services";
				} else {
					$condition->service = $condition->main_cat_name;
				}
				$condition->enctype_id =  encrypt($condition->id);
				$condition->maker_name =  $condition->model_name = $condition->version_name = "";
				$condition->maker_name = serviceHelper::get_maker_name($condition);
				$condition->model_name = serviceHelper::get_model_name($condition);
				$condition->version_name = serviceHelper::get_version_name($condition);
			}
		}
		if($page == "edit_request_quots") {
			$p2 = decrypt($p1);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
			$data['main_category'] = \App\MainCategory::where([['id' , '!=' , 25] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 'A']])->get();
			$data['request_quotes_details'] = \App\Service_special_condition::get_request_quots_special_condition_details(Auth::user()->id, $p2);
			$data['p1'] = $p2;
		}


		if($page == "car_maintenance") {
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days(); 
			$data['car_maintenance_category'] = \App\ItemsRepairsServicestime::get_all_maintenance_items();
			$data['special_conditions'] = \App\Service_special_condition::get_maintenance_special_condition(Auth::user()->id);
		    if($data['special_conditions']->count() > 0){
				foreach($data['special_conditions'] as $conditions){
					$conditions->maker_name =  $conditions->model_name = $conditions->version_name = ""; 
					$conditions->enctype_id =  encrypt($conditions->id);
					/*Set makers*/
					$conditions->maker_name = serviceHelper::get_maker_name($conditions);
					/*End*/
					/*Set Model Name */
					$conditions->model_name = serviceHelper::get_model_name($conditions);
					/*End*/
					/*Set Versions Name*/
					$conditions->version_name = serviceHelper::get_version_name($conditions);
					/*End*/
				}	
			}
		}
		if($page == "mot_services") {
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days(); 
			$data['special_conditions'] = \App\Service_special_condition::get_mot_special_condition(Auth::user()->id);
			foreach($data['special_conditions'] as $condition) {
				if($condition->interval_description_for_kms == NULL) {
					$condition->service = "All Services";
				} else {
					$condition->service = $condition->interval_description_for_kms;
				}
				$condition->enctype_id =  encrypt($condition->id);
				$condition->maker_name =  $condition->model_name = $condition->version_name = "";
				$condition->maker_name = serviceHelper::get_maker_name($condition);
				$condition->model_name = serviceHelper::get_model_name($condition);
				$condition->version_name = serviceHelper::get_version_name($condition);
			}
		}
		if($page == "edit_mot_special") {
			$p2 = decrypt($p1);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
			$data['mot_details'] = \App\Service_special_condition::get_mot_special_condition_details($p2);
			$data['p1'] = $p2;
		}


		if($page == "assemble_services") {
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days(); 
			$data['assemble_services_category'] = Users_category::spare_group_services(Auth::user()->id);
			$data['special_conditions'] = \App\Service_special_condition::get_assemble_special_condition(Auth::user()->id);
		}
		
	    if($page == "wrecker_services") {
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days(); 
			$data['wrecker_services_category'] = \App\WrackerServices::get_wracker_services();
			$data['special_conditions'] = \App\Service_special_condition::get_wrecker_special_condition(Auth::user()->id);
			if($data['special_conditions']->count() > 0){
				foreach($data['special_conditions'] as $conditions){
					$conditions->maker_name =  $conditions->model_name = $conditions->version_name = ""; 
					$conditions->enctype_id =  encrypt($conditions->id);
					/*Set makers*/
					$conditions->maker_name = serviceHelper::get_maker_name($conditions);
					/*End*/
					/*Set Model Name */
					$conditions->model_name = serviceHelper::get_model_name($conditions);
					/*End*/
					/*Set Versions Name*/
					$conditions->version_name = serviceHelper::get_version_name($conditions);
					/*End*/
				}	
			}
		}
		
		if($page == "edit_washing") {
			 $data['model_name'] = ""; $data['model_value'] = "";
			$p2 = decrypt($p1);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
			$data['car_washing_category'] = sHelper::get_subcategory_1(1);
			$data['washing_details'] = \App\Service_special_condition::get_special_condition_details($p2);
   			if($data['washing_details'] != NULL){
   			     if(!empty($data['washing_details']->models)){
   			        if($data['washing_details']->models != "1"){
   			           $model_details =  \App\Models::get_model($data['washing_details']->models);
                       $data['model_name'] = $model_details->idModello." >> ".$model_details->ModelloAnno;
                       $data['model_value'] = $data['washing_details']->models;
   			        }
   			        else{
   			           $data['model_name'] = "All Models";
                       $data['model_value'] = 1;
   			        }
   			     }
   			     if(!empty($data['washing_details']->versions)){
   			        if($data['washing_details']->versions != "all"){
   			           $version_details = \App\Version::get_version($data['washing_details']->versions); 
                       $data['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
                       $data['version_value'] = $data['washing_details']->versions;
   			        }
   			        else{
   			           $data['version_name'] = "All Versions";
                       $data['version_value'] = "all";
   			        }
   			     }
   			     //echo "<pre>";
   			     //print_r($data['washing_details']);exit;
                                
   			}
   			//echo "<pre>";
  			//print_r($data['washing_details']);exit;
		}
		if($page == "edit_revision") {
			$p2 = decrypt($p1);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
			//$data['cars__makers_category'] = kRomedaHelper::get_makers();
			$data['car_revision_category'] = \App\WorkshopCarRevisionServices::get_car_revision_services();
			$data['revision_details'] = \App\Service_special_condition::get_revision_special_condition_details($p2);
			if($data['revision_details'] != NULL){
   			     if(!empty($data['revision_details']->models)){
   			        if($data['revision_details']->models != "1"){
   			           $model_details =  \App\Models::get_model($data['revision_details']->models);
                       $data['model_name'] = $model_details->idModello." >> ".$model_details->ModelloAnno;
                       $data['model_value'] = $data['revision_details']->models;
   			        }
   			        else{
   			           $data['model_name'] = "All Models";
                       $data['model_value'] = 1;
   			        }
   			     }
   			     if(!empty($data['revision_details']->versions)){
   			        if($data['revision_details']->versions != "all"){
   			           $version_details = \App\Version::get_version($data['revision_details']->versions); 
                       $data['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
                       $data['version_value'] = $data['revision_details']->versions;
   			        }
   			        else{
   			           $data['version_name'] = "All Versions";
                       $data['version_value'] = "all";
   			        }
   			     }
                                
   			}
		}
		if($page == "edit_maintenance") {
			$p2 = decrypt($p1);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
			//$data['cars__makers_category'] = kRomedaHelper::get_makers();
			$data['car_maintenance_category'] = \App\ItemsRepairsServicestime::get_all_maintenance_items();
			$data['maintenance_details'] = \App\Service_special_condition::get_maintenance_special_condition_details($p2);
			if($data['maintenance_details'] != NULL){
				if(!empty($data['maintenance_details']->models)){
					if($data['maintenance_details']->models != "1"){
						$model_details =  \App\Models::get_model($data['maintenance_details']->models);
						$data['model_name'] = $model_details->idModello." >> ".$model_details->ModelloAnno;
						$data['model_value'] = $data['maintenance_details']->models;
					}
					else{
						$data['model_name'] = "All Models";
						$data['model_value'] = 1;
					}
				} else {
					$data['model_name'] = NULL;
					$data['model_value'] = NULL;
				}
   			    if(!empty($data['maintenance_details']->versions)){
   			        if($data['maintenance_details']->versions != "all"){
   			           $version_details = \App\Version::get_version($data['maintenance_details']->versions); 
                       $data['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
                       $data['version_value'] = $data['maintenance_details']->versions;
   			        }
   			        else{
   			           $data['version_name'] = "All Versions";
                       $data['version_value'] = "all";
   			        }
   			    } else {
					$data['version_name'] = NULL;
					$data['version_value'] = NULL;
				}
                                
   			}
		}
		if($page == "edit_wrecker_service") {
			$p2 = decrypt($p1);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
			//$data['cars__makers_category'] = kRomedaHelper::get_makers();
			$data['wrecker_services_category'] = \App\WrackerServices::get_wracker_services();
			$data['wrecker_details'] = \App\Service_special_condition::get_wrecker_special_condition_details($p2);
			if($data['wrecker_details'] != NULL){
   			     if(!empty($data['wrecker_details']->models)){
   			        if($data['wrecker_details']->models != "1"){
   			           $model_details =  \App\Models::get_model($data['wrecker_details']->models);
                       $data['model_name'] = $model_details->idModello." >> ".$model_details->ModelloAnno;
                       $data['model_value'] = $data['wrecker_details']->models;
   			        }
   			        else{
   			           $data['model_name'] = "All Models";
                       $data['model_value'] = 1;
   			        }
   			    } else {
					$data['model_name'] = NULL;
					$data['model_value'] = NULL;
				}
   			    if(!empty($data['wrecker_details']->versions)){
   			        if($data['wrecker_details']->versions != "all"){
   			           $version_details = \App\Version::get_version($data['wrecker_details']->versions); 
                       $data['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
                       $data['version_value'] = $data['wrecker_details']->versions;
   			        }
   			        else{
   			           $data['version_name'] = "All Versions";
                       $data['version_value'] = "all";
   			        }
   			    } else {
					$data['version_name'] = NULL;
					$data['version_value'] = NULL;

				}
                                
   			}
		}
        if($page == "edit_assemble_services") {
			$p2 = decrypt($p1);
			$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
			//$data['cars__makers_category'] = kRomedaHelper::get_makers();
			$data['assemble_services_category'] = Users_category::spare_group_services(Auth::user()->id);
			$data['assemble_details'] = \App\Service_special_condition::get_assemble_special_condition_details($p2);
			if($data['assemble_details'] != NULL){
				if(!empty($data['assemble_details']->models)){
					if($data['assemble_details']->models != "1"){
						$model_details =  \App\Models::get_model($data['assemble_details']->models);
						$data['model_name'] = $model_details->idModello." >> ".$model_details->ModelloAnno;
						$data['model_value'] = $data['assemble_details']->models;
					} else {
					  	$data['model_name'] = "All Models";
				   		$data['model_value'] = 1;
				   	}
				}
				if(!empty($data['assemble_details']->versions)){
					if($data['assemble_details']->versions != "all"){
						$version_details = \App\Version::get_version($data['assemble_details']->versions); 
						$data['version_name'] = $version_details->Versione." >> ".$version_details->ModelloCodice;
						$data['version_value'] = $data['assemble_details']->versions;
					} else {
					  	$data['version_name'] = "All Versions";
				   		$data['version_value'] = "all";
				   	}
				}
		   }
		}
	  
	   /*TYre Special COndition Script Start*/
	   if($page == "tyre24" || $page == "edit_tyre_special_con"){
		$data['tyre_measurement'] = apiHelper::tyre_measurement();
		$data['tyre_type'] = $data['tyre_measurement']['tyre_type'];
		$data['season_tyre_type'] = $data['tyre_measurement']['season_tyre_type'];
		$data['tyre_group'] = DB::table('categories')->where([['category_type' , '=' , 23] , ['deleted_at' , '=' , NULL] , ['status' , '=' , 0]])->get();
		$data['all_weekly_day'] = \App\Weekly_hour::get_all_days();
		$data['special_conditions'] = \App\Service_special_condition::get_tyre_special_condition(Auth::user()->id);
		/*Arrange data in tyre specila condition*/
		  if($data['special_conditions']->count() > 0){
			  foreach($data['special_conditions'] as $conditions){
				  $conditions->maker_name =  $conditions->model_name = $conditions->version_name = ""; 
				  $conditions->enctype_id =  encrypt($conditions->id);
				  /*Set makers*/
				  $conditions->maker_name = serviceHelper::get_maker_name($conditions);
				  /*End*/
				  /*Set Model Name */
				  $conditions->model_name = serviceHelper::get_model_name($conditions);
				  /*End*/
				  /*Set Versions Name*/
				  $conditions->version_name = serviceHelper::get_version_name($conditions);
				  /*End*/
			  }
		  }
		/*End*/
  
		/*For Edit code*/
		if($page == "edit_tyre_special_con"){
			if(empty($p1))return redirct()->back();
			$id = decrypt($p1);
			$data['condition_detail'] = \App\Service_special_condition::find(decrypt($p1));
			 /*Get Models and version Details */
			//if(!empty($data['condition_detail']->models)){
				$data['model_arr'] = sHelper::get_and_set_model($data['condition_detail']->models);
			/* } else {
				$data['model_arr'] = NULL;
			} */
			//if(!empty($data['condition_detail']->versions)){
				$data['version_arr'] = sHelper::get_and_set_version($data['condition_detail']->versions);
			//}
			// echo "<pre>";
			// print_r($data);exit;
			 /*End*/
		}
		/*End*/
	   }
	  /*End*/	
	  
	  if(!view()->exists('specialCondition.'.$page))
			return view("404")->with($data);
	  else
       return view("specialCondition.".$page)->with($data);
   } 
   
   public function save_special_condition_days($request , $special_condition_id){
      if(!empty($request->weekly_days)){
		 foreach($request->weekly_days as $day_data) {
				if(!empty($day_data)) {
					$day_data = \App\specialCondition_days::updateOrCreate(
					['service_special_conditions_id'=>$special_condition_id , 
					 'days_id'=>$day_data, 
					],
					['service_special_conditions_id'=>$special_condition_id,
					 'days_id'=>$day_data , 'status'=>'A' , 'deleted_at'=>NULL]);
				}
				else return FALSE;	
		   }
		   return TRUE;	 
		}
	  return FALSE;		
   }
   
   
   public function post_action(Request $request, $action) {
		if($action == "add_request_quots_special_conditions") {
			if($request->repeat_type == 2){
				if(empty($request->weekly_days)){
					return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);  
				}
			}
			$validator = \Validator::make($request->all(), [
				'category_id' => 'required', 'car_makers' => 'required','car_models' => 'required','car_version' => 'required','operation_type' => 'required','start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required','start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
			} else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_request_quotes_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$insert_result->id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
		if($action == "edit_quotes_special_condition") {
			$days_exist = \App\specialCondition_days::get_special_service_days($request->special_condition_id);
			if(!empty($days_exist)) {
				$result = \App\specialCondition_days::where('service_special_conditions_id' , $request->special_condition_id)->delete();
			}
			if($request->repeat_type == 2){
				if(empty($request->weekly_days)){
					return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);  
				}
			}
			$validator = \Validator::make($request->all(), [
				'category_id' => 'required', 'car_makers' => 'required','car_models' => 'required','car_version' => 'required','operation_type' => 'required','start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required','start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
			} else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_request_quotes_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$insert_result->id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Updated Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
		if($action == "edit_mot_special_condition") {
			$days_exist = \App\specialCondition_days::get_special_service_days($request->special_condition_id);
			if(!empty($days_exist)) {
				$result = \App\specialCondition_days::where('service_special_conditions_id' , $request->special_condition_id)->delete();
			}
			if($request->repeat_type == 2){
				if(empty($request->weekly_days)){
					return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);  
				}
			}
			$validator = \Validator::make($request->all(), [
				'category_id' => 'required', 'operation_type' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required', 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required','start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
			} else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_mot_special_conditions($request, $start_date, $expiry_date,$all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id' => $insert_result->id , 'days_id' => $day_data , 'status' => 'A' ));
					}	
				}
			}
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Updated Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
   		if($action == "add_mot_special_conditions") {
			if($request->repeat_type == 2){
				if(empty($request->weekly_days)){
					return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);  
				}
			}
			$validator = \Validator::make($request->all(), [
				'category_id' => 'required', 'operation_type' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required', 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required','start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->category_id == "all"){
				$all_services = 1;
				$category_id = 0;
            } else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_mot_special_conditions($request, $start_date, $expiry_date,$all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id' => $insert_result->id , 'days_id' => $day_data , 'status' => 'A' ));
					}	
				}
			}
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
	   /*Edit Special condition script start*/
	    if($action == "edit_tyre_special_condition"){
		     $validator = \Validator::make($request->all(), [
               'all_tyre_group'=>'required' ,'operation_type'=>'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required','operation_type'=>'required','start_time'=>'required' , 'end_time'=>'required' , 'maximum_appointment'=>'required','start_date'=>'required','repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array("error"=>$validator->errors()->getMessages(), "status"=>400));
			}
		   /*for days validation */
			 if($request->repeat_type == 2) {
			     if(empty($request->weekly_days)){
				   return json_encode(['status'=>100,'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
				}
			 } 
			 $days_exist = \App\specialCondition_days::get_special_service_days($request->edit_id);
			if($days_exist->count() > 0){
			      /*soft delete Days */
				   $update_response = \App\specialCondition_days::where([['service_special_conditions_id' , '=' , $request->edit_id]])->update(['deleted_at'=>now() , 'status'=>'P']);
				  /*End*/
			   }
			/*End*/
			/*Set Category Or  all category details*/
			$all_services = 0;
		    $category_id = $request->all_tyre_group;
			if($request->all_tyre_group == 0){
			    $all_services = 1;
				$category_id = 0;
			 }
			/*End*/  
			/*Save In database script Start*/
			$response = \App\Service_special_condition::save_tyre_special_conditions($request , $all_services , $category_id);
			if($response){
			   if($request->repeat_type == 2) {
			      $days_response = $this->save_special_condition_days($request , $response->id);
			      }
				  return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record Save Successful !!!.</div>']); 
			   }
			 else{
			    return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']); 
			   }  
			
		  }
	   /*End*/
	   /*Save Tyre Special Condition Script Start*/
	    if($action == "save_tyre_special_condition"){
		     $validator = \Validator::make($request->all(), [
               'all_tyre_group'=>'required' ,'operation_type'=>'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required','operation_type'=>'required','start_time'=>'required' , 'end_time'=>'required' , 'maximum_appointment'=>'required','start_date'=>'required','repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array("error"=>$validator->errors()->getMessages(), "status"=>400));
			}
			/*for days validation */
			 if($request->repeat_type == 2) {
			     if(empty($request->weekly_days)){
				   return json_encode(['status'=>100,'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
				}
			 } 
			/*End*/
			/*Set Category Or  all category details*/
			$all_services = 0;
		    $category_id = $request->all_tyre_group;
			if($request->all_tyre_group == 0){
			    $all_services = 1;
				$category_id = 0;
			 }
			/*End*/  
			/*Save In database script Start*/
			DB::beginTransaction();
			$response = \App\Service_special_condition::save_tyre_special_conditions($request , $all_services , $category_id);
			$days_response = TRUE;
			if($request->repeat_type == 2) {
			  $days_response = $this->save_special_condition_days($request , $response->id);
			 }
			if(!$response || !$days_response){
			      DB::rollback();
				 return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			  }
			else {
                  DB::commit();
				  return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Save Successfully !!!.</div>']);
			  }  
			/*End*/
		  }  
	   /*End*/
       if($action == "edit_assemble_special_conditions") {
			// return $request;
			$days_exist = \App\specialCondition_days::get_special_service_days($request->special_condition_id);
			if(!empty($days_exist)) {
				$result = \App\specialCondition_days::where('service_special_conditions_id' , $request->special_condition_id)->delete();
			}
			$validator = \Validator::make($request->all(), [
                'category_id' => 'required', 'operation_type' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
			} else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$service = \App\Service_special_condition::find($request->special_condition_id);
			$service->category_id = $category_id;
			$service->all_services = $all_services;
			$service->makers = $request->car_makers;
			$service->models = $request->car_models;
			$service->versions = $request->car_version;
			$service->operation_type = $request->operation_type;
			$service->start_hour = $request->start_time;
			$service->end_hour = $request->end_time;
			$service->discount_type = $request->discount_type;
			$service->amount_percentage = $request->amount;
			$service->max_appointement = $request->maximum_appointment;
			$service->start_date = $request->start_date;
			$service->expiry_date = $request->expiry_date;
			$service->select_type = $request->repeat_type;
			$res = $service->save();
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$request->special_condition_id ,'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($res) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Updated Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
       if($action == "add_assemble_special_conditions") {
			// return $request;
			if($request->repeat_type == 2){
				if(empty($request->weekly_days)){
				   return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);				                  }
			}
			$validator = \Validator::make($request->all(), [
                'category_id' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
            } else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_assemble_special_conditions($request, $start_date, $expiry_date,$all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$insert_result->id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
       if($action == "edit_wrecker_special_condition") {
			$days_exist = \App\specialCondition_days::get_special_service_days($request->special_condition_id);
			if(!empty($days_exist)) {
				$result = \App\specialCondition_days::where('service_special_conditions_id' , $request->special_condition_id)->delete();
			}
			$validator = \Validator::make($request->all(), [
                'service_name' => 'required','weight_type' => 'required', 'car_makers' => 'required','car_models' => 'required','car_version' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' , 'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->service_name == 0){
				$all_services = 1;
				$category_id = 0;
            } else {
				$all_services = 0;
				$category_id = $request->service_name;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$service = \App\Service_special_condition::find($request->special_condition_id);
			$service->category_id = $category_id;
			$service->all_services = $all_services;
				$service->makers = $request->car_makers;
			$service->models = $request->car_models;
			$service->versions = $request->car_version;
			$service->operation_type = $request->operation_type;
			$service->weight_type = $request->weight_type;
			$service->start_hour = $request->start_time;
			$service->end_hour = $request->end_time;
			$service->discount_type = $request->discount_type;
			$service->amount_percentage = $request->amount;
			$service->max_appointement = $request->maximum_appointment;
			$service->start_date = $request->start_date;
			$service->expiry_date = $request->expiry_date;
			$service->select_type = $request->repeat_type;
			$res = $service->save();
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$request->special_condition_id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($res) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Updated Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
       if($action == "edit_maintenance_special_condition") {
			$days_exist = \App\specialCondition_days::get_special_service_days($request->special_condition_id);
			if(!empty($days_exist)) {
				$result = \App\specialCondition_days::where('service_special_conditions_id' , $request->special_condition_id)->delete();
			}
			$validator = \Validator::make($request->all(), [
                'category_id' => 'required', 'operation_type' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
			} else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$service = \App\Service_special_condition::find($request->special_condition_id);
			$service->category_id = $category_id;
			$service->all_services = $all_services;
			$service->makers = $request->car_makers;
			$service->models = $request->car_models;
			$service->versions = $request->car_version;
			$service->operation_type = $request->operation_type;
			$service->start_hour = $request->start_time;
			$service->end_hour = $request->end_time;
			$service->discount_type = $request->discount_type;
			$service->amount_percentage = $request->amount;
			$service->max_appointement = $request->maximum_appointment;
			$service->start_date = $request->start_date;
			$service->expiry_date = $request->expiry_date;
			$service->select_type = $request->repeat_type;
			$res = $service->save();
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$request->special_condition_id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($res) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Updated Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
       if($action == "edit_revision_special_condition") {
			$days_exist = \App\specialCondition_days::get_special_service_days($request->special_condition_id);
			if(!empty($days_exist)) {
				$result = \App\specialCondition_days::where('service_special_conditions_id' , $request->special_condition_id)->delete();
			}
			$validator = \Validator::make($request->all(), [
                'category_id' => 'required', 'operation_type' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
			} else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$service = \App\Service_special_condition::find($request->special_condition_id);
			$service->category_id = $category_id;
			$service->all_services = $all_services;
			$service->makers = $request->car_makers;
			$service->models = $request->car_models;
			$service->versions = $request->car_version;
			$service->operation_type = $request->operation_type;
			$service->start_hour = $request->start_time;
			$service->end_hour = $request->end_time;
			$service->discount_type = $request->discount_type;
			$service->amount_percentage = $request->amount;
			$service->max_appointement = $request->maximum_appointment;
			$service->start_date = $request->start_date;
			$service->expiry_date = $request->expiry_date;
			$service->select_type = $request->repeat_type;
			$res = $service->save();
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$request->special_condition_id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($res) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Updated Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
       if($action == "edit_special_condition") {
			if(!empty($request->special_condition_id)) {
				$days_exist = \App\specialCondition_days::get_special_service_days($request->special_condition_id);
				if(!empty($days_exist)) {
					$result = \App\specialCondition_days::where('service_special_conditions_id' , $request->special_condition_id)->delete();
				}
				$validator = \Validator::make($request->all(), [
					'category_id' => 'required', 'car_size' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required',
					'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required',
					'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
				]);
				if($validator->fails()){
					return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
				}
				if($request->category_id == 0){
					$all_services = 1;
					$category_id = 0;
				} else {
					$all_services = 0;
					$category_id = $request->category_id;
				}
				$start_date = (date("Y-m-d", strtotime($request->start_date)));
				$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
				$service = \App\Service_special_condition::find($request->special_condition_id);
				$service->category_id = $category_id;
				$service->all_services = $all_services;
				$service->makers = $request->car_makers;
				$service->models = $request->car_models;
				$service->versions = $request->car_version;
				$service->car_size = $request->car_size;
				$service->operation_type = $request->operation_type;
				$service->start_hour = $request->start_time;
				$service->end_hour = $request->end_time;
				$service->discount_type = $request->discount_type;
				$service->amount_percentage = $request->amount;
				$service->max_appointement = $request->maximum_appointment;
				$service->start_date = $request->start_date;
				$service->expiry_date = $request->expiry_date;
				$service->select_type = $request->repeat_type;
				$res = $service->save();
				if($request->repeat_type == 2) {
					foreach($request->weekly_days as $day_data) {
						if(!empty($day_data)) {
							$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$request->special_condition_id , 'days_id'=>$day_data , 'status'=>'A' ));
						}	
					}
				}
				if($res) {
					return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Updated Successfully !!!.</div>']);
				} else {
					return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
				}
			}
		}
       if($action == "add_wrecker_special_conditions") {
           if($request->repeat_type == 2){
              if(empty($request->weekly_days)){
                 return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);  
              }
           }
           
		   $validator = \Validator::make($request->all(), [
                'service_type' => 'required', 'service_name' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required',
                'weight_type' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' , 'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            if($request->service_name == 0){
				$all_services = 1;
				$category_id = 0;
            } else {
				$all_services = 0;
				$category_id = $request->service_name;
			}
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_wrecker_special_conditions($request, $start_date, $expiry_date,$all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$insert_result->id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
       if($action == "add_maintenance_special_conditions") {
           if($request->repeat_type == 2){
              if(empty($request->weekly_days)){
                 return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);  
              }
           }
           $validator = \Validator::make($request->all(), [
                'category_id' => 'required', 'operation_type' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
            } else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			/*if(!empty($request->car_makers)) {
				$cars_data = $request->car_makers;
				$car = explode('/', $cars_data);
				$car_id = $car[0];
				$car_name = $car[1];
			}*/
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_maintenance_special_conditions($request, $start_date, $expiry_date,$all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$insert_result->id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
       if($action == "add_revision_special_conditions") {
           if($request->repeat_type == 2){
              if(empty($request->weekly_days)){
                 return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);  
              }
           }
           $validator = \Validator::make($request->all(), [
                'category_id' => 'required', 'operation_type' => 'required','car_makers' => 'required','car_models' => 'required','car_version' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
            } else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			/*if(!empty($request->car_makers)) {
				$cars_data = $request->car_makers;
				$car = explode('/', $cars_data);
				$car_id = $car[0];
				$car_name = $car[1];
			}*/
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_revision_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$insert_result->id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			/*if(!empty($request->car_makers)) {
				foreach($request->car_makers as $cars_data) {
					if(!empty($cars_data)) {
						$car = explode('/', $cars_data);
						$car_id = $car[0];
						$car_name = $car[1];
						$cars_details = \App\SpecialConditionsCars::create(array('service_special_conditions_id'=>$insert_result->id , 'cars_id'=>$car_id, 'cars_name' => $car_name));
					}	
				}
			}*/
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
		if($action == "add_special_conditions") {
		  //  return $request;
		    if($request->repeat_type == 2){
              if(empty($request->weekly_days)){
                 return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please select any one weekly days !!!.</div>']);  
              }
           }
		    $validator = \Validator::make($request->all(), [
                'category_id' => 'required', 'car_size' => 'required',
                'operation_type' => 'required' , 'start_time'=>'required' , 'end_time'=>'required' ,  'maximum_appointment'=>'required',
                'start_date' => 'required' , 'repeat_type'=>'required' , 'expiry_date'=>'required' 
            ]);
            if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
           if($request->category_id == 0){
				$all_services = 1;
				$category_id = 0;
            } else {
				$all_services = 0;
				$category_id = $request->category_id;
			}
			/*if(!empty($request->car_makers)) {
				$cars_data = $request->car_makers;
				$car = explode('/', $cars_data);
				$car_id = $car[0];
				$car_name = $car[1];
			}*/
			$start_date = (date("Y-m-d", strtotime($request->start_date)));
			$expiry_date = (date("Y-m-d", strtotime($request->expiry_date)));
			$insert_result = \App\Service_special_condition::add_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id);
			if($request->repeat_type == 2) {
				foreach($request->weekly_days as $day_data) {
					if(!empty($day_data)) {
						$day_data = \App\specialCondition_days::create(array('service_special_conditions_id'=>$insert_result->id , 'days_id'=>$day_data , 'status'=>'A' ));
					}	
				}
			}
			if($insert_result) {
				return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Special Condition Added Successfully !!!.</div>']);
			} else {
				return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
			}
		}
	}

	public function get_action(Request $request, $action) {
	    if($action == "get_selected_day") {
			// return $request;
			if(!empty($request->edit_id)) {
				$selected_days = \App\specialCondition_days::get_special_service_days($request->edit_id);
				if($selected_days)
					return json_encode(array("status"=>200 , "response"=>$selected_days));	
				else 
					return json_encode(array("status"=>400));
			}
		}
	    if($action == "get_time_arrives") {
			if(!empty($request->service_name)) {
				$total_time = \App\WorkshopWreckerServices::get_total_times($request->service_name);
				if($total_time)
					return json_encode(array("status"=>200 , "response"=>$total_time));	
				else 
					return json_encode(array("status"=>400));
			}
		}
	    if($action == "get_services") {
			if($request->service_type == 0) {
				$services = \App\WrackerServices::get_wracker_services();
			} else {
				$services = \App\WrackerServices::get_wrecker_Services($request->service_type);
			}
			if(count($services) > 0)
				return json_encode(array("status"=>200 , "response"=>$services));	
			else 
				return json_encode(array("status"=>400));
		}
	    if($action == "get_selected_cars") {
			if(!empty($request->service_id)) {
				$car_data = \App\SpecialConditionsCars::get_special_service_cars($request->service_id);
				return view('specialCondition.component.selected_cars')->with(['car_data'=>$car_data]);
			}
		}
		if($action == "get_selected_days") {
			if(!empty($request->service_id)) {
				$day_data = \App\specialCondition_days::get_special_service_days($request->service_id);
				return view('specialCondition.component.selected_days')->with(['day_data'=>$day_data]);
			}
		}
		if($action == "delete_days") {
			// return $request;exit;
			if(!empty($request->days_id)) {
				$day_data = \App\specialCondition_days::delete_weekly_days($request->days_id);
				if($day_data) {
					return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Days Deleted Successfully !!!.</div>']);
				} else {
					return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
				}
			}
		}
		if($action == "delete_cars") {
			if(!empty($request->cars_id)) {
				$car_data = \App\SpecialConditionsCars::delete_cars($request->cars_id);
				if($car_data) {
					return json_encode(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Cars Deleted Successfully !!!.</div>']);
				} else {
					return json_encode(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']);
				}
			}
		}
	}
   
    
}
