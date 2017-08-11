<?php

namespace App\Http\Middleware;

use Closure;

//use the Auth Facades
use Illuminate\Support\Facades\Auth;

//use profile controller
use App\roles_id;

class CheckAdminRole
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

        if(auth::guard('dashboard')->check()):
            //check what role the current user have
            $roles = roles_id::where('username',auth::guard('dashboard')->user()->username)->with('role')->get();

            $roles_array = [];

            foreach($roles as $r):
                array_push($roles_array,$r->role->role_name);
            endforeach;

            //role must be equals to either admin or inventory
            if(count(array_intersect($roles_array, ['admin'])) > 0):
                //proceed to reporting
                return $next($request);
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
                return redirect('/dashboard/login');
            }

        endif;

        
    }
}
