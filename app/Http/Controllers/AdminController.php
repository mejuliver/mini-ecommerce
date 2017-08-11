<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\Auth;
 
//validation facades
use Illuminate\Support\Facades\Validator;

//storage and file facades
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;    
//validation facades

//use models
use App\profile;
use App\User;
use App\orders;
use App\cart;
use App\items;
use App\items_review;
use App\tags;
use App\categories;
use App\item_categories;
use App\item_images;

use App\roles_id;
use App\role;
use App\notifications;

//use facades redis
use Illuminate\Support\Facades\Redis;

class AdminController extends HomeController
{
    public function users_page()
    {

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Admin';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //get users
        $users = profile::all();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/admin/users'),
            'name' => 'Users'   
            ]
        ];

        return view('pages.admin.users',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','users'));
    }
    //activate account
    public function activate_account(Request $request){
        //set status to active
        $p = profile::where('username',$request->id)->first();
        $p->status = 'active';
        $p->update();

        //save to notification table
        $not = new notifications();
        $not->username = $request->id;
        $not->sender = 'system';
        $not->contents = 'Your account has been activated, please reload the page';
        $not->save();

        //send notification
        $data = [
            'event' => 'notification',
            'contents' => 'Your account has been activated, please reload the page',
            'to' => [$request->id],
            'type' => 'notification',
            'sender' => 'system',
        ];
 
        Redis::publish('notifications',json_encode($data));

        return response()->json([ 'success' => true, 'id' => $request->id ]);
    }
    //reject account
    public function reject_account(Request $request){
        //check if account exist
        if(User::where('username',$request->id)->first()){
            //delete account
            User::where('username',$request->id)->first()->delete();

            return response()->json([ 'success' => true, 'id' => $request->id ]);
        }else{
            return response()->json([ 'success' => false, 'message' => 'Account does not exist anymore']);
        }


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
    //view account
    public function view_account($id)
    {

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Admin';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //get users data
        $users_profile = profile::where('username',$id)->with('user')->with('roles_id.role')->with('permissions_id.permission')->with('tags')->first();
        //get items on sale count
        $items = count(items::where('username',$id)->get());
        //calculate the reviews
        $five_rating = count(items_review::where('user',$id)->where('status','approved')->where('rating',5)->get());
        $four_rating = count(items_review::where('user',$id)->where('status','approved')->where('rating',4)->get());
        $three_rating = count(items_review::where('user',$id)->where('status','approved')->where('rating',3)->get());
        $two_rating = count(items_review::where('user',$id)->where('status','approved')->where('rating',2)->get());
        $one_rating = count(items_review::where('user',$id)->where('status','approved')->where('rating',1)->get());

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

        //get the tags
        $tags = tags::where('username',$id)->get();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/admin/users'),
            'name' => 'Users'   
            ],
            [
            'link' => url('/app/system/admin/users/'.$id),
            'name' => $id   
            ]
        ];

        return view('pages.admin.view_user',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','users_profile','items','reviews','tags'));
    }

    public function sale_items()
    {

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Admin';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //get sale items
        $items = items::with('item_images')->with('item_review')->orderBy('created_at','asc')->orderBy('updated_at','asc')->take(200)->get();

        foreach($items as $i){
            $cart_array = [];
            foreach(cart::where('item_id',$i->item_id)->get() as $c):
                array_push($cart_array, $c->order_id);
            endforeach;
            array_unique($cart_array);

            $i->orders = count($cart_array);

            $i->order_name = str_limit($i->order_name, 50);
            $i->format_date = date('M d, Y h:m A',strtotime($i->created_at));
        }


        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/admin/sale-items'),
            'name' => 'Sale Items'
            ]
        ];

        return view('pages.admin.sale_items',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','items'));
    }
    //sale items item view page
    public function sale_items_item_view($id){

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Admin';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //get all categories
        $categories = categories::where(function($query) use($id){
            foreach(item_categories::where('item_id',$id)->get() as $i):
                $query->orWhere('cat_id','!=',$i->cat_id);
            endforeach;
        })->get();


        //get default image
        $default_item_image = item_images::find(items::where('item_id',$id)->first()->default_item_image);

        //get sale items
        $item = items::where('item_id',$id)->with([
            'item_images' => function($query) use($default_item_image){
                $query->where('id','!=',$default_item_image->id);
            }
        ])->with('item_review')->with('item_tags')->with('item_categories.category')->with('items_payment_type.payment_type')->orderBy('created_at','asc')->orderBy('updated_at','asc')->first();

        
        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/admin/sale-items'),
            'name' => 'Sale Items'   
            ],
            [
            'link' => url('/app/system/admin/sale-items/item/'.$id),
            'name' => str_limit($item->item_name, 20)
            ]
        ];

        return view('pages.admin.sale_items_item_view',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','item','categories','default_item_image'));
    }
    //sale items date filter
    public function sale_items_date_filter(Request $request){
        $date_from= date_create($request->from);
        $from = date_format($date_from,"Y-m-d H:i:s");
        //to
        $date_to= date_create($request->to);
        $to = date_format($date_to,"Y-m-d H:i:s");

        //retrieved the request table records
        $items = items::whereBetween('created_at', [$from, $to])->select('item_id','username','item_name','price','created_at')->with('item_images')->with('item_review')->with('order')->orderBy('created_at','asc')->orderBy('updated_at','asc')->get();

        foreach($items as $i):
            $i->item_name = str_limit($i->item_name, 50);
            $i->format_date = date('M d, Y h:m A',strtotime($i->created_at));
        endforeach;

        return response()->json([ 'success' => true, 'items' => $items ]);

    }
    //reload sale items
    public function reload_sale_items(){
        //get sale items
        $items = items::with('item_images')->with('item_review')->orderBy('created_at','asc')->orderBy('updated_at','asc')->take(200)->get();

        foreach($items as $i){
            $cart_array = [];
            foreach(cart::where('item_id',$i->item_id)->get() as $c):
                array_push($cart_array, $c->order_id);
            endforeach;
            array_unique($cart_array);

            $i->orders = count($cart_array);

            $i->order_name = str_limit($i->order_name, 50);
            $i->format_date = date('M d, Y h:m A',strtotime($i->created_at));
        }

        return response()->json([ 'success' => true, 'items' => $items ]);
    }
    //view orders
    public function view_orders(Request $request){
        //get the total orders
        $orders = orders::where('id',$request->id)->with('cart')->with('profile')->with('payment_type')->get();
        foreach($orders as $o){
            $o->format_date = date('M d, Y h:m A',strtotime($o->created_at));
        }
        $total_orders = count($orders);
        $pending_orders = count(orders::where('id',$request->id)->where('status','pending')->get());
        $in_process_orders = count(orders::where('id',$request->id)->where('status','processing')->get());
        $completed_orders = count(orders::where('id',$request->id)->where('status','completed')->get());
        $error_orders = count(orders::where('id',$request->id)->where('status','error')->get());

        return response()->json([ 'success' => true,'orders' => $orders, 'total_orders' => $total_orders, 'pending_orders' => $pending_orders, 'in_process_orders' => $in_process_orders, 'completed_orders' => $completed_orders, 'error_orders' => $error_orders ]);
    }
    //categories
    public function categories()
    {

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Admin';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();


        //get categories
        $categories = categories::all();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/admin/categories'),
            'name' => 'Categories'
            ]
        ];

        return view('pages.admin.categories',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','categories'));
    }
    //add category
    public function add_category(Request $request){
        $error = array();
        $validator = Validator::make($request->all(),[
            'cat_name' => 'required|max:500|unique:categories',
        ]);
        if($validator->fails()){
            foreach($validator->errors()->getMessages() as $validationErrors):
                if (is_array($validationErrors)) {
                    foreach($validationErrors as $validationError):
                        $error[] = $validationError;
                    endforeach;
                } else {
                    $error[] = $validationErrors;
                }
            endforeach;
        }//end if validation fails

        if(count($error)===0){
            $link_name = preg_replace('/[^0-9a-zA-Z\s]/', '', strtolower($request->cat_name));
            $link_name = str_replace([' ','_'],'-', $link_name);
            $link_name = str_replace('&','and', $link_name);

            $c = new categories();
            $c->link_name = $link_name;
            $c->cat_name = $request->cat_name;
            $c->cat_desc = $request->cat_desc;
            $c->save();

            return response()->json([ 'success' => true, 'cat_id' => $c->cat_id, 'cat_name' => $c->cat_name, 'cat_desc' => $c->cat_desc ]);
        }else{
            $str = '<ul class="c-red padding-zero margin-zero menu">';
            foreach($error as $e ){
                $str.= '<li>'.$e.'</li>';
            }
            $str.='</ul>';

            return response()->json([ 'success' => false,  'message' => $str ]);
        }
    }
    public function delete_category(Request $request){
        //delete first all the tag associated with the requested tag
        foreach(item_categories::where('cat_id',$request->cat_id)->get() as $t):
            $t->delete();
        endforeach;
        categories::where('cat_id',$request->cat_id)->first()->delete();

        return response()->json([ 'success' => true, 'cat_id' => $request->cat_id ]);
    }
    //approve item
    public function approve_item(Request $request){
        $i = items::where('item_id',$request->id)->first();
        $i->status = 'approved';
        $i->update();

        //save to notification table
        $not = new notifications();
        $not->username = $i->username;
        $not->sender = 'system';
        $not->contents = 'Your item named '.$i->item_name.', please reload the page';
        $not->save();

        //send notification
        $data = [
            'event' => 'notification',
            'contents' => 'Your item named '.$i->item_name.', please reload the page',
            'to' => [$i->username],
            'type' => 'notification',
            'sender' => 'system',
        ];
 
        Redis::publish('notifications',json_encode($data));

        return response()->json([ 'success' => true, 'id' => $request->id ]);
    }
    //reject item
    public function reject_item(Request $request){
        items::where('item_id',$request->id)->first()->delete();

        return response()->json([ 'success' => true, 'id' => $request->id ]);
    }
    public function approve_new_img(Request $request){
        $p = profile::where('username',$request->id)->first();
        //dd(var_dump($this->current_user_info()->username.'/profile/temp_img/'.$p->temp_img.'x'.$this->current_user_info()->username.'/profile/'.$p->temp_img));
        //clear the profile folder of the user (not included the temo_img folder)
        foreach(Storage::files($p->username.'/profile') as $d){
            Storage::delete($d);
        }
        Storage::move($p->username.'/profile/temp_img/'.$p->temp_img, $p->username.'/profile/'.$p->temp_img);

        //update the user's data
        $p->img = $p->temp_img;
        $p->temp_img = '';
        $p->update();

        //save to notification table
        $not = new notifications();
        $not->username = $request->id;
        $not->sender = 'system';
        $not->contents = 'Your profile picture has been approved, please reload the page';
        $not->save();

        //send notification
        $data = [
            'event' => 'notification',
            'contents' => 'Your profile picture has been approved, please reload the page',
            'to' => [$request->id],
            'type' => 'notification',
            'sender' => 'system',
        ];
 
        Redis::publish('notifications',json_encode($data));

        return response()->json([ 'success' => true, 'id' => $request->id, 'img' => $p->img, 'name' => $p->first_name.' '.$p->last_name ]);
    }
    public function reject_new_img(Request $request){
        $p = profile::where('username',$request->id)->first();
        foreach(Storage::files($p->username.'/profile/temp_img') as $d){
            Storage::delete($d);
        }
        $p->temp_img = '';
        $p->update();

        //save to notification table
        $not = new notifications();
        $not->username = $request->id;
        $not->sender = 'system';
        $not->contents = 'Your profile picture has been rejected, please reload the page';
        $not->save();

        //send notification
        $data = [
            'event' => 'notification',
            'contents' => 'Your profile picture has been rejected, please reload the page',
            'to' => [$request->id],
            'type' => 'notification',
            'sender' => 'system',
        ];
 
        Redis::publish('notifications',json_encode($data));

        return response()->json([ 'success' => true, 'id' => $request->id, 'img' => $p->img, 'name' => $p->first_name.' '.$p->last_name ]);
    }
}
