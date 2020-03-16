<?php
namespace App\Http\Controllers\Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Auth;
use Notification;
use App\Notifications\SignupVerification;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
  
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    public function signup_verification($token){
        $userDetails = User::find($token);
        $userDetails->email_verified_at = date('Y-m-d H:i:s');
        if( $userDetails->save() ){
            Auth::login($userDetails);
            if($userDetails->roll_id == 1){
              return redirect('/seller/dashboard')->with(["login_success"=>'<div class="notice notice-success notice-sm"><strong>Success </strong> Login Successfully  !!! </div>']);  
            }
            elseif($userDetails->roll_id == 2){
                 return redirect('/vendor/dashboard')->with(["login_success"=>'<div class="notice notice-success notice-sm"><strong>Success </strong> Login Successfully  !!! </div>']);
                }
        }
    }
      
    protected function sign_in(Request $request){
         $validatedData = $request->validate([
              'name' => 'required', 'email'=>'required|email|unique:users' , 'password'=>'required' , 'confirm_password'=>'required' , 'roll_type'=>'required' ]);
        $result=  User::sign_in($request);
        if($result != NULL){
           Notification::send($result, new SignupVerification($result->id));
		   return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong>Success </strong> Registration  Successfully  ,  verification mail send in your registered mail , please verify . !!! </div>']);  
			}
		  elseif($result->roll_id == 2){
			  return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Please try again  !!! </div>']);
			}
    }
}
