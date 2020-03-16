<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Gallery extends Model{
  
    protected  $table = "galleries";
    protected $fillable = [
        'id', 'users_id', 'workshops_id' , 'main_category_id' , 'category_id', 'services_id','group_id' , 
        'product_group_group_id', 'product_sub_group_group_id', 'products_groups_items_item_id', 'products_groups_items_id', 'feedback_id',  'image_name' ,
        'image_url' , 'type' , 'type_status' ,'primary_image', 'deleted_at', 'created_at' , 'updated_at', 'user_details_id'];
	
	/* 
	type = 9 for N3 category and tpte_status = N3 category
	//type =10 for workshop description

       
	 */


   public static function add_service_gallery($image , $service_id){
		  $image_url = url("storage/services/$image");
		  return  Gallery::create(['users_id'=>Auth::user()->id , 'services_id'=>$service_id , 'image_name'=>$image , 'image_url'=>$image_url , 'type'=>5 , 'type_status'=>'Service image']); 
	}
	
    
   public static function add_category_gallery($image , $category_id){
		  $image_url = url("storage/category/$image");
		  return  Gallery::create(['users_id'=>Auth::user()->id , 'category_id'=>$category_id , 'image_name'=>$image , 'image_url'=>$image_url , 'type'=>4 , 'type_status'=>'Category  image']); 
	}    
    public static function add_wrecker_service_gallery($image , $category_id){
		$image_url = url("storage/category/$image");
		return  Gallery::create(['users_id'=>Auth::user()->id , 'category_id'=>$category_id , 'image_name'=>$image , 'image_url'=>$image_url , 'type'=>8 , 'type_status'=>'Wrecker Service  image']); 
  	}
   public static function update_status_all_image($con){
	  if(is_array($con)){
		  $result = DB::table('galleries')->where($con)->update([
                'primary_image' => !1
           ]);
		  if($result)return TRUE; else return 1; 
		 }
	}
   	public static function get_wrecker_images($id) {
		return Gallery::where([['category_id', '=', $id], ['type', '=', 8], ['deleted_at', '=', NULL]])->get();
	}
  	public static function get_category_image($category_id){
		return  Gallery::where([['category_id', '=', $category_id], ['type', '=', 4], ['deleted_at', '=', NULL]])->get(); 
	}
	public static function get_service_category_image($category_id) {
		return  Gallery::where([['category_id', '=', $category_id], ['deleted_at', '=', NULL]])->get();
	}
   
   public static function get_image_details($row_id){
		return  Gallery::where('id' , '=' ,$row_id)->first(); 
	}
	
	public static function add_workshop_gallery($image_name , $workshop_id){
		$image_url = url("storage/workshop/$image_name");
		return  Gallery::create(['users_id'=>Auth::user()->id , 'workshops_id'=>$workshop_id , 'image_name'=>$image_name , 'image_url'=>$image_url , 'type'=>1]); 
	}
	
	public static function add_profile_image($image_name){
		$image_url = url("storage/profile_image/$image_name");
		return  Gallery::create(['users_id'=>Auth::user()->id ,'image_name'=>$image_name , 'image_url'=>$image_url , 'type'=>2, "type_status"=>"profile Image"]); 
	}
	
	public static function get_workshop_image($workshop_id){
		return  Gallery::where(['workshops_id'=>$workshop_id , 'type'=>1])->get(); 
	}
	

   public static function add_car_image($image_name , $request){
		if(!empty($request->default_image)){
		    $primary_image = 1;
		  }
		else{
		     $primary_image = 0;
		  }  
		$image_url = url("carlogo/$image_name");
		return  Gallery::create(['users_id'=>Auth::user()->id, 
		                          'user_details_id'=>$request->users_details_id , 
								  'image_name'=>$image_name , 
								  'image_url'=>$image_url , 
								  'type'=>3, 
								  'type_status'=>'users details', 'primary_image'=>$primary_image]); 
	}

    public static function users_details_image($car_users_details_id){
       return Gallery::where([['user_details_id' , '=' , $car_users_details_id]])->get();
	}
	
	
	public static function get_service_image($services_id){
	  return  Gallery::where(['services_id'=>$services_id , 'type'=>5])->get(); 
	}

    public static function remove_image($row_id){
	  return DB::table('galleries')->where('id' , '=' , $row_id)->delete();
	}
	
	public static function get_group_images($group_id , $product_group_group_id = 0) {
        return  Gallery::where([['group_id' , '=' , $group_id ]])
                        ->orwhere([['product_group_group_id' , '=' ,$product_group_group_id]])
                        ->get(); 
    }
    
	public static function get_n3_group_images($group_id) {
        return  Gallery::where([['products_groups_items_id' , '=' , $group_id ] , ['type', '=', 9] , ['deleted_at' , '=' , NULL]])->get(); 
    }
    
	public static function add_group_images($image , $group_id , $product_group_group_id = NULL, $sub_group_group_id = NULL){
	   $image_url = url("storage/group_image/$image");
	   return  Gallery::create(['users_id'=>Auth::user()->id , 'group_id'=>$group_id , 'product_group_group_id'=>$product_group_group_id , 'product_sub_group_group_id' => $sub_group_group_id,
	   'image_name' => $image , 'image_url'=>$image_url , 'type'=>5 , 'type_status'=>'Group  image']); 
	}

	public static function add_n3_group_images($image , $group_id, $product_item_details){
		$image_url = url("storage/group_image/$image");
		return  Gallery::create(['users_id'=>Auth::user()->id , 'products_groups_items_id'=>$group_id , 'products_groups_items_item_id' => $product_item_details->item_id ,  'image_name' => $image , 'image_url'=>$image_url , 'type'=>9 , 'type_status'=>'N3 Category  image']); 
	 }


	public static function get_feedback_images($feedback_id) {
		return  Gallery::where([['feedback_id' , '=' , $feedback_id ]])->get(); 
	}
	public static function add_sos_category_gallery($image , $category_id){
		$image_url = url("storage/category/$image");
		return  Gallery::create(['users_id'=>Auth::user()->id , 'category_id'=>$category_id , 'image_name'=>$image , 'image_url'=>$image_url , 'type'=>6 , 'type_status'=>'SOS  image']); 
  	}
  	
  	public static function get_sos_images($id) {
		  return Gallery::where([['category_id', '=', $id], ['type', '=', 6]])->get();
	  }
	  public static function get_sos_image($cat_id) {
		if(!empty($cat_id)) {
			return  Gallery::where(['category_id'=>$cat_id , 'type'=>6])->get(); 
		}
	}
	public static function add_car_revision_gallery($image , $category_id){
		$image_url = url("storage/category/$image");
		return  Gallery::create(['users_id'=>Auth::user()->id , 'category_id'=>$category_id , 'main_category_id'=>2 , 'image_name'=>$image , 'image_url'=>$image_url , 'type'=>7 , 'type_status'=>'Car Revision  image']); 
  	}
  	public static function get_last_car_revision_images($id) {
	  	return Gallery::where([['category_id', '=', $id], ['type', '=', 7], ['deleted_at', '=', NULL]])->first();
  	}
  	public static function get_car_revision_images($id) {
	  return Gallery::where([['category_id', '=', $id], ['type', '=', 7], ['deleted_at', '=', NULL]])->get();
  	}
	//Add group gallery images
	public static function add_group_gallery($image , $category_id){
		$image_url = url("storage/category/$image");
		return  Gallery::create(['users_id'=>Auth::user()->id , 'category_id'=>$category_id , 'main_category_id'=>23 , 'image_name'=>$image , 'image_url'=>$image_url, 'type'=>5, 'type_status'=>'Tyre24 image']); 
	}
	//Get group gallery images
	public static function get_groups_images($id) {
		return Gallery::where([['category_id', '=', $id], ['type', '=', 5 ], ['deleted_at', '=', NULL]])->orderBy('id' , 'DESC')->get();
	}
	public static function add_multiple_images($images,$workshop_id){
		$image_url = url("storage/group_image/$images");
		return  Gallery::create(['users_id'=>Auth::user()->id,'image_name'=>$images , 'image_url'=>$image_url , 'type'=>10, "type_status"=>"Workshop Image"]); 	
	}

	public static function get_all_images($user_id = NULL){
		if($user_id == NULL){ $user_id = Auth::user()->id; }
		return Gallery::where([['users_id', '=', $user_id], ['type', '=', 10], ['deleted_at', '=', NULL]])
		->orderBy('id' , 'DESC')->get();
	}
	public static function get_worshop_images($workshop_id){
		return Gallery::where([['users_id', '=', $workshop_id], ['type', '=', 10], ['deleted_at', '=', NULL]])->orderBy('id' , 'DESC')->get();
	}
	public static function save_feedback_images($image, $feedback_id) {
		$image_url = url("public/storage/$image");
		return  Gallery::create(['users_id' => Auth::user()->id,'image_name'=>$image , 'image_url'=>$image_url , "feedback_id"=>$feedback_id]);
	}
}
