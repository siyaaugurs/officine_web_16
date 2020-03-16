<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
class Workshop_users_category extends Model{
     
   protected  $table = "workshop_users_categories";
   protected $fillable = ['id',
        'users_id', 'workshops_id', 'categories_id' , 'created_at','updated_at'
    ];
	
	
   public static function get_categories($workshop_id){
	  return  DB::table('workshop_users_categories as a')
	            ->leftjoin('categories as b' , 'a.categories_id' , '=' , 'b.id')
				->where('workshops_id' , '=' , $workshop_id)
				->select('a.*' , 'b.category_name')
				->get();
   }	
   
   public static function category_delete($id){
	  return  DB::table('workshop_users_categories')->where(array("id"=>$id))->delete(); 
	}
	
	public static function add_category_workshop($request){
		//return $request->al(l);
		$flag = 0;
	    foreach($request->category as $cat_id){
		     DB::table('workshop_users_categories')->insert(['users_id'=>Auth::user()->id , 'workshops_id'=>$request->workshop_id , 'categories_id'=>$cat_id]);
		    $flag = 1;
		   }
		 return $flag;  
	  //return  DB::table('workshop_users_categories')->where(array("id"=>$id))->delete(); 
	}
   
   
	
}
