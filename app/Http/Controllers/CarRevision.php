<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;

class CarRevision extends Controller{
   
    public function post_action (Request $request ,$action){ 
        /*if($action == "add_car_revision_category") {
            // return $request;exit;
            // $duplicate_exist = \App\Category::check_duplicate_category($request->category_name);
			// echo "<pre>";
			// print_r($duplicate_exist);exit;
			// if($duplicate_exist != NULL){
				// return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong> This Service Name is already listed !!!.</div>'));
			// } else {
                if (!empty($request->category_name) && !empty($request->time)) {
                    $result = \App\Category::add_car_revision_category($request);
                    if($result) {
                        return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Service Added successfully .</div>'));
                    } else {
                        return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
                    }
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Enter The Vlaues  .</div>'));
                }
			// }
        }*/
        if($action == "add_car_revision_category") {
			$validator = \Validator::make($request->all(), [
				'category_name' => 'required', 'description' => 'required',
			]);
			if($validator->fails()){
				return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
			}
			if(!empty($request)){
				$category_image = $this->upload_category_image($request); 
				if($category_image != 111){
					$result = \App\Category::add_car_revision_category($request, $category_image[0]);
                    if($request->cat_file_name){
                        foreach($category_image as $image){
                            $insert_category = \App\Gallery::add_car_revision_gallery($image , $result->id);
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
		if($action == "upload_car_revision_image") {
			if(count($request->cat_file_name) > 0){
				$category_images = $this->upload_category_image($request); 	 
                if(count($category_images) > 0){
					$category_result =  \App\Category::edit_category_image($request->category_id , $category_images[0]); 
                    if($category_result){
                        foreach($category_images as $image){
                            $insert_category = \App\Gallery::add_car_revision_gallery($image , $request->category_id);
                        }
                        if($category_result != NULL){
                            return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Image uploaded successfully !!! </div>'));
                        } else {
                            return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
                        }	
                    }
                }
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong>Please Select at least one image  !!! </div>'));
            }
		}
    }

    public function get_action( Request $request, $action) {
        if($action == "get_car_revision_image") {
			// return $request;exit;
			if(!empty($request->category_id)) {
				$images = \App\Gallery::get_car_revision_images($request->category_id);
                  if($images->count() > 0){
                     ?>
                     <div class="row">
                     <?php
                     foreach($images as $image){
                         ?>
                         <div class="col-sm-4 col-md-3 col-lg-3">
                                     <div class="card">
                                         <div class="card-img-actions m-1">
                                             <img class="card-img img-fluid" src="<?php echo $image->image_url; ?>" alt="" />
                                             <div class="card-img-actions-overlay card-img">
                                                 <a href='#' data-imageid="<?php echo $image->id; ?>" data-categoryid="<?php if(!empty($image->category_id)) echo $image->category_id; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_car_revision_images">
                                                     <i class="icon-trash"></i>
                                                 </a>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                         <?php
                       }
                     ?>
                      </div>
                     <?php  
                     exit;  
                  } 	
			}
		}
		if($action == "remove_car_revision_image") {
            $image_details = \App\Gallery::find($request->delete_id);
            if($image_details != NULL){
                    $delte_img = \App\Gallery::where('id' ,'=' ,$request->delete_id)->update(['deleted_at'=>date('Y-m-d h:i:s')]);
                    $image_arr = \App\Gallery::get_car_revision_images($request->category_id);
                    if($image_arr->count() > 0){
                        $image_name = $image_arr[0]->image_name;
                        $newimage_url = url("storage/category/$image_name");
                        $result_image = \App\Category::find($request->category_id);
                        $result_image->cat_images = $image_name;
                        $result_image->cat_image_url  = $newimage_url;
                        $result_image->save();
                    } 
            }
        }
		
        if($action == "workshop_car_revision_details"){
            if(!empty($request->max_appointment) && ( !empty($request->price))){
                $validator = \Validator::make($request->all(), [
                    'max_appointment' => 'required|regex:/^\d*(\.\d{2})?$/|not_in:0', 
                    'price'=>'required|regex:/^\d*(\.\d{2})?$/|not_in:0'
                ]);
                if($validator->fails()){
                    return json_encode(array( "error"=> $validator->errors()->getMessages(), "status" => 400));
                }
                $response = \App\WorkshopServicesPayments::save_update_car_revision($request);
                $get_car_revision = \App\Category::car_revision_service(Auth::user()->id);
                $request->service_id = NULL;
                if($get_car_revision->count() > 0){
                    foreach($get_car_revision as $key => $car_revision_service){
                        $request->service_id = $car_revision_service->id;
                        $response = \App\WorkshopCarRevisionServices::edit_service_price($request);
                    }
                }
                if($response)
                    return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong> Success , </strong> Record saved successfully !!!.</div>'));
                
                else
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
                  
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please fill all required fields !!!.</div>'));
            }   
        }
		
        if($action == "get_category_detail") {
            $result = \App\Category::get_car_revision_category($request->category_id);
			if($result != NULL){
				return json_encode(array("status"=>200 , "response"=>$result));	
			}
			else{
				return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-success"><strong>Success </strong> Record save successful !!! </div>'));	
			}
        }
    }
   
}
