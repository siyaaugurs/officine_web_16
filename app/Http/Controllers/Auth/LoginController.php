<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use Notification;
use App\Notifications\SignupVerification;
use Session;

class LoginController extends Controller{
    
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }
	
	
	public function registration(){
	   $data['title'] = "Officine Top - Registration ";
       return view("registration")->with($data);
	}
	
	
	public static function forget_password(){
	   //$data['tit']
	}
	
	public function login(){
	   $data['title'] = "Officine Top - login";
       return view("login")->with($data);
	}
	
	public function sign_in(Request $request){
	    $validatedData = $request->validate(array(
              'email' => 'required','password' => 'required'));
	    $user_details = User::get_user_details($request);
		if($user_details != NULL){
			if($user_details->deleted_at == NULL ){
				if(Hash::check($request->password, $user_details->password)){
					  if($user_details->email_verified_at != NULL){
						session(['users_roll_type' =>$user_details->roll_id]); 
						 Auth::login($user_details); 
						 if($user_details->roll_id == 1){
							return redirect('/seller/dashboard')->with(["login_success"=>'<div class="notice notice-success notice"><strong>Success </strong> Login Successfully  !!! </div>']);  
						   }
						 elseif($user_details->roll_id == 2){
							return redirect('/vendor/dashboard')->with(["login_success"=>'<div class="notice notice-success notice"><strong>Success </strong> Login Successfully  !!! </div>']);
						   }	
						   elseif($user_details->roll_id == 4){
							   return redirect('/admin/dashboard')->with(["login_success"=>'<div class="notice notice-success notice"><strong>Success </strong> Login Successfully  !!! </div>']);
						   }
						   elseif($user_details->roll_id == 5){
							   return redirect('/master')->with(["login_success"=>'<div class="notice notice-success notice"><strong>Success </strong> Login Successfully  !!! </div>']);
						   }
					 } 
					else{
					  //echo "Null";exit;
					  Notification::send($user_details , new SignupVerification($user_details->id));
					  return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong>Note </strong> Your email is not verified at , verification code send in your registered mail , please verify your mail   !!! </div>']);
					 }  
	   
				   }
				else{
					 return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice">
						 <strong>Wrong </strong> Password does not match !!!
	   </div>']);  
				   }		
			}
			else{
				return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice">
				<strong>Wrong </strong> Your Account is blocked from our database !!!
</div>']);  
			}
		 }
		else{
		  return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice">
                      <strong>Wrong </strong> User does not exists with this credential !!!
    </div>']);
		} 
	}
	
	
}
