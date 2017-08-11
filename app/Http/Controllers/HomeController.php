<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use View;

//use models
use App\profile;
use App\roles_id;
use App\role;
use App\permissions_id;
use App\permission;
use App\notifications;
use App\items;
use App\orders;
use App\cart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('dashboard');

        // View::share('user_info', auth()->check() ? profile::where('username', auth()->user()->username)->first() : null);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    function current_user_info(){
        // return profile::where('username',Auth::user()->username)->with(array('roles_id.role' => function($query){
        //     $query->addSelect(array('role_name'));
        // }))->first();
        return profile::where('username',auth::guard('dashboard')->user()->username)->with('user')->with('roles_id.role')->with('permissions_id.permission')->with('tags')->first();
    }

    function perms(){

        //pass the permission
        $perms = permissions_id::where('username',auth::guard('dashboard')->user()->username)->with('permission')->get();

        $perms_array = [];

        foreach($perms as $p):
            array_push($perms_array,$p->permission->perm_name);
        endforeach;

        return $perms_array;
    }

    function roles(){
        $roles = roles_id::where('username',auth::guard('dashboard')->user()->username)->with('role')->get();

        $roles_array = [];

        foreach($roles as $r):
            array_push($roles_array,$r->role->role_name);
        endforeach;

        return $roles_array;
    }

    function user_ability_perms($data){
        if(count(array_intersect($this->perms(), $data)) > 0):
            return true;
        else:
            return false;
        endif;
    }
    function user_ability_roles($data){
        if(count(array_intersect($this->roles(), $data)) > 0):
            return true;
        else:
            return false;
        endif;
    }
    function notifications(){
        if(Auth::guard('dashboard')->check()):
            return notifications::where('sender','!=',$this->current_user_info()->username)->where('username',$this->current_user_info()->username)->get();
        else:
            return false;
        endif;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Home';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();
        if($this->user_ability_roles(['admin'])){
            //get sale items count
            $sale_items_count = count(items::all());
            //get users with a seller role
            $seller_perm_id = permission::where('perm_name','can_sell')->first();
            $sellers_count = count(permissions_id::where('perm_id',$seller_perm_id->perm_id)->get());
            //get users with a buyer role
            $buyer_perm_id = permission::where('perm_name','can_buy')->first();
            $buyers_count = count(permissions_id::where('perm_id',$buyer_perm_id->perm_id)->get());
            //get orders
            $orders_count = count(orders::where('status','pending')->get());
            $admin = true;
            return view('pages.home',compact('current_page','roles','perms','notifications','user_info','sale_items_count','sellers_count','buyers_count','orders_count','admin'));
        }else{
            //get sale items count
            $sale_items_count = count(items::where('username',$this->current_user_info()->username)->get());
            //get orders
            $items = items::where('username',$this->current_user_info()->username)->get();
            $cart_array = [];
            foreach($items as $i){
                foreach(cart::where('item_id',$i->item_id)->get() as $c):
                    array_push($cart_array, $c->order_id);
                endforeach;
            }
            array_unique($cart_array);

            $orders = orders::where('status','pending')->where(function($query) use($cart_array){
                foreach($cart_array as $c){
                    $query->orWhere('order_id',$c);
                }
            })->get();
            
            $orders_count = count($orders);
            $admin = false;
            return view('pages.home',compact('current_page','roles','perms','notifications','user_info','sale_items_count','orders_count','admin'));
        }

    }
}
