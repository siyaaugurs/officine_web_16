<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;

class Logout extends Controller{
    
	public function logout(){
	   $user = Auth::user();
	   Session::forget('users_roll_type');
	   Auth::logout($user);
	   return redirect('/login');
	}
}
