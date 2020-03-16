<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;


class Category extends Model{
   
   	protected  $table = "categories";
   	protected $fillable = [
        'category_type','time', 'priority', 'price', 'status','parent_cat_id', 'category_name' , 'description', 'cat_images','cat_image_url', 'range_from', 'range_to', 'deleted_at','created_at' , 'updated_at'
    ];
	
 /*Defines category type  */	
/*
category_type = 1  For car washing 
*/
/*End*/    
   
   	
	public static function car_revision_service($user_id){
	   return DB::table('users_categories as uc')
	               ->join('categories as c' , 'c.category_type' , '=' , 'uc.categories_id')
				   ->where([['users_id' , '=' , $user_id] , 
	                        ['categories_id' , '=' , 2] ,
							['c.status' , '=' , 0]
							])->paginate(10);			
	}
   
     public static function add_category($request , $image , $type){
	    $image_url = url("storage/category/$image");
	    return Category::create(['category_type'=>$request->parent_category , 'parent_cat_id'=>$request->parent_category , 'category_name'=>$request->category_name  , 'cat_images'=>$image, 'cat_image_url'=>$image_url , 'description'=>$request->description,'status'=>'0']);
	}  
	
	public static function add_car_wash_category($request , $image , $type){
	    $image_url = url("storage/category/$image");
	    return Category::create(['category_type'=>$type  , 'parent_cat_id'=>$request->parent_category , 'category_name'=>$request->category_name , 'description'=>$request->description, 'cat_images'=>$image, 'cat_image_url'=>$image_url, 'status'=>0]);
	}
	
	
    public static function get_category(){
      return Category::where([['category_type' , '=' , 2], ['status', '=', 0]])->paginate(10);
	}	
	
	//Get Revision Services
	public static function get_revision_services(){
		return Category::where([['category_type' , '=' , 2], ['status', '=', 0]])->orderBy('priority' , 'DESC')->get();
	}
	public static function get_user_categories(){
		return DB::table('users_categories')->where([['categories_id' , '=' , 2], ['deleted_at', '=', NULL]])->get();			
	}
	//Get Revision Services
	
	
	public static function get_parent_category($parent_id = NULL){
		if(!empty($parent_id)){
		     return  Category::where([['parent_cat_id' , '=' , 1] , ['status' , '=' , 0]])->get();
		  } 
      return  Category::where([['parent_cat_id' , '=' , 0]])->get();
	}
   
   public static function edit_category($request){
	  // return $request->all();
	    //$image_url = url("storage/category/$image");
	    return Category::where('id' , '=' , $request->edit_id)->update(
		 ['category_type'=>$request->parent_category ,
		  'parent_cat_id'=>$request->parent_category ,
		  'category_name'=>$request->category_name, 
		  'description'=>$request->description,
		 ]);
	} 
	
	public static function get_cat_arr($cat_arr){
	   $result =  Category::wherein('id' , $cat_arr)->orderBy('category_name' , 'DESC')->get();
	   if($result->count() > 0){
		   return $result;
	   }else{
		   return FALSE;
	   }
	}
	
	public static function edit_category_image($edit_id , $image = NULL){
	    $image_url = url("storage/category/$image");
	    return Category::where('id' , '=' , $edit_id)->update(['cat_images'=>$image, 'cat_image_url'=>$image_url]);
	} 
	
	
	public static function get_category_details($category_id){
	   $result = DB::table('categories as c')
	                 ->leftjoin('service_time_prices as s' , 's.categories_id' , '=' , 'c.id')                     ->select('c.*' , 's.small_price' , 's.average_price' , 's.big_price' , 's.small_time' , 's.average_time' , 's.big_time')
	                 ->where('c.id' , $category_id)->first();
	   return $result;
	} 
  	
  	public static function get_feedback_category($category_id ){
	    if(!empty($category_id)) {
			return Category::select('category_name')->where('id' , '=' , $category_id)->first();
		}
	}
	public static function get_workshop_category($workshop_user_id) {
		if(!empty($workshop_user_id)) {
			return Category::where([['workshop_user_id' , '=' , $workshop_user_id], ['status', '=', 0]])->paginate(10);
		}
	}
	public static function check_workshop_category($workshop_user_id, $category_name) {
		if(!empty($workshop_user_id && $category_name)) {
			return Category::select('category_name')->where([['workshop_user_id' , '=' , $workshop_user_id], ['category_name' , '=' , $category_name], ['category_type', '=', 2]])->first();
		}
	}
	/*public static function add_car_revision_category($request) {
		return Category::updateOrCreate(
            ['id'=>$request->category_id] ,
            [
				'category_name' => $request->category_name ,
				'time' => $request->time ,
				'category_type' => 2,
				'status' => 0
            ]
        );
	}*/
	public static function add_car_revision_category($request, $category_image) {
		if($request->cat_file_name == NULL && $request->category_id != NULL) {
			$images = \App\Gallery::get_last_car_revision_images($request->category_id);
			if(!empty($images)) {
				$category_image = $images->image_name;
			}
		} 
		$image_url = url("storage/category/$category_image");
		return Category::updateOrCreate(
            ['id'=>$request->category_id] ,
            [
				'category_name' => $request->category_name ,
				'description' => $request->description ,
				'priority' => $request->priority ,
				'cat_images' => $category_image,
				'cat_image_url' => $image_url,
				'category_type' => 2,
				'status' => 0
            ]
        );
	}
	public static function get_car_revision_category($category_id) {
		if(!empty($category_id )) {
			return Category::where([['id' , '=' , $category_id]])->first();
		}
	}
	public static function get_all_category() {
			return Category::where([ ['category_type', '=', 2], ['status', '=', 0]])->get();
	}
	
	public static function check_duplicate_category($category_name) {
		if(!empty($category_name)) {
			return Category::select('category_name')->where([['category_name' , '=' , $category_name], ['category_type', '=', 2],['status', '=', 0]])->first();
		}
	}
   	public static function add_car_revision_service($request) {
		return Category::updateOrCreate(
            ['id'=>$request->category_id] ,
            [
				'category_name' => $request->category_name ,
				'price' => $request->price ,
				'category_type' => 2,
				'status' => 0,
            ]
        );
	}
	
	public static function get_sos_category() {
			return Category::where([['category_type', '=', 13]])->get();
	}
	
/*	public static function add_sos_category($request, $category_image) {
		$image_url = url("storage/category/$category_image");
		return Category::updateOrCreate(
            ['id'=>$request->sos_category_id] ,
            [
				'category_type' => 13,
				'parent_cat_id' => 13,
				'category_name' => $request->category_name ,
				'description' => $request->description ,
				'priority' => $request->priority,
				'cat_images' => $category_image,
				'cat_image_url' => $image_url,
				'status' =>$request->status,
            ]
        );
	}*/
	public static function add_sos_category($request, $category_image) {
		if($request->cat_file_name == NULL) {
			$images = \App\Gallery::get_sos_images($request->sos_category_id);
// 			echo "<pre>";
// 			print_r($images);exit;
			$category_image = $images[0]['image_name'];
		}
		$image_url = url("storage/category/$category_image");
		return Category::updateOrCreate(
            ['id'=>$request->sos_category_id] ,
            [
				'category_type' => 13,
				'parent_cat_id' => 13,
				'category_name' => $request->category_name ,
				'description' => $request->description ,
				'priority' => $request->priority,
				'cat_images' => $category_image,
				'cat_image_url' => $image_url,
				'status' =>$request->status,
            ]
        );
	}
	public static function get_sos_details($category_id) {
			return Category::where([['category_type', '=', 13], ['id', '=', $category_id]])->first();
	}
	
	public static function get_car_revision_services($category_type = NULL){
		if(!empty($category_type)){
		     return  Category::where([['category_type' , '=' , 2] , ['status' , '=' , 0]])->get();
		} 
      	return  Category::where([['category_type' , '=' , 0]])->get();
	}
   
   	public static function get_services($category_type = NULL){
	  return  Category::where([['category_type' , '=' ,$category_type ] , ['status' , '=' , 0]])->get();
	}
	//Add groups in tire management
	//Add groups in tire management
	public static function add_group($request, $category_image) {
		if($request->cat_file_name == NULL) {
			$images = \App\Gallery::get_groups_images($request->group_id);
			$category_image = $images[0]['image_name'];
		}
		// echo "<pre>";print_r($images);exit;
		$image_url = url("storage/category/$category_image");
		return Category::updateOrCreate(
			['id'=>$request->group_id] ,
            [
				'category_name' => $request->group_name ,
				'description' => $request->description ,
				'priority' => $request->group_priority ,
				'time' => $request->service_time ,
				'cat_images' => $category_image,
				'cat_image_url' => $image_url,
				'range_from' => $request->range_from,
				'range_to' => $request->range_to,
				'category_type' => 23,
				'status' => 0
			]
		);
	}
	//Get all groups
	public static function get_all_groups($category_type = NULL){
		return  Category::where([['category_type' , '=' ,$category_type ], ['deleted_at' , '=' , NULL]])->paginate(10);
	}
	//get group details by id
	public static function get_group_details($group_id) {
		return Category::where([['category_type', '=', 23], ['id', '=', $group_id]])->first();
	}
	//Delete group
	public static function delete_group($group_id) {
        return Category::where('id' , '=' , $group_id)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
	}
	//Get workshop category
	public static function get_workshop_tyre24_category($category_type) {
		return Category::where([['category_type' , '=' , $category_type] , ['status' , '!=' , 1], ['deleted_at' , '=' , NULL]])->orderBy('category_name' , 'DESC')->paginate(10);
	}

	public static function get_service_category($category_id ){
	    if(!empty($category_id)) {
			return DB::table('categories as a') 
						->where([['a.id' , '=' , $category_id]])
						->first(); 
		}
	}
   
}
