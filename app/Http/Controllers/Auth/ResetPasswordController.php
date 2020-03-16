<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Notification;
use App\Notifications\ResetPassword;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller{
    public function __construct(){
        $this->middleware('guest');
    }

    public function page($page , $p1 = NULL){
      $data['page'] = $page;	
      $data['title'] = "Officine Type - Add Workshop";
      if($page == "password_reset"){
         //echo "yes working";exit;
        if(empty($p1)) return redirect('password/reset_password');
            $result = \DB::table('password_resets')->where(['token'=>$p1])
		                                           ->orderBy('created_at' , 'DESC')
												   ->first();
             //echo "<pre>";
             //print_r($result);exit;
              
             $currentDate = strtotime($result->created_at);
             $futureDate = $currentDate + (60*30);
             $formatDate = date("Y-m-d H:i:s", $futureDate);
              
             $now =  date("Y-m-d H:i:s");
             if($now > $formatDate){
                return redirect('password/reset_password')->with(['msg'=>'<div class="notice notice-danger"><strong> Note, </strong> Your Reset password link is expired . !!! . </div>']);
              }
             $data['enctype_id'] = $p1;
        }

      return view($page)->with($data); 	
    }

    public function action(Request $request , $action){
        if($action == "change_password"){
           if(!empty($request->password)){
                if($request->password == $request->confirm_password){
                    $userDetails = \App\User::where('remember_token' , '=' , $request->enctype_id)->first();
                    if($userDetails != NULL){
                        $userDetails->password = Hash::make($request->password);
                        if( $userDetails->save() ){
                            return redirect('/login')->with(['msg'=>'<div class="notice notice-success"><strong> Success, </strong> password has been changed successfully !!! . </div>']);
                        }
                        else{
                            return redirect()->back()->with(['msg'=>'<div class="notice notice-danger"><strong> Wrong , </strong>Something wrong please try again  !!! . </div>']);  
                        }
                    } 
                    else{
                       return redirect('password/reset_password')->with(['msg'=>'<div class="notice notice-danger"><strong>Wrong, </strong>Please generate new password reset link. !!! . </div>']);   
                    }
                }
                else{
               return redirect()->back()->with(['msg'=>'<div class="notice notice-danger"><strong>Wrong, </strong> Password does not match   !!! . </div>']); 
           }
           }
           else{
            return redirect()->back()->with(['msg'=>'<div class="notice notice-danger"><strong>Wrong, </strong> Password is required   !!! . </div>']); 
           }
        }
        
    }
    
    public function send_reset_notification(Request $request){
      /*  $data = array('name'=>"Virat Gandhi");
        Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to('jitendrasahu17996@gmail.com', 'Tutorials Point')->subject
               ('Laravel Basic Testing Mail');
            $message->from('info@eglobalinternational.in','Virat Gandhi');
         });
         echo "Basic Email Sent. Check your inbox.";
         exit; */
        set_time_limit(500);
        $userDetails = \App\User::where("email" , '=' , $request->sendOn)->first();
        if($userDetails  != NULL){
           /*  $data =  array('name'=>"JITU", 'email'=>"JITU" ,'message'=>$request->message);
            Mail::to('jitendrasahu17996@gmail.com')->send(new ContactMail($data));
            echo '<div class="notice notice-success"> <strong> Success </strong> Thank you for contact me .  We will contact shortly   .</div>.';exit; */
            $enctype = md5(time().$request->sendOn);
            $sendUrl = url("password/password_reset")."/".$enctype;
            $mailData = array("linkurl"=>$sendUrl);
            Notification::send($userDetails  , new ResetPassword($mailData));
             $userDetails->remember_token = $enctype;
            $result = \DB::table('password_resets')->insert(['email'=>$request->sendOn , 'token'=>$enctype , 'created_at'=>date('Y-m-d H:i:s')]);
            if( $userDetails->save() ){
                echo '<div class="notice notice-success"><strong>Success, </strong> Password Reset link Send in your Registered Mail , please check spam folder.</div>';exit;
                }
            else{
                echo '<div class="notice notice-danger"><strong>Wrong, </strong> Something Wrong  !!! . </div>';exit;
                }                  
            }  
        else{
              echo '<div class="notice notice-danger"><strong>Wrong, </strong> User credential did not match  !!! . </div>';exit;
            }		                         
    }

}
