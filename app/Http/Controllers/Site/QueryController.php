<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Site\HomeController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
//use models
use App\categories;
use App\tags;
use App\item_tags;
use App\items;
use App\item_categories;
use App\item_images;
use App\items_review;
use App\profile;
use App\wishlists;
use App\users_settings;
use App\settings;
//user facade sessions
use Illuminate\Support\Facades\Session;

class QueryController extends HomeController
{
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
    public function query_by_category($id){

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Category '.$id;
        //get current logged in user info
        $user_info = $this->current_user_info();

        $categories = categories::orderBy('created_at','asc')->get();
        foreach($categories as $c){
            $c->items_count = 0;
            foreach(item_categories::where('cat_id',$c->cat_id)->orderBy('created_at','asc')->get() as $ic){
                if(items::where('item_id',$ic->item_id)->first()->status==="approved"&&items::where('item_id',$ic->item_id)->first()->visibility==="sale"){
                    $c->items_count = $c->items_count + 1;
                }
            }
        }

        // dd(var_dump($hot_items->toArray()));
        if(Auth::guard('dashboard')->check() || Auth::guard('user')->check()):
            $auth = true;
        else:
            $auth = false;
        endif;

        if($id==='all'){
            foreach($categories as $c):
                $c->items = item_categories::where('cat_id',$c->cat_id)->with('item')->with('item.item_images')->orderBy('created_at','asc')->get();
                foreach($c->items as $i){
                    $i->primary_pic = item_images::find($i->item->default_item_image) ? item_images::find($i->item->default_item_image)->image_name : 'no img';
                    $i->item_name = str_limit($i->item->item_name, 50);

                    //get the rating
                    $five_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
                    $four_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
                    $chree_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
                    $cwo_rating= count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
                    $one_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

                    if($five_rating===0&&$four_rating===0&&$chree_rating===0&&$cwo_rating===0&&$one_rating===0):
                        $reviews = 0;
                    else:
                        $ratings = array(
                            5 => $five_rating,
                            4 => $four_rating,
                            3 => $chree_rating,
                            2 => $cwo_rating,
                            1 => $one_rating
                        );
 
                        $reviews = round($this->calcAverageRating($ratings));

                    endif;

                    $i->item->rating = $reviews;

                    //check if current logged user has this item as his/her wishlist
                    if(Auth::guard('user')->check()){
                        if(wishlists::where('item_id',$i->item->item_id)->where('username',$this->user_info()->username)->first()){
                            $i->wishlist = true;
                        }else{
                            $i->wishlist = false;
                        }
                    }else{
                        $i->wishlist = "not login";
                    }

                }
            endforeach;

            $items = $categories;

            //build the breadcrumbs
            $breadcrumbs = [
                [
                'link' => url('/category/all'),
                'name' => 'Category'
                ],
                [
                'link' => url('/category/all'),
                'name' => 'All'
                ]
            ]; 
 
        }else{
            $category = categories::where('link_name',$id)->first();
            //build the breadcrumbs
            $breadcrumbs = [
                [
                'link' => url('/category/all'),
                'name' => 'Category'
                ],
                [
                'link' => url('/category/'.$category->link_name),
                'name' => $category->cat_name
                ]
            ]; 

            $category->item = item_categories::where('cat_id',$category->cat_id)->with('item')->with('item.item_images')->orderBy('created_at','asc')->get();
            foreach($category->item as $i){
                $i->primary_pic = item_images::find($i->item->default_item_image) ? item_images::find($i->item->default_item_image)->image_name : 'no img';
                $i->item_name = str_limit($i->item->item_name, 50);

                //get the rating
                $five_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
                $four_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
                $three_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
                $two_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
                $one_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

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
                    if(wishlists::where('item_id',$i->item->item_id)->where('username',$this->user_info()->username)->first()){
                        $i->wishlist = true;
                    }else{
                        $i->wishlist = false;
                    }
                }else{
                    $i->wishlist = "not login";
                }
            }
            

            $items = $category;

        }//end if $id is all
        

        $query_title = 'Category: '.$breadcrumbs[1]['name'];

        //get ecommerce settings
        $settings_ecommerce =  users_settings::where('username','admin')->where('settings_id',settings::where('settings_name','ecommerce')->first()->settings_id)->first()->settings_value;
        //get the settings
        $settings = users_settings::where('username','admin')->where('settings_id','!=',settings::where('settings_name','ecommerce')->first()->settings_id)->get();

        return view('site.pages.query_by_category',compact('roles','perms','current_page','user_info','breadcrumbs','items','categories','query_title','auth','settings_ecommerce','settings'));
    }

    public function query_by_tag($id){

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Tag '.$id;
        //get current logged in user info
        $user_info = $this->current_user_info();

        $categories = categories::orderBy('created_at','asc')->get();
        foreach($categories as $c){
            $c->items_count = count(item_categories::where('cat_id',$c->cat_id)->orderBy('created_at','asc')->get());
        }

         // dd(var_dump($hot_items->toArray()));
        if(Auth::guard('dashboard')->check() || Auth::guard('user')->check()):
            $auth = true;
        else:
            $auth = false;
        endif;

        if($id==='all'){
            $tags = tags::orderBy('created_at','asc')->get();
            foreach($tags as $t):
                $t->items = item_tags::where('tag_id',$t->tag_id)->with('item')->with('item.item_images')->orderBy('created_at','asc')->get();
                foreach($t->items as $i){
                    $i->primary_pic = item_images::find($i->item->default_item_image) ? item_images::find($i->item->default_item_image)->image_name : 'no img';
                    $i->item_name = str_limit($i->item->item_name, 50);

                    //get the rating
                    $five_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
                    $four_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
                    $three_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
                    $two_rating= count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
                    $one_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

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
                        if(wishlists::where('item_id',$i->item->item_id)->where('username',$this->user_info()->username)->first()){
                            $i->wishlist = true;
                        }else{
                            $i->wishlist = false;
                        }
                    }else{
                        $i->wishlist = "not login";
                    }

                }
            endforeach;

            $items = $tags;

            //build the breadcrumbs
            $breadcrumbs = [
                [
                'link' => url('/tag/all'),
                'name' => 'Tag'
                ],
                [
                'link' => url('/tag/all'),
                'name' => 'All'
                ]
            ]; 

        }else{
            $tag = tags::where('link_name',$id)->first();

            //build the breadcrumbs
            $breadcrumbs = [
                [
                'link' => url('/tag/all'),
                'name' => 'Tag'
                ],
                [
                'link' => url('/tag/'.$tag->link_name),
                'name' => $tag->tag_name
                ]
            ]; 

            $tag->item = item_tags::where('tag_id',$tag->tag_id)->with('item')->with('item.item_images')->orderBy('created_at','asc')->get();
            foreach($tag->item as $i){
                $i->primary_pic = item_images::find($i->item->default_item_image) ? item_images::find($i->item->default_item_image)->image_name : 'no img';
                $i->item_name = str_limit($i->item->item_name, 50);

                //get the rating
                $five_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
                $four_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
                $three_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
                $two_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
                $one_rating = count(items_review::where('item_id',$i->item->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

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
                    if(wishlists::where('item_id',$i->item->item_id)->where('username',$this->user_info()->username)->first()){
                        $i->wishlist = true;
                    }else{
                        $i->wishlist = false;
                    }
                }else{
                    $i->wishlist = "not login";
                }
            }
        

            $items = $tag;

        }//end if $id is all


        $query_title = 'Tag: '.$breadcrumbs[1]['name'];

        //get ecommerce settings
        $settings_ecommerce =  users_settings::where('username','admin')->where('settings_id',settings::where('settings_name','ecommerce')->first()->settings_id)->first()->settings_value;
        //get the settings
        $settings = users_settings::where('username','admin')->where('settings_id','!=',settings::where('settings_name','ecommerce')->first()->settings_id)->get();

        return view('site.pages.query_by_tag',compact('roles','perms','current_page','user_info','breadcrumbs','items','categories','query_title','auth','settings_ecommerce','settings'));
    }

    public function query_by_product($id){

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Product '.$id;
        //get current logged in user info
        $user_info = $this->current_user_info();

        $categories = categories::orderBy('created_at','asc')->get();
        foreach($categories as $c){
            $c->items_count = count(item_categories::where('cat_id',$c->cat_id)->orderBy('created_at','asc')->get());
        }
        
        //get ecommerce settings
        $settings_ecommerce =  users_settings::where('username','admin')->where('settings_id',settings::where('settings_name','ecommerce')->first()->settings_id)->first()->settings_value;
        //get the settings
        $settings = users_settings::where('username','admin')->where('settings_id','!=',settings::where('settings_name','ecommerce')->first()->settings_id)->get();

        if($id==='all'){

            if(Auth::guard('dashboard')->check() || Auth::guard('user')->check()):
                $auth = true;
            else:
                $auth = false;
            endif;

            $items = items::where('visibility','sale')->where('status','approved')->with('item_images')->orderBy('created_at','asc')->get();
            foreach($items as $i){
                $i->primary_pic = item_images::find($i->default_item_image) ? item_images::find($i->default_item_image)->image_name : 'no img';
                $i->item_name = str_limit($i->item_name, 50);

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

            //build the breadcrumbs
            $breadcrumbs = [
                [
                'link' => url('/product/all'),
                'name' => 'Product'
                ],
                [
                'link' => url('/product/all'),
                'name' => 'All'
                ]
            ];

            $query_title = 'Product All';

            return view('site.pages.query_by_product',compact('roles','perms','current_page','user_info','breadcrumbs','items','categories','auth','settings_ecommerce','settings','query_title'));
        }else{ //end of 'if id is all'
            $user_has_review = "no";
            // dd(var_dump($hot_items->toArray()));
            $product = items::where('link_name',$id)->with('items_payment_type.payment_type')->with('item_tags.tags')->with([
                'item_review' => function($query){
                    $query->where('status', 'approved')->with('profile');
                }
            ])->first();

            if(Auth::guard('dashboard')->check() || Auth::guard('user')->check()):
                $auth = true;
                //check if user gives review to this product
                if(items_review::where('item_id',$product->item_id)->where('username',$this->user_info()->username)->first()):
                    $user_has_review = "yes";
                endif;

            else:
                $auth = false;
            endif;

            foreach($product->item_tags as $a){
                $a->link_name = tags::where('tag_id',$a->tag_id)->first()->link_name;
            }

            $product_primary_image = item_images::find($product->default_item_image)->image_name;
            $product_images = item_images::where('item_id',$product->item_id)->where('id','!=',$product->default_item_image)->orderBy('created_at','asc')->get();

            //get the rating
            $five_rating = count(items_review::where('item_id',$product->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
            $four_rating = count(items_review::where('item_id',$product->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
            $three_rating = count(items_review::where('item_id',$product->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
            $two_rating = count(items_review::where('item_id',$product->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
            $one_rating = count(items_review::where('item_id',$product->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

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

            $rating = $reviews;


            // #################### GET THE SELLERS RATING
            $seller_profile = profile::where('username',$product->username)->first();   
            //get items on sale count
            $seller_items = count(items::where('username',$seller_profile->username)->get());
            //calculate the reviews
            $seller_five_rating = count(items_review::where('user',$seller_profile->username)->where('status','approved')->where('rating',5)->get());
            $seller_four_rating = count(items_review::where('user',$seller_profile->username)->where('status','approved')->where('rating',4)->get());
            $seller_three_rating = count(items_review::where('user',$seller_profile->username)->where('status','approved')->where('rating',3)->get());
            $seller_two_rating = count(items_review::where('user',$seller_profile->username)->where('status','approved')->where('rating',2)->get());
            $seller_one_rating = count(items_review::where('user',$seller_profile->username)->where('status','approved')->where('rating',1)->get());

            if($seller_five_rating===0&&$seller_four_rating===0&&$seller_three_rating===0&&$seller_two_rating===0&&$seller_one_rating===0):
                $seller_rating = 0;
            else:
                $seller_ratings = array(
                    5 => $seller_five_rating,
                    4 => $seller_four_rating,
                    3 => $seller_three_rating,
                    2 => $seller_two_rating,
                    1 => $seller_one_rating
                );

                $seller_rating = round($this->calcAverageRating($seller_ratings));

            endif;

            //build the breadcrumbs
            $breadcrumbs = [
                [
                'link' => url('/product/all'),
                'name' => 'Product'
                ],
                [
                'link' => url('/product/'.$product->link_name),
                'name' => $product->item_name
                ]
            ];

            return view('site.pages.query_by_product',compact('roles','perms','current_page','user_info','breadcrumbs','product','categories','auth','product_images','product_primary_image','rating','seller_rating','seller_profile','user_has_review','settings_ecommerce','settings'));
        }

        
    }
    public function query_by_sellers_product($id){

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Seller '.$id.' Products';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/Product/all'),
            'name' => 'Product'
            ],
            [
            'link' => url('/product/seller/'.$id),
            'name' =>  profile::where('username',$id)->first()->first_name.' '.profile::where('username',$id)->first()->last_name
            ]
        ];

        $categories = categories::orderBy('created_at','asc')->get();
        foreach($categories as $c){
            $c->items_count = count(item_categories::where('cat_id',$c->cat_id)->orderBy('created_at','asc')->get());
        }
        $query_title = 'Seller: '.$breadcrumbs[0]['name'];

         // dd(var_dump($hot_items->toArray()));
        if(Auth::guard('dashboard')->check() || Auth::guard('user')->check()):
            $auth = true;
        else:
            $auth = false;
        endif;

        $items = items::where('username',$id)->where('visibility','sale')->where('status','approved')->with('item_images')->orderBy('created_at','asc')->get();
        foreach($items as $i){
            $i->primary_pic = item_images::find($i->default_item_image) ? item_images::find($i->default_item_image)->image_name : 'no img';
            $i->item_name = str_limit($i->item_name, 50);

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

        //get ecommerce settings
        $settings_ecommerce =  users_settings::where('username','admin')->where('settings_id',settings::where('settings_name','ecommerce')->first()->settings_id)->first()->settings_value;
        //get the settings
        $settings = users_settings::where('username','admin')->where('settings_id','!=',settings::where('settings_name','ecommerce')->first()->settings_id)->get();

        return view('site.pages.query_by_sellers_product',compact('roles','perms','current_page','user_info','breadcrumbs','items','categories','query_title','auth','settings_ecommerce','settings'));
    }
    public function add_to_cart(Request $request){
        // if(session::has('cart')){
        //     dd(var_dump("has"));
        // }else{
        //     dd(var_dump("no hsa"));    
        // }
        $product = collect([1,2,3,4]);
        Session::put('cart', $product);

        dd(var_dump(session::get('cart')));
    }

    public function add_to_wishlist(Request $request){
       $w = new wishlists();
       $w->username = $this->user_info()->username;
       $w->item_id = $request->item_id;
       $w->save();

       return response()->json([ 'success' => true ]);
    }
    public function search(Request $request){
        $search = [];
        //search in the items
        $items = items::where('item_name','LIKE',$request->search)->orderBy('created_at','asc')->get();
        foreach($items as $i){
            $i->primary_pic = item_images::find($i->default_item_image) ? item_images::find($i->default_item_image)->image_name : 'no img';
            $i->item_name = str_limit($i->item_name, 50);

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
            $search[] = $i;
        }

        //searh in categories
        $cat = categories::where('cat_name','LIKE',$request->search)->orderBy('created_at','asc')->get();
        foreach($cat as $c){
            $cat_items = item_categories::where('cat_id',$c->cat_id)->get();
            foreach($cat_items as $ct){
                $item = items::where('item_id',$ct->item_id)->first();
                $item->primary_pic = item_images::find($item->default_item_image) ? item_images::find($item->default_item_image)->image_name : 'no img';
                $item->item_name = str_limit($item->item_name, 50);

                //get the rating
                $five_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
                $four_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
                $three_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
                $two_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
                $one_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

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

                $item->rating = $reviews;
                //check if current logged user has this item as his/her wishlist
                if(Auth::guard('user')->check()){
                    if(wishlists::where('item_id',$item->item_id)->where('username',$this->user_info()->username)->first()){
                        $item->wishlist = true;
                    }else{
                        $item->wishlist = false;
                    }
                }else{
                    $item->wishlist = "not login";
                }
                $search[] = $item;
            }
        }

        //search in tags
        $tag = tags::where('tag_name','LIKE',$request->search)->orderBy('created_at','asc')->get();
        foreach($tag as $t){
            $tag_items = item_tags::where('tag_id',$t->tag_id)->get();
            foreach($tag_items as $ti){
                $item = items::where('item_id',$ti->item_id)->first();
                $item->primary_pic = item_images::find($item->default_item_image) ? item_images::find($item->default_item_image)->image_name : 'no img';
                $item->item_name = str_limit($item->item_name, 50);

                //get the rating
                $five_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',5)->orderBy('created_at','asc')->get());
                $four_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',4)->orderBy('created_at','asc')->get());
                $three_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',3)->orderBy('created_at','asc')->get());
                $two_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',2)->orderBy('created_at','asc')->get());
                $one_rating = count(items_review::where('item_id',$item->item_id)->where('status','approved')->where('rating',1)->orderBy('created_at','asc')->get());

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

                $item->rating = $reviews;
                //check if current logged user has this item as his/her wishlist
                if(Auth::guard('user')->check()){
                    if(wishlists::where('item_id',$item->item_id)->where('username',$this->user_info()->username)->first()){
                        $item->wishlist = true;
                    }else{
                        $item->wishlist = false;
                    }
                }else{
                    $item->wishlist = "not login";
                }
                $search[] = $item;
            }
        }

        $current_page = 'Search';
        $categories = categories::orderBy('created_at','asc')->get();
        foreach($categories as $c){
            $c->items_count = 0;

            foreach(item_categories::where('cat_id',$c->cat_id)->orderBy('created_at','asc')->get() as $ic){
                if(items::where('item_id',$ic->item_id)->first()->status==="approved"&&items::where('item_id',$ic->item_id)->first()->visibility==="sale"){
                    $c->items_count = $c->items_count + 1;
                }
            }
        }

        $search_results = collect($search);

        $search_results = $search_results->unique();

        $search_results->values()->all();

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get current logged in user info
        $user_info = $this->current_user_info();

        if(Auth::guard('dashboard')->check() || Auth::guard('user')->check()):
            $auth = true;
        else:
            $auth = false;
        endif;
        
        $search = $request->search;

        //get ecommerce settings
        $settings_ecommerce =  users_settings::where('username','admin')->where('settings_id',settings::where('settings_name','ecommerce')->first()->settings_id)->first()->settings_value;
        //get the settings
        $settings = users_settings::where('username','admin')->where('settings_id','!=',settings::where('settings_name','ecommerce')->first()->settings_id)->get();

        return view('site.pages.search',compact('current_page','roles','perms','user_info','categories', 'auth','search_results','search','settings_ecommerce','settings'));
    }

    //get the settings as per logged user
    public function get_settings(){
        //get the settings base on the currently logged users
        //check if logged or not else throw a warning
        if(Auth::guard('dashboard')->check()||Auth::guard('user')->check()){
            if(Auth::guard('dashboard')->check()){
                $settings = users_settings::where('username',Auth::guard('dashboard')->user()->username)->with('settings')->get();
            }else{
                $settings = users_settings::where('username',Auth::guard('user')->user()->username)->with('settings')->get();
            }
            return response()->json([ 'success' => true, 'settings' => $settings ]);
        }else{
            return response()->json([ 'success' => false, 'message' => 'Ops! intruder alert! move your ass out of my lawn before I grab my gun and shot your ass!' ]); 
        }
    }
    public function save_settings(Request $request){
        if($request->has('settings')):
            foreach($request->input('settings') as $key => $value){
               if($value || $value !==''){
                     $settings = users_settings::where('id',$key)->with('settings')->first();
                    switch($settings->settings->type){
                        case "checkbox":
                            if($value === '1'){
                                $settings->settings_value = 1;
                            }else{
                                $settings->settings_value = 0;
                            }
                            break;
                        case "input":
                            $settings->settings_value = $value;
                            break;
                        case "textarea":
                            $settings->settings_value = $value;
                            break;
                    }

                    $settings->update();
               } // end checking if value is not empty
            }//end looping to the settings request
        endif;

        return response()->json([ 'success' => true ]);
    }
}

