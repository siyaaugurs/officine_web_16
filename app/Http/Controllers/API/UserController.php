<?php
namespace App\Http\Controllers\API;
use Mail;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Config;
use App\Model\Social_logins;
use sHelper;
use apiHelper;
use DB;
use Notification;
use App\Notifications\ResetPassword;
use App\Notifications\SignupVerification;
use App\Notifications\AppSignup;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller {
    public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
    public function generate_referal_code(){
      /*Manage referal codes start*/
      foreach(User::all() as $user){
        $own_referal_code =  sHelper::slug($user->f_name).$user->id;
        $user->own_referal_code = $own_referal_code;
        $user->save(); 
      }
 /*End*/
   }


    public function login(){ 
     // if(!empty(request('device_token_id'))){
       $user_detail = User::where([['email' , '=' , request('email')]])->first();
       if($user_detail != NULL){
         if(!empty($user_detail->email_verified_at)){
            if($user_detail->deleted_at != NULL){
              return $this->respFormat(0,'Your account is deactive,please contact with admin',null , null); 
            }
           if(Auth::attempt(['email' => request('email'), 'password'=> request('password'), 'deleted_at' => NULL])){ 
               $user = Auth::user(); 
               if(!empty(request('device_token_id'))){
                 $user->device_token = request('device_token_id');
                 $user->save();
               }
               // $success['token'] =  $user->createToken('MyApp')-> accessToken; 
               // return $this->respFormat(1,'',$success,null);
               if(!empty($user->remember_token)){
                   $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                  $success['user_id']= $user->id;
                   return $this->respFormat(1,'',$success,null);
               }
               else
               {
                   return $this->respFormat(0,'Verification link sent on your registered mail.Verify the same to activate your account.',null,null);
               }
              
           }else{ 
               return $this->respFormat(0,'Incorrect email or password',null , null);
           } 
         }
         else{
          $mail_data = ['link'=>$user_detail->remember_token];
          Notification::send($user_detail  , new AppSignup($mail_data));
          return $this->respFormat(0,'you are not verified customer , please check your mail and verify  ,  !!!.',null,null);
         }
       }
       else{
        return $this->respFormat(0,'Please check your email , you are not registered customer !!!.',null,null);
       }
      
      /* }
      else{
        return $this->respFormat(0,'Something went wrong , Device token is required !!!',null,null);
      } */
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 


    public function register_bk(Request $request) 
    { 
      
      //return $this->respFormat(1,'',Config("app.url"),null);
        $req=$request->all();
        if($req['is_social'] == 0){
          $validator = Validator::make($request->all(), [
              'f_name' => 'required', 
              'mobile_number' => 'numeric| numeric', 
              'email' => 'required|email', 
              'password' => 'required', 
              'confirm_password' => 'required|same:password', 
          ]);
          if($validator->fails()) { 
             $error = $validator->errors();
             return $this->respFormat(0,$error->first() , null , null);
          }
          $input = $request->all(); 
          $input['password'] = bcrypt($input['password']); 
          $emailExists=User::where('email',$input['email'])->count();
          $mobileExists=User::where('mobile_number',$input['mobile_number'])->count();
          if($emailExists==0 && $mobileExists==0){
             $rem_token=md5(time().$input['email'].$input['mobile_number']);
           $user = User::create([
                  'f_name' =>$input['f_name'],
                  'l_name' => $input['l_name'],
                  'user_name'=>$input['f_name'].' '. $input['l_name'], 
                  'email'=>$input['email'], 
                  'mobile_number'=>$input['mobile_number'], 
                  'roll_id'=>3, 
                  'is_signed'=>'0',
                  'remember_token'=>$rem_token,
                  'password'=>$input['password'],
                 
              ]); 
              $success['token'] =  $user->createToken('MyApp')->accessToken; 
              $success['name'] =  $user->user_name;
                $arr=array();
                $arr['subject']='You are register with Officine Top successfully';
                $arr['to']=$user->email;
                $arr['toName'] = $user->user_name;
                $arr['from'] = 'xyz@gmail.com';
                $arr['fromName'] = 'Officinee Top';
                $arr['attach'] = array();
                $link = Config("app.url").'api/verifyEmail/'.$rem_token;
                $dataTemp['template']='
                <h1>Hi '.$user->user_name.',</h1>
                <p>Welcome to Officine.</p>
                <p><a target="__blank" href="'.$link.'" >Click here</a> to verify your email </p>';
                $mailResp = json_decode($this->sent_email($arr,$dataTemp));
               return $this->respFormat(1,'Verification link sent on your registered mail.Verify the same to activate your account.',$success,null);
          }
          elseif($emailExists>0)
          {
             return $this->respFormat(0,'Email already exists',null,null);
            
          }
          elseif($mobileExists>0)
          {

            return $this->respFormat(0,'Mobile number already exists',null,null);
          }
        }
        else
        {
          $input = $request->all(); 
          $validator = Validator::make($request->all(), [  
              'provider_name' =>'required', 
              'provider_id'   =>'required', 
          ]);
          if ($validator->fails()) { 
            $error=$validator->errors();
            return $this->respFormat(0,$error->first(),null,null);      
          }

          if(strlen($input['email'])>0 && strlen($input['mobile_number'])>0){
              $validator = Validator::make($request->all(), [  
                'email'=>'required|email',
                'mobile_number'=>'required | numeric'
              ]);
              if($validator->fails()) { 
                $error=$validator->errors();
                return $this->respFormat(0,$error->first(),null,null);      
              }
            // $exists=User::where(array('email'=>$input['email'],'mobile_number'=>$input['mobile_number']))->count();

          }
          if(strlen($input['email'])>0) {
            $validator = Validator::make($request->all(), [  
              'email'=>'required|email'
            ]);
            if($validator->fails()) { 
              $error=$validator->errors();
              return $this->respFormat(0,$error->first(),null,null);      
            }
            $exists=User::where('email',$input['email'])->count();
          }
          elseif(strlen($input['mobile_number'])>0){
            $validator = Validator::make($request->all(), [  
              'mobile_number'=>'required | numeric'
            ]);
            if($validator->fails()) { 
              $error=$validator->errors();
              return $this->respFormat(0,$error->first(),null,null);      
            }
            $exists=User::where('mobile_number',$input['mobile_number'])->count();
          }
          if($exists==0){
              $rem_token=md5(time().$input['email'].$input['mobile_number']);
              $input['password'] = bcrypt($input['provider_id']);
              $user = User::create([
                'f_name' =>$input['f_name'],
                'l_name' => $input['l_name'],
                'user_name'=>$input['f_name'].' '. $input['l_name'], 
                'email'=>$input['email'], 
                'mobile_number'=>$input['mobile_number'], 
                'roll_id'=>3, 
                'is_signed'=>'0', 
                'remember_token'=>$rem_token,
                'password'=>$input['password'],
                'provider' => $input['provider_name'], 
                'provider_id' => $input['provider_id'], 
            ]);
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->user_name;
                $arr=array();
                $arr['subject']='You are register with Officine Top successfully';
                $arr['to']=$user->email;
                $arr['toName']=$user->user_name;
                $arr['from']='xyz@gmail.com';
                $arr['fromName']='Officine Top';
                $arr['attach']= array();
                $dataTemp['template'] ='
                <h1>Hi '.$user->user_name.',</h1>
                <p>Congrates!,You are register with us.</p>
                <p>Before login ,<a target="__blank" href="'.Config("app.url").'api/verifyEmail/'.$rem_token.'" >Click here</a> to verify your email </p>';
                $mailResp=json_decode($this->sent_email($arr,$dataTemp));
               return $this->respFormat(1,'',$success,null);
          }
          else
          {
             
            if(strlen($input['email'])>0)
            {
              // if(Auth::attempt(['email' => $input['email'], 'password' => $input['provider_id']]))
               $userExists = USER::where('email',$input['email'])->count(); 
//return $this->respFormat(1,'',$input['email'],null);
              if($userExists>0)
              { 
// return $this->respFormat(1,'testee',null,null);
                $user = USER::where('email',$input['email'])->first(); 
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                return $this->respFormat(1,'',$success,null);
              }else{ 
                return $this->respFormat(0,'Incorrect email or provider Id',null,null);
               
              } 
            }
            elseif(strlen($input['mobile_number'])>0){
              // if(Auth::attempt(['mobile_number' => $input['mobile_number'], 'password' => $input['provider_id']]))

              $userExists = USER::where('mobile_number',$input['mobile_number'])->count(); 
//return $this->respFormat(1,'',$input['email'],null);
              if($userExists>0)
              { 
                $user = USER::where('mobile_number',$input['mobile_number'])->first(); 
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
               return $this->respFormat(1,'',$success,null);
              }else{ 
                return $this->respFormat(0,'Incorrect mobile number or provider Id',null,null);
               
              } 
            }
          }
        }
        
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user()->with('userdetails')->where('id',Auth::id())->first(); 
        return $this->respFormat(1,'',$user,null);
    } 

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->respFormat(1,'You are logout successfully',null,null);
    }

    public function resetPassword(Request $request) { 
        $validator = Validator::make($request->all(), [  
            'email' => 'required|email',
        ]);
        if($validator->fails()) { 
            return $this->respFormat(0,$validator->errors()->first(),null,null);
        }
        $emailExists = User::where('email',$request->email)->first();
       /*  echo "<pre>";
        print_r($emailExists);exit; */
        if($emailExists != NULL){
          $link_url = Config("app.url").'api/resetLink/'.$emailExists->remember_token;
          $mail_data = ['linkurl'=>$link_url];
          Notification::send($emailExists  , new ResetPassword($mail_data));
          return $this->respFormat(0,'Reset password mail send in your mail , please check your mail !!!', null , null);
        }
        else {
          return $this->respFormat(0,'Email-id is not registered !!!',null,null);
        }
    } 

    public function changePassword(Request $request) 
    { 
        $input=$request->all();
        $validator = Validator::make($request->all(),[  
            'new_password' => 'required',
            'old_password' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            $error=$validator->errors();
            return $this->respFormat(0,$error->first(),null,null);
                       
        }
        else
        {
          $user = Auth::user();
          //$input['old_password']=bcrypt($input['old_password']);
          $hasher = app('hash');
          $exists=$hasher->check($input['old_password'], $user->password);
           // return $this->respFormat(1,$exists,$user->password,$input['old_password']);
          if($exists)
            { 
              $password=$input['new_password'];
              $enc_password = bcrypt($password);
              User::where('email',$user->email)->update(array('password'=>$enc_password));
           

              $arr=array();
               //$arr['emailType']='html';
              $arr['subject']='Officine top: Password changed successfully';
              $arr['to']=$user->email;
              $arr['toName']=$user->user_name;
              $arr['attach']=array();

              $arr['from']='xyz@gmail.com';
              $arr['fromName']='Officine Top';

              $dataTemp['template']='
              <h1>Hi '.$user->user_name.',</h1>
              <p>Your new password is '.$password.'.</p>';

              $mailResp=json_decode($this->sent_email($arr,$dataTemp));

              if($mailResp->responseStatus==false)
              {   
                return $this->respFormat(0,'Server problem .Please try again',null,null);
              }
              return $this->respFormat(1,'Password changed successfully.Please check your mail',null,null);
            }else{
              return $this->respFormat(0,'Old password doesnot match.',null,null);
            
            }

            
        }
    }

    public function sent_email($arr = array() ,$dataTemp) {
      $resp=array();
      $resp["responseStatus"]=true;
      $resp["responseMessage"] = "Mail sent successfully";
      $resp["dataset"] = array();
      if(count($arr) == 0){
         $resp["responseStatus"]=false;
         $resp["responseMessage"]="Please fill full detail";
         $resp["dataset"]=array();
         return json_encode($resp); 
      }
      Mail::send('mail',$dataTemp, function($message) use ($arr) {
              $message->to($arr['to'], $arr['toName'])->subject
              ($arr['subject']);
              foreach($arr['attach'] as $key=>$filePath){ 
                $message->attach($filePath);}
                $message->from($arr['from'],$arr['fromName']);
      });
      return json_encode($resp);  
    }

    public function respFormat($stcode,$msg,$data,$data_set) {
      $resp=array();
      $resp['status_code']=$stcode;
      $resp['message']=$msg;
      $resp['data']=$data;
      $resp['data_set']=$data_set;
      return response()->json($resp, $this->successStatus); 
    }

    public function verifyEmail($token) {
        if(User::where('remember_token',$token)->count() == 0){
          return '<!DOCTYPE html><html><body>You are not authorised user</body></html>';
        }
        User::where('remember_token',$token)->update(array('email_verified_at'=>date('Y-m-d H:i:s')));
        return '<!DOCTYPE html><html><body>Congrates! your email is verified.Please login with us.</body></html>';
    }

    public function resetLink($token , $error=''){
      $user = User::where([['remember_token' , '=' , $token]])->first();
      if($user != NULL){
        $link = Config("app.url");
          $html='<!DOCTYPE html>
                    <html>
                    <body>
                    <h2>Reset Password</h2>
                    <form action="'. $link.'/api/resetPasswordByLink" method="POST">
                      New Password:<br>
                      <input type="hidden" name="remember_token" value="'.$user->remember_token.'">
                      <input  type="password" name="password" value="" placeholder="Password" id="password" required pattern=".{4,10}">
                      <br><br>
                      <input  type="password" name="confirm_password" value="" placeholder="Confirm Password" id="c_password" required pattern=".{4,10}">
                      <br><br>
                      <input type="hidden" name="token" value="" >
                      <input type="submit" value="Reset Password" id="reset">
                    </form> 
                    <p style="color:red">'.$error.'</p>
                    </body>
                  </html>';
          return $html;         
      }
      else{
         echo "Something , went wrong please try again ";exit;
      }
    }
    

    public function resetPasswordByLink(Request $request) {
      $validator = Validator::make($request->all(), [  'remember_token' => 'required',]);
        if($validator->fails()) { 
            return $this->respFormat(0,$validator->errors()->first(),null,null);
        }
        if($request->password == $request->confirm_password){
          $enc_password = Hash::make($request->password);
         // $enc_password = bcrypt($request->password);
          $user = User::where('remember_token', $request->remember_token)->first();
          if($user != NULL){
             $user->password = $enc_password;
             if($user->save()){
               echo "Password reset successfully  !!!";exit;
             }
             else{
              echo "Something went wrong , please try again !!!";exit;
             }
          }
          else{ 
            echo "Something went wrong , please try again , user not exists in our database !!!";exit;
          }
        }
        else{
           echo "password does not matched !!!";exit;
        }
      }


 public function register(Request $request){ 
      $referral_code = NULL;
       /*find users referal */
       if(!empty($request->referral_code)){
        $referal_code_status =  DB::table('users')->where([['own_referal_code','=' , $request->referral_code]])->exists();
        if(!$referal_code_status){
          return $this->respFormat(0,'Referal code is not valid !!!',null , null);
        }
        $referral_code =  $request->referral_code;
       } 
        $req = $request->all();
        if($req['is_social'] == 0){
          $validator = Validator::make($request->all(), [
              'f_name' => 'required', 
              'mobile_number' => 'required|numeric| numeric', 
              'email' => 'required|email', 
              'password' => 'required', 
              'confirm_password' => 'required|same:password',
             // 'device_token_id'=>'required',
          ]);
          if ($validator->fails()) { 
               return $this->respFormat(0,$validator->errors()->first(),null,null);
           }
           
           $input = $request->all(); 
          $input['password'] = bcrypt($input['password']); 
          $emailExists = User::where('email',$input['email'])->count();
          $mobileExists = User::where('mobile_number',$input['mobile_number'])->count();
              if($emailExists==0 && $mobileExists==0){
                $rem_token = md5(time().$input['email'].$input['mobile_number']);
                $response_user = [];
                $transaction_response =  DB::transaction(function() use ($input , $rem_token , $referral_code , $response_user) {
                          $user = User::create([
                                'f_name' =>$input['f_name'],
                                'l_name' => $input['l_name'],
                                'user_name'=>sHelper::slug($input['f_name']).sHelper::slug($input['l_name']), 
                                'email'=>$input['email'], 
                                'mobile_number'=>$input['mobile_number'], 
                                'roll_id'=>3, 
                                'is_signed'=>'0',
                                'remember_token'=>$rem_token,
                                'password'=>$input['password'],
                                'device_token'=>$input['device_token_id'],
                                'referel_code'=>$referral_code,
                            ]);
                            $user->own_referal_code =  sHelper::slug($user->f_name).$user->id;
                            if( $user->save() ){
                              /*manage registration user wallet*/
                              if($user->own_referal_code != NULL){
                                 $amount = 0;
                                  if(!empty($referral_code)){ 
                                     $amount_arr = DB::table('master_bonus_amounts')->first();
                                     if($amount_arr != NULL){
                                       $amount = $amount_arr->for_registration;
                                     }  
                                  }
                                  apiHelper::manage_registration_time_wallet($user , $amount);
                              }
                              /*End*/
                            }
                  $response_user['token'] =  $user->createToken('MyApp')->accessToken; 
                  $response_user['name'] =  $user->user_name;
                   $mail_data = ['link'=>$rem_token];
                   Notification::send($user  , new AppSignup($mail_data));
                    $response_user['success'] = 200;
                   return $response_user; 
                  });
                  if(count($transaction_response) > 0){
                    if($transaction_response['success'] == 200){
                      return $this->respFormat(1, 'Verification link sent on your registered mail.Verify the same to activate your account.',$transaction_response,null);
                    }
                    else{
                      return $this->respFormat(1, 'Something went wrong , please try again !!!.',null,null);
                    }
                  }
                  else{
                    return $this->respFormat(1, 'Something went wrong , please try again !!!.',null,null);
                  }
                }
              elseif($emailExists>0){
                return $this->respFormat(0,'Email already exists',null,null); 
              }
              elseif($mobileExists>0){
                return $this->respFormat(0,'Mobile number already exists',null,null);
              }
        }
        else{
          $input = $request->all(); 
          $validator = Validator::make($request->all(), [  
              'provider_name' =>'required', 
              'provider_id'   =>'required', 
          ]);
          if($validator->fails()) { 
            $error=$validator->errors();
            return $this->respFormat(0,$error->first(),null,null);      
          }

          if(strlen($input['email'])>0 && strlen($input['mobile_number'])>0) {
            $validator = Validator::make($request->all(), [  
              'email'=>'required|email',
              'mobile_number'=>'required | numeric'
            ]);
            if($validator->fails()) { 
              $error=$validator->errors();
              return $this->respFormat(0,$error->first(),null,null);      
            }
          }
          if(strlen($input['email'])>0) {
            $validator = Validator::make($request->all(), [  
              'email'=>'required|email'
            ]);
            if($validator->fails()) { 
               $error = $validator->errors();
               return $this->respFormat(0,$error->first(),null,null);      
            }
            $user = User::where('email',$input['email'])->first();
          }
          elseif(strlen($input['mobile_number'])>0){
            $validator = Validator::make($request->all(), [  
              'mobile_number'=>'required | numeric'
            ]);
            if($validator->fails()) { 
              $error=$validator->errors();
              return $this->respFormat(0,$error->first(),null,null);      
            }
            $user = User::where('mobile_number',$input['mobile_number'])->first();
          }
          $exists = (array)$user;
         //return $this->respFormat(0,count($exists),null,null);  
          if(count($exists)==0){
              $rem_token = md5(time().$input['email'].$input['mobile_number']);
              $input['password'] = bcrypt($input['provider_id']);
              DB::transaction(function() use ($input , $rem_token , $referral_code) {
                    $user = User::create([
                      'f_name' =>$input['f_name'],
                      'l_name' => $input['l_name'],
                      'user_name'=>$input['f_name'].' '. $input['l_name'], 
                      'email'=>$input['email'], 
                      'mobile_number'=>$input['mobile_number'], 
                      'roll_id'=>3, 
                      'is_signed'=>'0', 
                      'remember_token'=>$rem_token,
                      'password'=>$input['password'],
                      'provider' => $input['provider_name'], 
                      'provider_id' => $input['provider_id'], 
                      'device_token'=>$input['device_token_id'],
                      'referel_code'=>$referral_code,
                  ]);
                  $user->own_referal_code =  sHelper::slug($user->f_name).$user->id;
                  $user->save();
                /*manage registration user wallet*/
                  if($user->own_referal_code != NULL){
                      $amount = 0;
                       if(!empty($referral_code)){ 
                          $amount_arr = DB::table('master_bonus_amounts')->first();
                          if($amount_arr != NULL){
                            $amount = $amount_arr->for_registration;
                          }  
                       }
                       apiHelper::manage_registration_time_wallet($user , $amount);
                  }
                  Social_logins::addSocial($input,$user->id);
                  $success['token'] =  $user->createToken('MyApp')->accessToken; 
                  $success['name'] =  $user->user_name;
                  $success['user_id'] = $user->id;
                  return $this->respFormat(1,'',$success,null);
              });
          }
          else{
            $is_user = User::where([['deleted_at', '=', NULL], ['id', '=', $user->id]])->first();
            if($is_user != NULL) {
                $resp = Social_logins::getSocial($input,$user->id);
                if($resp){                 
                    $success['token'] =  $user->createToken('MyApp')->accessToken; 
                    $success['user_id'] =$user->id;
                    return $this->respFormat(1,'',$success,null);
                } else { 
                    return $this->respFormat(0,'Incorrect email/mobile number or provider Id',null,null);
                   
                } 
            } else {
                return $this->respFormat(0,'Your Account is deleted !!!',null,null);
            } 
            
          }
        }
        
    }

}