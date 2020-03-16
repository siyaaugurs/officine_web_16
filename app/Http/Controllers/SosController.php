<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use sHelper;

class SosController extends Controller{

    public function post_action(Request $request, $action) { 
        if($action == "upload_category_image") {
            if(count($request->cat_file_name) > 0){
                $category_images = $this->upload_category_image($request); 	  
                if(count($category_images) > 0){
                    $category_result =  \App\Category::edit_category_image($request->category_id , $category_images[0]); 
                    if($category_result){
                        foreach($category_images as $image){
                            $insert_category = \App\Gallery::add_sos_category_gallery($image , $request->category_id);
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
        if($action == "add_sos_category"){
            // echo "<pre>";
            // print_r($request->all());exit;
            $validator = \Validator::make($request->all(), [
                'category_name' => 'required', 'description' => 'required',
               
            ]);
            if($validator->fails()){
                return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            if(!empty($request)){
				$category_image = $this->upload_category_image($request); 
				if($category_image != 111){
                    $result = \App\Category::add_sos_category($request, $category_image[0]);
                    if($request->cat_file_name){
                        foreach($category_image as $image){
                            $insert_category = \App\Gallery::add_sos_category_gallery($image , $result->id);
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

    public function get_action(Request $request, $action) {
        if($action == "get_category_details") {
            if(!empty($request->categoryId)) {
                $result = \App\Category::get_sos_details($request->categoryId);
                if($result){
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
        }
        if($action == "change_sos_category_status") {
            if(!empty($request->categoryId)){
                $result = \App\Category::find($request->categoryId);
                if($result != NULL){
                    $result->status = $request->status;
                    if($result->save()){
                        echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
                    }else{
                        echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
                    } 
                }
                else{
                    echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
                }	 
            }
            else{
                echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
            }
        }
        if($action == "get_sos_image"){  
            if(!empty($request->category_id)){
                  $images = \App\Gallery::get_sos_image($request->category_id);
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
                                                 <a href='#' data-imageid="<?php echo $image->id; ?>" data-categoryid="<?php if(!empty($image->category_id)) echo $image->category_id; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_sos_images">
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
             else{
               echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
               }
        }
        if($action == "remove_image") {
            $image_details = \App\Gallery::find($request->delete_id);
            if($image_details != NULL){
                $image_url = public_path("storage/category/");
                $filePath = $image_url.$image_details->image_name;
                if(file_exists($filePath)){ 
                    $image_details->delete();
                    $image_arr = \App\Gallery::get_sos_image($request->category_id);
                    echo "<pre>";
                    print_r($image_arr);exit;
                    if($image_arr->count() > 0){
                        $image_name = $image_arr[0]->image_name;
                        $newimage_url = url("storage/category/$image_name");
                        $result_image = \App\Category::find($request->category_id);
                        $result_image->cat_images = $image_name;
                        $result_image->cat_image_url  = $newimage_url;
                        $result_image->save();
                    } if(unlink($filePath)) {
                        echo json_encode(array('status'=>200));
                    }  
                }
                else{  echo json_encode(array('status'=>100)); } 
            }

        }
    }
}