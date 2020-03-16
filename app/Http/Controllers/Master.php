<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use sHelper;

class Master extends Controller{
   
    public function index($page = "home",  $p1 = NULL){
        $data['page'] = $page;
		$data['title'] = "Officine Type master - ".$page;
			/*Delete Main category Table script start*/
		if($page == "delete_main_cat"){
	        if(!empty($p1)){
			   $main_cat_details = \App\MainCategory::where('id' ,'=' ,$p1)->update(['deleted_at'=>date('Y-m-d h:i:s')]);
			   if($main_cat_details){
				  	  return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> deleted successfully !!! </div>']);
				}
			   else{
				    return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);
				  }	  
			   
			 } 
			else{
			 return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);
			}  
		  }  
		 /*End*/ 
		  
		if($page == "delete_cat"){
		    if($p1 != NULL){
			      $category = \App\Category::find($p1);
				  $category->status = 1;
				  if( $category->save() ){
					  return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> deleted successfully !!! </div>']);
					}
				  else 
				    return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);	
			  }
		  }    
		if(!view()->exists('master.'.$page))
			return view("404")->with($data);
		else   
        return view("master.".$page)->with($data);
     }
	 
	public function post_action(Request $request , $action){
	    /*Edit New category script statrt*/
		if($action == "edit_new_category"){
	         $validator = \Validator::make($request->all(), [
              'category_name' =>'required','description'=>'required' , 'parent_category'=>'required']);
			 if($validator->fails()){
              return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
			 }
			 
			 $result=  \App\Category::edit_category($request);
			 if($result != NULL){
			    $price_time_response = \App\ServiceTimePrice::add_time($request->edit_id , $request);
				return json_encode(array('status'=>200 , "msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Record saved successfull !!! </div>'));
			   }
			  else{
			   return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>']); 
			 } 
		  }
		/*End*/
	    /*Add New category script start*/
		 if($action == "add_new_category"){
		   $validator = \Validator::make($request->all(), [
              'category_name' =>'required','description'=>'required' , 'parent_category'=>'required']);
			 if($validator->fails()){
              return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
             }
             if(!empty($request->parent_category)){
               $category_images = $this->upload_category_image($request);
			   $result=  \App\Category::add_category($request , $category_images[0] , 2);  	 
			  if($result){
			    foreach($category_images as $image){
				 $insert_category = \App\Gallery::add_category_gallery($image , $result->id);
			 	}
			  }
			  if($result != NULL){
				return json_encode(array('status'=>200 , "msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Category Save successfull !!! </div>'));
			   }
			  else{
			   return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>']); 
			   }  
             }
             else{
                return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Note </strong> Please select parent category !!! </div>']); 
			 }
			  
		   }
		 /*End*/  
	    /*upload category images start*/
	   if($action == "upload_category_image"){
	     if(count($request->cat_file_name) > 0){
		  $category_images = $this->upload_category_image($request); 	  
		  if(count($category_images) > 0){
			  $category_result =  \App\Category::edit_category_image($request->category_id , $category_images[0]); 
			  if($category_result){
				foreach($category_images as $image){
				 $insert_category = \App\Gallery::add_category_gallery($image , $request->category_id);
				}
			  if($category_result != NULL){
				 return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success notice"><strong> Success </strong> Image uploaded successfully !!! </div>'));
			   }
			  else{
				return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>'));
			 }	
				}
			}
		  } 
		else{
			return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger notice"><strong> Wrong </strong>Please Select at least one image  !!! </div>'));
			  }	 
		 }	
		/*End*/
		
	    if($action == "edit_category"){
		   $validator = \Validator::make($request->all(), [
		      'edit_category_id'=>'required' , 'category_name' =>'required' , 'description'=>'required' , 'small_price'=>'required' , 'average_price'=>'required' , 'big_price'=>'required' , 'small_time'=>'required' , 'average_time'=>'required' , 'big_time'=>'required']);
			  if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
		
		   	   $result = \App\Category::edit_category($request);
			   if($result != NULL){
				  $price_time_response = \App\ServiceTimePrice::add_price_time($request->edit_category_id , $request);
				   	return json_encode(array('status'=>200 , "msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Category Saved successfully !!! </div>'));
				  }
			   else{
				    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>']); 
				  }	  
		 }				
		 
		 
	   /*Category Add start*/	 
	  /*Add Category sscript start*/
		 if($action == "add_car_wash_category"){
		   $validator = \Validator::make($request->all(), [
              'category_name' =>'required','description'=>'required','small_time'=>'required' , 'average_time'=>'required' , 'big_time'=>'required']);
			 if($validator->fails()){
              return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
             }
		  $category_images = $this->upload_category_image($request); 	
		  if(count($category_images) > 0){
			  $result=  \App\Category::add_car_wash_category($request , $category_images[0] , 1); 
			  if($result){
				  $price_time_response = \App\ServiceTimePrice::add_time($result->id , $request);
				foreach($category_images as $image){
				  $insert_category = \App\Gallery::add_category_gallery($image , $result->id);
				}
			  if($result != NULL){
				return json_encode(array('status'=>200 , "msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Record Saved successfully !!! </div>'));
			   }
			  else{
			   return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> please try again !!! </div>']); 
			 }	
				}
			}
		  else{
			return json_encode(['status'=>100, "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Image is required !!! </div>']); 
			  }	
		 }
		/*End*/
		/*End*/ 
	} 
	
  public  function get_action(Request $request , $action){
       if($action == "category_details"){
		  if(!empty($request->category_id)){
			  $result = \App\Category::get_category_details($request->category_id);
			  if($result){
				 return json_encode(['status'=>200 , "response"=>$result]);
				}
			  else{
				 return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
				}	
			}
		  else{
			 return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']); 
			}	
		 }
      if($action == "get_category_details"){
		   $response_category =  \App\Category::get_category_details($request->category_id);
		   if($response_category != NULL){
			   return json_encode(['status'=>200, 'response'=>$response_category]);  
			 }
		   else{
			  return json_encode(['status'=>404 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']); 
			 }	 
		 }
     
       if($action == "get_car_wash_image"){  
		   if(!empty($request->category_id)){
			     $images = \App\Gallery::get_category_image($request->category_id);
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
												<a href='<?php echo url("vendor_ajax/delete_image/$image->id") ?>' data-imageid="<?php echo $image->id; ?>" data-categoryid="<?php if(!empty($image->category_id)) echo $image->category_id; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_delete">
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
		
		 if($action == "remove_image"){
			$image_details = \App\Gallery::find($request->delete_id);
			if($image_details != NULL){
					// $image_details->delete();
					$delte_img = \App\Gallery::where('id' ,'=' ,$request->delete_id)->update(['deleted_at'=>date('Y-m-d h:i:s')]);
					$image_arr = \App\Gallery::get_category_image($request->category_id);
					if($image_arr->count() > 0){
						$image_name = $image_arr[0]->image_name;
						$newimage_url = url("storage/category/$image_name");
						$result_image = \App\Category::find($request->category_id);
						$result_image->cat_images = $image_name;
						$result_image->cat_image_url  = $newimage_url;
						$result_image->save();
					}
					echo json_encode(array('status'=>200));
			}
	   	}
	}
   
}
