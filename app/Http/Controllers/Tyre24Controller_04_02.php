<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use kRomedaHelper;
use \App\Users_category;
use sHelper;
use App\Http\Controllers\API\Tyre24Controller as Api_tyre24;


class Tyre24Controller_04_02 extends Controller{
	
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
			$data['get_tyres_list'] = \App\Tyre24::get_tyre();
		}
		
		if($page == "edit_tyre"){
			if(empty($p1))return redirect()->back(); 
			$tyre_obj = new Api_tyre24;
			$data['speed_index'] = $tyre_obj->speed_index_arr;
			$data['tyre_type'] = $tyre_obj->tyre_type_1;
			$data['season_tyre_type'] = $tyre_obj->season_tyre;
			$data['tyre_image'] = $data['tyre_details'] = $data['decode_tyre_type'] =  NULL;
			$data['manufacturer'] = \App\BrandLogo::get_brand_logo_details(2);
			$data['tyre'] = \App\Tyre24::get_tyres($p1);
			if($data['tyre'] != NULL){
				$data['tyre']->tyre_resp = json_decode($data['tyre']->tyre_response);
				$data['tyre']->tyre_image = $data['tyre']->tyre_details = $data['tyre']->tyre_label_image = "" ;
				$tyre_image = \App\TyreImage::get_all_tyre_image($data['tyre'], 1);
				$tyre_label_image = \App\TyreImage::get_all_tyre_image($data['tyre'], 2);
				if($tyre_image->count() > 0){
					$data['tyre']->tyre_image  = $tyre_image;
				} 
				if($tyre_label_image->count() > 0){
					$data['tyre']->tyre_label_image  = $tyre_label_image;
				} 
				$tyres_details = \App\Tyre24_details::get_tyre_details($data['tyre']->itemId);
				if($tyres_details != NULL){
					$data['tyre']->tyre_details = json_decode($tyres_details->tyre_detail_response);
				}
			}
		}
		//Manage groups
		if($page == "manage_groups"){
			$data['tyre_groups'] = \App\Category::get_all_groups(23);
			// echo "<pre>";print_r($data['tyre_groups']);exit;
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
		if($action == "get_tyre_from_database"){
			if(!empty($request->madel_value) && !empty($request->maker_name)){
				$maker_details = \App\Maker::get_makers($request->maker_name);
					if($maker_details != NULL){
						$maker_slug = sHelper::slug($maker_details->Marca);
						$model_detail = \App\Models::get_model($request->madel_value);
						if($model_detail != NULL){
							$model_slug = sHelper::model_slug_2($model_detail->Modello);
							$model_details_response = \App\Models_details::get_model_details($maker_slug , $model_slug , $model_detail->ModelloAnno , "ENG"); 
							if($model_details_response != NULL){
								if(!empty($model_details_response->tires)){
									$decode_tyre_response = json_decode($model_details_response->tires);
									$get_tyres_list = \App\Tyre24::get_tyres_list($decode_tyre_response);
									if($get_tyres_list->count() > 0){
										return view('tyre.component.tire_list')->with(['get_tyres_list'=>$get_tyres_list]);
											//return json_encode(array("status"=>200 , "response"=>$get_tyres_list));
										}
										else{
												echo '<div class="notice notice-danger"><strong>Wrong </strong> No record found !!! </div>';exit; 
											//return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Record not get from wheelsize  !!! </div>'));    
										} 
								}
							}
							else{
								echo '<div class="notice notice-danger"><strong>Wrong </strong> Record not get from wheelsize  !!! </div>';exit; 
							
							//	return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Record not get from wheelsize  !!! </div>')); 
							}
						} 
						else{
								echo '<div class="notice notice-danger"><strong>Wrong </strong> Model Not found  !!! </div>';exit;
							//return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Model Not found  !!! </div>')); 
						} 
					}	
					else{
						echo '<div class="notice notice-danger"><strong>Wrong </strong> Makers not found  !!! </div>';exit;
					//	return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Makers not found  !!! </div>')); 
					}
			}
			else{
					echo '<div class="notice notice-danger"><strong>Wrong </strong> Record not get from wheelsize  !!! </div>';exit; 
				//return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Record not get from wheelsize  !!! </div>')); 
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
			if(!empty($request->madel_value) && !empty($request->maker_name)){
			    $maker_details = \App\Maker::get_makers($request->maker_name);
				if($maker_details != NULL){
					$maker_slug = sHelper::slug($maker_details->Marca);
					if(!empty($maker_slug)){
						/*get model details from database */
						   $model_detail = \App\Models::get_model($request->madel_value);
						   if($model_detail != NULL){
								$model_slug = sHelper::model_slug_2($model_detail->Modello);
							   //$model_slug = sHelper::slug($model_detail->Modello);
							   /*Modeldetails From database*/
							   $model_details_response = \App\Models_details::get_model_details($maker_slug , $model_slug , $model_detail->ModelloAnno , "ENG"); 
							   //$model_details_response = \App\Models_details::get_model_details_new($maker_slug , $model_detail ,"ENG"); 
							   if($model_details_response == NULL){
								$wheel_model_details = sHelper::get_model_details($maker_slug , $model_detail);
								   $decode_response = json_decode($wheel_model_details);
								   if($decode_response->status == 200){
										if($decode_response->response != 500){
											//$model_details_response =  \App\Models_details::save_model_details($maker_slug , $model_slug , $model_detail->ModelloAnno , "ENG" , $decode_response->response);
											$model_details_response =  \App\Models_details::save_model_details($maker_slug , $model_slug , $model_detail->ModelloAnno , "ENG" , $decode_response->response , $model_detail);
											$model_details_response = \App\Models_details::get_model_details($maker_slug , $model_slug , $model_detail->ModelloAnno , "ENG");
											//$model_details_response = \App\Models_details::get_model_details_new($maker_slug , $model_detail ,"ENG"); 
										}
										else{
											return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Record not get from wheelsize  !!! </div>')); 
										}
								   }
								   else{
									return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Model details not found from wheelsize  !!! </div>')); 
								   }
							   }
							   if($model_details_response != NULL){
									/*Get Tires from tire24 API*/
									
									$save_tyre24_response = sHelper::save_tyre24response($model_details_response);
									/*  echo "<pre>";
						             print_r($save_tyre24_response);exit; */
									return $save_tyre24_response;
									
								   /* $get_tyre_response = sHelper::get_tyres($model_details_response); 
								   $tyre_decode_response = json_decode($get_tyre_response);  
								   if($tyre_decode_response->status == 200){
									   $tyre_response = json_decode($tyre_decode_response->response);
                        			   $save_tyres_response = \App\Tyre::save_tyres($tyre_response);
									   if($save_tyres_response != FALSE){
										  return json_encode(array("status"=>200 , "msg"=>'<div class="notice notice-success"><strong> Success </strong> Record Save Successful !!! </div>')); 
									   }
									   else{
										return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>')); 
									   }
								   }
								   else{
									return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>')); 
								   } */
							   }  
							   /*End*/
							}
							else{
								return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>')); 
							}
						
					  }
					else{
					  return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>'));
					  }  
				  }
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
		//.... Add pfu detail ...//
		if($action =='add_pfu'){
			$validator = \Validator::make($request->all(), [
				'tyre_type' => 'required', 
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
