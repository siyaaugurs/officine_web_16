<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class WrackerServices extends Model
{
    public  $table = "wracker_services";
    protected $fillable = ['id', 'services_name', 'type_of_weight_1_2000', 'type_of_weight_2000_3000', 'time_per_km', 'loading_unloading_time', 'wracker_service_type','service_image', 'service_image_url', 'description', 'status', 'deleted_at', 'created_at', 'updated_at'];

    public static function add_wracker_services($request, $category_image) {
		if($request->cat_file_name == NULL) {
            $images = \App\Gallery::get_wrecker_images($request->wracker_service_id);
			$category_image = $images[0]['image_name'];
		}
		$image_url = url("storage/category/$category_image");
		return WrackerServices::updateOrCreate(
            ['id'=>$request->wracker_service_id] ,
            [
				'services_name' => $request->service_name,
				'type_of_weight_1_2000' => $request->weight_type_1,
				'type_of_weight_2000_3000' => $request->weight_type_2 ,
				'time_per_km' => $request->time_per_km ,
				'loading_unloading_time' => $request->loading_unloading,
				// 'wracker_service_type' => $request->service_type,
				'service_image' => $category_image,
				'description' => $request->description,
				'service_image_url' => $image_url,
				'status' =>'A',
            ]
        );
    }
    
    public static function get_wracker_services() {
        // return WrackerServices::where([['deleted_at', '=', NULL]])->get();
        $result = DB::table('wracker_services as ws')
                        //->leftjoin('workshop_wrecker_services as s' , 'ws.id' , '=' , 's.wracker_services_id')
                        ->select('ws.*')
                        ->where([['ws.deleted_at' ,'=' , NULL]])->get();
        return $result;
    }

    public static function edit_category_image($edit_id , $image = NULL){
	    $image_url = url("storage/category/$image");
	    return WrackerServices::where('id' , '=' , $edit_id)->update(['service_image'=>$image, 'service_image_url'=>$image_url]);
    } 
    
    public static function get_wracker_services_details($category_id) {
        return WrackerServices::where([['id', '=', $category_id]])->first();
    }
    public static function get_wrecker_Services($service_type) {
        if(!empty($service_type)) {
            return WrackerServices::where([['deleted_at', '=', NULL], ['wracker_service_type', '=', $service_type]])->get();
        }
    }
    public static function get_wrecker_service($service_id) {
        return WrackerServices::where([['id', '=', $service_id]])->first();
    }
    public static function get_wracker_services_sos() {
        $result = DB::table('wracker_services as ws')
                       // ->join('galleries as g' , 'ws.id' , '=' , 'g.category_id')
                        ->select('ws.*')
                        ->where([['ws.deleted_at' ,'=' , NULL] , ['']])->get();
        return $result;
    }
    
    
	public static function get_wracker_services_image($get_wracker) {
        return DB::table('galleries as g')->select('g.*')->where([['category_id','=',$get_wracker],['g.type' ,'=' , 8] , ['deleted_at' ,'=' , NULL]])->get();
    }
	
}
