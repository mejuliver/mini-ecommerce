<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

//use models
use App\profile;
use App\roles_id;
use App\role;
use App\permissions_id;
use App\permission;
use App\notifications;
use App\categories;
use App\item_categories;
use App\items;
use App\items_review;
use App\item_images;
use App\wishlists;
use App\users_settings;
use App\settings;
//use carbon
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //check if logged in or not
        if(auth::guard('dashboard')->check()){
            $this->middleware('dashboard');
        }elseif(auth::guard('user')->check()){
            $this->middleware('user');
        }

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
        if(auth::guard('dashboard')->check() || auth::guard('user')->check()):
            return profile::where('username',$this->user_info()->username)->with('user')->with('roles_id.role')->with('permissions_id.permission')->with('tags')->first();
        endif;
    }

    function perms(){

        if(auth::guard('dashboard')->check() || auth::guard('user')->check()):
             //pass the permission
            $perms = permissions_id::where('username',$this->user_info()->username)->with('permission')->orderBy('created_at','asc')->get();

            $perms_array = [];

            foreach($perms as $p):
                array_push($perms_array,$p->permission->perm_name);
            endforeach;

            return $perms_array;
        endif;
       
    }

    function roles(){
        if(auth::guard('dashboard')->check() || auth::guard('user')->check()):

            $roles = roles_id::where('username',$this->user_info()->username)->with('role')->orderBy('created_at','asc')->get();

            $roles_array = [];

            foreach($roles as $r):
                array_push($roles_array,$r->role->role_name);
            endforeach;

            return $roles_array;

        endif;
    }
    function calcAverageRating($ratings) {

        $totalWeight = 0;
        $totalReviews = 0;

        foreach ($ratings as $weight => $numberofReviews) {
            $WeightMultipliedByNumber = $weight * $numberofReviews;
            $totalWeight += $WeightMultipliedByNumber;
            $totalReviews += $numberofReviews;
        }

        //divide the total weight by total number of reviews
        $averageRating = $totalWeight / $totalReviews;

        return $averageRating;
    }

    public function index()
    {
        
        $current_page = 'Home';
        $categories = categories::orderBy('created_at','asc')->get();
        foreach($categories as $c){
            $c->items_count = 0;
            foreach(item_categories::where('cat_id',$c->cat_id)->orderBy('created_at','asc')->get() as $ic){
                if(items::where('item_id',$ic->item_id)->first()->status==="approved"&&items::where('item_id',$ic->item_id)->first()->visibility==="sale"){
                    $c->items_count = $c->items_count + 1;
                }
            }
        }
        foreach($categories as $c):
            $c->item = item_categories::where('cat_id',$c->cat_id)->with('item')->with('item.wishlists')->with('item.item_images')->orderBy('created_at','asc')->get();
            foreach($c->item as $i){
                $i->primary_pic = item_images::find($i->item->default_item_image) ? item_images::find($i->item->default_item_image)->image_name : 'no img';
                $i->item_name = str_limit($i->item->item_name, 50);

                //get the rating
                $five_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
                $four_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
                $three_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
                $two_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
                $one_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

                if($five_rating===0&&$four_rating===0&&$three_rating===0&&$two_rating===0&&$one_rating===0):
                    $reviews = 0;
                else:
                    $ratings = array(
                        5 => $five_rating,
                        4 => $four_rating,
                        3 => $three_rating,
                        2 => $two_rating,
                        1 => $one_rating
                    );

                    $reviews = round($this->calcAverageRating($ratings));

                endif;

                $i->rating = $reviews;

                //check if current logged user has this item as his/her wishlist
                if(Auth::guard('user')->check()){
                    if(wishlists::where('item_id',$i->item_id)->where('username',$this->user_info()->username)->first()){
                        $i->wishlist = true;
                    }else{
                        $i->wishlist = false;
                    }
                }else{
                    $i->wishlist = "not login";
                }


            }
        endforeach;

        //get the new items
        $new_items = items::where(function($query){
            $query->where('visibility','sale')
                ->where('status','approved');
        })->where('created_at', '>=', Carbon::now()->subMonth())->orderBy('created_at','asc')->get();
        foreach($new_items as $n):
            $n->primary_pic = item_images::find($n->default_item_image) ? item_images::find($n->default_item_image)->image_name : 'no img';
            $n->item_name = str_limit($n->item_name, 50);

            //check if current logged user has this item as his/her wishlist
            if(Auth::guard('user')->check()){
                if(wishlists::where('item_id',$n->item_id)->where('username',$this->user_info()->username)->first()){
                    $n->wishlist = true;
                }else{
                    $n->wishlist = false;
                }
            }else{
                $n->wishlist = "not login";
            }

            $five_rating = count(items_review::where('item_id',$n->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
            $four_rating = count(items_review::where('item_id',$n->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
            $three_rating = count(items_review::where('item_id',$n->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
            $two_rating = count(items_review::where('item_id',$n->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
            $one_rating = count(items_review::where('item_id',$n->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

            if($five_rating===0&&$four_rating===0&&$three_rating===0&&$two_rating===0&&$one_rating===0):
                $reviews = 0;
            else:
                $ratings = array(
                    5 => $five_rating,
                    4 => $four_rating,
                    3 => $three_rating,
                    2 => $two_rating,
                    1 => $one_rating
                );

                $reviews = round($this->calcAverageRating($ratings));

            endif;

            $n->rating = $reviews;

        endforeach;
        //get the hot items
        $items = items::where(function($query){
            $query->where('visibility','sale')
                ->where('status','approved');
        })->orderBy('created_at','asc')->get();

        $hot_items_array = [];

        foreach($items as $i){
            $five_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
            $four_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
            $three_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
            $two_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
            $one_rating = count(items_review::where('item_id',$i->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

            if($five_rating===0&&$four_rating===0&&$three_rating===0&&$two_rating===0&&$one_rating===0):
                $reviews = 0;
            else:
                $ratings = array(
                    5 => $five_rating,
                    4 => $four_rating,
                    3 => $three_rating,
                    2 => $two_rating,
                    1 => $one_rating
                );

                $reviews = round($this->calcAverageRating($ratings));

            endif;

            if($reviews>=4){
                array_push($hot_items_array, $i->item_id);
            };

        }

        if(count($hot_items_array)!==0){
             $hot_items = items::where(function($query) use($hot_items_array){
                foreach($hot_items_array as $h){
                    $query->orWhere('item_id',$h);
                }
            })->orderBy('created_at','asc')->get();

            foreach ($hot_items as $h) {
                $h->primary_pic = item_images::find($h->default_item_image) ? item_images::find($h->default_item_image)->image_name : 'no img';
                $h->item_name = str_limit($h->item_name, 50);

                $five_rating = count(items_review::where('item_id',$h->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
                $four_rating = count(items_review::where('item_id',$h->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
                $three_rating = count(items_review::where('item_id',$h->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
                $two_rating = count(items_review::where('item_id',$h->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
                $one_rating = count(items_review::where('item_id',$h->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

                //check if current logged user has this item as his/her wishlist
                if(Auth::guard('user')->check()){
                    if(wishlists::where('item_id',$h->item_id)->where('username',$this->user_info()->username)->first()){
                        $h->wishlist = true;
                    }else{
                        $h->wishlist = false;
                    }
                }else{
                    $h->wishlist = "not login";
                }


                if($five_rating===0&&$four_rating===0&&$three_rating===0&&$two_rating===0&&$one_rating===0):
                    $reviews = 0;
                else:
                    $ratings = array(
                        5 => $five_rating,
                        4 => $four_rating,
                        3 => $three_rating,
                        2 => $two_rating,
                        1 => $one_rating
                    );

                    $reviews = round($this->calcAverageRating($ratings));

                endif;

                $h->rating = $reviews;
             }

        }else{
            $hot_items = [];
        }
        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get current logged in user info
        $user_info = $this->current_user_info();
        if(Auth::guard('dashboard')->check() || Auth::guard('user')->check()):
            $auth = true;
        else:
            $auth = false;
        endif;
        
        //get ecommerce settings
        $settings_ecommerce =  users_settings::where('username','admin')->where('settings_id',settings::where('settings_name','ecommerce')->first()->settings_id)->first()->settings_value;
        //get the settings
        $settings = users_settings::where('username','admin')->where('settings_id','!=',settings::where('settings_name','ecommerce')->first()->settings_id)->get();

        return view('site.pages.home',compact('current_page','roles','perms','user_info','categories', 'auth','hot_items','new_items','settings_ecommerce','settings'));
    }
}
