<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model{
	
	public  $table = "support_tickets";
    protected $fillable = ['id', 'token','ticket_type', 'user_from', 'user_to', 'for_admin' , 'status', 'deleted_at', 'created_at', 'updated_at'];
    
	/*
	 status = 'A' = for running status , 
	          'C' = for closed status , 
	*/	
	
	
	public static function support_tickets(){
      return \DB::table('support_tickets as a')
	                   ->join('users as b' , 'b.id' , '=' , 'a.user_from')
					   ->select('a.id' , 'a.status' ,'b.f_name' , 'b.l_name' , 'a.user_from' , 'b.company_name' , 'b.email' , 'b.mobile_number' , 'b.profile_image' , 'b.roll_id' , 'b.users_status' , 'a.created_at')
					   ->orderBy('a.updated_at' , 'DESC')  
					   ->get(); 
	}
  
}
