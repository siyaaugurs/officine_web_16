<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Products_assemble extends Model{
    
    protected  $table = "products_assembles";
	  protected $fillable = [
        'id', 'users_id',  'products_id'  , 'pa_status' ,'created_at' ,'deleted_at' , 'updated_at'];
	
    public static function add_products($request){
      $flag = FALSE;
        foreach($request->products as $products_id){
            Products_assemble::updateOrCreate(['users_id'=>Auth::user()->id,'products_id'=> $products_id] , 
            ['users_id'=>Auth::user()->id , 'products_id'=>$products_id ,  "pa_status"=>"A"]); 
        $flag = TRUE;;     
        } 
      return $flag;  
    }
   
    public static function get_users_assemble_products_paginate($users_id){
	  return  DB::table('products_assembles as pa')
                         ->join('products_new as p' , 'p.id' , '=' , 'pa.products_id')
						 ->select("pa.*" , 'p.products_name'  , 'p.kromeda_description', 'p.our_products_description' , 'p.price' , 'p.seller_price' , 'p.CodiceListino' , 'p.CodiceArticolo' , 'p.kromeda_products_id')
						 ->where([['pa.users_id' , '=' , $users_id] , ['pa.deleted_at' , '=' , NULL] , ['p.products_status' , '=' , 'A']])
						  ->paginate(15); 
	}
    /*public static function get_assemble_products(){
        return  DB::table('products_assembles as pa')
                         ->join('products as p' , 'p.id' , '=' , 'pa.products_id')
						 ->where([['users_id' , '=' , Auth::user()->id]])
						  ->get(); 
    }*/
    
    public static function get_assemble_products($group_id = NULL){
        if($group_id != NULL || !empty($group_id)) {
            return  DB::table('products_assembles as pa')
                            ->join('products as p' , 'p.id' , '=' , 'pa.products_id')
                            ->where([['users_id' , '=' , Auth::user()->id], ['p.category_id', '=', $group_id]])
                            ->get(); 
        }
        return  DB::table('products_assembles as pa')
                            ->join('products as p' , 'p.id' , '=' , 'pa.products_id')
                            ->where([['users_id' , '=' , Auth::user()->id]])
                            ->get(); 
    }
    
   public static function get_products_assemble($products_arr){
			  return  DB::table('products_assembles as pa')
                         ->join('products_new as p' , 'p.id' , '=' , 'pa.products_id')
						 ->select("pa.*" , 'p.products_name'  , 'p.kromeda_description', 'p.our_products_description' , 'p.price' , 'p.seller_price' , 'p.CodiceListino' , 'p.CodiceArticolo' , 'p.kromeda_products_id')
						 ->where('pa.users_id','=',Auth::user()->id)
						 ->whereIn('pa.products_id' , $products_arr)
						  ->get(); 
	}
    
    public static function get_users_products_id($users_id){
	     return  DB::table('products_assembles as pa')
                         ->where([['pa.users_id' , '=' , $users_id] , ['pa.deleted_at' , '=' , NULL]])
						  ->get(); 
	}
}
