<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
class Is_admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(Auth::user()->roll_id != 4){
           return redirect('/logout');
		  }
		 else  return $next($request);
    }
}
