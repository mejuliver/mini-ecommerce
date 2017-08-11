<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\permissions_id;
use App\roles_id;

class SetViewVariables
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {;

        //pass the permission
        $perms = permissions_id::where('username',auth()->user()->username)->with('permission')->get();
        $roles = roles_id::where('username',auth()->user()->username)->with('role')->get();

        $perms_array = [];
        $roles_array = [];

        foreach($perms as $p):
            array_push($p->permission->perm_name,$perms_array);
        endforeach;

        foreach($roles as $r):
            array_push($r->role->role_name,$roles_array);
        endforeach;

        view()->share('perms', $perms_array);
        view()->share('roles', $roles_array);

        return $next($request);
    }

}