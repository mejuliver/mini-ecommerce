<?php

namespace App\Http\Middleware;

use Closure;

//use the Auth Facades
use Illuminate\Support\Facades\Auth;

//use profile controller
use App\roles_id;
use App\users_settings;
use App\settings;

class CheckBuyerRole
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

        if(auth::guard('user')->check()):
            //check what role the current user have
            $roles = roles_id::where('username',auth::guard('user')->user()->username)->with('role')->get();

            $roles_array = [];

            foreach($roles as $r):
                array_push($roles_array,$r->role->role_name);
            endforeach;

            //role must be equals to either admin or inventory
            if(count(array_intersect($roles_array, ['buyer'])) > 0):
                //proceed to reporting
                if(users_settings::where('username','admin')->where('settings_id',settings::where('settings_name','ecommerce')->first()->settings_id)->first()->settings_value==='1'){
                    
                    return $next($request); 
                }else{
                    
                    return redirect('/');
                   
                }
                
            else :
                //redirect to error 404 page
                if($request->ajax()){
                    return response()->json([ 'success' => false, 'message' => 'Error: 69, you are a dangerous intruder']);
                }else{
                    return redirect('/error/404');
                }

            endif;

        else:
            if($request->ajax()){
                return response()->json([ 'success' => false, 'message' => 'Error: 69, you are a dangerous intruder']);
            }else{
                //redirect to login
                return redirect('/user/login');
            }

        endif;

        
    }
}
