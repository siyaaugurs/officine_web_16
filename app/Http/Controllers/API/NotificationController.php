<?php
namespace App\Http\Controllers\API;
use sHelper;
use apiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ManageAdverting;
use App\Coupon;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class NotificationController extends Controller{

/*send notification for user*/
public function show_notification(Request $request){
	if(Auth::user()->id){
		$push_notification_detail = DB::table('notifications')->where("notification_type" , '=' , 'Push')->get();
		if($push_notification_detail->count() >0){
			return sHelper::get_respFormat(1 ,"Push Notification show  !", null , $push_notification_detail);		
		} else {
			return sHelper::get_respFormat(0,"something went wrong,please try again !!!",null,null);		
		}
	} else {
		return sHelper::get_respFormat(0,"Unauthenticate , please login first .",null,null);
	}
}

public function add_notification_detail(Request $request){
	if(Auth::user()->id){
		$user_notification = \App\User_notification_detail::updateOrcreate(['user_id' => Auth::user()->id] ,[
			'user_id'=>Auth::user()->id,
			'lang' =>$request->lang,
			"notification_setting"=>$request->notification_setting,
			"notification_for_offer"=>$request->notification_for_offer,
			"notification_for_revision"=>$request->notification_for_revision,
		]);

		return sHelper::get_respFormat(1 ,"Add notification detail", null , null);		
	} else {
		return sHelper::get_respFormat(0,"Unauthenticate , please login first .",null,null);
	}
}

public function get_notification_detail(Request $request){
	if(Auth::user()->id){
		$user_notification = \App\User_notification_detail::where([['user_id','=', Auth::user()->id] ,['deleted_at' ,'=' ,NULL]])->first();
		if($user_notification != NULL){
			$user_notification->privacy_policy = 'https://services.officinetop.com/public/policy_pages/1';
			$user_notification->Terms_and_Conditions = 'https://services.officinetop.com/public/policy_pages/2';
			$user_notification->Cookies_information = 'https://services.officinetop.com/public/policy_pages/3';
			$user_notification->How_does_it_work  = 'https://services.officinetop.com/public/policy_pages/4';
		return sHelper::get_respFormat(1 ,"show notification detail", $user_notification , null);
		}else{
		return sHelper::get_respFormat(0 ,"No data", null , null);
		}				
	} else {
		return sHelper::get_respFormat(0,"Unauthenticate , please login first .",null,null);
	}

}
public function delete_account_for_customer(){
	if(Auth::user()->id){
		$user_info = \App\User::where([['id', '=' ,Auth::user()->id]])->first();   
		$user_info->deleted_at = now();
		$user_info->save();
	return sHelper::get_respFormat(1 ,"Your account deleted successfully.", null , null);		
	} else {
		return sHelper::get_respFormat(0,"Unauthenticate , please login first .",null,null);
	}
}


	

}
