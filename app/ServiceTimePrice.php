<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ServiceTimePrice extends Model{
  
    protected  $table = "service_time_prices";
    protected $fillable = [
        'id', 'users_id','categories_id' , 'small_price', 'average_price', 'big_price' , 'small_time' , 'average_time' ,'big_time' ,  'created_at', 'updated_at'];
	
	
	 public static function add_time($category_id , $request){
	  return ServiceTimePrice::updateOrCreate(['categories_id'=>$category_id] ,
	                                          ['users_id'=>Auth::user()->id , 
											   'categories_id'=>$category_id ,
											   'small_time'=>$request->small_time, 
											   'average_time'=>$request->average_time, 
											   'big_time'=>$request->big_time, 
											  ]);
	}
		
    public static function add_price_time($category_id , $request){
	  return ServiceTimePrice::updateOrCreate(['categories_id'=>$category_id] ,
	                                          ['users_id'=>Auth::user()->id , 
											   'categories_id'=>$category_id ,
											   'small_price'=>$request->small_price , 
											   'average_price'=>$request->average_price , 
											   'big_price'=>$request->big_price, 
											   'small_time'=>$request->small_time, 
											   'average_time'=>$request->average_time, 
											   'big_time'=>$request->big_time, 
											  ]);
	}
	
	public static function get_time_price($cat_id){
	    return ServiceTimePrice::where('categories_id' , $cat_id )->first();
	}
	
	
}
