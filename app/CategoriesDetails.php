<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriesDetails extends Model{
    
    protected  $table = "categories_details";
    protected $fillable = ['id', 'n1_n2_id', 'n1_n2_group_id' , 'n2_id'  , 'n2_group_id', 'n3_id' ,'n3_item_id', 'description', 'priority', 'status' , 'deleted_at','created_at', 'updated_at'];
     
    
   public static function save_group_details($request , $category_id , $category_group_id = NULL){
		if(!empty($category_group_id)){
	       $where_arr = ['n2_group_id'=>$category_group_id];
	    }
	    else{
	      $where_arr = ['n2_id'=>$category_id]; 
		  $category_group_id = NULL; 
	    }
        return CategoriesDetails::updateOrCreate($where_arr, 
                                                  ['n1_n2_id'=>$category_id , 
												   'n1_n2_group_id'=>$category_group_id ,
                                                   'description'=>$request->description , 
												   'priority'=>$request->priority]  
                                                   );
    }
	
   public static function save_sub_category_details($request , $category_id , $category_group_id = NULL){
	    if(!empty($category_group_id)){
	       $where_arr = ['n2_group_id'=>$category_group_id];  
	    }
	    else{
	      $where_arr = ['n2_id'=>$category_id]; 
	       $category_group_id = NULL;
		}
        return CategoriesDetails::updateOrCreate($where_arr, 
                                                  [ 'n2_id'=>$category_id, 
                                                    'n2_group_id'=>$category_group_id,
                                                    'description'=>$request->description,
                                                    'priority'=>$request->priority]  
                                                   );
    }

    public static function save_item_details($description , $priority , $n3_id , $n3_item_id = NULL){
        return CategoriesDetails::updateOrCreate([['n3_id' , '=' , $n3_id] ,
                                                  ['n3_item_id' , '=' , $n3_item_id] ], 
                                                  [ 'n3_id'=>$n3_id , 'n3_item_id'=>$n3_item_id ,
                                                    'description'=>$description , 'priority'=>$priority]  
                                                   );
    }
	
	public static function get_sub_category_details($category_id , $category_group_id){
	   if($category_group_id != NULL){
		    return CategoriesDetails::where([['n2_group_id' , '=' , $category_group_id]])->first();
		 }
		return CategoriesDetails::where([['n2_id' , '=' , $category_id]])->first(); 
	}

    
}
