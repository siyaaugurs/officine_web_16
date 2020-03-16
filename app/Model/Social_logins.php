<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Social_logins extends Model
{
    protected $fillable = ['user_id','provider','provider_id'];

	public static function addSocial($input,$user_id)
    {
     	Social_logins::create([
            'user_id'=>$user_id,
            'provider' =>$input['provider_name'],
            'provider_id' => $input['provider_id']
    	]);
    	return true;
	}

	public static function getSocial($input,$user_id)
    {
    	
        
        $matches=array(
                'user_id'=>$user_id,
                'provider' =>$input['provider_name']
            );

        $social_logins=Social_logins::where($matches)->first();

        if(count((array)$social_logins)==0)
        {
         	Social_logins::create([
	            'user_id'=>$user_id,
	            'provider' =>$input['provider_name'],
	            'provider_id' => $input['provider_id']
        	]);
        	return true;
        }elseif($social_logins->provider_id==$input['provider_id']){
            return true;
        }else{
        	return false;
        }  
	}
}
