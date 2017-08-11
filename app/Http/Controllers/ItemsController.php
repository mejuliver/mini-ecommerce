<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//storage and file facades
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;    
//validation facades
use Illuminate\Support\Facades\Validator;

//use models
use App\items;
use App\items_review;
use App\tags;
use App\item_tags;
use App\item_categories;
use App\categories;
use App\item_images;
use App\payment_types;
use App\items_payment_type;

use App\roles_id;
use App\role;
use App\notifications;

//use facades redis
use Illuminate\Support\Facades\Redis;

class ItemsController extends HomeController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    //sales item page
    public function index()
    {

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Sale Items';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //get sale items
        $items = items::where('username',auth::guard('dashboard')->user()->username)->select('item_id','username','item_name','price','quantity','created_at')->with('item_images')->with('item_review')->orderBy('created_at','asc')->orderBy('updated_at','asc')->take(200)->get();

        foreach($items as $i):
            $i->item_name = str_limit($i->item_name, 50);
        endforeach;

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/sale-items'),
            'name' => 'Sale Items'   
            ]
        ];

        return view('pages.sale_items',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','items'));
    }
    //create sale item
    public function create_sale_item(){
        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Sale Items';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/sale-items'),
            'name' => 'Sale Items'   
            ],
            [
            'link' => url('/app/system/sale-items/item/create'),
            'name' => 'Create Item'
            ]
        ];
        //get the payment types
        $payment_types = payment_types::all();

        //get categories
        $categories = categories::orderBy('created_at','asc')->get();

        return view('pages.sale_items_create_item',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','categories','payment_types'));
    }
    //add sale item
    public function add_sale_item(Request $request){
        $error = array();
        $validator = Validator::make($request->all(),[
            'item_name' => 'required|max:500|unique:items',
            'status' => 'required',
            'price' => 'required',
            'product_picture' => 'image|required|image_size:115-1000,115-10000',
            'category' => 'required',
            'payment_type' => 'required',
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

            $link_name = preg_replace('/[^0-9a-zA-Z\s]/', '', strtolower($request->item_name));
            $link_name = str_replace([' ','_'],'-', $link_name);
            $link_name = str_replace('&','and', $link_name);

            $item = new items();
            $item->username = $this->current_user_info()->username;
            $item->link_name = $link_name;
            $item->item_name = $request->item_name;
            $item->item_desc = str_replace(['<hr>','<hr />','<hr/>'], '', $request->item_desc);
            $item->details = str_replace(['<hr>','<hr />','<hr/>'], '', $request->details);
            if($request->allow_reviews){
                $item->allow_reviews = 'yes';
            }else{
                $item->allow_reviews = 'no';
            }
            $item->visibility = $request->status;
            $item->price = $request->price;
            $item->discounted = $request->discounted_price!=='0.00'?$request->discounted_price:'';
            $item->quantity = $request->quantity;
            $item->save();

            //add category
            foreach($request->category as $c):
                $cat = new item_categories();
                $cat->cat_id = $c;
                $cat->item_id = $item->item_id;
                $cat->save();                
            endforeach;
            if($request->tag):
                //add tags
                foreach($request->tag as $t):
                    $tag = new item_tags();
                    $tag->tag_id = $c;
                    $tag->item_id = $item->item_id;
                    $tag->save();                
                endforeach;
            endif;
            //add item payment type
            $p = new items_payment_type();
            $p->payment_id = $request->payment_type;
            $p->item_id = $item->item_id;
            $p->save();                
            

            //create directory
            Storage::makeDirectory('items/'.$this->current_user_info()->username.'/'.$item->item_id);

            //add the primary image
            $file = $request->file('product_picture');

            $extension = $file->getClientOriginalExtension();
            $file_name = $file->getClientOriginalName();

            //store the image
            Storage::put($this->current_user_info()->username.'/items/'.$item->item_id.'/'.$file_name, File::get($file));

            $img = new item_images();
            $img->image_name = $file_name;
            $img->image_type = $extension;
            $img->item_id = $item->item_id;
            $img->username = $this->current_user_info()->username;
            $img->save();

            //set the uploaded image as the primary product image
            $i  = items::where('item_id',$item->item_id)->first();
            $i->default_item_image = $img->id;
            $i->update();

            //get the username to those who have an admin username
            $admins = [];
            foreach(roles_id::where('role_id',role::where('role_name','admin')->first()->role_id)->get() as $a){

                array_push($admins, $a->username);

                //save to notification table
                $not = new notifications();
                $not->username= $a->username;
                $not->sender = 'system';
                $not->contents = '<b>'.$this->current_user_info()->first_name.' '.$this->current_user_info()->last_name.'</b> has created new item';
                $not->save();
            }

            //send notification
            $data = [
                'event' => 'notification',
                'contents' => '<b>'.$this->current_user_info()->first_name.' '.$this->current_user_info()->last_name.'</b> has created new item',
                'to' => $admins,
                'type' => 'notification',
                'sender' => 'system',
            ];
     
            Redis::publish('notifications',json_encode($data));

            return response()->json([ 'success' => true, 'link' => url('/app/system/sale-items/item/'.$item->item_id) ]);

        }else{
            $str = '<ul class="c-red padding-zero margin-zero menu">';
            foreach($error as $e ){
                $str.= '<li>'.$e.'</li>';
            }
            $str.='</ul>';

            return response()->json([ 'success' => false,  'message' => $str ]);
        }

        // return Redirect::back()->withErrors(['msg', 'The Message']);

        // @if($errors->any())
        // <h4>{{$errors->first()}}</h4>
        // @endif

    }

    //sale items item view page
    public function sale_item($id){

        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Sale Items';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //get default image
        $default_item_image = item_images::find(items::where('item_id',$id)->first()->default_item_image);

        //get sale items
        $item = items::where('item_id',$id)->with([
            'item_images' => function($query) use($default_item_image){
                $query->where('id','!=',$default_item_image->id);
            }
        ])->with('item_review')->with('item_tags')->with('item_categories.category')->with('items_payment_type.payment_type')->orderBy('created_at','asc')->orderBy('updated_at','asc')->first();

        $item_id = $item->item_id;
        //get all categories
        $categories = categories::where(function($query) use($item_id){
            foreach(item_categories::where('item_id',$item_id)->get() as $i):
                $query->where('cat_id','!=',$i->cat_id);
            endforeach;
        })->get();


        //get the payment types
        $payment_types = payment_types::all();
        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/sale-items'),
            'name' => 'Sale Items'   
            ],
            [
            'link' => url('/app/system/sale-items/item/'.$item->link_name),
            'name' => str_limit($item->item_name, 20)
            ]
        ];

        return view('pages.sale_items_item_view',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','item','categories','default_item_image','payment_types'));
    }
    //update item
    public function update_item(Request $request){
        $error = array();
        $validator = Validator::make($request->all(),[
            'item_name' => 'required|max:500',
            'status' => 'required',
            'price' => 'required',
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
        $i = items::where('item_id',$request->id)->first();
        //check if the the item name has been touch
        if($i->item_name!==$request->item_name){
            //check if item name already exist
             $validator = Validator::make($request->all(),[
                'item_name' => 'unique:items',
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
        }

        if(count($error)===0){
            $link_name = preg_replace('/[^0-9a-zA-Z\s]/', '', strtolower($request->item_name));
            $link_name = str_replace([' ','_'],'-', $link_name);
            $link_name = str_replace('&','and', $link_name);

            if($i->item_name!==$request->item_name){
                $i->link_name = $link_name;
                $i->item_name = $request->item_name;
            }
            $i->item_desc = str_replace(['<hr>','<hr />','<hr/>'], '', $request->item_desc);
            $i->details = str_replace(['<hr>','<hr />','<hr/>'], '', $request->details);
            if($request->allow_reviews){
                $i->allow_reviews = 'yes';
            }else{
                $i->allow_reviews = 'no';
            }
            $i->visibility = $request->status;
            $i->price = $request->price;
            $i->discounted = $request->discounted_price!=='0.00'?$request->discounted_price:'';
            $i->quantity = $request->quantity;
            $i->update();

            //update item payment type
            $p = items_payment_type::where('item_id',$request->id)->where('payment_id',$request->payment_type)->first();
            $p->payment_id = $request->payment_type;
            $p->update();  

            return response()->json([ 'success' => true ]);

        }else{
            $str = '<ul class="c-red padding-zero margin-zero menu">';
            foreach($error as $e ){
                $str.= '<li>'.$e.'</li>';
            }
            $str.='</ul>';

            return response()->json([ 'success' => false,  'message' => $str ]);
        }
    
    }
    //delete sale item
    public function delete_sale_item(Request $request){
        items::where('item_id',$request->item_id)->first()->delete();
        //remove the item directory
        Storage::deleteDirectory($this->current_user_info()->username.'/items/'.$request->item_id);

        return redirect('/app/system/sale-items/')->with('message', 'Item deleted successfully!');
    }
    //approve review
    public function approve_review(Request $request){
        $i = items_review::find($request->id);
        $i->status = 'approved';
        $i->update();

        return response()->json([ 'success' => true, 'id' => $request->id, 'reviews_count' => count(items_review::all()) ]);
    }
    //disapprove review
    public function disapprove_review(Request $request){
        items_review::find($request->id)->delete();

        return response()->json([ 'success' => true, 'id' => $request->id, 'reviews_count' => count(items_review::all()) ]);
    }
    //reload sale items
    public function reload_sale_items(){
        //get sale items
        $items = items::where('username',auth::guard('dashboard')->user()->username)->select('item_id','username','item_name','price','quantity','created_at')->with('item_images')->with('item_review')->orderBy('created_at','asc')->orderBy('updated_at','asc')->take(200)->get();

        foreach($items as $i):
            $i->item_name = str_limit($i->item_name, 50);
            $i->format_date = date('M d, Y h:m A',strtotime($i->created_at));
        endforeach;

        return response()->json([ 'success' => true, 'items' => $items ]);
    }
    //sale items date filter
    public function sale_items_date_filter(Request $request){
        $date_from= date_create($request->from);
        $from = date_format($date_from,"Y-m-d H:i:s");
        //to
        $date_to= date_create($request->to);
        $to = date_format($date_to,"Y-m-d H:i:s");

        //retrieved the request table records
        $items = items::where('username',auth::guard('dashboard')->user()->username)->whereBetween('created_at', [$from, $to])->select('item_id','username','item_name','price','quantity','created_at')->with('item_images')->with('item_review')->orderBy('created_at','asc')->orderBy('updated_at','asc')->get();

        foreach($items as $i):
            $i->item_name = str_limit($i->item_name, 50);
            $i->format_date = date('M d, Y h:m A',strtotime($i->created_at));
        endforeach;

        return response()->json([ 'success' => true, 'items' => $items ]);

    }
    //tag page
    public function tag_page(Request $request){
        $roles = $this->roles(); //get roles
        $perms = $this->perms(); //get perms

        $current_page = 'Sale Items';
        //get current logged in user info
        $user_info = $this->current_user_info();

        //get the notifications
        $notifications = $this->notifications();

        //get all tags
        $tags = tags::where('username','!=',$this->current_user_info()->username)->get();
        //my tags
        $my_tags = tags::where('username',$this->current_user_info()->username)->get();

        //build the breadcrumbs
        $breadcrumbs = [
            [
            'link' => url('/app/system/sale-items'),
            'name' => 'Sale Items'   
            ],
            [
            'link' => url('/app/system/sale-items/item/tags'),
            'name' => 'Tags'   
            ]
        ];

        return view('pages.sale_items_item_tags',compact('current_page','roles','perms','notifications','user_info','breadcrumbs','tags','my_tags'));
    }
    //create tag
    public function create_tag(Request $request){

        $error = array();
        $validator = Validator::make($request->all(),[
            'tag_name' => 'required|max:500|alpha_dash|unique:tags',
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
            $link_name = preg_replace('/[^0-9a-zA-Z\s]/', '', strtolower($request->tag_name));
            $link_name = str_replace([' ','_'],'-', $link_name);
            $link_name = str_replace('&','and', $link_name);

            $n = new tags();
            $n->link_name = $link_name;
            $n->tag_name = $request->tag_name;
            $n->username = $this->current_user_info()->username;
            $n->save();

            //get the username to those who have an admin username
            $admins = [];
            foreach(roles_id::where('role_id',role::where('role_name','admin')->first()->role_id)->get() as $a){

                array_push($admins, $a->username);

                //save to notification table
                $not = new notifications();
                $not->username= $a->username;
                $not->sender = 'system';
                $not->contents = '<b>'.$this->current_user_info()->first_name.' '.$this->current_user_info()->last_name.'</b> has created new tag';
                $not->save();
            }

            //send notification
            $data = [
                'event' => 'notification',
                'contents' => '<b>'.$this->current_user_info()->first_name.' '.$this->current_user_info()->last_name.'</b> has created new tag',
                'to' => $admins,
                'type' => 'notification',
                'sender' => 'system',
            ];
     
            Redis::publish('notifications',json_encode($data));

            return response()->json([ 'success' => true, 'tag_id' => $n->tag_id, 'tag_name' => $n->tag_name ]);
        }else{
            $str = '<ul class="c-red padding-zero margin-zero menu">';
            foreach($error as $e ){
                $str.= '<li>'.$e.'</li>';
            }
            $str.='</ul>';

            return response()->json([ 'success' => false,  'message' => $str ]);
        }

    }
    //delete tag
    public function delete_tag(Request $request){
        //delete first all the tag associated with the requested tag
        foreach(item_tags::where('tag_id',$request->tag_id)->get() as $t):
            $t->delete();
        endforeach;
        tags::where('tag_id',$request->tag_id)->first()->delete();

        return response()->json([ 'success' => true, 'tag_id' => $request->tag_id ]);
    }
    //attach tag to the item
    public function item_add_tag(Request $request){
        //check tag if exist, if it does then just update
        if(!item_tags::where('item_id',$request->item_id)->where('tag_id',$request->tag_id)->exists()){
            $n = new item_tags();
            $n->tag_id = $request->tag_id;
            $n->item_id = $request->item_id;
            $n->save();
            return response()->json([ 'success' => true ]);
        }
    }

    //remove tag
    public function item_remove_tag(Request $request){

        item_tags::where('item_id',$request->item_id)->where('tag_id',$request->tag_id)->first()->delete();

        return response()->json([ 'success' => true ]);
    }
    //search tag
    public function search_tag(Request $request){
        $tags = tags::where(function($query) use($request){
            foreach(item_tags::where('item_id',$request->item_id)->get() as $i){
                $query->orWhere('tag_id','!=',$i->tag_id);
            }
        })->where('tag_name','LIKE','%'.$request->ss.'%')->get();

        return response()->json([ 'success' => true, 'tags' => $tags, 'ss' => $request->ss ]);

    }
    //item add category
    public function item_add_category(Request $request){
        if($request->type === 'add'){
             //check tag if exist, if it does then just update
            if(!item_categories::where('item_id',$request->item_id)->where('cat_id',$request->cat_id)->exists()){
                $n = new item_categories();
                $n->cat_id = $request->cat_id;
                $n->item_id = $request->item_id;
                $n->save();

                //get the username to those who have an admin username
                $admins = [];
                foreach(roles_id::where('role_id',role::where('role_name','admin')->first()->role_id)->get() as $a){

                    array_push($admins, $a->username);

                    //save to notification table
                    $not = new notifications();
                    $not->username= $a->username;
                    $not->sender = 'system';
                    $not->contents = '<b>'.$this->current_user_info()->first_name.' '.$this->current_user_info()->last_name.'</b> has created new category';
                    $not->save();
                }

                //send notification
                $data = [
                    'event' => 'notification',
                    'contents' => '<b>'.$this->current_user_info()->first_name.' '.$this->current_user_info()->last_name.'</b> has created new category',
                    'to' => $admins,
                    'type' => 'notification',
                    'sender' => 'system',
                ];
         
                Redis::publish('notifications',json_encode($data));
            }
        }else{
            $n = item_categories::where('cat_id',$request->cat_id)->first()->delete();
        }
        return response()->json([ 'success' => true ]);
    }

    //upload item image
    public function item_add_item_image(Request $request){
        
        $error = array();
        $file = $request->file('image');
        //check if there's a file image
        if($file){ //if there's an image then
                $validator = Validator::make($request->all(),[
                    'image.*' => 'image|image_size:115-1000,115-10000'
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
                //check if image already exist
                foreach($request->file('image') as $i){
                    if(Storage::exists($this->current_user_info()->username.'/items/'.$request->item_id.'/'.$i->getClientOriginalName())){
                        $error[] = $i->getClientOriginalName()." already exists.";
                    }
                }
            if(count($error)===0){
                unset($error);
                foreach($request->file('image') as $i){

                    $extension = $i->getClientOriginalExtension();
                    $file_name = $i->getClientOriginalName();

                    //store the image
                    Storage::put($this->current_user_info()->username.'/items/'.$request->item_id.'/'.$file_name, File::get($i));

                    $img = new item_images();
                    $img->image_name = $file_name;
                    $img->image_type = $extension;
                    $img->item_id = $request->item_id;
                    $img->username = $this->current_user_info()->username;
                    $img->save();
                }
                return response()->json([ 'success' => true,  'images' => item_images::where('item_id',$request->item_id)->get(), 'default_item_image' => items::where('item_id',$request->item_id)->first()->default_item_image ]);
            }else{
                $str = '<ul class="c-red padding-zero margin-zero menu">';
                foreach($error as $e ){
                    $str.= '<li>'.$e.'</li>';
                }
                $str.='</ul>';

                return response()->json([ 'success' => false,  'message' => $str ]);
            }
        }else{ //else if there's no image file
            return response()->json([ 'success' => false,  'message' => "Image is required" ]);
        }//end checking if file exist
        

    }//end upload image
    //add primary image
    public function item_add_product_primary_image(Request $request){
        $p = items::where('item_id',$request->item_id)->first();
        $p->default_item_image = $request->image_id;
        $p->update();

        return response()->json([ 'success' => true, 'image_id' => $request->image_id ]);
    }
    //delete image
    public function item_delete_item_image(Request $request){
        $i = item_images::find($request->image_id);
        Storage::delete($this->current_user_info()->username.'/items/'.$i->item_id.'/'.$i->image_name);
        $i->delete();
        return response()->json([ 'success' => true, 'image_id' => $request->image_id ]);
    }
}
