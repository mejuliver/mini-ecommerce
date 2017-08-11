<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
//validation facades
use Illuminate\Support\Facades\Validator;

//use models
use App\profile;
use App\roles_id;
use App\role;
use App\permissions_id;
use App\permission;
use App\notifications;
use App\orders;
use App\items;
use App\cart;

class OrderController extends Controller
{
    public function __construct(){
        //check authentication first
        if(auth::guard('dashboard')->check()):
            $this->middleware('dashboard');
        elseif(auth::guard('user')->check()):
            $this->middleware('user');
        endif;
    }
    function user_info(){
        //check guard
        if(auth::guard('dashboard')->check()):
            //return username
            return auth::guard('dashboard')->user();
        elseif(auth::guard('user')->check()):
            return auth::guard('user')->user();
        endif;
        
    }
    function current_user_info(){
        return profile::where('username',$this->user_info()->username)->with('user')->with('roles_id.role')->with('permissions_id.permission')->with('tags')->first();
    }

    function perms(){
        //pass the permission
        $perms = permissions_id::where('username',$this->user_info()->username)->with('permission')->get();

        $perms_array = [];

        foreach($perms as $p):
            array_push($perms_array,$p->permission->perm_name);
        endforeach;

        return $perms_array;
    }

    function roles(){
        $roles = roles_id::where('username',$this->user_info()->username)->with('role')->get();

        $roles_array = [];

        foreach($roles as $r):
            array_push($roles_array,$r->role->role_name);
        endforeach;

        return $roles_array;
    }


    function notifications(){
        if(Auth::guard('dashboard')->check()):
            return notifications::where('sender','!=',$this->current_user_info()->username)->where('username',$this->current_user_info()->username)->get();
        else:
            return false;
        endif;
    }
    public function index()
    {

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Orders';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //get users
        $users = profile::all();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/admin/orders'),
            'name' => 'Orders'
            ]
        ];

        //get the user's order
        $orders = orders::where('username',$this->user_info()->username)->orderBy('created_at','asc')->get();

        return view('pages.orders',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','orders'));

    }
    //reload orders
    public function reload_orders(){
        //get the user's order
        $orders = orders::where('username',$this->user_info()->username)->orderBy('created_at','asc')->get();

        return response()->json([ 'success' => true, 'orders' => $orders ]);
    }
    //sale orders date filter
    public function orders_date_filter(Request $request){
        $date_from= date_create($request->from);
        $from = date_format($date_from,"Y-m-d H:i:s");
        //to
        $date_to= date_create($request->to);
        $to = date_format($date_to,"Y-m-d H:i:s");

        //retrieved the request table records
        $orders = orders::where('username',$this->user_info()->username)->whereBetween('created_at', [$from, $to])->orderBy('created_at','asc')->get();

        return response()->json([ 'success' => true, 'orders' => $orders ]);

    }
    //view orders items
    public function view_orders_items(Request $request){
        //get the total orders
        $items = cart::where('order_id',$request->id)->with('item')->get();
        foreach($items as $i){
            $i->item_name = $i->item->item_name;
            $i->format_date = date('M d, Y h:m A',strtotime($o->created_at));
        }

        return response()->json([ 'success' => true, 'items' => $items ]);
    }
    //get orders chart
    public function get_orders_chart(Request $request){

        //check role
        if(array_intersect($this->roles(), ['seller'])>0){
            //get orders
            $items = items::where('username',$this->user_info()->username)->get();
            $cart_array = [];
            foreach($items as $i){
                foreach(cart::where('item_id',$i->item_id)->get() as $c):
                    array_push($cart_array, $c->order_id);
                endforeach;
            }
            array_unique($cart_array);

            //get pending orders
            $pending_orders = orders::where('status','pending')->where(function($query) use($cart_array){
                foreach($cart_array as $c):
                    $query->orWhere('order_id',$c);
                endforeach;
            })->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();
            //get in process orders
            $processing_orders = orders::where('status','processing')->where(function($query) use($cart_array){
                foreach($cart_array as $c):
                    $query->orWhere('order_id',$c);
                endforeach;
            })->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();
            //get complete orders
            $completed_orders = orders::where('status','completed')->where(function($query) use($cart_array){
                foreach($cart_array as $c):
                    $query->orWhere('order_id',$c);
                endforeach;
            })->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();
            //get in error orders
            $error_orders = orders::where('status','error')->where(function($query) use($cart_array){
                foreach($cart_array as $c):
                    $query->orWhere('order_id',$c);
                endforeach;
            })->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();

        }else{
            //get pending orders
            $pending_orders = orders::where('status','pending')->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();
            //get in process orders
            $processing_orders = orders::where('status','processing')->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();
            //get complete orders
            $completed_orders = orders::where('status','completed')->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();
            //get in error orders
            $error_orders = orders::where('status','error')->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();
        }
        return response()->json([ 'success' => true, 'pending_orders' => $pending_orders, 'processing_orders' => $processing_orders, 'completed_orders' => $completed_orders, 'error_orders' => $error_orders ]);
    }
    //orders chat date filter
    public function get_orders_chart_date_filter(Request $request){
        $date_from= date_create($request->from);
        $from = date_format($date_from,"Y-m-d H:i:s");
        //to
        $date_to= date_create($request->to);
        $to = date_format($date_to,"Y-m-d H:i:s");

        //check role
        if(array_intersect($this->roles(), ['seller'])>0){
            //get orders
            $items = items::where('username',$this->user_info()->username)->get();
            $cart_array = [];
            foreach($items as $i){
                foreach(cart::where('item_id',$i->item_id)->get() as $c):
                    array_push($cart_array, $c->order_id);
                endforeach;
            }
            array_unique($cart_array);

            //get pending orders
            $pending_orders = orders::where('status','pending')->where(function($query) use($cart_array){
                foreach($cart_array as $c):
                    $query->orWhere('order_id',$c);
                endforeach;
            })->whereBetween('created_at', [$from, $to])->get();
            //get in process orders
            $processing_orders = orders::where('status','processing')->where(function($query) use($cart_array){
                foreach($cart_array as $c):
                    $query->orWhere('order_id',$c);
                endforeach;
            })->whereBetween('created_at', [$from, $to])->get();
            //get complete orders
            $completed_orders = orders::where('status','completed')->where(function($query) use($cart_array){
                foreach($cart_array as $c):
                    $query->orWhere('order_id',$c);
                endforeach;
            })->whereBetween('created_at', [$from, $to])->get();
            //get in error orders
            $error_orders = orders::where('status','error')->where(function($query) use($cart_array){
                foreach($cart_array as $c):
                    $query->orWhere('order_id',$c);
                endforeach;
            })->whereBetween('created_at', [$from, $to])->get();


        }else{
        //get pending orders
            $pending_orders = orders::where('status','pending')->whereBetween('created_at', [$from, $to])->get();
            //get in process orders
            $processing_orders = orders::where('status','processing')->whereBetween('created_at', [$from, $to])->get();
            //get complete orders
            $completed_orders = orders::where('status','completed')->whereBetween('created_at', [$from, $to])->get();
            //get in error orders
            $error_orders = orders::where('status','error')->whereBetween('created_at', [$from, $to])->get();
        }

        
        return response()->json([ 'success' => true, 'pending_orders' => $pending_orders, 'processing_orders' => $processing_orders, 'completed_orders' => $completed_orders, 'error_orders' => $error_orders ]);

    }
}
