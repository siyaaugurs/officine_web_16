<?php

namespace App;
use DB;
use Auth;

use Illuminate\Database\Eloquent\Model;

class Tyre_workshop_pfu extends Model
{
	protected  $table = "tyre_seller_pfus";
	protected $fillable = [
        'id','workshop_id', 'category', 'description', 'tyre_class', 'price', 'weights_of_tyres_from', 'weights_of_tyres_to','tyre_pfu_id','no_of_days','is_deleted' ,'created_at' , 'updated_at'];
        	
		/*public static function get_tyre_user_pfu($id){
			  return DB::table('tyre_seller_pfus as t')
						->leftjoin('tyre_pfus as tp' , 'tp.id' , '=' , 't.tyre_pfu_id')->where([['t.workshop_id', '=',$id],['t.deleted_at' , '=' , NULL]])->select('t.*' , 'tp.category')->get();
		}*/
		
		public static function get_tyre_seller_pfu($type,$id){
			   return DB::table('tyre_seller_pfus as t')
						->leftjoin('tyre_pfus as tp' , 'tp.id' , '=' , 't.tyre_pfu_id')->where([['t.workshop_id', '=',$id],['tp.tyre_type','=',$type],['t.deleted_at' , '=' , NULL]])->select('t.*' , 'tp.category')->get();
			//return  Tyre_workshop_pfu::where([['workshop_id', '=',$id],['deleted_at' , '=' , NULL]])->jeftjoin('tyre_pfus as t' , 't.id' , '=' , 't.tyre_pfu_id)->get();
		}

		public static function tyre_pfu_detail($tyre_type , $seller_id){
			return 	DB::table('tyre_pfus as a')
							->join('tyre_seller_pfus as b' , 'b.tyre_pfu_id' , '=' , 'a.id')
							->select('b.add_money' , 'b.no_of_days')
							->where([['a.tyre_type' , '=' ,$tyre_type] , ['b.workshop_id' , '=' , $seller_id] ,  ['a.deleted_at' , '=' , NULL]])
							->get();
		}	
		public static function add_pfu_detail($request) {
			return Tyre_workshop_pfu::updateOrCreate([
					'id' => $request->seller_pfu_id,
					'workshop_id' => Auth::user()->id,
				],
				[
					'workshop_id' => Auth::user()->id,
					'price' => $request->seller_price,
					'tyre_class' => $request->tyre_class,
					'description' => $request->description
				]);
		}
		public static function get_tyre_user_pfu() {
			return  Tyre_workshop_pfu::where([['deleted_at' , '=' , NULL], ['workshop_id', '=', Auth::user()->id]])->orderBy('id' , 'DESC')->get();
		}

		public static function get_seller_pfu_details($pfu_id) {
			return  Tyre_workshop_pfu::where([['workshop_id', '=', Auth::user()->id], ['id', '=', $pfu_id]])->first();
		}
		
		
}
