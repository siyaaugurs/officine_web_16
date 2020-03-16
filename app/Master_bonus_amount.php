<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Master_bonus_amount extends Model
{
    //
	protected $table = 'master_bonus_amounts';
	protected $fillable = ['id','for_registration' ,'two_level_amount' ,'three_level_amount' ,'deleted_at'];
	
	public static function get_bouns_amount(){
	 $get_bouns = DB::table('master_bonus_amounts')->first();
	 return $get_bouns;
	} 
}
