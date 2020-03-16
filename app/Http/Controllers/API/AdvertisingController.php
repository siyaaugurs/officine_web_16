<?php
namespace App\Http\Controllers\API;
use sHelper;
use apiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ManageAdverting;
use App\Advertising_image;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;



class AdvertisingController extends Controller{

public function get_all_advertising(Request $request){
	$advertising_datas = ManageAdverting::get_all_manage_advertising();
	if($advertising_datas->count() > 0){
		foreach($advertising_datas as $advertising_data){	
		$advertising_data->advertising_images  = [];
		$main_cat_id = 0;
		$images= Advertising_image::get_Advertising_images($advertising_data->id);
			if($images->count() >0){
				$main_cat_id = 0;
				$main_category_id = $advertising_data->main_category_id;
				$images->map(function ($image) use ($main_category_id){
					$image->main_cat_id = $main_category_id;
				});	
				$advertising_data->advertising_images = $images;
			}
		}
		
	}
	return sHelper::get_respFormat(1,null,null, $advertising_datas);  
	}	


}
