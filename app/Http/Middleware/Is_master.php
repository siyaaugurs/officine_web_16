<?php

namespace App\Http\Middleware;

use Closure;

class Is_master
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if(Auth::user()->roll_id != 5){
           return redirect('/logout');
		  }
		 else  return $next($request);
    }
}
