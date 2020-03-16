<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;



class Common_mobile extends Model{
   
   protected  $table = "common_mobiles";
   protected $fillable = ['id' , 'users_id', 'workshops_id', 'mobile' ,'created_at' , 'updated_at' ];
   
   
   public static function get_mobile($users_id){
      return Common_mobile::where('users_id' , '=' , $users_id)->get();
   }	
     public static function save_profile_contact($request){
        $result = Common_mobile::where('id' ,$request->contact_id)->update(
        ['mobile'=>$request->mobile_no,
        ]);
        return $result;
   }
   public static function add_user_contact($request){
      $result = Common_mobile::create(['users_id' => Auth::user()->id,
      'mobile'=>$request->mobile_no
      ]);
      return $result;



   }
}
