<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class SupportTicketMessages extends Model{
	
	public  $table = "support_ticket_messages";
    protected $fillable = ['id', 'support_ticket_id', 'sender_id','messages', 'type',  'deleted_at', 'created_at' , 'updated_at'];
	
    
	
	public static function save_images($image , $support_ticket_id){
	  $file_url = url("storage/$image");
	  return SupportTicketMessages::create(['support_ticket_id'=>$support_ticket_id, 'sender_id'=>Auth::user()->id , 'messages'=>$file_url , 'type'=>1]);
	}
	
	public static function save_msg($msg , $support_ticket_id){
		$msg = \DB::connection()->getPdo()->quote($msg);
	  return SupportTicketMessages::create(['support_ticket_id'=>$support_ticket_id, 'sender_id'=>Auth::user()->id ,  'messages'=>$msg,'type'=>2]);
	}
	

	
}
