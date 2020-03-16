<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class ExcutedQuery extends Model{
	
	protected  $table = "excuted_queries";
    protected $fillable = [
        'id', 'url', 'executed_status' , 'created_at' , 'updated_at'];    
	 
	public static function get_record($url){
	    return ExcutedQuery::where([['url' , '=' , $url]])->first();
	}
	
	public static function add_record($url){
	   return ExcutedQuery::create(['url'=>$url , 'executed_status'=>1]);
	}    
}
