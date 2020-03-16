<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use kRomedaHelper;
use \App\Users_category;
use sHelper;
use apiHelper;
use App\TyreImage;
use App\Http\Controllers\API\Tyre24Controller as Api_tyre24;
use kromedaDataHelper;


class Tyre24Controller extends Controller{
	
    public function pages($page = "home" , $p1 = NULL){
		$data['cars__makers_category'] = \App\Maker::all();
		$tyre_obj = new Api_tyre24;
		$data['speed_index'] = $tyre_obj->speed_index_arr;
		$data['tyre_type'] = $tyre_obj->tyre_type;
		$data['title'] = "Officine Top  - ".$page;
        $data['page'] = $page;
        if (Auth::check() && Session::has('users_roll_type')) {
			$data['users_profile'] = \App\User::find(Auth::user()->id);
		     $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
			 $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
			 $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
		}else{
		  return redirect('logout')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
		$data['current_users_roll_type'] = Session::get('users_roll_type');
		
		if($page == "manage_tires"){
			$data['tyre_measurement'] = apiHelper::tyre_measurement();
			$data['get_tyres_list'] = \App\Tyre24::get_tyre();
		}
		
		
		if($page == "edit_tyre"){
			if(empty($p1))return redirect()->back(); 
			$tyre_obj = new Api_tyre24;
			$data['tyre_measurement'] = apiHelper::tyre_measurement();
			$data['season_tyre_type'] = $data['tyre_measurement']['season_tyre_type'];
			$data['tyre_type'] = $data['tyre_measurement']['tyre_type'];
			$data['speed_index'] = $data['tyre_measurement']['speed_index'];
			$data['tyre_image'] = $data['tyre_details'] = $data['decode_tyre_type'] =  NULL;
			$data['manufacturer'] = \App\BrandLogo::get_brand_logo_details(2);
			$data['tyre'] = \App\Tyre24::get_tyres($p1);
			$data['tyre'] = kromedaDataHelper::arrange_tyre_detail($data['tyre']);
			if($data['tyre'] != NULL){
				$vhicle_type = !empty($data['tyre']->vehicle_tyre_type) ? $data['tyre']->vehicle_tyre_type :  $data['tyre']->type;
				$vhicle_id = DB::table('master_tyre_measurements')->whereJsonContains('code' , (string)$vhicle_type)->where([['deleted_at' , '=' , NULL] , ['type' , '=' , 1]])->first();
				if($vhicle_id != NULL){
					 $vhicle_arr = 	json_decode($vhicle_id->code);
					 if(in_array($vhicle_type , $vhicle_arr)){
						$data['tyre']->vehicle_tyre_type  = $vhicle_arr[0]; 
					 }
				}
				$data['tyre']->tyre_resp = json_decode($data['tyre']->tyre_response);
				$data['tyre']->tyre_image = $data['tyre']->tyre_details = $data['tyre']->tyre_label_image = "" ;
				$tyre_image = TyreImage::get_all_tyre_image($data['tyre'], 1);
				$tyre_label_image = TyreImage::get_all_tyre_image($data['tyre'], 2);
				if($tyre_image->count() > 0){
					$data['tyre']->tyre_image  = $tyre_image;
				} 
				if($tyre_label_image->count() > 0){
					$data['tyre']->tyre_label_image  = $tyre_label_image;
				} 
			}
			
		}

		//Manage groups
		if($page == "manage_groups"){
			$data['tyre_groups'] = \App\Category::get_all_groups(23);
			// echo "<pre>";print_r($data['tyre_groups']);exit;
		}
		if($page == "manage_tyre_mesurement"){
			$data['page_type'] = $page;
			$data['tyre_type_measure'] = \App\MasterTyreMeasurement::get_type_measurement(1);
			foreach($data['tyre_type_measure'] as $type_measure) {
				$type_code = json_decode($type_measure->code);
				$type_measure->tyre_codes = implode(',', $type_code);
			}
		}
		if($page == "season_type_management"){
			$data['page_type'] = $page;
			$data['season_type_measure'] = \App\MasterTyreMeasurement::get_type_measurement(2);
		}
		if($page == "speed_index"){
			$data['page_type'] = $page;
			$data['speed_index'] = \App\MasterTyreMeasurement::get_type_measurement(3);
		}
		if($page == "aspect_ratio"){
			$data['page_type'] = $page;
			$data['aspect_measure'] = \App\MasterTyreMeasurement::get_type_measurement(4);
		}
		if($page == "manage_diameter"){
			$data['page_type'] = $page;
			$data['diameter_measure'] = \App\MasterTyreMeasurement::get_type_measurement(5);
		}
		if($page == "manage_width"){
			$data['page_type'] = $page;
			$data['width_measure'] = \App\MasterTyreMeasurement::get_type_measurement(6);
		}
		
		if($page == "manage_pfu"){
			$tyre_obj = new Api_tyre24;
			$data['tyre_type'] = $this->tyre_type;
			$data['tyre_type_arr'] = $this->tyre_type_arr;
			$data['category_type'] = $this->category_type;
			$data['category_type2'] =$this->category_type2;
			$data['tyre_pfu'] = \App\Tyre_pfu::get_tyre_pfu();
		}

		/*List of custom tires */
		if($page == "list_of_custom_tires"){
			$data['get_tyres_list'] = \App\Tyre24::where([['type_status','=',2]])->paginate(10);
			foreach($data['get_tyres_list'] as $tyre){
				  $tyre->imageUrl = NULL;
				  $tyre = \kromedaDataHelper::arrange_tyre_detail($tyre);
				  $tyre_image = \App\TyreImage::get_all_tyre_image($tyre , 1);
				  	 if($tyre_image->count() > 0){
						 $tyre->imageUrl  = $tyre_image[0]->image_url;
					 } 
			 }
		 }
		 /*End*/
		
		
		
		if(!view()->exists('tyre.'.$page))
			return view("404")->with($data);
		else  
		return view("tyre.".$page)->with($data); 
	}

	
	public static function ajax_get_action(Request $request , $action){
		if($action == "get_tyre_by_load_index") {
			if(!empty($request->load_index) && !empty($request->tyre_type)) {
				$tyre_details = \App\MasterTyreMeasurement::where([['id', '=', $request->tyre_type] , ['deleted_at' , '=' , NULL]])->first();
				$tyre_type = json_decode($tyre_details->code);
				$response = \App\Tyre24::whereIn('type', $tyre_type)->where([['speed_index', '=', $request->load_index], ['deleted_at', '=', NULL]])->get();
				if($response->count() > 0) {
					return view('tyre.component.tire_list')->with(['get_tyres_list'=>$response]);
				} else {
					echo '<div class="notice notice-danger"><strong>Wrong </strong> No record found !!! </div>';exit; 
			   	}
			}
		}
		if($action == "delete_admin_pfu") {
			if(!empty($request->pfu_id)) {
				$pfu_details = \App\Tyre_pfu::delete_admin_pfu($request->pfu_id);
				if($pfu_details) {
					return json_encode(array(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record Deleted Successfully !!!.</div>']));
				} else {
					return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
				}
			} else {
				return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
			}
		}
		if($action == "get_tyre_measure_details") {
			if(!empty($request->measure_id)) {
                $result = \App\MasterTyreMeasurement::get_tyre_measurement_details($request);
                if($result){
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
		}
		if($action == "delete_tyre_measure") {
			if(!empty($request->measure_id)) {
				$measure_data = \App\MasterTyreMeasurement::delete_tyre_measure($request->measure_id);
				if($measure_data) {
					return json_encode(array(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record Deleted Successfully !!!.</div>']));
				} else {
					return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
				}
			} else {
				return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
			}
		}

		if($action == "get_tyre_from_database"){
			if(empty($request->with_measurement) || empty($request->aspect_ratio_measurement) || empty($request->diameter_measurement)){
				echo '<div class="notice notice-danger"><strong>Wrong </strong> please select all required fields !!! </div>';exit; 
			}
			$get_tyres_list = \App\Tyre24::get_tyres_list($request->with_measurement , $request->aspect_ratio_measurement , $request->diameter_measurement);
			if($get_tyres_list->count() > 0){
				return view('tyre.component.tire_list')->with(['get_tyres_list'=>$get_tyres_list]);
			}
			else{
				echo '<div class="notice notice-danger"><strong>Wrong </strong> No record found !!! </div>';exit; 
		   } 	
		}
		
		if($action == "get_tyre_en_number_from_database"){
			if(!empty($request->en_number)){
			   $data['get_tyres_list'] = \App\Tyre24::where([['tyre_response->ean_number' , '=' , $request->en_number]])->get();
			   return view('tyre.component.tire_list')->with($data);			
			  }
			else{
			   echo '<div class="notice notice-danger"><strong>Wrong </strong> Record not get from wheelsize  !!! </div>';exit; 
			
				//return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Record not get from wheelsize  !!! </div>')); 
			}
		}
		
		
		if($action == "get_tyres"){
			if(empty($request->with_measurement) || empty($request->aspect_ratio_measurement) || empty($request->diameter_measurement)){
				/*get model details from database */
				return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Please select all required field  !!! </div>']);
			}
			else{
				$save_tyre24_response = sHelper::save_tyre24response($request->with_measurement , $request->aspect_ratio_measurement , $request->diameter_measurement);
				return $save_tyre24_response;
			}
	  	}
		
		//Get group Details
		if($action == 'get_group_details') {
			if(!empty($request->group_id)) {
                $result = \App\Category::get_group_details($request->group_id);
                if($result){
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
		}
		//Get Tyre pfu detail//
		if($action == 'get_pfu_details') {
			if(!empty($request->pfu_id)) {
                $result = \App\Tyre_pfu::get_pfu_details($request->pfu_id);
                if($result){
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
		}
		
		//Change group status
		if($action == "change_group_status") {
            if(!empty($request->group_id)){
                $result = \App\Category::find($request->group_id);
                if($result != NULL){
                    $result->status = $request->status;
                    if($result->save()){
                        echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
                    } else {
                        echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
                    } 
                } else{
                    echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
                }	 
            } else{
                echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
            }
		}
		//Delete Group
		if($action == 'delete_group') {
			if(!empty($request->group_id)) {
				$group_data = \App\Category::delete_group($request->group_id);
				if($group_data) {
					return json_encode(array(['status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Group Deleted Successfully !!!.</div>']));
				} else {
					return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
				}
			} else {
				return json_encode(array(['status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went Worng. Please Try Again !!!.</div>']));
			}
		}
		//Update service price and max appointment with bulk edit
		if($action == "workshop_tyre24_group_details"){
			$validator = \Validator::make($request->all(), [
				'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
				'hourly_rate'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$response = \App\WorkshopServicesPayments::save_update_workshop_tyre24_group_details($request);
			$workshop_tyre24_category = \App\Category::get_workshop_tyre24_category(23);
			$request->group_id = NULL;
			foreach($workshop_tyre24_category as $key => $tyre24) {
				$request->group_id = $tyre24->id;
				$result = \App\WorkshopTyre24Details::edit_workshop_tyre24_group_price($request);
			}
			if($response)
				return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong> Success , </strong> Record saved successfully !!!.</div>'));
			
			else
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));	
		}
		
	}
	//Groups Management
    public function ajax_post_action(Request $request , $action){
		if($action == "add_type_type_measurement") {
			$validator = \Validator::make($request->all(), [
				'tyre_type_name' => 'required', 
				'type_code' => 'required', 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\MasterTyreMeasurement::add_tyre_type_measure($request);
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			} 
		}
		if($action == "add_season_type_measurement") {
			$validator = \Validator::make($request->all(), [
				'season_type_name' => 'required', 
				'season_code' => 'required', 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\MasterTyreMeasurement::add_season_type_measure($request);
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			} 
		}
		if($action == "add_aspect_type_measurement") {
			foreach($request->aspect_ratio_value as $aspect_ratio) {
				$result = \App\MasterTyreMeasurement::add_aspect_ratio_measure($aspect_ratio, $request);
			}
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}
		}
		if($action == "edit_aspect_type_measurement") {
			$validator = \Validator::make($request->all(), [
				'aspect_ratio_value' => 'required',
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\MasterTyreMeasurement::add_aspect_ratio_measure($request->aspect_ratio_value, $request);
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}
		}
		if($action == "add_speed_index_measurement") {
			foreach($request->speed_index_value as $speed_index) {
				$result = \App\MasterTyreMeasurement::add_speed_index_measure($speed_index, $request);
			}
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}
		}

		if($action == "edit_speed_index_measurement") {
			$validator = \Validator::make($request->all(), [
				'speed_index_value' => 'required',
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\MasterTyreMeasurement::add_speed_index_measure($request->speed_index_value, $request);
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}
		}
		if($action == "add_diameter_measurement") {
			$validator = \Validator::make($request->all(), [
				'diametere_value' => 'required',
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			foreach($request->diametere_value as $diameter_value) {
				$result = \App\MasterTyreMeasurement::add_diameter_measure($diameter_value, $request);
			}
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}
		}
		if($action == "edit_diameter_measurement") {
			$validator = \Validator::make($request->all(), [
				'diametere_value' => 'required',
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\MasterTyreMeasurement::add_diameter_measure($request->diametere_value, $request);
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}
		}
		if($action == "add_width_measurement") {
			$validator = \Validator::make($request->all(), [
				'width_value' => 'required',
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			foreach($request->width_value as $width_value) {
				$result = \App\MasterTyreMeasurement::add_width_measure($width_value, $request);
			}
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}
		}
		if($action == "edit_width_measurement") {
			$validator = \Validator::make($request->all(), [
				'width_value' => 'required',
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\MasterTyreMeasurement::add_width_measure($request->width_value, $request);
			if($result){
				return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			} else {	
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			}
		}
		//.... Add pfu detail ...//
		if($action =='add_pfu'){
			$validator = \Validator::make($request->all(), [
				'admin_price' => 'required', 
				'tyre_class' => 'required', 
				'description' => 'required', 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			$result = \App\Tyre_pfu::add_pfu_detail($request);
			if($result){
			  return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
			}
			else{	
			   return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
			} 
		}
		if($action == 'add_group'){
			$validator = \Validator::make($request->all(), [
				'group_name' => 'required', 
                'service_time' => 'required' , 
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if(!empty($request)){
				$category_image = $this->upload_category_image($request); 
				if($category_image != 111) {
					$result = \App\Category::add_group($request, $category_image[0]);
                    if($request->cat_file_name){
                        foreach($category_image as $image){
                            $insert_category = \App\Gallery::add_group_gallery($image , $result->id);
                        }
                    }
					if($result != NULL){
						return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
					} else {	
						return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
					} 
				} else {
					echo json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Wrong </strong> only , JPG , JPEG , PNG format supported !!! </div>')); 
				}
				return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong>Something went wrong , please try again .</div>' , "status"=>100));
			}
		}
	}
}
