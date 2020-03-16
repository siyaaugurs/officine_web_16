<?php
namespace App;
use Illuminate\Database\Eloquent\Model;


class BrandLogo extends Model{
    
    protected  $table = "brand_logos";
    protected $fillable = ['id', 'brand_type',  'brand_name', 'image',  'image_url', 'unique_id', 'deleted_at','created_at', 'updated_at'];
    public $brand_logo_type = [ 1=>"Spare Parts brands" , 2=>"Tyre Brands" , 3 => "Rim Brand", 4 => "Car brand"];

    public static function get_brand_logo_details($brand_type = NULL) {
		if($brand_type != NULL){
		   return BrandLogo::where([['deleted_at' , '=' , NULL] , ['brand_type' , '=' , $brand_type]])->get();
		  }
        return BrandLogo::where('deleted_at' , '=' , NULL)->orderBy('brand_type' , 'ASC')->get();
    }

    public static function upload_brand_logo($images , $brand_name ,$brand_id , $brand_type) {
        $image_url = url("storage/brand_logo_image/$images");
        return BrandLogo::where([['id' , '=' , $brand_id]])->update(['brand_type'=>$brand_type , 'image'=>$images , 'image_url'=>$image_url , 'brand_name'=>$brand_name]);
    }
    
    public static function brand_logo($brand_name){
	   return BrandLogo::where([['deleted_at' , '=' , NULL] , ['brand_name' , '=' , $brand_name]])->first(); 
	}
	public static function brand_logo_tyre($brand_name){
	   return BrandLogo::where([['deleted_at' , '=' , NULL] , ['brand_name' , '=' , $brand_name] ,['brand_type' , '=' , 2]])->first(); 
	}
	
	public static function add_new_brand($request, $images) {
        $image_url = NULL;
        if(!empty($images)) {
            $image_url = url("storage/brand_logo_image/$images");
        }
	    return BrandLogo::create(['brand_type'=>$request->brand_type,'brand_name' => $request->brand_name ,  'image'=>$images, 'image_url' => $image_url]);
    }

    public static function get_brand($brand_type){
        return BrandLogo::where([['brand_type' , '=' , $brand_type] , ['deleted_at' , '=' , NULL]])->get();
    }
    public static function get_brand_name($brand_id){
        return BrandLogo::where([['id' , '=' , $brand_id] , ['deleted_at' , '=' , NULL]])->first();
    }
}
