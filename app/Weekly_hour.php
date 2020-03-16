<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Weekly_hour extends Model{
   
	  protected  $table = "common_weekly_days";
	  protected $fillable = [
        'id', 'name', 'created_at' , 'updated_at'
    ];
	
	public static function get_all_days(){
	  $result = \DB::table('common_weekly_days')->get();
	  if($result->count() > 0)return $result; else FALSE;
	}
	
}
