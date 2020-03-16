<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermsCondition extends Model
{
    //
	protected $table ="terms_conditions";
	protected $fillable=['id','title','terms_conditions_detail' , 'created_at' , 'updated_at'];
	
	
	public static function add_terms_condition($request){
			return TermsCondition::updateOrCreate(['id'=>$request['id']] ,[
					'title' => $request['title'],
					'terms_conditions_detail' => $request['terms_conditions_detail'],
					]);
	}
	public static function get_terms_condition(){
			return TermsCondition::get();
	}
	public static function get_records_terms_condition($id){
			return TermsCondition::where([['id' ,'=',$id]])->get();
	}
	
	
	
}
