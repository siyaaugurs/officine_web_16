<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Notification as Notification_model;
use App\User;
use Notification as Mail_Notification;
use App\Notifications\Notifications;

class Notification extends Controller{
	
	public function index(){
	  
	}
	
	
	public function get_action(Request $request){
	  if(!empty($request->notification_id)){
		   $notification_detail = Notification_model::find($request->notification_id);
		   if($notification_detail != NULL){
            if($notification_detail->notification_type == "Push") {
			   $users =  User::where([['roll_id' , '=' , 3] , ['device_token' , '!=' , NULL]])->get();
			   if($users->count() > 0){
				   $users_token = $users->pluck('device_token')->all();
				 }
			    $this->notification($users_token, $notification_detail);
			    /*foreach ($users as $user) {
                  $this->notification($user->device_token, $notification_detail->title);
                }*/
            }
            if($notification_detail->notification_type == "Email") {
                $roll_type = [];
                if($notification_detail->target_user == "All") {
                    $roll_type = [1,2,3];
                } else if($notification_detail->target_user == "Customer") {
                    $roll_type = [3];
                } else if($notification_detail->target_user == "Workshop") {
                    $roll_type = [2];
                } else if($notification_detail->target_user == "Seller") {
                    $roll_type = [1];
                }
                $users =  User::whereIn('roll_id', $roll_type)->get();
                Mail_Notification::send($users  , new Notifications($notification_detail));
            }
			echo '<div class="notice notice-success"><strong>Success , </strong> Message Send successfull  !!!.</div>';exit;
		 }
		}
	  else{
		  echo '<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>';exit;
		}	
	}
	
	
	  public function notification($tokenList, $noti_detail){
        /* echo "<pre>";
        print_r($noti_detail->file_url);exit;  */
        $token = $tokenList[1];
        $server_key = "AIzaSyD5KpnMe4sQKF70AW8haB274HiGgX9heRQ";
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $content = substr(strip_tags($noti_detail->content) , 0 , 100);
        $notification = ['title' => $noti_detail->title,'sound' => true, 'body'=>$content , 'image'=>$noti_detail->file_url];
        $more_data = ['image'=>$noti_detail->file_url , 'url'=>$noti_detail->url , 'body'=>$content];
        $extraNotificationData = ["message"=>$notification,"moredata" =>$more_data];
        $fcmNotification = [
            'registration_ids'=>$tokenList, //multple token array
            //'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];
        $headers = [
            'Authorization:key='.$server_key,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        return true;
    }
  
}
