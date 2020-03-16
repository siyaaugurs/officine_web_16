<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Tyre_pfu;
use Auth;

class Tyre_pfu extends Model{ 
		protected  $table = "tyre_pfus";
		protected $fillable = [
			'id', 'users_id', 'tyre_type','tyre_type_description','tyre_type_description_for_seller','tyre_type_description_for_customer' ,'category','category_description','admin_price','user_pfu' ,'vehicles','weights_of_tyres_from','weights_of_tyres_to', 'tyre_class','is_deleted' ,'created_at' , 'updated_at'];
			
		public static function get_tyre_pfu(){
			return  Tyre_pfu::where([['deleted_at' , '=' , NULL]])->get();
		}
		
		public static function add_pfu_detail($request){
			return Tyre_pfu::updateOrCreate(
					['id'=>$request->pfu_id] ,
					[
					'users_id'=>Auth::user()->id,	
					'tyre_type_description'=>$request->description,
				    'admin_price' => $request->admin_price,
					'tyre_class' => $request->tyre_class,
					]);
		}
		
		public static function get_pfu_details($pfu_id){
			return Tyre_pfu::where('id',$pfu_id)->first();
		}

		
		public static function get_cateegory_Tyre_pfu($category){
			return  Tyre_pfu::where('category',$category)->first();
		}
		public static function delete_admin_pfu($pfu_id) {
			return Tyre_pfu::where([['id' , '=' , $pfu_id]])->update(['deleted_at' => date('Y-m-d H:i:s')]);
		}
		

}	