<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TyreImage extends Model{

    protected  $table = "tyre_images";
    protected $fillable = [
        'id','tyre_item_id', 'tyre24_id', 'image' , 'image_url', 'image_type', 'type_status' , 'deleted_at', 'created_at' ,'updated_at'];
    public $type = [1=>"Tyre image" , 2=>'Tyre Label image'];

        public static function save_image($images , $tyre24_id , $type ,  $tyre24_item_id = NULL){
          if($type == 1) $type_status = "tyre image";  
          if($type == 2) $type_status = "tyre label image";  
          foreach($images as $image){
                $image_url = url("storage/tyre_images/$image");
                TyreImage::create(['tyre_item_id'=>$tyre24_item_id, 'tyre24_id' => $tyre24_id, 'image'=>$image , 'image_url'=>$image_url, 'image_type' => $type, 'type_status' =>$type_status]);
            }
          return TRUE;
        }
        
        public static function save_label_image($images , $request){
            foreach($images as $image){
                $image_url = url("storage/tyre_images/$image");
                TyreImage::create(['tyre_item_id'=>$request->tyre_item_id,'tyre24_id' =>$request->tyres_id,'image'=>$image , 'image_url'=>$image_url, 'image_type' => 2, 'type_status' => 'Label Images']);
            }
          return TRUE;
        }

        public static function get_all_tyre_image($tyre, $type){
            if($tyre->type_status == 1) {
                $where_clause = [['tyre_item_id', '=', $tyre->itemId], ['image_type', '=', $type] , ['deleted_at' , '=' , NULL]];
            } else {
                $where_clause = [['tyre24_id', '=', $tyre->id], ['image_type', '=', $type] , ['deleted_at' , '=' , NULL]];
            }
          return TyreImage::where($where_clause)->select('id' , 'tyre24_id' , 'tyre_item_id' , 'image as image_name' , 'image_url' ,'deleted_at')->get(); 
        }

        public static function get_tyre_image($tyre_item_id){
          return TyreImage::where([['tyre_item_id' , '=' , $tyre_item_id] , ['deleted_at' , '=' , NULL]])
          ->select('id' , 'tyre24_id' , 'tyre_item_id' , 'image as image_name' , 'image_url' ,'deleted_at')
          ->get(); 
        }
}
