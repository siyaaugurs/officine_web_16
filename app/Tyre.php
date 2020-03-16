<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Tyre extends Model{
    
	public static function get_tyre_info($input){
	//return Tyre::where('width' , $input['width'])->where('aspect_ratio',$input['aspect_ratio'])->where('rim_diameter',$input['rim_diameter'])->get();
	return Tyre::where([['width', '=', $input['width']], ['aspect_ratio' ,'=',$input['aspect_ratio']],['rim_diameter','=',$input['rim_diameter']]])->get();
	
	}
	

	
}
