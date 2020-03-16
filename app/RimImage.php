<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RimImage extends Model{

    protected  $table = "rim_images";
    protected $fillable = [
        'id', 'rim_ids', 'rim_id', 'rim_alcar',  'image' , 'image_url' , 'deleted_at', 'created_at' ,'updated_at'];
    
        
        public static function save_image($images , $rim_id , $rim_alcar , $rim , $rim_ids = NULL){
            foreach($images as $image){
                $image_url = url("storage/rim_images/$image");
                RimImage::create(['rim_ids'=>$rim_ids , 'rim_id'=>$rim_id  ,'rim_alcar'=>$rim_alcar , 'image'=>$image , 'image_url'=>$image_url]);
            }
          return TRUE;
        }

        public static function get_rim_image($rim__alcar_id){
		    return RimImage::where([['rim_alcar' , '=' , $rim__alcar_id] , ['deleted_at' , '=' , NULL]])->get();
		}
}
