<?php
namespace App\Http\Middleware;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware{
    
    protected function redirectTo($request){
		//echo "<pre>";
		//print_r($request->all());exit;
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
