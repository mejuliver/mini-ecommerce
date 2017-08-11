<?php

namespace App\Http\Middleware;

use Closure;

//use the Auth Facades
use Illuminate\Support\Facades\Auth;

//use profile controller
use App\permissions_id;
use App\profile;

class CheckBuyerPerm
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

        if(auth::guard('dashboard')->check()||auth::guard('user')->check()):
            //check what guard we are on
            if(auth::guard('dashboard')->check()){
                $username = auth::guard('dashboard')->user()->username;
            }elseif(auth::guard('user')->check()){
                $username = auth::guard('user')->user()->username;
            }
            //check what role the current user have
            $perms = permissions_id::where('username',$username)->with('permission')->get();

            $perms_array = [];

            foreach($perms as $r):
                array_push($perms_array,$r->permission->perm_name);
            endforeach;

            //role must be equals to either admin or inventory
            if(count(array_intersect($perms_array, ['can_buy'])) > 0):
                if(profile::where('username',$username)->first()->status!=='pending'):
                    //proceed to reporting
                    return $next($request);
                else:
                    return redirect('/error/69');
                endif;

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
                return redirect('/error/69');
            }

        endif;

        
    }
}
