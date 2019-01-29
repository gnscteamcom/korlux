<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
    
        //*********************************************
        //Route validation for specific Role
        //(c) Team2one
        //(t) March 2016
        //*********************************************
        
        if($request->user()->id != 1){
            if($request->user()->is_admin == $role){
                return $next($request);
            }
            else{
                return redirect('login');
            }
        }

        return $next($request);
        
    }
}
