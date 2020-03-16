<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SupportTicket;
use Auth;
use sHelper;


class SupportController extends Controller{
	
	public function support_type(){
		return sHelper::get_respFormat(1 , null , null , $this->support_complain_type_app);
	}
    
	public function support_ticket(){
		if(Auth::check()){
		   $tickets =  SupportTicket::where(['user_from'=>Auth::user()->id])->orderBy('created_at' , 'DESC')->get();
		   if($tickets->count() > 0){
			    foreach($tickets as $ticket){
					$ticket->ticket_type_name = null;
					$ticket->ticket_type_name = $this->return_ticket_type($ticket->ticket_type);
					$ticket->messages = \App\SupportTicketMessages::where([['support_ticket_id' , '=' , $ticket->id]])->get(); 
				}
			return sHelper::get_respFormat(1 , null , null , $tickets);
		   }
		   else{
			return sHelper::get_respFormat(0 , "No tickets available !!!." , null , null);
		   }
		}
		else{
			return sHelper::get_respFormat(0 , "Please login first !!!." , null , null); 
		}
	}

	public function generate_support_ticket(Request $request){
		if(Auth::check()){
			if(empty($request->ticket_id)){
				$ticket_response = SupportTicket::create(['user_from'=>Auth::user()->id,
																 'ticket_type'=>(int) $request->ticket_type,
																 'user_to'=>$request->user_to,
																 'for_admin'=>'A',
																 'status'=>'A', 
																 ]);
			   }
			  else{
				 $ticket_response = SupportTicket::find($request->ticket_id);
			   }  
			   if($ticket_response != NULL){
					 if(!empty($request->images)){
						  $support_files = $this->upload_images($request);
						  if(count($support_files) > 0){
							  foreach($support_files as $key=>$image){
									$upload_image_response = \App\SupportTicketMessages::save_images($image , $ticket_response->id); 
									return sHelper::get_respFormat(1 , "" , $upload_image_response , null);   
								}
							}
					   }  
					  if(!empty($request->message)){
						  $save_msg_response = \App\SupportTicketMessages::save_msg($request->message , $ticket_response->id); 
						  return sHelper::get_respFormat(1 , "We will contact shortly !!!." , $save_msg_response , null);  
					   }
				   $ticket_response->updated_at = now();	
				   $ticket_response->save();
				  }
				else{
					return sHelper::get_respFormat(0 , "Something went wrong , please try again  !!!." , null , null); 
				}  
		}
	   else{
		 return sHelper::get_respFormat(0 , "Please login first !!!." , null , null); 
		}	  
	}
    
}
