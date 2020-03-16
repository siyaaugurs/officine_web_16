<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class Is_vendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
	    if(Auth::user()->roll_id == 2  || Auth::user()->roll_id == 4){
         return $next($request);
		  }
		 else  return redirect('/logout');

    }
}
