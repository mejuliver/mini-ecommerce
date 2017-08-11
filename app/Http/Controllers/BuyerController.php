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
use App\wishlists;
use App\items_review;

class BuyerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('user');

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
        return profile::where('username',auth::guard('user')->user()->username)->with('user')->with('roles_id.role')->with('permissions_id.permission')->with('tags')->first();
    }

    function perms(){

        //pass the permission
        $perms = permissions_id::where('username',auth::guard('user')->user()->username)->with('permission')->get();

        $perms_array = [];

        foreach($perms as $p):
            array_push($perms_array,$p->permission->perm_name);
        endforeach;

        return $perms_array;
    }

    function roles(){
        $roles = roles_id::where('username',auth::guard('user')->user()->username)->with('role')->get();

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
        if(Auth::check()):
            return notifications::where('username','!=',$this->current_user_info()->username)->where(function($query){
                $query->where('to_who',$this->current_user_info()->username)
                        ->orWhere(function($q){
                                foreach($this->current_user_info()->roles_id as $r):
                                    $q->orWhere('to_who',$r->role->role_name);
                                endforeach;
                          })
                      ->orWhere('to_who','all');
            })->get();

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

        //get user orders
        $orders = orders::where('status','pending')->where('username',$this->current_user_info()->username)->first();

        $my_orders_count = count($orders);

        $my_wishlists = count($this->current_user_info()->wishlists);
            
        return view('buyer.pages.home',compact('current_page','roles','perms','notifications','user_info','my_wishlists','my_orders_count'));

    }
    public function add_review(Request $request){
        //check if has already added a review
        if(items_review::where('item_id',$request->id)->where('username',$this->current_user_info()->username)->first()):
           return response()->json([ 'success' => false, 'message' => 'You already submitted an review for this item.' ]);
        else:
            $ir = new items_review();
            $ir->item_id = $request->id;
            $ir->username = $this->current_user_info()->username;
            $ir->user = items::where('item_id',$request->id)->first()->username;
            $ir->rating = $request->rating;
            $ir->review_title = $request->review_title;
            $ir->review_contents = $request->review_contents;
            $ir->save();

            //retrieve the new added review
            $review = items_review::where('id',$ir->id)->with('profile')->first();

            return response()->json([ 'success' => true, 'ir' => $review ]);
        endif;
    }
}
