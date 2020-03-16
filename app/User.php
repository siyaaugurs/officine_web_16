<?php
namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Auth;
use DB;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
      protected $table = "users"; 
      protected $fillable = ['id' , 
        'f_name', 'l_name', 'company_name' , 'user_name' , 'email' , 'provider', 'provider_id',  'email_verified_at' , 'mobile_number' , 'is_signed' , 'roll_id' , 'remember_token' , 'profile_image' , 'users_status' ,'password', 'created_at' , 'updated_at' , 'term_and_condition'  , 'know_us', 'for_news_letter' , 'device_token' , 'referel_code' , 'own_referal_code' ];
     
        public $roll_type = [1=>'Seller' , 2=>"Workshop" , 3=>"Customer" , 4=>"Admin" ];  


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public static function return_admin_id(){
        if(Auth::check()) { 
			$admin_detail =  DB::table('users')->where([['roll_id' , '=' , 4]])->first();
			if($admin_detail != NULL){$uid = $admin_detail->id; }
		}
        else{  $uid = 3; }
        return $uid;
    }


    public static function company_profile_detail($users_id){
        return DB::table('users as u')
               ->leftjoin('business_details as b' , 'b.users_id' , '=' , 'u.id')
               ->select('u.*' , 'b.owner_name' , 'b.business_name' , 'b.address_1' , 'b.address_2' , 'b.address_3' , 'b.registered_office', 'b.about_business' , 'b.postal_code')
               ->where([['u.id' , '=' , $users_id]])
               ->first(); 
        //return User::where([['id' , '=' , $users_id] , ['roll_id' , '=' , $roll_id]])->first();
    }
	
	public static function sign_in($request){
	  $username = $request->name;
	   $result = User::create([
            'f_name' =>$request->name,
            'l_name' => '',
            'company_name'=>$request->company_name,
            'user_name'=>$request->first_name , 
            'email'=>$request->email, 
            'mobile_number'=>$request->mobile , 
            'roll_id'=>$request->roll_type , 
            'user_name'=>$username, 
            'is_signed'=>1, 
            'password'=>Hash::make($request->password),
            'term_and_condition'=>1 , 
            'know_us'=>$request->how_did_you_know ,
            'for_news_letter'=>1
        ]);
		return $result;
	}
    public static function save_profile_data($request,$profile_pic){
		if($profile_pic == NULL)
		{
		$result = User::where('id' ,Auth::user()->id)->update(
        [//'email'=>$request->email,
         //'mobile_number'=>$request->mobile, 
        ]);
		}else{
		 $result = User::where('id' ,Auth::user()->id)->update(
        [//'email'=>$request->email,
         //'mobile_number'=>$request->mobile, 
         'profile_image'=>$profile_pic,
        ]);
		}	 
        return $result;
    }
     public static function save_password($password){
        $result = User::where('id' ,Auth::user()->id)->update(
        ['password'=>Hash::make($password),
        ]);
        return $result;
    }
	public static function get_user_details($request){
	    return User::where('email' , $request->email)->first();
	}

    public function userdetails(){
        return $this->hasMany('App\Model\UserDetails');
    }
    
	public static function edit_profile($data){
	    return User::where('id' ,'=', Auth::user()->id)->update($data);
	}
	

    public static function company_profile_details($users_id){
        return DB::table('users as u')
               ->leftjoin('business_details as b' , 'b.users_id' , '=' , 'u.id')
               ->select('u.*' , 'b.owner_name' , 'b.business_name' , 'b.address_1' , 'b.address_2' , 'b.address_3' , 'b.about_business')
               ->where([['u.id' , '=' , $users_id] , ['users_status' , '!=' , 'B']])
               ->first(); 
        //return User::where([['id' , '=' , $users_id] , ['roll_id' , '=' , $roll_id]])->first();
    }
    

    public static function check_users_type($users_id , $roll_id){
	    return User::where([['id' , '=' , $users_id]])->first();
    }
    
    public static function get_customers_record($p1) {
        return User::where('id' , $p1)->first();
    }
    public static function edit_customer_details($request, $profile_image) {
        $arr = ['f_name' =>$request->user_name,
                'email'=>$request->email, 
                'mobile_number'=>$request->mobile_number, 
                'profile_image'=>$profile_image, 
            ];
        return \App\User::where('id', $request->customer_id)->update($arr);
    }
    public static function get_serach_user($user_id) {
        if(!empty($user_id)) {
            return User::where('id', '=', $user_id)->get();
        }
    }
    
    public static function get_assemble_workshop($workshop_users_arr , $category_id , $days_id = NULL){
	   return \DB::table('users as u')
	           ->join('workshop_user_days as wud' , 'wud.users_id' , '=' , 'u.id')
			   ->join('business_details as b', 'b.users_id', '=', 'u.id')
			   ->select('u.id','u.f_name', 'u.l_name', 'u.profile_image', 'u.mobile_number', 'u.company_name' ,'b.owner_name', 'b.business_name', 'b.registered_office', 'b.about_business')
			   ->where([['common_weekly_days_id' , '=' ,$days_id] , ['wud.deleted_at' , '=' , NULL] , ['u.users_status' , '=' , 'A']]) 
			   ->whereIn('u.id' , $workshop_users_arr)
			   ->get();
	}
	
	public static function get_workshop_details($workshop_id){
	   return \DB::table('users as u')
	           ->join('business_details as b', 'b.users_id', '=', 'u.id')
			   ->select('u.id' , 'u.f_name' , 'u.l_name' , 'u.company_name' , 'u.mobile_number' , 'u.profile_image',
			   'b.owner_name', 'b.business_name', 'b.registered_office', 'b.about_business','b.address_3','b.address_2')
			   ->where([['u.id','=',$workshop_id] , ['u.users_status' , '=' , 'A'] , ['deleted_at' , '=' , NULL]])
			   ->first();
    }
    
	public static function get_workshop_company_details($workshop_id){
       return \DB::table('users as u')
               ->join('business_details as b', 'b.users_id', '=', 'u.id')
               ->select('u.id' , 'u.f_name' , 'u.l_name' , 'u.company_name' , 'u.mobile_number' , 'u.profile_image',
               'b.*')
               ->where([['u.id','=',$workshop_id] ])
               ->first();
    }


    //Get user details
    public static function users_details($remaining_users){
        return \DB::table('users as u')
                ->join('business_details as b', 'b.users_id', '=', 'u.id')
                ->select('u.id','u.f_name', 'u.l_name', 'u.profile_image', 'u.mobile_number', 'u.company_name' ,'b.owner_name', 'b.business_name', 'b.registered_office', 'b.about_business')
                ->whereIn('u.id' , $remaining_users)
                ->get();
     }
     
}
