<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ManageAdverting extends Model
{
    //
	protected $table = 'manage_advertings';
	protected $fillable = ['id','title','description','image','image_url','url','add_location','main_category_id','status'];
	
	public static function add_manageadverting($request){
		
	return	ManageAdverting::updateOrcreate(['id' => $request->id],
		['title' => $request->title,
		'description' => $request->description,
		'add_location' => $request->add_location,
		'url' => $request->url,
		'main_category_id' => $request->main_category_id,
        'status' =>0		
		]);
	}
	
	public static function get_all_manage_advertising(){
	 return	DB:: table('manage_advertings as a')->select('a.*' ,'c.main_cat_name')->leftjoin('main_category as c' ,'c.id' ,'=' ,'a.main_category_id')->where([['a.deleted_at' ,'=' ,NULL],['a.status','=',1]])->get();	
	}
	public static function get_all_manage_advertising_admin(){
	return  DB::table('manage_advertings as a')->select('a.*' ,'c.main_cat_name')->leftjoin('main_category as c' ,'c.id' ,'=' ,'a.main_category_id')->where([['a.deleted_at' ,'=' ,NULL]])->get();	
	}
	public static function edit_advertising_details($id){
		return ManageAdverting::where([['id','=' ,$id] , ['deleted_at' ,'=' ,NULL]])->first();
	}

	public static function get_all_nmanage_advertising_admin(){
		return	ManageAdverting::where([['deleted_at' ,'=' ,NULL]])->get();	
	   }
	// public static function delete_advertising($id){
	// 	return ManageAdverting::where([['id' ,'=',$id]])->delete();
		
	// }
}
