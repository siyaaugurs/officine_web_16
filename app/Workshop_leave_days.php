<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Workshop_leave_days extends Model{
   
    protected $table = "workshop_leave_days";
    protected $fillable = ['id' , 'users_id' , 'off_date' , 'status' , 'deleted_at', 'created_at' , 'updated_at'];   

    public static function add_date($request){
		/*
		Status A = "leave Approove"
		*/
		$date = date("Y-m-d H:i:s", strtotime($request->off_date));
		return Workshop_leave_days::create(['users_id' => Auth::user()->id , 'off_date' =>$date , 'status' => 'A']);
				 
	}
	
	
	public static function get_valid_workshop($request){
	 return  Workshop_leave_days::where([['off_date' , '=' , $request->selected_date] , ['status' , '=' , 'A'] , ['deleted_at' , '=' ,NULL]])->get();
	}
	
	public static function get_add_off_dates($user_id){
        return 	$result = \DB::table('workshop_leave_days')
        ->select('off_date' , 'id')
        ->where([['users_id' , '=' , $user_id] , ['deleted_at' ,'=' ,NULL]])
        ->get();
    }
}
