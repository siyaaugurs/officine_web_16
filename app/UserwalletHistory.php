<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserwalletHistory extends Model{

    protected  $table = "userwallet_histories";
    protected $fillable = ['id', 'user_id', 'title', 'description' , 'amount' , 'deleted_at','created_at' , 'updated_at'];
    
    public static function get_customers_wallet_histories($p1){
		return \App\UserwalletHistory::where('user_id' , $p1)->get();
	}
    
}
