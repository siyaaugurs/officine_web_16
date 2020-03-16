<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductsImage extends Model{
     protected  $table = "products_images";
	 protected $fillable = [
        'id', 'users_id',  'products_id' , 'product_kromeda_id' ,'CodiceArticolo' ,  'ls_CodiceListino' , 'image_name' , 'image_url','type','status','primary_status' ,'created_at' ,'deleted_at' , 'unique_id' , 'updated_at'];
	 
	 
	 public static function add_products_image($image_arr , $product_details){
	    $flag = 1;
		foreach($image_arr as $image){
			$image_url = url("storage/products_image/$image");
		    ProductsImage::create(['users_id'=>Auth::user()->id , 'products_id'=>$product_details->id , 'product_kromeda_id'=>$product_details->products_name ,'type'=>2,  'image_name'=>$image , 'image_url'=>$image_url , 'status'=>1 , 'primary_status'=>0]); 	  
		 $flag = 1;
		 }
		return TRUE; 
	 }
	 
	 public static function get_products_image($products_id){
	     $result =  ProductsImage::where('products_id' , '=' ,$products_id)->get();
	     if($result->count() > 0) return $result; 
		 else return FALSE;
	  }
	  
	public static function save_custom_product_image($image_arr , $product_id){
        $flag = 1;
		foreach($image_arr as $image){
			$image_url = url("storage/products_image/$image");
		    ProductsImage::create(['users_id'=>Auth::user()->id , 'products_id'=>$product_id,'type'=>2,  'image_name'=>$image , 'image_url'=>$image_url , 'status'=>1 , 'primary_status'=>0 , 'unique_id'=>uniqid()]); 	  
		 $flag = 1;
		 }
		return TRUE;
	 }  
	
	  
	public static function add_products_kromeda_image_url($product ,  $image_url , $product_id){
		return ProductsImage::updateOrCreate(['products_id'=>$product_id , 'ls_CodiceListino'=>$product->CodiceListino ,
	  	                                      'CodiceArticolo'=>$product->CodiceArticolo] ,
									 ['users_id'=>Auth::user()->id , 
									  'products_id'=>$product_id,
									  'CodiceArticolo'=>$product->CodiceArticolo, 
									  'ls_CodiceListino'=>$product->CodiceListino, 
									  'image_url'=>$image_url , 
									  'status'=>1 ,
									  'primary_status'=>1]); 	  
	}
	
  public static function add_products_kromeda_image_url_2($product ,  $image_url){
	    return ProductsImage::updateOrCreate(['products_id'=>$product->id , 'ls_CodiceListino'=>$product->CodiceListino ,
	  	                                      'CodiceArticolo'=>$product->CodiceArticolo] ,
									 ['users_id'=>Auth::user()->id , 
									  'products_id'=>$product->id ,
									  'CodiceArticolo'=>$product->CodiceArticolo, 
									  'ls_CodiceListino'=>$product->CodiceListino, 
									  'image_url'=>$image_url , 
									  'status'=>1 ,
									  'primary_status'=>1]); 	  
	} 
}
