<?php
namespace App\Http\Middleware;
use Closure;
use Auth;

class Is_seller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	 
    public function handle($request, Closure $next){
        //return Auth::user();
		if(Auth::user()->roll_id == 1 || Auth::user()->roll_id == 4){
		     return $next($request); 
          }
         else 
		return redirect('/logout');
    }
}
